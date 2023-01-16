<style type="text/css">
    .pudding{
        padding-left: 3px;
        padding-right: 3px;
    }
</style>
<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<form id="formclose"> 
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-check"></i> &nbsp; <?= $title; ?> <a href="#"
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
                    <label class="col-md-4">Kas/Bank</label>
                    <div class="col-sm-3">
                        <select class="form-control select2" name="ibagian" id="ibagian">
                            <?php foreach ($bagian as $ibagian):?>
                            <option value="<?php echo $ibagian->i_bagian;?>">
                                <?= $ibagian->e_bagian_name;?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <div class="input-group">
                        <input type="hidden" name="id" id="id" value="<?= $id;?>">
                        <input type="text" name="idocument" id="idocument" readonly="" autocomplete="off" onkeyup="gede(this);" maxlength="15" class="form-control input-sm" value="<?= $data->i_document;?>" aria-label="Text input with dropdown button">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <input class="form-control date" name="ddocument" id="ddocument" readonly="" value="<?=$data->d_document; ?>">
                    </div>
                    <div class="col-sm-4">
                        <select class="form-control select2" name="ikasbank" id="ikasbank">
                            <option value="<?=$data->id_kas_bank;?>"><?=$data->e_kas_name;?></option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">                    
                    <label class="col-md-3">Penyetor</label>
                    <label class="col-md-3">Bank</label>
                    <label class="col-md-6">No Giro</label>
                    <div class="col-sm-3">
                        <select class="form-control select2" name="ipenyetor" id="ipenyetor">
                            <option value="<?=$data->id_penyetor;?>"><?=$data->e_nama_karyawan;?></option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <select name="ibank" id="ibank" class="form-control select2" >
                            <option value="<?=$data->id_bank;?>"><?=$data->e_bank_name;?></option>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <select name="ireferensigiro" id="ireferensigiro" multiple="multiple" class="form-control select2" onchange="return getitemgiro(this.value);">
                            <?php if ($giro) {
                                foreach ($giro as $kuy) {?>
                                    <option value="<?= $kuy->id_document_reff;?>" selected><?= $kuy->i_giro;?></option>
                                <?php }
                            }?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-12">Keterangan</label>
                    <div class="col-sm-12">
                        <textarea type="text" id="eremark" name="eremark" class="form-control" value="" placeholder="Isi keterangan jika ada!"><?=$data->e_remark;?></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-12">
                        <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                        <button type="button" class="btn btn-warning btn-rounded btn-sm" onclick="statuschange('<?= $folder."','".$id;?>','1','<?= $dfrom."','".$dto;?>');"> <i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;Change Requested</button>&nbsp;
                        <button type="button" class="btn btn-danger btn-rounded btn-sm"  onclick="statuschange('<?= $folder."','".$id;?>','4','<?= $dfrom."','".$dto;?>');"> <i class="fa fa-times"></i>&nbsp;&nbsp;Reject</button>&nbsp;
                        <button type="button" id="approve" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Approve</button>&nbsp;
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
<div class="white-box" id="detail">
    <div class="col-sm-6">
        <h3 class="box-title m-b-0">Detail Barang</h3>
        <div class="m-b-0">
        </div>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nomor Giro</th>
                        <th>Tanggal Giro</th>
                        <th>Tanggal Jatuh Tempo</th>
                        <th>Penerima</th>
                        <th>Pelanggan</th>
                        <th>Jumlah</th>
                        <th>Action</th>
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
                                    <input type="hidden" value="<?= $row->id_document_reff;?>" readonly id="idgiro<?=$i;?>" class="form-control input-sm" name="idgiro<?=$i;?>">
                                    <input type="text" value="<?= $row->i_giro;?>" readonly id="igiro<?=$i;?>" class="form-control" name="igiro<?=$i;?>"style="width:150px;" >
                                </td>
                                <td>
                                    <input type="text" class="form-control" value="<?= $row->d_giro;?>" id="dgiro<?=$i;?>" name="dgiro<?=$i;?>" readonly style="width:130px;" >
                                </td>
                                <td>
                                    <input type="text" value="<?= $row->d_giro_duedate;?>" readonly id="djatuhtempo<?=$i;?>" class="form-control" name="djatuhtempo<?=$i;?>" style="width:130px;" >
                                </td>
                                <td>
                                    <input type="hidden" value="<?= $row->id_penerima;?>" readonly id="penerima<?=$i;?>" class="form-control" name="penerima<?=$i;?>">
                                    <input type="text" value="<?= $row->e_nama_karyawan;?>" readonly id="epenerima<?=$i;?>" class="form-control" name="epenerima<?=$i;?>" style="width:250px;" >
                                </td>
                                <td>
                                    <input type="hidden" value="<?= $row->id_customer;?>" readonly id="pelanggan<?=$i;?>" class="form-control" name="pelanggan<?=$i;?>">
                                    <input type="text" value="<?= $row->e_customer_name;?>" readonly id="epelanggan<?=$i;?>" class="form-control" name="epelanggan<?=$i;?>" style="width:250px;" >
                                </td>
                                <td>
                                    <input type="text" id="jumlah<?=$i;?>" class="form-control" value="<?= number_format($row->v_jumlah);?>" name="jumlah<?=$i;?>" style="width:150px;"  readonly>
                                    <input type="hidden" id="vsisa<?=$i;?>" class="form-control" value="<?= $row->v_sisa;?>" name="vsisa<?=$i;?>" style="width:150px;"  readonly>
                                </td>
                                <td>
                                    <input type="checkbox" name="cek<?=$i;?>" value="checked" id="cek<?=$i;?>" checked = true>
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
<script>
    $(document).ready(function () {
        showCalendar('.date');
        $('.select2').select2();
    });

    $('#approve').click(function(event) {
        ada = false;
        for (var i = 1; i <= $('#jml').val(); i++) {
            if (parseInt($('#jumlah'+i).val()) > parseInt($('#vsisa'+i).val())){
                swal('Dokumen Referensi sudah pernah dibuat, silahkan dicek kembali');
                $('#jumlah'+i).val($('#vsisa'+i).val());
                ada = true;
                return false;
            }
        }

        if (!ada) {
            statuschange('<?= $folder;?>',$('#id').val(),'6','<?= $dfrom."','".$dto;?>');
        }else{
            return false;
        }
    });
</script>