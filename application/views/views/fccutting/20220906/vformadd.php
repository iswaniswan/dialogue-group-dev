<style type="text/css">
    table,
    tr,
    td {
        /* border: 1px inset #C0C0C0 !important; */
        border-collapse: collapse;
        border-spacing: 0;
    }

    table,
    tr,
    th {
        /* border: 1px inset #4c5667 !important; */
        border-collapse: collapse;
        border-spacing: 0;
    }

    td:hover {
        border: 1px solid #00c292 !important;
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

    .middle {
        vertical-align: middle !important;
    }
</style>
<form id="cekinputan">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp;
                        <?= $title_list; ?></a>
                </div>
                <div class="panel-body table-responsive">
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
                                    <input type="text" name="idocument" required="" id="ifccutting" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number; ?>" maxlength="25" class="form-control input-sm" value="" aria-label="Text input with dropdown button">
                                    <!-- <span class="input-group-addon">
                                        <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                    </span> -->
                                </div>
                                <!-- <span class="notekode">Format : (<?= $number; ?>)</span><br> -->
                                <!-- <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span> -->
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
                                <button type="button" id="send" disabled="true" class="btn btn-primary btn-block btn-sm"><i class="fa fa-paper-plane-o"></i>Send</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="col-md-12">
                        <div class="form-group">
                            <span class="notekode"><b>N O T E : </b></span><br>
                            <span class="notekode">* Item yang disimpan hanya qty retur yang lebih besar dari 0.</span>
                        </div>
                    </div> -->
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
                    <table id="tabledatay" class="table color-table success-table table-bordered" cellpadding="8" cellspacing="1" width="100%">
                        <thead>
                            <tr class="d-flex">
                                <!-- <th class="text-center informasi" width="3%;">No</th> -->
                                <th width="10%;" class="middle col-1">Kode</th>
                                <th width="20%;" class="middle col-3">Nama Barang</th>
                                <th width="7%;" class="middle col-1">Warna</th>
                                <th class="col-1 text-right middle" width="7%;">Sisa Schedule<br>Berjalan</th>
                                <th class="col-1 text-right middle" width="7%;">Stock<br>Pengadaan</th>
                                <th class="col-1 text-right middle" width="7%;">Stock<br>Pengesetan</th>
                                <th class="col-1 text-right middle" width="7%;">Sisa Permintaan<br>Cutting</th>
                                <th class="col-1 text-right middle" width="7%;">Kondisi Stock<br>Persiapan Cutting</th>
                                <th class="col-1 text-right middle" width="7%;">Schedule Jahit</th>
                                <th class="col-1 text-right middle" width="7%;">Total Sisa</th>
                                <th class="col-1 text-right middle" width="7%;">Up Qty</th>
                                <th class="col-1 text-right middle" width="7%;">FC Cutting</th>
                                <th class="col-1 text-right middle" width="7%;">Set</th>
                                <th class="col-1 text-right middle" width="7%;">Jml Gelar</th>
                                <th class="col-3 middle">Material</th>
                                <th class="col-2 middle">Keterangan</th>
                                <!-- <th class="text-right link" width="7%;">FC Produksi <br>Yang Dibudgetkan</th> -->
                                <!-- <th class="text-right link" width="7%;">Schedule Jahit <br>Bulan Sebelumnya</th>
                                <th class="text-right link" width="7%;">Bahan Baku <br>Terkirim ke Jahit</th>
                                <th class="text-right formula" width="7%;">Sisa Schedule</th> -->
                                <!-- <th class="text-right link" width="7%;">Pendingan Permintaaan <br>Cutting Bulan Sebelumnya</th>
                                <th class="text-right formula" width="7%;">Kondisi Stock <br>Persiapan Cutting</th> -->
                            </tr>
                        </thead>
                        <tbody class="font-13">
                            <?php $no = 0;
                            $group = "";
                            foreach ($datadetail as $key) {
                                $i++;
                                $no++;
                                /* $sisa_schedule = $key->schedule_jahit - $key->bahan_baku;
                                $kondisi_stock = $sisa_schedule - ($key->n_stock_pengadaan + $key->n_stock_pengesetan + $key->pendingan_bulan_sebelumnya);
                                $permintaan_cutting = $kondisi_stock + $key->fc_produksi;
                                if ($permintaan_cutting > 0) {
                                    $fc_cutting = $permintaan_cutting;
                                } else {
                                    $fc_cutting = 0;
                                } */
                                /* $kondisi_stock = $key->n_stock_pengadaan + $key->n_stock_pengesetan;
                                $permintaan_cutting = $kondisi_stock + $key->fc_produksi;
                                if ($permintaan_cutting > 0) {
                                    $fc_cutting = $permintaan_cutting;
                                } else {
                                    $fc_cutting = 0;
                                } */
                                $sisa_schedule = 0;
                                $stok_pengadaan = $key->n_stock_pengadaan;
                                $stok_pengesetan = $key->n_stock_pengesetan;
                                $sisa_permintaan = 0;
                                $kondisi_stock = ($stok_pengadaan + $stok_pengesetan + $sisa_permintaan) - $sisa_schedule;
                                $schedule_jahit = $key->n_schedule_jahit;
                                $total_sisa = $schedule_jahit - ($kondisi_stock);
                                $up_qty = 0;
                                $fc_cutting = $total_sisa + $up_qty;
                                $v_set = $key->v_set;
                                $v_gelar = 0;
                                if ($v_set > 0) {
                                    $v_gelar = $fc_cutting / $v_set;
                                }
                            ?>
                                <tr class="d-flex">
                                    <td class="col-1 middle">
                                        <input readonly type="text" class="form-control input-sm" value="<?= $key->i_product_wip; ?>">
                                    </td>
                                    <td class="col-3 middle">
                                        <input readonly type="text" class="form-control input-sm" value="<?= $key->e_product_wipname; ?>">
                                    </td>
                                    <td class="col-1 middle">
                                        <input readonly type="text" class="form-control input-sm" value="<?= $key->e_color_name; ?>">
                                    </td>
                                    <td class="col-1 text-right">
                                        <input type="number" class="form-control input-sm text-right" name="n_sisa_schedule_berjalan<?= $i; ?>" id="n_sisa_schedule_berjalan<?= $i; ?>" onblur="if(this.value==''){this.value='0';} berhitung(<?= $i; ?>);" onkeyup="berhitung(<?= $i; ?>);" onfocus="if(this.value=='0'){this.value='';}" value="0">
                                        <!-- <input type="hidden" name="n_schedule_jahit<?= $i; ?>" id="n_schedule_jahit<?= $i; ?>" value="<?= $key->n_schedule_jahit; ?>">
                                        <input type="hidden" name="n_stb_pengadaan<?= $i; ?>" id="n_stb_pengadaan<?= $i; ?>" value="<?= $key->n_stb_pengadaan; ?>"> -->
                                    </td>
                                    <td class="col-1 text-right">
                                        <input readonly type="text" class="form-control input-sm text-right" name="n_stock_pengadaan<?= $i; ?>" id="n_stock_pengadaan<?= $i; ?>" value="<?= $key->n_stock_pengadaan; ?>">
                                    </td>
                                    <td class="col-1 text-right">
                                        <input readonly type="text" class="form-control input-sm text-right" name="n_stock_pengesetan<?= $i; ?>" id="n_stock_pengesetan<?= $i; ?>" value="<?= $key->n_stock_pengesetan; ?>">
                                    </td>
                                    <td class="col-1 text-right">
                                        <input type="number" class="form-control input-sm text-right" name="n_sisa_permintaan_cutting<?= $i; ?>" id="n_sisa_permintaan_cutting<?= $i; ?>" onkeyup="berhitung(<?= $i; ?>);" autocomplete="off" onblur="if(this.value==''){this.value='0';} berhitung(<?= $i; ?>);" onfocus="if(this.value=='0'){this.value='';}" value="0">
                                    </td>
                                    <td class="col-1 text-right">
                                        <input readonly type="text" class="form-control input-sm text-right" name="n_kondisi_stock<?= $i; ?>" id="n_kondisi_stock<?= $i; ?>" value="<?= $kondisi_stock; ?>">
                                    </td>
                                    <td class="col-1 text-right">
                                        <input readonly type="text" class="form-control input-sm text-right" name="n_fc_produksi_perhitungan<?= $i; ?>" id="n_fc_produksi_perhitungan<?= $i; ?>" value="<?= $schedule_jahit;?>">
                                        <!-- <input type="hidden" name="n_fc_produksi<?= $i; ?>" id="n_fc_produksi<?= $i; ?>" value="<?= $key->fc_produksi; ?>"> -->
                                    </td>
                                    <td class="col-1 text-right">
                                        <input readonly type="text" class="form-control input-sm text-right" name="n_total_sisa<?= $i; ?>" id="n_total_sisa<?= $i; ?>" value="<?= $total_sisa;?>">
                                    </td>
                                    <td class="col-1 text-right">
                                        <input type="number" class="form-control input-sm text-right" name="n_up_cutting<?= $i; ?>" id="n_up_cutting<?= $i; ?>" onkeyup="angkahungkul(this); berhitung(<?= $i; ?>);" autocomplete="off" onblur="if(this.value==''){this.value='0';}berhitung(<?= $i; ?>);" onfocus="if(this.value=='0'){this.value='';}" value="0">
                                    </td>
                                    <td class="col-1 text-right">
                                        <input readonly type="text" class="form-control input-sm text-right" name="n_fc_cutting<?= $i; ?>" id="n_fc_cutting<?= $i; ?>" value="<?= $fc_cutting; ?>">
                                    </td>
                                    <td class="col-1 text-right">
                                        <input readonly type="text" class="form-control input-sm text-right" name="v_set<?= $i; ?>" id="v_set<?= $i; ?>" value="<?= $v_set;?>">
                                    </td>
                                    <td class="col-1 text-right">
                                        <input readonly type="text" class="form-control input-sm text-right" name="v_gelar<?= $i; ?>" id="v_gelar<?= $i; ?>" value="<?= $v_gelar;?>">
                                    </td>
                                    <td class="col-3">
                                        <input readonly type="text" class="form-control input-sm" name="material<?= $i; ?>" id="material<?= $i; ?>" value="<?= $key->material;?>">
                                    </td>
                                    <td class="col-2 text-right"><input type="text" class="form-control input-sm" name="remark<?= $i; ?>"></td>
                                    <input type="hidden" name="id_product_wip<?= $i; ?>" value="<?= $key->id_product_wip; ?>">
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
<!-- <script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script> -->
<script src="<?= base_url(); ?>assets/js/jquery.validate.min.js"></script>
<script>
    /*----------  LOAD SAAT DOKUMEN DIBUKA  ----------*/
    $(document).ready(function() {
        fixedtable($('#tabledatay'));

        /*----------  Load Form Validation  ----------*/
        $('#cekinputan').validate({
            errorClass: "my-error-class",
            validClass: "my-valid-class"
        });

        // $('#ifccutting').mask('SS-0000-000000S');
        $('.select2').select2();
        /*----------  Tanggal tidak boleh kurang dari hari ini!  ----------*/
        showCalendar('.date', 0);
        number();
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
                $('#ifccutting').val(data);
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
        var valid = $("#cekinputan").valid();
        if (valid) {
            ada = false;
            if ($('#jml').val() == 0) {
                swal('Isi item minimal 1!');
                return false;
            } else {
                // for (var i = 1; i <= $('#jml_item').val(); i++) {
                //     if (parseInt($('#nilai_budgeting' + i).val()) == 0 || parseInt($('#nilai_budgeting' + i).val()) == null) {
                //         swal("Maaf :(","Nilai Budgeting harus lebih besar dari 0!","error");
                //         ada = true;
                //         return false;
                //     }
                // }
                // if (!ada) {
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
                                //$("select").attr("disabled", true);
                                $("#submit").attr("disabled", true);
                                $("#addrow").attr("disabled", true);
                                $("#send").attr("disabled", false);
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
                // } else {
                //     swal('Maaf :(', 'Total Jumlah Retur harus lebih besar dari 0 !', 'error');
                //     return false;
                // }
            }
        }
        return false;
    })

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
        let up = budgeting * (parseFloat($('#up' + i).val()) / 100);
        $('#nilai_budgeting' + i).val(Math.round((Math.abs(budgeting) + Math.abs(up)) * 1000) / 1000);
    }

    function ngitung(i) {
        let nilai_base = parseFloat($('#nilai_base' + i).val());
        let perkalian = parseFloat($('#perkalian' + i).val());
        let stok_pengesetan = parseFloat($('#stok_pengesetan' + i).val());
        let nilai = (nilai_base * perkalian) - stok_pengesetan;
        $('#fc_cutting' + i).val(nilai);
    }

    function itung(i) {
        $('#fc_cutting' + i).val(parseFloat($('#permintaan_cutting' + i).val()) + parseFloat($('#up_cutting' + i).val()));
    }

    function ngetang(i) {
        let n_sisa_schedule_berjalan = parseFloat($('#n_sisa_schedule_berjalan' + i).val());
        let n_stock_pengadaan = parseFloat($('#n_stock_pengadaan' + i).val());
        let n_stock_pengesetan = parseFloat($('#n_stock_pengesetan' + i).val());
        let n_sisa_permintaan_cutting = parseFloat($('#n_sisa_permintaan_cutting' + i).val());
        $('#n_kondisi_stock' + i).val(n_sisa_schedule_berjalan - n_stock_pengadaan - n_stock_pengesetan - n_sisa_permintaan_cutting);
        let kondisi_stock = parseFloat($('#n_kondisi_stock' + i).val()) + parseFloat($('#n_fc_produksi' + i).val());
        //$('#n_fc_produksi_perhitungan'+i).val(kondisi_stock);
        up(i);
    }

    function up(i) {


        let n_kondisi_stock = parseFloat($('#n_kondisi_stock' + i).val());
        let n_fc_produksi_perhitungan = parseFloat($('#n_fc_produksi_perhitungan' + i).val());
        let n_up_cutting = parseFloat($('#n_up_cutting' + i).val());
        $('#n_fc_cutting' + i).val(n_kondisi_stock - n_fc_produksi_perhitungan + n_up_cutting);
    }




    function berhitung(i) {
        var sisa_schedule = parseFloat($('#n_sisa_schedule_berjalan' + i).val());
        var stok_pengadaan = parseFloat($('#n_stock_pengadaan' + i).val());
        var stok_pengesetan = parseFloat($('#n_stock_pengesetan' + i).val());
        var sisa_permintaan = parseFloat($('#n_sisa_permintaan_cutting' + i).val());
        var schedule_jahit = parseFloat($('#n_fc_produksi_perhitungan' + i).val());
        var total_sisa = parseFloat($('#n_total_sisa' + i).val());
        var up_qty = parseFloat($('#n_up_cutting' + i).val());
        var v_set = parseFloat($('#v_set' + i).val());
        var fc_cutting = parseFloat($('#n_fc_cutting' + i).val());
        if (isNaN(sisa_schedule)) {
            sisa_schedule = 0;
        }
        if (isNaN(sisa_permintaan)) {
            sisa_permintaan = 0;
        }
        if (isNaN(up_qty)) {
            up_qty = 0;
        }
        if (isNaN(v_set)) {
            v_set = 0;
        }
        var kondisi_stock = (stok_pengadaan + stok_pengesetan + sisa_permintaan) - sisa_schedule;
        $('#n_kondisi_stock' + i).val(kondisi_stock);
        $('#n_total_sisa' + i).val(schedule_jahit - kondisi_stock);
        $('#n_fc_cutting' + i).val(total_sisa + up_qty);
        if(isFinite(fc_cutting / v_set)){
            $('#v_gelar' + i).val(fc_cutting / v_set);
        }
    }
</script>