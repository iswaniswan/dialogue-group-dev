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
            <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
            <div class="col-md-6">
                <div id="pesan"></div>  
                <?php if($data){
                ?>                
                    <div class="form-group">
                        <label class="col-md-12">Supplier</label>
                        <div class="col-sm-12">                          
                           <input type="text" name="isupplier" class="form-control" value="<?= $datasup->i_supplier;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">No Faktur Pajak</label>
                        <div class="col-sm-12">
                            <input type="text" name="ipajak" class="form-control" value="" >
                        </div>
                    </div>     
                    <div class="form-group">
                        <label class="col-md-12">Tanggal Faktur Pajak</label>
                        <div class="col-sm-12">
                            <input type="text" name="dpajak" id="dpajak" class="form-control date" value="" >
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                <div id="pesan"></div> 
                    <div class="form-group">
                        <label class="col-md-12">Dasar Pengenaan Pajak (DPP)</label>
                        <div class="col-sm-12">
                            <input type="text" name="totdpp" id="totdpp" class="form-control" value="" >
                        </div>
                    </div>    
                    <div class="form-group">
                        <label class="col-md-12">Jumlah PPN</label>
                        <div class="col-sm-12">
                            <input type="text" name="totppn" id="totppn" class="form-control" value="" >
                        </div>
                    </div>   
                    <div class="form-group">
                        <label class="col-md-12">Jumlah Total</label>
                        <div class="col-sm-12">
                            <input type="text" name="totakhir" id="totakhir" class="form-control" value="">
                        </div>
                    </div>  
                </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return validasi();"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                        </div>       
                    </div> 
                
                    <div class="col-md-12">
                    <?php
                    }else{                           
                            $read = "disabled";
                            echo "<table class=\"table table-striped bottom\" style=\"width:100%;\"><tr><td colspan=\"6\" style=\"text-align:center;\">Maaf Tidak Ada Data!</td></tr></table>";
                    }?> 
                    </div>
                    <table class="table table-bordered" id="tabledata" width="100%;" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No Nota</th>
                                <th>Tanggal Nota</th>                                
                                <th>PPN</th>
                                <th>Jumlah Total (Rp.)</th>
                                <th>Action</th>                         
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if($data){
                                $i = 0;
                                foreach($data as $row){
                                    $ppn   = $row->v_total*0.1;
                                    $total = $row->v_total+$ppn;
                                    $i++;
                            ?>
                            <tr> 
                            <td class="col-sm-1">
                               <?php echo $i;?>
                            </td>                        
                            <td class="col-sm-1">
                                <input style ="width:200px" type="text" id="inota<?=$i;?>" name="inota<?=$i;?>"value="<?= $row->i_nota; ?>" readonly>
                            </td>
                            <td class="col-sm-1" >  
                                <input style ="width:200px" type="text" id="dnota<?=$i;?>" name="dnota<?=$i;?>"value="<?= $row->d_nota; ?>" readonly> 
                            </td>
                            <td class="col-sm-1" >  
                                <input style ="width:150px" type="hidden" id="ppn<?=$i;?>" name="ppn<?=$i;?>"value="<?= $ppn; ?>" readonly>
                                <input style="width:150px" name="ppnfake<?php echo $i; ?>" id="ppnfake<?php echo $i; ?>" type="text" value="<?php echo number_format($ppn,2); ?>" readonly>
                            </td>
                            <td class="col-sm-1">
                                <input style ="width:200px" type="hidden" id="vtotal<?=$i;?>" name="vtotal<?=$i;?>"value="<?= $row->v_total; ?>" readonly>
                                <input style ="width:200px" name="totalfake<?php echo $i; ?>" id="totalfake<?php echo $i; ?>" type="text" value="<?php echo number_format($total,2); ?>" readonly>
                                <input style="width:200px"  name="total<?php echo $i; ?>" id="total<?php echo $i; ?>" type="hidden" value="<?php echo $total; ?>" readonly>
                            </td>
                            <td style="width:2%;">
                                <input type="checkbox" name="cek<?php echo $i; ?>" value="cek" id="cek<?php echo $i; ?>" onclick="hitungnilai(<?php echo $i ?>)">
                            </td> 
                            </tr>    
                            <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                            <?}
                            }else{
                                $i=0;
                                $read = "disabled";                               
                                echo "<table class=\"table table-striped bottom\" style=\"width:100%;\"><tr><td colspan=\"6\" style=\"text-align:center;\">Maaf Tidak Ada Nota!</td></tr></table>";
                            ?>
                                <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/tambah","#main")'> <i
                                class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali
                                </button>
                            <?}?>           
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

function hitungnilai(i){
    var dpp = formatulang(document.getElementById('totdpp').value);
    var ppn = formatulang(document.getElementById('totppn').value);    
    var tot = formatulang(document.getElementById('totakhir').value);

    if(document.getElementById('cek'+i).checked==true)
    {
        var nilaitot = document.getElementById('total'+i).value;
        var nilaippn = document.getElementById('ppn'+i).value;
        totdpp = parseFloat(nilaitot)-parseFloat(nilaippn);
        totakhir = parseFloat(dpp)+parseFloat(totdpp);
        totppn = parseFloat(ppn)+parseFloat(nilaippn);
        grandtot = parseFloat(totdpp)+parseFloat(nilaippn);
        grandtotakhir = parseFloat(tot)+parseFloat(grandtot);
    } else {
        var nilaitot = document.getElementById('total'+i).value;
        var nilaippn = document.getElementById('ppn'+i).value;
        totdpp = parseFloat(nilaitot)-parseFloat(nilaippn);
        totakhir = parseFloat(dpp)-parseFloat(totdpp);
        totppn = parseFloat(ppn)-parseFloat(nilaippn);
        grandtot = parseFloat(totdpp)+parseFloat(nilaippn);
        grandtotakhir = parseFloat(tot)-parseFloat(grandtot);
    }
    document.getElementById('totdpp').value = formatcemua(totakhir);
    document.getElementById('totppn').value = formatcemua(totppn);
    document.getElementById('totakhir').value = formatcemua(grandtotakhir);
}

function validasi(){
    var s=0;
    var textinputs = document.querySelectorAll('input[type=checkbox]'); 
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
