<div class="row">
    <div class="col-lg-12">
        <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/proses'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> 
            </div>
                
            <div class="panel-body table-responsive">            
            <div class="col-md-6">
                <div id="pesan"></div>
                    <div class="form-group row">
                        <label class="col-md-6">Area</label>
                        <label class="col-md-6">Bank</label>
                        <div class="col-sm-6">
                            <input name="eareaname" id="eareaname" class="form-control"readonly="" value="<?= $earea;?>">
                            <input type="hidden" name="iarea" id="iarea" class="form-control" required="" value="<?= $iarea; ?>">
                            <input type="hidden" name="periode" id="periode" value="<?= $periode; ?>">
                        </div>
                         <div class="col-sm-6">
                           <select name="ibank" id="ibank" class="form-control select2" onchange="bank(this.value);" required="">
                                <option value="">Pilih Bank</option>
                                <?php if ($bank) {
                                    foreach ($bank as $key) { ?>>
                                        <option value="<?= $key->i_bank;?>"><?= $key->e_bank_name;?></option> 
                                    <?php }
                                } ?>   
                            </select>
                            <input type="hidden" name="ebankname" id="ebankname" value="">
                        </div>
                    </div>  
                    <div class="form-group row">
                        <label class="col-md-6">Tanggal</label>
                        <label class="col-md-6">Periode</label>
                        <div class="col-sm-6">
                             <input name="dbank" id="dbank" class="form-control date" required="" value="<?= date('d-m-Y');?>" onchange="cektanggal();" reaonly>
                        </div>
                        <div class="col-sm-3">
                            <select name="iperiodebl" id="iperiodebl" class="form-control select2" onchange="cektanggal();">
                                <option value=""></option>
                                <option value='01' <?php if ($bulan=='01') {
                                    echo "selected";} ?>>Januari</option>
                                <option value='02' <?php if ($bulan=='02') {
                                    echo "selected";} ?>>Februari</option>
                                <option value='03' <?php if ($bulan=='03') {
                                    echo "selected";} ?>>Maret</option>
                                <option value='04' <?php if ($bulan=='04') {
                                    echo "selected";} ?>>April</option>
                                <option value='05' <?php if ($bulan=='05') {
                                    echo "selected";} ?>>Mei</option>
                                <option value='06' <?php if ($bulan=='06') {
                                    echo "selected";} ?>>Juni</option>
                                <option value='07' <?php if ($bulan=='07') {
                                    echo "selected";} ?>>Juli</option>
                                <option value='08' <?php if ($bulan=='08') {
                                    echo "selected";} ?>>Agustus</option>
                                <option value='09' <?php if ($bulan=='09') {
                                    echo "selected";} ?>>September</option>
                                <option value='10' <?php if ($bulan=='10') {
                                    echo "selected";} ?>>Oktober</option>
                                <option value='11' <?php if ($bulan=='11') {
                                    echo "selected";} ?>>November</option>
                                <option value='12' <?php if ($bulan=='12') {
                                    echo "selected";} ?>>Desember</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="iperiodeth" id="iperiodeth" class="form-control select2" required="" onchange="cektanggal();">
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
                            <button type="submit" id="submit" class="btn btn-inverse btn-rounded btn-sm" onclick="return validasi();"><i class="fa fa-spinner"></i>&nbsp;&nbsp;Proses</button>
                        </div>               
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

    $("form").submit(function (event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
    });

function validasi(){
var s=0;
    var textinputs = document.querySelectorAll('input[type=input]'); 
    var empty = [].filter.call( textinputs, function( el ) {
       return !el.checked
    });

    if (document.getElementById('iperiodebl').value=='') {
        swal("Maaf Tolong Pilih Periode!");
        return false;
    } else if(document.getElementById('iperiodeth').value==''){
        swal("Maaf Tolong Input Periode Tahun!");
        return false;
    } else if(document.getElementById('ibank').value==''){
        swal("Maaf Tolong Pilih Bank");
        return false;
    } else if(document.getElementById('dbank').value==''){
        swal("Maaf Tolong Pilih Tanggal");
        return false;
    } else {
        return true
    }
  }

  function cektanggal(){
        var coaperiode = $('#periode').val();
        var dbank      = $('#dbank').val();
        var periode    = $('#iperiodeth').val() + $('#iperiodebl').val();
        if(periode!='' && coaperiode!='' && periode.length==6){
            if( periode<coaperiode ){
                swal("Periode, Minimal = "+coaperiode+" !!!");
                $('#iperiodeth').val('');
                $('#iperiodebl').val('');
                $('#dbank').val('');
            }else{
                if(periode!='' && dbank!=''){
                    dtmp=dbank.split('-');
                    per=dtmp[2]+dtmp[1];
                    if( periode!=per ){
                        swal("Periode, Harus = Tanggal Bank !!!");
                        $('#dbank').val('');
                    }
                }
            }
        }
    }

    function bank() {
       var ebankname = $('#ibank option:selected').text();
       $('#ebankname').val(ebankname);
    }

</script>