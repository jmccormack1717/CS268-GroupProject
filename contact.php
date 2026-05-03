<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';
auth_bootstrap();

$pageTitle = 'Contact';
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
                <h1>Contact Us</h1>
                <div class="contact-static">
                    <p class="intro">Questions about the quizzes, scoring, or something not working on the site? Ideas for new categories or questions? Send us a note—we check this inbox regularly and do our best to reply within a couple of days.</p>
                    <p>Email the team: <a href="mailto:hello@techtrivia.net">hello@techtrivia.net</a></p>
                    <p class="contact-note">We do not collect messages through this page; please use your own email app to reach us at the address above.</p>
                </div>
            </div>
        </div>
        <?php require __DIR__ . '/includes/footer.php'; ?>
    </div>
</body>
</html>
