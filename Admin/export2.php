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
    $totalIncome += ($row['harga'] * $row['jumlah_pesanan']);
    $totalQty += $row['jumlah_pesanan'];
}

mysqli_data_seek($historyQuery, 0);
?>
<html>

<head>
    <title>Riwayat Pesanan - PDP Printing</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <style>
        @media print {
            body {
                padding: 20px;
                font-size: 12px;
            }

            .dataTables_info,
            .dataTables_length,
            .dataTables_filter {
                display: none;
            }

            .dt-buttons {
                display: none;
            }

            table {
                width: 100% !important;
            }

            .card {
                border: 1px solid #000;
                margin-bottom: 10px;
            }

            .no-print {
                display: none;
            }
        }

        tfoot {
            font-weight: bold;
            background-color: #f8f9fa;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="no-print">
            <h2>Riwayat Pesanan</h2>
            <h4>(PDP Printing)</h4>

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h5 class="card-title">Jumlah Pesanan : <?= $totalQty ?></h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5 class="card-title">Total Pendapatan : Rp <?= number_format($totalIncome, 0, ',', '.') ?></h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="data-tables datatable-dark">
            <table id="mauexport2" class="display table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Nama Pelanggan</th>
                        <th>Produk</th>
                        <th>Jumlah</th>
                        <th>Harga Satuan</th>
                        <th>Total Harga</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($historyQuery)) {
                        $totalHarga = $row['harga'] * $row['jumlah_pesanan'];
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($row['tgl_pesan'])) ?></td>
                            <td><?= $row['nama_pengguna'] ?></td>
                            <td><?= $row['nama_produk'] ?> <br> <?= $row['jenis'] ?> <br> <?= $row['ukuran'] ?: '-' ?></td>
                            <td><?= $row['jumlah_pesanan'] ?></td>
                            <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                            <td>Rp <?= number_format($totalHarga, 0, ',', '.') ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td colspan="1" class="text-right"><strong>Total Keseluruhan:</strong></td>
                        <td colspan="4"></td>
                        <td><strong>Rp <?= number_format($totalIncome, 0, ',', '.') ?></strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.colVis.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#mauexport2').DataTable({
                dom: 'Blfrtip',
                buttons: [{
                        extend: 'print',
                        title: 'RIWAYAT PESANAN - PDP PRINTING',
                        className: 'btn btn-primary',
                        footer: true,
                        exportOptions: {
                            columns: ':visible'
                        },
                        customize: function(win) {
                            $(win.document.body).find('h1').css('text-align', 'center');
                            $(win.document.body).find('table').addClass('compact').css('font-size', '10pt');

                            // Add summary info
                            var summary = `
                                <div style="margin-bottom: 20px;">
                                    <div style="display: inline-block; width: 48%; border: 1px solid #000; padding: 10px;">
                                        <h5 style="margin: 0;">Jumlah Pesanan</h5>
                                        <p style="font-size: 14pt; margin: 5px 0 0 0;"><?= $totalQty ?></p>
                                    </div>
                                    <div style="display: inline-block; width: 48%; border: 1px solid #000; padding: 10px; margin-left: 10px;">
                                        <h5 style="margin: 0;">Total Pendapatan</h5>
                                        <p style="font-size: 14pt; margin: 5px 0 0 0;">Rp <?= number_format($totalIncome, 0, ',', '.') ?></p>
                                    </div>
                                </div>
                            `;

                            $(win.document.body).prepend(summary);

                            // Add footer with totals
                            var footer = `
                                <div style="margin-top: 20px; text-align: right;">
                                    <p style="font-weight: bold;">Total Keseluruhan: Rp <?= number_format($totalIncome, 0, ',', '.') ?></p>
                                    <p>Dicetak pada: <?= date('d/m/Y H:i') ?></p>
                                </div>
                            `;

                            $(win.document.body).append(footer);
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        title: 'Riwayat Pesanan PDP Printing',
                        footer: true,
                        messageTop: 'Total Pendapatan: Rp <?= number_format($totalIncome, 0, ",", ".") ?>'
                    },
                    {
                        extend: 'pdfHtml5',
                        title: 'Riwayat Pesanan PDP Printing',
                        footer: true,
                        messageTop: 'Total Pendapatan: Rp <?= number_format($totalIncome, 0, ",", ".") ?>',
                        customize: function(doc) {
                            doc.content[1].table.widths =
                                Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                        }
                    }
                ],
                pageLength: 50,
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "Semua"]
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json'
                }
            });
        });
    </script>
</body>

</html>