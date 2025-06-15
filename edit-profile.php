<?php
session_start();
require_once 'koneksi.php';

// Check if user is logged in
if (!isset($_SESSION['no_telp'])) {
    header("Location: login.php");
    exit();
}

$no_telp = $_SESSION['no_telp'];
$error = "";
$success = "";

// Get current user data
$query = "SELECT * FROM pelanggan WHERE no_telp = '$no_telp'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

// Process form submission
if (isset($_POST['update'])) {
    $nama_pengguna = mysqli_real_escape_string($conn, $_POST['nama_pengguna']);
    $new_no_telp = mysqli_real_escape_string($conn, $_POST['no_telp']);
    $maps = mysqli_real_escape_string($conn, $_POST['maps']);

    // Validate input
    if (empty($nama_pengguna) || empty($new_no_telp)) {
        $error = "Nama pengguna dan nomor telepon harus diisi!";
    } elseif (!ctype_digit($new_no_telp)) {
        $error = "Nomor telepon hanya boleh berisi angka!";
    } else {
        // Check if phone number is being changed to one that already exists
        if ($new_no_telp != $no_telp) {
            $check_query = "SELECT * FROM pelanggan WHERE no_telp = '$new_no_telp'";
            $check_result = mysqli_query($conn, $check_query);

            if (mysqli_num_rows($check_result) > 0) {
                $error = "Nomor telepon ini sudah digunakan oleh pengguna lain!";
            }
        }

        if (empty($error)) {
            // Update user
            $update_query = "UPDATE pelanggan SET nama_pengguna = '$nama_pengguna', no_telp = '$new_no_telp', maps = '$maps' WHERE no_telp = '$no_telp'";

            if (mysqli_query($conn, $update_query)) {
                // Update session data
                $_SESSION['nama_pengguna'] = $nama_pengguna;
                $_SESSION['no_telp'] = $new_no_telp;
                $_SESSION['maps'] = $maps;

                $success = "Profil berhasil diperbarui!";

                // Update current phone number for the rest of the script
                $no_telp = $new_no_telp;
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
    <title>Edit Profil - PDP Printing Kudus</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" type="text/css" href="style.css" />
    <style>
        .edit-profile-container {
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
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .btn-secondary {
            background-color: #6c757d;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .error {
            color: red;
            margin-bottom: 15px;
        }

        .success {
            color: green;
            margin-bottom: 15px;
        }

        .button-group {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="edit-profile-container">
        <h2 style="text-align: center; margin-bottom: 30px;">Edit Profil</h2>

        <?php if (!empty($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="nama_pengguna">Nama Pengguna</label>
                <input type="text" id="nama_pengguna" name="nama_pengguna" placeholder="Masukkan nama lengkap" value="<?php echo $user['nama_pengguna']; ?>" required>
            </div>

            <div class="form-group">
                <label for="no_telp">Nomor WhatsApp</label>
                <input type="text" id="no_telp" name="no_telp" value="<?php echo $user['no_telp']; ?>"
                    pattern="[0-9]+" title="Masukkan hanya angka"
                    onkeypress="return event.charCode >= 48 && event.charCode <= 57" required>
            </div>

            <div class="form-group">
                <label for="maps">Alamat (Lokasi Google Maps)</label>
                <input type="text" id="maps" name="maps" value="<?php echo $user['maps']; ?>" placeholder="Paste link Google Maps anda di sini">
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

            <div class="button-group">
                <a href="index.php" class="btn btn-secondary">Kembali</a>
                <button type="submit" name="update" class="btn">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</body>

</html>