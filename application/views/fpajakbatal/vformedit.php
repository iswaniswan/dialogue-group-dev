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
                        <label class="col-md-12">Nota</label>
                        <?php if($isi->i_nota!='') {?>
                            <div class="col-sm-5">
					            <input type="text" id="i_nota" name="inota" class="form-control" required="" value="<?= $isi->i_nota; ?>" readonly>
				            </div>
                            <?php }else{ ?>
                                <input type="hidden" id="i_nota" name="inota" class="form-control" required="" value="">
				            <?php }	
				            if($isi->d_nota!=''){				
                                $tmp=explode("-",$isi->d_nota);
                                $th=$tmp[0];
                                $bl=$tmp[1];
                                $hr=$tmp[2];
                                $dnota=$hr."-".$bl."-".$th;
                                $tmp=explode("-",$isi->d_sj);
                                $th=$tmp[0];
                                $bl=$tmp[1];
                                $hr=$tmp[2];
                                $xsj=$hr."-".$bl."-".$th;
                            ?>
                             <div class="col-sm-3">
				            	<input type="text" name="dnota" id="dnota" class="form-control" required="" value="<?= $dnota; ?>" readonly>
                                <input type="hidden" name="dtmp" id="dtmp" class="form-control" required="" value="<?= $xsj; ?>" readonly>
                            </div>
				            <?php }else if($isi->d_sj!=''){ 
                                $tmp=explode("-",$isi->d_sj);
					            $th=$tmp[0];
					            $bl=$tmp[1];
					            $hr=$tmp[2];
                                $dnota=$hr."-".$bl."-".$th;
                            ?>
                                <input readonly type="hidden" id="dnota" name="dnota" class="form-control" value="<?= $dnota ?>">
                                <input type="hidden" name="dtmp" id="dtmp" class="form-control" required="" value="<?= $dnota; ?>" readonly>
                                <input type="hidden" name="inotaold" id="inotaold" class="form-control" required="" value="">
                            <?php } ?>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">SPB</label>
                        <?php 
			                $tmp=explode("-",$isi->d_spb);
			                $th=$tmp[0];
			                $bl=$tmp[1];
			                $hr=$tmp[2];
			                $dspb=$hr."-".$bl."-".$th;
		                ?>
                        <div class="col-sm-5">
                            <input type="text" readonly id="ispb" name="ispb" class="form-control" value="<?php echo $isi->i_spb; ?>" readonly>
                        </div>
                        <div class="col-sm-3">
			                <input type="text" id="dspb" name="dspb" class="form-control" value="<?php echo $dspb; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Promo</label>
                        <div class="col-sm-3">
                            <input readonly id="epromoname" name="epromoname" class="form-control" value="<?php echo $isi->e_promo_name; ?>" readonly >
		                    <input id="ispbprogram" name="ispbprogram" type="hidden" value="<?php echo $isi->i_spb_program; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-3">
                            <input readonly id="arena" name="arena" class="form-control" value="<?php echo $isi->e_area_name; ?>">
                        </div>
                        <div class="col-sm-3">
		                    <input readonly id="spbold" name="spbold" class="form-control" value="<?php echo $isi->i_spb_old; ?>">
                            <input id="iarea" name="iarea" type="hidden" class="form-control" value="<?php echo $isi->i_area; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Pelanggan</label>
                        <div class="col-sm-6">
                            <input readonly id="ecustomername" name="ecustomername" class="form-control" value="<?php echo $isi->e_customer_name; ?>" readonly>
		                    <input id="icustomer" name="icustomer" type="hidden" class="form-control" value="<?php echo $isi->i_customer; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">PO</label>
                        <div class="col-sm-5">
                            <input id="ispbpo" name="ispbpo" class="form-control" value="<?php echo $isi->i_spb_po; ?>" maxlength="10" readonly >
                        </div>
                    </div>
                    <div class="form-group row">   
                    <?php if($isi->f_spb_consigment=='t') echo "checked";?>                                                                                                        
                        <div class="col-sm-2"> 
                            <div class="form-check">
                                <label class="custom-control custom-checkbox">
                                    <input type="checkbox" id="fmasalah" name="fmasalah" class="custom-control-input" type="checkbox" value="">
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">Masalah</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-sm-2"> 
                            <div class="form-check">
                                <label class="custom-control custom-checkbox">
                                    <input type="checkbox" id="finsentif" name="finsentif" class="custom-control-input" type="checkbox" value="on">
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">Insentif</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-sm-2"> 
                            <div class="form-check">
                                <label class="custom-control custom-checkbox">
                                    <input type="checkbox" id="fcicil" name="fcicil" class="custom-control-input" type="checkbox" <?php if($isi->f_customer_cicil=='t') echo 'value="on" checked'; else echo 'value=""';?> onclick="cekcicil();">
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">Cicil</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-1">TOP</label><label class="col-sm-11">Jatuh Tempo</label>
                        <?php 
			                $tmp = explode("-", $isi->d_sj);
			                $det	= $tmp[2];
			                $mon	= $tmp[1];
			                $yir 	= $tmp[0];
			                $dsj	= $yir."/".$mon."/".$det;
                            if(substr($isi->i_sj,8,2)=='00'){
                              $topnya=$isi->n_spb_toplength+$isi->n_toleransi_pusat;
                            }else{
                              $topnya=$isi->n_spb_toplength+$isi->n_toleransi_cabang;
                            }
			                $dudet	=dateAdd("d",$topnya,$dsj);
			                $dudet 	= explode("-", $dudet);
			                $det1	= $dudet[2];
			                $mon1	= $dudet[1];
			                $yir1 	= $dudet[0];
			                $dudet	= $det1."-".$mon1."-".$yir1;
		                ?>
                        <div class="col-sm-2">
                            <input maxlength="3" id="nspbtoplength" class="form-control" name="nspbtoplength" readonly value="<?php echo $isi->n_spb_toplength; ?>">
                        </div>
                        <div class="col-sm-3">
			                <input id="djatuhtempo" name="djatuhtempo" class="form-control" readonly value="<?php echo $dudet; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Salesman</label>
                        <div class="col-sm-6">
                            <input readonly id="esalemanname" name="esalesmanname" class="form-control" value="<?php echo $isi->e_salesman_name; ?>" readonly>
		                    <input id="isalesman" name="isalesman" type="hidden" class="form-control" value="<?php echo $isi->i_salesman; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Surat Jalan</label>
                        <?php 
			                if($isi->d_sj!=''){
			                	$tmp=explode("-",$isi->d_sj);
			                	$th=$tmp[0];
			                	$bl=$tmp[1];
			                	$hr=$tmp[2];
			                	$dsj=$hr."-".$bl."-".$th;
			                }else{
			                	$dsj='';
			                }
		                ?>
                        <div class="col-sm-5">
                            <input id="fspbstokdaerah" name="fspbstokdaerah" class="form-control" type="hidden">
			                <input id="isj" name="isj" readonly class="form-control" value="<?php if($isi->i_sj) echo $isi->i_sj; ?>">
                        </div>
                        <div class="col-sm-3">
		                    <input readonly readonly id="dsj" class="form-control" name="dsj" value="<?php echo $dsj; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">PKP</label>
                        <div class="col-sm-8">
                            <input id="fspbpkp" name="fspbpkp" type="hidden" class="form-control" value="<?php echo $isi->f_spb_pkp;?>">
			                <input type="text" id="ecustomerpkpnpwp" name="ecustomerpkpnpwp" class="form-control" readonly value="<?php echo $isi->e_customer_pkpnpwp;?>">
			                <input type="hidden" id="fspbplusppn" name="fspbplusppn" class="form-control" value="<?php echo $isi->f_spb_plusppn;?>">
			                <input type="hidden" id="fspbplusdiscount" name="fspbplusdiscount" class="form-control" value="<?php echo $isi->f_spb_plusdiscount;?>">
			                <input type="hidden" id="nprice" name="nprice" class="form-control" value="1">
			                <input type="hidden" id="vnotagross" name="vnotagross" class="form-control" value="0">
			                <input type="hidden" id="vnotappn" name="vnotappn" class="form-control" value="0">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Tanggal Approve Pajak</label>
                        <div class="col-sm-8">
                            <input readonly id="dapprovepajak" name="dapprovepajak" class="form-control" value="<?php echo $tgl; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-8">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales(parseFloat(document.getElementById('jml').value));"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                            &nbsp;&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>                    
                    </div>
                </div>
                <div class="col-md-6">
                    <div id="pesan"></div>
                    <div class="form-group">
                        <label class="col-md-12">Kelompok Harga</label>
                        <div class="col-sm-8">
                            <input readonly id="epricegroupname" name="epricegroupname" class="form-control" value="<?php echo $isi->e_price_groupname; ?>">
		                    <input id="ipricegroup" name="ipricegroup" type="hidden" class="form-control" value="<?php echo $isi->i_price_group; ?>">
                            <input id="fspbconsigment" name="fspbconsigment" type="hidden" class="form-control" value="<?php echo $isi->f_spb_consigment; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nilai Kotor</label>
                        <?php 
			                $enin=number_format($isi->v_spb);
		                ?>
                        <div class="col-sm-6">
                            <input id="vspb" name="vspb" readonly class="form-control" value="<?php echo $enin; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Discount 1</label>
                        <div class="col-sm-3">
                            <input id ="ncustomerdiscount1" name="ncustomerdiscount1" readonly class="form-control" value="<?php echo $isi->n_spb_discount1; ?>" onkeyup="formatcemua(this.value);editnilai();">
                        </div>
                        <div class="col-sm-5">
		                    <input readonly id="vcustomerdiscount1" name="vcustomerdiscount1" class="form-control" value="<?php echo number_format($isi->v_spb_discount1); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Discount 2</label>
                        <div class="col-sm-3">
                            <input id="ncustomerdiscount2" name="ncustomerdiscount2" readonly class="form-control" value="<?php echo $isi->n_spb_discount2; ?>" onkeyup="formatcemua(this.value);editnilai();">
                        </div>
                        <div class="col-sm-5">
		                    <input readonly id="vcustomerdiscount2" name="vcustomerdiscount2" class="form-control" value="<?php echo number_format($isi->v_spb_discount2); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Discount 3</label>
                        <div class="col-sm-3">
                            <input id="ncustomerdiscount3" name="ncustomerdiscount3" readonly class="form-control" value="<?php echo $isi->n_spb_discount3; ?>" onkeyup="formatcemua(this.value);editnilai();">
                        </div>
                        <div class="col-sm-5">
		                    <input readonly id="vcustomerdiscount3" name="vcustomerdiscount3" class="form-control" value="<?php echo number_format($isi->v_spb_discount3); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Discount 4</label>
                        <div class="col-sm-3">
                            <input id="ncustomerdiscount4" name="ncustomerdiscount4" readonly class="form-control" value="<?php echo $isi->n_spb_discount4; ?>" onkeyup="formatcemua(this.value);editnilai();">
                        </div>
                        <div class="col-sm-5">
		                    <input readonly id="vcustomerdiscount4" name="vcustomerdiscount4" class="form-control" value="<?php echo number_format($isi->v_spb_discount4); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Discount Total</label>
                        <div class="col-sm-6">
                                <input readonly id="vspbdiscounttotal" name="vspbdiscounttotal" class="form-control" value="<?php echo number_format($isi->v_spb_discounttotal); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nilai Bersih</label>
                        <?php 
			                $tmp=$isi->v_spb-$isi->v_spb_discounttotal;
		                ?>
                        <div class="col-sm-6">
                            <input readonly id="vspbbersih" name="vspbbersih" readonly class="form-control" value="<?php echo number_format($tmp); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Discount Total (Realisasi) </label>
                        <div class="col-sm-6">
                            <input id="vspbdiscounttotalafter" name="vspbdiscounttotalafter" class="form-control" readonly value="<?php echo number_format($isi->v_nota_discounttotal); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nilai SPB (Realisasi)</label>
                        <div class="col-sm-6">
                            <input id="vspbafter" name="vspbafter" readonly class="form-control" value="<?php echo number_format($isi->v_nota_netto); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-6">
                            <input id="eremark" name="eremark" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Keterangan Approve Pajak</label>
                        <div class="col-sm-6">
                            <input id="eremarkpajak" name="eremarkpajak" class="form-control" value="">
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                        <table class="table table-bordered" width="100%;" cellspacing="0">
                            <thead>
                                <tr>
                                    <th style="text-align: center; width: 7%;">No</th>
                                    <th style="text-align: center; width: 15%;">Kode Barang</th>
                                    <th style="text-align: center; width: 30%;">Nama Barang</th>
                                    <th style="text-align: center;">Motif</th>
                                    <th style="text-align: center;">Harga</th>
                                    <th style="text-align: center;">Jumlah Pesan</th>
                                    <th style="text-align: center;">Jumlah Dlv</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($detail) {
                                    $i = 0;
                                    foreach($detail as $row){
				  	                        $i++;
					                        $harga	=number_format($row->v_unit_price,2);
                                            $ndeliv	=number_format($row->n_deliver,0);
                                            $norder	=number_format($row->n_order,0);
                                    ?>
                                        <tr>
                                            <td>
                                                    <input style="text-align:center;" readonly type="text" class="form-control" id="baris<?=$i;?>" name="baris<?=$i;?>" value="<?= $i;?>">
                                                    <input type="hidden" id="motif<?=$i;?>" name="motif<?=$i;?>" value="<?= $row->i_product_motif; ?>">
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
                                                <input class="form-control" style="text-align:right;" readonly type="text" id="vproductretail<?=$i;?>" name="vproductretail<?=$i;?>" value="<?= $harga; ?>">
                                            </td>
                                            <td>
                                                <input class="form-control" style="text-align:right;" type="text" id="norder<?=$i;?>" name="norder<?=$i;?>" value="<?= $norder; ?>" readonly>
                                            </td>
                                            <td>
                                                <input class="form-control" style="text-align:right;" type="text" id="ndeliver<?=$i;?>" name="ndeliver<?=$i;?>" value="<?= $ndeliv; ?>" readonly>
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
