<?php
if ($_POST) {
    foreach ($_POST['labels'] as $id => $label) {
        db()->prepare("UPDATE coproacademy SET label = ? WHERE id = ?")->execute([$label, $id]);
    }
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit;
}

$settings = db()->query("SELECT id, slug, label FROM coproacademy ORDER BY id")->fetchAll();
?>

<style>
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 10px;
        align-items: start;
        max-width: 800px;
    }

    .form-grid label {
        font-weight: bold;
        padding-top: 0.5em;
    }

    .form-grid textarea,
    .form-grid input[type="text"] {
        width: 100%;
        box-sizing: border-box;
    }

    .form-group {
        display: contents;
    }

    button {
        margin-top: 20px;
    }
</style>

<header class="page-header">
    <h1>Configuration Copro.Academy</h1>
    <div class="page-actions">
        <a href="/admin/site" class="btn secondary">Retour à la configuration</a>
    </div>
</header>

<form method="post">
    <?= csrf_field(3600) ?>

    <div class="form-grid">
        <?php foreach ($settings as $row): ?>
            <div class="form-group">
                <label for="field-<?= $row['id'] ?>"><?= htmlspecialchars($row['slug']) ?></label>
                <?php if (strlen($row['label']) > 100): ?>
                    <textarea id="field-<?= $row['id'] ?>" name="labels[<?= $row['id'] ?>]"><?= htmlspecialchars($row['label']) ?></textarea>
                <?php else: ?>
                    <input type="text" id="field-<?= $row['id'] ?>" name="labels[<?= $row['id'] ?>]" value="<?= htmlspecialchars($row['label']) ?>">
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <footer class="form-actions">
        <button type="submit" class="btn">Mettre à jour</button>
        <a href="/admin/faq" class="btn secondary">Annuler</a>
    </footer>

</form>