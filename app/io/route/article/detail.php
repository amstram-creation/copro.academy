<?php

return function ($args = null) {
    $db = db();
    $data = [];
    $data['article'] = qp($db, "SELECT * FROM article_plus WHERE slug = :slug", ['slug' => array_pop($args)])->fetch();
    if (!$data) {
        return ['error' => 'Formation non trouvée.'];
    }

    $data['title'] = $data['training']['label'];
    $data['description'] = 'Formation certifiée en gestion de copropriétés. Conformité à la législation belge.';

    $data['related_articles'] = qp(
        $db,
        "SELECT * FROM article_plus WHERE enabled_at IS NOT NULL AND category_id = :category_id AND slug != :slug ORDER BY enabled_at DESC LIMIT 3",
        ['category_id' => $data['article']['category_id'], 'slug' => $data['article']['slug']]
    )->fetchAll();
    return $data;
};
