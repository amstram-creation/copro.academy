<?php
$level_to_class = function ($slug) {
    return [
        'niveau-debutant' => 'success',
        'niveau-intermediaire' => 'primary',
        'niveau-avance' => 'warning',
        'niveau-expert' => 'error',
    ][$slug] ?? 'primary';
}
?>
<h1 class="text-center mt-xl mb-lg text-4xl font-bold"><?= l('formation.title'); ?></h1>

<!-- Section Introduction -->
<section class="container mb-xl" aria-labelledby="intro-title">
    <div class="card p-xl text-center">
        <h2 id="intro-title" class="mb-md"><?= l('formation.subtitle'); ?></h2>
        <p class="mb-0"><?= l('formation.description'); ?></p>
    </div>
</section>

<!-- Liste des formations -->
<section class="container mb-xl" aria-labelledby="formations-title">
    <h2 id="formations-title" class="text-center mb-2xl"><?= l('formation.catalogue'); ?></h2>

    <!-- Filtres de formations -->
    <div class="filters mb-xl" role="group" aria-label="<?= l('formation.filters_aria') ?>">
        <button class="filter-btn active" data-type="all"><?= l('formation.catalogue.all'); ?></button>
        <?php foreach ($formation_niveau as $slug => $label): ?>
            <button class="filter-btn" data-type="<?= htmlspecialchars($slug) ?>"><?= htmlspecialchars($label) ?></button>
        <?php endforeach; ?>
    </div>

    <div class="grid grid-cols-auto gap-xl" id="formationsContainer">
        <?php foreach ($formation as $item): ?>
            <!-- Formation card -->
            <article class="card formation-card" data-type="<?= $item['level_slug'] ?? '' ?>" data-level="<?= $item['level_label'] ?? '' ?>">
                <img src="/asset/image/formation/avatar/<?= e($item, 'slug'); ?>.webp" alt="<?= l('formation.card_image_alt') ?>" loading="lazy"
                    class="card__image">
                <div class="card__body">

                    <?php if (isset($item['level_label'])): ?>
                        <div class="badge badge--<?= $level_to_class($item['level_slug']) ?> mb-md"><?= $item['level_label'] ?></div>
                    <?php endif; ?>

                    <h3 class="card__title"><?= $item['label'] ?? l('formation.no_title_fallback') ?></h3>

                    <div class="bg-gray-50 p-md rounded mb-md">
                        <?php if (isset($item['duration_days']) && isset($item['duration_hours'])): ?>
                            <p class="text-sm mb-xs"><strong><?= l('formation.duration_label') ?> :</strong> <?= $item['duration_days'] ?? '?' ?> <?= l('formation.days') ?> (<?= $item['duration_hours'] ?? '?' ?>h)</p>
                        <?php endif; ?>
                        <?php if (isset($item['start_date'])): ?>
                            <p class="text-sm mb-xs"><strong><?= l('formation.date_label') ?> :</strong> <?= $item['start_date'] ?? '?' ?></p>
                        <?php endif; ?>
                        <?php if (isset($item['price_ht'])): ?>
                            <p class="text-sm mb-0"><strong><?= l('formation.price_label') ?> :</strong> <?= $item['price_ht'] ?? '?' ?>€ <?= l('formation.ht_label') ?></p>
                        <?php endif; ?>
                    </div>

                    <p class="card__content"><?= $item['content'] ?? '' ?></p>

                    <div class="formation-objectives mb-lg">
                        <h4 class="text-primary mb-sm"><?= l('formation.objectives_label') ?> :</h4>
                        <?php $objectives = explode(';', $item['objectives'] ?? '') ?>
                        <ul class="text-sm">
                            <?php foreach ($objectives as $objective): ?>
                                <li><?= trim($objective) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <a href="/formation/detail/<?= $item['slug'] ?>" class="btn btn--primary w-full"><?= l('formation.view_details') ?></a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<?php require('app/io/render/_partial/benefit.php'); ?>

<!-- Section CTA Formation -->
<section class="newsletter wide">
    <div class="container text-center">
        <h2 class="newsletter__title"><?= l('formation.cta_title') ?></h2>
        <p class="newsletter__description"><?= l('formation.cta_description') ?></p>
        <a href="/contact?sujet=sujet-formation" class="btn btn--primary btn--lg"><?= l('formation.request_info') ?></a>
    </div>
</section>

<?php
return function ($this_html, $args = []) {
    return ob_ret_get('app/io/render/layout.php', ($args ?? []) +  ['main' => $this_html, 'navbar__link__active' => 'formation'])[1];
};
