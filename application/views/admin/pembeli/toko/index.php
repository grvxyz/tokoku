<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Marketplace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f5f5f5; }
        .navbar { background-color: #00a96e; }
        .navbar-brand, .nav-link { color: #fff !important; font-weight: 600; }
        .search-bar { max-width: 500px; width: 100%; }
        .btn-search { background-color: #02885a; color: #fff; }
        .sidebar { background-color: #fff; padding: 15px; border-radius: 8px; }
        .sidebar a { color: #333; text-decoration: none; display: block; padding: 8px; border-radius: 5px; }
        .sidebar a:hover { background-color: #f0f0f0; }
        .product-card { transition: transform 0.3s; }
        .product-card:hover { transform: scale(1.05); }
        .footer { background-color: #343a40; color: #fff; padding: 20px 0; text-align: center; }

        .login-box, .register-box {
            max-width: 500px;
            margin: 60px auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
        }

        .btn-green {
            background-color: #00a96e;
            color: #fff;
        }
        .btn-green:hover {
            background-color: #02885a;
        }
    </style>
</head>
<body>

<!-- Header / Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="<?= site_url('home'); ?>">Marketplace</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="<?= site_url('home'); ?>">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#">About</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Cara Beli</a></li>

                <?php if ($this->session->userdata('id_pembeli')): ?>
                    <li class="nav-item"><a class="nav-link" href="<?= site_url('toko') ?>"> Toko</a></li>
                <?php endif; ?>
            </ul>

            <!-- Search Bar -->
            <form class="d-flex search-bar">
                <select class="form-select me-2">
                    <option value="">Kategori</option>
                    <option value="elektronik">Elektronik</option>
                    <option value="fashion">Fashion</option>
                </select>
                <input class="form-control" type="search" placeholder="Cari produk...">
                <button class="btn btn-search ms-2" type="submit">Cari</button>
            </form>

            <!-- Right-side nav -->
            <ul class="navbar-nav ms-3">
                <?php if ($this->session->userdata('logged_in_pembeli')): ?>
                    <li class="nav-item"><a class="nav-link" href="#">🛒 Cart</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= site_url('home/edit_profile') ?>">👤 Edit Profile</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= site_url('home/logout') ?>">🚪 Logout</a></li>
                <?php elseif (current_url() == site_url('home/register')): ?>
                    <li class="nav-item"><a class="nav-link" href="<?= site_url('home/login') ?>">🔑 Login</a></li>
                <?php elseif (current_url() == site_url('home/login')): ?>
                    <li class="nav-item"><a class="nav-link" href="<?= site_url('home/register') ?>">🔑 Register</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="#">🛒 Cart</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= site_url('home/login') ?>">🔑 Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= site_url('home/register') ?>">🔑 Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- ... Bagian awal tetap sama ... -->

<!-- Konten Utama -->
<div class="container mt-4">
    <h4>Daftar Toko Anda</h4>
    <a href="<?= site_url('toko/tambah') ?>" class="btn btn-primary mb-2">+ Tambah Toko</a>

    <?php if (isset($toko) && !empty($toko)): ?>
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th style="width: 60px;">Logo</th>
                    <th>Nama Toko</th>
                    <th>Deskripsi</th>
                    <th style="width: 160px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($toko as $row): ?>
                <tr>
                    <td>
                        <img src="<?= base_url('uploads/pembeli/toko/' . htmlspecialchars($row->logo_toko ?? 'default_logo.png')) ?>"
                             width="50" height="50" alt="Logo Toko"
                             onerror="this.onerror=null;this.src='<?= base_url('uploads/pembeli/toko/default_logo.png') ?>';">
                    </td>
                    <td><?= htmlspecialchars($row->nama_toko ?? '-') ?></td>
                    <td><?= htmlspecialchars($row->deskripsi ?? '-') ?></td>
                    <td>
                        <a href="<?= site_url('toko/edit/' . $row->id_toko) ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="<?= site_url('toko/hapus/' . $row->id_toko) ?>" class="btn btn-danger btn-sm"
                           onclick="return confirm('Yakin ingin menghapus toko ini?')">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">Belum ada toko. <a href="<?= site_url('toko/tambah') ?>" class="alert-link">Buat toko baru sekarang</a>.</div>
    <?php endif; ?>
</div>

<!-- Footer -->
<div class="footer mt-4">
    <p>&copy; 2025 Marketplace. All Rights Reserved.</p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
