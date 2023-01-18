<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-3">Bagian Pembuat</label>
                        <label class="col-md-4">Nomor Dokumen</label>
                        <label class="col-md-5">Tanggal Dokumen</label>
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2" required="">
                                <option value="<?=$data->i_bagian;?>"><?=$data->e_bagian_name;?></option>
                            </select>
                            <input type="hidden" id="id" name="id" class="form-control" value="<?= $data->id;?>">
                        </div>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" name="idocument" id="idocument" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="SJ-2010-000001" maxlength="15" class="form-control input-sm" value="<?=$data->i_document;?>" aria-label="Text input with dropdown button">
                                <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span>
                            </div>
                            <span class="notekode">Format : (<?= $data->i_document;?>)</span><br>
                            <span class="notekode" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-2"> 
                             <input type="text" id= "ddocument" name="ddocument" class="form-control date" value="<?= $data->d_document; ?>" placeholder="<?=date('d-m-Y');?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Partner</label>
                        <label class="col-md-4">Nomor Referensi</label>
                        <label class="col-md-5">Tanggal Referensi</label>
                        <div class="col-sm-3">
                            <select name="ipartner" id="ipartner" class="form-control select2">
                                <option value="<?= $data->i_bagian_pengirim; ?>"><?= $data->e_bagian_pengirim; ?></option>
                            </select>
                            <input type="hidden" id= "idpartner" name="idpartner" class="form-control date" value="<?= $data->i_bagian_pengirim; ?>">
                        </div>
                        <div class="col-sm-4">
                            <select name="ireff" id="ireff" class="form-control select2" onchange="getdataitem(this.value);"> 
                                <option value="<?= $data->id_document_reff; ?>"><?= $data->i_reff; ?></option>
                            </select>
                            <input type="hidden" id= "idreff" name="idreff" class="form-control date" value="<?= $data->id_document_reff; ?>">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id= "dreferensi" name="dreferensi" class="form-control" value="<?= $data->d_reff; ?>" required="" placeholder="<?=date('d-m-Y');?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea id= "eremark" name="eremark" class="form-control" placeholder="Isi keterangan jika ada!"><?= $data->e_remark; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
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
</div>
<div class="white-box" id="detail">
    <div class="col-sm-5">
        <h3 class="box-title m-b-0">Detail Barang</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead> 
                    <tr>
                        <th style="text-align:center;">No</th>
                        <th style="text-align:center;">Kode Barang</th>
                        <th style="text-align:center;">Nama Barang</th>
                        <th style="text-align:center;">Warna</th>
                        <th style="text-align:center;">Quantity (Pengembalian)</th>
                        <th style="text-align:center;">Quantity Masuk</th>
                        <th style="text-align:center;">Keterangan</th>
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
                        <td style="text-align: center;"><?= $i;?>
                            <input style="width:10px" type="hidden" class="form-control" readonly id="baris<?= $i;?>" name="baris[]" value="<?= $i;?>">
                        </td> 
                        <td>  
                            <input style="width:120px" type="hidden" class="form-control" id="idproduct<?=$i;?>" name="idproduct[]"value="<?= $row->id_product_base; ?>" readonly>
                            <input style="width:120px" type="text" class="form-control" id="iproductwip<?=$i;?>" name="iproductwip[]"value="<?= $row->i_product_base; ?>" readonly>
                        </td>
                        <td>
                            <input style="width:350px" type="text" class="form-control" id="eproductwip<?=$i;?>" name="eproductwip[]"value="<?= $row->e_product_basename; ?>" readonly>
                        </td>                           
                        <td>  
                            <input type="hidden" class="form-control" id="idcolorpro<?=$i;?>" name="idcolorpro[]"value="<?= $row->id_color; ?>" readonly>
                            <input style="width:150px" type="text" class="form-control" id="ecolorname<?=$i;?>" name="ecolorname[]"value="<?= $row->e_color_name; ?>" readonly>
                        </td> 
                        <td>
                            <input style="text-align:right;width:100px" type="text" class="form-control" id="nquantitywip<?=$i;?>" name="nquantitywip[]" value="<?= $row->n_quantity_keluar; ?>" readonly> 
                        </td>
                        <td>
                            <input style="text-align:right;width:100px" style="width:5%"style="width:5%"type="text" class="form-control inputitem" id="nquantitywipmasuk<?=$i;?>" name="nquantitywipmasuk[]" value="<?= $row->n_quantity_masuk; ?>" onblur='if(this.value==""){this.value="0";}' onfocus='if(this.value=="0"){this.value="";}' onkeyup="ceksaldo(<?=$i;?>);">
                            <input style="text-align:right;width:100px" style="width:5%"style="width:5%"type="hidden" class="form-control" id="nquantitywipsisa<?=$i;?>" name="nquantitywipsisa[]" value="<?= $row->n_quantity_sisa; ?>" readonly> 
                        </td>                       
                        <td>  
                            <input style="width:350px" type="text" class="form-control" id="edesc<?=$i;?>" name="edesc[]"value="<?= $row->e_remark; ?>">
                        </td>         
                    </tr>                      
                    <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                    <?}
                        $i=0;
                        $read = "disabled";
                    }else{ 
                        echo "<table class=\"table table-striped bottom\" style=\"width:100%;\"><tr><td colspan=\"16\" style=\"text-align:center;\">Maaf Tidak Ada  Bon Masuk!</td></tr></table>";     
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
        /**
        * Tidak boleh lebih dari hari ini, dan maksimal mundur 1830 hari (5 tahun) dari hari ini
        */
        showCalendar('.date',1830,0);
        max_tgl();
        $('#idocument').mask('SS-0000-000000S');
        //memanggil function untuk penomoran dokumen
        //number();
        $('#ipartner').select2({
            placeholder: 'Pilih Partner',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/partner'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: false
            }
        });

        $('#ireff').select2({
            placeholder: 'Pilih Referensi',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/referensi'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                        ipartner : $('#ipartner').val(),
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: false
            }
        });
    });

    $( "#idocument" ).keyup(function() {
        $.ajax({
            type: "post",
            data: {
                'kode' : $(this).val(),
            },
            url: '<?= base_url($folder.'/cform/cekkode'); ?>',
            dataType: "json",
            success: function (data) {
                if (data==1) {
                    $(".notekode").attr("hidden", false);
                    $("#submit").attr("disabled", true);
                }else{
                    $(".notekode").attr("hidden", true);
                    $("#submit").attr("disabled", false);
                }
            },
            error: function () {
                swal('Error :)');
            }
        });
    });

    //menyesuaikan periode di running number sesuai dengan tanggal dokumen
    $( "#ddocument" ).change(function() {
        $.ajax({
            type: "post",
            data: {
                'tgl' : $(this).val(),
            },
            url: '<?= base_url($folder.'/cform/number'); ?>',
            dataType: "json",
            success: function (data) {
                $('#idocument').val(data);
            },
            error: function () {
                swal('Error :)');
            }
        });
    });

    $("#ipartner").change(function(){
        $('#ireff').attr("disabled", false);
    });

    $('#ceklis').click(function(event) {
        if($('#ceklis').is(':checked')){
            $("#idocument").attr("readonly", false);
        }else{
            $("#idocument").attr("readonly", true);
        }
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


    $("form").submit(function (event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#addrow").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#send").attr("hidden", false);
    });

    //untuk me-generate running number
    function number() {
        $.ajax({
            type: "post",
            data: {
                'tgl' : $('#ddocument').val(),
                'ibagian' : $('#ibagian').val(),
            },
            url: '<?= base_url($folder.'/cform/number'); ?>',
            dataType: "json",
            success: function (data) {
                $('#idocument').val(data);
            },
            error: function () {
                swal('Error :)');
            }
        });
    }

    function getdataitem(ireff) {
        var idreff   = $('#ireff').val();
        var ipartner = $('#ipartner').val();
            $.ajax({
                type: "post",
                data: {
                    'idreff': idreff,
                    'ipartner' : ipartner,
                },
                url: '<?= base_url($folder.'/cform/getdataitem'); ?>',
                dataType: "json",
                success: function (data) {  
                    
                    $('#jml').val(data['jmlitem']);
                    $("#tabledatax tbody").remove();
                    $("#detail").attr("hidden", false);

                    var dref =  data['datahead']['d_document'];
                    $("#dreferensi").val(dref);

                    for (let a = 0; a < data['jmlitem']; a++) {
                        var no = a+1;
                        count=$('#tabledatax tr').length;   
                        var idproduct         = data['dataitem'][a]['id_product_base'];
                        var iproduct          = data['dataitem'][a]['i_product_base'];
                        var eproduct          = data['dataitem'][a]['e_product_basename'];
                        var nquantitwip       = data['dataitem'][a]['n_quantity'];
                        var nquantitywipsisa  = data['dataitem'][a]['n_sisa_retur'];                       
                        var icolor            = data['dataitem'][a]['i_color'];
                        var idcolor           = data['dataitem'][a]['id_color'];
                        var ecolor            = data['dataitem'][a]['e_color_name'];

                        var cols        = "";
                        var newRow = $("<tr>");
                            cols += '<td style="text-align: center;"><spanx id="snum'+no+'">'+no+'</spanx><input type="hidden" id="baris'+no+'" type="text" class="form-control" name="baris'+no+'" value="'+no+'"></td>';
                            cols += '<td><input readonly style="width:120px;" class="form-control" type="text" id="iproduct'+no+'" name="iproduct[]" value="'+iproduct+'"><input readonly style="width:100px;" class="form-control" type="hidden" id="idproduct'+no+'" name="idproduct[]" value="'+idproduct+'"></td>';
                            cols += '<td><input readonly style="width:400px;" class="form-control" type="text" id="eproduct'+no+'" name="eproduct'+no+'" value="'+eproduct+'"></td>'; 
                            cols += '<td><input style="width:40px;" type="hidden" id="idcolorpro'+no+'" name="idcolorpro[]" value="'+idcolor+'"><input style="width:40px;" type="hidden" id="icolorpro'+no+'" name="icolorpro[]" value="'+icolor+'"><input style="width:150px;" class="form-control" type="text" id="ecolor'+no+'" readonly name="ecolor'+no+'" value="'+ecolor+'"></td>';
                            cols += '<td><input style="width:100px;" readonly class="form-control" type="text" id="nquantitywip'+no+'" name="nquantitywip[]" value="'+nquantitywipsisa+'"></td>';
                            cols += '<td><input style="width:100px;" class="form-control inputitem" type="text" id="nquantitywipmasuk'+no+'" name="nquantitywipmasuk[]" value="'+nquantitywipsisa+'"  onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' onkeyup="ceksaldo('+no+');"></td>';                            
                            cols += '<td><input style="width:500px;" class="form-control" type="text" id="edesc'+no+'" name="edesc[]"  placeholder="Isi keterangan jika ada!" value=""><input style="width:40px;"  type="hidden" id="isatuan'+no+'" name="isatuan[]" value=""></td>';

                            newRow.append(cols);
                            $("#tabledatax").append(newRow);
                    }

                    function formatSelection(val) {
                        return val.name;
                    }
                    max_tgl();
                    $("#tabledatax").on("click", ".ibtnDel", function (event) {
                        $(this).closest("tr").remove();       
                    });
                },
            error: function () {
                alert('Error :)');
            }
        });
    } 

    function ceksaldo(i) {
        if (parseFloat($('#nquantitywipmasuk'+i).val()) > parseFloat($('#nquantitywip'+i).val())) {
            swal('Quantity Retur Masuk tidak boleh lebih dari Quantity Pengembalian!!!');
            $('#nquantitywipmasuk'+i).val($('#nquantitywip'+i).val());
        }
        if (parseFloat($('#nquantitywipmasuk'+i).val()) == 0 || parseFloat($('#nquantitywipmasuk'+i).val()) == null) {
            swal('Quantity Retur Masuk tidak boleh 0 atau kosong!!!');
            $('#nquantitywipmasuk'+i).val($('#nquantitywip'+i).val());
        }
    }

    function max_tgl(val) {
        $('#ddocument').datepicker('destroy');
        $('#ddocument').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: "dd-mm-yyyy",
            todayBtn: "linked",
            daysOfWeekDisabled: [0],
            startDate: document.getElementById('dreferensi').value,
        });
    }
    $('#ddocument').datepicker({
        autoclose: true,
        todayHighlight: true,
        format: "dd-mm-yyyy",
        todayBtn: "linked",
        daysOfWeekDisabled: [0],
        startDate: document.getElementById('dreferensi').value,
    });

    function konfirm() {
        var jml = $('#jml').val();
        ada = false;
        if(jml==0){
            swal('Isi data item minimal 1 !!!');
            return false;
        }else{
            $("#tabledatax tbody tr").each(function() {
                $(this).find("td select").each(function() {
                    if ($(this).val()=='' || $(this).val()==null) {
                        swal('Kode barang tidak boleh kosong!');
                        ada = true;
                    }
                });
                $(this).find("td .inputitem").each(function() {
                    if ($(this).val()=='' || $(this).val()==null || $(this).val()==0) {
                        swal('Quantity Tidak Boleh Kosong Atau 0!');
                        ada = true;
                    }
                });
            });
            if (!ada) {
                return true;
            }else{
                return false;
            }
        }    
    }
</script>