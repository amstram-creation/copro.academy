<?php
require_once 'add/arrow/arrow.php';
// io/route/admin/service/alter.php
return function ($args) {
    $id = $args[0] ?? null;
    $service = row(db(), 'service', 'id');
    if ($id) {
        $service(ROW_LOAD, ['id' => $id]);
        if (!$service) {
            header('HTTP/1.0 404 Not Found');
            exit('Service non trouvé');
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $cleam = [];
        $clean['label'] = trim($_POST['label'] ?? '');
        $clean['content'] = trim($_POST['content'] ?? '');
        $clean['image_src'] = trim($_POST['image_src'] ?? '');
        $clean['alt_text'] = trim($_POST['alt_text'] ?? '');
        $clean['link'] = trim($_POST['link'] ?? '');
        $clean['link_text'] = trim($_POST['link_text'] ?? '');
        $clean['sort_order'] = (int)($_POST['sort_order'] ?? 0);

        if ($_POST['action'] ?? '' === 'delete' && $id) {
            qp(db(), "DELETE FROM service WHERE id = ?", [$id]);
            header('Location: /admin/service');
            exit;
        }

        $service(ROW_SET|ROW_SAVE, $clean);

        header('Location: /admin/service');
        exit;
    }

    return [
        'title' => $id ? 'Modifier le service' : 'Nouveau service',
        'service' => $service(ROW_GET)
    ];
};
