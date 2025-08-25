<?php

require_once 'app/mapper/taxonomy.php';
require_once 'add/arrow/arrow.php';

return function ($args) {
    $slug = $args[0] ?? null;
    $event = row(db(), 'event', 'id');

    if ($slug) {
        $res = $event(ROW_LOAD|ROW_GET, ['slug' => $slug]);
        $res || http_out(301, 'Event not found', ['Location' => "/admin/event"]);
    }

    // Handle POST submission
    if (!empty($_POST)) {

        $clean = $_POST;
        $clean['category_id']       = tag_id_by_slug($_POST['category_slug'], 'evenement-categorie') ?: null;
        // $clean['duration_minutes']  = (int)($_POST['duration_minutes'] ?? 0) ?: null;
        $clean['price_ht']          = ($_POST['price_ht'] ?? '') !== '' ? (float)$_POST['price_ht'] : null;
        $clean['places_max']        = ($_POST['places_max'] ?? '') !== '' ? (int)$_POST['places_max'] : null;
        $clean['online']            = (int)!empty($_POST['online']);
        $clean['speaker']           = trim($_POST['speaker'] ?? '') ?: null;
        $clean['location']          = trim($_POST['location'] ?? '') ?: null;

        empty($_POST['published']) 
            ? ($clean['enabled_at'] = null)
            : ($clean['enabled_at'] = date('Y-m-d H:i:s'));

        $event(ROW_SET | ROW_SCHEMA);
        $event(ROW_SET, $clean);

        $event(ROW_SAVE);
        if ($event(ROW_GET | ROW_ERROR)) {
            vd($event(ROW_GET | ROW_ERROR));
            vd($event(ROW_GET | ROW_EDIT));
            vd($event(ROW_GET | ROW_MORE));
            die;
        }

        http_out(302, '', ['Location' => "/admin/event/alter/" . $event(ROW_GET, ['slug'])]);
    }

    $event_data = $event(ROW_GET);
    return [
        'title' => $slug ? "Modifier l'événement - {$event_data['label']}" : 'Nouvel événement',
        'event' => $event_data,
        'categories' => tag_by_parent('evenement-categorie'),
    ];
};

