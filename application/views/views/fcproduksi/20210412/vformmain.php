
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-list"></i> &nbsp; <?= $title; ?>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/edit'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="white-box">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-3">Date From</label><label class="col-md-9">Date To</label>
                        <div class="col-sm-3">
                            <input type="text" id="dfrom" name="dfrom" class="form-control date"  readonly value="">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="dto" name="dto" class="form-control date"  readonly value="<?php echo date("d-m-Y"); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-12">  
                            <button type="submit" id="submit" class="btn btn-inverse btn-rounded btn-sm" onclick="return validasi();"> <i class="fa fa-pencil"></i>&nbsp;&nbsp;Edit</button> 
                            <button type="button" id="view" class="btn btn-primary btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/views/"+$("#dfrom").val()+"/"+$("#dto").val(),"#main");' onclick="return validasi();"><i class="fa fa-search"></i>&nbsp;&nbsp;View</button>  
                            <a id="href" onclick="return validasi();"><button type="button" class="btn btn-secondary btn-rounded btn-sm"><i class="fa fa-download" onclick="return validasi();"></i>&nbsp;&nbsp;Download</button> </a>
                            <button type="button" id="upload" class="btn btn-outline-info btn-rounded btn-sm" onclick="return validasi();"><i class="fa fa-upload"></i>&nbsp;&nbsp;Upload</button> 
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-6">Upload File (Optional)</label><label for="input-file-now">Extention nya harus .xls</label>
                        <input type="file" id="input-file-now" name="userfile" class="dropify" /><br>
                    </div>
                </div>
                </form>
            </div>
            </div>
        </div>
    </div>
</div>

<script>
function validasi() {
    var dfrom = $('#dfrom').val();
    var dto = $('#dto').val();
    if ( dfrom == '' || dto == '') {
        swal('Data header Belum Lengkap');
        return false;
    } else {
        $('#href').attr('href','<?php echo site_url($folder.'/cform/export/');?>'+dfrom+'/'+dto);
        return true;
    }
}

$(document).ready(function () {
    $('.select2').select2();
    showCalendar('.date');

    $("#upload").on("click", function () {
        var dfrom = $('#dfrom').val();
        var dto   = $('#dto').val();
        var formData = new FormData();
        formData.append('userfile', $('input[type=file]')[0].files[0]);
        formData.append('dfrom',dfrom);
        formData.append('dto',dto);
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/load'); ?>",
            data:formData,
            processData:false,
            contentType:false,
            cache:false,
            async:false,
            success: function(data){
                var json = JSON.parse(data);
                var status = json.status;
                if (status=='berhasil') {
                    var dfrom = json.dfrom;
                    var dto   = json.dto;   
                    show('<?= $folder;?>/cform/loadview/'+dfrom+'/'+dto,'#main');   
                }else{
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

function getbarang() {
    $("#approve").attr("disabled", true);
    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/getbarang');?>",
        dataType: 'json',
        success: function(data){
            $("#iproduct").html(data.kop);
            if (data.kosong=='kopong') {
                $("#submit").attr("disabled", true);
 
            }else{
                $("#submit").attr("disabled", false);
            }
        },
        error:function(XMLHttpRequest){
            alert(XMLHttpRequest.responseText);
        }
    })
} 
</script>
