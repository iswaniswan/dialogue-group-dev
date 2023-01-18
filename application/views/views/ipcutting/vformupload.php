<!-- <?php /* echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/act_upload'), 'update' => '#pesan', 'type' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal')); */ ?> -->
<form>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <i class="fa fa-upload"></i> &nbsp; <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom; ?>/<?= $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?= $title_list; ?></a>
                </div>
                <div class="panel-body table-responsive">
                    <div id="pesan"></div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-sm-4">Bagian Pembuat</label>
                            <label class="col-md-4">Nomor Dokumen</label>
                            <label class="col-md-4">Tanggal Dokumen</label>
                            <div class="col-sm-4">
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
                                <div class="input-group">
                                    <input type="text" name="idocument" id="ipcutting" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="IP-2010-000001" maxlength="15" class="form-control input-sm" value="<?= $number; ?>" aria-label="Text input with dropdown button">
                                    <span class="input-group-addon">
                                        <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                    </span>
                                </div>
                                <span class="notekode">Format : (<?= $number; ?>)</span><br>
                                <span class="notekode" hidden="true"><b> * No. Sudah Ada!</b></span>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" id="ddocument" name="ddocument" class="form-control input-sm date" value="<?php echo date("d-m-Y"); ?>" required="" placeholder="<?= date('d-m-Y'); ?>" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <!--   <label class="col-md-4">Perusahaan</label> -->
                            <label class="col-md-4">Nomor Referensi</label>
                            <label class="col-md-4">Tanggal Referensi</label>
                            <label class="col-md-4">Keterangan</label>
                            <div class="col-sm-4">
                                <select name="ireff" id="ireff" class="form-control select2" onchange="getdata(this.value);">
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" id="dreferensi" name="dreferensi" class="form-control input-sm" value="" required="" placeholder="<?= date('d-m-Y'); ?>" readonly>
                            </div>
                            <div class="col-sm-4">
                                <textarea id="eremark" name="eremark" class="form-control input-sm" placeholder="Isi keterangan jika ada!"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="white-box" id="detail">
        <div class="panel-body">
            <div class=" form-group">
                <label for="input-file-now">Extention nya harus .xls</label>
                <input type="file" id="input-file-now" name="userfile" class="dropify" /><br>
                <button type="submit" class="btn btn-success btn-rounded btn-sm mr-1"><i class="fa fa-upload mr-1"></i>&nbsp;&nbsp;Upload</button>
                <a id="href" onclick="return donwload();"><button type="button" class="btn btn-primary btn-rounded btn-sm mr-1"><i class="fa fa-download"></i>&nbsp;&nbsp;Download</button> </a>
                <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom; ?>/<?= $dto; ?>','#main'); return false;"><i class="ti-arrow-circle-left mr-2"></i>Kembali</button>
            </div>
        </div>
    </div>
    <input type="hidden" name="jml" id="jml" value="0">
</form>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
    $(document).ready(function() {
        $('.dropify').dropify();
        $('.select2').select2();
        showCalendar('.date');

        $('#ipcutting').mask('SS-0000-000000S');
        //memanggil function untuk penomoran dokumen
        number();

        $('#ireff').select2({
            placeholder: 'Pilih Referensi',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder . '/cform/referensi'); ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    var query = {
                        q: params.term,
                        // iasal : $('#ipengirim').val(),
                    }
                    return query;
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                cache: false
            }
        });

        $("#ipcutting").keyup(function() {
            $.ajax({
                type: "post",
                data: {
                    'kode': $(this).val(),
                    'ibagian': $('#ibagian').val(),
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

        //menyesuaikan periode di running number sesuai dengan tanggal dokumen
        $("#ddocument").change(function() {
            $.ajax({
                type: "post",
                data: {
                    'tgl': $(this).val(),
                    'ibagian': $('#ibagian').val(),
                },
                url: '<?= base_url($folder . '/cform/number'); ?>',
                dataType: "json",
                success: function(data) {
                    $('#ipcutting').val(data);
                },
                error: function() {
                    swal('Error :)');
                }
            });
        });

        //untuk me-generate running number
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
                    $('#ipcutting').val(data);
                },
                error: function() {
                    swal('Error :)');
                }
            });
        }

        $("#ipengirim").change(function() {
            $('#ireff').attr("disabled", false);
        });

        $('#ceklis').click(function(event) {
            if ($('#ceklis').is(':checked')) {
                $("#ipcutting").attr("readonly", false);
            } else {
                $("#ipcutting").attr("readonly", true);
            }
        });

        $('#send').click(function(event) {
            statuschange('<?= $folder; ?>', $('#id').val(), '2', '<?= $dfrom . "','" . $dto; ?>');
        });
    });

    function donwload() {
        var ireff = $('#ireff').val();
        if (ireff == null || ireff == '') {
            swal('Referensi Harus Dipilih!');
            return false;
        } else {
            $('#href').attr('href', '<?php echo site_url($folder . '/cform/download_file/'); ?>' + ireff);
            return true;
        }
    }

    function getdata(ireff) {
        var idreff = $('#ireff').val();
        // var ipengirim = $('#ipengirim').val();
        if (idreff) {
            $.ajax({
                type: "post",
                data: {
                    'idreff': idreff,
                },
                url: '<?= base_url($folder . '/cform/getdata'); ?>',
                dataType: "json",
                success: function(data) {
                    var dref = data['datahead']['d_document'];
                    $("#dreferensi").val(dref);
                },
                error: function() {
                    alert('Error :)');
                }
            });
        }
    }

    function set_check(i) {
        if ($("#f_auto_cutter" + i + ":checked").length > 0) {
            $('#f_auto' + i).val('t');
        } else {
            $('#f_auto' + i).val('f');
        }
    }

    function max_tgl() {
        $('#ddocument').datepicker('destroy');
        $('#ddocument').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: "dd-mm-yyyy",
            todayBtn: "linked",
            daysOfWeekDisabled: [0],
            startDate: document.getElementById('dreferensi').value,
        });
    }

    function validasi(id) {
        nquantityma = $("#nquantitywipsisa" + id).val();
        nquantitymasuk = $("#nquantitywipmasuk" + id).val();
        nquantitymaterial = $("#nquantitybahansisa" + id).val();
        nquantitymasukmaterial = $("#nquantitybahanmasuk" + id).val();

        if (parseFloat(nquantitymasuk) > parseFloat(nquantityma)) {
            swal('Quantity Masuk Tidak Boleh Lebih Dari Quantity Keluar');
            $("#nquantitywipmasuk" + id).val(nquantityma);
        }
        if (parseFloat(nquantitymasukmaterial) > parseFloat(nquantitymaterial)) {
            swal('Quantity Masuk Tidak Boleh Lebih Dari Quantity Keluar');
            $("#nquantitybahanmasuk" + id).val(nquantitymaterial);
        }

        if (parseFloat(nquantitymasuk) == '0') {
            swal('Quantity Tidak Boleh 0 atau Kosong');
            $("#nquantitywipmasuk" + id).val(nquantityma);
        }
        if (parseFloat(nquantitymasukmaterial) == '0') {
            swal('Quantity Tidak Boleh 0 atau Kosong');
            $("#nquantitybahanmasuk" + id).val(nquantitymaterial);
        }
    }

    /* $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        // $("select").attr("disabled", true);
        $("#addrow").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#send").attr("hidden", false);
    }); */

    $('form').on('submit', function(e) { //bind event on form submit.
        e.preventDefault();
        if ($('#input-file-now').val()!='' && $('#ireff').val()!='') {
            var formData = new FormData(this);
            formData.append('userfile', $('input[type=file]')[0].files[0]);
            $.ajax({
                type: "POST",
                enctype: "multipart/form-data",
                url: "<?= base_url($folder . '/cform/act_upload'); ?>",
                data: formData,
                contentType: false,
                processData: false,
                cache: false,
                success: function(data) {
                    var json = JSON.parse(data);
                    if (json.sukses == true) {
                        swal({
                            title: "Berhasil!",
                            text: "File Berhasil Diupload dan Simpan :)",
                            type: "success",
                            showConfirmButton: false,
                            timer: 1500
                        });
                        show('<?= $folder;?>/cform','#main');   
                    } else {
                        swal({
                            title: "Gagal!",
                            text: "File Gagal Diupload dan Simpan:)",
                            type: "error",
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                },
            });
        } else {
            swal('Pilih File & Referensi terlebih dahulu!');
        }
    });

    $("#upload").on("click", function() {
        var ibagian = $('#ibagian').val();
        var idcustomer = $('#idcustomer').val();
        var bulan = $('#bulan').val();
        var tahun = $('#tahun').val();

        var formData = new FormData();
        /* formData.append('userfile', $('input[type=file]')[0].files[0]);
        formData.append('ibagian', ibagian);
        formData.append('idcustomer', idcustomer);
        formData.append('bulan', bulan);
        formData.append('tahun', tahun); */
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder . '/cform/act_upload'); ?>",
            enctype: "multipart/form-data",
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            async: false,
            success: function(data) {
                /* var json = JSON.parse(data);
                var status = json.status;
                if (status == 'berhasil') {
                    var idcustomer = json.idcustomer;
                    var tahun = json.tahun;
                    var bulan = json.bulan;
                    var ibagian = json.ibagian;
                    swal({
                        title: "Berhasil!",
                        text: "File Berhasil Diupload :)",
                        type: "success",
                        showConfirmButton: false,
                        timer: 1500
                    });
                } else {
                    swal({
                        title: "Gagal!",
                        text: "File Gagal Diupload :)",
                        type: "error",
                        showConfirmButton: false,
                        timer: 1500
                    });
                } */
            },
        });
    });

    function konfirm() {
        var jml = $('#jml').val();
        ada = false;
        if (jml == 0) {
            swal('Isi data item minimal 1 !!!');
            return false;
        } else {
            $("#tabledatax tbody tr").each(function() {
                $(this).find("td select").each(function() {
                    if ($(this).val() == '' || $(this).val() == null) {
                        swal('Kode barang tidak boleh kosong!');
                        ada = true;
                    }
                });
                $(this).find("td .inputitem").each(function() {
                    if ($(this).val() == '' || $(this).val() == null || $(this).val() == 0) {
                        swal('Quantity Tidak Boleh Kosong Atau 0!');
                        ada = true;
                    }
                });
            });
            if (!ada) {
                return true;
            } else {
                return false;
            }
        }
    }
</script>