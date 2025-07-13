<?php $this->load->view('admin/header'); ?>
<?php $this->load->view('admin/menu'); ?>
<div class="card">
    <div class="card-body">
        <h4>Manage Admin</h4>

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
                    <th>Nama</th>
                    <th>Email</th> 
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($admin as $a): ?>
                <tr>
                    <td><?= $a->id; ?></td>
                    <td><?= $a->nama; ?></td>
                    <td><?= $a->email; ?></td>
                    <td>
                        <a href="<?= site_url('admin1/edit/' . $a->id); ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="<?= site_url('admin1/ganti_password/' . $a->id); ?>" class="btn btn-info btn-sm">Ganti Password</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $this->load->view('admin/footer'); ?>