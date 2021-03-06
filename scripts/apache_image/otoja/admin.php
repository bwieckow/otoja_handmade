<?php
    session_start();

    if($_SESSION['rola']!="admin")
    {
        header('Location: index.php');
        exit();
    }

    if(isset($_POST['tytul'])) {
        $wszystko_OK = true;

        //Sprawdzenie tytulu
        $tytul = $_POST['tytul'];
        if(strlen($tytul) < 3 || strlen($tytul) > 50) {
            $wszystko_OK = false;
            $_SESSION['e_tytul'] = "Tytuł musi posiadać od 3 do 50 znaków";
        }

        //Sprawdzenie opisu
        $opis = $_POST['opis'];
        if(strlen($opis) < 3) {
            $wszystko_OK = false;
            $_SESSION['e_opis'] = "Opis musi posiadać ponad 3 znaki";
        } elseif (strlen($opis) > 1500) {
            $wszystko_OK = false;
            $_SESSION['e_opis'] = "Opis nie może posiadać więcej niż 1500 znaków";
        }

        //Sprawdzanie ścieżki
        $sciezka = $_POST['sciezka'];
        if(strlen($sciezka) > 50) {
            $wszystko_OK = false;
            $_SESSION['e_sciezka'] = "Ścieżka nie może posiadać więcej niż 50 znaków";
        }

        require_once "connect.php";
        mysqli_report(MYSQLI_REPORT_STRICT);

        //Dodawanie Posta
        try {
            $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
            if($polaczenie->connect_errno!=0)
            {
                throw new Exception(mysqli_connect_errno());
            } else {
                if ($wszystko_OK==true)
                {
                    if ($polaczenie->query(sprintf("INSERT INTO posty
                                                    VALUES(NULL, '%s', '%s', '%s', '%s', '%s', now())",
                                                    $_SESSION['user'],
                                                    $_SESSION['email'],
                                                    $tytul,
                                                    $opis,
                                                    $sciezka)))
                    {
                        $_SESSION['post-dodany'] = true;
                        header("Location: postdodany.php");
                    }
                    else {
                        throw new Exception($polaczenie->error);
                    }
                }
            }

            $polaczenie->close();
        }
        catch (Exception $e) {

        }

    }
?>

<!DOCTYPE HTML>
<html lang="pl">

<head>
    <meta charset="utf-8"/>
    <meta name="viewport" http-equiv="X-UA-Compatible" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <title>OTOJA - handmade</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>


<body>



    <div class="page-flow col-sm-10 col-lg-8">
        <img src="images/logo.png" style="width: 60%;" alt="LOGO"> <br>

        <?php
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
                    <li class="nav-item  active">
                        <a class="nav-link" href="admin.php">Dodaj post <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="users.php">Zarządzaj użytkownikami</a>
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

        ?>



        <div class="newpost col-lg-8">
            <form method="post">
                Tytuł: <input type="text" class="newpost-input form-control" name="tytul">
                <?php
                    if(isset($_SESSION['e_tytul']))
                    {
                        echo '<div class="error">'.$_SESSION['e_tytul'].'</div>';
                        unset($_SESSION['e_tytul']);
                    }
                ?>
                <br>

                Opis: <input type="text" class="newpost-input form-control" name="opis">
                <?php
                    if(isset($_SESSION['e_opis']))
                    {
                        echo '<div class="error">'.$_SESSION['e_opis'].'</div>';
                        unset($_SESSION['e_opis']);
                    }
                ?>
                <br>

                OpisTA: <br> <textarea name="opis2" id="opis2id" rows="10" class="newpost-input form-control"></textarea> <br>

                Ścieżka do zdjęcia: <input type="text" class="newpost-input form-control" name="sciezka">
                <?php
                    if(isset($_SESSION['e_sciezka']))
                    {
                        echo '<div class="error">'.$_SESSION['e_sciezka'].'</div><br>';
                        unset($_SESSION['e_sciezka']);
                    } else {
                        echo "<br>";
                    }
                ?>
                <br>

                <input type="submit" class="btn btn-primary newpost-input" value="Dodaj post">
            </form>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>