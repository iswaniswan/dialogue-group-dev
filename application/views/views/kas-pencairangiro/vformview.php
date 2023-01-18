<style type="text/css">
    .pudding{
        padding-left: 3px;
        padding-right: 3px;
    }
</style>
<form id="formclose"> 
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
                        <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;                        
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
                                    <?= $row->i_kliring;?>
                                </td>
                                <td>
                                    <?= $row->i_giro;?>
                                </td>
                                <td>
                                    <?= $row->e_nama_karyawan;?>
                                </td>
                                <td>
                                    <?= $row->e_bank_name;?>
                                </td>
                                <td>
                                    <?= number_format($row->v_jumlah);?>
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
        showCalendar('.date', 1830, 0);
        $('.select2').select2();
    });  
</script>