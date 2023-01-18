<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">  
                    <div class="form-group row">
                        <label class="col-md-2">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-3">Nomor Referensi</label>
                        <label class="col-md-2">Tanggal Referensi</label>
                        <div class="col-sm-2">
                            <select name="ibagian" id="ibagian" class="form-control select2" disabled>
                                <?php if ($gudang) {
                                    foreach ($gudang->result() as $key) { ?>
                                        <option value="<?= trim($key->i_bagian);?>"<?php if ($key->i_bagian==$data->i_bagian) {?> selected <?php } ?>><?= $key->e_bagian_name;?></option>
                                    <?php }
                                } ?> 
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" value="<?= $id;?>">
                                <input type="hidden" name="idocumentold" id="idocumentold" value="<?= $data->i_document;?>">
                                <input type="text" name="idocument" id="idocument" readonly="" autocomplete="off" onkeyup="gede(this);" maxlength="16" class="form-control input-sm" value="<?= $data->i_document;?>" aria-label="Text input with dropdown button">
                                <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span>
                            </div>
                            <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="ddocument" required="" id="ddocument" class="form-control input-sm" value="<?= $data->d_document;?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <select name="ireferensi" id="ireferensi" class="form-control select2" disabled>
                                <option value="<?= $data->id_reff;?>"><?= $data->i_referensi;?></option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="dreferensi" id="dreferensi" class="form-control input-sm" value="<?= $data->d_referensi;?>" readonly>
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-3">Terima Dari</label>
                        <label class="col-md-9">Keterangan</label>
                        <div class="col-sm-3">
                            <input type="hidden" name="ipengirim" id="ipengirim" value="<?= $data->i_bagian_pengirim;?>" readonly>
                            <input type="text" name="epengirim" id="epengirim" class="form-control input-sm" value="<?= $data->e_bagian_name_pengirim;?>" readonly>
                        </div>
                        <div class="col-sm-5">
                            <textarea readonly type="text" id= "eremark" name="eremark" maxlength="250" class="form-control input-sm" placeholder="Isi keterangan jika ada!"><?= $data->e_remark;?></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                       <div class="col-sm-12">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                           
                        </div>
                    </div>
                </div>           
            </div>
        </div>
    </div>
</div>
<?php $i = 0; if ($detail) {?>
    <div class="white-box">
        <h3 class="box-title m-b-0">Detail Barang</h3>
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 3%;">No</th>
                        <th class="text-center" style="width: 10%;">Kode</th>
                        <th class="text-center" style="width: 30%;">Nama Material</th>
                        <th class="text-center" style="width: 10%;">Satuan</th>
                        <th class="text-center" style="width: 10%;">Schedule Cutting</th>
                        <th class="text-center" style="width: 8%;">Quantity Kirim</th>
                        <th class="text-center" style="width: 9%;">Quantity Terima</th>
                        <th class="text-center">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $group = ''; foreach ($detail as $key) {?>
                        <tr>
                            <?php if($group==""){?>
                                <td style="font-size:16px; background-color: #ddd;" colspan = "8"><b><?= $key->i_product_wip.' - '.$key->e_product_wipname.' - '.$key->e_color_name;?></b></td>
                            <?php }else{ 
                                if($group!=$key->id_product_wip){?>
                                    <td style="font-size:16px; background-color: #ddd;" colspan = "8"><b><?= $key->i_product_wip.' - '.$key->e_product_wipname.' - '.$key->e_color_name;?></b></td>
                                <?php }
                            }?>
                        </tr>
                        <?php $group = $key->id_product_wip; ?>
                        <tr>
                            <td class="text-center"><?= $i+1;?></td>
                            <td>
                                <input type="hidden" id="id_reff_item<?= $i ;?>" name="id_reff_item<?= $i ;?>" value="<?= $key->id_reff_item;?>">
                                <input type="hidden" id="idproduct<?= $i ;?>" name="idproduct<?= $i ;?>" value="<?= $key->id_product_wip;?>">
                                <input type="hidden" id="idmaterial<?= $i ;?>" name="idmaterial<?= $i ;?>" value="<?= $key->id_material;?>">
                                <input class="form-control input-sm" readonly type="text" id="imaterial<?= $i ;?>" name="imaterial<?= $i ;?>" value="<?= $key->i_material;?>">
                            </td>
                            <td>
                                <input class="form-control input-sm" readonly type="text" id="ematerialname<?= $i ;?>" name="ematerialname<?= $i ;?>" value="<?= $key->e_material_name;?>">
                            </td>
                            <td>
                                <input readonly class="form-control input-sm" type="text" id="satuan<?= $i ;?>" name="satuan<?= $i ;?>" value="<?= $key->e_satuan_name;?>">
                            </td>
                            <td>
                                <input readonly class="form-control input-sm text-right" type="text" id="d_schedule<?= $i ;?>" name="d_schedule<?= $i ;?>" value="<?= $key->d_schedule;?>">
                            </td>
                            <td>
                                <input class="form-control input-sm text-right" type="text" id="nsisa<?= $i ;?>" name="nsisa<?= $i ;?>" value="<?= $key->n_quantity_kirim;?>" readonly>
                            </td>
                            <td>
                                <input readonly class="form-control input-sm text-right" type="text" id="npemenuhan<?= $i ;?>" name="npemenuhan<?= $i ;?>" placeholder="0" onkeyup="angkahungkul(this); cekvalidasi(<?=$i;?>)" value="<?= $key->n_quantity;?>">
                            </td>
                            <td>
                                <input readonly class="form-control input-sm" type="text" id="eremark<?= $i ;?>" name="eremark<?= $i ;?>" value="<?= $key->e_remark;?>" placeholder="Isi keterangan jika ada!">
                            </td>
                        </tr>
                        <?php $i++; 
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
<?php } ?>
<input type="hidden" name="jml" id="jml" value ="<?= $i ;?>">
</form>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
    $('#approve').click(function(event) {
        var habis     = false;
        var xmaterial = '';
        for (var x = 0; x < $("#jml").val(); x++) {
            if (parseInt($('#nquantity'+x).val()) < parseInt($('#npemenuhan'+x).val())) {
                var habis = true;
                xmaterial += $('#imaterial'+x).val()+', ';
            }
        }

        var n = xmaterial.length-2;
        if (habis==false) {
            statuschange('<?= $folder;?>',$('#id').val(),'6','<?= $dfrom."','".$dto;?>');
        }else{
            // swal("Yaaahhh :(", "Jml "+xmaterial.substr(0,n)+" nya sudah habis.. :(", "error");
            return false;
        }
    });
</script>