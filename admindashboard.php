<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

auth_bootstrap();
require_admin();

$pageTitle = 'Admin dashboard';
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
                <h1>Admin dashboard</h1>
                <p>Signed in as <?= h(auth_username()) ?>.</p>
                <ul>
                    <li><a href="managequestions.php">Manage questions</a></li>
                    <li><a href="logout.php">Log out</a></li>
                    <li><a href="index.html">Home</a></li>
                </ul>
            </div>
        </div>
        <?php require __DIR__ . '/includes/footer.php'; ?>
    </div>
</body>
</html>
