<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom.'/'.$dto ;?>','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            
            <div class="panel-body table-responsive">
            <div id="pesan"></div>  
                <input readonly type = "hidden" name = "dfrom" id = "dfrom" value = "<?= $dfrom ;?>">
                <input readonly type = "hidden" name = "dto" id = "dto" value = "<?= $dto ;?>">
                <div class="col-md-6">             
                    <div class="form-group row">
                        <label class="col-md-12">No Bon Keluar</label>
                        <div class="col-sm-5">
                            <input type="text" id="ibonk" name="ibonk" class="form-control"  value="<?= $data->i_bonk ;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label id = "dep" class="col-md-5">Gudang Pembuat</label><label class="col-md-3">Tgl Bon Keluar</label>
                        <div class="col-sm-5">
                            <select name="idepartement" id="idepartement" class="form-control" disabled>
                                <?php if ($gudang) {
                                    foreach ($gudang->result() as $key) { ?>
                                        <option value="<?= trim($key->i_departement);?>"<?php if ($key->i_departement==$data->i_sub_bagian) {
                                            echo "selected";
                                        }?>><?= $key->e_departement_name;?></option> 
                                    <?php }
                                } ?> 
                            </select>
                        </div>
                        <div class="col-sm-3">
                                <input type="text" id="dbonk" name="dbonk" class="form-control date"  value="<?= date('d-m-Y',strtotime($data->d_bonk)) ;?>" readonly>
                        </div>
                        <div class="col-sm-8"></div>
                    </div>  

                    <div class="form-group row">
                        <label class="col-md-12">Gudang Tujuan</label>
                        <div class="col-sm-5">
                            <select name="itujuan" id="itujuan" class="form-control" disabled>
                                <?php if ($ngadug) {
                                    foreach ($ngadug->result() as $key) { ?>
                                        <option value="<?= trim($key->i_departement);?>"<?php if ($key->i_departement==$data->i_sub_bagian) {
                                            echo "selected";
                                        }?>><?= $key->e_departement_name;?></option> 
                                    <?php }
                                } ?> 
                            </select>
                        </div>
                        <div class="col-sm-8"></div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-5">No Schedule</label><label class="col-md-7">Tgl Schedule</label>
                        <div class="col-sm-5">
                            <input readonly name="ischedule" id="ischedule" class="form-control" value = "<?= $data->i_schedule ;?>">
                        </div> 
                        <div class="col-sm-3">
                            <input readonly type="text" name="dschedule" id="dschedule" class="form-control" value="<?= date('d-m-Y',strtotime($data->d_schedule)) ;?>" readonly>
                        </div>
                    </div>
                
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-7">
                            <?php if ($data->i_status == '1' || $data->i_status == '3' || $data->i_status == '7') {?>
                                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return konfirm();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Update</button>&nbsp;
                            <?php } ?>
                                <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"><i class="ti-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                            <?php if ($data->i_status == '1'/*  || $data->i_status == '3' || $data->i_status == '7' */) {?>
                                <button type="button" id="send" onclick="statuschange('<?= $folder;?>',$('#ibonk').val(),'2','<?= $dfrom."','".$dto;?>');" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                            <?php }elseif($data->i_status=='2') {?>
                                <button type="button" class="btn btn-primary btn-rounded btn-sm" onclick="statuschange('<?= $folder."','".$data->i_bonk;?>','7','<?= $dfrom."','".$dto;?>');"> <i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Cancel</button>&nbsp;&nbsp;
                            <?php } ?>
                        </div>               
                    </div>
                </div>
            </div> <!-- Panel body -->
        </div>
    </div>
</div>
<?php 
    $counter = 0; 
    if ($datadetail) { ?>
<div class="white-box" id="detail">
    <h3 class="box-title m-b-0">Detail Barang</h3>
    <div class="table-responsive">
    <table id="tabledata" class="table color-table inverse-table table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th style = "text-align : center">No</th>
                <th style = "text-align : center">Kode Material</th>
                <th style = "text-align : center">Nama Material</th>
                <th style = "text-align : center">Qty Set</th>
                <th style = "text-align : center">Kirim (Lembar)</th>
                <th style = "text-align : center">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $group = "";
                foreach ($datadetail as $row) {
                    $product2 = $row->i_product.$row->i_color_wip;

                    if($group==""){
                        echo '<tr class = "bg-success text-white"><td colspan = "11" style="font-size:16px;"><b>'.$row->i_product.' ('.$row->e_product_name.') - '.$row->e_color_name.' ( '.$row->n_quantity_product.' ) </b></td></tr>'; //<input readonly style="width:60px;" class="form-control" type="text" id="nquantity"'.$counter.' name="nquantity" value="'.$row->n_quantity. onkeyup = hitungnilai3(this.value,'.$product2.')
                    }else{
                        if(($group!=$row->i_product)){
                            echo '<tr class = "bg-success text-white"><td colspan = "11" style="font-size:16px;"><b>'.$row->i_product.' ('.$row->e_product_name.') - '.$row->e_color_name.' ( '.$row->n_quantity_product.' ) </b></td></tr>'; //<input readonly style="width:60px;" class="form-control" type="text" id="nquantity"'.$counter.' name="nquantity" value="'.$row->n_quantity. onkeyup = hitungnilai3(this.value,'.$product2.')
                        }
                    }
                    $counter++;?>

            <?php 
                $group = $row->i_product;
            ?>
                    <tr>
                        <td class="text-center">
                            <?= $counter;?>
                        </td>

                        <td hidden>
                            <input readonly value="<?= $row->i_product;?>" type="text" id="iproduct<?= $counter;?>" class="form-control" name="iproduct<?= $counter;?>" style = "width:100px;">
                        </td>

                        <td hidden>
                            <input readonly value="<?= $product2;?>" type="text" id="iproductcolor<?= $counter;?>" class="form-control" name="iproductcolor<?=$counter;?>">
                        </td>

                        <td hidden>
                            <input readonly value="<?= $row->e_product_name;?>" type="text" id="eproductname<?= $counter;?>" class="form-control" name="eproductname<?=$counter;?>" style = "width:375px;">
                        </td>

                        <td hidden>
                            <input readonly value="<?= $row->i_color_wip;?>" type="hidden" id="warna<?= $counter;?>" class="form-control" name="warna<?=$counter;?>">
                            <input readonly value="<?= $row->e_color_name;?>" type="text" id="ecolor<?= $counter;?>" class="form-control" name="ecolor<?=$counter;?>" style = "width:85px;">
                        </td>

                        <td>
                            <input readonly value="<?= $row->i_material;?>" type="text" id="imaterial<?= $counter;?>" class="form-control" name="imaterial<?= $counter;?>">
                        </td>

                        <td>
                            <input readonly value="<?= $row->e_material_name;?>" type="text" id="ematerial<?= $counter;?>" class="form-control" name="ematerial<?= $counter;?>" style = "width:375px;">
                        </td>
            <!-- ------------------------------------------------------------------------------------------ -->
                        <td hidden>
                            <input readonly value="<?= $row->n_quantity_product;?>" type="text" id="nquantitytmp<?= $counter;?>" class="form-control text-right" name="nquantitytmp<?= $counter;?>" style = "width:85px;">
                        </td>

                        <td>
                            <input readonly value="<?= $row->n_quantity_material;?>" type="text" id="nmaterial<?= $counter;?>" class="form-control text-right" name="nmaterial<?=$counter;?>" style = "width:85px;">
                        </td>

                        <td>
                            <input value="<?= $row->n_material_sisa;?>" type="text" id="ndeliver<?= $counter;?>" class="form-control text-right" name="ndeliver<?=$counter;?>" style = "width:85px;" onkeyup = "cekjml()">
                        </td>

                        <td>
                            <input class = "form-control" name = "eremark<?= $counter;?>" id = "eremark<?= $counter;?>" value = "<?= $row->e_remark ;?>">
                        </td>
                    </tr>
                <?php } ?>
                <input type = "hidden" id = "jml" name = "jml" value = "<?= $counter ;?>">
        </tbody>
    </table>
    </div>
</div>
<?php } ?>
</form>
<script>
$("form").submit(function (event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
});

$(document).ready(function () {
  $('.select2').select2();
  showCalendar('.date');
});

function konfirm() {
    if($('#dbonk').val()=='' || $('#dbonk').val()==null){ 
        swal('Isi tanggal bon keluar !');
        return false;
    }else if($('#ischedule').val()=='' || $('#ischedule').val()==null){
        swal('Pilih Schedule !!!');
        return false;
    }
}

function cekjml(){
    for(i=1;i<=$('#jml').val();i++){
        document.getElementById("ndeliver"+i).value = document.getElementById("ndeliver"+i).value.replace(/[^\d.-]/g,'');
        ndeliver    = document.getElementById("ndeliver"+i).value;
        nmaterial        = document.getElementById("nmaterial"+i).value;

        if(parseInt(ndeliver) > parseInt(nmaterial)){
            swal ('Jumlah Kirim melebihi Schedule !');
            document.getElementById("ndeliver"+i).value = 0;
            return false;
        }
    }
}
</script>