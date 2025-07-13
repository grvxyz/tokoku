<div class="container mt-4">
    <h4>Edit Promo</h4>
    <form action="<?= site_url('promo/update/'.$promo->id_promo) ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="old_poster" value="<?= $promo->poster ?>">
        <div class="mb-3">
            <label>Nama Promo</label>
            <input type="text" name="nama_promo" class="form-control" value="<?= $promo->nama_promo ?>">
        </div>
        <div class="mb-3">
            <label>Poster Baru (biarkan kosong jika tidak ganti)</label>
            <input type="file" name="poster" class="form-control">
        </div>
        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="aktif" <?= $promo->status == 'aktif' ? 'selected' : '' ?>>Aktif</option>
                <option value="nonaktif" <?= $promo->status == 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Link</label>
            <input type="text" name="link" class="form-control" value="<?= $promo->link ?>">
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
