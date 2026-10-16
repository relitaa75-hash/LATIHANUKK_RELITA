<?php

session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

include "../koneksi.php";


if (isset($_POST['simpan'])) {

    $tanggal = date('Y-m-d');

    $pelanggan = $_POST['pelanggan'];

    $total = $_POST['total'];

    $bayar = $_POST['bayar'];


    /* HITUNG KEMBALIAN */

    $kembalian = $bayar - $total;


    /* CEK UANG */

    if ($bayar < $total) {

        echo "<script>

                alert('Uang pembayaran kurang!');

                window.location='penjualan.php';

              </script>";

        exit;
    }


    /* SIMPAN PENJUALAN */

    $query = mysqli_query(
        $koneksi,

        "INSERT INTO penjualan
        (TanggalPenjualan, PelangganID, TotalHarga)

        VALUES
        ('$tanggal', '$pelanggan', '$total')"
    );


    if ($query) {

        $id = mysqli_insert_id($koneksi);


        /* SIMPAN DATA UNTUK NOTA */

        $_SESSION['nota'] = [

            'id' => $id,

            'tanggal' => $tanggal,

            'pelanggan' => $pelanggan,

            'total' => $total,

            'bayar' => $bayar,

            'kembalian' => $kembalian

        ];


        /* PINDAH KE NOTA */

        header("Location: nota.php");

        exit;


    } else {

        echo "Data gagal disimpan.";

        echo "<br>";

        echo mysqli_error($koneksi);

    }

}

?><?php

session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

include "../koneksi.php";


if (isset($_POST['simpan'])) {

    $tanggal = date('Y-m-d');

    $pelanggan = $_POST['pelanggan'];

    $total = $_POST['total'];

    $bayar = $_POST['bayar'];


    /* HITUNG KEMBALIAN */

    $kembalian = $bayar - $total;


    /* CEK UANG */

    if ($bayar < $total) {

        echo "<script>

                alert('Uang pembayaran kurang!');

                window.location='penjualan.php';

              </script>";

        exit;
    }


    /* SIMPAN PENJUALAN */

    $query = mysqli_query(
        $koneksi,

        "INSERT INTO penjualan
        (TanggalPenjualan, PelangganID, TotalHarga)

        VALUES
        ('$tanggal', '$pelanggan', '$total')"
    );


    if ($query) {

        $id = mysqli_insert_id($koneksi);


        /* SIMPAN DATA UNTUK NOTA */

        $_SESSION['nota'] = [

            'id' => $id,

            'tanggal' => $tanggal,

            'pelanggan' => $pelanggan,

            'total' => $total,

            'bayar' => $bayar,

            'kembalian' => $kembalian

        ];


        /* PINDAH KE NOTA */

        header("Location: nota.php");

        exit;


    } else {

        echo "Data gagal disimpan.";

        echo "<br>";

        echo mysqli_error($koneksi);

    }

}

?>