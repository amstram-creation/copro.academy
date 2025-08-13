<header class="page-header">
    <h1>Programme - <?= htmlspecialchars($training['label']) ?></h1>
    <nav class="page-actions">
        <a href="/admin/training" class="btn secondary">Retour aux formations</a>
        <a href="/admin/training/alter/<?= $training['slug'] ?>" class="btn secondary">Modifier formation</a>
        <a href="#add-session" class="btn" onclick="toggleSessionForm()">Nouvelle session</a>
    </nav>
</header>

<section class="program-overview">
    <div class="stats-grid">
        <div class="panel stat-card">
            <strong><?= count($sessions) ?></strong>
            <span>Sessions programmées</span>
        </div>
        <div class="panel stat-card">
            <strong><?= $training['duration_days'] ?></strong>
            <span>Jour<?= $training['duration_days'] > 1 ? 's' : '' ?></span>
        </div>
        <div class="panel stat-card">
            <strong><?= $total_duration ?>h</strong>
            <span>Durée totale</span>
        </div>
        <div class="panel stat-card">
            <strong><?= date('d/m/Y', strtotime($training['start_date'])) ?></strong>
            <span>Date de début</span>
        </div>
    </div>
</section>

<?php if ($edit_session || !empty($_GET['add'])): ?>

<?php endif; ?>

<?php include __DIR__ . '/_program_schedule.php'; ?>


<script>
    function closeSessionForm() {
        window.location.href = '/admin/training/program/<?= $training_id ?>';
    }

    function duplicateDay(day) {
        const targetDay = prompt(`Dupliquer le Jour ${day} vers quel jour ?`);
        if (targetDay && targetDay >= 1 && targetDay <= <?= $training['duration_days'] ?>) {
            // This would require a more complex implementation to duplicate all sessions
            alert('Fonctionnalité à implémenter');
        }
    }

    // Auto-generate slug from title
    document.getElementById('label')?.addEventListener('input', function() {
        const slugField = document.getElementById('slug');
        if (slugField && !slugField.value) {
            const day = document.getElementById('day_number').value;
            const slug = this.value.toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '') + '-j' + day;
            slugField.value = slug;
        }
    });

    // Time validation
    document.getElementById('time_end')?.addEventListener('change', function() {
        const startTime = document.getElementById('time_start').value;
        const endTime = this.value;

        if (startTime && endTime && endTime <= startTime) {
            alert('L\'heure de fin doit être après l\'heure de début');
            this.focus();
        }
    });
</script>