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
                            <label class="col-md-3">Nomor Dokumen</label>
                            <label class="col-md-3">Tanggal</label>
                            <label class="col-md-3">Bagian</label>
                            <label class="col-md-3">Kategori Jahit</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control input-sm" name="idocument" id="idocument">
                            </div>
                            <div class="col-sm-3">
                                <input type="text" class="form-control input-sm date" name="ddocument" id="ddocument">
                            </div>
                            <div class="col-sm-3">
                                <select name="ibagian" id="ibagian" class="form-control select2" >
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <select name="ikategori" id="ikategori" class="form-control select2" >
                                </select>
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
        //$('.select2').select2();
        showCalendar('.date');

        $('#idocument').mask('SS-0000-000000S');
        //memanggil function untuk penomoran dokumen

        $('#ibagian').select2({
            placeholder: 'Cari Bagian',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getbagian'); ?>',
                dataType: 'json',
                delay: 250,          
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });

        $('#ikategori').select2({
            placeholder: 'Cari Kategori Jahit',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getkategori'); ?>',
                dataType: 'json',
                delay: 250,          
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
    });

    function donwload() {
            $('#href').attr('href', '<?php echo site_url($folder . '/cform/download_file/'); ?>');
            return true;
    }

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

</script>