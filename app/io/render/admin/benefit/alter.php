<?php
// io/render/admin/benefit/alter.php
$is_edit = !empty($benefit['id']);
?>

<header class="page-header">
    <h1><?= $is_edit ? 'Modifier le benefit' : 'Nouveau benefit' ?></h1>
    <?php if ($is_edit): ?>
        <nav class="page-actions">

            <a href="/admin/benefit" class="btn secondary">Retour à la liste</a>
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
    <input type="hidden" name="id" value="<?= $benefit['id'] ?? null ?>">

    <section class="form-main">

        <fieldset class="form-group">
            <label for="title">Titre du benefit *</label>
            <input type="text" name="title" id="title"
                value="<?= htmlspecialchars($benefit['title'] ?? '') ?>"
                required maxlength="255">
        </fieldset>

        <fieldset class="form-group">
            <label for="description">Description *</label>
            <textarea name="description" id="description" rows="6" required
                class="content-editor"><?= htmlspecialchars($benefit['description'] ?? '') ?></textarea>
        </fieldset>

        <fieldset class="form-group">
            <label for="icon">Icône *</label>
            <input type="text" name="icon" id="icon"
                value="<?= htmlspecialchars($benefit['icon'] ?? '') ?>"
                required maxlength="50"
                placeholder="🚀">
            <small>Emoji ou classe d'icône</small>
            <button class="emoji-trigger">😀</button>
        </fieldset>
        <div class="form-row">


            </fieldset>
        </div>
    </section>

    <?php if ($is_edit): ?>
        <aside>
            <section class="panel stats-box">
                <header>
                    <h2>Informations</h2>
                </header>
                <dl class="stats-list">
                    <dt>Statut</dt>
                    <dd>
                        <label for="is_active">
                            <input type="checkbox" name="is_active" id="is_active" value="1"
                                <?= ($benefit['is_active'] ?? 1) ? 'checked' : '' ?>>
                            Actif
                        </label>
                    </dd>
                    <dt>Ordre d'affichage</dt>
                    <dd>
                        <fieldset class="form-group">
                            <input type="number" name="sort_order" id="sort_order"
                                value="<?= $benefit['sort_order'] ?? 0 ?>"
                                min="0" max="999">
                            <small>Les benefits sont triés par ordre croissant (0 = premier)</small>
                        </fieldset>
                    </dd>
                    <?php if ($benefit['created_at']): ?>
                        <dt>Créé</dt>
                        <dd>
                            <time datetime="<?= $benefit['created_at'] ?>">
                                <?= date('d/m/Y à H:i', strtotime($benefit['created_at'])) ?>
                            </time>
                        </dd>
                    <?php endif; ?>

                    <?php if ($benefit['updated_at']): ?>
                        <dt>Modifié</dt>
                        <dd>
                            <time datetime="<?= $benefit['updated_at'] ?>">
                                <?= date('d/m/Y à H:i', strtotime($benefit['updated_at'])) ?>
                            </time>
                        </dd>
                    <?php endif; ?>
                </dl>
            </section>
        </aside>
    <?php endif; ?>

    <footer class="form-actions">
        <button type="submit" class="btn">
            <?= $is_edit ? 'Mettre à jour' : 'Créer le benefit' ?>
        </button>
        <a href="/admin/benefit" class="btn secondary">Annuler</a>

        <?php if ($is_edit): ?>
            <button type="submit" name="action" value="delete" class="btn danger"
                data-confirm="Supprimer ce benefit ?">
                Supprimer
            </button>
        <?php endif; ?>
    </footer>
</form>