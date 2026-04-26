<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

/**
 * Shared PDO connection (singleton per request).
 *
 * @throws PDOException if the connection fails
 */
function db(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $dsn = sprintf(
        'mysql:host=%s;dbname=%s;charset=%s',
        DB_HOST,
        DB_NAME,
        DB_CHARSET
    );

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (PDOException $e) {
        $m = $e->getMessage();
        if (str_contains($m, '1049') || str_contains($m, 'Unknown database')) {
            http_response_code(503);
            header('Content-Type: text/plain; charset=UTF-8');
            echo "MySQL database is missing (expected name: " . DB_NAME . ").\n\n"
                . "Fix: open phpMyAdmin, use the Import tab, and run sql/schema.sql from this project.\n"
                . "That file creates the database and tables. Then reload this page.\n";
            exit;
        }
        throw $e;
    }

    return $pdo;
}
