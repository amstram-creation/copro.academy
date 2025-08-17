<?php
$is_edit = !empty($trainer['id']);
?>

<header class="page-header">
    <h1><?= $is_edit ? 'Modifier le formateur' : 'Nouveau formateur' ?></h1>
    <?php if ($is_edit): ?>
        <nav class="page-actions">
            <a href="/admin/trainer" class="btn secondary">Retour à la liste</a>
            <?php if ($trainer['enabled_at']): ?>
                <a href="/trainer/<?= $trainer['slug'] ?>" class="btn secondary" target="_blank">
                    Voir le profil
                </a>
            <?php endif; ?>
        </nav>
    <?php endif; ?>
</header>

<form method="post" class="alter-form" enctype="multipart/form-data">
    <?= csrf_field(3600) ?>
    <input type="hidden" name="id" value="<?= $trainer['id'] ?? null ?>">

    <section class="form-main">
        <fieldset class="form-group">
            <label for="label">Nom complet *</label>
            <input type="text" name="label" id="label"
                value="<?= htmlspecialchars($trainer['label'] ?? '') ?>"
                required maxlength="100">
        </fieldset>

        <fieldset class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email"
                value="<?= htmlspecialchars($trainer['email'] ?? '') ?>"
                maxlength="100">
            <small>Adresse email professionnelle du formateur</small>
        </fieldset>

        <fieldset class="form-group">
            <label for="bio">Biographie</label>
            <textarea name="bio" id="bio" rows="6"
                placeholder="Présentation du formateur, ses qualifications, son expérience..."><?= htmlspecialchars($trainer['bio'] ?? '') ?></textarea>
            <small>Description qui apparaîtra sur le profil public</small>
        </fieldset>

        <fieldset class="form-group">
            <label for="hire_date">Date d'embauche</label>
            <input type="date" name="hire_date" id="hire_date"
                value="<?= $trainer['hire_date'] ?? '' ?>">
            <small>Date d'entrée dans l'équipe</small>
        </fieldset>
    </section>

    <aside>
        <section class="panel publish-box">
            <header>
                <h2>Publication</h2>
            </header>

            <fieldset class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="published" value="1"
                        <?= !empty($trainer['enabled_at']) ? 'checked' : '' ?>>
                    <span class="checkbox-text">
                        <?php if ($trainer['enabled_at']): ?>
                            Activé le <time datetime="<?= $trainer['enabled_at'] ?>"><?= $trainer['enabled_at'] ?></time>
                        <?php else: ?>
                            Activer le formateur
                        <?php endif; ?>
                    </span>
                </label>

            </fieldset>


            <fieldset class="form-group">

                <label for="label">Slug *</label>
                <input
                    type="text"
                    name="slug"
                    value="<?= htmlspecialchars($trainer['slug'] ?? '') ?>"
                    required
                    maxlength="200"
                    aria-describedby="label-help">
                <small>Généré automatiquement à partir du nom</small>
            </fieldset>

            <footer>
                <button type="submit" class="btn">Sauver</button>
                <button class="emoji-trigger">😀</button>
            </footer>
        </section>
        <?php
        $dropzone_relative_path = 'trainer/avatar/' . $trainer['slug'];
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
                        <time datetime="<?= $trainer['created_at'] ?>">
                            <?= date('d/m/Y', strtotime($trainer['created_at'])) ?>
                        </time>
                    </dd>

                    <?php if ($trainer['updated_at']): ?>
                        <dt>Modifié le</dt>
                        <dd>
                            <time datetime="<?= $trainer['updated_at'] ?>">
                                <?= date('d/m/Y à H:i', strtotime($trainer['updated_at'])) ?>
                            </time>
                        </dd>
                    <?php endif; ?>

                    <?php if ($trainer['hire_date']): ?>
                        <dt>Ancienneté</dt>
                        <dd>
                            <?php
                            $years = floor((time() - strtotime($trainer['hire_date'])) / (365.25 * 24 * 3600));
                            echo $years > 0 ? "{$years} an" . ($years > 1 ? 's' : '') : 'Moins d\'un an';
                            ?>
                        </dd>
                    <?php endif; ?>

                    <dt>Slug</dt>
                    <dd><code><?= htmlspecialchars($trainer['slug'] ?? 'auto-généré') ?></code></dd>
                </dl>
            </section>
        <?php endif; ?>
    </aside>

    <footer class="form-actions">
        <button type="submit" class="btn">
            <?= $is_edit ? 'Mettre à jour' : 'Créer le formateur' ?>
        </button>
        <a href="/admin/trainer" class="btn secondary">Retour</a>

        <?php if ($is_edit): ?>
            <button type="submit" name="action" value="delete" class="btn danger"
                data-confirm="Êtes-vous sûr de vouloir supprimer ce formateur ?">
                Supprimer
            </button>
        <?php endif; ?>
    </footer>
</form>

<style>
    .trainer-photo {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #e5e7eb;
    }

    .current-image {
        text-align: center;
        margin-bottom: 1rem;
    }

    .current-image figcaption {
        margin-top: 0.5rem;
        color: #6b7280;
        font-size: 0.875rem;
    }

    .stats-list {
        display: grid;
        gap: 0.5rem;
    }

    .stats-list dt {
        font-weight: 600;
        color: #374151;
    }

    .stats-list dd {
        color: #6b7280;
        margin: 0 0 0.75rem 0;
    }

    .stats-list code {
        background: #f3f4f6;
        padding: 0.125rem 0.25rem;
        border-radius: 0.25rem;
        font-size: 0.875rem;
    }
</style>