<?php

declare(strict_types=1);

require_once __DIR__ . '/auth.php';

auth_bootstrap();

?>
<div class="user-bar" role="navigation" aria-label="Account">
    <?php if (auth_is_logged_in()): ?>
        <span class="user-bar-name"><?= h(auth_username()) ?></span>
        <?php if (auth_is_admin()): ?>
            <a href="admindashboard.php" class="admin-badge" title="Administrator" aria-label="Administrator dashboard">&#9733;</a>
            <span class="user-bar-sep">|</span>
        <?php endif; ?>
        <a href="logout.php">Log out</a>
    <?php else: ?>
        <a href="login.php">Log in</a>
        <span class="user-bar-sep">|</span>
        <a href="register.php">Register</a>
    <?php endif; ?>
</div>
