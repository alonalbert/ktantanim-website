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
    $b->render("http://google.com", "Download Original");
?>


<br />
</body>
</html>

