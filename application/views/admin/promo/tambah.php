<div class="container mt-4">
    <h4>Tambah Promo</h4>
    <form action="<?= site_url('promo/simpan') ?>" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Nama Promo</label>
            <input type="text" name="nama_promo" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Poster</label>
            <input type="file" name="poster" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="aktif">Aktif</option>
                <option value="nonaktif">Nonaktif</option>
            </select> 
        </div>
        <div class="mb-3">
            <label>Link</label>
            <input type="text" name="link" class="form-control">
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
    </form>
</div>
