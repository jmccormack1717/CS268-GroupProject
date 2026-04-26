<?php

declare(strict_types=1);

require_once __DIR__ . '/db.php';

/**
 * User's highest single-quiz score (max of score column), or null if no attempts.
 */
function user_best_score(?int $userId): ?int
{
    if ($userId === null || $userId < 1) {
        return null;
    }
    $st = db()->prepare('SELECT MAX(score) AS m FROM quiz_attempts WHERE user_id = :u');
    $st->execute(['u' => $userId]);
    $m = $st->fetchColumn();
    if ($m === null || $m === false) {
        return null;
    }

    return (int) $m;
}

/**
 * Rank 1 = best among all users, by each user's best single-game score (ties get same rank logic: strict greater only).
 */
function user_rank_by_best_score(?int $userId): ?int
{
    if ($userId === null || $userId < 1) {
        return null;
    }
    $best = user_best_score($userId);
    if ($best === null) {
        return null;
    }
    $st = db()->prepare(
        'SELECT 1 + COUNT(*) FROM (
            SELECT user_id, MAX(score) AS best_sc FROM quiz_attempts GROUP BY user_id
        ) x WHERE x.best_sc > :b'
    );
    $st->execute(['b' => $best]);

    return (int) $st->fetchColumn();
}
