<?php

function loadLibrary($dir) {
    foreach (glob($dir) as $file) {
        if (is_dir($file)) {
            loadLibrary($file . '/*');
        } else {
            require_once $file;
        }
    }
}

loadLibrary(__DIR__ . '/lib/*');

require_once dirname(__DIR__) . '/vendor/autoload.php';

ini_set('log_errors', 1);
ini_set('error_log', dirname(__DIR__) . '/logs/php-error.log');

parse_str(getenv('QUERY_STRING'));
parse_str(getenv('POST'));

$accountPath = $_SERVER['HOME'];
$accountSettingsPath = $accountPath . DIRECTORY_SEPARATOR . '.letsencrypt';
if (!file_exists($accountSettingsPath)) {
    mkdir($accountSettingsPath);
}

