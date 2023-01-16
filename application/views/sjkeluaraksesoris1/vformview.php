<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
            <div class="panel-body table-responsive">
                <div class="col-md-6">
                <div id="pesan"></div>                      
                <div class="form-group">
                    <label class="col-md-12">Tanggal Bon M Keluar</label>
                    <div class="col-sm-12">
                        <input type="hidden" name="ibonk" class="form-control" value="<?= $data->i_bonk;?>" >
                        <input type="text" name="dbonk" class="form-control date" value="<?= $data->d_bonk;?>" readonly>
                    </div>
                </div>  
                <div class="form-group">
                    <label class="col-md-12">No Schedule</label>
                    <div class="col-sm-12">
                        <input type="text" name="ischedule" class="form-control" value="<?= $dataschedule->i_schedule;?>" readonly>
                    </div>
                </div> 
                </div>
                    <div class="col-md-6">
                    <div id="pesan"></div>
                    <div class="form-group">
                        <label class="col-md-12">Gudang</label>
                        <div class="col-sm-12">
                            <input type="hidden" name="igudang" class="form-control" value="<?= $data->i_gudang;?>">
                             <input type="text" name="igudangfake" class="form-control" value="<?= $data->e_gudang_name;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <input type="text" name="eremarkh" class="form-control" maxlength="60"  value="<?= $data->e_remark;?>" readonly>
                        </div>
                    </div> 
                </div>  
            <div class="panel-body table-responsive">
                <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Warna</th>
                            <th>Qty Schedule</th>
                            <th>Qty Bon M</th>
                            <th>Saldo</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                     <?$i = 0;
                        foreach ($datadetail as $row) {
                        $i++;

                        $saldo=$row->n_quantity-$row->n_pemenuhan;
                    ?>
                    <tr>
                        <td class="col-sm-1">
                            <?php echo $i;?>
                        </td>              
                        <td class="col-sm-1">
                            <?php echo $row->i_product;?>
                        </td>
                        <td class="col-sm-1" >  
                            <?php echo $row->e_product_name;?>
                        </td>
                        <td class="col-sm-1" > 
                            <?php echo $row->warna;?>   
                        </td>
                        <td class="col-sm-1" > 
                            <?php echo $row->n_quantity;?>                      
                        </td>
                        <td class="col-sm-1">
                            <?php echo $row->n_pemenuhan;?>
                        </td>
                        <td class="col-sm-1">
                            <?php echo $saldo;?>
                        </td> 
                        <td class="col-sm-1">
                            <?php echo $row->e_remark;?>
                        </td>
                        </tr>
                        <?}?>
                        <input style ="width:50px"type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                    </tbody>
                </table>
            </div>    
        </form>
    </div>
</div>
<script>
</script>