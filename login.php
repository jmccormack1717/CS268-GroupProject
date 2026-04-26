<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

auth_bootstrap();

if (auth_is_logged_in()) {
    redirect(auth_is_admin() ? 'admindashboard.php' : 'index.php');
}

$error = '';
$loginValue = '';
$registeredNotice = isset($_GET['registered']) && (string) $_GET['registered'] === '1';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $loginValue = trim((string) ($_POST['login'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    if ($loginValue === '' || $password === '') {
        $error = 'Please enter your username or email and password.';
    } else {
        $stmt = db()->prepare(
            'SELECT id, username, password_hash, role FROM users WHERE username = :u OR email = :e LIMIT 1'
        );
        $emailKey = strtolower($loginValue);
        $stmt->execute(['u' => $loginValue, 'e' => $emailKey]);
        $row = $stmt->fetch();

        if ($row === false || !password_verify($password, (string) $row['password_hash'])) {
            $error = 'Invalid username or password.';
        } else {
            auth_login_user((int) $row['id'], (string) $row['username'], (string) $row['role']);
            redirect(((string) $row['role'] === 'admin') ? 'admindashboard.php' : 'index.php');
        }
    }
}

$pageTitle = 'Login';
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
                <h1>Log in</h1>
                <?php if ($registeredNotice): ?>
                    <p class="form-success">Account created. You can log in below.</p>
                <?php endif; ?>
                <?php if ($error !== ''): ?>
                    <p class="form-error"><?= h($error) ?></p>
                <?php endif; ?>
                <form method="post" action="login.php" autocomplete="on">
                    <p>
                        <label for="login">Username or email</label><br>
                        <input type="text" name="login" id="login" value="<?= h($loginValue) ?>" required maxlength="255">
                    </p>
                    <p>
                        <label for="password">Password</label><br>
                        <input type="password" name="password" id="password" required autocomplete="current-password">
                    </p>
                    <p>
                        <button type="submit">Log in</button>
                    </p>
                </form>
                <p><a href="register.php">Create an account</a></p>
                <p><a href="index.php">Back to home</a></p>
            </div>
        </div>
        <?php require __DIR__ . '/includes/footer.php'; ?>
    </div>
</body>
</html>
