<?php
require_once 'add/badhat/db.php';
require_once 'add/arrow/arrow.php';

return function ($args = null) {
    [$training_slug, $session_id] = $args;

    $training = row(db(), 'training');
    $training = $training(ROW_LOAD, ['slug' => $training_slug, 'revoked_at' => null]);

    $source = row(db(), 'training_program');
    $source(ROW_LOAD, ['id' => $session_id]);

    $target_day = (int)($_GET['to_day'] ?? 0);

    if ($target_day >= 1 && $target_day <= $training['duration_days']) {
        $clone = row(db(), 'training_program');
        $clone(ROW_SET | ROW_SCHEMA, $source(ROW_GET | ROW_SCHEMA));
        $clone(ROW_SET, $source(ROW_GET));
        $clone(ROW_SET, [
            'slug' => $source(ROW_GET, ['slug']) . '-j' . $target_day,
            'day_number' => $target_day,
            'training_id' => $training['id']
        ]);
        $clone(ROW_SAVE);
        !$clone(ROW_ERROR) && http_out(302, '', ['Location' => "/admin/training/program/" . $training['slug']]) && exit;
    }

    http_out(302, '', ['Location' => "/admin/training/program/" . $training['slug']]);
    exit;
};
