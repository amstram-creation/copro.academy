<?php

return function ($args = null) {
    $db = db();

    $data = ['title' => 'Accueil', 'description' => 'Contactez-nous pour toute question sur nos formations, événements ou services. Notre équipe est là pour vous aider.'];

    // 1) On centralise toutes les requêtes dans un tableau [clé => SQL]
    $queries = [
        'faq'              => "SELECT label, content FROM faq ORDER BY sort_order",
        'service'          => "SELECT * FROM service ORDER BY sort_order",
        // 'recent_articles'  => "SELECT * FROM article_plus WHERE enabled_at<=NOW() ORDER BY featured DESC, enabled_at DESC LIMIT 3",
        'articles_events' => "SELECT * FROM articles_events ORDER BY featured DESC, unified_date DESC LIMIT 3",
        // 'featured_articles' => "SELECT * FROM article WHERE featured=1 AND enabled_at<=NOW() ORDER BY enabled_at DESC LIMIT 3",
        // 'upcoming_events'   => "SELECT * FROM event WHERE event_date>=CURDATE() ORDER BY event_date LIMIT 3",
        'benefits'          => "SELECT * FROM benefit ORDER BY sort_order",
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
