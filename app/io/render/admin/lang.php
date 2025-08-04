<div class="page-header">
    <h1>Language Editor</h1>
    <div class="page-actions">
        <?php foreach ($languages as $file): $lang = basename($file, '.php'); ?>
            <a href="?lang=<?= $lang ?>"
                class="btn <?= $lang === $currentLang ? 'btn-primary' : 'btn-secondary' ?>">
                <?= strtoupper($lang) ?>
            </a>
        <?php endforeach; ?>
        <a href="/admin/site" class="btn secondary">Retour à la configuration</a>
    </div>
</div>

<form method="post" class="alter-form">
    <?= csrf_field(3600) ?>
    <div class="form-main">
        <?php foreach ($sections as $sectionName => $items): ?>
            <div class="meta-box" id="section-<?= e($sectionName) ?>">
                <header>
                    <h2><?= ucfirst($sectionName) ?></h2>
                </header>
                <?php foreach ($items as $item): ?>
                    <div class="form-group">
                        <label><?= $item['key'] ?></label>
                        <input type="text"
                            name="content[<?= e($item['key']) ?>]"
                            value="<?= e($item['value']) ?>"
                            class="form-control">
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <aside>
        <div class="meta-box panel">
            <header>
                <h2>Language Info</h2>
            </header>
            <dl class="stats-list">
                <dt>File</dt>
                <dd><code><?= $currentLang ?>.php</code></dd>
                <dt>Keys</dt>
                <dd><?= count($content ?? []) ?></dd>
                <dt>Sections</dt>
                <dd><?= count($sections ?? []) ?></dd>
            </dl>
        </div>

        <div class="meta-box panel">
            <header>
                <h2>Table of Contents</h2>
            </header>
            <ul class="toc-list">
                <?php foreach ($sections as $sectionName => $items): ?>
                    <li>
                        <a href="#section-<?= e($sectionName) ?>">
                            <?= ucfirst($sectionName) ?>
                            <span class="count">(<?= count($items) ?>)</span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </aside>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Save Changes</button>
    </div>
</form>