<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-check"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?=$dfrom;?>/<?=$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-3">Bagian Pembuat</label>
                        <label class="col-md-4">Nomor Dokumen</label>
                        <label class="col-md-5">Tanggal Dokumen</label>
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2" required="" disabled>
                                <option value="<?=$data->i_bagian;?>"><?=$data->e_bagian_name;?></option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                           <input type="text" id="idocument" name="idocument" class="form-control" value="<?= $data->i_document ;?>" readonly>
                        </div>
                        <div class="col-sm-2">
                             <input type="text" id= "ddocument" name="ddocument" class="form-control" value="<?= $data->d_document; ?>" placeholder="<?=date('d-m-Y');?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Pengirim</label>
                        <label class="col-md-4">Nomor Referensi</label>
                        <label class="col-md-5">Tanggal Referensi</label>
                        <div class="col-sm-3">
                            <select name="ipengirim" id="ipengirim" class="form-control select2" disabled>
                                <option value="<?= $data->i_bagian_pengirim; ?>"><?= $data->e_bagian_pengirim; ?></option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <select name="ireff" id="ireff" class="form-control select2" onchange="getdataitem(this.value);" disabled> 
                                <option value="<?= $data->id_reff; ?>"><?= $data->i_reff; ?></option>
                            </select>
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
                    <div class="col-md-12">
                    <div class="form-group">
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
<?php $i = 0; if ($datadetail) {?>
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
                        <th style="text-align:center;">Qty Keluar</th>
                        <th style="text-align:center;">Qty Sisa</th>
                        <th style="text-align:center;">Qty Masuk</th>
                        <th style="text-align:center;">Keterangan</th>
                    </tr>
                </thead>
                <tbody>   
                    <?php $z = 0; $group = ""; foreach ($datadetail as $key) { $i++; 
                        if($group!=$key->id_product_wip){
                            $z++;
                        }
                        ?>
                            <?php if($group==""){?>
                            <tr id="tr<?= $z;?>">
                                <td colspan="3" class="tdna">
                                    <input type="text" id="iproduct<?=$z;?>" name="iproduct[]" class="form-control" value="<?= $key->i_product_wip.' - '.$key->e_product_wipname.' - '.$key->e_color_name;?>" readonly>
                                </td>
                                <td class="text-right">
                                   <input style="width:120px" type="text" id="nquantitywipkeluar<?=$z;?>" name="nquantitywipkeluar[]" class="form-control" value="<?= $key->n_quantity_wip_keluar;?>" readonly>
                                </td>
                                <td class="text-right">
                                   <input style="width:120px" type="text" id="nquantitywipsisa<?=$z;?>" name="nquantitywipsisa[]" class="form-control" value="<?= $key->n_sisa_wip;?>" readonly>
                                </td>
                                <td class="text-right">
                                   <input style="width:120px" type="text" id="nquantitywipmasuk<?=$z;?>" name="nquantitywipmasuk[]" class="form-control" value="<?= $key->n_quantity_wip_masuk;?>" readonly>
                                </td>
                                <td></td>
                            </tr>
                            <?php }else{ 
                                if($group!=$key->id_product_wip){?>
                                <tr id="tr<?= $z;?>">
                                    <td colspan="3" class="tdna">
                                        <input type="text" id="iproduct<?=$z;?>" name="iproduct[]" class="form-control" value="<?= $key->i_product_wip.' - '.$key->e_product_wipname.' - '.$key->e_color_name;?>" readonly>
                                    </td>
                                    <td class="text-right">
                                       <input style="width:120px" type="text" id="nquantitywipkeluar<?=$z;?>" name="nquantitywipkeluar[]" class="form-control" value="<?= $key->n_quantity_wip_keluar;?>" readonly>
                                    </td>
                                    <td class="text-right">
                                       <input style="width:120px" type="text" id="nquantitywipsisa<?=$z;?>" name="nquantitywipsisa[]" class="form-control" value="<?= $key->n_sisa_wip;?>" readonly>
                                    </td>
                                    <td class="text-right">
                                       <input style="width:120px" type="text" id="nquantitywipmasuk<?=$z;?>" name="nquantitywipmasuk[]" class="form-control" value="<?= $key->n_quantity_wip_masuk;?>" readonly>
                                    </td>
                                    <td></td>
                                </tr>
                                <?php //$i = 1;
                                }
                            }?>
                        </tr>
                        <?php $group = $key->id_product_wip; ?>
                       <tr class="del<?= $i;?>">
                            <td class="text-center">
                                <?= $i ;?>
                            </td>
                            <td>
                                <input type="text" style="width:120px" id="imaterial<?=$i;?>" name="imaterial[]" class="form-control" value="<?= $key->i_material;?>" readonly>
                            </td>
                            <td>
                                <input type="text" style="width:350px" id="ematerial<?=$i;?>" name="ematerial[]" class="form-control" value="<?= $key->e_material_name;?>" readonly>
                            </td>
                            <td>
                                <input style="width:120px" type="text" id="nquantitymaterialkeluar<?=$i;?>" name="nquantitymaterialkeluar[]" class="form-control" value="<?= $key->n_quantity_material_keluar;?>" readonly>
                            </td>
                            <td>
                                <input style="width:120px" type="text" id="nquantitymaterialsisa<?=$i;?>" name="nquantitymaterialsisa[]" class="form-control" value="<?= $key->n_sisa_material;?>" readonly>
                            </td>
                            <td>
                                <input style="width:120px" type="text" id="nquantitymaterialmasuk<?=$i;?>" name="nquantitymaterialmasuk[]" class="form-control" value="<?= $key->n_quantity_material_masuk;?>" readonly>
                            </td>
                            <td>
                                <input style="width:250px" type="text" id="edesc<?=$i;?>" name="edesc[]" class="form-control" value="<?= $key->e_remark;?>" readonly>
                            </td>
                        </tr>
                    <?php } ?> 
                    <input type="hidden" name="jml" id="jml" value ="<?= $i ;?>"> 
                </tbody>
            </table>
        </div>
    </div>
</div>
</form>
<?php } ?>
<script>
    $(document).ready(function () {
        $('.select2').select2();
    });

    $('#approve').click(function(event) {
        ada = false;
        for (var i = 1; i <= $('#jml').val(); i++) {
            //alert($('#nquantitywipsisa'+i).val());
            if (parseInt($('#nquantitywipmasuk'+i).val()) > parseInt($('#nquantitywipsisa'+i).val()) || (parseInt($('#nquantitymaterialmasuk'+i).val()) > parseInt($('#nquantitymaterialsisa'+i).val()))) {
                swal('Dokumen Referensi sudah pernah dibuat, silahkan dicek kembali');
                //$('#nquantitywipmasuk'+i).val($('#nquantitywipsisa'+i).val());
                //$('#nquantitymaterialmasuk'+i).val($('#nquantitymaterialsisa'+i).val());
                ada = true;
                return false;
            }
        }
        if (!ada) {
           // statuschange('<?= $folder;?>',$('#id').val(),'6','<?= $dfrom."','".$dto;?>');
        }else{
            return false;
        }
    });
</script>