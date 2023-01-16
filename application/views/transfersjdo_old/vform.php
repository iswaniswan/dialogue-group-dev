<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> 
            </div>
            <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/list'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
            <div class="panel-body table-responsive">
            <div class="col-md-6">
                <div id="pesan"></div>
                <div class="form-group row">
                    <label class="col-md-3">Date From</label>
                    <label class="col-md-9">Date To</label>
                    <div class="col-sm-3">
                        <input type="text" id= "dfrom1" name="dfrom1" class="form-control date" required value="" readonly>
                    </div>
                    <div class="col-sm-3">
                        <input type="text" id= "dto1" name="dto1" class="form-control date" required value="" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Bank</label>
                    <div class="col-sm-6">                           
                        <select name="ibank" id="ibank" class="form-control select2"> 
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
        $('#ibank').select2({
        placeholder: 'Pilih Bank',
        allowClear: true,
        ajax: {
          url: '<?= base_url($folder.'/cform/bank'); ?>',
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

    if (document.getElementById('dfrom1').value=='') {
        swal("Maaf Tolong Pilih Date From!");
        return false;
    }else if(document.getElementById('dto1').value=='') {
        swal("Maaf Tolong Pilih Date to!");
        return false;
    }else if(document.getElementById('ibank').value=='') {
        swal("Maaf Tolong Pilih Bank!");
        return false;
    }else {
        return true
    }
}    
</script>