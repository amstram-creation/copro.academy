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
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
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
            <a href="/" target="_blank">Voir le site</a>
            <a href="/admin"><?= htmlspecialchars(auth()) ?></a>
            <a href="/logout">Déconnexion</a>
        </div>
    </header>
    <main class="admin-main">
        <?= $main ?? ''; ?>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
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
        import hideEmojiModal from '/asset/js/emojis-unicode.js';
        import formatPubDates from '/asset/js/format.js';

        document.addEventListener('DOMContentLoaded', () => {
            const labelInput = document.querySelector('main form input[name="label"]');
            const slugInput = document.querySelector('main form input[name="slug"]');
            if (labelInput && slugInput) {
                labelInput.addEventListener('input', () => {
                    slugInput.value = slugify(labelInput.value);
                });
            }

            dropzones('.drop-zone');
            hideEmojiModal();
            formatPubDates('time[datetime]');
            // Replace textareas with Quill editors
            document.querySelectorAll('form textarea').forEach((textarea) => {
                // Hide original textarea
                textarea.style.display = 'none';

                // Create Quill container
                const quillContainer = document.createElement('div');
                quillContainer.className = 'wysiwyg';
                quillContainer.innerHTML = textarea.value;

                // Insert after textarea
                textarea.parentNode.insertBefore(quillContainer, textarea.nextSibling);

                // Initialize Quill
                const quill = new Quill(quillContainer, {
                    theme: 'snow',
                    modules: {
                        toolbar: [
                            ['bold', 'italic', 'underline'],
                            [{
                                'color': []
                            }],
                            ['link', 'blockquote'],
                            [{
                                'list': 'ordered'
                            }, {
                                'list': 'bullet'
                            }],
                            ['clean'],
                        ]
                    }
                });
            });

            // Add single submit listener per form
            document.querySelectorAll('form:has(.wysiwyg)').forEach((form) => {
                form.addEventListener('submit', (e) => {
                    // e.preventDefault(); // Prevent submission for debugging
                    form.querySelectorAll('textarea').forEach((textarea) => {
                        console.log('Submitting form with WYSIWYG editor');

                        const fieldset = textarea.closest('fieldset');
                        const content = fieldset.querySelector('.ql-editor').innerHTML;
                        console.log('Content:', content);
                        textarea.value = content.replace(/<[^>]*>/g, '').trim() ? content : '';
                        console.log('Updated textarea value:', textarea.value);
                    });
                });
            });
        });
    </script>
</body>

</html>