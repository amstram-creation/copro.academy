<header class="page-header">
    <h1>Articles</h1>
    <nav class="page-actions">
        <a href="/admin/article/alter" class="btn">Nouvel article</a>
    </nav>
</header>

<section class="content-filters">
</section>

<?php if (empty($articles)): ?>
    <div class="panel empty-state">
        <p>Aucun article trouvé.</p>
        <a href="/admin/article/alter" class="btn">Créer le premier article</a>
    </div>
<?php else: ?>
    <div class="data-table">
        <table>
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Catégorie</th>
                    <th>Statut</th>
                    <th>Créé le</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($articles as $article): ?>

                    <?php
                    $is_past = strtotime($event['enabled_at']) < time();
                    $is_upcoming = strtotime($event['enabled_at']) > time();
                    ?>
                    <tr <?= $is_past ? ' class="past"' : '' ?>>
                        <td>
                            <strong><?= e($article,'label') ?></strong>
                            <?php if ($article['featured']): ?>
                                <span class="badge featured">À la une</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($article['category_label'] ?? 'Non classé') ?></td>
                        <td>
                            <span class="status <?= $article['enabled_at'] ? 'published' : 'draft' ?>">
                                <?= $article['enabled_at'] ? 'Publié' : 'Brouillon' ?>
                            </span>
                        </td>
                        <td>
                            <time datetime="<?= $article['created_at'] ?>">
                                <?= date('d/m/Y', strtotime($article['created_at'])) ?>
                            </time>
                        </td>
                        <td class="actions">
                            <a href="/admin/article/alter/<?= $article['slug'] ?>" class="btn small">Modifier</a>
                            <?php if ($article['enabled_at']): ?>
                                <a href="/article/detail/<?= $article['slug'] ?>" class="btn small secondary" target="_blank">Voir</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>


<?php endif; ?>