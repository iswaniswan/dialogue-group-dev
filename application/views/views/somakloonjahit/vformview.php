<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-check"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <?if ($data){?>
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-4">Nomor SO</label><label class="col-md-6">Periode</label>
                        <div class="col-sm-4">
                         <input type="text" name="ikodeso" id="ikodeso" class="form-control" value="<?= $data->i_stok_opname_makloonjahit;?>" readonly>
                        </div>
                        <div class="col-sm-2">
                         <input type="text" name="periode" id="periode" class="form-control" value="<?= $data->i_periode;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">Partner Jahit</label> <label class="col-md-4">Tanggal Terakhir SO</label>
                        <div class="col-sm-6">
                            <input type="hidden" name="partner" id="partner" class="form-control" value="<?= $data->partner;?>" readonly>   
                            <input type="text" name="epartner" id="epartner" class="form-control" value="<?= $data->e_unitjahit_name;?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id="dso" name="dso" class="form-control" readonly value="<?= $data->d_so;?>" readonly>
                        </div>
                    </div>

                    <!-- <div class="form-group row">
                        <label class="col-md-4">Bulan</label>
                        <label class="col-md-8">Tahun</label>
                        <div class="col-sm-4">
                            <select class="form-control select2" disabled="" readonly>
                                <option value=""></option>
                                <option value='01' <?php /*if ($bulan=='01') {
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
                                    echo "selected";} */?>>Desember</option>
                            </select>
                            <input type="hidden" name="dbulan" id="dbulan" class="form-control" value="<?= $bulan;?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" name="dtahun" id="dtahun" class="form-control" value="<?= $tahun;?>" readonly>
                        </div> -->
                    <!-- </div> -->
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-10">  
                        <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                    </div>
                    </div>
                </div>
                <?}else{
                    $read = "disabled";
                    echo "<table class=\"table table-striped bottom\" style=\"width:100%;\"><tr><td colspan=\"16\" style=\"text-align:center;\">Data Kosong!</td></tr></table>"; 
                    ?>
                    <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>  
                 <?}?>
                    <div class="panel-body table-responsive">
                        <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="3%">No</th>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Warna</th>
                                    <th>Saldo Awal</th>
                                    <th>Saldo Akhir</th>
                                    <th>Stok Opname</th>
                                    <th>Selisih</th> 
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $i = 0;
                                    foreach ($datadetail as $row) {
                                    $i++;
                                    $so = $row["so"];
                                    $sa = $row["saldoakhir"];
                                    $total = $so-abs($sa);
                                ?>
                                <tr>
                                <td style="text-align: center;width:60px" >
                                    <input style="width:60px" type="text" class="form-control" readonly id="baris<?= $i;?>" name="baris<?= $i;?>" value="<?= $i;?>">
                                </td>
                                <td class="col-sm-1">
                                    <input style="width:100px"type="text" class="form-control" id="iwip<?=$i;?>" name="iwip<?=$i;?>"value="<?= $row["kodewip"]; ?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:400px"type="text" class="form-control" id="ewip<?=$i;?>" name="ewip<?=$i;?>"value="<?= $row["barangwip"];?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:150px" type="text" class="form-control" id="ecolor<?=$i;?>" name="ecolor<?=$i;?>" value="<?= $row["ecolor"]; ?>" readonly >
                                    <input style ="width:90px" type="hidden" class="form-control" id="icolor<?=$i;?>" name="icolor<?=$i;?>" value="<?= $row["icolor"]; ?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:100px"type="text" class="form-control" id="saldoawal<?=$i;?>" name="saldoawal<?=$i;?>"value="<?= $row["saldoawal"]; ?>"readonly >
                                </td>
                                
                                <td class="col-sm-1">
                                    <input style ="width:100px"type="text" class="form-control" id="saldoakhir<?=$i;?>" name="saldoakhir<?=$i;?>"value="<?= $row["saldoakhir"]; ?>" readonly>
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:100px"type="number" class="form-control" id="stokopname<?=$i;?>" name="stokopname<?=$i;?>"value="<?= $row["so"]; ?>"  readonly
                                    onfocus="if(this.value=='0'){this.value='';}">
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:100px"type="text" class="form-control" id="selisih<?=$i;?>" name="selisih<?=$i;?>"value="<?= $total; ?>" readonly>
                                </td>
                                <?}?>
                                </tr>
                                <input style ="width:50px"type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
$(document).ready(function () {
    $('.select2').select2();
    showCalendar('.date');
    //cekselisih();
});

 $("form").submit(function (event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("#submit").attr("disabled", true);
});

function cekselisih(){
    var jml = $('#jml').val();
    for(var i=1; i<=jml; i++){
        var saldoakhir = $('#saldoakhir'+i).val();
        var stokopname = $('#stokopname'+i).val();

        total = stokopname-Math.abs(saldoakhir);
        $('#selisih'+i).val(formatcemua(total));

    }
}

</script>