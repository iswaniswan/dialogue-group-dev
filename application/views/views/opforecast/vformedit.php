<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"></a>
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
                        <input type="text" id="dop" name="dop" class="form-control date"value="<?php echo $tgl; ?>">
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
                                <?= $iopstatus->i_op_status." - ".$iopstatus->e_op_statusname;?>
                                </option>
                                <?php endforeach; ?>                              
                        </select>
                        <div class="col-sm-6">
                        <input id="eopstatusname" name="eopstatusname" type="hidden">
                        </div>
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
		  		            <input name="eopremark" id="eopremark" value="" type="text" onkeyup="gede(this)">
		                <?php 
			                }
		                ?>
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
                        <input readonly id="eareaname" name="eareaname" value="<?php echo $isi->e_area_name; ?>">
		                <input id="iarea" name="iarea" type="hidden" value="<?php echo $isi->i_area; ?>">
                        </div>
                    </div>                    
                    
                    <div class="form-group">
                        <label class="col-md-12">OP Lama</label>
                        <div class="col-sm-12">
                            <input type="text" name="iopold" id="iopold" value="<?php echo $iopold; ?>">
                        </div>
                    </div>
                </div>
                    <input type="hidden" name="jml" id="jml"<?php if(isset($jmlitem)){ echo "value=\"$jmlitem\""; }else{echo "value=\"0\"";}?>>
                    
                    <div class="panel-body table-responsive">
                        <table id="tabledata" class="display table" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th width="20%">Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Motif</th>
                                    <th>Harga</th>
                                    <th>Jumlah Pesan</th>
                                    <th>Jumlah Stock</th>
                                    <th>Jumlah OP</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div id="detailisi" align="center">
                        <?php
                            $i=0;
                            if($detail){
                                foreach($detail as $row){
                                    $i++;
					                $pangaos=$row->v_product_mill;
					                $pangaos=number_format($pangaos,2);
                                    $jujum=$row->saldofc;
                                    if(isset($row->n_saldo)){
                                        $nop=$row->n_acc-$row->n_stock;
                                    }else{
                                        $nop=$row->n_order-$row->n_deliver;
                                    }
                                    $jujum=number_format($jujum);
					                echo "<table class=\"listtable\" style=\"width:750px;\"><tbody>
					                		<tr>
		    		                		<td style=\"width:22px;\"><input style=\"width:22px;\" readonly type=\"text\" 
					                			id=\"baris$i\" name=\"baris$i\" value=\"$i\"><input type=\"hidden\" id=\"motif$i\" 
					                			name=\"motif$i\" value=\"$row->i_product_motif\"></td>
					                		<td style=\"width:64px;\"><input style=\"width:64px;\" readonly type=\"text\" 
					                			id=\"iproduct$i\" name=\"iproduct$i\" value=\"$row->i_product\"></td>
					                		<td style=\"width:258px;\"><input style=\"width:258px;\" readonly 
					                			type=\"text\" id=\"eproductname$i\"
					                			name=\"eproductname$i\" value=\"$row->e_product_name\"></td>
					                		<td style=\"width:148px;\"><input style=\"width:148px;\" readonly 
					                			type=\"text\" id=\"emotifname$i\"
					                			name=\"emotifname$i\" value=\"($row->e_product_motifname) - $row->e_remark\"></td>
					                		<td style=\"width:92px;\"><input style=\"text-align:right; width:92px;\" 
					                			type=\"text\" id=\"vproductmill$i\" readonly
                                                name=\"vproductmill$i\" value=\"$pangaos\"></td>";
                                    if(isset($row->n_saldo)){
                                        echo "<td style=\"width:38px;\"><input style=\"text-align:right; width:38px;\" 
                                                type=\"text\" id=\"nspb$i\" readonly	name=\"nspb$i\" value=\"$row->n_saldo\"></td>";
                                    }else{
                                        echo "<td style=\"width:38px;\"><input style=\"text-align:right; width:38px;\" 
                                                type=\"text\" id=\"nspb$i\" readonly	name=\"nspb$i\" value=\"$row->n_order\"></td>";
                                    }
                                        echo "<td style=\"width:42px;\"><input style=\"text-align:right; width:42px;\" 
                                                type=\"text\" id=\"nquantitystock$i\" name=\"nquantitystock$i\" 
                                                value=\"$jujum\" readonly></td>";
                                        echo "<td style=\"width:38px;\"><input style=\"text-align:right; width:38px;\" 
                                                type=\"text\" id=\"norder$i\" name=\"norder$i\" value=\"$nop\"></td>
                                        </tr>
                                        </tbody></table>";
                                }
                            }
                        ?>
                    </div>
                    <input type="hidden" name="jml" id="jml"  value="<?php echo $i;?>">
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

function dipales(a){
	cek='false'; 
    if((document.getElementById("dop").value!='') && 
        (document.getElementById("iopstatus").value!='') && 
        (document.getElementById("iarea").value!='')) {    
        if(a==0){
            alert('Isi data item minimal 1 !!!');
            return false;
        }else{                
            for(i=1;i<=a;i++){                    
                if((document.getElementById("iproduct"+i).value=='') || 
                    (document.getElementById("eproductname"+i).value=='') || 
                    (document.getElementById("norder"+i).value=='')){
                    alert('Data item masih ada yang salah !!!');                    
                    return false;
                    cek='false';
                }else{
                    return true;
                    cek='true'; 
                } 
            }
        }
        if(cek=='true'){
            document.getElementById("submit").disabled=true;
        }else{
            return false;
        }
    }else{
        alert('Data header masih ada yang salah !!!');
        return false;
    }
}
</script>
