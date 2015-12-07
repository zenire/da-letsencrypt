<?php

namespace LetsEncrypt;

require_once dirname(__DIR__) . '/vendor/autoload.php';

ini_set('log_errors', 1);
ini_set('error_log', dirname(__DIR__) . '/logs/php-error.log');

parse_str(getenv('QUERY_STRING'));
parse_str(getenv('POST'));