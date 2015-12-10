<?php

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

echo $version;