<style type="text/css">
    .pudding{
        padding-left: 3px;
        padding-right: 3px;
    }
    .bold{
        font-weight: bold;
    }
</style>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-check"></i>  <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i> <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
            <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-4">Area</label>
                        <div class="col-sm-3">
                            <input type="hidden" name="id" id="id" value="<?= $id;?>">
                            <input type="hidden" name="dfrom" id="dfrom" value="<?= $dfrom;?>">
                            <input type="hidden" name="dto" id="dto" value="<?= $dto;?>">
                            <input type="text" readonly="" class="form-control input-sm" value="<?= $data->e_bagian_name;?>">
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" readonly="" class="form-control input-sm" value="<?= $data->i_document;?>">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" class="form-control input-sm date" readonly value="<?= $data->d_document;?>">
                        </div>
                        <div class="col-sm-4">
                            <input type="text" readonly="" class="form-control input-sm" value="<?= $data->e_area;?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Customer</label>
                        <label class="col-md-2">Kelompok Harga</label>
                        <label class="col-md-3">Salesman</label>
                        <label class="col-md-3">Nomor Referensi</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control input-sm" readonly value="<?= $data->e_customer_name;?>">
                        </div>              
                        <div class="col-sm-2">
                            <input type="text" readonly="" class="form-control input-sm" value="<?= $data->i_harga.' - '.$data->e_harga;?>">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" readonly="" class="form-control input-sm" value="<?= $data->e_sales;?>">
                        </div>                                            
                        <div class="col-sm-3">
                            <input type="text" readonly="" class="form-control input-sm" value="<?= $data->i_referensi;?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">Keterangan Approve</label>                            
                        <label class="col-md-6">Keterangan Not Approve</label>                            
                        <div class="col-sm-6">
                            <textarea class="form-control" autofocus placeholder="Alasan di Approve ..." id="e_note_approve"></textarea>
                        </div>
                        <div class="col-sm-6">
                            <textarea class="form-control" placeholder="Alasan tidak di Approve ..." id="e_not_note_approve"></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <div class="col-sm-12 text-center">
                            <button type="button" class="btn btn-success btn-rounded btn-sm mr-2" onclick="approve();"> <i class="fa fa-check-square-o mr-2"></i>Approve</button>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm mr-2" onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i class="fa fa-arrow-circle-left mr-2"></i>Kembali</button>
                            <button type="button" class="btn btn-warning btn-rounded btn-sm mr-2"  onclick="notapprove();"> <i class="fa fa-times mr-2"></i>Not Approve</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $i = 0; if ($datadetail) {?>       
    <input type="hidden" readonly="" class="form-control input-sm" value="<?= count($datadetail);?>" id="jml"> 
    <div class="white-box" id="detail">
        <div class="col-sm-12">
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
                                <td class="text-center"><spanx id="snum<?=$i;?>"><?=$i;?></spanx></td>
                                <td><?= $key->i_product_base;?>     <input type="hidden" name="i_product_base<?=$i;?>" id="i_product_base<?=$i;?>" value="<?= $key->i_product_base;?>"> </td>
                                <td><?= $key->e_product_basename;?></td>
                                <td class="text-right">
                                    <?= $key->n_quantity;?>
                                    <input type="hidden" name="qty<?=$i;?>" id="qty<?=$i;?>" value="<?= $key->n_quantity;?>">
                                    <input type="hidden" name="fc<?=$i;?>" id="fc<?=$i;?>" value="<?= $key->fc;?>">
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


<script type="text/javascript">
    function approve() {
        let note = $('#e_note_approve').val();
        let id = $('#id').val();
        let dfrom = $('#dfrom').val();
        let dto = $('#dto').val();
        if(note=='' || note==null){
            swal('Maaf :(','Alasan di Approve harus diisi!','error');
            $('#e_note_approve').focus();
        }else{
            let param = id+'|'+note;
            statuschange('<?= $folder;?>',''+param+'','6',''+dfrom+'',''+dto+'');
        }
    }
    function notapprove() {
        let note = $('#e_not_note_approve').val();
        let id = $('#id').val();
        let dfrom = $('#dfrom').val();
        let dto = $('#dto').val();
        if(note=='' || note==null){
            swal('Maaf :(','Alasan tidak di Approve harus diisi!','error');
            $('#e_not_note_approve').focus();
        }else{
            let param = id+'|'+note;
            statuschange('<?= $folder;?>',''+param+'','5',''+dfrom+'',''+dto+'');
        }
    }
</script>