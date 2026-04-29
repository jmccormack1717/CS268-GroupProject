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
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div id="container">
        <?php require __DIR__ . '/includes/header.php'; ?>
        <div class="main">
            <div class="content">
                <p><b>About</b></p>
                <div class="aboutContent">
                        <p class="intro">Tech Trivia is a course website for an online trivia community that wants a fun, interactive way to test their technology knowledge. </p>
                        
                        <div class="mission">
                        <h2>Our Mission</h2>
                        <p>To make learning and testing your knowledge about technology fun and interactive through 
                        our trivia challenges.</p>
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
                            <img src="images/Computer.webp" alt="Computer image">
                        </div>
                        
                    </div>
            </div>
        </div>
        <?php require __DIR__ . '/includes/footer.php'; ?>
    </div>
</body>
</html>
