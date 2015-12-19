<?php

global $_POST, $_GET;

use DirectAdmin\LetsEncrypt\Lib\Logger;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$servers = [
    'live' => 'https://acme-v01.api.letsencrypt.org/directory',
    'staging' => 'https://acme-staging.api.letsencrypt.org/directory'
];

if (!defined('CRON')) {
    parse_str(getenv('QUERY_STRING'), $_GET);
    parse_str(getenv('POST'), $_POST);

    if (!isset($_SERVER['SESSION_SELECTED_DOMAIN']) || empty($_SERVER['SESSION_SELECTED_DOMAIN'])) {
        if ($_SERVER['RUNNING_AS'] != 'admin') {
            $log = new Logger();
            $log->error('Please select a domain first at the DirectAdmin homepage.');
        }
    }
}