<?php
session_start();
include("../config.php");

if(isset($_SESSION["login"])) {
    header("Location: ../../focie");
}
else {
    if(isset($_COOKIE["stay_logged"])) {
        $conn = mysqli_connect($hostname, $db_username, $db_password, $database);

        $result = mysqli_query($conn, "SELECT login, haslo FROM fociarz WHERE (login = '$_COOKIE[login]' OR email = '$_COOKIE[login]') AND haslo = '$_COOKIE[password]'");
        if(mysqli_num_rows($result) == 1) {
            $_SESSION["login"] = $_COOKIE["login"];
            header("Location: ../../focie");
        }
        else {
            setcookie("stay_logged", "", time() - 60, "/");
            setcookie("login", "", time() - 60, "/");
            setcookie("password", "", time() - 60, "/");
        }

        mysqli_close($conn);
    }
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
    <!-- logowanie -->
    <?php
    if(isset($_POST["login"]) && isset($_POST["password"])) {
        // zmienna login może też przechowywać email
        $login = $_POST["login"];
        $password = $_POST["password"];

        $conn = mysqli_connect($hostname, $db_username, $db_password, $database);

        $result = mysqli_query($conn, "SELECT login, haslo FROM fociarz WHERE (login = '$login' OR email = '$login') AND haslo = '$password'");
        if(mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_array($result);

            $_SESSION["login"] = $row['login'];
            if(isset($_POST["stay_logged"])) {
                setcookie("stay_logged", "true", time() + (86400 * 30), "/");
                setcookie("login", $login, time() + (86400 * 30), "/");
                setcookie("password", $password, time() + (86400 * 30), "/");
            }
            header("Location: ../../focie");
        }
        else {
            $_SESSION["error_message"] = "Nieprawidłowy login bądź hasło!";
        }

        mysqli_close($conn);
    }
    ?>

    <div class="container">
        <form class="row justify-content-center" method="post">
            <div class="mt-5 col-lg-4 col-md-6 col-sm-12">
                <h1 class="text-center">
                    <img width="60px" src="../favicon.ico">
                    <?php echo $title." - logowanie";?>
                </h1>

                <p class="form-text text-muted">Zaloguj się za pomocą tych samych danych, które podałeś podczas rejestracji</p>

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" name="login" id="login" placeholder="login" required autofocus>
                    <label for="login">Login lub adres email</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="password" class="form-control" name="password" id="password" placeholder="hasło" required>
                    <label for="password">Hasło</label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="stay_logged" id="stay-logged">
                    <label class="form-check-label" for="stay-logged">Zapamiętaj moje dane</label>
                </div>

                <p class="error-message"><?php if(isset($_SESSION["error_message"])) echo $_SESSION["error_message"];?></p>

                <button type="submit" class="form-control btn btn-primary">Zaloguj</button>
                <p class="form-text text-muted">Nie masz jeszcze konta? Kliknij <a href="../rejestracja">tutaj</a> aby się zarejestrować</p>
            </div>
        </form>
    </div>
    
    <script>
        if(window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>