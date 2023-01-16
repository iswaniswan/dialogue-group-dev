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
                        <input type="hidden" name="dfrom" id="dfrom" value="<?= $dfrom ?>">
                        <input type="hidden" name="dto" id="dto" value="<?= $dto ?>">
                        <label class="col-sm-3">Bagian Pembuat</label>
                        <label class="col-sm-3">Nomor Dokumen</label>
                        <label class="col-sm-3">Tanggal Dokumen</label>
                        <label class="col-sm-3">Tujuan Pengiriman</label>
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
                                <input type="text" name="i_document" id="i_document" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number;?>" maxlength="15" class="form-control input-sm" value="" aria-label="Text input with dropdown button">
                                <span class="input-group-addon" hidden="true">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span>
                            </div>
                            <span class="notekode" hidden="true">Format : (<?= $number;?>)</span><br>
                            <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="d_document" name="d_document" class="form-control input-sm date" onchange="tanggal(this.value); number();" required="" readonly value="<?= date("d-m-Y"); ?>">
                        </div>
                        <!-- <div class="col-sm-3">
                            <input type="text" id="dbp" name="dbp" class="form-control input-sm date"  required="" readonly value="<?= date("d-m-Y", strtotime('+1 month', strtotime(date('d-m-Y')))); ?>">
                        </div> -->
                        <div class="col-sm-3">
                            <select name="itujuan" id="itujuan" class="form-control select2" onchange="number();">
                                <?php if ($tujuan) {
                                    foreach ($tujuan as $row) : ?>
                                        <option value="<?= $row->id_bagian; ?>|<?= $row->id_company ?>|<?= $row->i_bagian ?>">
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
                        <th class="text-center" style="width: 10%;">Stok</th>
                        <th class="text-center" style="width: 10%;">Jml</th>
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
</from>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
    $(document).ready(function () {
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
                    let tujuan = $('#itujuan').val();
                    let strsplit = tujuan.split('|');
                    let ibagian = strsplit[2];
                    let idcompany = strsplit[1];
                    var query = {
                        q: params.term,
                        ibagian : ibagian,
                        idcompany: idcompany
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
                        ibagian   : ibagian,
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

    $('#ceklis').click(function(event) {
        if($('#ceklis').is(':checked')){
            $("#i_document").attr("readonly", false);
        }else{
            $("#i_document").attr("readonly", true);
            $("#ada").attr("hidden", true);
            number();
        }
    });

    $('#ibagian').change(function(event) {
        $('#ikategori').val('');
        $('#ikategori').html('');
        $('#ijenis').val('');
        $('#ijenis').html('');
        $('#tabledatax tbody').remove();
    });

    $('#itujuan').change(() => {
        $('#tabledatax tbody').remove();
        $('#ikategori').val('');
        $('#ikategori').html('');
        $('#ijenis').val('');
        $('#ijenis').html('');
    })

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
        cols += '<td><input type="text" readonly id="stok'+i+'" class="form-control text-right input-sm inputitem" autocomplete="off" name="stok[]" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);"></td>';
        cols += '<td><input type="text" id="nquantity'+i+'" class="form-control text-right input-sm inputitem" autocomplete="off" name="nquantity[]" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);validasi(' + i + ')"></td>';
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
        let tujuan = $('#itujuan').val();
        let strsplit = tujuan.split('|');
        let ibagian = strsplit[2];
        let idcompany = strsplit[1];
        $.ajax({
            type: "post",
            data: {
                'imaterial': $('#imaterial'+id).val(),
                'idcompany': idcompany,
                'ibagian': $('#ibagian').val(),
                'dfrom': $('#dfrom').val(),
                'dto': $('#dto').val(),
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

    function validasi(id) {
        var jml = document.getElementById("jml").value;
        if (id === undefined) {
            for (i = 1; i <= jml; i++) {
                var nquantity = document.getElementById("nquantity" + i).value;
                var stok = document.getElementById("stok" + i).value;

                var material = $('#idproduct'+i).val();
                var qty = parseFloat($('#nquantity'+i).val());
                for (let j = 1; j <= $('#jml').val(); j++) {
                    if (material == $('#idproduct'+j).val() && i != j) {
                        qty += parseFloat($('#nquantity'+j).val());
                    }
                }
                if (parseFloat(qty) > parseFloat(stok)) {
                    swal('Quantity Kirim Tidak Boleh Melebihi \nSaldo akhir ' + stok);
                    document.getElementById("nquantity" + i).value = 0;
                    return true;
                    break;
                }
                /* if (parseFloat(nquantity) == 0 && parseFloat(nquantity) == '') {
                    swal('Quantity Kirim Tidak Boleh 0 atau Kosong');
                    document.getElementById("nquantity" + i).value = stok;
                    return true;
                    break;
                } */
            }
            return false;
        } else {
            var material = $('#idproduct'+id).val();
            var nquantity = parseFloat(document.getElementById("nquantity" + id).value);
            var stok = parseFloat(document.getElementById("stok" + id).value);
            var qty = parseFloat($('#nquantity'+id).val());
            for (let i = 1; i <= $('#jml').val(); i++) {
                if (material == $('#idproduct'+i).val() && id != i) {
                    qty += parseFloat($('#nquantity'+i).val());
                }
            }
            // console.log(qty);
            if (parseFloat(qty) > parseFloat(stok)) {
                swal('Quantity Kirim Tidak Boleh Melebihi \nSaldo akhir ' + stok);
                document.getElementById("nquantity" + id).value = 0;
            }
            /* if (parseFloat(qty) == 0 && parseFloat(qty) == '') {
                swal('Quantity Kirim Tidak Boleh 0 atau Kosong');
                document.getElementById("nquantity" + id).value = 0;
            } */
        }
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