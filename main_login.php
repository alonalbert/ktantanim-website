<html>
<head>
  <script>
      function getParameterByName(name) {
          url = window.location.href;
          name = name.replace(/[\[\]]/g, "\\$&");
          var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
              results = regex.exec(url);
          if (!results) return null;
          if (!results[2]) return '';
          return decodeURIComponent(results[2].replace(/\+/g, " "));
      }
      window.onload = function () {
          var e = document.getElementById("redirect");
          e.value=getParameterByName('redirect');
      };

  </script>
</head>
<body>
<form name="form1" method="get" action="checklogin.php">
  <input title="redirect" name="redirect" type="hidden" id="redirect">
  <table width="300" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC">
    <tr>
      <td>
        <table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#FFFFFF">
          <tr>
            <td colspan="3"><strong>Member Login </strong></td>
          </tr>
          <tr>
            <td width="78">Username</td>
            <td width="6">:</td>
            <td width="294">
              <input title="Username" name="username" type="text" id="username">
            </td>
          </tr>
          <tr>
            <td>Password</td>
            <td>:</td>
            <td>
              <input title="Password" name="password" type="password" id="password">
            </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td><input type="submit" name="Submit" value="Login"></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</form>
</body>
</html>

