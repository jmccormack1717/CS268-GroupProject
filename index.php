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
    <div id="container">
        <?php require __DIR__ . '/includes/header.php'; ?>
        <div class="main">
            <div class="content">
                <h1><B>Time to Test Your Tech Knowledge</B></h1>
                    <div class="index">
                        <div class="aboutIndex">
                            <h3>About Tech Trivia</h3>
                            <p>Tech Trivia is an interactive quiz platform, 
                                created to test your technology knowledge using trivia questions. There is a challenge for you whether you are a beginner or expert. </p>
                            <a href="about.html">Learn More</a>
                        </div>

                        <div class="challenge">
                            <img class="leftImg" src="images/question-mark-hi.png" alt="question mark">
                            <div class="aboutIndex">
                                <h3>Choose Your Challenge</h3>
                                <p>Lets see how much you really know. Click the button if you are ready your test your knowledge.</p>
                                <a class="button" href="categories.html">Start a quiz</a>
                            </div>
                            <img class="rightImg" src="images/brain.png" alt="animated brain">
                        </div>
                        
                        
                    </div>
            </div>
        </div>
        <?php require __DIR__ . '/includes/footer.php'; ?>
    </div>
</body>
</html>
