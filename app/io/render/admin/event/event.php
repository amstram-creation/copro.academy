<header class="page-header">
    <h1>Événements</h1>
    <nav class="page-actions">
        <a href="/admin/event/alter" class="btn">Nouvel événement</a>
    </nav>
</header>

<section class="content-filters">
    <nav class="filter-tabs">
        <a href="/admin/event"
            class="<?= empty($current_status) ? 'active' : '' ?>">Tous</a>
        <a href="/admin/event?status=upcoming"
            class="<?= $current_status === 'upcoming' ? 'active' : '' ?>">À venir</a>
        <a href="/admin/event?status=past"
            class="<?= $current_status === 'past' ? 'active' : '' ?>">Passés</a>
        <a href="/admin/event?status=published"
            class="<?= $current_status === 'published' ? 'active' : '' ?>">Publiés</a>
        <a href="/admin/event?status=draft"
            class="<?= $current_status === 'draft' ? 'active' : '' ?>">Brouillons</a>
    </nav>

</section>

<?php if (empty($events)): ?>
    <div class="panel empty-state">
        <p>Aucun événement trouvé.</p>
        <a href="/admin/event/alter" class="btn">Créer le premier événement</a>
    </div>
<?php else: ?>
    <div class="data-table">
        <table>
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Date & Heure</th>
                    <th>Catégorie</th>
                    <th>Inscriptions</th>
                    <th>Prix</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($events as $event): ?>
                    <?php
                    $is_past = strtotime($event['event_date']) < time();
                    $is_upcoming = strtotime($event['event_date']) > time();
                    ?>
                    <tr <?= $is_past ? ' class="past"' : '' ?>>
                        <td>
                            <strong><?= htmlspecialchars($event['label']) ?></strong>
                            <?php if ($event['online']): ?>
                                <span class="badge online">En ligne</span>
                            <?php endif; ?>
                            <?php if ($event['speaker']): ?>
                                <br><small>Intervenant: <?= htmlspecialchars($event['speaker']) ?></small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <time datetime="<?= $event['event_date'] ?>">
                                <?= date('d/m/Y', strtotime($event['event_date'])) ?><br>
                                <small><?= date('H:i', strtotime($event['event_date'])) ?>
                                   </small>
                            </time>
                            <?php if ($is_past): ?>
                                <br><span class="badge past">Terminé</span>
                            <?php elseif ($is_upcoming): ?>
                                <br><span class="badge upcoming">À venir</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($event['category_label'] ?? 'Non classé') ?></td>
                        <td>
                            <strong><?= $event['bookings_count'] ?></strong>
                            <?php if ($event['places_max']): ?>
                                / <?= $event['places_max'] ?>
                                <?php
                                $fill_rate = $event['places_max'] > 0 ? ($event['bookings_count'] / $event['places_max']) * 100 : 0;
                                if ($fill_rate >= 90): ?>
                                    <span class="badge full">Complet</span>
                                <?php elseif ($fill_rate >= 75): ?>
                                    <span class="badge filling">Bientôt complet</span>
                                <?php endif; ?>
                            <?php else: ?>
                                <br><small>Illimité</small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($event['price_ht']): ?>
                                <?= number_format($event['price_ht'], 2) ?> €
                            <?php else: ?>
                                <span class="badge free">Gratuit</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="status <?= $event['enabled_at'] ? 'published' : 'draft' ?>">
                                <?= $event['enabled_at'] ? 'Publié' : 'Brouillon' ?>
                            </span>
                        </td>
                        <td class="actions">
                            <a href="/admin/event/alter/<?= $event['slug'] ?>" class="btn small">Modifier</a>
                            <?php if ($event['enabled_at']): ?>
                                <a href="/event/<?= $event['slug'] ?>" class="btn small secondary" target="_blank">Voir</a>
                            <?php endif; ?>
                            <?php if ($event['bookings_count'] > 0): ?>
                                <a href="/admin/booking?event_id=<?= $event['id'] ?>" class="btn small tertiary">Inscrits</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

<?php endif; ?>