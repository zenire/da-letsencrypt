<?php

namespace DirectAdmin\LetsEncrypt\Lib\Utility;

/**
 * Class ConfigurableTrait
 *
 * @package DirectAdmin\LetsEncrypt\Lib\Utility
 */
trait ConfigurableTrait {

    use StorageTrait;

    private $config = [];
    private $initializedConfig = false;

    /**
     * Set or receive config variable
     *
     * @param string|null $key Key of entry
     * @param string|null $value
     *
     * @return string|null
     */
    public function config($key = null, $value = null) {
        if (!$this->initializedConfig) {
            $this->initializeConfig();
        }

        if ($value == null) {
            return (($key == null) ? $this->config : $this->config[$key]);
        } else {
            $this->config[$key] = $value;

            $this->saveConfig();
        }
    }

    /**
     * Delete a key from the configuration and save it
     *
     * @param $key
     */
    public function deleteConfigKey($key) {
        unset($this->config[$key]);

        $this->saveConfig();
    }

    /**
     * Initialize configuration to local variable
     */
    public function initializeConfig() {
        if ($this->existsInStorage('config.json')) {
            $this->config = json_decode($this->getFromStorage('config.json'), true);
        }

        $this->initializedConfig = true;
    }

    /**
     * Save configuration file to storage
     */
    public function saveConfig() {
        $this->writeToStorage('config.json', json_encode($this->config));
    }
}