<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-4">Departement</label>
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2" disabled>
                                <option value="<?= $data->i_bagian;?>"><?= $data->e_bagian_name;?></option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" readonly="" class="form-control" value="<?=$data->id;?>" >   
                                <input type="text" name="idocument" id="idocument" readonly="" class="form-control" value="<?=$data->i_document;?>">                               
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <input class="form-control datedoc" name="ddocument" id="ddocument" readonly="" required="" value="<?= date("d-m-Y"); ?>">
                        </div>
                        <div class="col-sm-4">
                            <select name="idepartement" id="idepartement" class="form-control select2" disabled>
                                <option value="<?= $data->i_departement;?>"><?= $data->e_departement_name;?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">                        
                        <label class="col-md-3">Karyawan</label>         
                        <label class="col-md-3">Jumlah</label>  
                        <label class="col-md-6">Keperluan</label>    
                        <div class="col-sm-3">
                            <select name="ikaryawan" id="ikaryawan" class="form-control select2" disabled> 
                                <option value="<?= $data->id_karyawan;?>"><?= $data->e_nama_karyawan;?></option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <input name="vjumlah" id="vjumlah" class="form-control" autocomplete="off" required="" value=<?=number_format($data->v_jumlah);?> readonly>
                        </div>  
                        <div class="col-sm-6">
                            <input name="ekeperluan" id="ekeperluan" class="form-control" value=<?=$data->e_keperluan;?> readonly>
                        </div>   
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                           <textarea id="eremark" name="eremark" class="form-control" placeholder="Isi keterangan jika ada!" readonly><?=$data->e_remark;?></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                        </div> 
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.datedoc',1800,0);
    }); 
</script>