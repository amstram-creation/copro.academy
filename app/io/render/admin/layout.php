<?php
extract($io ?? []);
$user = auth();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Copro Academy Admin') ?></title>
    <link rel="stylesheet" href="/asset/css/admin/variables.css">
    <link rel="stylesheet" href="/asset/css/admin/base.css">
    <link rel="stylesheet" href="/asset/css/admin/utilities.css">
    <link rel="stylesheet" href="/asset/css/admin/layout.css">
    <link rel="stylesheet" href="/asset/css/admin/components.css">
    <link rel="stylesheet" href="/asset/css/admin/emojis-unicode.css">

    <meta name="robots" content="noindex,nofollow">
    <?php if (isset($css)) : ?>
        <link rel="stylesheet" href="<?= htmlspecialchars($css) ?>">
    <?php endif; ?>
</head>

<body class="admin">
    <header class="admin-header">
        <div class="admin-nav">
            <a href="/admin" class="logo">Copro Academy</a>

            <nav>
                <a href="/admin/home">Accueil</a>
                <a href="/admin/article">Articles</a>
                <a href="/admin/event">Événements</a>
                <a href="/admin/training">Formations</a>
                <a href="/admin/trainer">Formateurs</a>
                <!-- <a href="/admin/contact">Contact</a> -->
                <a href="/admin/site">Site</a>
            </nav>
        </div>
        <div class="admin-user">
            <span><?= htmlspecialchars(auth()) ?></span>
            <a href="/logout">Déconnexion</a>
        </div>
    </header>
    <main class="admin-main">
        <?= $main ?? ''; ?>
    </main>
    <script nonce="<?= csp_nonce() ?>">
        // Admin UI interactions
        document.addEventListener('click', e => {
            if (e.target.matches('[data-confirm]')) {
                if (!confirm(e.target.dataset.confirm)) {
                    e.preventDefault();
                }
            }
        });
    </script>
    <script type="module">
        import slugify from '/asset/js/slug.js';
        import dropzones from '/asset/js/dropzone.js';
        import init from '/asset/js/emojis-unicode.js';

        document.addEventListener('DOMContentLoaded', () => {
            const labelInput = document.querySelector('main form input[name="label"]');
            const slugInput = document.querySelector('main form input[name="slug"]');
            if (labelInput && slugInput) {
                labelInput.addEventListener('input', () => {
                    slugInput.value = slugify(labelInput.value);
                });
            }

            dropzones('.drop-zone');
            init();
        });
    </script>
</body>

</html>