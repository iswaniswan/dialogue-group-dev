<!-- <form id="submit" method="post" action="<?= $folder;?>/cform/insertspbnew"> -->
<form id="form" action="" method="post">
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-check"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;&nbsp;<?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                 <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-3">Jenis SPB</label>
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2">
                                <?php if ($bagian) {
                                    foreach ($bagian as $row):?>
                                        <option value="<?= $row->i_bagian;?>" <?php if ($row->i_bagian==$data->i_bagian) {?> selected <?php } ?>>
                                            <?= $row->e_bagian_name;?>
                                        </option>
                                    <?php endforeach; 
                                } ?>
                            </select>
                            <input type="hidden" name="ibagianreff" id="ibagianreff" class="form-control" value="<?= $data->ibagian_reff;?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" value="<?= $id;?>">
                                <input type="text" name="isj" id="isj" readonly="" autocomplete="off" class="form-control" value="<?= $data->i_document;?>" readonly>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="ddocument" name="ddocument" class="form-control"  required="" readonly value="<?= $data->d_document;?>">
                        </div>
                        <div class="col-sm-3">
                            <select name="ijenis" id="ijenis" class="form-control select2">
                                 <option value="<?=$data->id_type_spb;?>"><?=$data->e_type_name;?></option>
                            </select>
                        </div>                            
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Area</label>
                        <label class="col-md-3">Customer</label>  
                        <label class="col-md-3">Nomor Referensi</label>
                        <label class="col-md-2">Tanggal Referensi</label>  
                        <div class="col-sm-3">
                            <select name="iarea" id="iarea" class="form-control select2">
                                <option value="<?=$data->id_area;?>"><?=$data->e_area;?></option>
                            </select>
                        </div> 
                        <div class="col-sm-3">
                            <select name="icustomer" id="icustomer" class="form-control select2">
                                <option value="<?=$data->id_customer;?>"><?=$data->e_customer_name;?></option>
                            </select>
                            <input type="hidden" id="ndiskontotal" name="ndiskontotal" class="form-control" value="" readonly>
                        </div>  
                        <div class="col-sm-3">
                            <select name="ireferensi" id="ireferensi" class="form-control select2">
                                <option value="<?=$data->id_document_reff;?>"><?=$data->i_referensi;?></option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="dreferensi" name="dreferensi" class="form-control" value="<?= $data->d_referensi; ?>" readonly>
                        </div>   
                    </div>    
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label> 
                        <div class="col-sm-12">
                            <textarea id="eremark" name="eremark" class="form-control" readonly><?= $data->e_remark; ?></textarea>     
                            <!-- hitungan u/ spb baru -->                       
                            <input type="hidden" id="nkotor" name="nkotor" class="form-control" value="" readonly>
                            <input type="hidden" id="nbersih" name="nbersih" class="form-control" value="" readonly>
                            <input type="hidden" id="vdpp" name="vdpp" class="form-control" value="" readonly>
                            <input type="hidden" id="vppn" name="vppn" class="form-control" value="" readonly>

                             <!-- hitungan u/ spb lama -->                       
                            <input type="hidden" id="nkotorold" name="nkotorold" class="form-control" value="" readonly>
                            <input type="hidden" id="nbersihold" name="nbersihold" class="form-control" value="" readonly>
                            <input type="hidden" id="vdppold" name="vdppold" class="form-control" value="" readonly>
                            <input type="hidden" id="vppnold" name="vppnold" class="form-control" value="" readonly>
                        </div>  
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-3 col-sm-12">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                            <button type="button" class="btn btn-warning btn-rounded btn-sm" onclick="statuschange('<?= $folder."','".$data->id;?>','1','<?= $dfrom."','".$dto;?>');"> <i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;Change Requested</button>&nbsp;
                            <button type="button" class="btn btn-danger btn-rounded btn-sm"  onclick="statuschange('<?= $folder."','".$data->id;?>','4','<?= $dfrom."','".$dto;?>');"> <i class="fa fa-times"></i>&nbsp;&nbsp;Reject</button>&nbsp;
                            <button type="button" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Approve</button>&nbsp;
                      </div>
                    </div>
                </div>
            </div>
        </div>
<div class="white-box" id="detail">
    <div class="col-sm-5">
        <h3 class="box-title m-b-0">Detail Barang</h3>
        <div class="m-b-0">
        </div>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th style="text-align: center; width: 3%;">No</th>
                        <th style="text-align: center; width: 15%;">Kode Barang</th>
                        <th style="text-align: center; width: 25%;">Nama barang</th>
                        <th style="text-align: center; width: 10%;">Saldo</th>
                        <th style="text-align: center; width: 10%;">Qty Permintaan</th>
                        <th style="text-align: center; width: 10%;">Qty Belum Terpenuhi</th>
                        <th style="text-align: center; width: 10%;">Qty Pemenuhan</th>
                        <th style="text-align: center; width: 30%;">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if($datadetail){
                        $i = 0;
                        foreach($datadetail as $row){
                            $sisa_b = $row->nquantity_permintaan - $row->n_quantity;
                            $i++;                             
                    ?>
                    <tr>   
                        <td style="text-align: center;"><?= $i;?>
                            <input style="width:10px" type="hidden" class="form-control" readonly id="baris<?= $i;?>" name="baris[]" value="<?= $i;?>">
                        </td> 
                        <td>  
                            <input style="width:120px" type="hidden" class="form-control" id="idproduct<?=$i;?>" name="idproduct[]"value="<?= $row->id_product; ?>" readonly>
                            <input style="width:120px" type="text" class="form-control" id="iproduct<?=$i;?>" name="iproduct[]"value="<?= $row->i_product_base; ?>" readonly>

                            <input style="width:350px" type="hidden" class="form-control" id="vprice<?=$i;?>" name="vprice[]"value="<?=$row->v_price;?>" readonly>
                            <input style="width:350px" type="hidden" class="form-control" id="1ndiskon<?=$i;?>" name="1ndiskon[]"value="<?=$row->n_diskon1;?>" readonly>
                            <input style="width:350px" type="hidden" class="form-control" id="2ndiskon<?=$i;?>" name="2ndiskon[]"value="<?=$row->n_diskon2;?>" readonly>
                            <input style="width:350px" type="hidden" class="form-control" id="3ndiskon<?=$i;?>" name="3ndiskon[]"value="<?=$row->n_diskon3;?>" readonly>
                            <input style="width:350px" type="hidden" class="form-control" id="1vdiskon<?=$i;?>" name="1vdiskon[]"value="" readonly>
                            <input style="width:350px" type="hidden" class="form-control" id="2vdiskon<?=$i;?>" name="2vdiskon[]"value="" readonly>
                            <input style="width:350px" type="hidden" class="form-control" id="3vdiskon<?=$i;?>" name="3vdiskon[]"value="" readonly>
                            <input style="width:350px" type="hidden" class="form-control" id="vdiskonadd<?=$i;?>" name="vdiskonadd[]"value="<?=$row->v_diskontambahan;?>" readonly>
                            <input style="width:350px" type="hidden" class="form-control" id="vtdiskon<?=$i;?>" name="vtdiskon[]"value="" readonly>
                        </td>
                        <td>
                            <input style="width:350px" type="text" class="form-control" id="eproduct<?=$i;?>" name="eproduct[]"value="<?= $row->e_product_basename; ?>" readonly>
                        </td>              
                        <td>
                            <input style="width:100px" type="text" class="form-control" id="nsaldo<?=$i;?>" name="nsaldo[]" value="0" readonly> 
                        </td>              
                        <td>
                            <input style="width:100px" type="text" class="form-control" id="nquantitymemo<?=$i;?>" name="nquantitymemo[]" value="<?= $row->nquantity_permintaan; ?>" readonly> 
                        </td>
                        <td>
                            <input style="width:100px" type="text" class="form-control" id="sisa<?=$i;?>" name="sisa[]" value="<?= $row->nquantity_pemenuhan; ?>" readonly>

                            <input style="width:100px" type="hidden" class="form-control" id="sisab<?=$i;?>" name="sisab[]" value="<?= $sisa_b; ?>" readonly>
                        </td>
                        <td>
                            <input style="width:100px" style="width:5%"style="width:5%"type="text" class="form-control" id="nquantity<?=$i;?>" name="nquantity[]" value="<?= $row->n_quantity; ?>" onkeyup="ceksaldo(<?=$i;?>);" readonly>
                        </td>                    
                        <td>
                            <input style="width:350px" type="text" class="form-control" id="edesc<?=$i;?>" name="edesc[]"value="<?=$row->e_remark;?>" readonly>
                            <!-- hitungan u/ spb baru -->                           
                            <input style="width:100px" type="hidden" class="form-control" id="vtotal<?=$i;?>" name="vtotal[]" value="" readonly> 
                            <input style="width:100px" type="hidden" class="form-control" id="vtotalbersih<?=$i;?>" name="vtotalbersih[]" value="" readonly> 

                            <!-- hitungan u/ spb lama -->                           
                            <input style="width:100px" type="hidden" class="form-control" id="vtotalold<?=$i;?>" name="vtotalold[]" value="" readonly> 
                            <input style="width:100px" type="hidden" class="form-control" id="vtotalbersihold<?=$i;?>" name="vtotalbersihold[]" value="" readonly> 
                        </td>                                  
                    </tr>                    
                    <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                    <?}
                    }?>        
                </tbody>
            </table>
        </div>
    </div>
</div>
</form>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date');
        hitungb();
        hitungold();
    });

     $(document).ready(function(){
        // click on button submit
        $("#submit").on('click', function(){
            ada = false;
            for (var i = 1; i <= $('#jml').val(); i++) {
                if (parseInt($('#nquantity'+i).val()) > parseInt($('#sisa'+i).val())){
                    swal('Dokumen Referensi sudah pernah dibuat, silahkan dicek kembali');
                    ada = true;
                    return false;
                }   
            }
            if (!ada) {
                for (var i = 1; i <= $('#jml').val(); i++) {
                    if(parseInt($('#sisab'+i).val()) > 0){
                        cek = true;
                        //alert("cek");
                    }else{
                        //alert("a");
                        cek = false;
                    }
                }
                swal({   
                        title: "Quantity masih belum terpenuhi!",   
                        text: " Buat SPB baru?",   
                        type: "warning",   
                        showCancelButton: true,   
                        confirmButtonColor: "#DD6B55",   
                        cancelButtonText: "Tidak, hanya Approve!",   
                        confirmButtonText: "Ya, Buat SPB baru!",   
                        closeOnConfirm: false,   
                        closeOnCancel: false 
                    }, function(isConfirm){   
                        if (isConfirm) { 
                            $.ajax({
                                url: '<?= base_url($folder.'/cform/insertspbnew/'); ?>',
                                type : "POST", // type of action POST || GET
                                dataType : 'json', // data type
                                data : $("#form").serialize(), // post data || get data
                                success : function(data) {
                                    // you can see the result from the console
                                    // tab of the developer tools
                                    swal("Insert!", "Data berhasil Di insert :)", "success");
                                    show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main');
                                    //console.log(result);
                                    //return false;
                                },
                                error: function() {
                                    swal("Maaf", "Data gagal insert :(", "error");
                                    //console.log(xhr, resp, text);
                                }
                            });
                        } else {     
                            swal("Dibatalkan", "Anda membatalkan insert spb baru :)", "error");
                            statuschange('<?= $folder;?>',$('#id').val(),'6','<?= $dfrom."','".$dto;?>');
                        } 
                    });
                // send ajax
                
            }else{
                return false;
            }
        });
    });

    function hitungb(){
        var total = 0;
        var totaldis = 0;
        var vjumlah = 0;
        var dpp = 0;
        var ppn = 0;
        var grand = 0;

        for (var i = 1; i <= $('#jml').val(); i++) {
            var sisa      = $('#sisab'+i).val();
            var vprice    = $('#vprice'+i).val();
            var disc1     = formatulang($('#1ndiskon'+i).val());
            var disc2     = formatulang($('#2ndiskon'+i).val());
            var disc3     = formatulang($('#3ndiskon'+i).val());
            var disc4     = formatulang($('#vdiskonadd'+i).val());

            jumlah = sisa * vprice;
            var ndisc1 = jumlah * (disc1/100);
            var ndisc2 = (jumlah - ndisc1) * (disc2/100);
            var ndisc3 = (jumlah - ndisc1 - ndisc2) * (disc3/100);

            var vtotaldis = (ndisc1 + ndisc2 + ndisc3 + parseInt(disc4));
               
            var vtotal  = jumlah - vtotaldis;

            $('#1vdiskon'+i).val(ndisc1);
            $('#2vdiskon'+i).val(ndisc2);
            $('#3vdiskon'+i).val(ndisc3);
            $('#vtdiskon'+i).val(vtotaldis);
            $('#vtotal'+i).val(jumlah);
            $('#vtotalbersih'+i).val(vtotal);

            totaldis += vtotaldis;
            vjumlah += jumlah;
            total += vtotal;
        }
        $('#nkotor').val(vjumlah);
        $('#ndiskontotal').val(totaldis);
 
        dpp = vjumlah - totaldis;
        ppn = dpp * 0.1;
        grand = dpp + ppn;

        $('#nbersih').val(grand);
        $('#vdpp').val(dpp);
        $('#vppn').val(ppn);
    }

    function hitungold(){
        var total = 0;
        var totaldis = 0;
        var vjumlah = 0;
        var dpp = 0;
        var ppn = 0;
        var grand = 0;

        for (var i = 1; i <= $('#jml').val(); i++) {
            var sisa      = $('#nquantity'+i).val();
            var vprice    = $('#vprice'+i).val();
            var disc1     = formatulang($('#1ndiskon'+i).val());
            var disc2     = formatulang($('#2ndiskon'+i).val());
            var disc3     = formatulang($('#3ndiskon'+i).val());
            var disc4     = formatulang($('#vdiskonadd'+i).val());

            jumlah = sisa * vprice;
            var ndisc1 = jumlah * (disc1/100);
            var ndisc2 = (jumlah - ndisc1) * (disc2/100);
            var ndisc3 = (jumlah - ndisc1 - ndisc2) * (disc3/100);

            var vtotaldis = (ndisc1 + ndisc2 + ndisc3 + parseInt(disc4));
               
            var vtotal  = jumlah - vtotaldis;

            $('#vtotalold'+i).val(jumlah);
            $('#vtotalbersihold'+i).val(vtotal);

            totaldis += vtotaldis;
            vjumlah += jumlah;
            total += vtotal;
        }
        $('#nkotorold').val(vjumlah);
 
        dpp = vjumlah - totaldis;
        ppn = dpp * 0.1;
        grand = dpp + ppn;

        $('#nbersihold').val(grand);
        $('#vdppold').val(dpp);
        $('#vppnold').val(ppn);
    }
</script>