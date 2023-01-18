<?php 
include ("php/fungsi.php");
?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>

            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div class="col-md-6">
                    <div id="pesan"></div>
                    <div class="form-group row">
                    <label class="col-md-6">No SPB</label><label class="col-md-6">Tanggal SPB</label>
                        <?php 
			                if($isi->d_spb!='') {
			                	$tmp=explode("-",$isi->d_spb);
			                	$th=$tmp[0];
			                	$bl=$tmp[1];
			                	$hr=$tmp[2];
			                	$dspb=$hr."-".$bl."-".$th;
			                } else {
			                	$dspb='';
			                }	
			                if($isi->d_sj!='') {
			                	$tmp=explode("-",$isi->d_sj);
			                	$th=$tmp[0];
			                	$bl=$tmp[1];
			                	$hr=$tmp[2];
			                	$isi->d_sj=$hr."-".$bl."-".$th;
			                }	
		                ?>
                        <div class="col-sm-6">
                            <input readonly type="text" id="ispb" name="ispb" class="form-control" type="text" value="<?php echo $isi->i_spb; ?>">
                        </div>
                        <div class="col-sm-3">
			                <input readonly type="text" id="dspb" name="dspb" class="form-control date" value="<?php echo $dspb; ?>">
                            <input readonly type="hidden" id="iperiode" name="iperiode" class="form-control" value="<?php echo $iperiode; ?>">
                            <input readonly type="hidden" id="dspbsys" name="dspbsys" class="form-control date" value="<?php echo $dspb; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-6">
                            <input readonly id="eareaname" name="eareaname" class="form-control" value="<?php echo $isi->e_area_name; ?>">
		                    <input id="iarea" name="iarea" type="hidden" class="form-control" value="<?php echo $isi->i_area; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Pelanggan</label>
                        <div class="col-sm-6">
                            <input readonly id="ecustomername" name="ecustomername" class="form-control" value="<?php echo $isi->e_customer_name; ?>">
		                    <input id="icustomer" name="icustomer" type="hidden" class="form-control" value="<?php echo $isi->i_customer; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">PO</label>
                        <div class="col-sm-6">
                            <input id="ispbpo" name="ispbpo" class="form-control" value="<?php echo $isi->i_spb_po; ?>" maxlength="30" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Konsinyasi</label>
                        <div class="col-sm-3">
                            <div class="form-check has-error">
                                <label class="custom-control custom-checkbox">
                                    <input type="checkbox" id="fspbconsigment" name="fspbconsigment" class="custom-control-input" 
                                        <?php if($isi->f_spb_consigment=='t'){ 
                                               echo 'checked  value="on"';
                                            }else{
                                                echo 'value=""';
                                            }?>>
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">SPB Lama</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <input id="ispbold" name="ispbold" type="text" class="form-control" value="<?php echo $isi->i_spb_old; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">TOP</label>
                        <div class="col-sm-5">
                            <input maxlength="3" id="nspbtoplength" name="nspbtoplength" readonly class="form-control" value="<?php echo $isi->n_spb_toplength; ?>">
                        </div>
                        <div class="col-sm-4">
                            <div class="form-check has-error">
                                <label class="custom-control custom-checkbox">
                                    <input type="checkbox" id="fspbstockdaerah" name="fspbstockdaerah" class="custom-control-input" 
                                    <?php if($isi->f_spb_stockdaerah=='t') {
				                        echo 'checked  value="on"';}else{echo 'value=""';} ?>" <?php if($isi->i_nota=='') echo "onclick=pilihstockdaerah(this.value);"?>>
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">Stock Daerah</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Salesman</label>
                        <div class="col-sm-6">
                            <input readonly id="esalesmanname" name="esalesmanname" class="form-control" value="<?php echo $isi->e_salesman_name.'-'.$isi->i_salesman; ?>">
		                    <input id="isalesman" name="isalesman" type="hidden" class="form-control" value="<?php echo $isi->i_salesman; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Stock Daerah</label>
                        <div class="col-sm-6">
                            <input id="fspbstokdaerah" name="fspbstokdaerah" class="form-control" type="hidden">
			                <input id="fspbsiapnotagudang" name="fspbsiapnotagudang" type="hidden" <?php if($isi->f_spb_siapnotagudang=='t'){
                                                                              echo "value='on'";
                                                                            }else{
                                                                              echo "value=''";
                                                                            }
                                                                         ?>>
		                    <input id="f_spb_op" name="f_spb_op" type="hidden" <?php if($isi->f_spb_op=='t'){
                                                                                              echo "value='on'";
                                                                                            }else{
                                                                                              echo "value=''";
                                                                                            }
                                                                                         ?>>
		                    <input id="f_spb_program" name="f_spb_program" type="hidden" <?php if($isi->f_spb_program=='t'){
                                                                                              echo "value='on'";
                                                                                            }else{
                                                                                              echo "value=''";
                                                                                            }
                                                                                         ?>>
		                    <input id="f_spb_cancel" name="f_spb_cancel" type="hidden" <?php if($isi->f_spb_cancel=='t'){
                                                                                              echo "value='on'";
                                                                                            }else{
                                                                                              echo "value=''";
                                                                                            }
                                                                                         ?>>
                            <input readonly type="text" id="isj" name="isj" class="form-control" value="<?php if($isi->i_sj) echo $isi->i_sj; ?>" readonly>
                        </div>
                        <div class="col-sm-3">
		                    <input readonly type="text" id="dsj" name="dsj" class="form-control date" value="<?php if($isi->d_sj) echo $isi->d_sj; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">PKP</label>
                        <div class="col-sm-6">
                            <input id="fspbplusppn" name="fspbplusppn" type="hidden" class="form-control" value="<?php echo $isi->f_spb_plusppn;?>">
			                <input id="fspbplusdiscount" name="fspbplusdiscount" type="hidden" class="form-control" value="<?php echo $isi->f_spb_plusdiscount;?>">
			                <input id="fspbpkp" name="fspbpkp" type="hidden" class="form-control" value="<?php echo $isi->f_spb_pkp;?>">
			                <input id="fcustomerfirst" name="fcustomerfirst" type="hidden" class="form-control" value="<?php echo $isi->f_customer_first;?>">
			                <input type="text" id="ecustomerpkpnpwp" name="ecustomerpkpnpwp" readonly class="form-control" value="<?php echo $isi->e_customer_pkpnpwp;?>">
                        </div>
                    </div>
                    <div class="form-group row">
                    <div class="col-sm-offset-5 col-sm-8">
                        <?php if($isi->f_spb_plusppn == 't'){
                            echo '*Sesudah PPN & Sebelum Diskon';
                        }else{
                            echo '*Sebelum PPN & Sebelum Diskon';
                        }?>
                        
		            </div>
                        <div class="col-sm-offset-5 col-sm-8">
                            <br>
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales(parseFloat(document.getElementById('jml').value));"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                            &nbsp;&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/view/<?=$dfrom;?>/<?=$dto;?>/<?=$iarea;?>","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>                    
                    </div>
                </div>
                <div class="col-md-6">
                    <div id="pesan"></div>
                    <div class="form-group">
                        <label class="col-md-12">Kelompok Harga</label>
                        <div class="col-sm-8">
                        <?php if($isi->e_price_groupname==''){
                                $isi->e_price_groupname=$isi->i_price_group;
                        }?>
                            <input readonly id="epricegroupname" name="epricegroupname" class="form-control" value="<?php echo $isi->e_price_groupname; ?>">
		                    <input id="ipricegroup" name="ipricegroup" type="hidden" class="form-control" value="<?php echo $isi->i_price_group; ?>">
                            <input id="istore" name="istore" type="hidden" value="<?php echo $isi->i_store; ?>">
                            <input id="istorelocation" name="istorelocation" type="hidden" value="<?php echo $isi->i_store_location; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nilai Kotor</label>
                        <div class="col-sm-6">
                        <?php 
	                        if($nilaiorderspb==$isi->v_spb){
	                        	$norderspbbefore= $isi->v_spb;
	                        	$disc1parsing	= explode(".",$isi->n_spb_discount1,strlen($isi->n_spb_discount1));
                                if($isi->f_spb_consigment!='t'){
  	                            	$disc1		= ($norderspbbefore * $disc1parsing[0])/100;
                                }else{
                                    $disc1    = $isi->v_spb_discounttotal;
                                }
	                            	$disc1parsing2	= explode(".",$isi->n_spb_discount2,strlen($isi->n_spb_discount2));
                                if($isi->f_spb_consigment!='t'){
	                            	$disc2		= ($norderspbbefore * $disc1parsing2[0])/100;
                                }else{
                                    $disc2    = $isi->v_spb_discount2;
                                }
	                            $disc1parsing3	= explode(".",$isi->n_spb_discount3,strlen($isi->n_spb_discount3));
	                            if($isi->f_spb_consigment!='t'){
                                        $disc3		= ($norderspbbefore * $disc1parsing3[0])/100;			
                                }else{
                                        $disc3    = $isi->v_spb_discount3;
                                }
	                            if($isi->f_spb_consigment!='t'){
  	                            	$disctot  = $disc1+$disc2+$disc3;
                                }else{
                                    $disctot  = $isi->v_spb_discounttotal;
                                }
	                        	$norderspbafter	= ($isi->v_spb - $disctot);
                            }elseif($isi->v_spb_after<$nilaiorderspb){
	                        	$norderspbbefore= $nilaiorderspb;
	                        	$disc1parsing	= explode(".",$isi->n_spb_discount1,strlen($isi->n_spb_discount1));
	                        	if($isi->f_spb_consigment!='t'){
                                    $disc1		= ($norderspbbefore * $disc1parsing[0])/100;
                                }else{
                                    $disc1    = $isi->v_spb_discounttotal;
                                }
	                            	$disc1parsing2	= explode(".",$isi->n_spb_discount2,strlen($isi->n_spb_discount2));
	                            if($isi->f_spb_consigment!='t'){
                                    $disc2		= ($norderspbbefore * $disc1parsing2[0])/100;
                                }else{
                                    $disc2    = $isi->v_spb_discount2;
                                }
	                            $disc1parsing3	= explode(".",$isi->n_spb_discount3,strlen($isi->n_spb_discount3));
	                            if($isi->f_spb_consigment!='t'){
                                    $disc3		= ($norderspbbefore * $disc1parsing3[0])/100;
                                }else{
                                    $disc3    = $isi->v_spb_discount3;
                                }
	                            if($isi->f_spb_consigment!='t'){
  	                            	$disctot  = $disc1+$disc2+$disc3;
                                }else{
                                  $disctot  = $isi->v_spb_discounttotal;
                                }
	                        	    $norderspbafter	= ($isi->v_spb - $disctot);
	                        }else{
	                        	$norderspbbefore= $nilaiorderspb;
	                        	$disc1parsing		= explode(".",$isi->n_spb_discount1,strlen($isi->n_spb_discount1));
	                        	if($isi->f_spb_consigment!='t'){
                                    $disc1		= ($norderspbbefore * $disc1parsing[0])/100;
                                }else{
                                    $disc1    = $isi->v_spb_discounttotal;
                                }
	                            $disc1parsing2	= explode(".",$isi->n_spb_discount2,strlen($isi->n_spb_discount2));
	                        	if($isi->f_spb_consigment!='t'){
                                    $disc2		= ($norderspbbefore * $disc1parsing2[0])/100;
                                }else{
                                    $disc2    = $isi->v_spb_discount2;
                                }
	                        	$disc1parsing3	= explode(".",$isi->n_spb_discount3,strlen($isi->n_spb_discount3));
	                        	if($isi->f_spb_consigment!='t'){
                                    $disc3		= ($norderspbbefore * $disc1parsing3[0])/100;
                                }else{
                                    $disc3    = $isi->v_spb_discount3;
                                }
	                        	if($isi->f_spb_consigment!='t'){
  	                        	    $disctot  = $disc1+$disc2+$disc3;
                                }else{
                                    $disctot  = $isi->v_spb_discounttotal;
                                }
#	                        		$norderspbafter	= ($nilaiorderspb - (($disc1+$disc2+$disc3)));
	                        	$norderspbafter	= ($isi->v_spb - $disctot);
	                        }
	                        ?>
                            <input id="vspb" name="vspb" class="form-control" value="<?php echo number_format($norderspbbefore); ?>">
                            <input type="hidden" id="vspbx" name="vspbx" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Discount 1</label>
                        <div class="col-sm-3">
                            <input id ="ncustomerdiscount1"name="ncustomerdiscount1" class="form-control" value="<?php echo $isi->n_spb_discount1; ?>">
                        </div>
                        <div class="col-sm-5">
		                    <input id="vcustomerdiscount1" name="vcustomerdiscount1" class="form-control" value="<?php echo number_format($disc1); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Discount 2</label>
                        <div class="col-sm-3">
                            <input readonly id="ncustomerdiscount2" name="ncustomerdiscount2" class="form-control" value="<?php echo $isi->n_spb_discount2; ?>">
                        </div>
                        <div class="col-sm-3">
		                    <input readonly id="vcustomerdiscount2" name="vcustomerdiscount2" class="form-control" value="<?php echo number_format($disc2); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Discount 3</label>
                        <div class="col-sm-3">
                            <input readonly id="ncustomerdiscount3" name="ncustomerdiscount3" class="form-control" value="<?php echo $isi->n_spb_discount3; ?>">
		                </div>
                        <div class="col-sm-5">
                            <input readonly id="vcustomerdiscount3" name="vcustomerdiscount3" class="form-control" value="<?php echo number_format($disc3); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Discount Total</label>
                        <div class="col-sm-6">
                            <input <?php if( ($isi->n_spb_discount1!='0.00') || ($isi->n_spb_discount2!='0.00') || ($isi->n_spb_discount2!='0.00') ){
						                echo "readonly ";
					                }?>id="vspbdiscounttotal" name="vspbdiscounttotal" class="form-control" value="<?php echo number_format($disctot); ?>" onkeyup="hitungnilaiy($this.value);"></td>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nilai Bersih</label>
                        <div class="col-sm-6">
                        <input readonly id="vspbbersih" name="vspbbersih" class="form-control" value="<?php echo number_format($norderspbafter); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Discount Total (Realisasi) </label>
                        <div class="col-sm-6">
                        <input id="vspbdiscounttotalafter" name="vspbdiscounttotalafter" readonly class="form-control" value="<?php echo number_format($isi->v_spb_discounttotalafter); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nilai SPB (Realisasi)</label>
                        <div class="col-sm-6">
                            <input id="vspbafter" name="vspbafter" class="form-control" readonly <?php $tmp=$isi->v_spb_after-$isi->v_spb_discounttotalafter;?> class="form-control" value="<?php echo number_format($tmp);?>">
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                        <table class="table color-table inverse-table table-bordered" width="100%;" cellspacing="0">
                            <thead>
                                <tr>
                                    <th style="text-align: center; width: 7%;">No</th>
                                    <th style="text-align: center; width: 12%;">Kode Barang</th>
                                    <th style="text-align: center; width: 30%;">Nama Barang</th>
                                    <th style="text-align: center; width: 7%;">Motif</th>
                                    <th style="text-align: center; width: 15%;">Harga</th>
                                    <th style="text-align: center; width: 7%;">Jumlah Pesan</th>
                                    <th style="text-align: center; width: 7%;">Jumlah Pemenuhan</th>
                                    <th style="text-align: right; width: 15%;">Total</th>
                                    <th style="text-align: center; width: 30%;"">Keterangan</th>
                                </tr>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($detail) {
                                    $i = 0;
                                    foreach($detail as $row){
				  	                        $i++;
                                              $pangaos=number_format($row->v_unit_price,2);
                                              $hrgnew=number_format($row->hrgnew,2);
                                              $total=$row->v_unit_price*$row->n_order;
                                              $total=number_format($total,2);
                                    ?>
                                        <tr>
                                            <td>
                                                    <input style="text-align:center;" readonly type="text" class="form-control" id="baris<?=$i;?>" name="baris<?=$i;?>" value="<?= $i;?>">
                                                    <input type="hidden" id="motif<?=$i;?>" name="motif<?=$i;?>" value="<?= $row->i_product_motif; ?>">
                                                    <input type="hidden" id="iproductstatus<?=$i;?>" name="iproductstatus<?=$i;?>" value="<?= $row->i_product_status; ?>">
                                            </td>
                                            <td>
                                                <input class="form-control" readonly type="text" id="iproduct<?=$i;?>" name="iproduct<?=$i;?>" value="<?= $row->i_product; ?>">
                                            </td>
                                            <td>
                                                <input readonly type="text" class="form-control" id="eproductname<?=$i;?>" name="eproductname<?=$i;?>" value="<?= $row->e_product_name; ?>">
                                            </td>
                                            <td>
                                                <input class="form-control" readonly type="text" id="emotifname<?=$i;?>" name="emotifname<?=$i;?>" value="<?= $row->e_product_motifname; ?>">
                                            </td>
                                            <td>
                                                <input class="form-control" style="text-align:right;" readonly type="text" id="vproductretail<?=$i;?>" name="vproductretail<?=$i;?>" value="<?= $pangaos; ?>">
                                                <input class="form-control" style="text-align:right;" readonly type="hidden" id="hrgnew<?=$i;?>" name="hrgnew<?=$i;?>" value="<?= $hrgnew; ?>">
                                            </td>
                                            <td>
                                                <input class="form-control" style="text-align:right;" type="text" id="norder<?=$i;?>" name="norder<?=$i;?>" value="<?= $row->n_order; ?>" readonly>
                                            </td>
                                            <td>
                                                <input class="form-control" style="text-align:right;" type="text" id="ndeliver<?=$i;?>" name="ndeliver<?=$i;?>" value="<?= $row->n_deliver; ?>" readonly>
                                                <input class="form-control" style="text-align:right;" type="hidden" id="ndeliverx<?=$i;?>" name="ndeliverx<?=$i;?>" value="<?= $row->n_deliver; ?>" readonly>
                                            </td>
                                            <td>
                                                <input class="form-control" style="text-align:right;" type="text" id="vtotal<?=$i;?>" name="vtotal<?=$i;?>" value="<?= $total; ?>" readonly>
                                                <input class="form-control" style="text-align:right;" type="hidden" id="vtotalx<?=$i;?>" name="vtotalx<?=$i;?>" value="<?= $total; ?>" readonly>
                                            </td>
                                            <td>
                                                <input class="form-control" style="text-align:right;" type="text" id="eremark<?=$i;?>" name="eremark<?=$i;?>" value="<?= $row->ket; ?>" readonly>
                                            </td>
                                        </tr>
                                    <?php  } ?>
                                    <input type="hidden" readonly name="jml" id="jml" value="<?= $i;?>">
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
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
