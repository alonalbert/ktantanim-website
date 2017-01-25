<?php
require_once "Colors.php";
require_once "Button.php";
require_once "Localization.php";
require_once "Photo.php";
require_once "TextImage.php";
require_once "UriBuilder.php";

$albumPath = $_GET['album'];
$index = $_GET['index'];
$photos = glob("photos/$albumPath/*.jpg");
$photo = $photos[$index];
$photoBasename = pathinfo($photo, PATHINFO_BASENAME);
$photoExt = pathinfo($photo, PATHINFO_EXTENSION);
$album = pathinfo($albumPath, PATHINFO_BASENAME);
$albumTitle = preg_replace('/\d\d (.*)/', '${1}', $album);
$requestUri = $_SERVER['REQUEST_URI'];
?>

<html dir="<?= direction() ?>" xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title><?php echo _('Photo') . " - $albumTitle" ?></title>
  <?php Button::init() ?>
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
        echo '<img src="' . TextImage::create('img/title.png', $albumTitle, 'davidbd', 18, Colors::$YELLOW) . '"/>'
        ?>

        <br/>

        <!-- Hover Buttons -->
        <?php
        $count = count($photos);
        $button = new Button('david', 13, Colors::$BLACK, 'img/button.png', 'img/button-hover.png');

        $uri = new UriBuilder($requestUri);
        $uri->setPath('AlbumPage.php');
        $uri->setParam($index, null);
        $albumPageUri = $uri->build();
        $button->render($albumPageUri, message('Album'));

        $uri = new UriBuilder($_SERVER['REQUEST_URI']);

        $uri->setParam('index', $index > 0 ? $index - 1 : $count - 1);
        $button->render($uri->build(), message('Previous'));

        $uri->setParam('index', ($index + 1) % $count);
        $button->render($uri->build(), message('Next'));

        $button->render("$photo", message('Download Original'), sprintf("%s-%03d.%s",
          str_replace(' ', '', $albumTitle),
          $index + 1,
          $photoExt));
        ?>
      </p>
    </td>
  </tr>
  </tbody>
</table>

<div align="center">
  <?php
  $resize = Photo::resize($photo, '600');
  echo "<a href='$albumPageUri'><img alt='$photoBasename' src='$resize'/></a>"
  ?>

</div>
</body>
</html>
