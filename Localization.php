<?php
$locale = "he_IL";
if (isSet($_GET["locale"])) {
  $locale = $_GET["locale"];
}
putenv("LANGUAGE=$locale");
putenv("LC_ALL=$locale");
setlocale(LC_ALL, $locale);
$domain = 'messages';
bindtextdomain($domain, "./locale");
textdomain($domain);
bind_textdomain_codeset($domain, 'UTF-8'); // Returns UTF-8
?>