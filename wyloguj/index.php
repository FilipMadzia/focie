<?php
session_start();
session_destroy();

setcookie("stay_logged", "", time() - 60, "/");
setcookie("login", "", time() - 60, "/");
setcookie("password", "", time() - 60, "/");

header("Location: ../logowanie");
?>