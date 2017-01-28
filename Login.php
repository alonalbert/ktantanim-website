<?php
const CLIENT_ID = '915378992994-1pe7pbuqdpvmcpqtlkct00cuslbkcjhu.apps.googleusercontent.com';
?>

<html>
<head>
  <script src="https://apis.google.com/js/platform.js" async defer></script>
  <meta name="google-signin-client_id" content="<?= CLIENT_ID ?>">
  <script>
    function onSignIn(googleUser) {
      var profile = googleUser.getBasicProfile();
      var token = googleUser.getAuthResponse().id_token;

      var redirect = getParameterByName("redirect");
      console.log('Token: ' + token);
      console.log('Redirect: ' + redirect);

      window.location = "LoginCheckToken.php?token=" + token + "&redirect=" + encodeURIComponent(redirect);
    }
    function getParameterByName(name) {
      url = window.location.href;
      name = name.replace(/[\[\]]/g, "\\$&");
      var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
      if (!results) return null;
      if (!results[2]) return '';
      return decodeURIComponent(results[2].replace(/\+/g, " "));
    }
  </script>
</head>
<body style="background-image: url(img/background.png)">
<div align="center">
  This is a restricted area. Please Sign in.
</div>
<div align="center" class="g-signin2" data-onsuccess="onSignIn"></div>
</body>
</html>

