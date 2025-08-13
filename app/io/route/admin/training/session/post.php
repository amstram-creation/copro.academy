<?php

return function (array $training, callable $session) {

    empty($_POST) && throw new BadMethodCallException('This route only handles POST requests', 400);

    // Handle form submission (create/update session)
    $session = row(db(), 'training_program');
    $is_edit = !empty($_POST['id']);
    if ($is_edit) {
        $session(ROW_LOAD, ['id' => (int)$_POST['id']]);
        if (!$session(ROW_GET) || $session(ROW_GET, ['training_id']) != $training['id']) {
            http_out(404, 'Session not found');
        }
    }

    // Validate and clean data
    $clean = [
        'training_id' => $training['id'],
        'day_number' => max(1, min($training['duration_days'], (int)($_POST['day_number'] ?? 1))),
        'time_start' => $_POST['time_start'] ?: null,
        'time_end' => $_POST['time_end'] ?: null,
        'label' => trim($_POST['label'] ?: ''),
        'description' => trim($_POST['description'] ?: '') ?: null,
        'objectives' => trim($_POST['objectives'] ?: '') ?: null,
    ];

    // Generate slug if not provided
    if (empty($_POST['slug']) && $clean['label']) {
        $base_slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $clean['label']));
        $base_slug = trim($base_slug, '-');
        $clean['slug'] = $base_slug . '-j' . $clean['day_number'];
    } else {
        $clean['slug'] = trim($_POST['slug'] ?: '');
    }

    // // Validate time order
    // if (strtotime($clean['time_end']) <= strtotime($clean['time_start'])) {
    //     http_out(400, 'End time must be after start time');
    // }

    // // Check for time conflicts (same day, overlapping times)
    // $conflict_query = "
    //         SELECT id FROM training_program 
    //         WHERE training_id = ? AND day_number = ?
    //         AND ((time_start < ? AND time_end > ?) OR (time_start < ? AND time_end > ?))
    //     ";
    // $conflict_params = [
    //     $training['id'],
    //     $clean['day_number'],
    //     $clean['time_end'],
    //     $clean['time_start'],
    //     $clean['time_end'],
    //     $clean['time_start']
    // ];

    // if ($is_edit) {
    //     $conflict_query .= " AND id != ?";
    //     $conflict_params[] = (int)$_POST['id'];
    // }

    // $conflict = qp(db(), $conflict_query, $conflict_params)->fetch();
    // if ($conflict) {
    //     http_out(400, 'Time conflict with existing session');
    // }

    // Save session
    $session(ROW_SET | ROW_SCHEMA);
    $session(ROW_SET | ROW_SAVE, $clean);

    http_out(302, '', ['Location' => "/admin/training/program/" . $training['slug']]);
};
