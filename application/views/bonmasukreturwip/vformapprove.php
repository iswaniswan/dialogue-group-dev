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
                            <select name="ibagian" id="ibagian" class="form-control select2" disabled="">
                                <option value="<?=$data->i_bagian;?>"><?=$data->e_bagian_name;?></option>
                            </select>
                            <input type="hidden" id="id" name="id" class="form-control" value="<?= $data->id;?>">
                        </div>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" name="idocument" id="idocument" readonly=""  class="form-control input-sm" value="<?=$data->i_document;?>">
                            </div>
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
                                <option value="<?= $data->i_bagian_pengirim; ?>">
                                    <?= $data->e_bagian_pengirim ?> - <?= $data->name ?>
                                </option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <select name="ireff" id="ireff" class="form-control select2" disabled=""> 
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
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm mr-2" onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i class="fa fa-arrow-circle-left mr-2"></i>Kembali</button>
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
                        <th class="text-center" width="3%;">No</th>
                        <th class="text-center" width="12%;">Kode Barang</th>
                        <th class="text-center" width="25%;">Nama Barang</th>
                        <th class="text-center" width="12%;">Quantity Kirim</th>
                        <th class="text-center" width="12%;">Quantity Terima</th>
                        <th class="text-center">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                  <?php
                        if($datadetail){
                        $i = 0;
                        $lastproduct = ''; 
                        foreach($datadetail as $row){
                            $i++;                             
                    ?>
                    <tr>   
                        <td class="text-center"><?= $i;?>
                            <input type="hidden" class="form-control" readonly id="baris<?= $i;?>" name="baris[]" value="<?= $i;?>">
                        </td> 
                        <td><?= $row->i_product_base; ?>  
                            <input type="hidden" class="form-control" id="idproduct<?=$i;?>" name="idproduct[]"value="<?= $row->id_product; ?>" readonly>
                            <input type="hidden" class="form-control" id="iproduct<?=$i;?>" name="iproduct[]"value="<?= $row->i_product_base; ?>" readonly>
                        </td>
                        <td><?= $row->e_product_basename; ?>
                            <input type="hidden" class="form-control" id="eproduct<?=$i;?>" name="eproduct[]"value="<?= $row->e_product_basename; ?>" readonly>
                        </td>
                        <td class="text-right"><?= $row->n_quantity_reff; ?>
                            <input type="hidden" class="form-control" id="nquantity<?=$i;?>" name="nquantity[]" value="<?= $row->n_quantity_reff; ?>" readonly> 
                        </td>
                        <td class="text-right"><?= $row->n_quantity; ?>
                            <input type="hidden" class="form-control" id="nquantitymsk<?=$i;?>" name="nquantitymsk[]" value="<?= $row->n_quantity; ?>" onkeyup="validasi(<?=$i;?>);" readonly> 
                        </td>
                        <td><?=$row->e_remark;?>
                            <input type="hidden" class="form-control" id="edesc<?=$i;?>" name="edesc[]"value="<?=$row->e_remark;?>" readonly>
                        </td>                                            
                    </tr>                       
                    <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                    <?php }
                    }else{
                        $i=0;
                        $read = "disabled";
                        echo "<table class=\"table table-striped bottom\" style=\"width:100%;\"><tr><td colspan=\"16\" style=\"text-align:center;\">Maaf Tidak Ada  Bon Masuk!</td></tr></table>"; 
                    }?>        
                </tbody>
            </table>
        </div>
    </div>
</div>
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
            if (parseInt($('#nquantitymsk'+i).val()) > parseInt($('#nquantitysisa'+i).val())){
                swal('Dokumen Referensi sudah pernah dibuat, silahkan dicek kembali');
                //$('#nquantitymsk'+i).val($('#nquantitysisa'+i).val());
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
