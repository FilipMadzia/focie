<?php
session_start();
include("../config.php");

if(!isset($_SESSION["login"])) {
    header("Location: ../logowanie");
}

$nazwa = $_GET["nazwa"];

$conn = mysqli_connect($hostname, $db_username, $db_password, $database);
mysqli_query($conn, "DELETE FROM zdjecie WHERE id_album = (SELECT id_album FROM album WHERE nazwa = '$nazwa' AND id_fociarz = (SELECT id_fociarz FROM fociarz WHERE login = '$_SESSION[login]'));");
mysqli_query($conn, "DELETE FROM album WHERE nazwa = '$nazwa' AND id_fociarz = (SELECT id_fociarz FROM fociarz WHERE login = '$_SESSION[login]');");
mysqli_close($conn);

$dirPath = "../fociarz/".$_SESSION["login"]."/".$nazwa;

function deleteDir($path) {
    return is_file($path) ?
            @unlink($path) :
            array_map(__FUNCTION__, glob($path.'/*')) == @rmdir($path);
}

deleteDir($dirPath);

rmdir($dirPath);

header("Location: ../../focie");
?>