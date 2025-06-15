<?php
session_start();
require_once 'koneksi.php';

// Check if already logged in
if (isset($_SESSION['no_telp'])) {
    header("Location: index.php");
    exit();
}

$error = "";

// Process login
if (isset($_POST['login'])) {
    $nama_pengguna = mysqli_real_escape_string($conn, $_POST['nama_pengguna']);
    $no_telp = mysqli_real_escape_string($conn, $_POST['no_telp']);

    // Validate input
    if (empty($nama_pengguna) || empty($no_telp)) {
        $error = "Semua kolom harus diisi!";
    } elseif (!ctype_digit($no_telp)) {
        $error = "Nomor telepon hanya boleh berisi angka!";
    } else {
        // Check if user exists
        $query = "SELECT * FROM pelanggan WHERE nama_pengguna = '$nama_pengguna' AND no_telp = '$no_telp'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            $_SESSION['nama_pengguna'] = $user['nama_pengguna'];
            $_SESSION['no_telp'] = $user['no_telp'];
            $_SESSION['maps'] = $user['maps'];

            header("Location: index.php");
            exit();
        } else {
            $error = "Nama pengguna atau nomor telepon tidak valid!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PDP Printing Kudus</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" type="text/css" href="style.css" />
    <style>
        .login-container {
            max-width: 500px;
            margin: 100px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .btn {
            display: inline-block;
            background-color: var(--satu-color);
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            width: 100%;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .error {
            color: red;
            margin-bottom: 15px;
        }

        .toggle-form {
            text-align: center;
            margin-top: 20px;
        }

        .toggle-form a {
            color: var(--satu-color);
            text-decoration: none;
        }

        .toggle-form a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h2 style="text-align: center; margin-bottom: 30px;">Login</h2>

        <?php if (!empty($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="nama_pengguna">Nama Pengguna</label>
                <input type="text" id="nama_pengguna" name="nama_pengguna" required>
            </div>

            <div class="form-group">
                <label for="no_telp">Nomor WhatsApp</label>
                <input type="text" id="no_telp" name="no_telp"
                    pattern="[0-9]+" title="Masukkan hanya angka"
                    onkeypress="return event.charCode >= 48 && event.charCode <= 57" required>
            </div>

            <button type="submit" name="login" class="btn">Login</button>
        </form>

        <div class="toggle-form">
            <p>Belum memiliki akun? <a href="register.php">Daftar Sekarang</a></p>
            <p><a href="index.php">Kembali ke Beranda</a></p>
        </div>
    </div>
</body>

</html>