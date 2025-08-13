<?php
// io/route/admin/service/service.php
return function ($args) {
    $services = db()->query("SELECT id, label, LEFT(content, 100) as preview, link, link_text, sort_order, created_at FROM service  ORDER BY sort_order ASC, created_at DESC")->fetchAll();
    return [
        'title' => 'Services - Page d\'accueil',
        'services' => $services
    ];
};
