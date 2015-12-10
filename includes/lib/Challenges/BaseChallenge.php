<?php

namespace DirectAdmin\LetsEncrypt\Lib\Challenges;

use DirectAdmin\LetsEncrypt\Lib\Domain;

class BaseChallenge {

    protected $type;
    protected $status;
    protected $uri;
    protected $token;

    protected $location;

    protected $domain;

    /**
     * Challenge constructor
     *
     * @param \stdClass $challenge Challenge properties from ACME
     * @param string $location Location
     * @param Domain $domain Domain which we need to challenge
     */
    function __construct($challenge, $location, $domain) {
        $this->type = $challenge->type;
        $this->status = $challenge->status;
        $this->uri = $challenge->uri;
        $this->token = $challenge->token;

        $this->location = $location;

        $this->domain = $domain;
    }

    public function solve() {
        throw new \Exception('Challenge is unsolvable');
    }

    public function solvable() {
        return false;
    }

    function __debugInfo() {
        return [
            'type' => $this->type,
            'status' => $this->status,
            'uri' => $this->uri,
            'token' => $this->token,
            'location' => $this->location,
            'domain' => $this->domain,
            'solvable' => $this->solvable()
        ];
    }
}