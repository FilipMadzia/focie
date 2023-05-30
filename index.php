<?php
session_start();
include("config.php");

if(!isset($_SESSION["login"])) {
    header("Location: logowanie");
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

        $new_album_query = mysqli_query($conn, "INSERT INTO album(nazwa, data_utworzenia, id_fociarz) VALUES('$name', NOW(), (SELECT id_fociarz FROM fociarz WHERE login = '$_SESSION[login]'));");

        mysqli_close($conn);

        mkdir("fociarz/".$_SESSION["login"]."/".$name);
    }
    ?>
    
    <div class="container">
        <div class="row">
            <!-- ukryte okno do tworzenia albumu -->
            <div class="position-absolute col-lg-2 col-md-6 col-sm-12 top-50 start-50 translate-middle" id="new-folder">
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
            <!-- ikona utworzenia albumu -->
            <div class="folder col-lg-2 col-md-3 col-sm-6" id="new-folder-button">
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

    <script>
        const new_folder_button = document.querySelector("#new-folder-button");
        const new_folder = document.querySelector("#new-folder");
        const close_button = document.querySelector("#close-button");
        const name = document.querySelector("#name");

        new_folder_button.addEventListener("click", () => {
            new_folder.style.display = "block";
            name.focus();
        });
        close_button.addEventListener("click", () => {
            new_folder.style.display = "none";
        });

        if(window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>