<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5" style="max-width: 600px;">
    <h4>Edit Profil Pembeli</h4>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
    <?php endif; ?>

    <?php if (isset($pembeli)): ?>
        <form method="post" action="<?= site_url('home/act_profile') ?>">
            <div class="mb-3">
                <label>Nama</label>
                <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($pembeli->nama) ?>" required>
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($pembeli->email) ?>" required>
            </div>
            <div class="mb-3">
                <label>Alamat</label>
                <textarea name="alamat" class="form-control"><?= htmlspecialchars($pembeli->alamat) ?></textarea>
            </div>
            <div class="mb-3">
                <label>No HP</label>
                <input type="text" name="nomor_hp" class="form-control" value="<?= htmlspecialchars($pembeli->nomor_hp) ?>">
            </div>
            <div class="mb-3">
                <label>Password Baru (kosongkan jika tidak ingin ubah)</label>
                <input type="password" name="password" class="form-control">
            </div>
            <button type="submit" class="btn btn-success">Simpan Perubahan</button>
        </form>
    <?php else: ?>
        <div class="alert alert-danger">Data pengguna tidak ditemukan.</div>
    <?php endif; ?>
</div>
</body>
</html>
