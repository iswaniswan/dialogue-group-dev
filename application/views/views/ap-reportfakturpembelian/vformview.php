<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> 
                <a href="#"
                    onclick="show('<?= $folder; ?>/cform/index/<?= $bulan.'/'.$tahun.'/'.$isupplier.'/'.$isupplierx.'/';?>','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i><?= $title_list; ?> </a>
            </div>
        <div class="panel-body table-responsive">
            <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?> 
            <div class="col-md-6">
                <div id="pesan"></div>  
                    <div class="form-group row">
                        <label class="col-md-4">No Faktur</label> 
                        <label class="col-md-5">Supplier</label>
                        <label class="col-md-3">Tanggal Faktur</label>
                        <div class="col-sm-4">
                            <input type="text" name="inota" id="inota" class="form-control" value="<?= $data->i_nota;?>" readonly>
                        </div>
                        <div class="col-sm-5">
                            <input type="hidden" name="isupplier" class="form-control" value="<?= $data->i_supplier;?>" readonly>
                            <input type="hidden" name="isupplierx" class="form-control" value="<?= $isupplierx;?>" readonly>
                            <input type="text" name="isupplierfake" class="form-control" value="<?= $data->e_supplier_name;?>"
                            readonly>
                            <input type="hidden" name="fsupplierpkp" id="fsupplierpkp" class="form-control"
                            value="<?= $data->f_supplier_pkp;?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <input type = "hidden" name = "bulan" value = "<?= $bulan; ?>" readonly>
                            <input type = "hidden" name = "tahun" value = "<?= $tahun; ?>" readonly>
                            <input type="text" name="dnota" id="dnota" class="form-control date" value="<?= date("d-m-Y",strtotime($data->d_nota))?>" readonly="" disabled="">
                        </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-md-4">Jenis Pembelian</label>
                    <label class="col-md-5">Diskon</label>
                    <div class="col-sm-4">
                      <!--- <input type="hidden" name="ipaymenttype" class="form-control" value="<?= $data->i_payment_type;?>" readonly> -->
                      <select name="ipaymenttype" class="form-control select2" readonly disabled="">
                        <option value="0" <?php if($data->i_payment_type =='0') { ?> selected <?php } ?>>Cash</option>
                        <option value="1" <?php if($data->i_payment_type =='1') { ?> selected <?php } ?>>Kredit</option>
                      </select>
                    </div>
                    <div class="col-sm-5">
                      <input type="text" name="vdiskon" id="vdiskon" class="form-control" value="<?= $data->v_diskon; ?>" maxlength="3"
                        onkeypress="return angka(event)" onkeyup="hitungdiskon()" readonly>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-9">Keterangan</label>
                    <div class="col-sm-9">
                      <input type="text" name="eremark" id="eremark" class="form-control" value="<?= $data->e_remark;?>" readonly>
                    </div>
                  </div>
            </div>
                <div class="col-md-6">
                  <div id="pesan"></div>
                  <div class="form-group row">
                    <label class="col-md-4">Tanggal Terima Faktur</label>
                    <label class="col-md-4">No Pajak</label>
                    <label class="col-md-4">Tanggal Pajak</label>
                    <div class="col-sm-4">
                      <input type="text" name="dreceivefaktur" id="dreceivefaktur" class="form-control date"
                        value="<?= date("d-m-Y",strtotime($data->d_terima_faktur)); ?>" readonly="" disabled="">
                    </div>
                    <div class="col-sm-4">
                      <input type="text" name="ipajak" id="ipajak" class="form-control" value="<?= $data->i_pajak;?>" readonly>
                    </div>
                    <div class="col-sm-4">
                      <input type="text" name="dpajak" id="dpajak" class="form-control date" value="<?= date("d-m-Y",strtotime($data->d_pajak)); ?>" readonly disabled="">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-md-4">Nilai Total DPP</label>
                    <label class="col-md-4">Nilai Total PPN</label>
                    <label class="col-md-4">Jumlah Total</label>
                    <div class="col-sm-4">
                      <input type="text" name="vtotaldpp" id="vtotaldpp" class="form-control" value="<?= $data->v_dpp?>" readonly>
                    </div>
                    <div class="col-sm-4">
                      <input type="text" name="vtotalppn" id="vtotalppn" class="form-control" value="<?= $data->v_ppn;?>" readonly>
                    </div>
                    <div class="col-sm-4">
                      <input type="text" name="vtotalfa" id="vtotalfa" class="form-control" value="<?= $data->v_total;?>" readonly>
                    </div>
                  </div>
                </div>        
                <div class="panel-body table-responsive">      
                   <table class="table table-bordered" id="tabledata" width="100%;" cellspacing="0"> 
                        <thead>
                            <tr>
                                <th>No</th>
                                <!--  <th>No OP</th> -->
                                <th>No BTB</th>
                                <!-- <th>No SJ</th> -->
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Qty Eks</th>
                                <th>Satuan Eks</th>
                                <th>Qty In</th>
                                <th>Satuan In</th>
                                <th>Harga</th>
                                <th>DPP</th>
                                <th>PPN</th>
                                <th>Jumlah Total (Rp.)</th>                      
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if($data1){
                                $i = 0;
                                foreach($data1 as $row){
                                    $i++;
                            ?>
                            <tr>   
                            <td class="col-sm-1">
                               <?php echo $i; ?>
                            </td>                      
                            <td class="col-sm-1">
                                <input style="width:170px" class="form-control" type="text" id="ibtb<?=$i;?>" name="ibtb<?=$i;?>" value="<?= $row->i_btb; ?>" readonly>
                                <input style="width:150px" class="form-control" type="hidden" id="isj<?=$i;?>" name="isj<?=$i;?>" value="<?= $row->i_sj; ?>" readonly>
                                <input style="width:100px" type="hidden" id="dsj<?=$i;?>" name="dsj<?=$i;?>"value="<?= $row->d_sj; ?>" readonly>
                            </td>
                             <td class="col-sm-1" >  
                                <input style="width:100px" class="form-control" type="text" id="imaterial<?=$i;?>" name="imaterial<?=$i;?>" value="<?= $row->i_material; ?>" readonly>
                            </td>
                            <td class="col-sm-1" >  
                                <input style="width:300px" class="form-control" type="text" id="ematerial<?=$i;?>" name="ematerial<?=$i;?>" value="<?= $row->e_material_name; ?>" readonly>
                                <input style="width:100px" class="form-control" type="hidden" id="isupplier<?=$i;?>" name="isupplier<?=$i;?>" value="<?= $row->i_supplier; ?>" readonly>
                            </td>
                            <td class="col-sm-1">
                                <input style ="width:70px"type="text" id="nquantityeks<?=$i;?>" name="nquantityeks<?=$i;?>" value="<?=$row->n_qty_eks;?>" class="form-control" readonly>
                            </td>
                            <td class="col-sm-1">
                                <input style="width:100px;" type="hidden" id="isatuaneks<?=$i;?>" class="form-control" name="isatuaneks<?=$i;?>" value="<?=$row->i_satuan_eks;?>" readonly>
                                <input style="width:100px;" type="text" id="esatuaneks<?=$i;?>" class="form-control" name="esatuaneks<?=$i;?>" value="<?=$row->satuaneks;?>" readonly>
                            </td>
                            <td class="col-sm-1" >  
                                <input style="width:100px" class="form-control" type="text" id="nquantity<?=$i;?>"name="nquantity<?=$i;?>" value="<?php echo number_format($row->n_quantity,2); ?>" readonly>
                            </td>
                            <td class="col-sm-1">
                                <input style="width:100px" class="form-control" type="hidden" id="isatuan<?=$i;?>" name="isatuan<?=$i;?>" value="<?= $row->i_satuan_code; ?>" readonly>
                                <input style="width:100px" class="form-control" type="text" id="esatuan<?=$i;?>" name="esatuan<?=$i;?>" value="<?= $row->e_satuan; ?>" readonly>
                            </td>
                            <td class="col-sm-1">
                                <input style="width:100px" class="form-control" type="text" id="vharga<?=$i;?>" name="vharga<?=$i;?>" value="<?= $row->v_price; ?>" readonly>
                            </td>
                            <td class="col-sm-1">
                                <input style="width:100px" class="form-control" type="text" id="vdpp<?=$i;?>" name="vdpp<?=$i;?>" value="<?= $row->v_dpp?>" readonly>
                            </td>
                            <td class="col-sm-1">
                                <input style="width:100px" class="form-control" type="text" id="vppn<?=$i;?>" name="vppn<?=$i;?>" value="<?=$row->v_ppn;?>" readonly>
                            </td>
                            <td class="col-sm-1">
                                <input style="width:150px" type="hidden" id="vtotal<?=$i;?>" name="vtotal<?=$i;?>"value="<?= $row->v_total; ?>" readonly>
                                <input style="width:150px" class="form-control" name="totalfake<?php echo $i; ?>" id="totalfake<?php echo $i; ?>" type="hidden" value="<?php echo number_format($row->v_total,2); ?>" readonly>
                                <input style="width:150px" class="form-control" type="text" id="vtotalsem<?=$i;?>" name="vtotalsem<?=$i;?>" value="<?= $row->v_total;?>" readonly>
                            </td>                
                            </tr>    
                            <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                            <?}
                            }?>           
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
    var totfak = formatulang(document.getElementById('vtotalfa').value);
    if(document.getElementById('cek'+i).checked==true){
        var nilaisj = document.getElementById('vtotal'+i).value;
        totakhir = parseFloat(totfak)+parseFloat(nilaisj);
    } else {
        var nilaisj = document.getElementById('vtotal'+i).value;
        totakhir = parseFloat(totfak)-parseFloat(nilaisj);
    }
    document.getElementById('vtotalfa').value = formatcemua(totakhir);
}

function validasi(){
    var s=0;
    var textinputs = document.querySelectorAll('input[type=checkbox]'); 
    var empty = [].filter.call( textinputs, function( el ) {
       return !el.checked
    });
    if (textinputs.length == empty.length) {
        alert("Maaf Tolong Pilih Minimal 1 SJ!");
        return false;
    } else if(document.getElementById('dnota').value==''){
        alert("Maaf Tolong Pilih Tanggal Faktur");
        return false;
    } else {
        return true
    }
  }
</script>
