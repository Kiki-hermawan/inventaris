<?= $this->extend('main/layout') ?>

<?= $this->section('title') ?>
Form Edit Satuan
<?= $this->endSection('title') ?>

<?= $this->section('subtitle') ?>

<?= form_button('', '<i class="fa fa-backward"></i> kembali', [
    'class' => 'btn btn-warning',
    'onclick' => "location.href=('" . site_url('satuan') . "')"
]) ?>

<?= $this->endSection('subtitle') ?>

<?= $this->section('isi') ?>

<?= form_open('/satuan/updatedata', '', [
    'idsatuan' => $id
]) ?>
<div class="form-group">
    <label for="namasatuan">Nama satuan</label>
    <?= form_input('namasatuan', $nama, [
        'class' => 'form-control',
        'id' => 'namasatuan',
        'autofocus' => true,
    ]) ?>

    <?= session()->getFlashdata('errorNamasatuan'); ?>

</div>

<div class="form-group">
    <?= form_submit('', 'Update', [
        'class' => 'btn btn-success',
    ]) ?>
</div>
<?= form_close(); ?>
<?= $this->endSection('isi') ?>