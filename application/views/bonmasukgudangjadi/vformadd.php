<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i>  <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-3">Tanggal Dokumen</label>
                        <label class="col-md-3">Bagian Pengirim</label>
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" required="" class="form-control select2">
                                <?php if ($bagian) {
                                    foreach ($bagian->result() as $key) { ?>
                                        <option value="<?= trim($key->i_bagian); ?>"><?= $key->e_bagian_name; ?></option>
                                <?php }
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" name="idocument" required="" id="ibbm" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number; ?>" maxlength="16" class="form-control input-sm" value="" aria-label="Text input with dropdown button">
                                <!-- <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span> -->
                            </div>
                            <span class="notekode">Format : (<?= $number; ?>)</span><br>
                            <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" name="ddocument" required="" id="ddocument" class="form-control input-sm date" value="<?= date('d-m-Y'); ?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <select name="ipengirim" required="" id="ipengirim" class="form-control select2" data-placeholder="Pilih Pengirim">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Nomor Dokumen Reff</label>
                        <label class="col-md-3">Jenis Barang</label>
                        <label class="col-md-6">Keterangan</label>
                        <div class="col-sm-3">
                            <select name="ireff" id="ireff" required="" class="form-control input-sm select2"></select>
                        </div>
                        <div class="col-sm-3">
                            <!-- <select name="ijenis" id="ijenis" class="form-control select2">
                                <?php if ($jenisbarang) {
                                    foreach ($jenisbarang as $row) : ?>
                                        <option value="<?= $row->id; ?>">
                                            <?= $row->e_jenis_name; ?>
                                        </option>
                                    <?php endforeach;
                                } ?>
                            </select> -->
                            <input type="text" readonly class="form-control input-sm" name="jenis_barang" id="jenis_barang" value="" placeholder="Jenis Barang Masuk">
                            <input type="hidden" class="form-control input-sm" name="id_jenis_barang" id="id_jenis_barang" value="">
                        </div>
                        <div class="col-sm-6">
                            <textarea type="text" id="eremark" name="eremark" maxlength="250" class="form-control input-sm" placeholder="Isi keterangan jika ada!"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <button type="submit" id="submit" class="btn btn-success btn-block btn-sm"><i class="fa fa-save mr-2"></i>Simpan</button>
                        </div>
                        <div class="col-sm-4">
                            <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main')"> <i class="fa fa-arrow-circle-left mr-2"></i>Kembali</button>
                        </div>
                        <div class="col-sm-4">
                            <button type="button" hidden="true" id="send" class="btn btn-primary btn-block btn-sm"><i class="fa fa-paper-plane-o mr-2"></i>Send</button>
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
        <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%" hidden="true">
            <thead>
                <tr>
                    <th class="text-center" width="3%">No</th>
                    <th class="text-center" width="10%">Kode</th>
                    <th class="text-center" width="30%">Nama Barang</th>
                    <th class="text-center" width="12%">Warna</th>
                    <th class="text-center" width="8%">Qty</th>
                    <th class="text-center" width="10%">Qty Terima</th>
                    <th class="text-center">Keterangan</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
</form>
<!-- <script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script> -->
<script>
    /*----------  LOAD SAAT DOKUMEN READY  ----------*/

    $(document).ready(function() {
        // $('#ibbm').mask('SSS-0000-000000S');
        number();
        $('.select2').select2();
        /*Tidak boleh lebih dari hari ini*/
        showCalendar('.date', null, 0);

        $('#ipengirim').select2({
            placeholder: 'Pilih Pengirim',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder . '/cform/pengirim'); ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    var query = {
                        q: params.term,
                        ibagian: $('#ibagian').val(),
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
        }).change(function(event) {
            $('#ireff').val('');
            $('#ireff').html('');
            $("#tabledatax tr:gt(0)").remove();
            $("#jml").val(0);
        });

        $('#ireff').select2({
            placeholder: 'Cari Referensi',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder . '/cform/referensi'); ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    var query = {
                        q: params.term,
                        ipengirim: $('#ipengirim').val(),
                        ibagian: $('#ibagian').val(),
                    }
                    return query;
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        }).change(function() {

            /*----------  GET DATA DETAIL AFTER CHANGE REFERENSI  ----------*/

            $("#tabledatax").attr("hidden", false);
            $("#detail").attr("hidden", false);
            $("#tabledatax tr:gt(0)").remove();
            $("#jml").val(0);
            $.ajax({
                type: "post",
                data: {
                    'id': $(this).val(),
                    'ibagian': $('#ibagian').val(),
                    'ipengirim': $('#ipengirim').val(),
                },
                url: '<?= base_url($folder . '/cform/detailreferensi'); ?>',
                dataType: "json",
                success: function(data) {
                    if (data['data'].length > 0) {
                        $('#jenis_barang').val(data['data'][0]['e_jenis_name']);
                        $('#id_jenis_barang').val(data['data'][0]['id_jenis_barang_keluar']);
                    }
                    if (data['detail'].length > 0) {
                        $('#jml').val(data['detail'].length);
                        for (let x = 0; x < data['detail'].length; x++) {
                            var cols = "";
                            var newRow = $("<tr>");
                            cols += '<td class="text-center">' + (x + 1) + '</td>';
                            cols += '<td><input class="form-control input-sm" readonly type="text" id="iproduct' + x + '" name="iproduct' + x + '" value="' + data['detail'][x]['i_product'] + '"><input type="hidden" id="idproduct' + x + '" name="idproduct' + x + '" value="' + data['detail'][x]['id_product'] + '"></td>';
                            cols += '<td><input class="form-control input-sm" readonly type="text" id="eproduct' + x + '" name="eproduct' + x + '" value="' + data['detail'][x]['e_product'] + '"></td>';
                            cols += '<td><input readonly class="form-control input-sm" type="text" id="ecolor' + x + '" name="ecolor' + x + '" value="' + data['detail'][x]['e_color_name'] + '"></td>';
                            cols += '<td><input readonly class="form-control input-sm text-right" type="text" id="nquantity' + x + '" name="nquantity' + x + '" value="' + data['detail'][x]['n_quantity_sisa'] + '"></td>';
                            cols += '<td><input class="form-control input-sm text-right" type="text" id="npemenuhan' + x + '" name="npemenuhan' + x + '" value="' + data['detail'][x]['n_quantity_sisa'] + '" placeholder="0" onkeypress="return hanyaAngka(event);" onkeyup="ceksaldo(' + x + ');"></td>';
                            cols += '<td><input type="text" class="form-control input-sm" placeholder="Isi keterangan jika ada!" name="eremark' + x + '"></td>';
                            newRow.append(cols);
                            $("#tabledatax").append(newRow);
                        }
                    }
                },
                error: function() {
                    swal('Ada kesalahan :(');
                }
            })
        });
    });

    /*----------  CEK SALDO  ----------*/
    function ceksaldo(i) {
        if (parseFloat($('#npemenuhan' + i).val()) > parseFloat($('#nquantity' + i).val())) {
            swal('Qty terima tidak boleh lebih dari qty sisa!!!');
            $('#npemenuhan' + i).val($('#nquantity' + i).val());
        }
    }

    /*----------  NOMOR DOKUMEN  ----------*/

    function number() {
        $.ajax({
            type: "post",
            data: {
                'tgl': $('#ddocument').val(),
                'ibagian': $('#ibagian').val(),
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

    /*----------  KONDISI PAS CHECKBOX DI NO DOKUMEN DIKLIK  ----------*/

    $('#ceklis').click(function(event) {
        if ($('#ceklis').is(':checked')) {
            $("#ibbm").attr("readonly", false);
        } else {
            $("#ibbm").attr("readonly", true);
            $("#ada").attr("hidden", true);
            number();
        }
    });

    /*----------  CEK NO DOKUMEN SAAT DIKETIK  ----------*/

    $("#ibbm").keyup(function() {
        $.ajax({
            type: "post",
            data: {
                'kode': $(this).val(),
                'ibagian': $('#ibagian').val(),
            },
            url: '<?= base_url($folder . '/cform/cekkode'); ?>',
            dataType: "json",
            success: function(data) {
                if (data == 1) {
                    $("#ada").attr("hidden", false);
                    $("#submit").attr("disabled", true);
                } else {
                    $("#ada").attr("hidden", true);
                    $("#submit").attr("disabled", false);
                }
            },
            error: function() {
                swal('Error :(');
            }
        });
    });

    /*----------  UPDATE STATUS DOKUMEN KE WAIT APPROVE ----------*/

    $('#send').click(function(event) {
        statuschange('<?= $folder; ?>', $('#id').val(), '2', '<?= $dfrom . "','" . $dto; ?>');
    });

    /*----------  UPDATE NO DOKUMEN SAAT BAGIAN PEMBUAT DAN TANGGAL DOKUMEN DIRUBAH  ----------*/

    $('#ddocument, #ibagian').change(function(event) {
        number();
    });

    /*----------  VALIDASI SAAT MENEKAN TOMBOL SIMPAN  ----------*/

    $('#submit').click(function(event) {
        if ($("#jml").val() == 0) {
            swal('Isi data item minimal 1 !!!');
            return false;
            // }else{
            //     for (var i = 0; i < $("#jml").val(); i++) {
            //         if($("#npemenuhan"+i).val()=='' || $("#npemenuhan"+i).val()==null || $("#npemenuhan"+i).val()==0){
            //             swal('Jumlah Pemenuhan Harus Lebih Besar Dari 0!');
            //             return false;
            //         }
            //     }
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

    const mergeLabel = (data) => {
        let _data = data.reduce((result, item) => {
            if (result[item.name]) {
                result[item.name].children.forEach((e) => {
                    if (e.id !== item.id) {
                        return result[item.name].children.push({
                            id:item.id, text:item.text
                        });
                    }
                })
                return result;
            }

            (result[item.name] ??= { text:item.name, children: [] }).children.push({
                id:item.id, text:item.text
            })
            
            return result;
        }, {});

        console.log(Object.values(_data));
        return Object.values(_data);
    }

</script>