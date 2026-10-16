<?php

session_start();

if(!isset($_SESSION['login'])){

    header("Location: ../login.php");

    exit;

}

?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1">

<title>Admin Dashboard</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

</head>


<body class="bg-light">


<nav class="navbar navbar-dark bg-dark">

<div class="container">

<a class="navbar-brand"
href="index.php">

ADMIN DASHBOARD

</a>

</div>

</nav>


<div class="bg-primary text-white">

<div class="container py-4">

<h2>
DAILY MART
</h2>

<p class="mb-0">
Sistem Penjualan & Kasir
</p>

</div>

</div>


<nav class="navbar navbar-expand-lg bg-white shadow-sm">

<div class="container">

<a class="nav-link me-3"
href="index.php">

Dashboard

</a>

<a class="nav-link me-3"
href="pelanggan.php">

Pelanggan

</a>

<a class="nav-link me-3"
href="produk.php">

Produk

</a>

<a class="nav-link me-3"
href="penjualan.php">

Penjualan

</a>

<a class="nav-link me-3"
href="detail_penjualan.php">

Detail Penjualan

</a>

<a href="laporan.php">Laporan</a>

</a>

<a class="nav-link text-danger"
href="../logout.php">

Logout

</a>

</div>

</nav>


<div class="container py-4">