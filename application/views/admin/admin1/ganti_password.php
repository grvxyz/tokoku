<?php $this->load->view('admin/header'); ?>
<?php $this->load->view('admin/menu'); ?>
<div class="card">
    <div class="card-body">
        <h4>Ganti Password Admin</h4>

        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger">
                <?= $this->session->flashdata('error') ?>
            </div>
        <?php endif; ?>

        <form action="<?= site_url('admin1/update_password') ?>" method="post">
            <input type="hidden" name="id" value="<?= $admin->id ?>">

            <div class="mb-3">
                <label for="new_password" class="form-label">Password Baru</label>
                <input type="password" class="form-control" id="new_password" name="new_password" required>
            </div>

            <div class="mb-3">
                <label for="confirm_password" class="form-label">Konfirmasi Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>

            <button type="submit" class="btn btn-success">Ganti Password</button>
            <a href="<?= site_url('admin1'); ?>" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
<?php $this->load->view('admin/footer'); ?>
