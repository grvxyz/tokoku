<?php $this->load->view('admin/header'); ?>
<?php $this->load->view('admin/menu'); ?>

<div class="card">
    <div class="card-body">
        <h4>Edit Admin</h4>

        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger">
                <?= $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?>

        <form action="<?= site_url('admin1/update/' . $admin->id); ?>" method="post">
            <input type="hidden" name="id" value="<?= $admin->id ?>">

            <div class="mb-3">
                <label for="nama" class="form-label">Nama</label>
                <input type="text" class="form-control" id="nama" name="nama" value="<?= $admin->nama; ?>" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= $admin->email; ?>" required>
            </div>

            <button type="submit" class="btn btn-success">Update</button>
            <a href="<?= site_url('admin1'); ?>" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>

