<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/view/<?= $dfrom;?>/<?= $dto;?>/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
            <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div class="col-md-6">
                    <div id="pesan"></div>
                    <div class="form-group row">
                        <label class="col-md-6">No DO</label><label class="col-md-6">Tanggal DO</label>
                        <div class="col-sm-6">
                            <?php 
                                $tmp=explode("-",$isi->d_do);
				                $th=$tmp[0];
				                $bl=$tmp[1];
				                $hr=$tmp[2];
                                $ddo=$hr."-".$bl."-".$th;
			                ?>
                            <input type="hidden" id="bop" name="bop" class="form-control" value="<?php echo $bl; ?>" readonly>
                            <input type="text" id="ido" name="ido" class="form-control" value="<?php echo $isi->i_do; ?>">
                            <input id="idoold" name="idoold" type="hidden" value="<?php echo $isi->i_do; ?>">
                        </div>
                        <div class="col-sm-6">
                            <input readonly id="ddo" name="ddo" class="form-control date" value="<?php echo $ddo; ?>" readonly>
                            <input hidden id="tgldo" name="tgldo" class="form-control date" value="<?php echo $ddo; ?>"">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-12">
                            <input readonly id="eareaname" class="form-control" name="eareaname" value="<?php echo $isi->e_area_name; ?>">
		                    <input id="iarea" name="iarea" type="hidden" value="<?php echo $isi->i_area; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-3 col-sm-8">
                            <?php  
                                $tmp=explode("-",$isi->d_do);
                                $th=$tmp[0];
                                $bl=$tmp[1];
                                $hr=$tmp[2];
                                $ddo=$hr."-".$bl."-".$th;
                                $periodedo = $th.$bl;
                                if($periodeskrg <= $periodedo){?>
                                    <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i
                                        class="fa fa-plus"></i>&nbsp;&nbsp;Simpan</button>
                                        &nbsp;&nbsp;
                                    <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Item</button>
                            <? }?>
                                &nbsp;&nbsp;<button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/view/<?= $dfrom;?>/<?= $dto;?>/","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-6">No OP</label><label class="col-md-6">Pemasok</label>
                        <div class="col-sm-6">
                            <input type="text" id="iop" name="iop" class="form-control" maxlength= "15" value="<?php echo $isi->i_op; ?>" readonly>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" id="esuppliername" name="esuppliername" class="form-control date" value="<?php echo $isi->e_supplier_name;?>" readonly>
                            <input type="hidden" id="isupplier" name="isupplier" class="form-control date" value="<?php echo $isi->i_supplier;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Nilai Kotor</label>
                        <div class="col-sm-12">
                            <input readonly style="text-align:right;" id="vdogross" class="form-control" name="vdogross" value="<?php echo number_format($isi->v_do_gross);?>">
                        </div>
                    </div>
                </div>
                <div class="panel-body table-responsive">
                <div id="pesan"></div>
                    <table id="tabledata" class="display table" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th width="7%" style="text-align:center;">No</th>
                                <th width="12%" style="text-align:center;">Kode Barang</th>
                                <th width="35%" style="text-align:center;">Nama Barang</th>
                                <th width="20%" style="text-align:center;">Keterangan</th>
                                <th width="10%" style="text-align:center;">Harga</th>
                                <th width="7%" style="text-align:center;">Jml Kirim</th>
                                <th width="10%" style="text-align:center;">Total</th>
                                <th style="text-align:center;">Action</th>
                            </tr>
                        </thead>
                        <body>
                            <?php 				
				                $i=0;
				                foreach($detail as $row){
                                    $i++;
				                	$pangaos=0;
				                	$total=0*$row->n_deliver;
				                	$total=number_format($total,2);
                            ?>
                                <tr>
                                    <td class="text-center">
                                        <?= $i;?>
                                        <input class="form-control" readonly type="hidden" id="baris<?= $i;?>" name="baris<?= $i;?>" value="<?= $i;?>">
                                        <input class="form-control" type="hidden" id="motif<?= $i;?>" name="motif<?= $i;?>" value="<?= $row->i_product_motif;?>">
                                    </td>
                                    <td>
                                        <input class="form-control" readonly type="text" id="iproduct<?= $i;?>" name="iproduct<?= $i;?>" value="<?= $row->i_product;?>">
                                    </td>
                                    <td>
                                        <input class="form-control" readonly type="text" id="eproductname<?= $i;?>" name="eproductname<?= $i;?>" value="<?= $row->e_product_name;?>">
                                    </td>
                                    <td>
                                        <input class="form-control" type="text" id="eremark<?= $i;?>" name="eremark<?= $i;?>" value="<?= $row->e_remark;?>">
                                        <input class="form-control" readonly type="hidden" id="emotifname<?= $i;?>" name="emotifname<?= $i;?>" value="<?= $row->e_product_motifname;?>">
                                    </td>
                                    <td>
                                        <input class="form-control text-right" readonly type="text" id="vproductmill<?= $i;?>" name="vproductmill<?= $i;?>" value="<?= number_format($row->v_product_mill);?>">
                                    </td>
                                    <td>
                                        <input class="form-control text-right" type="text" id="ndeliver<?= $i;?>" name="ndeliver<?= $i;?>" value="<?= $row->n_deliver;?>"  onkeyup="hitungnilai(this.value); pembandingnilai();">
                                        <input class="form-control" type="hidden" id="ntmp<?= $i;?>" name="ntmp<?= $i;?>" value="<?= $row->n_deliver;?>">
                                        <input class="form-control" type="hidden" id="ndeliverhidden<?= $i;?>" name="ndeliverhidden<?= $i;?>" value="<?= $row->n_order;?>">
                                    </td>
                                    <td>
                                        <input class="form-control text-right" readonly type="text" id="vtotal<?= $i;?>" name="vtotal<?= $i;?>" value="<?= number_format($row->total);?>">
                                    </td>
                                    <td style="text-align: center;">
                                        <?php if(check_role($i_menu,4) && $isi->f_do_cancel == 'f'){?>
                                            <button type="button" onclick="hapusdetail('<?= $row->i_do."','".$isi->i_op."','".$isi->v_do_gross."','".$isi->i_area."','".$row->i_supplier."','".$row->i_product."','".$row->i_product_grade."','".$row->i_product_motif."','".$row->d_do;?>'); return false;" title="Delete" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                        <? } ?>
                                    </td>
                                </tr>
				            <?}?>
                        </body>
                    </table>
                    <input type="hidden" name="jml" id="jml" value="<?=$jmlitem;?>">
			    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
 $(document).ready(function () {
    
    $(".select2").select2();

    showCalendar('.date');
    hitungnilai();
});

var xx = $('#jml').val();
    $("#addrow").on("click", function () {
        xx++;
        $('#jml').val(xx);
        var newRow = $("<tr>");
        var cols = "";
        cols += '<td class="text-center">'+xx+'<input type="hidden" id="baris'+xx+'" class="form-control" name="baris'+xx+'" value="'+xx+'"><input type="hidden" id="motif'+xx+'" name="motif'+xx+'" value=""></td>';
        cols += '<td><select id="iproduct'+xx+ '" class="form-control select2" name="iproduct'+xx+'" onchange="detail('+xx+');" value=""></select></td>';
        cols += '<td><input class="form-control" type="text" id="eproductname'+xx+'" name="eproductname'+xx+'" value="" readonly></td>';
        cols += '<td><input class="form-control" type="text" id="eremark'+xx+'" name="eremark'+xx+'" value=""><input type="hidden" class="form-control" type="text" id="emotifname'+xx+'" name="emotifname'+xx+'" value="" readonly></td>';
        cols += '<td><input class="form-control text-right" readonly type="text" id="vproductmill'+xx+'" name="vproductmill'+xx+'" value="0"></td>';
        cols += '<td><input class="form-control text-right" readonly type="text" id="ndeliver'+xx+'" name="ndeliver'+xx+'" value="0" onkeyup="hitungnilai(); pembandingnilai('+xx+');"><input type="hidden" id="ndeliverhidden'+xx+'" name="ndeliverhidden'+xx+'" value=""><input type="hidden" id="ntmp'+xx+'" name="ntmp'+xx+'" value=""></td>';
        cols += '<td><input class="form-control text-right" type="text" id="vtotal'+xx+'" name="vtotal'+xx+'" value="0"></td>';
        cols += '<td></td>';
        newRow.append(cols);
        $("#tabledata").append(newRow);
        $('#iproduct'+xx).select2({
            placeholder: 'Cari Kode / Nama',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/product/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query   = {
                        q   : params.term,
                        iop : $('#iop').val(),
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

    function hitungnilai(){
	    jml=document.getElementById("jml").value;
	    if (isNaN(parseFloat(jml))){
	    	  alert("Input harus numerik");
	    }else{
            total=0;
            for(i=1;i<=jml;i++){
	        	hrg=formatulang(document.getElementById("vproductmill"+i).value);
	        	qty=formatulang(document.getElementById("ndeliver"+i).value);
	        	harga=parseFloat(hrg)*parseFloat(qty);
	        	total=total+harga;
  	        	document.getElementById("vtotal"+i).value=formatcemua(harga);
            }
	    	document.getElementById("vdogross").value=formatcemua(total);
	    }
    }

    function pembandingnilai(a) {
	  var n_deliver	= document.getElementById('ndeliver'+a).value;
	  var deliverasal	= document.getElementById('ndeliverhidden'+a).value;
	  var jml = document.getElementById('jml').value;
	  if(parseFloat(n_deliver) > parseFloat(deliverasal)) {
		  alert('Jml kirim ( '+n_deliver+' item ) tdk dpt melebihi Order ( '+deliverasal+' item )');
		  document.getElementById('ndeliver'+a).value	= deliverasal;
		  document.getElementById('ndeliver'+a).focus();
		  hitungnilai();
		  return false;
	  }
    }


    function detail(id){
        ada=false;
        var a = $('#iproduct'+id).val();
        var x = $('#jml').val();
        for(i=1;i<=x;i++){            
            if((a == $('#iproduct'+i).val()) && (i!=x)){
                swal ("kode : "+a+" sudah ada !!!!!");            
                ada=true;            
                break;        
            }else{            
                ada=false;             
            }
        }
        if(!ada){
            var iproduct    = $('#iproduct'+id).val();
            $.ajax({
                type: "post",
                data: {
                    'iproduct'  : iproduct,
                    'iop'       : $('#iop').val(),
                },
                url: '<?= base_url($folder.'/cform/detailproduct'); ?>',
                dataType: "json",
                success: function (data) {
                    $('#eproductname'+id).val(data[0].e_product_name);
                    $('#vproductmill'+id).val(formatcemua(data[0].v_product_mill));
                    $('#motif'+id).val(data[0].i_product_motif);
                    $('#emotifname'+id).val(data[0].e_product_motifname);
                    $('#ndeliver'+id).val(data[0].n_order);
                    $('#ndeliver'+id).focus();
                    hitungnilai();
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


    function hapusdetail(ido,iop,vdogross,iarea,isupplier,iproduct,iproductgrade,iproductmotif,$ddo) {
        swal({   
            title: "Apakah anda yakin ?",   
            text: "Anda tidak akan dapat memulihkan data ini!",   
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Ya, hapus!",   
            cancelButtonText: "Tidak, batalkan!",   
            closeOnConfirm: false,   
            closeOnCancel: false 
        }, function(isConfirm){   
            if (isConfirm) { 
                $.ajax({
                    type: "post",
                    data: {
                        'ido'           : ido,
                        'iop'           : iop,
                        'vdogross'      : vdogross,
                        'iarea'         : iarea,
                        'isupplier'     : isupplier,
                        'iproduct'      : iproduct,
                        'iproductgrade' : iproductgrade,
                        'iproductmotif' : iproductmotif,
                        'ddo'           : $('#ddo').val()
                    },
                    url: '<?= base_url($folder.'/cform/deleteitem'); ?>',
                    dataType: "json",
                    success: function (data) {
                        swal("Dihapus!", "Data berhasil dihapus :)", "success");
                        show('<?= $folder;?>/cform/edit/<?= $ido.'/'.$isupplier.'/'.$iarea.'/'.$dfrom.'/'.$dto;?>','#main');     
                    },
                    error: function () {
                        swal("Maaf", "Data gagal dihapus :(", "error");
                    }
                });
            } else {     
                swal("Dibatalkan", "Anda membatalkan penghapusan :)", "error");
            } 
        });
    }
</script>
