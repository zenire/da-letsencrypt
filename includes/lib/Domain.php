<?php

namespace DirectAdmin\LetsEncrypt\Lib;

use Crypt_RSA;
use DirectAdmin\LetsEncrypt\Lib\Utility\ConfigurableTrait;
use DirectAdmin\LetsEncrypt\Lib\Utility\StorageTrait;
use Kelunik\Acme\KeyPair;

/**
 * Class Domain
 *
 * @package DirectAdmin\LetsEncrypt\Lib
 */
class Domain {

    use ConfigurableTrait;

    private $domain;
    private $subdomains;

    private $certificates;

    /** @var  KeyPair */
    public $domainKeys;
    public $account;

    /**
     * @param String $domain Domain name
     * @param Account $account Account
     */
    function __construct($domain, $subdomains, $account) {
        $this->domain = $domain;
        $this->subdomains = $subdomains;
        $this->account = $account;
    }

    /**
     * Create domain RSA keys
     *
     * @return KeyPair
     * @throws \Exception
     */
    public function createKeys() {
        $rsa = new Crypt_RSA();

        $keys = $rsa->createKey(4096);

        if ($keys['partialkey'] === false) {
            $this->domainKeys = new KeyPair($keys['privatekey'], $keys['publickey']);
        } else {
            throw new \Exception('CPU was to slow, we\'ve not yet coded this part.');
        }

        return $this->domainKeys;
    }

    /**
     * Request certificate at ACME
     *
     * @param KeyPair|null $domainKeys
     * @return array
     * @throws \Exception
     * @throws \Kelunik\Acme\AcmeException
     */
    public function requestCertificate($domainKeys = null) {
        if ($domainKeys == null) {
            if ($this->domainKeys == null) {
                $this->createKeys();
            } else {
                $domainKeys = $this->domainKeys;
            }
        }

        $domains = (array) $this->getDomain();
        $domains += $this->getSubdomains();

        $location = $this->account->acme->requestCertificate($domainKeys, $domains);
        $this->certificates = $this->account->acme->pollForCertificate($location);

        return $this->certificates;
    }

    /**
     * Apply certificates to DirectAdmin
     *
     * @return bool
     * @throws \Exception
     */
    public function applyCertificates() {
        $sock = new HTTPSocket();
        $sock->connect('127.0.0.1', 2222);
        $sock->set_login('admin');
        $sock->set_method('POST');
        $sock->query('/CMD_API_SSL', [
            'domain' => $this->getDomain(),
            'action' => 'save',
            'type' => 'paste',
            'certificate' => $this->domainKeys->getPrivate() . PHP_EOL . $this->getCertificate(),
            'submit' => 'Save'
        ]);
        $result = $sock->fetch_parsed_body();

        if ($result['error'] != 0) {
            throw new \Exception('Error while executing first API request: ' . $result['details']);
        }

        $sock = new HTTPSocket();
        $sock->connect('127.0.0.1', 2222);
        $sock->set_login('admin');
        $sock->set_method('POST');
        $sock->query('/CMD_API_SSL', [
            'domain' => $this->getDomain(),
            'action' => 'save',
            'type' => 'cacert',
            'active' => 'yes',
            'cacert' => implode("\n", $this->getCertificateAuthorityCertificates()),
            'submit' => 'Save'
        ]);
        $result = $sock->fetch_parsed_body();

        if ($result['error'] != 0) {
            throw new \Exception('Error while executing second API request: ' . $result['details']);
        }

        return true;
    }

    /**
     * Get the domains certificate
     *
     * @return string
     */
    public function getCertificate() {
        return trim($this->certificates[0]);
    }

    /**
     * Get a array of certificate authority certificates
     *
     * @return string[]
     */
    public function getCertificateAuthorityCertificates() {
        return array_map('trim', array_slice($this->certificates, 1));
    }

    /**
     * Get domain's name
     *
     * @return String
     */
    public function getDomain() {
        return $this->domain;
    }

    /**
     * Get list of subdomains
     *
     * @return Array
     */
    public function getSubdomains() {
        return $this->subdomains;
    }

    /**
     * Get path to domain root
     *
     * @return string
     */
    public function getPath() {
        return DIRECTORY_SEPARATOR . 'home' . DIRECTORY_SEPARATOR . $this->account->getUsername() . DIRECTORY_SEPARATOR . 'domains' . DIRECTORY_SEPARATOR . $this->getDomain();
    }

    /**
     * Get path to domain storage
     *
     * @return string
     */
    public function getStoragePath() {
        return $this->getPath() . DIRECTORY_SEPARATOR . '.letsencrypt';
    }

    function __debugInfo() {
        return [
            'account' => $this->account,
            'domain' => $this->domain
        ];
    }
}
