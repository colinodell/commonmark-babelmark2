<?php
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\GithubFlavoredMarkdownConverter;

require_once __DIR__.'/../vendor/autoload.php';

function getVersion() {
    if (defined('League\CommonMark\CommonMarkConverter::VERSION')) {
        $version = CommonMarkConverter::VERSION;
        if (preg_match('/^[\d\.]+$/', $version) === 1) {
            return $version;
        }
    }

    $lock = json_decode(file_get_contents(__DIR__.'/../composer.lock'), true);
    foreach ($lock['packages'] as $package) {
        if ($package['name'] === 'league/commonmark') {
            return $package['version'];
        }
    }

    if (isset($version)) {
        return $version;
    }

    return 'latest';
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
$html = $converter->convertToHtml($markdown);

header('Content-Type: application/json');
echo json_encode([
    'name' => 'league/commonmark GFM',
    'version' => getVersion(),
    'html' => $html,
]);
