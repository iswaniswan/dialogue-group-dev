<style type="text/css">
    .pudding{
        padding-left: 3px;
        padding-right: 3px;
    }
</style>
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
                    <label class="col-md-4">Kas/Bank</label>
                    <div class="col-sm-3">
                        <select class="form-control select2" name="ibagian" id="ibagian" disabled>
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
                        <input class="form-control" name="ddocument" id="ddocument" readonly="" value="<?=$data->d_document;?>">
                    </div>
                    <div class="col-sm-3">
                        <select class="form-control select2" name="ikasbank" id="ikasbank" disabled>
                            <option value="<?=$data->id_kas_bank;?>"><?=$data->e_kas_name;?></option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">                    
                    <label class="col-md-3">Customer</label>
                    <label class="col-md-3">Nomor Referensi</label></label>
                    <label class="col-md-6">Nomor Refrensi Giro</label>
                    <div class="col-sm-3">
                        <select class="form-control select2" name="icustomer" id="icustomer" disabled>
                            <option value="<?=$data->id_customer;?>"><?=$data->e_customer_name;?></option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <select class="form-control select2" name="ikriling" id="ikriling" disabled>
                            <option value="<?=$data->id_document_reff;?>"><?=$data->i_kliring;?></option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <select name="ireferensigiro" id="ireferensigiro" class="form-control select2" disabled>
                            <option value="<?=$data->id_giro;?>"><?=$data->i_giro;?></option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-12">Keterangan</label>
                    <div class="col-sm-12">
                        <textarea type="text" id="eremark" name="eremark" class="form-control" value="" placeholder="Isi keterangan jika ada!" readonly><?=$data->e_remark;?></textarea>
                    </div>
                </div>
            </div>
                <div class="col-md-12">
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
<div class="white-box" id="detail">
    <div class="col-sm-6">
        <h3 class="box-title m-b-0">Detail Barang</h3>
        <div class="m-b-0">
        </div>
    </div>
   <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal Kliring Giro</th>
                        <th>Tanggal Giro</th>
                        <th>Bank</th>
                        <th>Penyetor</th>
                        <th>Jumlah</th>
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
                                    <input type="hidden" readonly id="idkliring<?=$i;?>" class="form-control input-sm" name="idkliring<?=$i;?>" value="<?= $row->id_document_reff;?>">
                                    <input type="text" readonly id="ikliring<?=$i;?>" class="form-control" name="ikliring<?=$i;?>"style="width:250px;" value="<?= $row->i_kliring;?>">
                                </td>
                                <td>
                                    <input type="hidden" readonly id="idgiro<?=$i;?>" class="form-control input-sm" name="idgiro<?=$i;?>" value="<?= $row->id_giro;?>">
                                    <input type="text" class="form-control" id="igiro<?=$i;?>" name="igiro<?=$i;?>" readonly style="width:150px;" value="<?= $row->i_giro;?>">
                                </td>
                                <td>
                                    <input type="hidden" readonly id="idpenyetor<?=$i;?>" class="form-control" name="idpenyetor<?=$i;?>" value="<?= $row->id_penyetor;?>">
                                    <input type="text" readonly id="epenyetor<?=$i;?>" class="form-control" name="epenyetor<?=$i;?>" style="width:250px;" value="<?= $row->e_nama_karyawan;?>" >
                                </td>
                                <td>
                                    <input type="hidden" readonly id="idbank<?=$i;?>" class="form-control" name="idbank<?=$i;?>" value="<?= $row->id_bank;?>" >
                                    <input type="text" readonly id="ebank<?=$i;?>" class="form-control" name="ebank<?=$i;?>" style="width:250px;" value="<?= $row->e_bank_name;?>" >
                                </td>
                                <td>
                                    <input type="text" id="jumlah<?=$i;?>" class="form-control" value="<?= number_format($row->v_jumlah);?>" name="jumlah<?=$i;?>" style="width:150px;"  readonly>
                                    <input type="hidden" id="vsisa<?=$i;?>" class="form-control" value="<?= $row->v_sisa;?>" name="vsisa<?=$i;?>" style="width:150px;"  readonly>
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