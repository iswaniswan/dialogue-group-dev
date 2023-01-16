<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> 
                <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
        <div class="panel-body table-responsive">
            <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
            <div class="col-md-6">
                <div id="pesan"></div>                
                    <div class="form-group">
                        <label class="col-md-12">Customer</label>
                        <div class="col-sm-12">                          
                           <input type="text" name="icustomer" class="form-control" value="<?= $data->i_customer;?>" readonly>
                           <input type="text" name="ecustomer" class="form-control" value="<?= $data->e_customer_name;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">Periode Bulan</label>
                        <label class="col-md-6">Periode Tahun</label>
                        <div class="col-sm-6">
                            <input type="text" name="ecustomer" class="form-control" value="<?= $data->periode;?>" readonly>
                             <select class="form-control select2" name="iperiodebl" disabled="">
                                <option value=""></option>
                                <option value='01' <?php if ($bln=='01') {
                                    echo "selected";} ?>>Januari</option>
                                <option value='02' <?php if ($bln=='02') {
                                    echo "selected";} ?>>Februari</option>
                                <option value='03' <?php if ($bln=='03') {
                                    echo "selected";} ?>>Maret</option>
                                <option value='04' <?php if ($bln=='04') {
                                    echo "selected";} ?>>April</option>
                                <option value='05' <?php if ($bln=='05') {
                                    echo "selected";} ?>>Mei</option>
                                <option value='06' <?php if ($bln=='06') {
                                    echo "selected";} ?>>Juni</option>
                                <option value='07' <?php if ($bln=='07') {
                                    echo "selected";} ?>>Juli</option>
                                <option value='08' <?php if ($bln=='08') {
                                    echo "selected";} ?>>Agustus</option>
                                <option value='09' <?php if ($bln=='09') {
                                    echo "selected";} ?>>September</option>
                                <option value='10' <?php if ($bln=='10') {
                                    echo "selected";} ?>>Oktober</option>
                                <option value='11' <?php if ($bln=='11') {
                                    echo "selected";} ?>>November</option>
                                <option value='12' <?php if ($bln=='12') {
                                    echo "selected";} ?>>Desember</option>
                            </select>
                        </div>
                         <div class="col-sm-6">
                            <input type="text" name="iperiodeth" class="form-control" value="<?= $thn;?>" readonly >
                        </div>
                    </div>   
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" > <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                        </div>       
                    </div> 
                    <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Kode Warna/Motif</th>                              
                                <th>Jumlah Forecast</th> 
                                <th>Sisa</th>    
                                <th>Harga</th>             
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $i = 0;
                                foreach($datadetail as $row){
                                    $i++;
                            ?>
                            <tr>          
                            <td class="col-sm-1">
                                <?php echo $i;?>
                            </td>              
                            <td class="col-sm-1">
                                <input style ="width:200px" class="form-control" type="text" id="iproduct<?=$i;?>" name="iproduct<?=$i;?>"value="<?= $row->i_product; ?>" readonly>
                            </td>
                            <td class="col-sm-1">
                                <input style ="width:200px" class="form-control" type="text" id="eproduct<?=$i;?>" name="eproduct<?=$i;?>"value="<?= $row->e_product_basename; ?>" readonly>
                            </td> 
                            <td class="col-sm-1" >  
                                <input style ="width:100px" class="form-control" type="text" id="icolor<?=$i;?>" name="icolor<?=$i;?>"value="<?= $row->i_color; ?>" readonly>
                                <input style ="width:100px" class="form-control" type="text" id="ecolor<?=$i;?>" name="ecolor<?=$i;?>"value="<?= $row->e_color_name; ?>" readonly>
                            </td>                            
                            <td class="col-sm-1" >  
                                <input style ="width:200px" class="form-control" type="text" id="nquantity<?=$i;?>" name="nquantity<?=$i;?>"value="<?= $row->n_quantity; ?>" readonly>
                            </td>
                            <td class="col-sm-1" >  
                                <input style ="width:200px" class="form-control" type="text" id="nsisa<?=$i;?>" name="nsisa<?=$i;?>"value="<?= $row->sisa; ?>">
                            </td>
                            <td class="col-sm-1" >  
                                <input style ="width:200px" class="form-control" type="text" id="vprice<?=$i;?>" name="vprice<?=$i;?>"value="<?= $row->v_price; ?>" readonly>
                            </td>
                            </tr>    
                            
                            <?}
                            ?>           
                            <input type="text" name="jml" id="jml" value="<?= $i; ?>">
                        </tbody>                         
                    </table>
            </div> 
                </form>
            </div>
        </div>
    </div>
        </div>
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

function validasi(){
    var s=0;
    var textinputs = document.querySelectorAll('input[type=input]'); 
    var empty = [].filter.call( textinputs, function( el ) {
       return !el.checked
    });
    if (textinputs.length == empty.length) {
        alert("Maaf Tolong Pilih Minimal 1 Nota!");
        return false;
    } else if(document.getElementById('dpajak').value==''){
        alert("Maaf Tolong Pilih Tanggal Faktur");
        return false;
    } else {
        return true
    }
  }
</script>