<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-list"></i> &nbsp; <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/tambah'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-4">Bagian Pembuat</label>
                        <label class="col-sm-4">Nomor Dokumen</label>
                        <label class="col-sm-4">Tanggal Dokumen</label>
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
                                <input type="text" name="idocument" id="i_so" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="SO-2021-0001" maxlength="25" class="form-control input-sm" value="<?= $number; ?>" aria-label="Text input with dropdown button">
                                <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span>
                            </div>
                            <span class="notekode">Format : (<?= $number; ?>)</span><br>
                            <span class="notekode" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id="ddocument" name="ddocument" class="form-control input-sm date" value="<?php echo date("d-m-Y"); ?>" required="" placeholder="<?= date('d-m-Y'); ?>" readonly>
                            <input type="hidden" name="dfrom" id="dfrom" value="<?= $dfrom ?>">
                            <input type="hidden" name="dto" id="dto" value="<?= $dto ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <button type="submit" id="submit" class="btn btn-success btn-block btn-sm"> <i class="fa fa-search mr-2"></i>Proses</button>
                        </div>
                        <div class="col-sm-6">
                            <button type="button" class="btn btn-inverse btn-block btn-sm" onclick='show("<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>","#main")'><i class="fa fa-arrow-circle-left mr-2"></i>Kembali</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel">
                    <div class="panel-body">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-md-6">Upload File (Optional)</label>
                                <label class="col-md-6 text-right notekode">Formatnya .xls</label>
                                <div class="col-sm-12">
                                    <input type="file" id="input-file-now" name="userfile" class="dropify" />
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <button type="button" id="upload" class="btn btn-success btn-block btn-sm"><i class="fa fa-upload mr-1 mr-2"></i>Upload Stockopname</button>
                                </div>
                                <div class="col-md-6">
                                    <a id="href" onclick="return export_data();"><button type="button" class="btn btn-primary btn-block btn-sm"><i class="fa fa-download mr-2"></i>Download Template</button> </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>  
    </div>
</div>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
    //menyesuaikan periode di running number sesuai dengan tanggal dokumen
    $("#ibagian, #ddocument").change(function() {
        number();
    });

    $('#ceklis').click(function(event) {
        if ($('#ceklis').is(':checked')) {
            $("#i_so").attr("readonly", false);
        } else {
            $("#i_so").attr("readonly", true);
        }
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
                $('#i_so').val(data);
            },
            error: function() {
                swal('Error :)');
            }
        });
    }

    $("#i_so").keyup(function() {
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

    function validasi() {
        var ibagian = $('#ibagian').val();
        var dfrom = <?= $dfrom; ?>;
        var dto = <?= $dto; ?>;
        //alert(gudang + dso);
        if (ibagian == '') {
            swal('Data header Belum Lengkap');
            return false;
        } else {
            $('#href').attr('href', '<?php echo site_url($folder . '/cform/export/' . $dfrom . '/' . $dto . '/'); ?>' + ibagian);
            return true;
        }
    }

    $(document).ready(function() {
        $('.select2').select2();
        showCalendar('.date');

        // $('#i_so').mask('SS-0000-000S');
        //memanggil function untuk penomoran dokumen
        number();

        $('.dropify').dropify();
        $("#upload").on("click", function() {
            var ibagian = $('#ibagian').val();
            var i_so = $('#i_so').val();
            var ddocument = $('#ddocument').val();
            var dfrom = $('#dfrom').val();
            var dto = $('#dto').val();

            var formData = new FormData();
            formData.append('userfile', $('input[type=file]')[0].files[0]);
            formData.append('ibagian', ibagian);
            formData.append('i_so', i_so);
            formData.append('ddocument', ddocument);
            formData.append('dfrom', dfrom);
            formData.append('dto', dto);

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
                        var ibagian = json.ibagian;
                        var i_so = json.i_so;
                        var ddocument = json.ddocument;
                        var dfrom = json.dfrom;
                        var dto = json.dto;
                        show('<?= $folder; ?>/cform/loadview/' + ibagian + '/' + i_so + '/' + ddocument + '/' + dfrom + '/' + dto, '#main');
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
    });

    function export_data() {
        var ibagian = $('#ibagian').val();
        var dfrom = <?= $dfrom; ?>;
        var dto = <?= $dto; ?>;
        //alert(gudang + dso);
        if (ibagian == '') {
            swal('Data header Belum Lengkap');
            return false;
        } else {
            $('#href').attr('href', '<?php echo site_url($folder . '/cform/export/' . $dfrom . '/' . $dto . '/'); ?>' + ibagian);
            return true;
        }
    }
</script>