<style type="text/css">
    .pudding {
        padding-left: 3px;
        padding-right: 3px;
        font-size: 14px;
        background-color: #e1f1e4;
    }
</style>
<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-3">Bagian Pembuat</label>
                        <label class="col-md-3">No Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-2">Batasan Pemenuhan</label>
                        <label class="col-md-2">Jenis Pembelian</label>
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2" required="" disabled="true">
                                <option value="<?= $data->i_bagian; ?>"><?= $data->bagian_pembuat; ?></option>
                            </select>
                            <input type="hidden" id="id" name="id" value="<?= $data->id ?>">
                            <input type="hidden" id="ibagian" name="ibagian" value="<?= $data->i_bagian; ?>">
                            <input type="hidden" id="istatus" name="istatus" value="<?= $data->i_status; ?>">
                        </div>
                        <?php if ($data->i_status != '6') { ?>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="text" name="iop" id="iop" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="PP-2010-000001" maxlength="15" class="form-control input-sm" value="<?= $data->i_op; ?>" aria-label="Text input with dropdown button">
                                    <!-- <span class="input-group-addon">
                                        <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                    </span> -->
                                </div>
                                <!-- <span class="notekode">Format : (<?= $data->i_op; ?>)</span><br>
                                <span class="notekode" hidden="true"><b> * No. Sudah Ada!</b></span> -->
                            </div>
                        <?php } else { ?>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="text" name="iop" id="iop" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="PP-2010-000001" maxlength="15" class="form-control input-sm" value="<?= $data->i_op; ?>" aria-label="Text input with dropdown button">
                                </div>
                            </div>
                        <?php
                        } ?>
                        <input type="hidden" id="d_pp" name="d_pp" value="<?= date("d-m-Y", strtotime($data->d_pp)); ?>">
                        <?php if ($data->i_status != '6') { ?>
                            <div class="col-sm-2">
                                <input type="text" id="dop" name="dop" class="form-control input-sm date" required="" readonly value="<?= $data->d_op; ?>">
                            </div>
                        <?php } else { ?>
                            <div class="col-sm-2">
                                <input type="text" id="dop" name="dop" class="form-control input-sm" required="" readonly value="<?= $data->d_op; ?>">
                            </div>
                        <?php } ?>
                        <?php if ($data->i_status != '6') { ?>
                            <div class="col-sm-2">
                                <input type="text" id="dbp" name="dbp" class="form-control input-sm date" required="" readonly value="<?= date('d-m-Y', strtotime($data->d_deliv)); ?>">
                            </div>
                        <?php } else { ?>
                            <div class="col-sm-2">
                                <input type="text" id="dbp" name="dbp" class="form-control input-sm" required="" readonly value="<?= date('d-m-Y', strtotime($data->d_deliv)); ?>">
                            </div>
                        <?php } ?>
                        <div class="col-sm-2">
                            <input type="text" id="jenis" name="jenis" class="form-control input-sm" required="" readonly value="<?= $data->jenis_pembelian; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <!-- <label class="col-md-3">Gudang</label> -->
                        <!-- <label class="col-md-3">No Referensi</label> -->
                        <label class="col-md-3">Supplier</label>
                        <label class="col-md-3">Importance Status</label>
                        <label class="col-md-6">Keterangan</label>
                        <!-- <div class="col-sm-3"> -->
                        <?php $e_bagian_name = str_replace('"', '', str_replace("}", "", str_replace("{", "", str_replace(",", ",", $data->e_bagian_name)))); ?>
                        <!-- <input type="text" name="egudang" id="egudang" class="form-control input-sm" value="<?php /* $e_bagian_name */ ?>" readonly required> -->
                        <!-- </div> -->
                        <!-- <div class="col-sm-3">
                            <input type="text" name="ipp" id="ipp" class="form-control input-sm" value="<?php /* $data->i_pp */ ?>" readonly required>
                        </div> -->
                        <div class="col-sm-3">
                            <input type="hidden" id="isupplier" name="isupplier" class="form-control" required="" onkeyup="gede(this)" value="<?= $data->i_supplier; ?>" readonly>
                            <input type="text" id="esupplier" name="esupplier" class="form-control input-sm" required="" onkeyup="gede(this)" value="<?= $data->i_supplier . " - " . $data->e_supplier_name; ?>" readonly>
                            <input type="hidden" id="esuppliername" name="esuppliername" value="<?= $data->e_supplier_name; ?>">
                            <input type="hidden" id="ntop" name="ntop" value="<?= $data->n_top; ?>">
                            <?php if ($data->i_type_pajak == 'I') {
                                $fppn = 't';
                            } else if ($data->i_type_pajak == 'E') {
                                $fppn = 'f';
                            }
                            ?>
                            <input type="hidden" id="itypepajak" name="itypepajak" value="<?= $data->i_type_pajak; ?>">
                            <input type="hidden" id="fppn" name="fppn" value="<?= $fppn; ?>">
                            <input type="hidden" id="ndiskon" name="ndiskon" value="<?= $data->n_diskon; ?>">
                            <input type="hidden" id="fpkp" name="fpkp" value="<?= $data->f_pkp; ?>">
                        </div>
                        <div class="col-sm-3">
                            <?php if ($data->i_status != '6') { ?>
                                <select name="importantstatus" id="importantstatus" class="form-control select2">
                                    <option value="<?= $data->i_status_op; ?>"><?= $data->e_status_op; ?></option>
                                </select>
                            <?php } else { ?>
                                <input type="hidden" name="importantstatusharga" id="importantstatusharga" value="<?= $data->i_status_op; ?>">
                                <input type="text" name="emportantstatus" class="form-control" id="emportantstatus" value="<?= $data->e_status_op; ?>" readonly>
                            <?php } ?>
                        </div>
                        <?php if ($data->i_status != '6') { ?>
                            <div class="col-sm-6">
                                <textarea type="text" name="eremarkh" id="eremark" class="form-control" value="" placeholder="Keterangan"><?= $data->e_remark; ?></textarea>
                            </div>
                        <?php } else { ?>
                            <div class="col-sm-6">
                                <textarea type="text" name="eremarkh" id="eremark" class="form-control" value="" placeholder="Keterangan" readonly><?= $data->e_remark; ?></textarea>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <?php if ($data->i_status == '1' || $data->i_status == '3' || $data->i_status == '7') { ?>
                                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return validasi();"><i class="fa fa-save"></i>&nbsp;&nbsp;Update</button>&nbsp;
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
<div class="white-box" id="detail">
    <div class="col-sm-5">
        <h3 class="box-title m-b-0">Detail Barang</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr class="d-flex">
                        <th class="text-center col-1">No</th>
                        <th class="col-1">Kode</th>
                        <th class="col-4">Nama Material</th>
                        <th class="col-1">Kode Material<br>Supplier </th>
                        <th class="text-right col-1">Qty PP</th>
                        <th class="text-right col-1" width="8%">Qty OP</th>
                        <th class="text-right col-1" width="8%">Min Order</th>
                        <th class="col-1">Satuan</th>
                        <th class="text-right col-1" width="10%;">Harga Lama</th>
                        <th class="text-right col-1" width="10%;">Harga Baru Exc</th>
                        <th class="text-right col-1" width="10%;">Harga Baru Inc</th>
                        <th class="text-right col-1" width="12%;">Total</th>
                        <th class="col-2">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($data2) {
                        $i = 0;
                        $group = "";
                        $no = 0;
                        foreach ($data2 as $row) {
                            $i++;
                            $no++;
                            if ($data->i_status == '2') {
                                $row->n_sisa = $row->n_qty_pp - $row->qty_op + $row->n_quantity;
                            }else{
                                $row->n_sisa = $row->n_qty_pp - $row->qty_op;
                            }
                            $total = $row->n_quantity * $row->v_price_ppn;
                            if ($group == "") { ?>
                                <tr class="pudding d-flex">
                                    <td colspan="12">Nomor PP : <b><?= $row->i_pp; ?></b> &nbsp;&nbsp;-&nbsp;&nbsp; Tanggal PP : <b><?= $row->d_pp; ?></b> &nbsp;&nbsp; (<b><?= $row->e_bagian_name; ?> )</td>
                                </tr>
                                <?php } else {
                                if ($group != $row->id_pp) { ?>
                                    <tr class="pudding d-flex">
                                        <td colspan="12">Nomor PP : <b><?= $row->i_pp; ?></b> &nbsp;&nbsp;-&nbsp;&nbsp; Tanggal PP : <b><?= $row->d_pp; ?></b> &nbsp;&nbsp; (<b><?= $row->e_bagian_name; ?> )</td>
                                    </tr>
                            <?php $no = 1;
                                }
                            }
                            $group = $row->id_pp

                            ?>
                            <tr class="d-flex">
                                <td class="text-center col-1">
                                    <?= $i; ?>
                                    <input type="hidden" class="form-control" readonly id="baris<?= $i; ?>" name="baris<?= $i; ?>" value="<?= $i; ?>">
                                    <input type="hidden" id="ipp<?= $i; ?>" name="ipp<?= $i; ?>" value="<?= $row->i_pp; ?>" readonly>
                                    <input type="hidden" name="idpp<?= $i; ?>" id="idpp<?= $i; ?>" value="<?= $row->id_pp; ?>">
                                    <input type="hidden" class="form-control" id="ibagian<?= $i; ?>" name="ibagian<?= $i; ?>" value="<?= $row->i_bagian; ?>" readonly>
                                </td>
                                <td class="col-1">
                                    <input type="text" class="form-control input-sm" id="imaterial<?= $i; ?>" name="imaterial<?= $i; ?>" value="<?= $row->i_material; ?>" readonly>
                                </td>
                                <td class="col-4">
                                    <input type="text" class="form-control input-sm" id="ematerialname<?= $i; ?>" name="ematerialname<?= $i; ?>" value="<?= $row->e_material_name; ?>" readonly>
                                </td>
                                <td class="col-1">
                                    <input type="text" class="form-control input-sm" id="imaterialsupplier<?= $i; ?>" name="imaterialsupplier<?= $i; ?>" value="<?= $row->i_material_supplier; ?>">
                                </td>
                                <td class="col-1">
                                    <input type="text" class="form-control input-sm text-right" id="nquantitypp<?= $i; ?>" name="nquantitypp<?= $i; ?>" value="<?= $row->n_sisa; ?>" autocomplete="off" readonly>
                                </td>

                                <td class="col-1">
                                    <input type="text" class="form-control input-sm text-right" id="nquantity<?= $i; ?>" name="nquantity<?= $i; ?>" value="<?= $row->n_quantity; ?>" autocomplete="off" onkeyup="valstock(<?= $i; ?>);hitungall(this.value, '<?= $row->i_material; ?>', '<?= $i; ?>' ); angkahungkul(this);if(this.value=='0'){this.value='';}" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}">
                                    <input type="hidden" class="form-control" id="npemenuhan<?= $i; ?>" name="npemenuhan<?= $i; ?>" value="<?= $row->n_sisa; ?>" readonly>
                                </td>

                                <td class="col-1">
                                    <input type="text" class="form-control input-sm text-right" id="minorder<?= $i; ?>" name="minorder<?= $i; ?>" value="<?= $row->n_min_order; ?>" autocomplete="off" readonly>
                                </td>

                                <td class="col-1">
                                    <input type="hidden" id="isatuan<?= $i; ?>" name="isatuan<?= $i; ?>" value="<?= $row->i_satuan_code; ?>" readonly>
                                    <input type="text" class="form-control input-sm" id="isatuan1<?= $i; ?>" name="isatuan1<?= $i; ?>" value="<?= $row->e_satuan_name; ?>" readonly>
                                </td>

                                <td class="col-1">
                                    <input type="text" class="form-control input-sm text-right" id="vpricedefault<?= $i; ?>" name="vpricedefault<?= $i; ?>" value="<?= number_format($row->v_price_default, 2); ?>" autocomplete="off" readonly>
                                </td>

                                <td class="col-1">
                                    <input type="text" class="form-control input-sm text-right <?= $row->i_material; ?> number" id="vprice<?= $i; ?>" name="vprice<?= $i; ?>" value="<?= (!$row->v_price) ? 0 : number_format($row->v_price, 4); ?>" autocomplete="off" onkeyup="hitungall(this.value, '<?= $row->i_material; ?>', '<?= $i; ?>' );angkahungkul(this);reformat(this);if(this.value=='0'){this.value='';}" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}">
                                </td>
                                <td class="col-1">
                                    <input type="text" class="form-control input-sm text-right number" id="vpriceppn<?= $i; ?>" name="vpriceppn<?= $i; ?>" value="<?= number_format($row->v_price_ppn, 4); ?>" readonly>
                                    <input type="hidden" id="ppn<?= $i; ?>" name="ppn<?= $i; ?>" value="<?= $row->n_ppn; ?>">
                                </td>

                                <td class="col-1">
                                    <input type="text" class="form-control input-sm text-right number" id="vtotal<?= $i; ?>" name="vtotal<?= $i; ?>" value="<?= number_format($total, 4); ?>" readonly>
                                </td>
                                <td class="col-2">
                                    <?php if ($row->i_status != '6') { ?>
                                        <input type="text" class="form-control input-sm" id="eremark<?= $i; ?>" name="eremark<?= $i; ?>" value="<?= $row->remark; ?>" placeholder="Isi Keterangan Jika Ada!">
                                    <?php } else { ?>
                                        <input type="text" class="form-control input-sm" id="eremark<?= $i; ?>" name="eremark<?= $i; ?>" value="<?= $row->remark; ?>" placeholder="Isi Keterangan Jika Ada!">
                                    <?php } ?>
                                </td>
                            </tr>

                    <? }
                    } else {
                        $i = 0;
                        $read = "disabled";
                        echo "<table class=\"table table-striped bottom\" style=\"width:100%;\"><tr><td colspan=\"16\" style=\"text-align:center;\">Maaf Tidak Ada PP!</td></tr></table>";
                    } ?>
                    <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
</div>
</div>

<div class="white-box" id="detail2">
    <div class="col-sm-5">
        <h3 class="box-title m-b-0">Total Barang *khusus multi pp</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table inverse-table table-bordered class" cellpadding="9" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th style="text-align:center;width:4%">No</th>
                        <th style="text-align:center;width:10%">Kode</th>
                        <th style="text-align:center;width:30%">Nama Barang</th>
                        <th style="text-align:center;width:9%">Qty</th>
                        <th style="text-align:center;width:9%">Min Order</th>
                        <th style="text-align:center;width:11%">Satuan</th>
                        <th style="text-align:center;width:10%">Harga</th>
                        <th style="text-align:center;width:10%">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $urut = 0;
                    $total_semua = 0;
                    if ($datatotal) {
                        foreach ($datatotal as $row) {
                            $urut++;
                            $total = $row->n_sisa * $row->v_price_ppn;
                            $total_semua += $total;
                    ?>
                            <tr>
                                <td class="text-center">
                                    <?= $urut; ?>
                                </td>
                                <td>
                                    <?php echo $row->i_material; ?>
                                </td>
                                <td>
                                    <?php echo $row->e_material_name; ?>
                                </td>
                                <td class="text-right" id="sisa_<?= $row->i_material; ?>">
                                    <span><?php echo $row->n_sisa; ?></span>
                                </td>

                                <td class="text-right">
                                    <span><?php echo $row->n_min_order; ?></span>
                                </td>

                                <td>
                                    <?php echo $row->e_satuan_name; ?>
                                </td>
                                <td class="text-right">
                                    <input type="text" class="form-control input-sm text-right number" id="harga_<?= $row->i_material; ?>" value="<?= number_format($row->v_price_ppn, 4); ?>" readonly>
                                </td>
                                <td class="text-right">
                                    <input type="text" class="form-control input-sm text-right number" id="total_<?= $row->i_material; ?>" value="<?= number_format($total, 4); ?>" readonly>
                                </td>
                            </tr>
                        <?php  } ?>
                        <tr>
                            <td colspan="7"></td>
                            <td colspan="1" class="text-right"><input type="text" class="form-control input-sm text-right number" id="totalsemua" value="<?= number_format($total_semua, 4); ?>" readonly></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</form>
<!-- <script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script> -->
<script src="<?= base_url(); ?>assets/js/jquery.validate.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2();
        $('#dop').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: "dd-mm-yyyy",
            todayBtn: "linked",
            daysOfWeekDisabled: [0],
            startDate: document.getElementById('d_pp').value,
        });
        // showCalendar('.date');
        fixedtable($('.table'));
        cekharga();
        //$('.number').number(true, 4, '.', ',');

        // $('#iop').mask('SS-0000-000000S');

        $('#importantstatus').select2({
            placeholder: "Pilih Importance Status",
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder . '/cform/importancestatus'); ?>',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
    });

    $('#send').click(function(event) {
        statuschange('<?= $folder; ?>', $('#id').val(), '2', '<?= $dfrom . "','" . $dto; ?>');
    });

    $('#cancel').click(function(event) {
        statuschange('<?= $folder; ?>', $('#id').val(), '1', '<?= $dfrom . "','" . $dto; ?>');
    });

    $('#hapus').click(function(event) {
        statuschange('<?= $folder; ?>', $('#id').val(), '5', '<?= $dfrom . "','" . $dto; ?>');
    });

    function maxi() {
        /* var dop = $('#dop').val();
        var da = dop.substr(0, 2);
        var ma = dop.substr(3, 2);
        var ya = dop.substr(6, 4);
        var today = new Date(ya, ma, da);
        var year = today.getFullYear();
        var month = today.getMonth();
        var date = today.getDate();
        var day = new Date(year, month, date + 30);

        mnth = ("0" + (day.getMonth())).slice(-2),
            dath = ("0" + day.getDate()).slice(-2);
        jam = [dath, mnth, today.getFullYear()].join("-");

        $('#dbp').val(jam); */
        const date = $('#dop').val().split('-');
        const date_op = date[2] + '-' + date[1] + '-' + date[0];
        let now = new Date(date_op);
        // console.log(now);
        let next30days = new Date(now.setDate(now.getDate() + 30));
        $('#dbp').val(formatDate(next30days));
        // console.log('Next 30th day: ' + next30days.toUTCString());
    }

    function validasi() {
        var s = 0;
        var jml = $('#jml').val();
        var harga = $('#vprice' + i).val();

        var textinputs = document.querySelectorAll('input[type=input]');
        var empty = [].filter.call(textinputs, function(el) {
            return !el.checked
        });

        if (document.getElementById('dop').value == '') {
            swal("Tanggal OP Masih Kosong!");
            return false;
        } else if (document.getElementById('importantstatus').value == '' || document.getElementById('importantstatus').value == null) {
            swal("Importance Status Masih Kosong");
            return false;
        } else {
            $("#tabledatax tbody tr").each(function() {
                $(this).find("td .inputitem").each(function() {
                    if ($(this).val() == '' || $(this).val() == null || $(this).val() == 0) {
                        swal('Harga Tidak Boleh Kosong Atau 0!');
                        ada = true;
                    }
                });
            });
            return true
        }
    }

    $("#iop").keyup(function() {
        $.ajax({
            type: "post",
            data: {
                'kode': $(this).val(),
            },
            url: '<?= base_url($folder . '/cform/cekkode'); ?>',
            dataType: "json",
            success: function(data) {
                if (data == 1) {
                    $(".notekode").attr("hidden", false);
                    $("#submit").attr("disabled", true);
                } else {
                    $(".notekode").attr("hidden", true);
                    $("#submit").attr("disabled", false);
                }
            },
            error: function() {
                swal('Error :)');
            }
        });
    });

    $('#ceklis').click(function(event) {
        if ($('#ceklis').is(':checked')) {
            $("#iop").attr("readonly", false);
        } else {
            $("#iop").attr("readonly", true);
            $("#iop").val("<?= $number; ?>");
        }
    });

    function cekharga() {

        var i = $('#jml').val();
        var harga = $('#vprice' + i).val();

        if (harga == '') {
            swal("Harga masih kosong, Input Harga terlebih dahulu di Master Harga Per Supplier");
        }
    }

    $("#dop").change(function() {
        maxi();
        $.ajax({
            type: "post",
            data: {
                'tgl': $(this).val(),
            },
            url: '<?= base_url($folder . '/cform/number'); ?>',
            dataType: "json",
            success: function(data) {
                $('#iop').val(data);
            },
            error: function() {
                swal('Error :)');
            }
        });
    });


    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#sendd").attr("disabled", true);
        $("#cancel").attr("disabled", true);
    });

    function getenabledcancel() {
        swal("Berhasil", "Cancel Dokumen", "success");
        $('#sendd').attr("disabled", true);
        $('#cancel').attr("disabled", true);
        $('#submit').attr("disabled", true);
    }

    function getenabledsend() {
        swal("Berhasil", "Dokumen Terkirim ke Atasan", "success");
        $('#sendd').attr("disabled", true);
        $('#cancel').attr("disabled", true);
        $('#submit').attr("disabled", true);
    }

    function valstock(id) {
        // var jml = $('#jml').val();
        // for(var i=1; i<=jml; i++){        
        //        var stock         = $('#nquantity'+i).val();
        //        var noutstanding  = $('#npemenuhan'+i).val();
        //        var qty_now       = $('#nquantity_now'+i).val();
        //        if (stock == ''){
        //         stock = 0;
        //     }
        //     if(parseFloat(stock) > parseFloat(noutstanding)){
        //         swal ("Jumlah quantity melebihi stock");
        //         document.getElementById("nquantity"+id).value=0;
        //         break;
        //     }
        // }
        var jml = $('#jml').val();
        // for(var i=1; i<=jml; i++){        
        var stock = $('#nquantity' + id).val();
        var noutstanding = $('#npemenuhan' + id).val();
        if (stock == '') {
            stock = 0;
        }
        if (parseFloat(noutstanding) < parseFloat(stock)) {
            swal("Jumlah quantity melebihi Sisa PP");
            $('#nquantity' + id).val(noutstanding);
            // break;
        }
    }

    function hitung(nilai, kode, i) {
        //setter
        // alert(formatulang('1,000,000.12'));
        var awal = formatulang(nilai) == '' ? nilai : awal;
        awal = formatulang(nilai) || 0;
        //alert(awal + " " + kode);
        $('.' + kode + ':not(#vprice' + i + ')').val(awal);
        var ppn = formatulang($('#ppn' + i).val());
        awal = (parseFloat(awal) + (parseFloat(awal) * (parseFloat(ppn) / 100)));
        $('#harga_' + kode).val(awal);

        var jml = $('#jml').val();
        var total_item = 0;
        var total_semua = 0;
        for (var i = 1; i <= jml; i++) {

            var qty = $('#nquantity' + i).val() == '' ? $('#nquantity' + i).val(0) : qty;
            qty = $('#nquantity' + i).val() || 0;

            var price = formatulang($('#vprice' + i).val()) == '' ? $('#vprice' + i).val(0) : price;
            price = formatulang($('#vprice' + i).val()) || 0;
            var ppn = formatulang($('#ppn' + i).val()) || 0;
            var price = (parseFloat(price) + (parseFloat(price) * (parseFloat(ppn) / 100)));
            $('#vpriceppn' + i).val(price.toLocaleString('en-US'));
            total = (parseFloat(qty) * parseFloat(price));
            $('#vtotal' + i).val(total.toLocaleString('en-US'));

            if ($('#imaterial' + i).val() == kode) {
                total_item += total;
            }
            total_semua += total;
        }
        $('#total_' + kode).val(total_item);
        $('#totalsemua').val(total_semua);
    }

    function hitungqty(kode) {
        //setter
        var jml = $('#jml').val();
        var sisa_qty = 0;
        var total_item = 0;
        var total_semua = 0;
        for (var i = 1; i <= jml; i++) {


            var qty = $('#nquantity' + i).val() == '' ? $('#nquantity' + i).val(0) : qty;
            qty = $('#nquantity' + i).val() || 0;

            var price = formatulang($('#vprice' + i).val()) == '' ? $('#vprice' + i).val(0) : price;
            price = formatulang($('#vprice' + i).val()) || 0;
            var ppn = formatulang($('#ppn' + i).val()) || 0;
            var price = (parseFloat(price) + (parseFloat(price) * (parseFloat(ppn) / 100)));
            $('#vpriceppn' + i).val(formatcemua(Math.round(price)));
            /* alert(ppn%); */

            total = (parseFloat(qty) * parseFloat(price));
            $('#vtotal' + i).val(total);

            if ($('#imaterial' + i).val() == kode) {
                sisa_qty += parseFloat(qty);
                total_item += total;
            }
            total_semua += total;
        }
        //alert(sisa_qty + "  " + total_qty);
        $('#sisa_' + kode + ' span').text(sisa_qty);
        $('#total_' + kode).val(total_item);
        $('#totalsemua').val(total_semua);
    }

    function hitungall(nilai, kode, i) {
        //setter
        // alert(formatulang('1,000,000.12'));
        var awal = formatulang(nilai) == '' ? nilai : awal;
        awal = formatulang(nilai) || 0;
        //alert(awal + " " + kode);
        $('.' + kode + ':not(#vprice' + i + ')').val(awal);
        var ppn = formatulang($('#ppn' + i).val());
        awal = (parseFloat(awal) + (parseFloat(awal) * (parseFloat(ppn) / 100)));
        $('#harga_' + kode).val(formatdecimal(awal));

        var jml = $('#jml').val();
        var sisa_qty = 0;
        var total_item = 0;
        var total_semua = 0;
        for (var i = 1; i <= jml; i++) {

            var qty = $('#nquantity' + i).val() == '' ? $('#nquantity' + i).val(0) : qty;
            qty = $('#nquantity' + i).val() || 0;

            var price = formatulang($('#vprice' + i).val()) == '' ? $('#vprice' + i).val(0) : price;
            price = formatulang($('#vprice' + i).val()) || 0;
            var ppn = formatulang($('#ppn' + i).val()) || 0;
            var price = (parseFloat(price) + (parseFloat(price) * (parseFloat(ppn) / 100)));
            price = formatdecimal(price.toFixed(4));
            $('#vpriceppn' + i).val(price);
            total = (parseFloat(qty) * formatulang(price));
            $('#vtotal' + i).val(formatdecimal(total.toFixed(4)));

            if ($('#imaterial' + i).val() == kode) {
                sisa_qty += parseFloat(qty);
                total_item += total;
            }
            total_semua += total;
        }
        $('#total_' + kode).val(formatdecimal(total_item));
        $('#totalsemua').val(formatdecimal(total_semua));
    }

    $(document).ready(function() {
        $("#cancel").on("click", function() {
            var iop = $("#iop").val();
            $.ajax({
                type: "POST",
                url: "<?= base_url($folder . '/cform/cancel'); ?>",
                data: {
                    'iop': iop,
                },
                dataType: 'json',
                delay: 250,
                success: function(data) {
                    return {
                        results: data
                    };
                },
                cache: true
            });
        });
    });

    $(document).ready(function() {
        $("#sendd").on("click", function() {
            var iop = $("#iop").val();
            $.ajax({
                type: "POST",
                url: "<?= base_url($folder . '/cform/sendd'); ?>",
                data: {
                    'iop': iop,
                },
                dataType: 'json',
                delay: 250,
                success: function(data) {
                    return {
                        results: data
                    };
                },
                cache: true
            });
        });
    });

    // function max_tgl(val) {
    //   $('#dop').datepicker('destroy');
    //   $('#dop').datepicker({
    //     autoclose: true,
    //     todayHighlight: true,
    //     format: "dd-mm-yyyy",
    //     todayBtn: "linked",
    //     daysOfWeekDisabled: [0],
    //     startDate: document.getElementById('dpp').value,
    // });
    // }
    // $('#dop').datepicker({
    //   autoclose: true,
    //   todayHighlight: true,
    //   format: "dd-mm-yyyy",
    //   todayBtn: "linked",
    //   daysOfWeekDisabled: [0],
    //   startDate: document.getElementById('dpp').value,
    // });

    // function max_tglkirim(val) {
    //   $('#ddelivery').datepicker('destroy');
    //   $('#ddelivery').datepicker({
    //     autoclose: true,
    //     todayHighlight: true,
    //     format: "dd-mm-yyyy",
    //     todayBtn: "linked",
    //     daysOfWeekDisabled: [0],
    //     startDate: document.getElementById('dop').value,
    // });
    // }
    // $('#ddelivery').datepicker({
    //   autoclose: true,
    //   todayHighlight: true,
    //   format: "dd-mm-yyyy",
    //   todayBtn: "linked",
    //   daysOfWeekDisabled: [0],
    //   startDate: document.getElementById('dop').value,
    // });
</script>