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
                <div class="col-md-6">
                <div id="pesan"></div>                
                </div>    
                <div class="panel-body table-responsive">
                    <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No SJ</th>
                                <th>Tanggal SJ</th>
                                <th>Customer</th>
                                <th>Jenis</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Warna</th>
                                <th>Qty</th>
                                <th>Keterangan</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?if($data){
                                $i = 0;
                                foreach($data as $row){
                                    $i++;?>
                            <tr>
                            <td class="col-sm-1">
                                <input style ="width:40px"type="text" id="no<?=$i;?>" name="no<?=$i;?>"value="<?= $i; ?>" readonly class="form-control">
                            </td>
                            <td class="col-sm-1">
                                <input style ="width:150px"type="text" id="isj<?=$i;?>" name="isj<?=$i;?>"value="<?= $row->i_sj; ?>" readonly class="form-control">
                            </td>
                            <td class="col-sm-1">
                                <input style ="width:150px"type="text" id="dsj<?=$i;?>" name="dsj<?=$i;?>"value="<?= $row->d_sj; ?>" readonly class="form-control">
                            </td>
                            <td class="col-sm-1">
                                <input style ="width:90px" type="text" id="ipenerima<?=$i;?>" name="ipenerima<?=$i;?>"value="<?= $row->i_penerima; ?>" readonly class="form-control">
                            </td>
                            <td class="col-sm-1">
                                <input style ="width:90px" type="text" id="ijenis<?=$i;?>" name="ijenis<?=$i;?>"value="<?= $row->i_jenis; ?>" readonly class="form-control">
                                <input style ="width:90px" type="text" id="ejenis<?=$i;?>" name="ejenis<?=$i;?>"value="<?= $row->e_jenis_keluar; ?>" readonly class="form-control">
                            </td>
                            <td class="col-sm-1">
                                <input style ="width:100px" type="text" id="iproduct<?=$i;?>" name="iproduct<?=$i;?>"value="<?= $row->i_product; ?>" readonly class="form-control">
                            </td>
                            <td class="col-sm-1">
                                <input style ="width:200px" type="text" id="eproductname<?=$i;?>" name="eproductname<?=$i;?>"value="<?= $row->e_product_name; ?>" readonly class="form-control">
                            </td>
                            <td class="col-sm-1">
                                <input style ="width:70px" type="text" id="icolor<?=$i;?>" name="icolor<?=$i;?>"value="<?= $row->i_color; ?>" readonly class="form-control">
                                <input style ="width:150px" type="text" id="ecolor<?=$i;?>" name="ecolor<?=$i;?>"value="<?= $row->e_color_name; ?>" readonly class="form-control">
                            </td>
                            <td class="col-sm-1">
                                <input style ="width:70px"type="text" id="qty<?=$i;?>" name="qty<?=$i;?>"value="<?= $row->n_quantity; ?>" readonly class="form-control">
                            </td>
                            <td class="col-sm-1">
                                <input style ="width:250px"type="text" id="eremark<?=$i;?>" name="eremark<?=$i;?>"value="" class="form-control">
                            </td>
                            <td class="col-sm-1">
                                    <input type="checkbox" type="text"  name="cek<?=$i;?>" id="cek" value="cek">
                            </td>
                            </tr>
                            <?}}?>
                            <label class="col-md-12">Jumlah Data</label>
                            <input style ="width:50px"type="text" name="jml" id="jml" value="<?= $i; ?>">
                        </tbody>
                    </table>               
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return validasi();"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" id="checkAll" name="checkAll" class="custom-control-input">
                                <span class="custom-control-indicator"></span>
                                <span class="custom-control-description">Pilih Semua</span>
                            </label>  
                        </div>
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
    $('.select2').select2();
    showCalendar('.date');
});

$("#checkAll").click(function(){
    $('input:checkbox').not(this).prop('checked', this.checked);
});

function validasi(){
    var s=0;
    var textinputs = document.querySelectorAll('input[type=checkbox]'); 
    var empty = [].filter.call( textinputs, function( el ) {
       return !el.checked
    });
    if (textinputs.length == empty.length) {
        swal("Maaf Tolong Pilih Minimal 1 SJ!");
        return false;
    } else {
        return true
    }
}
</script>