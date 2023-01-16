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
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?=$title_list;?></a>
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
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
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
                    <button type="button" id="addrow" class="btn btn-info btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Item</button>
                </div> -->
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table font-11 success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 3%;">No</th>
                        <th class="text-center" style="width: 35%;">Barang</th>
                        <th class="text-center" style="width: 10%;">Satuan</th>
                        <th class="text-center" style="width: 10%;">Tgl Schedule</th>
                        <th class="text-center" style="width: 10%;">Jml Permintaan</th>
                        <th class="text-center" style="width: 10%;">Jml Kirim</th>
                        <th class="text-center">Keterangan</th>
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
                                <td>
                                    <select data-urut="<?= $i; ?>" id="idscheduleitem<?= $i; ?>" class="form-control input-sm" name="idscheduleitem[]" disabled>
                                        <option value="<?= $row->id_schedule_item; ?>"><?= $row->i_material.' - '.$row->e_material_name.' - Tgl : '.$row->d_schedule.''; ?></option>
                                    </select>
                                </td>
                                <td><input type="text" disabled readonly id="esatuan<?= $i; ?>" class="form-control input-sm" name="esatuan[]" value="<?= $row->e_satuan_name; ?>"></td>
                                <td><input type="text" disabled readonly id="d_schedule<?= $i; ?>" class="form-control input-sm" name="d_schedule[]" value="<?= $row->d_schedule; ?>"></td>
                                <td><input type="text" disabled id="nquantity<?= $i; ?>" class="form-control text-right input-sm inputitem" autocomplete="off" readonly name="nquantity[]" value="<?= $row->n_quantity_material_schedule; ?>" onkeyup="angkahungkul(this);"></td>
                                <td><input type="text" disabled id="nquantity_kirim<?= $i; ?>" class="form-control text-right input-sm inputitem" autocomplete="off" name="nquantity_kirim[]" onblur='if(this.value==""){this.value="0";}' onfocus='if(this.value=="0"){this.value="";}' value="<?= $row->n_quantity_material; ?>" onkeyup="angkahungkul(this);cek(<?= $i; ?>)"></td>
                                <td><input type="text" disabled id="eremark<?= $i; ?>" class="form-control input-sm" name="eremark[]" value="<?= $row->e_remark; ?>" /></td>
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

        for (var i = 0; i <= $('#jml').val(); i++) {
            $('#idscheduleitem'+ i).select2({
                placeholder: 'Cari Kode / Nama Material / Tgl Schedule',
                allowClear: true,
                width: '100%',
                type: "POST",
                ajax: {
                    url: '<?= base_url($folder.'/cform/material/'); ?>',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        var query   = {
                            q          : params.term,
                            ibagian    : $('#ibagian').val(),
                        }
                        return query;
                    },
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                }
            }).change(function(event) {
                let id = $(this).data('urut');
                $.ajax({
                    type: "post",
                    data: {
                        'idscheduleitem': $(this).val(),
                    },
                    url: '<?= base_url($folder.'/cform/getmaterial'); ?>',
                    dataType: "json",
                    success: function (data) {
                        ada = false;
                        for(var i = 1; i <=$('#jml').val(); i++){
                            if(($('#idscheduleitem'+id).val() == $('#idscheduleitem'+i).val()) && (i!=id)){
                                swal ("kode sudah ada !!!!!");
                                ada = true;
                                break;
                            }else{
                                ada = false;     
                            }
                        }

                        if(!ada){
                            $('#d_schedule'+id).val(data[0]['d_schedule']);
                            $('#esatuan'+id).val(data[0]['e_satuan_name']);
                            $('#nquantity'+id).val(data[0]['n_quantity']);
                            $('#nquantity_kirim'+id).val(data[0]['n_quantity']);
                            $('#nquantity_kirim'+id).focus();
                        }else{
                            $('#idscheduleitem'+id).html('');
                            $('#idscheduleitem'+id).val('');
                            $('#d_schedule'+id).val('');
                            $('#esatuan'+id).val('');
                            $('#nquantity'+id).val('');
                            $('#nquantity_kirim'+id).val('');
                        }
                    },
                    error: function () {
                        swal('Ada kesalahan :(');
                    }
                });
            });   
        }
    });
    
</script>