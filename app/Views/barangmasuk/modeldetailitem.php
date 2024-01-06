<!-- Modal -->
<div class="modal fade" id="modalitem" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Detail Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-sm table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Harga Masuk</th>
                            <th>Harga Jual</th>
                            <th>Jumlah</th>
                            <th>Sub Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $nomor = 1;
                        foreach ($tampildatadetail->getResultArray() as $row) :
                        ?>
                            <tr>
                                <td><?= $nomor++; ?></td>
                                <td><?= $row['detbrgkode']; ?></td>
                                <td><?= $row['brgnama']; ?></td>
                                <td style="text-align: right;"><?= number_format($row['dethargamasuk'], 0, ",", ".") ?></td>
                                <td style="text-align: right;"><?= number_format($row['dethargajual'], 0, ",", ".") ?></td>
                                <td style="text-align: center;"><?= number_format($row['detjml'], 0, ",", ".") ?></td>
                                <td style="text-align: right;"><?= number_format($row['detsubtotal'], 0, ",", ".") ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="row viewdetaildata"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- <script>
    function cariDataBarang() {
        let cari = $('#cari').val();
        $.ajax({
            type: "post",
            url: "/barangmasuk/detailCariBarang",
            data: {
                cari: cari
            },
            dataType: "json",
            beforeSend: function() {
                $('.viewdetaildata').html('<i class="fa fa-spin fa-spinner"></i>');
            },
            success: function(response) {
                if (response.data) {
                    $('.viewdetaildata').html(response.data);
                }
            },
            errors: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + '\n' + thrownError);
            }
        });
    }

    $(document).ready(function() {
        $('#btnCari').click(function(e) {
            e.preventDefault();
            cariDataBarang();
        });
        $('#cari').keydown(function (e) { 
            if (e.keyCode == '13') {
                e.preventDefault();
                cariDataBarang();
            }
        });
    });
</script> -->