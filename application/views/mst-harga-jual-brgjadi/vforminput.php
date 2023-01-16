<?php

$data = $proses->row();
?>
<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-6">Kategori Barang</label>
                        <label class="col-md-6">Jenis Barang</label>
                        <div class="col-sm-6">
                            <input type="hidden" name="ikodekelompok" id="ikodekelompok" class="form-control input-sm" required="" onkeyup="gede(this)" value="<?php echo $data->i_kode_kelompok; ?>" readonly>
                            <input type="text" name="ekodekelompok" id="ekodekelompok" class="form-control input-sm" required="" onkeyup="gede(this)" value="<?php echo $data->i_kode_kelompok . " - " . $data->e_nama_kelompok; ?>" readonly>
                        </div>
                        <div class="col-sm-6">
                            <input type="hidden" name="ikodejenis" id="ikodejenis" class="form-control input-sm" required="" onkeyup="gede(this)" value="<?= $data->i_type_code; ?>" readonly>
                            <input type="text" name="ekodejenis" id="ekodejenis" class="form-control input-sm" required="" onkeyup="gede(this)" value="<?= $data->i_type_code . "-" . $data->e_type_name; ?>" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <button type="submit" id="submit" class="btn btn-success btn-block btn-sm" onclick="return validasi();"> <i class="fa fa-save mr-2"></i>Simpan</button>
                        </div>
                        <div class="col-sm-6">
                            <button type="button" class="btn btn-inverse btn-block btn-sm" onclick='show("<?= $folder; ?>/cform/tambah","#main")'><i class="fa fa-arrow-circle-left mr-2"></i>Kembali</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="white-box" id="detail">
    <div class="col-sm-12">
        <div class="table-responsive">
            <div class="form-group">
                <span style="color: #8B0000"><b>Note : </b> Harap mengisi harga terlebih dahulu</span>
            </div>
            <table id="myTable" class="table color-table inverse-table table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Warna</th>
                        <th>Grade Barang</th>
                        <th>Kode Harga</th>
                        <th class="text-right">Harga</th>
                        <th>Tgl Berlaku</th>
                        <th class="text-center">Act</th>
                    </tr>
                </thead>
                <tbody>
                    <? $i = 0;
                    foreach ($proses->result() as $row) {
                        $i++; ?>
                        <tr>
                            <td width="3%" style="text-align: center;"><?= $i; ?>
                                <input type="hidden" class="form-control input-sm" readonly id="baris<?= $i; ?>" name="baris<?= $i; ?>" value="<?= $i; ?>">
                            </td>
                            <td class="col-sm-1">
                                <input type="hidden" id="kodebrg<?= $i; ?>" class="form-control input-sm" name="kodebrg<?= $i; ?>" value="<?= $row->id_product; ?>" readonly>
                                <input type="text" id="ikodebrg<?= $i; ?>" class="form-control input-sm" name="ikodebrg<?= $i; ?>" value="<?= $row->i_product_base; ?>" readonly>
                            </td>
                            <td class="col-sm-3">
                                <input type="text" id="namabrg<?= $i; ?>" class="form-control input-sm" name="namabrg<?= $i; ?>" value="<?= $row->e_product_basename; ?>" readonly>
                            </td>
                            <td class="col-sm-1">
                                <input type="text" id="ecolor<?= $i; ?>" class="form-control input-sm" name="ecolor<?= $i; ?>" value="<?= $row->e_color_name; ?>" readonly>
                            </td>
                            <td class="col-sm-1">
                                <select class="form-control select2" name="id_jenis_barang_keluar<?= $i; ?>" required id="id_jenis_barang_keluar<?= $i; ?>">
                                    <?php if ($grade->num_rows() > 0) {
                                        foreach ($grade->result() as $key) { ?>
                                            <option value="<?= $key->id; ?>"><?= $key->e_jenis_name; ?></option>
                                    <?php }
                                    } ?>
                                </select>
                            </td>
                            <td class="col-sm-1">
                                <input type="hidden" id="ikodeharga<?= $i; ?>" class="form-control input-sm" name="ikodeharga<?= $i; ?>" value="<?= $row->id_harga_kode; ?>" readonly>
                                <input type="text" id="ekodeharga<?= $i; ?>" class="form-control input-sm" name="ekodeharga<?= $i; ?>" value="<?= $row->e_harga; ?>" readonly>
                            </td>
                            <td class="col-sm-1">
                                <input type="text" id="harga<?= $i; ?>" class="form-control text-right input-sm" name="harga<?= $i; ?>" value="" placeholder="0" onkeypress="return hanyaAngka(event)">
                            </td>
                            <td class="col-sm-1">
                                <input type="text" id="dberlaku<?= $i; ?>" class="form-control input-sm date" name="dberlaku<?= $i; ?>" value="" readonly>
                            </td>
                            <td style="width:2%;" class="text-center">
                                <input type="checkbox" name="cek<?php echo $i; ?>" value="cek" id="cek<?php echo $i; ?>">
                            </td>
                        </tr>
                    <? } ?>
                </tbody>
            </table>
            <input type="hidden" name="jml" id="jml" value="<?= $i; ?>" readonly>
        </div>
    </div>
</div>
</div>
</form>
<script>
    $(document).ready(function() {
        $('#myTable').DataTable();
        $('.select2').select2();
        showCalendar('.date');
    });

    $('.dataTables_paginate').on('click', function() {
        $('.select2').select2();
        showCalendar('.date');
    });

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
    });

    function hanyaAngka(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))

            return false;
        return true;
    }

    function validasi() {
        var s = 0;
        var jml = document.getElementById("jml").value;
        var maxpil = 1;
        var jml = $("input[type=checkbox]:checked").length;
        var textinputs = document.querySelectorAll('input[type=checkbox]');
        var empty = [].filter.call(textinputs, function(el) {
            return !el.checked
        });

        if (textinputs.length == empty.length) {
            swal("Barang Belum dipilih !!");
            return false;
        }

        for (i = 1; i <= jml; i++) {
            if ($('#cek' + i).val() == "cek") {
                if ($("#dberlaku" + i).val() == '' || $("#dberlaku" + i).val() == null || $("#ikodeharga" + i).val() == '' || $("#ikodeharga" + i).val() == null || $("#harga" + i).val() == '' || $("#harga" + i).val() == null) {
                    swal('Data Item Belum Lengkap!');
                    return false;
                } else {
                    return true;
                }
            }
        }
    }
</script>