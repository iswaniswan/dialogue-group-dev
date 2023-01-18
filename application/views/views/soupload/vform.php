<div class="panel panel-info">
    <div class="panel-heading">
        <i class="fa fa-upload"></i> &nbsp; <?= $title; ?> <a href="#"
        onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
        class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
    </div>
</div>
<div class="row">
    <div class="col-sm-12 ol-md-12 col-xs-12">
        <form id="submit">
            <div class="white-box">
                <h3 class="box-title">Pilih File Yang Akan Diupload!</h3>
                <label for="input-file-now">Extention nya harus .xls</label>
                <input type="file" id="input-file-now" name="userfile" class="dropify" required=""/><br>
                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-upload"></i>&nbsp;&nbsp;Upload
                </button>
            </div>
        </form>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('#submit').submit(function(e){
            e.preventDefault(); 
            $.ajax({
                url: '<?= base_url($folder.'/cform/upload'); ?>',
                type:"post",
                data:new FormData(this),
                processData:false,
                contentType:false,
                cache:false,
                async:false,
                success: function(data){
                    if (data=='berhasil') {
                        swal({
                            title: "Berhasil!",
                            text: "File Berhasil Diupload :)",
                            type: "success",
                            showConfirmButton: false,
                            timer: 1500
                        });
                        show('<?= $folderx;?>/cform/','#main');   
                    }else{
                        swal({
                            title: "Gagal!",
                            text: "File Gagal Diupload :)",
                            type: "error",
                            showConfirmButton: false,
                            timer: 1500
                        });                
                        /*show('<?= $folder;?>/cform/','#main'); */  
                    }
                }
            });
        });
        $('.dropify').dropify();
    }); 
</script>