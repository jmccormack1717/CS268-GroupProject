<?php

declare(strict_types=1);

if (!isset($QUIZ_DIFFICULTY) || !in_array($QUIZ_DIFFICULTY, ['easy', 'medium', 'hard'], true)) {
    http_response_code(500);
    exit;
}

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/quiz_lib.php';

auth_bootstrap();
$guest = !auth_is_logged_in();

$difficulty = $QUIZ_DIFFICULTY;
$selfPage = basename((string) ($_SERVER['PHP_SELF'] ?? 'quiz.php'));
$diffLabel = ucfirst($difficulty);
$pageTitle = $diffLabel . ' quiz';
$leaderLink = 'leaderboard.php';

$loadError = '';
$formError = '';
$questions = [];
$activeCategoryId = 0;
$activeCategoryName = '';
$showResult = null;
$categories = [];
$mode = 'need_login';
$need = (int) QUIZ_QUESTION_COUNT;

if (!$guest) {
    $uid = auth_user_id();
    if ($uid === null) {
        redirect('login.php');
    }

    $categories = quiz_list_categories();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && (string) ($_POST['form'] ?? '') === 'submit_quiz') {
    $active = $_SESSION['active_quiz'] ?? null;
    if (!is_array($active) || (string) ($active['page'] ?? '') !== $selfPage) {
        $formError = 'This quiz session is no longer valid. Start the quiz again.';
    } else {
        $ids = $active['question_ids'] ?? [];
        if (!is_array($ids) || $ids === []) {
            $formError = 'No questions in session. Start the quiz again.';
        } else {
            $catId = (int) ($active['category_id'] ?? 0);
            if ((string) ($active['difficulty'] ?? '') !== $difficulty) {
                $formError = 'Invalid quiz session.';
            } elseif ($catId < 1) {
                $formError = 'Invalid category.';
            } else {
                $answers = $_POST['answer'] ?? [];
                if (!is_array($answers)) {
                    $answers = [];
                }
                $ansKeys = array_map('strval', array_keys($answers));
                $idStrs = array_map('strval', $ids);
                sort($ansKeys);
                sort($idStrs);
                if ($ansKeys !== $idStrs) {
                    $formError = 'Answer every question before submitting.';
                } else {
                    $correctMap = quiz_fetch_answers_for_ids($ids, $catId, $difficulty);
                    if (count($correctMap) !== count($ids)) {
                        $formError = 'Could not verify your answers. Try again.';
                    } else {
                        $score = quiz_score_answers($ids, $answers, $correctMap);
                        $total = count($ids);
                        $ins = db()->prepare(
                            'INSERT INTO quiz_attempts (user_id, category_id, difficulty, score, total_questions) VALUES (:u, :c, :d, :s, :t)'
                        );
                        $ins->execute([
                            'u' => $uid,
                            'c' => $catId,
                            'd' => $difficulty,
                            's' => $score,
                            't' => $total,
                        ]);
                        unset($_SESSION['active_quiz']);
                        $_SESSION['quiz_result_flash'] = [
                            'score' => $score,
                            'total' => $total,
                            'diff' => $difficulty,
                            'cat' => quiz_category_name($catId) ?? 'Category',
                            'category_id' => $catId,
                        ];
                        $loc = $selfPage . '?category=' . $catId . '&finished=1';
                        header('Location: ' . $loc);
                        exit;
                    }
                }
            }
        }
    }
}

$justFinished = $_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['finished']) && (string) $_GET['finished'] === '1';
$resultCategory = isset($_GET['category']) ? (int) $_GET['category'] : 0;

if ($justFinished) {
    $flash = $_SESSION['quiz_result_flash'] ?? null;
    if (is_array($flash)
        && (int) ($flash['category_id'] ?? 0) === $resultCategory
        && (string) ($flash['diff'] ?? '') === $difficulty
    ) {
        $showResult = $flash;
    }
    unset($_SESSION['quiz_result_flash']);
}

$mode = 'pick';

if (!is_array($showResult) && $formError === '') {
    $catGet = isset($_GET['category']) ? (int) $_GET['category'] : 0;
    if ($justFinished) {
        $mode = 'finished_empty';
    } elseif ($catGet > 0) {
        $name = quiz_category_name($catGet);
        if ($name === null) {
            $loadError = 'That category was not found.';
        } else {
            $rows = quiz_load_questions($catGet, $difficulty, $need);
            if (count($rows) < $need) {
                $loadError = 'Not enough ' . $diffLabel . ' questions in this category. Need at least '
                    . (string) $need . ' (or lower QUIZ_QUESTION_COUNT in config).';
            } else {
                $ids = [];
                foreach ($rows as $r) {
                    $ids[] = (int) $r['id'];
                }
                $_SESSION['active_quiz'] = [
                    'page' => $selfPage,
                    'difficulty' => $difficulty,
                    'category_id' => $catGet,
                    'question_ids' => $ids,
                    'questions' => $rows,
                ];
                $questions = $rows;
                $activeCategoryId = $catGet;
                $activeCategoryName = $name;
                $mode = 'quiz';
            }
        }
    }
} elseif (is_array($showResult)) {
    $mode = 'result';
} elseif ($formError !== '' && is_array($_SESSION['active_quiz'] ?? null)) {
    $active = $_SESSION['active_quiz'];
    if ((string) ($active['page'] ?? '') === $selfPage) {
        $questions = $active['questions'] ?? [];
        $activeCategoryId = (int) ($active['category_id'] ?? 0);
        $activeCategoryName = quiz_category_name($activeCategoryId) ?? '';
        $mode = 'quiz';
    }
}

if ($loadError !== '') {
    $mode = 'load_error';
}

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?= h($pageTitle) ?></title>
    <?php require __DIR__ . '/head_assets.php'; ?>
</head>
<body>
    <div id="container">
        <?php require __DIR__ . '/header.php'; ?>
        <div class="main">
            <div class="content admin-content">
                <h1><?= h($pageTitle) ?></h1>

                <?php if ($mode === 'need_login'): ?>
                    <p>Log in to play this quiz and save your scores.</p>
                    <p><a href="login.php">Log in</a> &middot; <a href="register.php">Register</a></p>
                <?php elseif ($mode === 'result' && is_array($showResult)): ?>
                    <p class="form-success">You scored <?= h((string) (int) $showResult['score']) ?> out of <?= h((string) (int) $showResult['total']) ?>
                        (<?= h($diffLabel) ?>, <?= h((string) $showResult['cat']) ?>).</p>
                    <p>
                        <a href="<?= h($selfPage) ?>?category=<?= (int) $showResult['category_id'] ?>">Play this level again</a>
                        &middot; <a href="<?= h($selfPage) ?>">Pick another category</a>
                        &middot; <a href="<?= h($leaderLink) ?>">Leaderboard</a>
                    </p>
                <?php elseif ($mode === 'finished_empty'): ?>
                    <p class="form-error">That score was already shown. <a href="<?= h($selfPage) ?>">Start a new quiz</a> or open the <a href="<?= h($leaderLink) ?>">leaderboard</a>.</p>
                <?php elseif ($mode === 'load_error'): ?>
                    <p class="form-error"><?= h($loadError) ?></p>
                    <p><a href="<?= h($selfPage) ?>">Back</a></p>
                <?php elseif ($formError !== ''): ?>
                    <p class="form-error"><?= h($formError) ?></p>
                <?php endif; ?>

                <?php if ($mode === 'pick' && $loadError === ''): ?>
                    <p>Choose a category (you will get <?= h((string) $need) ?> random <?= h($diffLabel) ?> questions).</p>
                    <?php if ($categories === []): ?>
                        <p class="form-error">No categories in the database.</p>
                    <?php else: ?>
                        <ul class="link-list">
                            <?php foreach ($categories as $c): ?>
                                <li><a href="<?= h($selfPage) ?>?category=<?= (int) $c['id'] ?>"><?= h($c['name']) ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if ($mode === 'quiz' && $questions !== []): ?>
                    <p>Category: <strong><?= h($activeCategoryName) ?></strong> &middot; <a href="<?= h($selfPage) ?>">Change category</a></p>
                    <form class="q-form" method="post" action="<?= h($selfPage) . '?category=' . (int) $activeCategoryId ?>">
                        <input type="hidden" name="form" value="submit_quiz">
                        <?php
                        $n = 0;
                        foreach ($questions as $q):
                            $n++;
                        $qid = (int) $q['id'];
                        ?>
                            <fieldset class="quiz-fieldset">
                                <legend>Question <?= h((string) $n) ?></legend>
                                <p class="q-text"><?= h((string) $q['question_text']) ?></p>
                                <?php foreach (['a' => 'choice_a', 'b' => 'choice_b', 'c' => 'choice_c', 'd' => 'choice_d'] as $let => $col): ?>
                                    <label class="inline-label block-choice">
                                        <input type="radio" name="answer[<?= (int) $qid ?>]" value="<?= h($let) ?>" required>
                                        <span class="choice-label"><?= h(strtoupper($let)) ?>.</span> <?= h((string) $q[$col]) ?>
                                    </label>
                                <?php endforeach; ?>
                            </fieldset>
                        <?php endforeach; ?>
                        <p><button type="submit">Submit answers</button></p>
                    </form>
                <?php endif; ?>
            </div>
        </div>
        <?php require __DIR__ . '/footer.php'; ?>
    </div>
    <script src="js/quiz.js" defer></script>
</body>
</html>
