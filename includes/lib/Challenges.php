<?php

namespace DirectAdmin\LetsEncrypt\Lib;

use DirectAdmin\LetsEncrypt\Lib\Challenges\BaseChallenge;

class Challenges {

    /** @var Domain */
    private $domain;
    /** @var array  */
    private $subdomains;

    private $status = [];
    private $expires = [];

    /** @var array[BaseChallenge[]] */
    private $challenges = [];
    /** @var array[BaseChallenge[]] */
    private $solvableChallenges = [];
    private $location = [];
    private $combinations = [];

    /**
     * Class constructor
     *
     * @param Domain $domain
     * @param array  $subdomains
     */
    function __construct(Domain $domain, array $subdomains = []) {
        $this->domain = $domain;
        $this->subdomains = $subdomains;
    }

    /**
     * Receive challanges from ACME
     *
     * @return string Challenges
     */
    public function receiveChallenges() {
        $domains = array_merge((array) $this->domain->getDomain(), $this->subdomains);

        foreach ($domains as $domain) {
            list($this->location[$domain], $response) = \amp\wait($this->domain->account->acme->requestChallenges($domain));

            $this->combinations[$domain] = $response->combinations;
            $this->status[$domain] = $response->status;
            $this->expires[$domain] = $response->expires;

            foreach ($response->challenges as $challenge) {
                $challengeClassName = '\\DirectAdmin\\LetsEncrypt\\Lib\\Challenges\\';
                $challengeClassName .= ucfirst(strtolower(preg_replace("/[^A-Za-z0-9 ]/", '', $challenge->type))) . 'Challenge';

                if (class_exists($challengeClassName)) {
                    $this->challenges[$domain][] = new $challengeClassName($challenge, $this->location[$domain], $this->domain, $domain);
                } else {
                    $this->challenges[$domain][] = new BaseChallenge($challenge, $this->location[$domain], $this->domain, $domain);
                }
            }
        }

        return $this->challenges;
    }

    /**
     * Find the solvable challenges
     *
     * @return array
     * @throws \Exception
     */
    public function solvableChallenges() {
        if (empty($this->challenges)) {
            $this->receiveChallenges();
        }

        $domains = array_merge((array) $this->domain->getDomain(), $this->subdomains);

        foreach ($domains as $domain) {
            $this->solvableChallenges[$domain] = [];

            foreach ($this->challenges[$domain] as $i => $challenge) {
                if ($challenge->solvable()) {
                    $this->solvableChallenges[$domain][] = $i;
                }
            }

            foreach ($this->solvableChallenges[$domain] as $i => $challenge) {
                if (!in_array([$challenge], $this->combinations[$domain])) {
                    unset($this->solvableChallenges[$domain][$i]);
                }
            }

            if (empty($this->solvableChallenges[$domain])) {
                throw new \Exception('We didn\'t receive any challenge we can solve for ' . $domain);
            }
        }

        return $this->solvableChallenges;
    }

    /**
     * Solve a challenge
     *
     * @throws \Exception
     */
    public function solveChallenge() {
        if (empty($this->solvableChallenges)) {
            $this->solvableChallenges();
        }

        $domains = array_merge((array) $this->domain->getDomain(), $this->subdomains);

        foreach ($domains as $domain) {
            $challenge = $this->challenges[$domain][reset($this->solvableChallenges[$domain])];

            if ($challenge->solvable()) {
                try {
                    $challenge->solve();
                } catch (\Exception $e) {
                    throw new \Exception('Exception while solving challenge for ' . $domain
                        . ': ' .$e->getMessage(), $e->getCode(), $e);
                }
            } else {
                throw new \Exception('Defined unsolvable challenge for ' . $domain);
            }
        }
    }

    function __debugInfo() {
        return [
            'domain' => $this->domain,
            'status' => $this->status,
            'expires' => $this->expires,
            'challenges' => $this->challenges,
            'solvableChallenges' => $this->solvableChallenges,
            'location' => $this->location,
            'combinations' => $this->combinations
        ];
    }
}
