<?php

return function ($quest) {
    require_once 'app/mapper/resource.php';

    $page = max(1, (int)($_GET['page'] ?? 1));
    $status = $_GET['status'] ?? 'all';
    $limit = 20;
    $offset = ($page - 1) * $limit;

    $where_conditions = [];
    $params = [];

    if ($status !== 'all') {
        $where_conditions[] = "r.status = ?";
        $params[] = $status;
    }

    $where_clause = $where_conditions ? 'WHERE ' . implode(' AND ', $where_conditions) : '';

    $resources = dbq(
        "SELECT r.*, u.full_name as uploader_name, u.username
         FROM resources r
         JOIN users u ON r.user_id = u.id
         {$where_clause}
         ORDER BY r.created_at DESC
         LIMIT {$limit} OFFSET {$offset}",
        $params
    )->fetchAll();

    $total = dbq(
        "SELECT COUNT(*) FROM resources r {$where_clause}",
        $params
    )->fetchColumn();

    $total_pages = ceil($total / $limit);

    return [
        'payload' => [
            'title' => 'Manage Resources - Admin',
            'resources' => $resources,
            'current_status' => $status,
            'pagination' => [
                'current' => $page,
                'total' => $total_pages,
                'has_prev' => $page > 1,
                'has_next' => $page < $total_pages
            ]
        ]
    ];
};