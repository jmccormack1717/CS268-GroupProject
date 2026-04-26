<?php

declare(strict_types=1);

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

/**
 * @return list<array{id:int,name:string}>
 */
function quiz_list_categories(): array
{
    $q = db()->query('SELECT id, name FROM categories ORDER BY name ASC');
    $rows = $q->fetchAll();
    if (!is_array($rows)) {
        return [];
    }

    return $rows;
}

function quiz_category_name(int $id): ?string
{
    if ($id < 1) {
        return null;
    }
    $s = db()->prepare('SELECT name FROM categories WHERE id = :id LIMIT 1');
    $s->execute(['id' => $id]);
    $n = $s->fetchColumn();
    if ($n === false) {
        return null;
    }

    return (string) $n;
}

/**
 * @return list<array<string,mixed>> rows without correct_choice
 */
function quiz_load_questions(int $categoryId, string $difficulty, int $count): array
{
    if ($count < 1) {
        return [];
    }
    $n = (int) $count;
    $sql = 'SELECT id, question_text, choice_a, choice_b, choice_c, choice_d
            FROM questions
            WHERE category_id = :c AND difficulty = :d
            ORDER BY RAND() LIMIT ' . $n;
    $st = db()->prepare($sql);
    $st->bindValue('c', $categoryId, PDO::PARAM_INT);
    $st->bindValue('d', $difficulty, PDO::PARAM_STR);
    $st->execute();

    $rows = $st->fetchAll();
    if (!is_array($rows)) {
        return [];
    }

    return $rows;
}

/**
 * @param list<int> $ids
 * @return array<int,string> id => correct letter a-d
 */
function quiz_fetch_answers_for_ids(array $ids, int $categoryId, string $difficulty): array
{
    if ($ids === []) {
        return [];
    }
    $idList = array_map('intval', $ids);
    $idList = array_filter($idList, static fn (int $x) => $x > 0);
    if ($idList === []) {
        return [];
    }
    $placeholders = implode(',', array_fill(0, count($idList), '?'));
    $sql = "SELECT id, correct_choice FROM questions
            WHERE id IN ($placeholders) AND category_id = ? AND difficulty = ?";
    $params = $idList;
    $params[] = $categoryId;
    $params[] = $difficulty;
    $st = db()->prepare($sql);
    $st->execute($params);
    $out = [];
    while ($row = $st->fetch()) {
        $out[(int) $row['id']] = (string) $row['correct_choice'];
    }

    return $out;
}

/**
 * @param array<int|string,mixed> $postAnswers from $_POST['answer']
 */
function quiz_score_answers(array $ids, array $postAnswers, array $correctMap): int
{
    $score = 0;
    foreach ($ids as $id) {
        $i = (int) $id;
        $sub = $postAnswers[$i] ?? $postAnswers[(string) $i] ?? '';
        $got = normalize_choice((string) $sub);
        if ($got !== null && isset($correctMap[$i]) && $got === $correctMap[$i]) {
            $score++;
        }
    }

    return $score;
}
