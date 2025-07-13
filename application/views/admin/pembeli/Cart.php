<div class="container my-5">
    <h3 class="mb-4">Keranjang Belanja</h3>

    <?php if (!empty($items)): ?>
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Produk</th>
                        <th>Toko</th>
                        <th>Harga</th>
                        <th>Qty</th>
                        <th>Total</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $total = 0; ?>
                    <?php foreach ($items as $item): 
                        $subtotal = $item->harga_produk * $item->qty;
                        $total += $subtotal;
                    ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="<?= !empty($item->foto) ? base_url('uploads/pembeli/produk/' . $item->foto) : base_url('assets/no-image.png') ?>"
                                     style="width: 80px; height: 80px;" class="me-3 rounded border" alt="<?= $item->nama_produk ?>">
                                <span><?= $item->nama_produk ?></span>
                            </div>
                        </td>
                        <td><?= $item->nama_toko ?></td>
                        <td>Rp<?= number_format($item->harga_produk, 0, ',', '.') ?></td>
                        <td>
                            <form method="post" action="<?= base_url('index.php/cart/update') ?>" class="d-flex align-items-center">
                                <input type="hidden" name="id_keranjang" value="<?= $item->id_keranjang ?>">
                                <input type="number" name="qty" value="<?= $item->qty ?>" min="1" class="form-control form-control-sm me-2" style="width: 70px;">
                                <button type="submit" class="btn btn-sm btn-secondary">Update</button>
                            </form>
                        </td>
                        <td>Rp<?= number_format($subtotal, 0, ',', '.') ?></td>
                        <td>
                            <a href="<?= base_url('index.php/cart/delete/' . $item->id_keranjang) ?>" onclick="return confirm('Yakin ingin menghapus item ini?')" class="btn btn-sm btn-danger">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-end">Total</th>
                        <th colspan="2">Rp<?= number_format($total, 0, ',', '.') ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4">
            <a href="<?= base_url('index.php/cart/clear') ?>" onclick="return confirm('Kosongkan keranjang?')" class="btn btn-outline-danger">
                Kosongkan Keranjang
            </a>
            <a href="<?= base_url('index.php/keranjang') ?>" class="btn btn-primary">Checkout</a>
        </div>

    <?php else: ?>
        <!-- Jika keranjang kosong -->
        <div class="text-center my-5">
            <i class="bi bi-cart-x display-3 text-muted"></i>
            <h4 class="mt-3">Keranjang Belanja Kosong</h4>
            <p class="text-muted">Yuk, isi keranjangmu dengan produk berkualitas dan harga terbaik!</p>
            <a href="<?= site_url('home') ?>" class="btn btn-primary mt-3">Belanja Sekarang</a>
        </div>
    <?php endif; ?>
</div>

<!-- Footer -->

