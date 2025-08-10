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
    <link rel="stylesheet" href="/static/css/0-foundation.css">
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
            <?php
            $navbar__link__active = $navbar__link__active ?? '';
            ?>
            <nav class="navbar__nav" role="navigation" aria-label="Navigation principale" id="main-nav">
                <a href="/" class="navbar__link <?= $navbar__link__active === '' ? 'navbar__link--active' : '' ?>"><?= l('nav.home') ?></a>
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
    <aside class="partners__logo">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 188.973 188.973">
            <path d="M34.016 94.486h60.472V34.012H34.016v60.474m60.472 60.471h60.473V94.483H94.488v60.474" fill="#4194d7"></path>
            <path d="M0 94.486h188.976M94.488-.003v188.976" fill="none" stroke="#231f20" stroke-width=".589" stroke-miterlimit="10"></path>
            <path d="M103.785 84.534h5.23v-42.36h-5.546v42.36h.316m19.265-23.007h-2.375V46.13h.986c5.646 0 8.706 1.249 8.706 7.85 0 6.284-2.8 7.546-7.316 7.546zm-.967-19.354h-6.927v.315l-.002 41.732v.314h5.516v-.314l.005-18.736h2.134c8.555 0 13.078-4.042 13.078-11.687 0-7.712-4.645-11.624-13.804-11.624m17.804 42.361h5.209v-42.36h-5.523v42.36h.314m-95.404 23.611h2.333c2.979 0 4.34 0 4.34 6.64 0 7.847-3.289 7.847-4.693 7.847h-1.98zm1.726 34.45h-1.726v-16.003h3.069c1.917 0 3.248.402 4.073 1.228 1.346 1.345 1.344 3.715 1.344 6.716 0 8.059-2.834 8.059-6.76 8.059zm7.185-18.471c2.318-1.222 3.281-4.057 3.281-9.52 0-7.106-2.996-10.416-9.427-10.416H38.96v42.361h8.65c9.753 0 10.88-6.564 10.88-11.504 0-7.7-2.376-9.934-5.096-10.921m8.642 22.426h5.202v-42.362h-5.517v42.361h.315m21.975-42.361l-.042.265-3.875 23.343-3.646-23.341-.042-.267h-5.53l.06.367 6.87 41.73.044.264h4.187l.047-.26 7.396-41.73.063-.371h-5.532" fill="#231f20"></path>
        </svg>
    </aside>

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
                    <a href="<?= viewport('coproacademy', 'facebook') ?>" aria-label="Facebook">
                        <img src="/static/image/facebook-svgrepo-com-3.svg" alt="Facebook">
                    </a>
                    <a href="<?= viewport('coproacademy', 'instagram') ?>" aria-label="Instagram">
                        <img src="/static/image/instagram-svgrepo-com-2.svg" alt="Instagram">
                    </a>
                    <a href="<?= viewport('coproacademy', 'linkedin') ?>" aria-label="LinkedIn">
                        <img src="/static/image/linkedin-svgrepo-com.svg" alt="LinkedIn">
                    </a>
                </div>
            </div>

            <div class="footer__section text-center">
                <img src="/static/image/copro.academy.logo.alpha.png" alt="<?= l('img.logo_alt') ?>" style="max-height: 160px; width: auto;">
            </div>
        </div>

        <div class="footer__separator"></div>

        <div class="footer__copyright">
            <div class="flex justify-center gap-lg flex-wrap mt-sm">
                <span><?= l('contact.email') ?> : <a href="mailto:<?= viewport('coproacademy', 'email') ?>"><?= viewport('coproacademy', 'email') ?></a></span>
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