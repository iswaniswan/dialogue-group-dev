<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-check"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;&nbsp;<?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div class="col-md-12">
                   <div class="form-group row">
                    <label class="col-md-3">Bagian Pembuat</label>
                    <label class="col-md-3">Nomor Dokumen</label>
                    <label class="col-md-2">Tanggal Dokumen</label>
                    <label class="col-md-4">Partner</label>
                    <div class="col-sm-3">
                        <input type="text" readonly="" id="ibagian" name="ibagian" class="form-control" value="<?=$data->e_bagian_name;?>">
                        <input type="hidden" id="id" name="id" class="form-control" value="<?= $data->id;?>">
                    </div>
                    <div class="col-sm-3">
                        <div class="input-group">
                            <input type="text" name="ikeluar" id="ikeluar" readonly="" autocomplete="off" class="form-control input-sm" value="<?=$data->i_document;?>" aria-label="Text input with dropdown button" readonly>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <input type="text" id="dkeluar" name="dkeluar" class="form-control input-sm" value="<?= $data->d_document; ?>" readonly>  
                    </div>
                    <div class="col-sm-4">
                        <input type="text" id="ipartner" name="ipartner" class="form-control input-sm" value="<?= $data->e_partner_name; ?>" readonly>  
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3">Nomor Referensi</label>
                    <label class="col-md-2">Tanggal Referensi</label> 
                    <label class="col-md-7">Keterangan</label> 
                    <div class="col-sm-3">
                        <input type="text" id="imemo" name="imemo" class="form-control input-sm" value="<?= $data->document_referensi ?>" readonly>
                    </div>
                    <div class="col-sm-2">
                        <input type="text" id="dmemo" name="dmemo" class="form-control input-sm" value="<?= $data->d_referensi ?>" readonly>
                    </div>
                    <div class="col-sm-7">
                     <textarea id= "eremark" name="eremark" class="form-control input-sm" readonly><?= $data->e_remark; ?></textarea>
                 </div>
             </div>                 
             <div class="form-group row">
                <div class="col-sm-offset-3 col-sm-12">
                    <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                    <button type="button" class="btn btn-warning btn-rounded btn-sm" onclick="statuschange('<?= $folder."','".$data->id;?>','1','<?= $dfrom."','".$dto;?>');"> <i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;Change Requested</button>&nbsp;
                    <button type="button" class="btn btn-danger btn-rounded btn-sm"  onclick="statuschange('<?= $folder."','".$data->id;?>','4','<?= $dfrom."','".$dto;?>');"> <i class="fa fa-times"></i>&nbsp;&nbsp;Reject</button>&nbsp;
                    <button type="button" id="approve" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Approve</button>&nbsp;
                </div>
            </div>
        </div>
    </div>
</div>
<div class="white-box" id="detail">
    <div class="col-sm-5">
        <h3 class="box-title m-b-0">Detail Barang</h3>
        <div class="m-b-0">
        </div>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" width="3%;">No</th>
                        <th class="text-center" width="10%;">Kode Barang</th>
                        <th class="text-center" width="30%;">Nama barang</th>
                        <th class="text-center">Jml Permintaan</th>
                        <th class="text-center">Jml Sisa</th>
                        <th class="text-center">Jml Pemenuhan</th>
                        <th class="text-center">Satuan</th>
                        <th class="text-center" width="20%;">Keterangan</th>
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
                                <td class="text-center">
                                    <?= $i;?>
                                    <input style="width:10px" type="hidden" class="form-control" readonly id="baris<?= $i;?>" name="baris[]" value="<?= $i;?>">
                                </td> 
                                <td>  
                                    <?= $row->i_material; ?>
                                </td>
                                <td>
                                    <?= $row->e_material_name; ?>
                                </td>                            
                                <td class="text-right">
                                    <?= $row->nquantity_permintaan; ?>
                                </td>
                                <td class="text-right">
                                    <?= $row->nquantity_pemenuhan; ?>
                                </td>
                                <td class="text-right">
                                    <?= $row->n_quantity; ?>
                                </td>                    
                                <td class="text-right">
                                    <?= $row->e_satuan_name; ?>
                                </td>                    
                                <td>
                                    <?=$row->e_remark;?>
                                </td>                                            
                            </tr>
                            <input style="width:10px" type="hidden" class="form-control" readonly id="baris<?= $i;?>" name="baris[]" value="<?= $i;?>">                        
                            <input style="width:120px" type="hidden" class="form-control" id="idmaterial<?=$i;?>" name="idmaterial[]"value="<?= $row->id_material; ?>" readonly>
                            <input style="width:120px" type="hidden" class="form-control" id="imaterial<?=$i;?>" name="imaterial[]"value="<?= $row->i_material; ?>" readonly>
                            <input style="width:350px" type="hidden" class="form-control" id="ematerial<?=$i;?>" name="ematerial[]"value="<?= $row->e_material_name; ?>" readonly>
                            <input style="width:100px" type="hidden" class="form-control" id="nquantitymemo<?=$i;?>" name="nquantitymemo[]" value="<?= $row->nquantity_permintaan; ?>" readonly>
                            <input style="width:100px" type="hidden" class="form-control" id="sisa<?=$i;?>" name="sisa[]" value="<?= $row->nquantity_pemenuhan; ?>" readonly>
                            <input style="width:100px" style="width:5%"style="width:5%"type="hidden" class="form-control" id="nquantity<?=$i;?>" name="nquantity[]" value="<?= $row->n_quantity; ?>" onkeyup="ceksaldo(<?=$i;?>);" readonly>
                            <input style="width:100px" type="hidden" class="form-control" id="satuan<?=$i;?>" name="satuan[]" value="<?= $row->e_satuan_name; ?>" readonly>
                            <input style="width:350px" type="hidden" class="form-control" id="edesc<?=$i;?>" name="edesc[]"value="<?=$row->e_remark;?>" readonly>                     
                            <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                        <?php }
                    }?>      
                </tbody>
            </table>
        </div>
    </div>
</div>
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