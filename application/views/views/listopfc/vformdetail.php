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
                <div class="col-md-4">
                    <div id="pesan"></div>
                    <div class="form-group row">
                        <label class="col-md-6">No OP</label><label class="col-md-6">Tanggal OP</label>
                        <div class="col-sm-6">
                            <?php 
				                $tmp=explode("-",$isi->d_op);
				                $th=$tmp[0];
				                $bl=$tmp[1];
				                $hr=$tmp[2];
				                $dop=$hr."-".$bl."-".$th;
			                ?>
                            <input type="hidden" id="bop" name="bop" class="form-control" value="<?php echo $bl; ?>" readonly>
                            <input hidden id="i_reff" name="i_reff" value="<?php echo $isi->i_reff; ?>">
                            <input type="text" id="iop" name="iop" class="form-control" value="<?php echo $isi->i_op; ?>">
                        </div>
                        <div class="col-sm-6">
                            <input readonly id="dop" name="dop" class="form-control date" value="<?php echo $dop; ?>" readonly>
			                <input hidden id="tglop" name="tglop" class="form-control date" value="<?php echo $dop; ?>"">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Status</label>
                        <div class="col-sm-12">
                            <input name="eopstatusname" id="eopstatusname" class="form-control" value="<?php echo $isi->e_op_statusname; ?>" readonly>
                            <input id="iopstatus" name="iopstatus" type="hidden" class="form-control" value="<?php echo $isi->i_op_status; ?>">
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
                                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i
                                    class="fa fa-plus"></i>&nbsp;&nbsp;Simpan</button>
                            &nbsp;&nbsp;<button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/view/<?= $dfrom;?>/<?= $dto;?>/","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group row">
                        <label class="col-md-6">SPB</label><label class="col-md-6">Tanggal SPB</label>
                        <div class="col-sm-6">
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
                            <input type="text" id="ispb" name="ispb" class="form-control" maxlength= "15" onkeyup="gede(this)" value="<?php //echo $ispb; ?>" readonly>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" id="dspb" name="dspb" class="form-control date" value="<?php //echo $dspb;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Batas Kirim</label>
                        <div class="col-sm-2">
                            <input style="text-align:middle;" maxlength=3 id="ndeliverylimit" class="form-control" name="ndeliverylimit" class="form-control" value="1">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
		  		            <input name="eopremark" id="eopremark" class="form-control" value="<?= $isi->e_op_remark; ?>" type="text">
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group row">
                        <label class="col-md-2">TOP</label><label class="col-md-10">No OP Lama</label>
                        <div class="col-sm-2">
                            <input readonly style="text-align:middle;" maxlength=3 id="ntoplength" name="ntoplength" class="form-control" value="<?php echo $isi->n_top_length; ?>">
                        </div>
                        <div class="col-sm-10">
			                <input maxlength=7 id="iopold" name="iopold" class="form-control" value="<?php echo $isi->i_op_old; ?>"></td>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Supplier</label>
                        <div class="col-sm-12">
                            <input readonly id="esuppliername" name="esuppliername" class="form-control" value="<?php echo $isi->e_supplier_name; ?>">
			                <input type="hidden" id="isupplier" name="isupplier" class="form-control" value="<?php echo $supplier; ?>">
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-12">Pelanggan</label>
                        <div class="col-sm-12">
                            <input readonly id="ecustomername" name="ecustomername" class="form-control" value="<?php //echo $isi->e_customer_name; ?>">
		                    <input id="icustomer" name="icustomer" type="hidden" class="form-control" value="<?php //echo $isi->i_customer; ?>">
                        </div>
                    </div>
                </div>
                    <input type="hidden" name="jml" id="jml"<?php if(isset($jmlitem)){ echo "value=\"$jmlitem\""; }else{echo "value=\"0\"";}?>>
                    
                            <div class="panel-body table-responsive">
                                <table id="tabledata" class="display table" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="20%">No</th>
                                            <th>Kode Barang</th>
                                            <th>Nama Barang</th>
                                            <th>Motif</th>
                                            <th>Harga</th>
                                            <th>Jml Pesan</th>
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
                    $pangaos=number_format($pangaos);
                    $this->db->select(" * from tm_ic
										where i_product = '$row->i_product' 
										and i_product_motif='$row->i_product_motif'", false);
                    $query = $this->db->get();
                    if ($query->num_rows() > 0){
                        foreach($query->result() as $raw){
                            $jujum=number_format($raw->n_quantity_stock);
                            $nop=number_format($row->n_order);
                        }
                    }else{
                        $jujum='0';
                        $nop=$row->n_order;
                    }
                    echo "<table id=\"tabledata\" class=\"display table\" cellspacing=\"0\" width=\"100%\"><tbody>
                            <tr>
                                <td style=\"width:50px;\"><input readonly type=\"text\" id=\"baris$i\" class=\"form-control\" name=\"baris$i\" value=\"$i\">
                                    <input type=\"hidden\" id=\"motif$i\" name=\"motif$i\" value=\"$row->i_product_motif\" ></td>
                                <td style=\"width:130px;\"><input readonly type=\"text\" id=\"iproduct$i\" class=\"form-control\" name=\"iproduct$i\" value=\"$row->i_product\"></td>
                                <td style=\"width:300px;\"><input readonly type=\"text\" id=\"eproductname$i\" name=\"eproductname$i\" class=\"form-control\" value=\"$row->e_product_name\"></td>
                                <td style=\"width:130px;\"><input readonly type=\"text\" id=\"emotifname$i\" name=\"emotifname$i\" class=\"form-control\" value=\"\"></td>
                                <td style=\"width:120px;\"><input type=\"text\" id=\"vproductmill$i\" readonly name=\"vproductmill$i\" class=\"form-control\" value=\"$pangaos\"></td>
                                <td><input type=\"text\" id=\"nspb$i\" class=\"form-control\" readonly name=\"nspb$i\" value=\"$row->n_order\"></td>
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
</script>
