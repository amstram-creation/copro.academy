<section class="program-schedule">
    <?php foreach ($program_by_day as $day => $day_sessions): ?>
        <div class="program-day" id="day-<?= $day ?>">
            <header class="day-header">
                <h2>Jour <?= $day ?></h2>
                <div class="day-actions">
                    <?php if (!empty($day_sessions)): ?>
                        <span class="session-count"><?= count($day_sessions) ?> session<?= count($day_sessions) > 1 ? 's' : '' ?></span>
                        <button type="button" onclick="duplicateDay(<?= $day ?>)" class="btn small tertiary">Dupliquer le jour</button>
                    <?php endif; ?>
                    <a href="/admin/training/session/<?= $training['slug'] ?>" class="btn small">Ajouter session</a>
                </div>
            </header>

            <?php if (empty($day_sessions)): ?>
                <section class="empty-day">
                    <p>Aucune session programmée pour ce jour</p>
                    <a href="/admin/training/session/<?= $training['slug'] ?>" class="btn">Créer la première session</a>
                </section>
            <?php else: ?>
                <div class="sessions-timeline">
                    <?php foreach ($day_sessions as $session): ?>
                        <article class="session-card">
                            <div class="session-time">
                                <time><?= date('H:i', strtotime($session['time_start'])) ?></time>
                                <span class="separator">-</span>
                                <time><?= date('H:i', strtotime($session['time_end'])) ?></time>
                                <small class="duration">
                                    <?php
                                    $duration = (strtotime($session['time_end']) - strtotime($session['time_start'])) / 60;
                                    echo round($duration) . 'min';
                                    ?>
                                </small>
                            </div>

                            <div class="session-content">
                                <h3><?= htmlspecialchars($session['label']) ?></h3>

                                <?php if ($session['description']): ?>
                                    <p class="session-description"><?= nl2br(htmlspecialchars($session['description'])) ?></p>
                                <?php endif; ?>

                                <?php if ($session['objectives']): ?>
                                    <div class="session-objectives">
                                        <strong>Objectifs :</strong>
                                        <p><?= nl2br(htmlspecialchars($session['objectives'])) ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="session-actions">
                                <a href="/admin/training/session/<?= $training['slug'] ?>/<?= $session['id'] ?>" class="btn small">Modifier</a>
                                <a href="/admin/delete/session/<?= $session['id'] ?>"
                                    onclick="return confirm('Supprimer cette session ?')"
                                    class="btn small danger">Supprimer</a>
                                <div class="dropdown">
                                    <button type="button" class="btn small secondary dropdown-toggle">•••</button>
                                    <div class="dropdown-menu">
                                        <?php for ($target_day = 1; $target_day <= $training['duration_days']; $target_day++): ?>
                                            <?php if ($target_day != $day): ?>
                                                <a href="/admin/training/session/duplicate/<?= $training['slug'] ?>/<?= $session['id'] ?>?to_day=<?= $target_day ?>" class="dropdown-item">
                                                    Dupliquer vers Jour <?= $target_day ?>
                                                </a>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                        <hr>

                                    </div>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</section>