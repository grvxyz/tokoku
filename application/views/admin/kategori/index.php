<div class="card">
    <div class="card-body">
        <h4>Manage Kategori</h4>
        <a href="<?= site_url('kategori/create'); ?>" class="btn btn-primary mb-3">Tambah Kategori</a>
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
                    <th>Nama Kategori</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($kategori as $k) : ?>
                <tr>
                    <td><?= $k['id_kategori']; ?></td>
                    <td><?= $k['nama_kategori']; ?></td>
                    <td>
                        <a href="<?= site_url('kategori/edit/'.$k['id_kategori']); ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="<?= site_url('kategori/delete/'.$k['id_kategori']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</div>
