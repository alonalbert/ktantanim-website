<?php

require_once "Localization.php";

class TextImage {
  public static function create($imageFile, $textId, $fontName, $textSize, $color) {
    global $locale;
    $dir = pathinfo($imageFile, PATHINFO_DIRNAME) . '/text';
    $name = pathinfo($imageFile, PATHINFO_FILENAME);
    $ext = pathinfo($imageFile, PATHINFO_EXTENSION);
    $newImageFile = sprintf('%s/%s-%s-%s-%s-%s-%02x%02x%02x.%s',
      $dir,
      $name,
      str_replace(' ', '_', $textId),
      $locale,
      str_replace(' ', '_', $fontName),
      $textSize,
      $color[0],
      $color[1],
      $color[2],
      $ext);

    if (!file_exists($newImageFile)) {
      if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
      }
      $fonfFile = "fonts/$fontName.ttf";

      $text = _($textId);

      if (strpos($locale, 'he') == 0) {
        $text = iconv("ISO-8859-8", "UTF-8", hebrev(iconv("UTF-8", "ISO-8859-8", $text)));
      }

      // Measure the text height
      //
      $box = imagettfbbox($textSize, 0, $fonfFile, $text);
      $textWidth = $box[2] - $box[0];
      $textHeight = $box[1] - $box[7];

      // Use a margin equivalent to 4 characters
      //
      $box = imagettfbbox($textSize, 0, $fonfFile, 'XXXX');
      $marginWidth = $box[2] - $box[0];
      $marginHeight = $box[1] - $box[7];

      // Get size of image
      //
      $imageSize = getimagesize($imageFile);

      $width = $imageSize[0];
      $height = $imageSize[1];

      // Enlarge image if text takes more space
      //
      if ($width < $textWidth + $marginWidth) {
        $newWidth = $textWidth + $marginWidth;
      } else {
        $newWidth = $width;
      }

      if ($height < $textHeight + $marginHeight) {
        $newHeight = $textHeight + $marginHeight;
      } else {
        $newHeight = $height;
      }

      // Copy base image onto a new image
      //
      $newImage = imagecreatetruecolor($newWidth, $newHeight);

      imagealphablending($newImage, false);
      imagesavealpha($newImage, true);


      $imageColor = imagecolorallocate($newImage, $color[0], $color[1], $color[2]);
      $image = imagecreatefrompng($imageFile);
      imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

      // Write the text
      //

      $x = ($newWidth - $textWidth) / 2;
      $y = $newHeight - ($newHeight - $textHeight) / 2;
      imagealphablending($newImage, true);
      imagettftext($newImage, $textSize, 0, $x, $y, $imageColor, $fonfFile, $text);

      imagepng($newImage, $newImageFile);
    }

    return $newImageFile;
  }
}