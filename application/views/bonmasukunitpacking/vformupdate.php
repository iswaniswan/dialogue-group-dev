<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/updatemanual'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
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
                        <label class="col-md-3">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-4">Keterangan</label>
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" required="" class="form-control select2">
                                <?php if ($bagian) {
                                    foreach ($bagian->result() as $key) { ?>
                                        <option value="<?= trim($key->i_bagian);?>" <?php if ($key->i_bagian == $data->i_bagian) {?> selected <?php } ?>><?= $key->e_bagian_name;?></option> 
                                    <?php }
                                } ?> 
                            </select>
                            <input type="hidden" id="ibagianold" value="<?= $data->i_bagian;?>">
                            <input type="hidden" name="ipengirim" value="<?= $data->id_bagian_pengirim;?>">
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="hidden" id="id" name="id" value="<?= $data->id;?>">
                                <input type="hidden" id="ibbmold" value="<?= $data->i_document;?>">  
                                <input type="text" name="idocument" required="" id="ibbm" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number;?>" maxlength="16" class="form-control input-sm" value="<?= $data->i_document;?>" aria-label="Text input with dropdown button">
                                <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span>
                            </div>
                            <span class="notekode">Format : (<?= $number;?>)</span><br>
                            <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="ddocument" required="" id="ddocument" class="form-control input-sm date" value="<?= $data->d_document;?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <textarea type="text" id="eremark" name="eremark" maxlength="250" class="form-control input-sm" placeholder="Isi keterangan jika ada!"><?= $data->e_remark;?></textarea>
                        </div>
                    </div> 
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <?php if ($data->i_status == '1' || $data->i_status == '3' || $data->i_status == '7') {?>
                                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save" ></i>&nbsp;&nbsp;Update</button>&nbsp;
                                <button type="button" id="addrow" class="btn btn-info btn-sm btn-rounded"><i class="fa fa-plus"></i>&nbsp;&nbsp;Item</button>&nbsp;
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
<?php $i = 1; if ($datadetail) {?>
<div class="white-box" id="detail">
    <h3 class="box-title m-b-0">Detail Barang</h3>
    <div class="table-responsive">
        <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
            <thead>
                <tr>
                    <th class="text-center" width="3%">No</th>
                    <th class="text-center" width="50%">Barang</th>
                    <th class="text-center" width="10%">Qty</th>
                    <th class="text-center">Keterangan</th>
                    <th class="text-center" style="width: 5%;">Act</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($datadetail as $key) {?>
                    <tr>
                        <td class="text-center"><spanx id="snum<?=$i;?>"><?=$i;?></td>
                        <td>
                            <select data-nourut="<?= $i ;?>" id="idproduct<?= $i;?>" name="idproduct<?= $i;?>" class="form-control select2">
                                <option value="<?= $key->id_product;?>"><?= $key->i_product.' - '.$key->e_product.' '.$key->e_color_name;?></option>
                            </select>
                        </td>
                        <td><input class="form-control input-sm text-right" type="text" id="npemenuhan<?=$i;?>" name="npemenuhan<?=$i;?>" value="<?= $key->n_quantity;?>" placeholder="0" onkeyup="angkahungkul(this);"></td>
                        <td><input class="form-control input-sm" placeholder="Isi keterangan jika ada!" type="text" id="eremark<?=$i;?>" name="eremark<?=$i;?>" value="<?= $key->e_remark;?>"></td>
                        <td class="text-center"><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>
                    </tr>
                <?php $i++; } ?>
            </tbody>
        </table>
    </div>
</div>
<input type="hidden" name="jml" id="jml" value ="<?= $i ;?>">
<?php } ?>
</form>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>

    /*----------  LOAD SAAT DOKUMEN READY  ----------*/
    
    $(document).ready(function () {
        $('#ibbm').mask('SSS-0000-000000S');
        $('.select2').select2();
        /*Tidak boleh lebih dari hari ini*/
        showCalendar('.date',null,0);

        $('#ipengirim').select2({
            placeholder: 'Pilih Pengirim',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/pengirim'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query   = {
                        q          : params.term,
                        ibagian    : $('#ibagian').val(),
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
            $("#tabledatax tr:gt(0)").remove();
            $("#jml").val(0);
        });

        for (var i = 1; i <= $('#jml').val(); i++) {
            $('#idproduct'+ i).select2({
                placeholder: 'Cari Kode / Nama Barang Jadi',
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
            }).change(function(event){
                /**
                 * Cek Barang Sudah Ada
                 */
                var z = $(this).data('nourut');
                var ada = true;
                for(var x = 1; x <= $('#jml').val(); x++){
                    if ($(this).val()!=null) {
                        if((($(this).val()) == $('#idproduct'+x).val()) && (z!=x)){
                            swal ("kode barang tersebut sudah ada !!!!!");
                            ada = false;
                            break;
                        }
                    }
                }
                if (!ada) {                
                    $(this).val('');
                    $(this).html('');
                }else{
                    $('#npemenuhan'+z).focus();
                }
            });
        }
    });

    var x = $('#jml').val();
    $("#addrow").on("click", function () {
        x++;
        $("#jml").val(x);
        $("#tabledatax").attr("hidden", false);
        $("#detail").attr("hidden", false);
        var no     = $('#tabledatax tr').length;
        var newRow = $("<tr>");
        var cols   = "";
        cols += '<td class="text-center"><spanx id="snum'+x+'">'+no+'</td>';
        cols += '<td><select data-nourut="'+x+'" id="idproduct'+x+'" name="idproduct'+x+'" class="form-control"></select></td>';
        cols += '<td><input class="form-control input-sm text-right inputitem" type="text" id="npemenuhan'+x+'" name="npemenuhan'+x+'" value="" placeholder="0" onkeypress="return hanyaAngka(event);" onkeyup="ceksaldo('+x+');"></td>';
        cols += '<td><input type="text" class="form-control input-sm" placeholder="Isi keterangan jika ada!" name="eremark'+x+'"></td>';
        cols += `<td class="text-center"><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>`;
        newRow.append(cols);
        $("#tabledatax").append(newRow);
        $('#idproduct'+ x).select2({
            placeholder: 'Cari Kode / Nama Barang Jadi',
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
        }).change(function(event){
            /**
             * Cek Barang Sudah Ada
             */
            var z = $(this).data('nourut');
            var ada = true;
            for(var x = 1; x <= $('#jml').val(); x++){
                if ($(this).val()!=null) {
                    if((($(this).val()) == $('#idproduct'+x).val()) && (z!=x)){
                        swal ("kode barang tersebut sudah ada !!!!!");
                        ada = false;
                        break;
                    }
                }
            }
            if (!ada) {                
                $(this).val('');
                $(this).html('');
            }else{
                $('#npemenuhan'+z).focus();
            }
        });
    });

    $("#tabledatax").on("click", ".ibtnDel", function (event) {    
        $(this).closest("tr").remove();

        $('#jml').val(x);
        var obj = $('#tabledatax tr:visible').find('spanx');
        $.each( obj, function( key, value ) {
            id = value.id;
            $('#'+id).html(key+1);
        });
    });

    /*----------  NOMOR DOKUMEN  ----------*/    

    function number() {
        if ($('#ibagian').val() == $('#ibagianold').val()) {
            $('#ibbm').val($('#ibbmold').val());
        }else{
            $.ajax({
                type: "post",
                data: {
                    'tgl' : $('#ddocument').val(),
                    'ibagian' : $('#ibagian').val(),
                },
                url: '<?= base_url($folder.'/cform/number'); ?>',
                dataType: "json",
                success: function (data) {
                    $('#ibbm').val(data);
                },
                error: function () {
                    swal('Error :(');
                }
            });
        }
    }

    /*----------  KONDISI PAS CHECKBOX DI NO DOKUMEN DIKLIK  ----------*/
    
    $('#ceklis').click(function(event) {
        if($('#ceklis').is(':checked')){
            $("#ibbm").attr("readonly", false);
        }else{
            $("#ibbm").attr("readonly", true);
            $("#ada").attr("hidden", true);
            number();
        }
    });

    /*----------  CEK NO DOKUMEN SAAT DIKETIK  ----------*/    

    $( "#ibbm" ).keyup(function() {
        $.ajax({
            type: "post",
            data: {
                'kode' : $(this).val(),
                'ibagian' : $('#ibagian').val(),
            },
            url: '<?= base_url($folder.'/cform/cekkode'); ?>',
            dataType: "json",
            success: function (data) {
                if (data==1 && ($('#ibbm').val() != $('#ibbmold').val())) {
                    $("#ada").attr("hidden", false);
                    $("#submit").attr("disabled", true);
                }else{
                    $("#ada").attr("hidden", true);
                    $("#submit").attr("disabled", false);
                }
            },
            error: function () {
                swal('Error :(');
            }
        });
    });

    /*----------  UPDATE STATUS DOKUMEN KE WAIT APPROVE ----------*/    

    $('#send').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'2','<?= $dfrom."','".$dto;?>');
    });

    /*----------  UPDATE NO DOKUMEN SAAT BAGIAN PEMBUAT DAN TANGGAL DOKUMEN DIRUBAH  ----------*/
    
    $('#ddocument, #ibagian').change(function(event) {
        number();
    });

    /*----------  VALIDASI SAAT MENEKAN TOMBOL SIMPAN  ----------*/
    
    $('#submit').click(function(event) {
        ada = false;
        if($("#jml").val()==0){
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
    });

    /*----------  KONDISI SETELAH MENEKAN TOMBOL SIMPAN  ----------*/    

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#send").attr("hidden", false);
    });
</script>