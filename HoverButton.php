<?php

require_once "TextImage.php";

class HoverButton
{

    private $name;
    private $text;
    private $button;
    private $hover;

    const ROOT = "http://ktantanim.com/ktantanim/sites/Ktantanim/graphics/text/";

    private $buttonImage = 'img/button.png';

    private $hoverImage;

    private $fontName;

    private $textSize = 13;

    private $colorArray = '#000000';

    public static function init() {
        echo '
            <style>
                .hover-button { text-decoration: none}
            </style>
            <script type="text/javascript">
              function PreloadImage(path) {
                var image = new Image(); image.src=path; return image;
              }
            </script>';
    }

    public function __construct($buttonImage, $hoverImage, $fontName, $textSize, $colorArray) {
        $this->buttonImage = $buttonImage;
        $this->hoverImage = $hoverImage;
        $this->fontName = $fontName;
        $this->textSize = $textSize;
        $this->colorArray = $colorArray;
    }

    public function render($name, $text, $url) {
        $button =  TextImage::create($this->buttonImage, $text, $this->fontName, $this->textSize, $this->colorArray);
        $hover =  TextImage::create($this->hoverImage, $text, $this->fontName, $this->textSize, $this->colorArray);

        echo " 
        <!-- $this->name Button -->
        <script type='text/javascript'>
            " . $name . "Image=PreloadImage('" . $button . "');
            " . $name . "HoverImage=PreloadImage('" . $hover . "');
        </script>
        
        <a href='$url' class='hover-button'
            onmouseover=" . '"' . "document['". $name . "Button'].src=" . $name . "HoverImage.src" . '"' ."
            onmouseout=" . '"' . "document['" . $name . "Button'].src=" . $name . "Image.src" . '"' .">
            <img alt='" . $name . "' src='" . $button . "'
                style='border: none; vertical-align: middle' id='" . $name . "Button' name='" . $name . "Button' />
        </a>";
    }
}
