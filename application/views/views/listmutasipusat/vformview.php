<link href="<?= base_url();?>assets/plugins/bower_components/bootstrap-table/dist/bootstrap-table.min.css" rel="stylesheet" type="text/css" />
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-info-circle"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title m-b-0"><?= strtoupper($title).'-'.$istore.'('.$istorelocation.')';?></h3>
                    <?php 
                    if($isi){
                        foreach($isi as $row){
                            $periode=$row->e_mutasi_periode;
                        }
                    }else{
                        $periode=$iperiode;
                    }
                    $a=substr($periode,0,4);
                    $bln = substr($periode,4,2);
                    $b=substr($periode,4,2);
                    $periode=mbulan($b)." - ".$a;
                    ?>
                    <p class="text-muted m-b-30"><b>Periode : <?= $periode;?></b></p>
                    <?php
                    $selisih=0;
                    $saldoakhir=0;
                    $stockopname=0;
                    $rpselisih=0;
                    $rpsaldoakhir=0;
                    $rpstockopname=0;
                    if($isi){
                        foreach($isi as $row){
                            $selisih=$selisih+(($row->n_saldo_stockopname+$row->n_saldo_git+$row->n_git_penjualan)-$row->n_saldo_akhir);
                            $saldoakhir=$saldoakhir+$row->n_saldo_akhir;
                            $stockopname=$stockopname+($row->n_saldo_stockopname+$row->n_saldo_git+$row->n_git_penjualan);
                            $rpselisih=$rpselisih+((($row->n_saldo_stockopname+$row->n_saldo_git+$row->n_git_penjualan)-$row->n_saldo_akhir)*$row->v_product_retail);
                            $rpsaldoakhir=$rpsaldoakhir+($row->n_saldo_akhir*$row->v_product_retail);
                            $rpstockopname=$rpstockopname+(($row->n_saldo_stockopname+$row->n_saldo_git+$row->n_git_penjualan)*$row->v_product_retail);
                        }
                    }
                    ?>
                    <p class="text-muted">Saldo Akhir : <?= 'Rp. '.number_format($rpsaldoakhir);?></p>
                    <p class="text-muted">Saldo Stock Opname : <?= 'Rp.'.number_format($rpstockopname);?></p>
                    <p class="text-muted">Selisih : <?= 'Rp. '.number_format($rpselisih);?></p>
                    <div class="table-responsive">
                        <table data-show-columns="true" id="clmtable" data-height="500" data-mobile-responsive="true" class="table color-table success-table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Kode</th>
                                    <th class="text-center">Nama</th>
                                    <th class="text-center">Saldo Awal</th>
                                    <th class="text-center">Pembelian</th>
                                    <th class="text-center">Dari Cabang</th>
                                    <th class="text-center">Retur Penjualan</th>
                                    <th class="text-center">Retur Pabrik</th>
                                    <th class="text-center">Penjualan</th>
                                    <th class="text-center">Ke Cabang</th>
                                    <th class="text-center">Sld Akhir</th>
                                    <th class="text-center">Sld Opname</th>
                                    <th class="text-center">Selisih</th>
                                    <th class="text-center">GiT</th>
                                    <th class="text-center">GiT Penj</th>
                                    <th class="text-center">Act</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($isi) {
                                    $no=1;
                                    $selisih=0;
                                    $rpsaldoakhir=0;
                                    $rpstockopname=0;
                                    $group='';
                                    $totsawal=0;
                                    $totbeli=0;
                                    $totdcbg=0;
                                    $totretj=0;
                                    $totretp=0;
                                    $totjual=0;
                                    $totkcbg=0;
                                    $totsakhir=0;
                                    $totsopn=0;
                                    $totselisih=0;
                                    $totgit=0;
                                    $totgitj=0;
                                    foreach ($isi as $row) {
                                        $no++;
                                        $selisih=($row->n_saldo_stockopname+$row->n_saldo_git+$row->n_git_penjualan)-$row->n_saldo_akhir;
                                        $rpsaldoakhir=$row->n_saldo_akhir*$row->v_product_retail;
                                        $rpstockopname=$row->n_saldo_stockopname*$row->v_product_retail;

                                        if($bln == "01"){
                                            $saldoawal=$row->n_saldo_awal;
                                        }else{
                                            $saldoawal=$row->n_saldo_awal+$row->n_mutasi_gitasal+$row->n_git_penjualanasal;
                                        }
                                        if($group==''){ ?>
                                            <tr>
                                                <td colspan="16" class="text-center text-success" style="font-size:18px;"><b><?= strtoupper($row->e_product_groupname);?></b></td>
                                            </tr>
                                            <?php 
                                            $no=1;
                                            $gtotsawal=0;
                                            $gtotbeli=0;
                                            $gtotdcbg=0;
                                            $gtotretj=0;
                                            $gtotretp=0;
                                            $gtotjual=0;
                                            $gtotkcbg=0;
                                            $gtotsakhir=0;
                                            $gtotsopn=0;
                                            $gtotselisih=0;
                                            $gtotgit=0;
                                            $gtotgitj=0;
                                        }else{
                                            if($group!=$row->e_product_groupname){?>
                                                <tr>
                                                    <td colspan="3" class="text-center"><b>TOTAL <?= strtoupper($group);?></b></td>
                                                    <td class="text-right"><b><?= $gtotsawal;?></b></td>
                                                    <td class="text-right"><b><?= $gtotbeli;?></b></td>
                                                    <td class="text-right"><b><?= $gtotdcbg;?></b></td>
                                                    <td class="text-right"><b><?= $gtotretj;?></b></td>
                                                    <td class="text-right"><b><?= $gtotretp;?></b></td>
                                                    <td class="text-right"><b><?= $gtotjual;?></b></td>
                                                    <td class="text-right"><b><?= $gtotkcbg;?></b></td>
                                                    <td class="text-right"><b><?= $gtotsakhir;?></b></td>
                                                    <td class="text-right"><b><?= $gtotsopn;?></b></td>
                                                    <td class="text-right"><b><?= $gtotselisih;?></b></td>
                                                    <td class="text-right"><b><?= $gtotgit;?></b></td>
                                                    <td class="text-right"><b><?= $gtotgitj;?></b></td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="16" colspan="17" class="text-center text-success" style="font-size:18px;"><b><?= strtoupper($row->e_product_groupname);?></b></td>
                                                </tr>
                                                <?php 
                                                $no=1;
                                                $gtotsawal=0;
                                                $gtotbeli=0;
                                                $gtotdcbg=0;
                                                $gtotretj=0;
                                                $gtotretp=0;
                                                $gtotjual=0;
                                                $gtotkcbg=0;
                                                $gtotsakhir=0;
                                                $gtotsopn=0;
                                                $gtotselisih=0;
                                                $gtotgit=0;
                                                $gtotgitj=0;
                                            }
                                        } 
                                        $group=$row->e_product_groupname;
                                        ?>
                                        <tr>
                                            <td style="font-size: 12px;" class="text-center"><?= $no;?></td>
                                            <td style="font-size: 12px;" class="text-left"><?= $row->i_product;?></td>
                                            <td style="font-size: 12px;" class="text-left"><?= $row->e_product_name."(".$row->i_product_grade.")";?></td>
                                            <td style="font-size: 12px;" class="text-right"><?= $saldoawal;?></td>
                                            <td style="font-size: 12px;" class="text-right"><?= $row->n_mutasi_pembelian;?></td>
                                            <td style="font-size: 12px;" class="text-right"><?= $row->n_mutasi_bbm;?></td>
                                            <td style="font-size: 12px;" class="text-right"><?= $row->n_mutasi_returoutlet;?></td>
                                            <td style="font-size: 12px;" class="text-right"><?= $row->n_mutasi_returpabrik;?></td>
                                            <td style="font-size: 12px;" class="text-right"><?= $row->n_mutasi_penjualan;?></td>
                                            <td style="font-size: 12px;" class="text-right"><?= $row->n_mutasi_bbk;?></td>
                                            <td style="font-size: 12px;" class="text-right"><?= $row->n_saldo_akhir;?></td>
                                            <td style="font-size: 12px;" class="text-right"><?= $row->n_saldo_stockopname;?></td>
                                            <td style="font-size: 12px;" class="text-right"><?= $selisih;?></td>
                                            <td style="font-size: 12px;" class="text-right"><?= $row->n_saldo_git;?></td>
                                            <td style="font-size: 12px;" class="text-right"><?= $row->n_git_penjualan;?></td>
                                            <td class="text-center"><a href="#" onclick="show('<?= $folder;?>/cform/detail/<?= $iperiode."/".$iarea."/".$row->i_product."/".$saldoawal."/".$istorelocation."/".$row->i_product_grade;?>','#main');"><i class="fa fa-pencil"></i></a></td>
                                        </tr>
                                        <?php 
                                        $gtotsawal=$gtotsawal+$saldoawal;
                                        $gtotbeli=$gtotbeli+$row->n_mutasi_pembelian;
                                        $gtotdcbg=$gtotdcbg+$row->n_mutasi_bbm;
                                        $gtotretj=$gtotretj+$row->n_mutasi_returoutlet;
                                        $gtotretp=$gtotretp+$row->n_mutasi_returpabrik;
                                        $gtotjual=$gtotjual+$row->n_mutasi_penjualan;
                                        $gtotkcbg=$gtotkcbg+$row->n_mutasi_bbk;
                                        $gtotsakhir=$gtotsakhir+$row->n_saldo_akhir;
                                        $gtotsopn=$gtotsopn+$row->n_saldo_stockopname;
                                        $gtotselisih=$gtotselisih+$selisih;
                                        $gtotgit=$gtotgit+$row->n_saldo_git;
                                        $gtotgitj=$gtotgitj+$row->n_git_penjualan;
                                        $totsawal=$totsawal+$saldoawal;
                                        $totbeli=$totbeli+$row->n_mutasi_pembelian;
                                        $totdcbg=$totdcbg+$row->n_mutasi_bbm;
                                        $totretj=$totretj+$row->n_mutasi_returoutlet;
                                        $totretp=$totretp+$row->n_mutasi_returpabrik;
                                        $totjual=$totjual+$row->n_mutasi_penjualan;
                                        $totkcbg=$totkcbg+$row->n_mutasi_bbk;
                                        $totsakhir=$totsakhir+$row->n_saldo_akhir;
                                        $totsopn=$totsopn+$row->n_saldo_stockopname;
                                        $totselisih=$totselisih+$selisih;
                                        $totgit=$totgit+$row->n_saldo_git;
                                        $totgitj=$totgitj+$row->n_git_penjualan;
                                    } ?>
                                    <tr>
                                        <td colspan="3" class="text-center"><b>TOTAL <?= strtoupper($group);?></b></td>
                                        <td class="text-right"><b><?= $gtotsawal;?></b></td>
                                        <td class="text-right"><b><?= $gtotbeli;?></b></td>
                                        <td class="text-right"><b><?= $gtotdcbg;?></b></td>
                                        <td class="text-right"><b><?= $gtotretj;?></b></td>
                                        <td class="text-right"><b><?= $gtotretp;?></b></td>
                                        <td class="text-right"><b><?= $gtotjual;?></b></td>
                                        <td class="text-right"><b><?= $gtotkcbg;?></b></td>
                                        <td class="text-right"><b><?= $gtotsakhir;?></b></td>
                                        <td class="text-right"><b><?= $gtotsopn;?></b></td>
                                        <td class="text-right"><b><?= $gtotselisih;?></b></td>
                                        <td class="text-right"><b><?= $gtotgit;?></b></td>
                                        <td class="text-right"><b><?= $gtotgitj;?></b></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-center"><b>TOTAL</b></td>
                                        <td class="text-right"><b><?= $totsawal;?></b></td>
                                        <td class="text-right"><b><?= $totbeli;?></b></td>
                                        <td class="text-right"><b><?= $totdcbg;?></b></td>
                                        <td class="text-right"><b><?= $totretj;?></b></td>
                                        <td class="text-right"><b><?= $totretp;?></b></td>
                                        <td class="text-right"><b><?= $totjual;?></b></td>
                                        <td class="text-right"><b><?= $totkcbg;?></b></td>
                                        <td class="text-right"><b><?= $totsakhir;?></b></td>
                                        <td class="text-right"><b><?= $totsopn;?></b></td>
                                        <td class="text-right"><b><?= $totselisih;?></b></td>
                                        <td class="text-right"><b><?= $totgit;?></b></td>
                                        <td class="text-right"><b><?= $totgitj;?></b></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url();?>assets/plugins/bower_components/bootstrap-table/dist/bootstrap-table.min.js">
</script>