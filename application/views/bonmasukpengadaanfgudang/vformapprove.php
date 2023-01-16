<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-check"></i>  <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i><?= $title_list; ?></a>
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
                            <input type="hidden" id="id" name="id" class="form-control" value="<?= $data->id;?>">
                            <input type="text" id="idocument" name="idocument" class="form-control input-sm" value="<?= $data->i_document ;?>" readonly>
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
                                <option value="<?= $data->i_bagian_pengirim; ?>"><?= $data->e_bagian_pengirim; ?></option>
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
                            <textarea id= "eremark" name="eremark" class="form-control" placeholder="Isi keterangan jika ada!" readonly><?= $data->e_remark; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm mr-2" onclick="show('<?= $folder1;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i class="fa fa-arrow-circle-left mr-2"></i>Kembali</button>
                            <button type="button" class="btn btn-warning btn-rounded btn-sm mr-2" onclick="statuschange('<?= $folder."','".$data->id;?>','1','<?= $dfrom."','".$dto;?>');"> <i class="fa fa-pencil-square-o mr-2"></i>Change Requested</button>
                            <button type="button" class="btn btn-danger btn-rounded btn-sm mr-2"  onclick="statuschange('<?= $folder."','".$data->id;?>','4','<?= $dfrom."','".$dto;?>');"> <i class="fa fa-times mr-2"></i>Reject</button>
                            <button type="button" id="approve" class="btn btn-success btn-rounded btn-sm mr-2"> <i class="fa fa-check-square-o mr-2"></i>Approve</button>
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
            <table id="tabledatax" class="table color-table inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead> 
                    <tr>
                        <th class="text-center">No</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th class="text-right">Qty Keluar</th>
                        <th class="text-right">Qty Pemenuhan</th>
                        <th class="text-right">Qty Masuk</th>
                        <th>Keterangan</th>
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
                           <?= $row->i_material; ?>
                        </td>
                        <td>
                           <?= $row->e_material_name; ?>
                        </td>                            
                        <td class="text-right">
                            <?= $row->n_quantity_keluar; ?> 
                        </td>
                        <td class="text-right">
                           <?= $row->n_quantity_sisa; ?>
                        </td>
                        <td class="text-right">
                           <?= $row->n_quantity_masuk; ?>
                        </td>                 
                        <td>
                            <?=$row->e_remark;?>
                        </td>                                            
                    </tr>                      
                    <input type="hidden" class="form-control" readonly id="baris<?= $i;?>" name="baris[]" value="<?= $i;?>">
                    <input type="hidden" class="form-control" id="idmaterial<?=$i;?>" name="idmaterial[]"value="<?= $row->id_material; ?>" readonly>
                    <input type="hidden" class="form-control" id="imaterial<?=$i;?>" name="imaterial[]"value="<?= $row->i_material; ?>" readonly>
                    <input type="hidden" class="form-control" id="ematerial<?=$i;?>" name="ematerial[]"value="<?= $row->e_material_name; ?>" readonly>
                    <input type="hidden" class="form-control" id="nquantitykeluar<?=$i;?>" name="nquantitykeluar[]" value="<?= $row->n_quantity_keluar; ?>" readonly> 
                    <input type="hidden" class="form-control" id="nquantitysisa<?=$i;?>" name="nquantitysisa[]" value="<?= $row->n_quantity_sisa; ?>" readonly> 
                    <input type="hidden" class="form-control" id="nquantity<?=$i;?>" name="nquantity[]" value="<?= $row->n_quantity_masuk; ?>" readonly>
                    <input type="hidden" class="form-control" id="edesc<?=$i;?>" name="edesc[]"value="<?=$row->e_remark;?>" readonly>
                    </tr>                       
                    <?}
                    }?>        
                </tbody>
            </table>
        </div>
    </div>
</div>
<input type="hidden" name="jml" id="jml" value ="<?= $i ;?>">
</form>
<script>
    $(document).ready(function () {
        $('.select2').select2();
        /**
        * Tidak boleh lebih dari hari ini, dan maksimal mundur 1830 hari (5 tahun) dari hari ini
        */
        showCalendar('.date',1830,0);
    });

    $('#approve').click(function(event) {
        ada = false;
        for (var i = 1; i <= $('#jml').val(); i++) {
            if (parseInt($('#nquantity'+i).val()) > parseInt($('#nquantitysisa'+i).val())) {
                swal('Dokumen Referensi sudah pernah dibuat, silahkan dicek kembali');
                //$('#nquantity'+i).val($('#nquantitysisa'+i).val());
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
