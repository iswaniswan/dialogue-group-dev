<style>
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
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye fa-lg"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?=$title_list;?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-sm-12">
                    <div class="form-group row">
                        <label class="col-sm-3">Bagian Pembuat</label>
                        <label class="col-sm-3">Nomor Dokumen</label>
                        <label class="col-sm-3">Tanggal Dokumen</label>
                        <label class="col-sm-3">Tujuan Pengiriman</label>
                        <div class="col-sm-3">
                            <input type="text" readonly="" name="ebagianname" id="ebagianname" class="form-control input-sm" value="<?= $data->e_bagian;?>">
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" name="ipp" id="ipp" readonly="" onkeyup="gede(this);" placeholder="PP-2010-000001" maxlength="16" class="form-control input-sm" value="<?= $data->i_document;?>" aria-label="Text input with dropdown button">
                            </div>
                            <span class="notekode" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="dpp" name="dpp" class="form-control input-sm date" required="" readonly value="<?= date('d-m-Y', strtotime($data->d_document)); ?>">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="dbp" name="dbp" class="form-control input-sm date" required="" readonly value="<?= $data->e_bagian_name;?> | <?= $data->name ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea readonly="" class="form-control input-sm" name="eremark"><?= $data->e_remark;?></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
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
                        <th>Kode</th>
                        <th>Nama Material</th>
                        <th>Satuan</th>
                        <th class="text-right">Jml</th>
                        <!-- <th class="link">Supplier</th>
                        <th class="text-right link">Harga Supp</th> -->
                        <!-- <th class="text-right">Harga Adj</th> -->
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 0;
                    if ($detail) {
                        foreach ($detail as $row) {
                            $i++;?>
                            <tr>
                                <td class="text-center"><?= $i; ?></td>
                                <td><?= $row->i_material; ?></td>
                                <td><?= $row->e_material_name; ?></td>
                                <td><?= $row->e_satuan_name; ?></td>
                                <td class="text-right"><?= $row->n_quantity; ?></td>
                                <!-- <td><?= $row->i_supplier. " - " . $row->e_supplier_name; ?></td>
                                <td class="text-right"><?= $row->v_price; ?></td> -->
                                <!-- <td class="text-right"><?= $row->v_price_adj; ?></td> -->
                                <td><?= $row->e_remark; ?></td>
                            </tr>
                        <?php } 
                    }?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('.select2').select2();
        fixedtable($('.table'));
    })
</script>