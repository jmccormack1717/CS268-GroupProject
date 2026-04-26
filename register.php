<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

auth_bootstrap();

if (auth_is_logged_in()) {
    redirect(auth_is_admin() ? 'admindashboard.php' : 'index.php');
}

$errors = [];
$usernameValue = '';
$emailValue = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usernameValue = trim((string) ($_POST['username'] ?? ''));
    $emailValue = trim((string) ($_POST['email'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');
    $passwordConfirm = (string) ($_POST['password_confirm'] ?? '');

    if ($usernameValue === '') {
        $errors[] = 'Username is required.';
    } elseif (!preg_match('/^[A-Za-z0-9_]{3,64}$/', $usernameValue)) {
        $errors[] = 'Username must be 3 to 64 letters, numbers, or underscores.';
    }

    if ($emailValue === '') {
        $errors[] = 'Email is required.';
    } elseif (filter_var($emailValue, FILTER_VALIDATE_EMAIL) === false) {
        $errors[] = 'Please enter a valid email address.';
    }

    if ($password === '') {
        $errors[] = 'Password is required.';
    } elseif (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters.';
    }

    if ($password !== $passwordConfirm) {
        $errors[] = 'Passwords do not match.';
    }

    if ($errors === []) {
        $emailNorm = strtolower($emailValue);
        $dup = db()->prepare('SELECT id FROM users WHERE username = :u OR email = :e LIMIT 1');
        $dup->execute(['u' => $usernameValue, 'e' => $emailNorm]);
        if ($dup->fetch() !== false) {
            $errors[] = 'That username or email is already registered.';
        }
    }

    if ($errors === []) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        try {
            $ins = db()->prepare(
                'INSERT INTO users (username, email, password_hash, role) VALUES (:u, :e, :p, \'user\')'
            );
            $ins->execute([
                'u' => $usernameValue,
                'e' => $emailNorm,
                'p' => $hash,
            ]);
        } catch (PDOException) {
            $errors[] = 'Could not create account. Please try again.';
        }
        if ($errors === []) {
            redirect('login.php?registered=1');
        }
    }
}

$pageTitle = 'Register';
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
                <h1>Create account</h1>
                <?php foreach ($errors as $msg): ?>
                    <p class="form-error"><?= h($msg) ?></p>
                <?php endforeach; ?>
                <form method="post" action="register.php" autocomplete="on">
                    <p>
                        <label for="username">Username</label><br>
                        <input type="text" name="username" id="username" value="<?= h($usernameValue) ?>" required maxlength="64" pattern="[A-Za-z0-9_]{3,64}" title="3-64 letters, numbers, or underscores">
                    </p>
                    <p>
                        <label for="email">Email</label><br>
                        <input type="email" name="email" id="email" value="<?= h($emailValue) ?>" required maxlength="255">
                    </p>
                    <p>
                        <label for="password">Password</label><br>
                        <input type="password" name="password" id="password" required minlength="8" autocomplete="new-password">
                    </p>
                    <p>
                        <label for="password_confirm">Confirm password</label><br>
                        <input type="password" name="password_confirm" id="password_confirm" required minlength="8" autocomplete="new-password">
                    </p>
                    <p>
                        <button type="submit">Register</button>
                    </p>
                </form>
                <p><a href="login.php">Already have an account? Log in</a></p>
                <p><a href="index.php">Back to home</a></p>
            </div>
        </div>
        <?php require __DIR__ . '/includes/footer.php'; ?>
    </div>
</body>
</html>
