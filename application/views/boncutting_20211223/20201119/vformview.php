<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
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
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom.'/'.$dto ;?>','#main'); return false;"><i class="ti-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;&nbsp;
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
                            <input readonly value="<?= $row->n_quantity;?>" type="text" id="nquantitytmp<?= $counter;?>" class="form-control text-right" name="nquantitytmp<?= $counter;?>" style = "width:85px;">
                        </td>

                        <td>
                            <input readonly value="<?= $row->n_quantity_material;?>" type="text" id="nmaterial<?= $counter;?>" class="form-control text-right" name="nmaterial<?=$counter;?>" style = "width:85px;">
                        </td>

                        <td>
                            <input readonly value="<?= $row->n_material_sisa;?>" type="text" id="ndeliver<?= $counter;?>" class="form-control text-right" name="ndeliver<?=$counter;?>" style = "width:85px;">
                        </td>

                        <td>
                            <input disabled class = "form-control" name = "eremark<?= $counter;?>" id = "eremark<?= $counter;?>" value = "<?= $row->e_remark ;?>">
                            <!-- <textarea disabled class = "form-control" name = "eremark<?= $counter;?>" id = "eremark<?= $counter;?>"><?= $row->e_remark ;?></textarea> -->
                        </td>
                    </tr>
                <?php } ?>
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

    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/getreferensi');?>",
        // data:{
        //     'cari': cari,
        // },
        dataType: 'json',
        success: function(data){
            $("#ischedule").html(data.kop);
            if (data.kosong=='kopong') {
                $("#submit").attr("disabled", true);
            }else{
                $("#submit").attr("disabled", false);
            }
        },

        error:function(XMLHttpRequest){
            alert(XMLHttpRequest.responseText);
        }
    })
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

function get(id) {
    $("#tabledata tr:gt(0)").remove();
    $("#jml").val(0);
    $("#submit").attr("hidden", false); 

        $.ajax({
            type: "post",
            url: '<?= base_url($folder.'/cform/getschedule'); ?>',
            data: {
                'ischedule': id
            },
            dataType: "json",
            success: function (data) {
            if((data['data'][0]!=null && data['brgop'] != null)){ 
                $("#tabledata tbody").remove();
                $("#tabledata").attr("hidden", false);  
                $('#dschedule').val(data['data'][0].d_schedule);
                
                var group = '';
                for (let a = 0; a < data['brgop'].length; a++) {
                    var no = a+1;
                    var produk      = data['brgop'][a]['i_product'];
                    var namaproduk  = data['brgop'][a]['e_product_name'];
                    var warna       = data['brgop'][a]['warna'];
                    var color       = data['brgop'][a]['i_color'];
                    var material    = data['brgop'][a]['e_material_name'];
                    var imaterial   = data['brgop'][a]['i_material'];
                    var itujuan     = data['brgop'][a]['tujuan'];
                    var qty         = data['brgop'][a]['n_quantity'];
                    var sisa        = data['brgop'][a]['n_quantity_sisa'];
                    var gelar       = data['brgop'][a]['n_gelar_tmp'];
                    var set         = data['brgop'][a]['n_set_tmp'];
                    var totalgelar  = parseFloat(data['brgop'][a]['n_total_gelartmp']);
                    var pgelar      = parseFloat(data['brgop'][a]['n_panjang_kaintmp']);
                    var fbisbisan   = data['brgop'][a]['f_bisbisan'];
                    var product     = produk+color;
                    var product2    = "'"+product+"'";

                    if(fbisbisan == 't'){
                        var bis = 'checked';
                    }else{
                        var bis = '';
                    }

                    var cols        = "";
                    var cols2       = "";
                    
                    var newRow      = $("<tr>");

                    if(group==""){
                        cols2 += '<td class = "bg-success text-white" colspan = "11" style=\"font-size:16px;\"><b>'+produk+' ('+namaproduk+') - '+warna+' ('+qty+')</b></td>'; //<input style="width:60px;" class="form-control" type="text" id="nquantity'+a+'" name="nquantity'+a+'" value="'+sisa+'" onkeyup="hitungnilai3(this.value,'+product2+');">
                    }else{
                        if((group!=product)){
                            cols2 += '<td class = "bg-success text-white" colspan = "11" style=\"font-size:16px;\"><b>'+produk+' ('+namaproduk+') - '+warna+' ('+qty+')</b></td>'; //<input style="width:60px;" class="form-control" type="text" id="nquantity'+a+'" name="nquantity'+a+'" value="'+sisa+'" onkeyup="hitungnilai3(this.value,'+product2+');">
                        }
                    }
                
                    newRow.append(cols2);
                    $("#tabledata").append(newRow);

                    var newRow      = $("<tr>");

                    group=product;
                        /* HEADERNYA */
                        cols += '<td><input style="width:38px;" class="form-control" readonly type="text" id="baris'+a+'" name="baris'+a+'" value="'+no+'"><input readonly style="width:60px;" type="hidden" id="fbisbisan'+a+'" name="fbisbisan'+a+'" value="'+fbisbisan+'"></td>';
                        cols += '<td hidden><input style="width:85px;" class="form-control" readonly type="text" id="iproduct'+a+'" name="iproduct'+a+'" value="'+produk+'"></td>';
                        cols += '<td hidden><input style="width:85px;" class="form-control" readonly type="text" id="iproductcolor'+a+'" name="iproductcolor'+a+'" value="'+product+'"></td>';
                        cols += '<td hidden><input style="width:250px;" class="form-control" readonly type="text" id="eproductname'+a+'" name="eproductname'+a+'" value="'+namaproduk+'"></td>';
                        cols += '<td hidden><input readonly style="width:100px;" class="form-control" type="text" id="warna'+a+'" name="warna'+a+'" value="'+warna+'"><input readonly style="width:70px;"  type="hidden" id="icolor'+a+'" name="icolor'+a+'" value="'+color+'"></td>';
                        cols += '<td><input readonly style="width:150px;" class="form-control" type="text" id="imaterial'+a+'" name="imaterial'+a+'" value="'+imaterial+'"></td>';
                        cols += '<td hidden><input readonly style="width:150px;" class="form-control" type="text" id="itujuan'+a+'" name="itujuan'+a+'" value="'+itujuan+'"></td>';
                        cols += '<td><input readonly style="width:350px;" class="form-control" type="text" id="ematerial'+a+'" name="ematerial'+a+'" value="'+material+'"></td>';
                        
                        /* ITEMNYA */
                        cols += '<td hidden><input readonly style="width:70px;" class="form-control" type="text" id="sisa'+a+'" name="sisa'+a+'" value ='+sisa+'></td>';
                        cols += '<td hidden><input readonly style="width:70px;" class="form-control" type="text" id="nquantitytmp'+a+'" name="nquantitytmp'+a+'" value ='+sisa+' onkeyup="hitungnilai3(this.value,'+a+');"></td>';
                        cols += '<td><input readonly style="width:80px;" class="form-control text-right" type="text" id="vset'+a+'" name="vset'+a+'" value="'+qty+'"></td>';
                        cols += '<td><input style="width:80px;" class="form-control text-right" type="text" id="ndeliver'+a+'" name="ndeliver'+a+'" value="'+qty+'" onkeyup = "cekjml();" placeholder="0"></td>';//;angkahungkul(this)
                        cols += '<td><input style="width:125px;" class="form-control" type="text" id="eremark'+a+'" name="eremark'+a+'" value=""></td>';
                        /******************************************************* */

                    console.log(produk); /* LOG ERROR DI CONSOLE */
                                        
                    newRow.append(cols);
                    $("#tabledata").append(newRow);
                    //document.getElementById("ndeliver"+a).placeholder = "0"; 
                }
                $('#jml').val(no);
            }
            },
            error: function () {
                alert('Error :)');
            }
        });
    }

    function cekjml(){
        for(i=0;i<=$('#jml').val();i++){
            document.getElementById("ndeliver"+i).value = document.getElementById("ndeliver"+i).value.replace(/[^\d.-]/g,'');
            ndeliver    = document.getElementById("ndeliver"+i).value;
            vset        = document.getElementById("vset"+i).value;

            if(parseInt(ndeliver) > parseInt(vset)){
                swal ('Jumlah Kirim melebihi Schedule !');
                document.getElementById("ndeliver"+i).value = 0;
                return false;
            }
        }
    }
</script>