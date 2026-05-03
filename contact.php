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
                    <div class="formContact">
                        <form class="contact-form" method="post" action="#">
                        <label for="contact-name">Name</label>
                        <input type="text" name="name" id="contact-name" autocomplete="name">

                        <label for="contact-email">E-mail</label>
                        <input type="email" name="email" id="contact-email" autocomplete="email">

                        <label for="contact-comment">Comment</label>
                        <textarea name="comment" id="contact-comment" rows="6" cols="30"></textarea>

                        <button type="submit">Submit</button>
                        </form>
                </div>
            </div>
        </div>
        <?php require __DIR__ . '/includes/footer.php'; ?>
    </div>
    <script src="js/forms.js" defer></script>
</body>
</html>
