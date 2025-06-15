<?php
session_start();
require_once 'koneksi.php';

// Check if already logged in
if (isset($_SESSION['no_telp'])) {
    header("Location: index.php");
    exit();
}

$error = "";
$success = "";

// Process registration
if (isset($_POST['register'])) {
    $nama_pengguna = mysqli_real_escape_string($conn, $_POST['nama_pengguna']);
    $no_telp = mysqli_real_escape_string($conn, $_POST['no_telp']);
    $maps = mysqli_real_escape_string($conn, $_POST['maps']);

    // Validate input
    if (empty($nama_pengguna) || empty($no_telp)) {
        $error = "Nama pengguna dan nomor telepon harus diisi!";
    } elseif (!ctype_digit($no_telp)) {
        $error = "Nomor telepon hanya boleh berisi angka!";
    } else {
        // Check if phone number already exists
        $check_phone_query = "SELECT * FROM pelanggan WHERE no_telp = '$no_telp'";
        $check_phone_result = mysqli_query($conn, $check_phone_query);

        // Check if username already exists
        $check_name_query = "SELECT * FROM pelanggan WHERE nama_pengguna = '$nama_pengguna'";
        $check_name_result = mysqli_query($conn, $check_name_query);

        if (mysqli_num_rows($check_phone_result) > 0) {
            $error = "Nomor telepon ini sudah terdaftar!";
        } elseif (mysqli_num_rows($check_name_result) > 0) {
            $error = "Nama pengguna ini sudah terdaftar!";
        } else {
            // Insert new user
            $insert_query = "INSERT INTO pelanggan (no_telp, nama_pengguna, maps) VALUES ('$no_telp', '$nama_pengguna', '$maps')";

            if (mysqli_query($conn, $insert_query)) {
                // Store user data in session
                $_SESSION['no_telp'] = $no_telp;
                $_SESSION['nama_pengguna'] = $nama_pengguna;
                $_SESSION['maps'] = $maps;

                $success = "Pendaftaran berhasil! Anda akan dialihkan ke halaman utama.";
                header("refresh:2;url=index.php");
            } else {
                $error = "Error: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - PDP Printing Kudus</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" type="text/css" href="style.css" />
    <style>
        .register-container {
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

        .success {
            color: green;
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
    <div class="register-container">
        <h2 style="text-align: center; margin-bottom: 30px;">Daftar Akun Baru</h2>

        <?php if (!empty($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="nama_pengguna">Nama Pengguna</label>
                <input type="text" id="nama_pengguna" name="nama_pengguna" placeholder="Masukkan nama lengkap" required>
            </div>

            <div class="form-group">
                <label for="no_telp">Nomor WhatsApp</label>
                <input type="text" id="no_telp" name="no_telp" placeholder="Contoh: 08xxxxxxxxxx"
                    pattern="[0-9]+" title="Masukkan hanya angka"
                    onkeypress="return event.charCode >= 48 && event.charCode <= 57" required>
            </div>

            <div class="form-group">
                <label for="maps">Alamat (Lokasi Google Maps)</label>
                <input type="text" id="maps" name="maps" placeholder="Paste link Google Maps anda di sini">
                <small style="color: #666; display: block; margin-top: 5px;">
                    Cara mendapatkan link Google Maps:
                    <ol style="margin: 5px 0 0 15px; padding-left: 15px;">
                        <li>Buka aplikasi Google Maps di ponsel/komputer</li>
                        <li>Pastikan titik lokasi Anda ditempati</li>
                        <li>Tekan tombol "Share"</li>
                        <li>Pilih "Copy link"</li>
                        <li>Tempelkan link tersebut di kolom di atas</li>
                    </ol>
                </small>
            </div>

            <button type="submit" name="register" class="btn">Daftar</button>
        </form>

        <div class="toggle-form">
            <p>Sudah memiliki akun? <a href="login.php">Login Sekarang</a></p>
            <p><a href="index.php">Kembali ke Beranda</a></p>
        </div>
    </div>
</body>

</html>