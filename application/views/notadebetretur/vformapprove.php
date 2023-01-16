<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-check"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/index/<?=$dfrom;?>/<?=$dto;?>','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?=$title_list;?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row ">
                        <label class="col-md-4">Bagian Pembuat</label>
                        <label class="col-md-4">No Dokumen</label>
                        <label class="col-md-4">Tanggal Dokumen</label>
                        <div class="col-sm-4">
                            <select name="ikodemaster" id="ikodemaster" class="form-control select2" disabled="true">
                                <option value="<?=$data->i_bagian;?>"><?=$data->e_bagian_name;?></option>
                            </select>
                            <input type="hidden" id="id" name="id" value="<?=$data->id?>">
                        </div>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" name="iretur" id="iretur" class="form-control" value="<?=$data->i_retur_beli;?>" readonly>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="dretur" name="dretur" class="form-control" value="<?= $data->d_retur; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Supplier</label>
                        <label class="col-md-4">No Referensi</label>
                        <label class="col-md-4">Tanggal Referensi</label>
                        <div class="col-sm-4">
                            <select name="isupplier" id="isupplier" class="form-control select2" disabled="true">
                                <option value="<?=$data->i_supplier;?>"><?=$data->e_supplier_name;?></option>
                            </select>
                            <input type="hidden" name="esupplier" id="esupplier" class="form-control" value="<?=$data->e_supplier_name;?>">
                        </div>
                        <div class="col-sm-4">
                            <select name="ifaktur" id="ifaktur" class="form-control select2" disabled="true">
                                <option value="<?=$data->id_btb;?>"><?=$data->i_btb;?></option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id= "dnota" name="dnota" class="form-control" value="<?=$data->d_btb;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">    
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea type="text" id="eremark" name="eremark" class="form-control" value="" placeholder="Isi keterangan jika ada!" readonly><?=$data->e_remark;?></textarea>
                            <input class="form-control" type="hidden" id="vtotal" name="vtotal" value="<?=$data->v_total;?>" readonly>
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
<div class="white-box" id="detail">
    <div class="col-sm-5">
        <h3 class="box-title m-b-0">Detail Barang</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table dark-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead> 
                    <tr>
                        <th style="text-align:center;">No</th>
                        <th style="text-align:center;">Kode Barang</th>
                        <th style="text-align:center;">Nama Barang</th>
                        <th style="text-align:center;">Satuan</th>
                        <th style="text-align:center;">Qty BTB</th>
                        <th style="text-align:center;">Qty Retur</th>
                        <th style="text-align:center;">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?$i = 0;
                    if ($detail) {
                        foreach ($detail as $row) {
                            $i++;?>
                            <tr>
                                <td style="text-align: center;"><?= $i;?>
                                <input type="hidden" class="form-control" readonly id="baris<?= $i;?>" name="baris<?= $i;?>" value="<?= $i;?>">
                                <input style ="width:150px" type="hidden" class="form-control" id="isj<?=$i;?>" name="isj<?=$i;?>"value="<?= $row->i_sj_supplier;?>" readonly >
                                <input style ="width:150px" type="hidden" class="form-control" id="iditem<?=$i;?>" name="iditem<?=$i;?>"value="<?= $row->id;?>" readonly >
                            </td>
                            <td>
                                <input style ="width:150px" type="text" class="form-control" id="iproduct<?=$i;?>" name="iproduct<?=$i;?>"value="<?= $row->i_material; ?>" readonly >
                            </td>
                            <td>
                                <input style ="width:450px" type="text" class="form-control" id="ematerialname<?=$i;?>" name="ematerialname<?=$i;?>"value="<?= $row->e_material_name; ?>" readonly >
                            </td>
                            <td>
                                <input type="text" style="width:100px" class="form-control" id="esatuan<?=$i;?>" name="esatuan<?=$i;?>"value="<?= $row->e_satuan_name; ?>" readonly >
                                <input type="hidden" class="form-control" id="isatuan<?=$i;?>" name="isatuan<?=$i;?>"value="<?= $row->i_satuan_code; ?>" readonly >
                            </td>
                            <td>
                                <input type="text" style="width:100px" class="form-control" id="qty<?=$i;?>" name="qty<?=$i;?>"value="<?= $row->n_quantity_btb; ?>" readonly >
                                <input type="hidden" style="width:100px" class="form-control" id="sisaretur<?=$i;?>" name="sisaretur<?=$i;?>"value="<?= $row->n_quantity_sisa_retur; ?>" readonly >
                            </td>
                            <td>
                                <input type="text" style="width:100px" class="form-control" id="qtyretur<?=$i;?>" name="qtyretur<?=$i;?>" value="<?= $row->n_quantity_retur; ?>" readonly>
                                <input type="hidden" class="form-control" id="vunitprice<?=$i;?>" name="vunitprice<?=$i;?>" value="<?= $row->v_price; ?>"readonly >
                            </td>
                            <td>
                                <input type="text" style="width:200px" class="form-control" id="edesc<?=$i;?>" name="edesc<?=$i;?>"value="<?= $row->e_remark; ?>" readonly>
                            </td>
                            </tr>
                        <?php } 
                    }?>
                </tbody>
                <input type="hidden" name="jml" id="jml" value ="<?= $i ;?>">
            </table>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date');
    });

    $('#approve').click(function(event) {
        ada = false;
        for (var i = 1; i <= $('#jml').val(); i++) {
            if (parseInt($('#qtyretur'+i).val()) > parseInt($('#sisaretur'+i).val())) {
                swal('Dokumen Referensi sudah pernah dibuat, silahkan dicek kembali');
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