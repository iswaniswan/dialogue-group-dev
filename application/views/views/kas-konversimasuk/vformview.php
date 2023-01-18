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
                        <label class="col-md-3">No Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-3">Kas/Bank</label>
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2" required="" disabled>
                                <option value="<?=$data->i_bagian;?>"><?=$data->e_bagian_name;?></option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" readonly="" class="form-control" value="<?=$data->id;?>"> 
                                <input type="text" name="idocument" id="idocument" readonly="" autocomplete="off" class="form-control" value="<?=$data->i_document;?>" aria-label="Text input with dropdown button">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id= "ddocument" name="ddocument" class="form-control" value="<?=$data->d_document;?>" required="" readonly>
                        </div>
                        <div class="col-sm-3">
                            <select name="ikasbank" id="ikasbank" class="form-control select2" disabled> 
                                <option value="<?=$data->id_kas_bank;?>"><?=$data->e_kas_name;?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">                     
                        <label class="col-md-3">Partner</label>                        
                        <label class="col-md-3">Nomor Referensi</label>
                        <label class="col-md-2">Tanggal Referensi</label>
                        <label class="col-md-2">Sisa Nilai Referensi</label> 
                        <div class="col-sm-3">
                            <select name="ipartner" id="ipartner" class="form-control select2" disabled> 
                            <?php if ($partner) {
                                foreach ($partner as $kuy) {?>
                                    <option value="<?= $kuy->id_partner.'|'.$kuy->e_partner_type;?>" selected><?= $kuy->e_partner_name;?></option>
                                <?php }
                            }?>
                            </select>
                        </div>   
                       <div class="col-sm-3">
                            <select name="ireferensi" id="ireferensi" class="form-control select2" disabled> 
                                <option value="<?= $data->id_document_reff.'|'.$data->dreferensi.'|'.$data->nilai_ref;?>" selected><?= $data->referensi;?></option>
                            </select>
                        </div>   
                        <div class="col-sm-2">
                            <input class="form-control" name="dreferensi" id="dreferensi" value="<?=$data->dreferensi;?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input class="form-control" name="vnilai" id="vnilai" value="<?=$data->n_sisa;?>" readonly>
                            <input type="hidden" class="form-control" name="vnilai_a" id="vnilai_a" value="<?=$data->n_nilai;?>"readonly>
                            <input type="hidden" class="form-control" name="vnilaiold" id="vnilaiold" value="<?=$data->n_nilai_old;?>" readonly>
                        </div>                     
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">Customer</label>
                        <label class="col-md-6">Keterangan</label>
                        <div class="col-sm-6">
                            <select name="icustomer" id="icustomer" class="form-control select2" multiple="multiple" onchange="return getitemcustomer(this.value);" disabled>
                            <?php if ($customer) {
                                foreach ($customer as $ada) {?>
                                    <option value="<?= $ada->id_customer;?>" selected><?= $ada->e_customer_name;?></option>
                                <?php }
                            }?>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <textarea id= "eremark" name="eremark" class="form-control" placeholder="Isi keterangan jika ada!" readonly><?=$data->e_remark;?></textarea>
                        </div>
                    </div>
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
<div class="white-box" id="detail" >
    <div class="col-sm-5">
        <h3 class="box-title m-b-0">Detail</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead> 
                    <tr>
                        <th style="text-align:center;">No</th>
                        <th style="text-align:center;">Kode Customer</th>
                        <th style="text-align:center;">Nama Customer</th>
                        <th style="text-align:center;">Nilai</th>
                        <th style="text-align:center;">Keterangan</th>
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
                                    <?= $row->i_customer;?>
                                </td>
                                <td>
                                    <?= $row->e_customer_name;?>
                                </td>
                                <td>
                                    <?= $row->n_nilai;?>
                                </td>
                                <td>
                                    <?= $row->e_remark;?>
                                </td>
                            </tr>
                        <?php } 
                    }?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<input type="hidden" name="jml" id="jml" value ="<?= $i;?>" readonly>
</form>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
    $(document).ready(function () {
        $('.select2').select2();
    });
</script>