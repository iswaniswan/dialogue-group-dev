<style type="text/css">
    #tabledatay td {
        padding: 4px 4px !important;
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
                            <label class="col-md-3">Periode Forecast Produksi</label>
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
                                    <input type="hidden" name="idocumentold" id="ifcjahitold" value="<?= $data->i_document; ?>">
                                    <input type="text" name="idocument" required="" id="ifcjahit" readonly="" autocomplete="off" onkeyup="gede(this);" maxlength="25" class="form-control input-sm" value="<?= $data->i_document; ?>" aria-label="Text input with dropdown button">
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
    if ($datadetail->num_rows()>0) { ?>
        <div class="white-box" id="detail">
            <div class="col-sm-6">
                <h3 class="box-title m-b-0">Detail Item</h3>
            </div>
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table id="tabledatay" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                        <caption>Total Data = <?= $datadetail->num_rows();?></caption>
                        <thead>
                            <tr>
                                <th class="text-center" width="3%;">No</th>
                                <th width="7%;">Kode Barang</th>
                                <th width="20%;">Nama Barang</th>
                                <th width="7%;">Warna</th>
                                <!-- <th class="text-right" width="7%;">FC Produksi</th> -->
                                <th class="text-right" width="7%;">Stok Pengadaan</th>
                                <th class="text-right" width="5%;">FC Jahit <i class="fa fa-question-circle fa-lg ml-2" tabindex="0" data-trigger="focus" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" title="Dari fc produksi yang di budgetkan + Stock Pengadaan"></i></th>
                                <!-- <th class="text-right" width="5%;">FC<br>Internal</th>
                                <th class="text-right" width="5%;">FC<br>Eksternal</th>
                                <th class="text-right" width="5%;">FC<br>Ade</th> -->
                                <th width="13%;">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 0;
                            $no = 1;
                            $group = "";
                            foreach ($datadetail->result() as $key) { 
                                $subkategori = trim(str_replace(" ", "", $key->e_type_name));
                                if ($group == "") { ?>
                                    <tr class="table-active">
                                        <td class="text-center"><a href="#" class="toggler" data-icon-name="fa-eye-slash" data-prod-cat="<?= $subkategori; ?>"><i class="fa fa-lg fa-eye-slash text-success"></i></a></td>
                                        <td colspan="10"><?= $key->e_type_name; ?></td>
                                    </tr>
                                    <?php } else {
                                    if ($group != $key->e_type_name) { ?>
                                        <tr class="table-active">
                                            <td class="text-center"><a href="#" class="toggler" data-icon-name="fa-eye-slash" data-prod-cat="<?= $subkategori; ?>"><i class="fa fa-lg fa-eye-slash text-success"></i></a></td>
                                            <td colspan="10"><?= $key->e_type_name; ?></td>
                                        </tr>
                                <?php $no = 1;
                                    }
                                }
                                $group = $key->e_type_name;
                                ?>
                                <tr class="<?= $subkategori; ?>" style="display:none">
                                    <td class="text-center"><?= $no; ?></td>
                                    <td>
                                        <input type="hidden" id="idproduct<?= $i; ?>" name="idproduct<?= $i; ?>" value="<?= $key->id_product_wip; ?>">
                                        <input class="form-control input-sm" readonly type="text" id="iproduct<?= $i; ?>" name="iproduct<?= $i; ?>" value="<?= $key->i_product_wip; ?>">
                                    </td>
                                    <td>
                                        <input class="form-control input-sm" readonly type="text" id="e_product_name<?= $i; ?>" name="e_product_name<?= $i; ?>" value="<?= $key->e_product_name; ?>">
                                    </td>
                                    <td>
                                        <input readonly class="form-control input-sm" type="text" id="e_color_name<?= $i; ?>" name="e_color_name<?= $i; ?>" value="<?= $key->e_color_name; ?>">
                                    </td>
                                    <td hidden>
                                        <input class="form-control input-sm text-right" type="text" id="n_quantity<?= $i; ?>" name="n_quantity<?= $i; ?>" value="<?= $key->n_fc; ?>" readonly>
                                    </td>
                                    <td>
                                        <input class="form-control input-sm text-right" type="text" id="n_pengadaan<?= $i; ?>" name="n_pengadaan<?= $i; ?>" value="<?= $key->n_pengadaan; ?>" readonly>
                                    </td>
                                    <td>
                                        <input class="form-control input-sm text-right" type="text" readonly id="n_fcjahit<?= $i; ?>" name="n_fcjahit<?= $i; ?>" placeholder="0" onkeyup="angkahungkul(this); cekvalidasi(<?= $i; ?>)" value="<?= $key->n_quantity; ?>">
                                    </td>
                                    <td hidden>
                                        <input class="form-control input-sm text-right" type="text" id="n_fcjahit_internal<?= $i; ?>" name="n_fcjahit_internal<?= $i; ?>" placeholder="0" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" onkeyup="angkahungkul(this); cekvalidasi(<?= $i; ?>);" value="<?= $key->n_quantity_internal; ?>">
                                    </td>
                                    <td hidden>
                                        <input class="form-control input-sm text-right" type="text" id="n_fcjahit_eksternal<?= $i; ?>" name="n_fcjahit_eksternal<?= $i; ?>" placeholder="0" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" onkeyup="angkahungkul(this); cekvalidasi(<?= $i; ?>);" value="<?= $key->n_quantity_eksternal; ?>">
                                    </td>
                                    <td hidden>
                                        <input class="form-control input-sm text-right" type="text" id="n_fcjahit_ade<?= $i; ?>" name="n_fcjahit_ade<?= $i; ?>" placeholder="0" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" onkeyup="angkahungkul(this); cekvalidasi(<?= $i; ?>);" value="<?= $key->n_quantity_ade; ?>">
                                    </td>
                                    <td>
                                        <input class="form-control input-sm" type="text" id="e_remark<?= $i; ?>" name="e_remark<?= $i; ?>" value="<?= $key->e_remark; ?>" placeholder="Isi keterangan jika ada!">
                                    </td>
                                </tr>
                            <?php
                                $i++;
                                $no++;
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
    function cekvalidasi(i) {
        // return true;
        // n_quantity = $("#n_quantity"+i).val();
        // n_pengadaan     = $("#n_pengadaan"+i).val();
        n_fcjahit = parseFloat($("#n_fcjahit" + i).val());
        n_fcjahit_internal = parseFloat($("#n_fcjahit_internal" + i).val());
        n_fcjahit_eksternal = parseFloat($("#n_fcjahit_eksternal" + i).val());
        n_fcjahit_ade = parseFloat($("#n_fcjahit_ade" + i).val());
        n_fc = n_fcjahit_internal + n_fcjahit_eksternal + n_fcjahit_ade;
        if (n_fc > n_fcjahit) {
            swal("Maaf forcast tidak boleh lebih dari forecast total !");
            $("#n_fcjahit_internal" + i).val(0);
            $("#n_fcjahit_eksternal" + i).val(0);
            $("#n_fcjahit_ade" + i).val(0);
        }


        // //alert("cek");
        // if(parseFloat(nquantity)>parseFloat(nsisa)){
        //     swal('Quantity Terima Tidak Boleh Lebih Dari Quantity Kirim');
        //     $("#npemenuhan"+i).val(nsisa);
        // }
        // if(parseFloat(nquantity) == '0' || parseFloat(nquantity) == '' || parseFloat(nquantity) == null){
        //     swal('Quantity Terima Tidak Boleh Kosong atau 0');
        //     $("#npemenuhan"+i).val(nsisa);
        // }
    }

    /*----------  LOAD SAAT DOKUMEN DIBUKA  ----------*/
    $(document).ready(function() {
        hetang();
        popover();
        fixedtable($('#tabledatay'));
        /*----------  Load Form Validation  ----------*/
        $('#cekinputan').validate({
            errorClass: "my-error-class",
            validClass: "my-valid-class"
        });

        // $('#ifcjahit').mask('SS-0000-000000S');
        $('.select2').select2();
        /*----------  Tanggal tidak boleh kurang dari hari ini!  ----------*/
        showCalendar('.date', 0);

        $(".toggler").click(function(e) {
            e.preventDefault();
            $('.' + $(this).attr('data-prod-cat')).toggle();
            // $(this).addClass('active');

            //Remove the icon class
            if ($(this).find('i').hasClass('fa-eye')) {
                //then change back to the original one
                $(this).find('i').removeClass('fa-eye').addClass($(this).data('icon-name'));
            } else {
                //Remove the cross from all other icons
                $('.faq-links').each(function() {
                    if ($(this).find('i').hasClass('fa-eye')) {
                        $(this).find('i').removeClass('fa-eye').addClass($(this).data('icon-name'));
                    }
                });

                $(this).find('i').addClass('fa-eye').removeClass($(this).data('icon-name'));
            }
        });
    });

    /*----------  RUBAH NO DOKUMEN (GANTI TANGGAL & BAGIAN)  ----------*/
    $('#ibagian, #ddocument').change(function(event) {
        number();
    });

    /*----------  RUNNING NUMBER DOKUMEN  ----------*/
    function number() {
        if (($('#ibagian').val() == $('#ibagianold').val())) {
            $('#ifcjahit').val($('#ifcjahitold').val());
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
                    $('#ifcjahit').val(data);
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
            $("#ifcjahit").attr("readonly", false);
        } else {
            $("#ifcjahit").attr("readonly", true);
            $("#ada").attr("hidden", true);
            number();
        }
    });

    /*----------  CEK NO DOKUMEN  ----------*/
    $("#ifcjahit").keyup(function() {
        $.ajax({
            type: "post",
            data: {
                'kode': $(this).val(),
                'ibagian': $('#ibagian').val(),
            },
            url: '<?= base_url($folder . '/cform/cekkode'); ?>',
            dataType: "json",
            success: function(data) {
                if (data == 1 && ($('#ifcjahit').val() != $('#ifcjahitold').val())) {
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