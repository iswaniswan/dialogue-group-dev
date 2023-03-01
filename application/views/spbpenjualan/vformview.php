<style type="text/css">
    .pudding{
        padding-left: 3px;
        padding-right: 3px;
    }
    .bold{
        font-weight: bold;
    }
    .font12{
        font-size: 12px !important;
        font-weight: 500 !important;
    }
    .nowrap {
        white-space:nowrap !important;
    }

    .toggle-button-cover
    {
        display: table-cell;
        position: relative;
        width: 125px;
        box-sizing: border-box;
    }

    .button-cover
    {
        height: 30px;
        margin: 2px;
        background-color: #fff;
        box-shadow: 0 10px 20px -8px #c5d6d6;
        border-radius: 4px;
    }

    .button-cover:before
    {
        counter-increment: button-counter;
        content: counter(button-counter);
        position: absolute;
        right: 0;
        bottom: 0;
        color: #d7e3e3;
        font-size: 12px;
        line-height: 1;
        padding: 5px;
    }

    .button-cover, .knobs, .layer
    {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
    }

    .button
    {
        position: relative;
        top: 50%;
        width: 120px;
        height: 36px;
        margin: -20px auto 0 auto;
        overflow: hidden;
    }

    .button.r, .button.r .layer
    {
        border-radius: 100px;
    }

    .button.b2
    {
        border-radius: 2px;
    }

    .checkbox
    {
        position: relative;
        width: 100%;
        height: 100%;
        padding: 0;
        margin: 0;
        opacity: 0;
        cursor: pointer;
        z-index: 3;
    }

    .knobs
    {
        z-index: 2;
    }

    .layer
    {
        width: 100%;
        background-color: #ebf7fc;
        transition: 0.3s ease all;
        z-index: 1;
    }

    /* Button 10 */
    #button-10 .knobs:before, #button-10 .knobs:after, #button-10 .knobs span
    {
        position: absolute;
        width: 54px;
        height: 50px;
        font-size: 10px;
        font-weight: bold;
        text-align: center;
        line-height: 1;
        padding: 9px 0px 9px 0px;
        border-radius: 2px;
        transition: 0.3s ease all;
    }

    #button-10 .knobs:before
    {
        content: '';
        left: 0px;
        background-color: #03A9F4;
    }

    #button-10 .knobs:after
    {
        content: 'Stok Daerah';
        right: 1px;
        color: #4e4e4e;
    }

    #button-10 .knobs span
    {
        display: inline-block;
        left: 0px;
        color: #fff;
        z-index: 1;
    }

    #button-10 .checkbox:checked + .knobs span
    {
        color: #4e4e4e;
    }

    #button-10 .checkbox:checked + .knobs:before
    {
        left: 65px;
        background-color: #F44336;
    }

    #button-10 .checkbox:checked + .knobs:after
    {
        color: #fff;
    }

    #button-10 .checkbox:checked ~ .layer
    {
        background-color: #fcebeb;
    }
</style>
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
                        <label class="col-md-4">Area</label>
                        <div class="col-sm-3">
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
                        <label class="col-md-3">Customer</label>
                        <label class="col-md-2">Kelompok Harga</label>
                        <label class="col-md-2">Salesman</label>
                        <label class="col-md-2">Penentuan Stok</label>
                        <label class="col-md-3">Nomor Referensi</label>

                        <div class="col-sm-3">
                            <input type="text" class="form-control input-sm" readonly value="<?= $data->e_customer_name;?>">
                        </div>              
                        <div class="col-sm-2">
                            <input type="text" readonly="" class="form-control input-sm" value="<?= $data->i_harga.' - '.$data->e_harga;?>">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" readonly="" class="form-control input-sm" value="<?= $data->e_sales;?>">
                        </div>

                        <div class="col-sm-2">
                            <div class="toggle-button-cover">
                              <div class="button-cover">
                                <div class="button b2" id="button-10">
                                  <input type="checkbox" class="checkbox" name="f_spb_stockdaerah" <?= ($data->f_spb_stockdaerah == 't') ? "checked" : "";?> disabled>
                                  <div class="knobs">
                                    <span>Stok Pusat</span>
                                  </div>
                                  <div class="layer"></div>
                                </div>
                              </div>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <input type="text" readonly="" class="form-control input-sm" value="<?= $data->i_referensi;?>">
                        </div>                                          
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Jenis Barang</label>                            
                        <label class="col-md-9">Keterangan</label>                            
                        <div class="col-sm-3">
                            <input type="text" readonly="" class="form-control input-sm" value="<?= $data->e_jenis_name;?>">
                        </div>
                        <div class="col-sm-9">
                            <textarea class="form-control" readonly=""><?= $data->e_remark;?></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
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
<?php $i = 0; if ($datadetail) {?>        
    <div class="white-box" id="detail">
        <div class="col-sm-12">
            <h3 class="box-title m-b-0">Detail Barang</h3>
        </div>
        <div class="col-sm-12">
            <div class="table-responsive">
                <table id="tabledatay" class="table color-table nowrap inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center" width="3%">No</th>
                            <th class="text-center">Kode</th>
                            <th class="text-center">Nama Barang</th>
                            <th class="text-center">Warna</th>
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
                    <tbody class="font12">
                        <?php foreach ($datadetail as $key) { $i++; ?>
                            <tr>
                                <td class="text-center"><spanx id="snum<?=$i;?>"><?=$i;?></spanx></td>
                                <td><?= $key->i_product_base;?></td>
                                <td><?= $key->e_product_basename;?></td>
                                <td><?= $key->e_color_name;?></td>
                                <td class="text-right"><?= $key->n_quantity;?></td>
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
                            <th class="text-right" colspan="10">Total :</th>
                            <th class="text-right"><?= number_format($data->v_kotor);?></th>
                            <th></th>
                        </tr>
                        <tr>
                            <th class="text-right" colspan="10">Diskon :</th>
                            <th class="text-right"><?= number_format($data->v_diskon);?></th>
                            <th></th>
                        </tr>
                        <tr>
                            <th class="text-right" colspan="10">DPP :</th>
                            <th class="text-right"><?= number_format($data->v_dpp);?></th>
                            <th></th>
                        </tr>
                        <tr>
                            <th class="text-right" colspan="10">PPN (<?= number_format($data->n_ppn);?>%) :</th>
                            <th class="text-right"><?= number_format($data->v_ppn);?></th>
                            <th></th>
                        </tr>
                        <tr>
                            <th class="text-right" colspan="10">Grand Total :</th>
                            <th class="text-right"><b><?= number_format($data->v_bersih);?></b></th>
                            <th></th>
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