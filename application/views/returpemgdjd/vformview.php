<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/index/<?=$dfrom;?>/<?=$dto;?>','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?=$title_list;?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row ">
                        <label class="col-md-4">Bagian Pembuat</label>
                        <label class="col-md-4">No Dokumen</label>
                        <label class="col-md-4">Tanggal Dokumen</label>
                        <div class="col-sm-4">
                            <select name="ikodemaster" id="ikodemaster" class="form-control select2" disabled="true">
                                <option value="<?=$data->i_bagian;?>"><?=$data->e_bagian_name;?></option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" name="iretur" id="iretur" class="form-control" value="<?=$data->i_retur_beli;?>" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id="dretur" name="dretur" class="form-control" value="<?= $data->d_retur; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Supplier</label>
                        <label class="col-md-4">No Referensi</label>
                        <label class="col-md-4">Tanggal Referensi</label>
                        <div class="col-sm-4">
                            <select name="isupplier" id="isupplier" class="form-control select2" onchange="getnota(this.value);" disabled="true">
                                <option value="<?=$data->i_supplier;?>"><?=$data->e_supplier_name;?></option>
                            </select>
                            <input type="hidden" name="esupplier" id="esupplier" class="form-control" value="<?=$data->e_supplier_name;?>">
                        </div>
                        <div class="col-sm-4">
                            <select name="ifaktur" id="ifaktur" class="form-control select2" onchange="get(this.value);" disabled="true">
                                <option value="<?=$data->id_btb;?>"><?=$data->i_btb;?></option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id= "dnota" name="dnota" class="form-control" value="<?=$data->d_btb;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">    
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea type="text" id="eremark" name="eremark" class="form-control" value="" placeholder="Isi keterangan jika ada!" readonly><?=$data->e_remark;?></textarea>
                            <input class="form-control" type="hidden" id="vtotal" name="vtotal" value="<?=$data->v_total;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-8 col-sm-5">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/index/<?=$dfrom;?>/<?=$dto;?>","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="jml" id="jml" value="0">
<div class="white-box" id="detail">
    <div class="col-sm-5">
        <h3 class="box-title m-b-0">Detail Barang</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table dark-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
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
                    <?$i = 0;
                    if ($detail) {
                        foreach ($detail as $row) {
                            $i++;?>
                            <tr>
                                <td class="text-center"><?= $i; ?></td>
                                <td><?= $row->i_material; ?></td>
                                <td><?= $row->e_material_name; ?></td>
                                <td><?= $row->e_satuan_name; ?></td>
                                <td class="text-right"><?= $row->n_quantity_btb; ?></td>
                                <td class="text-right"><?= $row->n_quantity_retur; ?></td>
                                <td><?= $row->e_remark; ?></td>
                            </tr>
                        <?php } 
                    }?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date');
    });
</script>