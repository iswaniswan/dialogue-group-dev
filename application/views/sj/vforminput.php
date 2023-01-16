<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-12">Tanggal SJ</label>
                        <div class="col-sm-12">
                            <input type="text" name="dsj" id="dsj" class="form-control" value="<?= $dsj; ?>" required="" readonly="">
                            <input id="isjold" name="isjold" type="hidden" value="<?php if($isjold) echo $isjold; ?>">
                            <input id="isj" name="isj" type="hidden"></td>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nama Toko</label>
                        <div class="col-sm-12">
                            <input type="text" name="ecustomer" id="ecustomer" class="form-control" readonly="" value="<?= $ecustomername;?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">&nbsp;</label>
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales(parseFloat(document.getElementById('jml').value));"> <i
                                class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                                &nbsp;&nbsp;
                                <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i
                                    class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <input type="hidden" name="jml" id="jml" value="<?= $jmlitem; ?>">
                            <div class="form-group">
                                <label class="col-md-12">Area</label>
                                <div class="col-sm-12">
                                    <select name="area" id="area" class="form-control" disabled="">
                                        <?php if($area){ foreach ($area->result() as $kuy):?>
                                            <option value="<?php echo $kuy->i_area;?>" <? if($kuy->i_area==$iarea) echo 'selected'; ?>>
                                                <?php echo $kuy->i_area." - ".$kuy->e_area_name;?></option>
                                            <?php endforeach; 
                                        } ?>
                                    </select>
                                    <input type="hidden" name="iarea" value="<?= $iarea;?>">
                                    <input type="hidden" name="istore" value="<?= $istore;?>">
                                    <input id="istore" name="istore" type="hidden" value="<?= $istore;?>">
                                    <input id="ntop" name="ntop" type="hidden" value="<?= $ntop; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">SPB</label>
                                <div class="col-sm-12">
                                    <input type="text" name="ispb" class="form-control" readonly="" value="<?= $ispb;?>">
                                    <input type="hidden" name="dspb" class="form-control" readonly="" value="<?= $dspb;?>">
                                    <input id="vsjgross" name="vsjgross" type="hidden" value="<?php if($vsjgross) echo $vsjgross; ?>">
                                    <input id="nsjdiscount1" name="nsjdiscount1" type="hidden" value="<?= $nsjdiscount1; ?>">
                                    <input id="nsjdiscount2" name="nsjdiscount2" type="hidden" value="<?= $nsjdiscount2; ?>">
                                    <input id="nsjdiscount3" name="nsjdiscount3" type="hidden" value="<?= $nsjdiscount3; ?>">
                                    <input id="vsjdiscount1" name="vsjdiscount1" type="hidden" value="<?= $vsjdiscount1; ?>">
                                    <input id="vsjdiscount2" name="vsjdiscount2" type="hidden" value="<?= $vsjdiscount2; ?>">
                                    <input id="vsjdiscount3" name="vsjdiscount3" type="hidden" value="<?= $vsjdiscount3; ?>">
                                    <input id="vsjdiscounttotal" name="vsjdiscounttotal" type="hidden" value="<?= $vsjdiscounttotal; ?>">
                                    <input id="fspbplusppn" name="fspbplusppn" type="hidden" value="<?= $fspbplusppn; ?>">
                                    <input id="fspbconsigment" name="fspbconsigment" type="hidden" value="<?= $fspbconsigment; ?>">
                                    <input id="fplusppn" name="fplusppn" type="hidden" value="<?= $fspbplusppn; ?>">
                                    <input id="icustomer" name="icustomer" type="hidden" value="<?= $icustomer; ?>">
                                    <input id="isalesman" name="isalesman" type="hidden" value="<?= $isalesman; ?>"></td>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Nilai</label>
                                <div class="col-sm-12">
                                    <input type="text" readonly style="text-align: left;" id="vsjnetto" class="form-control" name="vsjnetto" value="0">
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="text-align: center; width: 7%;">No</th>
                                        <th style="text-align: center; width: 15%;">Kode</th>
                                        <th style="text-align: center; width: 40%;">Nama Barang</th>
                                        <th style="text-align: center;">Motif</th>
                                        <th style="text-align: center;">Jml Ord</th>
                                        <th style="text-align: center;">Jml Kirim</th>
                                        <th style="text-align: center;" class="text-nowrap">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($detail) {
                                        $i = 0;
                                        foreach ($detail as $row) { 
                                            $i++; 
                                            $pangaos = number_format($row->harga,2);
                                            $query=$this->db->query(" 
                                                SELECT f_spb_stockdaerah 
                                                FROM tm_spb
                                                WHERE i_spb= '$ispb' 
                                                AND i_area='$iarea'",false);
                                            if ($query->num_rows() > 0){
                                                foreach($query->result() as $qq){
                                                    $stockdaerah=$qq->f_spb_stockdaerah;
                                                }
                                            }
                                            if($stockdaerah=='f'){
                                                $query=$this->db->query(" 
                                                    SELECT n_saldo_akhir AS qty 
                                                    FROM f_mutasi_stock_pusat_saldoakhir('$thbl') 
                                                    WHERE i_product = '$row->kode' 
                                                    AND i_product_grade = '$row->grade'",false); 
                                            }else{
                                                $query=$this->db->query("   
                                                    SELECT n_saldo_akhir AS qty 
                                                    FROM f_mutasi_stock_daerah_all_saldoakhir('$thbl') 
                                                    WHERE i_product = '$row->kode' 
                                                    AND i_product_grade = '$row->grade'
                                                    AND i_store = '$istore' ",false);
                                            }
                                            if ($query->num_rows() > 0){
                                                foreach($query->result() as $tt){
                                                    $stock=$tt->qty;
                                                }
                                            }else{
                                                $stock=0;
                                            }
                                            if($stock>$row->n_qty)$stock=$row->n_qty;
                                            if($stock<0)$stock=0;
                                            $vtot=$row->harga*$stock;
                                            if($stock>$row->n_deliver) $stock=$row->n_deliver;
                                            if($fspbconsigment=='t') $stock=$row->n_qty;
                                            $stock=number_format($stock);
                                            ?>
                                            <tr>
                                                <td>
                                                    <div class="col-sm-12">
                                                        <input style="text-align: center;" readonly type="text" class="form-control" id="baris<?=$i;?>" name="baris<?=$i;?>" value="<?= $i;?>">
                                                        <input type="hidden" id="motif<?=$i;?>" name="motif<?=$i;?>" value="<?= $row->motif; ?>">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="col-sm-12">
                                                        <input class="form-control" readonly type="text" id="iproduct<?=$i;?>" name="iproduct<?=$i;?>" value="<?= $row->kode; ?>">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="col-sm-12">
                                                        <input readonly type="text" class="form-control" id="eproductname<?=$i;?>" name="eproductname<?=$i;?>" value="<?= $row->nama; ?>">
                                                    </div>
                                                </td>
                                                <td>
                                                    <input class="form-control" readonly type="text" id="emotifname<?=$i;?>" name="emotifname<?=$i;?>" value="<?= $row->namamotif; ?>">
                                                    <input type="hidden" id="vproductmill<?=$i;?>" name="vproductmill<?=$i;?>" value="<?= $pangaos ;?>">
                                                </td>
                                                <td>
                                                    <input class="form-control form-control-success" readonly style="text-align:right;" type="text" id="norder<?=$i;?>" name="norder<?=$i;?>" value="<?= $row->n_qty; ?>">
                                                </td>
                                                <td>
                                                    <input class="form-control"  style="text-align:right;" type="text" id="ndeliver<?=$i;?>" name="ndeliver<?=$i;?>" value="<?= $stock; ?>"
                                                    onblur="hitungnilai(); pembandingnilai('<?=$i;?>');" onkeyup="hitungnilai(); pembandingnilai('<?=$i;?>');" onpaste="hitungnilai(); pembandingnilai('<?=$i;?>');" autocomplete="off">
                                                    <input type="hidden" id="ndeliverhidden<?=$i;?>" name="ndeliverhidden<?=$i;?>" value="<?= $stock ;?>">
                                                    <input type="hidden" id="vtotal<?=$i;?>" name="vtotal<?=$i;?>" value="<?= $vtot; ?>">
                                                </td>
                                                <td style="text-align: center;">
                                                    <input type="checkbox" name="chk<?=$i;?>" id="chk<?=$i;?>" value="" onclick="pilihan(this.value,'<?=$i;?>');">
                                                </td>
                                            </tr>
                                        <?php  }
                                    } ?>
                                </tbody>
                            </table>
                        </form>
                    </div>
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
        $(".select2").select2();
        showCalendar('.date');
        hitungnilai();
    });

    function pembandingnilai(a) {
        var n_qty = formatulang(document.getElementById('norder'+a).value);
        var n_deliver = formatulang(document.getElementById('ndeliver'+a).value);
        var deliverasal   = formatulang(document.getElementById('ndeliverhidden'+a).value);

        if(parseInt(n_deliver) > parseInt(n_qty)) {
            alert('Jml kirim ( '+n_deliver+' item ) tdk dpt melebihi Order ( '+n_qty+' item )');
            document.getElementById('ndeliver'+a).value   = deliverasal;
            document.getElementById('ndeliver'+a).focus();
            return false;
        }else if(parseInt(n_deliver) > parseInt(deliverasal)) {
            istore = document.getElementById('istore').value;
            kons    = document.getElementById('fspbconsigment').value;
            alert('Jml kirim ( '+n_deliver+' item ) tdk dpt melebihi Stock ( '+deliverasal+' item )');
            document.getElementById('ndeliver'+a).value = deliverasal;
            document.getElementById('ndeliver'+a).focus();
            return false;
        }
    }

    function dipales(a){
        cek='false';
        if((document.getElementById("dsj").value!='') && (document.getElementById("iarea").value!='')) {
            if(a==0){
                alert('Isi data item minimal 1 !!!');
                return false;
            }else{
                for(i=1;i<=a;i++){
                    if((document.getElementById("iproduct"+i).value=='') || (document.getElementById("eproductname"+i).value=='') || (document.getElementById("norder"+i).value=='')){                        
                        alert('Data item masih ada yang salah !!!');                    
                        exit();
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

    function hitungnilai(){
        jml=document.getElementById("jml").value;
        if (jml<=0){
        }else{
            salah=false;
            gud=document.getElementById("istore").value;
            kons=document.getElementById("fspbconsigment").value;
            for(i=1;i<=jml;i++){              
                y=formatulang(document.getElementById("ndeliver"+i).value);              
                if(!isNaN(y)){                
                    stock  =formatulang(document.getElementById("ndeliverhidden"+i).value);                
                    deliver=formatulang(document.getElementById("ndeliver"+i).value);                
                    if(parseFloat(stock)<0)stock=0;                
                    if(parseFloat(deliver)>parseFloat(stock)){                  
                        alert('Jumlah Kirim melebihi jumlah stock');                  
                        document.getElementById("ndeliver"+i).value=0;                  
                        salah=true;                  
                        break;
                    }
                }else{
                    alert('Input harus numerik');
                    document.getElementById("ndeliver"+i).value=0;
                }
            }
            if(!salah){
                gros=0;
                grosgr=0;
                for(i=1;i<=jml;i++){                  
                    if(document.getElementById("chk"+i).value=='on'){
                        if(document.getElementById("fplusppn").value=='f' && document.getElementById("fspbconsigment").value=='f'){                      
                            hrg=parseFloat(formatulang(document.getElementById("vproductmill"+i).value))/1.1;                  
                        }else{                      
                            hrg=formatulang(document.getElementById("vproductmill"+i).value);                  
                        }                  
                        hrggr=formatulang(document.getElementById("vproductmill"+i).value);                  
                        qty=formatulang(document.getElementById("ndeliver"+i).value);                  
                        vhrg=parseFloat(hrg)*parseFloat(qty);                  
                        vhrggr=parseFloat(hrggr)*parseFloat(qty);                  
                        gros=gros+vhrg;                  
                        grosgr=grosgr+vhrggr;                  
                        document.getElementById("vtotal"+i).value=formatcemua(vhrg);
                    }
                }
                document.getElementById("vsjgross").value=formatcemua(grosgr);
                nsjdisc1=parseFloat(formatulang(document.getElementById("nsjdiscount1").value));
                nsjdisc2=parseFloat(formatulang(document.getElementById("nsjdiscount2").value));
                nsjdisc3=parseFloat(formatulang(document.getElementById("nsjdiscount3").value));
                vsjdiscounttotal=parseFloat(formatulang(document.getElementById("vsjdiscounttotal").value))
                if( (nsjdisc1+nsjdisc2+nsjdisc3==0) && (vsjdiscounttotal!=0) ){                  
                    vsjdisc1=vsjdiscounttotal;              
                }else{                  
                    vsjdisc1=0;              
                }              
                vsjdisc2=0;              
                vsjdisc3=0;
                vtot =0;
                if(gros>0){                  
                    if(nsjdisc1>0){                      
                        vsjdisc1=vsjdisc1+((grosgr*nsjdisc1)/100);
                        vsjdisc2=vsjdisc2+(((grosgr-vsjdisc1)*nsjdisc2)/100);
                        vsjdisc3=vsjdisc3+(((grosgr-(vsjdisc1+vsjdisc2))*nsjdisc3)/100);
                        document.getElementById("vsjdiscount1").value=formatcemua(vsjdisc1);
                        document.getElementById("vsjdiscount2").value=formatcemua(vsjdisc2);
                        document.getElementById("vsjdiscount3").value=formatcemua(vsjdisc3);
                        vdis1=parseFloat(vsjdisc1);
                        vdis2=parseFloat(vsjdisc2);
                        vdis3=parseFloat(vsjdisc3);
                        nTDisc1  = nsjdisc1  + nsjdisc2  * (100-nsjdisc1)/100;
                        nTDisc2  = nsjdisc3  * (100-nsjdisc3)/100;
                        nTDisc   = nTDisc1 + nTDisc2 * (100-nTDisc1)/100;
                    }
                    if( (nTDisc==0) && (vsjdiscounttotal!=0) ){ 
                        vtotdis = vsjdiscounttotal;
                    }else{
                        vtotdis = nTDisc * grosgr / 100;
                    }

                    if(document.getElementById("fplusppn").value=='t'){
                        vtotdis=Math.round(vtotdis);
                    }
                    if(document.getElementById("fspbconsigment").value=='f'){                      
                        document.getElementById("vsjdiscounttotal").value=formatcemua(Math.round(vtotdis));                      
                        vtotbersih=parseFloat(grosgr)-parseFloat(vtotdis);                      
                        document.getElementById("vsjnetto").value=formatcemua(Math.round(vtotbersih));                  
                    }else if(document.getElementById("fplusppn").value=='t'){                      
                        vtotbersih=parseFloat(gros)-parseFloat(formatulang(document.getElementById("vsjdiscounttotal").value));                      
                        document.getElementById("vsjnetto").value=formatcemua(Math.round(vtotbersih));                  
                    }else{                    
                        vtotbersih=parseFloat(grosgr)-parseFloat(Math.round(vtotdis));                    
                        document.getElementById("vsjnetto").value=formatcemua(Math.round(vtotbersih));                
                    }            
                }else{              
                    document.getElementById("vsjdiscount1").value=formatcemua(vsjdisc1);              
                    document.getElementById("vsjdiscount2").value=formatcemua(vsjdisc2);              
                    document.getElementById("vsjdiscount3").value=formatcemua(vsjdisc3);              
                    document.getElementById("vsjnetto").value=0;              
                    if(document.getElementById("fspbconsigment").value=='f'){                
                        document.getElementById("vsjdiscounttotal").value=0;
                    }
                }
            }
        }
    }

    function pilihan(a,b){      
        if(a==''){          
            document.getElementById("chk"+b).value='on';      
        }else{          
            document.getElementById("chk"+b).value='';      
        }      
        hitungnilai();  
    }
</script>