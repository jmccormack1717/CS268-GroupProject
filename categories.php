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
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div id="container">
        <?php require __DIR__ . '/includes/header.php'; ?>
        <div class="main">
            <div class="content">
                <p><b>Categories</b></p>
            </div>
        </div>
        <div class="footer">
            <p>This is the footer</p>
        </div>
    </div>
</body>
</html>
