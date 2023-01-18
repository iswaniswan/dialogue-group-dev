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
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-3">Kas/Bank</label>
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2" disabled>
                                <option value="<?=$data->i_bagian;?>"><?=$data->e_bagian_name;?></option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" readonly="" class="form-control" value="<?=$data->id;?>"> 
                                <input type="text" name="idocument" id="idocument" readonly="" autocomplete="off" onkeyup="gede(this);" class="form-control" value="<?=$data->i_document;?>" aria-label="Text input with dropdown button">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id= "ddocument" name="ddocument" class="form-control" value="<?=$data->d_document;?>" required="" readonly>
                        </div>
                        <div class="col-sm-3">
                            <select name="ikasbank" id="ikasbank" class="form-control select2" disabled> 
                                <option value="<?=$data->id_kas_bank.'|'.$data->i_bank;?>"><?=$data->e_kas_name;?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">                     
                        <label class="col-md-3">Bank</label>
                        <label class="col-md-5">Customer</label>
                        <label class="col-md-4">Nilai</label>   
                        <div class="col-sm-3">
                            <?php if($data->id_bank == 0 || $data->id_bank == null){?>
                            <select name="ibank" id="ibank" class="form-control select2" disabled> 
                            </select>
                            <?}else{?>
                            <select name="ibank" id="ibank" class="form-control select2" disabled> 
                                <option value="<?=$data->id_bank;?>"><?=$data->e_bank_name;?></option>
                            </select>
                            <?}?>
                        </div>   
                        <div class="col-sm-5">
                            <select name="icustomer" id="icustomer" multiple="multiple" class="form-control select2" disabled>
                            <?php if ($customer) {
                                foreach ($customer as $kuy) {?>
                                    <option value="<?= $kuy->id_customer;?>" selected><?= $kuy->e_customer_name;?></option>
                                <?php }
                            }?>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <input class="form-control" name="vnilai" id="vnilai" readonly value="<?=$data->n_nilai;?>">
                        </div>                     
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea id= "eremark" name="eremark" class="form-control" placeholder="Isi keterangan jika ada!" readonly><?=$data->e_remark;?></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                            <button type="button" class="btn btn-warning btn-rounded btn-sm" onclick="statuschange('<?= $folder."','".$data->id;?>','1','<?= $dfrom."','".$dto;?>');"> <i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;Change Requested</button>&nbsp;
                            <button type="button" class="btn btn-danger btn-rounded btn-sm"  onclick="statuschange('<?= $folder."','".$data->id;?>','4','<?= $dfrom."','".$dto;?>');"> <i class="fa fa-times"></i>&nbsp;&nbsp;Reject</button>&nbsp;
                            <button type="button" id="approve" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Approve</button>&nbsp;
                        </div>
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
            <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
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
                                    <input type="hidden" readonly id="idcustomer<?=$i;?>" class="form-control input-sm" name="idcustomer<?=$i;?>" value="<?= $row->id_customer;?>">
                                    <input type="text" readonly id="icustomer<?=$i;?>" class="form-control" name="icustomer<?=$i;?>"style="width:150px;" value="<?= $row->i_customer;?>">
                                </td>
                                <td>
                                    <input type="text" class="form-control" id="ecustomer<?=$i;?>" name="ecustomer<?=$i;?>" readonly style="width:350px;" value="<?= $row->e_customer_name;?>">
                                </td>
                                <td>
                                    <input type="text" id="v_nilai<?=$i;?>" class="form-control" name="v_nilai<?=$i;?>" style="width:200px;" value="<?= $row->n_nilai;?>" readonly>
                                </td>
                                <td>
                                    <input type="text" id="edesc<?=$i;?>" class="form-control" name="edesc<?=$i;?>" style="width:400px;" value="<?= $row->e_remark;?>" readonly>
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

    $('#approve').click(function(event) {
        ada = false;
        if (!ada) {
            statuschange('<?= $folder;?>',$('#id').val(),'6','<?= $dfrom."','".$dto;?>');
        }else{
            return false;
        }
    });
</script>