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
                        <label class="col-md-12">Tanggal SJ / No SJ</label>
                        <? if($isi->d_sjr){
			                    if($isi->d_sjr!=''){
			                    	$tmp=explode("-",$isi->d_sjr);
			                    	$hr=$tmp[2];
			                    	$bl=$tmp[1];
			                    	$th=$tmp[0];
			                    	$isi->d_sjr=$hr."-".$bl."-".$th;
			                    }
		                    }
                            if($isi->d_sjr_receive){
		                        if($isi->d_sjr_receive!=''){
		                            $tmp=explode("-",$isi->d_sjr_receive);
		                            $hr=$tmp[2];
		                            $bl=$tmp[1];
		                            $th=$tmp[0];
		                            $isi->d_sjr_receive=$hr."-".$bl."-".$th;
		                        }
		                    }
		                ?>
                        <div class="col-sm-3">
                           <input readonly id="dsj" name="dsj" class="form-control" value="<? if($isi->d_sjr) echo $isi->d_sjr; ?>">
                        </div>
                        <div class="col-sm-6">
		                   <input readonly id="isj" name="isj" class="form-control" value="<? if($isi->i_sjr) echo $isi->i_sjr; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-6">
                            <input readonly id="eareaname" name="eareaname" class="form-control" value="<? if($isi->e_area_name) echo $isi->e_area_name; ?>">
		                    <input id="iarea" name="iarea" type="hidden" class="form-control"  value="<? if($isi->i_area) echo $isi->i_area; ?>">
		                    <input id="istore" name="istore" type="hidden" class="form-control" value="<? if($isi->i_store) echo $isi->i_store; ?>">
                        </div>
                    </div> 
                    <div class="form-group">
                        <label class="col-md-12">SJ Lama</label>
                        <div class="col-sm-6">
                            <input readonly id="isjold" name="isjold" type="text" class="form-control" value="<? echo $isi->i_sjr_old; ?>">
                        </div>
                    </div> 
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales(parseFloat(document.getElementById('jml').value));"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                            &nbsp;&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                            &nbsp;&nbsp;
                            <?
                            if( ($jmlitem!=0) || ($jmlitem!='') ){?>
                                <label class="custom-control custom-checkbox">
                                <input type="checkbox" id="checkAll" name="checkAll" class="custom-control-input">
                                <span class="custom-control-indicator"></span>
                                <span class="custom-control-description">&nbsp;&nbsp;Check All</span>
                                </label>
                            <?}?> 
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-12">Tanggal Terima</label>
                        <div class="col-sm-6">
                            <input readonly id="dreceive" name="dreceive" type="text" class="form-control date" value="<? echo $isi->d_sjr_receive; ?>">
                            <input type="hidden" id="tglreceive" name="tglreceive" type="text" value="<? echo $isi->d_sjr_receive; ?>">    
                        </div>
                    </div> 
                    <div class="form-group">
                        <label class="col-md-12">Nilai Kirim</label>
                        <div class="col-sm-6"> 
                            <input readonly style="text-align:right;"  id="vsj" name="vsj" class="form-control" value="<? echo number_format($isi->v_sjr); ?>">
                            <input type="hidden" name="jml" id="jml" class="form-control" value="<? if($jmlitem) echo $jmlitem; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nilai Terima</label>
                        <div class="col-sm-6">
                        <input readonly style="text-align:right;" readonly id="vsjrec" name="vsjrec" class="form-control" value="0">
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
                                    <th style="text-align: center;">Keterangan</th>
                                    <th style="text-align: center;">Jumlah Retur</th>
                                    <th style="text-align: center;">Jumlah Terima</th>
                                    <th style="text-align: center;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($detail) {
                                    $i = 0;
                                    foreach ($detail as $row) { 
                                        $vtotal=$row->v_unit_price*$row->n_quantity_receive;
                                        $i++; 
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
                                                <input class="form-control" readonly type="hidden" id="emotifname<?=$i;?>" name="emotifname<?=$i;?>" value="<?= $row->e_product_motifname; ?>">
                                            </td>
                                            <td>
                                                <input class="form-control" type="text" id="eremark<?=$i;?>" name="eremark<?=$i;?>" value="<?= $row->e_remark; ?>">
                                                <input class="form-control" style="text-align:right;" readonly type="hidden" id="vproductmill<?=$i;?>" name="vproductmill<?=$i;?>" value="<?= $row->v_unit_price; ?>">
                                            </td>
                                            <td>
                                                <input class="form-control form-control-success" readonly style="text-align:right;" type="text" id="nretur<?=$i;?>" name="nretur<?=$i;?>" value="<?= $row->n_quantity_retur; ?>">
                                            </td>
                                            <td>
                                                <input class="form-control" style="text-align:right;" type="text" id="nreceive<?=$i;?>" name="nreceive<?=$i;?>" value="<?= $row->n_quantity_receive; ?>" onkeyup="hitungnilai(<?=$i;?>);">
                                                <input class="form-control" style="text-align:right;" readonly type="hidden" id="ntmp<?=$i;?>" name="ntmp<?=$i;?>" value="<?= $row->n_quantity_receive; ?>">
                                                <input class="form-control" style="text-align:right;" readonly type="hidden" id="vtotal<?=$i;?>" name="vtotal<?=$i;?>" value="<?= $vtotal; ?>">
                                            </td>
                                            <td>
                                                <input type='checkbox' name="chk<?=$i;?>" id="chk<?=$i;?>" value='on' checked onclick='pilihan(this.value,".$i.")'>
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
    });

    $("#checkAll").click(function(){
        $('input:checkbox').not(this).prop('checked', this.checked);
    });

    function dipales(a){
  	 cek='false';
  	 if((document.getElementById("dreceive").value!='') && (document.getElementById("iarea").value!='')) {
  	 	if(a==0){
  	 		alert('Isi data item minimal 1 !!!');
  	 	}else{
   			for(i=1;i<=a;i++){
				if((document.getElementById("iproduct"+i).value=='') || (document.getElementById("eproductname"+i).value=='') || (document.getElementById("nreceive"+i).value=='')){
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
            document.getElementById("cmdtambahitem").disabled=true;
 	  		document.getElementById("cmdpilihsemua").disabled=true;
 	  		document.getElementById("cmdtidakpilihsemua").disabled=true;
    	  }else{
	     	document.getElementById("login").disabled=false;
		  }
    }else{
   		alert('Data header masih ada yang salah !!!');
    }
  }

  function hitungnilai(brs){
      var tot=0;
      var ord = $("#nreceive"+brs).val();
	  if (isNaN(parseFloat(ord))){
		alert("Input harus numerik");
	  }else{
        var hrg = formatulang($("#vproductmill"+brs).val());
        qty=formatulang(ord);
        vhrg=parseFloat(hrg)*parseFloat(qty);
        $('#vtotal'+brs).val(formatcemua(vhrg));

        jml=parseFloat(document.getElementById("jml").value);
        for(i=1;i<=jml;i++){
          if(document.getElementById("chk"+i).value=='on'){
            tot+=parseFloat(formatulang($("#vtotal"+i).val()));
          }
        }
        $('#vsjrec').val(formatcemua(tot));
	  }
  }

    function pilihan(a,b){
	  if(a==''){
		  document.getElementById("chk"+b).value='on';
	  }else{
		  document.getElementById("chk"+b).value='';
	  }
     hitungnilai(b);
    }

	function pilihsemua(){
		var jml=parseFloat(document.getElementById("jml").value);
		for(i=1;i<=jml;i++){
			document.getElementById("chk"+i).checked=true;
			document.getElementById("chk"+i).value='on';
		}
		for(i=1;i<=jml;i++){
            hitungnilai(i);
		}
	}
	function tidakpilihsemua(){
		var jml=parseFloat(document.getElementById("jml").value);
		for(i=1;i<=jml;i++){
			document.getElementById("chk"+i).checked=false;
			document.getElementById("chk"+i).value='';
		}
		for(i=1;i<=jml;i++){
            hitungnilai(i);
		}
	}

  function cektanggal(){
    dsj=document.getElementById('dsj').value;
    dsjrec=document.getElementById('dreceive').value;
    dtmpsj=dsj.split('-');
    dtmpsjrec=dsjrec.split('-');
    persj=dtmpsj[2]+dtmpsj[1]+dtmpsj[0];
    persjrec=dtmpsjrec[2]+dtmpsjrec[1]+dtmpsjrecj[0];
    alert(persj);
    alert(persjrec);
    if( (persj!='') && (persjrec!='') ){
      if(compareDates(document.getElementById('dsj').value,document.getElementById('dreceive').value)==-1)
      {
        alert("Tanggal SJR Receive tidak boleh lebih kecil dari tanggal SJR !!!");
        document.getElementById("dreceive").value='';
      }
    }else if( persjr>persj ){
        alert("Tanggal SJR Receive tidak boleh lebih kecil dari tanggal SJR !!!");
        document.getElementById("dreceive").value='';
    }
  }
</script>