<style type="text/css">
    .font{
        font-size: 16px;
        background-color: #ffffe0;
    }
</style>
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
                        <label class="col-md-3">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-3">Tanggal Dokumen</label>
                        <label class="col-md-3">Tipe Makloon</label>
                        <div class="col-sm-3">
                            <input type="text" readonly="" class="form-control input-sm" value="<?= $data->e_bagian_name;?>">
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="hidden" id="id" value="<?= $id;?>">
                                <input type="text" readonly="" class="form-control input-sm" value="<?= $data->i_document;?>">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" class="form-control input-sm" value="<?= $data->d_document;?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" class="form-control input-sm" value="<?= $data->e_type_makloon_name;?>" readonly>
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-3">Partner</label>
                        <label class="col-md-4">Dokumen Referensi</label>
                        <label class="col-md-5">Keterangan</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control input-sm" value="<?= $data->e_supplier_name;?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <select type="text" readonly multiple="multiple" name="idreff[]" required="" id="idreff" class="form-control input-sm select2">
                                <?php if ($referensi) {
                                    foreach ($referensi->result() as $key) {?>
                                        <option value="<?= $key->id;?>" selected><?= 'Nomor : '.$key->i_document.' - Tanggal : '.$key->d_document;?></option>
                                    <?php }
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-5">
                            <textarea type="text" readonly class="form-control input-sm"><?= $data->e_remark;?></textarea>
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
<?php $i = 0; if ($datadetail) {?>
<div class="white-box" id="detail">
    <div class="col-sm-3">
        <h3 class="box-title m-b-0">Detail Barang</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatay" class="table color-table inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" width="3%">No</th>
                        <th class="text-center" width="12%">Referensi</th>
                        <th class="text-center" width="9%">Kode</th>
                        <th class="text-center" width="25%">Nama Barang</th>
                        <th class="text-center" width="10%">Satuan</th>
                        <th class="text-center" width="7%">Jml</th>
                        <th class="text-center" width="7%">Sisa</th>
                        <th class="text-center" width="7%">Terima</th>
                        <th class="text-center" width="15%">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $z = 0; 
                    $group = "";
                    foreach ($datadetail as $key) {
                        if($group!=$key->id_document.$key->id_material){
                            $z++;
                        } 
                        if($group==""){ ?>
                            <tr class='tdna'>
                                <td class="text-center"><?=$z;?></td>
                                <td><?= $key->i_document;?></td>
                                <td><?= $key->i_material;?></td>
                                <td><?= $key->e_material_name;?></td>
                                <td><?= $key->e_satuan_name;?></td>
                                <td class="text-right"><?= $key->n_quantity_reff;?></td>
                                <td class="text-right"><?= $key->n_quantity_sisa_reff;?></td>
                                <td class="text-right"><?= $key->n_quantity;?></td>
                                <td></td>
                            </tr>
                            <tr class="font"><td colspan="9"><b>List Detail Barang</b></td></tr>
                            <?php 
                        }else{
                            if($group!=$key->id_document.$key->id_material){?>
                                <tr class='tdna'>
                                    <td class="text-center"><?=$z;?></td>
                                    <td><?= $key->i_document;?></td>
                                    <td><?= $key->i_material;?></td>
                                    <td><?= $key->e_material_name;?></td>
                                    <td><?= $key->e_satuan_name;?></td>
                                    <td class="text-right"><?= $key->n_quantity_reff;?></td>
                                    <td class="text-right"><?= $key->n_quantity_sisa_reff;?></td>
                                    <td class="text-right"><?= $key->n_quantity;?></td>
                                    <td></td>
                                </tr>
                                <tr class="font"><td colspan="9"><b>List Detail Barang</b></td></tr>
                            <?php }
                        }
                        $group = $key->id_document.$key->id_material;?>
                        <tr>
                            <td class="text-center">#</td>
                            <td>
                                <input type="hidden" id="imaterial<?=$i;?>" value="<?= $key->i_material;?>">
                                <input type="hidden" id="nqtysisa<?=$i;?>" value="<?= $key->n_quantity_sisa_reff;?>">
                                <input type="hidden" id="nqty<?=$i;?>" value="<?= $key->n_quantity;?>">
                                <input type="hidden" id="imateriallist<?=$i;?>" value="<?= $key->i_material_list;?>">
                                <?= $key->i_document;?>
                            </td>
                            <td><?= $key->i_material_list;?></td>
                            <td><?= $key->e_material_list;?></td>
                            <td><?= $key->e_satuan_list;?></td>
                            <td class="text-right"><?= $key->n_quantity_list_reff;?><input type="hidden" id="nqtylistsemua<?=$i;?>"value="<?= $key->n_quantity_list_reff;?>"></td>
                            <td class="text-right"><?= $key->n_quantity_list_sisa_reff;?><input type="hidden"id="nqtylistsisa<?=$i;?>" value="<?= $key->n_quantity_list_sisa_reff;?>"></td>
                            <td class="text-right"><?= $key->n_quantity_list;?><input type="hidden" id="nqtylist<?=$i;?>" value="<?= $key->n_quantity_list;?>"></td>
                            <td><?= $key->e_remark;?></td>
                        </tr>
                        <?php
                        $i++; 
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<input type="hidden" name="jml" id="jml" value ="<?= $i ;?>">
<?php } ?>
<script>
    $(document).ready(function () {
        $('.select2').select2();
    });

    $('#approve').click(function(event) {
        var habis     = false;
        var sisa      = false;
        var iproduct  = '';
        var imaterial = '';
        for (var x = 0; x < $("#jml").val(); x++) {

            if (parseInt($('#nqty'+x).val()) > parseInt($('#nqtysisa'+x).val())) {
                var sisa = true;
                imaterial += $('#imaterial'+x).val()+', ';
            }

            if (parseInt($('#nqtylist'+x).val()) > parseInt($('#nqtylistsisa'+x).val())) {
                var habis = true;
                iproduct += $('#imateriallist'+x).val()+', ';
            }
        }

        var n = iproduct.length-2;
        var m = imaterial.length-2;
        if (habis==false && sisa==false) {
            statuschange('<?= $folder;?>',$('#id').val(),'6','<?= $dfrom."','".$dto;?>');
        }else{
            swal("Yaaahhh :(", "Jml Pemenuhan Kode "+imaterial.substr(0,n)+" dan Detail Barang "+iproduct.substr(0,n)+" Melebihi Jml Sisa :(", "error");
            return false;
        }
    });
</script>