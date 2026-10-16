<?php

session_start();

$username = $_POST['username'];
$password = $_POST['password'];

$password_md5 = md5($password);

if ($username == "kasir" && $password_md5 == "827ccb0eea8a706c4c34a16891f84e7b") {

    $_SESSION['kasir_login'] = true;
    $_SESSION['username_kasir'] = "kasir";

    header("Location: index.php");
    exit;

} else {

    header("Location: login_kasir.php?error=1");
    exit;

}

?>