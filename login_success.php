<?php
session_start();
if(!isset($_SESSION['username'])) {
  foreach ($_SESSION as $index => $item) {
    echo "$index=$item  <bt/>";
  }
  echo "Session not set";
  header("location:Login.php");
}
?>

<html>
<body>
Login Successful
</body>
</html>
