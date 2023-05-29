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

                <div class="form-floating">
                    <input type="password" class="form-control" name="password" id="password" placeholder="hasło" required>
                    <label for="password">Hasło</label>
                </div>

                <p class="error-message"><?php if(isset($_SESSION["error_message"])) echo $_SESSION["error_message"];?></p>

                <button type="submit" class="form-control btn btn-primary">Zaloguj</button>
                <p class="form-text text-muted">Nie masz jeszcze konta? Kliknij <a href="../rejestracja">tutaj</a> aby się zarejestrować</p>
            </div>
        </form>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>