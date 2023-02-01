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
                                <option value="<?= $data->i_bagian_pengirim; ?>"><?= $data->e_bagian_name_pengirim; ?></option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <select name="ireff" id="ireff" class="form-control select2" onchange="" disabled> 
                                <option value="<?= $data->id_referensi; ?>"><?= $data->i_document_referensi; ?></option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id= "dreferensi" name="dreferensi" class="form-control input-sm" value="<?= $data->d_referensi; ?>" required="" placeholder="<?=date('d-m-Y');?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea id= "eremark" name="eremark" class="form-control" placeholder="Isi keterangan jika ada!" readonly><?= $data->e_remark; ?></textarea>
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
<input type="hidden" name="jml" id="jml">
<div class="white-box" id="detail">
    <div class="col-sm-5">
        <h3 class="box-title m-b-0">Detail Barang</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead> 
                     <tr>
                        <th width="5%" class="text-center">No</th>
                        <th width="10%">Kode Barang</th>
                        <th>Nama Barang</th>
                        <th width="10%">Satuan</th>
                        <th class="text-right" width="10%">Qty Kirim</th>
                        <!-- <th class="text-right" width="12%">Qty Pemenuhan</th> -->
                        <th class="text-right" width="10%">Qty Masuk</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                <?php if($datadetail){
                        $i = 0;
                        foreach($datadetail as $row){
                            $i++; ?>
                            <tr>   
                                <td style="text-align: center;"><?= $i;?>
                                    <input style="width:10px" type="hidden" class="form-control" readonly id="baris<?= $i;?>" name="baris[]" value="<?= $i;?>">
                                </td> 
                                <td><?= $row->i_material ?></td>
                                <td><?= $row->e_material_name ?></td>
                                <td><?= $row->e_satuan_name ?></td>
                                <td class="text-right">
                                    <?= number_format($row->n_quantity_reff_sisa, 4, ",", ".") ; ?>
                                </td>                 
                                <?php /*
                                <td class="text-right">
                                    <?= number_format($row->n_quantity_reff, 4, ",", ".") ; ?>
                                </td>
                                */ ?>
                                <td class="text-right">
                                    <?= number_format($row->n_quantity, 4, ",", ".") ; ?> 
                                </td>
                                <td>
                                    <?=$row->e_remark;?>
                                </td>                                            
                            </tr>                       
                            <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                    <?php }
                    } ?>       
                </tbody>
            </table>
        </div>
    </div>
</div>
</form>
<script>
    $(document).ready(function () {
        $('.select2').select2();
    });
</script>