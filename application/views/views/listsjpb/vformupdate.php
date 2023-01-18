<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-12">No SJ / Tanggal SJ</label>
                            <?php 
                                if($isi->d_sjpb){
			                        if($isi->d_sjpb!=''){
			                        	$tmp=explode("-",$isi->d_sjpb);
			                        	$hr=$tmp[2];
			                        	$bl=$tmp[1];
			                        	$th=$tmp[0];
			                        	$isi->d_sjpb=$hr."-".$bl."-".$th;
			                        }?>
                               <input hidden id="bsj" name="bsj" value="<?php echo $bl; ?>">
                               <?php }
		                        if($isi->d_sjp){
		                       	    if($isi->d_sjp!=''){
		                       	    	$tmp=explode("-",$isi->d_sjp);
		                       	    	$hr=$tmp[2];
		                       	    	$bl=$tmp[1];
		                       	    	$th=$tmp[0];
		                       	    	$isi->d_sjp=$hr."-".$bl."-".$th;
		                       	    }
		                        }
		                    ?>
                        <div class="col-sm-5">
                            <input type="text" name="isj" id="isj" class="form-control" value="<?php if($isi->i_sjpb) echo $isi->i_sjpb; ?>">
                        </div>
                        <div class="col-sm-3">    
                            <input readonly type="text" name="dsj" id="dsj" class="form-control date" value="<?php if($isi->d_sjpb) echo $isi->d_sjpb; ?>">
                            <input hidden id="tglsj" name="tglsj" value="<?php echo $isi->d_sjpb; ?>">
                        </div>
                    </div>    
                    <div class="form-group row">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-6">
                            <input readonly id="eareaname" name="eareaname" class="form-control" value="<?php if($isi->e_area_name) echo $isi->e_area_name; ?>">
		                    <input id="iarea" name="iarea" type="hidden" class="form-control" value="<?php if($isi->i_area) echo $isi->i_area; ?>">
                            <input id="istore" name="istore" type="hidden" class="form-control" value="<?php if($isi->i_store) echo $isi->i_store; ?>">
                        </div>
                    </div>               
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-12">
                            <?php
                                if($isi->d_sjpb_receive != ''){?>
                                    <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales(parseFloat(document.getElementById('jml').value));"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>&nbsp;&nbsp;
                               <?php }
                            ?>
                            <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Item</button>&nbsp;&nbsp;                                
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Batal</button>                               
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-12">SJP</label>
                        <div class="col-sm-3">
                            <input readonly id="isjp" name="isjp" class="form-control" value="<?php if($isi->i_sjp) echo $isi->i_sjp; ?>">
                        </div>
                        <div class="col-sm-3">
                            <input readonly id="dsjp" name="dsjp" type="text" class="form-control" value="<?php if($isi->d_sjp) echo $isi->d_sjp; ?>">
                        </div>
                    </div>    
                    <div class="form-group row">
                        <label class="col-md-12">Nilai</label>
                        <div class="col-sm-6">
                            <input style="text-align:right" id="vsjpb" name="vsjpb" class="form-control" value="<?php if($isi->v_sjpb) echo $isi->v_sjpb; ?>">
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-12">Nama Toko</label>
                        <div class="col-sm-6">
                            <input readonly id="ecustomername" name="ecustomername" class="form-control" value="<?php if($isi->e_customer_name) echo $isi->e_customer_name; ?>">
                            <input type="hidden" id="icustomer" name="icustomer" class="form-control" value="<?php if($isi->i_customer) echo $isi->i_customer; ?>">
                            <input type="hidden" id="ispg" name="ispg" class="form-control" value="<?php if($isi->i_spg) echo $isi->i_spg; ?>">
                            <input readonly type="hidden" id="jml" name="jml" class="form-control" value="<?php echo $jmlitem; ?>">
                        </div>
                    </div>               
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th style="text-align: center; width: 5%;">No</th>
                                <th style="text-align: center; width: 20%;">Kode</th>
                                <th style="text-align: center; width: 40%;">Nama Barang</th>
                                <th style="text-align: center; width: 7%;">Motif</th>
                                <th style="text-align: center; width: 7%;">Kode Harga</th>
                                <th style="text-align: center;">Jumlah Kirim</th>
                                <th style="text-align: center;">Jumlah Terima</th>
                                <th style="text-align: center; width: 5%;">Act</th>
                            </tr>
                        </thead>
                        <?php 
					    if($detail){
						    $i=1;
						    foreach($detail as $row){?>
                            <tbody>
                                <tr>
                                    <td style="text-align: center;">
                                        <input  type="text" class="form-control" readonly id="baris<?= $i;?>" name="baris<?= $i;?>" value="<?= $i;?>">
                                        <input  class="form-control" type="hidden" id="motif<?= $i;?>" name="motif<?= $i;?>" value="<?= $row->i_product_motif;?>">
                                        <input  class="form-control" type="hidden" id="iproductgrade<?= $i;?>" name="iproductgrade<?= $i;?>" value="<?= $row->i_product_grade;?>">
                                    </td>
                                    <td>
                                        <input class="form-control" readonly id="iproduct<?= $i;?>" name="iproduct<?= $i;?>" value="<?= $row->i_product;?>">
                                    </td>
                                    <td>
                                        <input class="form-control" readonly id="eproductname<?= $i;?>" name="eproductname<?= $i;?>" value="<?= $row->e_product_name;?>">
                                    </td>
                                    <td>
                                        <input readonly type="text" class="form-control" readonly id="emotifname<?= $i;?>" name="emotifname<?= $i;?>" value="<?= $row->e_product_motifname;?>">
                                        <input type="hidden" class="form-control" readonly id="vproductmill<?= $i;?>" name="vproductmill<?= $i;?>" value="<?= $row->v_unit_price;?>">
                                    </td>
                                    <td>
                                        <input readonly class="form-control" id="ipricegroup<?= $i;?>" name="ipricegroup<?= $i;?>" value="<?= $row->i_price_group;?>">
                                        <input type="hidden" class="form-control" id="vproductmill<?= $i;?>" name="vproductmill<?= $i;?>" value="<?= $row->v_unit_price;?>">
                                    </td>
                                    <td>
                                        <input class="form-control" id="ndeliver<?= $i;?>" name="ndeliver<?= $i;?>" value="<?= $row->n_deliver;?>" onkeyup="hitungnilai(); pembandingnilai(this.value);" autocomplete="off">
                                        <input class="form-control" type = "hidden" id="ndeliverx<?= $i;?>" name="ndeliverx<?= $i;?>" value="<?= $row->n_deliver;?>" onkeyup="hitungnilai(); pembandingnilai(this.value);" autocomplete="off">
                                    </td>
                                    <td>
                                        <input class="form-control" id="nreceive<?= $i;?>" name="nreceive<?= $i;?>" value="<?= $row->n_receive;?>" onkeyup="hitungnilai(); pembandingnilai(this.value);">
                                        <input class="form-control" type="hidden" id="vtotal<?= $i;?>" name="vtotal<?= $i;?>" value="<?= $row->v_sjpb;?>">
                                    </td>
                                    <td style="text-align: center;">
                                        <input type='checkbox' name="chk<?=$i;?>" id="chk<?=$i;?>" value='on' checked onclick='ngetang()'>
                                    </td>
                                </tr>
                            <?php $i++;}               
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
    var xx = $('#jml').val();
    var uu = xx-1;
    $("#addrow").on("click", function () {
        xx++;
        uu++;
        $("#tabledata").attr("hidden", false);
        var iproduct = $('#iproduct'+uu).val();
        count=$('#tabledata tr').length;
        if ((iproduct==''||iproduct==null)&&(count>1)) {
            swal('Isi dulu yang masih kosong!!');
            xx = xx-1;
            uu = uu-1;
            return false;
        }
        $('#jml').val(xx);
        var newRow = $("<tr>");
        var cols = "";
        cols += '<td style="text-align: center;"><spanx id="snum'+xx+'">'+count+'</spanx><input type="hidden" id="baris'+xx+'" type="text" class="form-control" name="baris'+xx+'" value="'+xx+'"><input type="hidden" id="motif'+xx+'" name="motif'+xx+'" value=""></td>';
        cols += '<td><select id="iproduct'+xx+ '" class="form-control" name="iproduct'+xx+'" onchange="getdetailproduct('+xx+')";><input type="hidden" id="productprice'+xx+'" name="productprice'+xx+'" value=""></select></td>';
        cols += '<td><input readonly id="eproductname'+xx+ '" class="form-control" name="eproductname'+xx+'"></td>';
        cols += '<td><input readonly id="emotifname'+xx+ '" class="form-control" name="emotifname'+xx+'"></td>';
        cols += '<td style="width:50px;"><input style="text-align:right; width:50px;" type="text" class="form-control" id="norder'+xx+'" name="norder'+xx+'" value="" onkeyup="hitungnilai('+xx+');">';
        cols += '<input type="hidden" id="vtotal'+xx+'" name="vtotal'+xx+'" class="form-control" value="">';
        cols += '<td style="width:105px;"><input style="width:105px;" type="text" id="eremark'+xx+'" name="eremark'+xx+'" class="form-control" value=""></td>';
        cols += '<td><button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';
        newRow.append(cols);
        $("#tabledata").append(newRow);
        $('#iproduct'+xx).select2({
            placeholder: 'Cari Kode/Nama',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getproduct/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query   = {
                        q       : params.term,
                        istore   : $('#istore').val(),
                        icustomer   : $('#icustomer').val()
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

    function hitungnilai(){
        jml=document.getElementById("jml").value;
        if (jml<=0){
        }else{
          gros=0;
          for(i=1;i<=jml;i++){
            if(document.getElementById("chk"+i).value=='on'){
              hrg=formatulang(document.getElementById("vproductmill"+i).value);
              qty=formatulang(document.getElementById("ndeliver"+i).value);
              vhrg=parseFloat(hrg)*parseFloat(qty);
              document.getElementById("vtotal"+i).value=vhrg;
              document.getElementById("vtotal"+i).value=formatcemua(vhrg);
              gros=gros+parseFloat(formatulang(document.getElementById("vtotal"+i).value));
            }
          }
          document.getElementById("vsjpb").value=formatcemua(gros);
        }
    }
    function pembandingnilai(a){
	    var n_qty	= document.getElementById('norder'+a).value;
	    var n_deliver	= document.getElementById('ndeliver'+a).value;
	    var deliverasal	= document.getElementById('ndeliverhidden'+a).value;
	    if(parseInt(n_deliver) > parseInt(n_qty)) {
	    	alert('Jml kirim ( '+n_deliver+' item ) tdk dpt melebihi Order ( '+n_qty+' item )');
	    	document.getElementById('ndeliver'+a).value	= deliverasal;
	    	document.getElementById('ndeliver'+a).focus();
	    	return false;
	    }else if(parseInt(n_deliver) > parseInt(deliverasal)) {
            i_store = document.getElementById('istore').value;
            kons=document.getElementById("fspbconsigment").value;
	          if(i_store!='AA' && istore!='PB' && kons!='t') {
	            alert('Jml kirim ( '+n_deliver+' item ) tdk dpt melebihi Stock ( '+deliverasal+' item )');
	            document.getElementById('ndeliver'+a).value	= deliverasal;
	            document.getElementById('ndeliver'+a).focus();
	            return false;
            }
        }
    }
    function getdetailproduct(id){
        ada=false;
        var a = $('#iproduct'+id).val();
        var x = $('#jml').val();
        for(i=1;i<=x;i++){
            if((a == $('#iproduct'+i).val()) && (i!=x)){
                swal ("kode Barang : "+a+" sudah ada !!!!!");            
                ada=true;            
                break;        
            }else{            
                ada=false;             
            }
        }

        if(!ada){
            var iproduct = $('#iproduct'+id).val();
            $.ajax({
                type: "post",
                data: {
                    'iproduct'  : iproduct,
                    'istore'    : $('#istore').val(),
                    'icustomer' : $('#icustomer').val()
                },
                url: '<?= base_url($folder.'/cform/getdetailproduct'); ?>',
                dataType: "json",
                success: function (data) {
                    $('#eproductname'+id).val(data[0].e_product_name);
                    $('#motif'+id).val(data[0].i_product_motif);
                    $('#emotifname'+id).val(data[0].e_product_motifname);
                    $('#productprice'+id).val(formatcemua(data[0].v_product_retail));
                    $('#iproductgrade'+id).val(data[0].i_product_grade);
                    $('#ndeliver'+id).val(0);
                },
                error: function () {
                    swal('Error :)');
                }
            });
        }else{
            $('#iproduct'+id).html('');
            $('#iproduct'+id).val('');
        }
    }

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#addrow").attr("disabled", true);
    });

    function dipales(a){
        if((document.getElementById("tglsj").value!='') && 
            (document.getElementById("iarea").value!='') && 
            (document.getElementById("isjp").value!='') && 
            (document.getElementById("icustomer").value!='')) {
            if(a==0){
                swal('Isi data item minimal 1 !!!');
                return false;
            }else{
                for(i=1;i<=a;i++){
                    if((document.getElementById("iproduct"+i).value=='') || (document.getElementById("eproductname"+i).value=='') || (document.getElementById("ndeliver"+i).value=='')){
                        swal('Data item masih ada yang salah !!!');
                        return false;
                    }else{
                        return true;
                    }
                }
            }

        }else{
            swal('Data header masih ada yang salah !!!');
            return false;
        }
    }
</script>