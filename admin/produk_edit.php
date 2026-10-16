<?php

include "../koneksi.php";

$id = $_GET['id'];

$data = mysqli_fetch_assoc(
    mysqli_query(
        $koneksi,
        "SELECT * FROM produk
         WHERE ProdukID='$id'"
    )
);


if(isset($_POST['update'])){

    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    mysqli_query(
        $koneksi,

        "UPDATE produk SET

        NamaProduk='$nama',

        Harga='$harga',

        Stok='$stok'

        WHERE ProdukID='$id'"
    );

    header("Location: produk.php");

    exit;
}

?>


<!DOCTYPE html>

<html>

<head>

<title>Edit Produk</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

</head>

<body>

<div class="container mt-5">

<h3>Edit Produk</h3>

<form method="POST">

<input
name="nama"
class="form-control mb-2"
value="<?= $data['NamaProduk']; ?>"
required
>

<input
type="number"
name="harga"
class="form-control mb-2"
value="<?= $data['Harga']; ?>"
required
>

<input
type="number"
name="stok"
class="form-control mb-2"
value="<?= $data['Stok']; ?>"
required
>

<button
name="update"
class="btn btn-primary">

Update

</button>

<a
href="produk.php"
class="btn btn-secondary">

Kembali

</a>

</form>

</div>

</body>

</html>