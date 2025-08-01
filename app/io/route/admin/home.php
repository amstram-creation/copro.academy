<?php
//io/route/admin/home.php
return function ($args) {
    $slides = glob($_SERVER['DOCUMENT_ROOT'] . '/asset/image/hero_slide/*.webp');
    $slides = array_map(function ($slide) {
        return str_replace($_SERVER['DOCUMENT_ROOT'], '', $slide);
    }, $slides);

    $benefits = db()->query("
        SELECT id, icon, title, LEFT(description, 100) as preview, 
               sort_order, is_active, created_at
        FROM benefit 
        WHERE is_active = 1
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
