<!-- Hero Section avec Carousel -->
<section class="hero wide" aria-label="Présentation principale">
    <?php foreach ($hero_slides as $index => $slide): ?>
        <img src="<?= $slide ?>" class="hero__image <?= $index === 0 ? 'hero__image--active' : '' ?>" alt="Formation en gestion de copropriété" loading="eager">
    <?php endforeach; ?>

    <div class="hero__overlay" aria-hidden="true"></div>

    <div class="hero__content">
        <h1 class="hero__title"><?= l('home.hero_title'); ?></h1>
        <h2 class="hero__subtitle"><?= l('home.hero_subtitle'); ?></h2>
        <p class="hero__description"><?= l('home.hero_description'); ?></p>

        <div class="hero__actions">
            <a href="/formation" class="btn btn--primary btn--lg">Découvrir nos formations</a>
            <a href="/article" class="btn btn--secondary btn--lg">Actualités & Événements</a>
        </div>
    </div>
</section>


<!-- Section Services -->
<section class="container mt-3xl" aria-labelledby="services-title">
    <h2 id="services-title" class="text-center mb-2xl">Nos Services</h2>

    <div class="grid grid-cols-auto gap-xl">
        <?php foreach ($service as $item) : ?>
            <article class="card">
                <img src="/asset/image/service/avatar/<?= $item['id'] ?>.webp" alt="<?= $item['label'] ?? 'Service' ?>" loading="lazy" class="card__image">
                <div class="card__body">
                    <h3 class="card__title"><?= $item['label'] ?? 'Service' ?></h3>
                    <p class="card__content"><?= $item['content'] ?? 'Description du service' ?></p>
                    <a href="<?= $item['link'] ?? '/contact' ?>" class="btn btn--primary">En savoir plus</a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<!-- Articles récents -->
<section class="container mt-3xl" aria-labelledby="recent-articles">
    <h2 id="recent-articles" class="text-center mb-2xl">Articles & Événements récents</h2>

    <div class="grid grid-cols-auto gap-xl">
        <?php foreach ($articles_events as $article) : ?>
            <article class="card">
                <?php if ($article['featured'] ?? false) : ?>
                    <div class="badge badge--error" style="position: absolute; top: 1rem; right: 1rem; z-index: 1;">À la
                        une</div>
                <?php endif; ?>
                <img src="/asset/image/article/avatar/<?= $article['slug'] ?>.webp" alt="Nouvelle réglementation copropriété 2025" loading="lazy"
                    class="card__image">
                <div class="card__body">
                    <div class="article-meta">
                        <span class="article-category"><?= $article['category_label'] ?? '' ?></span>
                        <time class="article-date" datetime="<?= $article['enabled_at'] ?? '' ?>"><?= $article['enabled_at'] ?? '' ?></time>
                    </div>
                    <h3 class="card__title"><?= $article['label'] ?? 'Titre manquant' ?></h3>
                    <p class="card__content"><?= $article['summary'] ?? 'Titre manquant' ?></p>
                    <div class="flex flex-wrap gap-sm mb-lg">
                        <span class="tag">Charges</span>
                        <span class="tag">Législation</span>
                        <span class="tag">2025</span>
                    </div>
                    <a href="/article/<?= $article['slug'] ?>" class="btn btn--primary">Lire l'article complet</a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<?php require('app/io/render/_partial/faq.php'); ?>
<?php require('app/io/render/_partial/benefit.php'); ?>
<?php require('app/io/render/_partial/newsletter.php'); ?>

<?php
return function ($this_html, $args = []) {
    return ob_ret_get('app/io/render/layout.php', ($args ?? []) +  ['main' => $this_html, 'navbar__link__active' => ''])[1];
};
