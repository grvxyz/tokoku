<div class="card">
    <div class="card-body">
        <h4>Edit Kategori</h4>
        <form action="<?= site_url('kategori/update/'.$kategori['id_kategori']); ?>" method="post">
            <div class="mb-3">
                <label for="nama_kategori" class="form-label">Nama Kategori</label>
                <input type="text" class="form-control" id="nama_kategori" name="nama_kategori" value="<?= $kategori['nama_kategori']; ?>" required>
            </div>
            <button type="submit" class="btn btn-success">Update</button>
            <a href="<?= site_url('kategori'); ?>" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
