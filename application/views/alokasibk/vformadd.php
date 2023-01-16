<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> 
            </div>
            <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
            <div class="panel-body table-responsive">
            <div class="col-md-6">
                <div id="pesan"></div>
                <div class="form-group">
                        <label class="col-md-12">Date from</label>
                        <div class="col-sm-6">
                            <input type="hidden" id= "dfrom" name="dfrom" class="form-control date"  readonly value="<?php if($dfrom) echo $dfrom; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Date to</label>
                        <div class="col-sm-6">
                            <input type="hidden" id= "dto" name="dto" class="form-control date"  readonly value="<?php if($dto) echo $dto; ?>">
                        </div>
                    </div>
                <div class="form-group">
                    <label class="col-md-12">Supplier</label>
                    <div class="col-sm-12">                           
                        <input type="hidden" name="isupplier" class="form-control" value="<?= $datasup->i_supplier;?>" readonly>
                        <input type="hidden" name="isupplierfake" class="form-control" value=" <?php if($isupplier) echo $isupplier; ?>" readonly>
                    </div>
                </div>      
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-5">
                        <button type="submit" id="submit" class="btn btn-info btn-rounded btn-sm">Simpan</button>                    
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
        $('#isupplier').select2({
        placeholder: 'Pilih Supplier',
        allowClear: true,
        ajax: {
          url: '<?= base_url($folder.'/cform/supplier'); ?>',
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
</script>