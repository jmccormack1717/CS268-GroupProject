<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';
auth_bootstrap();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Home</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php require __DIR__ . '/includes/userbar.php'; ?>
    <div id="container">
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
                <li class="button"><a href="about.html">About</a></li>
                <li class="button"><a href="categories.html">Categories</a></li>
                <li class="button"><a href="easyquiz.php">Easy Quiz</a></li>
                <li class="button"><a href="mediumquiz.php">Medium Quiz</a></li>
                <li class="button"><a href="hardquiz.php">Hard Quiz</a></li>
                <li class="button"><a href="leaderboard.php">Leaderboard</a></li>
                <li class="button"><a href="contact.html">Contact</a></li>
            </ul>
        </div>
        <div class="main">
            <div class="content">
                <p><b>Home</b></p>
            </div>
        </div>
        <div class="footer">
            <p>This is the footer</p>
        </div>
    </div>
</body>
</html>
