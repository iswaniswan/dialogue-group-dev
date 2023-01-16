<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-list"></i> &nbsp; <?= $title; ?>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/view'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Periode</label>
                        <label class="col-md-8">Tahun</label>
                        <div class="col-sm-4">
                           <select name="iperiodebl" id="iperiodebl" class="form-control select2" >
                            <option value="">Pilih Bulan</option>
                                <option value='01'>Januari</option>
                                <option value='02'>Februari</option>
                                <option value='03'>Maret</option>
                                <option value='04'>April</option>
                                <option value='05'>Mei</option>
                                <option value='06'>Juni</option>
                                <option value='07'>Juli</option>
                                <option value='08'>Agustus</option>
                                <option value='09'>September</option>
                                <option value='10'>Oktober</option>
                                <option value='11'>November</option>
                                <option value='12'>Desember</option>
                        </select>
                        </div>
                        <div class="col-sm-4">
                             <select name="iperiodeth" id="iperiodeth" class="form-control select2" required="">
                                <option value=""></option>
                                <?php 
                                $tahun1 = date('Y')-3;
                                $tahun2 = date('Y');
                                for($i=$tahun1;$i<=$tahun2;$i++){ ?>
                                    <option value="<?= $i;?>" <?php if ($tahun==$i) {
                                    echo "selected";} ?>><?= $i;?></option>
                                <?php } ?>
                            </select>  
                        </div>
                    </div>
                    <div class="form-group row">
                    </div>
                    <div class="form-group row">
                    </div>
                    <div class="form-group row">
                    </div>
                    <div class="form-group row">
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-12">  
                            <button type="submit" id="submit" class="btn btn-inverse btn-rounded btn-sm" onclick="return cek();"> <i class="fa fa-search"></i>&nbsp;&nbsp;View</button>
                            <button type="button" id="approve" class="btn btn-success btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/approval/"+$("#iperiodebl").val()+"/"+$("#iperiodeth").val(),"#main")' ><i class="fa fa-check"></i>&nbsp;&nbsp;Approve</button>  
                            <a id="href" onclick="return cek();"><button type="button" class="btn btn-secondary btn-rounded btn-sm"><i class="fa fa-download"></i>&nbsp;&nbsp;Download</button> </a>
                            <button type="button" id="upload" class="btn btn-outline-info btn-rounded btn-sm" onclick="return cek();"><i class="fa fa-upload"></i>&nbsp;&nbsp;Upload</button> 
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-6">Upload File (Optional)</label>
                        <label for="input-file-now">Extention nya harus .xls</label>
                        <div class="col-sm-10">
                            <input type="file" id="input-file-now" name="userfile" class="dropify" /><br>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    $('.select2').select2();
    showCalendar('.date');

    $("#upload").on("click", function () {
        var iperiodebl = $('#iperiodebl').val();
        var iperiodeth = $('#iperiodeth').val();
        var formData = new FormData();
        formData.append('userfile', $('input[type=file]')[0].files[0]);
        formData.append('iperiodebl',iperiodebl);
        formData.append('iperiodeth',iperiodeth);
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
                    var iperiodebl = json.iperiodebl;
                    var iperiodeth = json.iperiodeth;
                    show('<?= $folder;?>/cform/loadview/'+iperiodebl+'/'+iperiodeth,'#main');   
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

$('#iperiodebl, #iperiodeth, #bulan, #tahun').on('change',function(){
    var bulan = $('#iperiodebl').val();
    var tahun = $('#iperiodeth').val();

    $('#href').attr('href','<?php echo site_url($folder.'/cform/export/');?>'+bulan+'/'+tahun);
});

function cek(){
      var periode   = $('#iperiodebl').val();
      var periodeth = $('#iperiodeth').val();
      if (periode == '' || periodeth == ''){
        swal("Data Masih ada yang belum dipilih");
        return false;
      }else{
        return true;
      }
}
</script>