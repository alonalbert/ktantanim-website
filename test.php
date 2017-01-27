<?php
const CLIENT_ID = '915378992994-1pe7pbuqdpvmcpqtlkct00cuslbkcjhu.apps.googleusercontent.com';
?>

<html>
<head>
  <meta name="google-signin-client_id" content="<?= CLIENT_ID ?>">
  <script>
    function onSignIn(googleUser) {
      var profile = googleUser.getBasicProfile();
      var token = googleUser.getAuthResponse().id_token;

      console.log('Token: ' + token);
      document.cookie = "token=" + token;
    }

    function onLoad() {
      console.log('onLoad()');
      gapi.load('auth2', function() {
        console.log('Loaded()');
        gapi.auth2.init();
      });
    }

    function signOut() {
      gapi.auth2.init();
      var auth2 = gapi.auth2.getAuthInstance();
      auth2.signOut().then(function () {
        document.cookie = "token=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
        console.log('User signed out.');
      });
    }
  </script>
  <script src="https://apis.google.com/js/platform.js?onload=onLoad" async defer></script>
</head>
<body>
<div class="g-signin2" data-onsuccess="onSignIn"></div>

<a href="#" onclick="signOut();">Sign out</a>
<script>
</script>
</body>
</html>
