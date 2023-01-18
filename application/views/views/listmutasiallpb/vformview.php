<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-info-circle"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <?php
            if ($isi) {
                foreach ($isi as $row) {
                    $periode = $row->e_mutasi_periode;
                }
            } else {
                $periode = $iperiode;
            }
            $a = substr($periode, 0, 4);
            $b = substr($periode, 4, 2);
            $periode = mbulan($b) . " - " . $a;
            ?>
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title m-b-0">Periode : <?= $periode;?></h3>
                    <?php
                    $rpselisih = 0;
                    $rpsaldoakhir = 0;
                    $rpstockopname = 0;
                    if ($isi) {
                        foreach ($isi as $row) {
                            $rpselisih = $rpselisih + (($row->n_saldo_stockopname - $row->n_saldo_akhir) * $row->v_product_retail);
                            $rpsaldoakhir = $rpsaldoakhir + ($row->n_saldo_akhir * $row->v_product_retail);
                            $rpstockopname = $rpstockopname + ($row->n_saldo_stockopname * $row->v_product_retail);

                        }
                    }?>
                    <h3 class="box-title m-b-0">Saldo Akhir : <?= 'Rp. '.number_format($rpsaldoakhir);?></h3>
                    <h3 class="box-title m-b-0">Saldo Stock Opname : <?= 'Rp.'.number_format($rpstockopname);?></h3>
                    <h3 class="box-title m-b-0">Selisih : <?= 'Rp. '.number_format($rpselisih);?></h3>
                    <div class="table-responsive">
                        <table data-show-columns="true" id="clmtable" data-height="500" data-mobile-responsive="true" class="table color-table success-table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Kode</th>
                                    <th class="text-center">Nama</th>
                                    <th class="text-center">Nama Toko</th>
                                    <th class="text-center">Saldo Awal</th>
                                    <th class="text-center">Dari Pusat</th>
                                    <th class="text-center">Dari Lang</th>
                                    <th class="text-center">Penjualan</th>
                                    <th class="text-center">ke Pusat</th>
                                    <th class="text-center">Sld Akhir</th>
                                    <th class="text-center">Sld Opname</th>
                                    <th class="text-center">Selisih</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($isi) {
                                    $i = 1;
                                    $selisih = 0;
                                    $tsaldoawal = 0;
                                    $tdrpst = 0;
                                    $tdrln = 0;
                                    $tpenj = 0;
                                    $tkpst = 0;
                                    $tsaldoakhir = 0;
                                    $tso = 0;
                                    $tselisih = 0;
                                    foreach ($isi as $row) {
                                        $selisih = ($row->n_saldo_stockopname) - $row->n_saldo_akhir;
                                        ?>
                                        <tr>
                                            <td class="text-center"><?= $i;?></td>
                                            <td><?= $row->i_product;?></td>
                                            <td><?= $row->e_product_name;?></td>
                                            <td><?= $row->e_customer_name;?></td>
                                            <td class="text-right"><?= $row->n_saldo_awal;?></td>
                                            <td class="text-right"><?= $row->n_mutasi_daripusat;?></td>
                                            <td class="text-right"><?= $row->n_mutasi_darilang;?></td>
                                            <td class="text-right"><?= $row->n_mutasi_penjualan;?></td>
                                            <td class="text-right"><?= $row->n_mutasi_kepusat;?></td>
                                            <td class="text-right"><?= $row->n_saldo_akhir;?></td>
                                            <td class="text-right"><?= $row->n_saldo_stockopname;?></td>
                                            <td class="text-right"><?= $selisih;?></td>
                                            <td class="text-center">
                                                <a href="#" onclick="show('<?= $folder;?>/cform/detail/<?= $iperiode.'/'.$row->i_customer.'/'.$row->i_product.'/'.$row->n_saldo_awal;?>','#main');"><i class="fa fa-pencil"></i></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <?php 
                                    $i++;
                                    $tsaldoawal = $tsaldoawal + $row->n_saldo_awal;
                                    $tdrpst = $tdrpst + $row->n_mutasi_daripusat;
                                    $tdrln = $tdrln + $row->n_mutasi_darilang;
                                    $tpenj = $tpenj + $row->n_mutasi_penjualan;
                                    $tkpst = $tkpst + $row->n_mutasi_kepusat;
                                    $tsaldoakhir = $tsaldoakhir + $row->n_saldo_akhir;
                                    $tso = $tso + $row->n_saldo_stockopname;
                                    $tselisih = $tselisih + $selisih;
                                    ?>
                                <?php } ?>
                                <tfoot>                                    
                                    <tr>
                                        <th class="text-center" colspan="4">Total</th>
                                        <th class="text-right"><?= $tsaldoawal;?></th>
                                        <th class="text-right"><?= $tdrpst;?></th>
                                        <th class="text-right"><?= $tdrln;?></th>
                                        <th class="text-right"><?= $tpenj;?></th>
                                        <th class="text-right"><?= $tkpst;?></th>
                                        <th class="text-right"><?= $tsaldoakhir;?></th>
                                        <th class="text-right"><?= $tso;?></th>
                                        <th class="text-right"><?= $tselisih;?></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            <?php }?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>