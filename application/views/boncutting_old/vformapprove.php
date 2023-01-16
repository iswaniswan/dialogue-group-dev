<style>
    .font-11{
    padding-left: 3px;
    padding-right: 3px;
    font-size: 11px;  
    height: 20px;  
}
.font-12{
    padding-left: 3px;
    padding-right: 3px;
    font-size: 12px;    
}
</style>
<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i>  <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i><?=$title_list;?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-sm-12">
                    <div class="form-group row">
                        <label class="col-sm-3">Bagian Pembuat</label>
                        <label class="col-sm-3">Nomor Dokumen</label>
                        <label class="col-sm-2">Tanggal Dokumen</label>
                        <label class="col-sm-4">Keterangan</label>
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2" required="" disabled>
                                <?php if ($bagian) {
                                    foreach ($bagian as $row):?>
                                        <option value="<?= $row->i_bagian;?>" <?php if ($row->i_bagian==$data->i_bagian) {?> selected <?php } ?>>
                                            <?= $row->e_bagian_name;?>
                                        </option>
                                    <?php endforeach; 
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" value="<?= $data->id;?>">
                                <input type="hidden" name="istb_cuttingold" id="istb_cuttingold" value="<?= $data->i_document;?>" >
                                <input type="text" name="istb_cutting" id="istb_cutting" readonly="" autocomplete="off" onkeyup="gede(this);" maxlength="15" class="form-control input-sm" value="<?= $data->i_document;?>" aria-label="Text input with dropdown button">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="dstb_cutting" name="dstb_cutting" class="form-control input-sm" required="" readonly value="<?= date('d-m-Y', strtotime($data->d_document)); ?>">
                        </div>
                        <div class="col-sm-4">
                             <textarea class="form-control input-sm" name="remark" placeholder="Isi keterangan jika ada!" readonly><?= $data->e_remark;?></textarea> 
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm mr-2" onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i class="fa fa-arrow-circle-left mr-2"></i>Kembali</button>
                            <button type="button" class="btn btn-warning btn-rounded btn-sm mr-2" onclick="statuschange('<?= $folder."','".$data->id;?>','3','<?= $dfrom."','".$dto;?>');"> <i class="fa fa-pencil-square-o mr-2"></i>Change Requested</button>
                            <button type="button" class="btn btn-danger btn-rounded btn-sm mr-2"  onclick="statuschange('<?= $folder."','".$data->id;?>','4','<?= $dfrom."','".$dto;?>');"> <i class="fa fa-times mr-2"></i>Reject</button>
                            <button type="button" class="btn btn-success btn-rounded btn-sm mr-2" onclick="statuschange('<?= $folder."','".$data->id;?>','6','<?= $dfrom."','".$dto;?>');"> <i class="fa fa-check-square-o mr-2"></i>Approve</button>
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
        <div class="m-b-0">
            <div class="form-group row">
               <!-- <div class="col-sm-1">
                    <button type="button" id="addrow" class="btn btn-info btn-sm"><i class="fa fa-plus"></i>Item</button>
                </div> -->
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table font-12 inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 3%;">No</th>
                        <th>Kode Material</th>
                        <th>Nama Material</th>
                        <th>Kode WIP</th>
                        <th>Nama WIP</th>
                        <th>Tgl Schedule</th>
                        <th class="text-right">Jml Lembar</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $i = 0;
                    if ($detail) {
                        foreach ($detail as $row) {
                            $i++;
                            ?>
                            <tr>
                                <td style="text-align: center;"><spanx id="snum<?= $i; ?>"><?= $i; ?></spanx></td>
                                <td><?= $row->i_material;?><input type="hidden" value="<?= $row->id_reff; ?>" name="idscheduleitem<?= $i; ?>" id="idscheduleitem<?= $i; ?>"></td>
                                <td><?= $row->e_material_name;?></td>
                                <td><?= $row->i_product_wip;?></td>
                                <td><?= $row->e_product_wipname;?></td>
                                <td><?= $row->d_schedule; ?></td>
                                <td class="text-right"><?= $row->n_quantity; ?></td>
                                <td><?= $row->e_remark; ?></td>
                            </tr>
                        <?php } 
                    }?>
                    <input type="hidden" name="jml" id="jml" value ="<?= $i;?>">
                </tbody>
            </table>
        </div>
    </div>
</div>
</from>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date');
    });
    
</script>