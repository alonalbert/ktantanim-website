<?php

class TextImage {
    public static function create($imageFile, $text, $fontName, $textSize, $style, $color) {
        $dir = pathinfo($imageFile, PATHINFO_DIRNAME) . '/text';
        $name = pathinfo($imageFile, PATHINFO_FILENAME);
        $ext = pathinfo($imageFile, PATHINFO_EXTENSION);
        $newImageFile = sprintf('%s/%s-%s-%s-%s-%s-%s.%s',
            $dir,
            $name,
            $text,
            $fontName,
            $textSize,
            $color,
            $style,
            $ext);

        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        $fonfFile = "fonts/$fontName.ttf";

        // Measure the text height
        //
        $box = imagettfbbox($textSize, 0, $fonfFile, $text);
        $textWidth = $box[2] - $box[0];
        $textHeight = $box[1] - $box[7];

        // Use a margin equivalent to 4 characters
        //
        $box = imagettfbbox($textSize, 0, $fonfFile, 'XXXX');
        $marginWidth = $box[2] - $box[0];

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

        if ($height < $textHeight) {
            $newHeight = $textHeight;
        } else {
            $newHeight = $height;
        }

        // Copy base image onto a new image
        //
        $newImage = imagecreatetruecolor($width, $height);
        imagealphablending($newImage, false);
        imagesavealpha($newImage,true);


        $black = imagecolorallocate($newImage, 0, 0, 0);
        $Image = imagecreatefrompng($imageFile);
        imagecopyresampled($newImage, $Image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        // Write the text
        //

        $x = ($newWidth - $textWidth) / 2;
        $y = $newHeight - ($newHeight - $textHeight) / 2;
        imagettftext($newImage, $textSize, 0, $x, $y, $black, $fonfFile, $text);

        imagepng($newImage, $newImageFile);

        return $newImageFile;
    }
}