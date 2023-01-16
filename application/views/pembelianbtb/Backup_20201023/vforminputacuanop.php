<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/prosesacuanop'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div class="col-md-6">
                    <div id="pesan"></div>
                
                    </div>    
                            <div class="panel-body table-responsive">
                                <table id="tabledata" class="display table" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th width="20%">Nomor OP</th>
                                            <th>Supplier</th>
                                            <th>Kode Barang</th>
                                            <th>Nama Barang</th>
                                            <th>Satuan</th>
                                            <th>Qty</th>
                                            <th>jenis</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?$i = 0;
                                        foreach ($data as $row) {
                                        $i++;?>
                                        <tr>
                                        <td class="col-sm-1">
                                            <input style ="width:40px"type="text" id="no<?=$i;?>" name="no<?=$i;?>"value="<?= $i; ?>" readonly >
                                        </td>
                                        <td class="col-sm-1">
                                            <input style ="width:150px"type="text" id="iop<?=$i;?>" name="iop<?=$i;?>"value="<?= $row->i_op; ?>" readonly >
                                        </td>
                                        <td class="col-sm-1">
                                            <input style ="width:150px"type="text" id="esuppliername<?=$i;?>" name="esuppliername<?=$i;?>"value="<?= $row->e_supplier_name; ?>" readonly >
                                            <input style ="width:150px"type="hidden" id="isupplier<?=$i;?>" name="isupplier<?=$i;?>"value="<?= $row->i_supplier; ?>" readonly >
                                        </td>
                                        <td class="col-sm-1">
                                            <input style ="width:90px" type="text" id="imaterial<?=$i;?>" name="imaterial<?=$i;?>"value="<?= $row->i_material; ?>" readonly >
                                        </td>
                                        <td class="col-sm-1">
                                            <input style ="width:200px" type="text" id="ematerialname<?=$i;?>" name="ematerialname<?=$i;?>"value="<?= $row->e_material_name; ?>" readonly >
                                        </td>
                                        <td class="col-sm-1">
                                            <input style ="width:70px" type="text" id="esatuan<?=$i;?>" name="esatuan<?=$i;?>"value="<?= $row->e_satuan; ?>" readonly >
                                            <input style ="width:70px" type="hidden" id="isatuan<?=$i;?>" name="isatuan<?=$i;?>"value="<?= $row->i_satuan; ?>" readonly >
                                        </td>
                                        <td class="col-sm-1">
                                            <input style ="width:70px"type="text" id="qty<?=$i;?>" name="qty<?=$i;?>"value="<?= $row->n_quantity; ?>" >
                                        </td>
                                        <td class="col-sm-1">
                                            <input style ="width:70px"type="hidden" id="ipayment<?=$i;?>" name="ipayment<?=$i;?>"value="<?= $row->ipayment; ?>" >
                                            <input style ="width:70px"type="text" id="qty<?=$i;?>" name="qty<?=$i;?>"value="<?= $row->e_payment_typename; ?>" >
                                            <!-- <input style ="width:60px"type="text" id="ipayment<#?=$i;?>" name="ipayment<#?=$i;?>"
                                            value="<#?if($row->ipayment == 0){echo "Cash";}else{echo "Kredit";} ?>" > -->
                                        </td>
                                        <td class="col-sm-1">
                                                <input type="checkbox"type="text"  name="acuanop<?=$i;?>" id = "acuanop">
                                        </td>
                                        </tr>
                                        <?}?>
                                        <label class="col-md-12">Jumlah Data</label>
                                        <input style ="width:50px"type="text" name="jml" id="jml" value="<?= $i; ?>">
                                        <input style ="width:50px"type="hidden" name="jenisp" id="jenisp" value="<?= $jenisp; ?>">
                                    </tbody>
                                </table>
                            </div>
                            <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-info btn-rounded btn-sm"> <i
                                    class="fa fa-plus"></i>&nbsp;&nbsp;Proses</button>
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