<style type="text/css">
    .tableFixHead {
        white-space: nowrap !important;
    }
</style>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Pembuat Dokumen</label>
                        <label class="col-md-2">Nomor Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-5">Keterangan</label>
                        <div class="col-sm-3">
                            <input type="text" name="e_bagian_name" id="e_bagian_name" class="form-control input-sm" value="<?= $datahead->e_bagian_name; ?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="hidden" name="id" id="id" class="form-control input-sm" value="<?= $datahead->id; ?>" readonly="">
                            <input type="text" name="idocument" id="idocument" class="form-control input-sm" value="<?= $datahead->i_document; ?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="ddocument" id="ddocument" class="form-control input-sm" value="<?= $datahead->d_document; ?>" readonly>
                        </div>
                        <div class="col-sm-5">
                            <textarea name="eremarkh" id="eremarkh" class="form-control input-sm" readonly><?= $datahead->e_remark; ?></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="white-box" id="detail">
    <!-- <div class="col-sm-5">
        <h3 class="box-title m-b-0">Detail Barang</h3>
    </div> -->
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatay" class="table color-table tableFixHead inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" width="3%;">No</th>
                        <th width="20%">Asal</th>
                        <th width="15%">Kode Barang</th>
                        <th width="25%">Nama Barang</th>
                        <th width="5%">Warna</th>
                        <th class="text-right" width="5%;">Jumlah SO</th>
                        <th width="auto">Keterangan</th>
                    </tr>
                    <tr>
                        <th colspan="5" class="text-center">Total</th>
                        <th class="text-right" id="total">
                            <?php $total = 0;
                            foreach ($datadetail as $rowtotal) {
                                $total += (float) $rowtotal['n_quantity'];
                            }
                            echo $total; ?>
                        </th>
                        <th>&nbsp; &nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 0;
                    foreach ($datadetail as $key) {
                        $i++; ?>
                        <tr>
                            <td class="text-center">
                                <spanx id="snum<?= $i; ?>"><?= $i; ?></spanx>
                            </td>
                            <td><?= $key['name']; ?></td>
                            <td><?= $key['i_product_wip']; ?></td>
                            <td><?= $key['e_product_wipname']; ?></td>
                            <td><?= $key['e_color_name']; ?></td>
                            <td class="text-right"><?= $key["n_quantity"]; ?></td>
                            <td><?= $key["e_remark"]; ?></td>
                            <!-- <td class="col-1"><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td> -->
                        </tr>
                    <?php } ?>
                    <input style="width:50px" type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                </tbody>
            </table>
        </div>
    </div>
</div>
</form>
<script>
    $(document).ready(function() {
        // fixedtable($('.table'));
        var $table = $('#tabledatay');

        function buildTable(elm) {
            elm.bootstrapTable('destroy').bootstrapTable({
                height: 400,
                // columns          : columns,
                // data             : data,
                search: true,
                showColumns: true,
                // showToggle       : true,
                // clickToSelect    : true,
                fixedColumns: true,
                fixedNumber: 3,
                // fixedRightNumber: 1
            })
        }

        $(function() {
            // buildTable($table)
        })
    });

    function approve() {
        statuschange('<?= $folder . "','" . $id; ?>', '6', '<?= $dfrom . "','" . $dto; ?>');
    }
</script>