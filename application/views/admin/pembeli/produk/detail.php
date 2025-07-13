<?php $this->load->view('homepage/header'); ?>
<?php $this->load->view('homepage/menu'); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
    .replies-container {
        border-left: 3px solid #eee;
    }

    .reply-item {
        background: #f9f9f9;
        padding: 10px;
        border-radius: 5px;
    }

    .reply-form {
        background: #f5f5f5;
        padding: 10px;
        border-radius: 5px;
    }

    .comment-header {
        margin-bottom: 8px;
    }

    .comment-item {
        padding: 15px;
        margin-bottom: 15px;
        border: 1px solid #eee;
        border-radius: 5px;
        background: #fff;
    }

    .comment-item:hover {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .product-detail-container {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        padding: 25px;
        margin-bottom: 30px;
    }

    .product-image {
        border-radius: 8px;
        object-fit: contain;
        max-height: 500px;
        width: 100%;
        background: #f9f9f9;
        padding: 15px;
    }

    .product-title {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 10px;
        color: #333;
    }

    .product-price {
        font-size: 28px;
        font-weight: 700;
        color: #e53935;
        margin: 15px 0;
    }

    .product-stock {
        font-size: 16px;
        margin-bottom: 20px;
    }

    .in-stock {
        color: #4CAF50;
        font-weight: 600;
    }

    .out-of-stock {
        color: #f44336;
        font-weight: 600;
    }

    .product-description {
        color: #555;
        line-height: 1.6;
        margin-bottom: 25px;
        font-size: 15px;
    }

    .action-buttons {
        display: flex;
        gap: 15px;
        margin-top: 30px;
    }

    .btn-cart {
        background-color: #FF5722;
        border: none;
        padding: 12px 25px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-cart:hover {
        background-color: #E64A19;
        transform: translateY(-2px);
    }

    .btn-wishlist {
        background-color: #fff;
        border: 1px solid #ddd;
        color: #555;
        padding: 12px 20px;
        transition: all 0.3s;
    }

    .btn-wishlist:hover {
        border-color: #FF5722;
        color: #FF5722;
    }

    .delivery-info {
        background: #f5f5f5;
        border-radius: 8px;
        padding: 15px;
        margin-top: 20px;
    }

    .delivery-info h5 {
        font-size: 16px;
        margin-bottom: 10px;
    }

    .delivery-info p {
        font-size: 14px;
        color: #666;
        margin-bottom: 5px;
    }

    .rating-section {
        display: flex;
        align-items: center;
        margin: 15px 0;
    }

    .rating-stars {
        color: #FFC107;
        font-size: 18px;
        margin-right: 10px;
    }

    .rating-count {
        color: #2196F3;
        font-size: 14px;
        cursor: pointer;
    }

    .product-meta {
        font-size: 14px;
        color: #777;
        margin-top: 10px;
    }

    .share-buttons {
        margin-top: 20px;
    }

    .share-buttons a {
        margin-right: 10px;
        color: #555;
        font-size: 18px;
    }

    .guarantee-badge {
        display: inline-block;
        background: #f8f9fa;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 5px 10px;
        font-size: 12px;
        margin-right: 8px;
        margin-bottom: 8px;
    }

    .comment-section {
        margin-top: 40px;
    }

    .comment-form {
        background: #f9f9f9;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 30px;
    }

    .comment-list {
        margin-top: 30px;
    }

    .comment-item {
        border-bottom: 1px solid #eee;
        padding: 15px 0;
    }

    .comment-author {
        font-weight: bold;
        margin-bottom: 5px;
    }

    .comment-date {
        font-size: 12px;
        color: #777;
    }

    .comment-content {
        margin-top: 10px;
    }

    .no-comments {
        color: #777;
        font-style: italic;
    }

    .rating-input {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
    }

    .rating-input input {
        display: none;
    }

    .rating-input label {
        color: #ddd;
        font-size: 24px;
        padding: 0 5px;
        cursor: pointer;
    }

    .rating-input input:checked~label {
        color: #FFC107;
    }

    .rating-input label:hover,
    .rating-input label:hover~label {
        color: #FFC107;
    }

    /* Tambahan: Agar isi tab terlihat jelas */
    .tab-content {
        background-color: #fff;
        color: #333;
    }

    .tab-content p,
    .tab-content h5,
    .tab-content td {
        color: #333;
    }

    .nav-tabs .nav-link {
        color: #000 !important;
        /* Ubah warna teks menjadi hitam */
    }

    .nav-tabs .nav-link.active {
        color: #000 !important;
        /* Warna teks tab aktif */
        font-weight: bold;
    }
</style>


<div class="container mt-4 mb-5">

    <div class="product-detail-container">
        <div class="row">
            <div class="col-md-6">
                <div class="text-center">
                    <img src="<?= base_url('uploads/pembeli/produk/' . $produk->foto) ?>" class="product-image" alt="<?= $produk->nama_produk ?>">
                </div>
            </div>
            <div class="col-md-6">
                <h1 class="product-title"><?= $produk->nama_produk ?></h1>
                <?php if (!empty($toko)): ?>
    <p><strong>Nama Toko:</strong> <?= $toko->nama_toko ?></p>
<?php endif; ?>

                <?php
                // Hitung rata-rata rating
                $total_rating = 0;
                $average_rating = 0;
                if (isset($komentar) && count($komentar) > 0) {
                    foreach ($komentar as $k) {
                        $total_rating += $k->rating;
                    }
                    $average_rating = $total_rating / count($komentar);
                }
                ?>

                <!-- RATING SECTION: Perbaikan ganda dan konsistensi ikon -->
<div class="rating-section">
    <div class="rating-stars mb-2">
        <?php
        $rounded_avg = round($average_rating);
        for ($i = 1; $i <= 5; $i++): ?>
            <?php if ($i <= $rounded_avg): ?>
                <i class="fas fa-star"></i>
            <?php else: ?>
                <i class="far fa-star"></i>
            <?php endif; ?>
        <?php endfor; ?>
        <span class="ms-2">(<?= number_format($average_rating, 1) ?> dari 5)</span>
    </div>
    <span class="rating-count">(<?= isset($komentar) ? count($komentar) : 0 ?> ulasan)</span>
</div>




                <div class="product-price">Rp <?= number_format($produk->harga, 0, ',', '.') ?></div>

                <div class="product-stock">
                    Status:
                    <span class="<?= $produk->stok > 0 ? 'in-stock' : 'out-of-stock' ?>">
                        <?= $produk->stok > 0 ? 'Tersedia' : 'Habis' ?>
                    </span>
                    <?php if ($produk->stok > 0): ?>
                        | Stok: <strong><?= $produk->stok ?></strong>
                    <?php endif; ?>
                </div>

                <div class="product-description">
                    <?= nl2br($produk->deskripsi) ?>
                </div>

                <div class="guarantee-section">
                    <span class="guarantee-badge"><i class="fas fa-shield-alt"></i> Garansi 1 Tahun</span>
                    <span class="guarantee-badge"><i class="fas fa-undo"></i> 14 Hari Pengembalian</span>
                    <span class="guarantee-badge"><i class="fas fa-check-circle"></i> Original</span>
                </div>

                <div class="action-buttons">
    <?= form_open('cart/add') ?>
        <input type="hidden" name="id_produk" value="<?= $produk->id_produk ?>">
        <input type="hidden" name="harga" value="<?= $produk->harga ?>">
        <input type="hidden" name="qty" value="1">
        <button type="submit" class="btn btn-cart btn-lg">
            <i class="fas fa-shopping-cart"></i> Tambah ke Keranjang
        </button>
    <?= form_close() ?>

    <button class="btn btn-wishlist btn-lg">
        <i class="far fa-heart"></i> Wishlist
    </button>
</div>



                <div class="share-buttons">
                    <span>Bagikan: </span>
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-whatsapp"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                </div>

                <div class="product-meta">
    <?php if (isset($produk->nama_kategori)): ?>
        <p>Kategori: <a href="#"><?= $produk->nama_kategori ?></a> <strong>Dilihat:</strong> <?= $produk->dilihat ?> kali</p>
    <?php else: ?>
        <p><strong>Dilihat:</strong> <?= $produk->dilihat ?> kali</p>
    <?php endif; ?>
</div>

            </div>
        </div>
    </div>

    <!-- Product Tabs Section -->
    <div class="product-detail-container mt-4">
        <ul class="nav nav-tabs" id="productTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="description-tab" data-toggle="tab" href="#description" role="tab">Deskripsi</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="reviews-tab" data-toggle="tab" href="#reviews" role="tab">Ulasan (<?= isset($komentar) ? count($komentar) : 0 ?>)</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="shipping-tab" data-toggle="tab" href="#shipping" role="tab">Pengiriman</a>
            </li>
        </ul>
        <div class="tab-content p-3 border border-top-0 rounded-bottom" id="productTabsContent">
            <div class="tab-pane fade show active" id="description" role="tabpanel">
                <h5>Detail Produk</h5>
                <p><?= nl2br($produk->deskripsi) ?></p>
                <h5 class="mt-4">Spesifikasi</h5>
                <table class="table table-bordered">
                    <tr>
                        <td width="30%">Merek</td>
                        <td><?= $produk->nama_merek ?></td>
                    </tr>
                    <tr>
                        <td>Berat</td>
                        <td>500 gram</td>
                    </tr>
                    <tr>
                        <td>Dimensi</td>
                        <td>20 x 15 x 10 cm</td>
                    </tr>
                </table>
            </div>
            <div class="tab-pane fade" id="reviews" role="tabpanel">
                <!-- Comment Section -->
                <div class="comment-section">
                    <h5>Berikan Ulasan</h5>
                    <?php if ($this->session->userdata('id_pembeli')): ?>
                        <div class="comment-form">
                            <?= form_open(site_url('produk/tambah_komentar/' . $produk->id_produk)) ?>
                            <div class="form-group">
                                <label for="rating">Rating</label>
                                <div class="rating-input">
                                    <input type="radio" id="star5" name="rating" value="5" required />
                                    <label for="star5" title="5 stars">★</label>
                                    <input type="radio" id="star4" name="rating" value="4" />
                                    <label for="star4" title="4 stars">★</label>
                                    <input type="radio" id="star3" name="rating" value="3" />
                                    <label for="star3" title="3 stars">★</label>
                                    <input type="radio" id="star2" name="rating" value="2" />
                                    <label for="star2" title="2 stars">★</label>
                                    <input type="radio" id="star1" name="rating" value="1" />
                                    <label for="star1" title="1 star">★</label>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="komentar">Komentar</label>
                                <textarea class="form-control" id="komentar" name="komentar" rows="3" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Kirim Ulasan</button>
                            <?= form_close() ?>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            Silakan <a href="<?= site_url('home/login') ?>">login</a> untuk memberikan ulasan.
                        </div>
                    <?php endif; ?>

    <div class="comment-list mt-4">
        <h5>Ulasan Pelanggan</h5>

        <?php if (isset($komentar) && count($komentar) > 0): ?>
            <?php foreach ($komentar as $k): ?>
                <div class="comment-item" id="komentar-<?= $k->id_komentar ?>">
                    <div class="comment-header d-flex justify-content-between">
                        <div>
                            <span class="comment-author"><?= $k->nama_pembeli ?></span>

                            <?php if (isset($k->rating) && !is_null($k->rating)): ?>
                                <div class="rating-stars mb-2">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <?php if ($i <= (int)$k->rating): ?>
                                            <i class="fas fa-star"></i>
                                        <?php else: ?>
                                            <i class="far fa-star"></i>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                    <span class="text-muted ms-2"><?= number_format($k->rating, 1) ?> dari 5</span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="comment-date"><?= date('d M Y H:i', strtotime($k->tanggal)) ?></div>
                    </div>

                    <div class="comment-content"><?= nl2br($k->komentar) ?></div>

                    <!-- Balasan Komentar -->
                    <?php if (isset($k->replies) && !empty($k->replies)): ?>
                        <div class="replies-container ml-4 mt-3 pl-3 border-left">
                            <?php foreach ($k->replies as $reply): ?>
                                <div class="reply-item mb-3">
                                    <div class="comment-header d-flex justify-content-between">
                                        <div>
                                            <span class="comment-author text-primary">
                                                <?= $reply->nama_toko ? $reply->nama_toko : 'Admin Toko' ?>
                                                <span class="badge badge-info">Pemilik Toko</span>
                                            </span>
                                        </div>
                                        <div class="comment-date"><?= date('d M Y H:i', strtotime($reply->tanggal)) ?></div>
                                    </div>
                                    <div class="comment-content"><?= nl2br($reply->komentar) ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                                    <!-- Form Balasan (hanya tampil untuk pemilik toko) -->
<?php if (!empty($is_pemilik_toko)): ?>
    <div class="reply-form mt-3">
        <?= form_open(site_url('produk/tambah_balasan/' . $k->id_komentar)) ?>
        <div class="form-group">
            <label for="balasan">Balas Ulasan</label>
            <textarea class="form-control" id="balasan" name="balasan" rows="2" required></textarea>
        </div>
        <button type="submit" class="btn btn-sm btn-outline-primary">Kirim Balasan</button>
        <?= form_close() ?>
    </div>
<?php endif; ?> 
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="no-comments">Belum ada ulasan untuk produk ini.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="shipping" role="tabpanel">
                <h5>Kebijakan Pengiriman</h5>
                <p>Kami bekerjasama dengan beberapa jasa pengiriman terkemuka untuk memastikan produk Anda sampai dengan aman dan tepat waktu.</p>
                <h5 class="mt-4">Jasa Pengiriman</h5>
                <div class="row">
                    <div class="col-md-2 text-center">
                        <img src="https://via.placeholder.com/60" class="img-fluid" alt="JNE">
                    </div>
                    <div class="col-md-2 text-center">
                        <img src="https://via.placeholder.com/60" class="img-fluid" alt="J&T">
                    </div>
                    <!-- More shipping options -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Tambahan JS agar tab bisa diklik -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {
        $('#productTabs a').on('click', function(e) {
            e.preventDefault();
            $(this).tab('show');
        });
    });
</script>
<?php $this->load->view('homepage/footer'); ?>