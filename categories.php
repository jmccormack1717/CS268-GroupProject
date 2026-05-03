<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';
auth_bootstrap();

$pageTitle = 'Categories';
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
                <h1>Categories</h1>
                    <p><b>Select a difficulty level for your quiz.</b></p>
                    <br>
                    <div class="catContent">
                        <div class="easy">
                            <p>If you have a beginner level of knowledge about technology, try the easy quiz.</p>
                            <a href="easyquiz.php">Easy Quiz</a>
                        </div>

                        <div class="medium">
                            <p>If you have an intermediate level of knowledge about technology, try the medium quiz.</p>
                            <a href="mediumquiz.php">Medium Quiz</a>
                        </div>
                        
                        <div class="hard">
                            <p>If you have an expert level of knowledge about technology, try the hard quiz.</p>
                            <a href="hardquiz.php">Hard Quiz</a>
                        </div>
                        
                    </div>
                    <br>
                    <p><b>After you finish, open the leaderboard to see how your score compares. <img class="catImage" src="images/thumbsup.png" alt="Thumbs-up icon"></b></p>
                    
                </div>
            </div>
        <?php require __DIR__ . '/includes/footer.php'; ?>
    </div>
</body>
</html>
