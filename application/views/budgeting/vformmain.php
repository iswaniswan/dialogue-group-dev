<style>
    .dropify-wrapper {
        max-height: 107px;
    }
</style>
<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/tambah'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal', 'id' => 'idform')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-list"></i> &nbsp; <?= $title; ?><a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
                  <input type="hidden"  name="dfrom" value="<?= $dfrom; ?>" />
                  <input type="hidden"  name="dto" value="<?= $dto; ?>" />
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12 ol-md-6 col-xs-12">
        <div class="white-box">
            <h3 class="box-title">Input Data</h3>
            <div class="form-group row">
                <label class="col-sm-6">Bagian Pembuat</label>
                <label class="col-md-4">Bulan</label>
                <label class="col-md-2">Tahun</label>
                <div class="col-sm-6">
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

                <div class="col-sm-4">
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

            <div class="row">
                <div class="col-sm-6">
                    <button type="button" id="submit" class="btn btn-info btn-block btn-sm" onclick="validasi();"> <i class="fa fa-search fa-lg mr-2"></i>View</button>
                </div>
                <div class="col-sm-6">
                    <button type="button" class="btn btn-inverse btn-block btn-sm" onclick='show("<?= $folder; ?>/cform/","#main")'><i class="fa fa-arrow-circle-left fa-lg mr-2"></i>Kembali</button>
                </div>
            </div>

            <br>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-12">Upload Untuk Penyesuaian QTY saja</label>
                        <label class="col-md-12 text-right notekode">Formatnya .xls</label>
                        <div class="col-sm-12">
                            <input type="file" id="input-file-now" name="userfile" class="dropify" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <button type="button" id="upload" class="btn btn-success btn-block btn-sm"><i class="fa fa-upload mr-1 mr-2"></i>Upload</button>
                        </div>
                        <div class="col-md-6">
                            <a id="href_export" onclick="return export_data();"><button type="button" class="btn btn-primary btn-block btn-sm"><i class="fa fa-download mr-2"></i>Download Template</button> </a>
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

    function export_data() {
        var ibagian = $('#ibagian').val();
        var bulan = $('#bulan').val();
        var tahun = $('#tahun').val();
        var dfrom = $('#dfrom').val();
        var dto = $('#dto').val();
        var id = '';

        $('#href_export').attr('href', '<?php echo site_url($folder . '/cform/export_template/'); ?>'+ibagian+'/'+tahun+'/'+bulan+'/'+dfrom+'/'+dto+'/'+id);
        return true;
        
    }


    function validasi() {
        var ibagian = $('#ibagian').val();
        if (ibagian == '') {
            swal('Data header Belum Lengkap');
        } else {
            $.ajax({
                type: "POST",
                url: "<?php echo site_url($folder . '/Cform/checkfcproduksi'); ?>",
                data: {
                    "tahun": $('#tahun').val(),
                    "bulan": $('#bulan').val(),
                },
                dataType: 'json',
                success: function(data) {
                    //console.log(data);
                    if (data == "1") {
                        swal({
                            title: "Peringatan!",
                            text: "Dokumen FC Produksi Periode " + $('#tahun').val() + $('#bulan').val() + " sedang dalam proses",
                            type: "warning",
                            showConfirmButton: false,
                            timer: 2500
                        });
                    } else {
                        $('#idform').submit();
                    }
                },
                error: function(XMLHttpRequest) {
                    alert(XMLHttpRequest.responseText);
                }
            });
        }
    }

    $(document).ready(function() {
        $('.select2').select2();
        showCalendar('.date');

        $("#upload").on("click", function() {
            var ibagian = $('#ibagian').val();
            var bulan = $('#bulan').val();
            var tahun = $('#tahun').val();

            if (ibagian == '') {
                swal('Data header Belum Lengkap');
            } else {
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url($folder . '/Cform/checkfcproduksi'); ?>",
                    data: {
                        "tahun": $('#tahun').val(),
                        "bulan": $('#bulan').val(),
                    },
                    dataType: 'json',
                    success: function(data) {
                        //console.log(data);
                        if (data == "1") {
                            swal({
                                title: "Peringatan!",
                                text: "Dokumen FC Produksi Periode " + $('#tahun').val() + $('#bulan').val() + " sedang dalam proses",
                                type: "warning",
                                showConfirmButton: false,
                                timer: 2500
                            });
                        } else {
                            var formData = new FormData();
                            formData.append('userfile', $('input[type=file]')[0].files[0]);
                            formData.append('ibagian', ibagian);
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
                                    var json = JSON.parse(data);
                                    var status = json.status;
                                    if (status == 'berhasil') {
                                        var tahun = json.tahun;
                                        var bulan = json.bulan;
                                        var ibagian = json.ibagian;
                                        show('<?= $folder; ?>/cform/loadview/' + tahun + '/' + bulan + '/' + ibagian, '#main');
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
                        }
                    },
                    error: function(XMLHttpRequest) {
                        alert(XMLHttpRequest.responseText);
                    }
                });
            }
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