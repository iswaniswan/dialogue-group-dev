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
                        <label class="col-md-12">Tanggal SPB</label>
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
                        <div class="col-sm-5">
                            <input readonly id="ispb" name="ispb" class="form-control" type="text" value="<?php echo $isi->i_spb; ?>">
                        </div>
                        <div class="col-sm-3">
			                <input readonly id="dspb" name="dspb" class="form-control date" value="<?php echo $dspb; ?>">
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
                                    <input type="checkbox" id="fspbconsigment" name="fspbconsigment" class="custom-control-input" <?php if($isi->f_spb_consigment=='t'){ echo "checked"; echo "value='on'";}else{echo "value=''";}?>>
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
                                    <input type="checkbox" id="fspbstockdaerah" name="fspbstockdaerah" class="custom-control-input" <?php if($isi->f_spb_stockdaerah=='t') {
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
                        <div class="col-sm-5">
                            <input id="fspbstokdaerah" name="fspbstokdaerah" class="form-control" type="hidden">
			                <input id="isj" name="isj" class="form-control" readonly>
                        </div>
                        <div class="col-sm-3">
		                    <input readonly readonly id="dsj" name="dsj" class="form-control date">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Terima Gudang</label>
                        <div class="col-sm-3">
                            <input id="dspbstorereceive" name="dspbstorereceive" class="form-control" value="<?php echo $isi->d_spb_storereceive;?>" maxlength="20" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <?php if(($isi->f_spb_cancel =='t') || ($isi->i_nota !='') || ($isi->f_spb_op =='t') || ($isi->i_store!='')){}else{ ?>
		                        <input name="login" id="login" value="Simpan" type="submit" onclick="dipales(parseFloat(document.getElementById('jml').value));">
                        <?php }?>
                        <div class="col-sm-offset-5 col-sm-8">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
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
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nilai Kotor</label>
                        <div class="col-sm-6">
                            <input readonly id="vspb" name="vspb" class="form-control" value="<?php echo number_format($isi->v_spb); ?>">
                            <input type="hidden" id="vspbx" name="vspbx" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Discount 1</label>
                        <div class="col-sm-3">
                            <input readonly id ="ncustomerdiscount1"name="ncustomerdiscount1" class="form-control" value="<?php echo $isi->n_spb_discount1; ?>">
                        </div>
                        <div class="col-sm-5">
		                    <input readonly id="vcustomerdiscount1" name="vcustomerdiscount1" class="form-control" value="<?php echo number_format($isi->v_spb_discount1); ?>">
                            <input type="hidden" readonly id="vcustomerdiscount1x" name="vcustomerdiscount1x" class="form-control" value="<?php if($isi->f_spb_consigment!='t') echo '0'; else echo number_format($disc1);?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Discount 2</label>
                        <div class="col-sm-3">
                            <input readonly id="ncustomerdiscount2" name="ncustomerdiscount2" class="form-control" value="<?php echo $isi->n_spb_discount2; ?>">
                        </div>
                        <div class="col-sm-3">
		                    <input readonly id="vcustomerdiscount2" name="vcustomerdiscount2" class="form-control" value="<?php echo number_format($isi->v_spb_discount2); ?>">
                            <input type="hidden" readonly id="vcustomerdiscount2x" name="vcustomerdiscount2x" class="form-control" value="0">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Discount 3</label>
                        <div class="col-sm-3">
                            <input readonly id="ncustomerdiscount3" name="ncustomerdiscount3" class="form-control" value="<?php echo $isi->n_spb_discount3; ?>">
		                </div>
                        <div class="col-sm-5">
                            <input readonly id="vcustomerdiscount3" name="vcustomerdiscount3" class="form-control" value="<?php echo number_format($isi->v_spb_discount3); ?>">
                            <input type="hidden" readonly id="vcustomerdiscount3x" name="vcustomerdiscount3x" class="form-control" value="0">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Discount Total</label>
                        <div class="col-sm-6">
                            <input <?php if( ($isi->n_spb_discount1!='0.00') || ($isi->n_spb_discount2!='0.00') || ($isi->n_spb_discount2!='0.00') ){
						                echo "readonly ";
					                }?>id="vspbdiscounttotal" name="vspbdiscounttotal" class="form-control" readonly value="<?php echo number_format($isi->v_spb_discounttotal); ?>"></td>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nilai Bersih</label>
                        <?php 
			                $tmp=$isi->v_spb-$isi->v_spb_discounttotal;
		                ?>
                        <div class="col-sm-6">
                        <input readonly id="vspbbersih" name="vspbbersih" class="form-control" value="<?php echo number_format($tmp); ?>">
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
                        <table class="table table-bordered" width="100%;" cellspacing="0">
                            <thead>
                                <tr>
                                    <th style="text-align: center; width: 7%;">No</th>
                                    <th style="text-align: center; width: 12%;">Kode Barang</th>
                                    <th style="text-align: center; width: 30%;">Nama Barang</th>
                                    <th style="text-align: center; width: 7%;">Motif</th>
                                    <th style="text-align: center; width: 15%;">Harga</th>
                                    <th style="text-align: center; width: 7%;">Jumlah Pesan</th>
                                    <th style="text-align: center; width: 15%;">Total</th>
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
                                            </td>
                                            <td>
                                                <input class="form-control" style="text-align:right;" type="text" id="norder<?=$i;?>" name="norder<?=$i;?>" value="<?= $row->n_order; ?>" readonly onkeyup="hitungnilai(this.value,<?=$jmlitem;?>)">
                                            </td>
                                            <td>
                                                <input class="form-control" style="text-align:right;" type="text" id="vtotal<?=$i;?>" name="vtotal<?=$i;?>" value="<?= $total; ?>" readonly>
                                            </td>
                                            <td>
                                                <input class="form-control" style="text-align:right;" type="text" id="eremark<?=$i;?>" name="eremark<?=$i;?>" value="<?= $row->e_remark; ?>" readonly>
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
