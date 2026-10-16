<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

include "../koneksi.php";

$id = $_GET['id'];

mysqli_query(
    $koneksi,
    "DELETE FROM pelanggan WHERE PelangganID='$id'"
);

header("Location: pelanggan.php");
exit;
?>