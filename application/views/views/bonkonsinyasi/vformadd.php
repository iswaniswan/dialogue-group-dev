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
                    <div class="form-group row">
                        <label class="col-md-12">Tanggal</label>
                            <div class="col-sm-3">
                                <input readonly id="dnotapb" name="dnotapb" class="form-control" value="<?php echo $tgl; ?>" onclick="showCalendar('',this,this,'','dnotapb',0,20,1)">
                                <input type=hidden id="dnotapbx" name="dnotapbx" class="form-control" value="<?php echo $tgl; ?>">
                            </div>
                            <div class="col-sm-4">
                                <input id="inotapb" name="inotapb" class="form-control" value="<?php if($inotapb) echo $inotapb; ?>" maxlength=7>
                            </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-6">
                            <select id="iarea" class="form-control select2" name="iarea" onchange="get(this.value);">
                                <option value="">-- Pilih Area --</option>
                                <?php foreach ($area as $iarea):?>
                                <option value="<?php echo $iarea->i_area;?>">
                                    <?= $iarea->i_area." - ".$iarea->e_area_name;?></option>
                                <?php endforeach; ?>
                            </select>
                            <input id="eareaname" name="eareaname" class="form-control" type="hidden" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">SPG</label>
                        <div class="col-sm-6">
                            <select id="ispg" name="ispg" class="form-control select2" onchange="getcust(this.value);"></select>
                            <input id="espgname" name="espgname" type="hidden" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales(parseFloat(document.getElementById('jml').value));"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                            &nbsp;&nbsp;
                            <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm" ><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah</button>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div id="pesan"></div>
                    <div class="form-group row">
                        <label class="col-md-12">Pelanggan</label>
                        <div class="col-sm-6">
                            <input readonly id="ecustomername" name="ecustomername" class="form-control" value="">
                            <input id="icustomer" name="icustomer" type="hidden" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Potongan</label>
                        <div class="col-sm-2">
                            <input style="text-align:right;" id="nnotapbdiscount" name="nnotapbdiscount" class="form-control" value="0" onkeyup="hitungnilai();">
                        </div>
                        <div><b>%</b></div>
                        <div class="col-sm-3">
                            <input style="text-align:right;" readonly id="vnotapbdiscount" name="vnotapbdiscount" class="form-control" value="0" onkeyup="diskonrupiah();">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Total</label>
                        <div class="col-sm-6">
                            <input type="hidden" id="vnotapbgross" name="vnotapbgross" class="form-control" value="0">
                            <input readonly id="vnotapbnetto" name="vnotapbnetto" class="form-control" value="0">
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
                            </table>
                    </div>
                    <div id="pesan"></div>
                    <input type="hidden" name="jml" id="jml" value="0">
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

    var counter = 0;
    $("#addrow").on("click", function () {
        counter++;
        document.getElementById("jml").value = counter;
        var newRow = $("<tr>");
        var icustomer = $("#icustomer").val();
        
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

        $("#tabledata").on("click", ".ibtnDel", function (event) {
            $(this).closest("tr").remove();       
            counter -= 1
            document.getElementById("jml").value = counter;
        });
       
        $('#iproduct'+ counter).select2({
        placeholder: 'Pilih Kode Barang',
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
          cache: true
        }
        });
        });

    $("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
        counter -= 1
        document.getElementById("jml").value = counter;

    });

    function get(iarea) {
        $.ajax({
            type: "POST",
            url: "<?php echo site_url($folder.'/Cform/getspg');?>",
            data:"iarea="+iarea,
            dataType: 'json',
            success: function(data){
                $("#ispg").html(data.kop);
                if (data.kosong=='kopong') {
                    $("#submit").attr("disabled", true);
                }else{
                    $("#submit").attr("disabled", false);
                }
            },

            error:function(XMLHttpRequest){
                alert(XMLHttpRequest.responseText);
            }
        })
    }

    function getcust(id){
        $.ajax({
        type: "post",
        data: {
            'i_spg': id
        },
        url: '<?= base_url($folder.'/cform/getcustomer'); ?>',
        dataType: "json",
        success: function (data) {
            $('#ecustomername').val(data[0].e_customer_name);
            $('#icustomer').val(data[0].i_customer);
        },
        error: function () {
            alert('Error :)');
        }
        });
    }

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
           // hitungnilai();
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