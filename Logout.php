<?php
session_start();
$_SESSION['SIGNED_ID'] = "NO";
?>

<html>
<body style="background-image: url(img/background.png)">
<div align="center">
  Good bye!!!

  <!-- todo: redirect to home page -->
</div>
<div align="center" class="g-signin2" data-onsuccess="onSignIn"></div>
</body>
</html>

