<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>

            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/updateop' array('id' => 'spbformupdate', 'name' => 'spbformupdate', 'onsubmit' => 'sendRequest(); return false')), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div class="col-md-6">
                <div id="pesan"></div>
                    <div class="form-group">
                        <label class="col-md-12">No OP</label>
                        <div class="col-sm-12">
                        <?php 
				            $tmp=explode("-",$isi->d_op);
				            $th=$tmp[0];
				            $bl=$tmp[1];
				            $hr=$tmp[2];
				            $dop=$hr."-".$bl."-".$th;
			            ?>
                        <input type="hidden" id="bop" name="bop" class="form-control" value="<?php echo $bl; ?>" readonly>
                        <input type="text" id="iop" name="iop" class="form-control" value="<?php echo $isi->i_op; ?>">
                        <input readonly id="dop" name="dop" class="form-control date" value="<?php echo $dop; ?>" readonly">
			            <input hidden id="tglop" name="tglop" class="form-control date" value="<?php echo $dop; ?>"">
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
		  		        <input name="eopremark" id="eopremark" value="<?= $isi->e_op_remark; ?>" type="text">
                        </div>
                    </div>
                    <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-12">SPB</label>
                        <div class="col-sm-12">
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
                        <input type="text" id="ispb" name="ispb" class="form-control" maxlength= "15" onkeyup="gede(this)" value="<?php echo $ispb; ?>" readonly>
                        <input type="text" id="dspb" name="dspb" class="form-control date" value="<?php echo $dspb;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-12">
                        <input readonly id="eareaname" name="eareaname" value="<?php echo $isi->e_area_name; ?>">
		                <input id="iarea" name="iarea" type="hidden" value="<?php echo $isi->i_area; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-info btn-rounded btn-sm" onclick="dipales(parseFloat(document.getElementById('jml').value));"> <i
                                    class="fa fa-plus"></i>&nbsp;&nbsp;Simpan</button>
                        </div>
                    </div>
                </div>


                <div class="form-group">
                    <label class="col-md-12">Batas Kirim</label>
                    <input style="text-align:right;" maxlength=3 id="ndeliverylimit" name="ndeliverylimit" class="form-control" value="1">
                </div>
                <div class="form-group">
                    <label class="col-md-12">TOP</label>
                    <input readonly style="text-align:right;" maxlength=3 id="ntoplength" name="ntoplength" class="form-control" value="<?php echo $isi->n_top_length; ?>">
			        <input maxlength=7 id="iopold" name="iopold" class="form-control" value="<?php echo $isi->i_op_old; ?>"></td>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Supplier</label>
                    <div class="col-sm-12">
                        <input readonly  id="esuppliername" name="esuppliername" value="<?php echo $isi->e_supplier_name; ?>">
			            <input type="hidden" id="isupplier" name="isupplier" value="<?php echo $supplier; ?>">
                    </div>
                </div> 
                <div class="form-group">
                    <label class="col-md-12">Pelanggan</label>
                    <div class="col-sm-12">
                    <input readonly id="ecustomername" name="ecustomername" value="<?php echo $isi->e_customer_name; ?>">
		            <input id="icustomer" name="icustomer" type="hidden" value="<?php echo $isi->i_customer; ?>">
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

                <div id="detailisi" align="center">
				<?php 				
				$i=0;
				foreach($detail as $row){
			  	    $i++;
				    	$pangaos=$row->v_product_mill;
				    	$pangaos=number_format($pangaos,2);
				    	$jujum=$row->n_stock;
                    if(isset($row->n_saldo)){
                        $nop=$row->n_acc-$row->n_stock;
                    }else{
				    	$nop=$row->n_order-$row->n_deliver;
                    }
                    $jujum=number_format($jujum);
                    echo "<table id=\"tabledata\" class=\"display table\" cellspacing=\"0\" width=\"100%\"><tbody>
                            <tr>
                            <td style=\"width:50px;\"><input readonly type=\"text\" id=\"baris$i\" class=\"form-control\" name=\"baris$i\" value=\"$i\">
                                <input type=\"hidden\" id=\"motif$i\" name=\"motif$i\" value=\"$row->i_product_motif\" ></td>
                            <td style=\"width:130px;\"><input readonly type=\"text\" id=\"iproduct$i\" class=\"form-control\" name=\"iproduct$i\" value=\"$row->i_product\"></td>
                            <td style=\"width:300px;\"><input readonly type=\"text\" id=\"eproductname$i\" name=\"eproductname$i\" class=\"form-control\" value=\"$row->e_product_name\"></td>
                            <td style=\"width:130px;\"><input readonly type=\"text\" id=\"emotifname$i\" name=\"emotifname$i\" class=\"form-control\" value=\"($row->e_product_motifname) - $row->e_remark\"></td>
                            <td style=\"width:120px;\"><input type=\"text\" id=\"vproductmill$i\" readonly name=\"vproductmill$i\" class=\"form-control\" value=\"$pangaos\"></td>";
                    if(isset($row->n_saldo)){
                            echo "<td><input type=\"text\" id=\"nspb$i\" class=\"form-control\" readonly name=\"nspb$i\" value=\"$row->n_saldo\"></td>";
                    }else{
                            echo "<td><input type=\"text\" id=\"nspb$i\" class=\"form-control\" readonly name=\"nspb$i\" value=\"$row->n_order\"></td>";
                    }
				    	    echo "<td><input type=\"text\" id=\"nquantitystock$i\" name=\"nquantitystock$i\" class=\"form-control\" value=\"$jujum\" readonly></td>
				    			  <td><input type=\"text\" id=\"norder$i\" name=\"norder$i\" class=\"form-control\" value=\"$nop\"></td>
				    		</tr>
				    		</tbody></table>";
				}
				?>
			    </div>
                </div>
                </form>
                </div>
            </form>
        </div>
    </div>
</div>
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
	if((document.getElementById("iopstatus").value!='') && (document.getElementById("dop").value!='')){
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
</script>
