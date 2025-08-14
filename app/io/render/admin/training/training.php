<header class="page-header">
    <h1>Formations</h1>
    <nav class="page-actions">
        <a href="/admin/training/alter" class="btn">Nouvelle formation</a>
    </nav>
</header>

<section class="content-filters">
    <nav class="filter-tabs">
        <a href="/admin/training"
            class="<?= empty($current_filter) ? 'active' : '' ?>">Toutes</a>
        <a href="/admin/training?filter=upcoming"
            class="<?= $current_filter === 'upcoming' ? 'active' : '' ?>">À venir</a>
        <a href="/admin/training?filter=past"
            class="<?= $current_filter === 'past' ? 'active' : '' ?>">Terminées</a>
        <a href="/admin/training?filter=published"
            class="<?= $current_filter === 'published' ? 'active' : '' ?>">Publiées</a>
        <a href="/admin/training?filter=draft"
            class="<?= $current_filter === 'draft' ? 'active' : '' ?>">Brouillons</a>
    </nav>


</section>

<?php if (empty($trainings)): ?>
    <div class="panel empty-state">
        <p>Aucune formation trouvée.</p>
        <a href="/admin/training/alter" class="btn">Créer la première formation</a>
    </div>
<?php else: ?>
    <div class="data-table">
        <table>
            <thead>
                <tr>
                    <th>Formation</th>
                    <th>Niveau</th>
                    <th>Durée</th>
                    <th>Date de début</th>
                    <th>Formateur</th>
                    <th>Prix</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($trainings as $training): ?>
                    <?php
                    $is_past = strtotime($training['start_date']) < time();
                    $is_upcoming = strtotime($training['start_date']) > time();
                    $end_date = date('Y-m-d', strtotime($training['start_date'] . ' + ' . ($training['duration_days'] - 1) . ' days'));
                    ?>
                    <tr <?= $is_past ? ' class="past"' : '' ?>>
                        <td>
                            <strong><?= htmlspecialchars($training['label']) ?></strong>
                            <?php if ($training['certification']): ?>
                                <span class="badge certification">Certifiante</span>
                            <?php endif; ?>
                            <?php if ($training['description']): ?>
                                <br><small class="description"><?= htmlspecialchars(substr($training['description'], 0, 100)) ?><?= strlen($training['description']) > 100 ? '...' : '' ?></small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="level-badge level-<?= strtolower($training['level_label'] ?? 'unknown') ?>">
                                <?= htmlspecialchars($training['level_label'] ?? 'Non défini') ?>
                            </span>
                        </td>
                        <td>
                            <strong><?= $training['duration_days'] ?> jour<?= $training['duration_days'] > 1 ? 's' : '' ?></strong>
                            <br><small><?= $training['duration_hours'] ?>h au total</small>
                        </td>
                        <td>
                            <time datetime="<?= $training['start_date'] ?>">
                                <?= date('d/m/Y', strtotime($training['start_date'])) ?>
                            </time>
                            <?php if ($training['duration_days'] > 1): ?>
                                <br><small>au <?= date('d/m/Y', strtotime($end_date)) ?></small>
                            <?php endif; ?>
                            <?php if ($is_past): ?>
                                <br><span class="badge past">Terminée</span>
                            <?php elseif ($is_upcoming): ?>
                                <br><span class="badge upcoming">À venir</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($training['trainer_label']): ?>
                                <?= htmlspecialchars($training['trainer_label']) ?>
                            <?php else: ?>
                                <span class="text-muted">Non assigné</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <strong><?= number_format($training['price_ht'], 0) ?> €</strong>
                            <br><small>HT</small>
                        </td>
                        <td>
                            <span class="status <?= $training['enabled_at'] ? 'published' : 'draft' ?>">
                                <?= $training['enabled_at'] ? 'Publiée' : 'Brouillon' ?>
                            </span>
                        </td>
                        <td class="actions">
                            <a href="/admin/training/alter/<?= $training['slug'] ?>" class="btn small">Modifier</a>
                            <?php if ($training['enabled_at']): ?>
                                <a href="/training/<?= $training['slug'] ?>" class="btn small secondary" target="_blank">Voir</a>
                            <?php endif; ?>
                            <?php if (($training['bookings_count'] ?? 0) > 0): ?>
                                <a href="/admin/booking?training_id=<?= $training['id'] ?>" class="btn small tertiary">Inscrits</a>
                            <?php endif; ?>
                            <a href="/admin/training/program/<?= $training['id'] ?>" class="btn small quaternary">Programme</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

   
<?php endif; ?>