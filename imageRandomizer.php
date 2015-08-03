<?php

/* =============================================================================
 * IMAGE RANDOMIZER
 * -----------------------------------------------------------------------------
 * version 1.1.0
 * by MetalTxus (Jesus Miguel Cruz Cana)
 * https://github.com/jesuscc1993
 * ============================================================================= */


/* =============================================================================
 * Configuration
 * ============================================================================= */

// Base path where the file will look for the images
$GLOBALS['path'] = '.';
// Time, in seconds, an image will be allowed to remain cached in the browser
$GLOBALS['maxCachedTime'] = 60;

/* =============================================================================
 * end of configuration
 * ============================================================================= */

$img = "";
$images = array();
$GLOBALS['contentTypes'] = new stdClass();
$GLOBALS['contentTypes']->gif = 'image/gif';
$GLOBALS['contentTypes']->png = 'image/png';
$GLOBALS['contentTypes']->jpg = 'image/jpeg';
$GLOBALS['contentTypes']->jpeg = 'image/jpeg';
start();


function start() {
    $path = $GLOBALS['path'];
    if (substr($path, -1) !== '/') {
        $path = $path . '/';
    }
    if (isset($_GET['id'])) {
        $path = $path . '/' . $_GET['id'] . '/';
    }

    // Get images
    $directory = opendir($path);
    while (false !== $file = readdir($directory)) {
        if (isset($GLOBALS['contentTypes']->{getFileExtension($file)})) {
            $images[] = $file;
        }
    }
    closedir($directory);

    // Randomize image
    if (count($images) > 0) {
        $imageNumber = time() % count($images);
        $img = $path . $images[$imageNumber];
        setHeaders($img);
        readfile($img);
    } else {
        echo 'No images were found on the path "' . $path . '".';
    }
}


function getFileExtension($file) {
    $fileInfo = pathinfo($file);
    $fileExtension = strtolower($fileInfo['extension']);
    return $fileExtension;
}


function getFileContentType($file) {
    $fileExtension = getFileExtension($file);
    $contentType = $GLOBALS['contentTypes']->{$fileExtension};

    if (!isset($contentType)) {
        $contentType = $GLOBALS['contentTypes']->png;
    }
    return $contentType;
}


function setHeaders($img) {
    header('Content-type: ' . getFileContentType($img));
    header('Cache-Control: max-age=' . $GLOBALS['maxCachedTime']);
    header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + $GLOBALS['maxCachedTime']));
}


function resizeImage($img) {
    $img = new Imagick($img);

    $width = 0;
    if (isset($_GET['width'])) {
        $width = $_GET['width'];
    }

    $height = 0;
    if (isset($_GET['height'])) {
        $height = $_GET['height'];
    }

    //$img->resizeImage($width, $height, Imagick::FILTER_CATROM, 0.5);
    $img->scaleImage($width, $height);     
    return $img;
}