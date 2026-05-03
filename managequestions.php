<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

auth_bootstrap();
require_admin();

$pageTitle = 'Manage questions';
$uid = auth_user_id();

$stmtCat = db()->query('SELECT id, name FROM categories ORDER BY name ASC');
$categories = $stmtCat->fetchAll();
$categoryCount = count($categories);

$flash = isset($_GET['flash']) && is_string($_GET['flash']) ? $_GET['flash'] : null;

$editId = isset($_GET['edit']) ? (int) $_GET['edit'] : 0;
$editRow = null;
if ($editId > 0) {
    $st = db()->prepare('SELECT * FROM questions WHERE id = :id LIMIT 1');
    $st->execute(['id' => $editId]);
    $editRow = $st->fetch() ?: null;
    if ($editRow === null) {
        $editId = 0;
    }
}

function collect_question_post(): array
{
    return [
        'category_id' => (int) ($_POST['category_id'] ?? 0),
        'difficulty' => trim((string) ($_POST['difficulty'] ?? '')),
        'question_text' => trim((string) ($_POST['question_text'] ?? '')),
        'choice_a' => trim((string) ($_POST['choice_a'] ?? '')),
        'choice_b' => trim((string) ($_POST['choice_b'] ?? '')),
        'choice_c' => trim((string) ($_POST['choice_c'] ?? '')),
        'choice_d' => trim((string) ($_POST['choice_d'] ?? '')),
        'correct_choice' => normalize_choice((string) ($_POST['correct_choice'] ?? '')),
    ];
}

function validate_question_input(array $f, int $catCount): array
{
    $errors = [];
    if ($catCount < 1) {
        $errors[] = 'Add at least one category to the database first.';
    } elseif ($f['category_id'] < 1) {
        $errors[] = 'Choose a category.';
    }
    if (!in_array($f['difficulty'], ['easy', 'medium', 'hard'], true)) {
        $errors[] = 'Choose a valid difficulty.';
    }
    if ($f['question_text'] === '') {
        $errors[] = 'Enter the question text.';
    }
    foreach (['a' => 'choice_a', 'b' => 'choice_b', 'c' => 'choice_c', 'd' => 'choice_d'] as $label => $key) {
        if ($f[$key] === '') {
            $errors[] = 'Enter text for choice ' . strtoupper($label) . '.';
        } elseif (strlen($f[$key]) > 512) {
            $errors[] = 'Each choice may be at most 512 characters.';
        }
    }
    if (strlen($f['question_text']) > 65000) {
        $errors[] = 'Question text is too long.';
    }
    if ($f['correct_choice'] === null) {
        $errors[] = 'Select the correct answer (A, B, C, or D).';
    }
    return $errors;
}

function category_exists(int $id): bool
{
    if ($id < 1) {
        return false;
    }
    $s = db()->prepare('SELECT 1 FROM categories WHERE id = :id LIMIT 1');
    $s->execute(['id' => $id]);

    return (bool) $s->fetchColumn();
}

$formErrors = [];
$formData = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = (string) ($_POST['action'] ?? '');

    if ($action === 'delete') {
        $delId = (int) ($_POST['question_id'] ?? 0);
        if ($delId < 1) {
            redirect('managequestions.php?flash=error');
        }
        $d = db()->prepare('DELETE FROM questions WHERE id = :id');
        $d->execute(['id' => $delId]);
        if ($d->rowCount() < 1) {
            redirect('managequestions.php?flash=error');
        }
        redirect('managequestions.php?flash=deleted');
    }

    $f = collect_question_post();
    $formData = $f;
    $formErrors = validate_question_input($f, $categoryCount);

    if ($formErrors === [] && !category_exists($f['category_id'])) {
        $formErrors[] = 'Invalid category.';
    }

    if ($formErrors === [] && $action === 'add') {
        if ($uid === null) {
            redirect('login.php');
        }
        $ins = db()->prepare(
            'INSERT INTO questions (category_id, difficulty, question_text, choice_a, choice_b, choice_c, choice_d, correct_choice, created_by) VALUES
            (:category_id, :difficulty, :question_text, :choice_a, :choice_b, :choice_c, :choice_d, :correct_choice, :created_by)'
        );
        $ins->execute([
            'category_id' => $f['category_id'],
            'difficulty' => $f['difficulty'],
            'question_text' => $f['question_text'],
            'choice_a' => $f['choice_a'],
            'choice_b' => $f['choice_b'],
            'choice_c' => $f['choice_c'],
            'choice_d' => $f['choice_d'],
            'correct_choice' => $f['correct_choice'],
            'created_by' => $uid,
        ]);
        redirect('managequestions.php?flash=saved');
    }

    if ($formErrors === [] && $action === 'update') {
        $qid = (int) ($_POST['question_id'] ?? 0);
        if ($qid < 1) {
            $formErrors[] = 'Invalid question to update.';
        } else {
            $ch = db()->prepare('SELECT id FROM questions WHERE id = :id LIMIT 1');
            $ch->execute(['id' => $qid]);
            if ($ch->fetch() === false) {
                $formErrors[] = 'Question not found.';
            } else {
                $up = db()->prepare(
                    'UPDATE questions SET category_id = :category_id, difficulty = :difficulty, question_text = :question_text,
                    choice_a = :choice_a, choice_b = :choice_b, choice_c = :choice_c, choice_d = :choice_d, correct_choice = :correct_choice
                    WHERE id = :id'
                );
                $up->execute([
                    'category_id' => $f['category_id'],
                    'difficulty' => $f['difficulty'],
                    'question_text' => $f['question_text'],
                    'choice_a' => $f['choice_a'],
                    'choice_b' => $f['choice_b'],
                    'choice_c' => $f['choice_c'],
                    'choice_d' => $f['choice_d'],
                    'correct_choice' => $f['correct_choice'],
                    'id' => $qid,
                ]);
                redirect('managequestions.php?flash=updated');
            }
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && (string) ($_POST['action'] ?? '') === 'update' && $formErrors !== [] && $formData !== null) {
    $eid = (int) ($_POST['question_id'] ?? 0);
    if ($eid > 0) {
        $st = db()->prepare('SELECT * FROM questions WHERE id = :id LIMIT 1');
        $st->execute(['id' => $eid]);
        $r = $st->fetch();
        if ($r !== false) {
            $editId = $eid;
            $editRow = $r;
            foreach (['category_id', 'difficulty', 'question_text', 'choice_a', 'choice_b', 'choice_c', 'choice_d', 'correct_choice'] as $k) {
                if (array_key_exists($k, $formData)) {
                    $editRow[$k] = $k === 'category_id' ? (int) $formData[$k] : $formData[$k];
                }
            }
        }
    }
}

$stmtList = db()->query(
    'SELECT q.id, q.difficulty, q.question_text, c.name AS category_name
     FROM questions q
     INNER JOIN categories c ON c.id = q.category_id
     ORDER BY q.id DESC'
);
$questionList = $stmtList->fetchAll();

$prefill = $formData;
if ($prefill === null && $editRow !== null) {
    $prefill = [
        'category_id' => (int) $editRow['category_id'],
        'difficulty' => (string) $editRow['difficulty'],
        'question_text' => (string) $editRow['question_text'],
        'choice_a' => (string) $editRow['choice_a'],
        'choice_b' => (string) $editRow['choice_b'],
        'choice_c' => (string) $editRow['choice_c'],
        'choice_d' => (string) $editRow['choice_d'],
        'correct_choice' => (string) $editRow['correct_choice'],
    ];
}

$showAddForm = $editId === 0;
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
                <h1>Manage questions</h1>
                <p class="admin-nav"><a href="admindashboard.php">Back to dashboard</a> &middot; <a href="logout.php">Log out</a></p>

                <?php if ($flash === 'saved'): ?>
                    <p class="form-success">Question added.</p>
                <?php elseif ($flash === 'updated'): ?>
                    <p class="form-success">Question updated.</p>
                <?php elseif ($flash === 'deleted'): ?>
                    <p class="form-success">Question deleted.</p>
                <?php elseif ($flash === 'error'): ?>
                    <p class="form-error">The last action did not complete.</p>
                <?php endif; ?>

                <?php if ($categoryCount < 1): ?>
                    <p class="form-error">No categories in the database. Import <code>sql/seed_categories.sql</code> or re-import <code>sql/schema.sql</code>.</p>
                <?php endif; ?>

                <h2>All questions</h2>
                <?php if (count($questionList) === 0): ?>
                    <p>No questions yet. Add one below.</p>
                <?php else: ?>
                    <div class="table-wrap">
                        <table class="data-table">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Category</th>
                                <th>Difficulty</th>
                                <th>Question</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($questionList as $q): ?>
                                <tr>
                                    <td><?= h((string) $q['id']) ?></td>
                                    <td><?= h($q['category_name']) ?></td>
                                    <td><?= h($q['difficulty']) ?></td>
                                    <td class="q-preview"><?= h(preview_string($q['question_text'], 80)) ?></td>
                                    <td class="q-actions">
                                        <a href="managequestions.php?edit=<?= (int) $q['id'] ?>">Edit</a>
                                        <form class="form-inline" method="post" action="managequestions.php" onsubmit="return confirm('Delete this question?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="question_id" value="<?= (int) $q['id'] ?>">
                                            <button type="submit" class="link-button">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>

                <?php foreach ($formErrors as $err): ?>
                    <p class="form-error"><?= h($err) ?></p>
                <?php endforeach; ?>

                <?php if ($showAddForm && $categoryCount > 0): ?>
                <h2 id="add">Add question</h2>
                <form class="q-form" method="post" action="managequestions.php#add" autocomplete="off">
                    <input type="hidden" name="action" value="add">
                    <p>
                        <label for="a_category_id">Category</label>
                        <select name="category_id" id="a_category_id" required>
                            <?php foreach ($categories as $c): ?>
                                <option value="<?= (int) $c['id'] ?>"
                                    <?= (isset($prefill['category_id']) && (int) $prefill['category_id'] === (int) $c['id']) ? ' selected' : '' ?>>
                                    <?= h($c['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </p>
                    <p>
                        <label for="a_difficulty">Difficulty</label>
                        <select name="difficulty" id="a_difficulty" required>
                            <?php foreach (['easy' => 'Easy', 'medium' => 'Medium', 'hard' => 'Hard'] as $k => $lab): ?>
                                <option value="<?= h($k) ?>" <?= (isset($prefill['difficulty']) && $prefill['difficulty'] === $k) ? ' selected' : '' ?>><?= h($lab) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </p>
                    <p>
                        <label for="a_qtext">Question</label>
                        <textarea name="question_text" id="a_qtext" rows="3" required><?= isset($prefill['question_text']) ? h($prefill['question_text']) : '' ?></textarea>
                    </p>
                    <p>
                        <label for="a_ca">Choice A</label>
                        <input type="text" name="choice_a" id="a_ca" value="<?= isset($prefill['choice_a']) ? h($prefill['choice_a']) : '' ?>" required maxlength="512">
                    </p>
                    <p>
                        <label for="a_cb">Choice B</label>
                        <input type="text" name="choice_b" id="a_cb" value="<?= isset($prefill['choice_b']) ? h($prefill['choice_b']) : '' ?>" required maxlength="512">
                    </p>
                    <p>
                        <label for="a_cc">Choice C</label>
                        <input type="text" name="choice_c" id="a_cc" value="<?= isset($prefill['choice_c']) ? h($prefill['choice_c']) : '' ?>" required maxlength="512">
                    </p>
                    <p>
                        <label for="a_cd">Choice D</label>
                        <input type="text" name="choice_d" id="a_cd" value="<?= isset($prefill['choice_d']) ? h($prefill['choice_d']) : '' ?>" required maxlength="512">
                    </p>
                    <p>
                        <span class="label">Correct answer</span><br>
                        <?php foreach (['a', 'b', 'c', 'd'] as $l): ?>
                            <label class="inline-label">
                                <input type="radio" name="correct_choice" value="<?= h($l) ?>"
                                    <?= (isset($prefill['correct_choice']) && $prefill['correct_choice'] === $l) ? ' checked' : '' ?> required>
                                <?= h(strtoupper($l)) ?>
                            </label>
                        <?php endforeach; ?>
                    </p>
                    <p>
                        <button type="submit">Add question</button>
                    </p>
                </form>
                <?php endif; ?>

                <?php if ($editId > 0 && $editRow !== null && $categoryCount > 0): ?>
                <h2>Edit question #<?= (int) $editId ?></h2>
                <p><a href="managequestions.php">Cancel edit</a></p>
                <form class="q-form" method="post" action="managequestions.php" autocomplete="off">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="question_id" value="<?= (int) $editId ?>">
                    <p>
                        <label for="e_category_id">Category</label>
                        <select name="category_id" id="e_category_id" required>
                            <?php foreach ($categories as $c): ?>
                                <option value="<?= (int) $c['id'] ?>"
                                    <?= ((int) $editRow['category_id'] === (int) $c['id']) ? ' selected' : '' ?>>
                                    <?= h($c['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </p>
                    <p>
                        <label for="e_difficulty">Difficulty</label>
                        <select name="difficulty" id="e_difficulty" required>
                            <?php foreach (['easy' => 'Easy', 'medium' => 'Medium', 'hard' => 'Hard'] as $k => $lab): ?>
                                <option value="<?= h($k) ?>" <?= ((string) $editRow['difficulty'] === $k) ? ' selected' : '' ?>><?= h($lab) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </p>
                    <p>
                        <label for="e_qtext">Question</label>
                        <textarea name="question_text" id="e_qtext" rows="3" required><?= h($editRow['question_text']) ?></textarea>
                    </p>
                    <p>
                        <label for="e_ca">Choice A</label>
                        <input type="text" name="choice_a" id="e_ca" value="<?= h($editRow['choice_a']) ?>" required maxlength="512">
                    </p>
                    <p>
                        <label for="e_cb">Choice B</label>
                        <input type="text" name="choice_b" id="e_cb" value="<?= h($editRow['choice_b']) ?>" required maxlength="512">
                    </p>
                    <p>
                        <label for="e_cc">Choice C</label>
                        <input type="text" name="choice_c" id="e_cc" value="<?= h($editRow['choice_c']) ?>" required maxlength="512">
                    </p>
                    <p>
                        <label for="e_cd">Choice D</label>
                        <input type="text" name="choice_d" id="e_cd" value="<?= h($editRow['choice_d']) ?>" required maxlength="512">
                    </p>
                    <p>
                        <span class="label">Correct answer</span><br>
                        <?php
                        $cc = (string) $editRow['correct_choice'];
                        foreach (['a', 'b', 'c', 'd'] as $l): ?>
                            <label class="inline-label">
                                <input type="radio" name="correct_choice" value="<?= h($l) ?>"
                                    <?= $cc === $l ? ' checked' : '' ?> required>
                                <?= h(strtoupper($l)) ?>
                            </label>
                        <?php endforeach; ?>
                    </p>
                    <p>
                        <button type="submit">Save changes</button>
                    </p>
                </form>
                <?php endif; ?>
            </div>
        </div>
        <?php require __DIR__ . '/includes/footer.php'; ?>
    </div>
    <script src="js/admin.js" defer></script>
</body>
</html>
