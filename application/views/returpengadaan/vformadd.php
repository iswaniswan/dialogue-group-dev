<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i>  <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i><?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-3">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-3">Tanggal Dokumen</label>
                        <label class="col-md-3">Pengirim</label>
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
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" name="idocument" id="iretur" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="SJ-2010-000001" maxlength="15" class="form-control input-sm" value="<?= $number;?>" aria-label="Text input with dropdown button">
                                <!-- <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span> -->
                            </div>
                            <span class="notekode">Format : (<?= $number;?>)</span><br>
                            <span class="notekode" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-3">
                             <input type="text" id= "ddocument" name="ddocument" class="form-control input-sm date" value="<?php echo date("d-m-Y"); ?>" required="" placeholder="<?=date('d-m-Y');?>"readonly>
                        </div>
                        <div class="col-sm-3">
                            <select name="itujuan" id="itujuan" class="form-control select2">
                                <!-- <?php if ($tujuan) {
                                    foreach ($tujuan as $row):?>
                                        <option value="<?= $row->i_bagian;?>">
                                            <?= $row->e_bagian_name;?>
                                        </option>
                                    <?php endforeach; 
                                } ?> -->
                                <?php if ($tujuan) {
                                    $group = "";
                                    foreach ($tujuan as $row) : ?>
                                    <?php if ($group!=$row->name) {?>
                                        </optgroup>
                                        <optgroup label="<?= strtoupper(str_replace(".","",$row->name));?>">
                                    <?php }
                                    $group = $row->name;
                                    ?>
                                        <option value="<?= "$row->id_company|$row->i_bagian"; ?>">
                                            <?= $row->e_bagian_name; ?>
                                        </option>
                                <?php endforeach;
                                } ?>
                            </select>
                        </div>  
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Nomor Referensi</label>
                        <label class="col-md-3">Tanggal Referensi</label>
                        <label class="col-md-6">Keterangan</label>
                        <div class="col-sm-3">
                            <select name="ireff" id="ireff" class="form-control select2" onchange="getdataitem(this.value);"> 
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id= "dreferensi" name="dreferensi" class="form-control input-sm" value="" required="" placeholder="<?=date('d-m-Y');?>" readonly>
                        </div>
                        <div class="col-sm-6">
                            <textarea id= "eremark" name="eremark" class="form-control" placeholder="Isi keterangan jika ada!"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <button type="submit" id="submit" class="btn btn-success btn-block btn-sm mr-2" onclick="return konfirm();"><i class="fa fa-save mr-2" ></i>Simpan</button>
                        </div>
                        <div class="col-sm-4">
                            <button type="button" class="btn btn-inverse btn-block btn-sm mr-2" onclick="show('<?= $folder; ?>/cform/','#main'); return false;"><i class="ti-arrow-circle-left mr-2"></i>Kembali</button>
                        </div>
                        <div class="col-sm-4">
                            <button type="button" disabled="true" id="send" onclick="changestatus('<?= $folder;?>',$('#id').val(),'2');" class="btn btn-primary btn-block btn-sm mr-2"><i class="fa fa-paper-plane-o mr-2"></i>Send</button>
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
            <table id="tabledatax" class="table color-table success-table table-bordered class" width="100%">
                <thead> 
                    <tr>
                        <th class="text-center" width="3%;">No</th>
                        <th width="12%;">Kode Barang</th>
                        <th width="25%;">Nama Barang</th>               
                        <th class="text-right" width="10%">Qty (Pengembalian)</th>
                        <!-- <th class="text-right" width="10%">Qty Sisa Retur</th> -->
                        <th class="text-right" width="10%">Qty</th>
                        <th class="text-center">Keterangan</th>
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
        showCalendar('.date',null,0);
        
        // $('#iretur').mask('SS-0000-000000S');
        //memanggil function untuk penomoran dokumen
        number();

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
                        ipengirim: $('#itujuan').val(),
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

    $( "#iretur" ).keyup(function() {
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
                    $('#iretur').val(data);
                },
                error: function () {
                    swal('Error :)');
                }
            });
    }

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
                $('#iretur').val(data);
            },
            error: function () {
                swal('Error :)');
            }
        });
    });

    $('#ceklis').click(function(event) {
        if($('#ceklis').is(':checked')){
            $("#iretur").attr("readonly", false);
        }else{
            $("#iretur").attr("readonly", true);
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
        $("#send").attr("disabled", false);
    });

    function getdataitem(ireff) {
        var idreff   = $('#ireff').val();
        if(idreff == '' || idreff == null){
            //alert("A");
            $("#tabledatax tr:gt(0)").remove();
            $("#jml").val(0);
        }else{ 
            $.ajax({
                type: "post",
                data: {
                    'idreff': idreff,
                },
                url: '<?= base_url($folder.'/cform/getdataitem'); ?>',
                dataType: "json",
                success: function (data) {  
                    $('#jml').val(data['jmlitem']);
                    $("#tabledatax tbody").remove();
                    $("#detail").attr("hidden", false);

                    var dref =  data['datahead']['d_document'];
                    $("#dreferensi").val(dref);
                    group = "";
                    i = 0;
                    for (let a = 0; a < data['jmlitem']; a++) {
                        i++;
                        var idproduct = data['dataitem'][a]['id_product_wip'];
                        var newRow      = $("<tr>");
                        var cols        = "";
                        /* var cols1       = ""; */

                        /* if(group == ""){
                            cols1 += '<td colspan="3"><input type="text" id="iproduct'+a+'" class="form-control input-sm" name="iproduct'+a+'" value="'+data['dataitem'][a]['i_product_wip']+' - '+data['dataitem'][a]['e_product_wipname']+' - '+data['dataitem'][a]['e_color_name']+'" readonly><input type="hidden" id="idproduct'+a+'" class="form-control" name="idproduct'+a+'" value="'+data['dataitem'][a]['id_product_wip']+'" readonly></td>';
                            cols1 += '<td><input class="form-control qty input-sm text-right" autocomplete="off" type="text"  id="nquantitywipkeluar'+a+'" name="nquantitywipkeluar'+a+'" value="'+data['dataitem'][a]['n_quantity_wip']+'" onkeyup="angkahungkul(this);" readonly></td>';
                            cols1 += '<td><input class="form-control qty input-sm text-right" autocomplete="off" type="text"  id="nquantitywipsisa'+a+'" name="nquantitywipsisa'+a+'" value="'+data['dataitem'][a]['n_quantity_wip_sisa']+'" onkeyup="angkahungkul(this);" readonly></td>';
                            cols1 += '<td><input class="form-control qty input-sm text-right inputitem" autocomplete="off" type="text" id="nquantitywipmasuk'+a+'" name="nquantitywipmasuk'+a+'" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="'+data['dataitem'][a]['n_quantity_wip_sisa']+'" onkeyup="ceksaldo('+a+');"></td>';
                            cols1 += '<td></td>';
                        }else{
                            if(group != idproduct){
                                cols1 += '<td colspan="3"><input type="text" id="iproduct" class="form-control input-sm" name="iproduct'+a+'" value="'+data['dataitem'][a]['i_product_wip']+' - '+data['dataitem'][a]['e_product_wipname']+' - '+data['dataitem'][a]['e_color_name']+'" readonly><input type="hidden" id="idproduct'+a+'" class="form-control" name="idproduct'+a+'" value="'+data['dataitem'][a]['id_product_wip']+'" readonly></td>';
                                cols1 += '<td><input class="form-control qty input-sm text-right" autocomplete="off" type="text"  id="nquantitywipkeluar'+a+'" name="nquantitywipkeluar'+a+'" value="'+data['dataitem'][a]['n_quantity_wip']+'" readonly onkeyup="angkahungkul(this);"></td>';
                                cols1 += '<td><input class="form-control qty input-sm text-right" autocomplete="off" type="text"  id="nquantitywipsisa'+a+'" name="nquantitywipsisa'+a+'" value="'+data['dataitem'][a]['n_quantity_wip_sisa']+'" readonly onkeyup="angkahungkul(this);"></td>';
                                cols1 += '<td><input class="form-control qty input-sm text-right inputitem" autocomplete="off" type="text" id="nquantitywipmasuk'+a+'" name="nquantitywipmasuk'+a+'" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="'+data['dataitem'][a]['n_quantity_wip_sisa']+'" onkeyup="ceksaldo('+a+');"></td>';
                                cols1 += '<td></td>';
                                //i = 1;
                            }
                        } */
                        /* newRow.append(cols1);
                        $("#tabledatax").append(newRow); */
                        /* group = idproduct; */
                        var newRow = $("<tr>");
                        cols += '<td class="text-center">'+i+'</td>';
                        cols += '<td><input type="hidden" name="idproductwip[]" id="idproductwip'+a+'" value="'+data['dataitem'][a]['id_product_wip']+'">';
                        /* cols += '<input type="hidden" class="idmaterial" name="idmaterial[]" id="idmaterial'+a+'" value="'+data['dataitem'][a]['id_material']+'">'; */
                        cols += '<input class="form-control input-sm" readonly type="text" value="'+data['dataitem'][a]['i_product_wip']+'"></td>';
                        cols += '<td><input class="form-control input-sm" readonly type="text" value="'+data['dataitem'][a]['e_product_wipname']+'"></td>';
                        /* cols += '<td><input class="form-control qty input-sm text-right" autocomplete="off" type="text" name="nquantitymaterialkeluar[]" id="nquantitymaterialkeluar'+a+'" readonly value="'+data['dataitem'][a]['n_quantity_material']+'"></td>';
                        cols += '<td><input class="form-control qty input-sm text-right" autocomplete="off" type="text" name="nquantitymaterialsisa[]" id="nquantitymaterialsisa'+a+'" readonly value="'+data['dataitem'][a]['n_sisa_material']+'"></td>';
                        cols += '<td><input class="form-control qty input-sm text-right inputitem" autocomplete="off" type="text" name="nquantitymaterialmasuk[]" id="nquantitymaterialmasuk'+a+'" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="'+data['dataitem'][a]['n_sisa_material']+'" onkeyup="ceksaldo('+a+');"></td>'; */
                        cols += '<td><input class="form-control qty input-sm text-right" autocomplete="off" type="text"  id="nquantitywipkeluar'+a+'" name="nquantitywipkeluar[]" value="'+data['dataitem'][a]['n_quantity_wip']+'" readonly onkeyup="angkahungkul(this);"></td>';
                        cols += '<td hidden><input class="form-control qty input-sm text-right" autocomplete="off" type="text"  id="nquantitywipsisa'+a+'" name="nquantitywipsisa[]" value="'+data['dataitem'][a]['n_quantity_wip_sisa']+'" readonly onkeyup="angkahungkul(this);"></td>';
                        cols += '<td><input class="form-control qty input-sm text-right inputitem" autocomplete="off" type="text" id="nquantitywipmasuk'+a+'" name="nquantitywipmasuk[]" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="'+data['dataitem'][a]['n_quantity_wip_sisa']+'" onkeyup="ceksaldo('+a+');"></td>';
                        cols += '<td colspan="2"><input class="form-control input-sm" type="text" name="edesc[]" id="edesc'+a+'" value="" placeholder="Isi keterangan jika ada!"></td></tr>';
                        newRow.append(cols);
                        $("#tabledatax").append(newRow);
                    }
                    max_tgl();
                },
                error: function () {
                    alert('Error :)');
                }
            });
        }
    } 

    function ceksaldo(i) {
        //alert(i);
        if (parseFloat($('#nquantitywipmasuk'+i).val()) > parseFloat($('#nquantitywipsisa'+i).val())) {
            swal('Quantity tidak boleh lebih dari Quantity Sisa Retur!!!');
            $('#nquantitywipmasuk'+i).val($('#nquantitywipsisa'+i).val());
        }
        if (parseFloat($('#nquantitymaterialmasuk'+i).val()) > parseFloat($('#nquantitymaterialsisa'+i).val())) {
            swal('Quantity tidak boleh lebih dari Quantity Sisa Retur!!!');
            $('#nquantitymaterialmasuk'+i).val($('#nquantitymaterialsisa'+i).val());
        }
        if(parseFloat($('#nquantitywipmasuk'+i).val()) == '0'){
            swal('Quantity Tidak Boleh 0 atau Kosong');
            $("#nquantitywipmasuk"+i).val($('#nquantitywipsisa'+i).val());
        } 
        if(parseFloat($('#nquantitymaterialmasuk'+i).val()) == '0'){
            swal('Quantity Tidak Boleh 0 atau Kosong');
            $("#nquantitymaterialmasuk"+i).val($('#nquantitymaterialsisa'+i).val());
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