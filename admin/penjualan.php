<?php 
 
include "../koneksi.php"; 
include "header.php"; 
 
 
/* ========================= 
   HAPUS PENJUALAN 
========================= */ 
 
if (isset($_GET['hapus'])) { 
 
    $id = intval($_GET['hapus']); 
 
 
    /* ========================= 
       AMBIL DETAIL PENJUALAN
    ========================= */ 
 
    $query_detail_hapus = mysqli_query( 
        $koneksi, 
        "SELECT * FROM detailpenjualan 
         WHERE PenjualanID = $id" 
    ); 
 
 
    /* ========================= 
       KEMBALIKAN STOK PRODUK
    ========================= */ 
 
    while ($detail_hapus = mysqli_fetch_assoc($query_detail_hapus)) { 
 
        $produk_hapus = intval($detail_hapus['ProdukID']); 
        $jumlah_hapus = intval($detail_hapus['JumlahProduk']); 
 
        mysqli_query( 
            $koneksi, 
            "UPDATE produk 
             SET Stok = Stok + $jumlah_hapus 
             WHERE ProdukID = $produk_hapus" 
        ); 
    } 
 
 
    /* ========================= 
       HAPUS DETAIL
    ========================= */ 
 
    mysqli_query( 
        $koneksi, 
        "DELETE FROM detailpenjualan 
         WHERE PenjualanID = $id" 
    ); 
 
 
    /* ========================= 
       HAPUS PENJUALAN
    ========================= */ 
 
    $hapus_penjualan = mysqli_query( 
        $koneksi, 
        "DELETE FROM penjualan 
         WHERE PenjualanID = $id" 
    ); 
 
 
    if ($hapus_penjualan) { 
 
        echo " 
        <script> 
            alert('Data penjualan berhasil dihapus! Stok produk telah dikembalikan.'); 
            window.location='penjualan.php'; 
        </script> 
        "; 
 
    } else { 
 
        echo " 
        <div class='alert alert-danger'> 
            Data penjualan gagal dihapus: 
            " . mysqli_error($koneksi) . " 
        </div> 
        "; 
    } 
 
    exit; 
} 
 
 
/* ========================= 
   UPDATE PENJUALAN 
========================= */ 
 
if (isset($_POST['update_penjualan'])) { 
 
    $id = intval($_POST['id']); 
    $pelanggan_baru = intval($_POST['PelangganID']); 
    $produk_baru = intval($_POST['ProdukID']); 
    $jumlah_baru = intval($_POST['JumlahProduk']); 
 
 
    /* ========================= 
       CEK JUMLAH 
    ========================= */ 
 
    if ($jumlah_baru <= 0) { 
 
        echo " 
        <script> 
            alert('Jumlah produk harus lebih dari 0!'); 
            window.location='penjualan.php?edit=$id'; 
        </script> 
        "; 
 
        exit; 
    } 
 
 
    /* ========================= 
       AMBIL DETAIL LAMA 
    ========================= */ 
 
    $query_detail_lama = mysqli_query( 
        $koneksi, 
        "SELECT * FROM detailpenjualan 
         WHERE PenjualanID = $id 
         LIMIT 1" 
    ); 
 
    $detail_lama = mysqli_fetch_assoc($query_detail_lama); 
 
 
    if (!$detail_lama) { 
 
        echo " 
        <script> 
            alert('Detail penjualan tidak ditemukan!'); 
            window.location='penjualan.php'; 
        </script> 
        "; 
 
        exit; 
    } 
 
 
    $produk_lama = intval($detail_lama['ProdukID']); 
    $jumlah_lama = intval($detail_lama['JumlahProduk']); 
 
 
    /* ========================= 
       AMBIL PRODUK BARU 
    ========================= */ 
 
    $query_produk_baru = mysqli_query( 
        $koneksi, 
        "SELECT * FROM produk 
         WHERE ProdukID = $produk_baru" 
    ); 
 
    $data_produk_baru = mysqli_fetch_assoc($query_produk_baru); 
 
 
    if (!$data_produk_baru) { 
 
        echo " 
        <script> 
            alert('Produk tidak ditemukan!'); 
            window.location='penjualan.php?edit=$id'; 
        </script> 
        "; 
 
        exit; 
    } 
 
 
    /* ========================= 
       CEK STOK
       
       Jika produk sama:
       stok sekarang + jumlah lama
       lalu dikurangi jumlah baru
    ========================= */ 
 
    if ($produk_lama == $produk_baru) { 
 
        $stok_tersedia = intval($data_produk_baru['Stok']) + $jumlah_lama; 
 
        if ($jumlah_baru > $stok_tersedia) { 
 
            echo " 
            <script> 
                alert('Stok tidak cukup! Stok tersedia untuk perubahan: $stok_tersedia'); 
                window.location='penjualan.php?edit=$id'; 
            </script> 
            "; 
 
            exit; 
        } 
 
    } else { 
 
        /* ========================= 
           PRODUK BERBEDA
        ========================= */ 
 
        if ($jumlah_baru > intval($data_produk_baru['Stok'])) { 
 
            echo " 
            <script> 
                alert('Stok produk baru tidak cukup!'); 
                window.location='penjualan.php?edit=$id'; 
            </script> 
            "; 
 
            exit; 
        } 
    } 
 
 
    /* ========================= 
       HITUNG TOTAL BARU 
    ========================= */ 
 
    $harga_baru = intval($data_produk_baru['Harga']); 
    $subtotal_baru = $harga_baru * $jumlah_baru; 
 
 
    /* ========================= 
       KEMBALIKAN STOK PRODUK LAMA
    ========================= */ 
 
    mysqli_query( 
        $koneksi, 
        "UPDATE produk 
         SET Stok = Stok + $jumlah_lama 
         WHERE ProdukID = $produk_lama" 
    ); 
 
 
    /* ========================= 
       KURANGI STOK PRODUK BARU
    ========================= */ 
 
    mysqli_query( 
        $koneksi, 
        "UPDATE produk 
         SET Stok = Stok - $jumlah_baru 
         WHERE ProdukID = $produk_baru" 
    ); 
 
 
    /* ========================= 
       UPDATE PENJUALAN
    ========================= */ 
 
    $update_penjualan = mysqli_query( 
        $koneksi, 
        "UPDATE penjualan SET 
            TotalHarga = '$subtotal_baru', 
            PelangganID = '$pelanggan_baru' 
         WHERE PenjualanID = $id" 
    ); 
 
 
    /* ========================= 
       UPDATE DETAIL
    ========================= */ 
 
    $update_detail = mysqli_query( 
        $koneksi, 
        "UPDATE detailpenjualan SET 
            ProdukID = '$produk_baru', 
            JumlahProduk = '$jumlah_baru', 
            Subtotal = '$subtotal_baru' 
         WHERE PenjualanID = $id" 
    ); 
 
 
    if ($update_penjualan && $update_detail) { 
 
        echo " 
        <script> 
            alert('Data penjualan berhasil diperbarui!'); 
            window.location='penjualan.php'; 
        </script> 
        "; 
 
    } else { 
 
        echo " 
        <script> 
            alert('Data penjualan gagal diperbarui!'); 
            window.location='penjualan.php'; 
        </script> 
        "; 
    } 
 
    exit; 
} 
 
 
/* ========================= 
   DATA YANG AKAN DIEDIT 
========================= */ 
 
$data_edit = null; 
$detail_edit = null; 
 
if (isset($_GET['edit'])) { 
 
    $id_edit = intval($_GET['edit']); 
 
 
    /* DATA PENJUALAN */ 
 
    $query_edit = mysqli_query( 
        $koneksi, 
        "SELECT * FROM penjualan 
         WHERE PenjualanID = $id_edit" 
    ); 
 
    $data_edit = mysqli_fetch_assoc($query_edit); 
 
 
    /* DATA DETAIL */ 
 
    if ($data_edit) { 
 
        $query_detail_edit = mysqli_query( 
            $koneksi, 
            "SELECT * FROM detailpenjualan 
             WHERE PenjualanID = $id_edit 
             LIMIT 1" 
        ); 
 
        $detail_edit = mysqli_fetch_assoc($query_detail_edit); 
    } 
} 
 
 
/* ========================= 
   SIMPAN PENJUALAN 
========================= */ 
 
if (isset($_POST['simpan'])) { 
 
    $pelanggan = intval($_POST['PelangganID']); 
    $produk = intval($_POST['ProdukID']); 
    $jumlah = intval($_POST['JumlahProduk']); 
 
 
    /* ========================= 
       AMBIL DATA PRODUK 
    ========================= */ 
 
    $query_produk = mysqli_query( 
        $koneksi, 
        "SELECT * FROM produk 
         WHERE ProdukID = $produk" 
    ); 
 
    $p = mysqli_fetch_assoc($query_produk); 
 
 
    /* ========================= 
       CEK PRODUK 
    ========================= */ 
 
    if (!$p) { 
 
        echo " 
        <div class='alert alert-danger'> 
            Produk tidak ditemukan 
        </div> 
        "; 
 
    } 
 
    /* ========================= 
       CEK STOK 
    ========================= */ 
 
    elseif ($jumlah > $p['Stok']) { 
 
        echo " 
        <div class='alert alert-danger'> 
            Stok tidak cukup 
        </div> 
        "; 
 
    } 
 
    else { 
 
        /* ========================= 
           HITUNG SUBTOTAL 
        ========================= */ 
 
        $subtotal = $p['Harga'] * $jumlah; 
 
 
        /* ========================= 
           SIMPAN PENJUALAN 
           NOW() = TANGGAL + WAKTU 
        ========================= */ 
 
        $simpan_penjualan = mysqli_query( 
            $koneksi, 
            "INSERT INTO penjualan 
            ( 
                TanggalPenjualan, 
                TotalHarga, 
                PelangganID 
            ) 
            VALUES 
            ( 
                NOW(), 
                '$subtotal', 
                '$pelanggan' 
            )" 
        ); 
 
 
        if ($simpan_penjualan) { 
 
            /* ========================= 
               AMBIL ID PENJUALAN 
            ========================= */ 
 
            $id = mysqli_insert_id($koneksi); 
 
 
            /* ========================= 
               SIMPAN DETAIL 
            ========================= */ 
 
            mysqli_query( 
                $koneksi, 
                "INSERT INTO detailpenjualan 
                ( 
                    PenjualanID, 
                    ProdukID, 
                    JumlahProduk, 
                    Subtotal 
                ) 
                VALUES 
                ( 
                    '$id', 
                    '$produk', 
                    '$jumlah', 
                    '$subtotal' 
                )" 
            ); 
 
 
            /* ========================= 
               KURANGI STOK 
            ========================= */ 
 
            mysqli_query( 
                $koneksi, 
                "UPDATE produk 
                 SET Stok = Stok - '$jumlah' 
                 WHERE ProdukID = '$produk'" 
            ); 
 
 
            echo " 
            <div class='alert alert-success'> 
                Penjualan berhasil disimpan 
            </div> 
            "; 
 
        } else { 
 
            echo " 
            <div class='alert alert-danger'> 
                Penjualan gagal disimpan: 
                " . mysqli_error($koneksi) . " 
            </div> 
            "; 
        } 
    } 
} 
 
 
/* ========================= 
   DATA PELANGGAN 
========================= */ 
 
$pelanggan = mysqli_query( 
    $koneksi, 
    "SELECT * FROM pelanggan" 
); 
 
 
/* ========================= 
   DATA PRODUK 
========================= */ 
 
$produk = mysqli_query( 
    $koneksi, 
    "SELECT * FROM produk 
     WHERE Stok > 0" 
); 
 
 
/* ========================= 
   DATA PENJUALAN 
========================= */ 
 
$data = mysqli_query( 
    $koneksi, 
    "SELECT 
        penjualan.*, 
        pelanggan.NamaPelanggan 
     FROM penjualan 
     JOIN pelanggan 
     ON penjualan.PelangganID = 
        pelanggan.PelangganID 
     ORDER BY penjualan.PenjualanID DESC" 
); 
 
?> 
 
 
<h3>Data Penjualan</h3> 
 
 
<!-- ========================= 
     FORM EDIT PENJUALAN
========================= --> 
 
<?php if ($data_edit && $detail_edit) { ?> 
 
<div class="card shadow border-0 mb-4"> 
 
    <div class="card-body"> 
 
        <h5>Edit Penjualan</h5> 
 
 
        <form method="POST"> 
 
            <input 
                type="hidden" 
                name="id" 
                value="<?= $data_edit['PenjualanID']; ?>" 
            > 
 
 
            <!-- PELANGGAN --> 
 
            <label>Pelanggan</label> 
 
            <select 
                name="PelangganID" 
                class="form-select mb-2" 
                required> 
 
                <option value=""> 
                    -- Pilih Pelanggan -- 
                </option> 
 
                <?php 
 
                $pelanggan_edit = mysqli_query( 
                    $koneksi, 
                    "SELECT * FROM pelanggan 
                     ORDER BY NamaPelanggan ASC" 
                ); 
 
                while ($pe = mysqli_fetch_assoc($pelanggan_edit)) { 
 
                ?> 
 
                    <option 
                        value="<?= $pe['PelangganID']; ?>" 
                        <?= ($pe['PelangganID'] == $data_edit['PelangganID']) ? 'selected' : ''; ?> 
                    > 
 
                        <?= htmlspecialchars( 
                            $pe['NamaPelanggan'] 
                        ); ?> 
 
                    </option> 
 
                <?php 
 
                } 
 
                ?> 
 
            </select> 
 
 
            <!-- PRODUK --> 
 
            <label>Produk</label> 
 
            <select 
                name="ProdukID" 
                class="form-select mb-2" 
                required> 
 
                <option value=""> 
                    -- Pilih Produk -- 
                </option> 
 
                <?php 
 
                $produk_edit = mysqli_query( 
                    $koneksi, 
                    "SELECT * FROM produk 
                     ORDER BY NamaProduk ASC" 
                ); 
 
                while ($produk_data_edit = mysqli_fetch_assoc($produk_edit)) { 
 
                    /* 
                       Produk lama tetap ditampilkan 
                       walaupun stoknya 0 
                    */ 
 
                    if ( 
                        $produk_data_edit['Stok'] > 0 || 
                        $produk_data_edit['ProdukID'] == $detail_edit['ProdukID'] 
                    ) { 
 
                ?> 
 
                    <option 
                        value="<?= $produk_data_edit['ProdukID']; ?>" 
                        <?= ($produk_data_edit['ProdukID'] == $detail_edit['ProdukID']) ? 'selected' : ''; ?> 
                    > 
 
                        <?= htmlspecialchars( 
                            $produk_data_edit['NamaProduk'] 
                        ); ?> 
 
                        - 
 
                        Rp <?= number_format( 
                            $produk_data_edit['Harga'], 
                            0, 
                            ',', 
                            '.' 
                        ); ?> 
 
                        (Stok <?= $produk_data_edit['Stok']; ?>) 
 
                    </option> 
 
                <?php 
 
                    } 
                } 
 
                ?> 
 
            </select> 
 
 
            <!-- JUMLAH --> 
 
            <label>Jumlah</label> 
 
            <input 
                type="number" 
                name="JumlahProduk" 
                class="form-control mb-3" 
                min="1" 
                value="<?= $detail_edit['JumlahProduk']; ?>" 
                required 
            > 
 
 
            <!-- SIMPAN --> 
 
            <button 
                type="submit" 
                name="update_penjualan" 
                class="btn btn-primary"> 
 
                Simpan Perubahan 
 
            </button> 
 
 
            <a 
                href="penjualan.php" 
                class="btn btn-secondary"> 
 
                Batal 
 
            </a> 
 
        </form> 
 
    </div> 
 
</div> 
 
<?php } ?> 
 
 
<!-- ========================= 
     FORM TAMBAH PENJUALAN 
========================= --> 
 
<div class="card shadow border-0 mb-4"> 
 
    <div class="card-body"> 
 
        <h5>Tambah Penjualan</h5> 
 
 
        <form method="POST"> 
 
 
            <!-- PELANGGAN --> 
 
            <label>Pelanggan</label> 
 
            <select 
                name="PelangganID" 
                class="form-select mb-2" 
                required> 
 
                <option value=""> 
                    -- Pilih Pelanggan -- 
                </option> 
 
 
                <?php 
 
                while ($p = mysqli_fetch_assoc($pelanggan)) { 
 
                ?> 
 
                    <option 
                        value="<?= $p['PelangganID']; ?>"> 
 
                        <?= htmlspecialchars( 
                            $p['NamaPelanggan'] 
                        ); ?> 
 
                    </option> 
 
                <?php 
 
                } 
 
                ?> 
 
            </select> 
 
 
            <!-- PRODUK --> 
 
            <label>Produk</label> 
 
            <select 
                name="ProdukID" 
                class="form-select mb-2" 
                required> 
 
                <option value=""> 
                    -- Pilih Produk -- 
                </option> 
 
 
                <?php 
 
                while ($p = mysqli_fetch_assoc($produk)) { 
 
                ?> 
 
                    <option 
                        value="<?= $p['ProdukID']; ?>"> 
 
                        <?= htmlspecialchars( 
                            $p['NamaProduk'] 
                        ); ?> 
 
                        - 
 
                        Rp <?= number_format( 
                            $p['Harga'], 
                            0, 
                            ',', 
                            '.' 
                        ); ?> 
 
                        (Stok <?= $p['Stok']; ?>) 
 
                    </option> 
 
                <?php 
 
                } 
 
                ?> 
 
            </select> 
 
 
            <!-- JUMLAH --> 
 
            <label>Jumlah</label> 
 
            <input 
                type="number" 
                name="JumlahProduk" 
                class="form-control mb-3" 
                min="1" 
                required 
            > 
 
 
            <!-- SIMPAN --> 
 
            <button 
                type="submit" 
                name="simpan" 
                class="btn btn-primary"> 
 
                Simpan Penjualan 
 
            </button> 
 
 
        </form> 
 
    </div> 
 
</div> 
 
 
<!-- ========================= 
     DATA PENJUALAN 
========================= --> 
 
<div class="card shadow border-0"> 
 
    <div class="card-body"> 
 
 
        <!-- ========================= 
             FITUR CARI 
        ========================= --> 
 
        <input 
            type="text" 
            id="cariPenjualan" 
            class="form-control mb-3" 
            placeholder="Cari ID penjualan, tanggal, pelanggan, atau total..." 
            onkeyup="cariPenjualan()" 
        > 
 
 
        <table 
            class="table table-bordered" 
            id="tabelPenjualan"> 
 
            <tr> 
 
                <th>No</th> 
 
                <th>ID</th> 
 
                <th>Tanggal & Waktu</th> 
 
                <th>Pelanggan</th> 
 
                <th>Total</th> 
 
                <th>Aksi</th> 
 
            </tr> 
 
 
            <?php 
 
            $no = 1; 
 
            while ($d = mysqli_fetch_assoc($data)) { 
 
            ?> 
 
            <tr class="barisPenjualan"> 
 
                <td> 
                    <?= $no++; ?> 
                </td> 
 
 
                <td> 
                    <?= $d['PenjualanID']; ?> 
                </td> 
 
 
                <!-- TANGGAL DAN WAKTU --> 
 
                <td> 
 
                    <?= date( 
                        'd-m-Y H:i:s', 
                        strtotime( 
                            $d['TanggalPenjualan'] 
                        ) 
                    ); ?> 
 
                </td> 
 
 
                <td> 
 
                    <?= htmlspecialchars( 
                        $d['NamaPelanggan'] 
                    ); ?> 
 
                </td> 
 
 
                <td> 
 
                    Rp <?= number_format( 
                        $d['TotalHarga'], 
                        0, 
                        ',', 
                        '.' 
                    ); ?> 
 
                </td> 
 
 
                <td> 
 
                    <a 
                        href="penjualan.php?edit=<?= $d['PenjualanID']; ?>" 
                        class="btn btn-warning btn-sm"> 
 
                        Edit 
 
                    </a> 
 
 
                    <a 
                        href="penjualan.php?hapus=<?= $d['PenjualanID']; ?>" 
                        class="btn btn-danger btn-sm" 
                        onclick="return confirm('Yakin ingin menghapus penjualan ini? Stok produk akan dikembalikan.');"> 
 
                        Hapus 
 
                    </a> 
 
 
                    <a 
                        href="detail_penjualan.php?id=<?= $d['PenjualanID']; ?>" 
                        class="btn btn-info btn-sm"> 
 
                        Detail 
 
                    </a> 
 
                </td> 
 
            </tr> 
 
            <?php 
 
            } 
 
            ?> 
 
        </table> 
 
 
        <!-- ========================= 
             PESAN JIKA TIDAK DITEMUKAN 
        ========================= --> 
 
        <div 
            id="penjualanTidakDitemukan" 
            style=" 
                display:none; 
                text-align:center; 
                padding:15px; 
                color:#777; 
            "> 
 
            Data penjualan tidak ditemukan. 
 
        </div> 
 
 
    </div> 
 
</div> 
 
 
<!-- ========================= 
     JAVASCRIPT PENCARIAN 
========================= --> 
 
<script> 
 
function cariPenjualan() { 
 
    let input = document.getElementById("cariPenjualan"); 
 
    let filter = input.value.toLowerCase().trim(); 
 
    let tabel = document.getElementById("tabelPenjualan"); 
 
    let baris = tabel.getElementsByClassName("barisPenjualan"); 
 
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
        "penjualanTidakDitemukan" 
    ); 
 
 
    if (filter !== "" && jumlahDitemukan === 0) { 
 
        pesan.style.display = "block"; 
 
    } else { 
 
        pesan.style.display = "none"; 
 
    } 
 
} 
 
</script> 
 
 
<?php include "footer.php"; ?>