<div class="card">
    <div class="card-body">
        <h4>Tambah Merek</h4>
        <form action="<?= site_url('merek/store'); ?>" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nama_merek" class="form-label">Nama Merek</label>
                <input type="text" class="form-control" name="nama_merek" required>
            </div>
            <div class="mb-3">
                <label for="gambar" class="form-label">Gambar Merek</label>
                <input type="file" class="form-control" name="gambar">
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="<?= site_url('merek'); ?>" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
