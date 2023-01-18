<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-3">Bagian Pembuat</label>
                        <label class="col-sm-3">Nomor Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-sm-4">Partner</label>
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2" required="" onchange="number();">
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
                                <input type="text" name="idocument" id="iretur" required="" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number;?>" maxlength="15" class="form-control input-sm" value="" aria-label="Text input with dropdown button">
                                <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span>
                            </div>
                            <span class="notekode">Format : (<?= $number;?>)</span><br>
                            <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div> 
                        <div class="col-sm-2">
                            <input type="text" id="ddocument" name="ddocument" class="form-control input-sm date" required="" value="<?= date('d-m-Y');?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <select name="itujuan" id="itujuan" class="form-control select2" required="">
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">                        
                        <label class="col-md-6">Nomor Referensi</label>
                        <label class="col-md-6">Keterangan</label>
                        <div class="col-sm-6" id="eks">
                            <select name="ireffeks" id="ireffeks" multiple="multiple" class="form-control select2" disabled="true">
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <textarea type="text" name="eremarkh" placeholder="Isi keterangan jika ada!!!" class="form-control input-sm" maxlength="250"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                         <div class="col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return konfirm();"><i class="fa fa-save"  ></i>&nbsp;&nbsp;Simpan</button>&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                            <button type="button" hidden="true" id="send" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                        </div>
                    </div>
                </div>    
        </div>
    </div>
</div>
</div>
<div class="white-box" id="detail">
    <div class="col-sm-3">
        <h3 class="box-title m-b-0">Detail Barang</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 3%;">No</th>
                        <th class="text-center" style="width: 20%;">No Dokumen Masuk</th>
                        <th class="text-center" style="width: 30%;">Nama Barang</th>
                        <th class="text-center" style="width: 15%;">Warna</th>
                        <th class="text-center" style="width: 10%;">Quantity</th>
                        <th class="text-center" style="width: 10%;">Quantity Retur</th>
                        <th class="text-center" style="width: 15%;">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<input type="hidden" name="jml" id="jml" value ="0">
</form>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#iretur').mask('SS-0000-000000S');
        $('.select2').select2();
        showCalendar('.date',1830, 0);
        number();
    });

    $('#ibagian, #ddocument').change(function(event) {
        number();
    });

    $( "#iretur" ).keyup(function() {
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
            $("#iretur").attr("readonly", false);
        }else{
            $("#iretur").attr("readonly", true);
            $("#ada").attr("hidden", true);
            number();
        }
    });

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

    $(document).ready(function () {
        $('#itujuan').select2({
            placeholder: 'Pilih Partner',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/partner/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q : params.term,
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
        }).change(function(event) {
            $("#ireffeks").attr("disabled", false);
            $("#tabledatax tr:gt(0)").remove();
            $("#jml").val(0);
            $("#ireffeks").val("");
            $("#ireffeks").html("");
        });

        $('#ireffeks').select2({
            placeholder: 'Cari No Referensi',
            width: '100%',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/refeksternal'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                        ipartner : $('#itujuan').val(),
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
    
    $("#ireffeks").change(function() {
        $("#ireffeks").val($(this).val());
        $("#tabledatax tr:gt(0)").remove();       
        $("#jml").val(0);
        $.ajax({
            type: "post",
            data: {
                'id'  : $(this).val(),
            },
            url: '<?= base_url($folder.'/cform/getdetailrefeks'); ?>',
            dataType: "json",
            success: function (data) {
                $('#tabledatax').attr('hidden', false);
                $('#jml').val(data['detail'].length);
                for (let a = 0; a < data['detail'].length; a++) {
                    var no = a+1;
                    var cols = "";
                    var newRow = $("<tr>");
                    cols += '<td style="text-align: center">'+no+'</td>';
                    cols += '<td style="text-align: center"><input style="width:250px;" type="text" class="form-control" readonly id="ireferensi'+no+'" name="ireferensi'+no+'" value="'+data['detail'][a]['i_document']+'"><input hidden class="form-control" readonly id="id_document'+no+'" name="id_document'+no+'" value="'+data['detail'][a]['id']+'"><input hidden class="form-control" readonly id="id_product'+no+'" name="id_product'+no+'" value="'+data['detail'][a]['id_product']+'"></td>';
                    cols += '<td><input style="width:400px;" class="form-control" readonly id="iproduct'+no+'" name="iproduct'+no+'" value="'+data['detail'][a]['i_product_base']+ ' - ' +data['detail'][a]['e_product_basename']+'"></td>';
                    cols += '<td><input type="hidden" id="icolor'+no+'" name="icolor'+no+'" value="'+data['detail'][a]['i_color']+'"><input style="width:150px;" readonly class="form-control" id="ecolor'+no+'" name="ecolor'+no+'" value="'+data['detail'][a]['e_color_name']+'"></td>';
                    cols += '<td><input style="width:100px;" class="form-control text-right" readonly id="sisa'+no+'" name="sisa'+no+'" value="'+data['detail'][a]['n_quantity_sisa']+'"></td>';
                    cols += '<td><input style="width:100px;" class="form-control text-right inputitem" id="nquantity'+no+'" name="nquantity'+no+'" value="'+data['detail'][a]['n_quantity_sisa']+'" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' onkeypress="return hanyaAngka(event);" onkeyup="ceksaldo('+no+');"></td>';
                    cols += '<td><input style="width:300px;" class="form-control" id="eremark'+no+'" name="eremark'+no+'" value=""></td>';
                    newRow.append(cols);
                    $("#tabledatax").append(newRow);
                }
            },
            error: function () {
                swal('Data kosong :)');
            }
        });
    });

    $('#send').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'2','<?= $dfrom."','".$dto;?>');
    });

    function ceksaldo(i) {
        if (parseFloat($('#nquantity'+i).val()) > parseFloat($('#sisa'+i).val())) {
            swal('Quantity Retur tidak boleh lebih dari Quantity!!!');
            $('#nquantity'+i).val($('#sisa'+i).val());
        }
        if (parseFloat($('#nquantity'+i).val()) == 0 || parseFloat($('#nquantity'+i).val()) == null) {
            swal('Quantity Retur tidak boleh 0 atau kosong!!!');
            $('#nquantity'+i).val($('#sisa'+i).val());
        }
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