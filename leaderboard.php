<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

auth_bootstrap();

$pageTitle = 'Leaderboard';

$rows = [];
if (auth_is_logged_in()) {
    $stmt = db()->query(
        'SELECT qa.score, qa.total_questions, qa.difficulty, qa.completed_at, u.username, c.name AS category_name
         FROM quiz_attempts qa
         INNER JOIN users u ON u.id = qa.user_id
         INNER JOIN categories c ON c.id = qa.category_id
         WHERE NOT EXISTS (
             SELECT 1
             FROM quiz_attempts qa2
             WHERE qa2.user_id = qa.user_id
               AND (
                   qa2.score > qa.score
                   OR (
                       qa2.score = qa.score
                       AND (
                           qa2.completed_at < qa.completed_at
                           OR (
                               qa2.completed_at = qa.completed_at
                               AND qa2.id < qa.id
                           )
                       )
                   )
               )
         )
         ORDER BY qa.score DESC, qa.completed_at ASC, qa.id ASC
         LIMIT 100'
    );
    $fetched = $stmt->fetchAll();
    $rows = is_array($fetched) ? $fetched : [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?= h($pageTitle) ?></title>
    <?php require __DIR__ . '/includes/head_assets.php'; ?>
</head>
<body>
    <div id="container">
        <?php require __DIR__ . '/includes/header.php'; ?>
        <div class="main">
            <div class="content admin-content">
                <h1><?= h($pageTitle) ?></h1>

                <?php if (!auth_is_logged_in()): ?>
                    <p>Log in to view the leaderboard.</p>
                    <p><a href="login.php">Log in</a> &middot; <a href="register.php">Register</a></p>
                <?php else: ?>
                    <p>Each user appears once with their best score (up to 100 users). Ordering: higher score first, then earlier date for that best run.</p>
                    <?php if ($rows === []): ?>
                        <p class="form-error">No quiz attempts yet. Take a quiz and submit your answers to appear here.</p>
                    <?php else: ?>
                        <div class="table-wrap">
                            <table class="data-table">
                                <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>User</th>
                                    <th>Best score</th>
                                    <th>Out of</th>
                                    <th>Difficulty</th>
                                    <th>Category</th>
                                    <th>When</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $r = 0;
                                foreach ($rows as $row):
                                    $r++;
                                    $when = (string) $row['completed_at'];
                                    ?>
                                    <tr>
                                        <td><?= h((string) $r) ?></td>
                                        <td><?= h((string) $row['username']) ?></td>
                                        <td><?= h((string) (int) $row['score']) ?></td>
                                        <td><?= h((string) (int) $row['total_questions']) ?></td>
                                        <td><?= h((string) $row['difficulty']) ?></td>
                                        <td><?= h((string) $row['category_name']) ?></td>
                                        <td><?= h($when) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
        <?php require __DIR__ . '/includes/footer.php'; ?>
    </div>
</body>
</html>
