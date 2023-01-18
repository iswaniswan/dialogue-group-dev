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
                <div class="col-md-6">
                    <div id="pesan"></div>
                    <div class="form-group row">
                        <label class="col-md-6">No SJ</label><label class="col-md-6">Tanggal SJ</label>
                        <?php if($isi->d_sj){
			                if($isi->d_sj!=''){
			                	  $tmp=explode("-",$isi->d_sj);
			                	  $hr=$tmp[2];
			                	  $bl=$tmp[1];
			                	  $th=$tmp[0];
			                	  $isi->d_sj=$hr."-".$bl."-".$th;
			                }
		                }?>
                            <div class="col-sm-6">
                                <input readonly id="isj" name="isj" class="form-control" value="<?php echo $isi->i_sj; ?>">
                            </div>
                            <div class="col-sm-3">
                                <input readonly id="dsj" name="dsj" class="form-control date" value="<?php echo $isi->d_sj; ?>">
                            </div>
                    </div>
                    <div class="form-group row">
                        <?php if($isi->d_spb){
			                if($isi->d_spb!=''){
			                	  $tmp=explode("-",$isi->d_spb);
			                	  $hr=$tmp[2];
			                	  $bl=$tmp[1];
			                	  $th=$tmp[0];
			                	  $isi->d_spb=$hr."-".$bl."-".$th;
			                }
		                }?>
                        <label class="col-md-6">No SPB</label><label class="col-md-6">Tanggal SPB</label>
                        <div class="col-sm-6">
                            <input readonly id="ispb" name="ispb" class="form-control" value="<?php echo $isi->i_spb; ?>">
                        </div>
                        <input id="vsjgross" name="vsjgross" type="hidden" value="<?php echo $isi->v_nota_gross; ?>">
		                <input id="nsjdiscount1" name="nsjdiscount1" type="hidden" value="<?php echo $isi->n_nota_discount1; ?>">
		                <input id="nsjdiscount2" name="nsjdiscount2" type="hidden" value="<?php echo $isi->n_nota_discount2; ?>">
		                <input id="nsjdiscount3" name="nsjdiscount3" type="hidden" value="<?php echo $isi->n_nota_discount3; ?>">
		                <input id="vsjdiscount1" name="vsjdiscount1" type="hidden" value="<?php echo $isi->v_nota_discount1; ?>">
		                <input id="vsjdiscount2" name="vsjdiscount2" type="hidden" value="<?php echo $isi->v_nota_discount2; ?>">
		                <input id="vsjdiscount3" name="vsjdiscount3" type="hidden" value="<?php echo $isi->v_nota_discount3; ?>">
		                <input id="vsjdiscounttotal" name="vsjdiscounttotal" type="hidden" value="<?php echo $isi->v_nota_discounttotal;?>">
                        <input id="fspbconsigment" name="fspbconsigment" type="hidden" value="<?php echo $stockdaerah->f_spb_consigment; ?>">
                        <input id="fplusppn" name="fplusppn" type="hidden" value="<?php echo $isi->f_plus_ppn; ?>">
		                <input id="icustomer" name="icustomer" type="hidden" value="<?php echo $isi->i_customer; ?>">
		                <input id="isalesman" name="isalesman" type="hidden" value="<?php echo $isi->i_salesman; ?>">
                        <input id="ntop" name="ntop" type="hidden" value="<?php echo $isi->n_nota_toplength; ?>">
                        <div class="col-sm-3">
                            <input readonly id="dspb" name="dspb" class="form-control date" value="<?php echo $isi->d_spb; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">SJ Lama</label><label class="col-md-6">Nilai</label>
                        <div class="col-sm-6">
                            <input id="isjold" name="isjold" class="form-control" type="text" value="<?php echo $isi->i_sj_old; ?>">
                        </div>
                        <div class="col-sm-3">
                            <input id="vsjnetto" readonly name="vsjnetto" class="form-control" type="text" value="<?php echo number_format($isi->v_nota_netto);  ?>">
                        </div>
                    </div>
                        <div class="form-group row">
                            <div class="col-sm-offset-3 col-sm-5">
                            <?php if(($isi->i_dkb==null && $isi->i_dkb=='' && $isi->i_nota== '') && $bisaedit && ($departemen=='7' || $departemen=='3' || $departemen='1')){ ?>
                            
		                        <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales(parseFloat(document.getElementById('jml').value));"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                                &nbsp;&nbsp; 
                            <?}?>
                                <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/view/<?=$dfrom;?>/<?=$dto;?>/<?=$iarea;?>","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                                &nbsp;&nbsp;
                            </div>
                        </div>
                </div>

                <div class="col-md-6">
                    <div id="pesan"></div>
                    <div class="form-group row">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-6">
                            <input readonly id="eareaname" class="form-control" name="eareaname" value="<?php if($isi->e_area_name) echo $isi->e_area_name; ?>">
                            <input id="iarea" name="iarea" class="form-control" type="hidden" value="<?php echo $isi->i_area; ?>">
                            <input id="istore" name="istore" type="hidden" value="<?php echo $stockdaerah->i_store; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Nama Toko</label>
                        <div class="col-sm-6">
                            <input readonly id="ecustomername" name="ecustomername" class="form-control" value="<?php if($isi->e_customer_name) echo $isi->e_customer_name; ?>">
                            <input id="icustomer" name="icustomer" type="hidden" class="form-control" value="<?php if($isi->i_customer) echo $isi->i_customer; ?>">
                        </div>
                    </div>
                </div>
                    <div class="table-responsive">
                    <table class="table table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th style="text-align: center; width: 7%;">No</th>
                                        <th style="text-align: center; width: 10%;">Kode Barang</th>
                                        <th style="text-align: center; width: 35%;">Nama Barang</th>
                                        <th style="text-align: center; width: 10%;">Motif</th>
                                        <th style="text-align: center;">Jumlah Order</th>
                                        <th style="text-align: center;">Jumlah Kirim</th>
                                        <th style="text-align: center;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php               
                                    if($detail){
                                         $i=1;
                                         foreach($detail as $row){
                                            if($stockdaerah == 'f'){
                                                $query=$this->db->query(" 
                                                                        SELECT 
                                                                            n_saldo_akhir-(n_mutasi_git+n_git_penjualan) as qty
                                                                        FROM 
                                                                            f_mutasi_stock_pusat_saldoakhir_detail('$thbl','$row->i_product','$row->i_product_grade')"
                                                                        ,false);
                                            }else{
                                                $query=$this->db->query(" 
                                                                        SELECT
                                                                            n_quantity_stock as qty 
                                                                        FROM 
                                                                            tm_ic
                                                                        WHERE 
                                                                            i_product='$row->i_product'
															                and i_product_motif='$row->i_product_motif'
															                and i_product_grade='$row->i_product_grade'
                                                                            and i_store='$stockdaerah->i_store' 
                                                                            and i_store_location='00' 
                                                                            and i_store_locationbin='00'"
                                                                        ,false);
                                            }
                                            if ($query->num_rows() > 0){
                                                foreach($query->result() as $tt){
                                                    $i++;
                                                    $jmlitem++;
                                                    if($tt->qty>=0){
                                                        $stock=$tt->qty+$row->n_deliver;
                                                    }else{
                                                        $stock=$row->n_deliver;
                                                    }
                                                }
                                            }else{
                                                $stock=0;
                                            }
                                            if($stock>$row->n_qty)$stock=$row->n_qty;
							                if($stock<0)$stock=0;
                                            $vtot=$row->harga*$stock;

                                            $stock=number_format($stock);
                                ?>
                                            <tr>
                                                <td style="text-align: center;">
                                                    <input  type="text" class="form-control" readonly id="baris<?= $i;?>" name="baris<?= $i;?>" value="<?= $i;?>">
                                                    <input  class="form-control" type="hidden" id="motif<?= $i;?>" name="motif<?= $i;?>" value="<?= $row->i_product_motif;?>">
                                                </td>
                                                <td>
                                                    <input class="form-control" readonly id="iproduct<?= $i;?>" name="iproduct<?= $i;?>" value="<?= $row->i_product;?>">
                                                </td>
                                                <td>
                                                    <input class="form-control" readonly id="eproductname<?= $i;?>" name="eproductname<?= $i;?>" value="<?= $row->e_product_name;?>">
                                                    <input type="hidden" class="form-control" readonly id="vunitprice<?= $i;?>" name="vunitprice<?= $i;?>" value="<?= $row->v_unit_price;?>">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" readonly id="emotifname<?= $i;?>" name="emotifname<?= $i;?>" value="<?= $row->e_product_motifname;?>">
                                                </td>
                                                <td>
                                                    <input class="form-control" id="norder<?= $i;?>" name="norder<?= $i;?>" readonly value="<?= $row->n_qty;?>">
                                                    <input type="hidden" class="form-control" id="vproductmill<?= $i;?>" name="vproductmill<?= $i;?>" value="<?= $row->v_unit_price;?>">
                                                </td>
                                                <td>
                                                    <input class="form-control" id="ndeliver<?= $i;?>" name="ndeliver<?= $i;?>" value="<?= $row->n_deliver;?>" onkeyup="hitungnilai(this.value);">
                                                    <input class="form-control" type="hidden" id="ntmp<?= $i;?>" name="ntmp<?= $i;?>" value="<?= $row->n_deliver;?>">
                                                    <input class="form-control" type="hidden" id="ndeliverhidden<?= $i;?>" name="ndeliverhidden<?= $i;?>" value="<?= $stock;?>">
                                                    <input class="form-control" type="hidden" id="vtotal<?= $i;?>" name="vtotal<?= $i;?>" value="">
                                                </td>
                                                <td style="text-align: center;">
                                                    <input type='checkbox' name="chk<?=$i;?>" id="chk<?=$i;?>" value='on' checked onclick="pilihan(this.value,'<?=$i;?>');">
                                                </td>
                                            </tr>
                                        <?
                                        }
                                            foreach($cquery as $tmp){
                                                if($stockdaerah == 'f'){
                                                    $query=$this->db->query(" 	
                                                                            select 
                                                                                n_quantity_stock as qty 
                                                                            from 
                                                                                tm_ic
                                                                            where 
                                                                                i_product='$tmp->i_product'
															                    and i_product_motif='$tmp->i_product_motif'
															                    and i_product_grade='$tmp->i_product_grade'
                                                                                and i_store='AA' and i_store_location='01' 
                                                                                and i_store_locationbin='00'"
                                                                            ,false);
                                                }else{
                                                    $query=$this->db->query(" 	
                                                                            select 
                                                                                n_quantity_stock as qty 
                                                                            from 
                                                                                tm_ic
                                                                            where 
                                                                                i_product='$tmp->i_product'
															                    and i_product_motif='$tmp->i_product_motif'
															                    and i_product_grade='$tmp->i_product_grade'
                                                                                and i_store='$stockdaerah->i_store' 
                                                                                and i_store_location='00' 
                                                                                and i_store_locationbin='00'"
                                                                            ,false);
                                                }
                                                if ($query->num_rows() > 0){
                                                    foreach($query->result() as $tt){
                                                        $i++;
                                                        $jmlitem++;
                                                        if($tt->qty>=0){
                                                            $stock=$tt->qty;
                                                        }else{
                                                            $stock=0;
                                                        }
                                                    }
                                                }else{
                                                    $stock=0;
                                                }
                                                if($stock<0)$stock=0;
                                                $vtot=$row->harga*$stock;
                                                $stock=number_format($stock);
                                            ?>
                                            <tr>
                                                <td style="text-align: center;">
                                                    <input  type="text" class="form-control" readonly id="baris<?= $i;?>" name="baris<?= $i;?>" value="<?= $i;?>">
                                                    <input  class="form-control" type="hidden" id="motif<?= $i;?>" name="motif<?= $i;?>" value="<?= $tmp->i_product_motif;?>">
                                                </td>
                                                <td>
                                                    <input class="form-control" readonly id="iproduct<?= $i;?>" name="iproduct<?= $i;?>" value="<?= $tmp->i_product;?>">
                                                </td>
                                                <td>
                                                    <input class="form-control" readonly id="eproductname<?= $i;?>" name="eproductname<?= $i;?>" value="<?= $tmp->e_product_name;?>">
                                                    <input type="hidden" class="form-control" readonly id="vunitprice<?= $i;?>" name="vunitprice<?= $i;?>" value="<?= $tmp->v_unit_price;?>">
                                                </td>
                                                <td>
                                                <input type="text" class="form-control" readonly id="emotifname<?= $i;?>" name="emotifname<?= $i;?>" value="<?= $tmp->e_product_motifname;?>">
                                                </td>
                                                <td>
                                                    <input class="form-control" id="norder<?= $i;?>" name="norder<?= $i;?>" readonly value="<?= $tmp->n_order;?>">
                                                    <input type="hidden" class="form-control" id="vproductmill<?= $i;?>" name="vproductmill<?= $i;?>" value="<?= $tmp->v_unit_price;?>">
                                                </td>
                                                <td>
                                                    <input class="form-control" id="ndeliver<?= $i;?>" name="ndeliver<?= $i;?>" value="<?= $tmp->n_deliver;?>" onkeyup="hitungnilai(this.value);">
                                                    <input class="form-control" type="hidden" id="ntmp<?= $i;?>" name="ntmp<?= $i;?>" value="<?= $tmp->n_deliver;?>">
                                                    <input class="form-control" type="hidden" id="ndeliverhidden<?= $i;?>" name="ndeliverhidden<?= $i;?>" value="<?= $stock;?>">
                                                    <input class="form-control" type="hidden" id="vtotal<?= $i;?>" name="vtotal<?= $i;?>" value="">
                                                </td>
                                                <td style="text-align: center;">
                                                    <input type='checkbox' name="chk<?=$i;?>" id="chk<?=$i;?>" value='on' checked onclick="pilihan(this.value,'<?=$i;?>');">
                                                </td>
                                            </tr>
                                        <?}
                                        }?>
                                    </div>
                                    <input type="hidden" name="jml" id="jml" value="<?= $jmlitem;?>">
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
    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date');
    });

    function dipales(a){
  	 cek='false';
  	 if((document.getElementById("dspb").value!='') &&
  	 	(document.getElementById("iarea").value!='')) {
  	 	if(a==0){
  	 		alert('Isi data item minimal 1 !!!');
  	 	}else{
   			for(i=1;i<=a;i++){
				if((document.getElementById("iproduct"+i).value=='') ||
					(document.getElementById("eproductname"+i).value=='') ||
					(document.getElementById("norder"+i).value=='')){
					alert('Data item masih ada yang salah !!!');
					exit();
					cek='false';
				}else{
					cek='true';	
				} 
			}
		}
		if(cek=='true'){
  	  		document.getElementById("login").disabled=true;
    	}else{
		   	document.getElementById("login").disabled=false;
		}
    }else{
   		alert('Data header masih ada yang salah !!!');
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
