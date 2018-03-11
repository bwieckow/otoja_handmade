<?php
    session_start();

    if (isset($_POST['email']))
    {
        //Udana walidacja? Zaklozmy ze tak
        $wszystko_OK = true;

        //Sprawdzenie nickname
        $nick = $_POST['nick'];

        //Sprawdzenie długości nicka
        if ((strlen($nick) < 3) || (strlen($nick) > 20))
        {
            $wszystko_OK = false;
            $_SESSION['e_nick'] = "Nick musi posiadać od 3 do 20 znaków";
        }

        if (ctype_alnum($nick)==false)
        {
            $wszystko_OK = false;
            $_SESSION['e_nick'] = "Nick może składać się tylko z liter i cyfr (bez polskich znaków)";
        }


        //Sprawdz poprawnosc adresu email
        $email = $_POST['email'];
        $emailB = filter_var($email, FILTER_SANITIZE_EMAIL);

        if ((filter_var($emailB, FILTER_VALIDATE_EMAIL)==false) || ($emailB != $email))
        {
            $wszystko_OK = false;
            $_SESSION['e_email'] = "Podaj poprawny adres email";
        }

        //Sprawdz poprawnosc hasla
        $haslo1 = $_POST['haslo1'];
        $haslo2 = $_POST['haslo2'];

        if ((strlen($haslo1)<8 || strlen($haslo1)>20))
        {
            $wszystko_OK = false;
            $_SESSION['e_haslo'] = "Hasło musi posiadać od 8-20 znaków";
        }

        if ($haslo1 != $haslo2)
        {
            $wszystko_OK = false;
            $_SESSION['e_haslo'] = "Podane hasła nie są indentyczne";
        }


        $haslo_hash = password_hash($haslo1, PASSWORD_DEFAULT);

        //Czy zaakceptowano regulamin?
        if (!isset($_POST['regulamin']))
        {
            $wszystko_OK = false;
            $_SESSION['e_regulamin'] = "Potwierdź akceptację regulaminu";
        }


        // //                                                               TODO: Bot czy nie bot?
        // $secret = "6Le4KkYUAAAAABvZI0QfdP_xf68FQSM2arZBLNTF";
        // $sprawdz = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);

        // $odpowiedz = json_decode($sprawdz);

        // echo $odpowiedz->success;

        // if ($odpowiedz->success==false) {
        //     $wszystko_OK = false;
        //     $_SESSION['e_bot'] = "Potwierdź, że nie jesteś botem";
        // }

        require_once "connect.php";
        mysqli_report(MYSQLI_REPORT_STRICT);

        try
        {
            $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);

            if($polaczenie->connect_errno!=0)
            {
                throw new Exception(mysqli_connect_errno());
            }
            else {
                //Czy email juz istnieje?
                $resultat = $polaczenie->query(sprintf("SELECT id FROM uzytkownicy WHERE email='%s'", $email));

                if (!$resultat) throw new Exception($polaczenie->error);

                $ile_takich_maili = $resultat->num_rows;
                if ($ile_takich_maili > 0)
                {
                    $wszystko_OK = false;
                    $_SESSION['e_email'] = "Istnieje już konto przypisane do tego adresu e-mail";
                }

                //Czy nick juz istnieje?
                $resultat = $polaczenie->query(sprintf("SELECT id FROM uzytkownicy WHERE user='%s'", $nick));

                if (!$resultat) throw new Exception($polaczenie->error);

                $ile_takich_nickow = $resultat->num_rows;
                if ($ile_takich_nickow > 0)
                {
                    $wszystko_OK = false;
                    $_SESSION['e_nick'] = "Istnieje już konto o takim nickname";
                }

                //Rejestracja uzytkownika
                if ($wszystko_OK==true)
                {
                    //Wszystkie testy zaliczone, dodajemy gracza
                    $rola = "user";
                    if ($polaczenie->query(sprintf("INSERT INTO uzytkownicy VALUES(NULL, '%s', '%s', '%s', '100', '100', '100', now() + INTERVAL 14 DAY, '%s')", $nick, $haslo_hash, $email, $rola)))
                    {
                        $_SESSION['udana-rejestracja'] = true;
                        header("Location: witamy.php");
                    }
                    else {
                        throw new Exception($polaczenie->error);
                    }
                }

                $polaczenie->close();
            }
        }
        catch(Exception $e) {
            echo '<span class="error"> Błąd serwera! Prosimy o rejestrację w innym terminie. </span>';
            echo '<br> ERROR: '.$e;
        }

    }

?>

<!DOCTYPE HTML>
<html lang="pl">

<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title>OTOJA - handmade</title>
    <script src='https://www.google.com/recaptcha/api.js'></script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>


<body>

    <div class="page-flow col-lg-8">

        <img src="images/logo.png" style="width: 75%;" alt="LOGO"> <br>

        <div class="login-form col-md-8 col-lg-4">
            <form method="post">

                Nickname: <br> <input type="text" class="login-input" name="nick"> <br>
                <?php
                    if(isset($_SESSION['e_nick']))
                    {
                        echo '<div class="error">'.$_SESSION['e_nick'].'</div>';
                        unset($_SESSION['e_nick']);
                    }
                ?>

                E-mail: <br> <input type="text" class="login-input" name="email"> <br>
                <?php
                    if(isset($_SESSION['e_email']))
                    {
                        echo '<div class="error">'.$_SESSION['e_email'].'</div>';
                        unset($_SESSION['e_email']);
                    }
                ?>

                Twoje hasło: <br> <input type="password" class="login-input" name="haslo1"> <br>
                <?php
                    if(isset($_SESSION['e_haslo']))
                    {
                        echo '<div class="error">'.$_SESSION['e_haslo'].'</div>';
                        unset($_SESSION['e_haslo']);
                    }
                ?>

                Powtórz hasło: <br> <input type="password" class="login-input" name="haslo2"> <br> <br>

                <div style="text-align: left;">
                    <label style="text-align: left;">
                        <input type="checkbox" style="text-align: left;" name="regulamin"> Akceptuję regulamin
                    </label>
                </div>


                <?php
                    if(isset($_SESSION['e_regulamin']))
                    {
                        echo '<div class="error">'.$_SESSION['e_regulamin'].'</div>';
                        unset($_SESSION['e_regulamin']);
                    }
                ?>

                <!-- <br>
                <div class="g-recaptcha" data-sitekey="6Le4KkYUAAAAAIGXLCGa7nB2kNXu6iqeR-nhxoyX"></div>
                <br>
                <?php
                    // if(isset($_SESSION['e_bot']))
                    // {
                    //     echo '<div class="error">'.$_SESSION['e_bot'].'</div>';
                    //     unset($_SESSION['e_bot']);
                    // }
                ?> -->

                <input type="submit" class="btn btn-primary login-input" value="Zarejestruj">

            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>
