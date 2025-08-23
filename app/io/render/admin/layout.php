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

            // Map each textarea → its Quill
            const quillByTextarea = new WeakMap();

            document.querySelectorAll('form textarea').forEach((textarea) => {
                // Hide textarea *visually*, not with display:none
                textarea.classList.add('sr-only-input');

                // Create Quill container
                const quillContainer = document.createElement('div');
                quillContainer.className = 'wysiwyg';
                quillContainer.innerHTML = textarea.value || ''; // initial value

                // Insert after textarea
                textarea.parentNode.insertBefore(quillContainer, textarea.nextSibling);

                // Init Quill
                const quill = new Quill(quillContainer, {
                    theme: 'snow',
                    modules: {
                        toolbar: [
                            ['bold', 'italic', 'underline'],
                            [{
                                color: []
                            }],
                            ['link', 'blockquote'],
                            [{
                                list: 'ordered'
                            }, {
                                list: 'bullet'
                            }],
                            ['clean'],
                        ]
                    }
                });

                // Keep textarea value in sync on every change (so it's ready before submit)
                const syncToTextarea = () => {
                    const html = quill.root.innerHTML;
                    const plain = quill.getText().trim(); // ignores formatting
                    textarea.value = plain ? html : ''; // empty if only formatting/whitespace
                };
                quill.on('text-change', syncToTextarea);
                syncToTextarea(); // initial sync

                quillByTextarea.set(textarea, quill);

                // If browser flags the (hidden) textarea invalid, focus the Quill editor instead
                textarea.addEventListener('invalid', (e) => {
                    e.preventDefault(); // prevent native focus on hidden control
                    const q = quillByTextarea.get(textarea);
                    if (q) {
                        q.focus();
                        // optional: visual hint
                        q.container.classList.add('quill-error-ring');
                        setTimeout(() => q.container.classList.remove('quill-error-ring'), 1200);
                    }
                });
            });

            // Optional: on submit, final sync (harmless if already synced)
            document.querySelectorAll('form:has(.wysiwyg)').forEach((form) => {
                form.addEventListener('submit', () => {


                    form.querySelectorAll('textarea').forEach((ta) => {
                        const q = quillByTextarea.get(ta);
                        if (!q) return;
                        const html = q.root.innerHTML;
                        const plain = q.getText().trim();
                        ta.value = plain ? html : '';
                    });
                });


            });

            document.querySelectorAll('form').forEach((form) => {
                form.addEventListener('invalid', (e) => {
                    console.log('invalid', e);
                    e.preventDefault(); // stop native focus jump

                    const field = e.target;

                    // Remove any previous error text
                    let msg = field.parentNode.querySelectorAll('.field-error-text');
                    msg.forEach((msg) => msg.remove());

                    // Insert new error text (browser decides message via validationMessage)
                    msg = document.createElement('span');
                    msg.className = 'field-error-text';
                    msg.textContent = field.validationMessage;
                    field.parentNode.appendChild(msg);

                    // Wiggle all submit buttons
                    const submitButtons = form.querySelectorAll('button[type="submit"], input[type="submit"]');
                    submitButtons.forEach((btn) => {
                        btn.classList.add('submit-error');
                        setTimeout(() => btn.classList.remove('submit-error'), 1000);
                    });
                }, true);
            });
        });
    </script>
</body>

</html>