<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> &nbsp;<?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Tanggal Berlaku</label>
                        <label class="col-md-3">Kategori Barang</label>
                        <label class="col-md-3">Jenis Barang</label>
                        <label class="col-md-3">Kode Barang</label>
                        <div class="col-sm-3">
                            <input type="hidden" name="dfrom" id="dfrom" class="form-control input-sm" readonly value="<?= $dfrom; ?>">
                            <input type="text" name="dberlaku" id="dberlaku" class="form-control input-sm date" readonly value="<?= $data->d_berlaku; ?>">
                            <input type="hidden" name="dberlakusebelum" id="dberlakusebelum" class="form-control input-sm date" readonly value="<?= date("d-m-Y", strtotime($data->d_berlaku)); ?>">
                            <input type="hidden" name="dakhirsebelum" id="dakhirsebelum" class="form-control input-sm date" readonly value="<?= date('Y-m-d', strtotime('-1 days', strtotime($dberlaku))); ?>">
                            <input type="hidden" name="id" id="id" class="form-control input-sm" value="<?= $data->id; ?>">
                        </div>
                        <div class="col-sm-3">
                            <input type="hidden" name="ikodekelompok" name="ikodekelompok" class="form-control input-sm" required="" onkeyup="gede(this)" value="<?= $data->i_kode_kelompok; ?>" readonly>
                            <input type="text" name="enamakelompok" name="enamakelompok" class="form-control input-sm" required="" onkeyup="gede(this)" value="<?= $data->e_nama_kelompok; ?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <input type="hidden" name="ikodejenis" id="ikodejenis" class="form-control input-sm" required="" maxlength="30" onkeyup="gede(this)" value="<?= $data->i_type_code; ?>" readonly>
                            <input type="text" name="ekodejenis" id="ekodejenis" class="form-control input-sm" required="" onkeyup="gede(this)" value="<?= $data->e_type_name; ?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <input type="hidden" name="kodebrg" id="kodebrg" class="form-control input-sm" required="" value="<?= $data->id_product_base; ?>" readonly>
                            <input type="text" name="ikodebrg" id="ikodebrg" class="form-control input-sm" required="" value="<?= $data->i_product_base; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Nama Barang</label>
                        <label class="col-md-2">Warna</label>
                        <label class="col-md-2">Jenis Barang</label>
                        <label class="col-md-3">Kode Harga</label>
                        <label class="col-md-2">Harga</label>
                        <div class="col-sm-3">
                            <input type="text" name="namabrg" id="namabrg" class="form-control input-sm" required="" value="<?= $data->e_product_basename; ?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="ecolor" id="ecolor" class="form-control input-sm" required="" value="<?= $data->e_color_name; ?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <select name="id_jenis_barang_keluar" id="id_jenis_barang_keluar" class="form-control select2" onchange="clear_table();">
                                <?php if ($jenisbarang->num_rows() > 0) {
                                    foreach ($jenisbarang->result() as $row) : ?>
                                        <option value="<?= $row->id; ?>" <?php if ($data->id_jenis_barang_keluar == $row->id) { ?> selected <?php } ?>>
                                            <?= $row->e_jenis_name; ?>
                                        </option>
                                <?php endforeach;
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <input type="hidden" name="ikodeharga" id="ikodeharga" class="form-control input-sm" required="" value="<?= $data->id_harga_kode; ?>" readonly>
                            <input type="text" name="ekodeharga" id="ekodeharga" class="form-control input-sm" required="" value="<?= $data->e_harga; ?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="harga" id="harga" class="form-control input-sm" required="" value="<?= $data->v_price; ?>" onkeyup="angkahungkul(this);">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <button type="button" id="save" class="btn btn-success btn-block btn-sm mr-2" onclick="return dipales();"> <i class="fa fa-save mr-2"></i>Update</button>
                        </div>
                        <!-- <div class="col-sm-3">
                                <button type="button" id="send" class="btn btn-primary btn-block btn-sm mr-2"><i class="fa fa-paper-plane-o mr-2"></i>Send</button>
                            </div>
                            <div class="col-sm-3">
                                <button type="button" id="hapus" class="btn btn-danger btn-block btn-sm mr-2"><i class="fa fa-trash mr-2"></i>Delete</button>
                            </div> -->
                        <div class="col-sm-6">
                            <button type="button" class="btn btn-inverse btn-block btn-sm" onclick='show("<?= $folder; ?>/cform/","#main")'><i class="fa fa-arrow-circle-left mr-2"></i>Kembali</button>
                        </div>
                        <!-- <?php if ($data->i_status == '1' || $data->i_status == '3') { ?> -->
                        <!-- <?php } elseif ($data->i_status == '2') { ?>
                            <div class="col-sm-6">
                                <button type="button" id="cancel" class="btn btn-primary btn-block btn-sm mr-2"><i class="fa fa-refresh mr-2"></i>Cancel</button>
                            </div>
                            <div class="col-sm-6">
                                <button type="button" class="btn btn-inverse btn-block btn-sm" onclick='show("<?= $folder; ?>/cform/","#main")'><i class="fa fa-arrow-circle-left mr-2"></i>Kembali</button>
                            </div>
                        <?php } ?> -->
                    </div>
                    <div>
                        <span style="color: #8B0000"><b>Note : </b>Jika akan mengubah tanggal berlaku, maka tanggal berlaku tidak boleh sama dengan tanggal berlaku sebelumnya</span>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
<script>
    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        //$("select").attr("disabled", true);
        $("#save").attr("disabled", true);
    });

    /*----------  UPDATE STATUS DOKUMEN  ----------*/
    $('#send').click(function(event) {
        statuschange('<?= $folder; ?>', $('#id').val(), '2', '', '');
    });

    $('#cancel').click(function(event) {
        statuschange('<?= $folder; ?>', $('#id').val(), '1', '', '');
    });

    $('#hapus').click(function(event) {
        statuschange('<?= $folder; ?>', $('#id').val(), '5', '', '');
    });

    $(document).ready(function() {
        $('.select2').select2();
        showCalendar('.date');
    });

    $('#dberlaku').on('change', function() {
        swal("Tanggal berlaku telah diubah!");
    });

    function dipales() {
        var kodebrg = $('#kodebrg').val();
        var ikodeharga = $('#ikodeharga').val();
        var harga = $('#harga').val();
        var dberlaku = $('#dberlaku').val();
        var dberlakusebelum = $('#dberlakusebelum').val();
        var dakhirsebelum = $('#dakhirsebelum').val();
        var dfrom = $('#dfrom').val();
        var id = $('#id').val();
        var id_jenis_barang_keluar = $('#id_jenis_barang_keluar').val();

        swal({
            title: "Ubah Tanggal Berlaku ?",
            text: "Mengubah tanggal berlaku!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Ubah, data yang sudah ada!",
            cancelButtonText: "Simpan, sebagai data baru!",
            closeOnConfirm: false,
            closeOnCancel: false
        }, function(isConfirm) {
            if (isConfirm) {
                $.ajax({
                    type: "post",
                    data: {
                        'kodebrg': kodebrg,
                        'ikodeharga': ikodeharga,
                        'harga': harga,
                        'dberlaku': dberlaku,
                        'dakhirsebelum': dakhirsebelum,
                        'dfrom': dfrom,
                        'dberlakusebelum': dberlakusebelum,
                        'id': id,
                        'id_jenis_barang_keluar': id_jenis_barang_keluar,
                    },
                    url: '<?= base_url($folder . '/cform/ubahtanggalberlaku'); ?>',
                    dataType: "json",
                    success: function(data) {
                        $("input").attr("disabled", true);
                        $("select").attr("disabled", true);
                        $("#save").attr("disabled", true);
                        swal("Ubah!", "Data berhasil diubah :)", "success");
                        show('<?= $folder; ?>/cform/edit/<?= $id; ?>/<?= $kodebrg ?>/<?= $dberlaku ?>/<?= $dfrom ?>/', '#main');
                    },
                    error: function() {
                        swal("Maaf", "Data gagal diubah :(", "error");
                    }
                });
            } else {
                $.ajax({
                    type: "post",
                    data: {
                        'kodebrg': kodebrg,
                        'ikodeharga': ikodeharga,
                        'harga': harga,
                        'dberlaku': dberlaku,
                        'dakhirsebelum': dakhirsebelum,
                        'dfrom': dfrom,
                        'dberlakusebelum': dberlakusebelum,
                        'id': id,
                        'id_jenis_barang_keluar': id_jenis_barang_keluar,
                    },
                    url: '<?= base_url($folder . '/cform/inserttanggalberlaku'); ?>',
                    dataType: "json",
                    success: function(data) {
                        $("input").attr("disabled", true);
                        $("select").attr("disabled", true);
                        $("#save").attr("disabled", true);
                        swal("Ubah!", "Data berhasil disimpan :)", "success");
                        show('<?= $folder; ?>/cform/edit/<?= $id; ?>/<?= $kodebrg ?>/<?= $dberlaku ?>/<?= $dfrom ?>/', '#main');
                    },
                    error: function() {
                        swal("Maaf", "Data gagal disimpan :(", "error");
                    }
                });
            }
        });
    }
</script>