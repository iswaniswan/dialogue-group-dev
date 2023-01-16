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
<!-- <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?> -->
<form>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?=$title_list;?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-sm-12">
                    <div class="form-group row">
                        <label class="col-sm-3">Bagian Pembuat</label>
                        <label class="col-sm-3">Nomor PP</label>
                        <label class="col-sm-3">Tanggal PP</label>
                        <label class="col-sm-3">Batas Pemenuhan</label>
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
                                <input type="text" name="ipp" id="ipp" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number;?>" maxlength="15" class="form-control input-sm" value="" aria-label="Text input with dropdown button">
                                <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span>
                            </div>
                            <span class="notekode">Format : (<?= $number;?>)</span><br>
                            <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="dpp" name="dpp" class="form-control input-sm date" onchange="tanggal(this.value); number();" required="" readonly value="<?= date("d-m-Y"); ?>">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="dbp" name="dbp" class="form-control input-sm date"  required="" readonly value="<?= date("d-m-Y", strtotime('+1 month', strtotime(date('d-m-Y')))); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea class="form-control input-sm" name="remark" placeholder="Isi keterangan jika ada!"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-10">
                            <button type="button" id="submit" class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                            <button type="button" id="send" hidden="true" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
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
                <label class="col-md-5">Kategori Barang</label>
                <label class="col-md-6">Jenis Barang</label>
                <label class="col-md-1"></label>
                <div class="col-sm-5">
                    <select class="form-control select2" name="ikategori" id="ikategori">
                        <option value="all">Semua Kategori</option>
                    </select>
                </div>
                <div class="col-sm-6">
                    <select class="form-control select2" name="ijenis" id="ijenis">
                        <option value="all">Semua Jenis</option>
                    </select>
                </div>
                <div class="col-sm-1">
                    <button type="button" id="addrow" class="btn btn-info btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Item</button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table font-11 success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 3%;">No</th>
                        <th class="text-center" style="width: 35%;">Barang</th>
                        <th class="text-center" style="width: 10%;">Satuan</th>
                        <th class="text-center" style="width: 10%;">Jml</th>
                        <!-- <th class="text-center" style="width: 20%;">Supplier</th>
                        <th class="text-center" style="width: 10%;">Harga Supp</th>
                        <th class="text-center" style="width: 10%;">Harga Adj</th> -->
                        <th class="text-center">Keterangan</th>
                        <th class="text-center" style="width: 3%;">Act</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<input type="hidden" name="jml" id="jml" value ="0">
<input type="hidden" name="id" id="id" value="0">
<input type="hidden" name="budgeting" id="budgeting" value ="f">
</from>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
    $(document).ready(function () {
        $('#ipp').mask('SS-0000-000000S');
        $('.select2').select2();
        showCalendar('.date');
        number();
        fixedtable($('.table'));

        $('#ikategori').select2({
            placeholder: 'Pilih Kategori',
            width: '100%',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/kelompok'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                        ibagian : $('#ibagian').val(),
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
                    var query = {
                        q: params.term,
                        ikategori : $('#ikategori').val(),
                        ibagian   : $('#ibagian').val(),
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

    $( "#ipp" ).keyup(function() {
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

    function number() {
        $.ajax({
            type: "post",
            data: {
                'tgl' : $('#dpp').val(),
                'ibagian' : $('#ibagian').val(),
            },
            url: '<?= base_url($folder.'/cform/number'); ?>',
            dataType: "json",
            success: function (data) {
                $('#ipp').val(data);
            },
            error: function () {
                swal('Error :)');
            }
        });
    }

    $('#ceklis').click(function(event) {
        if($('#ceklis').is(':checked')){
            $("#ipp").attr("readonly", false);
        }else{
            $("#ipp").attr("readonly", true);
            $("#ada").attr("hidden", true);
            number();
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

    /* $("form").submit(function (event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#addrow").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#send").attr("hidden", false);
    });
 */
    var i = 0;
    $("#addrow").on("click", function () {
        i++;
        $("#jml").val(i);
        var no     = $('#tabledatax tr').length;
        var newRow = $("<tr>");
        var cols   = "";
        cols += '<td style="text-align: center;"><spanx id="snum'+i+'">'+no+'</spanx></td>';
        cols += '<td><select id="imaterial'+i+ '" class="form-control input-sm" name="imaterial[]" onchange="getmaterial('+i+');"></td>';
        cols += '<td><input type="hidden" id="isatuan'+i+ '" name="isatuan[]"/><input type="text" readonly id="esatuan'+i+'" class="form-control input-sm" name="esatuan[]"></td>';
        cols += '<td><input type="text" id="nquantity'+i+'" class="form-control text-right input-sm inputitem" autocomplete="off" name="nquantity[]" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);"></td>';
        /* cols += '<td><select data-urut="'+i+'" id="isupplier'+i+'" class="form-control input-sm" name="isupplier[]"><option></option></option></select></td>';
        cols += '<td><input type="text" readonly id="harga_sup'+i+'" class="form-control text-right input-sm" autocomplete="off" name="harga_sup[]" value="0"></td>';
        cols += '<td><input type="text" id="harga_adj'+i+'" class="form-control text-right input-sm" autocomplete="off" name="harga_adj[]" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);"></td>'; */
        cols += '<td><input type="text" id="eremark'+i+'" class="form-control input-sm" name="eremark[]"/><input type="hidden" id="ikode'+i+'" name="ikode[]"/></td>';
        cols += '<td><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>';
        newRow.append(cols);
        $("#tabledatax").append(newRow);
        $('#imaterial'+ i).select2({
            placeholder: 'Cari Kode / Nama Material',
            allowClear: true,
            width: '100%',
            type: "POST",
            ajax: {
                url: '<?= base_url($folder.'/cform/material/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query   = {
                        q          : params.term,
                        ikategori  : $('#ikategori').val(),
                        ijenis     : $('#ijenis').val(),
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
        });
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
                    'i_material': $('#imaterial'+z).val(),
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
    });  

    $("#tabledatax").on("click", ".ibtnDel", function (event) {    
        $(this).closest("tr").remove();

        $('#jml').val(i);
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
                    $('#nquantity'+id).focus();
                }else{
                    $('#imaterial'+id).html('');
                    $('#imaterial'+id).val('');
                    $('#isatuan'+id).val('');
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
                        swal('Quantity Tidak Boleh Kosong Atau 0!');
                        ada = true;
                    }
                });
            });
            if (!ada) {
                swal({
                    title: "Simpan Data Ini?",
                    text: "Anda Dapat Membatalkannya Nanti",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonColor: 'LightSeaGreen',
                    confirmButtonText: "Ya, Simpan!",
                    closeOnConfirm: false
                }, function() {
                    $.ajax({
                        type: "POST",
                        data: $("form").serialize(),
                        url: '<?= base_url($folder . '/cform/simpan/'); ?>',
                        dataType: "json",
                        success: function(data) {
                            if (data.sukses == true) {
                                $('#id').val(data.id);
                                swal("Sukses!", "No Dokumen : " + data.kode + ", Berhasil Disimpan :)", "success");
                                $("input").attr("disabled", true);
                                $("select").attr("disabled", true);
                                $("#submit").attr("disabled", true);
                                $("#addrow").attr("disabled", true);
                                $("#send").attr("hidden", false);
                            } else if (data.sukses == 'ada') {
                                swal("Maaf :(", "No Dokumen : " + data.kode + ", Sudah Ada :(", "error");
                            } else {
                                swal("Maaf :(", "No Dokumen : " + data.kode + ", Gagal Disimpan :(", "error");
                            }
                        },
                        error: function() {
                            swal("Maaf", "Data Gagal Disimpan :(", "error");
                        }
                    });
                });
            }else{
                return false;
            }
        }
    }) 

    function tanggal(d) {
        $('#dbp').val(maxDate(d));
    }
</script>