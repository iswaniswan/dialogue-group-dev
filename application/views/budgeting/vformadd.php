<style>
    .dropify-wrapper {
        height: 118px !important;
    }

    .table>tbody>tr>td,
    .table>tbody>tr>th,
    .table>tfoot>tr>td,
    .table>tfoot>tr>th,
    .table>thead>tr>td,
    .table>thead>tr>th {
        padding: 3px 3px !important;
        vertical-align: middle !important;
        white-space: nowrap !important;
    }
</style>
<form id="cekinputan">
    <div class="row">
        <div class="col-lg-8">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <i class="fa fa-plus"></i> &nbsp; <?= $title; ?>
                    <!-- <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp;
                        <?= $title_list; ?></a> -->
                </div>
                <div class="panel-body">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-3">Bagian Pembuat</label>
                            <label class="col-md-3">Nomor Dokumen</label>
                            <label class="col-md-3">Tanggal Dokumen</label>
                            <label class="col-md-3">Periode Forecast Jahit</label>
                            <div class="col-sm-3">
                                <select name="ibagian" id="ibagian" class="form-control select2" required="">
                                    <?php if ($bagian) {
                                        foreach ($bagian as $row) : ?>
                                            <option value="<?= $row->i_bagian; ?>">
                                                <?= $row->e_bagian_name; ?>
                                            </option>
                                    <?php endforeach;
                                    } ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="hidden" name="id" id="id">
                                    <input type="text" name="idocument" required="" id="ibudgeting" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number; ?>" maxlength="25" class="form-control input-sm" value="">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <input type="text" id="ddocument" name="ddocument" class="form-control input-sm date" required="" readonly value="<?= date("d-m-Y"); ?>">
                            </div>
                            <div class="col-sm-3">
                                <input type="hidden" id="idforecast" name="idforecast" required="" value="<?= $id; ?>">
                                <input type="hidden" id="iperiode" name="iperiode" required="" value="<?= $tahun . $bulan; ?>">
                                <input type="text" class="form-control input-sm" readonly value="<?= $this->fungsi->mbulan($bulan) . ' ' . $tahun; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-12">Keterangan</label>
                            <div class="col-sm-12">
                                <textarea id="eremark" name="eremark" class="form-control input-sm" placeholder="Isi keterangan jika ada!"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <button type="button" id="submit" class="btn btn-success btn-block btn-sm"><i class="fa fa-lg mr-2 fa-save"></i>Simpan</button>
                            </div>
                            <div class="col-sm-4">
                                <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder; ?>/cform/indexx/<?= $dfrom . "/" . $dto; ?>','#main')"> <i class="fa fa-lg mr-2 fa-arrow-circle-left"></i>Kembali</button>
                            </div>
                            <div class="col-sm-4">
                                <button type="button" id="send" disabled="true" class="btn btn-primary btn-block btn-sm"><i class="fa fa-paper-plane-o fa-lg mr-2"></i>Send</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <i class=""></i><?= "Input With Excel"; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom; ?>/<?= $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list mr-2"></i> <?= $title_list; ?></a>
                </div>
                <div class="panel-body">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-6">Upload File (Optional)</label>
                            <label class="col-md-6 text-right notekode">Formatnya .xls</label>
                            <div class="col-sm-12">
                                <input type="file" id="input-file-now" name="userfile" class="dropify" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <button type="button" id="upload" class="btn btn-success btn-block btn-sm"><i class="fa fa-upload mr-1 mr-2"></i>Upload</button>
                            </div>
                            <div class="col-md-6">
                                <a id="href" onclick="return export_data();"><button type="button" class="btn btn-primary btn-block btn-sm"><i class="fa fa-download mr-2"></i>Download Template</button> </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $i = 0;
    if ($datadetail) { ?>
    <?php } else { ?>
        <div class="white-box">
            <div class="card card-outline-danger text-center text-dark">
                <div class="card-block">
                    <footer>
                        <cite title="Source Title"><b>Item Tidak Ada</b></cite>
                    </footer>
                </div>
            </div>
        </div>
    <?php } ?>
    <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">

    <?php $i = 0;
    if ($bisbisan) { ?>
    <?php } else { ?>
        <div class="white-box">
            <div class="card card-outline-danger text-center text-dark">
                <div class="card-block">
                    <footer>
                        <cite title="Source Title"><b>Item Bisbisan Tidak Ada</b></cite>
                    </footer>
                </div>
            </div>
        </div>
    <?php } ?>

    <?php $x = 0;
    if ($datadetaill) { ?>
        <div class="white-box" id="detail">
            <div class="col-sm-6">
                <h3 class="box-title m-b-0">Detail Item Material</h3>
            </div>
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table id="sitabel" class="table color-table tableFixHead success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                        <thead>
                            <tr>
                                <th class="text-center" width="3%;">No</th>
                                <th class="text-center">Kode</th>
                                <th class="text-center">Nama Barang</th>
                                <th class="text-center">Kebutuhan <br>
                                    <i class="fa fa-question-circle fa-lg ml-2" tabindex="0" data-trigger="focus" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" title="Hasil konversi dari fc produk ke material (otomatis 0 untuk acc packing)"></i>
                                </th>
                                <th class="text-center">Acc Pelengkap <br>
                                    <i class="fa fa-question-circle fa-lg ml-2" tabindex="0" data-trigger="focus" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" title="Khusus Acc Packing , max(fc berjalan, do) + fc dist - Stok gudang jadi - Stok packing"></i>
                                </th>
                                <th class="text-center">Stok Gudang <br>
                                    <i class="fa fa-question-circle fa-lg ml-2" tabindex="0" data-trigger="focus" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" title="Stok terupdate pada saat budgeting qty dibuat"></i>
                                </th>
                                <th class="text-center">Sisa Schedule <br>Belum Terkirim <br>
                                    <i class="fa fa-question-circle fa-lg ml-2" tabindex="0" data-trigger="focus" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" title="Sisa schedule yang belum terkirim"></i>
                                </th>
                                <th class="text-center">Total Perhitungan <br>Kebutuhan <br>
                                    <i class="fa fa-question-circle fa-lg ml-2" tabindex="0" data-trigger="focus" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" title="Kebutuhan + acc pelengkap - stok gudang + sisa schedule"></i>
                                </th>
                                <th class="text-center">Satuan <br>Pemakaian</th>
                                <th class="text-center">OP Sisa <br>
                                    <i class="fa fa-question-circle fa-lg ml-2" tabindex="0" data-trigger="focus" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" title="Sisa OP Belum Diterima"></i>
                                </th>
                                <th class="text-center">Budgeting <br> Perhitungan</th>
                                <th class="text-center">Up Qty</th>
                                <th class="text-center">Aktual</th>
                                <th class="text-center">Satuan <br> Pembelian</th>
                                <th class="text-center">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 0;
                            $group = "";
                            foreach ($datadetaill as $row) {
                                $x++;
                                $no++;


                                $budgeting = $row->kebutuhan - ($row->mutasi) + $row->acc_pelengkap;

                                if ($row->e_operator != null) {
                                    $hitungan = my_operator($budgeting, $row->n_faktor, $row->e_operator) + $row->estimasi;
                                } else {
                                    $hitungan = $budgeting + $row->estimasi;
                                }

                                if ($group != $row->e_nama_group_barang) { ?>
                                    <tr class="table-active">
                                        <td colspan="15"><b><?= $row->e_nama_group_barang; ?></b></td=>
                                    </tr>
                                <?php }
                                $group = $row->e_nama_group_barang;

                                ?>
                                <tr>
                                    <td class="text-center"><?= $no; ?></td>
                                    <td>
                                        <input readonly type="text" class="form-control input-sm w90" value="<?= $row->i_material; ?>">
                                    </td>
                                    <td>
                                        <input readonly type="text" class="form-control input-sm w350" value="<?= ucwords(strtolower($row->e_material_name)); ?>">
                                    </td>
                                    <td class="text-right">
                                        <input readonly type="text" class="form-control input-sm text-right w90" name="budgeting_awal<?= $x; ?>" id="budgeting_awal<?= $x; ?>" autocomplete="off" onkeyup="angkahungkul(this); hetang(<?= $x; ?>);" onblur="if(this.value==''){this.value='0';hetang(<?= $x; ?>);}" onfocus="if(this.value=='0'){this.value='';}" value="<?= number_format($row->kebutuhan, 2); ?>">
                                    </td>

                                    <td class="text-right">
                                        <input readonly type="text" class="form-control input-sm text-right w90" name="acc_pelengkap<?= $x; ?>" id="acc_pelengkap<?= $x; ?>" autocomplete="off" onkeyup="angkahungkul(this); hetang(<?= $x; ?>);" onblur="if(this.value==''){this.value='0';hetang(<?= $x; ?>);}" onfocus="if(this.value=='0'){this.value='';}" value="<?= number_format($row->acc_pelengkap, 2); ?>">
                                    </td>

                                    <td class="text-right">
                                        <input readonly type="text" class="form-control input-sm text-right w90" name="stok<?= $x; ?>" id="stok<?= $x; ?>" autocomplete="off" onkeyup="angkahungkul(this); hetang(<?= $x; ?>);" onblur="if(this.value==''){this.value='0';hetang(<?= $x; ?>);}" onfocus="if(this.value=='0'){this.value='';}" value="<?= number_format($row->mutasi, 2); ?>">
                                    </td>
                                    <td class="text-right">
                                        <input type="text" class="form-control input-sm text-right w90" name="schedule<?= $x; ?>" id="schedule<?= $x; ?>" autocomplete="off" onkeyup="angkahungkul(this); hetang(<?= $x; ?>);" onblur="if(this.value==''){this.value='0';hetang(<?= $x; ?>);}" onfocus="if(this.value=='0'){this.value='';}" value="0">
                                    </td>
                                    <td class="text-right">
                                        <input readonly type="text" class="form-control input-sm text-right w90" name="total_kebutuhan<?= $x; ?>" id="total_kebutuhan<?= $x; ?>" autocomplete="off" onkeyup="angkahungkul(this); hetang(<?= $x; ?>);" onblur="if(this.value==''){this.value='0';hetang(<?= $x; ?>);}" onfocus="if(this.value=='0'){this.value='';}" value="<?= number_format($budgeting, 2); ?>">
                                    </td>

                                    <td class="text-left"><?= ucwords(strtolower($row->e_satuan_name)); ?></td>
                                    <td class="text-right">
                                        <input type="text" class="form-control input-sm text-right w90" name="sisaop<?= $x; ?>" id="sisaop<?= $x; ?>" autocomplete="off" onkeyup="angkahungkul(this); hetang(<?= $x; ?>);" onblur="if(this.value==''){this.value='0';hetang(<?= $x; ?>);}" onfocus="if(this.value=='0'){this.value='';}" value="<?= number_format($row->estimasi); ?>">
                                    </td>
                                    <td class="text-right">
                                        <input readonly type="text" class="form-control input-sm text-right w90" name="nilai_budgeting<?= $x; ?>" id="nilai_budgeting<?= $x; ?>" autocomplete="off" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" onkeyup="angkahungkul(this);" value="<?= number_format($hitungan, 2); ?>">
                                    </td>

                                    <td class="text-right">
                                        <input type="text" class="form-control input-sm text-right w90" name="up<?= $x; ?>" id="up<?= $x; ?>" autocomplete="off" onkeyup="angkahungkul(this); hetang(<?= $x; ?>);" onblur="if(this.value==''){this.value='0';hetang(<?= $x; ?>);}" onfocus="if(this.value=='0'){this.value='';}" value="0">
                                    </td>

                                    <td class="text-right">
                                        <input readonly type="text" class="form-control input-sm text-right w90" name="nilai_actual<?= $x; ?>" id="nilai_actual<?= $x; ?>" autocomplete="off" value="<?= number_format($hitungan, 2); ?>">
                                    </td>
                                    <td class="text-left"><?= ucwords(strtolower($row->e_satuan_konversi)); ?></td>
                                    <td>
                                        <input type="text" class="form-control input-sm w150" name="ket<?= $x; ?>" id="ket<?= $x; ?>" autocomplete="off">
                                        <input type="hidden" name="id_material_item<?= $x; ?>" id="id_material_item<?= $x; ?>" value="<?= $row->id_material; ?>">
                                        <input type="hidden" name="i_satuan_konversi<?= $x; ?>" value="<?= $row->i_satuan_konversi; ?>">
                                        <input type="hidden" name="e_operator<?= $x; ?>" id="e_operator<?= $x; ?>" value="<?= $row->e_operator; ?>">
                                        <input type="hidden" name="n_faktor<?= $x; ?>" id="n_faktor<?= $x; ?>" value="<?= $row->n_faktor; ?>">
                                    </td>
                                </tr>
                            <?php
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php } else { ?>
        <div class="white-box">
            <div class="card card-outline-danger text-center text-dark">
                <div class="card-block">
                    <footer>
                        <cite title="Source Title"><b>Item Tidak Ada</b></cite>
                    </footer>
                </div>
            </div>
        </div>
    <?php } ?>
    <input type="hidden" name="jml_item" id="jml_item" value="<?= $x; ?>">
</form>
<script src="<?= base_url(); ?>assets/js/jquery.validate.min.js"></script>
<script>
    /*----------  LOAD SAAT DOKUMEN DIBUKA  ----------*/
    $(document).ready(function() {

        $('.dropify').dropify();
        /*----------  Load Form Validation  ----------*/
        $('#cekinputan').validate({
            errorClass: "my-error-class",
            validClass: "my-valid-class"
        });

        // $('#ibudgeting').mask('SSS-0000-000000S');
        $('.select2').select2();
        /*----------  Tanggal tidak boleh kurang dari hari ini!  ----------*/
        showCalendar('.date', 0);
        number();

        // fixedtable($('.table'));
        var $table = $('.table');

        function buildTable(elm) {
            elm.bootstrapTable('destroy').bootstrapTable({
                height: 400,
                // columns          : columns,
                // data             : data,
                search: false,
                showColumns: false,
                // showToggle       : true,
                // clickToSelect    : true,
                fixedColumns: true,
                fixedNumber: 3,
                // fixedRightNumber: 1
            })
        }

        $(function() {
            buildTable($table)
            popover();
        });

        popover();


        $("#upload").on("click", function() {
            var idforecast = $('#idforecast').val();
            if (idforecast.length > 0) {
                var formData = new FormData();
                formData.append('userfile', $('input[type=file]')[0].files[0]);
                formData.append('idforecast', idforecast);
                $.ajax({
                    type: "POST",
                    url: "<?= base_url($folder . '/cform/load'); ?>",
                    data: formData,
                    processData: false,
                    contentType: false,
                    cache: false,
                    async: false,
                    success: function(data) {
                        var json = JSON.parse(data);
                        var status = json.status;
                        var detail = json.datadetail;
                        if (status == 'berhasil') {
                            if (detail.length > 0) {
                                for (let i = 1; i <= $('#jml_item').val(); i++) {
                                    const id_material_item = $('#id_material_item'+i).val();
                                    for (let j = 0; j < detail.length; j++) {
                                        const id_material = detail[j]['id_material'];
                                        const n_sisa_schedule = detail[j]['n_sisa_schedule'];
                                        const n_op_sisa = detail[j]['n_op_sisa'];
                                        const n_up_qty = detail[j]['n_up_qty'];
                                        const e_remark = detail[j]['e_remark'];
                                        if (id_material_item === id_material) {
                                            $('#schedule'+i).val(n_sisa_schedule);
                                            $('#sisaop'+i).val(n_op_sisa);
                                            $('#up'+i).val(n_up_qty);
                                            $('#ket'+i).val(e_remark);
                                        }
                                        
                                    }
                                    hetang(i);
                                }
                            }
                        } else {
                            swal({
                                title: "Gagal!",
                                text: "File Gagal Diupload :)",
                                type: "error",
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }

                    },
                });
            } else {
                swal({
                    title: "Maaf!",
                    text: "Referensi tidak boleh kosong :)",
                    type: "info",
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        });
    });

    /*----------  RUBAH NO DOKUMEN (GANTI TANGGAL & BAGIAN)  ----------*/
    $('#ibagian, #ddocument').change(function(event) {
        number();
    });

    /*----------  RUNNING NUMBER DOKUMEN  ----------*/
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
                $('#ibudgeting').val(data);
            },
            error: function() {
                swal('Error :)');
            }
        });
    }

    /*----------  UPDATE STATUS DOKUMEN  ----------*/
    $('#send').click(function(event) {
        statuschange('<?= $folder; ?>', $('#id').val(), '2', '<?= $dfrom . "','" . $dto; ?>');
    });

    /*----------  CEKLIS NO DOKUMEN (MANUAL)  ----------*/
    $('#ceklis').click(function(event) {
        if ($('#ceklis').is(':checked')) {
            $("#ibudgeting").attr("readonly", false);
        } else {
            $("#ibudgeting").attr("readonly", true);
            $("#ada").attr("hidden", true);
            number();
        }
    });

    /*----------  CEK NO DOKUMEN  ----------*/
    $("#ibudgeting").keyup(function() {
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
                swal('Error :)');
            }
        });
    });

    /*----------  VALIDASI UPDATE DATA  ----------*/
    $("#submit").click(function(event) {
        $("table").find("*").attr("disabled", true);
        $("#sitabel").find("*").attr("disabled", false);
        var valid = $("#cekinputan").valid();
        if (valid) {
            ada = false;
            if ($('#jml_item').val() == 0) {
                swal('Isi item minimal 1!');
                return false;
            } else {
                /* for (var i = 1; i <= $('#jml_item').val(); i++) {
                    if (parseInt($('#nilai_budgeting' + i).val()) == 0 || parseInt($('#nilai_budgeting' + i).val()) == null) {
                        swal("Maaf :(","Nilai Budgeting harus lebih besar dari 0!","error");
                        ada = true;
                        return false;
                    }
                } */
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
                                    swal("Sukses!", "No Dokumen : " + data.kode +
                                        ", Berhasil Disimpan :)", "success");
                                    $("input").attr("disabled", true);
                                    $("select").attr("disabled", true);
                                    $("#submit").attr("disabled", true);
                                    $("#addrow").attr("disabled", true);
                                    $("#send").attr("hidden", false);
                                } else if (data.sukses == 'ada') {
                                    swal("Maaf :(", "No Dokumen : " + data.kode +
                                        ", Sudah Ada :(", "error");
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
                } else {
                    swal('Maaf :(', 'Total Jumlah Retur harus lebih besar dari 0 !', 'error');
                    return false;
                }
            }
        }
        return false;
    })

    function hetang(i) {

        let stok = parseFloat(formatulang($('#stok' + i).val()));
        let sisaop = parseFloat(formatulang($('#sisaop' + i).val()));
        let schedule = parseFloat(formatulang($('#schedule' + i).val()));
        let budgeting_awal = parseFloat(formatulang($('#budgeting_awal' + i).val()));
        let acc_pelengkap = parseFloat(formatulang($('#acc_pelengkap' + i).val()));


        let up = parseFloat(formatulang($('#up' + i).val()));
        // let nilai_budgeting = parseFloat($('#nilai_budgeting'+i).val());
        // let nilai_actual = parseFloat($('#nilai_actual'+i).val());

        var e_operator = $('#e_operator' + i).val();

        var subtotal = (budgeting_awal - (stok)) + acc_pelengkap + schedule;
        //console.log(budgeting_awal + " " + (stok) + " " + acc_pelengkap + " " + schedule);
        $('#total_kebutuhan' + i).val(number_format(subtotal, 2));

        var nilai_budgeting = subtotal;
        var nilai_actual = 0;

        if (e_operator != null && e_operator != "") {
            let n_faktor = parseFloat($('#n_faktor' + i).val());
            nilai_budgeting = my_operator(subtotal, n_faktor, e_operator) - sisaop;
            //eval(subtotal +  e_operator + n_faktor );
            // console.log(
            //     nilai_budgeting + " " + nilai_actual + " " + subtotal + "\n" +
            //     e_operator + " " + budgeting_awal + " " + stok + " " + n_faktor
            // );
        } else {
            nilai_budgeting = subtotal - sisaop;
        }

        if (nilai_budgeting < 0) {
            nilai_actual = up;
        } else {
            nilai_actual = nilai_budgeting + up;
        }

        $('#nilai_budgeting' + i).val(number_format(nilai_budgeting, 2));
        $('#nilai_actual' + i).val(number_format(nilai_actual, 2));
    }

    function my_operator(a, b, char) {
        switch (char) {
            case '=':
                return a = b;
            case '*':
                return a * b;
            case '+':
                return a + b;
            case '/':
                return a / b;
            case '-':
                return a - b;
        }
    }

    function export_data() {
        var idforecast = '<?= $id; ?>';
        if (idforecast == '') {
            swal('Referensi Schedule Kosong!!!');
            return false;
        } else {
            $('#href').attr('href', '<?php echo site_url($folder . '/cform/export/' . $tahun . '/' . $bulan . '/'); ?>' + idforecast);
            return true;
        }
    }
</script>