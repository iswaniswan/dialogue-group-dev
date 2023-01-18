<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-check"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?=$title_list;?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-sm-12">
                    <div class="form-group row">
                        <label class="col-md-4">Bagian Pembuat</label>
                        <label class="col-md-4">Nomor Dokumen</label>
                        <label class="col-md-4">Tanggal Dokumen</label>
                        <div class="col-sm-4">
                            <select name="ibagian" id="ibagian" class="form-control select2" required="">
                                <option value="<?=$data->i_bagian;?>"><?=$data->e_bagian_name;?></option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" value="<?= $id;?>" >
                                <input type="text" name="iretur" id="iretur" class="form-control" value="<?= $data->i_retur_beli;?>" readonly>                               
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="dretur" name="dretur" class="form-control date" required="" readonly value="<?= date('d-m-Y', strtotime($data->d_retur)); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Supplier</label>
                        <label class="col-md-4">Nomor Referensi</label><!-- dari Nota Pembelia -->
                        <label class="col-md-4">Tanggal Referensi</label>
                        <div class="col-sm-4">
                            <select name="isupplier" id="isupplier" class="form-control select2" onchange="getnota(this.value);">
                                <option value="<?=$data->i_supplier;?>"><?=$data->e_supplier_name;?></option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <select name="ifaktur" id="ifaktur" class="form-control select2" onchange="get(this.value);">
                               <option value="<?=$data->id_btb;?>"><?=$data->i_btb;?></option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id= "dnota" name="dnota" class="form-control" value=<?=$data->d_btb; ?> readonly>
                        </div>
                    </div>
                    <div class="form-group">    
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea type="text" id="eremark" name="eremark" class="form-control" value="" placeholder="Isi keterangan jika ada!" readonly><?= $data->e_remark;?></textarea>
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
<div class="white-box" id="detail">
    <div class="col-sm-5">
        <h3 class="box-title m-b-0">Detail Barang</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th style="text-align:center;">No</th>
                        <th style="text-align:center;">Kode Barang</th>
                        <th style="text-align:center;">Nama Barang</th>
                        <th style="text-align:center;">Satuan</th>
                        <th style="text-align:center;">Qty BTB</th>
                        <th style="text-align:center;">Qty Retur</th>
                        <th style="text-align:center;">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $i = 0;
                    if ($detail) {
                        foreach ($detail as $row) {

                            $i++;?>
                            <tr>
                                <td class="text-center"><spanx id="snum<?=$i;?>"><?= $i;?></spanx></td>
                                <td>
                                    <input type="text" value="<?= $row->i_material;?>" readonly id="imaterial<?=$i;?>" class="form-control input-sm" name="imaterial<?=$i;?>">
                                </td>
                                <td>
                                    <input type="text" value="<?= $row->e_material_name;?>" readonly id="ematerial<?=$i;?>" class="form-control input-sm" name="ematerial<?=$i;?>">
                                </td>
                                <td>
                                    <input type="hidden" value="<?= $row->i_satuan_code;?>" id="isatuan<?=$i;?>" name="isatuan<?=$i;?>">
                                    <input type="text" value="<?= $row->e_satuan_name;?>" readonly id="esatuan<?=$i;?>" class="form-control input-sm" name="esatuan<?=$i;?>">
                                </td>
                                <td>
                                    <input type="text" value="<?= $row->n_quantity_btb;?>" id="nquantitybtb<?=$i;?>" class="form-control text-right input-sm inputitem" name="nquantitybtb<?=$i;?>" readonly>
                                    <input type="hidden" style="width:100px" class="form-control" id="sisaretur<?=$i;?>" name="sisaretur<?=$i;?>"value="<?= $row->n_quantity_sisa_retur; ?>" readonly >
                                </td>
                                <td>
                                    <input type="text" value="<?= $row->n_quantity_retur;?>" id="nquantity<?=$i;?>" class="form-control text-right input-sm" autocomplete="off" name="nquantity<?=$i;?>" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="0" onkeyup="angkahungkul(this);" readonly>
                                </td>
                                <td>
                                    <input type="text" id="edesc<?=$i;?>" class="form-control input-sm" value="<?= $row->e_remark;?>" name="edesc<?=$i;?>" readonly>
                                </td>
                            </tr>
                        <?php } 
                    }?>
                    <input type="hidden" name="jml" id="jml" value ="<?= $i;?>">
                </tbody>
            </table>
        </div>
    </div>
</div>
</from>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('.select2').select2();
    })

    $('#approve').click(function(event) {
        ada = false;
        for (var i = 1; i <= $('#jml').val(); i++) {
            if (parseInt($('#nquantity'+i).val()) > parseInt($('#sisaretur'+i).val())) {
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