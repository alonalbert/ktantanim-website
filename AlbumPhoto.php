<?php
require_once "Colors.php";
require_once "HoverButton.php";
require_once "Localization.php";
require_once "Photo.php";
require_once "TextImage.php";

$size = 'web';
$albumPath = $_GET['album'];
$index = $_GET['index'];
$photos = glob("photos/$albumPath/*.jpg");
$photo = $photos[$index];
$photoBasename = pathinfo($photo, PATHINFO_BASENAME);
$albumName = pathinfo($albumPath, PATHINFO_BASENAME);
$album = preg_replace('/\d\d (.*)/', '${1}', $albumName);
?>

<?php startHtml() ?>

<head>
  <title><?php echo _('Photo') . " - $album" ?></title>
  <?php HoverButton::init() ?>
</head>
<body style="background-image: url(img/background.png)">
<?php
languageSwitcher();
?>
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
        $count = count($photos);
        $nextIndex = ($index + 1) % $count;
        $prevIndex = $index > 0 ? $index - 1 : $count - 1;
        $button = new HoverButton('img/button.png', 'img/button-hover.png', 'david', 13, Colors::$BLACK);
        $button->render('todo.php', message('Album'));
        $button->render("AlbumPhoto.php?locale=$locale&album=$albumPath&index=" . $prevIndex, message('Previous'));
        $button->render("AlbumPhoto.php?locale=$locale&album=$albumPath&index=" . $nextIndex, message('Next'));
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
