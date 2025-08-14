<h1><?= l('articles.title') ?></h1>

<!-- Section intro -->
<section>
    <div class="card p-xl text-center mb-xl">
        <h2 class="mb-md"><?= l('articles.stay_informed') ?></h2>
        <p class="mb-0"><?= l('articles.description') ?></p>
    </div>
</section>

<!-- Filtres et options d'affichage -->
<div class="filters-container">
    <div class="filters" role="group" aria-label="<?= l('articles.filters_aria') ?>">
        <!-- Progressive enhancement : liens par défaut, transformés en boutons par JS -->
        <a href="#" class="filter-btn active" data-type="all"><?= l('articles.filter.all') ?></a>
        <a href="#articles" class="filter-btn" data-type="article"><?= l('articles.filter.articles') ?></a>
        <a href="#events" class="filter-btn" data-type="event"><?= l('articles.filter.events') ?></a>
    </div>

    <!-- Controls cachés par défaut, montrés par JS -->
    <div class="view-controls" id="viewControls" style="display: none;">
        <button id="gridView" class="view-btn active" title="<?= l('articles.view.grid_title') ?>" aria-label="<?= l('articles.view.grid_aria') ?>">
            <span>⊞</span>
        </button>
        <button id="listView" class="view-btn" title="<?= l('articles.view.list_title') ?>" aria-label="<?= l('articles.view.list_aria') ?>">
            <span>☰</span>
        </button>
    </div>
</div>

<!-- Conteneur des articles -->
<section class="articles-section" aria-labelledby="articles-title">
    <h2 id="articles-title" class="sr-only"><?= l('articles.content_list') ?></h2>

    <div class="masonry" id="articlesContainer">
        <?php foreach ($articles_events as $i => $item): ?>
            <article class="card <?= ($i === 0) ? 'card-wide featured' : '' ?>" data-type="<?= e($item, 'type') ?>" data-date="2025-01-15">
                <?php if ($item['featured']): ?>
                    <div class="article-badge featured-badge"><?= l('articles.badge.featured') ?></div>
                <?php elseif ($item['type'] === 'event'): ?>
                    <div class="article-badge event-badge"><?= l('articles.badge.event') ?></div>
                <?php elseif ($item['type'] === 'webinar'): ?>
                    <div class="article-badge webinar-badge"><?= l('articles.badge.webinar') ?></div>
                <?php endif; ?>

                <img src="/asset/image/<?= e($item, 'type') ?>/avatar/<?= e($item, 'slug') ?>.webp" alt="<?= e($item, 'title') ?>" loading="lazy">
                <div class="card-content">
                    <div class="article-meta">
                        <span class="article-category"><?= e($item, 'category_label') ?></span>
                        <time datetime="<?= e($item, 'unified_date') ?>"><?= e($item, 'unified_date') ?></time>
                    </div>
                    <h3><?= e($item, 'label') ?></h3>
                    <p><?= $item['content'] ?></p>

                    <?php if ($item['type'] === 'event' || $item['type'] === 'webinar'): ?>
                        <div class="event-details">
                            <?php if (isset($item['event_date'])): ?>
                                <p><strong><?= l('articles.event.date_label') ?></strong> <time datetime><?= e($item, 'event_date') ?></time></p>
                            <?php endif; ?>
                            <?php if (isset($item['duration_minutes'])): ?>
                                <p><strong><?= l('articles.event.duration_label') ?></strong> <?= e($item, 'duration_minutes') ?> <?= l('articles.event.minutes') ?></p>
                            <?php endif; ?>
                            <?php if (isset($item['places_max'])): ?>
                                <p><strong><?= l('articles.event.places_label') ?></strong> <?= e($item, 'places_max') ?></p>
                            <?php endif; ?>
                            <?php if (isset($item['price_ht'])): ?>
                                <p><strong><?= l('articles.event.price_label') ?></strong> <?= e($item, 'price_ht') ?> €</p>
                            <?php endif; ?>
                        </div>
                        <a href="/contact?sujet=sujet-event#contact-form" class="cta"><?= l('articles.view_event') ?></a>
                    <?php else: ?>
                        <div class="article-tags">
                            <span class="tag"><?= l('articles.tag.charges') ?></span>
                            <span class="tag"><?= l('articles.tag.legislation') ?></span>
                            <span class="tag">2025</span>
                        </div>
                        <a href="/article/detail/<?= e($item, 'slug'); ?>" class="cta"><?= l('articles.read_full') ?></a>
                    <?php endif; ?>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<!-- Bouton charger plus -->
<button class="load-more" id="loadMore" aria-label="<?= l('articles.load_more_aria') ?>">
    <span class="load-text"><?= l('articles.load_more') ?></span>
    <span class="load-loading" style="display: none;"><?= l('articles.loading') ?></span>
</button>

<?php require('app/io/render/_partial/newsletter.php'); ?>

<?php
return function ($this_html, $args = []) {
    return ob_ret_get('app/io/render/layout.php', ($args ?? []) +  ['main' => $this_html, 'navbar__link__active' => 'article'])[1];
};
