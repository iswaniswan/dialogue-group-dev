<style type="text/css">
    table,
    tr,
    td {
        /* border: 1px inset #C0C0C0 !important; */
        border-collapse: collapse;
        border-spacing: 0;
        border-top: 1px inset white !important;
        border-right: 1px inset #C0C0C0 !important;
        border-bottom: 1px inset white !important;
        border-left: 1px inset #C0C0C0 !important;
    }

    table,
    tr,
    th {
        border: 1px inset #4c5667 !important;
        border-collapse: collapse;
        border-spacing: 0;
        border-top: 1px solid #ff0;
        border-right: 2px dashed #f0F;
        border-bottom: 3px dotted #f00;
        border-left: 5px solid #09f;
    }

    tr:hover {
        border: 2px solid #FFD700 !important;
    }

    .select2-results__options {
        font-size: 14px !important;
    }

    .select2-selection__rendered {
        font-size: 12px;
    }

    .pudding {
        padding-left: 3px;
        padding-right: 3px;
        font-size: 14px;
        background-color: #e1f1e4;
    }

    .font-11 {
        padding-left: 3px;
        padding-right: 3px;
        font-size: 11px;
        height: 20px;
    }

    .font-12 {
        padding-left: 3px;
        padding-right: 3px;
        font-size: 12px;
    }



    .font-13 {
        padding-left: 3px;
        padding-right: 3px;
        font-size: 13px;
    }

    .nowrap {
        white-space: nowrap !important;
    }
</style>
<form id="cekinputan">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp;
                        <?= $title_list; ?></a>
                </div>
                <div class="panel-body table-responsive">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-3">Bagian Pembuat</label>
                            <label class="col-md-3">Nomor Dokumen</label>
                            <label class="col-md-3">Tanggal Dokumen</label>
                            <label class="col-md-3">Periode Forecast Produksi</label>
                            <div class="col-sm-3">
                                <select name="ibagian" id="ibagian" class="form-control select2" required="" disabled="">
                                    <?php if ($bagian) {
                                        foreach ($bagian as $row) : ?>
                                            <option value="<?= $row->i_bagian; ?>" <?php if ($row->i_bagian == $data->i_bagian) { ?> selected <?php } ?>>
                                                <?= $row->e_bagian_name; ?>
                                            </option>
                                    <?php endforeach;
                                    } ?>
                                </select>
                                <input type="hidden" name="ibagianold" id="ibagianold" value="<?= $data->i_bagian; ?>">
                            </div>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="hidden" name="id" id="id" value="<?= $id; ?>">
                                    <input type="hidden" name="idocumentold" id="ifccuttingold" value="<?= $data->i_document; ?>">
                                    <input type="text" name="idocument" required="" id="ifccutting" readonly="" autocomplete="off" onkeyup="gede(this);" maxlength="25" class="form-control input-sm" value="<?= $data->i_document; ?>" aria-label="Text input with dropdown button">
                                </div>
                                <span class="notekode">Format : (<?= $number; ?>)</span><br>
                                <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                            </div>
                            <div class="col-sm-3">
                                <input type="text" id="ddocument" name="ddocument" class="form-control input-sm date" required="" readonly value="<?= $data->d_document; ?>">
                            </div>
                            <div class="col-sm-3">
                                <input type="hidden" id="idforecast" name="idforecast" required="" value="<?= $data->id_referensi; ?>">
                                <input type="hidden" id="iperiode" name="iperiode" required="" value="<?= $data->tahun . $data->bulan; ?>">
                                <input type="text" class="form-control input-sm" readonly value="<?= $this->fungsi->mbulan($data->bulan) . ' ' . $data->tahun; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-12">Keterangan</label>
                            <div class="col-sm-12">
                                <textarea id="eremark" name="eremark" class="form-control" placeholder="Isi keterangan jika ada!" readonly><?= $data->e_remark; ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $i = 0;
    if ($datadetail) { ?>
        <div class="white-box" id="detail">
            <div class="col-sm-6">
                <h3 class="box-title m-b-0">Detail Item</h3>
            </div>
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table id="tabledatay" class="table color-table nowrap table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                        <thead>
                            <tr>
                                <th class="text-center informasi" width="3%;">No</th>
                                <th width="10%;" class="informasi">Kode</th>
                                <th width="20%;" class="informasi">Nama Barang</th>
                                <th width="7%;" class="informasi">Warna</th>
                                <th class="text-right link" width="7%;">FC Produksi <br>Yang Dibudgetkan</th>
                                <!-- <th class="text-right link" width="7%;">Schedule Jahit <br>Bulan Sebelumnya</th>
                                <th class="text-right link" width="7%;">Bahan Baku <br>Terkirim ke Jahit</th>
                                <th class="text-right formula" width="7%;">Sisa Schedule</th>
                                <th class="text-right link" width="7%;">Stock Pengadaan</th> -->
                                <th class="text-right link" width="7%;">Stock Pengesetan</th>
                                <!-- <th class="text-right link" width="7%;">Pendingan Permintaaan <br>Cutting Bulan Sebelumnya</th>
                                <th class="text-right formula" width="7%;">Kondisi Stock <br>Persiapan Cutting</th> -->
                                <th class="text-right formula" width="7%;">Permintaan Cutting</th>
                                <th class="text-right inputan" width="7%;">Memo Penambahan</th>
                                <th class="text-right hasil" width="7%;">FC Cutting</th>
                                <th class="inputan">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="font-13">
                            <?php $no = 0;
                            $group = "";
                            foreach ($datadetail as $key) {
                                $i++;
                                $no++;
                            ?>
                                <tr>
                                    <td class="text-center"><?= $no; ?></td>
                                    <td><?= $key->i_product_wip; ?></td>
                                    <td><?= ucwords(strtolower($key->e_product_wipname)); ?></td>
                                    <td><?= ucwords(strtolower($key->e_color_name)); ?></td>
                                    <td class="text-right"><?= $key->n_fc_perhitungan; ?></td>
                                    <td class="text-right" hidden><?= $key->n_schedule_jahit; ?></td>
                                    <td class="text-right" hidden><?= $key->n_bahan_baku; ?></td>
                                    <td class="text-right" hidden><?= $key->n_sisa_schedule; ?></td>
                                    <td class="text-right" hidden><?= $key->n_stock_pengadaan; ?></td>
                                    <td class="text-right"><?= $key->n_stock_pengesetan; ?></td>
                                    <td class="text-right" hidden><?= $key->n_pendingan_permintaan_cutting; ?></td>
                                    <td class="text-right" hidden><?= $key->n_kondisi_stock; ?></td>
                                    <td class="text-right"><?= $key->n_permintaan_cutting; ?></td>
                                    <td class="text-right"><?= $key->n_up_cutting; ?></td>
                                    <td class="text-right"><?= $key->n_fc_cutting; ?></td>
                                    <td><?= $key->e_remark; ?></td>
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
    <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">

</form>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script src="<?= base_url(); ?>assets/js/jquery.validate.min.js"></script>
<script>
    /*----------  LOAD SAAT DOKUMEN DIBUKA  ----------*/
    $(document).ready(function() {
        fixedtable($('#tabledatay'));

        hetang();
        /*----------  Load Form Validation  ----------*/
        $('#cekinputan').validate({
            errorClass: "my-error-class",
            validClass: "my-valid-class"
        });

        $('#ifccutting').mask('SS-0000-000000S');
        $('.select2').select2();
        /*----------  Tanggal tidak boleh kurang dari hari ini!  ----------*/
        showCalendar('.date', 0);
    });

    /*----------  RUBAH NO DOKUMEN (GANTI TANGGAL & BAGIAN)  ----------*/
    $('#ibagian, #ddocument').change(function(event) {
        number();
    });

    /*----------  RUNNING NUMBER DOKUMEN  ----------*/
    function number() {
        if (($('#ibagian').val() == $('#ibagianold').val())) {
            $('#ifccutting').val($('#ifccuttingold').val());
        } else {
            $.ajax({
                type: "post",
                data: {
                    'tgl': $('#ddocument').val(),
                    'ibagian': $('#ibagian').val(),
                },
                url: '<?= base_url($folder . '/cform/number'); ?>',
                dataType: "json",
                success: function(data) {
                    $('#ifccutting').val(data);
                },
                error: function() {
                    swal('Error :)');
                }
            });
        }
    }

    /*----------  UPDATE STATUS DOKUMEN  ----------*/
    $('#send').click(function(event) {
        statuschange('<?= $folder; ?>', $('#id').val(), '2', '<?= $dfrom . "','" . $dto; ?>');
    });

    $('#cancel').click(function(event) {
        statuschange('<?= $folder; ?>', $('#id').val(), '1', '<?= $dfrom . "','" . $dto; ?>');
    });

    $('#hapus').click(function(event) {
        statuschange('<?= $folder; ?>', $('#id').val(), '5', '<?= $dfrom . "','" . $dto; ?>');
    });

    /*----------  CEKLIS NO DOKUMEN (MANUAL)  ----------*/
    $('#ceklis').click(function(event) {
        if ($('#ceklis').is(':checked')) {
            $("#ifccutting").attr("readonly", false);
        } else {
            $("#ifccutting").attr("readonly", true);
            $("#ada").attr("hidden", true);
            number();
        }
    });

    /*----------  CEK NO DOKUMEN  ----------*/
    $("#ifccutting").keyup(function() {
        $.ajax({
            type: "post",
            data: {
                'kode': $(this).val(),
                'ibagian': $('#ibagian').val(),
            },
            url: '<?= base_url($folder . '/cform/cekkode'); ?>',
            dataType: "json",
            success: function(data) {
                if (data == 1 && ($('#ifccutting').val() != $('#ifccuttingold').val())) {
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

    function hetang(i) {
        let nilai_mutasi = parseFloat($('#nilai_mutasi' + i).val());
        let nilai_estimasi = parseFloat($('#nilai_estimasi' + i).val());
        let nilai_kebutuhan = parseFloat($('#nilai_kebutuhan' + i).val());
        let nilai_op_sisa = parseFloat($('#nilai_op_sisa' + i).val());

        let stock_estimasi = nilai_mutasi - nilai_estimasi;
        if (stock_estimasi < 0) {
            stock_estimasi = 0;
        }
        let budgeting = Math.abs(stock_estimasi) - Math.abs(nilai_kebutuhan) + Math.abs(nilai_op_sisa);

        // let budgeting = Math.abs(nilai_mutasi) - Math.abs(nilai_estimasi) - Math.abs(nilai_kebutuhan) - Math.abs(nilai_op_sisa);
        let up = budgeting * (parseFloat($('#up' + i).val()) / 100);
        $('#nilai_budgeting' + i).val(Math.round((Math.abs(budgeting) + Math.abs(up)) * 1000) / 1000);
    }

    /*----------  VALIDASI UPDATE DATA  ----------*/
    $("#submit").click(function(event) {
        var valid = $("#cekinputan").valid();
        if (valid) {
            ada = false;
            if ($('#jml').val() == 0) {
                swal('Isi item minimal 1!');
                return false;
            } else {
                // for (var i = 1; i <= $('#jml_item').val(); i++) {
                //     if (parseInt($('#nilai_budgeting' + i).val()) == 0 || parseInt($('#nilai_budgeting' + i)
                //     .val()) == null) {
                //         swal("Maaf :(", "Nilai Budgeting harus lebih besar dari 0!", "error");
                //         ada = true;
                //         return false;
                //     }
                // }
                // if (!ada) {
                swal({
                    title: "Update Data Ini?",
                    text: "Anda Dapat Membatalkannya Nanti",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonColor: 'LightSeaGreen',
                    confirmButtonText: "Ya, Update!",
                    closeOnConfirm: false
                }, function() {
                    $.ajax({
                        type: "POST",
                        data: $("form").serialize(),
                        url: '<?= base_url($folder . '/cform/update/'); ?>',
                        dataType: "json",
                        success: function(data) {
                            if (data.sukses == true) {
                                swal("Sukses!", "No Dokumen : " + data.kode +
                                    ", Berhasil Diupdate :)", "success");
                                $("input").attr("disabled", true);
                                //$("select").attr("disabled", true);
                                $("#submit").attr("disabled", true);
                                $("#addrow").attr("disabled", true);
                                $("#send").attr("hidden", false);
                            } else if (data.sukses == 'ada') {
                                swal("Maaf :(", "No Dokumen : " + data.kode +
                                    ", Sudah Ada :(", "error");
                            } else {
                                swal("Maaf :(", "No Dokumen : " + data.kode +
                                    ", Gagal Diupdate :(", "error");
                            }
                        },
                        error: function() {
                            swal("Maaf", "Data Gagal Diupdate :(", "error");
                        }
                    });
                });
                // } else {
                //     swal('Maaf :(', 'Total Jumlah Retur harus lebih besar dari 0 !', 'error');
                //     return false;
                // }
            }
        }
        return false;
    })
</script>