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

    <?php
    $conn = mysqli_connect($hostname, $db_username, $db_password, $database);
    $nazwa = $_GET["nazwa"];

    // sprawdzenie, czy album istnieje
    $album_query = mysqli_query($conn, "SELECT nazwa FROM album WHERE nazwa = '$nazwa';");
    if(mysqli_num_rows($album_query) == 0) {
        header("Location: ../../focie");
    }

    if(isset($_FILES["image"]["name"])) {
        if(strtolower(pathinfo(basename($_FILES["image"]["name"]), PATHINFO_EXTENSION)) == "jpg" || strtolower(pathinfo(basename($_FILES["image"]["name"]), PATHINFO_EXTENSION)) == "png") {
            $image_name = basename($_FILES["image"]["name"]);

            while(file_exists("../fociarz/".$_SESSION["login"]."/".$nazwa."/".$image_name)) {
                $image_name = "(kopia)".$image_name;
            }

            $image_query = mysqli_query($conn, "INSERT INTO zdjecie(nazwa, data_dodania, id_album) VALUES('$image_name', NOW(), (SELECT id_album FROM album WHERE nazwa = '$nazwa'));");
            
            // zapisanie zdjęcia
            move_uploaded_file($_FILES["image"]["tmp_name"], "../fociarz/".$_SESSION["login"]."/".$nazwa."/".$image_name);                  
        }
        else {
            $_SESSION["error_message"] = "Plik w nieprawidłowym formacie";
        }
    }

    mysqli_close($conn);
    ?>

    <div class="container">
        <div class="row">
            <!-- informacje o folderze -->
            <div class="text-center col-lg-12 col-md-12 col-sm-12">
                <?php
                $conn = mysqli_connect($hostname, $db_username, $db_password, $database);

                // ilosc zdjęć w albumie
                $result = mysqli_query($conn, "SELECT COUNT(z.id_zdjecie) AS ilosc_zdjec FROM zdjecie z WHERE z.id_album = (SELECT id_album FROM album WHERE nazwa = '$nazwa');");
                $photo_count = mysqli_fetch_array($result);
                $photo_count = $photo_count["ilosc_zdjec"];

                $data_utworzenia = mysqli_query($conn, "SELECT data_utworzenia FROM album WHERE nazwa = '$nazwa'");
                $data_utworzenia = mysqli_fetch_column($data_utworzenia);
                ?>

                <p class="text-muted">Album: <?php echo "<span class='text-dark'>".$nazwa."</span>";?> Data utworzenia: <?php echo "<span class='text-dark'>".$data_utworzenia."</span>";?> Ilosć zdjęć: <?php echo "<span class='text-dark'>".$photo_count."</span>";?></p>
            </div>
            <!-- ukryte okno do przesłania zdjęcia -->
            <div class="position-absolute col-lg-2 col-md-6 col-sm-12 top-50 start-50 translate-middle" id="upload-image">
                <img id="close-button" src="../ikony/zamknij.svg" alt="Zamknij">
                <h3>Prześlij plik</h3>

                <form method="post" enctype="multipart/form-data">
                    <input class="mt-4 mb-4" type="file" name="image" id="image" required>

                    <button type="submit" class="form-control btn btn-primary">Stwórz</button>
                </form>
            </div>
            <div class="image col-lg-2 col-md-3 col-sm-6" id="upload-image-button">
                <img width="100%" src="../ikony/dodaj_zdjecie.svg">
            </div>
            <?php
            $conn = mysqli_connect($hostname, $db_username, $db_password, $database);

            $result = mysqli_query($conn, "SELECT nazwa, data_dodania, id_album FROM zdjecie WHERE id_album = (SELECT id_album FROM album WHERE nazwa = (SELECT nazwa FROM album WHERE nazwa = '$nazwa')) ORDER BY data_dodania DESC;");
            while($row = mysqli_fetch_array($result)) {
                $image = $_SESSION["login"]."/".$nazwa."/".$row["nazwa"];
                ?>
                <div class="image col-lg-2 col-md-3 col-sm-6">
                    <a href="../fociarz/<?php echo $image;?>" download>
                        <img width="100%" style="aspect-ratio: 1/1" src="../fociarz/<?php echo $image;?>" alt="<?php echo $row["nazwa"];?>">
                    </a>
                    <p class="image-name"><?php echo $row["nazwa"];?></p>
                </div>
                <?php
            }

            mysqli_close($conn);
            ?>
        </div>
    </div>
    
    <h1 class="error-message text-center"><?php if(isset($_SESSION["error_message"])){echo $_SESSION["error_message"];}?></h1>

    <script>
        const upload_image_button = document.querySelector("#upload-image-button");
        const upload_image = document.querySelector("#upload-image");
        const close_button = document.querySelector("#close-button");

        upload_image_button.addEventListener("click", () => {
            upload_image.style.display = "block";
        });
        close_button.addEventListener("click", () => {
            upload_image.style.display = "none";
        });

        if(window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>