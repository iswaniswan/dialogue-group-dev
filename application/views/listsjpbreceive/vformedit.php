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
                        <label class="col-md-12">Tanggal SJ</label>
                        <?php if($isi->d_sjpb){
			                if($isi->d_sjpb!=''){
			                	  $tmp=explode("-",$isi->d_sjpb);
			                	  $hr=$tmp[2];
			                	  $bl=$tmp[1];
			                	  $th=$tmp[0];
			                	  $isi->d_sjpb=$hr."-".$bl."-".$th;
			                }
		                }
                        if($isi->d_sjp){
		                	if($isi->d_sjp!=''){
		                	  $tmp=explode("-",$isi->d_sjp);
		                	  $hr=$tmp[2];
		                	  $bl=$tmp[1];
		                	  $th=$tmp[0];
		                	  $isi->d_sjp=$hr."-".$bl."-".$th;
		                	}
		                }
                        if($isi->d_sjpb_receive){
		                    if($isi->d_sjpb_receive!=''){
		                	  $tmp=explode("-",$isi->d_sjpb_receive);
		                	  $hr=$tmp[2];
		                	  $bl=$tmp[1];
		                	  $th=$tmp[0];
		                	  $isi->d_sjpb_receive=$hr."-".$bl."-".$th;
		                    }
		                }
		                    ?>
                            <div class="col-sm-3">
                                <input readonly id="dsj" name="dsj" class="form-control date" value="<?php echo $isi->d_sjpb; ?>">
                            </div>
                            <div class="col-sm-6">
                                <input id="inotapb" name="inotapb" class="form-control" value="<?php echo $isi->i_sjpb; ?>">
                            </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-6">
                            <input readonly id="eareaname" class="form-control" name="eareaname" value="<?php if($isi->e_area_name) echo $isi->e_area_name; ?>">
                            <input id="iarea" name="iarea" class="form-control" type="hidden" value="<?php if($iarea) echo $iarea; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">SJP</label>
                        <div class="col-sm-6">
                            <input readonly id="isjp" name="isjp" class="form-control" value="<?php if($isi->i_sjp) echo $isi->i_sjp; ?>">
                            <input id="dsjp" name="dsjp" type="hidden" class="form-control" value="<?php if($isi->d_sjp) echo $isi->d_sjp; ?>">
                        </div>
                    </div>
                    <?php 
                        if ($pst == '00'){ ?>
                        <div class="form-group row">
                            <div class="col-sm-offset-3 col-sm-5">
		                        <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales(parseFloat(document.getElementById('jml').value));"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                                &nbsp;&nbsp; 
                                <button <?php echo 'disabled';?> type="button" id="addrow" class="btn btn-info btn-rounded btn-sm" ><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah Item</button>
                                &nbsp;&nbsp;
                                <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                                &nbsp;&nbsp;
                                <?php if(($jmlitem!=0) || ($jmlitem!='')){ ?>
                                <label class="custom-control custom-checkbox">
                                    <input type="checkbox" id="checkAll" name="checkAll" class="custom-control-input" checked>
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">&nbsp;&nbsp;Check All</span>
                                </label>
                                <?php
                            } ?> 
                            </div>
                        </div>
                    <?php } 	
                    ?>
                        <div class="form-group row">
                            <div class="col-sm-offset-3 col-sm-5">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                            &nbsp;&nbsp;
                            <?php if(($jmlitem!=0) || ($jmlitem!='')){ ?>
                                <label class="custom-control custom-checkbox">
                                    <input type="checkbox" id="checkAll" name="checkAll" class="custom-control-input" checked>
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">&nbsp;&nbsp;Check All</span>
                                </label>
                                <?php
                            } ?> 
                            </div>
                        </div>
                </div>

                <div class="col-md-6">
                    <div id="pesan"></div>
                    <div class="form-group row">
                        <label class="col-md-12">Pelanggan</label>
                        <div class="col-sm-6">
                            <input readonly id="ecustomername" name="ecustomername" class="form-control" value="<?php if($isi->e_customer_name) echo $isi->e_customer_name; ?>">
                            <input id="icustomer" name="icustomer" type="hidden" class="form-control" value="<?php if($isi->i_customer) echo $isi->i_customer; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Tanggal Terima</label>
                        <div class="col-sm-3">
                            <input readonly id="dreceive" name="dreceive" class="form-control date" value="<?php echo $isi->d_sjpb_receive; ?>">
                            <input readonly type="hidden" id="tglreceive" name="tglreceive" class="form-control date" value="<?php echo $isi->d_sjpb_receive; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Nilai Kirim</label>
                        <div class="col-sm-6">
                            <input readonly type="text" id="vsjpb" name="vsjpb" class="form-control" value="<?php echo number_format($isi->v_sjpb); ?>">
                            <input readonly type="hidden" id="jml" name="jml" class="form-control" value="<?php echo $jmlitem; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Nilai Terima</label>
                        <div class="col-sm-6">
                            <input readonly type="text" id="vsjpbrec" name="vsjpbrec" class="form-control" value="<?php echo number_format($isi->v_sjpb_receive); ?>">
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
                                        <th style="text-align: center;">Jumlah Kirim</th>
                                        <th style="text-align: center;">Jumlah Terima</th>
                                        <th style="text-align: center;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php               
                                    if($detail){
                                         $i=1;
                                         foreach($detail as $row){
                                            if($row->n_receive=='') $row->n_receive==0;
                                            if($row->n_receive=='' || $row->n_receive==0){
                                              $jmlterima=$row->n_deliver;
                                            }else{
                                              $jmlterima=$row->n_receive;
                                            }
                                            $vtotal=$row->v_unit_price*$row->n_receive;
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
                                                    <input type="hidden" class="form-control" readonly id="emotifname<?= $i;?>" name="emotifname<?= $i;?>" value="<?= $row->e_product_motifname;?>">
                                                    <input type="hidden" class="form-control" readonly id="vunitprice<?= $i;?>" name="vunitprice<?= $i;?>" value="<?= $row->v_unit_price;?>">
                                                </td>
                                                <td>
                                                    <input class="form-control" id="ndeliver<?= $i;?>" name="ndeliver<?= $i;?>" value="<?= $row->n_deliver;?>">
                                                    <input type="hidden" class="form-control" id="vproductmill<?= $i;?>" name="vproductmill<?= $i;?>" value="<?= $row->v_unit_price;?>">
                                                </td>
                                                <td>
                                                    <input class="form-control" id="nreceive<?= $i;?>" name="nreceive<?= $i;?>" value="<?= $jmlterima;?>">
                                                    <input class="form-control" type="hidden" id="ntmp<?= $i;?>" name="ntmp<?= $i;?>" value="<?= $row->n_receive;?>">
                                                    <input class="form-control" type="hidden" id="vtotal<?= $i;?>" name="vtotal<?= $i;?>" value="<?= $vtotal;?>">
                                                </td>
                                                <td style="text-align: center;">
                                                    <input type='checkbox' name="chk<?=$i;?>" id="chk<?=$i;?>" value='on' checked onclick='ngetang()'>
                                                </td>
                                            </tr>
                                        <?php $i++;}
                                        
                                    }?>
                                    </div>
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
    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date');
    });

    $("#checkAll").click(function(){
        $('input:checkbox').not(this).prop('checked', this.checked);
        ngetang();
    });

    function ngetang(){
        var jml = parseFloat($('#jml').val());
        var tot = 0;
        for(brs=1;brs<=jml;brs++){    
            ord = $("#nreceive"+brs).val();
            hrg  = formatulang($("#vproductmill"+brs).val());
            qty  = formatulang(ord);
            vhrg = parseFloat(hrg)*parseFloat(qty);
            $("#vtotal"+brs).val(formatcemua(vhrg));
            if($("#chk"+brs).is(':checked')){
                tot+=parseFloat(formatulang($("#vtotal"+brs).val()));
            }
        }
        $("#vsjrec").val(formatcemua(tot));
    }

    var counter = $('#jml').val();
    $("#addrow").on("click", function () {
        counter++;
        if(counter<=20){
            $("#tabledata").attr("hidden", false);
            $('#jml').val(counter);
            var icustomer = $("#icustomer").val();
            count=$('#tabledata tr').length;
            var newRow = $("<tr>");
            var cols = "";
            cols+='<td><input readonly style="width:40px;" type="text" id="baris'+counter+'" name="baris'+counter+'"  class="form-control" value="'+counter+'"><input type="hidden" id="motif'+counter+'" name="motif'+counter+'" value=""><input type="hidden" id="ipricegroupco'+counter+'" name="ipricegroupco'+counter+'" value=""></td>';
            cols+='<td><select style="width:200px;" readonly type="text" id="iproduct'+counter+'" name="iproduct'+counter+'"  class="form-control select2" value="" onchange="getproduct('+counter+')"></select></td>';
            cols+='<td><input readonly style="width:300px;" readonly type="text" id="eproductname'+counter+'" name="eproductname'+counter+'"  class="form-control" value=""></td>';
            cols+='<td><input style="text-align:right; width:100px;" type="text" id="nquantity'+counter+'" name="nquantity'+counter+'"  class="form-control" value="" onkeyup="hitungnilai('+counter+');"></td>';
            cols+='<td><input readonly style="text-align:right; width:100px;" type="text" id="vunitprice'+counter+'" name="vunitprice'+counter+'"  class="form-control" value=""></td>';
            cols+='<td><input readonly style="text-align:right; width:100px;" type="text" id="total'+counter+'" name="total'+counter+'"  class="form-control" value=""></td>';
            cols+='<td><input style="text-align:right; width:100px;" type="text" id="eremark'+counter+'" name="eremark'+counter+'"  class="form-control" value=""></td>';
            cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger " value="Delete"></td>';
            newRow.append(cols);
            $("#tabledata").append(newRow);
            $('#iproduct'+counter).select2({
                placeholder: 'Cari Produk',
                allowClear: true,
                ajax: {
                    url: '<?= base_url($folder.'/cform/databarang/'); ?>'+icustomer,
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    },
                    cache: false
                }
            });
        }else{
            swal("Maksimal 20 Nota");
        }
    });

    $("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
        counter -= 1
        document.getElementById("jml").value = counter;

    });

    function getproduct(id){
        var iproduct = $('#iproduct'+id).val();
        var icustomer = $("#icustomer").val();
        $.ajax({
        type: "post",
        data: {
            'i_product': iproduct
        },
        url: '<?= base_url($folder.'/cform/getproduct/'); ?>'+icustomer,
        dataType: "json",
        success: function (data) {
            $('#iproduct'+id).val(data[0].i_product);
            $('#eproductname'+id).val(data[0].e_product_name);
            $('#vunitprice'+id).val(data[0].v_product_retail);
            $('#motif'+id).val(data[0].i_product_motif);
            $('#ipricegroupco'+id).val(data[0].i_price_groupco);
            hitungnilai();
        },
        error: function () {
            alert('Error :)');
        }
    });
    }

    function hitungnilai(){
        jml=document.getElementById("jml").value;
        bener=true;
          for(i=1;i<=jml;i++){
          qty=document.getElementById("nquantity"+i).value;
          if (isNaN(parseFloat(qty))){
            alert("Input harus numerik");
            bener=false;
            break;
          }
        }
        if(bener){
          tot=0;
          subtot=0;
             for(i=1;i<=jml;i++){
            qty=parseFloat(formatulang(document.getElementById("nquantity"+i).value));
            hrg=parseFloat(formatulang(document.getElementById("vunitprice"+i).value));
            subtot=qty*hrg;
               document.getElementById("total"+i).value=formatcemua(subtot);
            tot=tot+subtot;
          }
           document.getElementById("vnotapbgross").value=formatcemua(tot);
          dis=parseFloat(formatulang(document.getElementById("nnotapbdiscount").value));
          vdis=(tot*dis)/100;
            document.getElementById("vnotapbdiscount").value=formatcemua(vdis);
            document.getElementById("vnotapbnetto").value=formatcemua(tot-vdis);
        }
    }

    function diskonrupiah(){
        jml=document.getElementById("jml").value;
        bener=true;
          for(i=1;i<=jml;i++){
          qty=document.getElementById("nquantity"+i).value;
          if (isNaN(parseFloat(qty))){
            alert("Input harus numerik");
            bener=false;
            break;
          }
        }
        if(bener){
          tot=0;
          subtot=0;
             for(i=1;i<=jml;i++){
            qty=parseFloat(formatulang(document.getElementById("nquantity"+i).value));
            hrg=parseFloat(formatulang(document.getElementById("vunitprice"+i).value));
            subtot=qty*hrg;
               document.getElementById("total"+i).value=formatcemua(subtot);
            tot=tot+subtot;
          }
           document.getElementById("vnotapbgross").value=formatcemua(tot);
          vdis=parseFloat(formatulang(document.getElementById("vnotapbdiscount").value));
          dis=roundNumber((vdis*100)/tot,2);
            document.getElementById("nnotapbdiscount").value=dis;
            document.getElementById("vnotapbnetto").value=formatcemua(tot-vdis);
        }
    }

  function dipales(a){
    cek='false';
     if((document.getElementById("dnotapb").value!='') &&
         (document.getElementById("inotapb").value!='')) {
        if(a==0){
         alert('Isi data item minimal 1 !!!');
        }else{
            for(i=1;i<=a;i++){
            if((document.getElementById("iproduct"+i).value=='') ||
               (document.getElementById("eproductname"+i).value=='') ||
               (document.getElementById("nquantity"+i).value=='')){
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
      }else{
            document.getElementById("login").disabled=false;
       }
    }else{
         alert('Data header masih ada yang salah !!!');
    }
  }
</script>