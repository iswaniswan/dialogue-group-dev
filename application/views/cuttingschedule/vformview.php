<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-3">Bagian Pembuat</label>
                        <label class="col-sm-2">Nomor Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-5">Keterangan</label>
                        <div class="col-sm-3">
                            <input type="text" readonly="" name="ebagianname" id="ebagianname" class="form-control input-sm" value="<?= $data->e_bagian_name;?>">
                        </div>
                        <div class="col-sm-2">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" value="<?= $data->id;?>">
                                <input type="hidden" name="ischeduleold" id="ischeduleold" value="<?= $data->i_document;?>">
                                <input type="text" name="ischedule" id="ischedule" readonly="" autocomplete="off" onkeyup="gede(this);" maxlength="15" class="form-control input-sm" value="<?= $data->i_document;?>" aria-label="Text input with dropdown button">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="ddocument" name="ddocument" class="form-control input-sm date" maxlength="5"  value="<?= date('d-m-Y', strtotime($data->d_document));?>" readonly>
                        </div>
                        <div class="col-sm-5">
                            <textarea type="text" name="eremarkh" readonly="" class="form-control input-sm" maxlength="250"><?= $data->e_remark;?></textarea>
                        </div>
                    </div>  
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $i = 0; if ($datadetail) {?>
    <div class="white-box">
        <div class="col-sm-12">
            <h3 class="box-title m-b-0">Detail Barang</h3>
            <div class="table-responsive">
                <table id="tabledatax" class="table color-table inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 3%;">No</th>
                            <th class="text-center" style="width: 10%;">Tgl Schedule</th>
                            <th class="text-center" style="width: 45%;">Nama Barang</th>
                            <th class="text-center" style="width: 10%;">Qty</th>
                            <th class="text-center">Ket</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($datadetail as $key) {
                            $i++;
                            ?>
                            <tr>
                                <td class="text-center"><?= $i ;?></td>
                                <td class="text-center"><?= date('d-m-Y', strtotime($key->d_schedule));?></td>
                                <td><?= $key->i_product_wip.' - '.$key->e_product_wipname.' - '.$key->e_color_name;?></td>
                                <td class="text-right"><?= $key->n_quantity;?></td>
                                <td><?= $key->e_remark;?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <input type="hidden" name="jml" id="jml" value ="<?= $i ;?>">
<?php } ?>
<script>
    $(document).ready(function () {
        $('.select2').select2();
    });
</script>