<table class="table table_striped table-sm table-hover">
    <thead>
        <th>No</th>
        <th>Kode Barang</th>
        <th>Nama Barang</th>
        <th>Harga Jual</th>
        <th>Harga Beli</th>
        <th>Jumlah</th>
        <th>Sub Total</th>
        <th>#</th>
    </thead>
    <tbody>
        <?php
        $nomor = 1;
        foreach ($datatemp->getResultArray() as $row) :
        ?>
            <tr>
                <td><?= $nomor++; ?></td>
                <td><?= $row['detbrgkode'] ?></td>
                <td><?= $row['brgnama'] ?></td>
                <td style="text-align: right;">     
                    <?= number_format($row['dethargajual'], 0, ",", ".") ?>
                </td>
                <td style="text-align: right;">
                    <?= number_format($row['dethargamasuk'], 0, ",", ".") ?>
                </td>
                <td style="text-align: right;">
                    <?= number_format($row['detjml'], 0, ",", ".") ?>
                </td>
                <td style="text-align: right;">
                    <?= number_format($row['detsubtotal'], 0, ",", ".") ?>
                </td>
                <td>
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="hapusItem('<?= $row['iddetail'] ?>')">
                        <i class="fa fa-trash-alt"></i>
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
    function hapusItem(id) {
        Swal.fire({
            title: "Anda Yakin?",
            text: "Apakah anda ingin menghapus",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, hapus"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "post",
                    url: "/barangmasuk/hapus",
                    data: {
                        id: id
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.sukses) {
                            dataTemp();
                            Swal.fire({
                                icon: "success",
                                title: "Berhasil",
                                text: response.sukses,
                            });
                        }
                    },
                    errors: function(xhr, ajaxOptions, thrownError) {
                        alert(xhr.status + '\n' + thrownError);
                    }
                });
            }
        });

    }
</script>