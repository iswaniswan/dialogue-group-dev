<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
            <i class="fa fa-list"></i> <?= $title; ?>&nbsp;  <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-arrow-circle-o-left"></i>&nbsp; Kembali</a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                
                    <div class="panel-body table-responsive">
                        <table id="tabledata" class="display table" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Warna</th>
                                    <th>Grade</th>
                                    <th>Qty</th>
                                    <!-- <th>Keterangan</th> -->
            
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0;
                                    foreach ($data2 as $row) {
                                    $i++;
                                ?>
                                <tr>
                                <td class="col-sm-1">
                                    <?= $i; ?>
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:160px"type="text" id="imaterial<?=$i;?>" name="imaterial<?=$i;?>"value="<?= $row->i_product; ?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:250px"type="text" id="ematerialname<?=$i;?>" name="ematerialname<?=$i;?>"value="<?= $row->e_product_name; ?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:80px" type="text" id="esatuan<?=$i;?>" name="esatuan<?=$i;?>"value="<?= $row->e_color_name; ?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:80px" type="text" id="iunitkonv<?=$i;?>" name="iunitkonv<?=$i;?>"value="<?= $row->i_product_grade; ?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:70px"type="text" id="saldoawal<?=$i;?>" name="saldoawal<?=$i;?>"value="<?= $row->n_quantity_stock; ?>"readonly >
                                </td>
                                
                                <td>
                                <!-- <input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"> -->
                                </td>
                                </tr>
                                <? }?>
                                <!-- <label class="col-md-12">Jumlah Data</label>
                                <input style ="width:50px"type="text" name="jml" id="jml" value="<#?= $i; ?>"> -->
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

