<?php

function move($directoryFrom, $directoryTo) {
    foreach (scandir($directoryFrom) as $file) {
        if (in_array($file, array('.', '..'))) {
            continue;
        }

        $fromPath = $directoryFrom . DIRECTORY_SEPARATOR . $file;
        $toPath = $directoryTo . DIRECTORY_SEPARATOR . $file;

        if (is_dir($fromPath)) {
            mkdir($toPath);

            move($fromPath, $toPath);
        } else {
            rename($fromPath, $toPath);
        }

    }
}

function rrmdir($dir) {
    if (!file_exists($dir)) {
        return true;
    }

    if (!is_dir($dir)) {
        return unlink($dir);
    }

    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }

        if (!rrmdir($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }

    }

    return rmdir($dir);
}