<div class="container mt-4">
    <!-- Tombol tambah -->
    <a href="<?= site_url('promo/tambah') ?>" class="btn btn-primary mb-3">Tambah Promo</a>

    <!-- Flash message -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
    <?php endif; ?>

    <!-- Form filter -->
    <form class="row mb-3" method="get">
        <div class="col-md-3">
            <select name="status" class="form-control" onchange="this.form.submit()">
                <option value="">-- Semua Status --</option>
                <option value="aktif" <?= $status == 'aktif' ? 'selected' : '' ?>>Aktif</option>
                <option value="nonaktif" <?= $status == 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
            </select>
        </div>
    </form>

    <!-- Tabel -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th><th>Nama Promo</th><th>Poster</th><th>Status</th><th>Link</th><th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($promo as $i => $row): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= $row->nama_promo ?></td>
                    <td><img src="<?= base_url('uploads/promo/' . $row->poster) ?>" width="100"></td>
                    <td><?= $row->status ?></td>
                    <td><?= $row->link ?></td>
                    <td>
                        <a href="<?= site_url('promo/edit/' . $row->id_promo) ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="<?= site_url('promo/hapus/' . $row->id_promo) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin?')">Hapus</a>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="mt-3">
        <?= $this->pagination->create_links(); ?>
    </div>
</div>
