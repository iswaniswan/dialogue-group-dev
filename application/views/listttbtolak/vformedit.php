<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <?php 
                    if($isi->d_receive1!=''){
                            $tmp=explode("-",$isi->d_receive1);
                            $th=$tmp[0];
                            $bl=$tmp[1];
                            $hr=substr($tmp[2],0,2);
                            $dreceive1=$hr."-".$bl."-".$th;
                    }else{
                        $dreceive1=null;
                    }

                    if($isi->d_ttb!=''){
                        $tmp2=explode("-",$isi->d_ttb);
                        $hr2=$tmp2[2];
                        $bl2=$tmp2[1];
                        $th2=$tmp2[0];
                        $dttb =$hr2."-".$bl2."-".$th2;
                    }

                    if($isi->d_nota!=''){
                        $tmp3=explode("-",$isi->d_nota);
                        $hr3=$tmp3[2];
                        $bl3=$tmp3[1];
                        $th3=$tmp3[0];
                        $isi->d_nota =$hr3."-".$bl3."-".$th3;
                    }
		        ?>
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Nomor TTB</label>
                        <label class="col-md-3">Tanggal TTB</label>
                        <label class="col-md-3">Tanggal Terima Sales</label>
                        <label class="col-md-3">Nilai Kotor</label>
                        <div class="col-sm-3">
                            <input id="ittb" name="ittb" class="form-control" required="" readonly value="<?= $ittb;?>">
                            <input type="hidden" id="ibbm" name="ibbm" value="<?= $isi->i_bbm; ?>">
                            <input type='hidden' id="dfrom" name="dfrom" value="<?= $dfrom; ?>">
                            <input type='hidden' id="dto" name="dto" value="<?= $dto; ?>">
                        </div>
                        <div class="col-sm-3">
                            <input id= "dttb" name="dttb" class="form-control date" required="" onchange="cektanggal();" readonly value="<?= $dttb;?>">
                            <input hidden id="bttb" name="bttb" value="<?= date('m', strtotime($isi->d_ttb)); ?>">
                            <input hidden id="tglttb" name="tglttb" value="<?= $isi->d_ttb;?>">
                            <input type="hidden" id="nttbyear" name="nttbyear" value="<?= $tahun;?>">
                        </div>
                        <div class="col-sm-3">
                            <input placeholder="Terima Sales" id="dreceive1" name="dreceive1" class="form-control date" required="" value="<?= $dreceive1; ?>">
                            <input type="hidden" id="tglreceive" name="tglreceive" value="<?= $dreceive1; ?>">
                        </div>
                        <div class="col-sm-3">
                            <input id="vttbgross" readonly name="vttbgross" class="form-control" value="<?= number_format($isi->v_ttb_gross); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">Pelanggan</label>
                        <label class="col-md-3">Discount 1</label>
                        <label class="col-md-3">Nilai Discount 1</label>
                        <div class="col-sm-6">
                            <input id="ecustomer" readonly name="ecustomer" class="form-control" value="<?= $isi->e_customer_name; ?>" readonly>
                            <input type="hidden" id="icustomer" readonly name="icustomer" class="form-control" value="<?= $isi->i_customer; ?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <input class="form-control" id="nttbdiscount1" name="nttbdiscount1" value="<?= $isi->n_ttb_discount1; ?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <input class="form-control" readonly id="vttbdiscount1" name="vttbdiscount1" value="<?= number_format($isi->v_ttb_discount1); ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">NPWP</label>
                        <label class="col-md-3">Tahun TTB</label>
                        <label class="col-md-3">Discount 2</label>
                        <label class="col-md-3">Nilai Discount 2</label>
                        <div class="col-sm-3">
                            <input class="form-control" id="ecustomerpkpnpwp" name="ecustomerpkpnpwp" value="<?= $isi->e_customer_pkpnpwp; ?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <input class="form-control" readonly="" type="text" id="nttbyear" name="nttbyear" value="<?= $tahun; ?>">
                        </div>
                        <div class="col-sm-3">
                            <input class="form-control" id="nttbdiscount2" name="nttbdiscount2" value="<?= $isi->n_ttb_discount2; ?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <input class="form-control" readonly id="vttbdiscount2" name="vttbdiscount2" value="<?= number_format($isi->v_ttb_discount2); ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Area</label>                        
                        <label class="col-md-3">Keterangan</label>
                        <label class="col-md-3">Discount 3</label>
                        <label class="col-md-3">Nilai Discount 3</label>
                        <div class="col-sm-3">
                            <input readonly id="eareaname" name="eareaname" class="form-control" value="<?= $isi->e_area_name; ?>">
                            <input id="iarea" name="iarea" type="hidden" value="<?= $isi->i_area; ?>">
                        </div>
                        <div class="col-sm-3">
                            <input id="fttbplusppn" name="fttbplusppn" type="hidden" value="<?= $isi->f_ttb_plusppn; ?>">
                            <input id="fttbplusdiscount" name="fttbplusdiscount" type="hidden" value="<?= $isi->f_ttb_plusdiscount; ?>">
                            <input id="fttbpkp" name="fttbpkp" type="hidden" value="">
                            <input class="form-control" id="eremark" name="eremark" value="">
                        </div>
                        <div class="col-sm-3">
                            <input class="form-control" id="nttbdiscount3" name="nttbdiscount3" value="<?= $isi->n_ttb_discount3; ?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <input class="form-control" readonly id="vttbdiscount3" name="vttbdiscount3" value="<?= number_format($isi->v_ttb_discount3); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2">Salesman</label>
                        <!-- <label class="col-md-3">Alasan Retur</label> -->
                        <label class="col-md-2">Nota</label>
                        <label class="col-md-2">Tgl Nota</label>
                        <label class="col-md-3">Discount Total</label>
                        <label class="col-md-3">Nilai Bersih</label>
                        <div class="col-sm-2">
                            <input class="form-control" readonly id="esalesman" name="esalesman" value="<?= $isi->e_salesman_name; ?>">
                            <input type="hidden" class="form-control" readonly id="isalesman" name="isalesman" value="<?= $isi->i_salesman; ?>">
                            <input type="hidden" class="form-control" id="isalesmanx" name="isalesmanx" value="<?= $isi->i_salesman; ?>">
                        </div>
                        <!-- <div class="col-sm-3">
                            <select class="form-control select2" id="ialasanretur" name="ialasanretur">
                                <option value="<#?= $isi->i_alasan_retur; ?>"><#?= $isi->e_alasan_returname; ?></option>
                            </select>
                        </div> -->
                        <div class="col-sm-2">
                            <input class="form-control" readonly id="inota" name="inota" value="<?= $isi->i_nota; ?>">
                            
                        </div>
                        <div class="col-sm-2">
                            <input class="form-control" readonly id="dnota" name="dnota" value="<?= $isi->d_nota; ?>">
                        </div>
                        <div class="col-sm-3">
                            <input class="form-control" id="vttbdiscounttotal" name="vttbdiscounttotal" value="<?= $isi->v_ttb_discounttotal; ?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <input class="form-control" readonly id="vttbnetto" name="vttbnetto" value="<?= number_format($isi->v_ttb_netto); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-8">
                            <?php if(check_role($i_menu, 3) && ($isi->f_ttb_cancel == 'f')){?>
                                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="dipales();"> <i class="fa fa-save"></i>&nbsp;&nbsp;Update</button>
                                &nbsp;&nbsp;
                            <?php } ?>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/view/<?= $xarea."/".$dfrom."/".$dto;?>","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table id="tabledata" class="table color-table inverse-table table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 4%;">No</th>
                                    <th class="text-center" style="width: 10%;">Kode</th>
                                    <th class="text-center" style="width: 30%;">Nama Barang</th>
                                    <th class="text-center">Motif</th>
                                    <th class="text-center">Harga</th>
                                    <th class="text-center">Qty Nota</th>
                                    <th class="text-center">Qty Tolak</th>
                                    <th class="text-center">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php               
                                $i=0;
                                if($detail){
                                    foreach($detail as $row){ 
                                        $i++;
                                        /* $harga      = number_format($row->v_unit_price);
                                        $nquantity  = number_format($row->n_quantity,0);
                                        $nqtyrec    = number_format($row->n_quantity_receive,0); */

                                        $harga		= number_format($row->v_unit_price);
                                        $ndeliv		= number_format($row->n_deliver,0);
                                        if($row->n_quantity=='')$row->n_quantity='0';
                                        $nquantity	= number_format($row->n_quantity,0);
                                        ?>
                                        <tr>
                                            <td class="text-center">
                                                <?= $i;?>
                                                <input type="hidden" class="form-control" readonly type="text" id="baris<?= $i;?>" name="baris<?= $i;?>" value="<?= $i;?>">
                                                <input class="form-control" type="hidden" id="motif<?= $i;?>" name="motif<?= $i;?>" value="<?= $row->i_product_motif;?>">
                                            </td>
                                            <td>
                                                <input class="form-control" readonly type="text" id="iproduct<?= $i;?>" name="iproduct<?= $i;?>" value="<?= $row->i_product;?>">
                                            </td>
                                            <td>
                                                <input class="form-control" readonly type="text" id="eproductname<?= $i;?>" name="eproductname<?= $i;?>" value="<?= $row->e_product_name;?>">
                                            </td>
                                            <td>
                                                <input class="form-control" readonly type="text" id="emotifname<?= $i;?>" name="emotifname<?= $i;?>" value="<?= $row->e_product_motifname;?>">
                                            </td>
                                            <td>
                                                <input readonly ass="form-control text-right" onkeypress="return hanyaAngka(event);" type="text" id="vproductretail<?= $i;?>" name="vproductretail<?= $i;?>" value="<?= $harga;?>">
                                            </td>
                                            <td>
                                                <input class="form-control text-right" readonly type="text" id="ndeliver<?= $i;?>" name="ndeliver<?= $i;?>" value="<?= $nquantity;?>">
                                            </td>
                                            <td>
                                                <input class="form-control text-right" onkeypress="return hanyaAngka(event);" text-right type="text" id="nquantity<?= $i;?>" name="nquantity<?= $i;?>" value="<?= $nquantity;?>" onkeyup="cekval(this.value);">
                                                <input class="form-control text-right" text-right type="hidden" id="nasal<?= $i;?>" name="nasal<?= $i;?>" value="<?= $nquantity;?>" readonly>
                                            </td>
                                            <td>
                                                <input class="form-control" type="text" id="eremark<?= $i;?>" name="eremark<?= $i;?>" value="<?= $row->e_ttb_remark;?>">
                                            </td>
                                        </tr>
                                    <?php }
                                } ?>
                                <input type="hidden" name="jml" id="jml" value="<?= $i;?>">
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
</div>
<script>
    function cekval(input){
        var jml   = parseFloat(document.getElementById("jml").value); 
        var num = input.replace(/\,/g,'');
        if(!isNaN(num)){
            for(j=1;j<=jml;j++){
                qty		= parseFloat(document.getElementById("nquantity"+j).value);
                del		= parseFloat(document.getElementById("ndeliver"+j).value);
                input	= document.getElementById("nquantity"+j);

                if(qty>del){
                    alert('Jumlah tolakan tidak boleh lebih dari jumlah Nota !!!');
                    input.value='0';
                }

            var jml 	= parseFloat(document.getElementById("jml").value); 
			var totdis 	= 0;
			var totnil	= 0;
			var hrg		= 0;
			var ndis1	= parseFloat(formatulang(document.getElementById("nttbdiscount1").value));
			var ndis2	= parseFloat(formatulang(document.getElementById("nttbdiscount2").value));
			var ndis3	= parseFloat(formatulang(document.getElementById("nttbdiscount3").value));
			var vdis1	= 0;
			var vdis2	= 0;
			var vdis3	= 0;
			for(i=1;i<=jml;i++){
			  	var hrgtmp 	= parseFloat(formatulang(document.getElementById("vproductretail"+i).value))*parseFloat(formatulang(document.getElementById("nquantity"+i).value));
				hrg			= hrg+hrgtmp;
			}
			vdis1=vdis1+((hrg*ndis1)/100);
			vdis2=vdis2+(((hrg-vdis1)*ndis2)/100);
			vdis3=vdis3+(((hrg-(vdis1+vdis2))*ndis3)/100);
			vdistot	= vdis1+vdis2+vdis3;
			vhrgreal= hrg-vdistot;
			document.getElementById("vttbdiscounttotal").value=formatcemua(vdistot);
			document.getElementById("vttbnetto").value=formatcemua(vhrgreal);
			document.getElementById("vttbgross").value=formatcemua(vhrgreal-vdistot);
          }
      }else{ 
          alert('input harus numerik !!!');
          input = input.substring(0,input.length-1);
      }
  }
  
function detail(id){
    ada=false;
    var a = $('#iproduct'+id).val();
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
        $.ajax({
            type: "post",
            data: {
                'iproduct'  : iproduct,
                'icustomer' : $('#icustomer').val(),
                'ipricegroup' : $('#ipricegroup').val(),
            },
            url: '<?= base_url($folder.'/cform/detailproduct'); ?>',
            dataType: "json",
            success: function (data) {
                $('#eproductname'+id).val(data[0].nama);
                $('#vunitprice'+id).val(formatcemua(data[0].harga));
                $('#emotifname'+id).val(data[0].namamotif);
                $('#motif'+id).val(data[0].motif);
                $('#nquantity'+id).focus();
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

function dipales(){
    cek='false';
	if((document.getElementById("dttb").value!='') &&
	  (document.getElementById("ittb").value!='')) {
	  var a=parseFloat(document.getElementById("jml").value);
	  for(i=1;i<=a;i++){
		if(document.getElementById("nquantity"+i).value!='0'){
		  cek='true';
		  break;
		}else{
		  cek='false';
		} 
	  }
	  if(cek=='true'){
	    document.getElementById("login").disabled=true;
		sendRequest(); 
		return false;
		document.ttbtolakform.submit();
	  }else{
		alert('Isi jumlah detail tolakan minimal 1 item !!!');
        return false;
	    //document.getElementById("login").disabled=false;
	  }
	}else{
	  alert('Data header masih ada yang salah !!!');
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

/* $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date', 3, 0);
    }); */

function cektanggal(){
    dspb = $('#dttb').val();
    bspb = $('#bttb').val();
    dtmp = dspb.split('-');
    per  = dtmp[2]+dtmp[1]+dtmp[0];
    bln  = dtmp[1];
    if( (bspb!='') && (dspb!='') ){
        if(bspb != bln){
            swal("Tanggal TTB tidak boleh dalam bulan yang berbeda !!!");
            $("#dttb").val('');
        }
    }

    dsj     = $('#dttb').val();
    dsjrec  = $('#dreceive1').val();
    sj      = dsj.split('-');
    tglsj   = sj[2]+sj[1]+sj[0];
    rc      = dsjrec.split('-');
    tglrec  = rc[2]+rc[1]+rc[0];
    if (tglrec<tglsj) {
        swal("Tanggal TTB Receive tidak boleh lebih kecil dari tanggal SJR !!!");
        $("#dreceive1").val('');
    }
}

    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date', 3, 0);
    });$(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date', 3, 0);
    });

/* function cecok(dreceive) {
        var dsj  = $('#dttb').val();
        dtmprec  = dreceive.split('-');
        thnrec   = dtmprec[2];
        blnrec   = dtmprec[1];
        hrrec    = dtmprec[0];
        dtmp     = dsj.split('-');
        thnsj    = dtmp[2];
        blnsj    = dtmp[1];
        hrsj     = dtmp[0];
        tglreceive = thnrec+blnrec+hrrec;
        tglsj      = thnsj+blnsj+hrsj;
        if (tglreceive<tglsj) {
            swal('Tidak boleh lebih kecil dari tanggal DKB!');
            $('#dreceive').val('');
            return false;
        }
    } */

function testes(){
    var a=$("#dttb").val();
    var b=$("#dreceive1").val();
    if(a!=''){
        a=a.split('-');
        a1=a[0];
        a2=a[1];
        a3=a[2];
        a=a3+a2+a1;
        a=parseFloat(a);
        if(b!=''){
            b=b.split('-');
            b1=b[0];
            b2=b[1];
            b3=b[2];
            b=b3+b2+b1;
            b=parseFloat(b);
            if(b<a){
                swal("Tanggal terima sales harus lebih besar dari tanggal TTB !!!!!");
                $("#dreceive1").val('');
            }
        }
    }
}
</script>