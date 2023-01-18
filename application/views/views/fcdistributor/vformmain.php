<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/tambah'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-list"></i> &nbsp; <?= $title; ?>
                <a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-3">Bagian Pembuat</label>
                        <label class="col-sm-4">Customer</label>
                        <label class="col-md-3">Bulan</label>
                        <label class="col-md-2">Tahun</label>
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2" required="">
                                <?php if ($bagian) {
                                    foreach ($bagian as $row) : ?>
                                        <option value="<?= $row->i_bagian; ?>"><?= $row->e_bagian_name; ?></option>
                                <?php endforeach;
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <select name="idcustomer" id="idcustomer" class="form-control select2" required="">
                                <?php if ($customer) {
                                    foreach ($customer as $row) : ?>
                                        <option value="<?= $row->id; ?>">
                                            <?= $row->e_customer_name; ?>
                                        </option>
                                <?php endforeach;
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select class="form-control select2" id="bulan" name="bulan">
                                <?php
                                $bulan = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");

                                $jlh_bln = count($bulan);
                                for ($c = 0; $c < $jlh_bln; $c += 1) {
                                    $sel = "";
                                    $i = $c + 1;
                                    if ($i <= 9) {
                                        $i = '0' . $i;
                                    }
                                    if ($i == date('m')) $sel = "selected";
                                    echo "<option value=$i $sel> $bulan[$c] </option>";
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <select class="form-control select2" name="tahun" id="tahun"></select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <button type="submit" id="submit" class="btn btn-info btn-block btn-sm" onclick="return validasi();"> <i class="fa fa-search mr-2"></i>Proses</button>
                        </div>
                        <div class="col-sm-6">
                            <button type="button" class="btn btn-inverse btn-block btn-sm" onclick='show("<?= $folder; ?>/cform/","#main")'><i class="fa fa-arrow-circle-left mr-2"></i>Kembali</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel">
            <div class="panel-body">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Upload File Perwarna (Optional)</label>
                        <label class="col-md-3 text-right notekode">Formatnya .xls</label>
                        <label class="col-md-3">Upload File (Optional)</label>
                        <label class="col-md-3 text-right notekode">Formatnya .xls</label>
                        <div class="col-sm-6">
                            <input type="file" id="input-file-now" name="userfile_warna" class="dropify" />
                        </div>
                        <div class="col-sm-6">
                            <input type="file" id="input-file-now" name="userfile" class="dropify" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <button type="button" id="upload_warna" class="btn btn-warning btn-block btn-sm"><i class="fa fa-upload mr-1 mr-2"></i>Upload Perwarna</button>
                        </div>
                        <div class="col-md-3">
                            <a id="href_warna" onclick="return export_warna();"><button type="button" class="btn btn-danger btn-block btn-sm"><i class="fa fa-download mr-2"></i>Download Perwarna</button> </a>
                        </div>
                        <div class="col-md-3">
                            <button type="button" id="upload" class="btn btn-success btn-block btn-sm"><i class="fa fa-upload mr-1 mr-2"></i>Upload Tanpa Warna</button>
                        </div>
                        <div class="col-md-3">
                            <a id="href_nowarna" onclick="return export_tanpa_warna();"><button type="button" class="btn btn-primary btn-block btn-sm"><i class="fa fa-download mr-2"></i>Download Tanpa Warna</button> </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</form>
<script>
    $(document).ready(function() {
        $('.select2').select2();
        showCalendar('.date');

        var min = new Date().getFullYear() - 1,
            max = min + 2,
            select = document.getElementById('tahun');

        for (var i = min; i <= max; i++) {
            var opt = document.createElement('option');
            if (i == new Date().getFullYear()) {
                opt.selected = true;
            }
            opt.value = i;
            opt.innerHTML = i;
            select.appendChild(opt);
        }
    });

    function export_warna() {
        var ibagian = $('#ibagian').val();
        var bulan = $('#bulan').val();
        var tahun = $('#tahun').val();
        var idcustomer = $('#idcustomer').val();
        if (ibagian == '' && idcustomer == '') {
            swal('Data header Belum Lengkap');
            return false;
        } else {
            $('#href_warna').attr('href', '<?php echo site_url($folder . '/cform/export_warna/'); ?>' + bulan + '/' + tahun);
            return true;
        }
    }

    function export_tanpa_warna() {
        var ibagian = $('#ibagian').val();
        var bulan = $('#bulan').val();
        var tahun = $('#tahun').val();
        var idcustomer = $('#idcustomer').val();
        if (ibagian == '' && idcustomer == '') {
            swal('Data header Belum Lengkap');
            return false;
        } else {
            $('#href_nowarna').attr('href', '<?php echo site_url($folder . '/cform/export/'); ?>' + bulan + '/' + tahun);
            return true;
        }
    }
    $(document).ready(function() {
        $('.select2').select2();
        showCalendar('.date');

        $("#upload").on("click", function() {

            var ibagian = $('#ibagian').val();
            var idcustomer = $('#idcustomer').val();
            var bulan = $('#bulan').val();
            var tahun = $('#tahun').val();

            var formData = new FormData();
            formData.append('userfile', $('input[type=file]')[1].files[0]);
            formData.append('ibagian', ibagian);
            formData.append('idcustomer', idcustomer);
            formData.append('bulan', bulan);
            formData.append('tahun', tahun);
            $.ajax({
                type: "POST",
                url: "<?= base_url($folder . '/cform/load'); ?>",
                data: formData,
                processData: false,
                contentType: false,
                cache: false,
                async: false,
                success: function(data) {
                    //console.log($('input[type=file]')[0].files[0]);
                    var json = JSON.parse(data);
                    var status = json.status;
                    if (status == 'berhasil') {
                        var idcustomer = json.idcustomer;
                        var tahun = json.tahun;
                        var bulan = json.bulan;
                        var ibagian = json.ibagian;
                        // swal({
                        //     title: "Berhasil!",
                        //     text: "File Berhasil Diupload :)",
                        //     type: "success",
                        //     showConfirmButton: false,
                        //     timer: 1500
                        // });
                        show('<?= $folder; ?>/cform/loadview/' + idcustomer + '/' + tahun + '/' + bulan + '/' + ibagian, '#main');
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
        });

        $("#upload_warna").on("click", function() {

            var ibagian = $('#ibagian').val();
            var idcustomer = $('#idcustomer').val();
            var bulan = $('#bulan').val();
            var tahun = $('#tahun').val();

            var formData = new FormData();
            formData.append('userfile_warna', $('input[type=file]')[0].files[0]);
            formData.append('ibagian', ibagian);
            formData.append('idcustomer', idcustomer);
            formData.append('bulan', bulan);
            formData.append('tahun', tahun);
            $.ajax({
                type: "POST",
                url: "<?= base_url($folder . '/cform/load_warna'); ?>",
                data: formData,
                processData: false,
                contentType: false,
                cache: false,
                async: false,
                success: function(data) {
                    //console.log($('input[type=file]')[0].files[0]);
                    var json = JSON.parse(data);
                    var status = json.status;
                    if (status == 'berhasil') {
                        var idcustomer = json.idcustomer;
                        var tahun = json.tahun;
                        var bulan = json.bulan;
                        var ibagian = json.ibagian;
                        // swal({
                        //     title: "Berhasil!",
                        //     text: "File Berhasil Diupload :)",
                        //     type: "success",
                        //     showConfirmButton: false,
                        //     timer: 1500
                        // });
                        show('<?= $folder; ?>/cform/loadview_warna/' + idcustomer + '/' + tahun + '/' + bulan + '/' + ibagian, '#main');
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
        });

        $('.dropify').dropify();
    });

    function getbarang(ikodemaster) {
        $("#approve").attr("disabled", true);
        $.ajax({
            type: "POST",
            url: "<?php echo site_url($folder . '/Cform/getbarang'); ?>",
            data: "ikodemaster=" + ikodemaster,
            dataType: 'json',
            success: function(data) {
                $("#ikodebarang").html(data.kop);
                if (data.kosong == 'kopong') {
                    $("#submit").attr("disabled", true);

                } else {
                    $("#submit").attr("disabled", false);
                }
            },

            error: function(XMLHttpRequest) {
                alert(XMLHttpRequest.responseText);
            }
        })
    }
</script>