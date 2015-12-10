<?php

namespace DirectAdmin\LetsEncrypt\Lib\Utility;

/**
 * Class StorageTrait
 *
 * @method string getStoragePath
 *
 * @package DirectAdmin\LetsEncrypt\Lib\Utility
 */
trait StorageTrait {

    /**
     * Write to the storage, always overwrites files
     *
     * @param $fileName
     * @param $content
     */
    public function writeToStorage($fileName, $content) {
        $path = $this->getStoragePath();

        if (!file_exists($path)) {
            mkdir($path);
        }

        file_put_contents($path . DIRECTORY_SEPARATOR . $fileName, $content);
    }

    /**
     * Get from storage
     *
     * @param $fileName
     *
     * @return string
     */
    public function getFromStorage($fileName) {
        return file_get_contents($this->getStoragePath() . DIRECTORY_SEPARATOR . $fileName);
    }

    /**
     * Check if a file or folder exists
     *
     * @param $fileName
     *
     * @return string
     */
    public function existsInStorage($fileName) {
        return file_exists($this->getStoragePath() . DIRECTORY_SEPARATOR . $fileName);
    }
}