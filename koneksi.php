<?php
// Database connection
$conn = mysqli_connect('localhost', 'root', '', 'pdp_printing');

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
