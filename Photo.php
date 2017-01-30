<?php
require_once "Utils.php";
\utils\checkToken();

$config = json_decode(file_get_contents('config.json'), true);
$sizes = $config['photoSizes'];

$filename = $_GET['filename'];
$sizeName = $_GET['size'];
$size = $sizes[$sizeName];
if ($size == null) {
  die("Invalid size: $sizeName");
}
$basename = basename($filename);
$dir = dirname($filename);
$targetDir = $dir . "/" . $sizeName;
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
  $newImage = imagecreatetruecolor($newWidth, $newHeight);
  imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

//      $newImage = imagescale($image, $newWidth, $newHeight, IMG_NEAREST_NEIGHBOUR);
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

$data = getimagesize($targetFilename);
if (!$data) {
die("Cannot get mime type");
} else {
  header('Content-Type: ' . $data['mime']);
}
// Send the file to the browser

readfile($targetFilename);
?>

