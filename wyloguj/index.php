<?php
session_start();
session_destroy();

setcookie("login", "", time() - 60, "/");
setcookie("password", "", time() - 60, "/");

header("Location: ../logowanie");
?>