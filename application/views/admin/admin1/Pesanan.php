<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            margin-top: 40px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            font-weight: 600;
            margin-bottom: 25px;
        }

        .table th, .table td {
            vertical-align: middle;
        }

        .form-select {
            min-width: 120px;
        }

        .alert {
            margin-top: 15px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2><i class="bi bi-box-seam"></i> Daftar Pesanan</h2>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
    <?php elseif ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Pembeli</th>
                    <th>Total Harga</th>
                    <th>Metode Pembayaran</th>
                    <th>Kurir</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($pesanan as $p): ?>
                <tr>
                    <td><?= $p->id_pesanan ?></td>
                    <td><?= $p->nama_pembeli ?? '<em>Tidak diketahui</em>' ?></td>
                    <td><strong>Rp<?= number_format($p->total_harga, 0, ',', '.') ?></strong></td>
                    <td><?= $p->metode_pembayaran ?? '-' ?></td>
                    <td><?= $p->kurir ?? '-' ?></td>
                    <td>
                        <span class="badge bg-<?=
                            match($p->status) {
                                'pending' => 'warning',
                                'diproses' => 'info',
                                'dikirim' => 'primary',
                                'selesai' => 'success',
                                'batal' => 'danger',
                                default => 'secondary'
                            }
                        ?>"><?= ucfirst($p->status) ?></span>
                    </td>
                    <td><?= $p->tanggal_pembelian ?></td>
                    <td>
                        <form method="post" action="<?= site_url('admin1/ubah_status_pesanan/' . $p->id_pesanan) ?>">
                            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                <?php
                                $status_options = ['pending', 'diproses', 'dikirim', 'selesai', 'batal'];
                                foreach ($status_options as $status) {
                                    $selected = $status === $p->status ? 'selected' : '';
                                    echo "<option value=\"$status\" $selected>$status</option>";
                                }
                                ?>
                            </select>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
