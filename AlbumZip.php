<?php

require_once "Utils.php";

\utils\checkToken();

$path = $_GET['path'];
$zip = $_GET['zip'];

$cwd = getcwd();
chdir($path);
$cwd = getcwd();
if (file_exists($zip)) {
  shell_exec("zip -u $zip *.jpg *.JPG");
} else {
  shell_exec("zip $zip *.jpg *.JPG");
}
chdir($cwd);

header("Location: $path/$zip");
die;