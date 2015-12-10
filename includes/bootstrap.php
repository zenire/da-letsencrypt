<?php

global $_POST, $_GET;

ini_set('log_errors', 1);
ini_set('error_log', dirname(__DIR__) . '/logs/php-error.log');

require_once dirname(__DIR__) . '/vendor/autoload.php';

parse_str(getenv('QUERY_STRING'), $_GET);
parse_str(getenv('POST'), $_POST);