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
                    <p class="intro">If you have questions, ideas, or feedback about Tech Trivia, reach out by email. We read messages about the site, the question bank, and how we can improve the quiz experience.</p>
                    <p>Email: <a href="mailto:contact@techtrivia.example">contact@techtrivia.example</a></p>
                    <p class="contact-note">This page is for contact information only; messages are not submitted through the website.</p>
                </div>
            </div>
        </div>
        <?php require __DIR__ . '/includes/footer.php'; ?>
    </div>
</body>
</html>
