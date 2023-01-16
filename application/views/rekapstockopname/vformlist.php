<div class="row">
    <div class="col-lg-12">
        <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/approve'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?>
        </div>
        <div class="panel-body table-responsive">
            <div id="pesan"></div>
            <table class="table color-bordered-table info-bordered-table">
                <thead>
                    <tr>
                        <th align="center">Store</th>
                        <th align="center">Jenis</th>
                        <th align="center">Saldo<br>Akhir (Qty)</th>
                        <th align="center">Saldo<br>Akhir (Rp)</th>
                        <th align="center">Total<br>Opname (Qty)</th>
                        <th align="center">Total<br>Opname (Rp)</th>
                        <th align="center">Selisih (Qty)</th>
                        <th align="center">Selisih (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
		            if($isi){
		                $nsaldoakhir=0;
		                $vsaldoakhir=0;
		                $nopname=0;
		                $vopname=0;
		                $nselisih=0;
		                $vselisih=0;
                        foreach($isi as $raw){
                            echo "<tr><td>$raw->i_store - $raw->e_store_name</td>
                                  <td>$raw->i_jenis - $raw->e_jenis</td>";
                            echo "<td align=right>".number_format($raw->n_saldoakhir - $raw->n_git - $raw->n_gitjual)."</td>
                                  <td align=right>".number_format($raw->v_saldoakhir_rp - $raw->v_git_rp - $raw->v_gitjual_rp)."</td>
                                  <td align=right>".number_format($raw->n_totalopname - $raw->n_git - $raw->n_gitjual)."</td>
                                  <td align=right>".number_format($raw->v_totalopname_rp - $raw->v_git_rp - $raw->v_gitjual_rp)."</td>
                                  <td align=right>".number_format(($raw->n_totalopname - $raw->n_git - $raw->n_gitjual) - ($raw->n_saldoakhir - $raw->n_git - $raw->n_gitjual))."</td>
                                  <td align=right>".number_format(($raw->v_totalopname_rp - $raw->v_git_rp - $raw->v_gitjual_rp) - ($raw->v_saldoakhir_rp - $raw->v_git_rp - $raw->v_gitjual_rp))."</td></tr>";

		                        $nsaldoakhir=$nsaldoakhir+($raw->n_saldoakhir - $raw->n_git - $raw->n_gitjual);
		                        $vsaldoakhir=$vsaldoakhir+($raw->v_saldoakhir_rp - $raw->v_git_rp - $raw->v_gitjual_rp);
		                        $nopname=$nopname+($raw->n_totalopname - $raw->n_git - $raw->n_gitjual);
		                        $vopname=$vopname+($raw->v_totalopname_rp - $raw->v_git_rp - $raw->v_gitjual_rp);
		                        $nselisih=$nselisih+(($raw->n_totalopname - $raw->n_git - $raw->n_gitjual) - ($raw->n_saldoakhir - $raw->n_git - $raw->n_gitjual));
		                        $vselisih=$vselisih+(($raw->v_totalopname_rp - $raw->v_git_rp - $raw->v_gitjual_rp) - ($raw->v_saldoakhir_rp - $raw->v_git_rp - $raw->v_gitjual_rp));
                        }?>
                    <tr>
	                  <th colspan="2" align="center"><b>TOTAL</b></th>
	                  <?php 
                        echo "<td align=right><b>".number_format($nsaldoakhir)."</b></td>
                              <td align=right><b>".number_format($vsaldoakhir)."</b></td>
                              <td align=right><b>".number_format($nopname)."</b></td>
                              <td align=right><b>".number_format($vopname)."</b></td>
                              <td align=right><b>".number_format($nselisih)."</b></td>
                              <td align=right><b>".number_format($vselisih)."</b></td></tr>";
                    }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</form>
</div>
</div>


<script>
</script>
