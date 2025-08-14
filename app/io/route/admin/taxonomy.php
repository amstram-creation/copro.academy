<?php
// app/io/route/admin/taxonomy.php
require_once 'add/arrow/arrow.php';

return function ($args) {

    if ($_POST) {
        $taxonomy = row(db(), 'taxonomy', 'id');

        // Create new taxonomy (sidebar form)
        if (!empty($_POST['label']) && !empty($_POST['slug'])) {
            $taxonomy(ROW_CREATE, [
                'label' => $_POST['label'],
                'slug' => $_POST['slug'],
                'sort_order' => (int)($_POST['sort_order'] ?? 0),
                'parent_id' => $_POST['parent_id'] ?: null,
                'enabled_at' => date('Y-m-d H:i:s')
            ]);
        }
        // Bulk update existing taxonomies (main form)
        else if (isset($_POST['items'])) {
            foreach ($_POST['items'] as $id => $data) {
                $taxonomy(ROW_UPDATE, [
                    'id' => (int)$id,
                    'label' => $data['label'],
                    'slug' => $data['slug'],
                    'sort_order' => (int)$data['sort_order'],
                    'parent_id' => (int)$data['parent_id'] ?: null
                ]);
                $taxonomy(ROW_RESET);
            }
        }
        // Delete taxonomy (if DELETE parameter sent)
        // else if (isset($_POST['delete_id'])) {
        //     $taxonomy(ROW_LOAD, ['id' => (int)$_POST['delete_id']]);
        //     $taxonomy(ROW_SET, ['revoked_at' => date('Y-m-d H:i:s')]);
        //     $taxonomy(ROW_SAVE);

        // }
        http_out(200, '', ['Location' => '/admin/taxonomy']);
    }

    // Load data (excluding soft-deleted)
    $taxonomies = db()->query("SELECT * FROM taxonomy_with_parent ORDER BY parent_slug, sort_order")->fetchAll();
    $root_categories = db()->query("SELECT * FROM taxonomy WHERE parent_id IS NULL AND revoked_at IS NULL ORDER BY sort_order")->fetchAll();

    return compact('taxonomies', 'root_categories');
};
