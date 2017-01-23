<?php

require_once "TextImage.php";

class HoverButton
{

    private $name;
    private $text;
    private $button;
    private $hover;

    const ROOT = "http://ktantanim.com/ktantanim/sites/Ktantanim/graphics/text/";

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

    public function __construct($name, $text) {
        $this->name = $name;
        $this->text = $text;
        $this->button =  TextImage::create('img/button.gif', $text, 'david', 10, 'Regular', 'Black');
        $this->hover =  TextImage::create('img/button-hover.gif', $text, 'david', 10, 'Regular', 'Black');
    }

    public function render() {
        echo " 
        <!-- $this->name Button -->
        <script type='text/javascript'>
            " . $this->name . "Image=PreloadImage('" . $this->button . "');
            " . $this->name . "HoverImage=PreloadImage('" . $this->hover . "');
        </script>
        
        <a href='$this->name.aspx?lang=en' class='hover-button'
            onmouseover=" . '"' . "document['". $this->name . "Button'].src=" . $this->name . "HoverImage.src" . '"' ."
            onmouseout=" . '"' . "document['" . $this->name . "Button'].src=" . $this->name . "Image.src" . '"' .">
            <img alt='" . $this->name . "' src='" . $this->button . "'
                style='border: none; vertical-align: middle' id='" . $this->name . "Button' name='" . $this->name . "Button' />
        </a>";
    }
}
