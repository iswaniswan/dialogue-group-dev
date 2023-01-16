<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                     <div class="form-group row">
                        <label class="col-sm-3">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-3">Tanggal Dokumen</label>
                        <label class="col-md-3">Pengirim</label>
                        <div class="col-sm-3">
                            <!-- <select name="ibagian" id="ibagian" class="form-control select2" required="" disabled="">
                                <option value="<?=$data->i_bagian;?>"><?=$data->e_bagian_name;?></option>
                            </select> -->
                            <input type="hidden" id="id" name="id" class="form-control" value="<?= $id;?>">
                            <input type="text" id= "e_bagian" name="ibagian" class="form-control input-sm" value="<?=$data->e_bagian_name;?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" name="idocument" id="idocument" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="SJ-2010-000001" maxlength="15" class="form-control input-sm" value="<?=$data->i_document;?>" aria-label="Text input with dropdown button">
                            </div>
                        </div>
                        <div class="col-sm-3"> 
                             <input type="text" id= "ddocument" name="ddocument" class="form-control input-sm" value="<?= $data->d_document; ?>" placeholder="<?=date('d-m-Y');?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id= "e_tujuan" name="itujuan" class="form-control input-sm" value="<?=$data->e_tujuan_name;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Nomor Referensi</label>
                        <label class="col-md-3">Tanggal Referensi</label>
                        <label class="col-md-6">Keterangan</label>
                        <div class="col-sm-3">
                            <!-- <select name="ireff" id="ireff" class="form-control select2" onchange="getdataitem(this.value);" disabled> 
                                <option value="<?= $data->id_document_reff; ?>"><?= $data->i_reff; ?></option>
                            </select> -->
                            <input type="hidden" id= "idreff" name="idreff" class="form-control" value="<?= $data->id_document_reff; ?>">
                            <input type="text" id= "reff" name="reff" class="form-control input-sm" value="<?= $data->i_reff; ?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id= "dreferensi" name="dreferensi" class="form-control input-sm" value="<?= $data->d_reff; ?>" required="" placeholder="<?=date('d-m-Y');?>" readonly>
                        </div>
                        <div class="col-sm-6">
                            <textarea id= "eremark" name="eremark" class="form-control" placeholder="Isi keterangan jika ada!" readonly><?= $data->e_remark; ?></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?=$dfrom;?>/<?=$dto;?>','#main'); return false;"><i class="ti-arrow-circle-left mr-2"></i>Kembali</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
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
                        <th class="text-center">No</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th class="text-right">Qty (Pengembalian)</th>
                        <!-- <th class="text-right">Qty Sisa Retur</th> -->
                        <th class="text-right">Qty</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $z = 0; $group = ""; foreach ($datadetail as $key) { $i++; ?>
                        <tr class="del<?= $i;?>">
                            <td class="text-center"><?= $i ;?></td>
                            <td>
                                <?= $key->i_product_wip;?>
                            </td>
                            <td>
                                <?= $key->e_product_wipname;?>
                            </td>
                            <td class="text-right">
                                <?= $key->n_quantity_wip_keluar;?>                                
                            </td>
                            <!-- <td class="text-right">
                                <?= $key->n_quantity_wip_sisa;?>
                            </td> -->
                            <td class="text-right">
                                <?= $key->n_quantity_wip_masuk;?>
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
<input type="hidden" name="jml" id="jml" value ="<?= $i ;?>">
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