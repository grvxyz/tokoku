<div class="container py-5">
    <h2 class="mb-4">Profil Saya</h2>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
    <?php endif; ?>

    <form action="<?= site_url('profil/update') ?>" method="post">
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Nama Lengkap</label>
                <input type="text" class="form-control" name="nama_pembeli" value="<?= $pembeli->nama ?? '' ?>" required>
            </div>
            <div class="col-md-6">
                <label>No. HP</label>
                <input type="text" class="form-control" name="no_hp" value="<?= $pembeli->nomor_hp ?? '' ?>" required>
            </div>
        </div>

        <div class="mb-3">
            <label>Alamat Lengkap</label>
            <input type="text" class="form-control" name="alamat" value="<?= $pembeli->alamat ?? '' ?>" required>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label>Provinsi</label>
                <select class="form-select" id="provinsi" name="provinsi" required>
                    <option value="">-- Pilih Provinsi --</option>
                    <?php foreach ($provinsi_list as $prov): ?>
                        <option value="<?= $prov->id ?>" data-nama="<?= $prov->name ?>"
                            <?= ($pembeli->provinsi_id ?? '') == $prov->id ? 'selected' : '' ?>>
                            <?= $prov->name ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="hidden" name="provinsi_nama" id="provinsi_nama" value="<?= $pembeli->provinsi ?? '' ?>">
            </div>

            <div class="col-md-6">
                <label>Kota/Kabupaten</label>
                <select class="form-select" id="kota" name="kota" required>
                    <option value="">-- Pilih Kota --</option>
                    <?php foreach ($kota_list as $kota): ?>
                        <option value="<?= $kota->id ?>" data-nama="<?= $kota->name ?>"
                            <?= ($pembeli->kota_id ?? '') == $kota->id ? 'selected' : '' ?>>
                            <?= $kota->name ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="hidden" name="kota_nama" id="kota_nama" value="<?= $pembeli->kota ?? '' ?>">
            </div>

            <div class="col-md-6 mt-3">
                <label>Kecamatan</label>
                <select class="form-select" id="kecamatan" name="kecamatan" required>
                    <option value="">-- Pilih Kecamatan --</option>
                    <?php foreach ($kecamatan_list as $kec): ?>
                        <option value="<?= $kec->id ?>" data-nama="<?= $kec->name ?>"
                            <?= ($pembeli->kecamatan_id ?? '') == $kec->id ? 'selected' : '' ?>>
                            <?= $kec->name ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="hidden" name="kecamatan_nama" id="kecamatan_nama" value="<?= $pembeli->kecamatan ?? '' ?>">
            </div>

            <div class="col-md-6 mt-3">
                <label>Kelurahan</label>
                <select class="form-select" id="kelurahan" name="kelurahan" required>
                    <option value="">-- Pilih Kelurahan --</option>
                    <?php foreach ($kelurahan_list as $kel): ?>
                        <option value="<?= $kel->id ?>" data-nama="<?= $kel->name ?>"
                            <?= ($pembeli->kelurahan_id ?? '') == $kel->id ? 'selected' : '' ?>>
                            <?= $kel->name ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="hidden" name="kelurahan_nama" id="kelurahan_nama" value="<?= $pembeli->kelurahan ?? '' ?>">
            </div>
        </div>

        <div class="mb-3">
            <label>Kode Pos</label>
            <input type="text" class="form-control" name="kode_pos" value="<?= $pembeli->kode_pos ?? '' ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>

<script>
$(function () {
    $('#provinsi').change(function () {
        let nama = $(this).find(':selected').data('nama');
        $('#provinsi_nama').val(nama);
        $('#kota').html('<option>Memuat...</option>');
        $('#kecamatan').html('<option value="">-- Pilih Kecamatan --</option>');
        $('#kelurahan').html('<option value="">-- Pilih Kelurahan --</option>');

        $.getJSON('<?= site_url('profil/get_kota/') ?>' + $(this).val(), function (data) {
            let html = '<option value="">-- Pilih Kota --</option>';
            data.forEach(item => {
                html += `<option value="${item.id}" data-nama="${item.name}">${item.name}</option>`;
            });
            $('#kota').html(html);
        });
    });

    $('#kota').change(function () {
        let nama = $(this).find(':selected').data('nama');
        $('#kota_nama').val(nama);
        $('#kecamatan').html('<option>Memuat...</option>');
        $('#kelurahan').html('<option value="">-- Pilih Kelurahan --</option>');

        $.getJSON('<?= site_url('profil/get_kecamatan/') ?>' + $(this).val(), function (data) {
            let html = '<option value="">-- Pilih Kecamatan --</option>';
            data.forEach(item => {
                html += `<option value="${item.id}" data-nama="${item.name}">${item.name}</option>`;
            });
            $('#kecamatan').html(html);
        });
    });

    $('#kecamatan').change(function () {
        let nama = $(this).find(':selected').data('nama');
        $('#kecamatan_nama').val(nama);
        $('#kelurahan').html('<option>Memuat...</option>');

        $.getJSON('<?= site_url('profil/get_kelurahan/') ?>' + $(this).val(), function (data) {
            let html = '<option value="">-- Pilih Kelurahan --</option>';
            data.forEach(item => {
                html += `<option value="${item.id}" data-nama="${item.name}">${item.name}</option>`;
            });
            $('#kelurahan').html(html);
        });
    });

    $('#kelurahan').change(function () {
        let nama = $(this).find(':selected').data('nama');
        $('#kelurahan_nama').val(nama);
    });
});
</script>
