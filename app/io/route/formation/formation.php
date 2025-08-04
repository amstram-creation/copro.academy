<?php
require_once 'app/mapper/taxonomy.php';

return function ($args = null) {
    $db = db();

    $data = ['title' => 'Formations', 'description' => 'Découvrez nos formations certifiées en gestion de copropriétés. Programmes adaptés aux professionnels de l\'immobilier et conformes à la législation belge.'];

    $data['formation'] = $db->query("SELECT * FROM training_plus WHERE enabled_at IS NOT NULL AND start_date >= CURDATE() ORDER BY start_date DESC LIMIT 10")->fetchAll();
    $data['formation_niveau'] = (tag_by_parent('formation-niveau'));
    $data['benefits'] = $db->query("SELECT * FROM benefit WHERE is_active = 1 ORDER BY sort_order")->fetchAll();

    return $data;
};
