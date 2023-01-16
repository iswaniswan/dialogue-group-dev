<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-4">Pembuat</label>
                        <label class="col-md-4">No faktur</label>
                        <label class="col-md-4">Tanggal Faktur</label>
                        <div class="col-sm-4">
                            <input type="hidden" id="idept" name="idept" class="form-control" value="<?php echo $data->i_dept;?>" readonly>
                            <input type="text" id="ekodemaster" name="ekodemaster" class="form-control" value="<?php echo $data->e_departement_name;?>" readonly>
                        </div>
                        <div class="col-sm-4">
                           <input type="text" id = "ifaktur" name="ifaktur" class="form-control" required="" value="<?= $data->i_faktur_code;?>"readonly>
                        </div>
                        <div class="col-sm-4">
                            <input name="dfaktur" id="dfaktur" class="form-control date" value ="<?= $data->d_faktur;?>" required="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Customer</label>
                        <label class="col-md-9">keterangan</label>
                        <div class="col-sm-3">
                            <input type="text" name="ecustomername" id="ecustomername" class="form-control" value="<?= $data->e_customer_name; ?>"readonly>
                            <input type="hidden" name="icustomer" id="icustomer" class="form-control" value="<?= $data->i_customer; ?>"readonly>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" name="eremark" id="eremark" class="form-control" value="<?= $data->e_remark; ?>"readonly>
                        </div>
                    </div>
                                    
                    <div class="form-group">
                    <div class="col-sm-offset-6 col-sm-12">
                        <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" ><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>  
                        <!-- <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah</button> -->
                        <!-- <button type="button" id="send" class="btn btn-success btn-rounded btn-sm" onclick="return getenabledsend();"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button> -->
                        <button type="button" class="btn btn-primary btn-rounded btn-sm" onclick="updatestatus('<?= $data->i_faktur_code;?>');"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                        <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>                                
                    </div>
          </div>
                </div>
                <div class="col-md-6">   
                    <div class="form-group row">
                        <label class="col-md-4">Nilai Kotor</label>
                        <label class="col-md-4">TotalDiscount</label>
                        <label class="col-md-4">Nilai Bersih</label>
                            <div class="col-sm-4">
                                <input type="text" id = "vspb" name="vspb" class="form-control"  value="<?= $data->v_kotor; ?>"readonly>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" id = "vspbdiscounttotal" name="vspbdiscounttotal" class="form-control"  value="<?= $data->v_discount; ?>"readonly>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" id = "vspbbersih" name="vspbbersih" class="form-control"  value="<?= $data->v_total_faktur; ?>"readonly>
                            </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Discount</label>
                        <label class="col-md-4">DPP</label>
                        <label class="col-md-4">PPN</label>
                            <div class="col-sm-4">
                                <input type="text" id = "vspb" name="vspb" class="form-control"  value="<?= $data->n_discount; ?>"readonly>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" id = "vspbdiscounttotal" name="vspbdiscounttotal" class="form-control"  value="<?= $data->v_dpp; ?>"readonly>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" id = "vspbbersih" name="vspbbersih" class="form-control"  value="<?= $data->v_ppn; ?>"readonly>
                            </div>
                    </div>
                    </div>
                </div>
                    <div class="panel-body table-responsive">
                        <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nomor DO</th>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <!-- <th>Warna</th> -->
                                    <th>Qty</th>
                                    <th>Harga</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0;
                                foreach ($data2 as $row) {
                                $i++;?>
                                <tr>
                                <td>
                                    <input style ="width:40px"type="text" id="no<?=$i;?>" name="no<?=$i;?>"value="<?= $i; ?>" readonly >
                                </td>
                                <td>
                                    <input style ="width:150px"type="text" id="ido<?=$i;?>" name="ido<?=$i;?>"value="<?= $row->i_do; ?>" readonly >
                                </td>
                                <td>
                                    <input style ="width:150px"type="text" id="iproduct<?=$i;?>" name="iproduct<?=$i;?>"value="<?= $row->i_product; ?>" readonly >
                                </td>
                                <td>
                                    <input style ="width:400px"type="text" id="eproductname<?=$i;?>" name="eproductname<?=$i;?>"value="<?= $row->e_product_name; ?>" readonly >
                                </td>
                                <td>
                                    <input style ="width:100px"type="text" id="nqty<?=$i;?>" name="nqty<?=$i;?>"value="<?= $row->n_deliver; ?>"readonly>
                                    <input style ="width:100px"type="hidden" id="nquantitystock<?=$i;?>" name="nquantitystock<?=$i;?>"value="<?= $row->n_deliver; ?>"readonly>
                                </td>
                                <td>
                                    <input style ="width:150px"type="text" id="vproductretail<?=$i;?>" name="vproductretail<?=$i;?>"value="<?= $row->v_unit_price; ?>"readonly>
                                    <input style ="width:150px"type="hidden" id="ncustomerdiscount1<?=$i;?>" name="ncustomerdiscount1<?=$i;?>"value="<?= $row->n_customer_discount1; ?>">
                                    <input style ="width:150px"type="hidden" id="ncustomerdiscount2<?=$i;?>" name="ncustomerdiscount2<?=$i;?>"value="<?= $row->n_customer_discount2; ?>">
                                    <input style ="width:150px"type="hidden" id="ncustomerdiscount3<?=$i;?>" name="ncustomerdiscount3<?=$i;?>"value="<?= $row->n_customer_discount3; ?>">
                                </td>
                                <td>
                                    <input style ="width:150px"type="text" id="vtotal<?=$i;?>" name="vtotal<?=$i;?>"value="<?= $row->vtotal; ?>" readonly>
                                </td>
                                
                                <!-- <td class="col-sm-1">
                                <input type="text" class="ibtnDel btn btn-md btn-danger "  value="Edit">
                                </td> -->
                                </tr>
                                <?}?>
                                <input style ="width:50px"type="text" name="jml" id="jml" value="<?= $i; ?>">
                            </tbody>
                        </table>
                    </div>
                </form>
            <div>
        </div>
    </div>
</div>

<script>

 $("form").submit(function(event) {
     event.preventDefault();
    //  $("input").attr("disabled", true);
    //  $("select").attr("disabled", true);
    //  $("#submit").attr("disabled", true);
 });


 $("#send").on("click", function () {
        var ifaktur = $("#ifaktur").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/send'); ?>",
            data: {
                     'kode'  : ifaktur,
                    },
            dataType: 'json',
            delay: 250, 
            success: function(data) {
                return {
                results: data
                };
            },
             cache: true
        });
    });

 function getenabledsend() {
    $('#send').attr("disabled", true);
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
    $("#addrow").attr("disabled", true);
    swal('Berhasil Di Send');
}

function updatestatus(ifaktur) {
    // var ifaktur = $("#ifaktur").val();
        swal({   
            title: "Kirim Draft Ini ke Atasan?",   
            text: "Anda tidak akan dapat memulihkan data ini!",   
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Ya, Kirim!",   
            cancelButtonText: "Tidak, batalkan!",   
            closeOnConfirm: false,   
            closeOnCancel: false 
        }, function(isConfirm){   
            if (isConfirm) { 
                $.ajax({
                    type: "post",
                    data: {
                        'ifaktur'  : ifaktur,
                        // 'istatus'  : istatus,
                    },
                    url: '<?= base_url($folder.'/cform/updatestatus'); ?>',
                    dataType: "json",
                    success: function (data) {
                        swal("Dikirim!", "Data berhasil Dikirim ke Atasan :)", "success");
                        show('<?= $folder;?>/cform/index/<?= $dfrom.'/'.$dto;?>','#main');
                    },
                    error: function () {
                        swal("Maaf", "Data gagal dikirim :(", "error");
                    }
                });
            } else {     
                swal("Dibatalkan", "Anda membatalkan pengiriman :)", "error");
            } 
        });
    }
// $(document).ready(function () {
//     $(".select").select();
//       showCalendar('.date');

    

//     // $("#tabledata").on("click", ".ibtnDel", function (event) {
//     //     $(this).closest("tr").remove();       
//     //     counter -= 1
//     // });
// });
    $(document).ready(function () {
        $(".select").select();
        showCalendar('.date');
    });

    $('#iproduct'+ counter).select2({
        placeholder: 'Pilih Product',
        allowClear: true,
        ajax: {
          url: '<?= base_url($folder.'/cform/getproduct/'); ?>',
          
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

      function getstock(ikodelokasi){
        var iop           = '<?= $data->i_op_code;?>';
        $.ajax({
            type: "post",
            data: {
                'ikodelokasi'   : ikodelokasi,
                'iop'           : iop,
                // 'iarea' : iarea,
                // 'ispb' : ispb,
                // 'dspb' : dspb
            },
            url: '<?= base_url($folder.'/cform/getdetstore'); ?>',
            dataType: "json",
            success: function (data) {
                // $('#istorelocation').val(data[0].i_store_location);
                // $('#estorelocationname').val(data[0].e_store_locationname);
                $('#nstock').val(data[0].n_quantity_stock);
                $('#submit').click();       
            },
            error: function () {
                alert('Error :)');
            }
        });
    }

      function getstock2(ikodemaster){
        // var dspb  = $('#dspb').val();
        $('#addrow').attr("disabled", false);
        // var iarea = $('#iarea').val();
        $.ajax({
            type: "post",
            data: {
                'ikodemaster': ikodemaster,
                // 'dspb'     : dspb,
                // 'iarea'    : iarea
            },
            url: '<?= base_url($folder.'/cform/getstock'); ?>',
            dataType: "json",
            success: function (data) {
                $('#ncustomerdiscount1').val(data[0].n_customer_discount1);
                $('#ncustomerdiscount2').val(data[0].n_customer_discount2);
                $('#ncustomerdiscount3').val(data[0].n_customer_discount3);
                $('#ecumstomeraddress').val(data[0].e_branch_address);
                $('#icustomer').val(data[0].i_customer);
                $('#isalesmanx').val(data[0].i_salesman);
                $('#esalesmannamex').val(data[0].e_salesman_name);        
            },
            error: function () {
                swal('Error :)');
            }
        });
    }

      function hitungnilai(isi,jml){
        jml=document.getElementById("jml").value;
        if (isNaN(parseFloat(isi))){
            swal("Input harus numerik");
        }else{
            
            dtmp1=parseFloat(formatulang(document.getElementById("ncustomerdiscount1"+jml).value));
            dtmp2=parseFloat(formatulang(document.getElementById("ncustomerdiscount2"+jml).value));
            dtmp3=parseFloat(formatulang(document.getElementById("ncustomerdiscount3"+jml).value));
            vdis1=0;
            vdis2=0;
            vdis3=0;
            vtot =0;
            
            
            for(i=1;i<=jml;i++){
                
                vhrg=formatulang(document.getElementById("vproductretail"+i).value);
                
                
                if (isNaN(parseFloat(document.getElementById("ndeliver"+i).value))){
                    nqty=0;
                    
                    
                }else{
                    // if((document.getElementById("fstock").value=='f')){
                    //     nqty=formatulang(document.getElementById("ndeliver"+i).value);
                    //     vhrg=parseFloat(vhrg)*parseFloat(nqty);
                    //     vtot=vtot+vhrg;
                    //     document.getElementById("vtotal"+i).value=formatcemua(vhrg);
                    //}
                    // else{
                        
                        if(parseFloat(document.getElementById("nquantitystock"+i).value)<parseFloat(document.getElementById("ndeliver"+i).value)){
                            
                            swal("Lebih Dari Stock!!!");
                            nqty=0;
                            vhrg=parseFloat(vhrg)*parseFloat(nqty);
                            vtot=vtot+vhrg;
                            document.getElementById("ndeliver"+i).value=formatcemua(vhrg);
                            document.getElementById("vtotal"+i).value=formatcemua(vhrg);

                        }else{
                            
                            nqty=formatulang(document.getElementById("ndeliver"+i).value);
                            vhrg=parseFloat(vhrg)*parseFloat(nqty);
                            vtot=vtot+vhrg;
                            document.getElementById("vtotal"+i).value=formatcemua(vhrg);

                        }

                    // }
                }////
                
            }
            vdis1=vdis1+((vtot*dtmp1)/100);
            // alert("asasa");
            vdis2=vdis2+(((vtot-vdis1)*dtmp2)/100);
            vdis3=vdis3+(((vtot-(vdis1+vdis2))*dtmp3)/100);
            // document.getElementById("vcustomerdiscount1"+jml).value=formatcemua(Math.round(vdis1));
            // document.getElementById("vcustomerdiscount2"+jml).value=formatcemua(Math.round(vdis2));
            // document.getElementById("vcustomerdiscount3"+jml).value=formatcemua(Math.round(vdis3));
            vdis1=parseFloat(vdis1);
            vdis2=parseFloat(vdis2);
            vdis3=parseFloat(vdis3);
            vtotdis=vdis1+vdis2+vdis3;
            document.getElementById("vspbdiscounttotal").value=formatcemua(Math.round(vtotdis));
            document.getElementById("vspb").value=formatcemua(vtot);
            vtotbersih=parseFloat(formatulang(formatcemua(vtot)))-parseFloat(formatulang(formatcemua(Math.round(vtotdis))));
            document.getElementById("vspbbersih").value=formatcemua(vtotbersih);
        }
    }
</script>