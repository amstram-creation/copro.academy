<div class="page-header">
    <h1>Éditeur de textes</h1>
    <div class="page-actions">
        <?php foreach ($languages as $lang): ?>
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
                        <input type="text" name="content[<?= e($item['key']) ?>]" value="<?= e($item['value']) ?>" class="form-control">
                    </div>
                <?php endforeach; ?>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>

        <?php endforeach; ?>
    </div>

    <aside>
        <div class="meta-box panel">
            <header>
                <h2>Informations sur la langue</h2>
            </header>
            <dl class="stats-list">
                <dt>Code</dt>
                <dd><code><?= $currentLang ?></code></dd>
                <dt>Clés</dt>
                <dd><?= count($content ?? []) ?></dd>
                <dt>Sections</dt>
                <dd><?= count($sections ?? []) ?></dd>
            </dl>
        </div>

        <div class="meta-box panel">
            <header>
                <h2>Table des matières</h2>
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

</form>