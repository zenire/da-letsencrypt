<?php

global $_POST, $_GET;

use DirectAdmin\LetsEncrypt\Lib\Logger;

ini_set('log_errors', 1);
ini_set('error_log', dirname(__DIR__) . '/logs/php-error.log');

require_once dirname(__DIR__) . '/vendor/autoload.php';

parse_str(getenv('QUERY_STRING'), $_GET);
parse_str(getenv('POST'), $_POST);

if (!isset($_SERVER['SESSION_SELECTED_DOMAIN']) || empty($_SERVER['SESSION_SELECTED_DOMAIN'])) {
    $log = new Logger();
    $log->error('Please select a domain first at the DirectAdmin homepage.');
}