<html>

<?php
require_once "HoverButton.php";
?>

<head>
    <title>PHP Test</title>
    <?php HoverButton::init() ?>
</head>
<body>

<!-- Initialize Hovver Support -->

<?php
    $b = new HoverButton("AboutMe", "About Me");
    $b->render();
?>


<br />
</body>
</html>

