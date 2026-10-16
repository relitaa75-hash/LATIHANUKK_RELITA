<?php

include "../koneksi.php";
include "header.php";


if(isset($_POST['simpan'])){

    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    mysqli_query(
        $koneksi,
        "INSERT INTO produk
        (NamaProduk,Harga,Stok)
        VALUES
        ('$nama','$harga','$stok')"
    );
    
    mysqli_query(
    $koneksi,
    "INSERT INTO log_aktivitas
    (NamaUser, Aktivitas)
    VALUES
    ('Admin', 'Menambahkan produk: $nama')"
);

    header("Location: produk.php");

    exit;
}


if(isset($_GET['hapus'])){

    $id = $_GET['hapus'];

    mysqli_query(
        $koneksi,
        "DELETE FROM produk
        WHERE ProdukID='$id'"
    );

    header("Location: produk.php");

    exit;
}


$data = mysqli_query(
    $koneksi,
    "SELECT * FROM produk
     ORDER BY ProdukID DESC"
);

?>


<h3>Data Produk</h3>


<div class="card shadow border-0 mb-4">

<div class="card-body">

<h5>Tambah Produk</h5>

<form method="POST">

<input
type="text"
name="nama"
placeholder="Nama produk"
class="form-control mb-2"
required
>

<input
type="number"
name="harga"
placeholder="Harga"
class="form-control mb-2"
required
>

<input
type="number"
name="stok"
placeholder="Stok"
class="form-control mb-2"
required
>

<button
name="simpan"
class="btn btn-primary">

Simpan

</button>

</form>

</div>

</div>


<div class="card shadow border-0">

<div class="card-body">


<!-- =========================
     FITUR CARI PRODUK
========================= -->

<input
type="text"
id="cariProduk"
class="form-control mb-3"
placeholder="Cari nama produk, harga, atau stok..."
onkeyup="cariProduk()"
>


<table
class="table table-bordered"
id="tabelProduk">

<tr>

<th>No</th>
<th>Produk</th>
<th>Harga</th>
<th>Stok</th>
<th>Aksi</th>

</tr>


<?php

$no=1;

while($d=mysqli_fetch_assoc($data)){

?>

<tr class="barisProduk">

<td><?= $no++; ?></td>

<td>
<?= htmlspecialchars($d['NamaProduk']); ?>
</td>

<td>
Rp <?= number_format($d['Harga'],0,',','.'); ?>
</td>

<td>
<?= $d['Stok']; ?>
</td>

<td>

<a
href="produk_edit.php?id=<?= $d['ProdukID']; ?>"
class="btn btn-warning btn-sm">

Edit

</a>

<a
href="produk.php?hapus=<?= $d['ProdukID']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Hapus produk?')">

Hapus

</a>

</td>

</tr>

<?php } ?>

</table>


<!-- =========================
     PESAN TIDAK DITEMUKAN
========================= -->

<div
id="produkTidakDitemukan"
style="
    display:none;
    text-align:center;
    padding:15px;
    color:#777;
">

Data produk tidak ditemukan.

</div>


</div>

</div>


<!-- =========================
     JAVASCRIPT PENCARIAN
========================= -->

<script>

function cariProduk() {

    let input = document.getElementById("cariProduk");

    let filter = input.value.toLowerCase().trim();

    let tabel = document.getElementById("tabelProduk");

    let baris = tabel.getElementsByClassName("barisProduk");

    let jumlahDitemukan = 0;


    for (let i = 0; i < baris.length; i++) {

        let teks = baris[i].innerText.toLowerCase();


        if (teks.includes(filter)) {

            baris[i].style.display = "";

            jumlahDitemukan++;

        } else {

            baris[i].style.display = "none";

        }

    }


    let pesan = document.getElementById(
        "produkTidakDitemukan"
    );


    if (filter !== "" && jumlahDitemukan === 0) {

        pesan.style.display = "block";

    } else {

        pesan.style.display = "none";

    }

}

</script>


<?php include "footer.php"; ?>