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
</style>
<form id="cekinputan">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp;
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
                                    <!-- <span class="input-group-addon">
                                        <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                    </span> -->
                                </div>
                                <!-- <span class="notekode">Format : (<?= $number; ?>)</span><br>
                                <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span> -->
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
                                <textarea id="eremark" name="eremark" class="form-control" placeholder="Isi keterangan jika ada!"><?= $data->e_remark; ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <?php if ($data->i_status == '1' || $data->i_status == '3' || $data->i_status == '7') { ?>
                                    <button type="button" id="submit" class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save"></i>&nbsp;&nbsp;Update</button>&nbsp;
                                <?php } ?>
                                <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                                <?php if ($data->i_status == '1') { ?>
                                    <button type="button" id="send" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>&nbsp;
                                    <button type="button" id="hapus" class="btn btn-danger btn-rounded btn-sm"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete</button>&nbsp;
                                <?php } elseif ($data->i_status == '2') { ?>
                                    <button type="button" id="cancel" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-refresh"></i>&nbsp;&nbsp;Cancel</button>&nbsp;
                                <?php } ?>
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
                            <tr class="d-flex">
                                <!-- <th class="text-center informasi" width="3%;">No</th> -->
                                <th width="10%;" class="informasi middle col-1">Kode</th>
                                <th width="20%;" class="informasi middle col-3">Nama Barang</th>
                                <th width="7%;" class="informasi middle col-1">Warna</th>
                                <th class="col-1 text-right middle link" width="7%;">Sisa Schedule<br>Berjalan</th>
                                <th class="col-1 text-right middle link" width="7%;">Stock<br>Pengadaan</th>
                                <th class="col-1 text-right middle link" width="7%;">Stock<br>Pengesetan</th>
                                <th class="col-1 text-right middle inputan" width="7%;">Sisa Permintaan<br>Cutting</th>
                                <th class="col-1 text-right middle formula" width="7%;">Kondisi Stock<br>Persiapan Cutting</th>
                                <th class="col-1 text-right middle link" width="7%;">Schedule Jahit</th>
                                <th class="col-1 text-right middle inputan" width="7%;">Up Qty</th>
                                <th class="col-1 text-right middle hasil" width="7%;">FC Cutting</th>
                                <th class="col-2 middle inputan">Keterangan</th>
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
                                /* $kondisi_stock = $key->n_sisa_schedule_berjalan - $key->n_stock_pengadaan - $key->n_stock_pengesetan;
                                $permintaan_cutting = $kondisi_stock + $key->fc_produksi;
                                if ($permintaan_cutting > 0) {
                                    $fc_cutting = $permintaan_cutting;
                                } else {
                                    $fc_cutting = 0;
                                }  */
                            ?>
                                <tr class="d-flex">
                                    <!-- <td class="text-center middle"><?= $no; ?></td> -->
                                    <td class="col-1 middle"><?= $key->i_product_wip; ?></td>
                                    <td class="col-3 middle"><?= ucwords(strtolower($key->e_product_wipname)); ?></td>
                                    <td class="col-1 middle"><?= ucwords(strtolower($key->e_color_name)); ?></td>
                                    <td class="col-1 text-right">
                                        <input readonly type="text" class="form-control input-sm text-right" name="n_sisa_schedule_berjalan<?= $i; ?>" id="n_sisa_schedule_berjalan<?= $i; ?>" value="<?= $key->n_sisa_schedule_berjalan; ?>">
                                        <input type="hidden" name="n_schedule_jahit<?= $i; ?>" id="n_schedule_jahit<?= $i; ?>" value="<?= $key->n_schedule_jahit; ?>">
                                        <input type="hidden" name="n_stb_pengadaan<?= $i; ?>" id="n_stb_pengadaan<?= $i; ?>" value="<?= $key->n_stb_pengadaan; ?>">
                                    </td>
                                    <td class="col-1 text-right">
                                        <input readonly type="text" class="form-control input-sm text-right" name="n_stock_pengadaan<?= $i; ?>" id="n_stock_pengadaan<?= $i; ?>" value="<?= $key->n_stock_pengadaan; ?>">
                                    </td>
                                    <td class="col-1 text-right">
                                        <input readonly type="text" class="form-control input-sm text-right" name="n_stock_pengesetan<?= $i; ?>" id="n_stock_pengesetan<?= $i; ?>" value="<?= $key->n_stock_pengesetan; ?>">
                                    </td>
                                    <td class="col-1 text-right">
                                        <input type="text" class="form-control input-sm text-right" name="n_sisa_permintaan_cutting<?= $i; ?>" id="n_sisa_permintaan_cutting<?= $i; ?>" onkeyup="angkahungkul(this); ngetang(<?= $i;?>);" autocomplete="off" onblur="if(this.value==''){this.value='0';} ngetang(<?= $i;?>);" onfocus="if(this.value=='0'){this.value='';}" value="<?= $key->n_sisa_permintaan_cutting;?>">
                                    </td>
                                    <td class="col-1 text-right">
                                        <input readonly type="text" class="form-control input-sm text-right" name="n_kondisi_stock<?= $i; ?>" id="n_kondisi_stock<?= $i; ?>" value="<?= $key->n_kondisi_stock; ?>">
                                    </td>
                                    <td class="col-1 text-right">
                                        <input readonly type="text" class="form-control input-sm text-right" name="n_fc_produksi_perhitungan<?= $i; ?>" id="n_fc_produksi_perhitungan<?= $i; ?>" value="<?= $key->n_fc_produksi_perhitungan; ?>">
                                        <input type="hidden" name="n_fc_produksi<?= $i; ?>" id="n_fc_produksi<?= $i; ?>" value="<?= $key->n_fc_produksi_perhitungan; ?>">
                                    </td>
                                    <td class="col-1 text-right">
                                        <input type="text" class="form-control input-sm text-right" name="n_up_cutting<?= $i; ?>" id="n_up_cutting<?= $i; ?>" onkeyup="angkahungkul(this); up(<?= $i;?>);" autocomplete="off" onblur="if(this.value==''){this.value='0';}up(<?= $i;?>);" onfocus="if(this.value=='0'){this.value='';}" value="<?= $key->n_up_cutting; ?>">
                                    </td>
                                    <td class="col-1 text-right">
                                        <input readonly type="text" class="form-control input-sm text-right" name="n_fc_cutting<?= $i; ?>" id="n_fc_cutting<?= $i; ?>" value="<?= $key->n_fc_cutting; ?>">
                                    </td>
                                    <!-- <td class="text-right">
                                        <input readonly type="text" class="form-control input-sm text-right" name="fc_produksi<?= $i; ?>" id="fc_produksi<?= $i; ?>" value="<?= $key->fc_produksi; ?>">
                                    </td>
                                    <td class="text-right" hidden>
                                        <input type="text" class="form-control input-sm text-right" name="schedule_jahit<?= $i; ?>" id="schedule_jahit<?= $i; ?>" onkeyup="angkahungkul(this);" autocomplete="off" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="<?= $key->schedule_jahit; ?>">
                                    </td>
                                    <td class="text-right" hidden>
                                        <input type="text" class="form-control input-sm text-right" name="bahan_baku<?= $i; ?>" id="bahan_baku<?= $i; ?>" onkeyup="angkahungkul(this);" autocomplete="off" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="<?= $key->bahan_baku; ?>">
                                    </td>
                                    <td class="text-right" hidden>
                                        <input readonly type="text" class="form-control input-sm text-right" name="sisa_scedule<?= $i; ?>" id="sisa_scedule<?= $i; ?>" value="<?= $sisa_schedule; ?>">
                                    </td>
                                    <td class="text-right" hidden>
                                        <input readonly type="text" class="form-control input-sm text-right" name="n_stock_pengadaan<?= $i; ?>" id="n_stock_pengadaan<?= $i; ?>" value="<?= $key->n_stock_pengadaan; ?>">
                                    </td>
                                    <td class="text-right">
                                        <input readonly type="text" class="form-control input-sm text-right" name="n_stock_pengesetan<?= $i; ?>" id="n_stock_pengesetan<?= $i; ?>" value="<?= $key->n_stock_pengesetan; ?>">
                                    </td>
                                    <td class="text-right" hidden>
                                        <input type="text" class="form-control input-sm text-right" name="pendingan_bulan_sebelumnya<?= $i; ?>" id="pendingan_bulan_sebelumnya<?= $i; ?>" value="<?= $key->pendingan_bulan_sebelumnya; ?>" autocomplete="off" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}">
                                    </td>
                                    <td class="text-right" hidden>
                                        <input readonly type="text" class="form-control input-sm text-right" name="kondisi_stock<?= $i; ?>" id="kondisi_stock<?= $i; ?>" value="<?= $kondisi_stock; ?>">
                                    </td>
                                    <td class="text-right">
                                        <input readonly type="text" class="form-control input-sm text-right" name="permintaan_cutting<?= $i; ?>" id="permintaan_cutting<?= $i; ?>" value="<?= $permintaan_cutting; ?>">
                                    </td>
                                    <td class="text-right">
                                        <input type="text" class="form-control input-sm text-right" name="up_cutting<?= $i; ?>" id="up_cutting<?= $i; ?>" onkeyup="angkahungkul(this);" autocomplete="off" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="0">
                                    </td>
                                    <td class="text-right">
                                        <input readonly type="text" class="form-control input-sm text-right" name="fc_cutting<?= $i; ?>" id="fc_cutting<?= $i; ?>" value="<?= $fc_cutting; ?>">
                                    </td> -->
                                    <td class="col-2 text-right"><input type="text" class="form-control input-sm" name="remark<?= $i; ?>" value="<?= $key->e_remark; ?>"></td>
                                    <input type="hidden" name="id_product_wip<?= $i; ?>" value="<?= $key->id_product_wip; ?>">
                                    <!-- <input type="hidden" name="id_forecast<?= $i; ?>" value="<?= $key->id_forecast; ?>"> -->
                                </tr>
                            <?php
                            } ?>
                                <!-- <tr>
                                    <td class="text-center"><?= $no; ?></td>
                                    <td><?= $key->i_product_wip; ?></td>
                                    <td><?= ucwords(strtolower($key->e_product_wipname)); ?></td>
                                    <td><?= ucwords(strtolower($key->e_color_name)); ?></td>
                                    <td class="text-right">
                                        <input readonly type="text" class="form-control input-sm text-right" name="fc_produksi<?= $i; ?>" id="fc_produksi<?= $i; ?>" value="<?= $key->n_fc_perhitungan; ?>">
                                    </td>
                                    <td class="text-right" hidden>
                                        <input type="text" class="form-control input-sm text-right" name="schedule_jahit<?= $i; ?>" id="schedule_jahit<?= $i; ?>" onkeyup="angkahungkul(this);ngitung(<?= $i; ?>);" autocomplete="off" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="<?= $key->n_schedule_jahit; ?>">
                                    </td>
                                    <td class="text-right" hidden> 
                                        <input type="text" class="form-control input-sm text-right" name="bahan_baku<?= $i; ?>" id="bahan_baku<?= $i; ?>" onkeyup="angkahungkul(this);ngitung(<?= $i; ?>);" autocomplete="off" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="<?= $key->n_bahan_baku; ?>">
                                    </td>
                                    <td class="text-right" hidden>
                                        <input readonly type="text" class="form-control input-sm text-right" name="sisa_scedule<?= $i; ?>" id="sisa_scedule<?= $i; ?>" value="<?= $key->n_sisa_schedule; ?>">
                                    </td>
                                    <td class="text-right" hidden>
                                        <input readonly type="text" class="form-control input-sm text-right" name="n_stock_pengadaan<?= $i; ?>" id="n_stock_pengadaan<?= $i; ?>" value="<?= $key->n_stock_pengadaan; ?>">
                                    </td>
                                    <td class="text-right">
                                        <input readonly type="text" class="form-control input-sm text-right" name="n_stock_pengesetan<?= $i; ?>" id="n_stock_pengesetan<?= $i; ?>" value="<?= $key->n_stock_pengesetan; ?>">
                                    </td>
                                    <td class="text-right" hidden>
                                        <input type="text" class="form-control input-sm text-right" name="pendingan_bulan_sebelumnya<?= $i; ?>" id="pendingan_bulan_sebelumnya<?= $i; ?>" value="<?= $key->n_pendingan_permintaan_cutting; ?>" autocomplete="off" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}">
                                    </td>
                                    <td class="text-right" hidden>
                                        <input readonly type="text" class="form-control input-sm text-right" name="kondisi_stock<?= $i; ?>" id="kondisi_stock<?= $i; ?>" value="<?= $key->n_kondisi_stock; ?>">
                                    </td>
                                    <td class="text-right">
                                        <input readonly type="text" class="form-control input-sm text-right" name="permintaan_cutting<?= $i; ?>" id="permintaan_cutting<?= $i; ?>" value="<?= $key->n_permintaan_cutting; ?>">
                                    </td>
                                    <td class="text-right">
                                        <input type="text" class="form-control input-sm text-right" name="up_cutting<?= $i; ?>" id="up_cutting<?= $i; ?>" onkeyup="angkahungkul(this);itung(<?= $i; ?>);" autocomplete="off" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="<?= $key->n_up_cutting;?>">
                                    </td>
                                    <td class="text-right">
                                        <input readonly type="text" class="form-control input-sm text-right" name="fc_cutting<?= $i; ?>" id="fc_cutting<?= $i; ?>" value="<?= $key->n_fc_cutting; ?>">
                                    </td>
                                    <td class="text-right"><input type="text" class="form-control input-sm" name="remark<?= $i; ?>"><?= $key->e_remark;?></td>
                                    <input type="hidden" name="id_product_wip<?= $i; ?>" value="<?= $key->id_product_wip; ?>">
                                    <input type="hidden" name="id_forecast<?= $i; ?>" value="<?= $key->id_forecast; ?>">
                                </tr> -->
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
        
        hetang();
        /*----------  Load Form Validation  ----------*/
        $('#cekinputan').validate({
            errorClass: "my-error-class",
            validClass: "my-valid-class"
        });

        // $('#ifccutting').mask('SS-0000-000000S');
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
        let n_sisa_schedule_berjalan = parseFloat($('#n_sisa_schedule_berjalan'+i).val());
        let n_stock_pengadaan = parseFloat($('#n_stock_pengadaan'+i).val());
        let n_stock_pengesetan = parseFloat($('#n_stock_pengesetan'+i).val());
        let n_sisa_permintaan_cutting = parseFloat($('#n_sisa_permintaan_cutting'+i).val());
        $('#n_kondisi_stock'+i).val(n_sisa_schedule_berjalan - n_stock_pengadaan - n_stock_pengesetan - n_sisa_permintaan_cutting);
        let kondisi_stock = parseFloat($('#n_kondisi_stock'+i).val()) + parseFloat($('#n_fc_produksi'+i).val());
        $('#n_fc_produksi_perhitungan'+i).val(kondisi_stock);
        up(i);
    }

    function up(i) {
        let n_fc_produksi_perhitungan = parseFloat($('#n_fc_produksi_perhitungan'+i).val());
        let n_up_cutting = parseFloat($('#n_up_cutting'+i).val());
        $('#n_fc_cutting'+i).val(n_fc_produksi_perhitungan + n_up_cutting);
    }
</script>