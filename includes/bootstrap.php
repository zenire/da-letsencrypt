<?php

//foreach (glob(__DIR__ . '/lib/*') as $file) {
//    require_once $file;
//}

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

if (isset($domain)) {
    $domainPath = $_SERVER['HOME']  . DIRECTORY_SEPARATOR . 'domains' . DIRECTORY_SEPARATOR . $domain;
    $domainSettingsPath = $domainPath . DIRECTORY_SEPARATOR . '.letsencrypt';

    if (!file_exists($domainSettingsPath)) {
        mkdir($domainSettingsPath);
    }
}