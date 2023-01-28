<form id="cekinputan">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <i class="fa fa-plus fa-lg mr-2"></i><?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list fa-lg mr-2"></i><?= $title_list; ?></a>
                </div>
                <div class="panel-body table-responsive">
                    <div id="pesan"></div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-3">Bagian Pembuat</label>
                            <label class="col-md-3">Nomor Dokumen</label>
                            <label class="col-md-3">Tanggal Dokumen</label>
                            <label class="col-md-3">Referensi</label>
                            
                            <div class="col-sm-3">
                                <select name="i_bagian" id="i_bagian" required="" class="form-control select2">            
                                    <?php foreach ($bagian->result() as $key) { ?>
                                        <option value="<?= trim($key->i_bagian); ?>"><?= $key->e_bagian_name; ?></option>
                                    <?php } ?>                                    
                                </select>
                            </div>
                            
                            <?php /*
                            <div class="col-sm-3">
                                <select name="i_bagian" id="i_bagian" class="form-control select2" required>
                                    <?php if ($bagian) {
                                        $group = "";
                                        foreach ($bagian as $row) : ?>
                                        <?php if ($group!=$row->name) {?>
                                        <optgroup label="<?= strtoupper(str_replace(".","",$row->name));?>">
                                            <?php }
                                            $group = $row->name;
                                            ?>
                                            <option value="<?= "$row->id_company|$row->i_bagian"; ?>">
                                                <?= $row->e_bagian_name; ?>
                                            </option>
                                        <?php endforeach;
                                    } ?>
                                    </optgroup>
                                </select>
                            </div>
                            */ ?>

                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="hidden" name="id" id="id" value="">
                                    <input type="text" name="i_document" required="" id="ibbm" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number; ?>" maxlength="16" class="form-control input-sm" value="">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <input type="text" name="d_document" required="" id="d_document" class="form-control input-sm date" value="<?= date('d-m-Y'); ?>" readonly>
                            </div>
                            <div class="col-sm-3">
                                <select name="i_referensi" id="i_referensi" required="" class="form-control input-sm select2">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-12">Keterangan</label>
                            <div class="col-sm-12">
                                <textarea type="text" id="e_remark" name="e_remark" maxlength="250" class="form-control input-sm" placeholder="Isi keterangan jika ada!"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <button type="button" id="submit" class="btn btn-success btn-block btn-sm"><i class="fa fa-save fa-lg mr-2"></i>Simpan</button>
                            </div>
                            <div class="col-sm-4">
                                <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main')"> <i class="fa fa-arrow-circle-left fa-lg mr-2"></i>Kembali</button>
                            </div>
                            <div class="col-sm-4">
                                <button type="button" disabled="true" id="send" class="btn btn-primary btn-block btn-sm"><i class="fa fa-paper-plane-o fa-lg mr-2"></i>Send</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="jml" id="jml" value="0">
    <div class="white-box" id="detail" hidden="true">
        <h3 class="box-title m-b-0">Detail Barang</h3>
        <div class="table-responsive">
            <table id="sitabel" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" width="3%">No</th>
                        <th width="10%">Kode</th>
                        <th width="30%">Nama Material</th>
                        <th width="12%">Satuan</th>
                        <th class="text-right" width="8%">Jml</th>
                        <th class="text-right" width="10%">Jml Terima</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</form>
<script src="<?= base_url(); ?>assets/js/jquery.validate.min.js"></script>
<script>
    /*----------  LOAD SAAT DOKUMEN READY  ----------*/

    $(document).ready(function() {
        fixedtable($('#sitabel'));
        /*----------  Load Form Validation  ----------*/
        $('#cekinputan').validate({
            errorClass: "my-error-class",
            validClass: "my-valid-class"
        });
        number();
        $('.select2').select2();

        /*----------  UPDATE STATUS DOKUMEN KE WAIT APPROVE ----------*/

        $('#send').click(function(event) {
            statuschange('<?= $folder; ?>', $('#id').val(), '2', '<?= $dfrom . "','" . $dto; ?>');
        });

        /*----------  UPDATE NO DOKUMEN SAAT BAGIAN PEMBUAT DAN TANGGAL DOKUMEN DIRUBAH  ----------*/

        $('#d_document, #i_bagian').change(function(event) {
            number();
        });

        const mergeLabel = (array) => {
            let indexes = [];
            return Object.values(array.reduce((obj, { id: id, name: text, text: item }) => {
                if (indexes.includes(id) == false) {
                    (obj[text] ??= { text, children: [] }).children.push({ id, text: item });
                }
                indexes.push(id);
                return obj;
            }, {}));
        }
        /*Tidak boleh lebih dari hari ini*/
        showCalendar('.date', null, 0);
        $('#i_referensi').select2({
            placeholder: 'Cari Referensi',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder . '/cform/referensi'); ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    var query = {
                        q: params.term,
                        i_bagian: $('#i_bagian').val(),
                    }
                    return query;
                },
                processResults: function(result) {
                    const data = mergeLabel(result);
                    return {
                        results: data
                    };
                },
                cache: true
            }
        }).change(function() {

            /*----------  GET DATA DETAIL AFTER CHANGE REFERENSI  ----------*/

            $("#sitabel").attr("hidden", false);
            $("#detail").attr("hidden", false);
            $("#sitabel tbody tr:gt(0)").remove();
            $("#jml").val(0);
            $.ajax({
                type: "post",
                data: {
                    'id': $(this).val(),
                    'i_bagian': $('#i_bagian').val(),
                },
                url: '<?= base_url($folder . '/cform/detail_referensi'); ?>',
                dataType: "json",
                success: function(data) {
                    if (data['detail'] != null) {
                        $('#jml').val(data['detail'].length);
                        for (let x = 0; x < data['detail'].length; x++) {
                            var cols = "";
                            var newRow = $("<tr>");
                            cols += '<td class="text-center">' + (x + 1) + '</td>';
                            cols += '<td><input class="form-control input-sm" readonly type="text" id="i_material' + x + '" name="i_material' + x + '" value="' + data['detail'][x]['i_material'] + '"><input type="hidden" id="id_material' + x + '" name="id_material' + x + '" value="' + data['detail'][x]['id_material'] + '"></td>';
                            cols += '<td><input class="form-control input-sm" readonly type="text" id="e_material_name' + x + '" name="e_material_name' + x + '" value="' + data['detail'][x]['e_material_name'] + '"></td>';
                            cols += '<td><input readonly class="form-control input-sm" type="text" id="e_satuan_name' + x + '" name="e_satuan_name' + x + '" value="' + data['detail'][x]['e_satuan_name'] + '"></td>';
                            cols += '<td><input readonly class="form-control input-sm text-right" type="text" id="n_quantity_referensi' + x + '" name="n_quantity_referensi' + x + '" value="' + data['detail'][x]['n_quantity_sisa'] + '"></td>';
                            cols += '<td><input class="form-control input-sm text-right" type="number" id="n_quantity' + x + '" name="n_quantity' + x + '" value="' + data['detail'][x]['n_quantity_sisa'] + '" placeholder="0" onkeypress="return hanyaAngka(event);" onkeyup="ngetang(' + x + ');"></td>';
                            cols += '<td><input type="text" class="form-control input-sm" placeholder="Isi keterangan jika ada!" name="e_remark_item' + x + '"></td>';
                            newRow.append(cols);
                            $("#sitabel").append(newRow);
                        }
                    }
                },
                error: function() {
                    swal('Ada kesalahan :(');
                }
            })
        });

        /*----------  VALIDASI SAAT MENEKAN TOMBOL SIMPAN  ----------*/
        $("#submit").click(function(event) {
            var valid = $("#cekinputan").valid();
            if (valid) {
                ada = false;
                if ($("#jml").val() == 0) {
                    swal('Isi data item minimal 1 !!!');
                    return false;
                } else {
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
                                    swal("Sukses!", "No Dokumen : " + data.kode +
                                        ", Berhasil Disimpan :)", "success");
                                    $("input").attr("disabled", true);
                                    $("select").attr("disabled", true);
                                    $("#submit").attr("disabled", true);
                                    $("#addrow").attr("disabled", true);
                                    $("#send").attr("disabled", false);
                                } else if (data.sukses == 'ada') {
                                    swal("Maaf :(", "Data tersebut sudah ada :(", "error");
                                } else {
                                    swal("Maaf :(", "No Dokumen : " + data.kode +
                                        ", Gagal Disimpan :(", "error");
                                }
                            },
                            error: function() {
                                swal("Maaf", "Data Gagal Disimpan :(", "error");
                            }
                        });
                    });
                }
            }
            return false;
        });
    });

    /*----------  CEK SALDO  ----------*/
    function ngetang(i) {
        if (parseFloat($('#n_quantity' + i).val()) > parseFloat($('#n_quantity_referensi' + i).val())) {
            swal('Maaf :(','Jml terima = '+$('#n_quantity' + i).val()+' tidak boleh lebih dari jml sisa = '+$('#n_quantity_referensi' + i).val()+'','error');
            $('#n_quantity' + i).val($('#n_quantity_referensi' + i).val());
        }
    }

    /*----------  NOMOR DOKUMEN  ----------*/

    function number() {
        $.ajax({
            type: "post",
            data: {
                'tgl': $('#d_document').val(),
                'i_bagian': $('#i_bagian').val(),
            },
            url: '<?= base_url($folder . '/cform/number'); ?>',
            dataType: "json",
            success: function(data) {
                $('#ibbm').val(data);
            },
            error: function() {
                swal('Error :(');
            }
        });
    }
</script>