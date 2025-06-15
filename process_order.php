<?php
session_start();
require_once 'koneksi.php';

if (!isset($_SESSION['no_telp'])) {
    echo json_encode(['success' => false, 'message' => 'Anda harus login terlebih dahulu']);
    exit;
}

$response = ['success' => false];

try {
    // Get form data - make sure to properly sanitize all inputs
    $productId = $_POST['idproduk'] ?? '';
    $quantity = intval($_POST['quantity'] ?? 1);
    $delivery = $_POST['delivery'] ?? 'Ambil di Tempat';
    $notes = trim($_POST['pesan'] ?? ''); // Properly get the message and trim whitespace
    $ukuran = $_POST['ukuran'] ?? '';

    // Validate required fields
    if (empty($productId) || empty($ukuran)) {
        throw new Exception('Data produk tidak lengkap');
    }

    // Get user info
    $phone = $_SESSION['no_telp'];
    $userQuery = $conn->prepare("SELECT nama_pengguna FROM pelanggan WHERE no_telp = ?");
    $userQuery->bind_param("s", $phone);
    $userQuery->execute();
    $userResult = $userQuery->get_result();
    $user = $userResult->fetch_assoc();

    if (!$user) {
        throw new Exception('Data pengguna tidak ditemukan');
    }

    $namaPengguna = preg_replace('/[^a-zA-Z0-9_-]/', '_', $user['nama_pengguna']);
    $uploadDir = __DIR__ . '/../Index/uploads/' . $namaPengguna . '/';

    // Create directory if not exists
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Handle file upload
    $filePath = '';
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $existingFiles = glob($uploadDir . '*');
        $nextSequence = count($existingFiles) + 1;

        $originalName = pathinfo($_FILES['file']['name'], PATHINFO_FILENAME);
        $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        $sanitizedName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $originalName);
        $newFileName = $nextSequence . '_' . $sanitizedName . '.' . $extension;
        $targetPath = $uploadDir . $newFileName;

        if (move_uploaded_file($_FILES['file']['tmp_name'], $targetPath)) {
            $filePath = 'Index/uploads/' . $namaPengguna . '/' . $newFileName;
        }
    }

    // Insert into pesanan table
    $stmt = $conn->prepare("INSERT INTO pesanan (nama_pengguna, idproduk, jumlah_pesanan, metode_pengiriman, file, pesan) 
                           VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssisss", $user['nama_pengguna'], $productId, $quantity, $delivery, $filePath, $notes);

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Pesanan berhasil disimpan';
    } else {
        throw new Exception('Gagal menyimpan pesanan: ' . $stmt->error);
    }
} catch (Exception $e) {
    $response['message'] = 'Error: ' . $e->getMessage();

    // Rollback file upload if exception occurs
    if (isset($filePath) && $filePath && file_exists($filePath)) {
        unlink($filePath);
    }
}

header('Content-Type: application/json');
echo json_encode($response);
