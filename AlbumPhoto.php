<html xmlns="http://www.w3.org/1999/xhtml">

<?php
require_once "Colors.php";
require_once "HoverButton.php";
require_once "TextImage.php";
?>

<head>
  <title>Photo - Our Gan</title>
  <?php HoverButton::init() ?>
</head>
<body style="background-image: url(img/background.png)">
<?php
$size = 'web';
$collection = '2016/PhotoAlbums';
$album = '01 Our Gan';
$index = 0;
$photos = glob("photos/$collection/$album/*.jpg");
$photo = $photos[$index];
$photoBasename = pathinfo($photo, PATHINFO_BASENAME);
?>

<table cellspacing="0" cellpadding="0" width="100%" border="0">
  <tbody>
  <tr>
    <td>
      <p style="vertical-align:middle;text-align:center">
        <!-- Page Title -->
        <?php
        echo '<img src="' . TextImage::create('img/title.png', substr($album, 3), 'davidbd', 18, Colors::$YELLOW) . '"/>'
        ?>

        <br/>

        <!-- Hover Buttons -->
        <?php
        $button = new HoverButton('img/button.png', 'img/button-hover.png', 'david', 13, Colors::$BLACK);

        $button->render('todo.php', 'Album');
        $button->render('todo.php', 'Previous');
        $button->render('todo.php', 'Next');
        $button->render('todo.php', 'Download Original');
        ?>
      </p>
    </td>
  </tr>
  </tbody>
</table>

<div align="center">
  <?php
  echo "<a href='todo'><img alt='$photoBasename' src='Photo.php?image=$photo&size=600'/></a>"
//  echo "<a href='todo'><img alt='$photoBasename' src='photos/2016/PhotoAlbums/01%20Our%20Gan/600/IMG_20160827_153007.jpg'/></a>"
  ?>

</div>
</body>
</html>
