<?php
require_once 'add/arrow/arrow.php';

return function ($slug = null) {
    $slug = $slug[0] ?: null;
    $trainer = row(db(), 'trainer', 'id');

    if ($slug) {
        $trainer(ROW_LOAD, ['slug' => $slug]);
        if (!$trainer(ROW_GET)) {
            http_out(404, 'Trainer not found');
        }
    }

    if (!empty($_POST)) {
        if ($_POST['action'] === 'delete' && $slug) {
            // Soft delete
            $trainer(ROW_SET, ['revoked_at' => date('Y-m-d H:i:s')]);
            $trainer(ROW_SAVE);
            http_out(302, '', ['Location' => '/admin/trainer']);
        }

        $clean = $_POST;

        // Generate slug from label if not provided
        if (empty($clean['slug']) && !empty($clean['label'])) {
            $clean['slug'] = slugify($clean['label']);
        }

        // Handle date
        if (!empty($clean['hire_date'])) {
            $clean['hire_date'] = date('Y-m-d', strtotime($clean['hire_date']));
        } else {
            $clean['hire_date'] = null;
        }

        // Handle published status
        if (!empty($_POST['published']) && empty($trainer(ROW_GET, ['enabled_at']))) {
            $clean['enabled_at'] = date('Y-m-d H:i:s');
        } elseif (empty($_POST['published']) && !empty($trainer(ROW_GET, ['enabled_at']))) {
            $clean['enabled_at'] = null;
        }

        $trainer(ROW_SET | ROW_SCHEMA);
        $trainer(ROW_SET, $clean);
        $trainer(ROW_SAVE);
        if ($trainer(ROW_GET | ROW_ERROR)) {
            vd($trainer(ROW_GET | ROW_EDIT));
            vd($trainer(ROW_GET | ROW_ERROR));
            die;
        }
    
        http_out(302, '', ['Location' => "/admin/trainer/alter/" . $trainer(ROW_GET, ['slug'])]);
    }

    return [
        'title' => $slug ? "Modifier le formateur" : 'Nouveau formateur',
        'trainer' => $trainer(ROW_GET),
    ];
};

function slugify(string $text): string
{
    $text = strip_tags(trim($text));
    $text = preg_replace('/\s+/u', ' ', $text);

    if (class_exists(\Transliterator::class)) {
        $transliterator = \Transliterator::create('NFD; [:Nonspacing Mark:] Remove; NFC; Latin-ASCII');
        $text = $transliterator->transliterate($text) ?: $text;
    } else {
        $text = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text) ?: $text;
    }

    $text = mb_strtolower($text, 'UTF-8');
    $text = preg_replace('/[^\p{L}\p{Nd}]+/u', '-', $text);
    $text = preg_replace('/-+/u', '-', $text);
    $slug = trim($text, '-');

    if ($slug === '') {
        $slug = uniqid('trainer-', true);
    }

    return $slug;
}
