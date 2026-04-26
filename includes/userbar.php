<?php

declare(strict_types=1);

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/user_stats.php';

auth_bootstrap();

$barBest = null;
$barRank = null;
if (auth_is_logged_in()) {
    $uid = auth_user_id();
    $barBest = user_best_score($uid);
    $barRank = $barBest === null ? null : user_rank_by_best_score($uid);
}

?>
<div class="user-bar" role="navigation" aria-label="Account">
    <?php if (auth_is_logged_in()): ?>
        <span class="user-bar-name"><?= h(auth_username()) ?></span>
        <?php if (auth_is_admin()): ?>
            <a href="admindashboard.php" class="admin-badge" title="Administrator" aria-label="Administrator dashboard">&#9733;</a>
            <span class="user-bar-sep">|</span>
        <?php endif; ?>
        <?php if ($barBest === null): ?>
            <span class="user-bar-score" title="Complete a quiz to record a score">Best: &mdash;</span>
        <?php else: ?>
            <span class="user-bar-score" title="Your best score on any single quiz">Best: <?= (int) $barBest ?></span>
        <?php endif; ?>
        <span class="user-bar-sep">|</span>
        <?php if ($barRank === null): ?>
            <span class="user-bar-rank" title="Rank among all players by best score">Rank: &mdash;</span>
        <?php else: ?>
            <span class="user-bar-rank" title="Rank among all players by best score (1 is best)">Rank: #<?= (int) $barRank ?></span>
        <?php endif; ?>
        <span class="user-bar-sep">|</span>
        <a href="logout.php">Log out</a>
    <?php else: ?>
        <a href="login.php">Log in</a>
        <span class="user-bar-sep">|</span>
        <a href="register.php">Register</a>
    <?php endif; ?>
</div>
