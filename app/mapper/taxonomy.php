<?php
require_once 'add/badhat/db.php';

function tag_by_parent(string $slug): array
{
    $sql = "SELECT slug, label FROM taxonomy_with_parent WHERE parent_slug = ? ORDER BY sort_order, label";
    return qp(db(), $sql, [$slug])->fetchAll(PDO::FETCH_KEY_PAIR);
}

function tag_id_by_slug(string $slug, string $parent_slug)
{
    $sql = "SELECT id FROM taxonomy_with_parent WHERE slug = ? AND parent_slug = ?";
    return qp(db(), $sql, [$slug, $parent_slug])->fetchColumn();
}