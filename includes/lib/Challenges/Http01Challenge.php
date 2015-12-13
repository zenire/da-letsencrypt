<?php

namespace DirectAdmin\LetsEncrypt\Lib\Challenges;

/**
 * Class Http01Challenge
 *
 * @package DirectAdmin\LetsEncrypt\Lib\Challenges
 */
class Http01Challenge extends BaseChallenge {

    /**
     * Solve a HTTP-01 challenge
     *
     * @return bool
     */
    public function solve() {
        $payload = $this->domain->account->acme->generateHttp01Payload($this->token);

        $wwwCheck = explode('.', $this->challengeDomain, 2);

        if ($wwwCheck[0] === 'www') {
            // WWW, so use the path to without www
            $domainPath = $this->challengeDomain[1];
        } else {
            // without WWW, so use the normal domain
            $domainPath = $this->challengeDomain;
        }

        $subdomainCheck = explode('.', $domainPath, 2);

        if ($subdomainCheck[1] === $this->domain->getDomain()) {
            // The second key is the same as the domain, so we're on a subdomain

            $challengePath = $this->domain->getPath() . DIRECTORY_SEPARATOR . 'public_html' . DIRECTORY_SEPARATOR . $subdomainCheck[0] . DIRECTORY_SEPARATOR . '.well-known';
        } else {
            // Were not on a subdomain, use main domain
            $challengePath = $this->domain->getPath() . DIRECTORY_SEPARATOR . 'public_html' . DIRECTORY_SEPARATOR . '.well-known';
        }

        if (!file_exists($challengePath)) {
            mkdir($challengePath);

            if (defined('CRON')) {
                chown($challengePath, $this->domain->account->getUsername());
                chgrp($challengePath, $this->domain->account->getUsername());
            }
        }

        $challengePath .= DIRECTORY_SEPARATOR . 'acme-challenge';

        if (!file_exists($challengePath)) {
            mkdir($challengePath);

            if (defined('CRON')) {
                chown($challengePath, $this->domain->account->getUsername());
                chgrp($challengePath, $this->domain->account->getUsername());
            }
        }

        file_put_contents($challengePath . DIRECTORY_SEPARATOR . $this->token, $payload);

        if (defined('CRON')) {
            chown($challengePath . DIRECTORY_SEPARATOR . $this->token, $this->domain->account->getUsername());
            chgrp($challengePath . DIRECTORY_SEPARATOR . $this->token, $this->domain->account->getUsername());
        }

        $this->domain->account->acme->selfVerify($this->challengeDomain, $this->token, $payload);

        $this->domain->account->acme->answerChallenge($this->uri, $payload);
        $this->domain->account->acme->pollForChallenge($this->location);

        unlink($challengePath . DIRECTORY_SEPARATOR . $this->token);

        $isChallengePathEmpty = !(new \FilesystemIterator($challengePath))->valid();

        if ($isChallengePathEmpty) {
            rmdir($challengePath);

            $challengePath = dirname($challengePath);

            $isChallengePathEmpty = !(new \FilesystemIterator($challengePath))->valid();

            if ($isChallengePathEmpty) {
                rmdir($challengePath);
            }
        }

        return true;
    }

    /**
     * We're able to solve HTTP-01 challenges
     *
     * @return bool
     */
    public function solvable() {
        return true;
    }
}