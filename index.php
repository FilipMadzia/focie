<?php
session_start();
include("config.php");

if(!isset($_SESSION["login"])) {
    header("Location: logowanie");
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
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="../focie">
                <img width="30" src="favicon.ico">
                <?php echo $title;?>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="../focie">Albumy</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="konto">Konto</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- dodawanie albumów -->
    <?php
    if(isset($_POST["name"])) {
        $conn = mysqli_connect($hostname, $db_username, $db_password, $database);
        $name = $_POST["name"];

        $albums_with_same_name = mysqli_query($conn, "SELECT nazwa FROM album WHERE nazwa = '$name'");
        
        if(mysqli_num_rows($albums_with_same_name) == 0) {
            $new_album_query = mysqli_query($conn, "INSERT INTO album(nazwa, data_utworzenia, id_fociarz) VALUES('$name', NOW(), (SELECT id_fociarz FROM fociarz WHERE login = '$_SESSION[login]'));");

            mkdir("fociarz/".$_SESSION["login"]."/".$name);
            mysqli_close($conn);
        }
        else {
            $_SESSION["error_message"] = "Album o takiej nazwie już istnieje";
        }
    }
    ?>

    <!-- zmiana nazwy albumu -->
    <?php
    if(isset($_POST["new_name"])) {
        $conn = mysqli_connect($hostname, $db_username, $db_password, $database);
        $new_name = $_POST["new_name"];
        $current_name = $_POST["current_name"];

        $new_album_query = mysqli_query($conn, "UPDATE album SET nazwa = '$new_name' WHERE id_fociarz = (SELECT id_fociarz FROM fociarz WHERE login = '$_SESSION[login]') AND nazwa = '$current_name';");
        rename("fociarz/".$_SESSION["login"]."/".$current_name, "fociarz/".$_SESSION["login"]."/".$new_name);

        mysqli_close($conn);
    }
    ?>
    
    <div class="container">
        <div class="row">
            <h1 class="text-center text-danger">
                <?php
                if(isset($_SESSION["error_message"])) {
                    echo $_SESSION["error_message"];
                }
                ?>
            </h1>
            <!-- ukryte okno do tworzenia albumu -->
            <div style="z-index: 2;" class="position-absolute col-lg-2 col-md-6 col-sm-12 top-50 start-50 translate-middle" id="new-folder">
                <img id="close-button" src="ikony/zamknij.svg" alt="Zamknij">
                <h3>Nazwa albumu</h3>

                <form method="post">
                    <div class="form-floating mt-3 mb-3">
                        <input type="text" class="form-control" name="name" id="name" placeholder="Nazwa albumu" required>
                        <label for="name">Podaj nazwę albumu</label>
                    </div>

                    <button type="submit" class="form-control btn btn-primary">Stwórz</button>
                </form>
            </div>
            <!-- ukryte okno do zmiany nazwy albumu -->
            <div style="z-index: 2;" class="position-absolute col-lg-2 col-md-6 col-sm-12 top-50 start-50 translate-middle" id="new-name">
                <img id="close-button-2" src="ikony/zamknij.svg" alt="Zamknij">
                <h3>Nowa nazwa</h3>

                <form method="post">
                    <div class="form-floating mt-3 mb-3">
                        <input type="text" class="form-control" name="new_name" id="new-name-input" placeholder="Nowa nazwa albumu" required>
                        <label for="new_name">Podaj nową nazwę albumu</label>
                    </div>
                    <input type="hidden" name="current_name" id="current-name">

                    <button type="submit" class="form-control btn btn-primary">Zmień nazwę albumu</button>
                </form>
            </div>
            <!-- ikona utworzenia albumu -->
            <div class="col-lg-2 col-md-3 col-sm-6" id="new-folder-button">
                <img width="100%" src="ikony/dodaj_folder.svg">
            </div>
            <!-- wyświetlanie albumów -->
            <?php
            $conn = mysqli_connect($hostname, $db_username, $db_password, $database);

            $result = mysqli_query($conn, "SELECT nazwa FROM album WHERE id_fociarz = (SELECT id_fociarz FROM fociarz WHERE login = '$_SESSION[login]')");
            while($row = mysqli_fetch_array($result)) {
                ?>
                <div class="folder col-lg-2 col-md-3 col-sm-6">
                    <a class="album-link" href="album?nazwa=<?php echo $row['nazwa'];?>">
                        <img width="100%" src="ikony/folder.svg">
                        <p class="folder-name"><?php echo $row["nazwa"];?></p>
                    </a>
                </div>
                <?php
            }

            mysqli_close($conn);
            ?>
        </div>
    </div>

    <script src="script.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>