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
                        <label class="col-md-6">Tanggal Nota</label><label class="col-md-6">No Nota</label>
                        <div class="col-sm-6">
                            <input id= "dnota" name="dnota" class="form-control" required="" readonly value="<?= $isi->dsj;?>">
                        </div>
                        <div class="col-sm-6">
                            <input id="inotaold" name="inotaold" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">No SPB</label><label class="col-md-6">Tanggal SPB</label>
                        <div class="col-sm-6">
                            <input id="ispb" name="ispb" class="form-control" readonly value="<?= $isi->i_spb;?>">
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
                            <input type="hidden" readonly id="spbold" name="spbold" value="<?php echo $isi->i_spb_old; ?>"readonly >
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
                        <label class="col-md-12">PO</label>
                        <div class="col-sm-12">
                            <input type="text" id="ispbpo" name="ispbpo" class="form-control" maxlength="30" readonly value="<?= $isi->i_spb_po; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-3">
                            <div class="form-check">
                                <label class="custom-control custom-checkbox">
                                    <input type="checkbox" id="fmasalah" name="fmasalah" class="custom-control-input">
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">Masalah</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <label class="custom-control custom-checkbox">
                                    <input type="checkbox" id="finsentif" name="finsentif" class="custom-control-input">
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">Insentif</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <label class="custom-control custom-checkbox">
                                    <input type="checkbox" id="fcicil" name="fcicil" class="custom-control-input" 
                                    <?php if($isi->f_customer_cicil=='t') echo "checked";?>>
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">Cicil</span>
                                </label>
                            </div>
                        </div>
                    </div>  
                    <div class="form-group row">
                        <div class="col-md-3">
                            <div class="form-check">
                                <label class="custom-control custom-checkbox">
                                    <span class="custom-control-description">TOP&nbsp;&nbsp;</span>
                                    <input class="form-control" name="nspbtoplength" id="nspbtoplength" type="text" readonly="" value="<?= $isi->n_spb_toplength; ?>">
                                </label>
                            </div>
                        </div>
                        <?php 
                        $tmp = explode("-", $isi->d_sj);
                        $det    = $tmp[2];
                        $mon    = $tmp[1];
                        $yir    = $tmp[0];
                        $dsj    = $yir."/".$mon."/".$det;
                        if(substr($isi->i_sj,8,2)=='00'){
                            $topnya=$isi->n_spb_toplength;
                        }else{
                            $topnya=$isi->n_spb_toplength;
                        }
                        $dudet  = $this->fungsi->dateAdd("d",$topnya,$dsj);
                        $dudet  = explode("-", $dudet);
                        $det1   = $dudet[2];
                        $mon1   = $dudet[1];
                        $yir1   = $dudet[0];
                        $dudet  = $det1."-".$mon1."-".$yir1;
                        ?>
                        <div class="col-md-6">
                            <div class="form-check">
                                <label class="custom-control" >
                                    <span class="custom-control-description">Jatuh Tempo&nbsp;&nbsp;</span>
                                    <input class="form-control" name="djatuhtempo" id="djatuhtempo" type="text" readonly="" value="<?= $dudet; ?>">
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
                        <label class="col-md-6">Surat Jalan</label><label class="col-md-6">Tanggal Surat Jalan</label>
                        <div class="col-sm-6">
                            <input type="hidden" id="fspbstokdaerah" name="fspbstokdaerah" class="form-control" maxlength="7" value="">
                            <input type="text" id="isj" name="isj" class="form-control" maxlength="15" value="<?php echo $isi->i_sj; ?>" readonly>
                        </div>
                        <div class="col-sm-6">
                            <input type="hidden" id="dsj" name="dsj" class="form-control date" value="<?php echo $isi->d_sj; ?>" readonly>
                            <input type="text" id="dsx" name="dsx" class="form-control date" value="<?php echo $isi->dsj; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">PKP</label>
                        <div class="col-sm-12">
                            <input id="fspbpkp" name="fspbpkp" type="hidden" value="<?= $isi->f_spb_pkp;?>">
                            <input type="text" readonly id="ecustomerpkpnpwp" name="ecustomerpkpnpwp" class="form-control" maxlength="30" value="<?= $isi->e_customer_pkpnpwp;?>">
                            <input id="fspbplusppn" name="fspbplusppn" type="hidden" value="<?= $isi->f_spb_plusppn;?>">
                            <input id="fspbplusdiscount" name="fspbplusdiscount" type="hidden" value="<?= $isi->f_spb_plusdiscount;?>">
                            <input type="hidden" id="nprice" name="nprice" value="1">
                            <input type="hidden" id="vnotagross" name="vnotagross" value="0">
                            <input type="hidden" id="vnotappn" name="vnotappn" value="0">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-8">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales();"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                            &nbsp;&nbsp;<button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/view/<?= $dfrom;?>/<?= $dto;?>/<?= $iarea;?>/","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6"> 
                    <div class="form-group row">
                        <label class="col-md-12">Kelompok Harga</label>
                        <div class="col-sm-6">
                            <input type="text" id="epricegroupname" name="epricegroupname" class="form-control" value="<?= $isi->e_price_groupname; ?>" readonly>
                            <input id="ipricegroup" name="ipricegroup" type="hidden" value="<?= $isi->i_price_group; ?>">
                            <input id="fspbconsigment" name="fspbconsigment" type="hidden" value="<?php echo $isi->f_spb_consigment; ?>">
                        </div>
                    </div>
                    <?php 
                    $enin=number_format($isi->v_spb);
                    ?>
                    <div class="form-group row">
                        <label class="col-md-12">Nilai Kotor</label>
                        <div class="col-sm-12">
                            <input type="text" id="vspb" name="vspb" class="form-control" required="" readonly value="<?= $enin; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">Discount 1</label><label class="col-md-6">Nilai Discount 1</label>
                        <div class="col-sm-6">
                            <input id="ncustomerdiscount1" name="ncustomerdiscount1" class="form-control" required="" onkeypress="return hanyaAngka(event);" onkeyup="formatcemua(this.value); editnilai();" value="<?= $isi->n_spb_discount1; ?>">
                        </div>
                        <div class="col-sm-6">
                            <input id= "vcustomerdiscount1" name="vcustomerdiscount1" class="form-control" required="" readonly value="<?= number_format($isi->v_spb_discount1); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">Discount 2</label><label class="col-md-6">Nilai Discount 2</label>
                        <div class="col-sm-6">
                            <input id="ncustomerdiscount2" name="ncustomerdiscount2" class="form-control" required="" onkeypress="return hanyaAngka(event);" onkeyup="formatcemua(this.value); editnilai();" value="<?= $isi->n_spb_discount2; ?>">
                        </div>
                        <div class="col-sm-6">
                            <input id="vcustomerdiscount2" name="vcustomerdiscount2" class="form-control" required=""
                            readonly value="<?= number_format($isi->v_spb_discount2); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">Discount 3</label><label class="col-md-6">Nilai Discount 3</label>
                        <div class="col-sm-6">
                            <input id="ncustomerdiscount3" name="ncustomerdiscount3" class="form-control" required="" onkeypress="return hanyaAngka(event);" onkeyup="formatcemua(this.value); editnilai();" value="<?= $isi->n_spb_discount3; ?>">
                        </div>
                        <div class="col-sm-6">
                            <input id="vcustomerdiscount3" name="vcustomerdiscount3" class="form-control" required="" readonly value="<?= number_format($isi->v_spb_discount3); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">Discount 4</label><label class="col-md-6">Nilai Discount 4</label>
                        <div class="col-sm-6">
                            <input id="ncustomerdiscount4" name="ncustomerdiscount4" class="form-control" required="" onkeypress="return hanyaAngka(event);" onkeyup="formatcemua(this.value); editnilai();" value="<?= $isi->n_spb_discount4; ?>">
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
                            <input id="vspbdiscounttotalafter" name="vspbdiscounttotalafter" class="form-control" required="" onkeyup="hitungnilai();reformat(this);"
                            readonly value="<?= number_format($isi->v_nota_discounttotal); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Nilai SPB (Realisasi)</label>
                        <div class="col-sm-12">
                            <input id="vspbafter" name="vspbafter" class="form-control" required="" onkeyup="hitungdiscount();reformat(this);" value="<?= number_format($isi->v_nota_netto);?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <input id="eremark" name="eremark" value="" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%;" cellspacing="0">
                        <thead>
                            <tr>
                                <th style="text-align: center; width: 5%;">No</th>
                                <th style="text-align: center; width: 10%;">Kode Barang</th>
                                <th style="text-align: center; width: 30%;">Nama Barang</th>
                                <th style="text-align: center;">Motif</th>
                                <th style="text-align: center;">Harga</th>
                                <th style="text-align: center;">Qty Pesan</th>
                                <th style="text-align: center;">Qty Kirim</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($detail) {
                                $i = 0;
                                foreach ($detail as $row) { 
                                    $i++; 
                                    $harga  =number_format($row->v_unit_price,2);
                                    $norder =number_format($row->n_order,0);
                                    $ndeliv =number_format($row->n_deliver,0);
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
                                            <input class="form-control form-control-success" style="text-align:right;" readonly type="text" id="norder<?=$i;?>" name="norder<?=$i;?>" value="<?= $norder; ?>">
                                        </td>
                                        <td>
                                            <input class="form-control" style="text-align:right;" readonly type="text" id="ndeliver<?=$i;?>" name="ndeliver<?=$i;?>" value="<?= $ndeliv; ?>">
                                            <input class="form-control" style="text-align:right;" readonly type="hidden" id="vtotal<?=$i;?>" name="vtotal<?=$i;?>" value="">
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
    function editnilai(){
        var fppn = document.getElementById("fspbplusppn").value;
        var jml     = parseFloat(document.getElementById("jml").value);
        var totdis  = 0;
        var totnil  = 0;
        var hrg     = 0;
        var ndis1   = parseFloat(formatulang(document.getElementById("ncustomerdiscount1").value));
        var ndis2   = parseFloat(formatulang(document.getElementById("ncustomerdiscount2").value));
        var ndis3   = parseFloat(formatulang(document.getElementById("ncustomerdiscount3").value));
        var ndis4   = parseFloat(formatulang(document.getElementById("ncustomerdiscount4").value));
        if( (ndis1+ndis2+ndis3+ndis4==0) ){          
            vdis1=parseFloat(formatulang(document.getElementById("vspbdiscounttotalafter").value));
            document.getElementById("vspbdiscounttotal").value=document.getElementById("vspbdiscounttotalafter").value;
        }else{          
            vdis1=0;
        }
        var vdis2   = 0;
        var vdis3   = 0;
        var vdis4   = 0;
        for(i=1;i<=jml;i++){
            var vhrg = parseFloat(formatulang(document.getElementById("vproductretail"+i).value));
            qty=formatulang(document.getElementById("ndeliver"+i).value);
            hrgtmp=parseFloat(vhrg)*parseFloat(qty);
            hrg         = hrg+hrgtmp;
        }
        if(ndis1>0) vdis1=vdis1+(hrg*ndis1)/100;
        vdis2=vdis2+(((hrg-vdis1)*ndis2)/100);
        vdis3=vdis3+(((hrg-(vdis1+vdis2))*ndis3)/100);
        vdis4=vdis4+(((hrg-(vdis1+vdis2+vdis3))*ndis4)/100);
        vdistot   = Math.round(vdis1+vdis2+vdis3+vdis4);
        nTDisc1  = ndis1  + ndis2  * (100-ndis1)/100;
        nTDisc2  = ndis3  + ndis4  * (100-ndis3)/100;
        nTDisc   = nTDisc1 + nTDisc2 * (100-nTDisc1)/100;
        document.getElementById("vcustomerdiscount1").value=formatcemua(Math.round(vdis1));
        document.getElementById("vcustomerdiscount2").value=formatcemua(Math.round(vdis2));
        document.getElementById("vcustomerdiscount3").value=formatcemua(Math.round(vdis3));
        document.getElementById("vcustomerdiscount4").value=formatcemua(Math.round(vdis4));
        if(document.getElementById("fspbconsigment").value=='f'){
            vtotbersih=parseFloat(hrg)-parseFloat(Math.round(vdistot));
            document.getElementById("vspbdiscounttotalafter").value=formatcemua(Math.round(vdistot));
            document.getElementById("vspbafter").value=formatcemua(Math.round(vtotbersih));
        }
        var fdis = document.getElementById("fspbplusdiscount").value;
        var bersih = vtotbersih;
        var kotor  = hrg;
        if( (fppn=='t') && (fdis=='f') ){
            document.getElementById("nprice").value=1;
            document.getElementById("vnotappn").value=0;
        }else if( (fppn=='t') && (fdis=='t') ){
            document.getElementById("nprice").value=bersih/kotor;
            document.getElementById("vnotappn").value=0;
        }else if( (fppn=='f') && (fdis=='t') ){
            document.getElementById("nprice").value=1/1.1;
            kotorminppn=Math.round(hrg/1.1);
            if(document.getElementById("fspbconsigment").value=='f'){
              document.getElementById("vspbdiscounttotalafter").value=0;
              document.getElementById("vnotappn").value=formatcemua(Math.round(vppn));
          }
      }else if( (fppn=='f') && (fdis=='f') ){
        document.getElementById("nprice").value=1/1.1;
        if(document.getElementById("fspbconsigment").value=='f'){
            document.getElementById("vnotappn").value=formatcemua(Math.round(vppn));
        }else{
            vppn=(Math.round(hrg)-Math.round(vdistot))*0.1;
            document.getElementById("vspbdiscounttotalafter").value=formatcemua(Math.round(vdistot));
            document.getElementById("vspbafter").value=formatcemua(vtotbersih);

        }
    }
}

function hitungdiscount(){      
    var nilaispb=document.getElementById("vspb").value.replace(/\,/g,'');
    var nilaitot=document.getElementById("vspbafter").value.replace(/\,/g,'');
    if(!isNaN(nilaitot)){      
        if((nilaispb-nilaitot)<0){          
            alert("Nilai total tidak valid !!!!!");
            document.getElementById("vspbafter").value = document.getElementById("vspbafter").value.substring(0,input.value.length-1);
        }else{
            document.getElementById("vspbbersih").value=document.getElementById("vspbafter").value;
            document.getElementById("vspbdiscounttotal").value=formatcemua(nilaispb-nilaitot);
            document.getElementById("vspbdiscounttotalafter").value=formatcemua(nilaispb-nilaitot);
        }
    }else{   
        alert('input harus numerik !!!');    
        document.getElementById("vspbafter").value = document.getElementById("vspbafter").value.substring(0,input.value.length-1);
    }
} 

function hitungnilai(){    
    var nilaispb=document.getElementById("vspb").value.replace(/\,/g,'');
    var nilaidis=document.getElementById("vspbdiscounttotalafter").value.replace(/\,/g,'');
    if(!isNaN(nilaidis)){
        if((nilaispb-nilaidis)<0){
            alert("Nilai discount tidak valid !!!!!");
            document.getElementById("vspbdiscounttotalafter").value = document.getElementById("vspbdiscounttotalafter").value.substring(0,input.value.length-1);
        }else{
            document.getElementById("vspbafter").value=formatcemua(nilaispb-nilaidis);
        }
    }else{ 
        alert('input harus numerik !!!');
        document.getElementById("vspbdiscounttotalafter").value = document.getElementById("vspbdiscounttotalafter").value.substring(0,input.value.length-1);
    }
}

function dipales(){
    if(document.getElementById("dnota").value!=''){
        return true;
    }else{
        alert("Tanggal nota tidak boleh kosong");
        return false;
    }
}

$("form").submit(function(event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
});

function hanyaAngka(evt) {      
    var charCode = (evt.which) ? evt.which : event.keyCode      
    if (charCode > 31 && (charCode < 48 || charCode > 57))        
        return false;    
    return true;
}
</script>