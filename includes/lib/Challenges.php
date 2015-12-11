<?php

namespace DirectAdmin\LetsEncrypt\Lib;

use DirectAdmin\LetsEncrypt\Lib\Challenges\BaseChallenge;

class Challenges {

    private $domain;

    private $status;
    private $expires;

    /** @var BaseChallenge[] */
    private $challenges = [];
    /** @var BaseChallenge[] */
    private $solvableChallenges = [];
    private $location;
    private $combinations = [];

    /**
     * Class constructor
     *
     * @param Domain $domain
     */
    function __construct($domain) {
        $this->domain = $domain;
    }

    /**
     * Receive challanges from ACME
     *
     * @return string Challenges
     */
    public function receiveChallenges() {
        list($this->location, $response) = $this->domain->account->acme->requestChallenges($this->domain->getDomain());

        $this->combinations = $response->combinations;
        $this->status = $response->status;
        $this->expires = $response->expires;

        foreach ($response->challenges as $challenge) {
            $challengeClassName = '\\DirectAdmin\\LetsEncrypt\\Lib\\Challenges\\';
            $challengeClassName .= ucfirst(strtolower(preg_replace("/[^A-Za-z0-9 ]/", '', $challenge->type))) . 'Challenge';

            if (class_exists($challengeClassName)) {
                $this->challenges[] = new $challengeClassName($challenge, $this->location, $this->domain);
            } else {
                $this->challenges[] = new BaseChallenge($challenge, $this->location, $this->domain);
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

        $this->solvableChallenges = [];

        foreach ($this->challenges as $i => $challenge) {
            if ($challenge->solvable()) {
                $this->solvableChallenges[] = $i;
            }
        }

        foreach ($this->solvableChallenges as $i => $challenge) {
            if (!in_array([$challenge], $this->combinations)) {
                unset($this->solvableChallenges[$i]);
            }
        }

        if (empty($this->solvableChallenges)) {
            throw new \Exception('We didn\'t receive any challenge we can solve.');
        }

        return $this->solvableChallenges;
    }

    /**
     * Solve a challenge
     *
     * @param int|null $which Which challenge do we need to solve?
     * @throws \Exception
     */
    public function solveChallenge($which = null) {
        if ($which == null) {
            if (empty($this->solvableChallenges)) {
                $this->solvableChallenges();
            }

            $challenge = $this->challenges[reset($this->solvableChallenges)];
        } else {
            $challenge = $this->challenges[$which];
        }

        if ($challenge->solvable()) {
            $challenge->solve();
        } else {
            throw new \Exception('Defined unsolvable challenge');
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