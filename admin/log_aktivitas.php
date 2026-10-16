
<?php

session_start();

include "../koneksi.php";

/* =========================
   AMBIL DATA LOG AKTIVITAS
   ========================= */

$query = mysqli_query($koneksi, "
    SELECT *
    FROM log_aktivitas
    ORDER BY LogID DESC
");

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

    <title>Log Aktivitas - Daily Mart</title>

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

        /* =====================
           ERROR
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

        <a href="detail_penjualan.php">
            Detail Penjualan
        </a>

        <a href="laporan.php">
            Laporan
        </a>

        <a href="log_aktivitas.php"
           class="active">
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
        Log Aktivitas
    </div>


    <div class="card">

        <div class="card-header">

            <h2>
                Riwayat Aktivitas
            </h2>

        </div>


        <?php if (isset($error)) { ?>

            <div class="error">

                <b>Data log belum dapat ditampilkan.</b>

                <br><br>

                <?= htmlspecialchars($error); ?>

                <br><br>

                Pastikan tabel
                <b>log_aktivitas</b>
                sudah dibuat di database.

            </div>

        <?php } else { ?>


        <div class="table-wrapper">

            <table>

                <thead>

                    <tr>

                        <th>No</th>

                        <th>ID</th>

                        <th>User</th>

                        <th>Aktivitas</th>

                        <th>Waktu</th>

                    </tr>

                </thead>


                <tbody>

                <?php

                $no = 1;

                if (mysqli_num_rows($query) > 0) {

                    while ($data = mysqli_fetch_assoc($query)) {

                ?>

                    <tr>

                        <td>
                            <?= $no++; ?>
                        </td>

                        <td>
                            <?= htmlspecialchars(
                                $data['LogID']
                            ); ?>
                        </td>

                        <td>
                            <?= htmlspecialchars(
                                $data['NamaUser'] ?? '-'
                            ); ?>
                        </td>

                        <td>
                            <?= htmlspecialchars(
                                $data['Aktivitas']
                            ); ?>
                        </td>

                        <td>
                            <?= date(
                                'd-m-Y H:i:s',
                                strtotime($data['Waktu'])
                            ); ?>
                        </td>

                    </tr>

                <?php

                    }

                } else {

                ?>

                    <tr>

                        <td colspan="5"
                            style="text-align:center;">

                            Belum ada aktivitas.

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

