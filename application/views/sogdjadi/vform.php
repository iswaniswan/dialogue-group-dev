<div class="row">
    <div class="col-lg-12">
        <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/add'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> 
            </div>
                
            <div class="panel-body table-responsive">            
          
            <?php if ($msg != '') echo "<i>".$msg."</i><br><br>"; ?>
                <div id="pesan"></div>
                <div class="form-group">
                    <label class="col-md-6">Lokasi Gudang</label>
                    <div class="col-sm-4">
                        <select name="ilokasigudang" id="ilokasigudang" class="form-control select2" > 
                        </select>
                        <input type="hidden" name="ikodemaster" id="ikodemaster" class="form-control">
                    </div>
                </div> 
                <div class="form-group row">
                        <label class="col-md-12">Periode</label>
                        <div class="col-sm-2">
                        <select name="iperiodebl" id="iperiodebl" class="form-control select2">
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
                        <div class="col-sm-2">
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
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-info btn-rounded btn-sm" onclick="return validasi();"></i>Proses</button>
                        </div>               
                    </div>  
                     </div>     
        </form>
    </div>
</div>
<script>
$(document).ready(function () {
    $(".select2").select2();
 });

 $(document).ready(function () {
    $('.select2').select2();
    showCalendar('.date');
});


$(document).ready(function () {
        $('#ilokasigudang').select2({
        placeholder: 'Pilih lokasi',
        allowClear: true,
        ajax: {
          url: '<?= base_url($folder.'/cform/lokasigudang'); ?>',
          dataType: 'json',
          delay: 250,          
          processResults: function (data) {
            return {
              results: data
            };
          },
          cache: true
        }
      }).on("change", function(e) {
        var kode = $('#ilokasigudang').text();
        kode = kode.split("-");
        $('#ikodemaster').val(kode[1]);
     });
});

function validasi(){
var s=0;
    var textinputs = document.querySelectorAll('input[type=input]'); 
    var empty = [].filter.call( textinputs, function( el ) {
       return !el.checked
    });

    if (document.getElementById('ilokasigudang').value=='') {
        swal("Maaf Tolong Pilih Lokasi Gudang!");
        return false;
    } else if(document.getElementById('iperiodebl').value==''){
        swal("Maaf Tolong Pilih Periode Bulan!");
        return false;
    }else {
        return true
    }
}


function kodemaster(id){
        var ilokasigudang = $('#ilokasigudang'+id).val();
        $.ajax({
        type: "post",
        data: {
            'ilokasigudang': ilokasigudang
        },
        url: '<?= base_url($folder.'/cform/getkodemaster'); ?>',
        dataType: "json",
        success: function (data) {
            $('#ikodemaster'+id).val(data[0].i_kode_master);
        },
        error: function () {
            alert('Error :)');
        }
    });
    }
</script>
