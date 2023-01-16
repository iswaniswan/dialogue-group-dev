<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-list"></i> &nbsp; <?= $title; ?>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/tambah'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Gudang</label>
                        <label class="col-md-8">Barang</label>
                        <div class="col-sm-4">
                            <select name="ikodemaster" id="ikodemaster" class="form-control select2" onchange="getbarang(this.value);">
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <select name="ikodebarang" id="ikodebarang" class="form-control select2">
                            </select>
                             <input type="hidden" id= "kelompokbrg" name="kelompokbrg" class="form-control"  readonly value="<?= $kelompokbrg; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Tanggal</label>
                        <div class="col-sm-4">
                            <input type="text" id= "dso" name="dso" class="form-control date"  readonly value="<?php echo date("d-m-Y"); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-12">  
                            <button type="submit" id="submit" class="btn btn-inverse btn-rounded btn-sm" onclick="return validasi();"> <i class="fa fa-search"></i>&nbsp;&nbsp;View</button>
                            <button type="button" id="approve" class="btn btn-success btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/approve","#main")'><i class="fa fa-check"></i>&nbsp;&nbsp;Approve</button>  
                            <a id="href" onclick="return validasi();"><button type="button" class="btn btn-secondary btn-rounded btn-sm"><i class="fa fa-download"></i>&nbsp;&nbsp;Download</button> </a>
                            <button type="button" id="upload" class="btn btn-outline-info btn-rounded btn-sm" onclick="return validasi();"><i class="fa fa-upload"></i>&nbsp;&nbsp;Upload</button> 
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
        var gudang = $('#ikodemaster').val();
        var dso = $('#dso').val();
        var formData = new FormData();
        formData.append('userfile', $('input[type=file]')[0].files[0]);
        formData.append('gudang',gudang);
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
                    var gudang = json.gudang;
                    show('<?= $folder;?>/cform/loadview/'+dso+'/'+gudang,'#main');   
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

$(document).ready(function () {
//var ikodebarang = $('#ikodebarang').val();
$('#ikodemaster').select2({
    placeholder: 'Pilih Gudang',
    allowClear: true,
    ajax: {
      url: '<?= base_url($folder.'/cform/gudang'); ?>',
      dataType: 'json',
      delay: 250,          
      processResults: function (data) {
        return {
          results: data

        };
      },
      cache: true
    }
  })
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

$('#ikodebarang, #ikodemaster, #dso, #bulan, #tahun, #kelompokbrg').on('change',function(){
    var ikodemaster = $('#ikodemaster').val();
    var dso = $('#dso').val();
    var ikodebarang = $('#ikodebarang').val();
    if (ikodebarang == "BRG" || ikodebarang == null) {
        ikodebarang = "BRG";    }
    var kelompokbrg = $('#kelompokbrg').val();
    $('#href').attr('href','<?php echo site_url($folder.'/cform/export/');?>'+ikodemaster+'/'+dso+'/'+ikodebarang+'/'+kelompokbrg);
});

function validasi() {
    var gudang = $('#ikodemaster').val();
    var dso = $('#dso').val();
    var barang = $('#ikodebarang').val();
    //alert(gudang + dso);
    if (gudang == null || dso == '' || barang == '') {
        swal('Data header Belum Lengkap');
        return false;
    } else {
        return true;
    }
}
</script>