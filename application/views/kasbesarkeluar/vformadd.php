<div class="row">
    <div class="col-lg-12">
        <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/proses'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> 
            </div>
                
            <div class="panel-body table-responsive">
            
            <div class="col-md-12">
                <div id="pesan"></div>
                    <div class="form-group row">
                        <label class="col-md-6">Area</label>
                        <label class="col-md-6">Tanggal</label>
                        <div class="col-sm-6">
                            <input name="eareaname" id="eareaname" class="form-control"readonly="" value="<?= $earea;?>">
                            <input type="hidden" name="iarea" id="iarea" class="form-control" required="" value="<?= $iarea; ?>">
                            <input type="hidden" name="periode" id="periode" value="<?= $periode; ?>">
                        </div>
                        <div class="col-sm-6">
                             <input name="dbank" id="dbank" class="form-control date" required="" value="<?= date('d-m-Y');?>" onchange="cektanggal();">
                        </div>
                    </div>  
                    <div class="form-group">
                    <div class="row">
                        <label class="col-md-12">Periode</label>
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
                </div>
            </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-info btn-rounded btn-sm" onclick="return validasi();"></i>Proses</button>
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
    var counter = 0;
    $("#addrow").on("click", function () {
        counter++;
        document.getElementById("jml").value = counter;
        var newRow = $("<tr>");

        var cols = "";
        cols += '<td><input  type="text" id="icolor'+ counter + '" class="form-control" name="icolor'+ counter + '"></td>';
        cols += '<td><input  type="text" id="icolor'+ counter + '" class="form-control" name="icolor'+ counter + '"></td>';
        cols += '<td><input  type="text" id="icolor'+ counter + '" class="form-control" name="icolor'+ counter + '"></td>';
        cols += '<td><input  type="text" id="icolor'+ counter + '" class="form-control" name="icolor'+ counter + '"></td>';
        cols += '<td><input  type="text" id="icolor'+ counter + '" class="form-control" name="icolor'+ counter + '"></td>';
        cols += '<td><input  type="text" id="icolor'+ counter + '" class="form-control" name="icolor'+ counter + '"></td>';
        cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>';
        newRow.append(cols);
        $("#tabledata").append(newRow);

        $('#icolor'+ counter).select2({
        placeholder: 'Pilih Warna',
        allowClear: true,
        ajax: {
          url: '<?= base_url($folder.'/cform/color'); ?>',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            return {
              results: data
            };
          },
          cache: true
        }
      });
    });

    $("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();
        counter -= 1
        document.getElementById("jml").value = counter;
    });  

function validasi(){
var s=0;
    var textinputs = document.querySelectorAll('input[type=input]'); 
    var empty = [].filter.call( textinputs, function( el ) {
       return !el.checked
    });

    if (document.getElementById('iperiodebl').value=='') {
        alert("Maaf Tolong Pilih Periode!");
        return false;
    } else if(document.getElementById('iperiodeth').value==''){
        alert("Maaf Tolong Input Periode Tahun!");
        return false;
    } else if(document.getElementById('ibank').value==''){
        alert("Maaf Tolong Pilih Bank");
        return false;
    } else if(document.getElementById('dbank').value==''){
        alert("Maaf Tolong Pilih Tanggal");
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