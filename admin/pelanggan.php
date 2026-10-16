<?php 
 
session_start(); 
 
if (!isset($_SESSION['login'])) { 
    header("Location: ../login.php"); 
    exit; 
} 
 
include "../koneksi.php"; 


/* ========================= 
   TAMBAH PELANGGAN
========================= */ 

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
 
    $tambah = mysqli_query( 
        $koneksi, 
        "INSERT INTO pelanggan 
        (NamaPelanggan, Alamat, NomorTelepon) 
        VALUES 
        ('$nama', '$alamat', '$telepon')" 
    ); 
 
    if ($tambah) { 
 
        echo " 
        <script> 
            alert('Data pelanggan berhasil ditambahkan!'); 
            window.location='pelanggan.php'; 
        </script> 
        "; 
 
    } else { 
 
        echo " 
        <script> 
            alert('Data pelanggan gagal ditambahkan!'); 
            window.location='pelanggan.php'; 
        </script> 
        "; 
    } 
 
    exit; 
} 
 
 
/* ========================= 
   HAPUS PELANGGAN 
========================= */ 
 
if (isset($_GET['hapus'])) { 
 
    $id = intval($_GET['hapus']); 
 
    // Cari semua PenjualanID milik pelanggan 
    $cek_penjualan = mysqli_query( 
        $koneksi, 
        "SELECT PenjualanID 
         FROM penjualan 
         WHERE PelangganID = $id" 
    ); 
 
    // Hapus detail penjualan terlebih dahulu 
    while ($data_penjualan = mysqli_fetch_assoc($cek_penjualan)) { 
 
        $penjualan_id = intval($data_penjualan['PenjualanID']); 
 
        mysqli_query( 
            $koneksi, 
            "DELETE FROM detailpenjualan 
             WHERE PenjualanID = $penjualan_id" 
        ); 
    } 
 
    // Setelah detail terhapus, hapus penjualan 
    mysqli_query( 
        $koneksi, 
        "DELETE FROM penjualan 
         WHERE PelangganID = $id" 
    ); 
 
    // Terakhir hapus pelanggan 
    $hapus = mysqli_query( 
        $koneksi, 
        "DELETE FROM pelanggan 
         WHERE PelangganID = $id" 
    ); 
 
    if ($hapus) { 
 
        echo " 
        <script> 
            alert('Data pelanggan berhasil dihapus!'); 
            window.location='pelanggan.php'; 
        </script> 
        "; 
 
    } else { 
 
        echo " 
        <script> 
            alert('Data pelanggan gagal dihapus!'); 
            window.location='pelanggan.php'; 
        </script> 
        "; 
    } 
 
    exit; 
} 
 
 
/* ========================= 
   UPDATE PELANGGAN 
========================= */ 
 
if (isset($_POST['update'])) { 
 
    $id = intval($_POST['id']); 
 
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
        NamaPelanggan = '$nama', 
        Alamat = '$alamat', 
        NomorTelepon = '$telepon' 
        WHERE PelangganID = $id" 
    ); 
 
    header("Location: pelanggan.php"); 
    exit; 
} 
 
 
/* ========================= 
   DATA YANG AKAN DIEDIT 
========================= */ 
 
$data_edit = null; 
 
if (isset($_GET['edit'])) { 
 
    $id_edit = intval($_GET['edit']); 
 
    $hasil_edit = mysqli_query( 
        $koneksi, 
        "SELECT * FROM pelanggan 
         WHERE PelangganID = $id_edit" 
    ); 
 
    $data_edit = mysqli_fetch_assoc($hasil_edit); 
} 
 
?> 
 
<!DOCTYPE html> 
<html lang="id"> 
 
<head> 
 
    <meta charset="UTF-8"> 
 
    <meta name="viewport" 
          content="width=device-width, initial-scale=1.0"> 
 
    <title>Data Pelanggan - Daily Mart</title> 
 
    <style> 
 
        * { 
            box-sizing: border-box; 
        } 
 
        body { 
            margin: 0; 
            font-family: Arial, sans-serif; 
            background: #f4f5f7; 
            color: #222; 
        } 
 
 
        /* ========================= 
           HEADER 
        ========================= */ 
 
        .topbar { 
            background: #222; 
            color: white; 
            padding: 18px 8%; 
        } 
 
        .topbar h2 { 
            margin: 0; 
            font-size: 22px; 
        } 
 
 
        /* ========================= 
           HERO 
        ========================= */ 
 
        .hero { 
            background: #087df5; 
            color: white; 
            padding: 45px 8%; 
        } 
 
        .hero h1 { 
            margin: 0 0 12px; 
            font-size: 34px; 
        } 
 
        .hero h3 { 
            margin: 0; 
            font-size: 18px; 
            font-weight: normal; 
        } 
 
 
        /* ========================= 
           NAVBAR 
        ========================= */ 
 
        .navbar { 
            background: white; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            padding: 14px 8%; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.10); 
            flex-wrap: wrap; 
            gap: 15px; 
        } 
 
        .navbar a { 
            color: #222; 
            text-decoration: none; 
            font-size: 16px; 
        } 
 
        .navbar a:hover { 
            color: #087df5; 
        } 
 
        .navbar .logout { 
            color: red; 
        } 
 
 
        /* ========================= 
           CONTAINER 
        ========================= */ 
 
        .container { 
            width: 85%; 
            margin: 35px auto; 
        } 
 
        .container h2 { 
            font-size: 28px; 
            margin-bottom: 20px; 
        } 
 
 
        /* ========================= 
           CARD 
        ========================= */ 
 
        .card { 
            background: white; 
            padding: 25px; 
            border-radius: 8px; 
            box-shadow: 0 5px 15px rgba(0,0,0,0.10); 
            overflow-x: auto; 
        } 
 
 
        /* ========================= 
           PENCARIAN 
        ========================= */ 
 
        .search-box { 
            width: 100%; 
            padding: 12px 15px; 
            margin-bottom: 20px; 
            border: 1px solid #ccc; 
            border-radius: 5px; 
            font-size: 15px; 
            outline: none; 
        } 
 
        .search-box:focus { 
            border-color: #087df5; 
            box-shadow: 0 0 3px rgba(8,125,245,0.4); 
        } 
 
 
        /* ========================= 
           TABLE 
        ========================= */ 
 
        table { 
            width: 100%; 
            border-collapse: collapse; 
        } 
 
        th { 
            background: #333; 
            color: white; 
            padding: 14px; 
            text-align: left; 
        } 
 
        td { 
            padding: 14px; 
            border-bottom: 1px solid #ddd; 
        } 
 
        tr:hover { 
            background: #f8f8f8; 
        } 
 
 
        /* ========================= 
           PESAN DATA TIDAK DITEMUKAN 
        ========================= */ 
 
        #tidakDitemukan { 
            display: none; 
            text-align: center; 
            padding: 20px; 
            color: #777; 
        } 
 
 
        /* ========================= 
           BUTTON 
        ========================= */ 
 
        .btn { 
            display: inline-block; 
            padding: 8px 13px; 
            border-radius: 5px; 
            text-decoration: none; 
            color: white; 
            font-size: 14px; 
        } 
 
        .btn-edit { 
            background: #ffc107; 
            color: #222; 
        } 
 
        .btn-hapus { 
            background: #dc3545; 
        } 
 
        .btn-edit:hover, 
        .btn-hapus:hover { 
            opacity: 0.8; 
        } 
 
 
        /* ========================= 
           FORM EDIT 
        ========================= */ 
 
        .edit-card { 
            margin-bottom: 25px; 
        } 
 
        .edit-card h3 { 
            margin-top: 0; 
            font-size: 22px; 
        } 
 
        .form-group { 
            margin-bottom: 15px; 
        } 
 
        .form-group label { 
            display: block; 
            margin-bottom: 7px; 
            font-weight: bold; 
        } 
 
        .form-group input, 
        .form-group textarea { 
            width: 100%; 
            padding: 12px; 
            border: 1px solid #ccc; 
            border-radius: 5px; 
            font-size: 15px; 
        } 
 
        .form-group textarea { 
            height: 100px; 
            resize: vertical; 
        } 
 
        .btn-simpan { 
            background: #087df5; 
            border: none; 
            color: white; 
            padding: 11px 18px; 
            border-radius: 5px; 
            cursor: pointer; 
            font-size: 15px; 
        } 
 
        .btn-batal { 
            display: inline-block; 
            background: #777; 
            color: white; 
            text-decoration: none; 
            padding: 11px 18px; 
            border-radius: 5px; 
            margin-left: 5px; 
        } 
 
 
        /* ========================= 
           RESPONSIVE 
        ========================= */ 
 
        @media (max-width: 768px) { 
 
            .container { 
                width: 92%; 
            } 
 
            .hero h1 { 
                font-size: 28px; 
            } 
 
            .navbar { 
                justify-content: flex-start; 
            } 
 
            .navbar a { 
                margin-right: 15px; 
            } 
 
        } 
 
    </style> 
 
</head> 
 
<body> 
 
 
<!-- ========================= 
     HEADER 
========================= --> 
 
<div class="topbar"> 
 
    <h2>ADMIN DASHBOARD</h2> 
 
</div> 
 
 
<!-- ========================= 
     HERO 
========================= --> 
 
<div class="hero"> 
 
    <h1>DAILY MART</h1> 
 
    <h3>Sistem Penjualan & Kasir</h3> 
 
</div> 
 
 
<!-- ========================= 
     NAVBAR 
========================= --> 
 
<div class="navbar"> 
 
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
 
    <a href="detailpenjualan.php"> 
        Detail Penjualan 
    </a> 

    <a href="laporan.php">Laporan</a>

    <a href="log_aktivitas.php">
    Log Aktivitas
</a>
 
    <a href="../logout.php" class="logout"> 
        Logout 
    </a> 
 
</div> 
 
 
<!-- ========================= 
     ISI 
========================= --> 
 
<div class="container"> 
 
    <h2>Data Pelanggan</h2> 


    <!-- =====================
         TAMBAH PELANGGAN
    ====================== -->

    <div class="card edit-card">

        <h3>Tambah Pelanggan</h3>

        <form method="POST">

            <div class="form-group">

                <label>
                    Nama Pelanggan
                </label>

                <input
                    type="text"
                    name="nama"
                    placeholder="Masukkan nama pelanggan"
                    required
                >

            </div>


            <div class="form-group">

                <label>
                    Alamat
                </label>

                <textarea
                    name="alamat"
                    placeholder="Masukkan alamat pelanggan"
                    required
                ></textarea>

            </div>


            <div class="form-group">

                <label>
                    Nomor Telepon
                </label>

                <input
                    type="text"
                    name="telepon"
                    placeholder="Masukkan nomor telepon"
                    required
                >

            </div>


            <button
                type="submit"
                name="simpan"
                class="btn-simpan"
            >
                Simpan Pelanggan
            </button>

        </form>

    </div>
 
 
    <!-- ===================== 
         FORM EDIT 
    ====================== --> 
 
    <?php if ($data_edit) { ?> 
 
        <div class="card edit-card"> 
 
            <h3>Edit Pelanggan</h3> 
 
            <form method="POST"> 
 
                <input 
                    type="hidden" 
                    name="id" 
                    value="<?= $data_edit['PelangganID']; ?>" 
                > 
 
 
                <div class="form-group"> 
 
                    <label> 
                        Nama Pelanggan 
                    </label> 
 
                    <input 
                        type="text" 
                        name="nama" 
                        value="<?= htmlspecialchars( 
                            $data_edit['NamaPelanggan'] 
                        ); ?>" 
                        required 
                    > 
 
                </div> 
 
 
                <div class="form-group"> 
 
                    <label> 
                        Alamat 
                    </label> 
 
                    <textarea 
                        name="alamat" 
                        required 
                    ><?= htmlspecialchars( 
                        $data_edit['Alamat'] 
                    ); ?></textarea> 
 
                </div> 
 
 
                <div class="form-group"> 
 
                    <label> 
                        Nomor Telepon 
                    </label> 
 
                    <input 
                        type="text" 
                        name="telepon" 
                        value="<?= htmlspecialchars( 
                            $data_edit['NomorTelepon'] 
                        ); ?>" 
                        required 
                    > 
 
                </div> 
 
 
                <button 
                    type="submit" 
                    name="update" 
                    class="btn-simpan" 
                > 
                    Simpan Perubahan 
                </button> 
 
 
                <a 
                    href="pelanggan.php" 
                    class="btn-batal" 
                > 
                    Batal 
                </a> 
 
            </form> 
 
        </div> 
 
    <?php } ?> 
 
 
    <!-- ===================== 
         DATA PELANGGAN 
    ====================== --> 
 
    <div class="card"> 
 
        <!-- ===================== 
             PENCARIAN 
        ====================== --> 
 
        <input 
            type="text" 
            id="cariPelanggan" 
            class="search-box" 
            placeholder="Cari nama, alamat, atau nomor telepon..." 
            onkeyup="cariPelanggan()" 
        > 
 
 
        <table id="tabelPelanggan"> 
 
            <tr> 
 
                <th>No</th> 
 
                <th>Nama</th> 
 
                <th>Alamat</th> 
 
                <th>No. Telepon</th> 
 
                <th>Aksi</th> 
 
            </tr> 
 
 
            <?php 
 
            $no = 1; 
 
            $query = mysqli_query( 
                $koneksi, 
                "SELECT * FROM pelanggan 
                 ORDER BY PelangganID DESC" 
            ); 
 
            while ($data = mysqli_fetch_assoc($query)) { 
 
            ?> 
 
            <tr class="barisPelanggan"> 
 
                <td> 
                    <?= $no++; ?> 
                </td> 
 
                <td> 
                    <?= htmlspecialchars( 
                        $data['NamaPelanggan'] 
                    ); ?> 
                </td> 
 
                <td> 
                    <?= htmlspecialchars( 
                        $data['Alamat'] 
                    ); ?> 
                </td> 
 
                <td> 
                    <?= htmlspecialchars( 
                        $data['NomorTelepon'] 
                    ); ?> 
                </td> 
 
                <td> 
 
                    <a 
                        href="pelanggan.php?edit=<?= $data['PelangganID']; ?>" 
                        class="btn btn-edit" 
                    > 
                        Edit 
                    </a> 
 
 
                    <a 
                        href="pelanggan.php?hapus=<?= $data['PelangganID']; ?>" 
                        class="btn btn-hapus" 
                        onclick="return confirm('Yakin ingin menghapus pelanggan ini? Data penjualan pelanggan juga akan terhapus!')" 
                    > 
                        Hapus 
                    </a> 
 
                </td> 
 
            </tr> 
 
            <?php 
 
            } 
 
            ?> 
 
        </table> 
 
 
        <!-- ===================== 
             DATA TIDAK DITEMUKAN 
        ====================== --> 
 
        <div id="tidakDitemukan"> 
            Data pelanggan tidak ditemukan. 
        </div> 
 
    </div> 
 
</div> 
 
 
<!-- ========================= 
     JAVASCRIPT PENCARIAN 
========================= --> 
 
<script> 
 
function cariPelanggan() { 
 
    let input = document.getElementById("cariPelanggan"); 
 
    let filter = input.value.toLowerCase().trim(); 
 
    let tabel = document.getElementById("tabelPelanggan"); 
 
    let baris = tabel.getElementsByClassName("barisPelanggan"); 
 
    let jumlahDitemukan = 0; 
 
 
    for (let i = 0; i < baris.length; i++) { 
 
        let nama = baris[i] 
            .getElementsByTagName("td")[1] 
            .innerText 
            .toLowerCase(); 
 
        let alamat = baris[i] 
            .getElementsByTagName("td")[2] 
            .innerText 
            .toLowerCase(); 
 
        let telepon = baris[i] 
            .getElementsByTagName("td")[3] 
            .innerText 
            .toLowerCase(); 
 
 
        if ( 
            nama.includes(filter) || 
            alamat.includes(filter) || 
            telepon.includes(filter) 
        ) { 
 
            baris[i].style.display = ""; 
 
            jumlahDitemukan++; 
 
        } else { 
 
            baris[i].style.display = "none"; 
 
        } 
 
    } 
 
 
    let pesan = document.getElementById("tidakDitemukan"); 
 
 
    if (filter != "" && jumlahDitemukan == 0) { 
 
        pesan.style.display = "block"; 
 
    } else { 
 
        pesan.style.display = "none"; 
 
    } 
 
} 
 
</script> 
 
 
</body> 
 
</html>