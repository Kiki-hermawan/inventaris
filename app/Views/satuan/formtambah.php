<?= $this->extend('main/layout') ?>

<?= $this->section('title') ?>
Form Tambah Satuan
<?= $this->endSection('title') ?>

<?= $this->section('subtitle') ?>

<?= form_button('', '<i class="fa fa-backward"></i> kembali', [
    'class' => 'btn btn-warning',
    'onclick' => "location.href=('" . site_url('satuan') . "')"
]) ?>

<?= $this->endSection('subtitle') ?>

<?= $this->section('isi') ?>

<?= form_open('satuan/simpandata') ?>
<div class="form-group">
    <label for="namasatuan">Nama satuan</label>
    <?= form_input('namasatuan', '', [
        'class' => 'form-control',
        'id' => 'namasatuan',
        'autofocus' => true,
        'placholder' => 'Masukan Nama satuan'
    ]) ?>

    <?= session()->getFlashdata('errorNamasatuan'); ?>

</div>

<div class="form-group">
    <?= form_submit('', 'Simpan', [
        'class' => 'btn btn-success',
    ]) ?>
</div>
<?= form_close(); ?>
<?= $this->endSection('isi') ?>