<?= $this->extend('main/layout') ?>

<?= $this->section('title') ?>
Form Tambah Kategori
<?= $this->endSection('title') ?>

<?= $this->section('subtitle') ?>

<?= form_button('', '<i class="fa fa-backward"></i> kembali', [
    'class' => 'btn btn-warning',
    'onclick' => "location.href=('" . site_url('kategori') . "')"
]) ?>

<?= $this->endSection('subtitle') ?>

<?= $this->section('isi') ?>

<?= form_open('kategori/simpandata') ?>
<div class="form-group">
    <label for="namakategori">Nama Kategori</label>
    <?= form_input('namakategori', '', [
        'class' => 'form-control',
        'id' => 'namakategori',
        'autofocus' => true,
        'placholder' => 'Masukan Nama Kategori'
    ]) ?>

    <?= session()->getFlashdata('errorNamaKategori'); ?>

</div>

<div class="form-group">
    <?= form_submit('', 'Simpan', [
        'class' => 'btn btn-success',
    ]) ?>
</div>
<?= form_close(); ?>
<?= $this->endSection('isi') ?>