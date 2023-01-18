<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/approve'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-check"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?=$title_list;?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row ">
                        <label class="col-md-4">Bagian Pembuat</label>
                        <label class="col-md-4">Nomor Dokumen</label>
                        <label class="col-md-4">Tanggal Dokumen</label>
                        <div class="col-sm-4">
                            <select name="ibagian" id="ibagian" class="form-control select2" required="">
                                <option value="<?= $data->i_bagian; ?>"><?= $data->e_bagian_name; ?></option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" class="form-control" value="<?=$data->id;?>">
                                <input type="text" name="iretur" id="iretur" readonly class="form-control" value="<?= $data->i_document; ?>">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="dretur" name="dretur" class="form-control input-sm" value="<?= $data->d_document; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Supplier</label>
                        <label class="col-md-4">Nomor Referensi</label><!-- dari Nota faktur jasa makloon -->
                        <label class="col-md-4">Tanggal Referensi</label>
                        <div class="col-sm-4">
                            <select name="isupplier" id="isupplier" class="form-control select2" onchange="getnota(this.value);" disabled>
                                <option value="<?= $data->id_supplier; ?>"><?= $data->e_supplier_name; ?></option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <select name="ifaktur" id="ifaktur" class="form-control select2" onchange="getdetail(this.value);" disabled>
                                <option value="<?= $data->id_referensi_nota; ?>"><?= $data->i_document_referensi; ?></option>
                            </select>
                            <input type="hidden" id="idtypemakloon" name="idtypemakloon" class="form-control" value="<?= $data->id_type_makloon; ?>" readonly>
                            <input type="hidden" id="groupmakloon" name="groupmakloon" class="form-control" value="<?= $data->groupmakloon; ?>">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id= "dnota" name="dnota" class="form-control" placeholder="<?=date('d-m-Y');?>" value="<?= $data->d_referensi; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">    
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea type="text" id="eremark" name="eremark" class="form-control" value="" placeholder="Isi keterangan jika ada!" readonly><?= $data->e_remark; ?></textarea>
                            <input type="hidden" id="istatus" name="istatus" class="form-control" value="<?= $data->i_status; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                            <button type="button" class="btn btn-warning btn-rounded btn-sm" onclick="statuschange('<?= $folder."','".$data->id;?>','1','<?= $dfrom."','".$dto;?>');"> <i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;Change Requested</button>&nbsp;
                            <button type="button" class="btn btn-danger btn-rounded btn-sm"  onclick="statuschange('<?= $folder."','".$data->id;?>','4','<?= $dfrom."','".$dto;?>');"> <i class="fa fa-times"></i>&nbsp;&nbsp;Reject</button>&nbsp;
                            <button type="submit" id="approve" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Approve</button>&nbsp;
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
            <table id="tabledatax" class="table color-table inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead> 
                    <tr>
                        <th style="text-align:center;">No</th>
                        <th style="text-align:center;">Kode Barang</th>
                        <th style="text-align:center;">Nama Barang</th>
                        <th style="text-align:center;">Satuan</th>
                        <th style="text-align:center;">Qty</th>
                        <th style="text-align:center;">Qty Retur</th>
                        <th style="text-align:center;">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        if($detail){
                            $i=0;
                            $group = "";
                            foreach($detail as $row){$i++; 
                                if($group == ""){?>
                                    <tr class="pudding">
                                        <td colspan="7">Nomor SJ : <b><?= $row->i_reff; ?></b></td>
                                    </tr>
                                <?}else{
                                    if($group != $row->id_reff){?>
                                        <tr class="pudding">
                                            <td colspan="7">Nomor SJ : <b><?= $row->i_reff; ?></b></td>
                                        </tr>
                                    <?}
                                }
                                $group = $row->id_reff;
                            ?>
                                <tr>
                                    <td>
                                        <?=$i;?>
                                    </td>
                                    <td>
                                        <input type="hidden" class="form-control" name="idereffsj<?=$i;?>" id="idreffsj<?=$i;?>" value="<?=$row->id_reff;?>" readonly>
                                        <input type="hidden" class="form-control" name="idmaterial<?=$i;?>" id="idmaterial<?=$i;?>" value="<?=$row->id_material;?>" readonly>
                                        <input type="text" class="form-control" name="imaterial<?=$i;?>" id="imaterial<?=$i;?>" value="<?=$row->i_material;?>" readonly>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="ematerialname<?=$i;?>" id="ematerialname<?=$i;?>" value="<?=$row->e_material_name;?>" readonly>
                                    </td>
                                    <td>
                                        <input type="hidden" class="form-control" name="isatuan<?=$i;?>" id="isatuan<?=$i;?>" value="<?=$row->i_satuan_code;?>" readonly>
                                        <input type="text" class="form-control" name="esatuan<?=$i;?>" id="esatuan<?=$i;?>" value="<?=$row->e_satuan_name;?>" readonly>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="nquantityreff<?=$i;?>" id="nquantityreff<?=$i;?>" value="<?=$row->n_sisa;?>" readonly>
                                        <input type="hidden" class="form-control" name="nsisareff<?=$i;?>" id="nsisareff<?=$i;?>" value="<?=$row->n_sisa;?>" readonly>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="nretur<?=$i;?>" id="nretur<?=$i;?>" value="<?=$row->n_retur;?>" readonly>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="edesc<?=$i;?>" id="edesc<?=$i;?>" value="<?=$row->e_remark;?>" readonly>
                                    </td>
                                </tr>
                            <?}
                        }
                    ?>
                </tbody>
                <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">
            </table>
        </div>
    </div>
</div>
</form>
<script>
    $(document).ready(function(){
        $('.select2').select2();
    });
    $('#cancel').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'1','<?= $dfrom."','".$dto;?>');
    });

    $('#approve').click(function(event) {
        ada = false;
        for (var i = 1; i <= $('#jml').val(); i++) {
            if (parseInt($('#nretur'+i).val()) > parseInt($('#nsisareff'+i).val())) {
                swal('Dokumen Referensi sudah pernah dibuat, silahkan dicek kembali');
                $('#nretur'+i).val($('#nsisareff'+i).val());
                ada = true;
                return false;
            }
        }
    });
</script>