<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-6">
                            <input type="text" readonly id="eareaname" name="eareaname" class="form-control" value="<?= $eareaname;?>">
                            <input type="hidden" name="periode" id="periode" value="<?= $iperiode; ?>">
                            <input type="hidden" name="iarea" id="iarea" value="<?= $iarea ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Periode</label>
                        <!-- <label class="col-md-6">Bulan</label><label class="col-md-6">Tahun</label> -->
                        <div class="col-sm-3">
                            <select class="form-control" disabled="">
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
                            <input type="hidden" name="iperiodebl" id="iperiodebl" value="<?= $bulan;?>">
                        </div>
                        <div class="col-sm-3">
                            <select class="form-control" disabled="">
                                <option value=""></option>
                                <?php 
                                $tahun1 = date('Y')-3;
                                $tahun2 = date('Y');
                                for($i=$tahun1;$i<=$tahun2;$i++){ ?>
                                    <option value="<?= $i;?>" <?php if ($tahun==$i) {
                                    echo "selected";} ?>><?= $i;?></option>
                                <?php } ?>
                            </select>
                            <input type="hidden" name="iperiodeth" id="iperiodeth" value="<?= $tahun;?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-6">
                            <input type="text" id="edescription" name="edescription" class="form-control" value="Terima dari pusat">
                        </div>
                    </div>                        
                    <div class="form-group row"></div>   
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales();"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan
                            </button>&nbsp;&nbsp;
                            <button type="button" id="print" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-print"></i>&nbsp;&nbsp;Cetak
                            </button>&nbsp;&nbsp;                                
                            <button type="button" class="btn btn-primary btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/proses/<?= $iarea."/".$eareaname."/".$tanggal."/".$bulan."/".$tahun."/"; ?>","#main");'><i class="fa fa-refresh"></i>&nbsp;&nbsp;Reload
                            </button>&nbsp;&nbsp;                               
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-6">Tanggal Kas Kecil</label><label class="col-md-6">Saldo</label>
                        <div class="col-sm-6">
                            <input readonly name="dkk" id="dkk" class="form-control" value="<?php if($tanggal) echo $tanggal; ?>">
                        </div>
                        <div class="col-sm-6">
                            <input readonly name="vsaldo" id="vsaldo" class="form-control" value="<?php if($saldo) echo number_format($saldo); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Tanggal Bukti</label>
                        <div class="col-sm-6">
                            <input type="text" readonly id="dbukti" name="dbukti" class="form-control" value="<?= $dbukti;?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Jumlah</label>
                        <div class="col-sm-6">
                            <input type="text" id="vkk" name="vkk" class="form-control" value="0" onkeyup="reformat(this);">
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
        $(".select2").select2();
        showCalendar('.date');
    });
    function getcoa(i){
        var icoa = $('#icoa'+i).val();
        $.ajax({
            type: "post",
            data: {
                'icoa': icoa
            },
            url: '<?= base_url($folder.'/cform/getcoa'); ?>',
            dataType: "json",
            success: function (data) {
                $("#ecoaname"+i).val(data[0].e_coa_name);      
            },
            error: function () {
                alert('Error :)');
            }
        });
    }

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#addrow").attr("disabled", true);
    });

    function hanyaAngka(evt) {      
        var charCode = (evt.which) ? evt.which : event.keyCode      
        if (charCode > 31 && (charCode < 48 || charCode > 57))        
            return false;    
        return true;
    }

    function dipales(){
		if(
			(document.getElementById("iperiodeth").value=='') ||
			(document.getElementById("iperiodebl").value=='') ||
			(document.getElementById("iarea").value=='')||
			(document.getElementById("dkk").value=='')
		  ){
			alert("Data Header belum lengkap !!!");
		}else{			
			document.getElementById("login").disabled=true;
		}
	}
</script>