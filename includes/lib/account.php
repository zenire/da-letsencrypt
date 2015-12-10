<?php

namespace DirectAdmin\LetsEncrypt\Lib;

use Crypt_RSA;
use Kelunik\Acme\AcmeClient;
use Kelunik\Acme\AcmeService;
use Kelunik\Acme\KeyPair;

class Account {

    private $username;
    private $email;

    private $keyPair;

    private $acmeServer;

    private $publicKeyPath;
    private $privateKeyPath;

    /** @var  AcmeService */
    public $acme;

    function __construct($username, $email, $acmeServer) {
        $this->username = $username;
        $this->email = $email;

        $this->acmeServer = $acmeServer;

        if (!$this->loadKeys()) {
            $this->createKeys();

            $this->register();
        }
    }

    /**
     * Check if keys exists, and when they does load keys into local variables.
     *
     * @return bool
     */
    public function loadKeys() {
        $this->publicKeyPath = $this->getSettingsPath() . DIRECTORY_SEPARATOR . 'public.key';
        $this->privateKeyPath = $this->getSettingsPath() . DIRECTORY_SEPARATOR . 'private.key';

        if (!file_exists($this->publicKeyPath) || !file_exists($this->privateKeyPath)) {
            return false;
        } else {
            $publicKey = file_get_contents($this->publicKeyPath);
            $privateKey = file_get_contents($this->privateKeyPath);

            $this->keyPair = new KeyPair($privateKey, $publicKey);

            $this->acme = new AcmeService(new AcmeClient($this->acmeServer, $this->keyPair), $this->keyPair);

            return true;
        }
    }

    /**
     * Get path to user root
     *
     * @return string
     */
    public function getPath() {
        return DIRECTORY_SEPARATOR . 'home' . DIRECTORY_SEPARATOR . $this->username;
    }

    /**
     * Get path to users Let's Encrypt dir
     *
     * @return string
     */
    public function getSettingsPath() {
        return DIRECTORY_SEPARATOR . 'home' . DIRECTORY_SEPARATOR . $this->username . DIRECTORY_SEPARATOR . '.letsencrypt';
    }

    /**
     * Create and save a key pair for user
     *
     * @return KeyPair
     * @throws \Exception
     */
    public function createKeys() {
        $rsa = new Crypt_RSA();

        $keys = $rsa->createKey(4096);

        if ($keys['partialkey'] === false) {
            $this->keyPair = new KeyPair($keys['privatekey'], $keys['publickey']);

            file_put_contents($this->publicKeyPath, $this->keyPair->getPublic());
            file_put_contents($this->privateKeyPath, $this->keyPair->getPrivate());
        } else {
            throw new \Exception('CPU was to slow, we\'ve not yet coded this part.');
        }

        $this->acme = new AcmeService(new AcmeClient($this->acmeServer, $this->keyPair), $this->keyPair);

        return $this->keyPair;
    }

    /**
     * Register user at ACME
     *
     * @throws \Kelunik\Acme\AcmeException
     */
    public function register() {
        $this->acme->register($this->email);
    }

    /**
     * Get username of account
     *
     * @return string
     */
    public function getUsername() {
        return $this->username;
    }

    function __debugInfo() {
        return [
            'acme' => 'hidden',
            'username' => $this->username,
            'email' => $this->email,
            'keyPair' => 'hidden',
            'acmeServer' => $this->acmeServer,
            'publicKeyPath' => $this->publicKeyPath,
            'privateKeyPath' => $this->privateKeyPath
        ];
    }
}