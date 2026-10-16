<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login Kasir - Daily Mart</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
        }

        .login-box {
            width: 400px;
            margin: 100px auto;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.15);
        }

        .judul {
            text-align: center;
            margin-bottom: 25px;
        }

        .judul h2 {
            font-weight: bold;
        }
    </style>
</head>

<body>

<div class="login-box">

    <div class="judul">
        <h2>DAILY MART</h2>
        <p>Login Kasir</p>
    </div>

    <?php
    if (isset($_GET['error'])) {
        echo '<div class="alert alert-danger">
                Username atau password salah!
              </div>';
    }
    ?>

    <form action="proses_login_kasir.php" method="POST">

        <div class="mb-3">
            <label>Username</label>

            <input
                type="text"
                name="username"
                class="form-control"
                required
            >
        </div>

        <div class="mb-3">
            <label>Password</label>

            <input
                type="password"
                name="password"
                class="form-control"
                required
            >
        </div>

        <button type="submit" class="btn btn-primary w-100">
            Login Kasir
        </button>

    </form>

</div>

</body>
</html>