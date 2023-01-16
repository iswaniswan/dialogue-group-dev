<?= $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-check"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;&nbsp;<?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                     <div class="form-group row">
                        <label class="col-md-4">Bagian Pembuat</label>
                        <label class="col-md-4">Nomor Dokumen</label>
                        <label class="col-md-4">Tanggal Dokumen</label>
                        <div class="col-sm-4">
                             <select name="ibagian" id="ibagian" class="form-control select2" disabled="true">
                                <option value="<?=$data->i_bagian;?>"><?= $data->e_bagian_name; ?></option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="hidden" id="id" name="id" class="form-control" value="<?= $data->id;?>">
                                <input type="text" name="ikeluar" id="ikeluar" readonly="" autocomplete="off" onkeyup="gede(this);" class="form-control input-sm" value="<?= $data->i_document; ?>">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="dkeluar" name="dkeluar" class="form-control date" value="<?= $data->d_document; ?>" readonly onchange="return maxi();">  
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Partner</label>
                        <label class="col-md-4">Nomor Referensi</label>
                        <label class="col-md-4">Tanggal Referensi</label>      
                        <div class="col-sm-4">
                            <select name="ipartner" id="ipartner" class="form-control select2" disabled="true">
                                <option value="<?= $data->i_bagian_pengirim; ?>"><?= $data->e_bagian_pengirim; ?></option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <select name="imemo" id="imemo" class="form-control select2" disabled="true" onchange="getmemo(this.value);" disabled="true">
                                <option value="<?= $data->id_document_reff;?>"><?= $data->i_document_reff; ?></option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="dmemo" name="dmemo" class="form-control" value="<?= $data->d_document_reff;?>" readonly>
                        </div>           
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-10">
                           <textarea id= "eremark" name="eremark" class="form-control" placeholder="Isi keterangan jika ada!" readonly><?= $data->e_remark;?></textarea>
                        </div>
                    </div>                 
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
<div class="white-box" id="detail"> 
    <div class="col-sm-5">
        <h3 class="box-title m-b-0">Detail Barang</h3>
        <div class="m-b-0">
        </div>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table dark-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th style="text-align: center; width: 3%;">No</th>
                        <th style="text-align: center; width: 15%;">Kode Barang</th>
                        <th style="text-align: center; width: 25%;">Nama Barang</th>
                        <th style="text-align: center; width: 10%;">Qty Pengeluaran</th>
                        <th style="text-align: center; width: 10%;">Qty Sisa</th>
                        <th style="text-align: center; width: 10%;">Qty Pengembalian</th>
                        <th style="text-align: center; width: 30%;">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $i = 0;
                        if($detail){
                            foreach($detail as $row){$i++;?>
                                <tr>
                                    <td><?= $i; ?></td>
                                    <td><input type="text" id="imaterial<?=$i;?>" name="[]" class="form-control" style="width:120px" readonly value="<?= $row->i_material; ?>"></td>
                                    <td><input type="text" id="ematerial<?=$i;?>" name="[]" class="form-control" style="width:350px" readonly value="<?= $row->e_material_name; ?>"></td>
                                    <td><input type="text" id="nquantitymasuk<?=$i;?>" name="nquantitymasuk[]" class="form-control" style="width:120px" readonly value="<?= $row->qty_masuk; ?>"></td>
                                    <td><input type="text" id="nquantitysisa<?=$i;?>" name="nquantitysisa[]" class="form-control" style="width:120px" readonly value="<?= $row->n_quantity_sisa; ?>"></td>
                                    <td><input type="text" id="nquantityterima<?=$i;?>" name="nquantityterima[]" class="form-control" style="width:120px" readonly value="<?= $row->n_quantity; ?>"></td>
                                    <td><input type="text" id="edesc<?=$i;?>" name="edesc[]" class="form-control" style="width:250px" readonly value="<?= $row->e_remark; ?>"></td>
                                </tr>
                           <? }
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<input type="hidden" name="jml" id="jml" value="<?=$i;?>">
</form>
<script>
    $(document).ready(function () {
        $('.select2').select2();
    });

$('#approve').click(function(event) {
        ada = false;
        for (var i = 1; i <= $('#jml').val(); i++) {
            if (parseInt($('#nquantitymasuk'+i).val()) > parseInt($('#nquantitysisa'+i).val())){
                swal('Dokumen Referensi sudah pernah dibuat, silahkan dicek kembali');
                $('#nquantitymasuk'+i).val($('#nquantitysisa'+i).val());
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
