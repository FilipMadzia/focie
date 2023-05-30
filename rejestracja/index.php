<?php
session_start();
include("../config.php");

if(isset($_SESSION["login"])) {
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
    <!-- rejestracja -->
    <?php
    if(isset($_POST["login"])) {
        $login = $_POST["login"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $password_repeat = $_POST["password_repeat"];

        if($password == $password_repeat) {
            $conn = mysqli_connect($hostname, $db_username, $db_password, $database);

            if(strtolower(pathinfo(basename($_FILES["profile-picture"]["name"]), PATHINFO_EXTENSION)) == "jpg" || strtolower(pathinfo(basename($_FILES["profile-picture"]["name"]), PATHINFO_EXTENSION)) == "png") {
                // sprawdzanie, czy login jest unikalny
                $login_result = mysqli_query($conn, "SELECT login FROM fociarz WHERE login = '$login'");
                $login_row = mysqli_fetch_array($login_result);

                if(mysqli_num_rows($login_result) == 0) {
                    $register_query = mysqli_query($conn, "INSERT INTO fociarz(login, email, haslo, data_utworzenia) VALUES('$login', '$email', '$password', NOW());");
                    mkdir("../fociarz/".$login);

                    // zapisanie zdjęcia profilowego
                    move_uploaded_file($_FILES["profile-picture"]["tmp_name"], "../fociarz/".$login."/profilowe.png");

                    header("Location: ../logowanie");
                }
                else {
                    $_SESSION["error_message"] = "Login jest już zajęty";
                }

                mysqli_close($conn);
            }
            else {
                $_SESSION["error_message"] = "Plik w nieprawidłowym formacie";
            }
        }
        else {
            $_SESSION["error_message"] = "Hasła nie są identyczne";
        }
    }
    ?>

    <div class="container">
        <form class="row justify-content-center" method="post" enctype="multipart/form-data">
            <div class="mt-5 col-lg-4 col-md-6 col-sm-12">
                <h1 class="text-center">
                    <img width="60px" src="../favicon.ico">
                    <?php echo $title." - rejestracja";?>
                </h1>

                <p class="form-text text-muted">Rejestrując konto na naszej stronie potwierdzasz, że przeczytałeś i akceptujesz nasze <a href="https://youtu.be/dQw4w9WgXcQ" target="_blank">warunki usługi</a></p>

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" name="login" id="login" placeholder="login" required autofocus>
                    <label for="login">Login</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="email" class="form-control" name="email" id="email" placeholder="email" required>
                    <label for="email">Email</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="password" class="form-control" name="password" id="password" placeholder="hasło" minlength="4" required>
                    <label for="password">Hasło</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="password" class="form-control" name="password_repeat" id="password_repeat" placeholder="hasło" minlength="4" required>
                    <label for="password_repeat">Powtórz hasło</label>
                </div>
                
                <p class="error-message"><?php if(isset($_SESSION["error_message"])) echo $_SESSION["error_message"];?></p>

                <div class="mb-3">
                    <label for="profile-picture">Wybierz zdjęcie profilowe</label>
                    <input class="form-control" type="file" name="profile-picture" id="profile-picture" required>
                    <img class="mt-3 profile-picture" width="100%" id="profile-picture-preview" src="../default-profile.png">
                </div>

                <button type="submit" class="form-control btn btn-primary">Zarejestruj</button>
                <p class="form-text text-muted">Masz już konto? Kliknij <a href="../logowanie">tutaj</a> aby się zalogować</p>
            </div>
        </form>
    </div>

    <script>
        const profile_picture = document.querySelector("#profile-picture");
        const profile_picture_preview = document.querySelector("#profile-picture-preview");

        profile_picture.addEventListener("input", () => {
            const [file] = profile_picture.files;

            if(file) {
                profile_picture_preview.src = URL.createObjectURL(file);
            }
        });
        if(window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>