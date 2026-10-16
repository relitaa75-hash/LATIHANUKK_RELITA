
<?php
session_start();
include "../koneksi.php";

/* =========================
   CARI DATA DETAIL PENJUALAN
   ========================= */

$cari = "";

if (isset($_GET['cari'])) {
    $cari = $_GET['cari'];
}

/* =========================
   AMBIL DATA DETAIL PENJUALAN
   ========================= */

$query = mysqli_query($koneksi, "
    SELECT 
        dp.DetailID, 
        dp.PenjualanID, 
        dp.ProdukID, 
        dp.JumlahProduk, 
        dp.Subtotal, 
        p.NamaProduk, 
        p.Harga 
    FROM detailpenjualan dp 
    LEFT JOIN produk p ON dp.ProdukID = p.ProdukID
    WHERE 
        dp.DetailID LIKE '%$cari%'
        OR dp.PenjualanID LIKE '%$cari%'
        OR p.NamaProduk LIKE '%$cari%'
    ORDER BY dp.DetailID DESC
");

/* Kalau tabel belum ada / query error */
if (!$query) {
    $error = mysqli_error($koneksi);
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Detail Penjualan - Daily Mart</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f4f6f8;
            color: #111;
        }

        /* =====================
           HEADER ADMIN
           ===================== */

        .admin-header {
            background: #212529;
            color: white;
            padding: 22px 0;
        }

        .container {
            width: 83%;
            margin: auto;
        }

        .admin-header h3 {
            margin: 0;
            font-size: 20px;
            font-weight: normal;
        }

        /* =====================
           BANNER DAILY MART
           ===================== */

        .banner {
            background: #176df5;
            color: white;
            padding: 26px 0;
        }

        .banner h1 {
            margin: 0 0 10px 0;
            font-size: 32px;
        }

        .banner p {
            margin: 0;
            font-size: 15px;
        }

        /* =====================
           MENU
           ===================== */

        .navbar {
            background: white;
            border-bottom: 1px solid #ddd;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }

        .menu {
            width: 83%;
            margin: auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .menu a {
            text-decoration: none;
            color: #111;
            padding: 13px 0;
            font-size: 15px;
        }

        .menu a:hover {
            color: #176df5;
        }

        .menu .active {
            color: #176df5;
            font-weight: bold;
        }

        .menu .logout {
            color: red;
        }

        /* =====================
           CONTENT
           ===================== */

        .content {
            width: 83%;
            margin: 28px auto;
        }

        .content-title {
            font-size: 28px;
            margin-bottom: 20px;
        }

        /* =====================
           CARD
           ===================== */

        .card {
            background: white;
            border-radius: 8px;
            padding: 26px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .card-header h2 {
            margin: 0;
            font-size: 24px;
            font-weight: normal;
        }

        /* =====================
           BUTTON
           ===================== */

        .btn {
            display: inline-block;
            padding: 9px 13px;
            border-radius: 6px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-primary {
            background: #176df5;
            color: white;
        }

        .btn-primary:hover {
            background: #095bd9;
        }

        /* =====================
           PENCARIAN
           ===================== */

        .search-box {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .search-box input {
            flex: 1;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            outline: none;
        }

        .search-box input:focus {
            border-color: #176df5;
        }

        .btn-search {
            background: #176df5;
            color: white;
        }

        .btn-search:hover {
            background: #095bd9;
        }

        .btn-reset {
            background: #6c757d;
            color: white;
        }

        .btn-reset:hover {
            background: #5a6268;
        }

        /* =====================
           TABLE
           ===================== */

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #d8d8d8;
            padding: 11px 10px;
            text-align: left;
            font-size: 15px;
        }

        th {
            background: #fafafa;
            font-weight: bold;
        }

        tr:hover {
            background: #f8f8f8;
        }

        .rupiah {
            white-space: nowrap;
        }

        /* =====================
           PESAN ERROR
           ===================== */

        .error {
            background: #f8d7da;
            color: #842029;
            border: 1px solid #f5c2c7;
            padding: 15px;
            border-radius: 6px;
        }

        /* =====================
           RESPONSIVE
           ===================== */

        @media (max-width: 768px) {

            .container,
            .content,
            .menu {
                width: 94%;
            }

            .menu {
                flex-wrap: wrap;
                gap: 15px;
                justify-content: flex-start;
            }

            .menu a {
                margin-right: 15px;
            }

            .banner h1 {
                font-size: 27px;
            }

            .card-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .search-box {
                flex-direction: column;
            }

        }

    </style>

</head>


<body>


<!-- =========================
     ADMIN HEADER
     ========================= -->

<div class="admin-header">

    <div class="container">

        <h3>ADMIN DASHBOARD</h3>

    </div>

</div>


<!-- =========================
     BANNER
     ========================= -->

<div class="banner">

    <div class="container">

        <h1>DAILY MART</h1>

        <p>Sistem Penjualan & Kasir</p>

    </div>

</div>


<!-- =========================
     MENU
     ========================= -->

<div class="navbar">

    <div class="menu">

        <a href="index.php">
            Dashboard
        </a>

        <a href="pelanggan.php">
            Pelanggan
        </a>

        <a href="produk.php">
            Produk
        </a>

        <a href="penjualan.php">
            Penjualan
        </a>

        <a href="detail_penjualan.php"
           class="active">
            Detail Penjualan
        </a>

        <a href="laporan.php">
            Laporan
        </a>

        <a href="log_aktivitas.php">
    Log Aktivitas
</a>

        <a href="../index.php"
           class="logout">
            Logout
        </a>

    </div>

</div>


<!-- =========================
     CONTENT
     ========================= -->

<div class="content">

    <div class="content-title">
        Detail Penjualan
    </div>


    <div class="card">


        <div class="card-header">

            <h2>
                Data Detail Penjualan
            </h2>

            <a href="penjualan.php"
               class="btn btn-primary">

                + Tambah Penjualan

            </a>

        </div>


        <!-- =========================
             FORM CARI
             ========================= -->

        <form method="GET"
              action="detail_penjualan.php"
              class="search-box">

            <input
                type="text"
                name="cari"
                placeholder="Cari ID Detail, ID Penjualan, atau Nama Produk..."
                value="<?= htmlspecialchars($cari); ?>"
            >

            <button type="submit"
                    class="btn btn-search">

                Cari

            </button>

            <a href="detail_penjualan.php"
               class="btn btn-reset">

                Reset

            </a>

        </form>


        <?php if (isset($error)) { ?>

            <div class="error">

                <b>Data belum dapat ditampilkan.</b>

                <br><br>

                <?= htmlspecialchars($error); ?>

                <br><br>

                Pastikan tabel
                <b>detailpenjualan</b>
                dan
                <b>produk</b>
                sudah ada di database.

            </div>

        <?php } else { ?>


        <div class="table-wrapper">

            <table>

                <thead>

                    <tr>

                        <th>No</th>

                        <th>ID Detail</th>

                        <th>ID Penjualan</th>

                        <th>Produk</th>

                        <th>Harga</th>

                        <th>Jumlah</th>

                        <th>Subtotal</th>

                    </tr>

                </thead>


                <tbody>


                <?php

                $no = 1;

                if (mysqli_num_rows($query) > 0) {

                    while ($data = mysqli_fetch_assoc($query)) {

                        $namaProduk = !empty($data['NamaProduk'])
                            ? $data['NamaProduk']
                            : 'Produk tidak ditemukan';

                        $harga = isset($data['Harga'])
                            ? $data['Harga']
                            : 0;

                        $jumlah = isset($data['JumlahProduk'])
                            ? $data['JumlahProduk']
                            : 0;

                        $subtotal = isset($data['Subtotal'])
                            ? $data['Subtotal']
                            : ($harga * $jumlah);

                ?>


                    <tr>

                        <td>
                            <?= $no++; ?>
                        </td>


                        <td>
                            <?= htmlspecialchars(
                                $data['DetailID']
                            ); ?>
                        </td>


                        <td>
                            <?= htmlspecialchars(
                                $data['PenjualanID']
                            ); ?>
                        </td>


                        <td>
                            <?= htmlspecialchars(
                                $namaProduk
                            ); ?>
                        </td>


                        <td class="rupiah">

                            Rp
                            <?= number_format(
                                $harga,
                                0,
                                ',',
                                '.'
                            ); ?>

                        </td>


                        <td>
                            <?= htmlspecialchars(
                                $jumlah
                            ); ?>
                        </td>


                        <td class="rupiah">

                            Rp
                            <?= number_format(
                                $subtotal,
                                0,
                                ',',
                                '.'
                            ); ?>

                        </td>

                    </tr>


                <?php

                    }

                } else {

                ?>


                    <tr>

                        <td colspan="7"
                            style="text-align:center;">

                            <?php if ($cari != "") { ?>

                                Data detail penjualan
                                dengan kata
                                "<b><?= htmlspecialchars($cari); ?></b>"
                                tidak ditemukan.

                            <?php } else { ?>

                                Belum ada detail penjualan.

                            <?php } ?>

                        </td>

                    </tr>


                <?php

                }

                ?>


                </tbody>

            </table>

        </div>


        <?php } ?>


    </div>

</div>


</body>

</html>

