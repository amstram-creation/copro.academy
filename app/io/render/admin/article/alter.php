<?php
// io/render/admin/article/alter.php
$is_edit = !empty($article['id']);
?>

<header class="page-header">
    <h1><?= $is_edit ? 'Modifier l\'article' : 'Nouvel article' ?></h1>
    <?php if ($is_edit): ?>
        <nav class="page-actions">
            <a href="/admin/article" class="btn secondary">Retour à la liste</a>
            <?php if ($article['enabled_at']): ?>
                <a href="/article/detail/<?= $article['slug'] ?>" class="btn secondary" target="_blank">
                    Voir sur le site
                </a>
            <?php endif; ?>
        </nav>
    <?php endif; ?>
</header>

<form method="post" class="alter-form" enctype="multipart/form-data">
    <?= csrf_field(3600) ?>
    <input type="hidden" name="id" value="<?= $article['id'] ?? null ?>">

    <section class="form-main">
        <fieldset class="form-group">
            <label for="label">Titre *</label>
            <input
                type="text"
                id="label"
                name="label"
                required
                maxlength="200"
                aria-describedby="label-help"
                value="<?= ($article['label'] ?? '') ?>" />
        </fieldset>
        <fieldset class="form-group">
            <label for="summary">Résumé</label>
            <textarea
                id="summary"
                name="summary"
                rows="3"
                maxlength="500"
                aria-describedby="summary-help"><?= ($article['summary'] ?? '') ?></textarea>
            <small id="summary-help">Description courte pour les réseaux sociaux et moteurs de recherche</small>
        </fieldset>

        <fieldset class="form-group">
            <label for="content">Contenu</label>
            <textarea
                id="content"
                name="content"
                class="content-editor"
                rows="10"
                maxlength="500"
                aria-describedby="content-help">
                <?= ($article['content'] ?? '') ?>
            </textarea>
            <small id="content-help">Contenu principal, avant les sections</small>
        </fieldset>

        <!-- render all 5 sections unconditionally -->
        <?php for ($i = 1; $i <= 5; $i++): ?>
            <div class="section-block" data-index="<?= $i ?>">
                <fieldset class="form-group">
                    <label for="section<?= $i ?>_label">Section <?= $i ?> – Titre</label>
                    <textarea id="section<?= $i ?>_label" name="section<?= $i ?>_label" maxlength="200" placeholder="Titre de la section <?= $i ?>">
                        <?= $article['section' . $i . '_label'] ?? '' ?>
                    </textarea>
                </fieldset>

                <fieldset class="form-group">
                    <label for="section<?= $i ?>_content">Section <?= $i ?> – Contenu</label>
                    <textarea
                        id="section<?= $i ?>_content"
                        name="section<?= $i ?>_content"
                        rows="10"
                        class="content-editor"
                        placeholder="Contenu de la section <?= $i ?>">
                        <?= ($article['section' . $i . '_content'] ?? '') ?>
                    </textarea>
                </fieldset>
            </div>
        <?php endfor; ?>

        <p><a href="#" id="add-section">Ajouter une section</a></p>
    </section>

    <aside><?php include 'alter-aside.php'; ?></aside>

    <footer class="form-actions">
        <button type="submit" class="btn">Sauver</button>
        <a href="/admin/article" class="btn secondary">Retour</a>
        <?php if ($is_edit): ?>
            <button
                type="submit"
                name="action"
                value="delete"
                class="btn danger"
                data-confirm="Êtes-vous sûr de vouloir supprimer cet article ?">
                Supprimer
            </button>
        <?php endif; ?>
    </footer>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const addLink = document.getElementById('add-section');
        let nextIndex = 2;
        let foundNonEmpty = false;

        // Hide trailing empty sections (5 → 2) until you hit a non–empty one
        for (let i = 5; i >= 2; i--) {
            const lbl = document.getElementById(`section${i}_label`);
            const cnt = document.getElementById(`section${i}_content`);
            const block = document.querySelector(`.section-block[data-index="${i}"]`);
            const hasText = (lbl.value || '').trim() !== '' || (cnt.value || '').trim() !== '';

            if (!hasText) {
                block.style.display = 'none';
            } else {
                nextIndex = i + 1;
                foundNonEmpty = true;
                break;
            }
        }

        // If we never found any non-empty after 1, nextIndex stays at 2

        function disableLink() {
            addLink.textContent = 'Toutes les sections affichées';
            addLink.classList.add('disabled');
            addLink.removeAttribute('href');
        }

        // If there’s nothing to reveal, disable immediately
        if (nextIndex > 5) {
            disableLink();
        }

        addLink.addEventListener('click', function(e) {
            e.preventDefault();
            const block = document.querySelector(`.section-block[data-index="${nextIndex}"]`);
            if (!block) return;

            block.style.display = '';
            // find the next hidden section after this one
            let newNext = null;
            for (let j = nextIndex + 1; j <= 5; j++) {
                const b = document.querySelector(`.section-block[data-index="${j}"]`);
                if (b && b.style.display === 'none') {
                    newNext = j;
                    break;
                }
            }
            if (newNext) {
                nextIndex = newNext;
            } else {
                disableLink();
            }
        });
    });
</script>