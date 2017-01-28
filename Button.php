<?php

require_once "TextImage.php";

class Button {

  private $buttonImage;

  private $hoverImage;

  private $fontName;

  private $textSize;

  private $colorArray;

  public static function init() {
    echo '
            <style>
                .no-underline { text-decoration: none}
            </style>
            <script type="text/javascript">
              function PreloadImage(path) {
                var image = new Image(); image.src=path; return image;
              }
            </script>
            ';
  }

  public function __construct($fontName, $textSize, $colorArray, $buttonImage, $hoverImage = null) {
    $this->fontName = $fontName;
    $this->textSize = $textSize;
    $this->colorArray = $colorArray;
    $this->buttonImage = $buttonImage;
    $this->hoverImage = $hoverImage;
  }

  public function render($url, $textId, $downloadName = null) {
    $button = TextImage::create($this->buttonImage, $textId, $this->fontName, $this->textSize, $this->colorArray);
    list($width, $height) = getimagesize($button);

    $name = str_replace(' ', '', $textId);

    $download = $downloadName != null ? "download=$downloadName" : '';

    if ($this->hoverImage != null) {
      $hover = TextImage::create($this->hoverImage, $textId, $this->fontName, $this->textSize, $this->colorArray);
      echo " 
          <!-- $name Button -->
          <script type='text/javascript'>
              " . $name . "Image=PreloadImage('" . $button . "');
              " . $name . "HoverImage=PreloadImage('" . $hover . "');
          </script>
          
          <a href='$url' $download class='no-underline'
              onmouseover=" . '"' . "document['" . $name . "Button'].src=" . $name . "HoverImage.src" . '"' . "
              onmouseout=" . '"' . "document['" . $name . "Button'].src=" . $name . "Image.src" . '"' . ">
              <img alt='" . $name . "' src='" . $button . "'
                  style='border: none; vertical-align: middle; width: $width; height: $height' id='" . $name . "Button' name='" . $name . "Button' />
          </a>";
    } else {
      echo "
        <!-- $name Button -->
        <a href='$url' $download class='hover-button'><img src='$button' style='vertical-align:middle;border:none'/>
        ";
    }
  }
}
