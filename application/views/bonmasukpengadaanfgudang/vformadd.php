<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i>  <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i><?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-4">Bagian Pembuat</label>
                        <label class="col-md-4">Nomor Dokumen</label>
                        <label class="col-md-4">Tanggal Dokumen</label>
                        <div class="col-sm-4">
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
                                <input type="text" name="idocument" id="idocument" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="BBM-2010-000001" maxlength="15" class="form-control input-sm" value="<?= $number;?>" aria-label="Text input with dropdown button">
                                <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span>
                            </div>
                            <span class="notekode">Format : (<?= $number;?>)</span><br>
                            <span class="notekode" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-4">
                             <input type="text" id= "ddocument" name="ddocument" class="form-control input-sm date" value="<?php echo date("d-m-Y"); ?>" required="" placeholder="<?=date('d-m-Y');?>"readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Pengirim</label>
                        <label class="col-md-4">Nomor Referensi</label>
                        <label class="col-md-4">Tanggal Referensi</label>
                        <div class="col-sm-4">
                            <select name="ipengirim" id="ipengirim" class="form-control select2">
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <select name="ireff" id="ireff" class="form-control select2" onchange="getdataitem(this.value);" disabled> 
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id= "dreferensi" name="dreferensi" class="form-control input-sm" value="" required="" placeholder="<?=date('d-m-Y');?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea id= "eremark" name="eremark" class="form-control" placeholder="Isi keterangan jika ada!"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm mr-2" onclick="return konfirm();"><i class="fa fa-save mr-2" ></i>Simpan</button>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm mr-2" onclick="show('<?= $folder1; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"><i class="ti-arrow-circle-left mr-2"></i>Kembali</button>
                            <button type="button" hidden="true" id="send" onclick="changestatus('<?= $folder;?>',$('#kode').val(),'2');" class="btn btn-primary btn-rounded btn-sm mr-2"><i class="fa fa-paper-plane-o mr-2"></i>Send</button>
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
                        <th class="text-center" width="3%">No</th>
                        <th width="12%">Kode Barang</th>
                        <th width="25%">Nama Barang</th>
                        <th class="text-right" width="12%">Qty Keluar</th>
                        <th class="text-right" width="12%">Qty Pemenuhan</th>
                        <th class="text-right" width="12%">Qty Masuk</th>
                        <th>Keterangan</th>
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
        //  showCalendar('.date');
        
        $('#idocument').mask('SSS-0000-000000S');
        //memanggil function untuk penomoran dokumen
        number();
        $('#ipengirim').select2({
            placeholder: 'Pilih Bagian Pengirim',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/bagianpengirim'); ?>',
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
                        iasal : $('#ipengirim').val(),
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
                'ibagian' : $('#ibagian').val(),  
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
        number();
    });

    $("#ipengirim").change(function(){
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


    $("form").submit(function (event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#addrow").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#send").attr("hidden", false);
    });

    function getdataitem(ireff) {
        var idreff = $('#ireff').val();
        var ipengirim = $('#ipengirim').val();
            $.ajax({
                type: "post",
                data: {
                    'idreff': idreff,
                    'ipengirim' : ipengirim,
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
                        var idmaterial        = data['dataitem'][a]['id_material'];
                        var imaterial         = data['dataitem'][a]['i_material'];
                        var ematerial         = data['dataitem'][a]['e_material_name'];
                        var nquantity         = data['dataitem'][a]['n_quantity'];
                        var nquantitysisa     = data['dataitem'][a]['n_quantity_sisa'];

                        var cols        = "";
                        var newRow = $("<tr>");
                            cols += '<td style="text-align:center;">'+no+'<input class="form-control input-sm" readonly type="hidden" id="baris'+a+'" name="baris'+a+'" value="'+no+'"></td>';     
                            cols += '<td><input readonly class="form-control input-sm" type="text" id="imaterial'+a+'" name="imaterial[]" value="'+imaterial+'"><input readonly class="form-control" type="hidden" id="idmaterial'+a+'" name="idmaterial[]" value="'+idmaterial+'"></td>';
                            cols += '<td><input readonly class="form-control input-sm" type="text" id="ematerial'+a+'" name="ematerial'+a+'" value="'+ematerial+'"></td>'; 
                            cols += '<td><input readonly class="form-control text-right input-sm" type="text" id="nquantity'+a+'" name="nquantity[]" value="'+nquantity+'"></td>';
                            cols += '<td><input readonly class="form-control text-right input-sm" type="text" id="nquantitysisa'+a+'" name="nquantitysisa[]" value="'+nquantitysisa+'"></td>';
                            cols += '<td><input type="text" id="nquantitymasuk'+a+'" class="form-control text-right inputitem  input-sm" autocomplete="off" name="nquantitymasuk[]" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\'value="0" onkeyup="ceksaldo('+a+');"></td>';
                            cols += '<td><input class="form-control input-sm" type="text" id="edesc'+a+'" name="edesc[]" value="" placeholder="Isi keterangan jika ada!"></td>';

                        newRow.append(cols);
                        $("#tabledatax").append(newRow);
                    }
                max();

                function formatSelection(val) {
                    return val.name;
                }

                $("#tabledatax").on("click", ".ibtnDel", function (event) {
                    $(this).closest("tr").remove();       
                });

            },
            error: function () {
                alert('Error :)');
            }
        });            
    }

    function max(){
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

    function ceksaldo(i) {
        if (parseFloat($('#nquantitymasuk'+i).val()) > parseFloat($('#nquantitysisa'+i).val())) {
            swal('Quantity Masuk tidak boleh lebih dari Quantity Pemenuhan!!!');
            $('#nquantitymasuk'+i).val($('#nquantitysisa'+i).val());
        }
        if (parseFloat($('#nquantitymasuk'+i).val()) == 0){
            swal('Quantity Masuk tidak boleh kosong atau 0');
            $('#nquantitymasuk'+i).val($('#nquantitysisa'+i).val());
        } 
    }

    function konfirm() {
        var jml = $('#jml').val();
        if (($('#ibagian').val()!='' || $('#ibagian').val()) && ($('#ipengirim').val()!='' || $('#ipengirim').val()) && ($('#ireff').val()!='' || $('#ireff').val())) {
            if(jml==0){
                swal('Isi data item minimal 1 !!!');
                return false;
            }else{
                $(this).find("td .inputitem").each(function() {
                    if ($(this).val()=='' || $(this).val()==null || $(this).val()==0) {
                        swal('Quantity Tidak Boleh Kosong Atau 0!');
                        ada = true;
                    }
                });
                for(i=0;i<=jml;i++){
                    if($("#idmaterial"+i).val()=='' || $("#ematerial"+i).val()=='' || $("#nquantitymasuk"+i).val()=='' || $("#nquantitymasuk"+i).val()== 0){
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