<?php
    session_start();

    // if(!isset($_SESSION['zalogowany']))
    // {
    //     header('Location: index.php');
    //     exit();
    // }
?>

<!DOCTYPE HTML>
<html lang="pl">

<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title>OTOJA - handmade</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>


<body>

    <div class="container">
        <img src="images/logo.png" style="width: 60%;" alt="LOGO"> <br>

<?php

    if(isset($_SESSION['zalogowany']))
    {
        echo "<p>Witaj ".$_SESSION['user'].'! [ <a href="logout.php">Wyloguj</a> ]';

        if ($_SESSION['rola']=="admin") {
            echo '[ <a href="admin.php">Admin Panel</a> ]</p>';
        } else {
            echo '</p>';
        }
    }

    require_once "connect.php";
    mysqli_report(MYSQLI_REPORT_STRICT);

    try {
        $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
        if($polaczenie->connect_errno!=0)
        {
            throw new Exception(mysqli_connect_errno());
        } else {
            $resultat = $polaczenie->query("SELECT * FROM posty ORDER BY id DESC");

            if (!$resultat) throw new Exception($polaczenie->error);

            $ile_postow = $resultat->num_rows;
            if ($ile_postow>0) {
                for($i=1; $i<=$ile_postow; $i++)
                {
                    //$wiersz = $resultat[$i];
                    $wiersz = $resultat->fetch_assoc();
                    //echo $wiersz['photopath'];


                    echo '<div class="post">';

                    echo "<h3>".$wiersz['tytul']."</h3><br>";
                    echo '<p style="text-align: left;">'.$wiersz['opis']."</p>";
                    if((isset($wiersz['photopath']) && strlen($wiersz['photopath'])>0)) {
                        $sciezka = $wiersz['photopath'];
                        echo "<img src=$sciezka".' class="image-post" style="border-radius: 40px; padding: 25px;" alt="Nie można wczytać zdjęcia"><br>';
                    }

                    echo "</div>";
                }
            }
        }
        $polaczenie->close();
    } catch (Exception $e) {

    }


?>


<?php

// if(isset($_SESSION['zalogowany'])) {
//     echo "<p><strong>Drewno</strong>: ".$_SESSION['drewno'];
//     echo "| <strong>Kamien</strong>: ".$_SESSION['kamien'];
//     echo "| <strong>Zboże</strong>: ".$_SESSION['zboze']."</p>";

//     echo "<p><strong>email</strong>: ".$_SESSION['email'];
//     echo "<br/><strong>Data wygaśnięcia premium</strong>: ".$_SESSION['dnipremium']."</p>";

//     $dataczas = new DateTime('2017-05-01 9:33:59');

//     echo "Data i czas serwera: ".$dataczas->format('Y-m-d H:i:s')."<br>";

//     $koniec = DateTime::createFromFormat('Y-m-d H:i:s', $_SESSION['dnipremium']);

//     $roznica = $dataczas->diff($koniec);

//     if ($dataczas<$koniec)
//     {
//         echo "Pozostało premium: ".$roznica->format('%d dni, %h godzin, %i minut, %s sekund');
//     } else {
//         echo "Premium nieaktywne: ".$roznica->format('%d dni, %h godzin, %i minut, %s sekund');
//     }

//     // echo time()."<br>";

//     // echo date('Y-m-d H:i:s')."<br>";

//     // $dataczas = new DateTime();

//     // echo $dataczas->format('Y-m-d H:i:s')."<br>".print_r($dataczas);

// }

?>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>
