<header class="page-header">
    <h1>Demande de contact #<?= $contact['id'] ?></h1>
    <div class="page-actions">
        <a href="/admin/contact" class="btn secondary">← Retour à la liste</a>
    </div>
</header>

<div class="contact-detail">
    <section class="panel contact-info">
        <header>
            <h2>Informations</h2>
        </header>
        <?php //vd($contact, 'contact'); ?>
        <dl class="info-list">
            <dt>Nom</dt>
            <dd><?= htmlspecialchars($contact['label']) ?></dd>

            <dt>Email</dt>
            <dd><a href="mailto:<?= htmlspecialchars($contact['email']) ?>"><?= htmlspecialchars($contact['email']) ?></a></dd>

            <?php if ($contact['phone']): ?>
                <dt>Téléphone</dt>
                <dd><a href="tel:<?= htmlspecialchars($contact['phone']) ?>"><?= htmlspecialchars($contact['phone']) ?></a></dd>
            <?php endif; ?>

            <?php if ($contact['company']): ?>
                <dt>Société</dt>
                <dd><?= htmlspecialchars($contact['company']) ?></dd>
            <?php endif; ?>

            <dt>Sujet</dt>
            <dd><?= htmlspecialchars($contact['subject_label'] ?? 'Non classé') ?></dd>

            <dt>Message</dt>
            <dd><?= htmlspecialchars($contact['content'] ?? '') ?></dd>

            <dt>Statut</dt>
            <dd><span class="status <?= strtolower($contact['status_label'] ?? 'unknown') ?>"><?= htmlspecialchars($contact['status_label'] ?? 'Inconnu') ?></span></dd>

            <dt>Consentement</dt>
            <dd><span class="status <?= $contact['consent'] ? 'active' : 'inactive' ?>"><?= $contact['consent'] ? 'Accepté' : 'Refusé' ?></span></dd>

            <dt>Date de création</dt>
            <dd><time datetime="<?= $contact['created_at'] ?>"><?= date('d/m/Y H:i', strtotime($contact['created_at'])) ?></time></dd>

            <?php if ($contact['updated_at']): ?>
                <dt>Dernière modification</dt>
                <dd><time datetime="<?= $contact['updated_at'] ?>"><?= date('d/m/Y H:i', strtotime($contact['updated_at'])) ?></time></dd>
            <?php endif; ?>

            <?php if ($contact['revoked_at']): ?>
                <dt>Révoqué le</dt>
                <dd><time datetime="<?= $contact['revoked_at'] ?>"><?= date('d/m/Y H:i', strtotime($contact['revoked_at'])) ?></time></dd>
            <?php endif; ?>
        </dl>
    </section>

    <?php if ($contact['content']): ?>
        <section class="panel contact-message">
            <header>
                <h2>Message</h2>
            </header>
            <div class="message-content">
                <?= nl2br(htmlspecialchars($contact['content'])) ?>
            </div>
        </section>
    <?php endif; ?>
</div>

<style>
    .contact-detail {
        display: grid;
        gap: var(--space-lg);
    }

    .info-list {
        display: grid;
        gap: var(--space-sm);
        grid-template-columns: auto 1fr;
        column-gap: var(--space-md);
    }

    .info-list dt {
        font-weight: 600;
        color: var(--primary-blue);
        font-size: var(--text-sm);
    }

    .info-list dd {
        font-size: var(--text-sm);
        color: var(--text-dark);
        margin: 0;
    }

    .message-content {
        line-height: 1.6;
        white-space: pre-wrap;
    }
</style>