<?php
$is_edit = !empty($training['id']);
?>

<header class="page-header">
    <h1><?= $is_edit ? 'Modifier la formation' : 'Nouvelle formation' ?></h1>
    <?php if ($is_edit): ?>
        <nav class="page-actions">
            <a href="/admin/training" class="btn secondary">Retour à la liste</a>
            <?php if ($training['enabled_at']): ?>
                <a href="/formation/detail/<?= $training['slug'] ?>" class="btn secondary" target="_blank">Voir sur le site</a>
            <?php endif; ?>
        </nav>
    <?php endif; ?>
</header>

<form method="post" class="alter-form" enctype="multipart/form-data">
    <?= csrf_field(3600) ?>
    <input type="hidden" name="id" value="<?= $training['id'] ?? null ?>">

    <section class="form-main">
        <fieldset class="form-group">
            <label for="label">Titre de la formation *</label>
            <input type="text" name="label" id="label"
                value="<?= htmlspecialchars($training['label'] ?? '') ?>"
                required maxlength="200">

            <label for="label">Slug *</label>
            <input
                type="text"
                name="slug"
                value="<?= htmlspecialchars($training['slug'] ?? '') ?>"
                required
                maxlength="200"
                aria-describedby="label-help">
        </fieldset>


        <fieldset class="form-group">
            <label for="content">Description *</label>
            <textarea name="content" class="content-editor" id="description" rows="6" required><?= htmlspecialchars($training['content'] ?? '') ?></textarea>
        </fieldset>

        <div class="form-row">
            <fieldset class="form-group">
                <label for="start_date">Date de début *</label>
                <input type="date" name="start_date" id="start_date"
                    value="<?= $training['start_date'] ? date('Y-m-d', strtotime($training['start_date'])) : '' ?>"
                    required>
            </fieldset>

            <fieldset class="form-group">
                <label for="duration_days">Durée (jours) *</label>
                <input type="number" name="duration_days" id="duration_days"
                    value="<?= $training['duration_days'] ?? 1 ?>"
                    min="1" max="30" required>
            </fieldset>

            <fieldset class="form-group">
                <label for="duration_hours">Heures par jour *</label>
                <input type="number" name="duration_hours" id="duration_hours"
                    value="<?= $training['duration_hours'] ?? 7 ?>"
                    min="1" max="12" required>
            </fieldset>
        </div>

        <div class="form-row">
            <fieldset class="form-group">
                <label for="price_ht">Prix HT (€) *</label>
                <input type="number" name="price_ht" id="price_ht"
                    value="<?= $training['price_ht'] ?? '' ?>"
                    min="0" step="0.01" required>
            </fieldset>

            <fieldset class="form-group">
                <label for="places_max">Places maximum</label>
                <input type="number" name="places_max" id="places_max"
                    value="<?= $training['places_max'] ?? '' ?>"
                    min="1">
                <small>Laissez vide pour illimité</small>
            </fieldset>
        </div>

        <fieldset class="form-group">
            <label for="objectives">Objectifs pédagogiques</label>
            <textarea name="objectives" id="objectives" rows="4"><?= htmlspecialchars($training['objectives'] ?? '') ?></textarea>
            <small>Décrivez ce que les participants apprendront; veillez a separer chaque objectif par un point virgule (;)</small>
        </fieldset>

        <fieldset class="form-group">
            <label for="prerequisites">Prérequis</label>
            <textarea name="prerequisites" id="prerequisites" rows="3"><?= htmlspecialchars($training['prerequisites'] ?? '') ?></textarea>
            <small>Connaissances ou expérience nécessaires</small>
        </fieldset>

        <fieldset class="form-group">
            <label for="pause">Pause/Lunch</label>
            <textarea name="pause" id="pause" rows="4"><?= htmlspecialchars($training['pause'] ?? '') ?></textarea>
            <small>Dispositions pour le cafe du matin ou la pause du midi</small>
        </fieldset>

        <fieldset class="form-group">
            <label for="parking">Parking</label>
            <textarea name="parking" id="parking" rows="3"><?= htmlspecialchars($training['parking'] ?? '') ?></textarea>
            <small>Dispositions pour le parking</small>
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
                        <?= !empty($training['enabled_at']) ? 'checked' : '' ?>>
                    <span>Publier la formation</span>
                </label>
                <?php if ($training['enabled_at']): ?>
                    <small>
                        Publié le
                        <time datetime="<?= $training['enabled_at'] ?>">
                            <?= date('d/m/Y à H:i', strtotime($training['enabled_at'])) ?>
                        </time>
                    </small>
                <?php endif; ?>
            </fieldset>

            <fieldset class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="certification" value="1"
                        <?= !empty($training['certification']) ? 'checked' : '' ?>>
                    <span>Formation certifiante</span>
                </label>
                <small>Délivre une certification à l'issue</small>
            </fieldset>
        </section>

        <section class="panel meta-box">
            <header>
                <h2>Métadonnées</h2>
            </header>

            <fieldset class="form-group">
                <label for="level_slug">Niveau *</label>
                <select name="level_slug" id="level_slug" required>
                    <?php foreach ($levels as $slug => $label): ?>
                        <option value="<?= $slug ?>"
                            <?= ($training['level_slug'] ?? '') == $slug ? 'selected' : '' ?>>
                            <?= htmlspecialchars($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </fieldset>

            <fieldset class="form-group">
                <label for="trainer_id">Formateur</label>
                <select name="trainer_id" id="trainer_id">
                    <?php foreach ($trainers as $trainer): ?>
                        <option value="<?= $trainer['id'] ?>"
                            <?= ($training['trainer_id'] ?? '') == $trainer['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($trainer['label']) ?>
                            <?php if ($trainer['email']): ?>
                                (<?= htmlspecialchars($trainer['email']) ?>)
                            <?php endif; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <small><a href="/admin/trainer/alter" target="_blank">Créer un nouveau formateur</a></small>
            </fieldset>
        </section>

        <?php if ($is_edit && $training['id']): ?>
            <?php
            $dropzone_relative_path = 'formation/avatar/' . $training['slug'];
            $preview_src = '/asset/image/' . $dropzone_relative_path . '.webp';
            include('app/io/render/admin/dropzone.php')
            ?>

            <!-- Program Overview Panel -->
            <section class="panel program-panel">
                <header class="panel-header">
                    <h3>Programme de formation</h3>
                    <a href="/admin/training/program/<?= $training['slug'] ?>" class="btn small">Gérer le programme</a>
                </header>

                <?php
                // Get program sessions for this training
                $program_sessions = qp(db(), "
                SELECT day_number, COUNT(*) as sessions_count,
                       MIN(time_start) as first_session,
                       MAX(time_end) as last_session,
                       SUM(TIMESTAMPDIFF(MINUTE, time_start, time_end)) as total_minutes
                FROM training_program 
                WHERE training_id = ? 
                GROUP BY day_number
                ORDER BY day_number", [$training['id']])->fetchAll();

                $total_sessions = qp(db(), "SELECT COUNT(*) FROM training_program WHERE training_id = ?", [$training['id']])->fetchColumn();
                ?>

                <?php if (empty($program_sessions)): ?>
                    <div class="panel-content empty-state">
                        <p>Aucun programme détaillé</p>
                        <small>Créez le programme jour par jour avec les sessions, horaires et objectifs.</small>
                        <a href="/admin/training/program/<?= $training['id'] ?>" class="btn secondary small">Créer le programme</a>
                    </div>
                <?php else: ?>
                    <div class="panel-content">
                        <div class="program-stats">
                            <div class="stat-row">
                                <span class="stat-label">Sessions totales:</span>
                                <strong><?= $total_sessions ?></strong>
                            </div>
                            <div class="stat-row">
                                <span class="stat-label">Jours programmés:</span>
                                <strong><?= count($program_sessions) ?>/<?= $training['duration_days'] ?></strong>
                            </div>
                            <div class="stat-row">
                                <span class="stat-label">Durée programmée:</span>
                                <strong>
                                    <?php
                                    $total_minutes = array_sum(array_column($program_sessions, 'total_minutes'));
                                    echo round($total_minutes / 60, 1) . 'h';
                                    ?>
                                </strong>
                            </div>
                        </div>

                    </div>
                <?php endif; ?>

                <nav class="quick-actions">

                    <?php if ($training['enabled_at']): ?>
                        <a href="/training/detail/<?= $training['slug'] ?>" class="action-link" target="_blank">
                            <strong>Voir sur le site</strong>
                            <span>Page publique de la formation</span>
                        </a>
                    <?php endif; ?>

                    <?php
                    $bookings_count = qp(db(), "SELECT COUNT(*) FROM booking WHERE training_id = ? AND revoked_at IS NULL", [$training['id']])->fetchColumn();
                    ?>
                    <a href="/admin/booking?training_id=<?= $training['id'] ?>" class="action-link">
                        <strong>Inscriptions (<?= $bookings_count ?>)</strong>
                        <span>Gérer les participants</span>
                    </a>
                </nav>
            </section>

            <section class="panel stats-box">
                <header>
                    <h2>Statistiques</h2>
                </header>

                <dl class="stats-list">
                    <dt>Créé le</dt>
                    <dd>
                        <time datetime="<?= $training['created_at'] ?>">
                            <?= date('d/m/Y', strtotime($training['created_at'])) ?>
                        </time>
                    </dd>

                    <?php if ($training['updated_at']): ?>
                        <dt>Modifié le</dt>
                        <dd>
                            <time datetime="<?= $training['updated_at'] ?>">
                                <?= date('d/m/Y à H:i', strtotime($training['updated_at'])) ?>
                            </time>
                        </dd>
                    <?php endif; ?>

                    <dt>Durée totale</dt>
                    <dd>
                        <?= $training['duration_days'] * $training['duration_hours'] ?> heures
                        (<?= $training['duration_days'] ?> jour<?= $training['duration_days'] > 1 ? 's' : '' ?>)
                    </dd>

                    <?php
                    $bookings = qp(db(), "
                        SELECT COUNT(*) as count 
                        FROM booking 
                        WHERE training_id = ? AND revoked_at IS NULL
                    ", [$training['id']])->fetch();
                    ?>
                    <dt>Inscriptions</dt>
                    <dd>
                        <?= $bookings['count'] ?? 0 ?>
                        <?php if ($training['places_max']): ?>
                            / <?= $training['places_max'] ?>
                        <?php endif; ?>
                    </dd>
                </dl>
            </section>
        <?php endif; ?>
    </aside>

    <footer class="form-actions">
        <button type="submit" class="btn">
            <?= $is_edit ? 'Mettre à jour' : 'Créer la formation' ?>
        </button>
        <a href="/admin/training" class="btn secondary">Retour</a>

        <?php if ($is_edit): ?>
            <button type="submit" name="action" value="delete" class="btn danger"
                data-confirm="Supprimer cette formation et toutes ses inscriptions ?">
                Supprimer
            </button>
        <?php endif; ?>
    </footer>
</form>