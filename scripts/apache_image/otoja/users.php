<?php
session_start();

if ($_SESSION['rola'] != "admin") {
    header('Location: index.php');
    exit();
}

require_once "connect.php";
mysqli_report(MYSQLI_REPORT_STRICT);

//Dodawanie Posta
try {
    $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
    if ($polaczenie->connect_errno != 0) {
        throw new Exception(mysqli_connect_errno());
    } else {
        $uzytkownicy = $polaczenie->query(sprintf("SELECT * FROM uzytkownicy"));

        if (!$uzytkownicy) throw new Exception($polaczenie->error);

        $liczba_uzytkownikow = $uzytkownicy->num_rows;
        $_SESSION['liczba'] = $liczba_uzytkownikow;
    }

    $polaczenie->close();
} catch (Exception $e) {

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

<div class="page-flow col-sm-10 col-lg-8">
    <img src="images/logo.png" style="width: 60%;" alt="LOGO"> <br>

    <?php
    if (isset($_SESSION['zalogowany'])) {
        if (isset($_SESSION['zalogowany'])) {
            echo '<nav class="navbar navbar-expand-lg navbar-light">';
            echo '<span class="navbar-brand mb-0 h1">Witaj ' . $_SESSION['user'] . '</span>';

            echo <<<END
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin.php">Dodaj post</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="users.php">Zarządzaj użytkownikami <span class="sr-only">(current)</span></a>
                    </li>
                </ul>
                <ul class="navbar-nav mr-left-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Mój użytkownik
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="logout.php">Wyloguj</a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
END;

        }

    }
    ?>
    <br>
    <table class="table user-table">
        <tr>
            <td><strong>ID</strong></td>
            <td><strong>Nickname</strong></td>
            <td><strong>Adres E-mail</strong></td>
            <td><strong>Rola</strong></td>
        </tr>
        <?php
        for ($i = 1; $i <= $liczba_uzytkownikow; $i++) {
            $uzytkownik = $uzytkownicy->fetch_assoc();
            $id = $uzytkownik['id'];
            $user = $uzytkownik['user'];
            $email = $uzytkownik['email'];
            $rola = $uzytkownik['rola'];
            echo '<form action="edit.php" method="post">' . "<tr><td>$id</td><td>$user</td><td>$email</td><td>$rola</td><td>" . '<button class="btn btn-primary" name='. $id . ' value='. $id .">Edytuj</button></form></td></tr>";
        }

        ?>
    </table>

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