<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nomor DO</th>
                            <th>Tanggal DO</th>
                            <th>Nomor OP</th>
                            <th>Tanggal OP</th>
                            <th>Area</th>
                            <!-- <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Jumlah</th> -->
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 0;
                        foreach ($data as $row) {
                        $i++;?>
                        <tr>
                        <td class="col-sm-1">
                            <input style ="width:40px" type="text" id="no<?=$i;?>" name="no<?=$i;?>"value="<?= $i; ?>" readonly >
                        </td>
                        <td class="col-sm-1">
                            <input style ="width:200px" type="text" id="ido<?=$i;?>" name="ido<?=$i;?>"value="<?= $row->i_do; ?>" readonly >
                        </td>
                        <td class="col-sm-1">
                            <input style ="width:100px" type="text" id="ddo<?=$i;?>" name="ddo<?=$i;?>"value="<?= $row->d_do; ?>" readonly >
                        </td>
                        <td class="col-sm-1">                               
                            <input style ="width:150px" type="text" id="iop<?=$i;?>" name="iop<?=$i;?>"value="<?= $row->i_op; ?>" readonly >
                        </td>
                        <td class="col-sm-1">
                            <input style ="width:100px" type="text" id="dop<?=$i;?>" name="dop<?=$i;?>"value="<?= $row->d_op; ?>" readonly>
                        </td>
                        <td class="col-sm-1">
                            <input style ="width:100px" type="hidden" id="ibranch<?=$i;?>" name="ibranch<?=$i;?>"value="<?= $row->i_code; ?>" >
                            <input style ="width:300px" type="text" id="ebranch<?=$i;?>" name="ebranch<?=$i;?>"value="<?= $row->e_branch_name; ?>" readonly>
                       
                            <!-- <input style ="width:300px" type="hidden" id="iproduct<?=$i;?>" name="iproduct<?=$i;?>"value="<?= $row->i_product; ?>" readonly>
                        
                            <input style ="width:300px" type="hidden" id="eproduct<?=$i;?>" name="eproduct<?=$i;?>"value="<?= $row->e_product_name; ?>" readonly>
                      
                            <input style ="width:300px" type="hidden" id="ndeliver<?=$i;?>" name="ndeliver<?=$i;?>"value="<?= $row->n_deliver; ?>" readonly>
                            <input style ="width:300px" type="hidden" id="vdogross<?=$i;?>" name="vdogross<?=$i;?>"value="<?= $row->v_do_gross; ?>" readonly> -->

                            <input style ="width:300px" type="hidden" id="icustomer<?=$i;?>" name="icustomer<?=$i;?>"value="<?= $row->i_customer; ?>" readonly>
                           <!--  <input style ="width:300px" type="hidden" id="eremark<?=$i;?>" name="eremark<?=$i;?>"value="<?= $row->e_remark; ?>" readonly> -->

                        </td>
                        <td style="width:2%;">
                            <input type="checkbox" name="cek<?php echo $i; ?>" value="cek" id="cek<?php echo $i; ?>">
                        </td> 
                        </tr>
                        <?}?>
                        <label class="col-md-12">Jumlah Data</label>
                            <input style ="width:50px" type="text" name="jml" id="jml" value="<?= $i; ?>" readonly>
                    </tbody>                
                </table>
                <div class="col-md-6">                                                   
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return validasi();"><i class="fa fa-sign-out" ></i>&nbsp;&nbsp;Transfer</button>  
                            &nbsp;&nbsp;

                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" id="checkAll" name="checkAll" class="custom-control-input">
                                <span class="custom-control-indicator"></span>
                                <span class="custom-control-description">Pilih Semua</span>
                            </label>    
                        </div>
                    </div>
                </div>
            </div>   
            </form>
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

$("#checkAll").click(function(){
    $('input:checkbox').not(this).prop('checked', this.checked);
});

function chk(ck){
    
    if(document.getElementById('cek'+ck).checked==true){
        document.getElementById('cek'+ck).value=1; 
    }else{
        document.getElementById('cek'+ck).value=0;
    }
}

function validasi(){
var s=0;
    var textinputs = document.querySelectorAll('input[type=checkbox]'); 
    var empty = [].filter.call( textinputs, function( el ) {
       return !el.checked
    });
    if (textinputs.length == empty.length) {
        swal("Maaf Tolong Pilih Minimal 1 DO!");
        return false;
    }else{
        return true
    }
}
    
</script>