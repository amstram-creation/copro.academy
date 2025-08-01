<?php
require_once 'add/badhat/db.php';

return function ($args = null) {
    vd(1, __FILE__, $args);
    die;

    // Handle session deletion
    if (!empty($_GET['delete'])) {
        $delete_id = (int)$_GET['delete'];
        qp(db(), "DELETE FROM training_program WHERE id = ? AND training_id = ?", [$delete_id, $training_id]);
        http_out(302, '', ['Location' => "/admin/training/program/$training_id"]);
    }
};
