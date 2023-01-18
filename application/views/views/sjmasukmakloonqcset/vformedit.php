<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main');"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-3">Bagian Pembuat</label>
                        <label class="col-sm-2">Nomor Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-3">Surat Jalan Dari Supplier</label>
                        <label class="col-md-2">Tanggal Supplier</label>
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2" required="" onchange="number();">
                                 <option value="" selected>-- Pilih Bagian --</option>
                                <?php if ($bagian) {
                                    foreach ($bagian as $row):?>
                                        <option value="<?= $row->i_bagian;?>" <?php if ($row->i_bagian == $data->i_bagian) {?> selected <?php } ?>>
                                            <?= $row->e_bagian_name;?>
                                        </option>
                                    <?php endforeach; 
                                } ?>
                            </select>
                            <input type="hidden" name="ibagianold" id="ibagianold" value="<?= $data->i_bagian;?>">
                        </div>
                        <div class="col-sm-2">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" value="<?= $id;?>">
                                <input type="hidden" name="idocumentold" id="isjold" value="<?= $data->i_document;?>">
                                <input type="text" name="idocument" id="isj" required="" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number;?>" maxlength="15" class="form-control input-sm" value="<?= $data->i_document;?>" aria-label="Text input with dropdown button">
                                <!-- <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span> -->
                            </div>
                            <!-- <span class="notekode">Format : (<?= $number;?>)</span><br> -->
                            <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div> 
                        <div class="col-sm-2">
                            <input type="text" id="ddocument" name="ddocument" class="form-control input-sm date" required="" value="<?= $data->d_document;?>" readonly>
                        </div>
                         <div class="col-sm-3">
                            <input type="text" id="idocumentsup" name="idocumentsup" class="form-control input-sm" required="" value="<?= $data->i_document_supplier;?>">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="dsupplier" name="dsupplier" class="form-control input-sm date" required="" value="<?= $data->d_supplier;?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3">Partner</label>
                        <label class="col-sm-2">Tipe Makloon</label>
                        <label class="col-md-3">Nomor Referensi</label>
                        <label class="col-md-4">Keterangan</label>
                        <div class="col-sm-3">
                            <select name="ipartner" id="ipartner" class="form-control select2" required="">
                                <option value="<?= $data->id_supplier;?>"><?= $data->e_supplier_name;?></option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <select name="itype" id="itype" class="form-control select2" required="" onchange="number();">
                                 <?php if ($type) {
                                    foreach ($type as $row):?>
                                        <option value="<?= $row->id;?>"  <?php if ($row->id == $data->id_type_makloon) {?> selected <?php } ?>>
                                            <?= $row->e_type_makloon_name;?>
                                        </option>
                                    <?php endforeach; 
                                } ?>
                            </select>
                            <input type="hidden" name="itypeold" id="itypeold" value="<?= $data->id_type_makloon;?>">
                        </div>
                        <div class="col-sm-3" id="eks">
                            <select name="ireffeks" id="ireffeks" multiple="multiple" class="form-control select2">
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <textarea type="text" name="eremarkh" placeholder="Isi keterangan jika ada!!!" class="form-control input-sm" maxlength="250"><?= $data->e_remark;?></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <?php if ($data->i_status == '1' || $data->i_status == '3' || $data->i_status == '7') {?>
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
<?php $i = 0; if ($datadetail) {?>
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
                        <th class="text-center" style="width: 15%;">No Dokumen Keluar</th>
                        <th class="text-center" style="width: 35%;">Nama Panel</th>
                        <th class="text-center" style="width: 15%;">Warna</th>
                        <th class="text-center" style="width: 10%;">Qty Kirim</th>
                        <th class="text-center" style="width: 10%;">Qty Terima</th>
                        <th class="text-center" style="width: 15%;">Ket</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($datadetail as $key) {
                        $i++;
                        ?>
                    <tr>
                     <td class="text-center"><spanx id="snum<?= $i ;?>"><?= $i ;?></spanx></td>
                     <td style="text-align: center">
                        <input type="text" class="form-control  input-sm" readonly id="ireferensi<?= $i ;?>" name="ireferensi<?= $i ;?>" value="<?=  $key->i_document ;?>">
                        <input hidden class="form-control  input-sm" readonly id="id_document<?= $i ;?>" name="id_document<?= $i ;?>" value="<?=  $key->id_document_reff ;?>">
                        <input hidden class="form-control  input-sm" readonly id="id_panel_item<?= $i ;?>" name="id_panel_item<?= $i ;?>" value="<?=  $key->id_panel ;?>">
                        <input hidden class="form-control  input-sm" readonly id="idmarker<?= $i ;?>" name="idmarker<?= $i ;?>" value="<?=  $key->id_marker ;?>">
                    </td>
                    <td class="d-flex">
                        <input class="form-control  input-sm w-75" readonly id="iproduct<?= $i ;?>" name="iproduct<?= $i ;?>" value="<?= $key->i_panel;?>">
                        <input class="form-control  input-sm w-25" readonly id="marker<?= $i ;?>" name="marker<?= $i ;?>" value="<?= $key->e_marker_name ;?>">
                    </td>
                    <td>
                        <input readonly class="form-control  input-sm" id="ecolor<?= $i ;?>" name="ecolor<?= $i ;?>" value="<?= $key->e_color_name ;?>">
                    </td>
                    <td><input class="form-control  input-sm text-right" readonly id="sisa<?= $i ;?>" name="sisa<?= $i ;?>" value="<?= $key->keluar ;?>"></td>
                    <td><input class="form-control  input-sm text-right" id="nquantity<?= $i ;?>" placeholder="0" name="nquantity<?= $i ;?>" onblur='if(this.value==""){this.value="0";}' onfocus='if(this.value=="0"){this.value="";}' value="<?= $key->masuk ;?>" onkeypress="return hanyaAngka(event);" onkeyup="ceksaldo(<?= $i ;?>);"></td>
                    <td><input class="form-control  input-sm" id="eremark<?= $i ;?>" name="eremark<?= $i ;?>" value="<?= $key->e_remark ;?>"></td>
                   </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<input type="hidden" name="jml" id="jml" value ="<?= $i ;?>">
<?php } ?>
</form>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#isj').mask('SS-0000-000000S');
        $('.select2').select2();
        /*Tidak boleh kurang dari hari ini*/
        showCalendar('.date',null,0);

        $('#itype').select2({
            placeholder: 'Type Makloon',
        }).change(function() {
            $("#tabledatax tr:gt(0)").remove();
            $("#jml").val(0);
            $("#ireffeks").val("");
            $("#ireffeks").html("");
            number();
        });

        $('#ipartner').select2({
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
            $("#tabledatax tr:gt(0)").remove();
            $("#jml").val(0);
            $("#ireffeks").val("");
            $("#ireffeks").html("");
        });;

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
                        ipartner : $('#ipartner').val(),
                        itype : $('#itype').val()
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
                    cols += '<td style="text-align: center"><input type="text" class="form-control" readonly id="ireferensi'+no+'" name="ireferensi'+no+'" value="'+data['detail'][a]['i_document']+'"><input hidden class="form-control" readonly id="id_document'+no+'" name="id_document'+no+'" value="'+data['detail'][a]['id']+'"><input hidden class="form-control" readonly id="id_panel_item'+no+'" name="id_panel_item'+no+'" value="'+data['detail'][a]['id_panel']+'"><input hidden class="form-control" readonly id="idmarker'+no+'" name="idmarker'+no+'" value="'+data['detail'][a]['id_marker']+'"></td>';
                    cols += '<td class="d-flex"><input class="form-control w-75" readonly id="iproduct'+no+'" name="iproduct'+no+'" value="'+data['detail'][a]['i_panel']+ '"> <input class="form-control w-25" readonly id="marker'+no+'" name="marker'+no+'" value="'+data['detail'][a]['e_marker_name']+'"></td>';
                    cols += '<td><input type="hidden" id="icolor'+no+'" name="icolor'+no+'" value="'+data['detail'][a]['i_color']+'"><input readonly class="form-control" id="ecolor'+no+'" name="ecolor'+no+'" value="'+data['detail'][a]['e_color_name']+'"></td>';
                    cols += '<td><input class="form-control text-right" readonly id="sisa'+no+'" name="sisa'+no+'" value="'+data['detail'][a]['n_quantity']+'"></td>';
                    cols += '<td><input class="form-control text-right" id="nquantity'+no+'" placeholder="0" name="nquantity'+no+'" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeypress="return hanyaAngka(event);" onkeyup="ceksaldo('+no+');"></td>';
                    cols += '<td><input class="form-control" id="eremark'+no+'" name="eremark'+no+'" value=""></td>';
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

    $('#cancel').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'1','<?= $dfrom."','".$dto;?>');
    });

    $('#hapus').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'5','<?= $dfrom."','".$dto;?>');
    });


    function ceksaldo(i) {
        if (parseFloat($('#nquantity'+i).val()) > parseFloat($('#sisa'+i).val())) {
            swal('Qty terima tidak boleh lebih dari qty Kirim!!!');
            $('#nquantity'+i).val($('#sisa'+i).val());
        }
    }

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#send").attr("hidden", false);

    });

    function konfirm() {
        var jml = $('#jml').val();
        if ($('#ireffeks').val()!='') {
            if(jml==0){
                swal('Isi data item minimal 1 !!!');
                return false;
            }else{
                var jumlah = 0;
                for(i=1;i<=jml;i++){

                    if ($('#nquantity'+i).val() != '' && $('#nquantity'+i).val() != null ) {
                        jumlah = jumlah+parseFloat($('#nquantity'+i).val());
                    }
                    
                }

                if(jumlah == 0){
                    swal('Data item masih ada yang salah !!!');
                    return false;
                }else{
                    return true;
                } 
            }
        }else{
            swal('Data Header Masih Ada yang Kosong!');
            return false;
        }
    }

    /**
     * Input Kode Manual
     */

    $('#ceklis').click(function(event) {
        if($('#ceklis').is(':checked')){
            $("#isj").attr("readonly", false);
        }else{
            $("#isj").attr("readonly", true);
            $("#ada").attr("hidden", true);
            number();
        }
    });

    /**
     * Running Number
     */

    function number() {
        if (($('#ibagian').val() == $('#ibagianold').val()) && ($('#itype').val() == $('#itypeold').val())) {
            $('#isj').val($('#isjold').val());
        }else{
            $.ajax({
                type: "post",
                data: {
                    'tgl' : $('#ddocument').val(),
                    'itype' : $('#itype').val(),
                    'ibagian' : $('#ibagian').val(),
                },
                url: '<?= base_url($folder.'/cform/number'); ?>',
                dataType: "json",
                success: function (data) {
                    $('#isj').val(data);
                },
                error: function () {
                    swal('Error :)');
                }
            });
        }
        
    }

    $( "#isj" ).keyup(function() {
        $.ajax({
            type: "post",
            data: {
                'kode' : $(this).val(),
                'kodeold' : $('#isjold').val(),
                'ibagian' : $('#ibagian').val(),
                'itype' : $('#itype').val(),
                'itypeold' : $('#itypeold').val(),
            },
            url: '<?= base_url($folder.'/cform/cekkodeedit'); ?>',
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
</script>