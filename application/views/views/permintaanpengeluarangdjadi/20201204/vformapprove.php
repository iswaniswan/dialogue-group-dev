<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Gudang</label>
                        <label class="col-md-2">Nomor Bon Keluar</label>
                        <label class="col-md-2">Tanggal Bon Keluar</label>
                        <label class="col-md-2">Tujuan Keluar</label>
                        <label class="col-md-3">Departemen/Partner</label>
                        <div class="col-sm-3">
                            <select name="ikodemaster" id="ikodemaster" class="form-control select2" onchange="getstore();" disabled="">
                                <?php if ($gudang) {
                                    foreach ($gudang->result() as $key) { ?>
                                        <option value="<?= $key->i_sub_bagian;?>" <?php if ($data->i_kode_master==$key->i_sub_bagian) {
                                            echo "selected";
                                        }?>><?= $key->e_sub_bagian;?></option> 
                                    <?php }
                                } ?> 
                            </select>
                            <input type="hidden" id="ikodemasterold" name="ikodemasterold" class="form-control date" value="<?= $data->i_kode_master; ?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="ibonk" name="ibonk" class="form-control" value="<?= $data->i_bonmk; ?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="dbonk" name="dbonk" class="form-control date" value="<?= $data->dbonk; ?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <select name="itujuan" id="itujuan" class="form-control select2" onchange="departemen(this.value);" disabled="">
                                <option value=""></option>
                                <?php if ($tujuan) {
                                    foreach ($tujuan->result() as $key) { ?>
                                        <option value="<?= $key->i_tujuan;?>" <?php if ($data->i_tujuan_keluar==$key->i_tujuan) {
                                            echo "selected";
                                        }?>><?= $key->e_tujuan_name;?></option> 
                                    <?php }
                                } ?>  
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="idepartemen" id="idepartemen" class="form-control select2" disabled="">
                                <option value="<?= $data->i_departement;?>"><?= $data->departemen;?></option>
                            </select>
                        </div>
                    </div>
                    <?php if ($data->pic_eks=='' || $data->pic_eks==null) {
                        $hidden = 'hidden="true"';
                        $xhidden = '';
                    }else{
                        $xhidden = 'hidden="true"';
                        $hidden = '';
                    }?>
                    <div class="form-group row">
                        <label class="col-sm-2">Nomor Memo</label>
                        <label class="col-sm-2">Tanggal Memo</label>
                        <label class="col-sm-2">Perkiraan Pengembalian</label>
                        <label class="col-sm-3">PIC Internal</label>
                        <label <?= $hidden;?> id="lepic" class="col-sm-3">PIC Eksternal</label><label <?= $xhidden;?> id="lepicx" class="col-sm-3"></label>
                        <div class="col-sm-2">
                            <input readonly="" type="text" id="imemo" name="imemo" class="form-control" maxlength="" value="<?= $data->i_memo;?>">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="dmemo" name="dmemo" class="form-control date" value="<?= $data->dmemo; ?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="dback" name="dback" class="form-control date" value="<?= $data->dback; ?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <select name="ppic" id="ppic" class="form-control select2" disabled="">
                                <option value="<?= $data->pic;?>"><?= $data->e_nama_karyawan;?></option>
                            </select>
                        </div>
                        <div <?= $hidden;?> id="pice" class="col-sm-3">
                            <input readonly="" type="text" id="epic" name="epic" class="form-control" placeholder="Nama PIC" value="<?= $data->pic_eks; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea disabled="" id= "eremark" name="eremark" class="form-control"><?= $data->e_remark; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-12">
                            <?php if ((($idepartemen == '18' && $ilevel == 6) || ($idepartemen == '1' && $ilevel == 1)) && $data->i_status == '2') {?>
                                <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="updatestatus('<?= $data->i_bonmk."','".$data->i_kode_master;?>','7');"> <i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Cancel</button>&nbsp;&nbsp;
                                <button type="button" class="btn btn-warning btn-rounded btn-sm" onclick="updatestatus('<?= $data->i_bonmk."','".$data->i_kode_master;?>','3');"> <i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;&nbsp;Change Requested</button>&nbsp;&nbsp;
                                <button type="button" class="btn btn-danger btn-rounded btn-sm"  onclick="updatestatus('<?= $data->i_bonmk."','".$data->i_kode_master;?>','4');"> <i class="fa fa-times"></i>&nbsp;&nbsp;&nbsp;Reject</button>&nbsp;&nbsp;
                                <button type="button" class="btn btn-success btn-rounded btn-sm" onclick="updatestatus('<?= $data->i_bonmk."','".$data->i_kode_master;?>','6');"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Approve</button>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php 
                $counter = 0; 
                if ($detail) {?>
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table id="tabledata" class="table color-table inverse-table table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 3%;">No</th>
                                        <th class="text-center" style="width: 10%;">Kode Barang</th>
                                        <th class="text-center" style="width: 37%;">Nama Barang Jadi</th>
                                        <th class="text-center">Warna</th>
                                        <th class="text-center" style="width: 10%;">Quantity</th>
                                        <th class="text-center">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    foreach ($detail as $row) {
                                        $counter++;?>
                                        <tr>
                                            <td class="text-center">
                                                <?= $counter;?>
                                            </td>
                                            <td>
                                                <?= $row->i_product;?>
                                            </td>
                                            <td>
                                                <?= $row->e_product_name;?>
                                            </td>
                                            <td>
                                                <?= $row->e_color_name;?>
                                            </td>
                                            <td class="text-right">
                                                <?= $row->n_qty;?>
                                            </td>
                                            <td>
                                                <?= $row->e_remark;?>
                                            </td>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php } ?>
            <input type="hidden" name="jml" id="jml" readonly value="<?= $counter;?>">
            </form>
        </div>
    </div>
</div>
</div>

<script>
    function updatestatus(ibonk,ibagian,istatus) {
        swal({   
            title: "Update Draft Ini?",   
            text: "Anda tidak akan dapat memulihkan data ini!",   
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Ya, Update!",   
            cancelButtonText: "Tidak, batalkan!",   
            closeOnConfirm: false,   
            closeOnCancel: false 
        }, function(isConfirm){   
            if (isConfirm) { 
                $.ajax({
                    type: "post",
                    data: {
                        'ibonk'  : ibonk,
                        'ibagian'  : ibagian,
                        'istatus'  : istatus,
                    },
                    url: '<?= base_url($folder.'/cform/updatestatus'); ?>',
                    dataType: "json",
                    success: function (data) {
                        swal("Update!", "Data berhasil Diupdate :)", "success");
                        show('<?= $folder;?>/cform/index/<?= $dfrom.'/'.$dto;?>','#main');
                    },
                    error: function () {
                        swal("Maaf", "Data gagal diupdate :(", "error");
                    }
                });
            } else {     
                swal("Dibatalkan", "Anda membatalkan update :)", "error");
            } 
        });
    }
</script>