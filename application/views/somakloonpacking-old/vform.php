<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> 
            </div>
            <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/view'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
            <div class="panel-body table-responsive">
            <div class="col-md-6">
                <div id="pesan"></div>
                <div class="form-group">
                    <label class="col-md-12">Periode SO</label>
                    <div class="col-sm-6">
                        <input type="text" id= "dso" name="dso" class="form-control date" required value="" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Unit Packing</label>
                    <div class="col-sm-6">                           
                        <select name="iunitpacking" id="iunitpacking" class="form-control select2"> 
                        </select>
                    </div>
                </div> 
                <div class="form-group">
                    <label class="col-md-12">Gudang</label>
                    <div class="col-sm-6">                           
                        <select name="igudang" id="igudang" class="form-control select2"> 
                        </select>
                    </div>
                </div>      
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-5">
                        <button type="submit" id="submit" class="btn btn-inverse btn-rounded btn-sm" onclick="return validasi();"> <i class="fa fa-table"></i>&nbsp;&nbsp;View</button>                    
                    </div>
                </div>
                <input type="hidden" name="jml" id="jml">
            </div>
        </div>
    </div>
        </form>
    </div>
</div>
<script>
$("form").submit(function (event) {
    event.preventDefault();
});
    
$(document).ready(function () {
    $(".select2").select2();
});

 $(document).ready(function () {
    $('.select2').select2();
    showCalendar('.date');
});

$(document).ready(function () {
    $('#iunitpacking').select2({
    placeholder: 'Pilih Unit Packing',
    allowClear: true,
    ajax: {
      url: '<?= base_url($folder.'/cform/unitpacking'); ?>',
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

$(document).ready(function () {
    $('#igudang').select2({
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

function validasi(){
var s=0;
    var textinputs = document.querySelectorAll('input[type=input]'); 
    var empty = [].filter.call( textinputs, function( el ) {
       return !el.checked
    });

    if (document.getElementById('dso').value=='') {
        swal("Maaf Tolong Pilih Periode SO!");
        return false;
    }else if(document.getElementById('iunitpacking').value=='') {
        swal("Maaf Tolong Pilih Unit Packing!");
        return false;
    }else if(document.getElementById('igudang').value=='') {
        swal("Maaf Tolong Pilih Gudang!");
        return false;
    }else {
        return true
    }
}    
</script>