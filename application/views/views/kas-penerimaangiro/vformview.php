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
                        <label class="col-md-3">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-3">Giro</label>
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2" required="" disabled="">
                               <option value="<?=$data->i_bagian;?>"><?=$data->e_bagian_name;?></option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                             <div class="input-group">
                                <input type="hidden" name="id" id="id" readonly="" class="form-control" value="<?=$data->id;?>" >   
                                <input type="text" name="idocument" id="idocument" readonly="" class="form-control" value="<?=$data->i_document;?>" >                                
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <input class="form-control" name="ddocument" id="ddocument" readonly="" required="" value="<?= $data->d_document; ?>">
                        </div>
                        <div class="col-sm-3">
                            <input class="form-control" name="igiro" id="igiro" value="<?= $data->i_giro; ?>" readonly>
                        </div> 
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2">Tgl Giro</label>
                        <label class="col-md-2">Tgl Jatuh Tempo</label>
                        <label class="col-md-2">Tgl Setor</label>
                        <label class="col-md-2">Tgl Terima</label>
                        <label class="col-md-3">Penerima</label>
                        <div class="col-sm-2">
                            <input class="form-control" name="dgiro" id="dgiro" readonly="" required="" value="<?= $data->d_giro; ?>">
                        </div>
                        <div class="col-sm-2">
                            <input class="form-control" name="dgiroduedate" id="dgiroduedate" readonly="" required="" value="<?= $data->d_giro_duedate; ?>">
                        </div>
                        <div class="col-sm-2">
                            <input class="form-control" name="dsetor" id="dsetor" required="" value="<?= $data->d_setor; ?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input class="form-control" name="dgiroterima" id="dgiroterima" readonly="" required="" value="<?= $data->d_giro_terima; ?>">
                        </div>
                        <div class="col-sm-3">
                            <select name="ikaryawan" id="ikaryawan" class="form-control select2" disabled="">
                                <option value="<?=$data->id_karyawan;?>"><?=$data->e_nama_karyawan;?></option>
                            </select>
                        </div>      
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Area</label>
                        <label class="col-md-3">Customer</label>
                        <label class="col-md-3">Bank</label>            
                        <label class="col-md-2">Jumlah</label>                      
                        <div class="col-sm-3">
                            <select name="iarea" id="iarea" class="form-control select2" disabled="">
                                <option value="<?=$data->id_area;?>"><?=$data->e_area;?></option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="icustomer" id="icustomer" class="form-control select2" disabled=""> 
                                <option value="<?=$data->id_customer;?>"><?=$data->e_customer_name;?></option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="ibank" id="ibank" class="form-control select2" disabled="">
                                <option value="<?=$data->id_bank;?>"><?=$data->e_bank_name;?></option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <input name="vjumlah" id="vjumlah" class="form-control" autocomplete="off" required="" onkeyup="reformat(this);" value="<?= number_format($data->v_jumlah); ?>" readonly>
                        </div>   
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                           <textarea id="eremarkh" name="eremarkh" class="form-control" placeholder="Isi keterangan jika ada!" readonly=""><?= $data->e_remark; ?></textarea>
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
        showCalendar('.date');
    });
</script>