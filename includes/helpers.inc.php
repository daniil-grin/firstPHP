<?php
function html($text)
{
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}
function htmlout($text)
{
    echo html($text);
}
function markdown2html($text)
{
    $text = html($text);
    // Полужирное начертание
    $text = preg_replace('/__(.+?)__/s', '<strong>$1</strong>', $text);
    $text = preg_replace('/\*\*(.+?)\*\*/s', '<strong>$1</strong>', $text);
    // Курсивное начертание
    $text = preg_replace('/_(^_]+)_/', '<em>$1</em>', $text);
    $text = preg_replace('/\*([^\*]+)\*/', '<em>$1</em>', $text);
    return $text;
}
function markdownhtml($text)
{
    echo markdown2html($text);
}
