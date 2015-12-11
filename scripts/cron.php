<?php
use DirectAdmin\LetsEncrypt\Lib\Account;
use DirectAdmin\LetsEncrypt\Lib\Challenges;
use DirectAdmin\LetsEncrypt\Lib\Domain;

define('CRON', true);

require_once dirname(__DIR__) . '/includes/bootstrap.php';

$usersPath = '/usr/local/directadmin/data/users/';

// Get all users
$users = scandir($usersPath);

// Loop through all users
foreach ($users as $user) {
    // Check if it's not some junk thingy
    if (in_array($user, ['.', '..']) || empty($user)) {
        continue;
    }

    // Create account object
    $account = new Account($user, null, 'https://acme-staging.api.letsencrypt.org/directory');

    // Is there a config file present?
    if (!$account->existsInStorage('config.json')) {
        echo 'Skipped user ' . $account->getUsername() . PHP_EOL;

        continue;
    }

    echo 'Processing user ' . $account->getUsername() . PHP_EOL;

    if (!$account->loadKeys()) {
        echo 'No keys present at user ' . $account->getUsername() . PHP_EOL;

        continue;
    }

    $account->setEmail($account->config('email'));

    // Get all domains of the user
    $domains = file_get_contents($usersPath . DIRECTORY_SEPARATOR . $account->getUsername() . DIRECTORY_SEPARATOR . 'domains.list');

    // Loop through all domains of the user
    foreach (explode("\n", $domains) as $domain) {
        if (empty($domain)) {
            continue;
        }

        // Replace the $domain with our Domain object,
        $domain = new Domain($domain, $account);

        if (!$domain->existsInStorage('config.json')) {
            echo 'Skipped domain ' . $domain->getDomain() . PHP_EOL;

            continue;
        }

        echo 'Processing domain ' . $domain->getDomain() . PHP_EOL;

        // Check if a renew is required, if everything needs to be checked within 10 days or in the past
        if (strtotime($domain->config('expire')) - time() >= 10 * 86400) {
            echo 'Domain ' . $domain->getDomain() . ' doesn\'t need a reissue' . PHP_EOL;

            continue;
        }

        try {
            $challenges = new Challenges($domain);
            $challenges->solveChallenge();

            $domain->createKeys();
            $domain->requestCertificate(null, $domain->config('subdomains'));

            $domain->applyCertificates();

            $domain->config('domain', $domain->getDomain());
            $domain->config('subdomains', $domain->getSubdomains());

            $domain->config('status', 'applied to DirectAdmin (renewed)');
            $domain->config('expire', date('Y-m-d', strtotime('+50 days')));

            echo 'Reissued domain ' . $domain->getDomain() . ' with success.' . PHP_EOL;
        } catch(\Exception $e) {
            $log->error($e->getMessage());
        }
    }
}

// Rewrite and restart HTTPD files
$queue = 'action=rewrite&value=httpd' . PHP_EOL;
$queue .= 'action=httpd&value=graceful' . PHP_EOL;

file_put_contents('/usr/local/directadmin/data/task.queue', $queue, FILE_APPEND);