<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-12">Sumber</label>
                        <div class="col-sm-12">
                            <select name="isumber" id="isumber" class="form-control select2" onchange="get(this.value);">
                            <option value="">-- Pilih Sumber --</option>
                                <?php foreach ($sumber as $isumber):?>
                                <option value="<?php echo $isumber->i_sumber;?>">
                                    <?= $isumber->i_sumber." - ".$isumber->e_sumber;?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Tanggal BonM</label>
                        <div class="col-sm-7">
                            <input type="text" id= "dbonm" name="dbonm" class="form-control date"  readonly value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-info btn-rounded btn-sm"> <i
                                    class="fa fa-plus"></i>&nbsp;&nbsp;Simpan</button>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i
                                    class="fa fa-plus"></i>&nbsp;&nbsp;</button>
                            
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                <div class="form-group">
                        <label class="col-md-12">Jenis Masuk</label>
                        <div class="col-sm-12">
                        <select name="ijenis" id="ijenis" class="form-control select2" onchange="get(this.value);">
                                <option value="">-- Pilih Jenis Masuk --</option>
                                <?php foreach ($jenis as $ijenis):?>
                                <option value="<?php echo $ijenis->i_jenis_masuk;?>">
                                    <?= $ijenis->i_jenis_masuk." - ".$ijenis->e_jenis_masuk;?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">BonM manual</label>
                        <div class="col-sm-12">
                        <input type="text" id= "ibonmanual "name="ibonmanual" class="form-control" maxlength="30" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">keterangan</label>
                        <div class="col-sm-12">
                        <input type="text" id= "eremark "name="eremark" class="form-control" maxlength="30" value="">
                        </div>
                    </div>
                    <input type="text" name="jml" id="jml" value ="0">
                </div>
                    </div>
                            <div class="panel-body table-responsive">
                                <table id="tabledata" class="display table" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th width="15%">Kode Barang</th>
                                            <th width="20%">Nama Barang</th>
                                            <th width="15%">Warna</th>
                                            <th>Qty</th>
                                            <th>Keterangan</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                </div>
            </div>


        </div>
    </div>
</div>
                            </form>

<script>
function cekval(input){
     var jml   = counter;
     var num = input.replace(/\,/g,'');
     if(!isNaN(num)){
        for(j=1;j<=jml;j++){
           if(document.getElementById("nquantity"+j).value=='')
              document.getElementById("nquantity"+j).value='0';
             var jml    = counter;
             var totdis    = 0;
             var totnil = 0;
             var hrg    = 0;
             var ndis1  = parseFloat(formatulang(document.getElementById("nttbdiscount1").value));
             var ndis2  = parseFloat(formatulang(document.getElementById("nttbdiscount2").value));
             var ndis3  = parseFloat(formatulang(document.getElementById("nttbdiscount3").value));
             
             var vdis1  = 0;
             var vdis2  = 0;
             var vdis3  = 0;
             for(i=1;i<=jml;i++){
            document.getElementById("ndeliver"+i).value=document.getElementById("nquantity"+i).value;
                vprod=parseFloat(formatulang(document.getElementById("vunitprice"+i).value));
                nquan=parseFloat(formatulang(document.getElementById("nquantity"+i).value));
               var hrgtmp  = vprod*nquan;
                hrg        = hrg+hrgtmp;
             }
             
             vdis1=vdis1+((hrg*ndis1)/100);
             vdis2=vdis2+(((hrg-vdis1)*ndis2)/100);
             vdis3=vdis3+(((hrg-(vdis1+vdis2))*ndis3)/100);
             vdistot = vdis1+vdis2+vdis3;
             vhrgreal= hrg-vdistot;
             
             document.getElementById("vttbdiscount1").value=formatcemua(vdis1);
             
             document.getElementById("vttbdiscount2").value=formatcemua(vdis2);
             
             document.getElementById("vttbdiscount3").value=formatcemua(vdis3);
             document.getElementById("vttbdiscounttotal").value=formatcemua(vdistot);
             document.getElementById("vttbnetto").value=formatcemua(vhrgreal);
             document.getElementById("vttbgross").value=formatcemua(hrg);
          }
    }else{
        alert('input harus numerik !!!');
      input = input.substring(0,input.length-1);
     }
  }
  function cekqty(counter){
    var vjumlah = $('#nquantitykonv'+counter).val();
    // $('#vjumlah'+id).val(vjumlah);
    $('#nquantity'+counter).val(vjumlah);

  }
    var counter = 0;

    $("#addrow").on("click", function () {
        counter++;
        document.getElementById("jml").value = counter;
        var newRow = $("<tr>");
        
        var cols = "";
        cols += '<td><input readonly style=width:30px; id="baris'+counter+'" type="text" class="form-control" name="baris'+counter+'" value="'+counter+'"></td>';
        cols += '<td><select type="text" id="kodebrg'+ counter + '" class="form-control" name="kodebrg'+ counter + '" value="" onchange="getbrg('+ counter + ');"></td>';
        cols += '<td><input type="text" id="enamabrg'+ counter + '" type="text" class="form-control" name="enamabrg' + counter + '" value=""></td>';
        cols += '<td><select type="text" id="namawarna'+ counter + '" class="form-control" name="namawarna'+ counter + '" value="" onchange="getwarna('+ counter + ');"></td>';
        cols += '<td><input type="text" id="nquantity'+ counter + '" class="form-control" name="nquantity'+ counter + '" value="0" /></td>';
        cols += '<td><input type="text" id="edesc'+ counter + '" class="form-control" name="edesc' + counter + '" value=""/></td>';
        cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>';
        cols += '<td><input type="hidden" id="icolor'+ counter + '" class="form-control" name="icolor'+ counter + '" onkeyup="cekval(this.value);"/></td>';
        newRow.append(cols);
        $("#tabledata").append(newRow);
       
        $('#kodebrg'+ counter).select2({
        placeholder: 'Pilih Barang Wip',
        allowClear: true,
        ajax: {
          url: '<?= base_url($folder.'/cform/databrg'); ?>',
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
      $('#namawarna'+ counter).select2({
        placeholder: 'Pilih Warna',
        allowClear: true,
        ajax: {
          url: '<?= base_url($folder.'/cform/datawarna'); ?>',
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
    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date');
    });

    function getfaktur(icustomer) {
        /*alert(iarea);*/
        $.ajax({
            type: "POST",
            url: "<?php echo site_url($folder.'/Cform/getfaktur');?>",
            data:"icustomer="+icustomer,
            dataType: 'json',
            success: function(data){
                $("#inota").html(data.kop);
                /*$("#icustomer").val(data.sok);*/
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
    function getfaktur2(id){
        // alert(id);
        // var inota = $('#inota'+id).val();
        $.ajax({
        type: "post",
        data: {
            'i_nota': id
        },
        url: '<?= base_url($folder.'/cform/getharga2'); ?>',
        dataType: "json",
        success: function (data) {
            $('#nttbdiscount1').val(data[0].n_nota_discount1);
            $('#nttbdiscount2').val(data[0].n_nota_discount2);
            $('#nttbdiscount3').val(data[0].n_nota_discount3);
            $('#isalesman').val(data[0].i_salesman);
            // $('#vunitprice'+id).val(data[0].v_product_mill);
        },
        error: function () {
            alert('Error :)');
        }
    });
    }

    function getbrg(id){
        var kodebrg = $('#kodebrg'+id).val();
        $.ajax({
        type: "post",
        data: {
            'i_kodebrg': kodebrg
        },
        url: '<?= base_url($folder.'/cform/getbrg'); ?>',
        dataType: "json",
        success: function (data) {
            $('#enamabrg'+id).val(data[0].e_namabrg);
        },
        error: function () {
            alert('Error :)');
        }
    });
    }
    function getwarna(id){
        var namawarna = $('#namawarna'+id).val();
        $.ajax({
        type: "post",
        data: {
            'nama': namawarna
        },
        url: '<?= base_url($folder.'/cform/getwarna'); ?>',
        dataType: "json",
        success: function (data) {
            $('#icolor'+id).val(data[0].i_color);
        },
        error: function () {
            alert('Error :)');
        }
    });
    }
    
</script>