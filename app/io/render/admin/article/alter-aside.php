<section class="panel publish-box">
    <header>
        <h2>Publication</h2>
    </header>
    <fieldset class="form-group">
        <legend class="sr-only">État de publication</legend>
        <label class="checkbox-label">
            <input type="checkbox" name="published" value="1" <?= !empty($article['enabled_at']) ? 'checked' : '' ?>>
            <span class="checkbox-text">
                <?php if ($article['enabled_at']): ?>
                    Publié le <time datetime="<?= $article['enabled_at'] ?>"><?= $article['enabled_at'] ?></time>
                <?php else: ?>
                    Publier l'article
                <?php endif; ?>
            </span>
        </label>
    </fieldset>
    <label class="checkbox-label">
        <input
            type="checkbox"
            name="featured"
            value="1"
            <?= !empty($article['featured']) ? 'checked' : '' ?>>
        <span class="checkbox-text">Article à la une</span>
    </label>
    </fieldset>
    <footer>
        <button type="submit" class="btn">Sauver</button>
        <button class="emoji-trigger">😀</button>
    </footer>

</section>

<section class="panel meta-box">
    <header>
        <h2>Métadonnées</h2>
    </header>

    <fieldset class="form-group">
        <label for="category_slug">Catégorie</label>
        <select name="category_slug">
            <?php foreach (($categories) as $slug => $label): 
                ?>
                <option value="<?= $slug ?>"
                    <?= ($article['category_id'] ?? '') == tag_id_by_slug($slug, 'article-categorie') ? 'selected' : '' ?>>
                    <?= htmlspecialchars($label) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </fieldset>

    <fieldset class="form-group">
        <label for="reading_time">Temps de lecture (minutes)</label>
        <input
            type="number"
            name="reading_time"
            id="reading_time"
            min="1"
            max="60"
            value="<?= $article['reading_time'] ?? '' ?>"
            aria-describedby="reading-help">
        <small id="reading-help">Laissez vide pour calcul automatique</small>
    </fieldset>

    <script type="module">
        import reading_time from '/asset/js/reading-time.js';

        document.addEventListener('DOMContentLoaded', () => {
            const textArea = document.querySelector('textarea[name="content"]');
            const timeInput = document.querySelector('input[name="reading_time"]');

            if (!textArea || !timeInput) return;

            const updateReadingTime = () => {
                const content = textArea.value.trim();
                if (content === '') {
                    timeInput.value = '';
                } else {
                    // reading_time() returns a Number of whole minutes
                    timeInput.value = reading_time(content);
                }
            };

            // Compute on load (in case the textarea is pre-filled)
            updateReadingTime();

            // Recompute on every edit
            textArea.addEventListener('input', updateReadingTime);
        });
    </script>

</section>

<?php if ($is_edit): ?>
    <?php
    $dropzone_relative_path = 'article/avatar/' . $article['slug'];
    $preview_src = '/asset/image//' . $dropzone_relative_path . '.webp';
    include('app/io/render/admin/dropzone.php')
    ?>

    <section class="panel stats-box">
        <header>
            <h2>Statistiques</h2>
        </header>

        <dl class="stats-list">
            <dt>Créé le</dt>
            <dd>
                <time datetime="<?= $article['created_at'] ?>">
                    <?= date('d F Y', strtotime($article['created_at'])) ?>
                </time>
            </dd>

            <?php if ($article['updated_at']): ?>
                <dt>Modifié le</dt>
                <dd>
                    <time datetime="<?= $article['updated_at'] ?>">
                        <?= date('d F Y \à H:i',  strtotime($article['updated_at'])) ?>
                    </time>
                </dd>
            <?php endif; ?>

            <dt>Slug</dt>
            <dd><code><?= htmlspecialchars($article['slug'] ?? 'auto-généré') ?></code></dd>
        </dl>

    </section>
<?php endif; ?>