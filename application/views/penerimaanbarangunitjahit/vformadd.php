<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
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
                                <?php if ($bagian) {
                                    foreach ($bagian as $row):?>
                                        <option value="<?= $row->i_bagian;?>">
                                            <?= $row->e_bagian_name;?>
                                        </option>
                                    <?php endforeach; 
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" name="idocument" id="ipenerimaanbrg" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="SJ-2010-000001" maxlength="15" class="form-control input-sm" value="<?= $number;?>" aria-label="Text input with dropdown button">
                                <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span>
                            </div>
                            <span class="notekode">Format : (<?= $number;?>)</span><br>
                            <span class="notekode" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-2">
                             <input type="text" id= "ddocument" name="ddocument" class="form-control date" value="<?php echo date("d-m-Y"); ?>" required="" placeholder="<?=date('d-m-Y');?>"readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Partner</label>
                        <label class="col-md-4">Nomor Referensi</label>
                        <label class="col-md-5">Tanggal Referensi</label>
                        <div class="col-sm-3">
                            <select name="ipartner" id="ipartner" class="form-control select2">
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <select name="ireff" id="ireff" class="form-control select2" onchange="getdataitem(this.value);" disabled> 
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id= "dreferensi" name="dreferensi" class="form-control" value="" required="" placeholder="<?=date('d-m-Y');?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea id= "eremark" name="eremark" class="form-control" placeholder="Isi keterangan jika ada!"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return konfirm();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>&nbsp;&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/','#main'); return false;"><i class="ti-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;&nbsp;
                            <button type="button" hidden="true" id="send" onclick="changestatus('<?= $folder;?>',$('#id').val(),'2');" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<input type="hidden" name="jml" id="jml" value="0">
<div class="white-box" id="detail" hidden="true">
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
                        <th style="text-align:center;">Quantity Sisa</th>
                        <th style="text-align:center;">Quantity Masuk</th>
                        <th style="text-align:center;">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
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
        
        $('#ipenerimaanbrg').mask('SS-0000-000000S');
        //memanggil function untuk penomoran dokumen
        number();
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

    $( "#ipenerimaanbrg" ).keyup(function() {
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
                 'ibagian' : $('#ibagian').val(),
            },
            url: '<?= base_url($folder.'/cform/number'); ?>',
            dataType: "json",
            success: function (data) {
                $('#ipenerimaanbrg').val(data);
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
            $("#ipenerimaanbrg").attr("readonly", false);
        }else{
            $("#ipenerimaanbrg").attr("readonly", true);
        }
    });

    $('#send').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'2','<?= $dfrom."','".$dto;?>');
    });


    $("form").submit(function (event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#addrow").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#send").attr("hidden", false);
    });

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
                        var idproduct         = data['dataitem'][a]['id_product_wip'];
                        var iproduct          = data['dataitem'][a]['i_product_wip'];
                        var eproduct          = data['dataitem'][a]['e_product_wipname'];
                        var nquantitwip       = data['dataitem'][a]['n_quantity_wip'];
                        var nquantitywipsisa  = data['dataitem'][a]['n_quantity_wip_sisa'];                       
                        var icolor            = data['dataitem'][a]['i_color'];
                        var idcolor           = data['dataitem'][a]['id_color'];
                        var ecolor            = data['dataitem'][a]['e_color_name'];

                        var cols        = "";
                        var newRow = $("<tr>");
                            cols += '<td style="text-align: center;"><spanx id="snum'+no+'">'+no+'</spanx><input type="hidden" id="baris'+no+'" type="text" class="form-control" name="baris'+no+'" value="'+no+'"></td>';
                            cols += '<td><input readonly style="width:120px;" class="form-control" type="text" id="iproduct'+no+'" name="iproduct[]" value="'+iproduct+'"><input readonly style="width:100px;" class="form-control" type="hidden" id="idproduct'+no+'" name="idproduct[]" value="'+idproduct+'"></td>';
                            cols += '<td><input readonly style="width:400px;" class="form-control" type="text" id="eproduct'+no+'" name="eproduct'+no+'" value="'+eproduct+'"></td>'; 
                            cols += '<td><input style="width:40px;" type="hidden" id="idcolorpro'+no+'" name="idcolorpro[]" value="'+idcolor+'"><input style="width:40px;" type="hidden" id="icolorpro'+no+'" name="icolorpro[]" value="'+icolor+'"><input style="width:150px;" class="form-control" type="text" id="ecolor'+no+'" readonly name="ecolor'+no+'" value="'+ecolor+'"></td>';
                            cols += '<td><input style="width:100px;" readonly class="form-control" type="text" id="nquantitywip'+no+'" name="nquantitywip[]" value="'+nquantitwip+'"></td>';
                            cols += '<td><input style="width:100px;" readonly class="form-control" type="text" id="nquantitysisawip'+no+'" name="nquantitysisawip[]" value="'+nquantitywipsisa+'"></td>';
                            cols += '<td><input style="width:100px;" class="form-control inputitem" type="text" id="nquantitywipmasuk'+no+'" name="nquantitywipmasuk[]" value="'+nquantitywipsisa+'" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' onkeyup="ceksaldo('+no+');"></td>';
                            
                            cols += '<td><input style="width:500px;" class="form-control" type="text" id="edesc'+no+'" name="edesc[]" value=""><input style="width:40px;"  type="hidden" id="isatuan'+no+'" name="isatuan[]" value=""></td>';

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
        if (parseFloat($('#nquantitywipmasuk'+i).val()) > parseFloat($('#nquantitysisawip'+i).val())) {
            swal('Quantity tidak boleh lebih dari Quantity Sisa!!!');
            $('#nquantitywipmasuk'+i).val($('#nquantitysisawip'+i).val());
        }
        if(parseFloat($('#nquantitywipmasuk'+i).val()) == '0'){
            swal('Quantity Tidak Boleh 0 atau Kosong');
            $("#nquantitywipmasuk"+i).val($('#nquantitysisawip'+i).val());
        }
    }

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
                    $('#ipenerimaanbrg').val(data);
                },
                error: function () {
                    swal('Error :)');
                }
            });
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