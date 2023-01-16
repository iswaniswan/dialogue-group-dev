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
                        <label class="col-md-12">Tanggal</label>
                        <?php 
			                $tmp=explode("-",$isi->d_notapb);
			                $th=$tmp[0];
			                $bl=$tmp[1];
			                $hr=$tmp[2];
			                $thbl = $th.$bl;
			                $isi->d_notapb=$hr."-".$bl."-".$th;
		                ?>
                            <div class="col-sm-3">
                                <input readonly id="dnotapb" name="dnotapb" class="form-control date" value="<?php echo $isi->d_notapb; ?>">
                                <input type=hidden id="dnotapbx" name="dnotapbx" class="form-control" value="<?php echo $isi->d_notapb; ?>">
                            </div>
                            <div class="col-sm-6">
                                <input id="inotapb" name="inotapb" class="form-control" value="<?php if($inotapb) echo substr($inotapb,8,7); ?>" maxlength=7>
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
                        <label class="col-md-12">SPG</label>
                        <div class="col-sm-6">
                            <input readonly id="espgname" name="espgname" class="form-control" value="<?php if($isi->e_spg_name) echo $isi->e_spg_name; ?>">
                            <input id="ispg" name="ispg" type="hidden" class="form-control" value="<?php if($isi->i_spg) echo $isi->i_spg; ?>">
                        </div>
                    </div>
                    <?php 
                        if ($isi->f_notapb_cancel == 'f' && $isi->f_spb_rekap=='f' && ($isi->i_spb=='' || $isi->i_spb==null) && 
                         ($isi->i_cek=='' || $isi->i_cek==null)){ ?>
                        <div class="form-group row">
                            <div class="col-sm-offset-3 col-sm-5">
		                        <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales(parseFloat(document.getElementById('jml').value));"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                                &nbsp;&nbsp; 
                                <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm" ><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah Item</button>
                            </div>
                        </div>
                    <?php } 	
                    ?>
                </div>

                <div class="col-md-6">
                    <div id="pesan"></div>
                    <div class="form-group row">
                        <label class="col-md-12">Pelanggan</label>
                        <div class="col-sm-6">
                            <input readonly id="ecustomername" name="ecustomername" class="form-control" value="<?php if($isi->e_customer_name) echo $isi->e_customer_name; ?>">
                            <input id="icustomer" name="icustomer" type="hidden" class="form-control" value="<?php if($icustomer) echo $icustomer; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Potongan</label>
                        <div class="col-sm-2">
                            <input style="text-align:right;" id="nnotapbdiscount" name="nnotapbdiscount" class="form-control" value="<?php echo number_format($isi->n_notapb_discount); ?>" onkeyup="hitungnilai();">
                        </div>
                        <div class="col-sm-1"><b>%</b></div>
                        <div class="col-sm-4">
                            <input style="text-align:right;" readonly id="vnotapbdiscount" name="vnotapbdiscount" class="form-control" value="<?php echo number_format($isi->v_notapb_discount); ?>" onkeyup="diskonrupiah();">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Total</label>
                        <div class="col-sm-6">
                            <input type="hidden" id="vnotapbgross" name="vnotapbgross" class="form-control" value="<?php echo number_format($isi->v_notapb_gross); ?>">
                            <input readonly id="vnotapbnetto" name="vnotapbnetto" class="form-control" value="<?php echo number_format($isi->v_notapb_gross-$isi->v_notapb_discount); ?>">
                        </div>
                    </div>
                    </div>
                    <div class="panel-body table-responsive">
                    <table id="tabledata" class="display table" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Barang</th>
                                        <th>Nama Barang</th>
                                        <th>Jumlah</th>
                                        <th>Harga</th>
                                        <th>Total</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php               
                                    $i=0;
                                    if ($detail) {
                                        foreach($detail as $row){   
                                            $i++;
                                            $totall=$row->n_quantity*$row->v_unit_price;
                                            ?>
                                            <tr>
                                                <td style="text-align: center; width:50px">
                                                    <input style="font-size: 12px;" type="text" class="form-control" readonly id="baris<?= $i;?>" name="baris<?= $i;?>" value="<?= $i;?>">
                                                    <input style="font-size: 12px;" class="form-control" type="hidden" id="motif<?= $i;?>" name="motif<?= $i;?>" value="<?= $row->i_product_motif;?>">
                                                    <input style="font-size: 12px;" type="hidden" class="form-control" id="ipricegroupco<?= $i;?>" name="ipricegroupco<?= $i;?>" value="<?= $row->i_price_groupco; ?>">
                                                </td>
                                                <td style="text-align: center; width:100px">
                                                    <input style="font-size: 12px;" class="form-control" readonly id="iproduct<?= $i;?>" name="iproduct<?= $i;?>" value="<?= $row->i_product;?>">
                                                </td>
                                                <td style="text-align: center; width:200px">
                                                    <input style="font-size: 12px;" class="form-control" readonly id="eproductname<?= $i;?>" name="eproductname<?= $i;?>" value="<?= $row->e_product_name;?>">
                                                </td>
                                                <td style="text-align: center; width:50px">
                                                    <input style="font-size: 12px;" class="form-control" id="nquantity<?= $i;?>" name="nquantity<?= $i;?>" value="<?= $row->n_quantity;?>" onkeyup="hitungnilai(<?=$i;?>)">
                                                </td>
                                                <td style="text-align: center; width:100px">
                                                    <input style="font-size: 12px;" class="form-control" id="vunitprice<?= $i;?>" name="vunitprice<?= $i;?>" value="<?= number_format($row->v_unit_price);?>">
                                                </td>
                                                <td style="text-align: center; width:100px"> 
                                                    <input style="font-size: 12px;" class="form-control" readonly id="total<?= $i;?>" name="total<?= $i;?>" value="<?= number_format($totall);?>">
                                                </td>
                                                <td style="text-align: center; width:200px">
                                                    <input style="font-size: 12px;" class="form-control" id="eremark<?= $i;?>" name="eremark<?= $i;?>" value="<?= $row->e_remark;?>">
                                                </td>
                                            </tr>
                                        <?php }
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