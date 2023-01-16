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

    .font-11 {
        padding-left: 3px;
        padding-right: 3px;
        font-size: 11px;
        height: 20px;
    }

    .font-12 {
        padding-left: 3px;
        padding-right: 3px;
        font-size: 12px;
    }

    .nowrap {
        white-space: nowrap !important;
    }
</style>
<form id="cekinputan">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <i class="fa fa-eye fa-lg"></i> &nbsp; <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp;
                        <?= $title_list; ?></a>
                </div>
                <div class="panel-body">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-3">Bagian Pembuat</label>
                            <label class="col-md-3">Nomor Dokumen</label>
                            <label class="col-md-3">Tanggal Dokumen</label>
                            <label class="col-md-3">Periode Forecast</label>
                            <div class="col-sm-3">
                                <input type="text" readonly="" class="form-control input-sm" value="<?= $data->e_bagian_name; ?>">
                            </div>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="hidden" name="id" id="id" value="<?= $id; ?>">
                                    <input type="text" name="idocument" required="" id="ibudgeting" readonly="" class="form-control input-sm" value="<?= $data->i_document; ?>">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <input type="text" class="form-control input-sm date" readonly value="<?= str_replace("  ", "", $data->ddocument); ?>">
                            </div>
                            <div class="col-sm-3">
                                <input type="text" class="form-control input-sm" readonly value="<?= $this->fungsi->mbulan($data->bulan) . ' ' . $data->tahun; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-12">Keterangan</label>
                            <div class="col-sm-12">
                                <textarea id="eremark" name="eremark" class="form-control" readonly><?= $data->e_remark; ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
 
    <?php $x = 0;
    if ($datadetaill) { ?>
        <div class="white-box" id="detail">
            <div class="col-sm-6">
                <h3 class="box-title m-b-0">Detail Item Material</h3>
            </div>
            <div class="col-sm-12">
                <!-- <div class="table-multi-columns"> -->
                 
                <div class="table_fixed" style="width: 100%; max-height: 500px;">
                    <table id="tabledatay" class="table color-table nowrap inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                        <thead>
                            <tr>
                                <th class="text-center" width="3%;">No</th>
                                <th>Kode</th>
                                <th>Nama Material</th>
                               <th class="text-center col-1">Kebutuhan <br>
                                    <i class="fa fa-question-circle fa-lg ml-2" tabindex="0" data-trigger="focus" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" title="Hasil konversi dari fc produk ke material"></i>
                                </th>
                                <th class="text-center col-1">Acc Pelengkap <br>
                                    <i class="fa fa-question-circle fa-lg ml-2" tabindex="0" data-trigger="focus" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" title="Khusus Acc Packing , max(fc berjalan, do) + fc dist - Stok gudang jadi - Stok packing"></i>
                                </th>
                                <th class="text-center col-1">Stok Gudang <br>
                                    <i class="fa fa-question-circle fa-lg ml-2" tabindex="0" data-trigger="focus" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" title="Stok terupdate pada saat budgeting qty dibuat"></i>
                                </th>
                                <th class="text-center col-1">Sisa Schedule <br>Belum Terkirim <br>
                                    <i class="fa fa-question-circle fa-lg ml-2" tabindex="0" data-trigger="focus" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" title="Sisa schedule yang belum terkirim"></i>
                                </th>
                                <th class="text-center col-1">Total Perhitungan <br>Kebutuhan <br>
                                    <i class="fa fa-question-circle fa-lg ml-2" tabindex="0" data-trigger="focus" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" title="Kebutuhan + acc pelengkap - stok gudang + sisa schedule"></i>
                                </th>
                                <th class="col-1">Satuan <br>Pemakaian</th>
                                <th class="text-center col-1">OP Sisa <br>
                                    <i class="fa fa-question-circle fa-lg ml-2" tabindex="0" data-trigger="focus" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" title="Sisa OP Belum Diterima"></i>
                                </th>
                                <th class="text-center" width="10%;">Budgeting <br> Perhitungan</th>
                                <th class="text-center" width="7%;">Up Qty</th>
                                <th class="text-center" width="10%;">Aktual</th>
                                <th>Satuan <br> Pembelian</th>
                                <th width="12%;">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 0;
                            $group = "";
                            foreach ($datadetaill as $row) {
                                $x++;
                                $no++;
                                 $budgeting = $row->kebutuhan - ($row->mutasi) + $row->n_acc_pelengkap + $row->estimasi ;
                                // if ($row->e_operator!=null) {
                                //     $hitungan = my_operator($row->kebutuhan,$row->n_faktor,$row->e_operator);
                                // }else{
                                //     $hitungan = $row->kebutuhan;
                                // }
                                // /* $hitungan = $row->kebutuhan; */

                                // $budgeting = abs($row->mutasi-$row->estimasi - $hitungan-$row->op_sisa) ;
                                if ($group!=$row->e_nama_group_barang) {?>
                                    <tr class="table-active">
                                        <td colspan="14"><b><?= $row->e_nama_group_barang;?></b></td>
                                    </tr>
                                <?php }
                                $group = $row->e_nama_group_barang;
                            ?>
                                <tr>
                                    <td class="text-center"><?= $no; ?></td>
                                    <td><?= $row->i_material; ?></td>
                                    <td><?= ucwords(strtolower($row->e_material_name)); ?></td>
                                    <td class="text-right"><?= number_format($row->kebutuhan, 2); ?></td>
                                    <td class="text-right"><?= number_format($row->n_acc_pelengkap, 2); ?></td>
                                    <td class="text-right"><?= number_format($row->mutasi, 2); ?></td>
                                    <td class="text-right"><?= number_format($row->estimasi, 2); ?></td>
                                    <td class="text-right"><?= number_format($budgeting, 2); ?></td>
                                    <td><?= ucwords(strtolower($row->e_satuan_name)); ?></td>
                                    <td class="text-right"><?= number_format($row->op_sisa, 2); ?></td>
                                    <td class="text-right"><?= number_format($row->n_budgeting_perhitungan, 2); ?></td>
                                    <td class="text-right"><?= number_format($row->persen_up, 2); ?></td>
                                    <td class="text-right"><?= number_format($row->n_budgeting, 2); ?></td>
                                    <td><?= ucwords(strtolower($row->e_satuan_konversi)); ?></td>
                                    <td><?= $row->e_remark; ?></td>
                                </tr>
                            <?php
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php } else { ?>
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
    <input type="hidden" name="jml_item" id="jml_item" value="<?= $x; ?>">

    <h5>Multi-Columns Freeze Table</h5>
<!--  <div class="table-responsive"> -->
   


</form>
<script>
    $(function() {
        popover();
        // $(".table").freezeTable({
        //   'columnNum' : 3,
        // });
        //console.log($('.table-responsive').children("table"));
        $(".table_fixed").freezeTable({
          'columnNum' : 3,
          'scrollable': true,
        });

        // $(".table-responsive").freezeTable({
        //   'columnNum' : 2,
        //   'scrollable': true,
        // });

       
        //fixedtable($('.table'));
    })
</script>