<?php

session_start();

$username = $_POST['username'];
$password = $_POST['password'];

if ($username == "admin" && $password == "12345") {

    $_SESSION['login'] = true;
    $_SESSION['username'] = "admin";

    header("Location: admin/index.php");
    exit;

} else {

    echo "<script>
        alert('Username atau Password salah!');
        window.location='login.php';
    </script>";

}
?>