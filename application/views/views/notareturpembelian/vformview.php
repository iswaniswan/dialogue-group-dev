<style type="text/css">
    .select2-results__options{
        font-size:14px !important;
    }
    .select2-selection__rendered {
      font-size: 12px;
    }
    .pudding{
        padding-left: 3px;
        padding-right: 3px;
        font-size: 14px;
        background-color: #ddd;
    }
</style>
<form id="cekinputan">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
                </div>
                <div class="panel-body table-responsive">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-3">Bagian Pembuat</label>
                            <label class="col-md-3">Nomor Dokumen</label>
                            <label class="col-md-2">Tanggal Dokumen</label>
                            <label class="col-md-4">Supplier</label>
                            <div class="col-sm-3">
                                <input type="text" readonly="" class="form-control input-sm" value="<?= $data->e_bagian_name;?>">
                            </div>
                            <div class="col-sm-3">
                                <input type="hidden" name="id" id="id" value="<?= $data->id;?>">
                                <input type="text" readonly="" class="form-control input-sm" value="<?= $data->i_document;?>">
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control input-sm date" required="" readonly value="<?= $data->d_document;?>">
                            </div>
                            <div class="col-sm-4">
                                <input type="text" class="form-control input-sm" required="" readonly value="<?= $data->e_supplier_name; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-12">Keterangan</label>
                            <div class="col-sm-12">
                                <textarea class="form-control" readonly><?= $data->e_remark;?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $i = 0; if ($datadetail) {?>   
    <div class="white-box" id="detail">
        <div class="col-sm-6">
            <h3 class="box-title m-b-0">Detail Item</h3>
        </div>
        <div class="col-sm-12">
            <div class="table-responsive">
                <table id="tabledatay" class="table color-table inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center" width="3%;">No</th>
                            <th class="text-center" width="12%;">No. SJ</th>
                            <th class="text-center" width="11%;">Kode</th>
                            <th class="text-center" width="25%;">Nama Barang</th>
                            <th class="text-center" width="10%;">Satuan</th>
                            <th class="text-center" width="9%;">Harga</th>
                            <th class="text-center" width="8%;">Jml Retur Gudang</th>
                            <th class="text-center" width="9%;">Jml Retur</th>
                            <th class="text-center">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 0; $group = ""; foreach ($datadetail as $key) { 
                            $i++; $no++; 
                            if($group==""){ ?>
                                <tr class="pudding">
                                    <td colspan="9">Nomor Referensi : <b><?= $key->i_referensi;?></b> &nbsp;&nbsp;-&nbsp;&nbsp; Tanggal Referensi : <b><?= $key->d_referensi;?></b> &nbsp;&nbsp;-&nbsp;&nbsp; <b>(<?= $key->e_bagian_name;?>)</b></td>
                                </tr>
                                <?php 
                            }else{
                                if($group!=$key->i_referensi){?>
                                    <tr class="pudding">
                                        <td colspan="9">Nomor Referensi : <b><?= $key->i_referensi;?></b> &nbsp;&nbsp;-&nbsp;&nbsp; Tanggal Referensi : <b><?= $key->d_referensi;?></b> &nbsp;&nbsp;-&nbsp;&nbsp; <b>(<?= $key->e_bagian_name;?>)</b></td>
                                    </tr>
                                    <?php $no = 1; 
                                }
                            }
                            $group = $key->i_referensi;
                            ?>
                            <tr>
                                <td class="text-center"><?=$no;?></td>
                                <td><?= $key->i_sj_supplier;?></td>
                                <td><?= $key->i_material;?></td>
                                <td><?= $key->e_material_name;?></td>
                                <td><?= $key->e_satuan_name;?></td>
                                <td class="text-right"><?= number_format($key->v_price);?></td>
                                <td class="text-right"><?= $key->n_referensi;?></td>
                                <td class="text-right"><?= $key->n_retur;?></td>
                                <td><?= $key->e_remark;?></td>
                            </tr>
                            <?php 
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php }else{ ?>
        <div class="white-box">
            <div class="card card-outline-danger text-center text-dark">
                <div class="card-block">
                    <footer>
                        <cite title="Source Title"><b>Item Tidak Ada</b></cite>
                    </footer>
                </div>
            </div>
        </div>
    <?php } ?>
    <input type="hidden" name="jml" id="jml" value ="<?= $i;?>">
</form>