<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-check"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-3">Bagian Pembuat</label>
                        <label class="col-md-4">No Dokumen</label>
                        <label class="col-md-5">Tanggal Dokumen</label>
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2" required="" disabled="">
                                <option value="<?=$data->i_bagian;?>"><?=$data->e_bagian_name;?></option>
                            </select>
                            <input type="hidden" id="id" name="id" class="form-control" value="<?= $id;?>">
                        </div>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" name="idocument" id="idocument" readonly="" class="form-control input-sm" value="<?=$data->i_document;?>" aria-label="Text input with dropdown button">
                            </div>
                        </div>
                        <div class="col-sm-2"> 
                             <input type="text" id= "ddocument" name="ddocument" class="form-control" value="<?= $data->d_document; ?>" placeholder="<?=date('d-m-Y');?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Partner</label>
                        <label class="col-md-4">No Referensi</label>
                        <label class="col-md-5">Tanggal Referensi</label>
                        <div class="col-sm-3">
                            <select name="ipartner" id="ipartner" class="form-control select2" disabled>
                                <option value="<?= $data->i_bagian_pengirim; ?>"><?= $data->e_bagian_pengirim; ?></option>
                            </select>
                            <input type="hidden" id= "idpartner" name="idpartner" class="form-control date" value="<?= $data->i_bagian_pengirim; ?>">
                        </div>
                        <div class="col-sm-4">
                            <select name="ireff" id="ireff" class="form-control select2" onchange="getdataitem(this.value);" disabled> 
                                <option value="<?= $data->id_document_reff; ?>"><?= $data->i_reff; ?></option>
                            </select>
                            <input type="hidden" id= "idreff" name="idreff" class="form-control date" value="<?= $data->id_document_reff; ?>">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id= "dreferensi" name="dreferensi" class="form-control" value="<?= $data->d_reff; ?>" required="" placeholder="<?=date('d-m-Y');?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea id= "eremark" name="eremark" class="form-control" placeholder="Isi keterangan jika ada!" readonly><?= $data->e_remark; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-3 col-sm-12">
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
                        <th style="text-align:center;width:5%">No</th>
                        <th style="text-align:center;width:12%">Kode Barang</th>
                        <th style="text-align:center;width:30%">Nama Barang</th>
                        <th style="text-align:center;width:12%">Warna</th>
                        <th style="text-align:center;width:12%">Quantity (Pengembalian)</th>
                        <th style="text-align:center;width:12%">Quantity Masuk</th>
                        <th style="text-align:center;width:25%">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                      <?php
                        if($datadetail){
                        $i = 0;
                        foreach($datadetail as $row){
                            $i++;                             
                    ?>
                    <tr>                    
                        <td style="text-align: center;"><?= $i;?>
                            <input style="width:10px" type="hidden" class="form-control" readonly id="baris<?= $i;?>" name="baris[]" value="<?= $i;?>">
                        </td> 
                        <td>  
                            <input style="width:120px" type="hidden" class="form-control" id="idproduct<?=$i;?>" name="idproduct[]"value="<?= $row->id_product_base; ?>" readonly>
                            <input style="width:120px" type="text" class="form-control" id="iproductwip<?=$i;?>" name="iproductwip[]"value="<?= $row->i_product_base; ?>" readonly>
                        </td>
                        <td>
                            <input style="width:350px" type="text" class="form-control" id="eproductwip<?=$i;?>" name="eproductwip[]"value="<?= $row->e_product_basename; ?>" readonly>
                        </td>                           
                        <td>  
                            <input type="hidden" class="form-control" id="idcolorpro<?=$i;?>" name="idcolorpro[]"value="<?= $row->id_color; ?>" readonly>
                            <input style="width:150px" type="text" class="form-control" id="ecolorname<?=$i;?>" name="ecolorname[]"value="<?= $row->e_color_name; ?>" readonly>
                        </td> 
                        <td>
                            <input style="text-align:right;width:100px" type="text" class="form-control" id="nquantitywip<?=$i;?>" name="nquantitywip[]" value="<?= $row->n_quantity_keluar; ?>" readonly> 
                        </td>
                        <td>
                            <input style="text-align:right;width:100px" style="width:5%"style="width:5%"type="text" class="form-control inputitem" id="nquantitywipmasuk<?=$i;?>" name="nquantitywipmasuk[]" value="<?= $row->n_quantity_masuk; ?>" readonly>
                            <input style="text-align:right;width:100px" style="width:5%"style="width:5%"type="text" class="form-control" id="nquantitywipsisa<?=$i;?>" name="nquantitywipsisa[]" value="<?= $row->n_quantity_sisa; ?>" readonly> 
                        </td>                       
                        <td>  
                            <input style="width:350px" type="text" class="form-control" id="edesc<?=$i;?>" name="edesc[]"value="<?= $row->e_remark; ?>" readonly>
                        </td>         
                    </tr>                      
                    <?}
                    }?>
                    <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                </tbody>
            </table>
        </div>
    </div>
</div>
</form>
<script>
    $(document).ready(function () {
        $('.select2').select2();
    });

    $('#approve').click(function(event) {
        ada = false;
        //alert(jml);
        for (var i = 1; i <= $('#jml').val(); i++) {
            //alert($('#nquantitywipsisa'+i).val());
            if (parseInt($('#nquantitywipmasuk'+i).val()) > parseInt($('#nquantitywipsisa'+i).val())) {
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