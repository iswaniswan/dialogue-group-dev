<?= $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;&nbsp;<?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-3">Tanggal Dokumen</label>
                        <label class="col-md-3">Tujuan</label>    
                        <div class="col-sm-3">
                            <input type="hidden" name="xbagian" id="xbagian" value="<?= $data->i_bagian;?>">
                            <select name="ibagian" id="ibagian" class="form-control select2" disabled="">
                                <?php if ($bagian) {
                                    foreach ($bagian as $row):?>
                                        <option value="<?= $row->i_bagian;?>" <?php if ($row->i_bagian==$data->i_bagian) {?> selected <?php } ?>>
                                            <?= $row->e_bagian_name;?>
                                        </option>
                                    <?php endforeach; 
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" value="<?= $id;?>">                                
                                <input type="text" name="iretur" id="iretur" readonly="" class="form-control input-sm" value="<?= $data->i_retur;?>">                                
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="dretur" name="dretur" class="form-control input-sm"  required="" readonly value="<?= $data->d_retur;?>">
                        </div>
                        <div class="col-sm-3">
                            <select name="itujuan" id="itujuan" class="form-control input-sm select2" disabled="">
                                <?php if ($tujuan) {
                                    foreach ($tujuan as $row):?>
                                        <option value="<?= $row->i_bagian;?>" <?php if ($row->i_bagian==$data->i_tujuan) {?> selected <?php } ?>>
                                            <?= $row->e_bagian_name;?>
                                        </option>
                                    <?php endforeach; 
                                } ?>
                            </select>
                        </div>  
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea id="eremark" name="eremark" class="form-control input-sm" readonly><?= $data->e_remark;?></textarea>
                        </div>
                    </div>                   
                    <div class="row">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="white-box" id="detail">
    <div class="col-sm-5">
        <h3 class="box-title m-b-0">Detail Barang</h3>
        <div class="m-b-0">
        </div>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 3%;">No</th>
                        <th style="width: 15%;">Kode Barang</th>
                        <th style="width: 27%;">Nama Barang Jadi</th>
                        <th style="width: 15%;">Warna</th>
                        <th class="text-right" style="width: 10%;">Quantity</th>
                        <th style="width: 30%;" colspan="4">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $i = 0;
                    $group = '';
                    if ($datadetail) {
                        foreach ($datadetail as $row) {
                            $i++;if ($group != $row->id_product) { ?>
                                <tr>
                                    <td class="text-center">
                                        <spanx id="snum<?= $i; ?>"><?= $i; ?></spanx>
                                    </td>
                                    <td>
                                        <?= $row->i_product_base; ?>
                                    </td>
                                    <td>
                                        <?= $row->e_product_basename; ?>
                                    </td>
                                    <td>
                                        <?= $row->e_color_name; ?>
                                    </td>
                                    <td class="text-right">
                                        <?= $row->n_quantity; ?>
                                    </td>
                                    <td colspan="4">
                                        <?= $row->e_remark; ?>
                                    </td>
                                </tr>
                                <tr class="th<?= $i; ?> bold table-active">
                                    <td class="text-center"><i class="fa fa-hashtag fa-lg" aria-hidden="true"></i></td>
                                    <td>Kode Material</td>
                                    <td>Nama Material</td>
                                    <td>Satuan</td>
                                    <td class="text-right">Kebutuhan<br>Per PCs</td>
                                    <td class="text-right">Qty Total</td>
                                    <td class="text-right">Qty Rusak</td>
                                    <td class="text-right">Qty Bagus</td>
                                    <td class="text-center"><i class="fa fa-list-ul fa-lg" aria-hidden="true"></i></td>
                                </tr>
                            <?php }
                            $group = $row->id_product;
                            ?>
                            <tr>
                                <td class="text-center"><i class="fa fa-check-circle-o fa-lg text-info" aria-hidden="true"></i></td>
                                <td><?= $row->i_material;?></td>
                                <td><?= $row->e_material_name;?></td>
                                <td><?= $row->e_satuan_name;?></td>
                                <td class="text-right"><?= $row->n_kebutuhan;?></td>
                                <td class="text-right"><?= $row->n_quantity_total;?></td>
                                <td class="text-right"><?= $row->n_quantity_rusak;?></td>
                                <td class="text-right"><?= $row->n_quantity_bagus;?></td>
                                <td class="text-center"><i class="fa fa-thumbs-o-up fa-lg text-success" aria-hidden="true"></i></td>
                            </tr>
                    <?php }
                    } ?>
                    <input type="hidden" name="jml" id="jml" value ="<?= $i;?>">
                </tbody>
            </table>
        </div>
    </div>
</div>
</form>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
   $(document).ready(function () {
        showCalendar('.date');
        $('.select2').select2({
            width : '100%',
        });
    });
</script>