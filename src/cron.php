<?php

require_once 'includes/functions.php';

/**
 * Receive latest version
 */
$headers = get_headers('https://github.com/Petertjuh360/da-letsencrypt/releases/latest');

foreach ($headers as $header) {
    $header = explode(': ', $header, 2);

    if ($header[0] == 'Location') {
        $location = $header[1];
    }
}

$tagFound = false;
$version = null;

foreach (explode('/', $location) as $locationPart) {
    if ($version == null && $tagFound == true) {
        $version = $locationPart;
        break;
    }

    if ($locationPart == 'tag') {
        $tagFound = true;
    }
}

/**
 * Download and extract latest .zip
 */
$extract = '/tmp/archive' . time() . rand();
$archive = $extract . '.zip';
$downloadUrl = 'https://github.com/Petertjuh360/da-letsencrypt/archive/' . $version . '.zip';

file_put_contents($archive, file_get_contents($downloadUrl));

$zip = new ZipArchive();
$zip->open($archive);
$zip->extractTo($extract);

/**
 * Move all files to root
 */
move($extract . DIRECTORY_SEPARATOR . 'da-letsencrypt-1.0', $extract);
rrmdir($extract . DIRECTORY_SEPARATOR . 'da-letsencrypt-1.0');

/**
 * Run composer
 */
// TODO

/**
 * Extract folders to .tar.gz
 */
$tar = new PharData($extract . '.tar');

$tar->buildFromDirectory($extract);
$tar->compress(Phar::GZ);

/**
 * Write latest version to file and move tar.gz to download
 */
$fh = fopen( 'version', 'w' );
fwrite($fh, $version);
fclose($fh);

rename($extract . '.tar.gz', __DIR__ . DIRECTORY_SEPARATOR . 'download.tar.gz');

/**
 * Clean up /tmp directory
 */
rrmdir($extract);
unlink($archive);
unlink($extract . '.tar'); //why is this one created?
