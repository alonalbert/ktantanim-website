<?php
$locale = "he";
if (isSet($_GET["locale"])) {
  $locale = $_GET["locale"];
}
if ($locale == "he") {
  $locale = "he_IL";
}
putenv("LANGUAGE=$locale");
putenv("LC_ALL=$locale");
setlocale(LC_ALL, $locale);
$domain = 'messages';
bindtextdomain($domain, "./locale");
textdomain($domain);
bind_textdomain_codeset($domain, 'UTF-8'); // Returns UTF-8

function message($name) {
  return $name;
}

function isHebrew() {
  global $locale;
  return $locale == "he_IL";
}

?>