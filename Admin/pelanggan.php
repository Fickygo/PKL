<?php

require 'cek.php';

//ini ngitung jumlah pelanggan
$h1 = mysqli_query($c, "select * from pelanggan");
$h2 = mysqli_num_rows($h1); ////ini jumlah pelanggan bang

// Hapus pelanggan
if (isset($_POST['hapuspelanggan'])) {
    $idpl = $_POST['idpl'];

    $hapus = mysqli_query($c, "DELETE FROM pelanggan WHERE no_telp='$idpl'");

    if ($hapus) {
        // Using SweetAlert2 for success notification
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Pelanggan berhasil dihapus',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'pelanggan.php';
                    }
                });
            });
        </script>";
    } else {
        // Using SweetAlert2 for error notification
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Error!',
                    text: 'Gagal menghapus pelanggan: " . mysqli_error($c) . "',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Data Pelanggan - PDP Printing</title>

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
                    <h1 class="mt-4">Data Pelanggan</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Selamat datang di website PDP Printing</li>
                    </ol>
                    <div class="col-xl-4 col-md-6" style="margin-bottom: 2rem;">
                        <div class="card bg-primary text-white" style="padding: 12px 0;">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <h4 class="mb-0">Pelanggan : <?= $h2; ?></h4>
                                </div>
                                <i class="fas fa-users fa-2x text-white-50"></i>
                            </div>
                        </div>
                    </div>

                    <a href="export3.php" target="blank" class="btn btn-success mb-4">Print</a>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Data Pelanggan
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>Tanggal Register</th>
                                        <th>Nama Pelanggan</th>
                                        <th>No Telp</th>
                                        <th>Alamat</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $get = mysqli_query($c, "select * from pelanggan");

                                    while ($p = mysqli_fetch_array($get)) {
                                        $idpl = $p['no_telp'];
                                        $tanggal = $p['tanggal'];
                                        $namapelanggan = $p['nama_pengguna'];
                                        $notelp = $p['no_telp'];
                                        $alamat = $p['maps'];

                                        // Format nomor WhatsApp
                                        $whatsappNumber = preg_replace('/[^0-9]/', '', $notelp);
                                        if (substr($whatsappNumber, 0, 2) !== '62' && substr($whatsappNumber, 0, 1) === '0') {
                                            $whatsappNumber = '62' . substr($whatsappNumber, 1);
                                        }
                                    ?>
                                        <tr>
                                            <td><?= $tanggal; ?></td>
                                            <td><?= $namapelanggan; ?></td>
                                            <td>
                                                <?php
                                                // Format WhatsApp number
                                                $whatsappNumber = preg_replace('/[^0-9]/', '', $notelp);
                                                if (substr($whatsappNumber, 0, 2) !== '62' && substr($whatsappNumber, 0, 1) === '0') {
                                                    $whatsappNumber = '62' . substr($whatsappNumber, 1);
                                                }
                                                ?>
                                                <a href="https://api.whatsapp.com/send?phone=<?= $whatsappNumber; ?>" class="btn btn-success btn-sm" target="_blank">
                                                    <i class="fab fa-whatsapp"></i> <?= $notelp; ?>
                                                </a>
                                            </td>
                                            <td>
                                                <?php if (!empty($alamat)): ?>
                                                    <a href="<?= $alamat; ?>" class="btn btn-primary btn-sm" target="_blank">
                                                        <i class="fas fa-map-marker-alt"></i> Buka Maps
                                                    </a>
                                                <?php else: ?>
                                                    -
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete<?= $idpl; ?>">
                                                    Delete
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Modal Delete -->
                                        <div class="modal fade" id="delete<?= $idpl; ?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">

                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Hapus <?= $namapelanggan; ?></h4>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>

                                                    <form method="post">

                                                        <!-- Modal body -->
                                                        <div class="modal-body">
                                                            Apakah anda yakin ingin menghapus data ini?
                                                            <input type="hidden" name="idpl" value="<?= $idpl; ?>">
                                                        </div>

                                                        <!-- Modal footer -->
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-success" name="hapuspelanggan">Hapus</button>
                                                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                                        </div>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    }; //end of while
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; PKL 202251014</div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/chart-area-demo.js"></script>
    <script src="assets/demo/chart-bar-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
</body>

</html>