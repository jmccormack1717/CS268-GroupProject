<?php

declare(strict_types=1);

require_once __DIR__ . '/auth.php';
auth_bootstrap();
require __DIR__ . '/userbar.php';
?>
<div class="header">
    <?php if (auth_is_logged_in()): ?>
        <a href="logout.php" class="tButton">Log out</a>
    <?php else: ?>
        <a href="login.php" class="tButton">Log in</a>
    <?php endif; ?>
    <br>
    <h1>Tech Trivia</h1>
    <ul class="button">
        <li class="button"><a href="index.php">Home</a></li>
        <li class="button"><a href="about.php">About</a></li>
        <li class="button"><a href="categories.php">Categories</a></li>
        <li class="button"><a href="easyquiz.php">Easy Quiz</a></li>
        <li class="button"><a href="mediumquiz.php">Medium Quiz</a></li>
        <li class="button"><a href="hardquiz.php">Hard Quiz</a></li>
        <li class="button"><a href="leaderboard.php">Leaderboard</a></li>
        <li class="button"><a href="contact.php">Contact</a></li>
    </ul>
</div>
