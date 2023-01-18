<div class="row">
    <div class="col-lg-12">
    <div class="panel panel-info">
    <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?>
        <?php  if(check_role($this->i_menu, 1)){ ?><a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i
                class="fa fa-plus"></i> &nbsp;<?= $title; ?></a>
        <?php } ?>
    </div>
    <div class="panel-body table-responsive">
    <?php
        $a=substr($iperiodeawal,0,4);
	    $b=substr($iperiodeawal,4,2);
		$periodeawal=mbulan($b)." - ".$a;
		$a=substr($iperiodeakhir,0,4);
	    $b=substr($iperiodeakhir,4,2);
        $periodeakhir=mbulan($b)." - ".$a;
        echo "<center><h2>PLAFOND PERIODE - $periodeawal s/d $periodeakhir </h2></center>";?>
        <div class="form-group row">
            <div class="col-sm-offset-5 col-sm-8">
                <a id="href"><button type="button" class="btn btn-inverse btn-rounded btn-sm"> <i class="fa fa-download"></i>&nbsp;&nbsp;Export ke Excel</button></a>
            </div>
        </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <input type="hidden" id="iperiodeawal" name="iperiodeawal" value=<?php echo $iperiodeawal?>>
                <input type="hidden" id="iperiodeakhir" name="iperiodeakhir" value=<?php echo $iperiodeakhir?>>
                <table class="table color-bordered-table info-bordered-table">
                    <thead>
                        <tr>
                            <th align="center">No</th>
 	                        <th align="center">Area</th>
			                <th align="center">Customer</th>
			                <th align="center">TOP</th>
			                <th align="center">Kategori</th>
			                <th align="center">Index</th>
			                <th align="center">Rata Telat</th>
			                <th align="center">Total Penjualan</th>
			                <th align="center">Max Penjualan</th>
			                <th align="center">Rata Penjualan</th>
			                <th align="center">Plafond Program</th>
			                <th align="center">Plafond Sebelumnya</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
		            if($isi){
		                $i=0;
			            foreach($isi as $row){
			                $i++;
			                $icgb = $row->i_customer_groupbayar;
                            
			                echo "<tr>
			                        <td>$i</td>
				                    <td>$row->e_area_name</td>
				                    <td>$row->i_customer".' - '."$row->e_customer_name</td>
				                    <td>$row->n_customer_toplength</td>
				                    <td>$row->e_kategori</td>
				                    <td>$row->n_index</td>
				                    <td>".number_format($row->n_ratatelat)."</td>
				                    <td>".number_format($row->v_totalpenjualan)."</td>
				                    <td>".number_format($row->v_maxpenjualan)."</td>
				                    <td>".number_format($row->v_ratapenjualan)."</td>
				                    <td>".number_format($row->v_plafon)."</td>
                                    <td>".number_format($row->v_plafonsblmnya)."</td>
                                </tr>";
                            
                            $query = $this->db->query("select * from tm_plafond where i_customer_groupbayar = '$icgb' and e_periode_awal = '$iperiodeawal' 
                            and e_periode_akhir = '$iperiodeakhir' and i_acc is null");
                            
                            if ($query->num_rows() > 0){
                                $raw	= $query->row();
                                $icust          = $raw->i_customer_groupbayar;
                                $iperiodeawal   = $raw->e_periode_awal;
                                $iperiodeakhir  = $raw->e_periode_akhir;	          
                                $this->db->query("delete from tm_plafond where i_customer_groupbayar = '$icust' and e_periode_awal ='$iperiodeawal' 
                                and e_periode_akhir = '$iperiodeakhir'");
                            }
                        
	                        $kat=explode("-",$row->e_kategori);
	                        $ket=$kat[1];
	                        $kode=$kat[0];
					        $query 	= pg_query("SELECT current_timestamp as c");
					        while($rew=pg_fetch_assoc($query)){
					        	$now	  = $rew['c'];
					        }

	                        if(empty($row->n_ratatelat) || $row->n_ratatelat =='' || $row->n_ratatelat ==null){
	                          $row->n_ratatelat=0;
	                        }
	                        if($row->i_customer_groupbayar!=null){
                                $this->db->query("insert into tm_plafond values('$row->i_customer_groupbayar','$iperiodeawal','$iperiodeakhir','$kode','$ket',
                                                $row->n_ratatelat,'$row->n_index','$row->v_totalpenjualan','$row->v_maxpenjualan',
                                                '$row->v_ratapenjualan','$row->v_plafon','$row->v_plafonsblmnya',0,null,null,null,'$row->i_area','$now')");
                            }
      		            }
		            }
	                ?>
                    </tbody>
                </table>
                </div>
            </div>
    </form>
    </div>
    </div>
</div>


<script>
$(function(){
    $('#href').attr('href','<?php echo site_url($folder.'/cform/export/');?>'+$('#iperiodeawal').val() +'/' +$('#iperiodeakhir').val());
});


/*$('#iperiodeblawal, #iperiodethawal, #iperiodeblakhir, #iperiodethakhir').on('change',function(){
    var iperiodeblawal = $('#iperiodeblawal').val();
    var iperiodethawal = $('#iperiodethawal').val();
    var iperiodeblakhir = $('#iperiodeblakhir').val();
    var iperiodethakhir = $('#iperiodethakhir').val();
    $('#href').attr('href','<?php echo site_url($folder.'/cform/export/');?>'+iperiodethawal+iperiodeblawal +'/' +iperiodethakhir+iperiodeblakhir);
});*/
</script>
