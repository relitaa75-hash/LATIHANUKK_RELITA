<?php 

include "../koneksi.php"; 
include "header.php"; 


/* =========================
   DATA LAPORAN PENJUALAN
========================= */

$data = mysqli_query( 
    $koneksi, 
    "SELECT 
        penjualan.PenjualanID, 
        penjualan.TanggalPenjualan, 
        penjualan.TotalHarga, 
        pelanggan.NamaPelanggan 
      
     FROM penjualan 
      
     LEFT JOIN pelanggan 
     ON penjualan.PelangganID = 
        pelanggan.PelangganID 
      
     ORDER BY penjualan.PenjualanID DESC"
); 


/* =========================
   TOTAL PENJUALAN
========================= */

$total_penjualan = 0; 

$data_total = mysqli_query( 
    $koneksi, 
    "SELECT SUM(TotalHarga) AS total 
     FROM penjualan"
); 

$hasil_total = mysqli_fetch_assoc($data_total); 

if ($hasil_total['total'] != null) { 
    $total_penjualan = $hasil_total['total']; 
}


/* =========================
   REKAP PRODUK KELUAR
   PER BULAN
========================= */

$rekap_bulanan = mysqli_query(
    $koneksi,
    "SELECT
        MONTH(penjualan.TanggalPenjualan) AS bulan,
        YEAR(penjualan.TanggalPenjualan) AS tahun,
        SUM(detailpenjualan.JumlahProduk) AS produk_keluar,
        SUM(detailpenjualan.Subtotal) AS total_penjualan
     
     FROM detailpenjualan
     
     INNER JOIN penjualan
     ON detailpenjualan.PenjualanID = penjualan.PenjualanID
     
     GROUP BY
        YEAR(penjualan.TanggalPenjualan),
        MONTH(penjualan.TanggalPenjualan)
     
     ORDER BY
        YEAR(penjualan.TanggalPenjualan) DESC,
        MONTH(penjualan.TanggalPenjualan) DESC"
);


/* =========================
   NAMA BULAN
========================= */

$nama_bulan = array(
    1 => "Januari",
    2 => "Februari",
    3 => "Maret",
    4 => "April",
    5 => "Mei",
    6 => "Juni",
    7 => "Juli",
    8 => "Agustus",
    9 => "September",
    10 => "Oktober",
    11 => "November",
    12 => "Desember"
);

?> 


<h3>Laporan Penjualan</h3>


<!-- =========================
     CARD LAPORAN
========================= -->

<div class="card shadow border-0"> 

    <div class="card-body"> 


        <!-- =========================
             PENCARIAN
        ========================= -->

        <input 
            type="text" 
            id="cariLaporan" 
            class="form-control mb-3" 
            placeholder="Cari ID penjualan, tanggal, pelanggan, atau total..." 
            onkeyup="cariLaporan()" 
        > 


        <!-- =========================
             TABEL LAPORAN
        ========================= -->

        <table 
            class="table table-bordered" 
            id="tabelLaporan" 
        > 

            <tr> 

                <th>No</th> 

                <th>ID Penjualan</th> 

                <th>Tanggal & Waktu</th> 

                <th>Pelanggan</th> 

                <th>Total</th> 

                <th>Aksi</th> 

            </tr> 


            <?php 

            $no = 1; 

            while ($d = mysqli_fetch_assoc($data)) { 

            ?> 

            <tr class="barisLaporan"> 

                <td> 
                    <?= $no++; ?> 
                </td> 


                <td> 
                    <?= $d['PenjualanID']; ?> 
                </td> 


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
                        $d['NamaPelanggan'] ?? '-' 
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
                        href="detail_penjualan.php?id=<?= $d['PenjualanID']; ?>" 
                        class="btn btn-info btn-sm" 
                    > 

                        Detail 

                    </a> 

                </td> 

            </tr> 

            <?php 

            } 

            ?> 

        </table> 


        <!-- =========================
             PESAN TIDAK DITEMUKAN
        ========================= -->

        <div 
            id="laporanTidakDitemukan" 
            style=" 
                display:none; 
                text-align:center; 
                padding:15px; 
                color:#777; 
            " 
        > 

            Data laporan tidak ditemukan. 

        </div> 



        <!-- =========================
             TOTAL PENJUALAN
        ========================= -->

        <div 
            class="alert alert-primary mt-3" 
        > 

            <strong> 
                Total Seluruh Penjualan: 
            </strong> 

            Rp <?= number_format( 
                $total_penjualan, 
                0, 
                ',', 
                '.' 
            ); ?> 

        </div> 



        <!-- =========================
             REKAP PRODUK KELUAR
             SETIAP BULAN
        ========================= -->

        <h4 class="mt-4 mb-3">
            Rekap Produk Keluar Setiap Bulan
        </h4>


        <table class="table table-bordered">

            <tr>

                <th>No</th>

                <th>Bulan</th>

                <th>Tahun</th>

                <th>Jumlah Produk Keluar</th>

                <th>Total Penjualan</th>

            </tr>


            <?php

            $no_bulan = 1;

            $total_semua_produk = 0;

            $total_semua_bulan = 0;

            while ($r = mysqli_fetch_assoc($rekap_bulanan)) {

                $total_semua_produk += $r['produk_keluar'];

                $total_semua_bulan += $r['total_penjualan'];

            ?>

            <tr>

                <td>
                    <?= $no_bulan++; ?>
                </td>

                <td>
                    <?= $nama_bulan[$r['bulan']]; ?>
                </td>

                <td>
                    <?= $r['tahun']; ?>
                </td>

                <td>
                    <?= $r['produk_keluar']; ?> produk
                </td>

                <td>
                    Rp <?= number_format(
                        $r['total_penjualan'],
                        0,
                        ',',
                        '.'
                    ); ?>
                </td>

            </tr>

            <?php

            }

            ?>


            <!-- TOTAL -->

            <tr>

                <th colspan="3">
                    TOTAL
                </th>

                <th>
                    <?= $total_semua_produk; ?> produk
                </th>

                <th>
                    Rp <?= number_format(
                        $total_semua_bulan,
                        0,
                        ',',
                        '.'
                    ); ?>
                </th>

            </tr>

        </table>



        <!-- =========================
             CETAK
        ========================= -->

        <button 
            onclick="window.print()" 
            class="btn btn-primary" 
        > 

            Cetak Laporan 

        </button> 


    </div> 

</div> 



<!-- =========================
     JAVASCRIPT PENCARIAN
========================= -->

<script> 

function cariLaporan() { 

    let input = 
        document.getElementById("cariLaporan"); 

    let filter = 
        input.value.toLowerCase().trim(); 

    let tabel = 
        document.getElementById("tabelLaporan"); 

    let baris = 
        tabel.getElementsByClassName( 
            "barisLaporan" 
        ); 

    let jumlahDitemukan = 0; 


    for ( 
        let i = 0; 
        i < baris.length; 
        i++ 
    ) { 

        let teks = 
            baris[i].innerText.toLowerCase(); 


        if (teks.includes(filter)) { 

            baris[i].style.display = ""; 

            jumlahDitemukan++; 

        } else { 

            baris[i].style.display = "none"; 

        } 

    } 


    let pesan = 
        document.getElementById( 
            "laporanTidakDitemukan" 
        ); 


    if ( 
        filter !== "" && 
        jumlahDitemukan === 0 
    ) { 

        pesan.style.display = "block"; 

    } else { 

        pesan.style.display = "none"; 

    } 

} 

</script> 


<?php include "footer.php"; ?>