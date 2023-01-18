<style>
    .font-11{
    padding-left: 3px;
    padding-right: 3px;
    font-size: 11px;  
    height: 20px;  
}
.font-12{
    padding-left: 3px;
    padding-right: 3px;
    font-size: 12px;    
}
</style>
<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?=$title_list;?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-sm-12">
                    <div class="form-group row">
                        <label class="col-sm-3">Bagian Pembuat</label>
                        <label class="col-sm-3">Nomor Dokumen</label>
                        <label class="col-sm-3">Tanggal Dokumen</label>
                        <label class="col-sm-3">Tujuan Pengiriman</label>
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2" required="">
                                <?php if ($bagian) {
                                    foreach ($bagian as $row):?>
                                        <option value="<?= $row->i_bagian;?>" <?php if ($row->i_bagian==$data->i_bagian) {?> selected <?php } ?>>
                                            <?= $row->e_bagian_name;?>
                                        </option>
                                    <?php endforeach; 
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" value="<?= $id;?>">
                                <input type="hidden" name="i_document_old" id="i_document_old" value="<?= $data->i_document;?>">
                                <input type="text" name="i_document" id="i_document" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number;?>" maxlength="15" class="form-control input-sm" value="<?= $data->i_document;?>" aria-label="Text input with dropdown button">
                                <!-- <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span> -->
                            </div>
                            <span class="notekode" hidden="true">Format : (<?= $number;?>)</span><br>
                            <span class="notekode" id="ada" hidden="true"><b>* No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="d_document" name="d_document" class="form-control input-sm date" onchange="tanggal(this.value); number();" required="" readonly value="<?= $data->d_document ?>">
                        </div>
                        <div class="col-sm-3">
                            <select name="itujuan" id="itujuan" class="form-control select2" onchange="number();">
                                <?php if ($tujuan) {
                                    foreach ($tujuan as $row) : ?>
                                        <option value="<?= $row->id_bagian; ?>|<?= $row->id_company ?>|<?= $row->i_bagian ?>" <?php if ($row->i_bagian==$data->i_bagian_receive && $row->id_company==$data->id_company_receive) {?> selected <?php } ?>>
                                            <?= $row->e_bagian_name; ?> - <?= $row->name ?>
                                        </option>
                                <?php endforeach;
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea class="form-control input-sm" name="remark" placeholder="Isi keterangan jika ada!"><?= $data->e_remark;?></textarea>                 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <?php if ($data->i_status == '1' || $data->i_status == '3' || $data->i_status == '7') {?>
                                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save" ></i>&nbsp;&nbsp;Update</button>&nbsp;
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
<div class="white-box" id="detail">
    <div class="col-sm-5">
        <h3 class="box-title m-b-0">Detail Barang</h3>
        <div class="m-b-0">
            <div class="form-group row">
                <label class="col-md-6">Kategori Barang</label>
                <label class="col-md-5">Jenis Barang</label>
                <label class="col-md-1"></label>
                <div class="col-sm-6">
                    <select class="form-control select2" name="ikategori" id="ikategori">
                        <option value="all">Semua Kategori</option>
                    </select>
                </div>
                <div class="col-sm-5">
                    <select class="form-control select2" name="ijenis" id="ijenis">
                        <option value="all">Semua Jenis</option>
                    </select>
                </div>
                <div class="col-sm-1">
                    <?php if ($data->i_status == '1' || $data->i_status == '3' || $data->i_status == '7') {?>
                        <button type="button" id="addrow" class="btn btn-info btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Item</button>
                    <?php } ?>
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
                        <th style="width: 40%;">Kode/Barang Material</th>
                        <th style="width: 15%;">Satuan</th>
                        <!-- <th class="text-center" style="width: 10%;">Sisa</th> -->
                        <th class="text-right" style="width: 10%;">Stok</th>
                        <th class="text-right" style="width: 10%;">Jml</th>
                        <!-- <th class="text-center" style="width: 20%;">Supplier</th> -->
                        <!-- <th class="text-center" style="width: 10%;">Harga Supp</th> -->
                        <!-- <th class="text-center" style="width: 10%;">Harga Adj</th> -->
                        <th class="text-center">Keterangan</th>
                        <th class="text-center" style="width: 3%;">Act</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $i = 0;
                    if ($detail) {
                        foreach ($detail as $row) {
                            $i++;
                            ?>
                            <tr>
                                <td class="text-center"><spanx id="snum<?=$i;?>"><?= $i;?></spanx></td>
                                <td>
                                    <select id="imaterial<?=$i;?>" class="form-control input-sm" name="imaterial[]" onchange="getmaterial(<?=$i;?>);">
                                        <option value="<?= $row->id_material;?>"><?= $row->i_material." - ".$row->e_material_name;?></option>
                                    </select>
                                </td>
                                <td>
                                    <input type="hidden" value="<?= $row->i_satuan_code;?>" id="isatuan<?=$i;?>" name="isatuan[]"/>
                                    <input type="text" value="<?= $row->e_satuan_name;?>" readonly id="esatuan<?=$i;?>" class="form-control input-sm" name="esatuan[]">
                                </td>
                                <td><input type="text" readonly id="stok'+i+'" class="form-control text-right input-sm inputitem" autocomplete="off" name="stok[]" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="<?= $row->saldo_akhir ?>" onkeyup="angkahungkul(this);"></td>
                                <td>
                                    <input type="text" value="<?= $row->n_quantity;?>" id="nquantity<?=$i;?>" class="form-control text-right inputitem input-sm" autocomplete="off" name="nquantity[]" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="0" onkeyup="angkahungkul(this);">
                                </td>
                                <td>
                                    <input type="text" id="eremark<?=$i;?>" class="form-control input-sm" value="<?= $row->e_remark;?>" name="eremark[]"/>
                                </td>
                                <td>
                                   <button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button>
                                </td>
                            </tr>
                        <?php } 
                    }?>
                    <input type="hidden" name="jml" id="jml" value ="<?= $i;?>">
                </tbody>
            </table>
        </div>
    </div>
</div>
</from>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date');
        fixedtable($('.table'));
        // number();

        for (var i = 0; i <= $('#jml').val(); i++) {
            $('#imaterial'+i).select2({
                placeholder: 'Cari Kode / Nama Material',
                allowClear: true,
                type: "POST",
                ajax: {
                    url: '<?= base_url($folder.'/cform/material/'); ?>',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        let tujuan = $('#itujuan').val();
                        let strsplit = tujuan.split('|');
                        let ibagian = strsplit[2];
                        let idcompany = strsplit[1];
                        var query   = {
                            q          : params.term,
                            ikategori  : $('#ikategori').val(),
                            ijenis     : $('#ijenis').val(),
                            ibagian    : ibagian,
                            idcompany  : idcompany
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
            });

                if($('#budgeting').val() == 'f') {
                    $('#isupplier'+ i).select2({
                    placeholder: 'Cari Kode / Nama Supplier',
                    allowClear: true,
                    width: '100%',
                    type: "POST",
                    ajax: {
                        url: '<?= base_url($folder.'/cform/supplier/'); ?>',
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
                }).change(function(event) {
                    let z = $(this).data('urut');
                    $.ajax({
                        type: "post",
                        data: {
                            'i_supplier': $(this).val(),
                            'i_material': $('#i_material'+z).val(),
                            'd_document': $('#d_document').val(),
                        },
                        url: '<?= base_url($folder.'/cform/getmaterialprice'); ?>',
                        dataType: "json",
                        success: function (data) {
                            if(data.length>0){
                                $('#harga_sup'+z).val(data[0]['v_price']);
                                $('#harga_adj'+z).val(data[0]['v_price']);
                            }else{
                                $('#harga_sup'+z).val(0);
                                $('#harga_adj'+z).val(0);
                            }
                        },
                        error: function () {
                            swal('Ada kesalahan :(');
                        }
                    });
                });
            }
            
        }

        $('#ikategori').select2({
            placeholder: 'Pilih Kategori',
            width: '100%',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/kelompok'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    let tujuan = $('#itujuan').val();
                    let strsplit = tujuan.split('|');
                    let ibagian = strsplit[2];
                    let idcompany = strsplit[1];
                    var query = {
                        q: params.term,
                        ibagian : ibagian,
                        idcompany : idcompany
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data,
                    };
                },
                cache: false
            }
        }).change(function(event) {
            $('#ijenis').val('');
            $('#ijenis').html('');
        });

        $('#ijenis').select2({
            placeholder: 'Pilih Jenis',
            width: '100%',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/jenis'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    let tujuan = $('#itujuan').val();
                    let strsplit = tujuan.split('|');
                    let ibagian = strsplit[2];
                    let idcompany = strsplit[1];
                    var query = {
                        q: params.term,
                        ikategori : $('#ikategori').val(),
                        ibagian : ibagian,
                        idcompany : idcompany
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data,
                    };
                },
                cache: false
            }
        });
    });

    $( "#i_document" ).keyup(function() {
        $.ajax({
            type: "post",
            data: {
                'kode' : $(this).val(),
                'ibagian' : $('#ibagian').val(),
            },
            url: '<?= base_url($folder.'/cform/cekkode'); ?>',
            dataType: "json",
            success: function (data) {
                if (data==1 && ($('#i_document').val()!=$('#i_document_old').val())) {
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

    function number() {
        $.ajax({
            type: "post",
            data: {
                'tgl' : $('#d_document').val(),
                'ibagian' : $('#ibagian').val(),
                'itujuan' : $('#itujuan').val(),
            },
            url: '<?= base_url($folder.'/cform/number'); ?>',
            dataType: "json",
            success: function (data) {
                $('#i_document').val(data);
            },
            error: function () {
                swal('Error :)');
            }
        });
    }

    $('#ibagian').change(function(event) {
        number();
    });

    $('#ceklis').click(function(event) {
        if($('#ceklis').is(':checked')){
            $("#i_document").attr("readonly", false);
        }else{
            $("#i_document").attr("readonly", true);
            $("#i_document").val($("#i_document_old").val());
        }
    });

    $('#ibagian').change(function(event) {
        $('#ikategori').val('');
        $('#ikategori').html('');
        $('#ijenis').val('');
        $('#ijenis').html('');
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
        //$("select").attr("disabled", true);
        $("#addrow").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#send").attr("disabled", false);
    });

    var i = $('#jml').val();
    $("#addrow").on("click", function () {
        i++;
        $("#jml").val(i);
        var no     = $('#tabledatax tr').length;
        var newRow = $("<tr>");
        var cols   = "";
        cols += '<td class="text-center"><spanx id="snum'+i+'">'+no+'</spanx></td>';
        cols += '<td><select id="imaterial'+i+ '" class="form-control input-sm" name="imaterial[]" onchange="getmaterial('+i+');"></td>';
        cols += '<td><input type="hidden" id="isatuan'+i+ '" name="isatuan[]"/><input type="text" readonly id="esatuan'+i+'" class="form-control input-sm" name="esatuan[]"></td>';
        cols += '<td><input type="text" readonly id="stok'+i+'" class="form-control text-right input-sm inputitem" autocomplete="off" name="stok[]" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);"></td>';
        cols += '<td><input type="text" id="nquantity'+i+'" class="form-control text-right input-sm inputitem" autocomplete="off" name="nquantity[]" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);"></td>';
        cols += '<td><input type="text" id="eremark'+i+'" class="form-control input-sm" name="eremark[]"/><input type="hidden" id="ikode'+i+'" name="ikode[]"/></td>';
        cols += '<td><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>';
        newRow.append(cols);
        $("#tabledatax").append(newRow);
        $('#imaterial'+ i).select2({
            placeholder: 'Cari Kode / Nama Material',
            allowClear: true,
            type: "POST",
            ajax: {
                url: '<?= base_url($folder.'/cform/material/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    let tujuan = $('#itujuan').val();
                    let strsplit = tujuan.split('|');
                    let ibagian = strsplit[2];
                    let idcompany = strsplit[1];
                    var query   = {
                        q          : params.term,
                        ikategori  : $('#ikategori').val(),
                        ijenis     : $('#ijenis').val(),
                        ibagian    : ibagian,
                        idcompany  : idcompany
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
        });
    });  

    $("#tabledatax").on("click", ".ibtnDel", function (event) {    
        $(this).closest("tr").remove();

        /*$('#jml').val(i);*/
        del();
    });

    function del() {
        obj=$('#tabledatax tr:visible').find('spanx');
        $.each( obj, function( key, value ) {
            id = value.id;
            $('#'+id).html(key+1);
        });
    }

    function getmaterial(id){
        $.ajax({
            type: "post",
            data: {
                'imaterial': $('#imaterial'+id).val(),
                'itujuan': $('#itujuan').val().split('|')[1],
            },
            url: '<?= base_url($folder.'/cform/getmaterial'); ?>',
            dataType: "json",
            success: function (data) {
                ada = false;
                for(var i = 1; i <=$('#jml').val(); i++){
                    if(($('#imaterial'+id).val() == $('#imaterial'+i).val()) && (i!=id)){
                        swal ("kode : "+$('#imaterial'+id).val()+" sudah ada !!!!!");
                        ada = true;
                        break;
                    }else{
                        ada = false;     
                    }
                }
                if(!ada){
                    $('#ikode'+id).val(data[0].i_kode_kelompok);
                    $('#isatuan'+id).val(data[0].i_satuan_code);
                    $('#esatuan'+id).val(data[0].e_satuan_name);
                    $('#stok'+id).val(data[0].saldo_akhir);
                    $('#nquantity'+id).focus();
                }else{
                    $('#imaterial'+id).html('');
                    $('#imaterial'+id).val('');
                    $('#isatuan'+id).val('');
                    $('#stok'+id).val('');
                    $('#ikode'+id).val('');
                    $('#esatuan'+id).val('');
                }
            },
            error: function () {
                swal('Ada kesalahan :(');
            }
        });
    }

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
                        swal('Data item ada yang salah!');
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
    }) 
</script>