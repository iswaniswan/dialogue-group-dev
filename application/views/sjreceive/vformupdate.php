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
                    <div class="form-group row" hidden="true">
                        <label class="col-md-12">Alamat</label>
                        <div class="col-sm-12">
                            <input readonly type="text" id="ecumstomeraddress" name="ecumstomeraddress" class="form-control" maxlength="100" value="<?= $isi->e_customer_address; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">PO</label>
                        <div class="col-sm-12">
                            <input type="text" id="ispbpo" name="ispbpo" readonly class="form-control" maxlength="30" value="<?= $isi->i_spb_po; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Gudang</label>
                        <div class="col-sm-12">
                            <input type="hidden" id="istore" name="istore" class="form-control" maxlength="30" readonly value="<?= $istore;?>">
                            <input type="text" id="estorename" name="estorename" class="form-control" maxlength="30" readonly value="<?= $estorename;?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Lokasi Gudang</label>
                        <div class="col-sm-12">
                            <input type="hidden" id="istorelocation" name="istorelocation" class="form-control" maxlength="30" readonly value="<?= $istorelocation;?>">
                            <input type="text" id="estorelocationname" name="estorelocationname" class="form-control" maxlength="30" readonly value="<?= $estorelocationname;?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-3">
                            <div class="form-check">
                                <label class="custom-control custom-checkbox">
                                    <input type="checkbox" id="fspbconsigment" name="fspbconsigment" class="custom-control-input" 
                                    <?php if($isi->f_spb_consigment=='t') echo "checked";?>>
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">Konsinyasi</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <label class="custom-control custom-checkbox">
                                    <input type="checkbox" id="fspbstockdaerah" name="fspbstockdaerah" class="custom-control-input" <?php if($isi->f_spb_stockdaerah=='t') echo 'checked'; ?>>
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">Stock Daerah</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <label class="custom-control custom-checkbox">
                                    <span class="custom-control-description">TOP&nbsp;&nbsp;</span>
                                    <input class="form-control" name="nspbtoplength" id="nspbtoplength" type="text" readonly="" value="<?= $isi->n_spb_toplength; ?>">
                                </label>
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
                        <div class="col-sm-offset-5 col-sm-8">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales(parseFloat(document.getElementById('jml').value),'<?= $isi->f_spb_stockdaerah; ?>');"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                            &nbsp;&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i
                                class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                            </div>
                        </div>
                    </div> 
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
                                    <th style="text-align: center;">Qty Kirim</th>
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
                                        if($isi->f_spb_stockdaerah=='f'){
                                            $query = $this->mmaster->stock1($thbl, $row->i_product, $row->i_product_grade);
                                        }else{
                                            $query = $this->mmaster->stock2($thbl, $row->i_product, $row->i_product_grade, $istore);
                                        }
                                        if ($query->num_rows() > 0){
                                            foreach($query->result() as $tt){
                                                $stock=$tt->qty;
                                            }
                                        }else{
                                            $stock=0;
                                        }
                                        $nstock = $stock;
                                        if($stock>$row->n_order){
                                            $stock=$row->n_order;
                                        }
                                        if($stock<0){
                                            $stock=0;
                                        }
                                        if($isi->f_spb_consigment=='t') {
                                            $stock=$row->n_order;
                                        }
                                        $stock=number_format($stock);
                                        ?>
                                        <tr>
                                            <td>
                                                <div class="col-sm-12">
                                                    <input style="text-align:center;" readonly type="text" class="form-control" id="baris<?=$i;?>" name="baris<?=$i;?>" value="<?= $i;?>">
                                                    <input type="hidden" id="motif<?=$i;?>" name="motif<?=$i;?>" value="<?= $row->i_product_motif; ?>">
                                                    <input type="hidden" id="grade<?=$i;?>" name="grade<?=$i;?>" value="<?= $row->i_product_grade; ?>">
                                                    <input type="hidden" id="iproductstatus<?=$i;?>" name="iproductstatus<?=$i;?>" value="<?= $row->i_product_status; ?>">
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
                                                <input class="form-control" style="text-align:right;" type="text" id="ndeliver<?=$i;?>" name="ndeliver<?=$i;?>" value="<?= $stock;?>" onkeyup="hitungnilai(this.value)" onblur="hitungnilai(this.value)" onpaste="hitungnilai(this.value)" autocomplete="off">
                                                <input class="form-control" readonly type="hidden" id="eremark<?=$i;?>" name="eremark<?=$i;?>" value="<?= $row->e_remark; ?>">
                                                <input type="hidden" id="nstock<?=$i;?>" name="nstock<?=$i;?>" value="<?= $nstock;?>">
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
</div>
<script>
    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
    });

    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date', 0, 5);
        hitungnilai(0);
    });

    function dipales(a,b){
        cek='false';
        if(document.getElementById("istore").value!=''){
            if(a==0){
                alert('Isi data item minimal 1 !!!');
                return false;
            }else{
                for(i=1;i<=a;i++){
                    if((document.getElementById("ndeliver"+i).value=='')){
                        alert('Data item masih ada yang salah !!!');
                        cek='false';
                        return false;
                    }else{
                        cek='true'; 
                        return true;
                    } 
                }
            }
        }else{
            alert('Data header masih ada yang salah !!!');
            return false;
        }
    }

    function hitungnilai(isi){
        jml=document.getElementById("jml").value;
        if (isNaN(parseFloat(isi))){          
            alert("Input harus numerik");
        }else{          
            salah=false;          
            gud=document.getElementById("istore").value;
            if(gud!='AA'){
                for(i=1;i<=jml;i++){                  
                    stock  =formatulang(document.getElementById("nstock"+i).value);
                    deliver=formatulang(document.getElementById("ndeliver"+i).value);
                    order=formatulang(document.getElementById("norder"+i).value);
                    if(parseFloat(stock)<0)stock=0;
                    if(parseFloat(deliver)>parseFloat(stock)){
                        alert('Jumlah Kirim melebihi jumlah stock');
                        document.getElementById("ndeliver"+i).value=0;
                        salah=true;
                        break;
                    }else if(parseFloat(deliver)>parseFloat(order)){
                        alert('Jumlah Kirim melebihi jumlah pesan');
                        document.getElementById("ndeliver"+i).value=0;
                        salah=true;
                        break;
                    }
                }
            }else{
                for(i=1;i<=jml;i++){
                  deliver=formatulang(document.getElementById("ndeliver"+i).value);
                  order=formatulang(document.getElementById("norder"+i).value);
                  if(parseFloat(deliver)>parseFloat(order)){
                    alert('Jumlah Kirim melebihi jumlah pesan');
                    document.getElementById("ndeliver"+i).value=0;
                    salah=true;
                    break;
                }
            }
        }

        if(!salah){
            dtmp1=parseFloat(formatulang(document.getElementById("ncustomerdiscount1").value));
            dtmp2=parseFloat(formatulang(document.getElementById("ncustomerdiscount2").value));
            dtmp3=parseFloat(formatulang(document.getElementById("ncustomerdiscount3").value));
            dtmp4=parseFloat(formatulang(document.getElementById("ncustomerdiscount4").value));
            vdis1=0;
            vdis2=0;
            vdis3=0;
            vdis4=0;
            vdis1x=0;
            vdis2x=0;
            vdis3x=0;
            vdis4x=0;

            vtot =0;
            vtotx=0;
            for(i=1;i<=jml;i++){
                vhrg=formatulang(document.getElementById("vproductretail"+i).value);
                vhrgx=formatulang(document.getElementById("vproductretail"+i).value);
                nqty=formatulang(document.getElementById("norder"+i).value);
                nqtyx=formatulang(document.getElementById("ndeliver"+i).value);
                vhrg=parseFloat(vhrg)*parseFloat(nqty);
                vhrgx=parseFloat(vhrgx)*parseFloat(nqtyx);
                vtot=vtot+vhrg;
                vtotx=vtotx+vhrgx;
                document.getElementById("vtotal"+i).value=formatcemua(vhrg);
            }
            vdis1=vdis1+((vtot*dtmp1)/100);
            vdis2=vdis2+(((vtot-vdis1)*dtmp2)/100);
            vdis3=vdis3+(((vtot-(vdis1+vdis2))*dtmp3)/100);
            vdis4=vdis4+(((vtot-(vdis1+vdis2+vdis3))*dtmp4)/100);
            vdis1x=vdis1x+((vtotx*dtmp1)/100);
            vdis2x=vdis2x+(((vtotx-vdis1x)*dtmp2)/100);
            vdis3x=vdis3x+(((vtotx-(vdis1x+vdis2x))*dtmp3)/100);
            vdis4x=vdis4x+(((vtotx-(vdis1x+vdis2x+vdis3x))*dtmp4)/100);

            document.getElementById("vcustomerdiscount1").value=formatcemua(Math.round(vdis1));
            document.getElementById("vcustomerdiscount2").value=formatcemua(Math.round(vdis2));
            document.getElementById("vcustomerdiscount3").value=formatcemua(Math.round(vdis3));
            document.getElementById("vcustomerdiscount4").value=formatcemua(Math.round(vdis4));
            vdis1=parseFloat(vdis1);
            vdis2=parseFloat(vdis2);
            vdis3=parseFloat(vdis3);
            vtotdis=vdis1+vdis2+vdis3+vdis4;
            vdis1x=parseFloat(vdis1x);
            vdis2x=parseFloat(vdis2x);
            vdis3x=parseFloat(vdis3x);
            vdis4x=parseFloat(vdis4x);
            vtotdisx=vdis1x+vdis2x+vdis3x+vdis4x;
            document.getElementById("vspbdiscounttotal").value=formatcemua(Math.round(vtotdis));
            document.getElementById("vspb").value=formatcemua(vtot);
            vtotbersih=parseFloat(formatulang(formatcemua(vtot)))-parseFloat(formatulang(formatcemua(Math.round(vtotdis))));
            document.getElementById("vspbbersih").value=formatcemua(vtotbersih);
            document.getElementById("vspbdiscounttotalafter").value=formatcemua(Math.round(vtotdisx));
            vtotbersihx=parseFloat(formatulang(formatcemua(vtotx)))-parseFloat(formatulang(formatcemua(Math.round(vtotdisx))));
            document.getElementById("vspbafter").value=formatcemua(vtotbersihx);
        }
    }
}
</script>