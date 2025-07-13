<div class="card">
    <div class="card-body">
        <h4>Manage Merek</h4>
        <a href="<?= site_url('merek/create'); ?>" class="btn btn-primary mb-3">Tambah Merek</a>

        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success">
                <?= $this->session->flashdata('success'); ?>
            </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger">
                <?= $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Merek</th>
                    <th>Gambar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($merek as $m) : ?>
                <tr>
                    <td><?= $m['id_merek']; ?></td>
                    <td><?= $m['nama_merek']; ?></td>
                    <td><img src="<?= base_url('uploads/merek/' . $m['gambar']); ?>" width="100"></td>
                    <td>
                        <a href="<?= site_url('merek/edit/'.$m['id_merek']); ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="<?= site_url('merek/delete/'.$m['id_merek']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
