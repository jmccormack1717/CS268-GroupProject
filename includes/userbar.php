<?php

declare(strict_types=1);

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/user_stats.php';

auth_bootstrap();

$barRank = null;
if (auth_is_logged_in()) {
    $uid = auth_user_id();
    $best = user_best_score($uid);
    $barRank = $best === null ? null : user_rank_by_best_score($uid);
}

?>
<div class="user-bar" role="navigation" aria-label="Account">
    <?php if (auth_is_logged_in()): ?>
        <span class="user-bar-name"><?= h(auth_username()) ?></span>
        <?php if ($barRank === null): ?>
            <span class="user-bar-rank-inline user-bar-muted" title="No rank until you complete a quiz">-</span>
        <?php else: ?>
            <span class="user-bar-rank-inline" title="Rank from your best single quiz; lower numbers are better">#<?= (int) $barRank ?></span>
        <?php endif; ?>
        <?php if (auth_is_admin()): ?>
            <span class="user-bar-sep">|</span>
            <a href="admindashboard.php" class="admin-badge" title="Administrator" aria-label="Administrator dashboard">&#9733;</a>
        <?php endif; ?>
    <?php else: ?>
        <a href="login.php">Log in</a>
        <span class="user-bar-sep">|</span>
        <a href="register.php">Register</a>
    <?php endif; ?>
</div>
