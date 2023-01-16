<?= $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
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
                        <label class="col-md-4">Partner</label>
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2" required="">
                                <option value="<?=$data->i_bagian;?>"><?=$data->e_bagian_name;?></option>
                            </select>
                            <input type="hidden" id="id" name="id" class="form-control" value="<?= $data->id;?>">
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" name="ikeluar" id="ikeluar" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="BBK-2012-000001" maxlength="15" class="form-control input-sm" value="<?=$data->i_document;?>" aria-label="Text input with dropdown button">
                                <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span>
                            </div>
                            <span class="notekode">Format : (<?= $data->i_document;?>)</span><br>
                            <span class="notekode" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="dkeluar" name="dkeluar" class="form-control input-sm date"value="<?= $data->d_document; ?>" readonly onchange="maxi(this.value);">  
                        </div>
                        <div class="col-sm-4">
                            <select name="ipartner" id="ipartner" class="form-control select2">
                                <option value="<?= $data->id_partner; ?>"><?= $data->e_partner_name; ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Nomor Referensi</label>
                        <label class="col-md-2">Tanggal Referensi</label>                  
                        <label class="col-md-7">Keterangan</label>
                        <div class="col-sm-3">
                            <select name="imemo" required="" id="imemo" class="form-control select2">
                                <option value="<?= $data->id_document_reff; ?>"><?= $data->document_referensi; ?></option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="dmemo" name="dmemo" class="form-control input-sm" value="<?= $data->d_referensi ?>" readonly>
                        </div>
                        <div class="col-sm-7">
                           <textarea id= "eremark" name="eremark" class="form-control input-sm" placeholder="Isi Keterangan Jika Ada!"><?= $data->e_remark; ?></textarea>
                        </div>
                    </div>                 
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <?php if ($data->i_status == '1' || $data->i_status == '3' || $data->i_status == '7' || $data->i_status == '6') {?>
                                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return konfirm();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Update</button>&nbsp;
                            <?php } ?>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                            <?php if ($data->i_status == '1') {?>
                                <button type="button" id="send" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>&nbsp;
                                <button type="button" id="hapus" class="btn btn-danger btn-rounded btn-sm"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete</button>&nbsp;
                            <?php }elseif($data->i_status=='2') {?>
                                <button type="button" id="cancel" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-refresh"></i>&nbsp;&nbsp;Cancel</button>&nbsp;
                            <?php } ?>
                        </div> 
                    </div>
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
                        <th class="text-center" width="3%;">No</th>
                        <th class="text-center" width="10%;">Kode Barang</th>
                        <th class="text-center" width="30%;">Nama barang</th>
                        <th class="text-center">Jml Permintaan</th>
                        <th class="text-center">Jml Sisa</th>
                        <th class="text-center">Jml Pemenuhan</th>
                        <th class="text-center">Satuan</th>
                        <th class="text-center" width="20%;">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if($datadetail){
                        $i = 0;
                        foreach($datadetail as $row){
                            $i++;                             
                    ?>
                    <tr>   
                        <td class="text-center"><?= $i;?>
                            <input type="hidden" class="form-control input-sm" readonly id="baris<?= $i;?>" name="baris[]" value="<?= $i;?>">
                        </td> 
                        <td>  
                            <input type="hidden" class="form-control input-sm" id="idmaterial<?=$i;?>" name="idmaterial[]"value="<?= $row->id_material; ?>" readonly>
                            <input type="text" class="form-control input-sm" id="imaterial<?=$i;?>" name="imaterial[]"value="<?= $row->i_material; ?>" readonly>
                        </td>
                        <td>
                            <input type="text" class="form-control input-sm" id="ematerial<?=$i;?>" name="ematerial[]"value="<?= $row->e_material_name; ?>" readonly>
                        </td>                            
                        <td>
                            <input type="text" class="form-control input-sm text-right" id="nquantitymemo<?=$i;?>" name="nquantitymemo[]" value="<?= $row->nquantity_permintaan; ?>" readonly> 
                        </td>
                        <td>
                            <input type="text" class="form-control input-sm text-right" id="sisa<?=$i;?>" name="sisa[]" value="<?= $row->nquantity_pemenuhan; ?>" readonly> 
                        </td>
                        <td>
                            <input type="text" class="form-control input-sm text-right" autocomplete="off" id="nquantity<?=$i;?>" name="nquantity[]" value="<?= $row->n_quantity; ?>" onkeyup="ceksaldo(<?=$i;?>); angkahungkul(this);">
                        </td>      
                        <td>
                            <input style="width:100px" style="width:5%"style="width:5%"type="text" class="form-control" id="satuan<?=$i;?>" name="satuan[]" value="<?= $row->e_satuan_name; ?>"  readonly>
                        </td>                 
                        <td>
                            <input type="text" class="form-control input-sm" id="edesc<?=$i;?>" name="edesc[]"value="<?=$row->e_remark;?>">
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
        $('#ikeluar').mask('SSS-0000-000000S');
        $('.select2').select2();
        showCalendar('.date');
        //number();
        max();
        maxi();
    });

    $( "#ikeluar" ).keyup(function() {
        $.ajax({
            type: "post",
            data: {
                'kode' : $(this).val(),
                'ibagian' : $('#ibagian').val(),
            },
            url: '<?= base_url($folder.'/cform/cekkode'); ?>',
            dataType: "json",
            success: function (data) {
                if (data==1) {
                    $("#ada").attr("hidden", false);
                    $("#submit").attr("disabled", true);
                }else{
                    $("#ada").attr("hidden", true);
                    $("#submit").attr("disabled", false);
                }
            },
            error: function () {
                swal('Error :)');
            }
        });
    });

    $('#ceklis').click(function(event) {
        if($('#ceklis').is(':checked')){
            $("#ikeluar").attr("readonly", false);
        }else{
            $("#ikeluar").attr("readonly", true);
            $("#ada").attr("hidden", true);
            number();
        }
    });

    function number() {
        $.ajax({
            type: "post",
            data: {
                'tgl' : $('#dkeluar').val(),
                'ibagian' : $('#ibagian').val(),
            },
            url: '<?= base_url($folder.'/cform/number'); ?>',
            dataType: "json",
            success: function (data) {
                $('#ikeluar').val(data);
            },
            error: function () {
                swal('Error :)');
            }
        });
    }

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#addrow").attr("disabled", true);
        $("#send").attr("hidden", false);
    });

    $('#send').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'2','<?= $dfrom."','".$dto;?>');
    });

    // $(document).ready(function () {
    //     $('#ipartner').select2({
    //         placeholder: 'Pilih Partner',
    //         allowClear: true,
    //         ajax: {
    //             url: '<?= base_url($folder.'/cform/partner/'); ?>',
    //             dataType: 'json',
    //             delay: 250,
    //             data: function (params) {
    //                 var query = {
    //                     q : params.term,
    //                 }
    //                 return query;
    //             },
    //             processResults: function (data) {
    //                 return {
    //                     results: data
    //                 };
    //             },
    //             cache: false
    //         }
    //     }).change(function(event) {
    //         $("#imemo").attr("disabled", false);
    //         $("#tabledatax tr:gt(0)").remove();
    //         $("#jml").val(0);
    //         $("#imemo").val("");
    //         $("#imemo").html("");
    //     });

    //     $('#imemo').select2({
    //         placeholder: 'Cari No Referensi',
    //         width: '100%',
    //         allowClear: true,
    //         ajax: {
    //             url: '<?= base_url($folder.'/cform/referensi'); ?>',
    //             dataType: 'json',
    //             delay: 250,
    //             data: function (params) {
    //                 var query = {
    //                     q: params.term,
    //                     ipartner : $('#ipartner').val(),
    //                 }
    //                 return query;
    //             },
    //             processResults: function (data) {
    //                 return {
    //                     results: data
    //                 };
    //             },
    //             cache: false
    //         }
    //     });
    // });
    
    // $("#imemo").change(function() {
    //     $("#imemo").val($(this).val());
    //     $("#tabledatax tr:gt(0)").remove();       
    //     $("#jml").val(0);
    //     $.ajax({
    //         type: "post",
    //         data: {
    //             'id'  : $(this).val(),
    //         },
    //         url: '<?= base_url($folder.'/cform/getdetailrefeks'); ?>',
    //         dataType: "json",
    //         success: function (data) {
    //             var dmemo = data['head']['d_document'];
    //             var idpic = data['head']['id_pic_int'];
    //             var ipic  = data['head']['e_nama_karyawan'];
    //             var epic  = data['head']['e_pic_eks'];
    //             $('#dmemo').val(dmemo);
    //             $('#idpic').val(idpic);
    //             $('#ipic').val(ipic);
    //             $('#epic').val(epic);

    //             $('#tabledatax').attr('hidden', false);
    //             $('#jml').val(data['detail'].length);
    //             for (let a = 0; a < data['detail'].length; a++) {
    //                 var no = a+1;
    //                 var cols = "";
    //                 var newRow = $("<tr>");
    //                 cols += '<td style="text-align: center">'+no+'</td>';
    //                 cols += '<td style="text-align: center"><input hidden class="form-control" readonly id="idmaterial'+no+'" name="idmaterial[]" value="'+data['detail'][a]['id_material']+'"><input class="form-control" readonly id="imaterial'+no+'" name="imaterial'+no+'" value="'+data['detail'][a]['i_material']+'"></td>';
    //                 cols += '<td><input type="text" class="form-control" id="ematerial'+no+'" name="ematerial'+no+'" value="'+data['detail'][a]['e_material_name']+'" readonly></td>';
    //                 cols += '<td><input type="text" class="form-control" id="nquantitymemo'+no+'" name="nquantitymemo[]" value="'+data['detail'][a]['n_quantity']+'" readonly></td>';
    //                 cols += '<td><input class="form-control text-right" readonly id="sisa'+no+'" name="sisa[]" value="'+data['detail'][a]['n_quantity_sisa']+'"></td>';
    //                 cols += '<td><input class="form-control text-right" id="nquantity'+no+'" placeholder="0" name="nquantity[]" value="" onkeypress="return hanyaAngka(event);" onkeyup="ceksaldo('+no+');"></td>';
    //                 cols += '<td><input class="form-control" id="edesc'+no+'" name="edesc[]" value=""></td>';
    //                 newRow.append(cols);
    //                 $("#tabledatax").append(newRow);
    //             }
    //         },
    //         error: function () {
    //             swal('Data kosong :)');
    //         }
    //     });
    // });

    function max(){
        $('#dkeluar').datepicker('destroy');
        $('#dkeluar').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: "dd-mm-yyyy",
            todayBtn: "linked",
            daysOfWeekDisabled: [0],
            startDate: document.getElementById('dmemo').value,
        });
    }

    $('#dkeluar').datepicker({
        autoclose: true,
        todayHighlight: true,
        format: "dd-mm-yyyy",
        todayBtn: "linked",
        daysOfWeekDisabled: [0],
        startDate: document.getElementById('dmemo').value,
    });

    function maxi(){
        $('#dback').datepicker('destroy');
        $('#dback').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: "dd-mm-yyyy",
            todayBtn: "linked",
            daysOfWeekDisabled: [0],
            startDate: document.getElementById('dkeluar').value,
        });
    }

    $('#dback').datepicker({
        autoclose: true,
        todayHighlight: true,
        format: "dd-mm-yyyy",
        todayBtn: "linked",
        daysOfWeekDisabled: [0],
        startDate: document.getElementById('dkeluar').value,
    });

    $('#send').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'2','<?= $dfrom."','".$dto;?>');
    });
    $('#cancel').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'1','<?= $dfrom."','".$dto;?>');
    });
    $('#hapus').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'5','<?= $dfrom."','".$dto;?>');
    });

    function ceksaldo(i) {
        if (parseFloat($('#nquantity'+i).val()) > parseFloat($('#sisa'+i).val())) {
            swal('Qty terima tidak boleh lebih dari qty sisa!!!');
            $('#nquantity'+i).val($('#sisa'+i).val());
        }
    }

    function konfirm() {
        var jml = $('#jml').val();
        if (($('#ibagian').val()!='' || $('#ibagian').val()) && ($('#itujuan').val()!='' || $('#itujuan').val())) {
            if(jml==0){
                swal('Isi data item minimal 1 !!!');
                return false;
            }else{
                for(i=1;i<=jml;i++){
                    if($("#iproduct"+i).val()=='' || $("#eproductname"+i).val()=='' || $("#nquantity"+i).val()==''){
                        swal('Data item masih ada yang salah !!!');
                        return false;
                    }else{
                        return true;
                    } 
                }
            }
        }else{
            swal('Data Header Masih Ada yang Kosong!');
            return false;
        }
    }
</script>