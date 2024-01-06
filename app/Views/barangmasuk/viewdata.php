<?= $this->extend('main/layout') ?>

<?= $this->section('title') ?>
Data Transaksi Barang Masuk
<?= $this->endSection('title') ?>

<?= $this->section('subtitle') ?>

<button type="button" class="btn btn-sm btn-primary" onclick="location.href=('/barangmasuk')">
    <i class="fa fa-plus-circle"></i> Input Transaksi
</button>

<?= $this->endSection('subtitle') ?>

<?= $this->section('isi') ?>

<?= form_open('barangmasuk/data') ?>
<div class="input-group mb-2">
    <input type="text" class="form-control" placeholder="Cari Berdasarkan Faktur" name="cari" value="<?= $cari; ?>" autofocus="true">
    <div class="input-group-append">
        <button class="btn btn-outline-primary" type="submit" name="tombolcari">
            <i class="fa fa-search"></i>
        </button>
    </div>
</div>
<?= form_close(); ?>
<table class="table table-striped table-bordered table-sm table-hover">
    <thead>
        <tr>
            <th>No</th>
            <th>faktur</th>
            <th>Tanggal</th>
            <th>Jumlah Item</th>
            <th>Total Harga (Rp)</th>
            <th>#</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $nomor = 1 + (($nohalaman - 1) * 5);
        foreach ($tampildata as $row) :
        ?>
            <tr>
                <td><?= $nomor++; ?></td>
                <td><?= $row['faktur']; ?></td>
                <td><?= date('d-m-Y', strtotime($row['tglfaktur'])); ?></td>
                <td align="center">
                    <?php
                    $db = \Config\Database::connect();

                    $jumlahItem = $db->table('detail_barangmasuk')->where('detfaktur', $row['faktur'])->countAllResults();
                    ?>
                    <span style="cursor: pointer; font-weight: bold; color: blue;" onclick="detailItem('<?= $row['faktur'] ?>')"><?= $jumlahItem; ?></span>
                </td>
                <td>
                    <?= number_format($row['totalharga'], 0, ",", ".") ?>
                </td>
                <td>
                    <button type="button" class="btn btn-outline-info btn-sm " title="Edit Transaksi" onclick="edit('<?= sha1($row['faktur']) ?>')">
                        <i class="fa fa-edit"></i>
                    </button>
                    &nbsp;
                    <button type="button" class="btn btn-outline-danger btn-sm " title="Hapus Transaksi" onclick="hapusTransaksi('<?= $row['faktur'] ?>')">
                        <i class="fa fa-trash-alt"></i>
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<div class="viewmodal" style="display: none;"></div>
<span class="badge badge-success mt-2">
    <h6>
        <?= "total data : $totaldata"; ?>
    </h6>
</span>

<div class="float-center py-2">
    <?= $pager->links('barangmasuk', 'paging') ?>
</div>

<script>
    function hapusTransaksi(faktur) {
        Swal.fire({
            title: "Hapus Transaksi",
            text: "Apakah anda ingin menghapus transaksi ini",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, hapus"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "post",
                    url: "/barangmasuk/hapusTransaksi",
                    data: {
                        faktur: faktur
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.sukses) {
                            Swal.fire({
                                title: "Berhasil",
                                icon: "success",
                                text: response.sukses,
                            }).then((result) => {
                                window.location.reload();
                            })
                        }
                    },
                    errors: function(xhr, ajaxOptions, thrownError) {
                        alert(xhr.status + '\n' + thrownError);
                    }
                });
            }
        });

    }

    function edit(faktur) {
        window.location.href = ('/barangmasuk/edit/') + faktur;
    }

    function detailItem(faktur) {
        // alert(faktur);
        $.ajax({
            type: "post",
            url: "/barangmasuk/dataItem",
            data: {
                faktur: faktur
            },
            dataType: "json",
            success: function(response) {
                if (response.data) {
                    $('.viewmodal').html(response.data).show();
                    $('#modalitem').modal('show');
                }
            },
            errors: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + '\n' + thrownError);
            }
        });
    }
</script>
<?= $this->endSection('isi') ?>