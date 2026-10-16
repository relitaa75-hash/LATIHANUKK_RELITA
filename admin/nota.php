<?php

session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}


if (!isset($_SESSION['nota'])) {

    header("Location: penjualan.php");

    exit;

}


$nota = $_SESSION['nota'];

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Bukti Pembayaran</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>

        body {

            background: #f1f3f6;

            font-family: Arial, sans-serif;

        }


        .nota {

            width: 450px;

            max-width: 95%;

            margin: 50px auto;

            background: white;

            padding: 30px;

            border-radius: 18px;

            box-shadow:
                0 5px 25px rgba(0,0,0,0.12);

        }


        .berhasil {

            text-align: center;

        }


        .icon {

            width: 65px;

            height: 65px;

            background: #198754;

            color: white;

            border-radius: 50%;

            margin: auto;

            display: flex;

            justify-content: center;

            align-items: center;

            font-size: 35px;

            font-weight: bold;

        }


        .berhasil h3 {

            margin-top: 15px;

            font-weight: bold;

        }


        .nominal {

            text-align: center;

            font-size: 32px;

            font-weight: bold;

            margin: 25px 0;

        }


        .detail {

            border-top: 1px solid #ddd;

            padding-top: 20px;

        }


        .baris {

            display: flex;

            justify-content: space-between;

            margin-bottom: 15px;

        }


        .label {

            color: #777;

        }


        .nilai {

            font-weight: bold;

        }


        .status {

            background: #d1e7dd;

            color: #0f5132;

            text-align: center;

            padding: 12px;

            border-radius: 10px;

            font-weight: bold;

            margin-top: 20px;

        }


        .tombol {

            margin-top: 25px;

        }


        @media print {

            body {

                background: white;

            }


            .nota {

                box-shadow: none;

                margin: 0 auto;

            }


            .tombol {

                display: none;

            }

        }

    </style>

</head>


<body>


<div class="nota">


    <div class="berhasil">

        <div class="icon">
            ✓
        </div>


        <h3>
            Pembayaran Berhasil
        </h3>


        <p class="text-muted">
            DAILY MART
        </p>

    </div>


    <div class="nominal">

        Rp <?= number_format(
            $nota['total'],
            0,
            ',',
            '.'
        ) ?>

    </div>


    <div class="detail">


        <div class="baris">

            <span class="label">
                Status
            </span>

            <span class="nilai text-success">
                Berhasil
            </span>

        </div>


        <div class="baris">

            <span class="label">
                No. Transaksi
            </span>

            <span class="nilai">

                TRX<?= str_pad(
                    $nota['id'],
                    5,
                    '0',
                    STR_PAD_LEFT
                ) ?>

            </span>

        </div>


        <div class="baris">

            <span class="label">
                Tanggal
            </span>

            <span class="nilai">

                <?= date(
                    'd-m-Y',
                    strtotime($nota['tanggal'])
                ) ?>

            </span>

        </div>


        <div class="baris">

            <span class="label">
                Pelanggan
            </span>

            <span class="nilai">

                <?= htmlspecialchars(
                    $nota['pelanggan']
                ) ?>

            </span>

        </div>


        <div class="baris">

            <span class="label">
                Total Belanja
            </span>

            <span class="nilai">

                Rp <?= number_format(
                    $nota['total'],
                    0,
                    ',',
                    '.'
                ) ?>

            </span>

        </div>


        <div class="baris">

            <span class="label">
                Dibayar
            </span>

            <span class="nilai">

                Rp <?= number_format(
                    $nota['bayar'],
                    0,
                    ',',
                    '.'
                ) ?>

            </span>

        </div>


        <div class="baris">

            <span class="label">
                Kembalian
            </span>

            <span class="nilai">

                Rp <?= number_format(
                    $nota['kembalian'],
                    0,
                    ',',
                    '.'
                ) ?>

            </span>

        </div>


    </div>


    <div class="status">

        ✓ PEMBAYARAN LUNAS

    </div>


    <div class="tombol">

        <button
            onclick="window.print()"
            class="btn btn-primary w-100 mb-2"
        >

            🖨️ Cetak Nota

        </button>


        <a
            href="penjualan.php"
            class="btn btn-outline-secondary w-100"
        >

            ← Transaksi Baru

        </a>

    </div>


</div>


</body>

</html>