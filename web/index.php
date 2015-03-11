<?php
use League\CommonMark\CommonMarkConverter;

require_once __DIR__.'/../vendor/autoload.php';

function getVersion() {
    $lock = json_decode(file_get_contents(__DIR__.'/../composer.lock'), true);
    foreach ($lock['packages'] as $package) {
        if ($package['name'] === 'league/commonmark') {
            return $package['version'];
        }
    }

    return 'latest';
}

$markdown = $_GET['text'];

$converter = new CommonMarkConverter();
$html = $converter->convertToHtml($markdown);

header('Content-Type: application/json');
echo json_encode([
    'name' => 'league/commonmark',
    'version' => getVersion(),
    'html' => $html,
]);
