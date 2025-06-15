<?php

require 'cek.php';

// Get history data joined with product information
$historyQuery = mysqli_query($c, "SELECT h.*, p.nama_produk, p.jenis, p.ukuran, p.harga 
                                FROM history h
                                JOIN product p ON h.idproduk = p.idproduk
                                ORDER BY h.tgl_pesan DESC");

// Calculate totals
$totalQty = 0;
$totalIncome = 0;

while ($row = mysqli_fetch_assoc($historyQuery)) {
    $totalQty += $row['jumlah_pesanan'];
    $totalIncome += ($row['harga'] * $row['jumlah_pesanan']);
}

// Reset pointer to start for the table display
mysqli_data_seek($historyQuery, 0);

// Handle delete action for single item
if (isset($_GET['delete'])) {
    $tgl_pesan = $_GET['delete'];
    $deleteQuery = mysqli_query($c, "DELETE FROM history WHERE tgl_pesan = '$tgl_pesan'");
    if ($deleteQuery) {
        echo "<script>alert('Data berhasil dihapus');</script>";
        echo "<script>window.location.href='masuk.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data');</script>";
    }
}

// Handle bulk delete action
if (isset($_POST['bulk_delete'])) {
    if (isset($_POST['selected_items']) && !empty($_POST['selected_items'])) {
        $success = true;
        foreach ($_POST['selected_items'] as $tgl_pesan) {
            $deleteQuery = mysqli_query($c, "DELETE FROM history WHERE tgl_pesan = '$tgl_pesan'");
            if (!$deleteQuery) {
                $success = false;
                break;
            }
        }

        if ($success) {
            echo "<script>alert('Data terpilih berhasil dihapus');</script>";
            echo "<script>window.location.href='masuk.php';</script>";
        } else {
            echo "<script>alert('Gagal menghapus beberapa data');</script>";
        }
    } else {
        echo "<script>alert('Tidak ada data yang dipilih');</script>";
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
    <title>Riwayat Pesanan - PDP Printing</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
    <style>
        .action-buttons {
            display: flex;
            gap: 5px;
        }

        .delete-btn {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .delete-btn:hover {
            background-color: #c82333;
        }

        .bulk-actions {
            margin-bottom: 15px;
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .select-all-container {
            display: flex;
            align-items: center;
            margin-right: 15px;
        }

        .select-all-container label {
            margin-left: 5px;
            font-weight: bold;
        }

        /* Make sure checkboxes are visible and properly sized */
        input[type="checkbox"] {
            width: 16px;
            height: 16px;
            cursor: pointer;
        }

        /* Fix for datatables potentially hiding checkboxes */
        .datatable-checkbox-cell {
            text-align: center;
            vertical-align: middle;
        }
    </style>
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
                    <h1 class="mt-4">Riwayat Pesanan</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Selamat datang di website PDP Printing</li>
                    </ol>
                    <div class="row justify-content-start g-4">
                        <div class="col-xl-4 col-md-6">
                            <div class="card bg-primary text-white h-100">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div>
                                        <h4 class="mb-0">Jumlah Semua Pesanan : <?= $totalQty; ?></h4>
                                    </div>
                                    <i class="fas fa-dolly-flatbed fa-2x text-white-50"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-6">
                            <div class="card bg-success text-white h-100" style="padding: 12px 0;">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div>
                                        <h4 class="mb-0">Total Harga : Rp <?= number_format($totalIncome, 0, ',', '.'); ?></h4>
                                    </div>
                                    <i class="fas fa-money-bill-wave fa-2x text-white-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 d-flex justify-content-between">
                        <a href="export2.php" target="blank" class="btn btn-success mb-4">Print</a>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Data Pesanan
                        </div>

                        <div class="card-body">
                            <form method="post" id="bulk-action-form">
                                <div class="bulk-actions">
                                    <div class="select-all-container">
                                        <input type="checkbox" id="select-all" />
                                        <label for="select-all">Pilih Semua</label>
                                    </div>
                                    <button type="submit" name="bulk_delete" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus data terpilih?')">
                                        <i class="fas fa-trash-alt"></i> Hapus Terpilih
                                    </button>
                                </div>

                                <table id="datatablesSimple" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th width="50px" class="datatable-checkbox-cell">Pilih</th>
                                            <th>Tanggal</th>
                                            <th>Nama Pelanggan</th>
                                            <th>Produk</th>
                                            <th>Jenis</th>
                                            <th>Ukuran</th>
                                            <th>Jumlah Pesanan</th>
                                            <th>Total Harga</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while ($row = mysqli_fetch_assoc($historyQuery)) {
                                            $totalHarga = $row['harga'] * $row['jumlah_pesanan'];
                                        ?>
                                            <tr>
                                                <td class="datatable-checkbox-cell">
                                                    <input type="checkbox" name="selected_items[]" value="<?= $row['tgl_pesan'] ?>" class="row-checkbox" />
                                                </td>
                                                <td><?= $row['tgl_pesan'] ?></td>
                                                <td><?= $row['nama_pengguna'] ?></td>
                                                <td><?= $row['nama_produk'] ?></td>
                                                <td><?= $row['jenis'] ?></td>
                                                <td><?= $row['ukuran'] ?: '-' ?></td>
                                                <td><?= $row['jumlah_pesanan'] ?></td>
                                                <td>Rp <?= number_format($totalHarga, 0, ',', '.') ?></td>
                                                <td>
                                                    <a href="masuk.php?delete=<?= $row['tgl_pesan'] ?>" class="delete-btn" onclick="return confirm('Yakin ingin menghapus data ini?')">
                                                        <i class="fas fa-trash"></i> Hapus
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </form>
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
    <script src="js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/chart-area-demo.js"></script>
    <script src="assets/demo/chart-bar-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>

    <script>
        // Fixed script for select all functionality that works with simple-datatables
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize the DataTable
            const datatablesSimple = document.getElementById('datatablesSimple');
            if (datatablesSimple) {
                new simpleDatatables.DataTable(datatablesSimple);

                // Add event listener after DataTable is initialized
                setTimeout(function() {
                    setupCheckboxes();
                }, 500);
            }

            function setupCheckboxes() {
                const selectAllCheckbox = document.getElementById('select-all');
                if (!selectAllCheckbox) return;

                // Get all row checkboxes (including those in pagination)
                function getRowCheckboxes() {
                    return document.querySelectorAll('.row-checkbox');
                }

                // Select all functionality
                selectAllCheckbox.addEventListener('change', function() {
                    const isChecked = this.checked;
                    getRowCheckboxes().forEach(checkbox => {
                        checkbox.checked = isChecked;
                    });
                });

                // Update select all checkbox when individual checkboxes change
                document.addEventListener('click', function(e) {
                    if (e.target && e.target.classList.contains('row-checkbox')) {
                        const allCheckboxes = getRowCheckboxes();
                        const checkedCheckboxes = document.querySelectorAll('.row-checkbox:checked');

                        selectAllCheckbox.checked = allCheckboxes.length === checkedCheckboxes.length;
                        selectAllCheckbox.indeterminate = checkedCheckboxes.length > 0 &&
                            checkedCheckboxes.length < allCheckboxes.length;
                    }
                });

                // Make sure checkboxes are visible after pagination
                const paginationButtons = document.querySelectorAll('.dataTable-pagination a');
                paginationButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        // Short delay to allow pagination to complete
                        setTimeout(function() {
                            // Update the select all checkbox state
                            const allCheckboxes = getRowCheckboxes();
                            const checkedCheckboxes = document.querySelectorAll('.row-checkbox:checked');

                            selectAllCheckbox.checked = allCheckboxes.length === checkedCheckboxes.length &&
                                checkedCheckboxes.length > 0;
                            selectAllCheckbox.indeterminate = checkedCheckboxes.length > 0 &&
                                checkedCheckboxes.length < allCheckboxes.length;
                        }, 100);
                    });
                });
            }
        });
    </script>
</body>

</html>