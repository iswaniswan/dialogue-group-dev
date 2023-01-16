
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-list"></i> &nbsp; <?= $title; ?>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/view'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="white-box">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-12">Tanggal SO</label>
                        <div class="col-sm-4">
                            <input type="text" id="dso" name="dso" class="form-control date"  readonly value="<?php echo date("d-m-Y"); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                    </div>
                    <div class="form-group">
                    </div>
                    <div class="form-group">
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-12">  
                            <button type="submit" id="submit" class="btn btn-inverse btn-rounded btn-sm" onclick="return validasi();"> <i class="fa fa-search"></i>&nbsp;&nbsp;View</button>
                            <button type="button" id="approve" class="btn btn-success btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/approval/"+$("#dso").val(),"#main")' ><i class="fa fa-check"></i>&nbsp;&nbsp;Approve</button>  
                            <a id="href" onclick="return validasi();"><button type="button" class="btn btn-secondary btn-rounded btn-sm"><i class="fa fa-download"></i>&nbsp;&nbsp;Download</button> </a>
                            <button type="button" id="upload" class="btn btn-outline-info btn-rounded btn-sm" onclick="return validasi();"><i class="fa fa-upload"></i>&nbsp;&nbsp;Upload</button> 
                        </div>
                    </div>
                </div>
                <div class="col-md-6" >
                    <div class="form-group">
                        <label class="col-md-6">Upload File (Optional)</label>
                        <label for="input-file-now">Extention nya harus .xls</label>
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
    var dso = $('#dso').val();
    //alert(gudang + dso);
    if ( dso == '') {
        swal('Data header Belum Lengkap');
        return false;
    } else {
        $('#href').attr('href','<?php echo site_url($folder.'/cform/export/');?>'+dso);
        return true;
    }
}
$(document).ready(function () {
    $('.select2').select2();
    showCalendar('.date');

    $("#upload").on("click", function () {
        var dso = $('#dso').val();
        var formData = new FormData();
        formData.append('userfile', $('input[type=file]')[0].files[0]);
        //formData.append('gudang',gudang);
        formData.append('dso',dso);
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
                    var dso = json.dso;
                    show('<?= $folder;?>/cform/loadview/'+dso,'#main');   
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

function getbarang(ikodemaster) {
    $("#approve").attr("disabled", true);
    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/getbarang');?>",
        data:"ikodemaster="+ikodemaster,
        dataType: 'json',
        success: function(data){
            $("#ikodebarang").html(data.kop);
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