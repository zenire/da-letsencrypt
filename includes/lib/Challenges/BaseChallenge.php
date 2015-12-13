<?php

namespace DirectAdmin\LetsEncrypt\Lib\Challenges;

use DirectAdmin\LetsEncrypt\Lib\Domain;

/**
 * Class BaseChallenge
 *
 * @package DirectAdmin\LetsEncrypt\Lib\Challenges
 */
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
     * @param Domain $domain Main Domain object
     * @param string $challengeDomain Domain or subdomain to be challenged
     */
    function __construct($challenge, $location, $domain, $challengeDomain) {
        $this->type = $challenge->type;
        $this->status = $challenge->status;
        $this->uri = $challenge->uri;
        $this->token = $challenge->token;

        $this->location = $location;

        $this->domain = $domain;
        $this->challengeDomain = $challengeDomain;
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