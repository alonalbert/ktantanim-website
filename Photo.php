<?php

$filename = array_key_exists("image", $_GET) ? $_GET["image"] : $argv[1];
$size = array_key_exists("size", $_GET) ? $_GET["size"] : $argv[2];

$basename = basename($filename);
$dir = dirname($filename);
$targetDir = $dir . "/" . $size;
$targetFilename = $targetDir . "/" . $basename;

if (!file_exists($targetFilename)) {
    if (!is_dir($targetDir)) {
        mkdir($targetDir);
        if (!is_dir($targetDir)) {
            die('Cannot create directory');
        }
        chmod($targetDir, 0777);
    }
    $imageData = getimagesize($filename);
    $width = $imageData[0];
    $height = $imageData[1];
    $type = $imageData[2];
    $mime = $imageData["mime"];

    if ($width > $height) {
        $newWidth = $size;
        $newHeight = $height * $size / $width;
    }
    else {
        $newWidth = $width * $size / $height;
        $newHeight = $size;
    }
    switch ($type) {
        case IMAGETYPE_GIF:
            $image = imagecreatefromgif($filename);
            break;
        case IMAGETYPE_JPEG:
            $image = imagecreatefromjpeg($filename);
            break;
        case IMAGETYPE_PNG:
            $image = imagecreatefrompng($filename);
            break;
        default:
            die("Invalid image type (#{$type} = " . image_type_to_extension($type) . ")");
    }
    $newImage = imagescale($image, $newWidth, $newHeight, IMG_BICUBIC_FIXED);
    switch ($type) {
        case IMAGETYPE_GIF:
            imagegif($newImage, $targetFilename);
            break;
        case IMAGETYPE_JPEG:
            imagejpeg($newImage, $targetFilename, 90);
            break;
        case IMAGETYPE_PNG:
            imagepng($newImage, $targetFilename);
            break;
        default:
            throw new LogicException;
    }
    chmod($targetFilename, 0666);
    imagedestroy($image);
    imagedestroy($newImage);
}
header("Location: " . $targetFilename);
die();
?>

