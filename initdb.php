<?php

declare(strict_types=1);

/**
 * One-time database setup for Tech Trivia (course submission).
 *
 * Prerequisites: MySQL running (e.g. XAMPP), credentials in includes/config.php.
 * Usage: open http://localhost/<your-project-folder>/initdb.php once,
 *        or from a shell: php initdb.php
 *
 * If tables already exist, this script exits without changes. To re-run from
 * scratch, drop database techtrivia in phpMyAdmin first.
 */

require_once __DIR__ . '/includes/config.php';

$isCli = PHP_SAPI === 'cli';
if (!$isCli) {
    header('Content-Type: text/plain; charset=UTF-8');
}

$host = DB_HOST;
$user = DB_USER;
$pass = DB_PASS;
$dbName = DB_NAME;

$sqlFile = __DIR__ . DIRECTORY_SEPARATOR . 'sql' . DIRECTORY_SEPARATOR . 'schema.sql';
if (!is_readable($sqlFile)) {
    echo 'Error: cannot read ' . $sqlFile . "\n";
    exit(1);
}

$mysqli = mysqli_init();
if ($mysqli === false) {
    echo "Error: mysqli_init failed.\n";
    exit(1);
}

if (!@$mysqli->real_connect($host, $user, $pass, '')) {
    echo 'Error: could not connect to MySQL: ' . $mysqli->connect_error . "\n";
    exit(1);
}

$mysqli->set_charset('utf8mb4');

$dbEsc = $mysqli->real_escape_string($dbName);
$chk = $mysqli->query(
    "SELECT COUNT(*) AS c FROM information_schema.TABLES
     WHERE TABLE_SCHEMA = '{$dbEsc}' AND TABLE_NAME = 'users'"
);
if ($chk === false) {
    echo 'Error: ' . $mysqli->error . "\n";
    exit(1);
}
$row = $chk->fetch_assoc();
$chk->free();
if ($row !== null && (int) ($row['c'] ?? 0) > 0) {
    echo 'Database "' . $dbName . '" already has tables. Nothing to do.' . "\n";
    echo 'To reset: drop database ' . $dbName . " in phpMyAdmin, then run this script again.\n";
    exit(0);
}

$sql = file_get_contents($sqlFile);
if ($sql === false || $sql === '') {
    echo "Error: schema file is empty.\n";
    exit(1);
}

if (!$mysqli->multi_query($sql)) {
    echo 'Error running schema: ' . $mysqli->error . "\n";
    exit(1);
}

do {
    $result = $mysqli->store_result();
    if ($result instanceof mysqli_result) {
        $result->free();
    }
    if (!$mysqli->more_results()) {
        break;
    }
    if (!$mysqli->next_result()) {
        echo 'Error after statement: ' . $mysqli->error . "\n";
        exit(1);
    }
} while (true);

echo 'Done. Database "' . $dbName . '" is ready.' . "\n";
echo "Next: open the site, register an account, then promote to admin in MySQL if needed (see README).\n";
exit(0);
