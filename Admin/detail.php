<?php
include 'index.php';
if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $query = "SELECT * FROM pengguna WHERE id = $id";
    $result = mysqli_query($conn, $query);
    if ($row = mysqli_fetch_assoc($result)) {
        echo "<p><strong>Tanggal:</strong> " . $row['tanggal'] . "</p>";
        echo "<p><strong>Nama Pengguna:</strong> " . $row['nama_pengguna'] . "</p>";
        echo "<p><strong>WhatsApp:</strong> " . $row['whatsapp'] . "</p>";
        echo "<p><strong>Metode Pengiriman:</strong> " . $row['metode_pengiriman'] . "</p>";
        echo "<p><strong>Nama Produk:</strong> " . $row['nama_produk'] . "</p>";
        echo "<p><strong>Ukuran:</strong> " . $row['ukuran'] . "</p>";
        echo "<p><strong>Jumlah:</strong> " . $row['jumlah'] . "</p>";
        echo "<p><strong>Total Harga:</strong> Rp " . number_format($row['harga'], 0, ',', '.') . "</p>";
        echo "<p><strong>Pesan:</strong> " . nl2br($row['pesan']) . "</p>";
    } else {
        echo "<p>Data tidak ditemukan.</p>";
    }
}
