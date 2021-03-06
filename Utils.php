<?php

namespace utils;
function getAlbumTitle($album) {
    return preg_replace('/\d\d (.*)/', '${1}', $album);
}

function createZipFile($path, $name, $files) {
  $cwd = getcwd();
  chdir($path);
  $cwd = getcwd();
  if (file_exists($name)) {
    shell_exec("zip -u $name $files");
  } else {
    shell_exec("zip $name $files");
  }
  chdir($cwd);
}

function checkToken() {
  session_start();
  if (!isset($_SESSION['token']) || $_SESSION['token'] == '') {
    $redirect = urlencode($_SERVER['REQUEST_URI']);
    header("location:Login.php?redirect=$redirect");
    die;
  }
}