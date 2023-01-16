<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/approve'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                 <i class="fa fa-check"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-3">Bagian Pembuat</label>
                        <label class="col-md-3">No Dokumen</label>
                        <label class="col-md-6">Tanggal Dokumen</label>
     
                        <div class="col-sm-3">
                            <input type="text" readonly="" class="form-control input-sm" value="<?= $head->e_bagian_name;?>">
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" readonly="" class="form-control input-sm" value="<?= $head->i_document;?>">
                            </div>
                        </div>
                        <div class="col-sm-2">
                              <input type="text" name="ddocument" id="ddocument" class="form-control" value="<?= $head->d_document;?>" readonly="">
                        </div>
                    </div>
                    <div class="form-group row">                     
                        <label class="col-md-4">Kas/Bank Asal</label>
                        <label class="col-md-4">Nilai</label>   
                        <label class="col-md-4">Kas/Bank Tujuan</label>
                        <div class="col-sm-4">
                            <input type="text" readonly="" class="form-control input-sm" value="<?= $head->e_kas_nameaw.' ('.$head->e_coa_nameaw.')';?>">
                        </div>
                        <div class="col-sm-4">
                            <input type="text" class="form-control text-right" name="vnilai" id="vnilai" value="<?= number_format($head->v_nilai);?>" onblur='if(this.value==""){this.value="0";}' onfocus='if(this.value=="0"){this.value="";}' onkeyup="angkahungkul(this); reformat(this);" readonly>
                        </div>  
                        <div class="col-sm-4">
                            <input type="text" readonly="" class="form-control input-sm" value="<?= $head->e_kas_nameak.' ('.$head->e_coa_nameak.')';?>">
                        </div>
                                          
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea id="eremark" name="eremark" class="form-control" placeholder="Isi keterangan jika ada!" readonly><?= $head->e_remark;?></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                            <button type="button" class="btn btn-warning btn-rounded btn-sm" onclick="statuschange('<?= $folder."','".$id;?>','1','<?= $dfrom."','".$dto;?>');"> <i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;Change Requested</button>&nbsp;
                            <button type="button" class="btn btn-danger btn-rounded btn-sm"  onclick="statuschange('<?= $folder."','".$id;?>','4','<?= $dfrom."','".$dto;?>');"> <i class="fa fa-times"></i>&nbsp;&nbsp;Reject</button>&nbsp;
                            <button type="button" class="btn btn-success btn-rounded btn-sm" onclick="approve();"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Approve</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</form>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
    function approve() {
        statuschange('<?= $folder."','".$id;?>','6','<?= $dfrom."','".$dto;?>');   
    }
</script>