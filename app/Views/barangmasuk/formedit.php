<?= $this->extend('main/layout') ?>

<?= $this->section('title') ?>
Data Transaksi Barang Masuk
<?= $this->endSection('title') ?>

<?= $this->section('subtitle') ?>

<button type="button" class="btn btn-sm btn-primary" onclick="location.href=('/barangmasuk/data')">
    <i class="fa fa-backward"></i> Kembali
</button>

<?= $this->endSection('subtitle') ?>

<?= $this->section('isi') ?>
<table class="table table-striped table-bordered table-sm table-hover" style="width: 100%;">
    <tr>
        <td style="width: 20%;">No.Faktur</td>
        <td style="width: 2%;">:</td>
        <td style="width: 28%;"><?= $nofaktur ?></td>
        <td rowspan="3" style="vertical-align: middle; text-align: center; font-weight: bold; font-size: 25pt;" id="totalHarga"></td>
        <input type="hidden" id="faktur" value="<?= $nofaktur ?>">
    </tr>

    <tr>
        <td style="width: 20%;">TanggalFaktur</td>
        <td style="width: 2%;">:</td>
        <td style="width: 28%;"><?= date('d-m-Y', strtotime($tanggal)) ?></td>
    </tr>
</table>
<div class="card">
    <div class="card-header bg-primary">
        Input Barang
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="form-group col-md-3">
                <label for="">Kode Barang</label>
                <div class="input-group mb-2">
                    <input type="text" class="form-control" placeholder="Kode Barang" name="kdbarang" id="kdbarang">
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="button" id="tombolCariBarang">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
                <input type="hidden" name="iddetail" id="iddetail">
            </div>
            <div class="form-group col-md-3">
                <label for="">Nama Barang</label>
                <input type="text" class="form-control" name="namabarang" id="namabarang" readonly>
            </div>

            <div class="form-group col-md-2">
                <label for="">Harga Jual</label>
                <input type="text" class="form-control" name="hargajual" id="hargajual" readonly>
            </div>

            <div class="form-group col-md-2">
                <label for="">Harga Beli</label>
                <input type="number" class="form-control" name="hargabeli" id="hargabeli">
            </div>

            <div class="form-group col-md-1">
                <label for="">Jumlah</label>
                <input type="number" class="form-control" name="jumlah" id="jumlah">
            </div>

            <div class="form-group col-md-1">
                <label for="">Aksi</label>
                <div class="input-group">
                    <button type="button" class="btn btn-sm btn-info" title="Tambah Item" id="tombolTambahItem">
                        <i class="fa fa-plus-square"></i>
                    </button>

                    <button style="display: none;" type="button" class="btn btn-sm btn-primary" title="Edit Item" id="tombolEditItem">
                        <i class="fa fa-edit"></i>
                    </button>
                    &nbsp;
                    <button style="display: none;" type="button" class="btn btn-sm btn-secondary" title="Reload" id="tombolReload">
                        <i class="fa fa-sync-alt"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="row" id="tampilDataDetail"></div>
    </div>
</div>
<div class="modalcaribarang" style="display: none;"></div>

<script>
    function dataDetail() {
        let faktur = $('#faktur').val();

        $.ajax({
            type: "post",
            url: "/barangmasuk/dataDetail",
            data: {
                faktur: faktur
            },
            dataType: "json",
            success: function(response) {
                if (response.data) {
                    $('#tampilDataDetail').html(response.data);
                    $('#totalHarga').html(response.totalharga);
                }

            },
            errors: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + '\n' + thrownError);
            }
        });
    }

    function kosong() {
        $('#kdbarang').val('');
        $('#namabarang').val('');
        $('#hargajual').val('');
        $('#hargabeli').val('');
        $('#jumlah').val('');
        $('#kdbarang').focus();
    }

    function ambilDataBarang() {
        let kdbarang = $('#kdbarang').val();

        $.ajax({
            type: "post",
            url: "/barangmasuk/ambilDataBarang",
            data: {
                kdbarang: kdbarang
            },
            dataType: "json",
            success: function(response) {
                if (response.sukses) {
                    let data = response.sukses;
                    $('#namabarang').val(data.namabarang);
                    $('#hargajual').val(data.hargajual);
                    $('#hargabeli').focus();

                }

                if (response.error) {
                    alert(response.error);
                    kosong();
                }
            },
            errors: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + '\n' + thrownError);
            }
        });
    }

    $(document).ready(function() {
        dataDetail();

        $('#tombolReload').click(function(e) {
            e.preventDefault();
            $('#iddetail').val('');
            $(this).hide();
            $('#tombolEditItem').hide();
            $('#tombolTambahItem').fadeIn();

            kosong();
        });

        $('#tombolTambahItem').click(function(e) {
            e.preventDefault();
            let faktur = $('#faktur').val();
            let kdbarang = $('#kdbarang').val();
            let hargabeli = $('#hargabeli').val();
            let hargajual = $('#hargajual').val();
            let jumlah = $('#jumlah').val();

            if (faktur.length == 0) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Maaf, Faktur Harus diisi",
                });
            } else if (kdbarang.length == 0) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Maaf, KodeBarang Harus diisi",
                });
            } else if (hargabeli.length == 0) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Maaf, Harga Beli Harus diisi",
                });
            } else if (jumlah.length == 0) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Maaf,Jumlah Harus diisi",
                });
            } else {
                $.ajax({
                    type: "post",
                    url: "/barangmasuk/simpanDetail",
                    data: {
                        faktur: faktur,
                        kdbarang: kdbarang,
                        hargabeli: hargabeli,
                        hargajual: hargajual,
                        jumlah: jumlah

                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.sukses) {
                            alert(response.sukses);
                            kosong();
                            dataDetail();
                        }
                    },
                    errors: function(xhr, ajaxOptions, thrownError) {
                        alert(xhr.status + '\n' + thrownError);
                    }
                });
            }
        });

        $('#tombolEditItem').click(function(e) {
            e.preventDefault();
            let faktur = $('#faktur').val();
            let kdbarang = $('#kdbarang').val();
            let hargabeli = $('#hargabeli').val();
            let hargajual = $('#hargajual').val();
            let jumlah = $('#jumlah').val();
            
            $.ajax({
                type: "post",
                url: "/barangmasuk/updateItem",
                data: {
                    'iddetail': $('#iddetail').val(),
                    faktur: faktur,
                    kdbarang: kdbarang,
                    hargabeli: hargabeli,
                    hargajual: hargajual,
                    jumlah: jumlah
                },
                dataType: "json",
                success: function(response) {
                    if (response.sukses) {
                        alert(response.sukses);
                        kosong();
                        dataDetail();
                    }
                },
                errors: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + '\n' + thrownError);
                }
            });

        });

        $('#tombolCariBarang').click(function(e) {
            e.preventDefault();
            $.ajax({
                type: "post",
                url: "/barangmasuk/cariDataBarang",
                data: {

                },
                dataType: "json",
                success: function(response) {
                    if (response.data) {
                        $('.modalcaribarang').html(response.data).show();
                        $('#modalcaribarang').modal('show');
                    }
                },
                errors: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + '\n' + thrownError);
                }
            });

        });
    });
</script>
<?= $this->endSection('isi') ?>