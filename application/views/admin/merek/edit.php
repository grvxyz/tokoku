<div class="card">
    <div class="card-body">
        <h4>Edit Merek</h4>
        <form action="<?= site_url('merek/update/'.$merek['id_merek']); ?>" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nama_merek" class="form-label">Nama Merek</label>
                <input type="text" class="form-control" name="nama_merek" value="<?= $merek['nama_merek']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="gambar" class="form-label">Gambar Merek</label>
                <input type="file" class="form-control" name="gambar">
                <?php if (!empty($merek['gambar'])): ?>
    <p>Gambar saat ini:</p>\;''
    <img src="<?= base_url('uploads/merek/' . $merek['gambar']) ?>" width="100">
<?php endif; ?> 

            </div>
            <button type="submit" class="btn btn-success">Update</button>
            <a href="<?= site_url('merek'); ?>" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
