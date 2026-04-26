<?php

declare(strict_types=1);

/**
 * Application configuration.
 * Adjust database settings to match your local MySQL (e.g. XAMPP / WAMP defaults).
 */

define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'techtrivia');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

/** Number of questions per quiz (must have at least this many in DB per category+difficulty). */
define('QUIZ_QUESTION_COUNT', 5);
