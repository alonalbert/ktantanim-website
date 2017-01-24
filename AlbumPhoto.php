<?php
require_once "Colors.php";
require_once "HoverButton.php";
require_once "Localization.php";
require_once "Photo.php";
require_once "TextImage.php";

$size = 'web';
$albumPath = '2016/PhotoAlbums/01 Yosemite';
$index = 0;
$photos = glob("photos/$albumPath/*.jpg");
$photo = $photos[$index];
$photoBasename = pathinfo($photo, PATHINFO_BASENAME);
$albumName = pathinfo($albumPath, PATHINFO_BASENAME);
$album = preg_replace('/\d\d (.*)/', '${1}', $albumName);
global $locale;
?>

<?php
if (isHebrew()) {
?>
<html dir='rtl' xmlns="http://www.w3.org/1999/xhtml">
<?php
} else {
?>
<html dir='ltr' xmlns="http://www.w3.org/1999/xhtml">
<?php
}
?>

<head>
  <title><?php echo _('Photo') . " - $album" ?></title>
  <?php HoverButton::init() ?>
</head>
<body style="background-image: url(img/background.png)">
<table cellspacing="0" cellpadding="0" width="100%" border="0">
  <tbody>
  <tr>
    <td>
      <p style="vertical-align:middle;text-align:center">
        <!-- Page Title -->
        <?php
        echo '<img src="' . TextImage::create('img/title.png', $album, 'davidbd', 18, Colors::$YELLOW) . '"/>'
        ?>

        <br/>

        <!-- Hover Buttons -->
        <?php
        $button = new HoverButton('img/button.png', 'img/button-hover.png', 'david', 13, Colors::$BLACK);

        $button->render('todo.php', message('Album'));
        $button->render('todo.php', message('Previous'));
        $button->render('todo.php', message('Next'));
        $button->render('todo.php', message('Download Original'));
        ?>
      </p>
    </td>
  </tr>
  </tbody>
</table>

<div align="center">
  <?php
  $resize = Photo::resize($photo, '600');
  echo "<a href='todo'><img alt='$photoBasename' src='$resize'/></a>"
  ?>

</div>
</body>
</html>
