<?php

namespace utils;
function getAlbumTitle($album) {
    return preg_replace('/\d\d (.*)/', '${1}', $album);
}

function createZipFile($path, $name, $files) {
  $cwd = getcwd();
  chdir($path);
  shell_exec("zip -u $name $files");
  chdir($cwd);
}
