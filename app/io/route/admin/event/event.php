<?php
require_once 'app/mapper/mapper.php';

return function ($args) {
    $conditions = [];
    if ($search = $_GET['q'] ?? '') {
        $conditions = ['label LIKE' => "%$search%"];
    }

    if ($status = $_GET['status'] ?? '') {
        if ($status === 'upcoming') {
            $conditions['event_date >='] = date('Y-m-d H:i:s');
        } elseif ($status === 'past') {
            $conditions['event_date <'] = date('Y-m-d H:i:s');
        } elseif ($status === 'published') {
            $conditions['enabled_at IS NOT'] = null;
        } elseif ($status === 'draft') {
            $conditions['enabled_at'] = null;
        }
    }

    return [
        'title' => 'Événements',
        'events' => db()->query('SELECT * FROM event_plus ORDER BY event_date DESC')->fetchAll(),

        // 'search' => $search,
        'current_status' => $status
    ];
};
