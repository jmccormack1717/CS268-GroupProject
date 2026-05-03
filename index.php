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
    <?php require __DIR__ . '/includes/head_assets.php'; ?>
</head>
<body>
    <div id="container">
        <?php require __DIR__ . '/includes/header.php'; ?>
        <div class="main">
            <div class="content">
                <h1>Time to Test Your Tech Knowledge</h1>
                    <div class="index">
                        <div class="aboutIndex">
                            <h3>About Tech Trivia</h3>
                            <p>Tech Trivia is for people who like technology and for people who like quiz games. Choose a difficulty, answer technology questions, then compare your score on the leaderboard.</p>
                            <a href="about.php">Learn More</a>
                        </div>

                        <div class="challenge">
                            <img class="leftImg" src="images/questionmarkhi.png" alt="Question mark icon">
                            <div class="aboutIndex">
                                <h3>Choose Your Challenge</h3>
                                <p>Open the easy quiz to pick a category and begin (sign in first if you have not already).</p>
                                <a class="button" href="easyquiz.php">Start a quiz</a>
                            </div>
                            <img class="rightImg" src="images/brain.png" alt="Cartoon brain illustration">
                        </div>
                        
                        
                    </div>
            </div>
        </div>
        <?php require __DIR__ . '/includes/footer.php'; ?>
    </div>
</body>
</html>
