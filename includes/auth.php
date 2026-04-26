<?php

declare(strict_types=1);

require_once __DIR__ . '/functions.php';

function auth_bootstrap(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start([
            'cookie_httponly' => true,
            'cookie_samesite' => 'Lax',
        ]);
    }
}

function auth_user_id(): ?int
{
    if (!isset($_SESSION['user_id'])) {
        return null;
    }
    $id = (int) $_SESSION['user_id'];

    return $id > 0 ? $id : null;
}

function auth_username(): ?string
{
    if (!isset($_SESSION['username'])) {
        return null;
    }
    $name = (string) $_SESSION['username'];

    return $name !== '' ? $name : null;
}

function auth_role(): ?string
{
    $role = $_SESSION['role'] ?? null;
    if ($role !== 'user' && $role !== 'admin') {
        return null;
    }

    return $role;
}

function auth_is_logged_in(): bool
{
    return auth_user_id() !== null && auth_username() !== null;
}

function auth_is_admin(): bool
{
    return auth_is_logged_in() && auth_role() === 'admin';
}

function require_login(): void
{
    if (!auth_is_logged_in()) {
        redirect('login.php');
    }
}

function require_admin(): void
{
    if (!auth_is_admin()) {
        redirect('index.php');
    }
}

function auth_login_user(int $userId, string $username, string $role): void
{
    session_regenerate_id(true);
    $_SESSION['user_id'] = $userId;
    $_SESSION['username'] = $username;
    $_SESSION['role'] = $role;
}

function auth_logout(): void
{
    $_SESSION = [];
    if (session_status() === PHP_SESSION_ACTIVE) {
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
    }
}
