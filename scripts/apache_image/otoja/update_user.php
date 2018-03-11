<?php

session_start();

if($_SESSION['rola']!="admin") {
    header('Location: index.php');
    exit();
}


if ((strlen($_POST['user'])<=0) || (strlen($_POST['email'])<=0) || (strlen($_POST['rola'])<=0)) {
    $_SESSION['e_update'] = "<strong>ERROR:</strong> Żadne pole nie może pozostać puste";
    $_SESSION['e_u_user'] = '<strong>User:</strong> od 3 do 20 znaków';
    $_SESSION['e_u_email'] = "<strong>E-mail:</strong> Musi być poprawny";
    $_SESSION['e_u_rola'] = "<strong>Rola:</strong> user lub admin";
    header('Location: edit.php');
    exit();
} else {
    unset($_SESSION['u_user']);
    unset($_SESSION['u_email']);
    unset($_SESSION['u_rola']);
}

$wszystko_ok = true;

require_once "connect.php";

if($wszystko_ok) {
    try {
        $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
        if ($polaczenie->connect_errno != 0) {
            throw new Exception(mysqli_connect_errno());
        } else {
            if($polaczenie->query(sprintf("UPDATE uzytkownicy SET user='%s', email='%s', rola='%s' WHERE id='%s'", $_POST['user'], $_POST['email'], $_POST['rola'], $_SESSION['u_id']))) {
                unset($_SESSION['u_id']);
                $_SESSION['updated'] = true;
                header("Location: updated.php");
            }

        }

        $polaczenie->close();
    } catch (Exception $e) {
        throw new Exception(mysqli_connect_errno());
    }

}

?>