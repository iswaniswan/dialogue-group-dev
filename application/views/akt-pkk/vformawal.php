
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-bank"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/proses'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-6">
                            <select name="iarea" id="iarea" class="form-control select2" required=""></select>
                            <input type="hidden" name="eareaname" id="eareaname" value="">
                            <input type="hidden" name="periode" id="periode" value="<?= $periode; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Periode (Bulan / Tahun)</label>
                        <div class="col-sm-3">
                            <select name="iperiodebl" id="iperiodebl" class="form-control" required="" onchange="cektanggal();">
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
                            <select name="iperiodeth" id="iperiodeth" class="form-control" required="" onchange="cektanggal();">
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
                        <label class="col-md-12">Tanggal Kas Kecil</label>
                        <div class="col-sm-6">
                            <input name="dkk" id="dkk" readonly class="form-control date" required="" value="<?= date('d-m-Y');?>" onchange="cektanggal();">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Tanggal Bukti</label>
                        <div class="col-sm-6">
                            <input name="dbukti" id="dbukti" readonly class="form-control date" required="" value="<?= date('d-m-Y');?>" onchange="cektanggal();">
                        </div>
                    </div>
                    <div class="form-group row">
                            <div class="col-sm-offset-5 col-sm-8">
                                <button type="submit" id="submit" class="btn btn-inverse btn-rounded btn-sm"> <i class="fa fa-bank"></i>&nbsp;&nbsp;Proses</button>
                            </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
<script>
    function cektanggal(){
        var coaperiode = $('#periode').val();
        var dkk      = $('#dkk').val();
        var dbukti      = $('#dbukti').val();
        var periode    = $('#iperiodeth').val() + $('#iperiodebl').val();
        if(periode!='' && coaperiode!='' && periode.length==6){
            if( periode<coaperiode ){
                swal("Periode, Minimal = "+coaperiode+" !!!");
                $('#iperiodeth').val('');
                $('#iperiodebl').val('');
                $('#dkk').val('');
                $('#dbukti').val('');
            }else{
                if(periode!='' && dkk!='' && dbukti!=''){
                    dtmp=dkk.split('-');
                    per=dtmp[2]+dtmp[1];
                    dtmp1=dbukti.split('-');
                    per1=dtmp1[2]+dtmp1[1];
                    if( periode!=per && periode!=per1){
                        $('#dkk').val('');
                        $('#dbukti').val('');
                    }
                }
            }
        }
    }
    $(document).ready(function () {
        $(".select2").select2();
        showCalendar('.date');
    });

    $(document).ready(function () {
    $('#iarea').select2({
    placeholder: 'Pilih Area',
    allowClear: true,
    ajax: {
      url: '<?= base_url($folder.'/cform/dataarea'); ?>',
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
      var kode = $('#iarea').text();
      kode = kode.split("-");
      $('#eareaname').val(kode[1]);
    });
});
</script>