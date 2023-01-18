
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-list"></i> &nbsp; <?= $title; ?>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/tambah'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div class="white-box">
                <div class="col-md-12" >
                    <div class="form-group">
                        <label class="col-md-6">Upload File (Optional)</label>
                        <label class="col-md-6 text-right" for="input-file-now">Extention nya harus .xls</label>
                        <input type="file" id="input-file-now" name="userfile" class="dropify" /><br>
                        <button type="button" id="upload" class="btn btn-success btn-rounded btn-sm"><i class="fa fa-upload mr-1"></i>&nbsp;&nbsp;Update</button>
                        <a id="href" href="<?= base_url().$folder.'/Cform/export';?>"><button type="button" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-download"></i>&nbsp;&nbsp;Download Format</button></a>
                        <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button> 
                    </div>
                </div>
                </form>
            </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    $('.select2').select2();
    showCalendar('.date');

    $("#upload").on("click", function () {
        var formData = new FormData();
        formData.append('userfile', $('input[type=file]')[0].files[0]);
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/load'); ?>",
            data:formData,
            processData:false,
            contentType:false,
            cache:false,
            /* async:false, */
            beforeSend: function() {
                $("#main").block({
                    message: '<img src="assets/images/loading.gif" alt="" /><h1>Please Waiting...</h1>',
                    css: {
                        border: "none",
                        background: "none",
                    },
                });
            },
            complete: function(){
                $("#main").unblock();
            },
            success: function(data){
                var json = JSON.parse(data);
                var status = json.status;
                if (status=='berhasil') {
                    swal({
                        title: "Berhasil!",
                        text: "File Berhasil Diupdate :)",
                        type: "success",
                        showConfirmButton: false,
                        timer: 1500
                    });
                    show('<?= $folder;?>/cform/','#main');   
                }else{
                    swal({
                        title: "Gagal!",
                        text: "File Gagal Diupdate :)",
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
</script>