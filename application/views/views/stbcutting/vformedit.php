<form id="cekinputan">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <i class="fa fa-lg fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
                </div>
                <div class="panel-body table-responsive">
                    <div id="pesan"></div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-3">Bagian Pembuat</label>
                            <label class="col-md-3">Nomor Dokumen</label>
                            <label class="col-md-3">Tanggal Dokumen</label>
                            <label class="col-md-3">Tujuan</label>
                            <div class="col-sm-3">
                                <select name="i_bagian" id="i_bagian" required="" class="form-control select2">
                                    <?php if ($bagian) {
                                        foreach ($bagian->result() as $key) { ?>
                                            <option value="<?= trim($key->i_bagian); ?>" <?php if ($key->i_bagian == $data->i_bagian) { ?> selected <?php } ?>><?= $key->e_bagian_name; ?></option>
                                    <?php }
                                    } ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="text" name="i_document" required="" id="i_stb_sj" readonly="" autocomplete="off" onkeyup="gede(this);" maxlength="15" class="form-control input-sm" value="<?= $data->i_document; ?>">
                                    <input type="hidden" id="id" name="id" value="<?= $data->id; ?>">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <input type="text" name="d_document" required="" id="d_document" class="form-control input-sm date" value="<?= formatdmY($data->d_document); ?>" readonly>
                            </div>
                            <div class="col-sm-3">
                                <input type="hidden" name="id_company_tujuan" id="id_company_tujuan" value="<?= $data->id_company_tujuan; ?>">
                                <input type="text" class="form-control input-sm" value="<?= $data->name; ?>" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-md-3">Jenis Barang Keluar</label>
                            <label class="col-md-9">Keterangan</label>
                            <div class="col-md-3">
                                <select name="id_jenis_barang_keluar" id="id_jenis_barang_keluar" required="" class="form-control select2">
                                    <?php if ($jenis) {
                                        foreach ($jenis->result() as $key) { ?>
                                            <option value="<?= $key->id; ?>" <?php if ($key->id == $data->id_jenis_barang_keluar) { ?> selected <?php } ?>><?= $key->e_jenis_name; ?></option>
                                    <?php }
                                    } ?>
                                </select>
                            </div>
                            <div class="col-md-9">
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <textarea type="text" id="e_remark" name="e_remark" maxlength="250" class="form-control input-sm" placeholder="Isi keterangan jika ada!"><?= $data->e_remark; ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <?php if ($data->i_status == '1' || $data->i_status == '3' || $data->i_status == '7') { ?>
                                    <button type="button" id="submit" class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save mr-2 fa-lg"></i>Update</button>
                                <?php } ?>
                                <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;"><i class="fa fa-arrow-circle-left mr-2 fa-lg"></i>Kembali</button>
                                <?php if ($data->i_status == '1') { ?>
                                    <button type="button" id="send" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-paper-plane-o mr-2 fa-lg"></i>Send</button>
                                    <button type="button" id="hapus" class="btn btn-danger btn-rounded btn-sm"><i class="fa fa-trash mr-2 fa-lg"></i>Delete</button>
                                <?php } elseif ($data->i_status == '2') { ?>
                                    <button type="button" id="cancel" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-refresh mr-2 fa-lg"></i>Cancel</button>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="white-box" id="detail">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-2">
                    <h3 class="box-title m-b-0 ml-1">Detail Material</h3>
                </div>
            </div>
            <div class="table-responsive">
                <table id="sitabledata" class="table middle color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center" width="3%;">No</th>
                            <th>Kode</th>
                            <th>Nama Material</th>
                            <th>Bagian Panel</th>
                            <th>Kode Panel</th>
                            <th class="text-right">Qty<br>Penyusun</th>
                            <th class="text-right">Jml<br>Gelar</th>
                            <th class="text-right" hidden>STB Cutting<br>Hasil Baku</th>
                            <th class="text-right">Qty Panel<br>PCs</th>
                            <th class="text-right" hidden>Selisih<br>PCs</th>
                            <th class="text-right" width="10%"><span class="mr-4">Qty Kirim&nbsp;</span></th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <?php $i = 0;
                        if ($data_detail->num_rows() > 0) {
                            foreach ($data_detail->result() as $key) {
                                $i++; ?>
                                <tr>
                                    <td class="text-center middle"><b><?= $i; ?></b></td>
                                    <td class="middle"><?= $key->i_material; ?></td>
                                    <td class="middle"><?= $key->e_material_name; ?></td>
                                    <td class="middle">
                                        <input type="hidden" value="<?= $key->id; ?>" name="id_item<?= $i; ?>">
                                        <input type="hidden" value="<?= $key->id_schedule_item; ?>" name="id_schedule_item<?= $i; ?>">
                                        <?= $key->bagian; ?>
                                    </td>
                                    <td class="middle">
                                        <input type="hidden" value="<?= $key->id_panel_item; ?>" name="id_panel_item<?= $i; ?>">
                                        <?= $key->i_panel; ?>
                                    </td>
                                    <td class="middle text-right">
                                        <input type="hidden" value="<?= $key->n_quantity_penyusun; ?>" name="n_qty_penyusun<?= $i; ?>" id="n_qty_penyusun<?= $i; ?>; ?>">
                                        <?= $key->n_quantity_penyusun; ?>
                                    </td>
                                    <td class="middle text-right">
                                        <input type="hidden" value="<?= $key->n_jumlah_gelar; ?>" name="n_jumlah_gelar<?= $i; ?>" id="n_jumlah_gelar<?= $i; ?>">
                                        <?= $key->n_jumlah_gelar; ?>
                                    </td>
                                    <td class="middle text-right" hidden>
                                        <input type="hidden" value="<?= $key->n_quantity_stb_hasil; ?>" name="n_quantity_stb_cutting<?= $i; ?>">
                                        <?= number_format($key->n_quantity_stb_hasil, 2); ?>
                                    </td>
                                    <td class="middle text-right">
                                        <input type="hidden" value="<?= $key->n_quantity_panel; ?>" name="n_quantity_panel<?= $i; ?>">
                                        <?= $key->n_quantity_panel; ?>
                                    </td>
                                    <td class="middle text-right" hidden>
                                        <input type="hidden" value="<?= $key->n_quantity_selisih; ?>" name="n_quantity_selisih<?= $i; ?>">
                                        <?= $key->n_quantity_selisih; ?>
                                    </td>
                                    <td>
                                        <input value="<?= $key->n_quantity; ?>" type="number" id="n_quantity<?= $i; ?>" class="form-control text-right input-sm inputqty" autocomplete="off" name="n_quantity<?= $i; ?>" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}">
                                    </td>
                                    <td><input value="<?= $key->e_remark; ?>" type="text" class="form-control input-sm" name="e_remark_item<?= $i; ?>" id="e_remark_item<?= $i; ?>" placeholder="Isi keterangan jika ada!" /></td>
                                </tr>
                        <?php }
                        } ?>
                    </tbody>
                    <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                </table>
            </div>
        </div>
    </div>
</form>
<script src="<?= base_url(); ?>assets/js/jquery.validate.min.js"></script>
<script>
    /*----------  LOAD SAAT DOKUMEN READY  ----------*/
    $(document).ready(function() {
        fixedtable($('#sitabledata'));
        /*----------  Load Form Validation  ----------*/
        $('#cekinputan').validate({
            errorClass: "my-error-class",
            validClass: "my-valid-class"
        });
        $('.select2').select2({
            width: "100%",
        });
        /*Tidak boleh lebih dari hari ini*/
        showCalendar('.date', null, 0);
        showCalendar2('.tgl', 0, 999);


        /*----------  UPDATE STATUS DOKUMEN KE WAIT APPROVE ----------*/

        $('#send').click(function(event) {
            statuschange('<?= $folder; ?>', $('#id').val(), '2', '<?= $dfrom . "','" . $dto; ?>');
        });

        $('#cancel').click(function(event) {
            statuschange('<?= $folder; ?>', $('#id').val(), '1', '<?= $dfrom . "','" . $dto; ?>');
        });

        $('#hapus').click(function(event) {
            statuschange('<?= $folder; ?>', $('#id').val(), '5', '<?= $dfrom . "','" . $dto; ?>');
        });

        /*----------  VALIDASI SAAT MENEKAN TOMBOL SIMPAN  ----------*/
        $("#submit").click(function(event) {
            var valid = $("#cekinputan").valid();
            if (valid) {
                ada = false;
                /* if ($('#jml').val() == 0) {
                    swal('Isi item minimal 1!');
                    return false;
                } else { */
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
                                swal("Sukses!", "No Dokumen : " + data.kode + ", Berhasil Diupdate :)", "success");
                                $("input").attr("disabled", true);
                                $("select").attr("disabled", true);
                                $("#submit").attr("disabled", true);
                                $("#addrow").attr("disabled", true);
                                $("#send").attr("disabled", false);
                            } else if (data.sukses == 'ada') {
                                swal("Maaf :(", "Data tersebut sudah ada :(", "error");
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
            }
            return false;
        });
    });
</script>