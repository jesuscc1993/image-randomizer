<?php

/* =============================================================================
 * IMAGE RANDOMIZER
 * -----------------------------------------------------------------------------
 * version 1.0.0
 * by MetalTxus (Jesus Miguel Cruz Cana)
 * https://github.com/jesuscc1993
 * ============================================================================= */


/* =============================================================================
 * Configuration
 * ============================================================================= */

// Path where the file will look for the images
$path = '.';
// Time, in seconds, an image will be allowed to remain cached in the browser
$maxCachedTime = 60;

/* =============================================================================
 * end of configuration
 * ============================================================================= */

function getFileExtension($file) {
    $fileInfo = pathinfo($file);
    $fileExtension = strtolower($fileInfo['extension']);
    return $fileExtension;
}

if (substr($path, -1) !== '/') {
    $path = $path . '/';
}
if (isset($_GET['id'])) {
    $path = $path . '/' . $_GET['id'] . '/';
}

$contentTypes = new stdClass();
$contentTypes->gif = 'image/gif';
$contentTypes->png = 'image/png';
$contentTypes->jpg = 'image/jpeg';
$contentTypes->jpeg = 'image/jpeg';

$images = array();
$directory = opendir($path);
while (false !== $file = readdir($directory)) {
    if (isset($contentTypes->{getFileExtension($file)})) {
        $images[] = $file;
    }
}
closedir($directory);

if (count($images) > 0) {
    $imageNumber = time() % count($images);
    $img = $path . $images[$imageNumber];
    header('Content-type: ' . $contentTypes->{getFileExtension($img)});
    header('Cache-Control: max-age=' . $maxCachedTime);
    header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + $maxCachedTime));
    readfile($img);
} else {
    echo 'No images were found on the path "' . $path . '".';
}