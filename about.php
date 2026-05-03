<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';
auth_bootstrap();

$pageTitle = 'About';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?= h($pageTitle) ?></title>
    <?php require __DIR__ . '/includes/head_assets.php'; ?>
</head>
<body>
    <div id="container">
        <?php require __DIR__ . '/includes/header.php'; ?>
        <div class="main">
            <div class="content">
                <h1>About</h1>
                <div class="aboutContent">
                        <p class="intro">The client for this project is people who like technology topics and people who like trivia-style games. The site supports both groups with multiple-choice quizzes by difficulty and a score leaderboard.</p>

                        <div class="mission">
                        <h2>Mission</h2>
                        <p>The site should help users practice technology facts in a short quiz format and show clear feedback after each attempt.</p>
                        </div>

                        <div class="features">
                            <img src="images/trivialogo.webp" alt="Trivia lightbulb logo">
                            <div class="featuresText">
                                <h2>What You Can Do</h2>
                                <ul>
                                    <li>Take quizzes by category or difficulty</li>
                                    <li>View your scores</li>
                                    <li>Compete on a leaderboard</li>
                                    <li>Admins can manage questions</li>
                                </ul>
                            </div>
                            <img src="images/computer.webp" alt="Stylized desktop computer illustration">
                        </div>
                        
                    </div>
            </div>
        </div>
        <?php require __DIR__ . '/includes/footer.php'; ?>
    </div>
</body>
</html>
