<?php

namespace DirectAdmin\LetsEncrypt\Lib;

/**
 * Class Logger
 *
 * @package DirectAdmin\LetsEncrypt\Lib
 */
class Logger {

    private $lines = [];
    private $short = 'Successfully updated';

    private $errorShort = 'An error occurred';

    /**
     * Add a info line to the logger
     *
     * @param $line
     */
    public function info($line) {
        $this->lines[] = $line;
    }

    /**
     * Set a short for when the action succeess
     *
     * @param $short
     */
    public function short($short) {
        $this->short = $short;
    }

    /**
     * Set a short for when the action fails
     *
     * @param $short
     */
    public function setErrorShort($short) {
        $this->errorShort = $short;
    }

    /**
     * Add a error line to the logger, and by default exit page
     *
     * @param string $line Error line
     * @param string|null $short Use a custom short, instead of the $errorShort
     * @param bool $stop Stop page load using exit and render output
     */
    public function error($line, $short = null, $stop = true) {
        if ($short != null) {
            $this->short = $short;
        } else {
            $this->short = $this->errorShort;
        }

        $this->lines[] = '<b>' . $line . '</b>';

        if ($stop) {
            $this->output();
            exit(500);
        }
    }

    /**
     * Render output, will be printed
     *
     * @param bool|string $back
     */
    public function output($back = true) {
        if ($back === true) {
            $back = 'javascript: window.history.back();';
        }

        ?>
        <table width="100%" height="100%" cellspacing="0" cellpadding="5">
            <tbody><tr>
                <td valign="middle" align="center">
                    <p align="center"><?= $this->short; ?></p>
                </td>
            </tr>
            <tr>
                <td height="1" valign="middle" align="center">
                    <table width="50%">
                        <tbody>
                            <tr><td bgcolor="#C0C0C0"> </td></tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td valign="top" align="center">
                    <p align="center"><b>Details</b></p>
                    <p align="center">
                        <?= implode('<br />', $this->lines); ?>
                    </p>
                    <? if ($back): ?>
                        <p align="center"><a href="<?= $back; ?>">Back</a></p>
                    <? endif; ?>
                </td>
            </tr>
            </tbody>
        </table>
        <?php
    }
}