<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> 
            </div>
            <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
            <div class="panel-body table-responsive">
            <div id="pesan"></div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-md-12">Lokasi Gudang</label>
                    <div class="col-sm-12">                           
                        <input type="hidden" name="ilokasigudang" class="form-control" value="<?= $data->i_kode_lokasi;?>" readonly>
                        <input type="text" name="ilokasigudangfake" class="form-control" value="<?= $data->e_nama_master;?>" readonly>
                         <input type="hidden" name="ikodemaster" class="form-control" value="<?= $data->i_kode_master;?>" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-12">Periode</label>
                        <div class="col-sm-6">
                            <select class="form-control select2" disabled="">
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
                        <div class="col-sm-6">
                           <select class="form-control select2" disabled="">
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
                </div>
                <div class="col-md-6">
                <div id="pesan"></div>
                    <div class="form-group">
                        <label class="col-md-12">Tanggal Pencatatan SO</label>
                        <div class="col-sm-12">
                            <input type="text" name="dso" id="dso" class="form-control date" value="" readonly>
                        </div>
                    </div>     
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-5">
                        <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return validasi();"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>                    
                    </div>
                </div>
               
           
        <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Warna</th>
                            <th>Grade</th>                                
                            <th>Jumlah SO</th>                    
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            // if($stok){
                            $i = 0;
                            foreach($data2 as $row){
                            $i++;
                        ?>
                        <tr>  
                        <td class="col-sm-3">
                            <?php echo $i;?>
                        </td>                       
                        <td class="col-sm-3">
                            <input style ="width:200px" class="form-control" type="text" id="ikodebarang<?=$i;?>" name="ikodebarang<?=$i;?>"value="<?= $row->i_product; ?>" readonly>
                        </td>
                        <td class="col-sm-3" >  
                            <input style ="width:300px" class="form-control" type="text" id="enamabarang<?=$i;?>" name="enamabarang<?=$i;?>"value="<?= $row->e_product_name; ?>" readonly> 
                        </td>
                        <td class="col-sm-3" >  
                            <input style ="width:150px" class="form-control" type="hidden" id="icolor<?=$i;?>" name="icolor<?=$i;?>"value="<?= $row->i_color; ?>" readonly>
                            <input style ="width:150px" class="form-control" type="text" id="ecolorname<?=$i;?>" name="ecolorname<?=$i;?>"value="<?= $row->e_color_name; ?>" readonly>
                            <input style ="width:150px" class="form-control" type="hidden" id="fstop<?=$i;?>" name="fstop<?=$i;?>"value="<?= $row->f_product_active; ?>" readonly>
                        </td>
                        <td class="col-sm-3" >  
                            <input style ="width:300px" class="form-control" type="text" id="iproductgrade<?=$i;?>" name="iproductgrade<?=$i;?>"value="<?= $row->i_product_grade; ?>" readonly> 
                        </td>
                        <td class="col-sm-3">
                            <input style ="width:200px" class="form-control" type="text" id="quantity<?=$i;?>" name="quantity<?=$i;?>"value="0">
                        </td>                            
                        </tr>    
                       <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                    <?}
                    // }?>
                    </tbody>                         
                </table>
            </div>
        </div>
        </form>
    </div>
</div>
<script>
$("form").submit(function (event) {
    event.preventDefault();
   
});
    
// $(document).ready(function () {
//     $(".select2").select2();
//  });

 $(document).ready(function () {
    $('.select2').select2();
    showCalendar('.date');
});
function validasi(){
// var s=0;
//     var textinputs = document.querySelectorAll('input[type=input]'); 
//     var empty = [].filter.call( textinputs, function( el ) {
//        return !el.checked
//     });

    if (document.getElementById('dso').value=='') {
        swal("Maaf Tolong Pilih Tanggal SO");
        return false;
    }else {
        return true
    }
}

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