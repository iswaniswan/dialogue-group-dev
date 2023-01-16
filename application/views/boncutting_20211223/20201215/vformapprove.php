<style type="text/css">
    .tdna{
        font-size:16px; background-color: #ddd; font-weight: bold;
    }
</style>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">  
                    <div class="form-group row">
                        <label class="col-md-2">Bagian Pembuat</label>
                        <label class="col-md-3">No. Dokumen</label>
                        <label class="col-md-2">Tgl. Dokumen</label>
                        <label class="col-md-3">No. Dokumen Referensi</label>
                        <label class="col-md-2">Tgl. Dokumen Referensi</label>
                        <div class="col-sm-2">
                            <input type="text" readonly="" name="ebagian" class="form-control input-sm" value="<?= $data->e_bagian_name;?>">
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" value="<?= $id;?>">
                                <input type="hidden" name="idocumentold" id="idocumentold" value="<?= $data->i_document;?>">
                                <input type="text" name="idocument" readonly="" autocomplete="off" maxlength="16" class="form-control input-sm" value="<?= $data->i_document;?>">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="ddocument" id="ddocument" class="form-control input-sm date" value="<?= $data->d_document;?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <?php if ($data->i_refference!=null || $data->i_refference!='') {
                                $referensi = $data->i_refference;
                            }else{
                                $referensi = $data->i_schedule;
                            }?>
                            <input readonly="" class="form-control input-sm" value="<?= $referensi;?>">
                            <input type="hidden" name="idreff" id="idreff" value="<?= $data->id_reff;?>">
                            <input type="hidden" name="ireferensi" id="ireferensi" value="<?= $data->id_refference;?>">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="dreferensi" id="dreferensi" class="form-control input-sm" value="<?= $data->d_schedule;?>" readonly>
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-2">Bagian Tujuan</label>
                        <label class="col-md-10">Keterangan</label>
                        <div class="col-sm-2">
                            <input type="text" readonly="" name="etujuan" class="form-control input-sm" value="<?= $data->e_bagian_tujuan;?>">
                        </div>
                        <div class="col-sm-10">
                            <textarea type="text" readonly="" id= "eremark" name="eremark" maxlength="250" class="form-control input-sm" placeholder="Isi keterangan jika ada!"><?= $data->e_remark;?></textarea>
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
<?php $i = 0; if ($detail) {?>
    <div class="white-box" id="detail">
        <h3 class="box-title m-b-0">Detail Barang</h3>
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 3%;">No</th>
                        <th class="text-center" style="width: 10%;">Kode</th>
                        <th class="text-center" style="width: 40%;">Nama Material</th>
                        <th class="text-center" style="width: 10%;">Satuan</th>
                        <th class="text-center" style="width: 8%;">Jml Set</th>
                        <th class="text-center" style="width: 12%;">Jml Lembar</th>
                        <th class="text-center">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 0; $group = ''; foreach ($detail as $key) { $no++;?>
                        <tr>
                            <?php if($group==""){?>
                                <td class="tdna" colspan="5"><?= $key->i_product_wip.' - '.$key->e_product_wipname.' '.$key->e_color_name;?></td>
                                <td class="tdna text-right">Jml WIP</td>
                                <td class="tdna"><?= $key->qty;?></td>
                            <?php }else{ 
                                if($group!=$key->id_product_wip){?>
                                    <td class="tdna" colspan="5"><?= $key->i_product_wip.' - '.$key->e_product_wipname.' '.$key->e_color_name;?></td>
                                    <td class="tdna text-right">Jml WIP</td>
                                    <td class="tdna"><?= $key->qty;?></td>
                                <?php $no = 1; }
                            }?>
                        </tr>
                        <?php $group = $key->id_product_wip; ?>
                        <tr>
                            <td class="text-center"><?= $no;?></td>
                            <td><?= $key->i_material;?>
                            <input type="hidden" id="idproduct<?= $i ;?>" name="idproduct<?= $i ;?>" value="<?= $key->id_product_wip;?>">
                            <input type="hidden" id="idmaterial<?= $i ;?>" name="idmaterial<?= $i ;?>" value="<?= $key->id_material;?>">
                        </td>
                        <td><?= $key->e_material_name;?></td>
                        <td><?= $key->e_satuan_name;?></td>
                        <td class="text-right"><input type="hidden" id="jmlset<?= $i ;?>" name="jmlset<?= $i ;?>" value="<?= $key->n_quantity_reff;?>"><?= $key->n_quantity_reff;?></td>
                        <td class="text-right"><input type="hidden" id="jmllembar<?= $i ;?>" name="jmllembar<?= $i ;?>" value="<?= $key->n_quantity_reff_sisa;?>"><?= $key->n_quantity_reff_sisa;?></td>
                        <td>
                            <?= $key->e_remark;?>
                            <input type="hidden" id="vset<?= $i ;?>" name="vset<?= $i ;?>" value="<?= $key->v_set;?>">
                            <input type="hidden" id="vtoset<?= $i ;?>" name="vtoset<?= $i ;?>" value="<?= $key->v_toset;?>">
                            <input type="hidden" id="qty<?= $i ;?>" name="qty<?= $i ;?>" value="<?= $key->qty;?>">
                            <input type="hidden" id="qtysc<?= $i ;?>" name="qtysc<?= $i ;?>" value="<?= $key->qty;?>">
                            <input type="hidden" id="qschedule<?= $i ;?>" name="qschedule<?= $i ;?>" value="<?= $key->n_quantity_sisa_bon;?>">
                            <input type="hidden" id="qreff<?= $i ;?>" name="qreff<?= $i ;?>" value="<?= $key->qtyreff;?>">
                            <input type="hidden" id="qreffsisa<?= $i ;?>" name="qreffsisa<?= $i ;?>" value="<?= $key->qtyreffsisa;?>">
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
<script type="text/javascript">
    $('#approve').click(function(event) {
        var ada = false;
        if ($('#ireferensi').val()==null || $('#ireferensi').val()=='') {
            for (var i = 0; i < $('#jml').val(); i++) {
                if (parseInt($('#qschedule'+i).val()) < parseInt($('#qty'+i).val())) {
                    ada = true;
                }
            }
            if (ada==false) {
                statuschange('<?= $folder;?>',$('#id').val(),'6','<?= $dfrom."','".$dto;?>');
            }else{
                swal("Yaaahhh :(", "qty WIP tidak boleh lebih dari qty sisa di schedule!", "error");
                return false;
            }
        }else{
            for (var i = 0; i < $('#jml').val(); i++) {
                if (parseInt($('#qreffsisa'+i).val()) == parseInt($('#qreff'+i).val())) {
                    ada = true;
                }
            }
            if (ada==false) {
                statuschange('<?= $folder;?>',$('#id').val(),'6','<?= $dfrom."','".$dto;?>');
            }else{
                swal("Gagal Approve :(", "Jumlah lembar sudah tidak ada pendingan :( ", "error");
                return false;
            }
        }

        /*var habis     = false;
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
            swal("Yaaahhh :(", "Jml sisa kode material "+xmaterial.substr(0,n)+" nya sudah habis.. :(", "error");
            return false;
        }*/
    });
</script>