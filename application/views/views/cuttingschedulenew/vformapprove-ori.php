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
                        <label class="col-sm-4">Bagian Pembuat</label>
                        <label class="col-md-4">Nomor Dokumen</label>
                        <label class="col-md-4">Tanggal Dokumen</label>
                        <div class="col-sm-4">
                            <select name="ibagian" id="ibagian" class="form-control select2" required="" disabled>
                                <option value="<?=$data->i_bagian;?>"><?=$data->e_bagian_name;?></option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                           <input type="text" id="idocument" name="idocument" class="form-control input-sm" value="<?= $data->i_document ;?>" readonly>
                           <input type="hidden" id="id" name="id" class="form-control" value="<?= $data->id;?>">
                        </div>
                        <div class="col-sm-4">
                             <input type="text" id= "ddocument" name="ddocument" class="form-control input-sm" value="<?= $data->d_document; ?>" placeholder="<?=date('d-m-Y');?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Pengirim</label>
                        <label class="col-md-4">Nomor Referensi</label>
                        <label class="col-md-4">Tanggal Referensi</label>
                        <div class="col-sm-4">
                            <select name="ipengirim" id="ipengirim" class="form-control select2" disabled>
                                <option value="<?= $data->i_bagian_pengirim; ?>"><?= $data->e_bagian_name; ?></option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <select name="ireff" id="ireff" class="form-control select2" onchange="getdataitem(this.value);" disabled> 
                                <option value="<?= $data->id_reff; ?>"><?= $data->i_reff; ?></option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id= "dreferensi" name="dreferensi" class="form-control input-sm" value="<?= $data->d_reff; ?>" required="" placeholder="<?=date('d-m-Y');?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea id= "eremark" name="eremark" class="form-control input-sm" placeholder="Isi keterangan jika ada!" readonly><?= $data->e_remark; ?></textarea>
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
<?php $i = 0; if ($datadetail) {?>
<div class="white-box" id="detail">
    <div class="col-sm-5">
        <h3 class="box-title m-b-0">Detail Barang</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead> 
                    <tr>
                        <th style="text-align:center;width:5%">No</th>
                        <th style="text-align:center;width:10%">Kode Barang</th>
                        <th style="text-align:center;width:25%">Nama Barang</th>
                        <th style="text-align:center;width:10%">Qty Keluar</th>
                        <th style="text-align:center;width:10%">Qty Sisa</th>
                        <th style="text-align:center;width:10%">Qty Masuk</th>
                        <th style="text-align:center;width:25%">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                <?php $z = 0; $group = ""; foreach ($datadetail as $key) { $i++; 
                        if($group!=$key->id_product_wip){
                            $z++;
                        }
                        ?>
                            <?php if($group==""){?>
                            <tr id="tr<?= $z;?>" class="tdna">
                                <td colspan="3">
                                    <?= $key->i_product_wip.' - '.$key->e_product_wipname.' - '.$key->e_color_name;?>
                                </td>
                                <td class="text-right"><?= $key->n_quantity_wip_keluar;?>
                                   <input style="width:100px;" type="hidden" class="form-control" id="nquantitywip<?=$z;?>" name="nquantitywip[]" value="<?= $key->n_quantity_wip_keluar;?>" readonly>
                                </td>
                                <td class="text-right"><?= $key->n_quantity_wip_sisa;?>
                                   <input style="width:100px;" type="hidden" class="form-control" id="nquantitywipsisacutting<?=$z;?>" name="nquantitywipsisacutting[]" value="<?= $key->n_quantity_wip_sisa;?>" readonly>
                                </td>
                                <td class="text-right"><?= $key->n_quantity_wip_masuk;?>
                                   <input style="width:100px;" type="hidden" class="form-control" id="nquantitywipmasuk<?=$z;?>" name="nquantitymasuk[]" value="<?= $key->n_quantity_wip_masuk;?>" readonly>
                                </td>
                                <td></td>
                            <?php }else{ 
                                if($group!=$key->id_product_wip){?>
                                    <td colspan="3">
                                    <?= $key->i_product_wip.' - '.$key->e_product_wipname.' - '.$key->e_color_name;?>
                                    </td>
                                    <td class="text-right"><?= $key->n_quantity_wip_keluar;?>
                                    <input style="width:100px;" type="hidden" class="form-control" id="nquantitywip<?=$z;?>" name="nquantitywip[]" value="<?= $key->n_quantity_wip_keluar;?>" readonly>
                                    </td>
                                    <td class="text-right"><?= $key->n_quantity_wip_sisa;?>
                                    <input style="width:100px;" type="hidden" class="form-control" id="nquantitywipsisacutting<?=$z;?>" name="nquantitywipsisacutting[]" value="<?= $key->n_quantity_wip_sisa;?>" readonly>
                                    </td>
                                    <td class="text-right"><?= $key->n_quantity_wip_masuk;?>
                                    <input style="width:100px;" type="hidden" class="form-control" id="nquantitywipmasuk<?=$z;?>" name="nquantitymasuk[]" value="<?= $key->n_quantity_wip_masuk;?>" readonly>
                                    </td>
                                    <td></td>
                                <?php //$i = 1;
                                }
                            }?>
                        </tr>
                        <?php $group = $key->id_product_wip; ?>
                        <tr class="del<?= $i;?>">
                            <td class="text-center">
                                <?= $i ;?>
                            </td>
                            <td><?= $key->i_material;?>
                                <input type="hidden" name="idproductwip[]" id="idproductwip<?=$i;?>" value="<?= $key->id_product_wip;?>">
                                <input type="hidden" class="idmaterial" name="idmaterial[]" id="idmaterial<?=$i;?>" value="<?= $key->id_material;?>">
                                <input style="width:150px" type="hidden" class="form-control" id="imaterial<?=$i;?>" name="imaterial[]" value="<?= $key->i_material;?>" readonly>
                            </td>
                            <td><?= $key->e_material_name;?>
                                <input style="width:400px" type="hidden" class="form-control" id="ematerial<?=$i;?>" name="ematerial[]" value="<?= $key->e_material_name;?>" readonly>
                            </td>
                            <td class="text-right"><?= $key->n_quantity_keluar;?>
                                <input style="width:100px;" type="hidden" class="form-control" id="nquantity<?=$i;?>" name="nquantitybahan[]" value="<?= $key->n_quantity_keluar;?>" readonly>
                            </td>
                            <td class="text-right"><?= $key->n_quantity_sisa;?>
                                <input style="width:100px;" type="hidden" class="form-control" id="nquantitysisacutting<?=$i;?>" name="nquantitysisacutting[]" value="<?= $key->n_quantity_sisa;?>" readonly>
                            </td>
                            <td class="text-right"><?= $key->n_quantity_masuk;?>
                                <input style="width:100px;" type="hidden" class="form-control" id="nquantitymasuk<?=$i;?>" name="nquantitymasuk[]" value="<?= $key->n_quantity_masuk;?>" readonly>
                            </td>
                            <td><?= $key->e_remark;?>
                                <input style="width:400px" type="hidden" class="form-control" id="edesc<?=$i;?>" name="edesc[]" value="<?= $key->e_remark;?>" readonly>
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
        showCalendar('.date');
    });

    $('#approve').click(function(event) {
        ada = false;
        /* for (var i = 1; i <= $('#jml').val(); i++) {
            if (parseInt($('#nquantitywipmasuk'+i).val()) > parseInt($('#nquantitywipsisacutting'+i).val()) || (parseInt($('#nquantitymasuk'+i).val()) > parseInt($('#nquantitysisacutting'+i).val()))) {
                swal('Dokumen Referensi sudah pernah dibuat, silahkan dicek kembali');
                //$('#nquantitywipmasuk'+i).val($('#nquantitywipsisacutting'+i).val());
                //$('#nquantitymasuk'+i).val($('#nquantitysisacutting'+i).val());
                ada = true;
                return false;
            }
        } */

        if (!ada) {
            statuschange('<?= $folder;?>',$('#id').val(),'6','<?= $dfrom."','".$dto;?>');
        }else{
            return false;
        }
    });
</script>
