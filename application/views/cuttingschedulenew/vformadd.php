  <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?=$dfrom;?>/<?=$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?= $title_list; ?></a>
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
                      <!--   <label class="col-md-4">Perusahaan</label> -->
                        <label class="col-md-4">Nomor Referensi</label>
                        <label class="col-md-4">Tanggal Referensi</label>
                        <label class="col-md-4">Keterangan</label>
                      <!--   <div class="col-sm-4">
                            <select name="ipengirim" id="ipengirim" class="form-control select2">
                            </select>
                        </div> -->
                        <div class="col-sm-4">
                            <select name="ireff" id="ireff" class="form-control select2" onchange="getdataitem(this.value);"> 
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id= "dreferensi" name="dreferensi" class="form-control input-sm" value="" required="" placeholder="<?=date('d-m-Y');?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <textarea id= "eremark" name="eremark" class="form-control input-sm" placeholder="Isi keterangan jika ada!"></textarea>
                        </div>
                    </div>
                    <!-- <div class="form-group row">
                       
                        
                    </div> -->
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm mr-2" onclick="return konfirm();"><i class="fa fa-save mr-2"></i>Simpan</button>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm mr-2" onclick="show('<?= $folder; ?>/cform/index/<?=$dfrom;?>/<?=$dto;?>','#main'); return false;"><i class="ti-arrow-circle-left mr-2"></i>Kembali</button>
                            <button type="button" hidden="true" id="send" onclick="changestatus('<?= $folder;?>',$('#kode').val(),'2');" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-paper-plane-o mr-2"></i>Send</button>
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
                        <th style="text-align:center;">Qty</th>
                        <th style="text-align:center;">Tgl Schedule</th>
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
        showCalendar('.date');
        
        $('#idocument').mask('SS-0000-000000S');
        //memanggil function untuk penomoran dokumen
        number();
        // $('#ipengirim').select2({
        //     placeholder: 'Pilih Perusahaan',
        //     allowClear: true,
        //     ajax: {
        //         url: '<?= base_url($folder.'/cform/bagianpengirim'); ?>',
        //         dataType: 'json',
        //         delay: 250,
        //         data: function (params) {
        //             var query = {
        //                 q: params.term,
        //             }
        //             return query;
        //         },
        //         processResults: function (data) {
        //             return {
        //                 results: data
        //             };
        //         },
        //         cache: false
        //     }
        // });

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
                        // iasal : $('#ipengirim').val(),
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
        $.ajax({
            type: "post",
            data: {
                'tgl' : $(this).val(),
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

    function getdataitem(ireff) {
        var idreff = $('#ireff').val();
        // var ipengirim = $('#ipengirim').val();
        if (idreff) {
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
                            //var no = a+1;
                            //count=$('#tabledatax tr').length;   
                            var idproduct   = data['dataitem'][a]['id_product_wip'];
                            var newRow      = $("<tr>");
                            var cols        = "";
                            var cols1       = "";
                            if(group == ""){
                                cols1 += '<td colspan="3"><input type="text" id="iproduct'+a+'" class="form-control input-sm" name="iproduct'+a+'" value="'+data['dataitem'][a]['i_product_wip']+' - '+data['dataitem'][a]['e_product_wipname']+' - '+data['dataitem'][a]['e_color_name']+'" readonly><input type="hidden" id="idproduct'+a+'" class="form-control" name="idproduct'+a+'" value="'+data['dataitem'][a]['id_product_wip']+'" readonly></td>';
                                // cols1 += '<td><input style="width:100px;" class="form-control qty input-sm text-right" autocomplete="off" type="text"  id="nquantitywip'+a+'" name="nquantitywip'+a+'" value="'+data['dataitem'][a]['n_quantity_wip']+'" readonly></td>';
                                cols1 += '<td><input style="width:100px;" class="form-control qty input-sm text-right" autocomplete="off" type="text"  id="nquantitywipsisa'+a+'" name="nquantitywipsisa'+a+'" value="'+data['dataitem'][a]['n_quantity_wip_sisa']+'" readonly></td>';
                                // cols1 += '<td><input style="width:100px;" class="form-control qty input-sm text-right inputitem" autocomplete="off" type="text" id="nquantitywipmasuk'+a+'" name="nquantitywipmasuk'+a+'" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="'+data['dataitem'][a]['n_quantity_wip_sisa']+'" onkeyup="validasi('+a+');"></td>';
                                cols1 += '<td></td>';
                            }else{
                                if(group != idproduct){
                                    cols1 += '<td colspan="3"><input type="text" id="iproduct'+a+'" class="form-control input-sm" name="iproduct'+a+'" value="'+data['dataitem'][a]['i_product_wip']+' - '+data['dataitem'][a]['e_product_wipname']+' - '+data['dataitem'][a]['e_color_name']+'" readonly><input type="hidden" id="idproduct'+a+'" class="form-control" name="idproduct'+a+'" value="'+data['dataitem'][a]['id_product_wip']+'" readonly></td>';
                                    // cols1 += '<td><input style="width:100px;" class="form-control qty input-sm text-right" autocomplete="off" type="text"  id="nquantitywip'+a+'" name="nquantitywip'+a+'" value="'+data['dataitem'][a]['n_quantity_wip']+'" readonly></td>';
                                    cols1 += '<td><input style="width:100px;" class="form-control qty input-sm text-right" autocomplete="off" type="text"  id="nquantitywipsisa'+a+'" name="nquantitywipsisa'+a+'" value="'+data['dataitem'][a]['n_quantity_wip_sisa']+'" readonly></td>';
                                    // cols1 += '<td><input style="width:100px;" class="form-control qty input-sm text-right inputitem" autocomplete="off" type="text" id="nquantitywipmasuk'+a+'" name="nquantitywipmasuk'+a+'" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="'+data['dataitem'][a]['n_quantity_wip_sisa']+'" onkeyup="validasi('+a+');"></td>';
                                    cols1 += '<td></td>';
                                    //i = 1;
                                }
                            }
                            newRow.append(cols1);
                            $("#tabledatax").append(newRow);
                            group = idproduct;
                            var newRow = $("<tr>");
                            cols += '<td class="text-center">'+(a+1)+'</td>';
                            cols += '<td><input type="hidden" name="id_fccutting_item[]" id="id_fccutting_item'+a+'" value="'+data['dataitem'][a]['id']+'"><input type="hidden" name="idproductwip[]" id="idproductwip'+a+'" value="'+data['dataitem'][a]['id_product_wip']+'">';
                            cols += '<input type="hidden" class="idmaterial" name="idmaterial[]" id="idmaterial'+a+'" value="'+data['dataitem'][a]['id_material']+'">';
                            cols += '<input style="width:120px;" class="form-control input-sm" readonly type="text" value="'+data['dataitem'][a]['i_material']+'"></td>';
                            cols += '<td><input style="width:510px;" class="form-control input-sm" readonly type="text " value="'+data['dataitem'][a]['e_material_name']+'"></td>';
                            // cols += '<td><input style="width:100px;" class="form-control qty input-sm text-right" autocomplete="off" type="text" name="nquantitybahan[]" id="nquantitybahan'+a+'" readonly value="'+data['dataitem'][a]['n_quantity']+'"></td>';
                            cols += '<td><input style="width:100px;" class="form-control qty input-sm text-right" autocomplete="off" type="text" name="nquantitybahansisa[]" id="nquantitybahansisa'+a+'" readonly value="'+data['dataitem'][a]['n_quantity_sisa']+'"></td>';
                            cols += '<td><input style="width:100px;" type="text" readonly id="d_schedule'+a+'" class="form-control input-sm date" name="d_schedule[]"></td>';
                            // cols += '<td><input style="width:100px;" class="form-control qty input-sm text-right inputitem" autocomplete="off" type="text" name="nquantitybahanmasuk[]" id="nquantitybahanmasuk'+a+'" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="'+data['dataitem'][a]['n_quantity_sisa']+'" onkeyup="validasi('+a+');"></td>';
                            cols += '<td colspan="2"><input style="width:180px;" class="form-control input-sm" type="text" name="edesc[]" id="edesc'+a+'" value="'+data['dataitem'][a]['e_remark']+'" placeholder="Isi keterangan jika ada!"></td></tr>';
                            newRow.append(cols);
                            $("#tabledatax").append(newRow);
                            showCalendar('.date',0);
                    }

                    function formatSelection(val) {
                        return val.name;
                    }

                    $("#tabledatax").on("click", ".ibtnDel", function (event) {
                        $(this).closest("tr").remove();       
                    });
                    max_tgl();
                },
                error: function () {
                    alert('Error :)');
                }
            });
        }
    } 

    function max_tgl() {
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

    function validasi(id){
        nquantityma            = $("#nquantitywipsisa"+id).val();
        nquantitymasuk         = $("#nquantitywipmasuk"+id).val();
        nquantitymaterial      = $("#nquantitybahansisa"+id).val();
        nquantitymasukmaterial = $("#nquantitybahanmasuk"+id).val();
        
        if(parseFloat(nquantitymasuk)>parseFloat(nquantityma)){
            swal('Quantity Masuk Tidak Boleh Lebih Dari Quantity Keluar');
            $("#nquantitywipmasuk"+id).val(nquantityma);
        }
        if (parseFloat(nquantitymasukmaterial)>parseFloat(nquantitymaterial)){
            swal('Quantity Masuk Tidak Boleh Lebih Dari Quantity Keluar');
            $("#nquantitybahanmasuk"+id).val(nquantitymaterial);
        }

        if(parseFloat(nquantitymasuk) == '0'){
            swal('Quantity Tidak Boleh 0 atau Kosong');
            $("#nquantitywipmasuk"+id).val(nquantityma);
        } 
        if(parseFloat(nquantitymasukmaterial) == '0'){
            swal('Quantity Tidak Boleh 0 atau Kosong');
            $("#nquantitybahanmasuk"+id).val(nquantitymaterial);
        }
    }

    $("form").submit(function (event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        // $("select").attr("disabled", true);
        $("#addrow").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#send").attr("hidden", false);
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
