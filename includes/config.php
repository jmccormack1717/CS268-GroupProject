<?php

declare(strict_types=1);

/** Database and app constants. Change DB_* to match your local MySQL install. */

define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'techtrivia');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

/** Questions per quiz run. The database needs at least this many rows per category and difficulty. */
define('QUIZ_QUESTION_COUNT', 5);
