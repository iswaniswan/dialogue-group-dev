<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-3">Bagian Pembuat</label>
                        <label class="col-sm-3">Nomor Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>                       
                        <label class="col-md-2">Tanggal Kembali</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control input-sm" readonly="" name="ibagian" id="ibagian" value="<?= $data->e_bagian_name;?>">
                        </div>
                        <div class="col-sm-3">
                            <input type="hidden" name="id" id="id" value="<?= $id;?>">
                            <input type="hidden" name="isjold" id="isjold" value="<?= $data->i_document;?>">
                            <input type="text" name="idocument" id="isj" readonly="" class="form-control input-sm" value="<?= $data->i_document;?>" aria-label="Text input with dropdown button">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="ddocument" name="ddocument" class="form-control input-sm date" required="" value="<?= $data->d_document;?>" readonly>
                        </div>                        
                        <div class="col-sm-2">
                            <input type="text" id="dback" name="dback" class="form-control input-sm date" required="" value="<?= $data->d_back;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Type Makloon</label>
                        <label class="col-md-3">Partner</label>
                        <label class="col-md-4">Nomor Forecast</label>
                        <label class="col-md-2">Tanggal Forecast</label>      
                        <div class="col-sm-3">
                            <select name="itype" id="itype" class="form-control select2" required="" disabled>
                                <?php if ($type) {
                                    foreach ($type as $row):?>
                                        <option value="<?= $row->id;?>">
                                            <?= $row->e_type_makloon_name;?>
                                        </option>
                                    <?php endforeach; 
                                } ?>
                            </select>
                        </div>                  
                        <div class="col-sm-3">
                            <input type="text" class="form-control input-sm" readonly="" name="ipartner" id="ipartner" value="<?= $data->e_supplier_name;?>">
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id="iforecast" name="iforecast" class="form-control input-sm" readonly="" value="<?= $data->i_forecast;?>" required="" maxlength="15">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="dforecast" name="dforecast" class="form-control input-sm fc" value="<?= $data->d_forecast;?>" required="" readonly>
                        </div>                       
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea type="text" name="eremarkh" readonly="" class="form-control input-sm" maxlength="250"><?= $data->e_remark;?></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>
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
                        <th class="text-center" style="width: 10%;">Kode</th>
                        <th class="text-center" style="width: 45%;">Nama Barang</th>
                        <th class="text-center" style="width: 10%;">Satuan</th>
                        <th class="text-center" style="width: 10%;">Qty</th>
                        <th class="text-center">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $group = ""; foreach ($datadetail as $key) { $i++; ?>
                        <tr id="tr<?= $i;?>">
                            <?php if($group==""){?>
                                <td colspan="3" class="tdna">
                                    <?= $key->i_product_wip.' - '.$key->e_product_wipname.' - '.$key->e_color_name;?>
                                </td>
                                <td class="tdna"></td>
                                <td class="text-right tdna">
                                    <?= $key->n_quantity_wip;?>
                                </td>
                                <td class="tdna"></td>
                            <?php }else{ 
                                if($group!=$key->id_product_wip){?>
                                    <td colspan="3" class="tdna">
                                        <?= $key->i_product_wip.' - '.$key->e_product_wipname.' - '.$key->e_color_name;?>
                                    </td>
                                    <td class="text-right tdna">
                                        <?= $key->n_quantity_wip;?>
                                    </td>
                                    <td></td>
                                    <td class="tdna"></td>
                                <?php $i = 1;}
                            }?>
                        </tr>
                        <?php $group = $key->id_product_wip; ?>
                        <tr class="del<?= $i;?>">
                            <td class="text-center"><?= $i ;?></td>
                            <td><?= $key->i_material;?></td>
                            <td><?= $key->e_material_name;?></td>
                            <td><?= $key->e_satuan_name;?></td>
                            <td class="text-right"><?= $key->n_quantity;?></td>
                            <td><?= $key->e_remark;?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php } ?>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
    $(document).ready(function () {
        $('.select2').select2();
    });
</script>