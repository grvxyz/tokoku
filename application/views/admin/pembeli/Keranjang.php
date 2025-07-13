<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container py-5">
    <h2 class="mb-4 fw-bold">Checkout</h2>

    <div class="row">
        <!-- Kolom Kiri -->
        <div class="col-lg-8">
            <!-- Alamat Pengiriman -->
            <div class="card p-4 mb-4">
                <h5 class="mb-3 fw-semibold">📍 Alamat Pengiriman</h5>
                <p class="mb-1 fw-bold"><?= $user->nama ?> - <?= $user->nomor_hp ?></p>
                <p class="mb-0"><?= $user->alamat ?></p>
                <a href="<?= site_url('home/edit_profile') ?>" class="btn btn-outline-secondary btn-sm mt-2">Ganti Alamat</a>
            </div>

            <!-- Produk Dipesan -->
            <div class="card p-4 mb-4">
                <h5 class="mb-3 fw-semibold">🛒 Produk Dipesan</h5>
                <?php foreach ($keranjang as $item): ?>
                    <?php $subtotal = $item->qty * $item->harga; ?>
                    <div class="d-flex mb-3">
                        <img src="<?= !empty($item->foto) ? base_url('uploads/pembeli/produk/' . $item->foto) : base_url('assets/no-image.png') ?>"
                             style="width: 80px; height: 80px;" class="me-3 rounded" alt="<?= $item->nama_produk ?>">
                        <div class="flex-grow-1">
                            <h6 class="mb-1"><?= $item->nama_produk ?></h6>
                            <div class="text-muted"><?= $item->nama_kategori ?> - <?= $item->nama_merek ?></div>
                            <div class="mb-2">
                                <form action="<?= site_url('keranjang/ubah_qty/' . $item->id_keranjang . '/kurang') ?>" method="post" class="d-inline">
                                    <button class="btn btn-outline-secondary btn-sm">−</button>
                                </form>
                                <span class="mx-2"><?= $item->qty ?></span>
                                <form action="<?= site_url('keranjang/ubah_qty/' . $item->id_keranjang . '/tambah') ?>" method="post" class="d-inline">
                                    <button class="btn btn-outline-secondary btn-sm">+</button>
                                </form>
                            </div>
                            <div>Harga: Rp<?= number_format($item->harga, 0, ',', '.') ?></div>
                            <div><strong>Subtotal: Rp<?= number_format($subtotal, 0, ',', '.') ?></strong></div>
                        </div>
                        <div>
                            <a href="<?= site_url('keranjang/hapus/' . $item->id_keranjang) ?>" class="btn btn-outline-danger btn-sm ms-3" onclick="return confirm('Hapus produk ini?')">Hapus</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Kurir -->
            <div class="card p-4 mb-4">
                <h5 class="mb-3 fw-semibold">🚚 Metode Pengiriman</h5>
                <form action="<?= site_url('keranjang/pilih_kurir') ?>" method="post">
                    <input type="hidden" name="berat_total" value="<?= $berat_total ?>">
                    <div class="mb-3">
                        <label for="kurir" class="form-label fw-semibold">Pilih Kurir:</label>
                        <select class="form-select" name="kurir" id="kurir" required>
                            <option disabled selected value="">-- Pilih --</option>
                            <option value="ekonomi" <?= $kurir === 'ekonomi' ? 'selected' : '' ?>>Ekonomi (Rp32.000)</option>
                            <option value="standard" <?= $kurir === 'standard' ? 'selected' : '' ?>>Standard (Rp42.400)</option>
                            <option value="reguler" <?= $kurir === 'reguler' ? 'selected' : '' ?>>Reguler (Rp38.000)</option>
                        </select>
                    </div>
                    <button class="btn btn-outline-primary btn-sm" type="submit">Simpan Kurir</button>
                </form>
            </div>
        </div>

        <!-- Kolom Kanan -->
        <div class="col-lg-4">
            <div class="card p-4 shadow-sm">
                <!-- Ringkasan -->
                <h5 class="mt-3">Ringkasan Transaksi</h5>
                <div class="d-flex justify-content-between">
                    <span>Total Harga (<?= count($keranjang) ?> Barang)</span>
                    <strong>Rp<?= number_format($total, 0, ',', '.') ?></strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Ongkos Kirim</span>
                    <strong>Rp<?= number_format($biaya_kirim, 0, ',', '.') ?></strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Asuransi</span>
                    <strong>Rp<?= number_format($asuransi, 0, ',', '.') ?></strong>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <h5>Total Tagihan</h5>
                    <h4 class="text-primary">Rp<?= number_format($total_tagihan, 0, ',', '.') ?></h4>
                </div>

                <!-- Form Checkout -->
                <form id="checkout-form" class="mt-3">
                    <!-- Metode Pembayaran (opsional jika kamu ingin) -->
                    <div class="mb-2">
                        <label class="form-label fw-semibold">Metode Pembayaran</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="metode_pembayaran" value="midtrans" checked>
                            <label class="form-check-label">Bayar via Midtrans</label>
                        </div>
                    </div>

                    <!-- Tombol -->
                    <div class="d-grid gap-2 mt-3">
                        <button type="submit" id="pay-button" class="btn btn-success btn-lg">Bayar Sekarang</button>
                        <a href="<?= site_url('home') ?>" class="btn btn-outline-secondary">Lanjut Belanja</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Midtrans Snap Script -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-n1Of3AssMEuqTgXs"></script>
<script>
document.getElementById('checkout-form').addEventListener('submit', function(e) {
    e.preventDefault();

    const metode = document.querySelector('input[name="metode_pembayaran"]:checked').value;

    fetch("<?= site_url('keranjang/get_token') ?>", {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'metode_pembayaran=' + encodeURIComponent(metode)
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            snap.pay(data.token, {
                onSuccess: function(result) {
                    window.location.href = "<?= site_url('keranjang/sukses/') ?>" + data.order_id;
                },
                onPending: function(result) {
                    alert('Pembayaran tertunda. Silakan selesaikan di Midtrans.');
                },
                onError: function(result) {
                    alert('Gagal melakukan pembayaran!');
                },
                onClose: function() {
                    alert('Kamu menutup popup tanpa menyelesaikan pembayaran.');
                }
            });
        } else {
            alert(data.message || 'Terjadi kesalahan.');
        }
    })
    .catch(err => {
        alert('Gagal terhubung ke server!');
    });
});
</script>
