<?php
// app/io/render/admin/taxonomy.php
?>


<div class="meta-box panel">
    <header>
        <h2>Ajouter une catégorie</h2>
    </header>
    <form method="POST">
        <?= csrf_field(3600) ?>

        <fieldset class="form-group">
            <label>Label</label>
            <input type="text" name="label" class="form-control">
        </fieldset>
        <div class="taxonomy-meta">
            <fieldset class="form-group">
                <label>Parent</label>
                <select name="parent_id" class="form-control">
                    <option value="">Sélectionner un parent</option>
                    <?php foreach ($root_categories as $root): ?>
                        <option value="<?= $root['id'] ?>"><?= htmlspecialchars($root['label']) ?></option>
                    <?php endforeach; ?>
                </select>
            </fieldset>
            <fieldset class="form-group">
                <label>Slug</label>
                <input type="text" name="slug" class="form-control">
            </fieldset>
            <fieldset class="form-group">
                <label>Ordre</label>
                <input type="number" name="sort_order" value="1" class="form-control">
            </fieldset>
        </div>
        <button type="submit" class="btn btn-primary">Créer</button>
    </form>

</div>

<div class="page-header">
    <h1>Gestion Taxonomie</h1>
    <div class="page-actions">
        <a href="/admin/site" class="btn secondary">Retour à la configuration</a>
    </div>
</div>



<form method="POST" class="alter-form">
    <?= csrf_field(3600) ?>
    <div class="form-main">
        <?php
        // Group taxonomies by parent
        $grouped = [];
        foreach ($taxonomies as $taxonomy) {
            $parent_id = $taxonomy['parent_id'] ?? 'root';
            $grouped[$parent_id][] = $taxonomy;
        }

        // Display each parent section
        foreach ($root_categories as $parent): ?>
            <div class="taxonomy-section">
                <h2 id="section-<?= $parent['id'] ?>" class="section-header"><?= htmlspecialchars($parent['label']) ?></h2>

                <?php if (isset($grouped[$parent['id']])): ?>
                    <?php foreach ($grouped[$parent['id']] as $taxonomy): ?>
                        <div class="meta-box" id="item-<?= $taxonomy['id'] ?>">


                            <input type="hidden" name="items[<?= $taxonomy['id'] ?>][id]" value="<?= $taxonomy['id'] ?>">

                            <div class="form-group">
                                <label>Label</label>
                                <input type="text" name="items[<?= $taxonomy['id'] ?>][label]" value="<?= htmlspecialchars($taxonomy['label']) ?>" class="form-control" required>
                                <div class="taxonomy-meta">
                                    <div class="form-group">
                                        <label>Parent</label>
                                        <select name="items[<?= $taxonomy['id'] ?>][parent_id]" class="form-control">
                                            <option value="">Aucun</option>
                                            <?php foreach ($root_categories as $root): ?>
                                                <option value="<?= $root['id'] ?>" <?= $taxonomy['parent_id'] == $root['id'] ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($root['label']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Ordre</label>
                                        <input type="number" name="items[<?= $taxonomy['id'] ?>][sort_order]" value="<?= $taxonomy['sort_order'] ?>" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Slug</label>
                                        <input type="text" name="items[<?= $taxonomy['id'] ?>][slug]" value="<?= htmlspecialchars($taxonomy['slug']) ?>" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <div>
                        <button type="submit" class="btn btn-primary">Enregistrer toutes les modifications</button>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

    </div>

    <aside>
        <div class="meta-box panel">
            <header>
                <h2>Table des matières</h2>
            </header>
            <ul class="toc-list">
                <?php foreach ($root_categories as $root): ?>
                    <li>
                        <a href="#section-<?= $root['id'] ?>">
                            <?= htmlspecialchars($root['label']) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

    </aside>
</form>