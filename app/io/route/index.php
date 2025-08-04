<?php

return function ($args = null) {
    $db = db();

    $data = ['title' => 'Accueil', 'description' => 'Contactez-nous pour toute question sur nos formations, événements ou services. Notre équipe est là pour vous aider.'];

    // 1) On centralise toutes les requêtes dans un tableau [clé => SQL]
    $queries = [
        'faq'              => "SELECT label, content FROM faq ORDER BY sort_order",
        'service'          => "SELECT * FROM service WHERE revoked_at IS NULL ORDER BY sort_order",
        'articles_events'  => "SELECT * FROM articles_events WHERE enabled_at IS NOT NULL AND featured = 1 ORDER BY featured DESC, unified_date DESC LIMIT 3",
        'benefits'         => "SELECT * FROM benefit WHERE is_active = 1 ORDER BY sort_order",
    ];

    // 2) Boucle sur le tableau pour exécuter chaque requête
    foreach ($queries as $key => $sql) {
        ($_ = $db->query($sql)) && ($_ = $_->fetchAll()) && $data[$key] = $_;
    }
    $hero_slides = glob($_SERVER['DOCUMENT_ROOT'] . '/asset/image/home/hero_slide/*.webp');
    $data['hero_slides'] = array_map(function ($slide) {
        return str_replace($_SERVER['DOCUMENT_ROOT'], '', $slide);
    }, $hero_slides);

    return $data;
};
