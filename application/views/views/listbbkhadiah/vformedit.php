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
                        <label class="col-md-6">No BBK</label><label class="col-md-6">Tanggal BBK</label>
                            <div class="col-sm-6">
                                <input readonly id="ibbk" name="ibbk" class="form-control" value="<?php echo $isi->i_bbk; ?>">
                            </div>
                            <div class="col-sm-3">
                            <?php 
				                $tmp=explode("-",$isi->d_bbk);
				                $th =$tmp[0];
				                $bl =$tmp[1];
				                $hr =$tmp[2];
				                $isi->d_bbk=$hr."-".$bl."-".$th;
			                ?>
			                    <input type="text" id="dbbk" name="dbbk" class="form-control date" readonly value="<?php echo $isi->d_bbk; ?>"></td>
                            </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Toko</label>
                            <div class="col-sm-6">
                                <input readonly id="ecustomername" name="ecustomername" class="form-control" value="<?php echo $isi->e_customer_name; ?>">
                                <input readonly type="hidden" id="icustomer" name="icustomer" class="form-control" value="<?php echo $isi->i_customer; ?>">
                            </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-3 col-sm-8">
                            <?php if(($isi->departemen == '1' || $isi->departemen == '7') and $isi->f_bbk_cancel == 'f'){?>
		                        <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales(parseFloat(document.getElementById('jml').value));"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                                &nbsp;&nbsp; 
                                <button type="button" id="addrow" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah Item</button>
                           <? }?>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/view/<?=$dfrom;?>/<?=$dto;?>","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                            &nbsp;&nbsp;
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div id="pesan"></div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                            <div class="col-sm-6">
                                <input id="eremark" name="eremark" class="form-control" type="text" value="<?php echo $isi->e_remark; ?>">
                            </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Total</label>
                        <div class="col-sm-6">
                            <input readonly id="vtotal" name="vtotal" class="form-control" type="text" value="0">
                        </div>
                    </div>
                </div>
                    <div class="table-responsive">
                    <table class="table table-bordered" id="tabledata" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th style="text-align: center; width: 7%;">No</th>
                                        <th style="text-align: center; width: 12%;">Kode Barang</th>
                                        <th style="text-align: center; width: 50%;">Nama Barang</th>
                                        <th style="text-align: center; width: 10%;">Motif</th>
                                        <th style="text-align: center; width: 7%;"">Jumlah</th>
                                        <th style="text-align: center; width: 12%;"">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php               
                                    if($detail){
                                        $i=0;
                                        foreach($detail as $row){
                                                $i++;
                                                $total=$row->v_unit_price*$row->n_quantity;?>
                                                    <tr>
                                                        <td> 
                                                            <input style="width:40px;" readonly type="text" class="form-control" id="baris<?=$i;?>" name="baris<?=$i;?>" value="<?=$i;?>">
                                                            <input style="width:40px;" readonly type="hidden" class="form-control" id="motif<?=$i;?>" name="motif<?=$i;?>" value="<?=$row->i_product_motif;?>">
                                                        </td>
                                                        <td> 
                                                            <input style="width:100px;" readonly type="text" class="form-control" id="iproduct<?=$i; ?>" name="iproduct<?=$i; ?>" value="<?php echo $row->i_product; ?>">
                                                        </td>
                                                        <td> 
                                                            <input readonly type="text" class="form-control" id="eproductname<?=$i; ?>" name="eproductname<?=$i; ?>" value="<?php echo $row->e_product_name; ?>">
                                                        </td>
                                                        <td> 
                                                            <input style="width:100px;" readonly type="text" class="form-control" id="eproductmotifname<?=$i; ?>" name="eproductmotifname<?=$i; ?>" value="<?php echo $row->e_product_motifname; ?>">
                                                            <input style="width:100px;" readonly type="hidden" class="form-control" id="vunitprice<?=$i; ?>" name="vunitprice<?=$i; ?>" value="<?php echo $row->v_unit_price; ?>">
                                                        </td>
                                                        <td> 
                                                            <input style="width:100px;" type="text" class="form-control" id="nquantity<?=$i; ?>" name="nquantity<?=$i; ?>" value="<?php echo $row->n_quantity; ?>">
                                                            <input style="width:100px;" readonly type="hidden" class="form-control" id="nquantityx<?=$i; ?>" name="nquantityx<?=$i; ?>" value="<?php echo $row->n_quantity; ?>">
                                                        </td>
                                                        <td> 
                                                            <input style="width:100px;" readonly type="text" class="form-control" id="eremark<?=$i; ?>" name="eremark<?=$i; ?>" value="<?php echo $row->e_remark; ?>">
                                                            <input style="width:100px;" readonly type="hidden" class="form-control" id="vtotal<?=$i; ?>" name="vtotal<?=$i; ?>" value="<?php echo $total; ?>">
                                                        </td>
                                                    </tr>
                                           <? }
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
        cols += '<td style="text-align: center;"><spanx id="snum'+xx+'">'+count+'</spanx><input type="hidden" id="baris'+xx+'" type="text" class="form-control" name="baris'+xx+'" value="'+xx+'"><input type="hidden" id="motif'+xx+'" name="motif'+xx+'" value=""><input type="hidden" id="grade'+xx+'" name="grade'+xx+'" value=""></td>';
        cols += '<td><select id="iproduct'+xx+ '" class="form-control" name="iproduct'+xx+'" onchange="getdetailproduct('+xx+')";></select></td>';
        cols += '<td><input readonly id="eproductname'+xx+ '" class="form-control" name="eproductname'+xx+'"></td>';
        cols += '<td><input readonly id="emotifname'+xx+ '" class="form-control" name="emotifname'+xx+'"><input type="hidden" id="vunitprice'+xx+'" name="vunitprice'+xx+'" value="0"></td>';
        cols += '<td><input style="text-align: right;" id="nquantity'+xx+ '" class="form-control" name="nquantity'+xx+'" autocomplete="off" onkeypress="return hanyaAngka(event);" onkeyup="ngetang('+xx+');" value="0"></td>';
        cols += '<td><input id="eremark'+xx+ '" class="form-control" name="eremark'+xx+'"></td>';
        cols += '<td><button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';
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
                    'iproduct'  : iproduct
                },
                url: '<?= base_url($folder.'/cform/getdetailproduct'); ?>',
                dataType: "json",
                success: function (data) {
                    $('#eproductname'+id).val(data[0].nama);
                    $('#motif'+id).val(data[0].motif);
                    $('#emotifname'+id).val(data[0].namamotif);
                    $('#grade'+id).val(data[0].grade);
                    $('#vunitprice'+id).val(data[0].harga);
                    $('#nquantity'+id).focus();
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
    });

    function del() {
        obj=$('#tabledata tr').find('spanx');
        $.each( obj, function( key, value ) {
            id=value.id;
            $('#'+id).html(key+1);
        });
    }

    function ngetang(brs){
        jml = $("#nquantity"+brs).val();
        brs = $("#jml").val();
        tot = 0;
        for(i=1;i<=brs;i++){
            hrg=formatulang($("#vunitprice"+i).val());
            qty=formatulang($("#nquantity"+i).val());
            tot=tot+parseFloat(parseFloat(hrg)*parseFloat(qty));
        }
        $("#vtotal").val(formatcemua(tot));
    }

    function dipales(a){
  	 cek='false';
  	 if((document.getElementById("dbbk").value!='') &&
  	 	(document.getElementById("icustomer").value!='')) {
  	 	if(a==0){
  	 		alert('Isi data item minimal 1 !!!');
  	 	}else{
   			for(i=1;i<=a;i++){
				if((document.getElementById("iproduct"+i).value=='') ||
					(document.getElementById("eproductname"+i).value=='') ||
					(document.getElementById("nquantity"+i).value=='')){
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
	  jml=document.getElementById("nquantity"+brs).value;
	  if (isNaN(parseFloat(jml))){
		  alert("Input harus numerik");
	  }else{
      brs=document.getElementById("jml").value;
      tot=0;
 			for(i=1;i<=brs;i++){
        hrg=formatulang(document.getElementById("vunitprice"+i).value);
		    qty=formatulang(document.getElementById("nquantity"+i).value);
        tot=tot+parseFloat(parseFloat(hrg)*parseFloat(qty));
      }
		  document.getElementById("vtotal").value=formatcemua(tot);
	  }
  }

  function hitungtung(){
	  jml=document.getElementById("jml").value;
	  if (isNaN(parseFloat(jml))){
		  alert("Input harus numerik");
	  }else{
      brs=document.getElementById("jml").value;
      tot=0;
 			for(i=1;i<=brs;i++){
        hrg=formatulang(document.getElementById("vunitprice"+i).value);
		    qty=formatulang(document.getElementById("nquantity"+i).value);
        tot=tot+parseFloat(parseFloat(hrg)*parseFloat(qty));
      }
		  document.getElementById("vtotal").value=formatcemua(tot);

	  }
  }
</script>
