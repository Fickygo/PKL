<div class="product-popup" id="popup">
    <div class="product-popup-content">
        <div class="popup-close-btn"><i class="bi bi-x"></i></div>
        <div class="popup-grid">
            <div class="popup-left">
                <img src="" alt="" class="popup-product-img" />
            </div>
            <div class="popup-right">
                <h2 class="popup-title"></h2>
                <div class="popup-description"></div>

                <form class="product-form" enctype="multipart/form-data" method="POST" action="process_order.php">
                    <div class="form-group">
                        <label>Jenis Produk</label>
                        <div class="jenis">
                            <!-- Jenis options will be populated by JavaScript -->
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Metode Pengiriman</label>
                        <div class="delivery-options">
                            <label>
                                <input type="radio" name="delivery" value="Ambil di Tempat" checked />
                                Ambil di Tempat
                            </label>
                            <label>
                                <input type="radio" name="delivery" value="Kirim ke Lokasi" />
                                Kirim ke Lokasi
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Upload File (.pdf/.png/.jpg)</label>
                        <input type="file" name="file" id="fileInput" accept=".pdf,.png,.jpg,.jpeg" />
                        <!-- Preview container -->
                        <div class="file-preview-container" id="filePreviewContainer" style="display: none; margin-top: 10px;">
                            <div id="imagePreview" style="max-width: 100%; max-height: 200px; display: none;">
                                <img id="previewImage" src="#" alt="Preview Gambar" style="max-width: 100%; max-height: 200px;" />
                            </div>
                            <div id="pdfPreview" style="display: none;">
                                <embed id="previewPdf" src="#" width="100%" height="200px" type="application/pdf" />
                            </div>
                            <button type="button" id="removeFileBtn" style="margin-top: 5px; background: #ff4444; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer;">Hapus File</button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Ukuran</label>
                        <select class="select-ukuran" name="ukuran" required>
                            <option value=""></option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Jumlah</label>
                        <div class="quantity-input">
                            <button type="button" class="quantity-btn minus">-</button>
                            <input type="number" name="quantity" value="1" min="1" max="99" id="quantityInput" required />
                            <button type="button" class="quantity-btn plus">+</button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Total Harga</label>
                        <h3 class="popup-total-price">Rp 0</h3>
                    </div>

                    <div class="form-group">
                        <label>Pesan</label>
                        <textarea name="pesan" placeholder="Tuliskan keterangan tambahan"></textarea>
                    </div>

                    <button type="submit" class="submit-btn">Kirim Pesanan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .file-preview-container {
        border: 1px dashed #ccc;
        padding: 10px;
        border-radius: 5px;
        background-color: #f9f9f9;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('fileInput');
        const filePreviewContainer = document.getElementById('filePreviewContainer');
        const imagePreview = document.getElementById('imagePreview');
        const pdfPreview = document.getElementById('pdfPreview');
        const previewImage = document.getElementById('previewImage');
        const previewPdf = document.getElementById('previewPdf');
        const removeFileBtn = document.getElementById('removeFileBtn');

        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const fileType = file.type;

                if (fileType === 'application/pdf') {
                    // For PDF files
                    const pdfUrl = URL.createObjectURL(file);
                    previewPdf.src = pdfUrl;
                    pdfPreview.style.display = 'block';
                    imagePreview.style.display = 'none';
                    filePreviewContainer.style.display = 'block';
                } else if (fileType.match('image.*')) {
                    // For image files
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImage.src = e.target.result;
                        imagePreview.style.display = 'block';
                        pdfPreview.style.display = 'none';
                        filePreviewContainer.style.display = 'block';
                    }
                    reader.readAsDataURL(file);
                }
            }
        });

        removeFileBtn.addEventListener('click', function() {
            fileInput.value = '';
            filePreviewContainer.style.display = 'none';
            imagePreview.style.display = 'none';
            pdfPreview.style.display = 'none';
        });
    });
</script>