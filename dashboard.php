<?php
include "koneksi.php";

$pelanggan = mysqli_query($koneksi, "SELECT * FROM pelanggan");
$produk = mysqli_query($koneksi, "SELECT * FROM produk");
$penjualan = mysqli_query($koneksi, "SELECT * FROM penjualan");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sistem Penjualan</title>

    <style>
        body {
            margin: 0;
            font-family: Arial;
            background: #f2f2f2;
        }

        .header {
            background: #333;
            color: white;
            padding: 25px;
            text-align: center;
        }

        .menu {
            background: white;
            padding: 15px;
            text-align: center;
        }

        .menu a {
            text-decoration: none;
            color: #333;
            margin: 15px;
        }

        .login {
            background: #333;
            color: white !important;
            padding: 10px 15px;
            border-radius: 5px;
        }

        .container {
            width: 90%;
            margin: 30px auto;
        }

        .card {
            display: inline-block;
            width: 27%;
            background: white;
            margin: 10px;
            padding: 25px;
            text-align: center;
            border-radius: 10px;
        }

        .card h1 {
            font-size: 35px;
        }
    </style>
</head>

<body>

<div class="header">
    <h1>SISTEM PENJUALAN</h1>
    <p>Dashboard Informasi Penjualan</p>
</div>

<div class="menu">

    <a href="dashboard.php">Dashboard</a>

    <a href="login.php" class="login">
        Login Admin
    </a>

</div>

<div class="container">

    <h2>Dashboard</h2>

    <div class="card">
        <h3>Pelanggan</h3>
        <h1><?= mysqli_num_rows($pelanggan); ?></h1>
    </div>

    <div class="card">
        <h3>Produk</h3>
        <h1><?= mysqli_num_rows($produk); ?></h1>
    </div>

    <div class="card">
        <h3>Penjualan</h3>
        <h1><?= mysqli_num_rows($penjualan); ?></h1>
    </div>

</div>

</body>
</html>