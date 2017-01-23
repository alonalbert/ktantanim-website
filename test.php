<html>

<?php
require_once "HoverButton.php";
require_once "Colors.php";
?>

<head>
    <title>PHP Test</title>
    <?php HoverButton::init() ?>
</head>
<body>

<?php
    $b = new HoverButton('img/button.png', 'img/button-hover.png', 'david', 13, Colors::$RED);
    $b->render("AboutMe", "About Me", "http://google.com");
    $b->render("Hello", "Hello", "http://google.com");
?>


<br />
</body>
</html>

