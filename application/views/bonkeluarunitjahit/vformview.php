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
                        <label class="col-md-2">Nomor Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-3">Tujuan</label>    
                        <label class="col-md-2">Jenis Barang</label>  
                        <div class="col-sm-3">
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
                        <div class="col-sm-2">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" value="<?= $id;?>">
                                <input type="text" name="ibonk" id="ibonk" value="<?= $data->i_keluar_jahit;?>" class="form-control input-sm" readonly>                                
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="dbonk" name="dbonk" class="form-control input-sm"  required="" readonly value="<?= $data->d_keluar_jahit;?>">
                        </div>
                        <div class="col-sm-3">
                            <select name="itujuan" id="itujuan" class="form-control select2" disabled="">
                                <?php if ($tujuan) {
                                    foreach ($tujuan as $row):?>
                                        <option value="<?= $row->id_company.'|'.$row->i_bagian;?>" <?php if ($row->i_bagian.$row->id_company==$data->i_tujuan.$data->id_company_bagian) {?> selected <?php } ?>>
                                            <?= $row->e_bagian_name;?>
                                        </option>
                                    <?php endforeach; 
                                } ?>
                            </select>
                        </div>  
                        <div class="col-sm-2">
                            <select name="ijenisbarang" id="ijenisbarang" class="form-control select2" disabled>
                                <?php if ($jenis) {
                                    foreach ($jenis as $row):?>
                                        <option value="<?= $row->id;?>" <?php if ($row->id==$data->id_jenis_barang_keluar) {?> selected <?php } ?>>
                                            <?= $row->e_jenis_name;?>
                                        </option>
                                    <?php endforeach; 
                                } ?>
                            </select>
                        </div>  
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea id="eremark" name="eremark" class="form-control" readonly><?= $data->e_remark;?></textarea>
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
                        <th class="text-center" style="width: 15%;">Kode Barang</th>
                        <th class="text-center" style="width: 27%;">Nama Barang Jadi</th>
                        <th class="text-center" style="width: 15%;">Warna</th>
                        <th class="text-right" style="width: 10%;">Stock Jahit</th>
                        <th class="text-right" style="width: 10%;">Qty Kirim</th>
                        <th class="text-center" style="width: 30%;">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $i = 0;
                    if ($detail) {
                        foreach ($detail as $row) {
                            $i++;?>
                            <tr>
                                 <td class="text-center">
                                    <spanx id="snum<?=$i;?>"><?= $i;?></spanx>
                                </td>
                                <td>
                                   <?= $row->i_product_base;?>
                                </td>
                                <td>
                                   <?= $row->e_product_basename;?>
                                </td>
                                <td>
                                   <?= $row->e_color_name;?>
                                </td>
                                <td class="text-right">
                                   <?= $row->saldo_akhir;?>
                                </td>
                                <td class="text-right">
                                   <?= $row->n_quantity_product;?>
                                </td>
                                <td>
                                    <?= $row->e_remark;?>
                                </td>
                            </tr>
                        <?php } 
                    }?>
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
        $('.select2').select2({
            width : '100%',
        });
        showCalendar('.date');
    });
</script>