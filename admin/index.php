
<?php

include "../koneksi.php";
include "header.php";

$a = mysqli_fetch_assoc(
    mysqli_query(
        $koneksi,
        "SELECT COUNT(*) AS jumlah FROM pelanggan"
    )
);

$b = mysqli_fetch_assoc(
    mysqli_query(
        $koneksi,
        "SELECT COUNT(*) AS jumlah FROM produk"
    )
);

$c = mysqli_fetch_assoc(
    mysqli_query(
        $koneksi,
        "SELECT COUNT(*) AS jumlah FROM penjualan"
    )
);

?>

<h2>
Dashboard
</h2>

<p>
Selamat datang,
<b><?= $_SESSION['username']; ?></b>
</p>


<div class="row mt-4">


<div class="col-md-4">

<div class="card shadow border-0 text-center">

<div class="card-body">

<h5>Pelanggan</h5>

<h1>
<?= $a['jumlah']; ?>
</h1>

</div>

</div>

</div>


<div class="col-md-4">

<div class="card shadow border-0 text-center">

<div class="card-body">

<h5>Produk</h5>

<h1>
<?= $b['jumlah']; ?>
</h1>

</div>

</div>

</div>


<div class="col-md-4">

<div class="card shadow border-0 text-center">

<div class="card-body">

<h5>Penjualan</h5>

<h1>
<?= $c['jumlah']; ?>
</h1>

</div>

</div>

</div>


</div>


<div class="card shadow border-0 mt-4">

<div class="card-body">

<h4>
Kategori Produk
</h4>

<p>
Produk yang tersedia
</p>

<ul>

<li>Sembako</li>

<li>Kebutuhan Mandi</li>

<li>Kebutuhan Rumah Tangga</li>

<li>Peralatan Sekolah</li>

<li>Makanan & Minuman</li>

</ul>

</div>

</div>


<?php include "footer.php"; ?>