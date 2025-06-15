<?php
session_start();
require_once 'koneksi.php';


// Add this near the top of the file to handle product data
$products = [];
$result = $conn->query("SELECT idproduk, nama_produk, deskripsi, jenis, ukuran, harga FROM product");
while ($row = $result->fetch_assoc()) {
    $products[$row['nama_produk']][] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" type="text/css" href="style.css" />
    <link rel="icon" type="png" href="" />
    <title>PDP Printing Kudus | Cetak cepat kualitas hebat.</title>
</head>

<body>
    <div class="medsos">
        <p class="bi bi-clock"> Senin - Sabtu | 08.00 - 16.30</p>
        <a href="https://www.instagram.com/pdp_printing/" target="_blank" class="bi bi-instagram"></a>
        <a href="https://www.facebook.com/joindesign46/" target="_blank" class="bi bi-facebook"></a>
    </div>

    <header>
        <nav>
            <div class="menu-toggle">
                <input type="checkbox" />
                <span></span>
                <span></span>
                <span></span>
            </div>

            <img src="./img/logo.png" alt="" class="logo" />

            <ul>
                <li><a href="#home" style="--i: 1" class="active">HOME</a></li>
                <li><a href="#produk" style="--i: 2">PRODUK</a></li>
                <li><a href="#cara" style="--i: 3">CARA PEMESANAN</a></li>
                <li><a href="#tentang" style="--i: 4">TENTANG KAMI</a></li>
            </ul>

            <div class="menu-lain" style="display: flex; gap: 2rem;">
                <button class="menu akun" id="accountBtn">
                    <?php if (isset($_SESSION['no_telp'])): ?>
                        <i class="bi bi-person-fill-check" style="color: #53bace;"></i> <!-- Icon for logged in users -->
                    <?php else: ?>
                        <i class="bi bi-person-circle"></i> <!-- Icon for guests -->
                    <?php endif; ?>
                </button>
            </div>
        </nav>
        <div class="overlay"></div>
    </header>
    <?php include 'account-modal.php'; ?>

    <section class="home" id="home">
        <div class="home-main">
            <h3>Solusi masalah</h3>
            <h4 class="text-animate">
                <div class="card-animate">
                    <ul class="flip">
                        <li>Packaging</li>
                        <li>Percetakan</li>
                        <li>Desainer</li>
                    </ul>
                </div>
            </h4>
            <p>Cetak cepat kualitas hebat</p>
        </div>

        <div class="papan">
            <div class="papan-inti">
                <div class="papan-content" style="--i: 1">
                    <img src="./img/like.png" alt="" />
                    <h1>Kualitas Cetak Terjamin</h1>
                </div>
                <div class="papan-content" style="--i: 2">
                    <img src="./img/Talk.png" alt="" />
                    <h1>Customer Service yang Ramah dan Profesional</h1>
                </div>
                <div class="papan-content" style="--i: 3">
                    <img src="./img/RP.png" alt="" />
                    <h1>Dengan Harga yang Murah dan Memuaskan</h1>
                </div>
                <div class="papan-content" style="--i: 4">
                    <img src="./img/box.png" alt="" />
                    <h1>Pengiriman Cepat & Aman</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="produk" id="produk">
        <h2 class="heading">Pilihan <span>Cetak</span></h2>
        <div class="search-container">
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" id="searchInput" placeholder="Cari produk yang anda inginkan..." oninput="searchProducts()" />
            </div>
            <style>
                .search-container {
                    width: 50%;
                    margin: 0 auto;
                    /* Ini akan membuat container berada di tengah */
                    padding: 20px 0;
                    /* Memberikan jarak atas dan bawah */
                }

                .search-box {
                    position: relative;
                    width: 100%;
                }

                .search-box i {
                    position: absolute;
                    left: 15px;
                    top: 50%;
                    transform: translateY(-50%);
                    color: #777;
                }

                .search-box input {
                    width: 100%;
                    padding: 12px 20px 12px 40px;
                    border: 1px solid #ddd;
                    border-radius: 25px;
                    outline: none;
                    font-size: 16px;
                    transition: all 0.3s;
                }

                .search-box input:focus {
                    border-color: #53bace;
                    box-shadow: 0 0 5px rgba(83, 186, 206, 0.5);
                }
            </style>
            <script>
                function searchProducts() {
                    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
                    const productCards = document.querySelectorAll('.produk-card');
                    let foundResults = false;

                    productCards.forEach(card => {
                        const productName = card.querySelector('.produk-title').textContent.toLowerCase();
                        const productType = card.dataset.product.toLowerCase();

                        if (productName.includes(searchTerm)) {
                            card.style.display = 'block';
                            foundResults = true;
                        } else {
                            // Cek juga di jenis produk
                            const productData = <?php echo json_encode($products); ?>;
                            const types = productData[card.dataset.product]?.map(p => p.jenis.toLowerCase()) || [];

                            const hasMatchingType = types.some(type => type.includes(searchTerm));
                            if (hasMatchingType) {
                                card.style.display = 'block';
                                foundResults = true;
                            } else {
                                card.style.display = 'none';
                            }
                        }
                    });

                    if (!foundResults) {
                        alert('Produk atau jenis tidak ditemukan');
                        // Tampilkan semua produk jika tidak ada hasil
                        productCards.forEach(card => card.style.display = 'block');
                    }
                }

                // Tambahkan event listener untuk reset pencarian saat input dikosongkan
                document.getElementById('searchInput').addEventListener('input', function(e) {
                    if (e.target.value === '') {
                        document.querySelectorAll('.produk-card').forEach(card => {
                            card.style.display = 'block';
                        });
                    }
                });
            </script>
        </div>
        <div class="produk-grid">
            <?php
            $result = $conn->query("SELECT idproduk, nama_produk FROM product GROUP BY nama_produk");

            $productImages = [
                'Banner' => './img/produk/banner.png',
                'Brosur' => './img/produk/brosur.png',
                'Aksesoris' => './img/produk/pin.png',
                'Stiker' => './img/produk/stiker.png',
                'Kalender' => './img/produk/kalender-dinding.png',
                'Pamflet' => './img/produk/pamflet.png',
                'Mug' => './img/produk/mug.png',
                'Buku' => './img/produk/buku.png'
                // Add more products as needed
            ];

            while ($row = $result->fetch_assoc()) {
                $productName = $row['nama_produk'];
                $imageUrl = isset($productImages[$productName]) ? $productImages[$productName] : '';
                $productId = strtolower(str_replace(' ', '_', $productName));

                echo "<div class='produk-card' data-product='" . htmlspecialchars($productName) . "' " .
                    (isset($_SESSION['no_telp']) ? "onclick='openProduct(\"" . htmlspecialchars($productName) . "\")'" : "onclick='showLoginAlert()'") . ">";
                echo "<img src='" . $imageUrl . "' alt='" . htmlspecialchars($productName) . "'>";
                echo "<p class='produk-title'>" . htmlspecialchars($productName) . "</p>";
                echo "</div>";
            }
            ?>
        </div>
    </section>
    <?php include 'detail.php'; ?>

    <section class="cara" id="cara">
        <h2 class="heading">Informasi <span>Penting</span></h2>

        <div class="order-process">
            <div class="step-card">
                <div class="step-number">1</div>
                <h3>Pilih Produk</h3>
                <p>Pilih produk yang Anda inginkan dari katalog kami. Pastikan untuk memeriksa spesifikasi dan harga produk.</p>
            </div>
            <div class="step-card">
                <div class="step-number">2</div>
                <h3>Siapkan File Desain</h3>
                <p>Siapkan file desain Anda dalam format yang sesuai (.pdf, .jpg, atau .png). Jika belum memiliki desain, tim kami siap membantu.</p>
            </div>
            <div class="step-card">
                <div class="step-number">3</div>
                <h3>Notifikasi Pesanan</h3>
                <p>Kami akan memberikan notifikasi melalui WhatsApp dalam waktu 1-3 jam kerja</p>
            </div>
            <div class="step-card">
                <div class="step-number">4</div>
                <h3>Hubungi Kami</h3>
                <p>Hubungi kami melalui WhatsApp, telepon, atau kunjungi toko kami. Konsultasikan kebutuhan Anda dengan tim kami.</p>
            </div>
            <div class="step-card">
                <div class="step-number">5</div>
                <h3>Proses Cetak</h3>
                <p>Setelah desain disetujui, kami akan memulai proses cetak. Proses cetak memakan waktu 1-3 hari kerja.</p>
            </div>
        </div>
    </section>

    <section class="tentang" id="tentang">
        <div class="inti">
            <div class="inti-card">
                <div class="inti-in">
                    <h3>Tentang <span>Kami</span></h3>
                    <p>
                        PDP Printing adalah perusahaan yang bergerak di bidang digital printing, offset, dan laser print. Kami menyediakan berbagai macam produk cetak dengan kualitas terbaik dan harga yang terjangkau. Kami juga siap membantu Anda
                        dalam proses desain jika Anda belum memiliki desain.
                    </p>

                    <button class="add">Selengkapnya Tentang Kami</button>
                </div>
            </div>
        </div>

        <!-- Tambahkan ini sebelum </body> -->
        <div id="aboutModal" class="modal">
            <div class="modal-content">
                <span class="modal-close-btn">&times;</span>
                <h2>Tentang Kami Lebih Detail</h2>
                <div class="modal-body">
                    <p>PDP Printing adalah perusahaan percetakan profesional yang telah beroperasi sejak tahun 2017. Kami mengkhususkan diri dalam berbagai jenis produk cetak dengan kualitas tinggi dan pelayanan terbaik.</p>

                    <h3>Visi Kami</h3>
                    <p>Menjadi penyedia jasa percetakan terdepan di Kudus dengan kualitas dan pelayanan terbaik.</p>

                    <h3>Misi Kami</h3>
                    <ul>
                        <li>Memberikan produk cetak berkualitas tinggi</li>
                        <li>Memberikan pelayanan pelanggan yang ramah dan profesional</li>
                        <li>Memberikan harga yang kompetitif</li>
                        <li>Menggunakan teknologi terkini dalam proses produksi</li>
                    </ul>
                </div>
            </div>
            <style>
                /* About Modal Styles */
                #aboutModal {
                    display: none;
                    position: fixed;
                    z-index: 1000;
                    left: 0;
                    top: 0;
                    width: 100%;
                    height: 100%;
                    background-color: rgba(0, 0, 0, 0.7);
                    align-items: center;
                    justify-content: center;
                }

                #aboutModal .modal-content {
                    background-color: #fefefe;
                    margin: auto;
                    padding: 20px;
                    border-radius: 10px;
                    width: 80%;
                    max-width: 700px;
                    max-height: 80vh;
                    overflow-y: auto;
                    position: relative;
                }

                #aboutModal .modal-body {
                    padding: 20px 0;
                }

                #aboutModal h2 {
                    color: #53bace;
                    margin-bottom: 15px;
                }

                #aboutModal h3 {
                    color: #333;
                    margin-top: 20px;
                }

                #aboutModal ul {
                    padding-left: 20px;
                }

                #aboutModal ul li {
                    margin-bottom: 8px;
                }
            </style>
        </div>

        <div class="mengapa">
            <h2 class="heading">Mengapa <span>Kami</span></h2>

            <div class="mengapa-content">
                <div class="mengapa-card">
                    <h1 class="bi bi-journal-check"></h1>
                    <div>
                        <h2>Produk Beragam</h2>
                        <p>Kami menyediakan berbagai macam produk cetak.</p>
                    </div>
                </div>
                <div class="mengapa-card">
                    <h1 class="bi bi-vector-pen"></h1>
                    <div>
                        <h2>Belum Punya Desain?</h2>
                        <p>Kami siap membantu.</p>
                    </div>
                </div>
                <div class="mengapa-card">
                    <h1 class="bi bi-folder-check"></h1>
                    <div>
                        <h2>Data Kami Simpan Dengan Aman</h2>
                        <p>Jika ingin cetak ulang tak perlu khawatir data hilang.</p>
                    </div>
                </div>
                <div class="mengapa-card">
                    <h1 class="bi bi-bag-check"></h1>
                    <div>
                        <h2>Produk Berkualitas</h2>
                        <p>Kami memberikan produk dengan kualitas terbaik.</p>
                    </div>
                </div>
                <div class="mengapa-card">
                    <h1 class="bi bi-calculator"></h1>
                    <div>
                        <h2>Hitung Harga</h2>
                        <p>Anda bisa menghitung harga produk yang Anda pesan.</p>
                    </div>
                </div>
                <div class="mengapa-card">
                    <h1 class="bi bi-tag"></h1>
                    <div>
                        <h2>Harga Kompetitif</h2>
                        <p>Kami memberikan dan menyarankan harga yang optimal.</p>
                    </div>
                </div>
                <div class="mengapa-card">
                    <h1 class="bi bi-box-seam"></h1>
                    <div>
                        <h2>Produk Dalam Keadaan Aman</h2>
                        <p>Kami mengemas produk dengan rapi dan aman.</p>
                    </div>
                </div>
                <div class="mengapa-card">
                    <h1 class="bi bi-chat-left-text"></h1>
                    <div>
                        <h2>Konsultasikan Pada Kami</h2>
                        <p>Kami siap memberikan saran untuk optimasi kebutuhan Anda.</p>
                    </div>
                </div>
                <div class="mengapa-card">
                    <h1 class="bi bi-send"></h1>
                    <div>
                        <h2>Berbagai Media Komunikasi</h2>
                        <p>Melalui WA, e-mail, Telpon, kami siap berkomunikasi dengan Anda</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="bantuan">
        <a href="https://api.whatsapp.com/send?phone=6289507236506"><i class="bi bi-whatsapp"></i> Contact us</a>
    </div>

    <h2 class="heading">Info <span>Lainnya</span></h2>
    <div class="iklan">
        <div class="logos-slide">
            <img src="./img/iklan/1.jpg" alt="" />
            <img src="./img/iklan/2.jpg" alt="" />
            <img src="./img/iklan/3.jpg" alt="" />
            <img src="./img/iklan/4.jpg" alt="" />
            <img src="./img/iklan/5.jpg" alt="" />
            <img src="./img/iklan/6.jpg" alt="" />
            <img src="./img/iklan/7.jpg" alt="" />
            <img src="./img/iklan/8.jpg" alt="" />
        </div>

        <div class="gambar">
            <span class="gambar-close"></span>
            <img class="popup-gambar" src="" />
        </div>
    </div>

    <section class="footer">
        <div class="footer-main">
            <div class="footer-card">
                <div class="footer-logo">
                    <i class="bi bi-calendar-event"></i>
                    <h2>Jam Buka</h2>
                </div>
                <table>
                    <tr>
                        <td>Senin - Sabtu</td>
                        <td>: 08.00 - 16.00</td>
                    </tr>
                    <tr>
                        <td>Minggu / Tgl Merah</td>
                        <td>: Libur</td>
                    </tr>
                </table>
            </div>

            <div class="footer-card">
                <div class="footer-logo">
                    <i class="bi bi-people"></i>
                    <h2>Kontak</h2>
                </div>
                <div class="medsos-bawah">
                    <a href="https://www.instagram.com/pdp_printing/" target="_blank"><i class="bi bi-instagram"></i>pdp_printing</a>
                    <a href="https://www.facebook.com/joindesign46/" target="_blank"><i class="bi bi-facebook"></i>Pdp Printing New</a>
                    <a href="https://mail.google.com/mail/u/0/?view=cm&tf=1&fs=1&to=pdpprinting46@gmail.com" target="_blank"><i class="bi bi-envelope"></i>pdpprinting46@gmail.com</a>
                </div>
            </div>
        </div>

        <div class="map">
            <div class="footer-logo">
                <i class="bi bi-geo-alt-fill"></i>
                <h2>Lokasi</h2>
            </div>

            <iframe
                src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d283.84864660853094!2d110.82680364513598!3d-6.783528995666604!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e70dbea8961a1d3%3A0x28b393bfc565ca96!2sPDP%20Printing%20New!5e1!3m2!1sid!2sid!4v1739441205428!5m2!1sid!2sid"
                width="600"
                height="450"
                style="border: 0"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </section>

    <script src="script.js"></script>
    <script>
        // Fungsi untuk menampilkan alert (jika belum login)
        function showLoginAlert() {
            document.getElementById('accountModal').style.display = 'block';
        }

        // Account Modal functionality
        const accountBtn = document.getElementById('accountBtn');
        const accountModal = document.getElementById('accountModal');
        const modalCloseBtn = document.querySelector('.modal-close-btn');

        accountBtn.addEventListener('click', function() {
            accountModal.style.display = 'block';
        });

        modalCloseBtn.addEventListener('click', function() {
            accountModal.style.display = 'none';
        });

        window.addEventListener('click', function(event) {
            if (event.target == accountModal) {
                accountModal.style.display = 'none';
            }
        });

        // Function to open product popup
        function openProduct(productName) {
            const popup = document.getElementById('popup');
            const productData = <?php echo json_encode($products); ?>;
            const currentProduct = productData[productName];

            if (currentProduct) {
                // Set product title
                document.querySelector('.popup-title').textContent = productName;

                // Clear and populate jenis options
                const jenisContainer = document.querySelector('.jenis');
                jenisContainer.innerHTML = '';

                const uniqueTypes = [...new Set(currentProduct.map(p => p.jenis))];
                uniqueTypes.forEach((jenis, index) => {
                    if (jenis) {
                        const button = document.createElement('button');
                        button.className = 'tab-btn' + (index === 0 ? ' active' : '');
                        button.textContent = jenis;
                        button.onclick = function(e) {
                            e.preventDefault();
                            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
                            this.classList.add('active');
                            updateUkuranOptions(productName, jenis);
                            updateDescription(productName, jenis);
                        };

                        const label = document.createElement('label');
                        label.appendChild(button);
                        jenisContainer.appendChild(label);
                    }
                });

                // Initialize with first jenis
                if (uniqueTypes.length > 0 && uniqueTypes[0]) {
                    updateUkuranOptions(productName, uniqueTypes[0]);
                    updateDescription(productName, uniqueTypes[0]);
                }

                // Set up form submission
                const form = document.querySelector('.product-form');
                form.onsubmit = function(e) {
                    e.preventDefault();
                    submitOrder(productName);
                };

                // Show popup
                popup.style.display = 'flex';
            }
        }

        function updateUkuranOptions(productName, jenis) {
            const selectUkuran = document.querySelector('.select-ukuran');
            selectUkuran.innerHTML = '<option value="">Pilih Ukuran terlebih dahulu</option>';

            const productData = <?php echo json_encode($products); ?>;
            const filteredProducts = productData[productName].filter(p => p.jenis === jenis);

            filteredProducts.forEach(product => {
                if (product.ukuran) {
                    const option = document.createElement('option');
                    option.value = product.ukuran;
                    option.textContent = product.ukuran;
                    option.dataset.harga = product.harga || 0;
                    selectUkuran.appendChild(option);
                }
            });

            // Add event listener for price calculation
            selectUkuran.onchange = calculateTotal;
        }

        function updateDescription(productName, jenis) {
            const productData = <?php echo json_encode($products); ?>;
            const product = productData[productName].find(p => p.jenis === jenis);
            const descriptionElement = document.querySelector('.popup-description');

            if (product && product.deskripsi) {
                descriptionElement.textContent = product.deskripsi;
            } else {
                descriptionElement.textContent = 'Tidak ada deskripsi tersedia.';
            }
        }

        function calculateTotal() {
            const quantity = parseInt(document.getElementById('quantityInput').value) || 1;
            const selectedOption = document.querySelector('.select-ukuran option:checked');
            const price = selectedOption ? parseInt(selectedOption.dataset.harga) || 0 : 0;
            const total = quantity * price;

            document.querySelector('.popup-total-price').textContent = 'Rp ' + total.toLocaleString('id-ID');
        }

        function submitOrder(productName) {
            const formData = new FormData();
            const jenis = document.querySelector('.tab-btn.active').textContent;
            const ukuran = document.querySelector('.select-ukuran').value;
            const quantity = document.getElementById('quantityInput').value;
            const delivery = document.querySelector('input[name="delivery"]:checked').value;
            const fileInput = document.querySelector('input[type="file"]');
            const pesan = document.querySelector('textarea[name="pesan"]').value;

            // Find the product ID
            const productData = <?php echo json_encode($products); ?>;
            const product = productData[productName].find(p => p.jenis === jenis && (!ukuran || p.ukuran === ukuran));
            const productId = product ? product.idproduk : '';

            formData.append('idproduk', productId);
            formData.append('ukuran', ukuran);
            formData.append('quantity', quantity);
            formData.append('delivery', delivery);
            formData.append('pesan', pesan); // Ini yang diubah dari 'notes' ke 'pesan'

            if (fileInput.files.length > 0) {
                formData.append('file', fileInput.files[0]);
            }

            fetch('process_order.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Pesanan berhasil dikirim!');
                        document.getElementById('popup').style.display = 'none';
                    } else {
                        alert('Error: ' + (data.message || 'Gagal mengirim pesanan'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mengirim pesanan');
                });
        }

        // Quantity input controls
        document.querySelector('.quantity-btn.minus').addEventListener('click', function() {
            const input = document.getElementById('quantityInput');
            if (input.value > 1) {
                input.value--;
                calculateTotal();
            }
        });

        document.querySelector('.quantity-btn.plus').addEventListener('click', function() {
            const input = document.getElementById('quantityInput');
            if (input.value < 99) {
                input.value++;
                calculateTotal();
            }
        });

        document.getElementById('quantityInput').addEventListener('change', calculateTotal);

        // Close popup
        document.querySelector('.popup-close-btn').addEventListener('click', function() {
            document.getElementById('popup').style.display = 'none';
        });

        // Function to show login alert (for non-logged in users)
        function showLoginAlert() {
            document.getElementById('accountModal').style.display = 'block';
        }

        // Tambahkan ini di bagian atas script Anda (setelah deklarasi productImages)
        const productTypeImages = {
            'Banner': {
                'Biasa': './img/produk/banner.png',
                'X-banner': './img/produk/x-banner.png',
                'Y-banner': './img/produk/y-banner.png',
                'Roll banner': './img/produk/roll-banner.png'
            },

            'Kalender': {
                'Dinding': './img/produk/kalender-dinding.png',
                'Meja': './img/produk/kalender-meja.png',
            },

            'Buku': {
                'Biasa': './img/produk/buku.png',
                'Nota': './img/produk/nota.png'
            },

            'Aksesoris': {
                'Pin': './img/produk/pin.png',
                'Gantungan kunci': './img/produk/kunci.png'
            },

            'Brosur': {
                'BIasa': './img/produk/brosur.png'
            },
            'Mug': {
                'Kaca': './img/produk/mug.png'
            },
            'Stiker': {
                'Biasa': './img/produk/stiker.png',
                'OneWay': './img/produk/stiker.png'
            }
            // Dan seterusnya untuk jenis produk lainnya
        };

        function updateDescription(productName, jenis) {
            const productData = <?php echo json_encode($products); ?>;
            const product = productData[productName].find(p => p.jenis === jenis);
            const descriptionElement = document.querySelector('.popup-description');
            const productImageElement = document.querySelector('.popup-product-img');

            // Update deskripsi
            if (product && product.deskripsi) {
                descriptionElement.textContent = product.deskripsi;
            } else {
                descriptionElement.textContent = 'Tidak ada deskripsi tersedia.';
            }

            // Update gambar berdasarkan jenis
            if (productTypeImages[productName] && productTypeImages[productName][jenis]) {
                productImageElement.src = productTypeImages[productName][jenis];
            }
        }

        // Tambahkan ini di bagian script
        document.querySelector('.add').addEventListener('click', function() {
            document.getElementById('aboutModal').style.display = 'flex';
        });

        // Tambahkan event listener untuk tombol close
        document.querySelector('#aboutModal .modal-close-btn').addEventListener('click', function() {
            document.getElementById('aboutModal').style.display = 'none';
        });

        // Tutup modal saat klik di luar konten modal
        window.addEventListener('click', function(event) {
            if (event.target == document.getElementById('aboutModal')) {
                document.getElementById('aboutModal').style.display = 'none';
            }
        });
    </script>
</body>

</html>