<div class="row">
    <div class="col-lg-6">
        <div class="panel panel-info">
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/tambah'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-12 row mb-4">
                    <div class="col-sm-6">
                        <label>Date From</label>
                        <input type="text" id="dfrom" name="dfrom" class="form-control input-sm date" value="<?= $dfrom ?>" required="" placeholder="<?= date('d-m-Y'); ?>" readonly>
                    </div>

                    <div class="col-sm-6">
                        <label>Date To</label>
                        <input type="text" id="dto" name="dto" class="form-control input-sm date" value="<?= $dto ?>" required="" placeholder="<?= date('d-m-Y'); ?>" readonly>
                    </div>
                </div>

                <div class="col-md-12 row mb-5">
                    <label class="col-md-12">Upload File (Wajib)</label>
                    <label class="col-md-12 text-right notekode">Formatnya .db</label>
                    <div class="col-sm-12">
                        <input type="file" id="input-file-now" name="userfile" class="dropify" accept=".db" />
                    </div>
                </div>
                <div class="col-md-12 row">
                    <div class="col-md-6">
                        <button type="button" id="upload" class="btn btn-success btn-block btn-sm"><i class="fa fa-upload mr-1 mr-2"></i>Extract Statistic</button>
                    </div>
                    <div class="col-sm-6">
                        <button type="button" class="btn btn-inverse btn-block btn-sm" onclick='show("<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Refresh</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        // $('.select2').select2();
        showCalendar('.date');

        //memanggil function untuk penomoran dokumen
        $('.dropify').dropify();
        $("#upload").on("click", function() {
            var dfrom = $('#dfrom').val();
            var dto = $('#dto').val();

            if (dfrom == "" || dfrom == "" || $('#input-file-now')[0].files.length === 0) {
                swal({
                    title: "Gagal!",
                    text: "Tanggal dan File Wajib ada",
                    type: "error",
                    showConfirmButton: false,
                    timer: 1500
                });
            } else {
                var formData = new FormData();
                formData.append('userfile', $('input[type=file]')[0].files[0]);
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
                        var data = JSON.parse(data);
                        if (data.data === true) {
                            swal({
                                title: "Berhasil",
                                text: data.message,
                                type: "success",
                                showConfirmButton: false,
                                timer: 1500
                            });

                            var $a = $("<a>");
                            $a.attr("href", data.file);
                            $("body").append($a);
                            $a.attr("download", data.nama_file);
                            $a[0].click();
                            $a.remove();

                            
                            //$("." + id_laporan + "").unblock();
                        } else {
                            swal({
                                title: "Gagal!",
                                text: data.message,
                                type: "error",
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                        // var json = JSON.parse(data);
                        // var status = json.status;
                        // var message = json.message;
                        // if (status == true) {
                        //     swal({
                        //         title: "Berhasil",
                        //         text: message,
                        //         type: "success",
                        //         showConfirmButton: false,
                        //         timer: 1500
                        //     });
                        // } else {
                            
                        // }
                    },
                });
            }
            
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