<style type="text/css">
    .font{
        font-size: 16px;
        background-color: #ffffe0;
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
                        <label class="col-sm-2">Bagian Pembuat</label>
                        <label class="col-sm-3">Nomor Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-2">Pengeluaran ke</label>
                        <label class="col-md-3">Jenis Pengeluaran</label>                        
                        <div class="col-sm-2">
                            <input type="text" class="form-control input-sm" readonly="" name="ebagian" id="ebagian" value="<?= $data->e_bagian_name;?>">
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" value="<?= $id;?>">
                                <input type="hidden" name="isjold" id="isjold" value="<?= $data->i_document;?>">
                                <input type="text" name="idocument" id="isj" readonly="" class="form-control input-sm" value="<?= $data->i_document;?>">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="ddocument" name="ddocument" class="form-control input-sm date" required="" value="<?= $data->d_document;?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" class="form-control input-sm" readonly="" name="etujuan" id="etujuan" value="<?= $data->e_tujuan_name;?>">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" class="form-control input-sm" readonly="" name="ejeniskeluar" id="ejeniskeluar" value="<?= $data->e_jenis_name;?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3">Permintaan ke Gudang</label>
                        <label class="col-md-2">Perkiraan Kembali</label>
                        <label class="col-sm-4">Partner</label>
                        <label class="col-md-3">PIC Internal</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control input-sm" readonly="" name="egudang" id="egudang" value="<?= $data->e_bagian_tujuan;?>">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="dperkiraan" name="dperkiraan" class="form-control input-sm date" value="<?= $data->d_perkiraan;?>"  readonly>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" class="form-control input-sm" readonly="" name="epartner" id="epartner" value="<?= $data->e_partner_name;?>">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" class="form-control input-sm" readonly="" name="picinternal" id="picinternal" value="<?= $data->e_nama_karyawan;?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <?php if ($data->id_tujuan==1) {
                            $j = '5';
                        }else{
                            $j = '8';
                        }?>
                        <label class="col-sm-2">Nomor Memo (Optional)</label>
                        <label class="col-md-2">Tanggal Memo</label>
                        <label class="col-md-<?=$j;?>">Keterangan</label>
                        <?php if ($data->id_tujuan==1) {?>
                            <label class="col-md-3" id="lpicek">PIC Eksternal</label>
                        <?php } ?>
                        <div class="col-sm-2">
                            <input type="text" id="imemo" name="imemo" class="form-control input-sm" value="<?= $data->i_memo;?>" maxlength="30" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="dmemo" name="dmemo" class="form-control input-sm tgl" value="<?= $data->d_memo;?>" readonly>
                        </div>
                        <div class="col-sm-<?=$j;?>">
                            <textarea type="text" readonly="" name="eremarkh" class="form-control input-sm" maxlength="250"><?= $data->e_remark;?></textarea>
                        </div>
                        <?php if ($data->id_tujuan==1) {?>
                            <div class="col-sm-3" id="dpicek">
                                <input type="text" id="piceksternal" name="piceksternal" class="form-control input-sm" readonly="" value="<?= $data->e_pic_eks;?>">
                            </div>
                        <?php } ?>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
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
                        <th class="text-center" width="3%">No</th>
                        <th class="text-center" width="10%">Kode</th>
                        <th class="text-center" width="35%">Nama Barang</th>
                        <th class="text-center" width="10%">Warna</th>
                        <th class="text-center" width="10%">Saldo</th>
                        <th class="text-center" width="7%">Qty</th>
                        <th class="text-center">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    foreach ($datadetail as $key) {
                        $i++;?>
                        <tr>
                            <td class="text-center">
                                <spanx id="snum<?= $i ;?>"><?= $i;?></spanx>
                            </td>
                            <td><?= $key->i_product_base;?></td>
                            <td><?= $key->e_product_basename;?></td>
                            <td><?= $key->e_color_name;?></td>
                            <td class="text-right">0</td>
                            <td class="text-right"><?= $key->n_quantity;?></td>
                            <td><?= $key->e_remark;?></td>
                        </tr>
                        <?php 
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php } ?>