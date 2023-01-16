<style type="text/css">
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
        font-size: 13px;
        height: 25px;
    }

    .font-12 {
        padding-left: 3px;
        padding-right: 3px;
        font-size: 12px;
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
                            <label class="col-md-3">Periode Forecast</label>
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
                                    <input type="hidden" name="idocumentold" id="ibudgetingold" value="<?= $data->i_document; ?>">
                                    <input type="text" name="idocument" required="" id="ibudgeting" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number; ?>" maxlength="25" class="form-control input-sm" value="<?= $data->i_document; ?>" aria-label="Text input with dropdown button">
                                    <span class="input-group-addon">
                                        <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                    </span>
                                </div>
                                <span class="notekode">Format : (<?= $number; ?>)</span><br>
                                <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                            </div>
                            <div class="col-sm-3">
                                <input type="text" id="ddocument" name="ddocument" class="form-control input-sm date" required="" readonly value="<?= $data->d_document; ?>">
                            </div>
                            <div class="col-sm-3">
                                <input type="hidden" id="idforecast" name="idforecast" required="" value="<?= $data->id_referensi; ?>">
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

        <!-- <div class="white-box" id="detail" hidden>
        <div class="col-sm-6">
            <h3 class="box-title m-b-0">Detail Item</h3>
        </div>
        <div class="col-sm-12">
            <div class="table-responsive">
                <table id="tabledatay" class="table color-table success-table table-bordered class" cellpadding="8"
                    cellspacing="1" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center" width="3%;">No</th>
                            <th class="text-center" width="12%;">Kode Material</th>
                            <th class="text-center">Nama Material</th>
                            <th class="text-center" width="12%;">Pemakaian</th>
                            <th class="text-center" width="12%;">Kebutuhan</th>
                            <th class="text-center" width="14%;">Satuan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 0;
                        $group = "";
                        foreach ($datadetail as $key) {
                            $i++;
                            $no++;
                            if ($group == "") { ?>
                        <tr class="pudding">
                            <td colspan="4">Barang Jadi : <b><?= $key->i_product_base; ?> &nbsp;<?= ucwords(strtolower($key->e_product_basename)); ?>&nbsp;<?= ucwords(strtolower($key->e_color_name)); ?></b></td>
                            <td class="text-right">Qty : <b><?= $key->n_quantity; ?></b></td>
                            <td></td>
                        </tr>
                        <?php
                            } else {
                                if ($group != $key->id_product_base) { ?>
                        <tr class="pudding">
                            <td colspan="4">Barang Jadi : <b><?= $key->i_product_base; ?> &nbsp;<?= ucwords(strtolower($key->e_product_basename)); ?>&nbsp;<?= ucwords(strtolower($key->e_color_name)); ?></b></td>
                            <td class="text-right">Qty : <b><?= $key->n_quantity; ?></b></td>
                            <td></td>
                        </tr>
                        <?php $no = 1;
                                }
                            }
                            $group = $key->id_product_base;
                        ?>
                        <tr>
                            <td class="text-center"><?= $no; ?></td>
                            <td><?= $key->i_material; ?></td>
                            <td><?= ucwords(strtolower($key->e_material_name)); ?></td>
                            <td class="text-right"><?= number_format($key->pemakaian, 3); ?></td>
                            <td class="text-right"><?= number_format($key->kebutuhan, 3); ?></td>
                            <td><?= ucwords(strtolower($key->e_satuan_name)); ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div> -->

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
        <!--  <div class="white-box" id="detail" hidden>
        <div class="col-sm-6">
            <h3 class="box-title m-b-0">Detail Bis Bisan</h3>
        </div>
        <div class="col-sm-12">
            <div class="table-responsive">
                <table id="tabledatay" class="table color-table success-table table-bordered class" cellpadding="8"
                    cellspacing="1" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center" width="3%;">No</th>
                            <th width="12%;">Kode Material</th>
                            <th>Nama Material</th>
                            <th>Jenis Potong</th>
                            <th class="text-right" width="12%;">Ukuran</th>
                            <th class="text-right" width="12%;">Pemakaian</th>
                            <th class="text-right" width="12%;">Kebutuhan</th>
                            <th width="14%;">Satuan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 0;
                        foreach ($bisbisan as $key) {
                            $i++; ?>
                            <tr>
                                <td class="text-center"><?= $i; ?></td>
                                <td><?= $key->i_material; ?></td>
                                <td><?= $key->e_material_name; ?></td>
                                <td><?= $key->e_jenis_potong; ?></td>
                                <td class="text-right"><?= number_format($key->n_bisbisan, 3); ?></td>
                                <td class="text-right"><?= number_format($key->pemakaian, 3); ?></td>
                                <td class="text-right"><?= number_format($key->kebutuhan, 3); ?></td>
                                <td><?= $key->e_satuan_name; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div> -->
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
                    <table id="tabledatay" class="table color-table nowrap success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                        <!-- <thead>
                        <tr>
                            <th class="text-center" width="3%;">No</th>
                            <th>Kode</th>
                            <th>Nama Material</th>
                            <th class="text-center">Kebutuhan</th>
                            <th class="text-center">Acc Pelengkap</th>
                            <th class="text-center">Stok Gudang</th>
                            <th class="text-center">Sisa Schedule <br>Belum Terkirim</th>
                            <th>Satuan <br>Pemakaian</th>
                            <th class="text-center">OP Sisa</th>
                            <th class="text-center" width="10%;">Budgeting <br> Perhitungan</th>
                            <th class="text-center" width="10%;">Up Qty</th>
                            <th class="text-center" width="10%;">Aktual</th>
                            <th>Satuan <br> Pembelian</th>
                            <th width="12%;">Keterangan</th>
                        </tr>
                    </thead> -->
                        <thead>
                            <tr class="d-flex">
                                <th class="text-center col-1" width="3%;">No</th>
                                <th class="col-1">Kode</th>
                                <th class="col-5">Nama Barang</th>
                                <th class="text-center col-1">Kebutuhan <br>
                                    <i class="fa fa-question-circle fa-lg ml-2" tabindex="0" data-trigger="focus" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" title="Hasil konversi dari fc produk ke material"></i>
                                </th>
                                <th class="text-center col-1">Acc Pelengkap <br>
                                    <i class="fa fa-question-circle fa-lg ml-2" tabindex="0" data-trigger="focus" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" title="Khusus Acc Packing , max(fc berjalan, do) + fc dist - Stok gudang jadi - Stok packing"></i>
                                </th>
                                <th class="text-center col-1">Stok Gudang <br>
                                    <i class="fa fa-question-circle fa-lg ml-2" tabindex="0" data-trigger="focus" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" title="Stok terupdate pada saat budgeting qty dibuat"></i>
                                </th>
                                <th class="text-center col-1">Sisa Schedule <br>Belum Terkirim <br>
                                    <i class="fa fa-question-circle fa-lg ml-2" tabindex="0" data-trigger="focus" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" title="Sisa schedule yang belum terkirim"></i>
                                </th>
                                <th class="text-center col-1">Total Perhitungan <br>Kebutuhan <br>
                                    <i class="fa fa-question-circle fa-lg ml-2" tabindex="0" data-trigger="focus" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" title="Kebutuhan + acc pelengkap - stok gudang + sisa schedule"></i>
                                </th>
                                <th class="col-1">Satuan <br>Pemakaian</th>
                                <th class="text-center col-1">OP Sisa <br>
                                    <i class="fa fa-question-circle fa-lg ml-2" tabindex="0" data-trigger="focus" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" title="Sisa OP Belum Diterima"></i>
                                </th>
                                </th>
                                </th>
                                <th class="text-center col-1" width="10%;">Budgeting <br> Perhitungan</th>
                                <th class="text-center col-1" width="10%;">Up Qty</th>
                                <th class="text-center col-1" width="15%;">Aktual</th>
                                <th class="col-1">Satuan <br> Pembelian</th>
                                <th class="col-2" width="12%;">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 0;
                            $group = "";
                            foreach ($datadetaill as $row) {
                                $x++;
                                $no++;

                                $budgeting = $row->kebutuhan - ($row->mutasi) + $row->n_acc_pelengkap + $row->estimasi ;
                                // if ($row->e_operator!=null) {
                                //     $hitungan = my_operator($budgeting,$row->n_faktor,$row->e_operator);
                                // }else{
                                //     $hitungan = $budgeting;
                                // }
                                if ($group!=$row->e_nama_group_barang) {?>
                                    <tr class="d-flex table-active">
                                        <td class="col-12" colspan="7"><b><?= $row->e_nama_group_barang;?></b></td>
                                        <td class="col-7" colspan="6"></td>
                                    </tr>
                                <?php }
                                $group = $row->e_nama_group_barang;
                            ?>
                                <tr class="d-flex">
                                    <td class="text-center col-1"><?= $no; ?></td>
                                    <td class="col-1"><?= $row->i_material; ?></td>
                                    <td class="col-5"><?= ucwords(strtolower($row->e_material_name)); ?></td>
                                    <td class="text-right col-1">
                                        <input readonly type="text" class="form-control input-sm font-11 text-right" name="budgeting_awal<?= $x; ?>" id="budgeting_awal<?= $x; ?>" autocomplete="off" onkeyup="angkahungkul(this); hetang(<?= $x; ?>);" onblur="if(this.value==''){this.value='0';hetang(<?= $x; ?>);}" onfocus="if(this.value=='0'){this.value='';}" value="<?= number_format($row->kebutuhan, 2); ?>">
                                    </td>
                                    <td class="text-right col-1">
                                        <input readonly type="text" class="form-control input-sm font-11 text-right" name="acc_pelengkap<?= $x; ?>" id="acc_pelengkap<?= $x; ?>" autocomplete="off" onkeyup="angkahungkul(this); hetang(<?= $x; ?>);" onblur="if(this.value==''){this.value='0';hetang(<?= $x; ?>);}" onfocus="if(this.value=='0'){this.value='';}" value="<?= number_format($row->n_acc_pelengkap, 2); ?>">
                                    </td>
                                    <td class="text-right col-1">
                                        <input readonly type="text" class="form-control input-sm font-11 text-right" name="stok<?= $x; ?>" id="stok<?= $x; ?>" autocomplete="off" onkeyup="angkahungkul(this); hetang(<?= $x; ?>);" onblur="if(this.value==''){this.value='0';hetang(<?= $x; ?>);}" onfocus="if(this.value=='0'){this.value='';}" value="<?= number_format($row->mutasi, 2); ?>">
                                    </td>
                                    <td class="text-right col-1">
                                        <input type="text" class="form-control input-sm font-11 text-right" name="schedule<?= $x; ?>" id="schedule<?= $x; ?>" autocomplete="off" onkeyup="angkahungkul(this); hetang(<?= $x; ?>);" onblur="if(this.value==''){this.value='0';hetang(<?= $x; ?>);}" onfocus="if(this.value=='0'){this.value='';}" value="<?= number_format($row->estimasi,2); ?>">
                                    </td>
                                    <td class="text-right col-1">
                                        <input readonly type="text" class="form-control input-sm font-11 text-right" name="total_kebutuhan<?= $x; ?>" id="total_kebutuhan<?= $x; ?>" autocomplete="off" onkeyup="angkahungkul(this); hetang(<?= $x; ?>);" onblur="if(this.value==''){this.value='0';hetang(<?= $x; ?>);}" onfocus="if(this.value=='0'){this.value='';}" value="<?= number_format($budgeting, 2); ?>">
                                    </td>

                                    <td class="col-1"><?= ucwords(strtolower($row->e_satuan_name)); ?></td>
                                    <td class="text-right col-1">
                                        <input type="text" class="form-control input-sm font-11 text-right" name="sisaop<?= $x; ?>" id="sisaop<?= $x; ?>" autocomplete="off" onkeyup="angkahungkul(this); hetang(<?= $x; ?>);" onblur="if(this.value==''){this.value='0';hetang(<?= $x; ?>);}" onfocus="if(this.value=='0'){this.value='';}" value="<?= number_format($row->op_sisa,2); ?>">
                                    </td>
                                    <td class="text-right col-1">
                                        <input readonly type="text" class="form-control input-sm font-11 text-right" name="nilai_budgeting<?= $x; ?>" id="nilai_budgeting<?= $x; ?>" autocomplete="off" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" onkeyup="angkahungkul(this);" value="<?= number_format($row->n_budgeting_perhitungan, 2); ?>">
                                    </td>
                                    <td class="text-right col-1">
                                        <input type="text" class="form-control input-sm font-11 text-right" name="up<?= $x; ?>" id="up<?= $x; ?>" autocomplete="off" onkeyup="angkahungkul(this); hetang(<?= $x; ?>);" onblur="if(this.value==''){this.value='0';hetang(<?= $x; ?>);}" onfocus="if(this.value=='0'){this.value='';}" value="<?= number_format($row->persen_up,2); ?>">
                                    </td>
                                    <td class="text-right col-1">
                                        <input readonly type="text" class="form-control input-sm font-11 text-right" name="nilai_actual<?= $x; ?>" id="nilai_actual<?= $x; ?>" autocomplete="off" value="<?= number_format($row->n_budgeting, 2); ?>">
                                    </td>
                                    <td class="col-1"><?= ucwords(strtolower($row->e_satuan_konversi)); ?></td>
                                    <td class="col-2"><input type="text" class="form-control input-sm font-11" name="ket<?= $x; ?>" id="ket<?= $x; ?>" value="<?= $row->e_remark; ?>" autocomplete="off"></td>
                                    <input type="hidden" name="id_material_item<?= $x; ?>" value="<?= $row->id_material; ?>">
                                    <input type="hidden" name="i_satuan_konversi<?= $x; ?>" value="<?= $row->i_satuan_konversi; ?>">
                                    <input type="hidden" name="e_operator<?= $x; ?>" id="e_operator<?= $x; ?>" value="<?= $row->e_operator; ?>">
                                    <input type="hidden" name="n_faktor<?= $x; ?>" id="n_faktor<?= $x; ?>" value="<?= $row->n_faktor; ?>">
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
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script src="<?= base_url(); ?>assets/js/jquery.validate.min.js"></script>
<script>
    /*----------  LOAD SAAT DOKUMEN DIBUKA  ----------*/
    $(document).ready(function() {
        //hetang();
        /*----------  Load Form Validation  ----------*/
        $('#cekinputan').validate({
            errorClass: "my-error-class",
            validClass: "my-valid-class"
        });

        $('#ibudgeting').mask('SSS-0000-000000S');
        $('.select2').select2();
        /*----------  Tanggal tidak boleh kurang dari hari ini!  ----------*/
        showCalendar('.date', 0);
        popover();
        fixedtable($('.table'));
    });

    /*----------  RUBAH NO DOKUMEN (GANTI TANGGAL & BAGIAN)  ----------*/
    $('#ibagian, #ddocument').change(function(event) {
        number();
    });

    /*----------  RUNNING NUMBER DOKUMEN  ----------*/
    function number() {
        if (($('#ibagian').val() == $('#ibagianold').val())) {
            $('#ibudgeting').val($('#ibudgetingold').val());
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
                    $('#ibudgeting').val(data);
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
                if (data == 1 && ($('#ibudgeting').val() != $('#ibudgetingold').val())) {
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

        let stok = parseFloat(formatulang($('#stok' + i).val()));
        let sisaop = parseFloat(formatulang($('#sisaop' + i).val()));
        let schedule = parseFloat(formatulang($('#schedule' + i).val()));
        let budgeting_awal = parseFloat(formatulang($('#budgeting_awal' + i).val()));
        let acc_pelengkap = parseFloat(formatulang($('#acc_pelengkap' + i).val()));

        let up = parseFloat(formatulang($('#up' + i).val()));
        // console.log(stok+'|'+sisaop+'|'+schedule+'|'+budgeting_awal+'|'+acc_pelengkap+'|'+up);
        // let nilai_budgeting = parseFloat($('#nilai_budgeting'+i).val());
        // let nilai_actual = parseFloat($('#nilai_actual'+i).val());

        var e_operator = $('#e_operator' + i).val();


        var subtotal = (budgeting_awal - (stok)) + acc_pelengkap + schedule;
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
            nilai_budgeting += sisaop;
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
                if (!ada) {
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
                                    $("select").attr("disabled", true);
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
                } else {
                    swal('Maaf :(', 'Total Jumlah Retur harus lebih besar dari 0 !', 'error');
                    return false;
                }
            }
        }
        return false;
    })
</script>