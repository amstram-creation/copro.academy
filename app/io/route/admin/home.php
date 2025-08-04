<?php
//io/route/admin/home.php
return function ($args) {
    if(isset($_GET['delete_asset'])) {
        $file = rtrim($_SERVER['DOCUMENT_ROOT'], '/') . urldecode($_GET['delete_asset']);
        $file = realpath($file);
        if ($file === false || strpos($file, $_SERVER['DOCUMENT_ROOT']) !== 0) {
            header('Location: /admin/home');
            exit;
        }
        unlink($file);
        header('Location: /admin/home');
        exit;
    }
    
    $slides = glob($_SERVER['DOCUMENT_ROOT'] . '/asset/image/home/hero_slide/*.webp');

    $slides = array_map(function ($slide) {
        return str_replace($_SERVER['DOCUMENT_ROOT'], '', $slide);
    }, $slides);

    $benefits = db()->query("
        SELECT id, icon, title, LEFT(description, 100) as preview, sort_order, is_active, created_at
        FROM benefit 
        ORDER BY sort_order ASC, created_at DESC
    ")->fetchAll();

    $services = db()->query("
        SELECT id, label, content as preview, link, link_text, sort_order, created_at
        FROM service 
        ORDER BY sort_order ASC, created_at DESC
    ")->fetchAll();


    return [
        'title' => 'Page d\'accueil',
        'benefits' => $benefits,
        'slides' => $slides,
        'services' => $services
    ];
};
