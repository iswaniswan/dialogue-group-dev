<style type="text/css">
    .bold{
        font-weight: bold;
    }
</style>
<form id="cekinputan">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <i class="fa fa-check"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
                </div>
                <div class="panel-body table-responsive">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-3">Bagian Pembuat</label>
                            <label class="col-md-3">Nomor Dokumen</label>
                            <label class="col-md-2">Tanggal Dokumen</label>
                            <label class="col-md-4">Customer</label>
                            <div class="col-sm-3">
                                <input type="text" readonly="" class="form-control input-sm" value="<?= $data->e_bagian_name;?>">
                            </div>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="hidden" name="id" id="id" value="<?= $data->id;?>">
                                    <input type="text" readonly="" class="form-control input-sm" value="<?= $data->i_document;?>">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control input-sm date" readonly value="<?= $data->d_document;?>">
                            </div>
                            <div class="col-sm-4">
                                <input type="text" class="form-control input-sm" readonly value="<?= $data->e_customer_name.' ('.$data->i_customer.')';?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3">Nomor Referensi</label>
                            <label class="col-md-2">Tanggal Referensi</label>
                            <label class="col-md-7">Keterangan</label>                            
                            <div class="col-sm-3">
                                <input type="text" class="form-control input-sm" readonly value="<?= $data->i_referensi;?>">
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control input-sm" readonly value="<?= $data->d_referensi;?>">
                            </div>
                            <div class="col-sm-7">
                                <textarea id="eremarkh" name="eremarkh" class="form-control" readonly><?= $data->e_remark;?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                                <button type="button" class="btn btn-warning btn-rounded btn-sm" onclick="statuschange('<?= $folder."','".$id;?>','1','<?= $dfrom."','".$dto;?>');"> <i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;Change Requested</button>&nbsp;
                                <button type="button" class="btn btn-danger btn-rounded btn-sm"  onclick="statuschange('<?= $folder."','".$id;?>','4','<?= $dfrom."','".$dto;?>');"> <i class="fa fa-times"></i>&nbsp;&nbsp;Reject</button>&nbsp;
                                <button type="button" class="btn btn-success btn-rounded btn-sm" onclick="approve();"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Approve</button>
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
                <h3 class="box-title m-b-0">Detail Barang</h3>
            </div>
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table id="tabledatay" class="table color-table inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                        <thead>
                            <tr>
                                <th class="text-center" width="3%">No</th>
                                <th class="text-center">Kode</th>
                                <th class="text-center">Nama Barang</th>
                                <th class="text-center">Qty</th>
                                <th class="text-center">Harga</th>
                                <th class="text-center">Disc 1(%)</th>
                                <th class="text-center">Disc 2(%)</th>
                                <th class="text-center">Disc 3(%)</th>
                                <th class="text-center">Disc (Rp.)</th>
                                <th class="text-center">Total</th>
                                <th class="text-center">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="bold">
                            <?php foreach ($datadetail as $key) { $i++; ?>
                                <tr>
                                    <td class="text-center"><?=$i;?></td>
                                    <td><?= $key->i_product;?> <input type="hidden" name="i_product<?=$i;?>" id="i_product<?=$i;?>" value="<?= $key->i_product;?>"> </td>
                                    <td><?= $key->e_product;?></td>
                                    <td class="text-right">
                                        <?= $key->n_quantity;?>
                                        <input type="hidden" name="qty<?=$i;?>" id="qty<?=$i;?>" value="<?= $key->n_quantity;?>">
                                        <input type="hidden" name="qtysisa<?=$i;?>" id="qtysisa<?=$i;?>" value="<?= $key->n_quantity_reff;?>">
                                    </td>
                                    <td class="text-right"><?= number_format($key->v_price);?></td>
                                    <td class="text-right"><?= $key->n_diskon1;?></td>
                                    <td class="text-right"><?= $key->n_diskon2;?></td>
                                    <td class="text-right"><?= $key->n_diskon3;?></td>
                                    <td class="text-right"><?= number_format($key->v_diskon_tambahan);?></td>
                                    <td class="text-right"><?= number_format($key->v_total);?></td>
                                    <td><?= $key->e_remark;?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot class="bold">
                            <tr>
                                <td class="text-right" colspan="9">Total :</td>
                                <td class="text-right"><?= number_format($data->v_kotor);?></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="text-right" colspan="9">Diskon :</td>
                                <td class="text-right"><?= number_format($data->v_diskon);?></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="text-right" colspan="9">DPP :</td>
                                <td class="text-right"><?= number_format($data->v_dpp);?></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="text-right" colspan="9">PPN (10%) :</td>
                                <td class="text-right"><?= number_format($data->v_ppn);?></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="text-right" colspan="9">Grand Total :</td>
                                <td class="text-right"><b><?= number_format($data->v_bersih);?></b></td>
                                <td></td>
                            </tr>
                        </tfoot>
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
<script>
    /*----------  APPROVE DOKUMEN  ----------*/ 
    function approve() {
        var data = [];
        for (var i = 1; i <= $('#jml').val(); i++) { 
            if (parseInt($('#qty'+i).val()) > parseInt($('#qtysisa'+i).val())) {
                swal('Maaf :(','Quantity Kode : '+$('#iproduct'+i).val()+' Tidak Boleh lebih dari Qty Sisa '+$('#qtysisa'+i).val()+' !','error');
                data.push("lebih");
            } else {
                data.push("oke");
            }
        }

        if (data.includes("lebih") == false) {
            statuschange('<?= $folder."','".$id;?>','6','<?= $dfrom."','".$dto;?>');
        }           
    }
</script>