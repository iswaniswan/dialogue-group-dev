<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/index/<?=$dfrom;?>/<?=$dto;?>','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?=$title_list;?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row ">
                        <label class="col-md-3">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-3">Tanggal Dokumen</label>
                        <label class="col-md-3">Tujuan</label>
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2">
                                <option value="<?=$data->i_bagian;?>"><?=$data->e_bagian_name;?></option>
                            </select>
                            <input type="hidden" name="id" id="id" value="<?=$data->id;?>">
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="hidden" name="idocumentold" id="idocumentold" value="<?= $data->i_document;?>">
                                <input type="text" name="iretur" id="iretur" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number;?>" maxlength="15" class="form-control input-sm" value="<?=$data->i_document;?>" aria-label="Text input with dropdown button">
                                <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span>
                            </div>
                            <span class="notekode">Format : (<?= $number;?>)</span><br>
                            <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="ddocument" name="ddocument" class="form-control date" value="<?= $data->d_document; ?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <select name="itujuan" id="itujuan" class="form-control select2">
                                <option value="<?=$data->i_tujuan;?>"><?=$data->e_tujuan_name;?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Nomor Referensi</label>
                        <label class="col-md-8">Keterangan</label>
                        <div class="col-sm-4">
                            <select name="ireferensi" id="ireferensi" class="form-control select2">
                                <?php if($data->id_reff != 0){?>
                                    <option value="<?= $data->id_reff; ?>"><?= $data->i_document_reff; ?></option>
                                <?}?>
                            </select>
                            <span><i> *Optional</i></span>
                        </div>
                        <div class="col-sm-8">
                            <textarea type="text" id="eremark" name="eremark" class="form-control" value="" placeholder="Isi keterangan jika ada!"><?=$data->e_remark;?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-12">
                            <?php if ($data->i_status == '1' || $data->i_status == '3' || $data->i_status == '7') {?>
                                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return cek();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Update</button>&nbsp;
                                <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm" > <i class="fa fa-plus"></i>&nbsp;&nbsp;Item</button>&nbsp;
                            <?php } ?>
                            
                            <?php if ($data->i_status == '1') {?>
                                <button type="button" id="send" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>&nbsp;
                                <button type="button" id="hapus" class="btn btn-danger btn-rounded btn-sm"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete</button>&nbsp;
                            <?php }elseif($data->i_status=='2') {?>
                                <button type="button" id="cancel" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-refresh"></i>&nbsp;&nbsp;Cancel</button>&nbsp;
                            <?php } ?>

                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $i = 0; if ($datadetail) {?>
<div class="white-box" id="detail">
    <div class="col-sm-5">
        <h3 class="box-title m-b-0">Detail Barang</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead> 
                    <tr>
                        <th style="text-align:center;width:5%">No</th>
                        <th style="text-align:center;width:35%">Barang</th>
                        <th style="text-align:center;width:12%">Qty Retur</th>
                        <th style="text-align:center;width:25%">Keterangan</th>
                        <th style="text-align:center;width:5%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $z = 0; foreach ($datadetail as $key) { $z++; 
                        ?>
                        <tr id="tr<?= $z;?>">
                            <td style="text-align:center;"> <?=$z;?> </td>
                            <td>
                                <select data-nourut="<?= $z ;?>" id="idproduct<?= $z ;?>" class="form-control input-sm" name="idproduct<?= $z ;?>" >
                                    <option value="<?= $key->id_product_base;?>"><?= $key->i_product_base.' - '.$key->e_product_basename.' - '.$key->e_color_name;?></option>
                                </select>
                            </td>
                            <td>
                                <input class="form-control qty input-sm text-right inputitem" autocomplete="off" type="text" name="nquantity<?= $z ;?>" onblur='if(this.value==""){this.value="0";}' onfocus='if(this.value=="0"){this.value="";}' value="<?= $key->n_quantity;?>" onkeyup="angkahungkul(this);">
                            </td>
                            <td>
                                <input class="form-control" type="text" id="edesc<?=$z;?>" name="edesc<?=$z;?>" value="<?= $key->e_remark;?>">
                            </td>
                            <td class="text-center">
                                <button type="button" title="Delete" onclick="hapusdetail(<?= $z;?>);" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<input type="hidden" name="jml" id="jml" value ="<?= $z ;?>">
</form>
<?php } ?>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
    $(document).ready(function(){
        $('.select2').select2();
        showCalendar('.date', 1830, 0);

        $('#iretur').mask('SS-0000-000000S');

        $('#itujuan').select2({
            placeholder: 'Pilih Tujuan',
            allowClear: true,
            ajax: {
            url: '<?= base_url($folder.'/cform/bacatujuan'); ?>',
            dataType: 'json',
            delay: 250,          
            processResults: function (data) {
                return {
                results: data
                };
            },
            cache: true
            }
        });

        $('#ireferensi').select2({
            placeholder: 'Pilih Referensi',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getreferensi'); ?>',
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

        for (var i = 1; i <= $('#jml').val(); i++) {
            $('#idproduct'+ i).select2({
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
                            q         : params.term,
                            ipartner  : $('#ipartner').val(),
                            ddocument : $('#ddocument').val(),
                            ireferensi: $('#ireferensi').val(),
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
                /**
                 * Cek Barang Sudah Ada
                 * Get Detail Barang
                 */
                var z = $(this).data('nourut');
                var ada = true;
                for(var y = 1; y <= $('#jml').val(); y++){
                    if ($(this).val()!=null) {
                        if((($(this).val()) == $('#idproduct'+y).val()) && (z!=y)){
                            swal ("kode barang tersebut sudah ada !!!!!");
                            ada = false;
                            break;
                        }
                    }
                }
                if (!ada) {                
                    $(this).val('');
                    $(this).html('');
                    $("#tabledatax tbody").each(function() {
                        $("tr.del"+z).remove();
                    });
                }
            });
        }
    });

    $("#itujuan").change(function(){
        $("#addrow").attr("hidden", false);
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

    /**
     * Tambah Item
     */

    var i = $('#jml').val();
    $("#addrow").on("click", function () {
        //alert("tes");
        i++;
        $("#jml").val(i);
        $("#detail").attr("hidden", false);
        var no     = $('#tabledatax tr').length;
        var newRow = $('<tr id="tr'+i+'">');
        var cols   = "";
        cols += '<td style="text-align:center;">'+i+'</td>';
        cols += '<td><select data-nourut="'+i+'" id="idproduct'+i+'" class="form-control input-sm" name="idproduct'+i+'" ></select></td>';
        cols += '<td ><input class="form-control qty input-sm text-right inputitem" autocomplete="off" type="text" name="nquantity'+i+'" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);"></td>';
        cols += '<td ><input class="form-control" type="text" id="edesc'+i+'" name="edesc'+i+'" value="" placeholder="Isi keterangan jika ada!"></td>';
        cols += '<td class="text-center"><button type="button" title="Delete" onclick="hapusdetail('+i+');" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>';
        cols += '</tr>';
        newRow.append(cols);
        $("#tabledatax").append(newRow);
        $('#idproduct'+ i).select2({
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
                        q         : params.term,
                        ipartner  : $('#itujuan').val(),
                        ddocument : $('#ddocument').val(),
                        ireferensi: $('#ireferensi').val(),
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
            /**
             * Cek Barang Sudah Ada
             * Get Harga Barang
             */
            var z = $(this).data('nourut');
            var ada = true;
            for(var x = 1; x <= $('#jml').val(); x++){
                if ($(this).val()!=null) {
                    if((($(this).val()) == $('#idproduct'+x).val()) && (z!=x)){
                        swal ("Kode barang tersebut sudah ada !!!!!");
                        ada = false;
                        break;
                    }
                }
            }

            if(!ada){
                $(this).val('');
                $(this).html('');
            }
        });
    });
    /**
     * Hapus Detail Item
     */

    function hapusdetail(x) {
        $("#tabledatax tbody").each(function() {
            $("tr.del"+x).remove();
        });
    }

    $("#tabledatax").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();
    });

    function cek() {
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
</script>