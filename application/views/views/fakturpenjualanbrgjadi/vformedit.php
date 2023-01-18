<style type="text/css">
    .pudding {
        padding-left: 3px;
        padding-right: 3px;
        font-size: 14px;
        background-color: #e1f1e4;
    }
</style>
<!-- <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?> -->
<form>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?= $title_list; ?></a>
                </div>
                <div class="panel-body">
                    <div id="pesan"></div>
                    <div class="col-md-12">
                        <?php if ($head) {
                        ?>
                            <div class="form-group row">
                                <label class="col-md-3">Bagian Pembuat</label>
                                <label class="col-md-3">Nomor Dokumen</label>
                                <label class="col-md-2">Tanggal Dokumen</label>
                                <label class="col-md-4">Customer</label>
                                <div class="col-sm-3">
                                    <select name="ibagian" id="ibagian" class="form-control select2" required="">
                                        <?php if ($bagian) {
                                            foreach ($bagian as $row) : ?>
                                                <option value="<?= $row->i_bagian; ?>" <?php if ($row->i_bagian == $head->i_bagian) { ?> selected <?php } ?>>
                                                    <?= $row->e_bagian_name; ?>
                                                </option>
                                        <?php endforeach;
                                        } ?>
                                    </select>
                                    <input type="hidden" name="ibagianold" id="ibagianold" value="<?= $head->i_bagian; ?>">
                                </div>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <input type="hidden" name="id" id="id" class="form-control" value="<?= $head->id; ?>" readonly="">
                                        <input type="hidden" name="idocumentold" id="ifakturold" value="<?= $head->i_document; ?>">
                                        <input type="text" name="idocument" id="ifaktur" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="FP-2010-000001" maxlength="20" class="form-control input-sm" value="<?= $head->i_document; ?>" aria-label="Text input with dropdown button">
                                        <span class="input-group-addon">
                                            <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                        </span>
                                    </div>
                                    <span class="notekode">Format : (<?= $number; ?>)</span><br>
                                    <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" name="ddocument" id="ddocument" class="form-control input-sm" value="<?= $head->d_document; ?>" readonly="">
                                </div>
                                <div class="col-sm-4">
                                    <!--    <input type="hidden" name="isjaja" id="isjaja" class="form-control" value="" readonly> -->
                                    <input type="hidden" name="id_customer" class="form-control" value="<?= $head->id_customer; ?>" readonly>
                                    <input type="text" name="e_customer_name" class="form-control input-sm" value="<?= $head->e_customer_name; ?>" readonly>
                                    <input type="hidden" name="f_pkp" id="fsupplierpkp" class="form-control" value="<?= $head->f_pkp; ?>" readonly>
                                    <input type="hidden" name="n_customer_toplength" id="n_customer_toplength" class="form-control" value="<?= $head->n_customer_toplength; ?>" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <!-- <label class="col-md-2">Nomor Pajak</label>
                    <label class="col-md-2">Tanggal Pajak</label> -->
                                <label class="col-md-2">Tgl Terima Faktur</label>
                                <label class="col-md-2">Tgl Jatuh Tempo</label>
                                <label class="col-md-8">Keterangan</label>
                                <div class="col-sm-2">
                                    <input type="hidden" name="ipajak" id="ipajak" class="form-control" value="<?= $head->i_pajak; ?>">
                                    <input type="hidden" name="dpajak" id="dpajak" class="form-control date" value="<?= $head->d_pajak; ?>">
                                    <input type="text" name="dreceivefaktur" id="dreceivefaktur" class="form-control input-sm date" value="<?= $head->d_terima_faktur; ?>" readonly="" onchange="return tgl_jatuhtempo(this.value);">
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" name="djatuhtempo" id="djatuhtempo" class="form-control input-sm" value="<?= $head->d_jatuh_tempo; ?>" readonly>
                                </div>
                                <div class="col-sm-8">
                                    <textarea class="form-control input-sm" name="eremark" placeholder="Isi keterangan jika ada!"><?= $head->e_remark; ?></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <?php if ($head->i_status == '1' || $head->i_status == '3' || $head->i_status == '7') { ?>
                                        <button type="button" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return konfirm();"><i class="fa fa-lg fa-save"></i>&nbsp;&nbsp;Update</button>&nbsp;
                                    <?php } ?>
                                    <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;"><i class="fa fa-lg fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                                    <?php if ($head->i_status == '1') { ?>
                                        <button type="button" id="send" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-lg fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>&nbsp;
                                        <button type="button" id="hapus" class="btn btn-danger btn-rounded btn-sm"><i class="fa fa-lg fa-trash"></i>&nbsp;&nbsp;Delete</button>&nbsp;
                                    <?php } elseif ($head->i_status == '2') { ?>
                                        <button type="button" id="cancel" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-lg fa-refresh"></i>&nbsp;&nbsp;Cancel</button>&nbsp;
                                    <?php } ?>
                                </div>
                            </div>
                    </div>
                    <div class="col-md-12">
                        <span class="notekode"><b>Note : </b></span><br>
                        <span class="notekode">* Harga barang jadi yang digunakan adalah harga exclude.</span><br>
                        <span class="notekode">* Harga sesuai dengan harga ketika membuat sj!</span><br>
                        <span class="notekode">* Tanggal jatuh tempo adalah tanggal nota + TOP!</span><br>
                        <span class="notekode">* Jika sudah di terima maka tanggal jatuh tempo adalah tanggal terima + TOP!</span>ss
                    </div>
                    <div class="col-md-12">
                    <?php
                        } else {
                            $read = "disabled";
                            echo "<table class=\"table table-striped bottom\" style=\"width:100%;\"><tr><td colspan=\"6\" style=\"text-align:center;\">Maaf Tidak Ada Data!</td></tr></table>";
                        } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $i = 0;
    $group = "";
    $no = 0;
    if ($detail) { ?>
        <div class="white-box" id="detail">
            <div class="col-sm-6">
                <h3 class="box-title m-b-0">Detail Barang</h3>
            </div>
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table id="tabledatay" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                        <thead>
                            <tr>
                                <th class="text-center" width="3%">No</th>
                                <th class="text-center" width="30%;">Barang</th>
                                <th class="text-center">Qty</th>
                                <th class="text-center">Harga</th>
                                <th class="text-center" width="15%;">Disc 123 (%)</th>
                                <th class="text-center">Disc (Rp.)</th>
                                <th class="text-center">Total</th>
                                <th class="text-center">Keterangan</th>
                                <!--  <th class="text-center" width="3%">Act</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($detail as $key) {
                                $i++;
                                $no++;
                                $total = $key->v_price * $key->n_quantity;
                                if ($group == "") { ?>
                                    <tr class="pudding">
                                        <td colspan="8">Nomor SJ : <b><?= $key->i_document; ?></b> &nbsp;&nbsp;-&nbsp;&nbsp; Tanggal SJ : <b><?= $key->d_document; ?></b></td>
                                    </tr>
                                    <?php } else {
                                    if ($group != $key->id_document) { ?>
                                        <tr class="pudding">
                                            <td colspan="8">Nomor SJ : <b><?= $key->i_document; ?></b> &nbsp;&nbsp;-&nbsp;&nbsp; Tanggal SJ : <b><?= $key->d_document; ?></b></td>
                                        </tr>
                                <?php $no = 1;
                                    }
                                }
                                $group = $key->id_document; ?>
                                <tr>
                                    <td class="text-center">
                                        <spanx id="snum<?= $i; ?>"><?= $no; ?></spanx>
                                    </td>
                                    <td><input type="text" readonly class="form-control input-sm" name="i_product_base<?= $i; ?>" id="i_product_base<?= $i; ?>" value="<?= $key->i_product_base . ' - ' . $key->e_product_basename; ?>" />
                                        <input type="hidden" readonly class="form-control input-sm" name="e_product<?= $i; ?>" id="e_product<?= $i; ?>" value="<?= $key->e_product_basename; ?>" />
                                        <input type="hidden" readonly class="form-control input-sm" name="id_document<?= $i; ?>" id="id_document<?= $i; ?>" value="<?= $key->id_document; ?>" />
                                        <input type="hidden" readonly class="form-control input-sm" name="id_product<?= $i; ?>" id="id_product<?= $i; ?>" value="<?= $key->id_product; ?>" />
                                        <input type="hidden" readonly class="form-control input-sm" name="id_document_refferensi<?= $i; ?>" id="id_document_refferensi<?= $i; ?>" value="<?= $key->id_document_refferensi; ?>" />
                                    </td>
                                    <td><input type="text" id="nquantity<?= $i; ?>" class="form-control text-right input-sm inputitem" autocomplete="off" name="nquantity<?= $i; ?>" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="<?= $key->n_quantity; ?>" onkeyup="angkahungkul(this); hitungtotal();" readonly> <input type="hidden" readonly class="form-control input-sm text-right" name="nquantity_sj<?= $i; ?>" id="nquantity_sj<?= $i; ?>" value="<?= $key->n_quantity_sj; ?>" /></td>
                                    <td><input type="text" readonly class="form-control input-sm text-right" name="vharga<?= $i; ?>" id="vharga<?= $i; ?>" value="<?= number_format($key->v_price); ?>" /></td>
                                    <td>
                                        <div class="row">
                                            <div class="col-sm-4 pudding">
                                                <input type="text" readonly class="form-control input-sm text-right" placeholder="%1" name="ndisc1<?= $i; ?>" id="ndisc1<?= $i; ?>" value="<?= $key->n_diskon1; ?>" />
                                                <input type="hidden" readonly class="form-control input-sm text-right" name="vdisc1<?= $i; ?>" id="vdisc1<?= $i; ?>" value="<?= $key->v_diskon1; ?>" />
                                            </div>
                                            <div class="col-sm-4 pudding">
                                                <input type="text" readonly class="form-control input-sm text-right" placeholder="%2" name="ndisc2<?= $i; ?>" id="ndisc2<?= $i; ?>" value="<?= $key->n_diskon2; ?>" />
                                                <input type="hidden" readonly class="form-control input-sm text-right" name="vdisc2<?= $i; ?>" id="vdisc2<?= $i; ?>" value="<?= $key->v_diskon2; ?>" />
                                            </div>
                                            <div class="col-sm-4 pudding">
                                                <input type="text" readonly class="form-control input-sm text-right" placeholder="%3" name="ndisc3<?= $i; ?>" id="ndisc3<?= $i; ?>" value="<?= $key->n_diskon3; ?>" />
                                                <input type="hidden" readonly class="form-control input-sm text-right" name="vdisc3<?= $i; ?>" id="vdisc3<?= $i; ?>" value="<?= $key->v_diskon3; ?>" />
                                            </div>
                                        </div>
                                    </td>
                                    <td><input type="text" class="form-control input-sm text-right" name="vdiscount<?= $i; ?>" id="vdiscount<?= $i; ?>" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="<?= number_format($key->v_diskon_tambahan); ?>" onkeyup="angkahungkul(this); hitungtotal(); reformat(this);" readonly /></td>
                                    <td><input type="text" readonly class="form-control input-sm text-right" name="vtotal<?= $i; ?>" id="vtotal<?= $i; ?>" value="<?= number_format($total); ?>" /><input type="hidden" readonly class="form-control input-sm text-right" name="vtotaldiskon<?= $i; ?>" id="vtotaldiskon<?= $i; ?>" value="<?= $key->v_diskon_total; ?>" /></td>
                                    <td><input type="text" class="form-control input-sm" name="eremark<?= $i; ?>" id="eremark<?= $i; ?>" placeholder="Jika Ada!" value="<?= $key->e_remark; ?>"></td>
                                    <!--   <td class="text-center"><input type="checkbox" id="cek<?php echo $i; ?>" name="cek<?php echo $i; ?>" onclick="hitungnilai(<?php echo $i ?>)"></button></td> -->
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>

                            <tr>
                                <td class="text-right" colspan="6">Total :</td>
                                <td><input type="text" id="nkotor" name="nkotor" class="form-control input-sm text-right" value="<?= number_format($head->v_kotor); ?>" readonly></td>
                                <td colspan="2"></td>
                            </tr>
                            <tr>
                                <td class="text-right" colspan="6">Diskon :</td>
                                <td><input type="text" id="ndiskontotal" name="ndiskontotal" class="form-control input-sm text-right" readonly value="<?= number_format($head->v_diskon); ?>"></td>
                                <td colspan="2"></td>
                            </tr>
                            <tr>
                                <td class="text-right" colspan="6">DPP :</td>
                                <td><input type="text" id="vdpp" name="vdpp" class="form-control input-sm text-right" value="<?= number_format($head->v_dpp); ?>" readonly></td>
                                <td colspan="2"></td>
                            </tr>
                            <tr>
                                <td class="text-right" colspan="6">PPN (10%) :</td>
                                <td><input type="text" id="vppn" name="vppn" class="form-control input-sm text-right" value="<?= number_format($head->v_ppn); ?>" readonly></td>
                                <td colspan="2"></td>
                            </tr>
                            <tr>
                                <td class="text-right" colspan="6">Grand Total :</td>
                                <td><input type="text" id="nbersih" name="nbersih" class="form-control input-sm text-right" value="<?= number_format($head->v_bersih); ?>" readonly></td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
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
<script>
    $(document).ready(function() {
        $('#ifaktur').mask('SS-0000-000000S');
        $(".select2").select2();
        showCalendar('.date');
        //hitung();
        // tgl_jatuhtempo($('#ddocument').val());
        // number();
    });

    /*----------  RUBAH NO DOKUMEN (GANTI TANGGAL & BAGIAN)  ----------*/
    $('#ibagian').change(function(event) {
        number();
        //$("#tabledatay > tbody").remove();
        //$("#jml").val(0);
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
                $('#ifaktur').val(data);
            },
            error: function() {
                swal('Error :)');
            }
        });
    }

    /*----------  Jatuh Tempo Berdasarkan Tanggal Nota  ----------*/
    function tgl_jatuhtempo() {
        var ddocument = $('#ddocument').val();
        var n_customer_toplength = $('#n_customer_toplength').val();
        var a = parseInt(n_customer_toplength);
        var arr = ddocument.split("-");
        var d = arr[0];
        var m = arr[1];
        var y = arr[2];
        var x = y + " " + m + " " + d;
        var date = new Date(x);

        date.setDate(date.getDate() + a);
        var year = date.getFullYear();
        var month = date.getMonth();
        var ndate = date.getDate();
        var day = new Date(year, month, ndate);
        var year1 = day.getFullYear();
        var month1 = day.getMonth() + 1;
        var mm = ("0" + month1).slice(-2);
        var day1 = ("0" + day.getDate()).slice(-2);
        dnew = day1 + "-" + mm + "-" + year1;
        $('#djatuhtempo').val(dnew);
    }

    /*----------  Jatuh Tempo Berdasarkan Tanggal Nota  ----------*/
    function tgl_jatuhtempo(ddocument) {
        var n_customer_toplength = $('#n_customer_toplength').val();
        var a = parseInt(n_customer_toplength);
        var arr = ddocument.split("-");
        var d = arr[0];
        var m = arr[1];
        var y = arr[2];
        var x = y + " " + m + " " + d;
        var date = new Date(x);

        date.setDate(date.getDate() + a);
        var year = date.getFullYear();
        var month = date.getMonth();
        var ndate = date.getDate();
        var day = new Date(year, month, ndate);
        var year1 = day.getFullYear();
        var month1 = day.getMonth() + 1;
        var mm = ("0" + month1).slice(-2);
        var day1 = ("0" + day.getDate()).slice(-2);
        dnew = day1 + "-" + mm + "-" + year1;
        $('#djatuhtempo').val(dnew);
    }

    /*----------  HITUNG NILAI  ----------*/
    function hitungtotal() {
        var total = 0;
        var totaldis = 0;
        var vjumlah = 0;
        var dpp = 0;
        var ppn = 0;
        var grand = 0;
        for (var i = 1; i <= $('#jml').val(); i++) {
            if (typeof $('#id_product' + i).val() != 'undefined') {
                if (!isNaN(parseFloat($('#nquantity' + i).val()))) {
                    var qty = parseFloat($('#nquantity' + i).val());
                } else {
                    var qty = 0;
                }

                if (!isNaN(parseFloat($('#nquantity_sj' + i).val()))) {
                    var qty_sj = parseFloat($('#nquantity_sj' + i).val());
                } else {
                    var qty_sj = 0;
                }

                if (qty > qty_sj) {
                    swal('Jumlah Quantity tidak boleh melebihi Jumlah SJ : ' + qty_sj);
                    $('#nquantity' + i).val(qty_sj);
                    qty = qty_sj;
                    //break;
                }

                var jumlah = formatulang($('#vharga' + i).val()) * qty;
                var disc1 = formatulang($('#ndisc1' + i).val());
                var disc2 = formatulang($('#ndisc2' + i).val());
                var disc3 = formatulang($('#ndisc3' + i).val());
                if (!isNaN(parseFloat($('#vdiscount' + i).val()))) {
                    var disc4 = formatulang($('#vdiscount' + i).val());
                } else {
                    var disc4 = 0;
                }
                var ndisc1 = jumlah * (disc1 / 100);
                var ndisc2 = (jumlah - ndisc1) * (disc2 / 100);
                var ndisc3 = (jumlah - ndisc1 - ndisc2) * (disc3 / 100);

                var vtotaldis = (ndisc1 + ndisc2 + ndisc3 + parseFloat(disc4));

                var vtotal = jumlah - vtotaldis;

                $('#vdisc1' + i).val(ndisc1);
                $('#vdisc2' + i).val(ndisc2);
                $('#vdisc3' + i).val(ndisc3);
                $('#vtotaldiskon' + i).val(formatcemua(vtotaldis));
                $('#vtotal' + i).val(formatcemua(jumlah));
                $('#vtotalnet' + i).val(formatcemua(vtotal));
                totaldis += vtotaldis;
                vjumlah += jumlah;
                total += vtotal;
            }
        }
        $('#nkotor').val(formatcemua(vjumlah));
        $('#ndiskontotal').val(formatcemua(totaldis));

        dpp = vjumlah - totaldis;
        ppn = dpp * 0.1;
        grand = dpp + ppn;

        $('#nbersih').val(formatcemua(grand));
        $('#vdpp').val(formatcemua(dpp));
        $('#vppn').val(formatcemua(ppn));
    }

    /*----------  VALIDASI UPDATE DATA  ----------*/
    $("#submit").click(function(event) {
        ada = false;
        if (($('#ibagian').val() != '' || $('#ibagian').val() != null) && ($('#ddocument').val() != '' || $('#ddocument').val() != null)) {
            if ($('#jml').val() == 0) {
                swal('Isi item minimal 1!');
                return false;
            } else {
                $("#tabledatay tbody tr").each(function() {
                    // $(this).find("td select").each(function() {
                    //     if ($(this).val()=='' || $(this).val()==null) {
                    //         swal('Barang tidak boleh kosong!');
                    //         ada = true;
                    //     }
                    // });
                    $(this).find("td .inputitem").each(function() {
                        if ($(this).val() == '' || $(this).val() == null || $(this).val() == 0) {
                            swal('Quantity Tidak Boleh Kosong Atau 0!');
                            ada = true;
                        }
                    });
                });
                if (!ada) {
                    swal({
                        title: "Update Data Ini?",
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
                            url: '<?= base_url($folder . '/cform/update/'); ?>',
                            dataType: "json",
                            success: function(data) {
                                if (data.sukses == true) {
                                    $('#id').val(data.id);
                                    swal("Sukses!", "No Dokumen : " + data.kode + ", Berhasil Disimpan :)", "success");
                                    $("input").attr("disabled", true);
                                    $("select").attr("disabled", true);
                                    $("#submit").attr("disabled", true);
                                    $("#addrow").attr("disabled", true);
                                    $("#send").attr("hidden", false);
                                } else if (data.sukses == 'ada') {
                                    swal("Maaf :(", "No Dokumen : " + data.kode + ", Sudah Ada :(", "error");
                                } else {
                                    swal("Maaf :(", "No Dokumen : " + data.kode + ", Gagal Disimpan :(", "error");
                                }
                            },
                            error: function() {
                                swal("Maaf", "Data Gagal Diupdate :(", "error");
                            }
                        });
                    });
                } else {
                    return false;
                }
            }
        } else {
            swal('Data Header Masih Ada yang Kosong!');
            return false;
        }
    })

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
            $("#ifaktur").attr("readonly", false);
        } else {
            $("#ifaktur").attr("readonly", true);
            $("#ada").attr("hidden", true);
            number();
        }
    });

    /*----------  CEK NO DOKUMEN  ----------*/
    $("#ifaktur").keyup(function() {
        $.ajax({
            type: "post",
            data: {
                'kode': $(this).val(),
                'ibagian': $('#ibagian').val(),
            },
            url: '<?= base_url($folder . '/cform/cekkode'); ?>',
            dataType: "json",
            success: function(data) {
                if (data == 1 && ($('#ifaktur').val() != $('#ifakturold').val())) {
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
</script>