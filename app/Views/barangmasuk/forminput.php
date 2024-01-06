<?= $this->extend('main/layout') ?>

<?= $this->section('title') ?>
Input Barang Masuk
<?= $this->endSection('title') ?>

<?= $this->section('subtitle') ?>

<button type="button" class="btn btn-warning" onclick="location.href=('/barangmasuk/data')">
    <i class="fa fa-backward"></i> Kembali
</button>

<?= $this->endSection('subtitle') ?>

<?= $this->section('isi') ?>

<div class="form-row">
    <div class="form-group col-md-6">
        <label for="">Input Faktur Barang Masuk</label>
        <input type="text" class="form-control" placeholder="No.Faktur" name="faktur" id="faktur">
    </div>
    <div class="form-group col-md-6">
        <label for="">Tanggal Faktur</label>
        <input type="date" class="form-control" name="tglfaktur" id="tglfaktur" value="<?= date('Y-m-d') ?>">
    </div>
</div>

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
                    </button>&nbsp;
                    <button type="button" class="btn btn-sm btn-warning" title="Reload Data" id="tombolReload">
                        <i class="fa fa-sync-alt"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="row" id="tampilDataTemp"></div>
        <div class="row justify-content-end">
            <button type="button" class="btn btn-lg btn-success btn-sm" id="tombolSelesaiTransaksi">
                <i class="fa fa-save"></i> Selesai Transaksi
            </button>
        </div>
    </div>
</div>
<div class="modalcaribarang" style="display: none;"></div>
<script>
    function dataTemp() {
        let faktur = $('#faktur').val();

        $.ajax({
            type: "post",
            url: "/barangmasuk/dataTemp",
            data: {
                faktur: faktur
            },
            dataType: "json",
            success: function(response) {
                if (response.data) {
                    $('#tampilDataTemp').html(response.data);
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
        dataTemp();

        $('#kdbarang').keydown(function(e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                ambilDataBarang();
            }
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
                    url: "/barangmasuk/simpanTemp",
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
                            dataTemp();
                        }
                    },
                    errors: function(xhr, ajaxOptions, thrownError) {
                        alert(xhr.status + '\n' + thrownError);
                    }
                });
            }
        });
        $('#tombolReload').click(function(e) {
            e.preventDefault();
            dataTemp();

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

    $('#tombolSelesaiTransaksi').click(function(e) {
        e.preventDefault();
        let faktur = $('#faktur').val();

        if (faktur.length == 0) {
            Swal.fire({
                icon: "pesan",
                title: "Warning",
                text: "Maaf, Faktur Harus diisi",
            });
        } else {
            Swal.fire({
                title: "Selesai Transksi",
                text: "Yakin transaksi ini disimpan?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, simpan"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "post",
                        url: "/barangmasuk/selesaiTransaksi",
                        data: {
                            faktur: faktur,
                            tglfaktur: $('#tglfaktur').val()
                        },
                        dataType: "json",
                        success: function(response) {
                            if (response.error) {
                                Swal.fire({
                                    icon: "error",
                                    title: "Oops...",
                                    text: response.error
                                });
                            }

                            if (response.sukses) {
                                Swal.fire({
                                    icon: "sukses",
                                    title: "success",
                                    text: response.sukses
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.reload();
                                    }
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
    });
</script>
<?= $this->endSection('isi') ?>