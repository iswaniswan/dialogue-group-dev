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
                            <label class="col-md-6">No SPB</label><label class="col-md-6">Tanggal SPB</label>
                            <div class="col-sm-6">
                                <input id="ispb" name="ispb" class="form-control" required="" readonly value="<?= $isi->i_spb;?>">
                            </div>
                            <div class="col-sm-6">
                                <input id= "dspbx" name="dspbx" class="form-control" required="" readonly value="<?= $isi->dspb;?>">
                                <input type="hidden" id= "dspb" name="dspb" class="form-control" required="" readonly value="<?= $isi->d_spb;?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-12">Promo</label>
                            <div class="col-sm-12">
                                <input id= "epromoname" name="epromoname" class="form-control" required="" readonly value="<?= $isi->e_promo_name;?>">
                                <input id="ispbprogram" name="ispbprogram" type="hidden" value="<?= $isi->i_spb_program; ?>">
                            </div>
                        </div> 
                        <div class="form-group row">
                            <label class="col-md-12">Area</label>
                            <div class="col-sm-12">
                                <input id= "eareaname" name="eareaname" class="form-control" required="" readonly value="<?= $isi->e_area_name;?>">
                                <input id="iarea" name="iarea" type="hidden" value="<?= $isi->i_area; ?>">
                            </div>
                        </div> 
                        <div class="form-group row">
                            <label class="col-md-12">Pelanggan</label>
                            <div class="col-sm-12">
                                <input id= "ecustomername" name="ecustomername" class="form-control" required="" readonly value="<?= $isi->e_customer_name;?>">
                                <input id="icustomer" name="icustomer" type="hidden" value="<?= $isi->i_customer; ?>">
                            </div>
                        </div> 
                        <div class="form-group row">
                            <label class="col-md-12">Alamat</label>
                            <div class="col-sm-12">
                                <input readonly type="text" id="ecumstomeraddress" name="ecumstomeraddress" class="form-control" maxlength="100" value="<?= $isi->e_customer_address; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-12">PO</label>
                            <div class="col-sm-12">
                                <input type="text" id="ispbpo" name="ispbpo" class="form-control" maxlength="30" value="<?= $isi->i_spb_po; ?>">
                            </div>
                        </div>
                        <div class="form-group row" hidden="true">
                            <div class="col-md-3">
                                <div class="form-check">
                                    <label class="custom-control custom-checkbox">
                                        <input type="checkbox" id="fspbconsigment" name="fspbconsigment" class="custom-control-input" disabled="true" 
                                        <?php if($isi->f_spb_consigment=='t') echo "checked";?>>
                                        <span class="custom-control-indicator"></span>
                                        <span class="custom-control-description">Konsinyasi</span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="example-text-input" class="col-3 col-form-label">SPB Lama</label>
                                <div class="col-12">
                                    <input class="form-control" type="text" value="" id="ispbold" name="ispbold">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">
                                <div class="form-check">
                                    <label class="custom-control custom-checkbox">
                                        <input type="checkbox" id="fspbstockdaerah" name="fspbstockdaerah" class="custom-control-input" <?php if($isi->f_spb_stockdaerah=='t') echo 'checked'; ?> disabled=true>
                                        <span class="custom-control-indicator"></span>
                                        <span class="custom-control-description">Stock Daerah</span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nspbtoplength" class="col-3 col-form-label">TOP</label>
                                <div class="col-12">
                                    <input class="form-control" name="nspbtoplength" id="nspbtoplength" type="text" readonly="" value="<?= $isi->n_spb_toplength; ?>">
                                </div>
                            </div>
                        </div>  
                        <div class="form-group row">
                            <label class="col-md-12">Salesman</label>
                            <div class="col-sm-12">
                                <input type="hidden" readonly id="isalesman" name="isalesman" class="form-control" maxlength="30" value="<?= $isi->i_salesman; ?>">
                                <input readonly id="esalesmanname" name="esalesmanname" class="form-control" maxlength="30" value="<?= $isi->e_salesman_name; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-12">Stock Daerah</label>
                            <div class="col-sm-6">
                                <input type="hidden" id="fspbstokdaerah" name="fspbstokdaerah" class="form-control" maxlength="7" value="">
                                <input type="text" id="isj" name="isj" class="form-control" maxlength="15" value="" readonly>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" id="dsj" name="dsj" class="form-control date" value="" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-12">PKP</label>
                            <div class="col-sm-12">
                                <input type="text" readonly id="ecustomerpkpnpwp" name="ecustomerpkpnpwp" class="form-control" maxlength="30" value="<?= $isi->e_customer_pkpnpwp;?>">
                                <input id="fspbplusppn" name="fspbplusppn" type="hidden" value="<?= $isi->f_spb_plusppn;?>">
                                <input id="fspbplusdiscount" name="fspbplusdiscount" type="hidden" value="<?= $isi->f_spb_plusdiscount;?>">
                                <input id="fspbpkp" name="fspbpkp" type="hidden" value="<?= $isi->f_spb_pkp;?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-offset-3 col-sm-5">
                                &nbsp;
                            </div>
                            <div class="col-sm-offset-3 col-sm-5">
                                &nbsp;
                            </div>
                            <div class="col-sm-offset-3 col-sm-5">
                                &nbsp;
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-12">Ket Approve</label>
                            <div class="col-sm-12">
                                <input id="eapprove2" name="eapprove2" maxlength="100" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-offset-5 col-sm-8">
                                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="dipales();"> <i class="fa fa-save"></i>&nbsp;&nbsp;Approve</button>
                                &nbsp;&nbsp;
                                <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i
                                    class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                                </div>
                            </div>
                        </div> 
                    </form>
                        <div class="col-md-6"> 
                            <div class="form-group row">
                                <label class="col-md-12">Kelompok Harga</label>
                                <div class="col-sm-6">
                                    <input type="text" id="epricegroupname" name="epricegroupname" class="form-control" value="<?= $isi->e_price_groupname; ?>" readonly>
                                    <input id="ipricegroup" name="ipricegroup" type="hidden" value="<?= $isi->i_price_group; ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-12">Nilai Kotor</label>
                                <div class="col-sm-12">
                                    <input type="text" id="vspb" name="vspb" class="form-control" required="" readonly value="<?= number_format($isi->v_spb); ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-6">Discount 1</label><label class="col-md-6">Nilai Discount 1</label>
                                <div class="col-sm-6">
                                    <input id="ncustomerdiscount1" name="ncustomerdiscount1" class="form-control" required="" readonly value="<?= $isi->n_spb_discount1; ?>">
                                </div>
                                <div class="col-sm-6">
                                    <input id= "vcustomerdiscount1" name="vcustomerdiscount1" class="form-control" required="" readonly value="<?= number_format($isi->v_spb_discount1); ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-6">Discount 2</label><label class="col-md-6">Nilai Discount 2</label>
                                <div class="col-sm-6">
                                    <input id="ncustomerdiscount2" name="ncustomerdiscount2" class="form-control" required="" readonly value="<?= $isi->n_spb_discount2; ?>">
                                </div>
                                <div class="col-sm-6">
                                    <input id="vcustomerdiscount2" name="vcustomerdiscount2" class="form-control" required=""
                                    readonly value="<?= number_format($isi->v_spb_discount2); ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-6">Discount 3</label><label class="col-md-6">Nilai Discount 3</label>
                                <div class="col-sm-6">
                                    <input id="ncustomerdiscount3" name="ncustomerdiscount3" class="form-control" required="" readonly value="<?= $isi->n_spb_discount3; ?>">
                                </div>
                                <div class="col-sm-6">
                                    <input id="vcustomerdiscount3" name="vcustomerdiscount3" class="form-control" required=""
                                    readonly value="<?= number_format($isi->v_spb_discount3); ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-6">Discount 4</label><label class="col-md-6">Nilai Discount 4</label>
                                <div class="col-sm-6">
                                    <input id="ncustomerdiscount4" name="ncustomerdiscount4" class="form-control" required="" readonly value="<?= $isi->n_spb_discount4; ?>">
                                </div>
                                <div class="col-sm-6">
                                    <input id="vcustomerdiscount4" name="vcustomerdiscount4" class="form-control" required=""
                                    readonly value="<?= number_format($isi->v_spb_discount4); ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-12">Discount Total</label>
                                <div class="col-sm-12">
                                    <input readonly id="vspbdiscounttotal" name="vspbdiscounttotal" class="form-control" required="" 
                                    value="<?= number_format($isi->v_spb_discounttotal); ?>">
                                </div>
                            </div>
                            <?php 
                            $tmp=$isi->v_spb-$isi->v_spb_discounttotal;
                            ?>
                            <div class="form-group row">
                                <label class="col-md-12">Nilai Bersih</label>
                                <div class="col-sm-12">
                                    <input id="vspbbersih" name="vspbbersih" class="form-control" required="" readonly value="<?= number_format($tmp); ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-12">Discount Total (Realisasi)</label>
                                <div class="col-sm-12">
                                    <input id="vspbdiscounttotalafter" name="vspbdiscounttotalafter" class="form-control" required="" 
                                    readonly value="0">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-12">Nilai SPB (Realisasi)</label>
                                <div class="col-sm-12">
                                    <input id="vspbafter" name="vspbafter" class="form-control" required="" 
                                    readonly value="<?= $isi->v_spb_after;?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-12">Keterangan</label>
                                <div class="col-sm-12">
                                    <input id="eremarkx" name="eremarkx" maxlength="100" class="form-control" value="<?= $isi->e_remark1; ?>">
                                </div>
                            </div>                            
                        <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/notapprove'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                            <div class="form-group row">
                                <label class="col-md-12">Ket Not Approve</label>
                                <div class="col-sm-12">
                                    <input type="hidden" id="nospb" name="nospb" value="<?= $isi->i_spb; ?>">
                                    <input type="hidden" id="kdarea" name="kdarea" value="<?= $isi->i_area; ?>">
                                    <input id="enotapprove" name="enotapprove" maxlength="100" class="form-control">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-offset-5 col-sm-8">
                                    <button type="submit" id="submit" class="btn btn-warning btn-rounded btn-sm" onclick="dipalesegein();"> <i class="fa fa-times"></i>&nbsp;&nbsp;Not Approve</button>
                                </div>
                            </div>
                        </form>
                        </div>
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%;" cellspacing="0">
                            <thead>
                                <tr>
                                    <th style="text-align: center; width: 7%;">No</th>
                                    <th style="text-align: center; width: 10%;">Kode Barang</th>
                                    <th style="text-align: center; width: 30%;">Nama Barang</th>
                                    <th style="text-align: center;">Motif</th>
                                    <th style="text-align: center;">Harga</th>
                                    <th style="text-align: center;">Qty Pesan</th>
                                    <th style="text-align: center;">Total</th>
                                    <th style="text-align: center;">Ket</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($detail) {
                                    $i = 0;
                                    foreach ($detail as $row) { 
                                        $i++; 
                                        $nilai=number_format($row->v_unit_price,2);
                                        $jujum=number_format($row->n_order,0);
                                        $ntot =number_format($row->v_unit_price*$row->n_order,2);
                                        ?>
                                        <tr>
                                            <td>
                                                <div class="col-sm-12">
                                                    <input style="text-align:center;" readonly type="text" class="form-control" id="baris<?=$i;?>" name="baris<?=$i;?>" value="<?= $i;?>">
                                                    <input type="hidden" id="motif<?=$i;?>" name="motif<?=$i;?>" value="<?= $row->i_product_motif; ?>">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="col-sm-12">
                                                    <input class="form-control" readonly type="text" id="iproduct<?=$i;?>" name="iproduct<?=$i;?>" value="<?= $row->i_product; ?>">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="col-sm-12">
                                                    <input readonly type="text" class="form-control" id="eproductname<?=$i;?>" name="eproductname<?=$i;?>" value="<?= $row->e_product_name; ?>">
                                                </div>
                                            </td>
                                            <td>
                                                <input class="form-control" readonly type="text" id="emotifname<?=$i;?>" name="emotifname<?=$i;?>" value="<?= $row->e_product_motifname; ?>">
                                            </td>
                                            <td>
                                                <input class="form-control" style="text-align:right;" readonly type="text" id="vproductretail<?=$i;?>" name="vproductretail<?=$i;?>" value="<?= $nilai; ?>">
                                            </td>
                                            <td>
                                                <input class="form-control form-control-success" readonly style="text-align:right;" type="text" id="norder<?=$i;?>" name="norder<?=$i;?>" onkeyup="hitungnilai(this.value)" value="<?= $jujum; ?>">
                                            </td>
                                            <td>
                                                <input class="form-control" style="text-align:right;" readonly type="text" id="vtotal<?=$i;?>" name="vtotal<?=$i;?>" value="<?= $ntot; ?>">
                                            </td>
                                            <td>
                                                <input class="form-control" readonly type="text" id="eremark<?=$i;?>" name="eremark<?=$i;?>" value="<?= $row->e_remark; ?>">
                                            </td>
                                        </tr>
                                    <?php  } ?>
                                    <input type="hidden" readonly name="jml" id="jml" value="<?= $i;?>">
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>  
    function dipales(){    
        document.getElementById("login").disabled=true;  
    }  
    function dipalesegein(){    
        document.getElementById("login").disabled=true;    
        document.getElementById("notapprove").disabled=true;  
    }
</script>