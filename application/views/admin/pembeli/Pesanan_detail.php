<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="container py-5">
    <h2 class="mb-4">Detail Pesanan</h2>

    <form id="form-bayar">
        <input type="hidden" name="metode_pembayaran" value="<?= $pesanan->metode_pembayaran ?>">
        <!-- Bisa ditambah input tersembunyi lain jika dibutuhkan -->
    </form>

    <p><strong>ID Pesanan:</strong> <?= $pesanan->id_pesanan ?></p>
    <p><strong>Tanggal:</strong> <?= $pesanan->tanggal_pembelian ?></p>
    <p><strong>Status:</strong> <?= ucfirst($pesanan->status) ?></p>
    <p><strong>Kurir:</strong> <?= ucfirst($pesanan->kurir) ?></p>
    <p><strong>Total:</strong> Rp<?= number_format($pesanan->total_harga, 0, ',', '.') ?></p>

    <hr>

    <h5>Produk:</h5>
    <?php foreach ($items as $item): ?>
        <div class="d-flex align-items-center mb-3">
            <img src="<?= base_url('uploads/pembeli/produk/' . $item->foto) ?>" class="me-3" style="width: 80px; height: 80px; object-fit: contain;">
            <div>
                <strong><?= $item->nama_produk ?></strong><br>
                Jumlah: <?= $item->jumlah ?><br>
                Harga: Rp<?= number_format($item->harga, 0, ',', '.') ?>
            </div>
        </div>
    <?php endforeach; ?>

    <div class="mt-4 d-flex justify-content-between">
        <a href="<?= site_url('home') ?>" class="btn btn-outline-secondary">Kembali ke Home</a>
        <?php if ($pesanan->status == 'pending'): ?>
            <button class="btn btn-success" id="pay-button">Bayar Sekarang</button>
        <?php endif; ?>
    </div>
</div>

<!-- Midtrans Script -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="Mid-client-dmcPd4u-GhwQ11dU"></script>
<script>
document.getElementById('pay-button')?.addEventListener('click', function (e) {
    e.preventDefault();

    fetch("<?= site_url('keranjang/get_token') ?>", {
        method: "POST",
        body: new FormData(document.getElementById("form-bayar"))
    })
    .then(response => response.json())
    .then(data => {
        if (data.token) {
            snap.pay(data.token, {
                onSuccess: function(result){
                    window.location.href = "<?= site_url('keranjang/sukses/') ?>" + data.order_id;
                },
                onPending: function(result){
                    alert("Menunggu pembayaran...");
                },
                onError: function(result){
                    alert("Pembayaran gagal.");
                },
                onClose: function(){
                    alert("Kamu menutup pembayaran.");
                }
            });
        } else {
            alert(data.message || "Gagal mendapatkan token Midtrans.");
        }
    });
});
</script>
