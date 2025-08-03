<?php
$is_edit = !empty($event['id']);
?>

<header class="page-header">
    <h1><?= $is_edit ? 'Modifier l\'événement' : 'Nouvel événement' ?></h1>
    <?php if ($is_edit): ?>
        <nav class="page-actions">
            <a href="/admin/event" class="btn secondary">Retour à la liste</a>
            <?php if (!empty($event['enabled_at'])): ?>
                <a href="/event/detail/<?= $event['slug'] ?>" class="btn secondary" target="_blank">
                    Voir sur le site
                </a>
            <?php endif; ?>
        </nav>
    <?php endif; ?>
</header>

<form method="post" class="alter-form" enctype="multipart/form-data">
    <?= csrf_field(3600) ?>
    <input type="hidden" name="id" value="<?= $event['id'] ?? null ?>">

    <section class="form-main">
        <fieldset class="form-group">
            <label for="label">Titre de l'événement *</label>
            <input
                type="text"
                id="label"
                name="label"
                value="<?= htmlspecialchars($event['label'] ?? '') ?>"
                required
                maxlength="200"
                aria-describedby="label-help">
            <label for="label">Slug *</label>
            <input
                type="text"
                name="slug"
                value="<?= htmlspecialchars($event['slug'] ?? '') ?>"
                required
                maxlength="200"
                aria-describedby="label-help">
            <small id="label-help">Le slug sera généré automatiquement</small>
        </fieldset>

        <fieldset class="form-group">
            <label for="content">Description *</label>
            <textarea
                id="content"
                name="content"
                rows="10"
                required
                class="content-editor"><?= htmlspecialchars($event['content'] ?? '') ?></textarea>
        </fieldset>

        <div class="form-row">
            <fieldset class="form-group">
                <label for="event_date">Date et heure *</label>
                <input
                    type="datetime-local"
                    id="event_date"
                    name="event_date"
                    value="<?= $event['event_date'] ? date('Y-m-d\TH:i', strtotime($event['event_date'])) : '' ?>"
                    required>
            </fieldset>

            <fieldset class="form-group">
                <label for="duration_minutes">Durée (minutes) *</label>
                <input
                    type="number"
                    id="duration_minutes"
                    name="duration_minutes"
                    min="15"
                    max="480"
                    value="<?= $event['duration_minutes'] ?? '' ?>"
                    required
                    aria-describedby="duration-help">
                <small id="duration-help">Entre 15 minutes et 8 heures</small>
            </fieldset>
        </div>

        <div class="form-row">
            <fieldset class="form-group">
                <label for="speaker">Intervenant</label>
                <input
                    type="text"
                    id="speaker"
                    name="speaker"
                    value="<?= htmlspecialchars($event['speaker'] ?? '') ?>"
                    maxlength="100">
            </fieldset>

            <fieldset class="form-group">
                <label for="places_max">Nombre de places</label>
                <input
                    type="number"
                    id="places_max"
                    name="places_max"
                    min="1"
                    max="1000"
                    value="<?= $event['places_max'] ?? '' ?>"
                    aria-describedby="places-help">
                <small id="places-help">Laissez vide pour illimité</small>
            </fieldset>
        </div>

        <fieldset class="form-group">
            <label for="location">Lieu</label>
            <input
                type="text"
                id="location"
                name="location"
                value="<?= htmlspecialchars($event['location'] ?? '') ?>"
                maxlength="200"
                aria-describedby="location-help">
            <small id="location-help">Adresse physique ou plateforme en ligne</small>
        </fieldset>
    </section>

    <aside>
        <section class="panel publish-box">
            <header>
                <h2>Publication</h2>
            </header>

            <fieldset class="form-group">
                <legend class="sr-only">État de publication</legend>
                <label class="checkbox-label">
                    <input
                        type="checkbox"
                        name="published"
                        value="1"
                        <?= !empty($event['enabled_at']) ? 'checked' : '' ?>>
                    <span class="checkbox-text">Publier l'événement</span>
                </label>
                <?php if ($event['enabled_at']): ?>
                    <small>
                        Publié le
                        <time datetime="<?= $event['enabled_at'] ?>">
                            <?= date('d F Y \à H:i',  strtotime($event['enabled_at'])) ?>
                        </time>
                    </small>
                <?php endif; ?>
            </fieldset>

            <fieldset class="form-group">
                <label class="checkbox-label">
                    <input
                        type="checkbox"
                        name="online"
                        value="1"
                        <?= !empty($event['online']) ? 'checked' : '' ?>>
                    <span class="checkbox-text">Événement en ligne</span>
                </label>
            </fieldset>
        </section>

        <section class="panel meta-box">
            <header>
                <h2>Métadonnées</h2>
            </header>

            <fieldset class="form-group">
                <label for="category_slug">Catégorie</label>
                <select id="category_slug" name="category_slug">
                    <?php foreach ($categories as $slug => $label): ?>
                        <option
                            value="<?= $slug ?>"
                            <?= ($event['category_slug'] ?? '') === $slug ? 'selected' : '' ?>>
                            <?= htmlspecialchars($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </fieldset>

            <fieldset class="form-group">
                <label for="price_ht">Prix HT (€)</label>
                <input
                    type="number"
                    id="price_ht"
                    name="price_ht"
                    min="0"
                    step="0.01"
                    value="<?= $event['price_ht'] ?? '' ?>"
                    aria-describedby="price-help">
                <small id="price-help">Laissez vide pour gratuit</small>
            </fieldset>
        </section>
        <?php
        $dropzone_relative_path = 'event/avatar/' . $event['slug'];
        $preview_src = '/asset/image/' . $dropzone_relative_path . '.webp';
        include('app/io/render/admin/dropzone.php')
        ?>


        <?php if ($is_edit): ?>
            <section class="panel stats-box">
                <header>
                    <h2>Statistiques</h2>
                </header>

                <dl class="stats-list">
                    <dt>Créé le</dt>
                    <dd>
                        <time datetime="<?= $event['created_at'] ?>">
                            <?= date('d F Y', strtotime($event['created_at'])) ?>
                        </time>
                    </dd>

                    <?php if ($event['updated_at']): ?>
                        <dt>Modifié le</dt>
                        <dd>
                            <time datetime="<?= $event['updated_at'] ?>">
                                <?= date('d F Y \à H:i',  strtotime($event['updated_at'])) ?>
                            </time>
                        </dd>
                    <?php endif; ?>

                    <dt>Slug</dt>
                    <dd><code><?= htmlspecialchars($event['slug'] ?? 'auto-généré') ?></code></dd>

                    <?php if ($event['event_date']): ?>
                        <dt>Date de l'événement</dt>
                        <dd>
                            <time datetime="<?= $event['event_date'] ?>">
                                <?= date('d F Y \à H:i',  strtotime($event['event_date'])) ?>
                            </time>
                        </dd>
                    <?php endif; ?>
                </dl>
            </section>
        <?php endif; ?>
    </aside>

    <footer class="form-actions">
        <button type="submit" class="btn">
            <?= $is_edit ? 'Mettre à jour' : 'Créer l\'événement' ?>
        </button>
        <a href="/admin/event" class="btn secondary">Retour</a>

        <?php if ($is_edit): ?>
            <button
                type="submit"
                name="action"
                value="delete"
                class="btn danger"
                data-confirm="Êtes-vous sûr de vouloir supprimer cet événement ?">
                Supprimer
            </button>
        <?php endif; ?>
    </footer>
</form>