<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
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
                        <label class="col-sm-3">Bagian Pembuat</label>
                        <label class="col-sm-3">Nomor Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-sm-3">Jenis Faktur</label>
                        <div class="col-sm-3">
                            <select class="form-control select2" name="ibagian" id="ibagian">
                                <option value="<?php echo $data->i_bagian;?>"><?= $data->e_bagian_name;?></option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="hidden" id="id" name="id" class="form-control" required="" readonly value="<?=$id; ?>">
                                <input type="text" id="idocument" name="idocument" class="form-control" required="" readonly value="<?=$data->i_document; ?>">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="ddocument" name="ddocument" class="form-control" required="" readonly value="<?=$data->d_document; ?>">
                        </div>
                        <div class="col-sm-3">
                            <select name="ijenis" id="ijenis" class="form-control select2">
                               <option value="<?php echo $data->i_jenis_faktur;?>"><?= $data->e_jenis_faktur_name;?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Partner</label>  
                        <label class="col-sm-2">Jumlah</label> 
                        <label class="col-sm-6"></label>                  
                        <div class="col-sm-3">
                            <select name="ipartner" id="ipartner" class="form-control select2">
                                <?php if ($partner) {
                                    foreach ($partner as $key) { ?>
                                        <option value="<?= $key->id.'|'.$key->grouppartner;?>"
                                            <?php if ($key->id==$data->id_partner) {?> selected <?php } ?>>
                                            <?= $key->e_name;?></option> 
                                    <?php }
                                } ?>  
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="jumlah" name="jumlah" class="form-control" value="<?=number_format($data->v_total,0);?>" readonly>
                            <input type="hidden" id="sisa" name="sisa" class="form-control" value="<?=number_format($data->v_sisa,0);?>" readonly>
                        </div>
                        <div class="col-sm-1"></div>           
                    </div>
                    <div class="form-group row">                       
                        <label class="col-md-12">Keterangan</label>                    
                        <div class="col-sm-12">
                            <textarea id= "eremark" name="eremark" class="form-control" readonly=""><?=$data->e_remark;?></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                    </div>
                </div>           
            </div>
        </div>
    </div>
</div>
<div class="white-box" id="detail">
    <div class="col-sm-5">
        <h3 class="box-title m-b-0">Detail</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledata" class="table color-table inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%"> 
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Nomor Faktur Penjualan</th>
                        <th class="text-center">Tanggal Faktur Penjualan</th>
                        <th class="text-center">Nomor Faktur Pajak Penjualan</th>
                        <th class="text-center">Tanggal Faktur Pajak Penjualan</th>
                        <th class="text-center">Tanggal Jatuh Tempo</th>
                        <th class="text-center">Nilai Faktur</th>
                        <th class="text-center">Sisa</th>
                        <th class="text-center">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                     <?php 
                    $i = 0;
                    if ($datadetail) {
                        foreach ($datadetail as $row) {
                            $i++;?>
                            <tr>
                                <td class="text-center"><spanx id="snum<?=$i;?>"><?= $i;?></spanx></td>
                                <td>
                                    <?= $row->i_faktur;?>
                                <td>
                                    <?= $row->d_faktur;?>
                                </td>
                                <td>
                                    <?= $row->i_pajak;?>
                                </td>
                                <td>
                                    <?= $row->d_pajak;?>
                                </td>
                                <td>
                                    <?= $row->d_jatuh_tempo;?>
                                </td>
                                <td>
                                    <?= number_format($row->v_total,0);?>
                                </td>
                                <td>
                                    <?= number_format($row->v_sisa,0);?>
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
        $('.select2').select2();
        showCalendar('.date');
    });
</script>