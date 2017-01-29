function initGoogleAuth() {
  console.log('onLoad()');
  gapi.load('auth2', function() {
    console.log('Loaded()');
    gapi.auth2.init();
  });
}

function onSignIn(googleUser) {
  var profile = googleUser.getBasicProfile();
  var token = googleUser.getAuthResponse().id_token;

  var redirect = getParameterByName("redirect");
  console.log('Token: ' + token);
  console.log('Redirect: ' + redirect);

  window.location = "LoginCheckToken.php?token=" + token + "&redirect=" + encodeURIComponent(redirect);
}

function signOut() {
  console.log('Signed out.');
  var auth2 = gapi.auth2.getAuthInstance();
  auth2.signOut().then(function () {
    document.cookie = "token=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
    console.log('User signed out.');
    window.location = "Logout.php";
  });
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

function PreloadImage(path) {
  var image = new Image(); image.src=path; return image;
}
