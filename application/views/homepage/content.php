<!-- Tambahkan ini di <head> halaman kamu -->
<style>
.carousel-img {
    height: 350px; /* tinggi tetap */
    object-fit: cover; /* memotong gambar agar tetap proporsional */
    object-position: center; /* fokus bagian tengah gambar */
    border-radius: 15px; /* opsional, buat sudut melengkung */
}


</style>

<!-- Carousel Promo -->
<div id="carouselExample" class="carousel slide mt-3 container" data-bs-ride="carousel">
    <div class="carousel-inner">
        <?php $no = 0; foreach ($promo as $row): ?>
            <div class="carousel-item <?= $no == 0 ? 'active' : '' ?>">
                <img src="<?= base_url('uploads/promo/' . $row->poster) ?>"
                     class="d-block w-100 carousel-img"
                     alt="<?= $row->nama_promo ?>">
            </div>
        <?php $no++; endforeach; ?>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
</div>
<style>
.kategori-grid {
    display: flex;
    flex-wrap: wrap;
    justify-content: flex-start;
    gap: 20px;
}
.kategori-item {
    width: 90px;
    text-align: center;
    font-size: 14px;
}
.kategori-item img {
    width: 60px;
    height: 60px;
    object-fit: contain;
    margin-bottom: 5px;
}
</style>

<!-- KATEGORI -->
<div class="container mt-4">
    <h5 class="mb-3">Kategori</h5>
    <div class="kategori-grid">
        <div class="kategori-item">
            <img src="<?= base_url('uploads/icons/elektronik.png') ?>" alt="elektronik">
            <div>Elektronik</div>
        </div>
        <div class="kategori-item">
            <img src="<?= base_url('uploads/icons/pakaianpria.png') ?>" alt="pakaian pria">
            <div>pakaianpria</div>
        </div>
        <div class="kategori-item">
            <img src="<?= base_url('uploads/icons/pakaianwanita.png') ?>" alt="pakaian wanita">
            <div>pakaianwanita</div>

    </div>
</div>



<!-- Konten Utama -->
<div class="container mt-4">
    <div class="row">
        <!-- Sidebar -->
        <!-- Sidebar -->
<div class="col-md-3">
    <!-- Merek Dinamis -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-2">Merek</h5>
        </div>
        <div class="list-group list-group-flush">
            <?php foreach ($merek as $m): ?>
                <a href="<?= site_url('produk/merek/' . $m->id_merek) ?>" class="list-group-item">
                    <?= $m->nama_merek ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Toko Dinamis -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-2">Toko</h5>
        </div>
        <div class="list-group list-group-flush">
            <?php foreach ($toko as $t): ?>
                <a href="<?= site_url('toko/detail/' . $t->id_toko) ?>" class="list-group-item">
                    <?= $t->nama_toko ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>

        

        <!-- Produk -->
        <div class="col-md-9">
            <!-- Produk Terbaru -->
            <h5 class="mb-3">Produk Terbaru</h5>
            <div class="row">
                <?php foreach ($produk_terbaru as $p): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card product-card">
                            <img src="<?= base_url('uploads/pembeli/produk/' . $p->foto) ?>"
                                 class="card-img-top"
                                 style="width: 200px; height: 200px; object-fit: cover; margin: 0 auto;"
                                 alt="<?= $p->nama_produk ?>">
                            <div class="card-body">
                                <h6 class="card-title"><?= $p->nama_produk ?></h6>
                                <p class="text-muted mb-1"><small>Toko: <?= $p->nama_toko ?></small></p>
                                <p class="text-danger">Rp <?= number_format($p->harga, 0, ',', '.') ?></p>
                                <a href="<?= site_url('produk/detail/' . $p->id_produk) ?>" class="btn btn-success btn-sm">Beli</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

           <!-- Produk Terlaris -->
            <h5 class="mt-4 mb-3">Produk Terlaris</h5>
            <div class="row">
                <?php foreach ($produk_terlaris as $p): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card product-card">
                            <img src="<?= base_url('uploads/pembeli/produk/' . $p->foto) ?>"
                                 class="card-img-top"
                                 style="width: 200px; height: 200px; object-fit: cover; margin: 0 auto;"
                                 alt="<?= $p->nama_produk ?>">
                            <div class="card-body">
                                <h6 class="card-title"><?= $p->nama_produk ?></h6>
                                <p class="text-danger">Rp <?= number_format($p->harga, 0, ',', '.') ?></p>
                                <a href="<?= site_url('produk/detail/' . $p->id_produk) ?>" class="btn btn-default btn-sm">Detail</a>
                                <a href="<?= site_url('keranjang/tambah/' . $p->id_produk) ?>" class="btn btn-success btn-sm">Beli</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
