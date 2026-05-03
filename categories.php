<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';
auth_bootstrap();

$pageTitle = 'Categories';

$stmt = db()->query('SELECT id, name, description FROM categories ORDER BY name ASC');
$fetched = $stmt->fetchAll();
$categories = is_array($fetched) ? $fetched : [];

$whyMatters = [
    'General' => 'General technology literacy shows up in almost every CS course and workplace tool chain. A broad base makes it easier to pick up new frameworks and documentation.',
    'Web' => 'The web is the main delivery path for software many users see. HTML, CSS, and how browsers talk to servers are standard topics in web development and human-computer interface courses.',
    'Programming' => 'Programming languages, syntax, and basic problem solving are central to algorithms classes and coding exercises. Solid practice supports labs and technical interviews.',
    'Databases' => 'Relational data and SQL appear in backend systems, data science pipelines, and information systems classes. Knowing how data is stored and queried helps you build reliable applications.',
];

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
            <div class="content">
                <h1><?= h($pageTitle) ?></h1>
                <p class="categories-lead">Each quiz category matches a topic area in the question bank. Below is a short summary of what it covers and why it matters in computer science and related fields.</p>

                <?php if ($categories === []): ?>
                    <p class="form-error">No categories in the database.</p>
                <?php else: ?>
                    <div class="category-overview">
                        <?php foreach ($categories as $cat):
                            $name = (string) $cat['name'];
                            $desc = trim((string) ($cat['description'] ?? ''));
                            $why = $whyMatters[$name] ?? 'This topic supports common computing coursework and software projects that use similar concepts.';
                            ?>
                            <section class="category-block">
                                <h2><?= h($name) ?></h2>
                                <?php if ($desc !== ''): ?>
                                    <p class="category-desc"><?= h($desc) ?></p>
                                <?php endif; ?>
                                <p class="category-why"><?= h($why) ?></p>
                            </section>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <p class="categories-foot">To take a quiz, sign in and choose <strong>Easy Quiz</strong>, <strong>Medium Quiz</strong>, or <strong>Hard Quiz</strong> from the navigation bar. Each quiz asks you to pick one of these categories before the questions load.</p>
            </div>
        </div>
        <?php require __DIR__ . '/includes/footer.php'; ?>
    </div>
</body>
</html>
