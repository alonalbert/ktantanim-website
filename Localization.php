<?php
require_once "UriBuilder.php";

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

function startHtml() {
  if (isHebrew()) {
    echo('<html dir="rtl" xmlns="http://www.w3.org/1999/xhtml">');
  } else {
    echo('<html dir="ltr" xmlns="http://www.w3.org/1999/xhtml">');
  }
}

function languageSwitcher() {
  if (isHebrew()) {
    $label = "English";
    $newLocale = "en";
  } else {
    $label = "עברית";
    $newLocale = "he";
  }
  $uri = new UriBuilder($_SERVER['REQUEST_URI']);
  $uri->setParam('locale', $newLocale);
  $target = $uri->build();
  echo '<p style="vertical-align:middle;text-align:center">';
  echo "<a href='$target'>$label</a>";
  echo '</p>';
}
?>