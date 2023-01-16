<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-check"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-3">Bagian Pembuat</label>
                        <label class="col-sm-3">Nomor Dokumen</label>
                        <label class="col-md-6">Tanggal Dokumen</label>
                        <div class="col-sm-3">
                            <input type="text" readonly="" class="form-control input-sm" name="ibagian" id="ibagian" value="<?= $data->e_bagian_name;?>">
                            <input type="hidden" name="ibagianold" id="ibagianold" value="<?= $data->i_bagian;?>">
                        </div>                        
                        <div class="col-sm-3">
                            <input type="hidden" name="id" id="id" value="<?= $id;?>">
                            <input type="hidden" name="isjold" id="isjold" value="<?= $data->i_document;?>">
                            <input type="text" name="idocument" id="isj" required="" readonly="" class="form-control input-sm" value="<?= $data->i_document;?>" aria-label="Text input with dropdown button">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="ddocument" name="ddocument" class="form-control input-sm date" required="" value="<?= $data->d_document;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3">Bagian Tujuan</label>
                        <label class="col-sm-3">Nomor Referensi</label>
                        <label class="col-sm-6">Tanggal Referensi</label>
                        <div class="col-sm-3">
                            <input type="text" readonly="" class="form-control input-sm" name="ipartner" id="ipartner" value="<?= $data->e_bagian_tujuan;?>">
                        </div>
                        <div class="col-sm-3">
                            <select name="imemo" id="imemo" class="form-control select2" disabled="true">
                                <option value="<?= $data->id_document_reff; ?>"><?= $data->i_referensi; ?></option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="dmemo" name="dmemo" class="form-control" value=" <?= $data->d_referensi; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea type="text" name="eremarkh" readonly="" class="form-control input-sm" maxlength="250"><?= $data->e_remark;?></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                            <button type="button" class="btn btn-warning btn-rounded btn-sm" onclick="statuschange('<?= $folder."','".$data->id;?>','1','<?= $dfrom."','".$dto;?>');"> <i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;Change Requested</button>&nbsp;
                            <button type="button" class="btn btn-danger btn-rounded btn-sm"  onclick="statuschange('<?= $folder."','".$data->id;?>','4','<?= $dfrom."','".$dto;?>');"> <i class="fa fa-times"></i>&nbsp;&nbsp;Reject</button>&nbsp;
                            <button type="button" class="btn btn-success btn-rounded btn-sm" onclick="statuschange('<?= $folder."','".$data->id;?>','6','<?= $dfrom."','".$dto;?>');"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Approve</button>&nbsp;
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $i = 0; if ($datadetail) {?>
<div class="white-box" id="detail">
    <div class="col-sm-3">
        <h3 class="box-title m-b-0">Detail Barang</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th style="text-align: center; width: 3%;">No</th>
                        <th style="text-align: center; width: 15%;">Kode Barang</th>
                        <th style="text-align: center; width: 25%;">Nama barang</th>
                        <th style="text-align: center; width: 10%;">Saldo</th>
                        <th style="text-align: center; width: 10%;">Qty Permintaan</th>
                        <th style="text-align: center; width: 10%;">Qty Belum Terpenuhi</th>
                        <th style="text-align: center; width: 10%;">Qty Pemenuhan</th>
                        <th style="text-align: center; width: 30%;">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($datadetail as $row) {
                        $i++;
                        ?>
                        <tr>
                            <td style="text-align: center;"><?= $i;?>
                                <input style="width:10px" type="hidden" class="form-control" readonly id="baris<?= $i;?>" name="baris[]" value="<?= $i;?>">
                            </td> 
                            <td>  
                                <input style="width:120px" type="hidden" class="form-control" id="idproduct<?=$i;?>" name="idproduct[]"value="<?= $row->id_product_base; ?>" readonly>
                                <input style="width:120px" type="text" class="form-control" id="eproduct<?=$i;?>" name="eproduct[]"value="<?= $row->i_product_base; ?>" readonly>
                            </td>
                            <td>
                                <input style="width:350px" type="text" class="form-control" id="eproduct<?=$i;?>" name="eproduct[]"value="<?= $row->e_product_basename; ?>" readonly>
                            </td>                    
                            <td>
                                <input style="width:100px" type="text" class="form-control" id="nsaldo<?=$i;?>" name="nsaldo[]" value="0" readonly> 
                            </td>           
                            <td>
                                <input style="width:100px" type="text" class="form-control" id="nquantitymemo<?=$i;?>" name="nquantitymemo[]" value="<?= $row->n_quantity_permintaan; ?>" readonly> 
                            </td>
                            <td>
                                <input style="width:100px" type="text" class="form-control" id="sisa<?=$i;?>" name="sisa[]" value="<?= $row->n_quantity_pemenuhan; ?>" readonly> 
                            </td>
                            <td>
                                <input style="width:100px" style="width:5%"style="width:5%"type="text" class="form-control" id="nquantity<?=$i;?>" name="nquantity[]" value="<?= $row->n_quantity; ?>" onkeyup="ceksaldo(<?=$i;?>);" readonly>
                            </td>                    
                            <td>
                                <input style="width:350px" type="text" class="form-control" id="edesc<?=$i;?>" name="edesc[]"value="<?=$row->e_remark;?>" readonly>
                            </td>                     
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<input type="hidden" name="jml" id="jml" value ="<?= $i ;?>">
<?php } ?>

<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date');
    });

    $('#approve').click(function(event) {
        ada = false;
        for (var i = 1; i <= $('#jml').val(); i++) {
            if (parseInt($('#nquantity'+i).val()) > parseInt($('#sisa'+i).val())){
                swal('Dokumen Referensi sudah pernah dibuat, silahkan dicek kembali');
                //$('#nquantity'+i).val($('#sisa'+i).val());
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