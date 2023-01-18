<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group">
                    <label class="col-md-12">No Dokumen</label>
                        <div class="col-sm-12">
                            <input type="text" id= "ibonm "name="ibonm" class="form-control" maxlength="30" value="<?= $data->i_bonm;?>"readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">No Referensi</label>
                        <div class="col-sm-12">
                            <input type="text" id= "isj "name="isj" class="form-control" maxlength="30" value="<?= $data->i_sj;?>"readonly>
                        </div>
                    </div>                                      
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">     
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>                      
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/index/","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>              
                        </div>
                    </div>
                </div>
                <div class="col-md-6"> 
                <div class="form-group">
                        <label class="col-md-12">Tanggal</label>
                        <div class="col-sm-12">
                            <input type="text" id= "dsj" name="dsj" class="form-control"  required="" value="<?= $data->d_bonm;?>"readonly>
                        </div>
                    </div>                                      
                <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <input type="text" id= "eremark "name="eremark" class="form-control" maxlength="30" value="<?= $data->e_remark;?>">
                        </div>                        
                 </div> 
                 </div>                                      
                <div class="panel-body table-responsive">
                        <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th width="20%">Kode Produk</th>
                                    <th>Nama Produk</th>
                                    <th>Warna</th>
                                    <th>Qty</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?=$i = 0;
                                foreach ($datadetail as $row) {
                                $i++;?>
                                <tr>
                                <td class="col-sm-1">
                                    <input style ="width:50px"type="text" id="no<?=$i;?>" name="no<?=$i;?>"value="<?= $i; ?>" readonly class="form-control">
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:150px"type="text" id="iproduct<?=$i;?>" name="iproduct<?=$i;?>"value="<?= $row->i_product; ?>" readonly class="form-control">
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:400px"type="text" id="eproductname<?=$i;?>" name="eproductname<?=$i;?>"value="<?= $row->e_product_name; ?>" readonly class="form-control">
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:130px"type="hidden" id="icolor<?=$i;?>" name="icolor<?=$i;?>"value="<?= $row->i_color; ?>" readonly >
                                    <input style ="width:120px"type="text" id="ecolorname<?=$i;?>" name="ecolorname<?=$i;?>"value="<?= $row->e_color_name; ?>" readonly class="form-control">
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:100px"type="text" id="nquantity<?=$i;?>" name="nquantity<?=$i;?>"value="<?= $row->n_quantity; ?>" readonly class="form-control">
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
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    $('.select2').select2();
    showCalendar('.date');
});

$(document).ready(function () {
    $(".select2").select2();
});
</script>