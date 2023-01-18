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
                    <?php 
                    if($isi){
                        foreach($isi as $row){
                            $periode=$row->e_mutasi_periode;
                        }
                    #}else{
                    #    $periode=$iperiode;
                    #}
                    $a       = substr($periode,0,4);
                    $bln     = substr($periode,4,2);
                    $b       = substr($periode,4,2);
                    $periode = mbulan($b)." - ".$a;
                    ?>
                    <h3 class="box-title m-b-0"><?= strtoupper($title).' - '. $row->i_customer.'('.$row->e_customer_name.')';?></h3>
                    <p class="text-muted m-b-30"><b>Periode : <?= $periode;?></b></p>
                    <?php
                    $rpselisih=0;
                    $rpsaldoakhir=0;
                    $rpstockopname=0;
                    if($isi){
                        foreach($isi as $row){
                            $this->db->select("	i_product, v_product_retail
                                                from tr_product_price where i_product='$row->i_product' and i_price_group='00'");
                            $query = $this->db->get();
                            if ($query->num_rows() > 0){
                                foreach($query->result() as $tmp){
                                    $row->v_product_retail=$tmp->v_product_retail;
                                }
                            }else{
                                $row->v_product_retail=0;
                            }

                            $rpselisih      = $rpselisih+(($row->n_saldo_stockopname-$row->n_saldo_akhir)*$row->v_product_retail);
                            $rpsaldoakhir   = $rpsaldoakhir+($row->n_saldo_akhir*$row->v_product_retail);
                            $rpstockopname  = $rpstockopname+($row->n_saldo_stockopname*$row->v_product_retail);
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
                                    <th class="text-center">Dari Pusat</th>
                                    <th class="text-center">Dari Lang</th>
                                    <th class="text-center">Penjualan</th>
                                    <th class="text-center">ke Pusat</th>
                                    <th class="text-center">Sld Akhir</th>
                                    <th class="text-center">Sld Opname</th>
                                    <th class="text-center">Selisih</th>
                                    <th class="text-center">Act</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($isi) {
                                    $no          = 1;
                                    $selisih     = 0;
                                    $tsaldoawal  = 0;
                                    $tdrpst      = 0;
                                    $tdrln       = 0;
                                    $tpenj       = 0;
                                    $tkpst       = 0;
                                    $tsaldoakhir = 0;
                                    $tso         = 0;
                                    $tselisih    = 0;
                                    
                                    foreach ($isi as $row) {
                                        $selisih=($row->n_saldo_stockopname)-$row->n_saldo_akhir;
                                ?>
                                        <tr>
                                            <td style="font-size: 12px;" class="text-center"><?= $no;?></td>
                                            <td style="font-size: 12px;" class="text-left"><?= $row->i_product;?></td>
                                            <td style="font-size: 12px;" class="text-left"><?= $row->e_product_name;?></td>
                                            <td style="font-size: 12px;" class="text-right"><?= $row->n_saldo_awal;?></td>
                                            <td style="font-size: 12px;" class="text-right"><?= $row->n_mutasi_daripusat;?></td>
                                            <td style="font-size: 12px;" class="text-right"><?= $row->n_mutasi_darilang;?></td>
                                            <td style="font-size: 12px;" class="text-right"><?= $row->n_mutasi_penjualan;?></td>
                                            <td style="font-size: 12px;" class="text-right"><?= $row->n_mutasi_kepusat;?></td>
                                            <td style="font-size: 12px;" class="text-right"><?= $row->n_saldo_akhir;?></td>
                                            <td style="font-size: 12px;" class="text-right"><?= $row->n_saldo_stockopname;?></td>
                                            <td style="font-size: 12px;" class="text-right"><?= $selisih;?></td>
                                            <td class="text-center"><a href="#" onclick="show('<?= $folder;?>/cform/detail/<?= $iperiode."/".$icustomer."/".$row->i_product."/".$row->n_saldo_awal;?>','#main');"><i class="fa fa-pencil"></i></a></td>
                                        </tr>

                                        <?php
                                        $no++; 
                                        $tsaldoawal  = $tsaldoawal+$row->n_saldo_awal;
                                        $tdrpst      = $tdrpst+$row->n_mutasi_daripusat;
                                        $tdrln       = $tdrln+$row->n_mutasi_darilang;
                                        $tpenj       = $tpenj+$row->n_mutasi_penjualan;
                                        $tkpst       = $tkpst+$row->n_mutasi_kepusat;
                                        $tsaldoakhir = $tsaldoakhir+$row->n_saldo_akhir;
                                        $tso         = $tso+$row->n_saldo_stockopname;
                                        $tselisih    = $tselisih+$selisih;
                                    } ?>
                                    <tr>
                                        <td colspan="3" class="text-center"><b>TOTAL</b></td>
                                        <td class="text-right"><b><?= $tsaldoawal;?></b></td>
                                        <td class="text-right"><b><?= $tdrpst;?></b></td>
                                        <td class="text-right"><b><?= $tdrln;?></b></td>
                                        <td class="text-right"><b><?= $tpenj;?></b></td>
                                        <td class="text-right"><b><?= $tkpst;?></b></td>
                                        <td class="text-right"><b><?= $tsaldoakhir;?></b></td>
                                        <td class="text-right"><b><?= $tso;?></b></td>
                                        <td class="text-right"><b><?= $tselisih;?></b></td>
                                        <td></td>
                                    </tr>
                                <?php }
                            }else{
                                echo "<h2>Belum Ada Mutasi!!!</h2>";
                            } ?>
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