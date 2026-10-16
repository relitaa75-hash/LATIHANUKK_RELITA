<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #e9f0f0ff;
        }

        .login-container {
            width: 410px;
            margin: 100px auto;
            background: white;
            padding: 35px 30px 30px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 16px;
            border: 1px solid #999;
            border-radius: 4px;
            font-size: 14px;
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background: #333;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-login:hover {
            background: #24ad46ff;
        }

        .btn-kembali {
            display: block;
            width: 100%;
            padding: 12px;
            margin-top: 10px;
            background: white;
            color: #333;
            border: 1px solid #333;
            text-align: center;
            text-decoration: none;
            font-weight: bold;
        }

        .btn-kembali:hover {
            background: #eee;
        }
    </style>
</head>

<body>

<div class="login-container">

    <h2>LOGIN ADMIN</h2>

    <form action="proses_login.php" method="POST">

        <label>Username</label>
        <input type="text" name="username" placeholder="Username" required>

        <label>Password</label>
        <input type="password" name="password" placeholder="Password" required>

        <button type="submit" class="btn-login">
            LOGIN
        </button>

    </form>

    <!-- KEMBALI KE HALAMAN UTAMA -->
    <a href="index.php" class="btn-kembali">
        Kembali ke Dashboard Utama
    </a>

</div>

</body>
</html>