<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-4">No Bon Keluar</label>
                        <label class="col-md-4">Gudang</label>
                        <label class="col-md-4">Tanggal</label>
                        <div class="col-sm-4">
                            <input type="text" id="ibonk" name="ibonk" class="form-control date" value="<?= $data->i_bonk; ?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <select name="ikodemaster" id="ikodemaster" class="form-control select2" disabled="">
                                <option value="" selected>-- Pilih Gudang --</option>
                                <?php foreach ($kodemaster as $ikodemaster):?>
                                <?php if ($ikodemaster->i_kode_master == $data->i_kode_master) { ?>
                                    <option value="<?php echo $ikodemaster->i_kode_master;?>" selected><?= $ikodemaster->e_nama_master;?></option>
                                    <?php }else { ?>
                                    <option value="<?php echo $ikodemaster->i_kode_master;?>"><?= $ikodemaster->e_nama_master;?></option>
                                    <?php }?>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" id="istore" name="istore" class="form-control" value="<?= $data->i_kode_master; ?>">
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id="dbonk" name="dbonk" class="form-control date" value="<?= $data->d_bonk; ?>" readonly>
                        </div>
                    </div>    
                    <div class="form-group row">
                        <label class="col-md-8">No Permintaan</label>
                        <label class="col-md-4">Tanggal Permintaan</label>
                        <div class="col-sm-8">
                            <input type="text" id="imemo" name="imemo" class="form-control date" value="<?= $data->i_bon_permintaan; ?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id="dmemo" name="dmemo" class="form-control" value="<?= $data->d_bon_permintaan; ?>" readonly>
                        </div>
                    </div>                
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                        <input type="text" id= "eremark "name="eremark" class="form-control" maxlength="30" value="<?= $data->e_remark; ?>">
                        </div>
                    </div>   
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                        <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return cek();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>  
                        <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>           
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-12">Departement</label>
                        <div class="col-sm-6">
                            <select required="" id="idepartement" name="idepartement" class="form-control select2">
                                <option value="">Pilih Departement</option>
                                <?php foreach ($departemen as $idepartement):?>
                                <option value="<?php echo $idepartement->i_sub_bagian;?>"
                                <?php if($idepartement->i_sub_bagian==$data->i_departemen) { ?> selected="selected" <?php } ?>>
                                <?php echo $idepartement->e_sub_bagian;?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>       
                    <input type="hidden" name="jml" id="jml" value ="0">
                </div>
                    <div class="table-responsive">
                        <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Barang</th>
                                    <th width="40%">Nama Barang</th>
                                    <th>Qty Outstanding</th>
                                    <th>Qty Keluar</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?$i = 0;
                                foreach ($detail as $row) {
                                $i++;?>
                                <tr>
                                <td style="text-align: center;"><?=$i;?>
                                    <input type="hidden" class="form-control" readonly id="baris<?=$i;?>" name="baris<?=$i;?>" value="<?=$i;?>">
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:150px" type="text" class="form-control" id="imaterial<?=$i;?>" name="imaterial<?=$i;?>"value="<?=$row->i_kode_barang;?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:445px" type="text" class="form-control" id="ematerialname<?=$i;?>" name="ematerialname<?=$i;?>" value="<?=$row->e_material_name;?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:80px" type="text" class="form-control" id="nqtyout<?=$i;?>" name="nqtyout<?=$i;?>"value="<?= number_format($row->n_quantity_permintaan,0);?>" readonly>
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:80px"type="text" class="form-control" id="nqtykeluar<?=$i;?>" name="nqtykeluar<?=$i;?>"value="<?= number_format($row->n_quantity_keluar,0);?>" >
                                <td class="col-sm-1">
                                    <input style ="width:300px"type="text" class="form-control" id="edesc<?=$i;?>" name="edesc<?=$i;?>"value="<?=$row->e_remark;?>" >
                                </td>
                                </tr>
                                <?}?>
                            </tbody>
                        </table>
                        <input style ="width:50px" type="hidden" name="jml" id="jml" value="<?=$i;?>">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
$("form").submit(function(event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
});

$(document).ready(function () {
    $('.select2').select2();
    showCalendar('.date');
});

function cek() {
    var dbonk = $('#dbonk').val();
    var imemo = $('#imemo').val();
    var istore = $('#istore').val();

    if (dbonk == '' || imemo == null || istore == '') {
        swal('Data Header Belum Lengkap !!');
        return false;
    } else {
        return true;
    }
}
</script>