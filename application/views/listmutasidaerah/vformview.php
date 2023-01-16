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
                    <p class="text-muted m-b-30"><b>Periode : <?= $iperiode;?></b></p>
                    <?php
                        $selisih=0;
                        $saldoakhir=0;
                        $stockopname=0;
                        $rpselisih=0;
                        $rpsaldoakhir=0;
                        $rpstockopname=0;
                        if($isi){
                            foreach($isi as $row){
                                if($row->i_product_grade==' ' or $row->i_product_grade==''){
                                    $row->i_product_grade='A';
                                }
                                $query = $this->db->query("
                                                            select
                                                                i_product, 
                                                                v_product_retail
                                                            from 
                                                                tr_product_price
                                                            where 
                                                                i_product='$row->i_product' 
                                                                and i_product_grade='$row->i_product_grade' 
                                                                and i_price_group='00'
                                                        ");
		    	                if ($query->num_rows() > 0){
          		                    foreach($query->result() as $tmp){
            	                    	$row->v_product_retail=$tmp->v_product_retail;
          		                    }
        		                }else{
          		                    $row->v_product_retail=0;
                                }
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
                    <table data-show-columns="true" id="clmtable" data-height="500" data-mobile-responsive="true" class="table color-table success-table table-bordered">
                        <thead>
                            <tr>
                                <th rowspan="2" style="font-size: 12px;text-align: center;vertical-align: middle;">No</th>
				                <th rowspan="2" style="font-size: 12px;text-align: center;vertical-align: middle;">Kode</th>
				                <th rowspan="2" style="font-size: 12px;text-align: center;vertical-align: middle;">Nama</th>
				                <th colspan="3" style="font-size: 12px;text-align: center;vertical-align: middle;">Saldo Awal</th>
				                <th colspan="1" style="font-size: 12px;text-align: center;vertical-align: middle;">Masuk</th>
				                <th colspan="3" style="font-size: 12px;text-align: center;vertical-align: middle;">Keluar</th>
				                <th colspan="1" style="font-size: 12px;text-align: center;vertical-align: middle;">Saldo</th>
				                <th colspan="1" style="font-size: 12px;text-align: center;vertical-align: middle;">Stock</th>
				                <th colspan="1" style="font-size: 12px;text-align: center;vertical-align: middle;">Selisih</th>
				                <th rowspan="2" style="font-size: 12px;text-align: center;vertical-align: middle;">GIT</th>
				                <th rowspan="2" style="font-size: 12px;text-align: center;vertical-align: middle;">GIT Penj</th>
				                <th rowspan="2" style="font-size: 12px;text-align: center;vertical-align: middle;">Act</th>
                            </tr>
                            <tr>
                                <th style="font-size: 12px;text-align: center;vertical-align: middle;">Saldo Awal</th>
			                    <th style="font-size: 12px;text-align: center;vertical-align: middle;">GIT Awal</th>
			                    <th style="font-size: 12px;text-align: center;vertical-align: middle;">GIT Jual Awal</th>
			                    <th style="font-size: 12px;text-align: center;vertical-align: middle;">Dari Pusat</th>
			                    <th style="font-size: 12px;text-align: center;vertical-align: middle;">Jual</th>
			                    <th style="font-size: 12px;text-align: center;vertical-align: middle;">Pusat</th>
			                    <th style="font-size: 12px;text-align: center;vertical-align: middle;">MO</th>
			                    <th style="font-size: 12px;text-align: center;vertical-align: middle;">Akhir</th>
			                    <th style="font-size: 12px;text-align: center;vertical-align: middle;">Opn</th>
			                    <th style="font-size: 12px;text-align: center;vertical-align: middle;">(pcs)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($isi) {
                                $i=1;
                                $selisih=0;
                                $rpsaldoakhir=0;
                                $rpstockopname=0;
                                $group='';
                                $totsalawal=0;
                                $totgitawal=0;
                                $totgitjawal=0;
                                $totdpusat=0;
                                $totjual=0;
                                $totkpusat=0;
                                $totmo=0;
                                $totakhir=0;
                                $totopn=0;
                                $totsel=0;
                                $totgit=0;
                                $totgitj=0;
                                foreach ($isi as $row) {
                                    $query = $this->db->query("	
                                                                select
                                                                    v_product_retail
                                                                from 
                                                                    tr_product_price
                                                                where 
                                                                    i_product='$row->i_product' 
                                                                    and i_price_group='00'
                                                            ");
		    	                    if ($query->num_rows() > 0){
          		                        foreach($query->result() as $tmp){
            	                        	$row->v_product_retail=$tmp->v_product_retail;
          		                        }
        		                    }
                                    $selisih=($row->n_saldo_stockopname+$row->n_saldo_git+$row->n_git_penjualan)-$row->n_saldo_akhir;
        		                    $rpsaldoakhir=$row->n_saldo_akhir*$row->v_product_retail;
        		                    $rpstockopname=$row->n_saldo_stockopname*$row->v_product_retail;
        		                    $totsaldoawal=$row->n_saldo_awal;#+$row->n_mutasi_gitasal+$row->n_git_penjualanasal;
        		                    $saldoawal=$row->n_saldo_awal;
        		                    $gitawal=$row->n_mutasi_gitasal;
                                    $gitjualawal=$row->n_git_penjualanasal;
                                    ?>
                                    <tr>
                                        <td class="text-center"><?= $i;?></td>
                                        <td><?= $row->i_product;?></td>
                                        <td><?= $row->e_product_name;?></td>
                                        <td class="text-right"><?= $saldoawal;?></td>
                                        <td class="text-right"><?= $gitawal;?></td>
                                        <td class="text-right"><?= $gitjualawal;?></td>
                                        <td class="text-right"><?= $row->n_mutasi_bbm;?></td>
                                        <td class="text-right"><?= $row->n_mutasi_penjualan;?></td>
                                        <td class="text-right"><?= $row->n_mutasi_bbk;?></td>
                                        <td class="text-right"><?= $row->n_mutasi_ketoko;?></td>
                                        <td class="text-right"><?= $row->n_saldo_akhir;?></td>
                                        <td class="text-right"><?= $row->n_saldo_stockopname;?></td>
                                        <td class="text-right"><?= $selisih;?></td>
                                        <td class="text-right"><?= $row->n_saldo_git;?></td>
                                        <td class="text-right"><?= $row->n_git_penjualan;?></td>
                                        <td><?php echo"
                                    <a href=\"#\" onclick='show(\"listmutasidaerah/cform/detail/$iperiode/$iarea/$row->i_product/$totsaldoawal/$istorelocation/$row->e_product_name/$istore\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>"; ?></td>
                                    </tr>
                                    <?php $i++;
                                    $totsalawal=$totsalawal+$saldoawal;
        	                        $totgitawal=$totgitawal+$gitawal;
        	                        $totgitjawal=$totgitjawal+$gitjualawal;
        	                        $totdpusat=$totdpusat+$row->n_mutasi_bbm;
        	                        $totjual=$totjual+$row->n_mutasi_penjualan;
        	                        $totkpusat=$totkpusat+$row->n_mutasi_bbk;
        	                        $totmo=$totmo+$row->n_mutasi_ketoko;
        	                        $totakhir=$totakhir+$row->n_saldo_akhir;
        	                        $totopn=$totopn+$row->n_saldo_stockopname;
        	                        $totsel=$totsel+$selisih;
        	                        $totgit=$totgit+$row->n_saldo_git;
        	                        $totgitj=$totgitj+$row->n_git_penjualan;
                                    ?>
                            <?php } ?>
                                <tr>
                                    <td colspan="3" class="text-center">TOTAL</td>
                                    <td class="text-right"><?= $totsalawal;?></td>
                                    <td class="text-right"><?= $totgitawal;?></td>
                                    <td class="text-right"><?= $totgitjawal;?></td>
                                    <td class="text-right"><?= $totdpusat;?></td>
                                    <td class="text-right"><?= $totjual;?></td>
                                    <td class="text-right"><?= $totkpusat;?></td>
                                    <td class="text-right"><?= $totmo;?></td>
                                    <td class="text-right"><?= $totakhir;?></td>
                                    <td class="text-right"><?= $totopn;?></td>
                                    <td class="text-right"><?= $totsel;?></td>
                                    <td class="text-right"><?= $totgit;?></td>
                                    <td class="text-right"><?= $totgitj;?></td>
                                </tr>
                           <?php }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url();?>assets/plugins/bower_components/bootstrap-table/dist/bootstrap-table.min.js">
</script>