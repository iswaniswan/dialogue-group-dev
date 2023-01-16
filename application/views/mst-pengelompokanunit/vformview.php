<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                    
                <div class="form-group">
                        <label class="col-md-12">Kode Kelompok</label>
                        <div class="col-sm-6">
                            <input type="text" name="ikelompokunit" class="form-control" maxlength="5"  value="<?= $data->i_kelompok_unit; ?>" readonly>
                        </div>
                    </div>  
                    <div class="form-group">
                        <label class="col-md-12">Nama Kelompok</label>
                        <div class="col-sm-6">
                            <input type="text" name="namakelompok" class="form-control" maxlength="60"  value="<?= $data->nama_kelompok; ?>" readonly>
                        </div>
                    </div>   

            <div class="panel-body table-responsive">
                <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Unit Jahit</th>
                            <th>Unit Packing</th>                            
                        </tr>
                    </thead>
                    <tbody>
                        <?$i = 0;
                            foreach ($data2 as $row) {
                            $i++;?>
                            <tr>
                            <td class="col-sm-1" >
                                <input type="text" id="id<?=$i;?>" name="id<?=$i;?>"value="<?= $row->id; ?>" readonly>                                
                            </td>
                            <td class="col-sm-1" >  
                                <input type="text" id="iunitjahit<?=$i;?>" name="iunitjahit<?=$i;?>"value="<?= $row->i_unit_jahit.'-'.$row->e_unitjahit_name; ?>" readonly>
                            </td>
                            <td class="col-sm-1">
                                <input type="text" id="unitpacking<?=$i;?>" name="unitpacking<?=$i;?>"value="<?= $row->i_unit_packing.'-'.$row->e_nama_packing; ?>" readonly>
                            </td>
                             </tr>
                            
                            <?}?>
                    </tbody>
                </table>
            </div>            
        </form>
    </div>
</div>
<script>
    
    $("form").submit(function (event) {
        event.preventDefault();
    });
   
</script>