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
                <div class="col-md-6">
                    <div id="pesan"></div>
                    <?php if($data){
                    ?>              
                    <div class="form-group">
                        <label class="col-md-12">No OP</label>
                        <div class="col-sm-6">
                            <input type="text" name="iop" class="form-control" value="<?= $data->i_op;?>" readonly>
                            <input type='hidden' id="dop" name="dop" class="form-control" value="<?= $data->d_op;?>"></td>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">No DO</label>
                        <div class="col-sm-6">
                            <input id="ido" name="ido" type="text" maxlength="6" class="form-control" onkeyup="gede(this);" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Pemasok</label>
                        <div class="col-sm-6">
                            <input readonly id="esuppliername" class="form-control" name="esuppliername" value="<?= $data->e_supplier_name;?>"readonly>
                            <input id="isupplier" name="isupplier" class="form-control" type="hidden" value="<?= $data->i_supplier;?>">
                            <input id="nsupplierdiscount" name="nsupplierdiscount" class="form-control" type="hidden" value="<?=$data->n_supplier_discount; ?>">
                            <input id="nsupplierdiscount2" name="nsupplierdiscount2" class="form-control" type="hidden" value="<?=$data->n_supplier_discount2; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-info btn-rounded btn-sm" onclick="dipales(parseFloat(document.getElementById('jml').value));"> <i
                                    class="fa fa-plus"></i>&nbsp;&nbsp;Simpan</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div id="pesan"></div>
                    <div class="form-group">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-6">
                            <input readonly id="eareaname" name="eareaname" class="form-control" value="<?= $data->e_area_name;?>" readonly>
                            <input id="iarea" name="iarea" type="hidden" class="form-control" value="<?= $data->i_area;?>">
                        </div>
                    </div>
                    <div class="form-group">
                            <label class="col-md-12">Tanggal DO</label>
                            <div class="col-sm-6">
                                <input readonly id="ddo" name="ddo" class="form-control date" value="<?php echo $tgl; ?>">
                            </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nilai Kotor</label>
                        <div class="col-sm-6">
                            <input readonly id="vdogross" class="form-control" name="vdogross" value="0">
                        </div>
                    </div>
                    </div>
                    <div class="col-md-12">
                    <?php
                    }else{                           
                            $read = "disabled";
                            echo "<table class=\"table table-striped bottom\" style=\"width:100%;\"><tr><td colspan=\"6\" style=\"text-align:center;\">Maaf Tidak Ada Data!</td></tr></table>";
                    }?> 
                    </div>                    
                            <div class="panel-body table-responsive">
                                <table id="tabledata" class="display table" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode Barang</th>
                                            <th>Nama Barang</th>
                                            <th>Keterangan</th>
                                            <th>Harga</th>
                                            <th>Jumlah OP</th>
                                            <th>Jumlah Kirim</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <?php $i=0; ?>
                                    <?php if($data1!='') {?>
                                    <tbody>
                                    <? 
                                        $i=0;
                                        foreach($data1 as $row){
                                        $i++;
                                        $total=0;
                                        $jumdo=0;
                                    ?>
                                    <tr>
                                        <td class="col-sm-1"> 
                                            <input style="width:40px;" readonly type="text" class="form-control" id="baris<?=$i;?>" name="baris<?=$i;?>" value="<?=$i;?>">
                                            <input type="hidden"  class="form-control" id="motif<?=$i; ?>" name="motif<?=$i; ?>" value="<?php echo $row->i_product_motif; ?>">
                                        </td>
                                        <td class="col-sm-1"> 
                                            <input style="width:100px;" readonly type="text" class="form-control" id="iproduct<?=$i; ?>" name="iproduct<?=$i; ?>" value="<?php echo $row->i_product; ?>">
                                        </td>
                                        <td class="col-sm-1"> 
                                            <input style="width:272px;" readonly type="text" class="form-control" id="eproductname<?=$i; ?>" name="eproductname<?=$i; ?>" value="<?php echo $row->e_product_name; ?>">
                                        </td>
                                        <td class="col-sm-1"> 
                                            <input style="width:93px;"  type="text" id="eremark<?=$i; ?>" name="eremark<?=$i; ?>" value="">
                                            <input readonly style="width:93px;"  type="hidden" class="form-control" id="emotifname<?=$i; ?>" name="emotifname<?=$i; ?>" value="<?php echo $row->e_product_motifname; ?>">
                                        </td>
                                        <td class="col-sm-1"> 
                                            <input readonly style="width:85px;"  type="text" class="form-control" id="vproductmill<?=$i; ?>" name="vproductmill<?=$i; ?>" value="<?php echo $row->v_product_mill; ?>">
                                        </td>
                                        <td class="col-sm-1"> 
                                            <input style="width:85px;" type="text" class="form-control" readonly id="ndeliverhidden<?=$i; ?>" name="ndeliverhidden<?=$i; ?>" value="<?php echo $row->n_order; ?>">
                                            <input style="width:85px;" type="hidden" class="form-control" readonly id="ntmp<?=$i; ?>" name="ntmp<?=$i; ?>" value="<?php echo $jumdo; ?>">
                                            <input style="width:85px;" type="hidden" class="form-control" readonly id="sisa<?=$i; ?>" name="sisa<?=$i; ?>" value="<?php echo $row->sisa; ?>">
                                        </td>
                                        <td class="col-sm-1"> 
                                            <input style="width:85px;" type="text" class="form-control" id="ndeliver<?=$i; ?>" name="ndeliver<?=$i; ?>" value="<?php echo $jumdo; ?>" onkeyup="pembandingnilai(<?php if(isset($i)) echo $i; ?>); sisa(<?php if(isset($i)) echo $i; ?>); hitungnilai(this.value); ">
                                            <input type="hidden" class="form-control" id="ndeliverhidden<?=$i; ?>" name="ndeliverhidden<?=$i; ?>" value="<?php echo $row->n_order; ?>">
                                        </td>
                                        <td class="col-sm-1"> 
                                            <input readonly style="width:88px;" type="text" class="form-control" id="vtotal<?=$i; ?>" name="vtotal<?=$i; ?>" value="0">
                                        </td>
                                    </tr>
                                        <?}?>
                                    </tbody>
                                </table>
                                <?}?>
                            </div>
                            <div id="pesan"></div>
                            <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">
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

    function get(id) {
        /*alert(iarea);*/
        $.ajax({
            type: "post",
            data: {
                'i_op': id
            },
            url: '<?= base_url($folder.'/cform/getop'); ?>',
            dataType: "json",
            success: function (data) {
                $('#isupplier').val(data[0].i_supplier);
                $('#esuppliername').val(data[0].e_supplier_name);
                $('#iarea').val(data[0].i_area);
                $('#eareaname').val(data[0].e_area_name);
                $('#nsupplierdiscount').val(data[0].n_supplier_discount);
                $('#nsupplierdiscount2').val(data[0].n_supplier_discount2);
            },
            error: function () {
                alert('Error :)');
            }
        });
    }

    function pembandingnilai(a){
     var n_deliver   = document.getElementById('ndeliver'+a).value;
     var deliverasal = document.getElementById('ndeliverhidden'+a).value;

     if(parseFloat(n_deliver) > parseFloat(deliverasal)) {
        alert('Jml kirim ( '+n_deliver+' item ) tdk dpt melebihi Order ( '+deliverasal+' item )');
        document.getElementById('ndeliver'+a).value   = deliverasal;
        document.getElementById('ndeliver'+a).focus();
        return false;
     }
  }

  function hitungnilai(isi,jml){
   if (isNaN(parseFloat(isi))){
      alert("Input harus numerik");
   }else{
      var vtot=0;
      var dis1=parseFloat(document.getElementById("nsupplierdiscount").value);
      var dis2=parseFloat(document.getElementById("nsupplierdiscount2").value);
      jml=document.getElementById("jml").value;
      for(i=1;i<=jml;i++){
         var vhrg=formatulang(document.getElementById("vproductmill"+i).value);
         var nqty=formatulang(document.getElementById("ndeliver"+i).value);
         vhrg=parseFloat(vhrg)*parseFloat(nqty);
         vtot=vtot+vhrg;
         document.getElementById("vtotal"+i).value=formatcemua(vhrg);
      }
      var xx=(parseFloat(vtot)*dis1)/100;
      var tmp=parseFloat(vtot-xx);
      var yy=(tmp*dis2)/100;
      vtot=vtot-(xx+yy);
      document.getElementById("vdogross").value=formatcemua(vtot);
      document.getElementById("ntmp").value=nqty;
   }
  }

  function sisa(a){
    var sisa = parseFloat(document.getElementById('sisa'+a).value);
    var ndeliver = parseFloat(document.getElementById('ndeliver'+a).value);

    if(ndeliver > sisa){
      alert('Sisa Jumlah Kirim Adalah : '+sisa);
      document.getElementById('ndeliver'+a).value   = 0;
       document.getElementById('ndeliver'+a).focus();
    }
  }

  function dipales(a){
    cek='false';
    if((document.getElementById("ddo").value!='') &&
      (document.getElementById("isupplier").value!='') &&
      (document.getElementById("ido").value!='') &&
      (document.getElementById("iop").value!='')) {
      if(a==0){
         alert('Isi data item minimal 1 !!!');
      }else{
            for(i=1;i<=a;i++){
            if((document.getElementById("iproduct"+i).value=='') ||
               (document.getElementById("eproductname"+i).value=='') ||
               (document.getElementById("ndeliver"+i).value=='')){
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
         document.getElementById("submit").disabled=true;
      }else{
            document.getElementById("login").disabled=false;
         document.getElementById("cmdtambahitem").disabled=false;
      }
    }else{
         alert('Data header masih ada yang salah !!!');
    }
  }

</script>