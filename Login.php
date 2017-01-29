<?php
const CLIENT_ID = '915378992994-1pe7pbuqdpvmcpqtlkct00cuslbkcjhu.apps.googleusercontent.com';
?>

<html>
<head>
  <script src="https://apis.google.com/js/platform.js" async defer></script>
  <meta name="google-signin-client_id" content="<?= CLIENT_ID ?>">
  <script src="site.js"></script>
</head>
<body style="background-image: url(img/background.png)">
<div align="center">
  This is a restricted area. Please Sign in.
</div>
<div align="center" class="g-signin2" data-onsuccess="onSignIn"></div>
</body>
</html>

