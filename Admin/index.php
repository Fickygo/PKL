<?php
require 'cek.php';

// Handle delete and transfer operation
if (isset($_POST['hapuspesanan'])) {
    $id = $_POST['idproduk'];
    $tgl_pesan = $_POST['tgl_pesan']; // Tambahkan ini untuk mendapatkan tanggal pesan

    // First, get all data from the order to be deleted
    $getData = mysqli_query($c, "SELECT p.*, pr.nama_produk, pr.jenis, pr.ukuran, pr.harga 
                                FROM pesanan p
                                JOIN product pr ON p.idproduk = pr.idproduk
                                WHERE p.idproduk='$id' AND p.tgl_pesan='$tgl_pesan'");
    $order = mysqli_fetch_assoc($getData);

    if ($order) {
        // Insert the data into the riwayat (history) table
        $transfer = mysqli_query($c, "INSERT INTO history 
            (tgl_pesan, idproduk, nama_pengguna, jumlah_pesanan) 
            VALUES 
            ('{$order['tgl_pesan']}', '{$order['idproduk']}', 
            '{$order['nama_pengguna']}', '{$order['jumlah_pesanan']}')");

        if ($transfer) {
            $delete = mysqli_query($c, "DELETE FROM pesanan WHERE idproduk='$id' AND tgl_pesan='{$order['tgl_pesan']}'");

            if ($delete) {
                echo "<script>
                    alert('Pesanan berhasil dihapus dan dipindahkan ke riwayat');
                    window.location.href='index.php';
                </script>";
            } else {
                echo "<script>
                    alert('Pesanan berhasil dipindahkan ke riwayat tetapi gagal dihapus: " . mysqli_error($c) . "');
                    window.location.href='index.php';
                </script>";
            }
        } else {
            echo "<script>
                alert('Gagal memindahkan pesanan ke riwayat: " . mysqli_error($c) . "');
                window.location.href='index.php';
            </script>";
        }
    } else {
        echo "<script>
            alert('Pesanan tidak ditemukan');
            window.location.href='index.php';
        </script>";
    }
}

// Count total orders
$h1 = mysqli_query($c, "SELECT p.*, pr.nama_produk, pr.jenis, pr.ukuran, pr.harga, pl.maps 
                       FROM pesanan p
                       JOIN product pr ON p.idproduk = pr.idproduk
                       LEFT JOIN pelanggan pl ON p.nama_pengguna = pl.nama_pengguna");
$h2 = mysqli_num_rows($h1);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Pesanan - PDP Printing</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="index.php">PDP Printing</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>

    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">MENU</div>
                        <a class="nav-link" href="index.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-shopping-cart"></i></div>
                            Pesanan
                        </a>
                        <a class="nav-link" href="stock.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-line"></i></div>
                            Stok Barang
                        </a>
                        <a class="nav-link" href="masuk.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-dolly-flatbed"></i></div>
                            Riwayat Pesanan
                        </a>
                        <a class="nav-link" href="pelanggan.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                            Kelola Pelanggan
                        </a>
                        <a class="nav-link" href="logout.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-sign-out-alt"></i></div>
                            Logout
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    Admin
                </div>
            </nav>
        </div>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Data Pesanan</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Selamat datang di website PDP Printing</li>
                    </ol>
                    <div class="col-xl-4 col-md-6" style="margin-bottom: 2rem;">
                        <div class="card bg-primary text-white" style="padding: 12px 0;">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <h4 class="mb-0">Total Pesanan : <?= $h2; ?></h4>
                                </div>
                                <i class="fas fa-shopping-cart fa-2x text-white-50"></i>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Data Pesanan
                        </div>

                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Nama Pelanggan</th>
                                        <th>Metode Pengiriman</th>
                                        <th>Produk</th>
                                        <th>Jumlah Pesanan</th>
                                        <th>Total Harga</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $counter = 1;
                                    while ($row = mysqli_fetch_assoc($h1)) {
                                        // Get customer phone number from pelanggan table
                                        $customerQuery = mysqli_query($c, "SELECT no_telp FROM pelanggan WHERE nama_pengguna='{$row['nama_pengguna']}'");
                                        $customer = mysqli_fetch_assoc($customerQuery);
                                        $whatsappNumber = $customer ? preg_replace('/[^0-9]/', '', $customer['no_telp']) : '';

                                        if ($whatsappNumber && substr($whatsappNumber, 0, 2) !== '62' && substr($whatsappNumber, 0, 1) === '0') {
                                            $whatsappNumber = '62' . substr($whatsappNumber, 1);
                                        }

                                        $totalHarga = $row['harga'] * $row['jumlah_pesanan'];
                                    ?>
                                        <tr>
                                            <td><?= $counter++; ?></td>
                                            <td><?= $row['tgl_pesan'] ?></td>
                                            <td><?= $row['nama_pengguna'] ?></td>
                                            <td><?= $row['metode_pengiriman'] ?></td>
                                            <td>
                                                <?= $row['nama_produk'] ?> <br>
                                                <?= $row['jenis'] ?>
                                            </td>
                                            <td><?= $row['jumlah_pesanan'] ?></td>
                                            <td>Rp <?= number_format($totalHarga, 0, ',', '.') ?></td>
                                            <td>
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#detail<?= $counter; ?>">
                                                    Detail
                                                </button>
                                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete<?= $counter; ?>">
                                                    Delete
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Detail Modal -->
                                        <div class="modal fade" id="detail<?= $counter; ?>">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Detail Pesanan</h4>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>

                                                    <!-- Modal body -->
                                                    <div class="modal-body">
                                                        <div class="container-fluid">
                                                            <div class="row mb-2">
                                                                <div class="col-md-4 fw-bold">Tanggal</div>
                                                                <div class="col-md-8"><?= $row['tgl_pesan']; ?></div>
                                                            </div>
                                                            <div class="row mb-2">
                                                                <div class="col-md-4 fw-bold">Nama Pelanggan</div>
                                                                <div class="col-md-8"><?= $row['nama_pengguna']; ?></div>
                                                            </div>
                                                            <?php if ($whatsappNumber): ?>
                                                                <div class="row mb-2">
                                                                    <div class="col-md-4 fw-bold">No Telp</div>
                                                                    <div class="col-md-8">
                                                                        <?= $customer['no_telp']; ?>
                                                                        <a href="https://api.whatsapp.com/send?phone=<?= $whatsappNumber; ?>" class="btn btn-success btn-sm ms-2" target="_blank">
                                                                            <i class="fab fa-whatsapp"></i> Chat
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            <?php endif; ?>
                                                            <div class="row mb-2">
                                                                <div class="col-md-4 fw-bold">Metode Pengiriman</div>
                                                                <div class="col-md-8"><?= $row['metode_pengiriman']; ?></div>
                                                            </div>
                                                            <?php if (!empty($row['maps'])): ?>
                                                                <div class="row mb-2">
                                                                    <div class="col-md-4 fw-bold">Alamat</div>
                                                                    <div class="col-md-8">
                                                                        <a href="<?= $row['maps']; ?>" class="btn btn-primary btn-sm" target="_blank">
                                                                            <i class="fas fa-map-marker-alt"></i> Buka di Google Maps
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            <?php endif; ?>
                                                            <div class="row mb-2">
                                                                <div class="col-md-4 fw-bold">ID Produk</div>
                                                                <div class="col-md-8"><?= $row['idproduk']; ?></div>
                                                            </div>
                                                            <div class="row mb-2">
                                                                <div class="col-md-4 fw-bold">Produk</div>
                                                                <div class="col-md-8">
                                                                    <?= $row['nama_produk']; ?> - <?= $row['jenis']; ?> - <?= $row['ukuran']; ?>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-2">
                                                                <div class="col-md-4 fw-bold">File</div>
                                                                <div class="col-md-8">
                                                                    <?php
                                                                    if (!empty($row['file'])) {
                                                                        $filePath = $row['file'];
                                                                        $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);

                                                                        // Check if file exists
                                                                        if (file_exists($filePath)) {
                                                                            echo '<div class="d-flex align-items-center">';
                                                                            echo '<span class="me-2">' . basename($filePath) . '</span>';

                                                                            // Button to view/download file
                                                                            echo '<a href="' . $filePath . '" class="btn btn-sm btn-primary me-2" target="_blank">';
                                                                            echo '<i class="fas fa-eye"></i> Lihat';
                                                                            echo '</a>';

                                                                            // Download button
                                                                            echo '<a href="' . $filePath . '" class="btn btn-sm btn-success" download>';
                                                                            echo '<i class="fas fa-download"></i> Unduh';
                                                                            echo '</a>';

                                                                            echo '</div>';
                                                                        } else {
                                                                            echo '<span class="text-danger">File tidak ditemukan</span>';
                                                                        }
                                                                    } else {
                                                                        echo '-';
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-2">
                                                                <div class="col-md-4 fw-bold">Jumlah</div>
                                                                <div class="col-md-8"><?= $row['jumlah_pesanan']; ?></div>
                                                            </div>
                                                            <div class="row mb-2">
                                                                <div class="col-md-4 fw-bold">Harga Satuan</div>
                                                                <div class="col-md-8">Rp <?= number_format($row['harga'], 0, ',', '.'); ?></div>
                                                            </div>
                                                            <div class="row mb-2">
                                                                <div class="col-md-4 fw-bold">Total Harga</div>
                                                                <div class="col-md-8">Rp <?= number_format($totalHarga, 0, ',', '.'); ?></div>
                                                            </div>
                                                            <div class="row mb-2">
                                                                <div class="col-md-4 fw-bold">Pesan</div>
                                                                <div class="col-md-8"><?= $row['pesan'] ?: '-'; ?></div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Modal footer -->
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Delete Modal -->
                                        <div class="modal fade" id="delete<?= $counter; ?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Hapus Data Pesanan</h4>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>

                                                    <form method="post">
                                                        <!-- Modal body -->
                                                        <div class="modal-body">
                                                            <p>Apakah anda yakin ingin menghapus pesanan <strong><?= $row['nama_produk']; ?> <?= $row['jenis']; ?></strong> dari <strong><?= $row['nama_pengguna']; ?></strong>?</p>
                                                            <input type="hidden" name="idproduk" value="<?= $row['idproduk']; ?>">
                                                            <input type="hidden" name="tgl_pesan" value="<?= $row['tgl_pesan']; ?>">
                                                        </div>

                                                        <!-- Modal footer -->
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-danger" name="hapuspesanan">Hapus</button>
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
            <!-- Footer code remains the same... -->
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/chart-area-demo.js"></script>
    <script src="assets/demo/chart-bar-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
</body>

</html>