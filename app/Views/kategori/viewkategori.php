<?= $this->extend('main/layout') ?>

<?= $this->section('title') ?>
Data Kategori
<?= $this->endSection('title') ?>

<?= $this->section('subtitle') ?>

<?= form_button('', '<i class="fa fa-plus-circle"></i>Tambah Data', [
    'class' => 'btn btn-primary',
    'onclick' => "location.href=('" . site_url('kategori/formtambah') . "')"
]) ?>
<?= $this->endSection('subtitle') ?>

<?= $this->section('isi') ?>

<?= session()->getFlashdata('sukses'); ?>

<?= form_open('kategori') ?>
<div class="input-group col-3 mb-3">
    <input type="text" class="form-control" placeholder="Cari Data Kategori" aria-label="Recipient's username" aria-describedby="button-addon2" name="cari" value="<?= $cari; ?>">
    <button class="btn btn-outline-primary" type="submit" id="tombolcari" name="tombolcari">
        <i class="fa fa-search"></i>
    </button>
</div>
<?= form_close(); ?>

<table class="table table-striped table-bordered table-sm text-middle" style="width: 100%;">
    <thead>
        <tr>
            <th style="width: 5%;">No</th>
            <th>Nama Kategori</th>
            <th style="width: 15%;">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $nomor = 1 + (($nohalaman - 1) * 5);
        foreach ($tampildata as $row) :
        ?>
            <tr>
                <td><?= $nomor++; ?></td>
                <td><?= $row['katnama'] ?></td>
                <td>
                    <button type="button" class="btn btn-info btn-sm" title="edit data" onclick="edit('<?= $row['katid'] ?>')">
                        <i class="fa fa-edit"></i>
                    </button>

                    <button type="button" class="btn btn-danger btn-sm" title="hapus data" onclick="hapus('<?= $row['katid'] ?>')">
                        <i class="fa fa-trash-alt"></i>
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<span class="badge badge-success mt-2">
    <h6>
        <?= "total data : $totaldata"; ?>
    </h6>
</span>


<div class="float-center py-2">
    <?= $pager->links('kategori', 'paging') ?>
</div>

<script>
    function edit(id) {
        window.location = ('kategori/formedit/' + id);
    }

    function hapus(id) {
        pesan = confirm('yakin ingin dihapus ?');

        if (pesan) {
            window.location = ('kategori/hapus/' + id);
        } else {
            return false;
        }
    }
</script>

<?= $this->endSection('isi') ?>