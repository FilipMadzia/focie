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
                        <a class="nav-link" aria-current="page" href="../../focie">Albumy</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="../konto">Konto</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <?php
    $conn = mysqli_connect($hostname, $db_username, $db_password, $database);

    $result = mysqli_query($conn, "SELECT * FROM fociarz WHERE login = '$_SESSION[login]'");
    if(mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_array($result);

        // ilość stworzonych albumów
        $result = mysqli_query($conn, "SELECT COUNT(a.id_album) AS ilosc_albumow FROM fociarz f, album a WHERE f.login = '$_SESSION[login]' AND a.id_fociarz = f.id_fociarz;");
        $album_count = mysqli_fetch_array($result);
        $album_count = $album_count["ilosc_albumow"];

        // ilosc przesłanych zdjęć
        $result = mysqli_query($conn, "SELECT COUNT(z.id_zdjecie) AS ilosc_zdjec FROM fociarz f, album a, zdjecie z WHERE f.login = '$_SESSION[login]' AND z.id_album = a.id_album AND a.id_fociarz = f.id_fociarz;");
        $photo_count = mysqli_fetch_array($result);
        $photo_count = $photo_count["ilosc_zdjec"];
        ?>
        <div class="container">
            <div class="row mt-5">
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <img class="profile-picture" width="100%" src="../fociarz/<?php echo $row['login']?>/profilowe.png" alt="Zdjęcie profilowe">
                </div>

                <div class="col-lg-9 col-md-6 col-sm-12">
                    <h3><span class="text-muted">Login: </span><?php echo $row["login"];?></h3>
                    <h3><span class="text-muted">Email: </span><?php echo $row["email"];?></h3>
                    <h3><span class="text-muted">Data dodania: </span><?php echo $row["data_utworzenia"];?></h3>

                    <div class="divider"></div>

                    <h3><span class="text-muted">Ilość utworzonych albumów: </span><?php echo $album_count;?></h3>
                    <h3><span class="text-muted">Ilość przesłanych zdjęć: </span><?php echo $photo_count;?></h3>

                    <a class="btn btn-primary" href="../wyloguj">Wyloguj się</a>
                </div>
            </div>
        </div>
        <?php
    }
    else {
        $_SESSION["error_message"] = "Coś poszło nie tak :/";
    }

    mysqli_close($conn);
    ?>
    
    <h1 class="error-message text-center"><?php if(isset($_SESSION["error_message"])){echo $_SESSION["error_message"];}?></h1>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>