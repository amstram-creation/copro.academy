<header class="page-header">
    <h1>Session - <?= htmlspecialchars($training['label']) ?></h1>
    <nav class="page-actions">
        <a href="/admin/training/alter/<?= $training['slug'] ?>" class="btn secondary">Retour a la formation</a>
        <a href="/admin/training/program/<?= $training['slug'] ?>" class="btn secondary">Retour au programme</a>
        <a href="/admin/training/session/<?= $training['slug'] ?>" class="btn">Nouvelle session</a>
    </nav>
</header>
<section class="session-form-overlay" id="session-form">
    <div class="form-container">
        <header class="form-header">
            <h2><?= $edit_session ? 'Modifier la session' : 'Nouvelle session' ?></h2>
        </header>

        <form method="post" class="session-form">
            <?= csrf_field(3600) ?>
            <?php if ($edit_session): ?>
                <input type="hidden" name="id" value="<?= $edit_session['id'] ?>">
            <?php endif; ?>

            <div class="form-row">
                <fieldset class="form-group">
                    <label for="day_number">Jour *</label>
                    <select name="day_number" id="day_number" required>
                        <?php for ($day = 1; $day <= $training['duration_days']; $day++): ?>
                            <option value="<?= $day ?>" <?= ($edit_session['day_number'] ?? ($_GET['day'] ?? 1)) == $day ? 'selected' : '' ?>>
                                Jour <?= $day ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </fieldset>

                <fieldset class="form-group">
                    <label for="time_start">Heure de début *</label>
                    <input type="time" name="time_start" id="time_start" step="60"
                        value="<?= $edit_session['time_start'] ?? '' ?>" required>
                </fieldset>

                <fieldset class="form-group">
                    <label for="time_end">Heure de fin *</label>
                    <input type="time" name="time_end" id="time_end" step="60"
                        value="<?= $edit_session['time_end'] ?? '' ?>" required>
                </fieldset>
            </div>

            <fieldset class="form-group">
                <label for="label">Titre de la session *</label>
                <input type="text" name="label" id="label"
                    value="<?= htmlspecialchars($edit_session['label'] ?? '') ?>"
                    required maxlength="150">
            </fieldset>

            <fieldset class="form-group">
                <label for="slug">Slug</label>
                <input type="text" name="slug" id="slug"
                    value="<?= htmlspecialchars($edit_session['slug'] ?? '') ?>"
                    maxlength="155">
                <small>Généré automatiquement si vide</small>
            </fieldset>

            <fieldset class="form-group">
                <label for="content">Description</label>
                <textarea name="content" class="content-editor" id="content" rows="4"><?= htmlspecialchars($edit_session['content'] ?? '') ?></textarea>
            </fieldset>

            <fieldset class="form-group">
                <label for="objectives">Objectifs</label>
                <textarea name="objectives" id="objectives" rows="3"><?= htmlspecialchars($edit_session['objectives'] ?? '') ?></textarea>
            </fieldset>

            <div class="form-actions">
                <button type="submit" class="btn"><?= $edit_session ? 'Modifier' : 'Créer' ?> la session</button>
                <a href="/admin/training/program/<?= $training['slug'] ?>" class="btn secondary">Retour</a>
            </div>
        </form>
    </div>
</section>

<?php
include __DIR__ . '/_program_schedule.php'; ?>