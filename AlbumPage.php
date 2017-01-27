<?php
const CLIENT_ID = '915378992994-1pe7pbuqdpvmcpqtlkct00cuslbkcjhu.apps.googleusercontent.com';

require_once "Colors.php";
require_once "Button.php";
require_once "Localization.php";
require_once "Photo.php";
require_once "TextImage.php";
require_once "UriBuilder.php";
require_once "Utils.php";

session_start();

$albumPath = 'photos/' . $_GET['album'];
$photos = glob("$albumPath/*.jpg");
$collectionPath = pathinfo($albumPath, PATHINFO_DIRNAME);
$album = pathinfo($albumPath, PATHINFO_BASENAME);
$albumTitle = utils\getAlbumTitle($album);
$requestUri = $_SERVER['REQUEST_URI'];

if ($albumTitle == 'Our Gan') {
  if (!isset($_SESSION['SIGNED_ID']) || $_SESSION['SIGNED_ID'] != 'YES') {
    $redirect = urlencode($_SERVER['REQUEST_URI']);
    header("location:Login.php?redirect=$redirect");
    die;
  }
}
?>

<html dir="<?= direction() ?>" xmlns="http://www.w3.org/1999/xhtml">
<head>
  <script src="https://apis.google.com/js/platform.js?onload=onLoad" async defer></script>
  <meta name="google-signin-client_id" content="<?= CLIENT_ID ?>">
  <script>
    function onLoad() {
      console.log('onLoad()');
      gapi.load('auth2', function() {
        console.log('Loaded()');
        gapi.auth2.init();
      });
    }

    function signOut() {
      console.log('Signed out.');
      var auth2 = gapi.auth2.getAuthInstance();
      auth2.signOut().then(function () {
        document.cookie = "token=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
        console.log('User signed out.');
        window.location = "Logout.php";
      });
    }
  </script>
  <title><?php echo _('Album') . " - $albumTitle" ?></title>
  <?php Button::init() ?>
</head>
<body style="background-image: url(img/background.png)">
<a href="#" onclick="signOut();">Sign out</a>
<?php
languageSwitcher();
?>
<table cellspacing="0" cellpadding="0" width="100%" border="0">
  <tbody>
  <tr>
    <td>
      <!-- Top of Page Links -->
      <p style="vertical-align:middle;text-align:center">
        <?php
        $navigate = new Button('david', 10, Colors::$BLACK, 'img/navigate.png');
        $navigate->render('todo.php', message('Home'));
        $navigate->render('todo.php', message('Up'));
        ?>
        <br/>
        <!-- Page Title -->
        <img src='<?= TextImage::create('img/title.png', $albumTitle, 'davidbd', 18, Colors::$YELLOW) ?>'/>
        '
        <br/>

        <!-- Albums -->

        <?php

        $button = new Button('david', 13, Colors::$BLACK, 'img/button.png', 'img/button-hover.png');
        $dirs = array();
        foreach (new DirectoryIterator($collectionPath) as $file) {
          if ($file->isDir() && !$file->isDot()) {
            $dirs[count($dirs)] = clone $file;
          }
        }
        natsort($dirs);
        foreach ($dirs as $dir) {
          $siblingTitle = utils\getAlbumTitle($dir->getFilename());
          if ($siblingTitle == $albumTitle) {
            $img = TextImage::create('img/current.png', $albumTitle, 'david', 13, Colors::$BLACK);
            ?>
            <!-- Current Album needs no button -->
            <img src='<?= $img ?>' style='border: none; vertical-align: middle'/>
            <?php
          } else {
            $siblingPath = substr($dir->getPathname(), strlen('photos/'));
            $uri = new UriBuilder($requestUri);
            $uri->setParam('album', $siblingPath);
            $siblingUri = $uri->build();
            $button->render("$siblingUri", $siblingTitle);
          }
        }
        ?>
      </p>
    </td>
  </tr>
  </tbody>
</table>

<h2 style='text-align:center;font-family:Droid Serif,serif'><?= $albumTitle ?></h2>

<p style="text-align:center">
  <img alt="horizontal rule" src="img/seperator.png"/>
</p>

<div align="center">
  <table>
    <?php
    echo '
      <tr />
      <td align="center" />
      ';
    $row = 0;
    $col = 0;
    for ($i = 0; $i < count($photos); $i++) {
      $photo = $photos[$i];
      $thumb = Photo::resize($photo, 100);
      $uri = new UriBuilder($requestUri);
      $uri->setPath('AlbumPhoto.php');
      $uri->setParam('index', 0);
      $link = $uri->build();
      echo "<a><a href='$link'><img src='$thumb' /></a>
      ";
      $col++;
      if ($row % 2 == 0 && $col >= 3 || $col >= 4) {
        $col = 0;
        $row++;
        echo '
          <tr />
          <td align="center" />
          ';
      }
    }
    ?>
  </table>
</div>
<div align="center">
  <?php

  $zipfile = str_replace(' ', '', $albumTitle) . '.zip';
  utils\createZipFile($albumPath, $zipfile, '*.jpg *.JPG');
  $label = _('Download entire album');
  ?>
  <a href='<?= "$albumPath/$zipfile" ?>' download='<?= $zipfile ?>'><?= $label ?></a>

</div>

</body>
</html>
