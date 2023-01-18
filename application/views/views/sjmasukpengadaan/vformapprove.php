<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Penerima</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-4 form-control-label" for="pengirim">Pengirim</label>
                        <div class="col-sm-3">
                            <select name="idepartemen" id="idepartemen" class="form-control" disabled="">
                                <?php if ($gudang) {
                                    foreach ($gudang->result() as $key) { ?>
                                        <option value="<?= trim($key->i_departement);?>"<?php if ($key->i_departement==$data->i_departement) {
                                            echo "selected";
                                        }?>><?= $key->e_departement_name;?></option> 
                                    <?php }
                                } ?> 
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="id" name="id" class="form-control" value="<?= $data->i_sj; ?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="tgl" name="tgl" class="form-control date" value="<?= date("d-m-Y", strtotime($data->d_sj)); ?>" readonly>
                        </div>
                        <div class="col-sm-4 has-danger">
                            <input type="text" id="pengirim" name="pengirim" class="form-control form-control-danger" value="<?= $data->e_from;?>" readonly="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea id= "eremark" name="eremark" class="form-control" disabled=""><?= $data->e_remark;?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-12">
                            <button type="button" class="btn btn-primary btn-rounded btn-sm" onclick="changestatus('<?= $folder."','".$data->i_sj;?>','7');"> <i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Cancel</button>&nbsp;&nbsp;
                            <button type="button" class="btn btn-warning btn-rounded btn-sm" onclick="changestatus('<?= $folder."','".$data->i_sj;?>','3');"> <i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;&nbsp;Change Requested</button>&nbsp;&nbsp;
                            <button type="button" class="btn btn-danger btn-rounded btn-sm"  onclick="changestatus('<?= $folder."','".$data->i_sj;?>','4');"> <i class="fa fa-times"></i>&nbsp;&nbsp;&nbsp;Reject</button>&nbsp;&nbsp;
                            <button type="button" class="btn btn-success btn-rounded btn-sm" onclick="changestatus('<?= $folder."','".$data->i_sj;?>','6');"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Approve</button>&nbsp;&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/','#main'); return false;"><i class="ti-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;&nbsp;
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $i = 0; if ($datadetail) { ?>
    <div class="white-box">
        <h3 class="box-title m-b-0">Detail Barang</h3>
        <div class="table-responsive">
            <table id="tabledata" class="table color-table inverse-table table-bordered tablex" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 3%;">No</th>
                        <th class="text-center" style="width: 12%;">Kode</th>
                        <th class="text-center" style="width: 35%;">Nama Barang WIP</th>
                        <th class="text-center" style="width: 12%;">Warna</th>
                        <th class="text-center" style="width: 10%;">Qty</th>
                        <th class="text-center">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    foreach ($datadetail as $row) {
                        $i++;?>
                        <tr>
                            <td class="text-center">
                                <?= $i;?>
                            </td>
                            <td>
                                <?= $row->i_wip;?>
                            </td>
                            <td>
                                <?= $row->e_product_name;?>
                            </td>
                            <td>
                                <?= $row->e_color_name;?>
                            </td>
                            <td class="text-right">
                                <?= $row->n_quantity;?>
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
<input type="hidden" name="jml" id="jml" value ="<?= $i;?>">