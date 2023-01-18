<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-check"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-2">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-2">Tanggal Batas Kirim</label>
                        <label class="col-md-3">Area</label>
                        <div class="col-md-2">
                            <select name="ibagian" id="ibagian" class="form-control select2" required="" disabled>
                                <?php if ($bagian) {
                                    foreach ($bagian as $row):?>
                                         <option value="<?= $row->i_bagian;?>" <?php if ($row->i_bagian == $data->i_bagian) {?> selected <?php } ?>>
                                            <?= $row->e_bagian_name;?>
                                        </option>
                                    <?php endforeach; 
                                } ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" value="<?= $id;?>">
                                <input type="text" name="idocument" id="idocument" readonly="" autocomplete="off" onkeyup="gede(this);" class="form-control input-sm" value="<?= $data->i_document;?>" aria-label="Text input with dropdown button">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <input type="text" id="ddocument" name="ddocument" class="form-control input-sm" required="" readonly value="<?= $data->d_document;?>">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="dsend" name="dsend" class="form-control input-sm" required="" readonly value="<?= $data->d_estimate;?>">
                        </div>
                        <div class="col-sm-3">
                            <select name="iarea" id="iarea" class="form-control select2" required="" disabled>
                                <option value="<?=$data->id_area;?>"><?=$data->e_area;?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Customer</label>
                        <label class="col-md-3">Kelompok Harga</label>
                        <label class="col-md-3">Salesman</label>
                        <label class="col-md-3">Referensi OP</label>                        
                        <div class="col-sm-3">
                            <select name="icustomer" id="icustomer" class="form-control select2" required="" disabled>
                                <option value="<?=$data->id_customer;?>"><?=$data->e_customer_name;?></option>
                            </select>
                            <input type="hidden" id="1ndiskonitem" name="1ndiskonitem" class="form-control" readonly>
                            <input type="hidden" id="2ndiskonitem" name="2ndiskonitem" class="form-control" readonly>
                            <input type="hidden" id="3ndiskonitem" name="3ndiskonitem" class="form-control" readonly>
                        </div>
                        <div class="col-sm-3">
                            <input type="hidden" id="kodeharga" name="kodeharga" class="form-control" value="<?=$data->id_harga_kode;?>" readonly>
                            <input type="text" id="ekodeharga" name="ekodeharga" class="form-control" value="<?=$data->e_harga;?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <select name="isales" id="isales" class="form-control select2" required="" disabled>
                                <option value="<?=$data->id_sales;?>"><?=$data->e_sales;?></option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="ireferensiop" name="ireferensiop" class="form-control" value="<?= $data->i_referensi_op;?>" readonly>                           
                        </div>                                            
                    </div>     
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-md-12">
                            <textarea id="eremarkh" name="eremarkh" class="form-control" readonly><?= $data->e_remark;?></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-12">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                            <button type="button" class="btn btn-warning btn-rounded btn-sm" onclick="statuschange('<?= $folder."','".$id;?>','1','<?= $dfrom."','".$dto;?>');"> <i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;Change Requested</button>&nbsp;
                            <button type="button" class="btn btn-danger btn-rounded btn-sm"  onclick="statuschange('<?= $folder."','".$id;?>','4','<?= $dfrom."','".$dto;?>');"> <i class="fa fa-times"></i>&nbsp;&nbsp;Reject</button>&nbsp;
                            <button type="button" class="btn btn-success btn-rounded btn-sm" onclick="statuschange('<?= $folder."','".$id;?>','6','<?= $dfrom."','".$dto;?>');"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Approve</button>&nbsp;
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="white-box" id="detail">
    <div class="col-sm-3">
        <h3 class="box-title m-b-0">Detail Barang</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table dark-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Kode Barang</th>
                        <th class="text-center">Nama Barang</th>
                        <th class="text-center">Quantity</th>
                        <th class="text-center">Harga</th>
                        <th class="text-center">Diskon 1 (%)</th>
                        <th class="text-center">Diskon 2 (%)</th>
                        <th class="text-center">Diskon 3 (%)</th>
                        <th class="text-center">Diskon Tambahan (Rp.)</th>
                        <th class="text-center">Total</th>
                        <th class="text-center">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                    $i = 0;
                    if ($datadetail) {
                        foreach ($datadetail as $row) {
                            $totalnet = $row->v_total - $row->v_total_discount;
                            $i++;?>
                            <tr>
                                <td class="text-center"><spanx id="snum<?=$i;?>"><?= $i;?></spanx></td>
                                <td>
                                    <input style="width:150px;" type="text" readonly  id="iproduct<?= $i;?>" class="form-control" name="iproduct[]" value="<?= $row->i_product_base;?>">
                                    <input style="width:150px;" readonly id="idproduct<?= $i;?>" type="hidden" class="form-control" name="idproduct[]" value="<?= $row->id_product;?>">
                                </td>
                                <td>
                                    <select style="width:350px;" id="eproduct<?=$i;?>" class="form-control select2" name="eproduct[]" disabled>
                                        <option value="<?= $row->id_product;?>"><?= $row->e_product_basename;?></option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" value="<?= $row->n_quantity;?>" id="nquantity<?=$i;?>" class="form-control text-right" autocomplete="off" name="nquantity[]"  readonly>
                                </td>
                                <td>
                                    <input style="width:100px;" type="text" readonly  id="vharga<?=$i;?>" class="form-control" name="vharga[]" value="<?=  ($row->v_price);?>">
                                </td>
                                <td>
                                    <input style="width:100px;" type="text" readonly  id="ndiskon<?=$i;?>" class="form-control" name="ndiskon[]" value="<?= $row->n_diskon1;?>">
                                    <input style="width:100px;" readonly  id="vdiskon<?=$i;?>" type="hidden" class="form-control" name="vdiskon[]" value="<?= $row->v_diskon1;?>">
                                </td>
                                <td>
                                    <input style="width:100px;" type="text" readonly  id="ndiskonn<?=$i;?>" class="form-control" name="ndiskonn[]" value="<?= $row->n_diskon2;?>">
                                    <input style="width:100px;" readonly  id="vdiskonn<?=$i;?>" type="hidden" class="form-control" name="vdiskonn[]" value="<?= $row->v_diskon2;?>">
                                </td>
                                <td>
                                    <input style="width:100px;" type="text" readonly  id="ndiskonnn<?=$i;?>" class="form-control" name="ndiskonnn[]" value="<?= $row->n_diskon3;?>">
                                    <input style="width:100px;" type="hidden" readonly  id="vdiskonnn<?=$i;?>" class="form-control" name="vdiskonnn[]" value="<?= $row->v_diskon3;?>">
                                </td>
                                <td>
                                    <input style="width:100px;" type="text" id="adddiskon<?=$i;?>" class="form-control" name="adddiskon[]" value="<?= $row->v_diskontambahan;?>" readonly>
                                </td>
                                <td>
                                    <input style="width:100px;" type="text" id="vtotal<?=$i;?>" class="form-control" name="vtotal[]" value="<?= $row->v_total;?>" readonly>
                                    <input style="width:100px;" type="hidden" id="vtotaldiskon<?=$i;?>" class="form-control" name="vtotaldiskon[]" value="<?= $row->v_total_discount;?>" readonly>
                                    <input style="width:100px;" type="hidden" id="vtotalnet<?=$i;?>" class="form-control" name="vtotalnet[]" value="<?=$totalnet?>" readonly>
                                </td>
                                <td>
                                    <input style="width:300px;" type="text" id="eremark<?=$i;?>" class="form-control input-sm" value="<?= $row->e_remark;?>" name="eremark[]" readonly>
                                </td>
                            </tr>
                        <?php } 
                    }?>
                    <input type="hidden" name="jml" id="jml" value ="<?= $i;?>">
                </tbody>
                <tfoot>
                    <?php 
                        $grandtotal = $data->v_dpp + $data->v_ppn;
                    ?>
                    <tr>
                        <td class="text-right" colspan="8">Total</td>
                        <td>:</td>
                        <td><input type="text" id="nkotor" name="nkotor" class="form-control input-sm" readonly value=<?= $data->v_kotor;?> ></td>
                    </tr>
                    <tr>
                        <td class="text-right" colspan="8">Diskon</td>
                        <td>:</td>
                        <td><input type="text" id="ndiskontotal" name="ndiskontotal" class="form-control input-sm" readonly value=<?= $data->v_diskon;?> ></td>
                    </tr>
                    <tr>
                        <td class="text-right" colspan="8">DPP</td>
                        <td>:</td>
                        <td><input type="text" id="vdpp" name="vdpp" class="form-control input-sm" readonly value=<?= $data->v_dpp;?> ></td>
                    </tr>
                    <tr>
                        <td class="text-right" colspan="8">PPN (10%)</td>
                        <td>:</td>
                        <td><input type="text" id="vppn" name="vppn" class="form-control input-sm" readonly value=<?= $data->v_ppn;?> ></td>
                    </tr>
                    <tr>
                        <td class="text-right" colspan="8">Grand Total</td>
                        <td>:</td>
                        <td><input type="text" id="nbersih" name="nbersih" class="form-control input-sm" readonly value=<?= $grandtotal;?> ></td>
                    </tr>
                    </tfoot>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('.select2').select2();
    });
</script>