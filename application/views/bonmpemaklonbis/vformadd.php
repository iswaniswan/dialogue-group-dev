<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/proses'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div class="col-md-6">
                    <div id="pesan"></div>
                    <div class="form-group">
                        <label class="col-md-12">Supplier</label>
                        <div class="col-sm-12">
                            <select name="isupplier" id="isupplier" class="form-control select2">
                                <option value="">-- Pilih Supplier --</option>
                                <?php foreach ($supplier as $isupplier):?>
                                <option value="<?php echo $isupplier->i_supplier;?>">
                                    <?= $isupplier->i_supplier." - ".$isupplier->e_supplier_name;?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Jenis Pembayaran</label>
                        <div class="col-sm-7">
                        <select name="jenispembayaran" id="jenispembayaran" class="form-control select2">
                            <option value="0">Cash</option>
                            <option value="1">Kredit</option>
                        </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Acuan OP</label>
                        <div class="col-sm-12">
                        <input type="checkbox" class="form-check-input" name="acuanop">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-info btn-rounded btn-sm"> <i
                                    class="fa fa-plus"></i>&nbsp;&nbsp;Proses</button>
                        </div>
                    </div>
                    </div>
                    <input type="hidden" name="jml" id="jml">
                    
                            <!-- <div class="panel-body table-responsive">
                                <table id="tabledata" class="display table" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="20%">Kode Barang</th>
                                             <th>Kode Barang</th> 
                                            <th>Nama barang</th>
                                            <th>Satuan</th>
                                            <th>Qty</th>
                                            <th>Keterangan</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                         <tr>
                                        <td class="col-sm-4">
                                            <input type="text" name="name" class="form-control" />
                                        </td>
                                        <td class="col-sm-4">
                                            <input type="mail" name="mail"  class="form-control"/>
                                        </td>
                                        </tr> 
                                    </tbody>
                                </table> -->
                            </div>
                            </form>
                </div>
            </div>


        </div>
    </div>
</div>

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
    var counter = 0;

    $("#addrow").on("click", function () {
        counter++;
        document.getElementById("jml").value = counter;
        var newRow = $("<tr>");
        
        var cols = "";
        
        cols += '<td><select type="text" id="imaterial'+ counter + '" class="form-control" name="imaterial'+ counter + '" onchange="getmaterial('+ counter + ');"></td>';
        cols += '<td><input type="text" id="ematerialname'+ counter + '" type="text" class="form-control" name="ematerialname' + counter + '"></td>';
        cols += '<td><input type="text" id="esatuan'+ counter + '" class="form-control" name="esatuan'+ counter + '" onkeyup="cekval(this.value); reformat(this);"/></td>';
        cols += '<td><input type="text" id="nquantity'+ counter + '" class="form-control" name="nquantity'+ counter + '" onkeyup="cekval(this.value); reformat(this);"/></td>';
        // cols += '<td><input type="text" id="vunitprice'+ counter + '" class="form-control" name="vunitprice' + counter + '"/></td>';
        cols += '<td><input type="text" id="eremark'+ counter + '" class="form-control" name="eremark' + counter + '"/></td>';
        cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>';
        cols += '<td><input type="hidden" id="isatuan'+ counter + '" class="form-control" name="isatuan'+ counter + '" onkeyup="cekval(this.value);"/></td>';
        newRow.append(cols);
        $("#tabledata").append(newRow);
       
        $('#imaterial'+ counter).select2({
        placeholder: 'Pilih Material',
        allowClear: true,
        ajax: {
          url: '<?= base_url($folder.'/cform/datamaterial'); ?>',
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

    // function get(iarea) {
    //     /*alert(iarea);*/
    //     $.ajax({
    //         type: "POST",
    //         url: "<#?php echo site_url($folder.'/Cform/getcust');?>",
    //         data:"iarea="+iarea,
    //         dataType: 'json',
    //         success: function(data){
    //             $("#icustomer").html(data.kop);
    //             /*$("#icustomer").val(data.sok);*/
    //             if (data.kosong=='kopong') {
    //                 $("#submit").attr("disabled", true);
    //             }else{
    //                 $("#submit").attr("disabled", false);
    //             }
    //         },

    //         error:function(XMLHttpRequest){
    //             alert(XMLHttpRequest.responseText);
    //         }

    //     })
    // }
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

    function getmaterial(id){
        var imaterial = $('#imaterial'+id).val();
        $.ajax({
        type: "post",
        data: {
            'i_material': imaterial
        },
        url: '<?= base_url($folder.'/cform/getmaterial'); ?>',
        dataType: "json",
        success: function (data) {
            $('#ematerialname'+id).val(data[0].e_material_name);
            $('#esatuan'+id).val(data[0].e_satuan);
            $('#isatuan'+id).val(data[0].i_satuan);
        },
        error: function () {
            alert('Error :)');
        }
    });
    }
    
</script>