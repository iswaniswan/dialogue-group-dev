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
                        <label class="col-md-2">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-3">Nomor Referensi</label>
                        <label class="col-md-2">Tanggal Referensi</label>
                        <div class="col-sm-2">
                            <input type="text" readonly="" name="ebagiannamepembuar" id="ebagiannamepembuar" class="form-control input-sm" value="<?= $data->e_bagian;?>">
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" value="<?= $id;?>">
                                <input type="hidden" name="idocumentold" id="idocumentold" value="<?= $data->i_document;?>">
                                <input type="text" name="idocument" id="idocument" readonly="" autocomplete="off" onkeyup="gede(this);" maxlength="16" class="form-control input-sm" value="<?= $data->i_document;?>" aria-label="Text input with dropdown button">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="ddocument" required="" id="ddocument" class="form-control input-sm date" value="<?= $data->d_document;?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" readonly="" name="ireferensi" id="ireferensi" class="form-control input-sm" value="<?= $data->i_bbk;?>">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="dreferensi" id="dreferensi" class="form-control input-sm" value="<?= $data->d_referensi;?>" readonly>
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-3">Terima Dari</label>
                        <label class="col-md-2">Nomor SPBB</label>
                        <label class="col-md-2">Nomor Schedule</label>
                        <label class="col-md-5">Keterangan</label>
                        <div class="col-sm-3">
                            <input type="hidden" name="ipengirim" id="ipengirim" value="<?= $data->i_bagian_pengirim;?>" readonly>
                            <input type="text" name="epengirim" id="epengirim" class="form-control input-sm" value="<?= $data->e_bagian_name;?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="ispbb" id="ispbb" class="form-control input-sm" value="<?= $data->i_spbb;?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="ischedule" id="ischedule" class="form-control input-sm" value="<?= $data->i_schedule;?>" readonly>
                        </div>
                        <div class="col-sm-5">
                            <textarea type="text" id= "eremark" readonly="" name="eremark" maxlength="250" class="form-control input-sm" placeholder="Isi keterangan jika ada!"><?= $data->e_remark;?></textarea>
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
    <div class="white-box">
        <div class="col-sm-12">
            <h3 class="box-title m-b-0">Detail Barang</h3>
            <div class="table-responsive">
                <table id="tabledatax" class="table color-table inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 3%;">No</th>
                            <th class="text-center" style="width: 10%;">Kode</th>
                            <th class="text-center" style="width: 40%;">Nama Material</th>
                            <th class="text-center" style="width: 10%;">Satuan</th>
                            <th class="text-center" style="width: 8%;">Quantity Keluar</th>
                            <th class="text-center" style="width: 8%;">Quantity Sisa</th>
                            <th class="text-center" style="width: 9%;">Quantity Masuk</th>
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
                                <td><?= $key->i_material;?>
                                <input type="hidden" id="idproduct<?= $i ;?>" name="idproduct<?= $i ;?>" value="<?= $key->id_product_wip;?>">
                                <input type="hidden" id="idmaterial<?= $i ;?>" name="idmaterial<?= $i ;?>" value="<?= $key->id_material;?>">
                                <input type="hidden" id="imaterial<?= $i ;?>" name="imaterial<?= $i ;?>" value="<?= $key->i_material;?>">
                            </td>
                            <td><?= $key->e_material_name;?></td>
                            <td><?= $key->e_satuan_name;?></td>
                            <td class="text-right">
                                <?= $key->qty;?>
                                <input type="hidden" id="nquantity<?= $i ;?>" name="nquantity<?= $i ;?>" value="<?= $key->qty;?>">
                            </td>
                            <td class="text-right">
                                <?= $key->n_quantity_sisa;?>
                                <input type="hidden" id="nsisa<?= $i ;?>" name="nsisa<?= $i ;?>" value="<?= $key->n_quantity_sisa;?>">
                            </td>
                            <td class="text-right">
                                <?= $key->n_quantity;?>
                                <input type="hidden" id="npemenuhan<?= $i ;?>" name="npemenuhan<?= $i ;?>" value="<?= $key->n_quantity;?>">
                            </td>
                            <td><?= $key->e_remark;?></td>
                        </tr>
                        <?php $i++; 
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php } ?>
<script type="text/javascript">
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
            swal("Yaaahhh :(", "Jml sisa kode material "+xmaterial.substr(0,n)+" nya sudah habis.. :(", "error");
            return false;
        }
    });
</script>