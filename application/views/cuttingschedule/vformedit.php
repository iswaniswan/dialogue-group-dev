<!-- <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?> -->
<form>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-3">Bagian Pembuat</label>
                        <label class="col-sm-3">Nomor Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-4">Keterangan</label>
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2" required="" onchange="number();">
                                <?php if ($bagian) {
                                    foreach ($bagian as $row):?>
                                        <option value="<?= $row->i_bagian;?>" <?php if ($row->i_bagian == $data->i_bagian) {?> selected <?php } ?>>
                                            <?= $row->e_bagian_name;?>
                                        </option>
                                    <?php endforeach; 
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" value="<?= $data->id;?>">
                                <input type="hidden" name="ischeduleold" id="ischeduleold" value="<?= $data->i_document;?>">
                                <input type="text" name="ischedule" id="ischedule" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number;?>" maxlength="15" class="form-control input-sm" value="<?= $data->i_document;?>" aria-label="Text input with dropdown button">
                                <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span>
                            </div>
                            <span class="notekode">Format : (<?= $number;?>)</span><br>
                            <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="ddocument" name="ddocument" class="form-control input-sm date" maxlength="5"  value="<?= date('d-m-Y', strtotime($data->d_document));?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <textarea type="text" name="eremarkh" placeholder="Isi keterangan jika ada!!!" class="form-control input-sm" maxlength="250"><?= $data->e_remark;?></textarea>
                        </div>
                    </div>  
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <?php if ($data->i_status == '1' || $data->i_status == '3' || $data->i_status == '7') {?>
                                <button type="button" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return konfirm();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Update</button>&nbsp;
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
<?php $i = 0; if ($datadetail) {?>
    <div class="white-box" id="detail">
        <div class="col-sm-3">
            <h3 class="box-title m-b-0">Detail Barang</h3>
            <div class="m-b-0">
                <div class="form-group row">
                    <label class="col-md-12">Filter Berdasarkan Jenis Barang</label>
                    <div class="col-sm-10">
                        <select class="form-control select2" name="ijenis" id="ijenis">
                            <option value="all">Semua Jenis</option>
                            <?php if ($jenis) {
                                foreach ($jenis->result() as $key) {?>
                                    <option value="<?= $key->i_type_code;?>"><?= $key->e_type_name;?></option>
                                <?php }
                            }?>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <button type="button" id="addrow" class="btn btn-info btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Item</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="table-responsive">
                <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 3%;">No</th>
                            <th class="text-center" style="width: 10%;">Tgl Schedule</th>
                            <th class="text-center" style="width: 45%;">Nama Barang</th>
                            <th class="text-center" style="width: 10%;">Qty</th>
                            <th class="text-center">Ket</th>
                            <th class="text-center" style="width: 5%;">Act</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($datadetail as $key) {
                            $i++;
                            ?>
                            <tr>
                                <td class="text-center"><spanx id="snum<?= $i ;?>"><?= $i ;?></spanx></td>
                                <td><input type="text" readonly id="dschdetail<?= $i ;?>" value="<?= date('d-m-Y', strtotime($key->d_schedule));?>" class="form-control input-sm date" name="dschdetail<?= $i ;?>"></td>
                                <td>
                                    <select data-nourut="<?= $i ;?>" id="iproductcolor<?= $i ;?>" class="form-control select2 input-sm" name="iproductcolor<?= $i ;?>" >
                                        <option value="<?= $key->i_product_wip.'|'.$key->i_color;?>"><?= $key->i_product_wip.' - '.$key->e_product_wipname.' - '.$key->e_color_name;?></option>
                                    </select>
                                </td>
                                <td><input type="text" id="nquantity<?= $i ;?>" class="form-control text-right input-sm inputitem" autocomplete="off" name="nquantity<?= $i ;?>" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="<?= $key->n_quantity;?>" onkeyup="angkahungkul(this);"></td>
                                <td><input type="text" class="form-control input-sm" name="eremark<?= $i ;?>" id="eremark<?= $i ;?>" value="<?= $key->e_remark;?>" placeholder="Isi keterangan jika ada!"/></td>
                                <td class="text-center"><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>
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
<script>
    $(document).ready(function () {
        $('#ischedule').mask('SS-0000-000000S');
        $('.select2').select2();
        /*Tidak boleh kurang dari hari ini*/
        showCalendar('.date',0);
        for (var i = 1; i <= $('#jml').val(); i++) {
            $('#iproductcolor'+ i).select2({
                placeholder: 'Cari Kode / Nama WIP',
                allowClear: true,
                width: "100%",
                type: "POST",
                ajax: {
                    url: '<?= base_url($folder.'/cform/product/'); ?>',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        var query   = {
                            q          : params.term,
                            ijenis     : $('#ijenis').val(),
                        }
                        return query;
                    },
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                }
            }).change(function(event) {
                var z   = $(this).data('nourut');
                $('#nquantity'+z).focus();
                var ada = true;
                for(var x = 1; x <= $('#jml').val(); x++){
                    if ($(this).val()!=null) {
                        if((($(this).val()) == $('#iproductcolor'+x).val()) && (z!=x)){
                            swal ("kode : "+ $(this).val().substring(0, 7) +" warna tersebut sudah ada !!!!!");
                            ada = false;
                            break;
                        }
                    }
                }
                if (!ada) {                
                    $(this).val('');
                    $(this).html('');
                }
            });
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

    $( "#ischedule" ).keyup(function() {
        $.ajax({
            type: "post",
            data: {
                'kode' : $(this).val(),
                'ibagian' : $('#ibagian').val(),
            },
            url: '<?= base_url($folder.'/cform/cekkode'); ?>',
            dataType: "json",
            success: function (data) {
                if (data==1 && ($('#ischedule').val()!=$('#ischeduleold').val())) {
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
            $("#ischedule").attr("readonly", false);
        }else{
            $("#ischedule").attr("readonly", true);
            $("#ada").attr("hidden", true);
            $('#ischedule').val($('#ischeduleold').val());
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
                $('#ischedule').val(data);
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

    var i = $('#jml').val();
    $("#addrow").on("click", function () {
        i++;
        $("#jml").val(i);
        var no     = $('#tabledatax tr').length;
        var newRow = $("<tr>");
        var cols   = "";
        cols += `<td class="text-center"><spanx id="snum${i}">${no}</spanx></td>`;
        cols += `<td><input type="text" readonly id="dschdetail${i}" class="form-control input-sm date" name="dschdetail${i}"></td>`;
        cols += `<td><select data-nourut="${i}" id="iproductcolor${i}" class="form-control input-sm" name="iproductcolor${i}" ></select></td>`;
        cols += `<td><input type="text" id="nquantity${i}" class="form-control text-right input-sm inputitem" autocomplete="off" name="nquantity${i}" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);"></td>`;
        cols += `<td><input type="text" class="form-control input-sm" name="eremark${i}" id="eremark${i}" placeholder="Isi keterangan jika ada!"/></td>`;
        cols += `<td class="text-center"><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>`;
        newRow.append(cols);
        $("#tabledatax").append(newRow);
        $('#iproductcolor'+ i).select2({
            placeholder: 'Cari Kode / Nama WIP',
            allowClear: true,
            width: "100%",
            type: "POST",
            ajax: {
                url: '<?= base_url($folder.'/cform/product/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query   = {
                        q          : params.term,
                        ijenis     : $('#ijenis').val(),
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        }).change(function(event) {
            var z   = $(this).data('nourut');
            $('#nquantity'+z).focus();
            var ada = true;
            for(var x = 1; x <= $('#jml').val(); x++){
                if ($(this).val()!=null) {
                    if((($(this).val()) == $('#iproductcolor'+x).val()) && (z!=x)){
                        swal ("kode : "+ $(this).val().substring(0, 7) +" warna tersebut sudah ada !!!!!");
                        ada = false;
                        break;
                    }
                }
            }
            if (!ada) {                
                $(this).val('');
                $(this).html('');
            }
        });
        showCalendar('.date',0);
    });

    $("#tabledatax").on("click", ".ibtnDel", function (event) {    
        $(this).closest("tr").remove();

        $('#jml').val(i);
        var obj = $('#tabledatax tr:visible').find('spanx');
        $.each( obj, function( key, value ) {
            id = value.id;
            $('#'+id).html(key+1);
        });
    });

    $( "#submit" ).click(function(event) {
        ada = false;
        if ($('#jml').val()==0) {
            swal('Isi item minimal 1!');
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
                $(this).find("td .date").each(function() {
                    if ($(this).val()=='' || $(this).val()==null) {
                        swal('Tanggal schedule harus diisi!');
                        ada = true;
                    }
                });
            });
            if (!ada) {
                swal({   
                    title: "Update Data Ini?",   
                    text: "Anda Dapat Membatalkannya Nanti",
                    type: "warning",   
                    showCancelButton: true,   
                    confirmButtonColor: "#DD6B55",   
                    confirmButtonColor: 'LightSeaGreen',
                    confirmButtonText: "Ya, Update!",   
                    closeOnConfirm: false 
                }, function(){
                    $.ajax({
                        type: "POST",
                        data: $( "form" ).serialize(),
                        url: '<?= base_url($folder.'/cform/update/'); ?>',
                        dataType: "json",
                        success: function (data) {
                            if (data.sukses==true) {                                
                                swal("Diupate!", "No Dokumen : "+data.kode+", Berhasil Diupdate :)", "success"); 
                                $("input").attr("disabled", true);
                                $("select").attr("disabled", true);
                                $("#submit").attr("disabled", true);
                                $("#addrow").attr("disabled", true);
                                $("#send").attr("hidden", false);
                            }else{
                                swal("Maaf", "Data Gagal Diupdate :(", "error");    
                            }
                        },
                        error: function () {
                            swal("Maaf", "Data Gagal Diupdate :(", "error");
                        }
                    });
                });
            }else{
                return false;
            }
        }
    })
</script>