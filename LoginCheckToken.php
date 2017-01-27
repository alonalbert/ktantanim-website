<?php
const CLIENT_ID = '915378992994-1pe7pbuqdpvmcpqtlkct00cuslbkcjhu.apps.googleusercontent.com';

session_start();

function validate($token) {
  $url = "https://www.googleapis.com/oauth2/v3/tokeninfo?id_token=$token";
  $tokenInfo = json_decode(file_get_contents($url));
  if ($tokenInfo->aud != CLIENT_ID) {
    return false;
  }
  if ($tokenInfo->iss != 'accounts.google.com' && $tokenInfo->iss == 'https://accounts.google.com') {
    return false;
  }
  if (time() > $tokenInfo->exp) {
    return false;
  }
  $users = json_decode(file_get_contents('users.json'));

  $email = $tokenInfo->email;
  if (!in_array($email, $users)) {
    return false;
  }
  return true;
}

$token=$_GET['token'];
if (validate($token)) {
  $_SESSION['SIGNED_ID'] = "YES";
  $redirect=$_GET['redirect'];
  header("Location: $redirect");
  die;
}
else {
  ?>
<body style="background-image: url(img/background.png)">
<div align="center">
  Sign in failed or user not authorised. Please speak to owner for access to this area.
</div>
<div align="center" class="g-signin2" data-onsuccess="onSignIn"></div>
</body>
<?php
}
?>
