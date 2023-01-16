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
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-6">Nomor Nota</label><label class="col-md-6">Tanggal Nota</label>
                        <div class="col-sm-6">
                            <input id="inota" name="inota" class="form-control"  value="<?= $isi->i_nota;?>" readonly>
                        </div>
                        <div class="col-sm-6">
                            <input id= "dnota" name="dnota" class="form-control <?php if($isi->i_nota==''){echo"date";}?>" value="<?= date('d-m-Y', strtotime($isi->d_nota));?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">Nomor SPB</label><label class="col-md-6">Tanggal SPB</label>
                        <div class="col-sm-6">
                            <input id="ispb" name="ispb" class="form-control"  value="<?= $isi->i_spb;?>" readonly>
                        </div>
                        <div class="col-sm-6">
                            <input id= "dspb" name="dspb" class="form-control <?php if($isi->i_nota==''){echo"date";}?>" value="<?= date('d-m-Y', strtotime($isi->d_spb));?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-6">
                            <input id="eareaname" name="eareaname" class="form-control" value="<?= $isi->e_area_name; ?>" readonly>
                            <input readonly type="hidden" id="spbold" name="spbold" value="<?php echo $isi->i_spb_old; ?>">
		                    <input id="iarea" name="iarea" type="hidden" value="<?php echo $isi->i_area; ?>">
                            <input id="idkb" name="idkb" type="hidden" value="<?php echo $isi->i_dkb; ?>">
                            <input id="ddkb" name="ddkb" type="hidden" value="<?php echo $isi->d_dkb; ?>">
                            <input id="ibapb" name="ibapb" type="hidden" value="<?php echo $isi->i_bapb; ?>">
                            <input id="dbapb" name="dbapb" type="hidden" value="<?php echo $isi->d_bapb; ?>">
                            <input id="ecumstomeraddress" name="ecumstomeraddress" type="hidden" value="">                           
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Pelanggan</label>
                        <div class="col-sm-6">
                            <input id="ecustomername" name="ecustomername" class="form-control" value="<?= $isi->e_customer_name; ?>" readonly>
                            <input id="icustomer" name="icustomer" type="hidden" value="<?= $isi->i_customer;?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">PO</label>
                        <div class="col-sm-4">
                            <input id="ispbpo" name="ispbpo" class="form-control" maxlength="30" value="<?= $isi->i_spb_po; ?>">
                        </div>
                        <div class="col-sm-4">
                            <input readonly id="esalesman" name="esalesman" class="form-control" maxlength="30" value="<?= $isi->e_salesman_name; ?>">
                            <input type="hidden" id="isalesman" name="isalesman" class="form-control" maxlength="30" value="<?= $isi->i_salesman; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-3">
                            <div class="form-check">
                                <label class="custom-control custom-checkbox">
                                    <input type="checkbox" id="fspbconsigment" name="fspbconsigment" class="custom-control-input" <?php if($isi->f_spb_consigment=='t') { echo "checked value='on'"; }else{ echo "value=''";} ?>>
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">Konsinyasi</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <label class="custom-control custom-checkbox">
                                    <input type="checkbox" id="fmasalah" name="fmasalah" class="custom-control-input" <?php if($isi->f_masalah=='t') { echo "checked value='on'"; }else{ echo "value=''";} ?>>
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">Masalah</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <label class="custom-control custom-checkbox">
                                    <input type="checkbox" id="finsentif" name="finsentif" class="custom-control-input" <?php if($isi->f_insentif=='t') { echo "checked value='on'"; }else{ echo "value=''";} ?>>
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">Insentif</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <label class="custom-control custom-checkbox">
                                    <input type="checkbox" id="fcicilan" name="fcicilan" class="custom-control-input" <?php if($isi->f_cicil=='t') { echo "checked value='on'"; }else{ echo "value=''";} ?>>
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">Cicilan</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-3">
                            <div class="form-check">
                                <label class="custom-control custom-checkbox">
                                    <input type="checkbox" id="finsentif" name="finsentif" class="custom-control-input"  <?php if($isi->f_insentif=='t') {echo 'checked  value="on"';}else{echo 'value=""';}?>>
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">Insentif</span>
                                </label>
                            </div>
                        </div>
                        <label class="col-3 col-form-label">Nota Lama</label>
                        <div class="col-sm-4">
                            <input class="form-control" name="inotaold" id="inotaold" readonly value="<?= $isi->i_nota_old; ?>">
                        </div>
                    </div>  
                    <div class="form-group row">
                        <?php 
			                $tmp = explode("-", $isi->d_nota);
			                $det	= $tmp[2];
			                $mon	= $tmp[1];
			                $yir 	= $tmp[0];
			                $dspb	= $yir."/".$mon."/".$det;
                            $isi->n_nota_toplength=$isi->n_spb_toplength;
			                $dudet	= dateAdd("d",$isi->n_nota_toplength,$dspb);
			                $dudet 	= explode("-", $dudet);
			                $det1	= $dudet[2];
			                $mon1	= $dudet[1];
			                $yir1 	= $dudet[0];
			                $dudet	= $det1."-".$mon1."-".$yir1;
		                ?>
                            <label class="col-md-1 col-form-label">TOP</label>
                            <div class="col-sm-3">
                                <input class="form-control" name="nspbtoplength" id="nspbtoplength" readonly value="<?= $isi->n_nota_toplength; ?>">
                            </div>
                            <label class="col-md-2 col-form-label">Jatuh Tempo</label>
                            <div class="col-sm-3">
                                <input class="form-control" name="djatuhtempo" id="djatuhtempo" readonly value="<?= $dudet ?>">
                            </div>
                    </div>  
                    <div class="form-group row">
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
                            <label class="col-md-12">Surat Jalan</label>
                            <div class="col-sm-6">
                                <input id="fspbstokdaerah" name="fspbstokdaerah" type="hidden">
                                <input class="form-control" name="isj" id="isj" readonly value="<?= $isi->i_sj; ?>">
                            </div>
                            <div class="col-sm-3">
                                <input class="form-control" name="dsj" id="dsj" readonly value="<?= $isi->d_sj; ?>">
                            </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12 col-form-label">Keterangan</label>
                        <div class="col-sm-6">
                            <input class="form-control" name="eremark" id="eremark" readonly value="<?= $isi->e_remark; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Kelompok Harga</label>
                        <?php if($isi->e_price_groupname==''){
                            $isi->e_price_groupname=$isi->i_price_group;
                        } ?>
                        <div class="col-sm-6">
                            <input id="epricegroupname" name="epricegroupname" class="form-control" required="" readonly value="<?= $isi->e_price_groupname; ?>">
                            <input id="ipricegroup" name="ipricegroup" type="hidden" value="<?= $isi->i_price_group; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-8">
                            <button type="submit" disabled="true" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales(parseFloat(document.getElementById('jml').value));"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                            &nbsp;&nbsp;
                            <button type="submit" disabled="true" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales(parseFloat(document.getElementById('jml').value));"> <i class="fa fa-save"></i>&nbsp;&nbsp;Tambah Item</button>
                            &nbsp;&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Keluar</button>
                        </div>                    
                    </div>
                </div>
                
            <div class="col-md-6"> 
                <div class="form-group row">
                    <label class="col-md-12">Nilai Kotor</label>
                    <div class="col-sm-6">
                        <input id="vspb" name="vspb" class="form-control" required="" readonly value="<?= number_format($isi->v_spb); ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-6">Discount 1</label><label class="col-md-6">Nilai Discount 1</label>
                    <div class="col-sm-6">
                        <input id="ncustomerdiscount1" name="ncustomerdiscount1" class="form-control" required="" readonly value="<?= $isi->n_nota_discount1; ?>">
                    </div>
                    <div class="col-sm-6">
                        <input id= "vcustomerdiscount1" name="vcustomerdiscount1" class="form-control" required="" readonly value="<?= number_format($isi->v_nota_discount1); ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-6">Discount 2</label><label class="col-md-6">Nilai Discount 2</label>
                    <div class="col-sm-6">
                        <input id="ncustomerdiscount2" name="ncustomerdiscount2" class="form-control" required="" readonly value="<?= $isi->n_nota_discount2; ?>">
                    </div>
                    <div class="col-sm-6">
                        <input id="vcustomerdiscount2" name="vcustomerdiscount2" class="form-control" required="" readonly value="<?= number_format($isi->v_nota_discount2); ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-6">Discount 3</label><label class="col-md-6">Nilai Discount 3</label>
                    <div class="col-sm-6">
                        <input id="ncustomerdiscount3" name="ncustomerdiscount3" class="form-control" required="" readonly value="<?= $isi->n_nota_discount3; ?>">
                    </div>
                    <div class="col-sm-6">
                        <input id="vcustomerdiscount3" name="vcustomerdiscount3" class="form-control" required="" readonly value="<?= number_format($isi->v_nota_discount3); ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-6">Discount 4</label><label class="col-md-6">Nilai Discount 4</label>
                    <div class="col-sm-6">
                        <input id="ncustomerdiscount4" name="ncustomerdiscount4" class="form-control" required="" readonly value="<?= $isi->n_nota_discount4; ?>">
                    </div>
                    <div class="col-sm-6">
                        <input id="vcustomerdiscount4" name="vcustomerdiscount4" class="form-control" required="" readonly value="<?= number_format($isi->v_nota_discount4); ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-12">Discount Total</label>
                    <div class="col-sm-6">
                        <input readonly id="vspbdiscounttotal" name="vspbdiscounttotal" class="form-control"  value="<?= number_format($isi->v_nota_discounttotal); ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-12">Nilai Bersih</label>
                    <div class="col-sm-6">
                        <input id="vspbbersih" name="vspbbersih" class="form-control" required="" readonly value="<?= number_format($isi->v_nota_netto); ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-12">Nilai Kotor (Realisasi)</label>
                    <div class="col-sm-6">
                        <input id="vnotagross" name="vnotagross" class="form-control" required="" readonly value="<?= number_format($isi->v_nota_gross); ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-12">Discount Total (Realisasi)</label>
                    <div class="col-sm-6">
                        <input id="vspbdiscounttotalafter" name="vspbdiscounttotalafter" class="form-control" required="" readonly value="<?= number_format($isi->v_nota_discounttotal); ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-12">Nilai SPB (Realisasi)</label>
                    <div class="col-sm-6">
                        <input id="vspbafter" name="vspbafter"  class="form-control" readonly value="<?php echo number_format($isi->v_nota_netto); ?>">
			            <input type="hidden" id="fspbplusppn" name="fspbplusppn" value="<?php echo $isi->f_spb_plusppn;?>">
			            <input type="hidden" id="fspbplusdiscount" name="fspbplusdiscount" value="<?php echo $isi->f_spb_plusdiscount;?>">
			            <input type="hidden" id="nprice" name="nprice" value="<?php echo $isi->n_price;?>">
			            <input type="hidden" id="vnotappn" name="vnotappn" value="<?php echo number_format($isi->v_nota_ppn); ?>">
                    </div>
                </div>
            </div>
            <div class="panel-body table-responsive">
                <table id="tabledata" class="display table" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th style="text-align: center; width: 4%;">No</th>
                            <th style="text-align: center; width: 15%;">Kode Barang</th>
                            <th style="text-align: center; ">Nama Barang</th>
                            <th style="text-align: center; width: 10%;">Motif</th>
                            <th style="text-align: center; width: 15%;">Harga</th>
                            <th style="text-align: center; width: 10%;">Jumlah Dlv</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php               
                        $i=0;
                            foreach($detail as $row){ 
                                $pangaos=number_format($row->v_unit_price,2);
                                $ndeliv=$row->n_deliver;
                                if($row->n_deliver>0){
                                    $i++;
                                ?>
                                <tr>
                                    <td style="text-align: center;"><?= $i;?>
                                    <input type="hidden" class="form-control" readonly id="baris<?= $i;?>" name="baris<?= $i;?>" value="<?= $i;?>">
                                    <input type="hidden" id="motif<?= $i;?>" name="motif<?= $i;?>" value="<?= $row->i_product_motif;?>">
                                </td>
                                <td>
                                    <input style="font-size: 12px;" class="form-control" readonly id="iproduct<?= $i;?>" name="iproduct<?= $i;?>" value="<?= $row->i_product;?>">
                                </td>
                                <td>
                                    <input style="font-size: 12px;" class="form-control" readonly id="eproductname<?= $i;?>" name="eproductname<?= $i;?>" value="<?= $row->e_product_name;?>">
                                </td>
                                <td>
                                    <input style="font-size: 12px;" readonly class="form-control" id="emotifname<?= $i;?>" name="emotifname<?= $i;?>" value="<?= $row->e_product_motifname;?>">
                                </td>
                                <td>
                                    <input style="font-size: 12px; text-align: right;" readonly class="form-control" width:85px;"  id="vproductretail<?= $i;?>" name="vproductretail<?= $i;?>" value="<?= $pangaos;?>">
                                </td>
                                <td>
                                    <input style="font-size: 12px; text-align: right;" class="form-control" id="ndeliv<?= $i;?>" name="ndeliv<?= $i;?>" value="<?= $ndeliv;?>" readonly>
                                </td>
                            </tr>
                            <?php }
                        }?>
                    <input type="hidden" name="jml" id="jml" value="<?= $i;?>">
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
</script>