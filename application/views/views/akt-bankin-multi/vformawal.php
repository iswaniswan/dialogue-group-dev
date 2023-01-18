
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
                        <div class="col-sm-12">
                            <input name="eareaname" id="eareaname" class="form-control"readonly="" value="<?= $earea;?>">
                            <input type="hidden" name="iarea" id="iarea" class="form-control" required="" value="<?= $iarea; ?>">
                            <input type="hidden" name="periode" id="periode" value="<?= $periode; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Bank</label>
                        <div class="col-sm-12">
                            <select name="ibank" id="ibank" class="form-control" onchange="bank(this.value);" required="">
                                <option value=""></option>
                                <?php if ($bank) {
                                    foreach ($bank as $key) { ?>
                                        <option value="<?= $key->i_bank;?>"><?= $key->e_bank_name;?></option> 
                                    <?php }
                                } ?>   
                            </select>
                            <input type="hidden" name="ebankname" id="ebankname" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-8">
                            <button type="submit" id="submit" class="btn btn-inverse btn-rounded btn-sm"> <i class="fa fa-bank"></i>&nbsp;&nbsp;Proses</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-12">Periode Bank Masuk (Bulan / Tahun)</label>
                        <div class="col-sm-6">
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
                        <div class="col-sm-6">
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
                        <label class="col-md-12">Tanggal</label>
                        <div class="col-sm-12">
                            <input name="dbank" id="dbank" class="form-control date" required="" value="<?= date('d-m-Y');?>" onchange="cektanggal();">
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
        showCalendar('.date');
        $('.select2').select2();
        $('#ibank').select2({
            placeholder: 'Pilih Bank',
        });
    });

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