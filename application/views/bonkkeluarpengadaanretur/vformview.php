<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-3">Tujuan</label>    
                        <div class="col-sm-3">
                            <input type="hidden" name="xbagian" id="xbagian" value="<?= $data->i_bagian;?>">
                            <select name="ibagian" id="ibagian" class="form-control select2" disabled>
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
                                <input type="text" name="ibonk" id="ibonk" class="form-control" value="<?= $data->i_keluar_pengadaan_retur;?>" readonly>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="dbonk" name="dbonk" class="form-control date"  required="" readonly value="<?= $data->d_keluar_pengadaan;?>">
                        </div>
                        <div class="col-sm-3">
                            <select name="itujuan" id="itujuan" class="form-control select2" disabled>
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
                        <label class="col-md-12">Keterangan Retur</label>
                        <div class="col-sm-11">
                            <textarea id="eremark" name="eremark" class="form-control" readonly><?= $data->e_remark;?></textarea>
                        </div>   
                    </div>    
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-12">
                                <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                            </div>
                        </div>
                    </div>        
            </div>
    </div>
</div>

<?php $i = 0; if ($datadetail) {?>
<div class="white-box" id="detail">
    <div class="col-sm-3">
        <h3 class="box-title m-b-0">Detail Barang</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 3%;">No</th>
                        <th class="text-center" style="width: 55%;">Nama Barang</th>
                        <th class="text-center" style="width: 10%;">Qty</th>
                        <th class="text-center">Alasan Retur</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $group = ""; foreach ($datadetail as $key) { $i++; ?>
                        <tr id="tr<?= $i;?>" class="tdna">
                            <?php if($group==""){;?>
                                <td  class="tdna">
                                    <?= $i;?>
                                </td>
                                <td colspan="1" class="tdna">
                                    <?= $key->e_product_wipname.' - '.$key->e_color_name;?>
                                </td>
                                <td class="text-right">
                                   <?= $key->n_quantity_wip;?>
                                </td>
                                <td>
                                    <?= $key->e_remark_wip;?>
                                </td>
                            <?php }else{ 
                                if($group!=$key->id_product_wip){;?>
                                    <td  class="tdna">
                                        <?= $i;?>
                                    </td>
                                    <td colspan="1" class="tdna">
                                        <?= $key->e_product_wipname.' - '.$key->e_color_name;?>
                                    </td>
                                    <td class="text-right">
                                       <?= $key->n_quantity_wip;?>
                                    </td>
                                    <td>
                                        <?= $key->e_remark_wip;?>
                                    </td>
                                <?php $i = 1;}
                            }?>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php } ?>
<script>
    $(document).ready(function () {
        $('.select2').select2({
        });
    });
</script>