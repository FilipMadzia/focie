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
                    <input type="password" class="form-control" name="password" id="password" placeholder="hasło" required>
                    <label for="password">Hasło</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="password" class="form-control" name="password_repeat" id="password_repeat" placeholder="hasło" required>
                    <label for="password_repeat">Powtórz hasło</label>
                </div>

                <div class="mb-3">
                    <input class="form-control" type="file" id="profile-picture" required>
                    <img width="100%" id="profile-picture-preview">
                </div>


                <p class="error-message"><?php if(isset($_SESSION["error_message"])) echo $_SESSION["error_message"];?></p>

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
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>