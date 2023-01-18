<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
        <div class="panel-body table-responsive">
            <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
            <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-4">Kode Ukuran</label><label class="col-md-8">Nama Ukuran</label>
                        <div class="col-sm-4">
                            <input type="text" id="ikodeukuran" name="ikodeukuran" class="form-control" required="" maxlength="7" onkeyup="gede(this)" value="" onblur="checklength(this)">
                        </div>
                        <div class="col-sm-8">
                            <input type="text" id="enamaukuran" name="enamaukuran" class="form-control" required="" onkeyup="gede(this)" value="">
                        </div>
                    </div>   
                    <div class="form-group row">
                        <label class="col-md-12">Material</label>
                        <div class="col-sm-12">
                            <select name="imaterial" id="imaterial" class="form-control select2" >
                                <option value="">-- Pilih Jenis Kain / Bahan --</option>
                                <?php foreach ($material as $imaterial):?>
                                <option value="<?php echo $imaterial->i_material ;?>">
                                    <?= $imaterial->i_material.' - '.$imaterial->e_material_name;?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>  
                    <div class="form-group">
                        <div class="col-sm-offset-8 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                        </div>
                    </div>    
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-8">Tanggal Launching</label>
                        <div class="col-sm-4">
                            <input readonly type="hidden" id="dbuat" name="dbuat" class="form-control date" value="<?php echo date("d-m-Y"); ?>" onchange="max_tgl(this.value);">
                        </div>
                        <div class="col-sm-4">
                            <input readonly type="text" id="dberlaku" name="dberlaku" class="form-control date" value="">
                        </div>
                    </div>  
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <input type="text" id="eremark" name="eremark" class="form-control">
                        </div>
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
    showCalendar('.date');
    $(".select2").select2();
 });

 function checklength(el){
    if(el.value.length != 7){
        swal("Kode Harus 7 Karakter");
    }
 }

 $("form").submit(function (event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
});

function max_tgl(val) {
  $('#dberlaku').datepicker('destroy');
  $('#dberlaku').datepicker({
    autoclose: true,
    todayHighlight: true,
    format: "dd-mm-yyyy",
    todayBtn: "linked",
    daysOfWeekDisabled: [0],
    startDate: document.getElementById('dbuat').value,
  });
}
$('#dberlaku').datepicker({
  autoclose: true,
  todayHighlight: true,
  format: "dd-mm-yyyy",
  todayBtn: "linked",
  daysOfWeekDisabled: [0],
  startDate: document.getElementById('dbuat').value,
});
</script>
