<?php
session_start();

if ($_SESSION['rola'] != "admin") {
    header('Location: index.php');
    exit();
}

//TODO: przekazac ilosc_userow, zrobic petle i znalezc odpowiednie id for->if(strlen($_POST[$i])>0)

require_once "connect.php";
mysqli_report(MYSQLI_REPORT_STRICT);

if (isset($_SESSION['liczba'])) {
    $liczba_uzytkownikow = $_SESSION['liczba'];
    for ($i = 1; $i <= $liczba_uzytkownikow; $i++) {
        if (isset($_POST[$i])) {
            try {
                $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
                if ($polaczenie->connect_errno != 0) {
                    throw new Exception(mysqli_connect_errno());
                } else {
                    $uzytkownik = $polaczenie->query(sprintf("SELECT * FROM uzytkownicy WHERE id='%s'", $i));

                    if (!$uzytkownik) throw new Exception($polaczenie->error);

                    $wiersz = $uzytkownik->fetch_assoc();
                    $_SESSION['u_id'] = $wiersz['id'];
                    $_SESSION['u_user'] = $wiersz['user'];
                    $_SESSION['u_email'] = $wiersz['email'];
                    $_SESSION['u_rola'] = $wiersz['rola'];
                }

                $polaczenie->close();
            } catch (Exception $e) {

            }
        }
    }
    unset($_SESSION['liczba']);
}

?>

<!DOCTYPE HTML>
<html lang="pl">

<head>
    <meta charset="utf-8"/>
    <meta name="viewport" http-equiv="X-UA-Compatible" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <title>OTOJA - handmade</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>


<body>

<div class="page-flow col-lg-8">

    <img src="images/logo.png" style="width: 75%;" alt="LOGO"> <br>

    <div class="login-form col-md-8 col-lg-4">
        <form action="update_user.php" method="post">

            Nickname:
            <?php
            echo '<br> <input type="text" class="login-input form-control" name="user" value="' . $_SESSION['u_user'] . '"> <br>';
            ?>

            E-mail:
            <?php
            echo '<br> <input type="text" class="login-input form-control" name="email" value="' . $_SESSION['u_email'] . '"> <br>';
            ?>

            Rola:
            <?php
            echo '<br> <input type="text" class="login-input form-control" name="rola" value="' . $_SESSION['u_rola'] . '"> <br>';
            ?>

            <?php
            if (isset($_SESSION['e_update'])) {
                echo '<div class="error">' . $_SESSION['e_update'] . '</div>';
                echo '<div class="error">' . $_SESSION['e_u_user'] . '</div>';
                echo '<div class="error">' . $_SESSION['e_u_email'] . '</div>';
                echo '<div class="error">' . $_SESSION['e_u_rola'] . '</div>';
                unset($_SESSION['e_update']);
                unset($_SESSION['e_u_user']);
                unset($_SESSION['e_u_email']);
                unset($_SESSION['e_u_rola']);
            }
            ?>

            <br>
            <input type="submit" class="btn btn-primary login-input form-control" value="Aktualizuj dane">

        </form>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>
</body>
</html>