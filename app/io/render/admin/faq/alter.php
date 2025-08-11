<?php
// io/render/admin/faq/alter.php
$is_edit = !empty($faq['id']);
?>

<header class="page-header">
    <h1><?= $is_edit ? 'Modifier la FAQ' : 'Nouvelle FAQ' ?></h1>
    <?php if ($is_edit): ?>
        <nav class="page-actions">
            <a href="/admin/faq" class="btn secondary">Retour à la liste</a>
        </nav>
    <?php endif; ?>
</header>

<?php if (isset($error)): ?>
    <div class="panel error">
        <p><?= htmlspecialchars($error) ?></p>
    </div>
<?php endif; ?>

<form method="post" class="alter-form">
    <?= csrf_field(3600) ?>
    <input type="hidden" name="id" value="<?= $faq['id'] ?? null ?>">

    <section class="form-main">
        <fieldset class="form-group">
            <label for="label">Question *</label>
            <input type="text" name="label" id="label"
                value="<?= htmlspecialchars($faq['label'] ?? '') ?>"
                required maxlength="200">
        </fieldset>

        <fieldset class="form-group">
            <label for="slug">Slug *</label>
            <input type="text" name="slug" id="slug"
                value="<?= htmlspecialchars($faq['slug'] ?? '') ?>"
                required maxlength="200">
            <small>Le slug sera généré automatiquement</small>
        </fieldset>

        <fieldset class="form-group">
            <label for="content">Réponse *</label>
            <textarea name="content" id="content" rows="10" required
                class="content-editor"><?= htmlspecialchars($faq['content'] ?? '') ?></textarea>
        </fieldset>
    </section>

    <?php if ($is_edit): ?>
        <aside>
            <section class="panel stats-box">
                <header>
                    <h2>Informations</h2>
                </header>
                <dl class="stats-list">
                    <dt>Slug</dt>
                    <dd><code><?= htmlspecialchars($faq['slug'] ?? 'auto-généré') ?></code></dd>

                    <?php if ($faq['created_at']): ?>
                        <dt>Créée</dt>
                        <dd>
                            <time datetime="<?= $faq['created_at'] ?>">
                                <?= date('d/m/Y à H:i', strtotime($faq['created_at'])) ?>
                            </time>
                        </dd>
                    <?php endif; ?>

                    <?php if ($faq['updated_at']): ?>
                        <dt>Modifiée</dt>
                        <dd>
                            <time datetime="<?= $faq['updated_at'] ?>">
                                <?= date('d/m/Y à H:i', strtotime($faq['updated_at'])) ?>
                            </time>
                        </dd>
                    <?php endif; ?>
                </dl>
            </section>
        </aside>
    <?php endif; ?>

    <footer class="form-actions">
        <button type="submit" class="btn">
            <?= $is_edit ? 'Mettre à jour' : 'Créer la FAQ' ?>
        </button>
        <a href="/admin/faq" class="btn secondary">Annuler</a>

        <?php if ($is_edit): ?>
            <button type="submit" name="action" value="delete" class="btn danger"
                data-confirm="Supprimer cette FAQ ?">
                Supprimer
            </button>
        <?php endif; ?>
    </footer>
</form>