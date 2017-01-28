<?php
const CLIENT_ID = '915378992994-1pe7pbuqdpvmcpqtlkct00cuslbkcjhu.apps.googleusercontent.com';

require_once "Colors.php";
require_once "Button.php";
require_once "Localization.php";
require_once "TextImage.php";
require_once "UriBuilder.php";
require_once "Utils.php";

session_start();

$config = json_decode(file_get_contents('config.json'));

$year = $_GET['y'];
$section = $_GET['s'];
$album=$_GET['a'];

$root=$config->photosRoot;
$sectionPath = sprintf('%s/%s/%s', $root, $year, $section);
$albumWildcard = sprintf('%s/%s*', $sectionPath, $album);
$albumPath = glob($albumWildcard)[0];

$photos = glob("$albumPath/*.[jJ][pP][gG]");
$albumTitle = utils\getAlbumTitle(pathinfo($albumPath, PATHINFO_BASENAME));
$requestUri = $_SERVER['REQUEST_URI'];

if ($albumTitle == 'Our Gan') {
  if (!isset($_SESSION['token']) || $_SESSION['token'] == '') {
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
        foreach (new DirectoryIterator($sectionPath) as $file) {
          if ($file->isDir() && !$file->isDot()) {
            $dirs[count($dirs)] = clone $file;
          }
        }
        natsort($dirs);
        foreach ($dirs as $dir) {
          $albumDirname = $dir->getFilename();
          $siblingTitle = utils\getAlbumTitle($albumDirname);
          if ($siblingTitle == $albumTitle) {
            $img = TextImage::create('img/current.png', $albumTitle, 'david', 13, Colors::$BLACK);
            ?>
            <!-- Current Album needs no button -->
            <img src='<?= $img ?>' style='border: none; vertical-align: middle'/>
            <?php
          } else {
            $uri = new UriBuilder($requestUri);
            $uri->setParam('a', substr($albumDirname, 0, 2));
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
    $size = $config->photoSizes->thumbnail;

    for ($i = 0; $i < count($photos); $i++) {
    $uri = new UriBuilder($requestUri);
    $uri->setPath('AlbumPhoto.php');
    $uri->setParam('i', $i);
    $link = $uri->build();
    $photo = $photos[$i];
    list($width, $height) = getimagesize($photo);
    if ($width >= $height) {
      $w = $size;
      $h = $size * $height / $width;
    } else {
      $w = $size * $width / $height;
      $h = $size;
    }

    ?>
    <a href='<?= $link ?>' class="no-underline">
      <img src='Photo.php?filename=<?=  $photo ?>&size=thumbnail' style='width:<?= $w ?>px;height:<?= $h ?>px'/>
    </a>
    <?php
      $col++;
      if ($row % 2 == 0 && $col >= 4 || $col >= 5) {
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
