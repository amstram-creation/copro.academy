<?php
// io/render/admin/faq/faq.php
?>
<header class="page-header">
    <h1>FAQ - Questions fréquentes</h1>
    <nav class="page-actions">
        <a href="/admin/faq/alter" class="btn">Nouvelle question</a>
        <a href="/admin/site" class="btn secondary">Retour à la configuration</a>

    </nav>
</header>

<section class="content-filters">
    <form method="get" class="search-form">
        <input type="search" name="search" placeholder="Rechercher..."
            value="<?= htmlspecialchars($search) ?>">
        <button type="submit" class="btn secondary">Rechercher</button>
        <?php if ($search): ?>
            <a href="/admin/faq" class="btn secondary">Effacer</a>
        <?php endif; ?>
    </form>
</section>

<?php if (empty($faqs)): ?>
    <div class="panel empty-state">
        <p><?= $search ? 'Aucune FAQ trouvée.' : 'Aucune question fréquente.' ?></p>
        <a href="/admin/faq/alter" class="btn">Créer la première FAQ</a>
    </div>
<?php else: ?>
    <div class="data-table">
        <table>
            <thead>
                <tr>
                    <th>Question</th>
                    <th>Aperçu</th>
                    <th>Slug</th>
                    <th>Créée</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($faqs as $faq): ?>
                    <tr>
                        <td>
                            <strong><?= htmlspecialchars($faq['label']) ?></strong>
                        </td>
                        <td>
                            <small><?= htmlspecialchars($faq['preview']) ?><?= strlen($faq['preview']) >= 100 ? '...' : '' ?></small>
                        </td>
                        <td>
                            <code><?= htmlspecialchars($faq['slug']) ?></code>
                        </td>
                        <td>
                            <time datetime="<?= $faq['created_at'] ?>">
                                <?= date('d/m/Y', strtotime($faq['created_at'])) ?>
                            </time>
                        </td>
                        <td class="actions">
                            <a href="/admin/faq/alter/<?= $faq['id'] ?>" class="btn small">Modifier</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php if ($pagination['total_pages'] > 1): ?>
        <div class="pagination">
            <?php for ($p = 1; $p <= $pagination['total_pages']; $p++): ?>
                <a href="?page=<?= $p ?><?= $search ? '&search=' . urlencode($search) : '' ?>"
                    class="<?= $p === $pagination['page'] ? 'active' : '' ?>">
                    <?= $p ?>
                </a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
<?php endif; ?>