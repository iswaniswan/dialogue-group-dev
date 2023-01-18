<style>
    .nowrap {
        white-space: nowrap !important;
    }

    .warna {
        padding-left: 3px;
        padding-right: 3px;
        font-size: 14px;
        background-color: #ddd;
        font-weight: bold;
    }
</style>

<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom; ?>/<?= $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-4">Bagian Pembuat</label>
                        <label class="col-md-4">Nomor Dokumen</label>
                        <label class="col-md-4">Tanggal Dokumen</label>
                        <div class="col-sm-4">
                            <select name="ibagian" id="ibagian" class="form-control select2" required="" disabled>
                                <option value="<?= $data->i_bagian; ?>"><?= $data->e_bagian_name; ?></option>
                            </select>
                            <input type="hidden" id="id" name="id" class="form-control" value="<?= $data->id; ?>">
                            <input type="hidden" id="idocumentold" name="idocumentold" class="form-control" value="<?= $data->i_document; ?>">
                        </div>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" name="idocument" id="idocument" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="SC-2010-000001" maxlength="15" class="form-control input-sm" value="<?= $data->i_document; ?>" aria-label="Text input with dropdown button" disabled>
                                <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id="ddocument" name="ddocument" class="form-control input-sm" value="<?= $data->d_document; ?>" placeholder="<?= date('d-m-Y'); ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">

                        <label class="col-md-12">Keterangan</label>

        
                        <div class="col-sm-4">
                            <textarea id="eremark" name="eremark" class="form-control" placeholder="Isi keterangan jika ada!" readonly><?= $data->e_remark; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                            <button type="button" class="btn btn-warning btn-rounded btn-sm" onclick="statuschange('<?= $folder . "','" . $data->id; ?>','3','<?= $dfrom . "','" . $dto; ?>');"> <i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;Change Requested</button>&nbsp;
                            <button type="button" class="btn btn-danger btn-rounded btn-sm" onclick="statuschange('<?= $folder . "','" . $data->id; ?>','4','<?= $dfrom . "','" . $dto; ?>');"> <i class="fa fa-times"></i>&nbsp;&nbsp;Reject</button>&nbsp;
                            <button type="button" id="approve" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Approve</button>&nbsp;
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
    <div class="white-box" id="detail">
        <div class="col-sm-5">
            <h3 class="box-title m-b-0">Detail Barang</h3>
        </div>
        <div class="col-sm-12">
            <div class="table-responsive">
                <table id="tabledatax" class="table color-table nowrap inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                    <thead>
                        <tr>
                        <tr>
                            <th class="text-center" width="10%">Tanggal Pengerjaan</th>
                            <th class="text-center" width="20%">Nama Barang</th>
                            <th class="text-center" >Progress</th>
                            <th class="text-center" >FC Cutting</th>
                            <!-- <th class="text-center" >FC Produksi</th>
                            <th class="text-center" >Kondisi Stock Persiapan Cutting</th> -->
                            <th class="text-center" >Urutan</th>
                            <th class="text-center" >Keterangan</th>
                        </tr>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 0;
                        foreach($detail as $key){
                            $i++;
                        ?>
                        <tr>
                            <td><?= date("d-m-Y", strtotime($key->d_schedule));?></td>
                            <td><?= $key->i_product_wip.'-'.$key->e_product_wipname.'-'.$key->e_color_name;?></td>
                            <td><?= $key->e_progress;?></td>
                            <td><?= $key->n_fc_cutting;?></td>
                            <!-- <td><?= $key->n_fc_perhitungan;?></td>
                            <td><?= $key->n_kondisi_stock;?></td> -->
                            <td class="text-center"><spanx id="snum<?= $i ;?>"><?= $i ;?></spanx></td>
                            <td><?= $key->e_remark;?></td>
                        </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">
    </form>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
    $('#approve').click(function(event) {
        statuschange('<?= $folder; ?>', $('#id').val(), '6', '<?= $dfrom . "','" . $dto; ?>');
    });

    $(document).ready(function() {
        $('.select2').select2();
        showCalendar('.date', 1830, 0);

    });

    

    $('#send').click(function(event) {
        statuschange('<?= $folder; ?>', $('#id').val(), '2', '<?= $dfrom . "','" . $dto; ?>');
    });
    $('#cancel').click(function(event) {
        statuschange('<?= $folder; ?>', $('#id').val(), '1', '<?= $dfrom . "','" . $dto; ?>');
    });
    $('#hapus').click(function(event) {
        statuschange('<?= $folder; ?>', $('#id').val(), '5', '<?= $dfrom . "','" . $dto; ?>');
    });

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        // $("select").attr("disabled", true);
        $("#addrow").attr("disabled", true);
        $("#submit").attr("disabled", true);
    });

</script>