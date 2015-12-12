<?php

namespace DirectAdmin\LetsEncrypt\Lib;

/**
 * Class Config
 * A wrapper for DirectAdmin's config files
 *
 * @package DirectAdmin\LetsEncrypt\Lib
 */
class Config {

    private $path;
    private $config = [];
    private $initialized = false;

    /**
     * Initialize config class
     *
     * @param null $path
     */
    function __construct($path = null) {
        if ($path == null) {
            $this->path = '/usr/local/directadmin/conf/da-letsencrypt.conf';
        } else {
            $this->path = $path;
        }
    }

    /**
     * Initialize config file into $this->config
     */
    public function initialize() {
        $configString = file_get_contents($this->path);

        foreach (explode("\n", $configString) as $configLine) {
            if (empty($configLine)) {
                continue;
            }

            list($configKey, $configValue) = explode('=', $configLine, 2);

            $this->config[$configKey] = $configValue;
        }

        $this->initialized = true;
    }

    /**
     * Set or receive config variable
     *
     * @param string|null $key Key of entry
     * @param string|null $value
     *
     * @return string|null
     */
    public function config($key = null, $value = null) {
        if (!$this->initialized) {
            $this->initialize();
        }

        if ($value == null) {
            return (($key == null) ? $this->config : $this->config[$key]);
        } else {
            $this->config[$key] = $value;

            $this->save();
        }
    }

    /**
     * Save current configuration
     */
    public function save() {
        $configString = '';

        foreach ($this->config as $configKey => $configValue) {
            $configString .= $configKey . '=' . $configValue . PHP_EOL;
        }

        file_put_contents($this->path, $configString);
    }
}