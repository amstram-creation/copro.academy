<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Accueil' ?> - Copro Academy | Formations en gestion de copropriétés</title>
    <meta name="description"
        content="<?= $description ?? l('description') ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap"
        rel="stylesheet">

    <link rel="icon" type="image/png" href="/static/image/copro.academy.icon.alpha.png">

    <!-- Modular CSS -->
    <?= $prepend_css ?? '' ?>
    <link rel="stylesheet" href="/static/css/00-reset.css">
    <link rel="stylesheet" href="/static/css/01-variables.css">
    <link rel="stylesheet" href="/static/css/02-base.css">
    <link rel="stylesheet" href="/static/css/03-layout.css">
    <link rel="stylesheet" href="/static/css/04-components.css">
    <link rel="stylesheet" href="/static/css/05-utilities.css">
    <link rel="stylesheet" href="/static/css/06-pages.css">

    <?= $append_css ?? '' ?>
</head>

<body>
    <div class="skip-links">
        <a href="#main-content" class="skip-link"><?= l('a11y.skip_to_content') ?></a>
        <a href="#main-nav" class="skip-link"><?= l('a11y.skip_to_navigation') ?></a>
    </div>

    <!-- Navigation Header -->
    <header class="navbar" role="banner">
        <div class="navbar__container">
            <a href="/" class="navbar__logo-link" aria-label="<?= l('nav.back_to_home') ?>">
                <img src="/static/image/copro.academy.logo.alpha.png" alt="<?= viewport('coproacademy', 'label') ?> Logo" class="navbar__logo">
            </a>

            <button class="navbar__burger" aria-label="<?= l('nav.menu') ?>" aria-expanded="false">
                &#9776;
            </button>
            <nav class="navbar__nav" role="navigation" aria-label="Navigation principale" id="main-nav">
                <a href="/" class="navbar__link <?= empty($navbar__link__active) ? 'navbar__link--active' : '' ?>"><?= l('nav.home') ?></a>
                <a href="/article" class="navbar__link <?= $navbar__link__active === 'article' ? 'navbar__link--active' : '' ?>"><?= l('nav.articles') ?></a>
                <a href="/formation" class="navbar__link <?= $navbar__link__active === 'formation' ? 'navbar__link--active' : '' ?>"><?= l('nav.formation') ?></a>
                <a href="/contact" class="navbar__link <?= $navbar__link__active === 'contact' ? 'navbar__link--active' : '' ?>"><?= l('nav.contact') ?></a>
            </nav>
        </div>
    </header>

    <main id="main-content">
        <?= $main
            ?? '<section class="placeholder">
                <h2>Bienvenue chez Copro Academy</h2>
                <p>Le contenu de cette page est en cours de préparation. Revenez bientôt pour découvrir nos actualités et nos formations !</p>
             </section>'
        ?>
    </main>


    <footer class="footer" role="contentinfo">
        <div class="footer__content">
            <div class="footer__section">
                <h3><?= l('footer.navigation') ?></h3>
                <ul class="footer__links">
                    <li><a href="/"><?= l('nav.home') ?></a></li>
                    <li><a href="/article"><?= l('nav.articles') ?></a></li>
                    <li><a href="/formation"><?= l('nav.formation') ?></a></li>
                    <li><a href="/contact"><?= l('nav.contact') ?></a></li>
                </ul>
            </div>

            <div class="footer__section">
                <h3><?= l('footer.legal_info') ?></h3>
                <ul class="footer__links">
                    <li><a href="/legal/conditions-generales"><?= l('footer.general_conditions') ?></a></li>
                    <li><a href="/legal/politique-confidentialite"><?= l('footer.privacy_policy') ?></a></li>
                    <li><a href="/legal/mentions-legales"><?= l('footer.legal_mentions') ?></a></li>
                </ul>
            </div>

            <div class="footer__section">
                <h3><?= l('footer.follow_us') ?></h3>
                <div class="footer__social">
                    <a href="#" aria-label="Facebook">
                        <img src="/static/assets/logo_réseaux_sociaux/facebook-svgrepo-com-3.svg" alt="Facebook">
                    </a>
                    <a href="#" aria-label="Instagram">
                        <img src="/static/assets/logo_réseaux_sociaux/instagram-svgrepo-com-2.svg" alt="Instagram">
                    </a>
                    <a href="#" aria-label="LinkedIn">
                        <img src="/static/assets/logo_réseaux_sociaux/linkedin-svgrepo-com.svg" alt="LinkedIn">
                    </a>
                </div>
            </div>

            <div class="footer__section text-center">
                <img src="/static/assets/color1/full/white_logo_color1_background.png" alt="<?= l('img.logo_alt') ?>" style="max-height: 80px; width: auto;">
            </div>
        </div>

        <div class="footer__separator"></div>

        <div class="footer__copyright">
            <div class="flex justify-center gap-lg flex-wrap mt-sm">
                <span><?= l('contact.email') ?> : <a href="mailto:CoproAcademy@contact.be"><?= viewport('coproacademy', 'email') ?></a></span>
                <span><?= l('contact.phone') ?> : <a href="tel:<?= viewport('coproacademy', 'telephone') ?>"><?= viewport('coproacademy', 'telephone') ?></a></span>
                <span><?= l('contact.address') ?> : <?= nl2br(viewport('coproacademy', 'adresse')) ?></span>

            </div>
            <p>&copy; <?= date('Y') ?> <?= viewport('coproacademy', 'label') ?> - <?= l('all_rights_reserved') ?></p>
        </div>
    </footer>

    <?= $prepend_js ?? '' ?>
    <script src="/static/script.js"></script>
    <?= $append_js ?? '' ?>
</body>

</html>