<?php
// io/route/admin/article/alter.php

require_once 'app/mapper/taxonomy.php';
require_once 'add/bad/arrow.php';
require_once 'app/upload.php';

return function ($slug = null) {
    $slug = $slug[0] ?: null;
    $article = row(db(), 'article');

    if ($slug) {
        $article(ROW_LOAD, ['slug' => $slug]);
        if (!$article) {
            http_out(301, 'Article not found', ['Location' => "/admin/article"]);
        }
    }

    // Handle POST submission
    if (!empty($_POST)) {

        $clean = $_POST;
        $clean['category_id']   = tag_id_by_slug($_POST['category_slug'], 'article-categorie') ?: null;
        $clean['reading_time']  = (int)($_POST['reading_time'] ?? 0) ?: null;
        $clean['featured']      = (int)!empty($_POST['featured']);

        if (!empty($_POST['published']) && empty($article(ROW_GET, ['enabled_at'])))
            $clean['enabled_at'] = date('Y-m-d H:i:s');

        if (!empty($_FILES))
            foreach ($_FILES as $name => $file)
                $clean[$name] = upload($file, $_SERVER['DOCUMENT_ROOT'] . '/asset/image/article/' . $name);
        $article(ROW_SCHEMA);
        $article(ROW_SET, $clean);
        $article(ROW_SAVE);
        $article(ROW_LOAD);
        http_out(302, '', ['Location' => "/admin/article/alter/" . $article(ROW_GET, ['slug'])]);
    }

    $article = $article(ROW_GET);
    return [
        'title' => $slug ? "Modifier l'article - {$article['label']}" : 'Nouvel article',
        'article' => $article,
        'categories' => (tag_by_parent('article-categorie')),
    ];
};