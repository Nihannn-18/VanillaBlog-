<?php
// Include database (if needed) and Markdown converter
require_once __DIR__.'/db.php';
require_once __DIR__.'/markdown.php';

header('Content-Type: text/html; charset=utf-8');

// Read JSON data sent from JavaScript (AJAX request)
$data = json_decode(file_get_contents('php://input'), true) ?: [];
$text = $data['text'] ?? '';

// Convert Markdown text to HTML and output
echo md_to_html($text);
