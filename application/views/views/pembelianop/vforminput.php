<style type="text/css">
    .pudding {
        padding-left: 3px;
        padding-right: 3px;
        font-size: 12px;
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
</style>
<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal', 'id' => 'cekinputan')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom; ?>/<?= $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i><?= $title_list; ?></a>
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
                                <input type="text" name="iop" id="iop" required="" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="PP-2010-000001" maxlength="15" class="form-control input-sm" value="<?= $number; ?>" aria-label="Text input with dropdown button">
                                <!-- <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span> -->
                            </div>
                            <!-- <span class="notekode">Format : (<?= $number; ?>)</span><br>
                            <span class="notekode" hidden="true"><b> * No. Sudah Ada!</b></span> -->
                        </div>
                        <div class="col-sm-2">
                            <input type="hidden" id="d_pp" name="d_pp" value="<?= date("d-m-Y", strtotime($data->d_pp));?>">
                            <input type="text" id="dop" name="dop" class="form-control input-sm date" required="" readonly value="<?= date("d-m-Y"); ?>">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="dbp" name="dbp" class="form-control input-sm date" required="" readonly value="<?= date("d-m-Y", strtotime('+1 month', strtotime(date('d-m-Y')))); ?>">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="jenis" name="jenis" class="form-control input-sm" required="" readonly value="<?= $datasup->jenis_pembelian; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Supplier</label>
                        <label class="col-md-3">Importance Status</label>
                        <label class="col-md-5">Keterangan</label>
                        <div class="col-sm-4">
                            <select name="isupplier" id="isupplier" required="" class="form-control select2">
                                <option value="<?= $data->i_supplier; ?>"><?= $data->i_supplier . " - " . $data->e_supplier_name; ?></option>
                            </select>
                            <input type="hidden" id="i_supplier" name="i_supplier" class="form-control" required="" onkeyup="gede(this)" value="<?= $data->i_supplier; ?>" readonly>
                            <input type="hidden" id="esupplier" name="esupplier" class="form-control input-sm" required="" onkeyup="gede(this)" value="<?= $data->i_supplier . " - " . $data->e_supplier_name; ?>" readonly>
                            <input type="hidden" id="esuppliername" name="esuppliername" value="<?= $data->e_supplier_name; ?>">
                            <input type="hidden" id="ntop" name="ntop" value="<?= $data->n_supplier_toplength; ?>">
                            <?php if ($data->i_type_pajak == 'I') {
                                $fppn = 't';
                            } else if ($data->i_type_pajak == 'E') {
                                $fppn = 'f';
                            } else if ($data->i_type_pajak == null) {
                                $fppn = '';
                            }
                            ?>
                            <input type="hidden" id="itypepajak" name="itypepajak" value="<?= $data->i_type_pajak; ?>">
                            <input type="hidden" id="fppn" name="fppn" value="<?= $fppn; ?>">
                            <input type="hidden" id="ndiskon" name="ndiskon" value="<?= $data->n_diskon; ?>">
                            <input type="hidden" id="fpkp" name="fpkp" value="<?= $data->f_pkp; ?>">
                        </div>
                        <div class="col-sm-3">
                            <select name="importantstatus" id="importantstatus" required="" class="form-control select2">
                            </select>
                        </div>
                        <div class="col-sm-5">
                            <textarea type="text" name="eremarkh" id="eremark" class="form-control" value="" placeholder="Keterangan"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder; ?>/cform/tambah/<?= $dfrom . '/' . $dto; ?>","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                            <button type="button" hidden="true" id="send" class="btn btn-primary btn-rounded btn-sm"> <i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
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
                        <th class="col-1">Kode<br>Material Supplier</th>
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
                            $n_sisa = $row->n_quantity - $row->qty_op;
                            $v_harga_konversi = $row->v_harga_konversi == 0 ? $row->hrgpp : $row->v_harga_konversi;
                            $v_harga_ppn = $v_harga_konversi;
                            if ($row->i_type_pajak != 'E') {
                                $v_harga_ppn = round($v_harga_konversi * $row->n_dpp);
                            }
                            $total = $row->n_quantity * $v_harga_ppn;
                            if ($group == "") { ?>
                                <tr class="pudding d-flex">
                                    <td colspan="12">Nomor PP : <b><?= $row->i_pp; ?></b> &nbsp;&nbsp;-&nbsp;&nbsp; Tanggal PP : <b><?= $row->d_pp; ?></b> &nbsp;&nbsp; (<b><?= $row->e_bagian_name; ?> )
                                    </td>
                                </tr>
                                <?php } else {
                                if ($group != $row->id_pp) { ?>
                                    <tr class="pudding d-flex">
                                        <td colspan="12">Nomor PP : <b><?= $row->i_pp; ?></b> &nbsp;&nbsp;-&nbsp;&nbsp; Tanggal PP : <b><?= $row->d_pp; ?></b> &nbsp;&nbsp; (<b><?= $row->e_bagian_name; ?> )
                                        </td>
                                    </tr>
                            <?php $no = 1;
                                }
                            }
                            $group = $row->id_pp
                            ?>
                            <tr class="d-flex">
                                <td width="5%" class="text-center col-1">
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
                                    <input type="text" class="form-control input-sm" autocomplete="off" id="imaterialsupplier<?= $i; ?>" name="imaterialsupplier<?= $i; ?>" value="<?= $row->i_material_supplier; ?>">
                                </td>
                                <td class="col-1">
                                    <input type="text" class="form-control input-sm text-right" id="nquantitypp<?= $i; ?>" name="nquantitypp<?= $i; ?>" value="<?= $n_sisa; ?>" autocomplete="off" readonly>
                                </td>
                                <td class="col-1">
                                    <input type="text" class="form-control input-sm text-right" id="nquantity<?= $i; ?>" name="nquantity<?= $i; ?>" value="<?= $n_sisa; ?>" autocomplete="off" onkeyup="valstock(<?= $i; ?>);hitungall(this.value, '<?= $row->i_material; ?>', '<?= $i; ?>' ); angkahungkul(this);if(this.value=='0'){this.value='';}" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}">
                                    <input type="hidden" class="form-control" id="npemenuhan<?= $i; ?>" name="npemenuhan<?= $i; ?>" value="<?= $n_sisa; ?>" readonly>
                                </td>

                                <td class="col-1">
                                    <input type="text" class="form-control input-sm text-right" id="minorder<?= $i; ?>" name="minorder<?= $i; ?>" value="<?= $row->n_order; ?>" autocomplete="off" readonly>
                                </td>

                                <td class="col-1">
                                    <input type="hidden" id="isatuan<?= $i; ?>" name="isatuan<?= $i; ?>" value="<?= $row->i_satuan_code; ?>" readonly>
                                    <input type="text" class="form-control input-sm" id="esatuan<?= $i; ?>" name="esatuan<?= $i; ?>" value="<?= $row->e_satuan_name; ?>" readonly>
                                </td>
                                <td class="col-1">
                                    <input type="text" class="form-control input-sm text-right" id="vpricedefault<?= $i; ?>" name="vpricedefault<?= $i; ?>" value="<?= number_format($v_harga_konversi, 4); ?>" autocomplete="off" readonly>
                                </td>
                                <td class="col-1">
                                    <input type="text" class="form-control input-sm text-right <?= $row->i_material; ?> number" id="vprice<?= $i; ?>" name="vprice<?= $i; ?>" value="<?php echo (!$v_harga_konversi) ? 0 : number_format($v_harga_konversi, 4); ?>" autocomplete="off" onkeyup="hitungall(this.value, '<?= $row->i_material; ?>', '<?= $i; ?>' );angkahungkul(this);reformat(this);if(this.value=='0'){this.value='';}" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" placeholder="0">
                                </td>
                                <td class="col-1">
                                    <input type="text" class="form-control input-sm text-right number" id="vpriceppn<?= $i; ?>" name="vpriceppn<?= $i; ?>" value="<?= number_format($v_harga_ppn, 4); ?>" readonly>
                                    <input type="hidden" id="ppn<?= $i; ?>" name="ppn<?= $i; ?>" value="<?= $row->n_tax; ?>">
                                </td>
                                <td class="col-1">
                                    <input type="text" class="form-control input-sm text-right number" id="vtotal<?= $i; ?>" name="vtotal<?= $i; ?>" value="<?= number_format($total, 4); ?>" readonly>
                                </td>
                                <td class="col-2">
                                    <input type="text" class="form-control input-sm" placeholder="Isi Keterangan Jika Ada!" id="eremark<?= $i; ?>" name="eremark<?= $i; ?>" value="">
                                </td>
                            </tr>
                    <?php
                        }
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

<div class="white-box" id="detail2">
    <div class="col-sm-5">
        <h3 class="box-title m-b-0">Total Barang *khusus multi pp</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table inverse-table table-bordered class" cellpadding="9" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th width="3%;">No</th>
                        <th>Kode</th>
                        <th>Nama Barang</th>
                        <th class="text-right">Qty</th>
                        <th class="text-right">Min Order</th>
                        <th>Satuan</th>
                        <!-- <th class="text-right" width="10%;">Harga</th> -->
                        <th class="text-right" width="12%;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $urut = 0;
                    $total_semua = 0;
                    if ($datatotal) {
                        foreach ($datatotal as $row) {
                            $urut++;
                            $v_harga_konversi = $row->v_harga_konversi == 0 ? $row->hrgpp : $row->v_harga_konversi;
                            $v_harga_ppn = $v_harga_konversi;
                            if ($row->i_type_pajak != 'E') {
                                $v_harga_ppn = round($v_harga_konversi * $row->n_dpp);
                            }
                            $row->n_sisa = $row->n_sisa - $row->qty_op;
                            $total = $row->n_sisa * $v_harga_ppn;
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
                                    <span><?php echo $row->n_order; ?></span>
                                </td>

                                <td>
                                    <?php echo $row->e_satuan_name; ?>
                                </td>
                                <td class="text-right" hidden="true">
                                    <input type="text" class="form-control input-sm text-right number" id="harga_<?= $row->i_material; ?>" value="<?= number_format($v_harga_ppn, 4); ?>" readonly>
                                </td>
                                <td class="text-right">
                                    <input type="text" class="form-control input-sm text-right number" id="total_<?= $row->i_material; ?>" value="<?= number_format($total, 4); ?>" readonly>
                                </td>
                            </tr>
                        <?php  } ?>
                        <tr>
                            <td colspan="6" class="text-right"><b>TOTAL</b></td>
                            <td colspan="1" class="text-right"><input type="text" class="form-control input-sm text-right number" id="totalsemua" value="<?= number_format($total_semua,4); ?>" readonly></td>
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
        $(".select2").select2();
        // showCalendar('.date');
        cekharga();
        fixedtable($('.table'));
        // $('.number').number( true, 4, '.', ',' );

        $('#cekinputan').validate({
            errorClass: "my-error-class",
            validClass: "my-valid-class"
        });
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

        $('#isupplier').select2({
            placeholder: "Pilih Supplier",
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder . '/cform/get_supplier'); ?>',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        }).change(function(event) {
            for (let i = 1; i <= $('#jml').val(); i++) {
                $.ajax({
                    type: "post",
                    data: {
                        'i_supplier': $(this).val(),
                        'i_material': $('#imaterial' + i).val(),
                        'd_document': $('#d_document').val(),
                    },
                    url: '<?= base_url($folder . '/cform/getmaterialprice'); ?>',
                    dataType: "json",
                    success: function(data) {
                        if (data.length > 0) {
                            $('#vpricedefault' + i).val(data[0]['v_price']);
                            $('#vprice' + i).val(data[0]['v_price']);
                            hitung(data[0]['v_price'], $('#imaterial' + i).val(), i);
                        } else {
                            $('#vpricedefault' + i).val(0);
                            $('#vprice' + i).val(0);
                            hitung(0, $('#imaterial' + i).val(), i);
                        }
                    },
                    error: function() {
                        swal('Ada kesalahan :(');
                    }
                });
            }
        });
    });

    $('#send').click(function(event) {
        statuschange('<?= $folder; ?>', $('#id').val(), '2', '<?= $dfrom . "','" . $dto; ?>');
    });

    function maxi() {
        /* var date     = new Date($('#dop').val());
        const newday = new Date(date.setDate(date.getDate() + 30)); // Set now + 30 days as the new date
        console.log(date, newday); */
        const date = $('#dop').val().split('-');
        const date_op = date[2] + '-' + date[1] + '-' + date[0];
        let now = new Date(date_op);
        // console.log(now);
        let next30days = new Date(now.setDate(now.getDate() + 30));
        $('#dbp').val(formatDate(next30days));
        // console.log('Next 30th day: ' + next30days.toUTCString());
        // date.setdate(date.getDate() + 30);
        /* var da      = dop.substr(0, 2);
        var ma      = dop.substr(3, 2);
        var ya      = dop.substr(6, 4);
        var today   = new Date(dop)+30;
        var year    = today.getFullYear();
        var month   = today.getMonth();
        var date    = today.getDate()+30;
        console.log(date);
        var day     = new Date(year, month, date);

        mnth = ("0" + (day.getMonth())).slice(-2),
        dath = ("0" + day.getDate()).slice(-2);
        jam  = [dath, mnth, today.getFullYear()].join("-");

        $('#dbp').val(jam); */
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

    function cekharga() {
        var i = $('#jml').val();
        var harga = $('#vprice' + i).val();
        var jenis = '<?= $jenis; ?>';
        if (harga == '' && jenis == 'credit') {
            //swal("Harga masih kosong, Input Harga terlebih dahulu di Master Harga Per Supplier");
            //$("#submit").attr("disabled", true);
            //$("#send").attr("disabled", true);
        }
    }

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#addrow").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#send").attr("hidden", false);
    });

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
            price = formatdecimal(price);
            $('#vpriceppn' + i).val(price);
            total = (parseFloat(qty) * formatulang(price));
            $('#vtotal' + i).val(formatdecimal(total));

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

    function valstock(id) {
        var stock = $('#nquantity' + id).val();
        var noutstanding = $('#npemenuhan' + id).val();
        if (stock == '') {
            stock = 0;
        }
        if (parseFloat(noutstanding) < parseFloat(stock)) {
            swal("Jumlah quantity melebihi Sisa PP");
            $('#nquantity' + id).val(noutstanding);
        }
    }

    /*function max_tgl(val) {
        $('#dop').datepicker('destroy');
        $('#dop').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: "dd-mm-yyyy",
            todayBtn: "linked",
            daysOfWeekDisabled: [0],
            startDate: document.getElementById('dpp').value,
        });
    }*/

    $('#dop').datepicker({
        autoclose: true,
        todayHighlight: true,
        format: "dd-mm-yyyy",
        todayBtn: "linked",
        daysOfWeekDisabled: [0],
        startDate: document.getElementById('d_pp').value,
    });

    /*function max_tglkirim(val) {
        $('#ddeliv').datepicker('destroy');
        $('#ddeliv').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: "dd-mm-yyyy",
            todayBtn: "linked",
            daysOfWeekDisabled: [0],
            startDate: document.getElementById('dop').value,
        });
    }
    $('#ddeliv').datepicker({
        autoclose: true,
        todayHighlight: true,
        format: "dd-mm-yyyy",
        todayBtn: "linked",
        daysOfWeekDisabled: [0],
        startDate: document.getElementById('dop').value,
    });*/

    $("#submit").click(function(event) {
        var valid = $("#cekinputan").valid();
        if (valid) {
            /* for (var i = 1; i <= $('#jml').val(); i++) {
                if ($('#vprice'+i).val()=='' || $('#vprice'+i).val()==null || $('#vprice'+i).val()==0) {
                    swal('Maaf :(','Harga tidak boleh kosong! Silahkan input manual atau input di master harga terlebih dahulu!','error');
                    return false;
                }   
            } */
            /*var s=0;
            var i = $('#jml').val();
            var harga = $('#vprice'+i).val();
            var textinputs = document.querySelectorAll('input[type=input]'); 
            var empty = [].filter.call( textinputs, function( el ) {
                return !el.checked
            });

            if(document.getElementById('dop').value==''){
                swal("Tanggal OP Masih Kosong!");
                return false;
            }else if(document.getElementById('importantstatus').value=='' || document.getElementById('importantstatus').value== null){
                swal("Importance Status Masih Kosong");
                return false;
            }else if(document.getElementById('vprice').value==''){
                swal("Harga masih kosong, Input Harga terlebih dahulu di Master Harga Per Supplier");
                return false;
            }else{
                return true
            }*/
        } else {
            return false;
        }
    });

    /*function validasi(){
        var s=0;
        var i = $('#jml').val();
        var harga = $('#vprice'+i).val();
        var textinputs = document.querySelectorAll('input[type=input]'); 
        var empty = [].filter.call( textinputs, function( el ) {
            return !el.checked
        });

        if(document.getElementById('dop').value==''){
            swal("Tanggal OP Masih Kosong!");
            return false;
        }else if(document.getElementById('importantstatus').value=='' || document.getElementById('importantstatus').value== null){
            swal("Importance Status Masih Kosong");
            return false;
        }else if(document.getElementById('vprice').value==''){
            swal("Harga masih kosong, Input Harga terlebih dahulu di Master Harga Per Supplier");
            return false;
        }else{
            return true
        }
    }*/
</script>