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
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div id="container">
        <?php require __DIR__ . '/includes/header.php'; ?>
        <div class="main">
            <div class="content">
                <h1><B>Contact Us!</B></h1>
                    <div class="formContact">
                        <form name="Contact Us!" action="#" onsubmit="checkit(); return false;">
                        
                        <label>Name: </label>
                        <input type= "text" name="name">
                        <br>
            
                        <label>E-mail: </label>
                        <input type="text" name="email">
                        <br>

                        <label>Comment: </label>
                        <textarea name="comment" rows="6" cols="30"></textarea>
                        <br>
                        
                        <button type="submit">Submit</button>
                        
                        </form>
                </div>
            </div>
        </div>
        <?php require __DIR__ . '/includes/footer.php'; ?>
    </div>
</body>
</html>
