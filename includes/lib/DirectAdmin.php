<?php

namespace DirectAdmin\LetsEncrypt\Lib;

/**
 * Class DirectAdmin
 *
 * @package DirectAdmin\LetsEncrypt\Lib
 */
class DirectAdmin {

    /**
     * Static method to get a preconfigured HTTPSocket
     *
     * @return HTTPSocket
     */
    static function get() {
        $address = (isset($_SERVER['SSL']) && $_SERVER['SSL'] == "1") ? 'ssl://127.0.0.1' : '127.0.0.1';

        $socket = new HTTPSocket();
        $socket->connect($address, 2222);
        $socket->set_login('admin');

        return $socket;
    }
}