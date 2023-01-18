<div class="row">
    <div class="col-lg-12">
        <!-- <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?> -->
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?>
        </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/view'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-3">Date From</label><label class="col-md-3">Date To</label><label class="col-md-4">Supplier</label>
                        <div class="col-sm-3">
                            <input readonly name="dfrom" id="dfrom" class="form-control date" required="" value="<?= $dfrom; ?>">
                        </div>
                        <div class="col-sm-3">
                            <input readonly name="dto" id="dto" class="form-control date" required="" value="<?= $dto;?>">
                        </div>
                        <div class="col-sm-4">
                            <select id="isupplier" name="isupplier" class="form-control select2">
                            <?php if($isupplier == 'NA'){?>
                                <option value="<?= $isupplier; ?>">All Supplier</option>
                            <?}else{?>
                                <option value="<?= $isupplier; ?>"><?= $isupplier." - ".$esuppliername;?></option>
                                <option value="NA">All Supplier</option>
                                <?php if ($supplier) {
                                    foreach ($supplier as $key) { ?>
                                        <option value="<?= $key->i_supplier;?>"><?= $key->i_supplier." - ".$key->e_supplier_name;?></option> 
                                    <?php }
                                } ?>
                            <?}?>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <button type="submit" id="submit" class="btn btn-info"> <i class="fa fa-search"></i>&nbsp;&nbsp;Search</button>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        <div class="panel-body table-responsive">
            <div id="pesan"></div>
            <table id="tabledata" class="display nowrap" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <td colspan='13' align='left'>
                            <a href="#" id="href" onclick = "exportexcel();"><button type="button" class="btn btn-info btn-rounded btn-sm"> <i class="fa fa-download"></i>&nbsp;&nbsp;Download</button></a>&nbsp;&nbsp;&nbsp;&nbsp;
                        </td>
                    </tr>
                    <tr>
                        <th>Nomor Pajak</th>
                        <th>Kd Jenis Transaksi</th>
                        <th>FG Pengganti</th>
                        <th>Masa Faktur</th>
                        <th>Tahun Faktur</th>
                        <th>Tanggal Faktur</th>
                        <th>NPWP</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>Jumlah DPP</th>
                        <th>Jumlah PPN</th>
                        <th>Jumlah PPNBM</th>
                        <th>Act</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</form>
</div>
</div>


<script>
    $(document).ready(function () {
        datatable('#tabledata', base_url + '<?= $folder; ?>/Cform/data/<?= $dfrom ?>/<?= $dto ?>/<?= $isupplier ?>');
    });


    function exportexcel(){
        var abc = "<?php echo site_url($folder.'/cform/export/'.$dfrom.'/'.$dto); ?>";
        $("#href").attr("href",abc);
    }

    function downloadfaktur(ifaktur,isupplier,dfrom,dto){
        var lebar =1024;
        var tinggi=768;
        eval('window.open("<?php echo site_url($folder); ?>"+"/cform/download/"+ifaktur+"/"+isupplier+"/"+dfrom+"/"+dto,"","width="+lebar+"px,height="+tinggi+"px,resizable=1,menubar=1,scrollbars=1,top='+(screen.height-tinggi)/2+',left='+(screen.width-lebar)/2+'")');
    }

 $("form").submit(function(event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
});
 $(document).ready(function () {
    $(".select2").select2();
    showCalendar('.date');
 });
</script>
