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

        $challengePath = $this->domain->getPath() . DIRECTORY_SEPARATOR . 'public_html' . DIRECTORY_SEPARATOR . '.well-known';

        if (!file_exists($challengePath)) {
            mkdir($challengePath);
        }

        $challengePath .= DIRECTORY_SEPARATOR . 'acme-challenge';

        if (!file_exists($challengePath)) {
            mkdir($challengePath);
        }

        file_put_contents($challengePath . DIRECTORY_SEPARATOR . $this->token, $payload);

        $this->domain->account->acme->selfVerify($this->domain->getDomain(), $this->token, $payload);

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