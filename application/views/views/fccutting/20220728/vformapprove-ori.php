<style type="text/css">
.select2-results__options {
    font-size: 14px !important;
}

.select2-selection__rendered {
    font-size: 12px;
}

.pudding {
    padding-left: 3px;
    padding-right: 3px;
    font-size: 14px;
    background-color: #ddd;
}

.font-11{
    padding-left: 3px;
    padding-right: 3px;
    font-size: 11px;  
    height: 20px;  
}
.font-12{
    padding-left: 3px;
    padding-right: 3px;
    font-size: 12px;    
}
</style>
<form id="cekinputan">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <i class="fa fa-check"></i> &nbsp; <?= $title; ?> <a href="#"
                        onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                        class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp;
                        <?= $title_list; ?></a>
                </div>
                <div class="panel-body table-responsive">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-3">Bagian Pembuat</label>
                            <label class="col-md-3">Nomor Dokumen</label>
                            <label class="col-md-3">Tanggal Dokumen</label>
                            <label class="col-md-3">Periode Forecast</label>
                            <div class="col-sm-3">
                                <input type="text"  readonly=""
                                        class="form-control input-sm" value="<?= $data->e_bagian_name;?>">
                            </div>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="hidden" name="id" id="id" value="<?= $id;?>">
                                    <input type="text" name="idocument" required="" id="ibudgeting" readonly=""
                                        class="form-control input-sm" value="<?= $data->i_document;?>">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <input type="text" class="form-control input-sm date" readonly
                                    value="<?= $data->ddocument;?>">
                            </div>
                            <div class="col-sm-3">
                                <input type="text" class="form-control input-sm" readonly
                                    value="<?= $this->fungsi->mbulan($data->bulan).' '.$data->tahun; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-12">Keterangan</label>
                            <div class="col-sm-12">
                                <textarea id="eremark" name="eremark" class="form-control" readonly><?= $data->e_remark;?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                                <button type="button" class="btn btn-warning btn-rounded btn-sm" onclick="statuschange('<?= $folder."','".$id;?>','3','<?= $dfrom."','".$dto;?>');"> <i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;Change Requested</button>&nbsp;
                                <button type="button" class="btn btn-danger btn-rounded btn-sm" onclick="statuschange('<?= $folder."','".$id;?>','4','<?= $dfrom."','".$dto;?>');"> <i class="fa fa-times"></i>&nbsp;&nbsp;Reject</button>&nbsp;
                                <button type="button" class="btn btn-success btn-rounded btn-sm" onclick="statuschange('<?= $folder."','".$id;?>','6','<?= $dfrom."','".$dto;?>');"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Approve</button>
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
                <table id="tabledatay" class="table color-table inverse-table table-bordered class" cellpadding="8"
                    cellspacing="1" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center" width="3%;">No</th>
                            <th class="text-center" width="12%;">Kode Material</th>
                            <th class="text-center">Nama Material</th>
                            <th class="text-center" width="12%;">Pemakaian</th>
                            <th class="text-center" width="12%;">Kebutuhan</th>
                            <th class="text-center" width="14%;">Satuan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 0; $group = ""; foreach ($datadetail as $key) { 
                            $i++; $no++; 
                            if($group==""){ ?>
                        <tr class="pudding">
                            <td colspan="4">Barang Jadi : <b><?= $key->i_product_base;?>
                                    &nbsp;<?= ucwords(strtolower($key->e_product_basename));?>&nbsp;<?= ucwords(strtolower($key->e_color_name));?></b>
                            </td>
                            <td class="text-right">Qty : <b><?= $key->n_quantity;?></b></td>
                            <td></td>
                        </tr>
                        <?php 
                            }else{
                                if($group!=$key->id_product_base){?>
                        <tr class="pudding">
                            <td colspan="4">Barang Jadi : <b><?= $key->i_product_base;?>
                                    &nbsp;<?= ucwords(strtolower($key->e_product_basename));?>&nbsp;<?= ucwords(strtolower($key->e_color_name));?></b>
                            </td>
                            <td class="text-right">Qty : <b><?= $key->n_quantity;?></b></td>
                            <td></td>
                        </tr>
                        <?php $no = 1; 
                                }
                            }
                            $group = $key->id_product_base;
                            ?>
                        <tr>
                            <td class="text-center"><?=$no;?></td>
                            <td><?= $key->i_material;?></td>
                            <td><?= ucwords(strtolower($key->e_material_name));?></td>
                            <td class="text-right"><?= number_format($key->pemakaian,3);?></td>
                            <td class="text-right"><?= number_format($key->kebutuhan,3);?></td>
                            <td><?= ucwords(strtolower($key->e_satuan_name));?></td>
                            <input type="hidden" name="id_product_base<?=$i;?>" value="<?= $key->id_product_base;?>">
                            <input type="hidden" name="nilai_base<?=$i;?>" value="<?= $key->n_quantity;?>">
                            <input type="hidden" name="id_material<?=$i;?>" value="<?= $key->id_material;?>">
                            <input type="hidden" name="nilai_pemakaian<?=$i;?>" value="<?= $key->pemakaian;?>">
                            <input type="hidden" name="nilai_kebutuhan<?=$i;?>" value="<?= $key->kebutuhan;?>">
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
    <input type="hidden" name="jml" id="jml" value="<?= $i;?>">
    <?php $i = 0; if ($bisbisan) {?>
    <div class="white-box" id="detail">
        <div class="col-sm-6">
            <h3 class="box-title m-b-0">Detail Bis Bisan</h3>
        </div>
        <div class="col-sm-12">
            <div class="table-responsive">
                <table id="tabledatay" class="table color-table inverse-table table-bordered class" cellpadding="8"
                    cellspacing="1" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center" width="3%;">No</th>
                            <th width="12%;">Kode Material</th>
                            <th>Nama Material</th>
                            <th>Jenis Potong</th>
                            <th class="text-right" width="12%;">Ukuran</th>
                            <th class="text-right" width="12%;">Pemakaian</th>
                            <th class="text-right" width="12%;">Kebutuhan</th>
                            <th width="14%;">Satuan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 0; foreach($bisbisan AS $key){ $i++;?>
                            <tr>
                                <td class="text-center"><?= $i;?></td>
                                <td><?= $key->i_material;?></td>
                                <td><?= $key->e_material_name;?></td>
                                <td><?= $key->e_jenis_potong;?></td>
                                <td class="text-right"><?= number_format($key->n_bisbisan,3);?></td>
                                <td class="text-right"><?= number_format($key->pemakaian,3);?></td>
                                <td class="text-right"><?= number_format($key->kebutuhan,3);?></td>
                                <td><?= $key->e_satuan_name;?></td>
                            </tr>
                        <?php } ?>
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
                    <cite title="Source Title"><b>Item Bisbisan Tidak Ada</b></cite>
                </footer>
            </div>
        </div>
    </div>
    <?php } ?>
    <?php $x = 0; if ($datadetaill) {?>
    <div class="white-box" id="detail">
        <div class="col-sm-6">
            <h3 class="box-title m-b-0">Detail Item Material</h3>
        </div>
        <div class="col-sm-12">
            <div class="table-responsive">
                <table id="tabledatay" class="table color-table font-11 inverse-table table-bordered class" cellpadding="8"
                    cellspacing="1" width="100%">
                    <thead class="font-12">
                        <tr>
                            <th class="text-center" width="3%;">No</th>
                            <th>Kode</th>
                            <th>Nama Material</th>
                            <th>Satuan</th>
                            <th class="text-right">Stok Awal</th>
                            <th class="text-right">Estimasi</th>
                            <th class="text-right">Kebutuhan</th>
                            <th class="text-right">OP Sisa</th>
                            <th class="text-right" width="7%;">% Up</th>
                            <th class="text-right" width="10%;">Budgeting</th>
                            <th>Konversi</th>
                            <th width="12%;">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="font-11">
                        <?php $no = 0; $group = ""; foreach ($datadetaill as $row) { 
                            $x++; $no++;
                            if ($row->e_operator!=null) {
                                $hitungan = my_operator($row->kebutuhan,$row->n_faktor,$row->e_operator);
                            }else{
                                $hitungan = $row->kebutuhan;
                            }
                            /* $hitungan = $row->kebutuhan; */

                            $budgeting = abs($row->mutasi-$row->estimasi - $hitungan-$row->op_sisa) ;
                        ?>
                        <tr>
                            <td class="text-center"><?=$no;?></td>
                            <td><?= $row->i_material;?></td>
                            <td><?= ucwords(strtolower($row->e_material_name));?></td>
                            <td><?= ucwords(strtolower($row->e_satuan_name));?></td>
                            <td class="text-right"><?= $row->mutasi;?></td>
                            <td class="text-right"><?= $row->estimasi;?></td>
                            <td class="text-right"><?= number_format($row->kebutuhan,3);?></td>
                            <td class="text-right"><?= $row->op_sisa;?></td>
                            <td class="text-right"><?= $row->persen_up;?></td>
                            <td class="text-right"><?= number_format($row->n_budgeting,3);?></td>
                            <td><?= ucwords(strtolower($row->e_satuan_konversi));?></td>
                            <td><?= $row->e_remark;?></td>
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
    <input type="hidden" name="jml_item" id="jml_item" value="<?= $x;?>">
</form>