<?php
session_start();
include("../config.php");

if(!isset($_SESSION["login"])) {
    header("Location: ../logowanie");
}

$nazwa = $_GET["nazwa"];
?>