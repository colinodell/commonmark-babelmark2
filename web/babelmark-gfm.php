<?php

use Composer\InstalledVersions;
use League\CommonMark\GithubFlavoredMarkdownConverter;

require_once __DIR__.'/../vendor/autoload.php';

function getVersion() {
    return InstalledVersions::getPrettyVersion('league/commonmark');
}

if (!isset($_GET['text'])) {
    $markdown = '';
} else {
    $markdown = $_GET['text'];
    if (mb_strlen($markdown) > 1000) {
        http_response_code(413); // Request Entity Too Large
        $markdown = "Input must be less than 1,000 characters";
    }
}

$converter = new GithubFlavoredMarkdownConverter();
$html = (string) $converter->convertToHtml($markdown);

header('Content-Type: application/json');
echo json_encode([
    'name' => 'league/commonmark GFM',
    'version' => getVersion(),
    'html' => $html,
]);
