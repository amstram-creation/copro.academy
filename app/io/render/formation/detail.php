<div class="page-detail">
    <!-- Breadcrumb -->
    <nav class="breadcrumb" aria-label="<?= l('breadcrumb.aria_label') ?>">
        <ol>
            <li><a href="/"><?= l('breadcrumb.home') ?></a></li>
            <li><a href="/article"><?= l('breadcrumb.formations') ?></a></li>
            <li><span aria-current="page"><?= $training['label'] ?? '' ?></span></li>
        </ol>
    </nav>

    <!-- En-tête de formation -->
    <header class="formation-header">
        <div class="formation-badge"><?= $training['level_label'] ?? l('formation.default_title'); ?></div>
        <h1><?= $training['label'] ?? l('formation.default_title'); ?></h1>
        <p class="formation-subtitle"><?= $training['subtitle'] ?? l('formation.default_title'); ?></p>

        <div class="formation-key-info">
            <div class="info-item">
                <span class="icon">📅</span>
                <div>
                    <strong><?= l('formation.date_label') ?></strong>
                    <p><time datetime="<?= $training['start_date'] ?>"><?= $training['start_date'] ?></time></p>
                </div>
            </div>
            <div class="info-item">
                <span class="icon">⏱️</span>
                <div>
                    <strong><?= l('formation.duration_label') ?></strong>
                    <p><?= $training['duration_hours'] ?>h</p>
                </div>
            </div>
            <div class="info-item">
                <span class="icon">💰</span>
                <div>
                    <strong><?= l('formation.price_label') ?></strong>
                    <p><span class="price"><?= $training['price_ht'] ?></span> <?= l('formation.ht_label') ?></p>
                </div>
            </div>
            <div class="info-item">
                <span class="icon">👥</span>
                <div>
                    <strong><?= l('formation.places_label') ?></strong>
                    <p><?= $training['places_max'] ?> <?= l('formation.max_participants') ?></p>
                </div>
            </div>
        </div>
    </header>

    <!-- Image principale -->
    <figure class="formation-hero-image">
        <img src="/asset/image/formation/avatar/<?= e($training, 'slug'); ?>.webp" alt="<?= l('img.formation_alt') ?>" width="1200" height="400">
        <figcaption><?= $training['label'] ?? l('formation.default_title'); ?></figcaption>
    </figure>

    <!-- Contenu de la formation -->
    <div class="formation-content">
        <div class="content-main">
            <!-- Description -->
            <section class="formation-description">
                <h2><?= l('formation.description_title') ?></h2>
                <?= $training['content']; ?>
            </section>

            <!-- Objectifs -->
            <section class="formation-objectives">
                <!-- Corps de l'article -->
                <h2><?= l('formation.objectives_title') ?></h2>
                <p><?= $training['objectives'] ?></p>
            </section>
            <?php if ($training_sessions_by_day): ?>
                <!-- Programme détaillé -->
                <section class="formation-program">
                    <h2><?= l('formation.program_title') ?></h2>
                    <?php foreach ($training_sessions_by_day as $dayNum => $items): ?>
                        <div class="program-day">
                            <h3><?= l('formation.day') ?> <?= (int)$dayNum ?> : <?= l('formation.program.legal_foundations') ?></h3>
                            <div class="program-timeline">
                                <?php foreach ($items as $it): ?>
                                    <div class="timeline-item">
                                        <span class="time"><?= date('G\hi', strtotime($it['time_start'])) ?> - <?= date('G\hi', strtotime($it['time_end'])) ?></span>
                                        <div class="content">
                                            <h4><?= $it['label'] ?? '' ?></h4>
                                            <ul>
                                                <li><?= l('formation.content.legal_evolution') ?></li>
                                                <li><?= l('formation.content.civil_code') ?></li>
                                                <li><?= l('formation.content.recent_jurisprudence') ?></li>
                                            </ul>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>


                    <?php endforeach; ?>
                </section>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <aside class="formation-sidebar">
            <!-- Inscription -->
            <div class="inscription-card">
                <h3><?= l('formation.registration') ?></h3>
                <div class="price-info">
                    <span class="price"><?= $training['price_ht'] ?></span>
                    <span class="price-detail"><?= l('formation.vat_detail') ?></span>
                </div>
                <div class="total-price">
                    <strong><?= l('formation.total_ttc') ?>: <span class="price"><?= (float)($training['price_ht']) * 1.21 ?></span></strong>
                </div>
                <a href="/contact?sujet=sujet-formation" class="cta"><?= l('formation.register_now') ?></a>

                <div class="inscription-info">
                    <h4><?= l('formation.practical_info') ?></h4>
                    <ul>
                        <li><strong><?= l('formation.date_label') ?></strong> <time datetime="<?= $training['start_date'] ?>"><?= $training['start_date'] ?></time></li>
                        <li>📍 <strong><?= l('formation.location_label') ?></strong> <?= l('formation.location_brussels') ?></li>
                        <li>📍 <strongw><?= l('formation.location_label') ?></strong> <?= l('formation.location_brussels') ?></li>
                        <li>☕ <strong><?= l('formation.breaks_label') ?></strong> <?= $training['pause'] ?></li>
                        <li>🅿️ <strong><?= l('formation.parking_label') ?></strong> <?= $training['parking'] ?></li>
                    </ul>
                </div>
            </div>

            <!-- Formateur -->
            <div class="trainer-card">
                <h3><?= l('formation.your_trainer') ?></h3>
                <div class="trainer-info">
                    <img src="/asset/image/trainer/avatar/<?= $trainer['slug']; ?>.webp" alt="<?= l('formation.trainer_expert_alt') ?>" class="trainer-photo">
                    <div class="trainer-details">
                        <h4><?= $trainer['label'] ?? ''; ?></h4>
                        <p class="trainer-title"><?= $trainer['title'] ?? ''; ?></p>
                        <?php if (!empty($trainer['hire_date'])):
                            $hireDate = new DateTime($trainer['hire_date']);
                            $today    = new DateTime();
                            $diff     = $today->diff($hireDate);
                            $years    = $diff->y;
                            $unit     = ($years > 1) ? l('time.years') : l('time.year');
                        ?>
                            <p class="trainer-experience">
                                <?= l('formation.years_experience', $years, $unit); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
                <p><?= $trainer['bio']; ?></p>
            </div>

            <?php if (!empty($training['certification'])): ?>
                <!-- Certificat -->
                <div class="certificate-card">
                    <h3><?= l('formation.certification') ?></h3>
                    <div class="certificate-info">
                        <span class="certificate-icon">🏆</span>
                        <div>
                            <h4><?= l('formation.official_certificate') ?></h4>
                            <p><?= l('formation.certified_desc') ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Contact -->
            <div class="contact-card">
                <h3><?= l('formation.questions') ?></h3>
                <p><?= l('formation.team_available') ?></p>
                <div class="contact-methods">
                    <a href="tel:<?= viewport('coproacademy', 'telephone') ?>" class="contact-method">
                        <span class="icon">📞</span>
                        <?= viewport('coproacademy', 'telephone') ?>
                    </a>
                    <a href="mailto:<?= viewport('coproacademy', 'email') ?>" class="contact-method">
                        <span class="icon">✉️</span>
                        <?= viewport('coproacademy', 'email') ?>
                    </a>
                </div>
            </div>
        </aside>
    </div>
</div>
<?php
return function ($this_html, $args = []) {
    return ob_ret_get('app/io/render/layout.php', ($args ?? []) +  ['main' => $this_html, 'navbar__link__active' => 'formation'])[1];
};
