<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?=$dfrom;?>/<?=$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-4">Bagian Pembuat</label>
                        <label class="col-md-4">Nomor Dokumen</label>
                        <label class="col-md-4">Tanggal Dokumen</label>
                        <div class="col-sm-4">
                            <select name="ibagian" id="ibagian" class="form-control select2" required="" disabled>
                                <option value="<?=$data->i_bagian;?>"><?=$data->e_bagian_name;?></option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                           <input type="text" id="idocument" name="idocument" class="form-control input-sm" value="<?= $data->i_document ;?>" readonly>
                        </div>
                        <div class="col-sm-4">
                             <input type="text" id= "ddocument" name="ddocument" class="form-control input-sm" value="<?= $data->d_document; ?>" placeholder="<?=date('d-m-Y');?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Pengirim</label>
                        <label class="col-md-4">Nomor Referensi</label>
                        <label class="col-md-4">Tanggal Referensi</label>
                        <div class="col-sm-4">
                            <select name="ipengirim" id="ipengirim" class="form-control select2" disabled>
                                <option value="<?= $data->i_bagian_pengirim; ?>"><?= $data->e_bagian_name; ?></option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <select name="ireff" id="ireff" class="form-control select2" onchange="getdataitem(this.value);" disabled> 
                                <option value="<?= $data->id_reff; ?>"><?= $data->i_reff; ?></option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id= "dreferensi" name="dreferensi" class="form-control input-sm" value="<?= $data->d_reff; ?>" required="" placeholder="<?=date('d-m-Y');?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea id= "eremark" name="eremark" class="form-control input-sm" placeholder="Isi keterangan jika ada!" readonly><?= $data->e_remark; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-12">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?=$dfrom;?>/<?=$dto;?>','#main'); return false;"><i class="ti-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;&nbsp;
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<input type="hidden" name="jml" id="jml" value="0">
<?php $i = 0; if ($datadetail) {?>
<div class="white-box" id="detail">
    <div class="col-sm-5">
        <h3 class="box-title m-b-0">Detail Barang</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead> 
                    <tr>
                        <th style="text-align:center;width:5%">No</th>
                        <th style="text-align:center;width:10%">Kode Barang</th>
                        <th style="text-align:center;width:25%">Nama Barang</th>
                        <th style="text-align:center;width:10%">Qty Keluar</th>
                        <th style="text-align:center;width:10%">Qty Sisa</th>
                        <th style="text-align:center;width:10%">Qty Masuk</th>
                        <th style="text-align:center;width:25%">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                <?php $group = ""; foreach ($datadetail as $key) { $i++; ?>
                        <tr id="tr<?= $i;?>" class="tdna">
                            <?php if($group==""){?>
                                <td colspan="3" class="tdna">
                                    <?= $key->i_product_wip.' - '.$key->e_product_wipname.' - '.$key->e_color_name;?>
                                </td>
                                <td class="text-right">
                                    <?= number_format($key->n_quantity_wip_keluar);?>
                                </td>
                                <td class="text-right">
                                    <?= number_format($key->n_quantity_wip_sisa);?>
                                </td>
                                <td class="text-right">
                                    <?= number_format($key->n_quantity_wip_masuk);?>
                                </td>
                                <td></td>
                            <?php }else{ 
                                if($group!=$key->id_product_wip){?>
                                    <td colspan="3" class="tdna">
                                        <?= $key->i_product_wip.' - '.$key->e_product_wipname.' - '.$key->e_color_name;?>
                                    </td>
                                    <td class="text-right">
                                        <?= number_format($key->n_quantity_wip_keluar);?>
                                    </td>
                                    <td class="text-right">
                                        <?= number_format($key->n_quantity_wip_sisa);?>
                                    </td>
                                    <td class="text-right">
                                        <?= number_format($key->n_quantity_wip_masuk);?>
                                    </td>
                                    <td></td>
                                <?php $i = 1;}
                            }?>
                        </tr>
                        <?php $group = $key->id_product_wip; ?>
                        <tr class="del<?= $i;?>">
                            <td class="text-center"><?= $i ;?></td>
                            <td>
                                <?= $key->i_material;?>
                            </td>
                            <td>
                                <?= $key->e_material_name;?>
                            </td>
                            <td class="text-right">
                                <?= number_format($key->n_quantity_keluar);?>
                            </td>
                            <td class="text-right">
                                <?= number_format($key->n_quantity_sisa);?>
                            </td>
                            <td class="text-right">
                                <?= number_format($key->n_quantity_masuk);?>
                            </td>
                            <td>
                                <?= $key->e_remark;?>
                            </td>
                        </tr>
                    <?php } ?>  
                </tbody>
            </table>
        </div>
    </div>
</div>
</form>
<?php } ?>
<script>
    $(document).ready(function () {
        $('.select2').select2();
        /**
        * Tidak boleh lebih dari hari ini, dan maksimal mundur 1830 hari (5 tahun) dari hari ini
        */
        showCalendar('.date',1830,0);
    });
</script>
