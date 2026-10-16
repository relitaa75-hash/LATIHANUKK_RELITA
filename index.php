<?php

session_start();

if (!isset($_SESSION['kasir_login']) || $_SESSION['kasir_login'] != true) {
    header("Location: login_kasir.php");
    exit;
}

include "koneksi.php";

/* =========================
   TAMBAH PELANGGAN
========================= */
if (isset($_POST['simpan_pelanggan'])) {

    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $telepon = mysqli_real_escape_string($koneksi, $_POST['telepon']);

    mysqli_query($koneksi, "
        INSERT INTO pelanggan
        (NamaPelanggan, Alamat, NomorTelepon)
        VALUES
        ('$nama', '$alamat', '$telepon')
    ");

    header("Location: index.php");
    exit;
}


/* =========================
   SIMPAN TRANSAKSI
========================= */
if (isset($_POST['simpan_transaksi'])) {

    $pelanggan = intval($_POST['PelangganID']);
    $produk = intval($_POST['ProdukID']);
    $jumlah = intval($_POST['JumlahProduk']);

    if ($jumlah <= 0) {
        echo "<script>
                alert('Jumlah produk harus lebih dari 0');
                window.location='index.php';
              </script>";
        exit;
    }

    /* Ambil data produk */
    $query_produk = mysqli_query(
        $koneksi,
        "SELECT * FROM produk WHERE ProdukID = '$produk'"
    );

    $data_produk_transaksi = mysqli_fetch_assoc($query_produk);

    if (!$data_produk_transaksi) {

        echo "<script>
                alert('Produk tidak ditemukan');
                window.location='index.php';
              </script>";
        exit;
    }

    /* Cek stok */
    if ($jumlah > $data_produk_transaksi['Stok']) {

        echo "<script>
                alert('Stok tidak cukup');
                window.location='index.php';
              </script>";
        exit;
    }

    $harga = $data_produk_transaksi['Harga'];
    $subtotal = $harga * $jumlah;

    /* Simpan penjualan */
    $simpan_penjualan = mysqli_query(
        $koneksi,
        "INSERT INTO penjualan
        (TanggalPenjualan, TotalHarga, PelangganID)
        VALUES
        (NOW(), '$subtotal', '$pelanggan')"
    );

    if ($simpan_penjualan) {

        $penjualan_id = mysqli_insert_id($koneksi);

        /* Simpan detail penjualan */
        $simpan_detail = mysqli_query(
            $koneksi,
            "INSERT INTO detailpenjualan
            (PenjualanID, ProdukID, JumlahProduk, Subtotal)
            VALUES
            ('$penjualan_id', '$produk', '$jumlah', '$subtotal')"
        );

        if ($simpan_detail) {

            /* Kurangi stok */
            mysqli_query(
                $koneksi,
                "UPDATE produk
                 SET Stok = Stok - '$jumlah'
                 WHERE ProdukID = '$produk'"
            );

            echo "<script>
                    alert('Transaksi berhasil disimpan');
                    window.location='index.php';
                  </script>";
            exit;

        } else {

            echo "<script>
                    alert('Detail transaksi gagal disimpan: " . mysqli_error($koneksi) . "');
                    window.location='index.php';
                  </script>";
            exit;
        }

    } else {

        echo "<script>
                alert('Transaksi gagal disimpan: " . mysqli_error($koneksi) . "');
                window.location='index.php';
              </script>";
        exit;
    }
}


/* =========================
   DATA PELANGGAN
========================= */
$data_pelanggan = mysqli_query(
    $koneksi,
    "SELECT * FROM pelanggan
     ORDER BY PelangganID DESC"
);


/* =========================
   DATA PRODUK
========================= */
$data_produk = mysqli_query(
    $koneksi,
    "SELECT * FROM produk
     ORDER BY ProdukID DESC"
);


/* =========================
   DATA PELANGGAN UNTUK TRANSAKSI
========================= */
$data_pelanggan_transaksi = mysqli_query(
    $koneksi,
    "SELECT * FROM pelanggan
     ORDER BY NamaPelanggan ASC"
);


/* =========================
   DATA PRODUK UNTUK TRANSAKSI
========================= */
$data_produk_transaksi = mysqli_query(
    $koneksi,
    "SELECT * FROM produk
     WHERE Stok > 0
     ORDER BY NamaProduk ASC"
);


/* =========================
   DATA TRANSAKSI TERAKHIR
   SEKARANG SUDAH ADA
   NAMA BARANG
========================= */
$data_penjualan = mysqli_query(
    $koneksi,
    "SELECT
        penjualan.PenjualanID,
        penjualan.TanggalPenjualan,
        penjualan.TotalHarga,
        pelanggan.NamaPelanggan,
        produk.NamaProduk
     
     FROM penjualan
     
     LEFT JOIN pelanggan
     ON penjualan.PelangganID = pelanggan.PelangganID
     
     LEFT JOIN detailpenjualan
     ON penjualan.PenjualanID = detailpenjualan.PenjualanID
     
     LEFT JOIN produk
     ON detailpenjualan.ProdukID = produk.ProdukID
     
     ORDER BY penjualan.PenjualanID DESC
     
     LIMIT 10"
);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Daily Mart</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }

        /* NAVBAR */

        .navbar {
            background: #212529;
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h2 {
            color: white;
            margin: 0;
        }

        .navbar a {
            background: white;
            color: #212529;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
        }

        /* HERO */

        .hero {
            background: #f80880ff;
            color: white;
            text-align: center;
            padding: 60px 20px;
        }

        .hero h1 {
            margin: 0;
            font-size: 45px;
        }

        .hero p {
            font-size: 20px;
            margin-top: 10px;
        }

        /* CONTAINER */

        .container {
            width: 90%;
            max-width: 1100px;
            margin: 30px auto;
        }

        /* CARD */

        .card {
            background: white;
            padding: 25px;
            margin-bottom: 25px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        .card h2 {
            margin-top: 0;
            color: #333;
        }

        /* FORM */

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 7px;
            font-weight: bold;
        }

        input,
        select {
            width: 100%;
            padding: 11px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .btn {
            background: #212529;
            color: white;
            padding: 11px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .btn:hover {
            opacity: 0.9;
        }

        /* TABLE */

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        table th {
            background: #212529;
            color: white;
        }

        /* FOOTER */

        footer {
            background: #212529;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 40px;
        }

    </style>

</head>

<body>


<!-- =========================
     NAVBAR
========================= -->

<div class="navbar">

    <h2>SELAMAT DATANG</h2>

    <a href="login.php">Login Admin</a>

</div>


<!-- =========================
     HERO
========================= -->

<div class="hero">

    <h1>DAILY MART</h1>

    <p>Sistem Penjualan & Kasir</p>

</div>


<div class="container">


<!-- =========================
     INFORMASI DAILY MART
========================= -->

<div class="card">

    <h2>DAILY MART</h2>

    <p>
        Selamat datang di website Daily Mart.
        Website ini digunakan untuk mengelola data
        pelanggan, produk dan transaksi penjualan.
    </p>

    <p>
        <b>SMK Negeri 3 Kendal</b>
    </p>

    <p>
        Produk:
        Sembako, Kebutuhan Mandi, Kebutuhan Rumah Tangga, Makanan & Minuman, Peralatan Sekolah
    </p>

</div>


<!-- =========================
     TRANSAKSI KASIR
========================= -->

<div class="card">

    <h2>Transaksi Kasir</h2>

    <p>
        Silakan isi transaksi penjualan langsung
        di halaman utama.
    </p>

    <form method="POST">

        <div class="form-group">

            <label>Pelanggan</label>

            <select name="PelangganID" required>

                <option value="">
                    -- Pilih Pelanggan --
                </option>

                <?php while ($p = mysqli_fetch_assoc($data_pelanggan_transaksi)) { ?>

                    <option value="<?= $p['PelangganID']; ?>">

                        <?= htmlspecialchars($p['NamaPelanggan']); ?>

                    </option>

                <?php } ?>

            </select>

        </div>


        <div class="form-group">

            <label>Produk</label>

            <select name="ProdukID" required>

                <option value="">
                    -- Pilih Produk --
                </option>

                <?php while ($p = mysqli_fetch_assoc($data_produk_transaksi)) { ?>

                    <option value="<?= $p['ProdukID']; ?>">

                        <?= htmlspecialchars($p['NamaProduk']); ?>

                        -
                        Rp <?= number_format($p['Harga'], 0, ',', '.'); ?>

                        -
                        Stok: <?= $p['Stok']; ?>

                    </option>

                <?php } ?>

            </select>

        </div>


        <div class="form-group">

            <label>Jumlah Produk</label>

            <input
                type="number"
                name="JumlahProduk"
                min="1"
                placeholder="Masukkan jumlah"
                required
            >

        </div>


        <button
            type="submit"
            name="simpan_transaksi"
            class="btn"
        >
            Simpan Transaksi
        </button>

    </form>

</div>


<!-- =========================
     TAMBAH PELANGGAN
========================= -->

<div class="card">

    <h2>Tambah Pelanggan</h2>

    <form method="POST">

        <div class="form-group">

            <label>Nama Pelanggan</label>

            <input
                type="text"
                name="nama"
                placeholder="Masukkan nama pelanggan"
                required
            >

        </div>


        <div class="form-group">

            <label>Alamat</label>

            <input
                type="text"
                name="alamat"
                placeholder="Masukkan alamat"
                required
            >

        </div>


        <div class="form-group">

            <label>Nomor Telepon</label>

            <input
                type="text"
                name="telepon"
                placeholder="Masukkan nomor telepon"
                required
            >

        </div>


        <button
            type="submit"
            name="simpan_pelanggan"
            class="btn"
        >
            Simpan Pelanggan
        </button>

    </form>

</div>


<!-- =========================
     DATA PELANGGAN
========================= -->

<div class="card">

    <h2>Data Pelanggan</h2>

    <table>

        <tr>

            <th>No</th>

            <th>Nama</th>

            <th>Alamat</th>

            <th>Nomor Telepon</th>

        </tr>

        <?php

        $no = 1;

        while ($p = mysqli_fetch_assoc($data_pelanggan)) {

        ?>

        <tr>

            <td>
                <?= $no++; ?>
            </td>

            <td>
                <?= htmlspecialchars($p['NamaPelanggan']); ?>
            </td>

            <td>
                <?= htmlspecialchars($p['Alamat']); ?>
            </td>

            <td>
                <?= htmlspecialchars($p['NomorTelepon']); ?>
            </td>

        </tr>

        <?php } ?>

    </table>

</div>


<!-- =========================
     DATA PRODUK
========================= -->

<div class="card">

    <h2>Data Produk</h2>

    <table>

        <tr>

            <th>No</th>

            <th>Nama Produk</th>

            <th>Harga</th>

            <th>Stok</th>

        </tr>

        <?php

        $no = 1;

        while ($p = mysqli_fetch_assoc($data_produk)) {

        ?>

        <tr>

            <td>
                <?= $no++; ?>
            </td>

            <td>
                <?= htmlspecialchars($p['NamaProduk']); ?>
            </td>

            <td>
                Rp <?= number_format($p['Harga'], 0, ',', '.'); ?>
            </td>

            <td>
                <?= $p['Stok']; ?>
            </td>

        </tr>

        <?php } ?>

    </table>

</div>


<!-- =========================
     DATA TRANSAKSI TERAKHIR
========================= -->

<div class="card">

    <h2>Data Transaksi Terakhir</h2>

    <table>

        <tr>

            <th>ID</th>

            <th>Tanggal</th>

            <th>Pelanggan</th>

            <th>Nama Barang</th>

            <th>Total</th>

        </tr>

        <?php

        while ($p = mysqli_fetch_assoc($data_penjualan)) {

        ?>

        <tr>

            <td>
                <?= $p['PenjualanID']; ?>
            </td>

            <td>
                <?= date(
                    'd-m-Y H:i:s',
                    strtotime($p['TanggalPenjualan'])
                ); ?>
            </td>

            <td>
                <?= htmlspecialchars(
                    $p['NamaPelanggan'] ?? '-'
                ); ?>
            </td>

            <td>
                <?= htmlspecialchars(
                    $p['NamaProduk'] ?? '-'
                ); ?>
            </td>

            <td>
                Rp <?= number_format(
                    $p['TotalHarga'],
                    0,
                    ',',
                    '.'
                ); ?>
            </td>

        </tr>

        <?php } ?>

    </table>

</div>


</div>


<!-- =========================
     FOOTER
========================= -->

<footer>

    © 2026 Daily Mart - SMK Negeri 3 Kendal

</footer>


</body>

</html>  