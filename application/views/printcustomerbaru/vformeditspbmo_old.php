<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/updatespbreguler'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <?php 
                if($nilaiorderspb==$isi->v_spb){
                    $norderspbbefore= $isi->v_spb;
                    $disc1parsing   = explode(".",$isi->n_spb_discount1,strlen($isi->n_spb_discount1));
                    $disc1      = ($norderspbbefore * $disc1parsing[0])/100;
                    $disc1parsing2  = explode(".",$isi->n_spb_discount2,strlen($isi->n_spb_discount2));
                    $disc2      = (($norderspbbefore-$disc1) * $disc1parsing2[0])/100;
                    $disc1parsing3  = explode(".",$isi->n_spb_discount3,strlen($isi->n_spb_discount3));
                    $disc3      = ((($norderspbbefore-$disc1)-$disc2) * $disc1parsing3[0])/100;
                    $disc1parsing4  = explode(".",$isi->n_spb_discount4,strlen($isi->n_spb_discount4));
                    $disc4      = (((($norderspbbefore-$disc1)-$disc2)-$disc3) * $disc1parsing4[0])/100;
                    if($isi->n_spb_discount1=='0.00' && $isi->v_spb_discount1>0){
                        $disc1      = $isi->v_spb_discount1;
                    }
                    $disctot    = $disc1+$disc2+$disc3+$disc4;
                    $norderspbafter = ($isi->v_spb - (($disc1+$disc2+$disc3+$disc4)));
                }elseif($isi->v_spb_after<$nilaiorderspb){
                    $norderspbbefore= $nilaiorderspb;
                    $disc1parsing   = explode(".",$isi->n_spb_discount1,strlen($isi->n_spb_discount1));
                    $disc1      = ($norderspbbefore * $disc1parsing[0])/100;
                    $disc1parsing2  = explode(".",$isi->n_spb_discount2,strlen($isi->n_spb_discount2));
                    $disc2      = (($norderspbbefore-$disc1) * $disc1parsing2[0])/100;
                    $disc1parsing3  = explode(".",$isi->n_spb_discount3,strlen($isi->n_spb_discount3));
                    $disc3      = ((($norderspbbefore-$disc1)-$disc2) * $disc1parsing3[0])/100;
                    $disc1parsing4  = explode(".",$isi->n_spb_discount4,strlen($isi->n_spb_discount4));
                    $disc4      = (((($norderspbbefore-$disc1)-$disc2)-$disc3) * $disc1parsing4[0])/100;
                    if($isi->n_spb_discount1=='0.00' && $isi->v_spb_discount1>0){
                        $disc1      = $isi->v_spb_discount1;
                    }
                    $disctot    = $disc1+$disc2+$disc3+$disc4;
                    $norderspbafter = ($nilaiorderspb - (($disc1+$disc2+$disc3+$disc4)));
                }else{
                    $norderspbbefore= $nilaiorderspb;
                    $disc1parsing   = explode(".",$isi->n_spb_discount1,strlen($isi->n_spb_discount1));
                    $disc1      = ($norderspbbefore * $disc1parsing[0])/100;
                    $disc1parsing2  = explode(".",$isi->n_spb_discount2,strlen($isi->n_spb_discount2));
                    $disc2      = (($norderspbbefore-$disc1) * $disc1parsing2[0])/100;
                    $disc1parsing3  = explode(".",$isi->n_spb_discount3,strlen($isi->n_spb_discount3));
                    $disc3      = ((($norderspbbefore-$disc1)-$disc2) * $disc1parsing3[0])/100;
                    $disc1parsing4  = explode(".",$isi->n_spb_discount4,strlen($isi->n_spb_discount4));
                    $disc4      = (((($norderspbbefore-$disc1)-$disc2)-$disc3) * $disc1parsing4[0])/100;
                    if($isi->n_spb_discount1=='0.00' && $isi->v_spb_discount1>0){
                        $disc1      = $isi->v_spb_discount1;
                    }
                    $disctot    = $disc1+$disc2+$disc3+$disc4;
                    $norderspbafter = ($nilaiorderspb - (($disc1+$disc2+$disc3+$disc4)));
                }
                ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-6">Nomor SPB</label><label class="col-md-6">Tanggal SPB</label>
                        <div class="col-sm-6">
                            <input id="ispb" name="ispb" class="form-control"  value="<?= $isi->i_spb;?>" readonly>
                        </div>
                        <div class="col-sm-6">
                            <input id= "dspb" name="dspb" class="form-control <?php if($isi->i_nota==''){echo"date";}?>" value="<?= date('d-m-Y', strtotime($isi->d_spb));?>" readonly>
                            <input hidden id="bspb" name="bspb" value="<?= date('m', strtotime($isi->d_spb)); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Group Barang</label>
                        <div class="col-sm-12">
                            <?php if ($isi->i_nota!='') {?>
                                <input id="productgroup" name="productgroup" type="hidden" value="<?= $isi->i_product_group;?> ">
                                <select class="form-control" disabled="true">
                                    <?php if ($group) {
                                        foreach ($group as $key) { ?>
                                            <option value="<?= $key->i_product_group;?>" <?php if ($key->i_product_group==$isi->i_product_group) { ?> selected <?php } ?>><?= $key->e_product_groupname;?></option> 
                                        <?php }
                                    } ?>   
                                </select>
                            <?php }else{ ?>
                                <select name="productgroup" id="productgroup" class="form-control" onchange="group(this.value);">
                                    <?php if ($group) {
                                        foreach ($group as $key) { ?>
                                            <option value="<?= $key->i_product_group;?>" <?php if ($key->i_product_group==$isi->i_product_group) { ?> selected <?php } ?>><?= $key->e_product_groupname;?></option> 
                                        <?php }
                                    } ?>   
                                </select>
                            <?php } ?>
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-12">
                            <input id="eareaname" name="eareaname" class="form-control" value="<?= $isi->e_area_name; ?>" readonly>
                            <input id="iarea" name="iarea" type="hidden" value="<?= $isi->i_area; ?>">                            
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Pelanggan</label>
                        <div class="col-sm-12">
                            <input id="ecustomername" name="ecustomername" class="form-control" value="<?= $isi->e_customer_name; ?>" readonly>
                            <input id="icustomer" name="icustomer" type="hidden" value="<?= $isi->i_customer;?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Alamat</label>
                        <div class="col-sm-12">
                            <input readonly id="ecumstomeraddress" name="ecumstomeraddress" class="form-control" maxlength="100"  value="<?= $isi->e_customer_address; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">PO</label>
                        <div class="col-sm-12">
                            <input id="ispbpo" name="ispbpo" class="form-control" maxlength="30" value="<?= $isi->i_spb_po; ?>">
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
                        <div class="form-group row">
                            <label for="example-text-input" class="col-3 col-form-label">SPB Lama</label>
                            <div class="col-12">
                                <input class="form-control" value="<?= $isi->i_spb_old; ?>" id="ispbold" name="ispbold">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-3">
                            <div class="form-check">
                                <label class="custom-control custom-checkbox">
                                    <input type="checkbox" id="fspbstockdaerah" name="fspbstockdaerah" class="custom-control-input"  <?php if($isi->f_spb_stockdaerah=='t') {echo 'checked  value="on"';}else{echo 'value=""';}?>>
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">Stock Daerah</span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="nspbtoplength" class="col-3 col-form-label">TOP</label>
                            <div class="col-12">
                                <input class="form-control" name="nspbtoplength" id="nspbtoplength" readonly value="<?= $isi->n_spb_toplength; ?>">
                            </div>
                        </div>
                    </div>  
                    <div class="form-group row">
                        <label class="col-md-12">Salesman</label>
                        <div class="col-sm-12">
                         <?php if ($isi->i_nota!='') {?>
                            <input id="isalesman" name="isalesman" type="hidden" value="<?= $isi->i_salesman;?> ">
                            <select class="form-control" disabled="true">
                                <option value="<?= $isi->i_salesman;?>"><?= $isi->e_salesman_name; ?></option>
                            </select>
                        <?php }else{ ?>
                            <select class="form-control select2" name="isalesman" id="isalesman" onchange="getsales(this.value);">
                                <option value="<?= $isi->i_salesman;?>"><?= $isi->e_salesman_name; ?></option>
                            </select>
                        <?php } ?>
                        <input type="hidden" readonly id="isalesmanx" name="isalesmanx" class="form-control" maxlength="30" value="<?= $isi->i_salesman;?>">
                        <input type="hidden" readonly id="esalesmannamex" name="esalesmannamex" class="form-control" maxlength="30" value="<?= $isi->e_salesman_name;?>">
                    </div>
                </div>
                <?php 
                if($isi->d_sj!=''){         
                    $dsj=$isi->d_sj;
                }else{
                    $dsj='';
                }?>
                <div class="form-group row">
                    <label class="col-md-12">Stock Daerah</label>
                    <input id="fspbsiapnotagudang" name="fspbsiapnotagudang" type="hidden" <?php if($isi->f_spb_siapnotagudang=='t') {echo "value='on'";}else{echo "value=''";}?>>
                    <input id="f_spb_op" name="f_spb_op" type="hidden" <?php if($isi->f_spb_op=='t') {echo "value='on'";}else{echo "value=''";}?>>    
                    <input id="f_spb_program" name="f_spb_program" type="hidden" <?php if($isi->f_spb_program=='t') {echo "value='on'";}else{echo "value=''";}?>>
                    <input id="f_spb_cancel" name="f_spb_cancel" type="hidden" <?php if($isi->f_spb_cancel=='t') {echo "value='on'";}else{echo "value=''";}?>>
                    <div class="col-sm-6">
                        <input type="hidden" id="fspbstokdaerah" name="fspbstokdaerah" class="form-control" maxlength="7" value="">
                        <input id="isj" name="isj" class="form-control" maxlength="15" value="<?= $isi->i_sj; ?>" readonly>
                    </div>
                    <div class="col-sm-6">
                        <input id="dsj" name="dsj" class="form-control <?php if($isi->i_nota==''){ echo "date";} ?>" value="<?= $dsj; ?>" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-12">PKP</label>
                    <div class="col-sm-12">
                        <input readonly id="ecustomerpkpnpwp" name="ecustomerpkpnpwp" class="form-control" maxlength="30" value="<?= $isi->e_customer_pkpnpwp;?>">
                        <input id="fspbplusppn" name="fspbplusppn" type="hidden" value="<?php echo $isi->f_spb_plusppn;?>">
                        <input id="fspbplusdiscount" name="fspbplusdiscount" type="hidden" value="<?php echo $isi->f_spb_plusdiscount;?>">
                        <input id="fspbpkp" name="fspbpkp" type="hidden" value="<?php echo $isi->f_spb_pkp;?>">
                        <input id="fcustomerfirst" name="fcustomerfirst" type="hidden" value="<?php echo $isi->f_customer_first;?>">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-offset-5 col-sm-8">
                        <?php if(($isi->i_store =='') /*&& ($departement == 'spvpusat')*/ && ($isi->i_approve1=='') && ($isi->i_approve2=='') && ($isi->f_spb_rekap!='t') && ($isi->f_spb_cancel!='t')){ ?>
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales(parseFloat(document.getElementById('jml').value));"> <i class="fa fa-save"></i>&nbsp;&nbsp;Update</button>
                            &nbsp;&nbsp;
                        <?php } ?>
                        <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/view/<?= $xarea."/".$dfrom."/".$dto;?>","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        <?php if(($isi->i_store =='') && ($departement == 'spvpusat'||$departement == 'admin') && ($isi->i_approve1=='') && ($isi->i_approve2=='') && ($isi->f_spb_cancel!='t')){ ?>
                            &nbsp;&nbsp;
                            <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah</button>
                        <?php } ?>
                        <?php if(($isi->i_store =='') && ($departement == 'spvpusat'||$departement == 'admin') && ($isi->i_approve1=='') && ($isi->i_approve2=='') && ($isi->f_spb_cancel!='t')){ ?>
                            &nbsp;&nbsp;
                            <button type="button" id="refresh" class="btn btn-warning btn-rounded btn-sm"><i class="fa fa-refresh" onclick="refreshharga();"></i>&nbsp;&nbsp;Refresh</button>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6"> 
                <div class="form-group row">
                    <label class="col-md-12">Kelompok Harga</label>
                    <?php if($isi->e_price_groupname==''){
                        $isi->e_price_groupname=$isi->i_price_group;
                    } ?>
                    <div class="col-sm-12">
                        <input id="epricegroupname" name="epricegroupname" class="form-control" required="" readonly value="<?= $isi->e_price_groupname; ?>">
                        <input id="ipricegroup" name="ipricegroup" type="hidden" value="<?= $isi->i_price_group; ?>">
                        <input id="istore" name="istore" type="hidden" value="<?= $isi->i_store; ?>">
                        <input id="istorelocation" name="istorelocation" type="hidden" value="<?= $isi->i_store_location; ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-12">Nilai Kotor</label>
                    <div class="col-sm-12">
                        <input id="vspb" name="vspb" class="form-control" required="" readonly value="<?= number_format($norderspbbefore); ?>">
                        <input type="hidden" id="vspbx" name="vspbx" value="">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-6">Discount 1</label><label class="col-md-6">Nilai Discount 1</label>
                    <div class="col-sm-6">
                        <input id="ncustomerdiscount1" name="ncustomerdiscount1" class="form-control" required="" readonly value="<?= $isi->n_spb_discount1; ?>">
                    </div>
                    <div class="col-sm-6">
                        <input id= "vcustomerdiscount1" name="vcustomerdiscount1" class="form-control" required="" readonly value="<?= number_format($disc1); ?>">
                    </div>
                    <input type="hidden" readonly id="vcustomerdiscount1x" name="vcustomerdiscount1x" value="">
                </div>
                <div class="form-group row">
                    <label class="col-md-6">Discount 2</label><label class="col-md-6">Nilai Discount 2</label>
                    <div class="col-sm-6">
                        <input id="ncustomerdiscount2" name="ncustomerdiscount2" class="form-control" required="" readonly value="<?= $isi->n_spb_discount2; ?>">
                    </div>
                    <div class="col-sm-6">
                        <input id="vcustomerdiscount2" name="vcustomerdiscount2" class="form-control" required="" readonly value="<?= number_format($disc2); ?>">
                    </div>
                    <input type="hidden" readonly id="vcustomerdiscount2x" name="vcustomerdiscount2x" value="">
                </div>
                <div class="form-group row">
                    <label class="col-md-6">Discount 3</label><label class="col-md-6">Nilai Discount 3</label>
                    <div class="col-sm-6">
                        <input id="ncustomerdiscount3" name="ncustomerdiscount3" class="form-control" required="" readonly value="<?= $isi->n_spb_discount3; ?>">
                    </div>
                    <div class="col-sm-6">
                        <input id="vcustomerdiscount3" name="vcustomerdiscount3" class="form-control" required="" readonly value="<?= number_format($disc3); ?>">
                    </div>
                    <input type="hidden" readonly id="vcustomerdiscount3x" name="vcustomerdiscount3x" value="">
                </div>
                <div class="form-group row">
                    <label class="col-md-12">Discount Total</label>
                    <div class="col-sm-12">
                        <input readonly id="vspbdiscounttotal" name="vspbdiscounttotal" class="form-control" required="" <?php if( ($isi->n_spb_discount1!='0.00') || ($isi->n_spb_discount2!='0.00') || 
                        ($isi->n_spb_discount2!='0.00') || ($isi->n_spb_discount2!='0.00') ){echo "readonly ";}?> value="<?= number_format($disctot); ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-12">Nilai Bersih</label>
                    <div class="col-sm-12">
                        <input id="vspbbersih" name="vspbbersih" class="form-control" required="" readonly value="<?= number_format($norderspbafter); ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-12">Discount Total (Realisasi)</label>
                    <div class="col-sm-12">
                        <input id="vspbdiscounttotalafter" name="vspbdiscounttotalafter" class="form-control" required="" readonly value="<?= number_format($isi->v_spb_discounttotalafter); ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-12">Nilai SPB (Realisasi)</label>
                    <div class="col-sm-12">
                        <input id="vspbafter" name="vspbafter" class="form-control" required="" 
                        readonly <?php $tmp=$isi->v_spb_after-$isi->v_spb_discounttotalafter;?> value="<?= number_format($tmp);?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-12">Keterangan</label>
                    <div class="col-sm-12">
                        <input id="eremarkx" name="eremarkx" maxlength="100" class="form-control" value="<?= $isi->e_remark1; ?>">
                    </div>
                </div>
            </div>
            <div class="panel-body table-responsive">
                <table id="tabledata" class="display table" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th style="text-align: center; width: 4%;">No</th>
                            <th style="text-align: center; width: 10%;">Kode Barang</th>
                            <th style="text-align: center; width: 30%;">Nama Barang</th>
                            <th style="text-align: center; width: 5%;">Motif</th>
                            <th style="text-align: center;">Harga</th>
                            <th style="text-align: center;">Qty Pesan</th>
                            <th style="text-align: center;">Qty Pmnhn</th>
                            <th style="text-align: center;">Total</th>
                            <th style="text-align: center;">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php               
                        $i=0;
                        if($detail){
                            foreach($detail as $row){ 
                                $i++;
                                $pangaos=number_format($row->v_unit_price,2);
                                $total=$row->v_unit_price*$row->n_order;
                                $total=number_format($total,2);
                                ?>
                                <tr>
                                    <td style="text-align: center;"><?= $i;?>
                                    <input type="hidden" class="form-control" readonly id="baris<?= $i;?>" name="baris<?= $i;?>" value="<?= $i;?>">
                                    <input type="hidden" id="motif<?= $i;?>" name="motif<?= $i;?>" value="<?= $row->i_product_motif;?>">
                                    <input type="hidden" id="iproductstatus<?= $i;?>" name="iproductstatus<?= $i;?>" value="<?= $row->i_product_status;?>">
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
                                    <input style="font-size: 12px; text-align: right;" class="form-control" id="norder<?= $i;?>" name="norder<?= $i;?>" value="<?= $row->n_order;?>" onkeypress="return hanyaAngka(event);" onkeyup="hitungnilai(this.value)">
                                </td>
                                <td>
                                    <input style="font-size: 12px; text-align: right;" class="form-control" id="ndeliver<?= $i;?>" name="ndeliver<?= $i;?>" value="<?= $row->n_deliver;?>" onkeypress="return hanyaAngka(event);"  onkeyup="hitungnilai(this.value)">
                                    <input style="font-size: 12px;" type="hidden" id="ndeliverx<?= $i;?>" name="ndeliverx<?= $i;?>" value="<?= $row->n_deliver;?>" >
                                </td> 
                                <td>
                                    <input style="font-size: 12px; text-align: right;" readonly class="form-control" id="vtotal<?= $i;?>" name="vtotal<?= $i;?>" value="<?= $total;?>">
                                    <input style="font-size: 12px;" type="hidden" id="vtotalx<?= $i;?>" name="vtotalx<?= $i;?>" value="">
                                </td>
                                <td>
                                    <input style="font-size: 12px;" class="form-control" id="eremark<?= $i;?>" name="eremark<?= $i;?>" value="<?= $row->ket;?>" maxlength="50">
                                    <input style="font-size: 12px;" class="form-control" id="nquantitystock<?= $i;?>" name="nquantitystock<?= $i;?>" value="0" type="hidden">
                                </td>
                            </tr>
                        <?php }
                    } ?>
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
    var xx = $('#jml').val();
    $("#addrow").on("click", function () {
        xx++;
        /*document.getElementById("jml").value = xx;*/
        $('#jml').val(xx);
        var newRow = $("<tr>");
        var cols = "";
        cols += '<td style="text-align: center;">'+xx+'<input type="hidden" id="baris'+xx+'" class="form-control" name="baris'+xx+'" value="'+xx+'"><input type="hidden" id="motif'+xx+'" name="motif'+xx+'" value=""><input type="hidden" id="iproductstatus'+xx+'" name="iproductstatus'+xx+'" value=""></td>';
        cols += '<td><select id="iproduct'+xx+ '" class="form-control" name="iproduct'+xx+'" onchange="getharga('+xx+');" value=""></td>';
        cols += '<td><input id="eproductname'+xx+'" class="form-control" name="eproductname'+xx+'" value="" readonly></td>';
        cols += '<td><input id="emotifname'+xx+'" class="form-control" name="emotifname'+xx+'" value="" readonly></td>';

        cols += '<td><input style="text-align: right" id="vproductretail'+xx+'" class="form-control" name="vproductretail'+xx+'"/ readonly><input readonly type="hidden" id="v_product_min'+xx+'" name="v_product_min'+xx+'" value="" readonly></td>';
        cols += '<td><input style="text-align: right" id="norder'+xx+'" class="form-control" name="norder'+xx+'" onkeypress="return hanyaAngka(event)" onblur="cekminimal('+xx+');" onkeyup="hitungnilai(this.value)" autocomplete="off"><input type="hidden" id="nquantitystock'+xx+'" name="nquantitystock'+xx+'" value=""></td>';
        cols += '<td><input style="text-align: right" id="ndeliver'+xx+'" class="form-control" name="ndeliver'+xx+'" onkeypress="return hanyaAngka(event)" onkeyup="hitungnilai(this.value)" autocomplete="off"><input style="text-align: right" id="ndeliverx'+xx+'" class="form-control" name="ndeliverx'+xx+'" type="hidden" value="0"><input type="hidden" id="nquantitystock'+xx+'" name="nquantitystock'+xx+'" value=""></td>';
        cols += '<td><input style="text-align: right" id="vtotal'+xx+'" class="form-control" name="vtotal'+xx+'" readonly></td>';
        cols += '<td><input id="eremark'+xx+'" class="form-control" name="eremark'+xx+ '"/></td>';
        /*cols += '<td><button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';*/
        newRow.append(cols);
        $("#tabledata").append(newRow);
        $('#iproduct'+xx).select2({
            placeholder: 'Cari Kode / Nama',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/databrgreguler/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var kdharga     = $('#ipricegroup').val();
                    var groupbarang = $('#productgroup').val();
                    var ipromo      = $('#ipromo').val();
                    var kdgroup     = $('#icustomergroup').val();
                    var query   = {
                        q       : params.term,
                        kdharga : kdharga,
                        group   : groupbarang,
                        ipromo  : ipromo,
                        kdgroup : kdgroup
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

    $("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
        xx -= 1
        document.getElementById("jml").value = xx;
    });

    $(document).ready(function () {
        /*hitungnilai(0);*/
        var inota = '<?= $isi->i_nota;?>';
        /*$('.select2').select2();*/
        showCalendar('.date', 0, 2);
        if (inota=='') {            
            /*$('#icustomer').select2({
                placeholder: 'Cari Berdasarkan Kode / Nama',
                allowClear: true,
                ajax: {
                    url: '<?= base_url($folder.'/cform/getpelangganpromo/'); ?>',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        var dspb = $('#dspb').val();
                        var i_area = $('#iarea').val();
                        var i_promo = $('#ipromo').val();
                        var query = {
                            q: params.term,
                            dspb: dspb,
                            i_area: i_area,
                            i_promo: i_promo
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
            });*/


            $('#isalesman').select2({
                placeholder: 'Cari Berdasarkan Kode / Nama',
                allowClear: true,
                ajax: {
                    url: '<?= base_url($folder.'/cform/getsalespromo/'); ?>',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        var i_area = $('#iarea').val();
                        var d_spb  = $('#dspb').val();
                        var query = {
                            q: params.term,
                            i_area: i_area,
                            d_spb: d_spb
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
        }
    });

    function getarea(iarea){
        if (iarea!='') {
            $("#icustomer").attr("disabled", false);
        }else{
            $("#icustomer").attr("disabled", true);
        }
    }

    function getdetailpel(icustomer){
        var dspb  = $('#dspb').val();
        var iarea = $('#iarea').val();
        var ipromo = $('#ipromo').val();
        $.ajax({
            type: "post",
            data: {
                'icustomer': icustomer,
                'dspb'     : dspb,
                'iarea'    : iarea,
                'ipromo'   : ipromo
            },
            url: '<?= base_url($folder.'/cform/getdetailpelpromo'); ?>',
            dataType: "json",
            success: function (data) {
                var type = data[0].type;
                if (type=='1') {
                    $('#ncustomerdiscount1').val(data[0].disc1);
                    $('#ncustomerdiscount2').val(data[0].disc2);
                    $('#ncustomerdiscount3').val('0.00');
                    $('#ncustomerdiscount4').val('0.00');
                }else if(type=='2'){
                    $('#ncustomerdiscount1').val('0.00');
                    $('#ncustomerdiscount2').val('0.00');
                    $('#ncustomerdiscount3').val('0.00');
                    $('#ncustomerdiscount4').val('0.00');
                }else if(type=='3'){
                    if(data[0].n_customer_discount1=='0.00'){
                        d1=data[0].disc1;
                        d2=data[0].disc2;
                        d3='0.00';
                        d4='0.00';
                    }else if(data[0].n_customer_discount2=='0.00'){
                        d1=data[0].n_customer_discount1;
                        d2=data[0].disc1;
                        d3=data[0].disc2;
                        d4='0.00';
                    }else{
                        d1=data[0].n_customer_discount1;
                        d2=data[0].n_customer_discount2;
                        d3=data[0].disc1;
                        d4=data[0].disc2;
                    }
                    $('#ncustomerdiscount1').val(d1);
                    $('#ncustomerdiscount2').val(d2);
                    $('#ncustomerdiscount3').val(d3);
                    $('#ncustomerdiscount4').val(d4);
                }else if(type=='4'){
                    if(data[0].n_customer_discount1=='0.00'){
                        d1='0.00';
                        d2='0.00';
                        d3='0.00';
                        d4='0.00';
                    }else if(data[0].n_customer_discount2=='0.00'){
                        d1=data[0].n_customer_discount1;
                        d2='0.00';
                        d3='0.00';
                        d4='0.00';
                    }else{
                        d1=data[0].n_customer_discount1;
                        d2=data[0].n_customer_discount2;
                        d3='0.00';
                        d4='0.00';
                    }
                    $('#ncustomerdiscount1').val(d1);
                    $('#ncustomerdiscount2').val(d2);
                    $('#ncustomerdiscount3').val(d3);
                    $('#ncustomerdiscount4').val(d4);
                }else if(type=='5'){
                    if(data[0].n_customer_discount1=='0.00'){
                        d1=data[0].disc1;
                        d2='0.00';
                        d3='0.00';
                        d4='0.00';
                    }else if(data[0].n_customer_discount2=='0.00'){
                        d1=data[0].n_customer_discount1;
                        d2=data[0].disc1;
                        d3='0.00';
                        d4='0.00';
                    }else{
                        d1=data[0].n_customer_discount1;
                        d2=data[0].n_customer_discount2;
                        d3=data[0].disc1;
                        d4='0.00';
                    }
                }else if(type=='6'){
                    $('#ncustomerdiscount1').val(data[0].disc1);
                    $('#ncustomerdiscount2').val('0.00');
                    $('#ncustomerdiscount3').val('0.00');
                    $('#ncustomerdiscount4').val('0.00');
                }
                $('#ecustomerpkpnpwp').val(data[0].e_customer_pkpnpwp);
                $('#epricegroupname').val(data[0].e_price_groupname);
                $('#ipricegroup').val(data[0].i_price_group);
                $('#ecumstomeraddress').val(data[0].e_customer_address);
                $('#fcustomerfirst').val(data[0].f_customer_first);
                $('#nspbtoplength').val(data[0].n_customer_toplength);
                $('#select2-isalesman-container').html(data[0].e_salesman_name+'-'+data[0].i_salesman);
                $('#isalesmanx').val(data[0].i_salesman);
                $('#esalesmannamex').val(data[0].e_salesman_name); 
                $('#fspbplusppn').val(data[0].f_customer_plusppn); 
                $('#fspbplusdiscount').val(data[0].f_customer_plusdiscount);
                $('#icustomergroup').val(data[0].i_customer_group);
                hitungnilai(0,0);          
            },error: function () {
                swal('Error :)');
            }
        });
}


function getsales(isalesman){
    var dspb  = $('#dspb').val();
    var iarea = $('#iarea').val();
    $.ajax({
        type: "post",
        data: {
            'isalesman': isalesman,
            'dspb'     : dspb,
            'iarea'    : iarea
        },
        url: '<?= base_url($folder.'/cform/getdetailsalpromo'); ?>',
        dataType: "json",
        success: function (data) {
            $('#isalesmanx').val(data[0].i_salesman);
            $('#esalesmannamex').val(data[0].e_salesman_name);
        },
        error: function () {
            swal('Error :)');
        }
    });
}

function group(group){
    if (group!='') {
        $('#addrow').attr("disabled", false);
        $("#ipromo").attr("disabled", false);
        $("#iarea").attr("disabled", false);
    }else{
        $('#addrow').attr("disabled", true);
        $("#ipromo").attr("disabled", true);
        $("#iarea").attr("disabled", true);
    }
}

function promo(ipromo) {
    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/getareapromo');?>",
        data:"ipromo="+ipromo,
        dataType: 'json',
        success: function(data){
            $("#iarea").html(data.kop);
        },

        error:function(XMLHttpRequest){
            swal(XMLHttpRequest.responseText);
        }

    })
    if (promo!='') {
        $("#iarea").attr("disabled", false);
    }else{
        $("#iarea").attr("disabled", true);
    }
}

function getharga(id){
    ada=false;
    var a = $('#iproduct'+id).val();
    var e = $('#motif'+id).val();
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
        var iproduct    = $('#iproduct'+id).val();
        var kdharga     = $('#ipricegroup').val();
        var groupbarang = $('#productgroup').val();
        var ipromo      = $('#ipromo').val();
        var kdgroup     = $('#icustomergroup').val();
        $.ajax({
            type: "post",
            data: {
                'iproduct'  : iproduct,
                'kdharga'   : kdharga,
                'group'     : groupbarang,
                'ipromo'    : ipromo,
                'kdgroup'   : kdgroup
            },
            url: '<?= base_url($folder.'/cform/getdetailbarreguler'); ?>',
            dataType: "json",
            success: function (data) {
                $('#eproductname'+id).val(data[0].nama);
                $('#vproductretail'+id).val(formatcemua(data[0].harga));
                $('#emotifname'+id).val(data[0].namamotif);
                $('#motif'+id).val(data[0].motif);
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

function hitungnilai(isi){
    jml=document.getElementById("jml").value;
    if (isNaN(parseFloat(isi))){
        alert("Input harus numerik");
    }else{
        dtmp1=parseFloat(formatulang(document.getElementById("ncustomerdiscount1").value));
        dtmp2=parseFloat(formatulang(document.getElementById("ncustomerdiscount2").value));
        dtmp3=parseFloat(formatulang(document.getElementById("ncustomerdiscount3").value));
        if(document.getElementById("fspbconsigment").value!='on'){
            vdis1=0;
            vdis2=0;
            vdis3=0;
            vdis1x=0;
            vdis2x=0;
            vdis3x=0;
        }
        vtot =0;
        vtotx=0;
        for(i=1;i<=jml;i++){
            vhrg=formatulang(document.getElementById("vproductretail"+i).value);
            vhrgx=formatulang(document.getElementById("vproductretail"+i).value);
            nqty=formatulang(document.getElementById("norder"+i).value);
            nqtyx=formatulang(document.getElementById("ndeliver"+i).value);
            if(nqty=='')nqty=0;
            if(nqtyx=='')nqtyx=0;
            vhrg=parseFloat(vhrg)*parseFloat(nqty);
            vhrgx=parseFloat(vhrgx)*parseFloat(nqtyx);
            vtot=vtot+vhrg;
            vtotx=vtotx+vhrgx;
            document.getElementById("vtotal"+i).value=formatcemua(vhrg);
            document.getElementById("vtotalx"+i).value=formatcemua(vhrgx);
        }
        if(document.getElementById("fspbconsigment").value!='on'){
            vdis1=vdis1+((vtot*dtmp1)/100);
            vdis2=vdis2+(((vtot-vdis1)*dtmp2)/100);
            vdis3=vdis3+(((vtot-(vdis1+vdis2))*dtmp3)/100);
            vdis1x=vdis1x+((vtotx*dtmp1)/100);
            vdis2x=vdis2x+(((vtotx-vdis1x)*dtmp2)/100);
            vdis3x=vdis3x+(((vtotx-(vdis1x+vdis2x))*dtmp3)/100);
        }else{
            vdis1=parseFloat(formatulang(document.getElementById("vcustomerdiscount1").value));
            vdis2=parseFloat(formatulang(document.getElementById("vcustomerdiscount2").value));
            vdis3=parseFloat(formatulang(document.getElementById("vcustomerdiscount3").value));
            vdis1x=parseFloat(formatulang(document.getElementById("vcustomerdiscount1x").value));
            vdis2x=parseFloat(formatulang(document.getElementById("vcustomerdiscount2x").value));
            vdis3x=parseFloat(formatulang(document.getElementById("vcustomerdiscount3x").value));
        }
        document.getElementById("vcustomerdiscount1").value=formatcemua(Math.round(vdis1));
        document.getElementById("vcustomerdiscount2").value=formatcemua(Math.round(vdis2));
        document.getElementById("vcustomerdiscount3").value=formatcemua(Math.round(vdis3));
        document.getElementById("vcustomerdiscount1x").value=formatcemua(Math.round(vdis1x));
        document.getElementById("vcustomerdiscount2x").value=formatcemua(Math.round(vdis2x));
        document.getElementById("vcustomerdiscount3x").value=formatcemua(Math.round(vdis3x));
        vdis1=parseFloat(vdis1);
        vdis2=parseFloat(vdis2);
        vdis3=parseFloat(vdis3);
        vtotdis=vdis1+vdis2+vdis3;
        vdis1x=parseFloat(vdis1x);
        vdis2x=parseFloat(vdis2x);
        vdis3x=parseFloat(vdis3x);
        vtotdisx=vdis1x+vdis2x+vdis3x;
        document.getElementById("vspbdiscounttotal").value=formatcemua(Math.round(vtotdis));
        document.getElementById("vspb").value=formatcemua(vtot);
        vtotbersih=parseFloat(formatulang(formatcemua(vtot)))-parseFloat(formatulang(formatcemua(Math.round(vtotdis))));
        document.getElementById("vspbbersih").value=formatcemua(vtotbersih);
        if(vtotx!=0){
            document.getElementById("vspbdiscounttotalafter").value=formatcemua(Math.round(vtotdisx));
            vtotbersihx=parseFloat(formatulang(formatcemua(vtotx)))-parseFloat(formatulang(formatcemua(Math.round(vtotdisx))));
        }else{
            vtotbersihx=0;
        }
        document.getElementById("vspbafter").value=formatcemua(vtotbersihx);
    }
}
function diskonrp(){
    disc=parseFloat(formatulang(document.getElementById("vspbdiscounttotal").value));
    kotor=parseFloat(formatulang(document.getElementById("vspb").value));
    if (isNaN(parseFloat(disc))){
        alert("Input harus numerik");
    }else{
        document.getElementById("vcustomerdiscount2").value=0;
        document.getElementById("vcustomerdiscount3").value=0;
        document.getElementById("vcustomerdiscount1").value=formatcemua(disc);
        bersih=kotor-disc;
        document.getElementById("vspbbersih").value=formatcemua(bersih);
    }    
}

function refreshharga(){
    jml=document.getElementById("jml").value;
    for(i=1;i<=jml;i++){
        document.getElementById("vproductretail"+i).value=document.getElementById("hrgnew"+i).value;
    }
    hitungnilai(0);
}

function dipales(a){
    if((document.getElementById("dspb").value!='') &&
        (document.getElementById("icustomer").value!='') &&
        (document.getElementById("iarea").value!='') &&
        (document.getElementById("ipricegroup").value!='')&&
        (document.getElementById("esalesmannamex").value!='')&&
        (document.getElementById("isalesmanx").value!='')) {
        if(a==0){
            swal('Isi data item minimal 1 !!!');
            return false;
        }else{
            for(i=1;i<=a;i++){
                if((document.getElementById("iproduct"+i).value=='') || (document.getElementById("eproductname"+i).value=='') || (document.getElementById("norder"+i).value=='')){
                    swal('Data item masih ada yang salah !!!');
                    return false;
                }else{
                    return true;
                } 
            }
        }
    }else{
        swal('Data header masih ada yang salah !!!');
        return false;
    }
}

$("form").submit(function(event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
    $("#addrow").attr("disabled", true);
    $("#refresh").attr("disabled", true);
});

function cekminimal(jml){
    var min = parseFloat(document.getElementById("v_product_min"+jml).value);
    var norder = parseFloat(document.getElementById("norder"+jml).value);

    if(norder < min){
        swal('Jumlah Pesan Tidak Boleh Kurang Dari '+min);
        document.getElementById('norder'+jml).value = min;
    }
}
</script>