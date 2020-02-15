<?php
session_destroy();
unset($_SESSION["uid"]);
header("Location: userlogin.html");
exit;
?>