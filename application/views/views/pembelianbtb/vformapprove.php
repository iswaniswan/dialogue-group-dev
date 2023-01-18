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
                        <label class="col-md-3">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor BTB</label>
                        <label class="col-md-3">Tanggal BTB</label>
                        <label class="col-md-3">Gudang Penerima</label>
                        <div class="col-sm-3">
                            <input type="text" readonly="" name="ebagiannamepembuar" id="ebagiannamepembuar" class="form-control input-sm" value="<?= $data->e_bagian;?>">
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" value="<?= $data->id;?>">
                                <input type="text" name="ibtb" id="ibtb" readonly="" autocomplete="off" maxlength="17" class="form-control input-sm" value="<?= $data->i_btb;?>" aria-label="Text input with dropdown button">
                            </div>
                        </div>     
                        <div class="col-sm-3">
                            <input id="dbtb" name="dbtb" class="form-control input-sm date" value="<?= $data->d_btb; ?>" readonly>
                        </div>                        
                        <div class="col-sm-3">
                            <input type="text" readonly="" name="ebagianname" id="ebagianname" class="form-control input-sm" value="<?= $data->e_bagian_name;?>">
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-3">Supplier</label> 
                        <label class="col-md-3">SJ Supplier</label> 
                        <label class="col-md-2">Tanggal SJ</label> 
                        <label class="col-md-2">Nomor OP</label>
                        <label class="col-md-2">Tanggal OP</label>
                        <div class="col-sm-3">
                            <input type="text" id= "esupplier" name="esupplier" class="form-control input-sm" required="" value="<?= $data->e_supplier_name;?>" readonly>
                            <input type="hidden" id= "isupplier" name="isupplier" class="form-control input-sm" required="" value="<?= $data->i_supplier;?>">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id= "isj" name="isj" class="form-control input-sm" readonly required="" value="<?= $data->i_sj_supplier;?>" maxlength="15">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id= "dsj" name="dsj" class="form-control input-sm date" required="" value="<?= $data->d_sj;?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id= "iop" name="iop" class="form-control input-sm" required="" value="<?= $data->i_op;?>" readonly>
                            <input type="hidden" id= "idop" name="idop" class="form-control input-sm" required="" value="<?= $data->id;?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id= "dop" name="dop" class="form-control input-sm" required="" value="<?= $data->d_op;?>" readonly>
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-sm-8">Keterangan</label>
                        <label class="col-md-2">Nomor PP</label> 
                        <label class="col-md-2">Tanggal PP</label>
                        <div class="col-sm-8">
                            <textarea class="form-control input-sm" readonly name="remark"><?= $data->e_remark;?></textarea>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id= "ipp" name="ipp" class="form-control input-sm" required="" value="<?= $data->i_pp;?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id= "dpp" name="dpp" class="form-control input-sm" required="" value="<?= $data->d_pp;?>" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                        </div>
                        <div class="col-sm-3">
                            <button type="button" class="btn btn-warning btn-block btn-sm" onclick="statuschange('<?= $folder."','".$data->id;?>','1','<?= $dfrom."','".$dto;?>');"> <i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;Change Requested</button>&nbsp;
                        </div>
                        <div class="col-sm-3">
                            <button type="button" class="btn btn-danger btn-block btn-sm"  onclick="statuschange('<?= $folder."','".$data->id;?>','4','<?= $dfrom."','".$dto;?>');"> <i class="fa fa-times"></i>&nbsp;&nbsp;Reject</button>&nbsp;
                        </div>
                        <div class="col-sm-3">
                            <button type="button" id="approve" class="btn btn-success btn-block btn-sm"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Approve</button>&nbsp;
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
                        <th class="text-center" style="width: 3%;">No</th>
                        <!-- <th class="text-center" style="width: 12%;">Gudang</th> -->
                        <th style="width: 10%;">Kode</th>
                        <th style="width: 25%;">Nama Barang</th>
                        <!-- <th class="text-center" style="width: 10%;">Jml Eks</th>
                        <th class="text-center" style="width: 10%;">Sat Eks</th> -->
                        <th class="text-right" style="width: 10%;">Jml OP</th>
                        <th class="text-right" style="width: 10%;">Masuk</th>
                        <th class="text-right" style="width: 10%;">Toleransi</th>
                        <th style="width: 10%;">Satuan</th>
                        <th>Keterangan</th>
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
                                <td><?= $row->i_material;?></td>
                                <td><?= htmlentities($row->e_material_name);?></td>
                                <td hidden="true">
                                    <input type="text" id="nquantityeks<?=$i;?>" class="form-control text-right input-sm" autocomplete="off" name="nquantityeks[]" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="<?= $row->n_quantity_eks;?>" onkeyup="angkahungkul(this);">
                                </td>
                                <td hidden="true">
                                    <select id="isatuaneks<?=$i;?>" name="isatuaneks[]" class="form-control select2" data-placeholder="Pilih Satuan Sup">
                                        <option value="">Pilih Satuan</option>
                                        <?php if ($satuan) {
                                            foreach ($satuan as $key) {?>
                                                <option value="<?= $key->i_satuan_code;?>" <?php if ($key->i_satuan_code==$row->i_satuan_code_eks) {?> selected <?php } ?>><?= $key->e_satuan_name;?></option>
                                            <?php }
                                        }?>
                                    </select>
                                </td>

                                <td class="text-right"><?= $row->n_sisa;?></td>
                                <td class="text-right">
                                    <?= $row->n_quantity;?>
                                    <input type="hidden" value="<?= $row->n_quantity;?>" id="nquantity<?=$i;?>" name="nquantity[]"/>
                                    <input type="hidden" value="<?= $row->maximum;?>" id="nsisa<?=$i;?>" name="nsisa[]"/>
                                    <input type="hidden" value="<?= $row->i_satuan_code;?>" id="isatuan<?=$i;?>" name="isatuan[]"/>
                                </td>
                                <td class="text-right"><?= $row->toleransi;?></td>
                                <td><?= $row->e_satuan_name;?></td>
                                <td><?= $row->e_remark;?></td>
                            </tr>
                    <?php } 
                }?>
                <input type="hidden" name="jml" id="jml" value ="<?= $i;?>">
            </tbody>
        </table>
    </div>
</div>
</div>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
    $(document).ready(function () {
        $('.select2').select2({
            width : '100%',
        });
        fixedtable($('.table'));
    });

    /* $('#approve').click(function(event) {
        ada = false;
        for (var i = 1; i <= $('#jml').val(); i++) {
            if (parseInt($('#nquantity'+i).val()) > parseInt($('#nsisa'+i).val())) {
                swal('Jml tidak boleh lebih dari sisa op + toleransi = '+ $('#nsisa'+i).val());
                $('#nquantity'+i).val($('#nsisa'+i).val());
                ada = true;
                return false;
            }
        }

        if (!ada) {
            statuschange('<?= $folder;?>',$('#id').val(),'6','<?= $dfrom."','".$dto;?>');
        }else{
            return false;
        }
    }); */

    const btn = document.querySelector('#approve');
    const id = document.querySelector('#id').value;
    const folder = '<?= $folder; ?>';
    const dfrom = '<?= $dfrom; ?>';
    const dto = '<?= $dto; ?>';
    btn.addEventListener('click', function(event) {
        $.ajax({
            url: `${base_url}${folder}/cform/approve_validation/`,
            type: "POST", // type of action POST || GET
            dataType: 'json', // data type
            data: {
                id: id,
            }, // post data || get data
            success: function(data) {
                /** Jika Qty OP Melebihi PP */
                if (data['sisa'] === false) {
                    swal("Maaf", "Qty BTB ada yang melebihi Sisa OP :(", "error");
                    return false;
                } else {
                    statuschange(folder, id, '6', dfrom, dto);
                }
                // console.log(data);
            },
            error: function(err) {
                console.log(err);
                // swal("Maaf", "Data gagal diapprove :(", "error");
            }
        });
    });
</script>
