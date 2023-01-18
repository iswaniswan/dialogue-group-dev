<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>

            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div class="col-md-6">
                    <div id="pesan"></div>
                    <div class="form-group row">
                        <label class="col-md-6">No SJ</label><label class="col-md-6">Tanggal SJ</label>
                        <?php if($isi->d_sjr){
			                    if($isi->d_sjr!=''){
			                    	  $tmp=explode("-",$isi->d_sjr);
			                    	  $hr=$tmp[2];
			                    	  $bl=$tmp[1];
			                    	  $th=$tmp[0];
			                    	  $isi->d_sjr=$hr."-".$bl."-".$th;
			                    }
		                    }
	                        ?>
                            <div class="col-sm-6">
                                <input id="isj" name="isj" class="form-control" value="<?php echo $isi->i_sjr; ?>">
                            </div>
                            <div class="col-sm-3">
                                <input readonly id="dsj" name="dsj" class="form-control date" value="<?php echo $isi->d_sjr; ?>">
                            </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-6">
                            <input readonly id="eareaname" class="form-control" name="eareaname" value="<?php if($isi->e_area_name) echo $isi->e_area_name; ?>">
                            <input id="iarea" name="iarea" class="form-control" type="hidden" value="<?php if($iarea) echo $iarea; ?>">
                            <input id="istore" name="istore" type="hidden" value="<?php if($isi->i_store) echo $isi->i_store; ?>">
                            <input id="istorelocation" name="istorelocation" type="hidden" value="<?php if($isi->i_store_location) echo $isi->i_store_location; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-3 col-sm-8">
                            <?php
                                if($isi->d_sjr_receive == ''){?>
                                    <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales(parseFloat(document.getElementById('jml').value));"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                                    &nbsp;&nbsp; 
                                    <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm" ><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah Item</button>
                                    &nbsp;&nbsp;
                                <?}
                            ?>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/view/<?=$dfrom;?>/<?=$dto;?>/<?=$iarea;?>","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div id="pesan"></div>
                    <div class="form-group row">
                        <label class="col-md-12">SJ Lama</label>
                        <div class="col-sm-6">
                            <input id="isjold" name="isjold" class="form-control" value="<?php if($isi->i_sjr_old) echo $isi->i_sjr_old; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Nilai</label>
                        <div class="col-sm-6">
                            <input readonly type="text" id="vsj" name="vsj" class="form-control" value="<?php echo number_format($isi->v_sjr); ?>">
                        </div>
                    </div>
                    </div>
                    <div class="table-responsive">
                    <table class="table table-bordered" cellspacing="0" id="tabledata" width="100%">
                                <thead>
                                    <tr>
                                        <th style="text-align: center; width: 7%;">No</th>
                                        <th style="text-align: center; width: 10%;">Kode Barang</th>
                                        <th style="text-align: center; width: 35%;">Nama Barang</th>
                                        <th style="text-align: center; width: 35%;">Keterangan</th>
                                        <th style="text-align: center;">Jumlah Kirim</th>
                                        <th style="text-align: center;">Jumlah Terima</th>
                                        <th style="text-align: center;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php               
                                    if($detail){
                                         $i=0;
                                         foreach($detail as $row){
                                            $i++;
                                            $vtotal=$row->v_unit_price*$row->n_quantity_retur;
                                            $query = $this->db->query(" select * from tm_ic
                                                                        where i_store='$isi->i_store' and i_store_location='$isi->i_store_location'
                                                                        and i_product_motif='$row->i_product_motif' and i_product='$row->i_product'");
                                            if($query->num_rows()>0){
                                              foreach($query->result() as $raw){
                                                $stok=$raw->n_quantity_stock;
                                              }
                                            }else{
                                              $stok=0;          
                                            }
                                            $stok=$stok+$row->n_quantity_retur;
                                ?>
                                            <tr>
                                                <td style="text-align: center;">
                                                    <input  type="text" class="form-control" readonly id="baris<?= $i;?>" name="baris<?= $i;?>" value="<?= $i;?>">
                                                    <input  class="form-control" type="hidden" id="motif<?= $i;?>" name="motif<?= $i;?>" value="<?= $row->i_product_motif;?>">
                                                </td>
                                                <td>
                                                    <input class="form-control" readonly id="iproduct<?= $i;?>" name="iproduct<?= $i;?>" value="<?= $row->i_product;?>">
                                                </td>
                                                <td>
                                                    <input class="form-control" readonly id="eproductname<?= $i;?>" name="eproductname<?= $i;?>" value="<?= $row->e_product_name;?>">
                                                    <input type="hidden" class="form-control" readonly id="emotifname<?= $i;?>" name="emotifname<?= $i;?>" value="<?= $row->e_product_motifname;?>">
                                                </td>
                                                <td>
                                                    <input class="form-control" id="eremark<?= $i;?>" name="eremark<?= $i;?>" value="<?= $row->e_remark;?>">
                                                    <input type="hidden" class="form-control" id="vproductmill<?= $i;?>" name="vproductmill<?= $i;?>" value="<?= $row->v_unit_price;?>">
                                                </td>
                                                <td>
                                                    <input class="form-control" id="nretur<?= $i;?>" name="nretur<?= $i;?>" value="<?= $row->n_quantity_retur;?>" onkeyup="hitungnilai(<?=$i;?>)">
                                                    <input type="hidden" class="form-control" id="vtotal<?= $i;?>" name="vtotal<?= $i;?>" value="<?= $vtotal;?>">
                                                    <input type="hidden" class="form-control" id="stok<?= $i;?>" name="stok<?= $i;?>" value="<?= $stok;?>">
                                                </td>
                                                <td>
                                                    <input class="form-control" id="nreceive<?= $i;?>" name="nreceive<?= $i;?>" value="<?= $row->n_quantity_receive;?>">
                                                    <input type="hidden" class="form-control" id="nasal<?= $i;?>" name="nasal<?= $i;?>" value="<?= $row->n_quantity_retur;?>">
                                                    
                                                </td>
                                                <td style="text-align: center;">
                                                    <input type='checkbox' name="chk<?=$i;?>" id="chk<?=$i;?>" value='on' checked onclick='hitungnilai();'>
                                                </td>
                                            </tr>
                                        <?php }  
                                    }?>
                                    </div>
                                    <input type="hidden" name="jml" id="jml" value="<?= $i;?>">
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

var counter = $('#jml').val(); 
$("#addrow").on("click", function () {
    counter++;
    $("#tabledata").attr("hidden", false);
    $('#jml').val(counter);
    var istore = $("#istore").val();
    var istorelocation = $("#istorelocation").val();
    count=$('#tabledata tr').length;
    var newRow = $("<tr>");
    var cols = "";
    cols += '<td style="text-align: center;"><spanx id="snum'+counter+'">'+count+'</spanx><input type="hidden" id="baris'+counter+'" class="form-control" name="baris'+counter+'" value="'+counter+'"><input type="hidden" id="motif'+counter+'" name="motif'+counter+'" value=""></td>';
    cols += '<td><select style="width:200px;" id="iproduct'+ counter + '" class="form-control select2" name="iproduct'+ counter + '" onchange="getharga('+ counter + ');"></select></td>';
    cols += '<td><input readonly select style="width:300px;" type="text" id="eproductname'+ counter + '" type="text" class="form-control" name="eproductname' + counter + '"><input type="hidden" id="emotifname'+counter+'" name="emotifname'+counter+'" value=""></td>';
    cols += '<td><input type="text" id="eremark'+ counter + '" class="form-control" name="eremark'+ counter + '"/><input type="hidden" id="vproductmill'+counter+'" name="vproductmill'+counter+'" value=""></td>';
    cols += '<td><input style="width:90px;" type="text" id="nretur'+ counter + '" class="form-control" name="nretur'+ counter + '" onkeyup="hitungnilai('+counter+');" value=""><input type="hidden" id="stok'+counter+'" name="stok'+counter+'" value="stok'+counter+'"><input type="hidden" id="vtotal'+counter+'" name="vtotal'+counter+'" value="0"></td>';
    cols += '<td><input style="width:90px;" type="text" id="nreceive'+ counter + '" class="form-control" name="nreceive' + counter + '"/><input readonly style="width:90px;" type="hidden" id="nasal'+ counter + '" class="form-control" name="nasal' + counter + '" value="0" onkeyup="hitungnilai('+counter+');"/></td>';
    cols += '<td><input type="checkbox" name="chk'+counter+'" id="chk'+counter+'" value="on" checked onclick="hitungnilai();"></td>';
    cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>';
    newRow.append(cols);
    $("#tabledata").append(newRow);
    $('#iproduct'+counter).select2({
        placeholder: 'Cari Product / Barang',
        allowClear: true,
        ajax: {
            url: '<?= base_url($folder.'/cform/databrg/'); ?>'+istore +'/' +istorelocation,
            dataType: 'json',
            delay: 250,
            data: function (params) {
                var iproduct   = $('#iproduct').val();
                
                var query   = {
                    q           : params.term,
                    iproduct    : iproduct
                }
                return query;
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: false
        }
    });
});

    $("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
        /*xx -= 1;*/
        $('#jml').val(xx);
        del();
        hitungnilai();
    });
    function del() {
        obj=$('#tabledata tr').find('spanx');
        $.each( obj, function( key, value ) {
            id=value.id;
            $('#'+id).html(key+1);
        });
    }

$(document).ready(function () {
    showCalendar('.date');
});

function getharga(id){
    var iproduct = $('#iproduct'+id).val();
    $.ajax({
    type: "post",
    data: {
        'i_product': iproduct
    },
    url: '<?= base_url($folder.'/cform/getharga'); ?>',
    dataType: "json",
    success: function (data) {
        $('#eproductname'+id).val(data[0].e_product_name);
        $('#nretur'+id).val("0");
        $('#nreceive'+id).val("0");
        $('#nasal'+id).val("0");
        $('#vproductmill'+id).val(data[0].v_product_mill);
        $('#motif'+id).val(data[0].i_product_motif);
        $('#stok'+id).val(data[0].n_quantity_stock);

        ada=false;
        var a = $('#iproduct'+id).val();
        var e = $('#motif'+id).val();
        var jml = $('#jml').val();
        for(i=1;i<=jml;i++){
            if((a == $('#iproduct'+i).val()) && (i!=jml)){
                swal ("kode : "+a+" sudah ada !!!!!");
                ada=true;
                break;
            }else{
                ada=false;	   
            }
        }
        if(!ada){
            var iproduct    = $('#iproduct'+id).val();
            var istore      = $('#istore').val();
            $.ajax({
                type: "post",
                data: {
                    'iproduct'  : iproduct,
                    'istore'    : istore
                },
                url: '<?= base_url($folder.'/cform/getdetailbar'); ?>',
                dataType: "json",
                success: function (data) {
                    $('#eproductname'+id).val(data[0].e_product_name);
                    $('#nretur'+id).val("0");
                    $('#nreceive'+id).val("0");
                    $('#nasal'+id).val("0");
                    $('#vproductmill'+id).val(data[0].v_product_mill);
                    $('#motif'+id).val(data[0].i_product_motif);
                    $('#stok'+id).val(data[0].n_quantity_stock);
                },
            });
        }else{
            $('#iproduct'+id).html('');
            $('#iproduct'+id).val('');
            $('#eproductname'+id).val('');
            $('#nretur'+id).val('');
            $('#nreceive'+id).val('');
            $('#chk'+id).val('');
            $('#nasal'+id).val('');
        }
    },
    error: function () {
        swal('Error :)');
    }
});
}

function pembandingstok(){
    jml=document.getElementById("jml").value;
    gud=document.getElementById("istore").value;
    for(i=1;i<=jml;i++){
      stock  =formatulang(document.getElementById("stok"+i).value);
      retur  =formatulang(document.getElementById("nretur"+i).value);
      if(parseFloat(stock)<0)
        stock=0;
      if(gud!='PB'){
        if(parseFloat(retur)>parseFloat(stock)){
          alert('Jumlah Retur melebihi jumlah Stock');
          document.getElementById("nretur"+i).value=0;
          break;
        }
      }
    }
}

// function hitungnilai(brs){
//     var tot=0;
//     var nretur = $("#nretur"+brs).val();
//     if (isNaN(parseFloat(nretur))){
//           alert("Input harus numerik");
//     }else{
//         var hrg = formatulang($("#vproductmill"+brs).val());
//         qty=formatulang(nretur);
//         vhrg=parseFloat(hrg)*parseFloat(qty);
//         $('#vtotal'+brs).val(formatcemua(vhrg));
//         var jml = parseFloat(document.getElementById("jml").value);
//         for(i=1;i<=jml;i++){
//           if(document.getElementById("chk"+i).value=='on'){
//             tot+=parseFloat(formatulang($("#vtotal"+i).val()));
//           }
//         }
//         $('#nasal'+brs).val(nretur);
//         $('#vsj').val(formatcemua(tot));
//     }
// }

function hitungnilai(){
        var jml = parseFloat($('#jml').val());
        var tot = 0;
        for(brs=1;brs<=jml;brs++){    
            ord = $("#nretur"+brs).val();
            $("#nasal"+brs).val(ord);
            hrg  = formatulang($("#vproductmill"+brs).val());
            qty  = formatulang(ord);
            vhrg = parseFloat(hrg)*parseFloat(qty);
            $("#vtotal"+brs).val(formatcemua(vhrg));
            if($("#chk"+brs).is(':checked')){
                tot+=parseFloat(formatulang($("#vtotal"+brs).val()));
            }
            if($("#chk"+brs).is(':checked')){
                tot+=parseFloat(formatulang($("#vtotal"+brs).val()));
            }
        }
        $("#vsj").val(formatcemua(tot));
        
    }

function dipales(a){
      cek='false';
      if((document.getElementById("iarea").value!='')) {
           if(a==0){
               alert('Isi data item minimal 1 !!!');
           }else{
               for(i=1;i<=a;i++){
                  if((document.getElementById("iproduct"+i).value=='') ||
                      (document.getElementById("eproductname"+i).value=='') ||
                      (document.getElementById("nretur"+i).value=='')){
                      alert('Data item masih ada yang salah !!!');
                      exit();
                      cek='false';
                  }else{
                      cek='true';	
                  } 
              }
        }
        if(cek=='true'){
               document.getElementById("login").disabled=true;
               document.getElementById("cmdtambahitem").disabled=true;
        }else{
               document.getElementById("login").disabled=false;
        }
    }else{
       alert('Data header masih ada yang salah !!!');
    }
}

// function pilihan(a,b){
//     if(a==''){          
//             document.getElementById("chk"+b).value='on';      
//     }else{          
//         document.getElementById("chk"+b).value='';      
//     }      
//     hitungnilai();  
// }

</script>