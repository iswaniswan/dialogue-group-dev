<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/approve'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main');" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-3">Bagian Pembuat</label>
                        <label class="col-sm-3">Nomor Dokumen</label>
                        <label class="col-md-3">Tanggal Dokumen</label>
                        <label class="col-md-3">Pengirim</label>
                        <div class="col-sm-3">
                            <input type="text" readonly="" class="form-control input-sm" name="ibagian" id="ibagian" value="<?= $data->e_bagian_name; ?>">
                            <input type="hidden" name="ibagianold" id="ibagianold" value="<?= $data->i_bagian; ?>">
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" value="<?= $id; ?>">
                                <input type="hidden" name="idocumentold" id="isjold" value="<?= $data->i_document; ?>">
                                <input type="text" name="idocument" id="isj" required="" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number; ?>" maxlength="15" class="form-control input-sm" value="<?= $data->i_document; ?>" aria-label="Text input with dropdown button">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="ddocument" name="ddocument" class="form-control input-sm date" required="" value="<?= $data->d_document; ?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" readonly="" class="form-control input-sm" name="ibagian" id="ibagian" value="<?= $data->e_pengirim_name; ?>">
                            <input type="hidden" name="ibagianold" id="ibagianold" value="<?= $data->i_pengirim; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Nomor Referensi</label>
                        <label class="col-md-8">Keterangan</label>
                        <div class="col-sm-4" id="eks">
                            <input type="hidden" id="ireffeks" name="ireffeks" value="<?= $data->id_reff; ?>">
                            <input type="text" id="ireff" name="ireff" class="form-control input-sm date" required="" value="<?= $data->i_document_reff . ' | ' . $data->d_document_reff . ' | ' . $data->e_jenis_name; ?>" readonly>
                        </div>
                        <div class="col-sm-8">
                            <textarea type="text" name="eremarkh" placeholder="Isi keterangan jika ada!!!" class="form-control input-sm" maxlength="250"><?= $data->e_remark; ?></textarea>
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
<?php $i = 0;
if ($datadetail) { ?>
    <div class="white-box" id="detail">
        <div class="col-sm-3">
            <h3 class="box-title m-b-0">Detail Barang</h3>
        </div>
        <div class="col-sm-12">
            <div class="table-responsive">
                <table id="tabledatax" class="table color-table inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center align-middle" width="3%;">No</th>
                            <th>Nama Panel</th>
                            <th>Warna</th>
                            <th class="text-right">Qty Penyusun</th>
                            <th class="text-right">Qty Kirim</th>
                            <th class="text-right">Qty Terima</th>
                            <th class="text-right">Qty BS</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $group = '';
                        foreach ($datadetail as $key) {
                            $i++;
                            if ($group != $key->id_product_wip) { ?>
                                <tr class="table-active">
                                    <td class="text-center"><i class="fa fa-check-square-o fa-lg text-success" aria-hidden="true"></i></td>
                                    <td class="text-left"><?= $key->i_product_wip . ' - ' . $key->e_product_wipname; ?></td>
                                    <td><?= $key->e_color_name; ?></td>
                                    <td colspan="5"></td>
                                </tr>
                                <tr class="table-active">
                                    <td class="text-center"><i class="fa fa-dot-circle-o fa-lg text-info" aria-hidden="true"></i></td>
                                    <td colspan="7" class="text-left"><?= $key->i_material . ' - ' . $key->e_material_name; ?></td>
                                </tr>
                            <?php $i=1;}
                            $group = $key->id_product_wip;
                            ?>
                            <tr>
                                <td class="text-center">
                                    <spanx id="snum<?= $i; ?>"><?= $i; ?></spanx>
                                </td>
                                <td>
                                    <?= $key->i_panel . ' - ' . $key->bagian; ?>
                                </td>
                                <td>
                                    <?= $key->e_color_name; ?>
                                </td>
                                <td>
                                    <?= $key->n_quantity_penyusun; ?>
                                </td>
                                <td>
                                    <?= $key->keluarfull; ?>
                                </td>
                                <td>
                                    <?= $key->masuk; ?>
                                </td>
                                <td>
                                    <?= $key->keluarfull - $key->masuk; ?>
                                </td>
                                <td>
                                    <?= $key->e_remark; ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">
<?php } ?>
</form>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        var $table = $('#tabledatax');

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
                // fixedNumber: 2,
                // fixedRightNumber: 1
            })
        }

        $(function() {
            buildTable($table)
        })
    });


    function konfirm() {
        var jml = $('#jml').val();
        var qty = 0;
        var sisa = 0;
        for (i = 1; i <= jml; i++) {

            qty = qty + parseFloat($('#nquantity' + i).val());
            sisa = sisa + parseFloat($('#sisa' + i).val());


        }
        if (qty > sisa) {
            swal('Jumlah Terima Melebihi Jumlah Keluar');
            return false;
        } else {
            //statuschange('<?= $folder . "','" . $data->id; ?>','6','<?= $dfrom . "','" . $dto; ?>');
            return true;
        }
    }

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#cr").attr("disabled", true);
        $("#rj").attr("disabled", true);
    });
</script>