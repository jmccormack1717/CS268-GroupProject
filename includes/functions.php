<?php

declare(strict_types=1);

/**
 * Escape output for HTML (text nodes and most attributes). Use context-appropriate
 * escaping for URLs or JavaScript when those are added later.
 */
function h(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Normalize correct answer key to a single lowercase letter a-d.
 * Returns null if invalid (caller should reject invalid input).
 */
function normalize_choice(?string $choice): ?string
{
    if ($choice === null) {
        return null;
    }
    $c = strtolower(trim($choice));
    if (strlen($c) === 1 && in_array($c, ['a', 'b', 'c', 'd'], true)) {
        return $c;
    }
    return null;
}

function redirect(string $location): void
{
    header('Location: ' . $location);
    exit;
}

/**
 * Shorten text for a table cell (avoids very long one-line questions).
 */
function preview_string(string $value, int $max = 80): string
{
    if (function_exists('mb_strimwidth')) {
        return mb_strimwidth($value, 0, $max, '...', 'UTF-8');
    }
    if (strlen($value) <= $max) {
        return $value;
    }
    $cut = $max - 3;
    if ($cut < 1) {
        return '...';
    }

    return rtrim(substr($value, 0, $cut)) . '...';
}
