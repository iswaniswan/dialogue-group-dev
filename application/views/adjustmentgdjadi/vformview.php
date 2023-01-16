<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-4">Bagian Pembuat</label>
                        <label class="col-md-4">Nomor Dokumen</label>
                        <label class="col-md-4">Tanggal Dokumen</label>
                        <div class="col-md-4">
                            <select name="ibagian" id="ibagian" class="form-control select2" required="" disabled="">
                                <?php if ($bagian) {
                                    foreach ($bagian as $row):?>
                                         <option value="<?= $row->i_bagian;?>" <?php if ($row->i_bagian == $data->i_bagian) {?> selected <?php } ?>>
                                            <?= $row->e_bagian_name;?>
                                        </option>
                                    <?php endforeach; 
                                } ?>
                            </select>
                             <input type="hidden" name="ibagianold" id="ibagianold" value="<?= $data->i_bagian;?>">
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" value="<?= $id;?>">
                                <input type="hidden" name="idocumentold" id="idocumentold" value="<?= $data->i_adjustment;?>">
                                <input type="text" name="idocument" id="idocument" readonly="" autocomplete="off" onkeyup="gede(this);"  maxlength="25" class="form-control input-sm" value="<?= $data->i_adjustment;?>" disabled="">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <input type="text" id="ddocument" name="ddocument" class="form-control input-sm date" required="" disabled="" value="<?= $data->d_adjustment;?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-md-10">
                            <textarea id="eremarkh" name="eremarkh" class="form-control" disabled=""><?= $data->e_remark;?></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="white-box" id="detail">
    <div class="col-sm-3">
        <h3 class="box-title m-b-0">Detail Barang</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 3%;">No</th>
                        <th class="text-center" style="width: 10%;">Kode</th>
                        <th class="text-center" style="width: 45%;">Nama Barang</th>
                        <th class="text-center" style="width: 10%;">Qty</th>
                        <th class="text-center">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                <?$i = 0;
                    if ($datadetail) {
                        foreach ($datadetail as $row) {
                            $i++;?>
                            <tr>
                                <td class="text-center"><?= $i; ?></td>
                                <td><?= $row->i_product_base; ?></td>
                                <td><?= $row->e_product_basename; ?></td>
                                <td class="text-right"><?= $row->n_quantity; ?></td>
                                <td><?= $row->e_remark; ?></td>
                            </tr>
                        <?php } 
                    }?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('.select2').select2();
    });
</script>