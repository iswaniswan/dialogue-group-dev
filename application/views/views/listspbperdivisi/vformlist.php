<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?>
                <?php  if(check_role($this->i_menu, 1)){ ?><a href="#" onclick="show('<?= $folder; ?>/cform/tambah/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i
                        class="fa fa-plus"></i> &nbsp;<?= $title; ?></a>
                <?php } ?>
            </div>
            <div class="panel-body table-responsive">
            <table class="table color-bordered-table info-bordered-table" id="sitabel">
                    <thead>
                        <tr>
                            <th style="text-align: center; font-size: 14px;">No SPB</th>
			                <th style="text-align: center; font-size: 14px;">Tgl SPB</th>
			                <th style="text-align: center; font-size: 14px;">Sls</th>
			                <th style="text-align: center; font-size: 14px;">Lang</th>
			                <th style="text-align: center; font-size: 14px;">Area</th>
			                <th style="text-align: center; font-size: 14px;">SPB (Rp)</th>
			                <th style="text-align: center; font-size: 14px;">Nota (Rp)</th>
			                <th style="text-align: center; font-size: 14px;">%</th>
			                <th style="text-align: center; font-size: 14px;">Status</th>
			                <th style="text-align: center; font-size: 14px;">SJ</th>
			                <th style="text-align: center; font-size: 14px;">Nota</th>
			                <th style="text-align: center; font-size: 14px;">Daerah</th>
                            <th style="text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
		                if($isi){
                            $group='';
			                foreach($isi as $row){
                            $que=$this->db->query(" select sum(n_order*v_unit_price) as order
                                                    from tm_spb_item
                                                    where i_area = '$row->i_area' and i_spb='$row->i_spb'");
		                        if ($que->num_rows() > 0){
		                    	    foreach($que->result() as $riw){
                                $order=$riw->order;
                              }
                            }else{
                              $order=0;
                            }
                            $que=$this->db->query(" select sum(b.n_deliver*b.v_unit_price)as deliver from tm_nota_item b, tm_nota c
                                                    where c.i_area = '$row->i_area' and c.i_spb='$row->i_spb' and
                                                    b.i_sj=c.i_sj and b.i_area=c.i_area");
		                        if ($que->num_rows() > 0){
		                    	    foreach($que->result() as $riw){
                                $deliv=$riw->deliver;
                              }
                            }else{
                              $deliv=0;
                            }
                            if($deliv==''){
                              $persen='0.00';
                            }else{
                              $persen=number_format(($deliv/$order)*100,2);
                            }
                            if($row->f_spb_stockdaerah=='t')
                            {
                              $daerah='Ya';
                            }else{
                              $daerah='Tidak';
                            }
                            if($row->d_spb){
		                    	    $tmp=explode('-',$row->d_spb);
		                    	    $tgl=$tmp[2];
		                    	    $bln=$tmp[1];
		                    	    $thn=$tmp[0];
		                    	    $row->d_spb=$tgl.'-'.$bln.'-'.$thn;
                            }
			                if(
			                	 	  ($row->f_spb_cancel == 't') 
			                		 ){
			                	$status='Batal';
			                }elseif(
			                	 	  ($row->i_approve1 == null) && ($row->i_notapprove == null)
			                		 ){
			                	$status='Sales';
			                }elseif(
			                		  ($row->i_approve1 == null) && ($row->i_notapprove != null)
			                		 ){
			                	$status='Reject (sls)';
			                }elseif(
			                		  ($row->i_approve1 != null) && ($row->i_approve2 == null) &
			                		  ($row->i_notapprove == null)
			                		 ){
			                	$status='Keuangan';
			                }elseif(
			                		  ($row->i_approve1 != null) && ($row->i_approve2 == null) && 
			                		  ($row->i_notapprove != null)
			                		 ){
			                	$status='Reject (ar)';
			                }elseif(
			                		  ($row->i_approve1 != null) && ($row->i_approve2 != null) && 
			                		  ($row->i_store == null)
			                		 ){
			                	$status='Gudang';
			                }elseif(
			                		  ($row->i_approve1 != null) && ($row->i_approve2 != null) && 
			                		  ($row->i_store != null) && ($row->i_nota == null) && ($row->f_spb_stockdaerah == 'f') && 
			                		  ($row->f_spb_siapnotagudang == 'f') && ($row->f_spb_op == 'f')
			                		 ){
			                	$status='Pemenuhan SPB';
			                }elseif(
			                		  ($row->i_approve1 != null) && ($row->i_approve2 != null) && 
			                		  ($row->i_store != null) && ($row->i_nota == null) && ($row->f_spb_stockdaerah == 'f') &&
			                		  ($row->f_spb_siapnotagudang == 'f') && ($row->f_spb_op == 't') && ($row->f_spb_opclose == 'f')
			                		 ){
			                	$status='Proses OP';
			                }elseif(
			                		  ($row->i_approve1 != null) && ($row->i_approve2 != null) && 
			                		  ($row->i_store != null) && ($row->i_nota == null) && ($row->f_spb_stockdaerah == 'f') &&
			                		  ($row->f_spb_siapnotagudang == 'f') && ($row->f_spb_siapnotasales == 'f') && ($row->f_spb_opclose == 't')
			                		 ){
			                	$status='OP Close';
			                }elseif(
			                		  ($row->i_approve1 != null) && ($row->i_approve2 != null) && 
			                		  ($row->i_store != null) && ($row->i_nota == null) && ($row->f_spb_stockdaerah == 'f') &&
			                		  ($row->f_spb_siapnotagudang == 't') && ($row->f_spb_siapnotasales == 'f')
			                		 ){
			                	$status='Siap SJ (sales)';
			                }elseif(
			                		  ($row->i_approve1 != null) && ($row->i_approve2 != null) && 
			                		  ($row->i_store != null) && ($row->i_nota == null) && ($row->f_spb_stockdaerah == 'f') &&
			                		  ($row->f_spb_siapnotagudang == 't') && ($row->f_spb_siapnotasales == 't') && ($row->i_sj == null)
			                		 ){
			                	$status='Siap SJ';
			                }elseif(
			                		  ($row->i_approve1 != null) && ($row->i_approve2 != null) && 
			                		  ($row->i_store != null) && ($row->i_nota == null) && ($row->f_spb_stockdaerah == 'f') && 
			                		  ($row->f_spb_siapnotagudang == 't') && ($row->f_spb_siapnotasales == 't') && ($row->i_sj == null)
			                		 ){
			                	$status='Siap SJ';
			                }elseif(
			                		  ($row->i_approve1 != null) && ($row->i_approve2 != null) && ($row->i_dkb == null) && 
			                		  ($row->i_store != null) && ($row->i_nota == null) && ($row->f_spb_stockdaerah == 'f') && 
			                		  ($row->f_spb_siapnotagudang == 't') && ($row->f_spb_siapnotasales == 't') && ($row->i_sj != null)
			                		 ){
			                	$status='Siap DKB';
                            }elseif(
			                		  ($row->i_approve1 != null) && ($row->i_approve2 != null) && ($row->i_dkb != null) && 
			                		  ($row->i_store != null) && ($row->i_nota == null) && ($row->f_spb_stockdaerah == 'f') && 
			                		  ($row->f_spb_siapnotagudang == 't') && ($row->f_spb_siapnotasales == 't') && ($row->i_sj != null)
			                		 ){
			                	$status='Siap Nota';
			                }elseif(
			                		  ($row->i_approve1 != null) && ($row->i_approve2 != null) && 
			                		  ($row->i_store != null) && ($row->i_nota == null) && 
			                		  ($row->f_spb_stockdaerah == 't') && ($row->i_sj == null)
			                		 ){
			                	$status='Siap SJ';
			                }elseif(
			                		  ($row->i_approve1 != null) && ($row->i_approve2 != null) && 
			                		  ($row->i_store != null) && ($row->i_nota == null) && ($row->i_dkb == null) && 
			                		  ($row->f_spb_stockdaerah == 't') && ($row->i_sj != null)
			                		 ){
			                	$status='Siap DKB';
			                }elseif(
			                		  ($row->i_approve1 != null) && ($row->i_approve2 != null) && 
			                		  ($row->i_store != null) && ($row->i_nota == null) && ($row->i_dkb != null) && 
			                		  ($row->f_spb_stockdaerah == 't') && ($row->i_sj != null)
			                		 ){
			                	$status='Siap Nota';
			                }elseif(
			                		  ($row->i_approve1 != null) && 
			                		  ($row->i_approve2 != null) &&
			                 		  ($row->i_store != null) && 
			                		  ($row->i_nota != null) 
			                		 ){
			                	$status='Sudah dinotakan';			  
			                }elseif(($row->i_nota != null)){
			                	$status='Sudah dinotakan';
			                }else{
			                	$status='Unknown';		
			                }
			                $bersih	= number_format($row->v_spb-$row->v_spb_discounttotal);
			                $row->v_spb	= number_format($row->v_spb);
 			                $row->v_spb_discounttotal	= number_format($row->v_spb_discounttotal);
			                $nota	= number_format($row->v_nota_netto);
                            if($group==''){
                              $group=$row->e_product_groupname;
		                    	    echo "<tr>
      	                    			  <td valign=top align=center style=\"font-size: 15px;\" colspan=13><b>$row->e_product_groupname</b></td>
                                    </tr>";
                            }elseif($group!=$row->e_product_groupname){
                              $group=$row->e_product_groupname;
		                    	    echo "<tr>
      	                    			  <td valign=top align=center style=\"font-size: 15px;\" colspan=13><b>$row->e_product_groupname</b></td>
                                    </tr>";
                            }
			                echo "<tr>
			                	  <td valign=top style=\"font-size: 13px;\">$row->i_spb</td>
			                	  <td valign=top style=\"font-size: 13px;\">$row->d_spb</td>
			                	  <td valign=top style=\"font-size: 13px;\">$row->i_salesman</td>";
			                	if(substr($row->i_customer,2,3)!='000'){
			                		echo "
			                	  <td valign=top style=\"font-size: 13px;\">($row->i_customer) $row->e_customer_name</td>";
			                	}else{
			                		echo "
			                	  <td valign=top style=\"font-size: 13px;\">$row->xname</td>";
			                	}
			                echo "
			                	  <td valign=top style=\"font-size: 13px;\">$row->i_area</td>
			                	  <td valign=top align=right style=\"font-size: 13px;\">$bersih</td>
                                  <td valign=top align=right style=\"font-size: 13px;\">$nota</td>
				                  <td valign=top align=right style=\"font-size: 13px;\">$persen%</td>
				                  <td valign=top style=\"font-size: 13px;\">$status</td>
				                  <td valign=top style=\"font-size: 13px;\">$row->i_sj</td>
				                  <td valign=top style=\"font-size: 13px;\">$row->i_nota</td>
				                  <td valign=top style=\"font-size: 13px;\">$daerah</td>
				                  <td valign=top class=\"action\">";
			                if($row->i_spb_program!=null){
								echo "	
								<a href=\"#\" onclick='show(\"listspbperdivisi/cform/editpromo/$row->i_spb/$row->i_area/$row->i_spb_program/$dfrom/$dto/$iarea/\",\"#main\")'><i class=\"fa fa-pencil\"></i></a>";
			                }else{
			                	if($row->xname!=''){
			                		echo "
			                				<a href=\"#\" onclick='show(\"customernew/cform/edit/$row->i_spb/$row->i_area/$row->i_price_group/$dfrom/$dto/\",\"#main\")'><i class=\"fa fa-pencil\"></i></a>";
			                	}else{
			                		echo "
			                				<a href=\"#\" onclick='show(\"listspbperdivisi/cform/editspb/$row->i_spb/$row->i_area/$dfrom/$dto/$row->i_price_group/\",\"#main\")'><i class=\"fa fa-pencil\"></i></a>";
			                	}
			                }
			                	if( ($row->i_store == null) && ($row->i_approve1==null) && ($row->i_approve2==null) ){
			                		if( ($row->f_spb_stockdaerah == 'f') && ($row->f_spb_op == 't') ){
                                	        if($iarea == '00'){
          	                    	        	echo "&nbsp;&nbsp;
												  <a href=\"#\" onclick='hapus(\"$row->i_spb\",\"$row->i_area\");'><i class='fa fa-trash'></i></a>";
			                    	        }
                                	}else{
					            	        if($row->f_spb_cancel == 'f'){
          		                	            echo "&nbsp;&nbsp;
												  <a href=\"#\" onclick='hapus(\"$row->i_spb\",\"$row->i_area\");'><i class='fa fa-trash'></i></a>";
					            	        }
                                	}
				                }
				                echo "</td></tr>";				  
			                }
		                }
	                ?>
                    </tbody>
                </table>
				<td colspan='13' align='center'>
				<br>
                    <button type="button" name="cmdreset" id="cmdreset" class="btn btn-inverse btn-rounded btn-sm"> <i class="fa fa-download"></i>&nbsp;&nbsp;Export ke Excel</button></a>
				</td>
            </div>
        </div>
    </div>
</div>

<script>

	$( "#cmdreset" ).click(function() {  
    	var Contents = $('#sitabel').html();    
    	window.open('data:application/vnd.ms-excel, ' +  '<table>'+encodeURIComponent($('#sitabel').html()) +  '</table>' );
  	});

	

	function hapus(ispb,iarea) {
        swal({   
            title: "Apakah anda yakin ?",   
            text: "Anda tidak akan dapat memulihkan data ini!",   
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Ya, hapus!",   
            cancelButtonText: "Tidak, batalkan!",   
            closeOnConfirm: false,   
            closeOnCancel: false 
        }, function(isConfirm){   
            if (isConfirm) { 
                $.ajax({
                    type: "post",
                    data: {
                        'ispb' : ispb,
                        'iarea': iarea
                    },
                    url: '<?= base_url($folder.'/cform/delete'); ?>',
                    dataType: "json",
                    success: function (data) {
                        swal("Dihapus!", "Data berhasil dihapus :)", "success");
                        show('<?= $folder;?>/cform/view/<?= $dfrom."/".$dto."/".$iarea;?>','#main');   
                    },
                    error: function () {
                        swal("Maaf", "Data gagal dihapus :(", "error");
                    }
                });
            } else {     
                swal("Dibatalkan", "Anda membatalkan penghapusan :)", "error");
            } 
        });
    }
</script>