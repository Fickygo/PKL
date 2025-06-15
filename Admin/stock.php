<?php
// Modified PHP code for improved notifications

require 'cek.php';

//ini ngitung jumlah barang
$h1 = mysqli_query($c, "select * from product");
$h2 = mysqli_num_rows($h1); ////ini jumlah barang bang

// Handle edit barang submission
if (isset($_POST['editbarang'])) {
    $id = $_POST['idproduk'];
    $nama = $_POST['nama_produk'];
    $jenis = $_POST['jenis'];
    $ukuran = $_POST['ukuran'];
    $deskripsi = $_POST['deskripsi'];
    $harga = $_POST['harga'];

    // Update query
    $update_query = mysqli_query($c, "UPDATE product SET 
                                    nama_produk='$nama', 
                                    jenis='$jenis', 
                                    ukuran='$ukuran',
                                    deskripsi='$deskripsi',
                                    harga='$harga'
                                    WHERE idproduk='$id'");

    if ($update_query) {
        // Using SweetAlert2 for success notification
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Produk berhasil diupdate',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'stock.php';
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
                    text: 'Gagal update produk: " . mysqli_error($c) . "',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        </script>";
    }
}

// Handle hapus barang submission 
if (isset($_POST['hapusbarang'])) {
    $id = $_POST['idproduk'];

    // Delete query
    $delete_query = mysqli_query($c, "DELETE FROM product WHERE idproduk='$id'");

    if ($delete_query) {
        // Using SweetAlert2 for success notification
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Produk berhasil dihapus',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'stock.php';
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
                    text: 'Gagal menghapus produk: " . mysqli_error($c) . "',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        </script>";
    }
}

// Handle tambah barang submission
if (isset($_POST['tambahbarang'])) {
    $nama = $_POST['nama_produk'];
    $jenis = $_POST['jenis'];
    $ukuran = $_POST['ukuran'];
    $deskripsi = $_POST['deskripsi'];
    $harga = $_POST['harga'];

    // Generate custom ID based on nama_produk
    // First, sanitize the product name to create a valid ID prefix
    $prefix = preg_replace('/[^a-zA-Z0-9]/', '', $nama); // Remove special characters
    $prefix = strtolower(substr($prefix, 0, 10)); // Take first 10 chars and lowercase it

    // Find the highest sequence number for this prefix
    $sequence_query = mysqli_query($c, "SELECT idproduk FROM product WHERE idproduk LIKE '$prefix%' ORDER BY idproduk DESC LIMIT 1");

    if (mysqli_num_rows($sequence_query) > 0) {
        $last_id = mysqli_fetch_assoc($sequence_query)['idproduk'];
        // Extract the numeric part
        preg_match('/[0-9]+$/', $last_id, $matches);
        $sequence_number = isset($matches[0]) ? intval($matches[0]) + 1 : 1;
    } else {
        $sequence_number = 1;
    }

    // Create the new ID
    $new_id = $prefix . $sequence_number;

    // Insert query with custom ID
    $insert_query = mysqli_query($c, "INSERT INTO product (idproduk, nama_produk, jenis, ukuran, deskripsi, harga) 
                                    VALUES ('$new_id', '$nama', '$jenis', '$ukuran', '$deskripsi', '$harga')");

    if ($insert_query) {
        // Using SweetAlert2 for success notification
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Produk berhasil ditambahkan',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'stock.php';
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
                    text: 'Gagal menambahkan produk: " . mysqli_error($c) . "',
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
    <title>Stok Barang - PDP Printing</title>
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
                    <h1 class="mt-4">Stok Barang</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Selamat datang di website PDP Printing</li>
                    </ol>
                    <div class="col-xl-4 col-md-6" style="margin-bottom: 2rem;">
                        <div class="card bg-primary text-white" style="padding: 12px 0;">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <h4 class="mb-0">Total Barang : <?= $h2; ?></h4>
                                </div>
                                <i class="fas fa-box-open fa-2x text-white-50"></i>
                            </div>
                        </div>
                    </div>


                    <!-- Button to Open the Modal -->
                    <button type="button" class="btn btn-secondary mb-4" data-bs-toggle="modal" data-bs-target="#myModal">
                        Tambah Barang
                    </button>
                    <a href="export.php" target="blank" class="btn btn-success mb-4">Print</a>


                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Data Barang
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Produk</th>
                                        <th>Jenis</th>
                                        <th>Ukuran</th>
                                        <th>Deskripsi</th>
                                        <th>Harga</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>

                                <?php
                                $get = mysqli_query($c, "select * from product");
                                $i = 1;

                                while ($p = mysqli_fetch_array($get)) {
                                    $id = $p['idproduk'];
                                    $nama = $p['nama_produk'];
                                    $jenis = $p['jenis'];
                                    $ukuran = $p['ukuran'];
                                    $deskripsi = $p['deskripsi'];
                                    $harga = $p['harga'];

                                ?>


                                    <tr>
                                        <td><?= $i++; ?></td>
                                        <td><?= $nama; ?></td>
                                        <td><?= $jenis; ?></td>
                                        <td><?= $ukuran; ?></td>
                                        <td><?= $deskripsi; ?></td>
                                        <td>Rp. <?= number_format($harga); ?></td>
                                        <td>
                                            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#edit<?= $id; ?>">
                                                Edit
                                            </button>
                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete<?= $id; ?>">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>


                                    <!-- Modal Edit -->
                                    <div class="modal fade" id="edit<?= $id; ?>">
                                        <div class="modal-dialog">
                                            <div class="modal-content">

                                                <!-- Modal Header -->
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Ubah <strong><?= $nama; ?></strong> <?= $jenis; ?></h4>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>


                                                <form method="post">

                                                    <!-- Modal body -->
                                                    <div class="modal-body">
                                                        <input type="text" name="nama_produk" class="form-control" placeholder="Nama Produk" value="<?= $nama; ?>">
                                                        <input type="text" name="jenis" class="form-control mt-3" placeholder="Jenis Produk" value="<?= $jenis; ?>">
                                                        <input type="text" name="ukuran" class="form-control mt-3" placeholder="Ukuran" value="<?= $ukuran; ?>">
                                                        <input type="text" name="deskripsi" class="form-control mt-3" placeholder="Deskripsi" value="<?= $deskripsi; ?>">
                                                        <input type="num" name="harga" class="form-control mt-3" placeholder="Harga Produk" value="<?= $harga; ?>">
                                                        <input type="hidden" name="idproduk" value="<?= $id; ?>">
                                                    </div>

                                                    <!-- Modal footer -->
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-success" name="editbarang">Submit</button>
                                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </form>

                                            </div>
                                        </div>
                                    </div>



                                    <!-- Modal Delete -->
                                    <div class="modal fade" id="delete<?= $id; ?>">
                                        <div class="modal-dialog">
                                            <div class="modal-content">

                                                <!-- Modal Header -->
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Hapus <strong><?= $nama; ?></strong> <?= $jenis; ?></h4>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>


                                                <form method="post">

                                                    <!-- Modal body -->
                                                    <div class="modal-body">
                                                        Apakah anda yakin ingin menghapus barang ini?
                                                        <input type="hidden" name="idproduk" value="<?= $id; ?>">
                                                    </div>

                                                    <!-- Modal footer -->
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-success" name="hapusbarang">Hapus</button>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/chart-area-demo.js"></script>
    <script src="assets/demo/chart-bar-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
</body>


<!-- The Modal -->
<div class="modal fade" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Tambah Barang</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>


            <form method="post">

                <!-- Modal body -->
                <div class="modal-body">
                    <input type="text" name="nama_produk" class="form-control" placeholder="Nama Produk">
                    <input type="text" name="jenis" class="form-control mt-3" placeholder="Jenis">
                    <input type="text" name="ukuran" class="form-control mt-3" placeholder="Ukuran">
                    <input type="text" name="deskripsi" class="form-control mt-3" placeholder="Deskripsi">
                    <input type="num" name="harga" class="form-control mt-3" placeholder="Harga Produk">
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" name="tambahbarang">Submit</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
    // JavaScript function to validate numeric input
    function validateNumericInput(event) {
        // Allow only numbers, backspace, delete, tab, escape, and enter
        if (
            !/[0-9]/.test(event.key) &&
            event.key !== 'Backspace' &&
            event.key !== 'Delete' &&
            event.key !== 'Tab' &&
            event.key !== 'Escape' &&
            event.key !== 'Enter' &&
            event.key !== 'ArrowLeft' &&
            event.key !== 'ArrowRight'
        ) {
            event.preventDefault();
        }
    }

    // Add event listeners to all price and stock input fields when document is loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Get all input fields with name 'harga' or 'stok'
        const priceInputs = document.querySelectorAll('input[name="harga"]');

        // Add event listeners to price inputs
        priceInputs.forEach(function(input) {
            input.setAttribute('type', 'number');
            input.setAttribute('min', '0');
            input.addEventListener('keydown', validateNumericInput);
        });

        // Add event listeners to stock inputs
        stockInputs.forEach(function(input) {
            input.setAttribute('type', 'number');
            input.setAttribute('min', '0');
            input.addEventListener('keydown', validateNumericInput);
        });
    });
</script>

</html>