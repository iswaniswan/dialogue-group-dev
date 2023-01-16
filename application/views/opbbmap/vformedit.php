<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>

            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div class="col-md-6">
                <div id="pesan"></div>
                    <div class="row">
                        <label class="col-md-12">Tanggal</label>
                        <?php if(isset($isi->i_spb_old)) {
			                        $old=$isi->i_spb_old; 
		                       }elseif(isset($isi->i_spmb_old)){
			                        $old=$isi->i_spmb_old; 
		                       }else{
			                        $old="";
		                       }
		                ?>
                        <div class="col-sm-6">
                        <input type="text" id="dop" name="dop" class="form-control date" value="">
                        </div>
                        <div class="col-sm-6">
                        <input type="text" id="asal" name="asal" class="form-control" value="<?php if(isset($old)) echo $old;?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Status</label>
                        <select name="iopstatus" id="iopstatus" class="form-control select2" onchange="getopstatus(this.value);">
                                <option value="">-- Pilih Status OP --</option>
                                <?php foreach ($opstatus as $iopstatus):?>
                                <option value="<?php echo $iopstatus->i_op_status;?>">
                                    <?php echo $iopstatus->e_op_statusname;?>
                                </option>
                                <?php endforeach; ?>                              
                        </select>
                        <input id="eopstatusname" name="eopstatusname" type="hidden">
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                        <?php 
			                if($isi->e_customer_name!=''){
		                ?>
		  		            <input name="eopremark" id="eopremark" value="<?= $isi->e_customer_name; ?>" type="text">
		                <?php 
			                }else{
		                ?>
		  		            <input name="eopremark" id="eopremark" value="" type="text">
		                <?php 
			                }
		                ?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-12">SPB</label>
                        <?php 
			                if(isset($isi->d_spb)){
				                $tmp=explode("-",$isi->d_spb);
				                $th=$tmp[0];
				                $bl=$tmp[1];
				                $hr=$tmp[2];
				                $dspb=$hr."-".$bl."-".$th;
			                }else if(isset($isi->d_spmb)){
				                $tmp=explode("-",$isi->d_spmb);
				                $th=$tmp[0];
				                $bl=$tmp[1];
				                $hr=$tmp[2];
				                $dspb=$hr."-".$bl."-".$th;
			                }
		                ?>
                        <div class="col-sm-6">
                        <input type="text" id="ispb" name="ispb" class="form-control" maxlength= "15" onkeyup="gede(this)" value="<?php echo $ispb; ?>" readonly>
                        </div>
                        <div class="col-sm-6">
                        <input type="text" id="dspb" name="dspb" class="form-control" value="<?php echo $dspb;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-12">
                        <input type="text" id="eareaname" class="form-control" name="eareaname" value="<?php echo $isi->e_area_name; ?>" readonly >
		                <input id="iarea" name="iarea" class="form-control" type="hidden" value="<?php echo $isi->i_area; ?>">
                        </div>
                    </div>                
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-info btn-rounded btn-sm" onclick="dipales(parseFloat(document.getElementById('jml').value));"> <i
                                    class="fa fa-plus"></i>&nbsp;&nbsp;Simpan</button>
                        </div>
                    </div>
                </div>


                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-12">Batas Kirim</label>
                        <div class="col-sm-12">
                        <input type="text" maxlength=3 id="ndeliverylimit" name="ndeliverylimit" value="1">
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-12">TOP</label>
                        <div class="col-sm-6">
                        <input type="text" maxlength=3 class="form-control" id="nsuppliertoplength" name="nsuppliertoplength" value="" readonly>
                        </div>
                        <div class="col-sm-6">
                        <input maxlength=7 id="iopold" class="form-control" name="iopold" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Supplier</label>
                        <div class="col-sm-12">
                        <select name="isupplier" id="isupplier" class="form-control select2" onchange="get(this.value);">
                            <option value="">-- Pilih Supplier --</option>
                            <?php foreach ($supplier as $isupplier):?>
                            <option value="<?php echo $isupplier->i_supplier;?>">
                                <?= $isupplier->i_supplier." - ".$isupplier->e_supplier_name;?></option>
                            <?php endforeach; ?>
                        </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Pelanggan</label>
                        <div class="col-sm-12">
                        <input id="ecustomername" name="ecustomername" onclick="view_pelanggan()" class="form-control" value="<?php if(isset($isi->e_customer_name)) echo $isi->e_customer_name; ?>" readonly>
		                <input id="icustomer" name="icustomer" type="hidden" value="<?php if(isset($isi->i_customer)) echo $isi->i_customer; ?>">
                        </div>
                    </div>
                </div>
                    <input type="hidden" name="jml" id="jml"<?php if(isset($jmlitem)){ echo "value=\"$jmlitem\""; }else{echo "value=\"0\"";}?>>
                        <div class="panel-body table-responsive">
                            <table id="tabledata" class="display table" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Barang</th>
                                        <th>Nama Barang</th>
                                        <th>Motif</th>
                                        <th>Harga</th>
                                        <th>Jml Pesan</th>
                                        <th>Jml Stock</th>
                                        <th>Jml OP</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>

            <div id="detailisi" align="center">
				<?php 				
				echo "<table id=\"tabledata\" class=\"display table\" cellspacing=\"0\" width=\"100%\">";
				$i=0;
				foreach($detail as $row)
				{
				  	$i++;
					$pangaos=$row->v_product_mill;
					$pangaos=number_format($pangaos,2);			
					$jujum=$row->n_stock;
					if($jujum>=$row->n_order){					
						$nop=$row->n_order;
					}else{
						$nop=$row->n_order-$jujum;
					}
					$jujum=number_format($jujum);
					echo "<tbody>
							<tr>
		    				<td style=\"width:50px;\"><input class=\"form-control\" type=\"text\" 
								id=\"baris$i\" name=\"baris$i\" class=\"form-control\" value=\"$i\"><input type=\"hidden\" id=\"motif$i\" 
								name=\"motif$i\" value=\"$row->i_product_motif\"></td>
							<td style=\"width:130px;\"><input class=\"form-control\" type=\"text\" 
								id=\"iproduct$i\" name=\"iproduct$i\" value=\"$row->i_product\"></td>
							<td td style=\"width:350px;\"><input class=\"form-control\" 
								type=\"text\" id=\"eproductname$i\"
								name=\"eproductname$i\" value=\"$row->e_product_name\"></td>
							<td style=\"width:70px;\"><input class=\"form-control\" 
								type=\"text\" id=\"emotifname$i\"
								name=\"emotifname$i\" value=\"$row->e_product_motifname\"></td>
							<td><input 
								type=\"text\" id=\"vproductmill$i\" class=\"form-control\"
								name=\"vproductmill$i\" value=\"$pangaos\"></td>
							<td><input
								type=\"text\" id=\"nspb$i\" class=\"form-control\" name=\"nspb$i\" value=\"$row->n_order\" 
								onkeyup=\"hitungnilai(this.value,'$jmlitem')\"></td>
							<td><input
								type=\"text\" id=\"nquantitystock$i\" class=\"form-control\" name=\"nquantitystock$i\" 
								value=\"$jujum\"></td>
							<td><input 
								type=\"text\" id=\"norder$i\" name=\"norder$i\" class=\"form-control\" value=\"$nop\"></td>
							</tr>
						  </tbody>";
				}
				echo "<input type=\"hidden\" id=\"ispbdelete\" name=\"ispbdelete\" value=\"\">
		      		  <input type=\"hidden\" id=\"iproductdelete\" name=\"iproductdelete\" value=\"\">
		      		  <input type=\"hidden\" id=\"iproductgradedelete\" name=\"iproductgradedelete\" value=\"\">
					  <input type=\"hidden\" id=\"vdis1\" name=\"vdis1\" value=\"\">
					  <input type=\"hidden\" id=\"vdis2\" name=\"vdis2\" value=\"\">
					  <input type=\"hidden\" id=\"vdis3\" name=\"vdis3\" value=\"\">
					  <input type=\"hidden\" id=\"vtotdis\" name=\"vtotdis\" value=\"\">
					  <input type=\"hidden\" id=\"vtot\" name=\"vtot\" value=\"\">
					  <input type=\"hidden\" id=\"vtotbersih\" name=\"vtotbersih\" value=\"\">
		     		 ";
				?>
			</div>
                </div>
                </form>
                </div>
            </form>
        </div>
    </div>
</div>
<input type="hidden" name="jml" id="jml" 
	<?php if(isset($jmlitem)){ echo "value=\"$jmlitem\""; }else{echo "value=\"0\"";}?>>
<div id="pesan"></div>
    </td>
  </tr>
</table>

<script language="javascript" type="text/javascript">
$("form").submit(function(event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
});

$(document).ready(function () {
    $(".select2").select2();
    showCalendar('.date');
});

function getopstatus(iopstatus) {
    $.ajax({
    type: "POST",
    url: "<?php echo site_url($folder.'/Cform/getopstatus');?>",
    data:"iopstatus="+iopstatus,
    dataType: 'json',
    success: function(data){
        $("#iopstatus").val(data.iopstatus);
        $("#eopstatusname").val(data.eopstatusname);
    },

    error:function(XMLHttpRequest){
        alert(XMLHttpRequest.responseText);
    }

    })
}

function dipales(a){
	cek='false';
	if((document.getElementById("isupplier").value!='') &&
	   (document.getElementById("iopstatus").value!='') &&
	   (document.getElementById("dop").value!='')
	)
	{
  	 	if(a==0){
  	 		alert('Isi data item minimal 1 !!!');
  	 	}else{
    			for(i=1;i<=a;i++){
					if((document.getElementById("norder"+i).value=='')){
						alert('Data item masih ada yang salah !!!');
						cek='false';
					}else{
						cek='true';	
					} 
				}
		}
		if(cek=='true'){
  	  		document.getElementById("login").disabled=true;
    	}else{
		    document.getElementById("login").disabled=false;
		}
	}else{
  		alert('Data header masih ada yang salah !!!');
	}
}

function get(id) {
    $.ajax({
        type: "post",
        data: {
            'i_supplier': id
        },
        url: '<?= base_url($folder.'/Cform/getsupplier'); ?>',
        dataType: "json",
        success: function (data) {
            $('#isuppliergroup').val(data[0].i_supplier_group);
            $('#esuppliername').val(data[0].e_supplier_name);
            $('#nsuppliertoplength').val(data[0].n_supplier_toplength);
        },
        error: function () {
            alert('Error :)');
        }
    });
}

function getdataitem(){
    
}
</script>
