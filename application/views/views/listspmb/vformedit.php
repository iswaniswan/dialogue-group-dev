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
                        <label class="col-md-6">No SPMB</label><label class="col-md-6">Tanggal SPMB</label>
                        <?php if($isi->d_spmb){
			                if($isi->d_spmb!=''){
			                	  $tmp=explode("-",$isi->d_spmb);
			                	  $hr=$tmp[2];
			                	  $bl=$tmp[1];
			                	  $th=$tmp[0];
			                	  $isi->d_spmb=$hr."-".$bl."-".$th;
			                }
		                }?>
                            <div class="col-sm-6">
                                <input readonly id="ispmb" name="ispmb" class="form-control" value="<?php echo $isi->i_spmb; ?>">
                            </div>
                            <div class="col-sm-3">
                                <input readonly id="dspmb" name="dspmb" class="form-control date" value="<?php echo $isi->d_spmb; ?>">
                            </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-12">
                            <?php 
                                if($isi->i_sj == ''){?>
                                    <select name="iarea" id="iarea" class="form-control select2" required="">
                                    <option value="<?= $isi->i_area;?>"><?=$isi->e_area_name;?></option>
                                        <?php if ($area) {
                                            foreach ($area as $key) { ?>
                                                <option value="<?php echo $key->i_area;?>"><?php echo $key->i_area." - ".$key->e_area_name;?></option> 
                                            <?php }
                                        }else{?>
                                            <input readonly type="text" id="eareaname" class="form-control" name="eareaname" value="<?php if($isi->e_area_name) echo $isi->e_area_name; ?>">
                                            <input id="iarea" name="iarea" class="form-control" type="hidden" value="<?php echo $isi->i_area; ?>">
                                        <? } ?>   
                                    </select>
                                    <input readonly type="hidden" id="eareaname" class="form-control" name="eareaname" value="<?php if($isi->e_area_name) echo $isi->e_area_name; ?>">
                                    <input id="iarea" name="iarea" class="form-control" type="hidden" value="<?php echo $isi->i_area; ?>">
                                <?}
                            ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-3 col-sm-8">
                        <?php if($isi->i_approve2==null && $isi->i_approve2=='' && $isi->i_sj== ''){ ?>
                        
		                    <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales(parseFloat(document.getElementById('jml').value));"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                            &nbsp;&nbsp; 
                        <?}?>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/view/<?=$dfrom;?>/<?=$dto;?>/<?=$iarea;?>","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                            &nbsp;&nbsp;
                        <?php if(($isi->i_store == null) || ($isi->i_approve1=='') && $isi->i_approve2==null && $isi->i_approve2=='' && $isi->i_sj== ''){?>
                            <button type="button" id="addrow" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah Item</button>
                        <?}?>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div id="pesan"></div>
                    <div class="form-group row">
                        <label class="col-md-6">SPMB Lama</label><label class="col-md-6">Nilai ACC</label>
                        <div class="col-sm-6">
                            <input id="ispmbold" name="ispmbold" class="form-control" type="text" value="<?php echo $isi->i_spmb_old; ?>">
                        </div>
                        <div class="col-sm-6">
                            <input readonly id="vacc" name="vacc" class="form-control" value="0">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <input readonly id="eremark" class="form-control" name="eremark" value="<?php if($isi->e_remark) echo $isi->e_area_name; ?>">
                        </div>
                    </div>
                </div>
                <input readonly type="hidden" id="peraw" class="form-control" name="peraw" value="<?php echo $peraw; ?>">
                <input readonly type="hidden" id="perak" class="form-control" name="perak" value="<?php echo $perak; ?>">
                    <div class="table-responsive">
                    <table class="table table-bordered" id="tabledata" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th style="text-align: center; width: 7%;">No</th>
                                        <th style="text-align: center; width: 12%;">Kode Barang</th>
                                        <th style="text-align: center; width: 35%;">Nama Barang</th>
                                        <th style="text-align: center; width: 10%;">Motif</th>
                                        <th style="text-align: center; width: 7%;"">Jumlah Rata2</th>
                                        <th style="text-align: center; width: 12%;"">Nilai Rata2</th>
                                        <th style="text-align: center; width: 7%;"">Jumlah Pesan</th>
                                        <th style="text-align: center; width: 7%;"">Jumlah Acc</th>
                                        <th style="text-align: center; width: 20%;"">Keterangan</th>
                                        <th style="text-align: center;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php               
                                    if($detail){
                                         $i=0;
                                         foreach($detail as $row){
                                             $i++;
                                            $pangaos=number_format($row->v_unit_price,2);
                                            $total=$row->v_unit_price*$row->n_order;
                                            $total=number_format($total,2);
                                            if($row->n_acc==''){
                                                $row->n_acc=0;
                                            }
                                            $fpaw='FP-'.$peraw;
                                            $fpak='FP-'.$perak;
                                            $query = $this->db->query(" 
                                                                        select 
                                                                            trunc(sum(n_deliver*v_unit_price)/3) as vrata, 
                                                                            trunc(sum(n_deliver)/3) as nrata, 
                                                                            i_product 
                                                                        from 
                                                                            tm_nota_item
                                                                        where 
                                                                            i_nota>'$fpaw' 
                                                                            and i_nota<'$fpak' 
                                                                            and i_product='$row->i_product' 
                                                                            and i_product_motif='$row->i_product_motif' 
                                                                            and i_area='$row->i_area'
                                                                        group by 
                                                                            i_product 
                                                                        ");
                                            if($query->num_rows()>0){
                                              foreach($query->result() as $raw){
                                                $vrata=number_format($raw->vrata);
                                                $nrata=number_format($raw->nrata);
                                              }
                                            }else{
                                              $vrata=0;          
                                              $nrata=0;
                                            }
                                        ?>
                                            <tr>
                                                <td style="text-align: center;">
                                                    <input  type="text" class="form-control" readonly id="baris<?= $i;?>" name="baris<?= $i;?>" value="<?= $i;?>">
                                                </td>
                                                <td>
                                                    <input class="form-control" readonly id="iproduct<?= $i;?>" name="iproduct<?= $i;?>" value="<?= $row->i_product;?>">
                                                    <input  class="form-control" type="hidden" id="iproductmotif<?= $i;?>" name="iproductmotif<?= $i;?>" value="<?= $row->i_product_motif;?>">
                                                </td>
                                                <td>
                                                    <input class="form-control" readonly id="eproductname<?= $i;?>" name="eproductname<?= $i;?>" value="<?= $row->e_product_name;?>">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" readonly id="emotifname<?= $i;?>" name="emotifname<?= $i;?>" value="<?= $row->e_product_motifname;?>">
                                                    <input type="hidden" class="form-control" id="vproductmill<?= $i;?>" name="vproductmill<?= $i;?>" value="<?= $pangaos;?>">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" id="jmlrata<?= $i;?>" name="jmlrata<?= $i;?>" readonly value="<?= $nrata;?>">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" id="nilairata<?= $i;?>" name="nilairata<?= $i;?>" readonly value="<?= $vrata;?>">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" id="norder<?= $i;?>" name="norder<?= $i;?>" value="<?= $row->n_order;?>" onkeyup="hitungnilai(this.value);">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" id="nacc<?= $i;?>" name="nacc<?= $i;?>" readonly value="<?= $row->n_acc;?>">
                                                </td>
                                                <td>
                                                    <input class="form-control" id="eremark<?= $i;?>" name="eremark<?= $i;?>" value="<?= $row->e_remark;?>">
                                                    <input type="hidden" class="form-control" id="vtotal<?= $i;?>" name="vtotal<?= $i;?>" value="<?= $total;?>">
                                                </td>
                                                <td style="text-align: center;">
                                                <?php
                                                    if($isi->i_approve1 == '' && $isi->i_approve2==null && $isi->i_approve2=='' && $isi->i_sj== ''){?>
                                                        <button type="button" onclick="hapusdetail('<?= $row->i_spmb."','".$row->i_product."','".$row->i_product_grade."','".$row->i_product_motif; ?>'); return false;" title="Delete" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                                    <?}?>
                                                </td>
                                            </tr>
                                        <?
                                         }
                                        }
                                        ?>
                                    </div>
                                    <input type="hidden" name="jml" id="jml" value="<?= $jmlitem;?>">
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
    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date');
        hitungtung();
    });

    var xx = $('#jml').val(); 
    $("#addrow").on("click", function () {
        xx++;
        $("#tabledata").attr("hidden", false);
        $('#jml').val(xx);
        count=$('#tabledata tr').length;
        var newRow = $("<tr>");
        var cols = "";
        cols += '<td style="text-align: center;"><spanx id="snum'+xx+'">'+count+'</spanx><input type="hidden" id="baris'+xx+'" type="text" class="form-control" name="baris'+xx+'" value="'+xx+'"></td>';
        cols += '<td><select id="iproduct'+xx+'" class="form-control select2" name="iproduct'+xx+'" onchange="getdetailproduct('+xx+');"></select><input type="hidden" id="iproductmotif'+xx+'" name="iproductmotif'+xx+'" value=""></td>';
        cols += '<td><input readonly type="text" class="form-control" id="eproductname'+xx+'" name="eproductname'+xx+'" value=""></td>';
        cols += '<td><input readonly type="text" class="form-control" id="emotifname'+xx+'" name="emotifname'+xx+'" value=""><input readonly type="hidden" class="form-control" id="vproductmill'+xx+'" name="vproductmill'+xx+'" value=""></td>';
        cols += '<td><input style="text-align: right;" type="text" readonly class="form-control" id="jmlrata'+xx+'" name="jmlrata'+xx+'" class="form-control" value=""></td>';
        cols += '<td><input style="text-align: right;" type="text" readonly class="form-control" id="nilairata'+xx+'" name="nilairata'+xx+'" class="form-control" value=""></td>';
        cols += '<td><input style="text-align: right;" type="text" class="form-control" id="norder'+xx+'" name="norder'+xx+'" class="form-control" value="" onkeyup="hitungnilai('+xx+');"></td>';
        cols += '<td><input style="text-align: right;" type="text" class="form-control" id="nacc'+xx+'" name="nacc'+xx+'" class="form-control" value="0"></td>';
        cols += '<td><input style="text-align: right;" type="text" class="form-control" id="eremark'+xx+'" name="eremark'+xx+'" class="form-control" value=""><input style="text-align: right;" type="hidden" class="form-control" id="vtotal'+xx+'" name="vtotal'+xx+'" class="form-control" value=""></td>';
        cols += '<td style="text-align: center;"><button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';
        newRow.append(cols);
        $("#tabledata").append(newRow);
        $('#iproduct'+xx).select2({
            placeholder: 'Cari Product / Barang',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getproduct/'); ?>',
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

    function getdetailproduct(id){
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
            var iproduct = $('#iproduct'+id).val();
            var iproductmotif =  $('#iproductmotif'+id).val();
            $.ajax({
                type: "post",
                data: {
                    'iproduct'  : iproduct,
                    'iproductmotif' : iproductmotif
                },
                url: '<?= base_url($folder.'/cform/getdetailproduct'); ?>',
                dataType: "json",
                success: function (data) {
                    $('#iproduct'+id).val(data[0].i_product);
                    $('#eproductname'+id).val(data[0].e_product_name);
                    $('#iproductmotif'+id).val(data[0].i_product_motif);
                    $('#emotifname'+id).val(data[0].e_product_motifname);
                    $('#vproductmill'+id).val(data[0].v_product_mill);
                    getrata(id);
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

    $("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
        /*xx -= 1;*/
        $('#jml').val(xx);
        del();
        hitungtung();
    });

    function del() {
        obj=$('#tabledata tr').find('spanx');
        $.each( obj, function( key, value ) {
            id=value.id;
            $('#'+id).html(key+1);
        });
    }

    function dipales(a){
  	    cek='false';
  	    if((document.getElementById("dspmb").value!='') &&
  	 	    (document.getElementById("iarea").value!='')) {
  	 	    if(a==0){
  	 	    	alert('Isi data item minimal 1 !!!');
  	 	    }else{
   		    	for(i=1;i<=a;i++){
		    		if((document.getElementById("iproduct"+i).value=='') ||
		    			(document.getElementById("eproductname"+i).value=='') ||
		    			(document.getElementById("norder"+i).value=='')){
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

    function hitungnilai(brs){
	    ord=document.getElementById("norder"+brs).value;
	    if (isNaN(parseFloat(ord))){
	    	alert("Input harus numerik");
	    }else{
	    	hrg=formatulang(document.getElementById("vproductmill"+brs).value);
	    	qty=formatulang(ord);
	    	vhrg=parseFloat(hrg)*parseFloat(qty);
	    	document.getElementById("vtotal"+brs).value=formatcemua(vhrg);
	    }
    }

    function hitungtung(){
	    jml=document.getElementById("jml").value;
	    if (isNaN(parseFloat(jml))){
	    	  alert("Input harus numerik");
	    }else{
            total=0;
            for(i=1;i<=jml;i++){
	        	hrg=formatulang(document.getElementById("vproductmill"+i).value);
	        	qty=formatulang(document.getElementById("nacc"+i).value);
	        	harga=parseFloat(hrg)*parseFloat(qty);
	        	total=total+harga;
  	        	document.getElementById("vtotal"+i).value=formatcemua(harga);
//	        			alert(harga);
            }
	    	document.getElementById("vacc").value=formatcemua(total);
	    }
    }

    function getrata(id){
        var iproduct = $('#iproduct'+id).val();
        var iproductmotif = $('#iproductmotif'+id).val();
        var peraw = $('#peraw').val();
        var perak = $('#perak').val();
        var iarea = $('#iarea').val();
        $.ajax({
            type: "post",
            data: {
                'iproduct'  : iproduct,
                'iarea'  : iarea,
                'iproductmotif' : iproductmotif,
                'peraw' : peraw,
                'perak' : perak
            },
            url: '<?= base_url($folder.'/cform/getdetailrata'); ?>',
            dataType: "json",
            success: function (data) {
                if(data.length == 0){
                    $('#jmlrata'+id).val(0);
                    $('#nilairata'+id).val(0);
                }else{
                    $('#jmlrata'+id).val(data[0].vrata);
                    $('#nilairata'+id).val(data[0].nrata);
                }
            },
            error: function () {
                swal('Error :)');
            }
        });
    }

    function hapusdetail(ispmb,iproduct,iproductgrade,iproductmotif) {
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
                        'ispmb' : ispmb,
                        'iproductgrade' : iproductgrade,
                        'iproduct'   : iproduct,
                        'iproductmotif' : iproductmotif
                    },
                    url: '<?= base_url($folder.'/cform/deletedetail'); ?>',
                    dataType: "json",
                    success: function (data) {
                        swal("Dihapus!", "Data berhasil dihapus :)", "success");
                        show('<?= $folder;?>/cform/edit/<?= $ispmb.'/'.$iarea.'/'.$dfrom.'/'.$dto;?>','#main');     
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
