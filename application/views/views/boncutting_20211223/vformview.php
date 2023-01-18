<style type="text/css">
    .font{
        font-size: 16px;
        background-color: #e1f1e4;
    }

    .tdna{
        font-size:16px; background-color: #ddd; font-weight: bold;
    }
</style>
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
                        <label class="col-md-3">Bagian Pembuat</label>
                        <label class="col-md-3">No. Dokumen</label>
                        <label class="col-md-2">Tgl. Dokumen</label>
                        <label class="col-md-4">Dokumen Referensi</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control input-sm" readonly="" value="<?= $data->e_bagian_name;?>">
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" value="<?= $id;?>">
                                <input type="text" readonly="" class="form-control input-sm" value="<?= $data->i_document;?>">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" class="form-control input-sm date" value="<?= $data->d_document;?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <select disabled="" multiple="multiple" class="form-control input-sm select2">
                                <?php if ($referensi) {
                                    foreach ($referensi->result() as $key) {?>
                                        <option value="<?= $key->id;?>" selected><?= $key->i_document;?></option>
                                    <?php }
                                } ?>
                            </select>
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-3">Bagian Tujuan</label>
                        <label class="col-md-9">Keterangan</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control input-sm" readonly="" value="<?= $data->e_bagian_tujuan;?>">
                        </div>
                        <div class="col-sm-9">
                            <textarea type="text" readonly="" class="form-control input-sm"><?= $data->e_remark;?></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>
                    </div>
                </div>           
            </div>
        </div>
    </div>
</div>
<?php $i = 0; if ($detail) {?>
<div class="white-box" id="detail">
    <div class="col-sm-12">
        <h3 class="box-title m-b-0">Detail Barang</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 3%;">No</th>
                        <th class="text-center" style="width: 10%;">Kode</th>
                        <th class="text-center" style="width: 30%;">Nama Material</th>
                        <th class="text-center" style="width: 8%;">Gelar</th>
                        <th class="text-center" style="width: 8%;">Set</th>
                        <th class="text-center" style="width: 10%;">Jml Gelar</th>
                        <th class="text-center" style="width: 12%;">Jml Pemenuhan</th>
                        <th class="text-center" style="width: 12%;">Sisa</th>
                        <th class="text-center" style="width: 12%;">Jml Lembar</th>
                        <th class="text-center">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 0; $group = ''; foreach ($detail as $key) { $no++; ?>
                        <?php if($group==""){?>
                            <tr class="tdna">
                                <td colspan="5"><?= $key->i_document.' - '.$key->i_product_wip.' - '.$key->e_product_wipname.' '.$key->e_color_name;?></td>
                                <td class="text-right">Jml WIP</td>
                                <td class="text-right"><?= $key->qtywip_pemenuhan;?></td>
                                <td class="text-right"><?= $key->qtysisawip;?></td>
                                <td class="text-right"><?= $key->qtywip;?></td>
                                <td></td>
                            </tr>
                        <?php }else{ 
                            if($group!=$key->id_schedule.$key->id_product_wip){?>
                                <tr class="tdna">
                                <td colspan="5"><?= $key->i_document.' - '.$key->i_product_wip.' - '.$key->e_product_wipname.' '.$key->e_color_name;?></td>
                                <td class="text-right">Jml WIP</td>
                                <td class="text-right"><?= $key->qtywip_pemenuhan;?></td>
                                <td class="text-right"><?= $key->qtysisawip;?></td>
                                <td class="text-right"><?= $key->qtywip;?></td>
                                <td></td>
                            </tr>
                                <?php $no = 1;}
                            }?>
                            <?php $group = $key->id_schedule.$key->id_product_wip; ?>
                            <tr>
                                <td class="text-center"><?= $no;?></td>
                                <td><?= $key->i_material;?>
                                    <input class="form-control input-sm" readonly type="hidden" id="imaterial<?= $i ;?>" name="imaterial<?= $i ;?>" value="<?= $key->i_material;?>">
                                </td>
                                <td><?= $key->e_material_name;?></td>
                                <td class="text-right"><?= $key->n_gelar;?></td>
                                <td class="text-right"><?= $key->n_set;?></td>
                                <td class="text-right"><?= number_format($key->n_jumlah_gelar,2);?></td>
                                <td class="text-right"><?= $key->qtyma_pemenuhan;?></td>
                                <td class="text-right"><?= $key->qtysisama;?></td>
                                <td class="text-right"><?= $key->qtyma;?></td>
                                <td>
                                    <?= $key->e_remark;?>
                                    <input type="hidden" id="qtysc<?= $i ;?>" name="qtysc<?= $i ;?>" value="<?= $key->qtywip;?>">
                                </td>
                            </tr>
                            <?php $i++; 
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php } ?>
<input type="hidden" name="jml" id="jml" value ="<?= $i ;?>">
<script>   
    /*----------  LOAD SAAT DOKUMEN READY  ----------*/
    $(document).ready(function () {
        $('.select2').select2();
    }); 
</script>