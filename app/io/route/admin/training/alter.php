<?php
require_once 'add/arrow/arrow.php';
require_once 'app/mapper/taxonomy.php';

return function ($slug = null) {
    $slug = $slug[0] ?: null;
    $training = row(db(), 'training', 'id');

    if ($slug) {
        $training(ROW_LOAD, ['slug' => $slug]);
        if (!$training(ROW_GET)) {
            http_out(404, 'Training not found');
        }
    }

    if (!empty($_POST)) {
        $clean = $_POST;

        // Handle taxonomy relationships
        $clean['level_id'] = tag_id_by_slug($_POST['level_slug'], 'formation-niveau') ?: null;

        // Handle trainer relationship
        if (!empty($_POST['trainer_id'])) {
            $trainer_exists = qp(db(), "SELECT id FROM trainer WHERE id = ? AND revoked_at IS NULL", [$_POST['trainer_id']])->fetch();
            $clean['trainer_id'] = $trainer_exists ? (int)$_POST['trainer_id'] : null;
        } else {
            $clean['trainer_id'] = null;
        }

        // Convert numeric fields
        $clean['duration_days'] = max(1, (int)($_POST['duration_days'] ?? 1));
        $clean['duration_hours'] = max(1, (int)($_POST['duration_hours'] ?? 7));
        $clean['price_ht'] = max(0, (float)($_POST['price_ht'] ?? 0));
        $clean['places_max'] = (int)($_POST['places_max'] ?? 0) ?: null;
        $clean['certification'] = (int)!empty($_POST['certification']);

        // Handle publication
        if (!empty($_POST['published']) && empty($training(ROW_GET, ['enabled_at']))) {
            $clean['enabled_at'] = date('Y-m-d H:i:s');
        } elseif (empty($_POST['published'])) {
            $clean['enabled_at'] = null;
        }


        $training(ROW_SET | ROW_SCHEMA);
        $training(ROW_SET | ROW_SAVE, $clean);

        if ($training(ROW_GET | ROW_ERROR)) {
            vd($training(ROW_GET | ROW_EDIT));
            vd($training(ROW_GET | ROW_ERROR));
            die;
        }

        http_out(302, '', ['Location' => "/admin/training/alter/" . $training(ROW_GET, ['slug'])]);
    }

    // Get trainers for dropdown
    $trainers = db()->query("SELECT id, label, email FROM trainer WHERE revoked_at IS NULL ORDER BY label")->fetchAll();

    return [
        'title' => $slug ? "Modifier la formation" : 'Nouvelle formation',
        'training' => ($training(ROW_GET)),
        'levels' => tag_by_parent('formation-niveau'),
        'trainers' => $trainers,
    ];
};
