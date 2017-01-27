<?php
session_start();
if(!isset($_SESSION['username'])) {
  foreach ($_SESSION as $index => $item) {
    echo "$index=$item  <bt/>";
  }
  echo "Session not set";
  header("location:main_login.php");
}
?>

<html>
<body>
Login Successful
</body>
</html>
