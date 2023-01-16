<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-3">Bagian Pembuat</label>
                        <label class="col-md-4">Nomor Dokumen</label>
                        <label class="col-md-5">Tanggal Dokumen</label>
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2" disabled="">
                                <option value="<?=$data->i_bagian;?>"><?=$data->e_bagian_name;?></option>
                            </select>
                            <input type="hidden" id="id" name="id" class="form-control" value="<?= $data->id;?>">
                        </div>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" name="idocument" id="idocument" readonly=""  class="form-control input-sm" value="<?=$data->i_document;?>">
                            </div>
                        </div>
                        <div class="col-sm-2">
                             <input type="text" id= "ddocument" name="ddocument" class="form-control" value="<?= $data->d_document; ?>" placeholder="<?=date('d-m-Y');?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Pengirim</label>
                        <label class="col-md-4">Nomor Referensi</label>
                        <label class="col-md-5">Tanggal Referensi</label>
                        <div class="col-sm-3">
                            <select name="ipengirim" id="ipengirim" class="form-control select2" disabled>
                                <option value="<?= $data->i_bagian_pengirim; ?>"><?= $data->e_bagian_pengirim; ?></option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <select name="ireff" id="ireff" class="form-control select2" disabled=""> 
                                <option value="<?= $data->id_reff; ?>"><?= $data->i_reff; ?></option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id= "dreferensi" name="dreferensi" class="form-control" value="<?= $data->d_reff; ?>" required="" placeholder="<?=date('d-m-Y');?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea id= "eremark" name="eremark" class="form-control" placeholder="Isi keterangan jika ada!" readonly><?= $data->e_remark; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">                           
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                        </div>           
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
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table dark-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead> 
                    <tr>
                        <th style="text-align:center;">No</th>
                        <th style="text-align:center;">Kode Barang</th>
                        <th style="text-align:center;">Nama Barang</th>
                        <th style="text-align:center;">Warna</th>
                        <th style="text-align:center;">Quantity Keluar</th>
                        <th style="text-align:center;">Quantity Sisa</th>
                        <th style="text-align:center;">Quantity Masuk</th>
                        <th style="text-align:center;">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                        if($datadetail){
                        $i = 0;
                        $lastproduct = ''; 
                        foreach($datadetail as $row){
                            $i++;                             
                    ?>
                    <tr>   
                        <td style="text-align: center;"><?= $i;?>
                            <input style="width:10px" type="hidden" class="form-control" readonly id="baris<?= $i;?>" name="baris[]" value="<?= $i;?>">
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
                        <td>
                            <?= $row->n_quantity_qc; ?>
                        </td>
                        <td>
                            <?= $row->n_sisa; ?> 
                        </td>
                        <td>
                            <?= $row->n_quantity_masuk; ?>
                        </td>
                        <td>
                            <?=$row->e_remark;?>
                        </td>                                            
                    </tr>                       
                    <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                    <?}
                    }else{
                        $i=0;
                        $read = "disabled";
                        echo "<table class=\"table table-striped bottom\" style=\"width:100%;\"><tr><td colspan=\"16\" style=\"text-align:center;\">Maaf Tidak Ada  Bon Masuk!</td></tr></table>"; 
                    }?>        
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
        showCalendar('.date',1830,0);        
    });
</script>