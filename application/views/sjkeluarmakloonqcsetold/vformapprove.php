<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
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
                        <label class="col-sm-3">Bagian Pembuat</label>
                        <label class="col-sm-3">Tipe Makloon</label>
                        <label class="col-sm-2">Nomor Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-2">Perkiraan Kembali</label>
                        <div class="col-sm-3">
                            <input type="text" readonly="" class="form-control input-sm" name="ibagian" id="ibagian" value="<?= $data->e_bagian_name;?>">
                            <input type="hidden" name="ibagianold" id="ibagianold" value="<?= $data->i_bagian;?>">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" readonly="" class="form-control input-sm" name="itype" id="itype" value="<?= $data->e_type_makloon_name;?>">
                            <input type="hidden" name="itypeold" id="itypeold" value="<?= $data->id_type_makloon;?>">
                        </div>
                        <div class="col-sm-2">
                            <input type="hidden" name="id" id="id" value="<?= $id;?>">
                            <input type="hidden" name="isjold" id="isjold" value="<?= $data->i_document;?>">
                            <input type="text" name="idocument" id="isj" required="" readonly="" class="form-control input-sm" value="<?= $data->i_document;?>">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="ddocument" name="ddocument" class="form-control input-sm date" required="" value="<?= $data->d_document;?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="dperkiraan" required="" name="dperkiraan" class="form-control input-sm date" value="<?= $data->d_perkiraan;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3">Partner</label>
                        <label class="col-sm-3">Nomor Permintaan</label>
                        <label class="col-md-2">Tanggal Permintaan</label>
                        <label class="col-md-4">Keterangan</label>
                        <div class="col-sm-3">
                            <input type="text" readonly="" class="form-control input-sm" name="ipartner" id="ipartner" value="<?= $data->e_supplier_name;?>">
                            <input type="hidden" name="fpkp" id="fpkp" value="<?= $data->f_pkp;?>">
                            <input type="hidden" name="ndiskon" id="ndiskon" value="<?= $data->n_diskon;?>">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="ipermintaan" name="ipermintaan" class="form-control input-sm" maxlength="18" value="<?= $data->i_permintaan;?>" readonly required="" placeholder="No Permintaan Harus Diisi!">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="dpermintaan" name="dpermintaan" class="form-control input-sm date" value="<?= $data->d_perkiraan;?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <textarea type="text" name="eremarkh" placeholder="Isi keterangan jika ada!!!" class="form-control input-sm" readonly="" maxlength="250"><?= $data->e_remark;?></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                            <button type="button" class="btn btn-warning btn-rounded btn-sm" onclick="statuschange('<?= $folder."','".$data->id;?>','1','<?= $dfrom."','".$dto;?>');"> <i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;Change Requested</button>&nbsp;
                            <button type="button" class="btn btn-danger btn-rounded btn-sm"  onclick="statuschange('<?= $folder."','".$data->id;?>','4','<?= $dfrom."','".$dto;?>');"> <i class="fa fa-times"></i>&nbsp;&nbsp;Reject</button>&nbsp;
                            <button type="button" class="btn btn-success btn-rounded btn-sm" onclick="statuschange('<?= $folder."','".$data->id;?>','6','<?= $dfrom."','".$dto;?>');"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Approve</button>&nbsp;
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $i = 0; if ($datadetail) {?>
    <div class="white-box" id="detail">
        <div class="col-sm-3">
            <h3 class="box-title m-b-0">Detail Barang</h3>
        </div>
        <div class="col-sm-12">
            <div class="table-responsive">
                <table id="tabledatax" class="table color-table inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 3%;">No</th>
                            <th class="text-center" style="width: 10%;">Kode</th>
                            <th class="text-center" style="width: 45%;">Nama Barang</th>
                            <th class="text-center" style="width: 10%;">Qty</th>
                            <th class="text-center">Ket</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($datadetail as $key) {
                            $i++;
                            ?>
                            <tr>
                                <td class="text-center"><spanx id="snum<?= $i ;?>"><?= $i ;?></spanx></td>
                                <td><?= $key->i_product_wip;?></td>
                                <td><?= $key->namabarang;?></td>
                                <td class="text-right"><?= $key->n_quantity;?></td>
                                <td><?= $key->e_remark;?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <input type="hidden" name="jml" id="jml" value ="<?= $i ;?>">
<?php } ?>
</form>