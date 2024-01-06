<?= $this->extend('main/layout') ?>

<?= $this->section('title') ?>
Data Barang
<?= $this->endSection('title') ?>

<?= $this->section('subtitle') ?>

<button type="button" class="btn btn-primary" onclick="location.href=('barang/tambah')">
    <i class="fa fa-plus-circle"></i> Tambah Barang
</button>

<?= $this->endSection('subtitle') ?>

<?= $this->section('isi') ?>

<?= form_open('barang') ?>
<div class="input-group col-3 mb-3">
    <input type="text" class="form-control" placeholder="Cari Data Kode, Nama Barang & Kategori" aria-label="Recipient's username" aria-describedby="button-addon2" name="cari" value="<?= $cari; ?>" autofocus>
    <button class="btn btn-outline-primary" type="submit" name="tombolcari">
        <i class="fa fa-search"></i>
    </button>
</div>
<?= form_close(); ?>
<?= session()->getFlashdata('sukses'); ?>
<table class="table table-striped table-bordered table-sm text-middle" style="width: 100%;">
    <thead>
        <tr>
            <th style="width: 5%;">No</th>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Kategori</th>
            <th>Satuan</th>
            <th>Harga</th>
            <th>Stok</th>
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
                <td><?= $row['brgkode'] ?></td>
                <td><?= $row['brgnama'] ?></td>
                <td><?= $row['katnama'] ?></td>
                <td><?= $row['satnama'] ?></td>
                <td><?= number_format($row['brgharga'], 0) ?></td>
                <td><?= number_format($row['brgstok'], 0) ?></td>
                <td>
                    <button type="button" class="btn btn-info btn-sm" onclick="edit('<?= $row['brgkode'] ?>')">
                        <i class="fa fa-edit"></i>
                    </button>

                    <button type="button" class="btn btn-danger btn-sm" title="hapus data" onclick="hapus('<?= $row['brgkode'] ?>')">
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
    <?= $pager->links('barang', 'paging') ?>
</div>

<script>
    function edit(kode) {
        window.location.href = ('/barang/edit/' + kode);
    }

    function hapus(id) {
        pesan = confirm('yakin ingin dihapus ?');
        if (pesan) {
            window.location = ('barang/hapus/' + id);
        } else {
            return false;
        }
    }
</script>

<?= $this->endSection('isi') ?>