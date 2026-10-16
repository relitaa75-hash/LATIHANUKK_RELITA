<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

include "../koneksi.php";

$id = $_GET['id'];

$data = mysqli_fetch_assoc(
    mysqli_query(
        $koneksi,
        "SELECT * FROM pelanggan WHERE PelangganID='$id'"
    )
);

if (isset($_POST['simpan'])) {

    $nama = mysqli_real_escape_string(
        $koneksi,
        $_POST['nama']
    );

    $alamat = mysqli_real_escape_string(
        $koneksi,
        $_POST['alamat']
    );

    $telepon = mysqli_real_escape_string(
        $koneksi,
        $_POST['telepon']
    );

    mysqli_query(
        $koneksi,
        "UPDATE pelanggan SET
        NamaPelanggan='$nama',
        Alamat='$alamat',
        NomorTelepon='$telepon'
        WHERE PelangganID='$id'"
    );

    header("Location: pelanggan.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Pelanggan</title>

    <style>
        body {
            font-family: Arial;
            background: #f4f4f4;
        }

        .card {
            width: 500px;
            max-width: 90%;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
        }

        input,
        textarea {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
        }

        button,
        a {
            padding: 12px 20px;
            text-decoration: none;
            border: none;
            background: #087df5;
            color: white;
            cursor: pointer;
        }
    </style>
</head>

<body>

<div class="card">

    <h2>Edit Pelanggan</h2>

    <form method="POST">

        <input type="text"
               name="nama"
               value="<?= htmlspecialchars($data['NamaPelanggan']); ?>"
               placeholder="Nama pelanggan"
               required>

        <textarea name="alamat"
                  placeholder="Alamat"
                  required><?= htmlspecialchars($data['Alamat']); ?></textarea>

        <input type="text"
               name="telepon"
               value="<?= htmlspecialchars($data['NomorTelepon']); ?>"
               placeholder="Nomor telepon"
               required>

        <button type="submit" name="simpan">
            Simpan
        </button>

        <a href="pelanggan.php">Kembali</a>

    </form>

</div>

</body>
</html>