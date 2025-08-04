<?php
$callback = isset($_GET['message']);
$success = $callback && $_GET['message'] === 'succes';
?>

<h1><?= l('contact.title'); ?></h1>

<!-- Section reacting to ?message=success or other message -->
<section>
    <div class="card <?= $callback && !$success ? 'warning-box' : '' ?> p-xl text-center mb-xl">
        <?php if (isset($_GET['message'])): ?>
            <?php $key = $_GET['message'] === 'succes' ? 'success' : 'error'; ?>
            <h2 class="mb-md"><?= l('contact.form.' . $key . '.title'); ?></h2>
            <?php if ($_GET['message'] === 'succes'): ?>
                <p class="mb-0"> <?= l('contact.form.' . $key . '.text'); ?></p>
            <?php else: ?>
                <p class="mb-0"> <?= l('contact.form.' . $key . '.text'); ?></p>
                <p>(<?= htmlspecialchars($_GET['message'], ENT_QUOTES, 'UTF-8'); ?>)</p>
            <?php endif; ?>
        <?php else: ?>

            <!-- Section intro -->
            <h2 class="mb-md"><?= l('contact.subtitle'); ?></h2>
            <p class="mb-0"><?= l('contact.description'); ?></p>
        <?php endif; ?>
    </div>
</section>



<!-- Informations de contact -->
<section aria-labelledby="contact-info-title">
    <h2 id="contact-info-title" class="text-center mb-2xl"><?= l('contact.section.info'); ?></h2>

    <div class="contact-cards">
        <div class="contact-card">
            <div class="contact-card__icon">📧</div>
            <h3 class="contact-card__title"><?= l('contact.email'); ?></h3>
            <p><a href="mailto:<?= viewport('coproacademy', 'email') ?>"><?= viewport('coproacademy', 'email') ?></a></p>
            <p class="mb-0"><?= l('contact.email.response_time', viewport('coproacademy', 'email-response-time')) ?></p>
        </div>

        <div class="contact-card">
            <div class="contact-card__icon">📞</div>
            <h3 class="contact-card__title"><?= l('contact.phone'); ?></h3>
            <p><a href="tel:<?= viewport('coproacademy', 'telephone') ?>"><?= viewport('coproacademy', 'telephone') ?: 'Cette information est indisponible' ?></a></p>
            <p class="mb-0"><?= viewport('coproacademy', 'telephone-hours') ?: 'Cette information est indisponible' ?></p>
        </div>

        <div class="contact-card">
            <div class="contact-card__icon">📍</div>
            <h3 class="contact-card__title"><?= l('contact.address') ?></h3>
            <p><?= nl2br(viewport('coproacademy', 'adresse') ?: 'Cette information est indisponible') ?></p>
            <p class="mb-0"><?= l('contact.address.note') ?></p>
        </div>
    </div>
</section>

<!-- Formulaire de contact -->
<section aria-labelledby="form-title">
    <div class="card">

        <form method="POST" class="p-xl" style="width:100%; max-width: 800px; margin: 0 auto;">
            <?= csrf_field(3600) ?>

            <h2 id="form-title" class="text-center mb-xl"><?= l('contact.section.form'); ?></h2>


            <!--  Groupe : Informations personnelles -->
            <fieldset class="form-fieldset">
                <legend class="form-legend"><?= l('contact.form.personal_info'); ?></legend>

                <div class="grid grid-cols-2 gap-lg mobile:grid-cols-1">
                    <div class="form-group">
                        <label for="label" class="form-label">
                            <?= l('contact.form.name'); ?>
                            <span class="required-indicator" aria-label="obligatoire">*</span>
                        </label>
                        <input type="text" id="label" name="label" required class="form-input"
                            aria-describedby="nom-error nom-help" autocomplete="name">
                        <span class="form-help" id="nom-help"><?= l('contact.form.name.help'); ?></span>
                        <span class="form-error" id="nom-error" role="alert" aria-live="polite"></span>
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">
                            <?= l('contact.form.email'); ?>
                            <span class="required-indicator" aria-label="obligatoire">*</span>
                        </label>
                        <input type="email" id="email" name="email" required class="form-input"
                            aria-describedby="email-error email-help" autocomplete="email">
                        <span class="form-help" id="email-help"><?= l('contact.form.email.help'); ?></span>
                        <span class="form-error" id="email-error" role="alert" aria-live="polite"></span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-lg mobile:grid-cols-1">
                    <div class="form-group">
                        <label for="telephone" class="form-label"><?= l('contact.form.phone'); ?></label>
                        <input type="tel" id="telephone" name="telephone" class="form-input"
                            aria-describedby="telephone-help" autocomplete="tel">
                        <span class="form-help" id="telephone-help"><?= l('contact.form.phone.help'); ?></span>
                    </div>

                    <div class="form-group">
                        <label for="entreprise" class="form-label"><?= l('contact.form.entreprise'); ?></label>
                        <input type="text" id="entreprise" name="entreprise" class="form-input" autocomplete="organization">
                    </div>
                </div>
            </fieldset>

            <!--  Groupe : Demande -->
            <fieldset class="form-fieldset">
                <legend class="form-legend"><?= l('contact.form.request.legend'); ?></legend>

                <div class="form-group">
                    <label for="sujet" class="form-label">
                        <?= l('contact.form.request.subject'); ?>
                        <span class="required-indicator" aria-label="obligatoire">*</span>
                    </label>
                    <select id="sujet" name="sujet" required class="form-input"
                        aria-describedby="sujet-error">
                        <option value=""><?= l('contact.form.request.subject.default'); ?></option>
                        <?php foreach ($subjects as $slug => $label) :
                            $selected = (isset($subject) && $subject === $slug) ? ' selected' : '';
                        ?>
                            <option value="<?= e($slug) ?>" <?= $selected; ?>><?= e($label) ?></option>
                        <?php endforeach; ?>

                    </select>
                    <span class="form-error" id="sujet-error" role="alert" aria-live="polite"></span>
                </div>

                <div class="form-group">
                    <label for="content" class="form-label">
                        <?= l('contact.form.message'); ?>
                        <span class="required-indicator" aria-label="obligatoire">*</span>
                    </label>
                    <textarea id="content" name="content" rows="6" required class="form-input"
                        aria-describedby="message-error message-help"
                        placeholder="<?= l('contact.form.message.placeholder'); ?>"></textarea>
                    <span class="form-help" id="message-help"><?= l('contact.form.message.help'); ?></span>
                    <span class="form-error" id="message-error" role="alert" aria-live="polite"></span>
                </div>
            </fieldset>

            <!--  Groupe : Consentement -->
            <fieldset class="form-fieldset">
                <legend class="form-legend"><?= l('contact.consent.legend'); ?></legend>

                <div class="form-group">
                    <label class="checkbox">
                        <input type="checkbox" id="consent" name="consent" required class="checkbox__input"
                            aria-describedby="consent-error consent-help">
                        <span class="checkbox__mark" aria-hidden="true"></span>
                        <span class="checkbox__label">
                            <?= l('contact.consent.valid'); ?>
                            <span class="required-indicator" aria-label="obligatoire">*</span>
                        </span>
                    </label>
                    <span class="form-help" id="consent-help">
                        <?= l('contact.consent.help', '/legal/politique-confidentialite'); ?>
                    </span>
                    <span class="form-error" id="consent-error" role="alert" aria-live="polite"></span>
                </div>
            </fieldset>

            <div class="text-center mt-xl">
                <button type="submit" class="btn btn--primary btn--lg" aria-describedby="submit-help">
                    <span class="btn-text"><?= l('contact.form.submit'); ?></span>
                    <span class="btn-loading hidden" aria-hidden="true"><?= l('contact.submit_sending'); ?></span>
                </button>
                <span id="submit-help" class="block mt-md text-gray-500">
                    <?= l('contact.form.submit.help'); ?>
                </span>
            </div>
        </form>
    </div>

</section>

<?php require('app/io/render/_partial/faq.php') ?>

<!-- CTA final -->
<section class="newsletter wide">
    <div class="container">
        <h2 class="newsletter__title"><?= l('contact.cta.ready_to_start'); ?></h2>
        <p class="newsletter__description"><?= l('contact.cta.ready_description'); ?></p>
        <div class="flex gap-lg justify-center flex-wrap">
            <a href="tel:<?= viewport('coproacademy', 'telephone') ?>" class="btn btn--primary btn--lg"><?= l('contact.cta.call_now'); ?></a>
            <a href="mailto:<?= viewport('coproacademy', 'email') ?>" class="btn btn--secondary btn--lg"><?= l('contact.cta.send_email'); ?></a>
        </div>
    </div>
</section>

<?php
return function ($this_html, $args = []) {
    return ob_ret_get('app/io/render/layout.php', ($args ?? []) + ['main' => $this_html, 'navbar__link__active' => 'contact'])[1];
};
