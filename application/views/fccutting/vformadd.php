<style>
    .dropify-wrapper {
        height: 118px !important;
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
        <div class="white-box" id="detail">
            <div class="col-sm-6">
                <h3 class="box-title m-b-0">Detail Item</h3>
            </div>
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table id="tabledatay" class="table color-table success-table table-bordered" cellpadding="8" cellspacing="1" width="100%">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Kode</th>
                                <th class="text-center">Nama Barang</th>
                                <th class="text-center">Warna</th>
                                <th class="text-center">Sisa Schedule<br>Berjalan</th>
                                <th class="text-center">Stock<br>Pengadaan</th>
                                <th class="text-center">Stock<br>Pengesetan</th>
                                <th class="text-center">Sisa Permintaan<br>Cutting</th>
                                <th class="text-center">Kondisi Stock<br>Persiapan Cutting</th>
                                <th class="text-center">Schedule Jahit</th>
                                <th class="text-center">Total Sisa</th>
                                <th class="text-center">Up Qty</th>
                                <th class="text-center">FC Cutting</th>
                                <th class="text-center">Set</th>
                                <th class="text-center">Jml Gelar</th>
                                <th class="text-center">Material</th>
                                <th class="text-center">FC yg<br>Dibudgetkan</th>
                                <th class="text-center">Total Qty<br>Kain Utama</th>
                                <th class="text-center">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 0;
                            $group = "";
                            foreach ($datadetail as $key) {
                                $i++;
                                $no++;
                                $sisa_schedule = 0;
                                $stok_pengadaan = $key->n_stock_pengadaan;
                                $stok_pengesetan = $key->n_stock_pengesetan;
                                $sisa_permintaan = 0;
                                $kondisi_stock = ($stok_pengadaan + $stok_pengesetan + $sisa_permintaan) - $sisa_schedule;
                                $schedule_jahit = $key->n_schedule_jahit;
                                $total_sisa = $schedule_jahit - ($kondisi_stock);
                                if ($total_sisa < 0) {
                                    $total_sisa = 0;
                                }
                                $up_qty = 0;
                                $fc_cutting = $total_sisa + $up_qty;
                                $v_set = $key->v_set;
                                $v_gelar = 0;
                                if ($v_set > 0) {
                                    $v_gelar = $fc_cutting / $v_set;
                                }
                            ?>
                                <tr>
                                    <td><?= $no; ?></td>
                                    <td>
                                        <input readonly type="text" class="form-control input-sm w95" value="<?= $key->i_product_wip; ?>">
                                    </td>
                                    <td>
                                        <input readonly type="text" class="form-control input-sm w250" value="<?= htmlentities($key->e_product_wipname); ?>">
                                    </td>
                                    <td>
                                        <input readonly type="text" class="form-control input-sm w95" value="<?= $key->e_color_name; ?>">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control input-sm text-right w95" name="n_sisa_schedule_berjalan<?= $i; ?>" id="n_sisa_schedule_berjalan<?= $i; ?>" onblur="if(this.value==''){this.value='0';berhitung(<?= $i; ?>);} " onkeyup="angkahungkul(this);berhitung(<?= $i; ?>);" onfocus="if(this.value=='0'){this.value='';}" value="0">
                                    </td>
                                    <td>
                                        <input readonly type="text" class="form-control input-sm text-right w95" name="n_stock_pengadaan<?= $i; ?>" id="n_stock_pengadaan<?= $i; ?>" value="<?= $key->n_stock_pengadaan; ?>">
                                    </td>
                                    <td>
                                        <input readonly type="text" class="form-control input-sm text-right w95" name="n_stock_pengesetan<?= $i; ?>" id="n_stock_pengesetan<?= $i; ?>" value="<?= $key->n_stock_pengesetan; ?>">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control input-sm text-right w95" name="n_sisa_permintaan_cutting<?= $i; ?>" id="n_sisa_permintaan_cutting<?= $i; ?>" onkeyup="angkahungkul(this);berhitung(<?= $i; ?>);" autocomplete="off" onblur="if(this.value==''){this.value='0';berhitung(<?= $i; ?>);};" onfocus="if(this.value=='0'){this.value='';}" value="0">
                                    </td>
                                    <td>
                                        <input readonly type="text" class="form-control input-sm text-right w95" name="n_kondisi_stock<?= $i; ?>" id="n_kondisi_stock<?= $i; ?>" value="<?= $kondisi_stock; ?>">
                                    </td>
                                    <td>
                                        <input readonly type="text" class="form-control input-sm text-right w95" name="n_fc_produksi_perhitungan<?= $i; ?>" id="n_fc_produksi_perhitungan<?= $i; ?>" value="<?= $schedule_jahit; ?>">
                                        <!-- <input type="hidden" name="n_fc_produksi<?= $i; ?>" id="n_fc_produksi<?= $i; ?>" value="<?= $key->fc_produksi; ?>"> -->
                                    </td>
                                    <td>
                                        <input readonly type="text" class="form-control input-sm text-right w95" name="n_total_sisa<?= $i; ?>" id="n_total_sisa<?= $i; ?>" value="<?= $total_sisa; ?>">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control input-sm text-right w95" name="n_up_qty<?= $i; ?>" id="n_up_cutting<?= $i; ?>" onkeyup="angkahungkul(this); berhitung(<?= $i; ?>);" autocomplete="off" onblur="if(this.value==''){this.value='0';}berhitung(<?= $i; ?>);" onfocus="if(this.value=='0'){this.value='';}" value="0">
                                    </td>
                                    <td>
                                        <input readonly type="text" class="form-control input-sm text-right qty_<?= $i; ?> w95" name="n_fc_cutting<?= $i; ?>" id="n_fc_cutting<?= $i; ?>" value="<?= $fc_cutting; ?>">
                                    </td>
                                    <td>
                                        <input readonly type="text" class="form-control input-sm text-right w95" name="v_set<?= $i; ?>" id="v_set<?= $i; ?>" value="<?= $v_set; ?>">
                                    </td>
                                    <td>
                                        <input readonly type="text" class="form-control input-sm text-right w95" name="v_gelar<?= $i; ?>" id="v_gelar<?= $i; ?>" value="<?= $v_gelar; ?>">
                                    </td>
                                    <td>
                                        <input readonly type="text" class="form-control input-sm w250" name="material<?= $i; ?>" id="material<?= $i; ?>" value="<?= $key->material; ?>">
                                    </td>
                                    <td>
                                        <input readonly type="text" class="form-control input-sm text-right w95" name="n_fc_yang_dibutgetkan<?= $i; ?>" id="n_fc_yang_dibutgetkan<?= $i; ?>" value="<?= $key->n_quantity_dibudgetkan; ?>">
                                    </td>
                                    <td>
                                        <input type="hidden" id="i_material<?= $i; ?>" name="i_material<?= $i; ?>" value="<?= $key->i_material; ?>">
                                        <input type="hidden" id="id_material<?= $i; ?>" name="id_material<?= $i; ?>" value="<?= $key->id_material; ?>">
                                        <input readonly type="text" class="form-control input-sm text-right set_<?= $i; ?> w95" name="n_total_qty_kain_utama<?= $i; ?>" id="n_total_qty_kain_utama<?= $i; ?>" value="<?= $v_gelar; ?>">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control input-sm w150" name="remark<?= $i; ?>" id="remark<?= $i; ?>">
                                        <input type="hidden" id="id_product_wip<?= $i; ?>" name="id_product_wip<?= $i; ?>" value="<?= $key->id_product_wip; ?>">
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
    <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">

</form>
<!-- <script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script> -->
<script src="<?= base_url(); ?>assets/js/jquery.validate.min.js"></script>
<script>
    /*----------  LOAD SAAT DOKUMEN DIBUKA  ----------*/
    $(document).ready(function() {
        ngetang_ulang();
        // fixedtable($('#tabledatay'));
        var $table = $('#tabledatay');

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
                fixedNumber: 4,
                // fixedRightNumber: 1
            })
        }

        $(function() {
            buildTable($table)
        })

        /*----------  Load Form Validation  ----------*/
        $('#cekinputan').validate({
            errorClass: "my-error-class",
            validClass: "my-valid-class"
        });

        $('.dropify').dropify();

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

                                for (let i = 1; i <= $('#jml').val(); i++) {
                                    const id_product_wip = $('#id_product_wip'+i).val();
                                    for (let j = 0; j < detail.length; j++) {
                                        const id_product = detail[j]['id_product'];
                                        const n_sisa_schedule_berjalan = detail[j]['n_sisa_schedule_berjalan'];
                                        const n_sisa_permintaan_cutting = detail[j]['n_sisa_permintaan_cutting'];
                                        const n_up_cutting = detail[j]['n_up_cutting'];
                                        const e_remark = detail[j]['e_remark'];
                                        if (id_product_wip === id_product) {
                                            $('#n_sisa_schedule_berjalan'+i).val(n_sisa_schedule_berjalan);
                                            $('#n_sisa_permintaan_cutting'+i).val(n_sisa_permintaan_cutting);
                                            $('#n_up_cutting'+i).val(n_up_cutting);
                                            $('#remark'+i).val(e_remark);
                                        }
                                        
                                    }
                                    berhitung(i);
                                    // console.log(id_product_wip);
                                }
                                // clear_table();
                                // $('#jml').val(detail.length);
                                /* for (let i = 0; i < detail.length; i++) {
                                    var no = i + 1;
                                    var newRow = $("<tr>");
                                    var cols = "";
                                    cols +=
                                        `<td class="text-center"><spanx id="snum${i}">${no}</spanx></td>
                                        <td>
                                            <input class="form-control tgl input-sm" readonly type="text" id="d_schedule${i}" name="d_schedule${i}" value="${detail[i]['d_schedule']}" required>
                                        </td>
                                        <td>
                                            <input value="${detail[i]['i_product_wip']}" class="form-control input-sm" readonly type="text" id="iproduct${i}" name="iproduct${i}">
                                        </td>
                                        <td>
                                            <select class="form-control select2" id="idproduct${i}" required name="idproduct${i}" onchange="get_detail(${i});"><option value="${detail[i]['id']}">${detail[i]['i_product_wip']} - ${detail[i]['e_product_wipname']} ${detail[i]['e_color_name']}</option></select>
                                        </td>
                                        <td>
                                            <input readonly class="form-control input-sm" type="text" id="e_color_name${i}" name="e_color_name${i}" value="${detail[i]['e_color_name']}">
                                        </td>
                                        <td>
                                            <input readonly class="form-control input-sm text-right" type="text" id="n_uraian_jahit${i}" name="n_uraian_jahit${i}" placeholder="0" value="${detail[i]['n_uraian_jahit']}">
                                        </td>
                                        <td hidden>
                                            <input readonly class="form-control input-sm text-right" type="text" id="n_uraian_jahit_sisa${i}" name="n_uraian_jahit_sisa${i}" placeholder="0" value="${detail[i]['n_uraian_jahit']}">
                                        </td>
                                        <td>
                                            <input class="form-control input-sm text-right" type="number" min="0" id="n_schedule_jahit${i}" name="n_schedule_jahit${i}" placeholder="0" onkeyup="hehetangan(${i});" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="${detail[i]['n_schedule']}">
                                        </td>
                                        <td>
                                            <input value="${detail[i]['e_remark']}" class="form-control input-sm" type="text" id="e_note${i}" name="e_note${i}" value="" placeholder="Isi keterangan jika ada!">
                                            <input type="hidden" id="f_uraian_jahit${i}" name="f_uraian_jahit${i}" value="t">
                                        </td>
                                        <td class="text-center"><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>`;
                                    newRow.append(cols);
                                    $("#tabledatax").append(newRow);
                                } */
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
        $("table").find("*").attr("disabled", true);
        $("#tabledatay").find("*").attr("disabled", false);
        var formData = new FormData(document.getElementById('cekinputan'));
        // console.log($('table').length);
        // return false;
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
                        data: /* formData */ $("form").serialize() ,
                        url: '<?= base_url($folder . '/cform/simpan/'); ?>',
                        dataType: "json",
                        /* processData: false,
                        contentType: false, */
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
        var n_total_sisa = schedule_jahit - kondisi_stock;
        if (n_total_sisa < 0) {
            n_total_sisa = 0;
        }
        $('#n_kondisi_stock' + i).val(kondisi_stock);
        $('#n_total_sisa' + i).val(n_total_sisa);
        $('#n_fc_cutting' + i).val((n_total_sisa) + up_qty);
        if (isFinite(fc_cutting / v_set)) {
            $('#v_gelar' + i).val(fc_cutting / v_set);
        }
        ngetang_ulang();
    }

    function ngetang_ulang() {
        var material = '';
        var sum;
        for (let i = 1; i <= $('#jml').val(); i++) {
            if (material == $('#i_material' + i).val() && $('#i_material' + i).val() != '') {
                sum = 0;
                $('.qty_' + i).each(function() {
                    sum += parseFloat($(this).val());
                    // console.log($(this).val());
                });
                $('.set_' + i).val(sum);
            } else {
                sum = $('.qty_' + i).val();
                $('.set_' + i).val(sum);
            }
            material = i;
        }
    }

    function export_data() {
        var idforecast = '<?= $id_schedule; ?>';
        var tahun = '<?= $tahun; ?>';
        var bulan = '<?= $bulan; ?>';
        if (idforecast == '') {
            swal('Referensi Schedule Kosong!!!');
            return false;
        } else {
            $('#href').attr('href', '<?php echo site_url($folder . '/cform/export/' . $tahun . '/' . $bulan . '/'); ?>' + idforecast);
            return true;
        }
    }
</script>