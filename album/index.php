<?php
session_start();
include("../config.php");

if(!isset($_SESSION["login"])) {
    header("Location: ../../focie");
}
if(isset($_SESSION["error_message"])) {
    unset($_SESSION["error_message"]);
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title;?></title>
    <link rel="shortcut icon" href="../favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="../../focie">
                <img width="30" src="../favicon.ico">
                <?php echo $title;?>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="../../focie">Albumy</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../konto">Konto</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <?php
            $conn = mysqli_connect($hostname, $db_username, $db_password, $database);
            $nazwa = $_GET["nazwa"];

            $result = mysqli_query($conn, "SELECT nazwa, data_dodania, id_album FROM zdjecie WHERE id_album = (SELECT id_album FROM album WHERE nazwa = (SELECT nazwa FROM album WHERE nazwa = '$nazwa'));");
            while($row = mysqli_fetch_array($result)) {
                $image = $_SESSION["login"]."/".$nazwa."/".$row["nazwa"];
                ?>
                <div class="col-lg-2 col-md-3 col-sm-6">
                    <img width="100%" src="../fociarz/<?php echo $image;?>" alt="<?php echo $nazwa;?>">
                </div>
                <?php
            }

            mysqli_close($conn);
            ?>
        </div>
    </div>
    
    <h1 class="error-message text-center"><?php if(isset($_SESSION["error_message"])){echo $_SESSION["error_message"];}?></h1>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>