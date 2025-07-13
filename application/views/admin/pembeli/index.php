<div class="container mt-3">
    <h4 class="mb-3">Daftar Pembeli</h4>

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

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($pembeli as $p): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= htmlspecialchars($p['nama'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?= htmlspecialchars($p['email'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td>
                                    <span class="badge bg-<?= ($p['status'] == 'aktif') ? 'success' : 'danger'; ?>">
                                        <?= ucfirst(htmlspecialchars($p['status'], ENT_QUOTES, 'UTF-8')); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?= base_url('index.php/pembeli/update_status/' . $p['id_pembeli'] . '/' . $p['status']); ?>" 
                                       class="btn btn-warning btn-sm">
                                        <?= ($p['status'] == 'aktif') ? 'Nonaktifkan' : 'Aktifkan'; ?>
                                    </a>
                                    <a href="<?= base_url('index.php/pembeli/delete/' . $p['id_pembeli']); ?>" 
                                       class="btn btn-danger btn-sm" 
                                       onclick="return confirm('Yakin ingin menghapus pembeli ini?');">
                                        Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($pembeli)): ?>
                            <tr>
                                <td colspan="5" class="text-center">Belum ada pembeli.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
