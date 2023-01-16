<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-bar-chart-o"></i> <?= $title; ?>
            <a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i
                class="fa fa-arrow-circle-o-left"></i> &nbsp;<?= "Kembali"; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <table class="table color-table info-table hover-table table-bordered" id="sitabel">
                    <thead>
                        <tr>
                            <th>Kode</th>
			                <th>Nama</th>
			                <th>H00</th>
			                <th>H01</th>
			                <th>H02</th>
			                <th>H03</th>
			                <th>H04</th>
			                <th>H05</th>
			                <th>H06</th>
			                <th>HG0</th>
			                <th>HG2</th>
			                <th>HG3</th>
			                <th>HG5</th>
			                <th>Jns</th>
			                <th>Nm Jenis</th>
			                <th>Kls</th>
			                <th>Nm Kelas</th>
			                <th>Ktgr</th>
			                <th>Nm Kategori</th>
			                <th>PL</th>
			                <th>Tgl Daftar</th>
			                <?php 
			                  if($this->session->userdata('i_departement')=='6'){
			                ?>
			                <th>Pabrik</th>
			                <?php 
			                  }
			                ?>
			                <th>Grade</th>
			                <th>Status</th>
			                <th>Kd Supp</th>
			                <th>Nm Supplier</th>
			                <th>Ada Stok</th>
			                <th>Kd Group</th>
			                <th>Nm Group</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
		                if($isi){
		                	foreach($isi as $row){
		                		if($row->d_product_register!=''){
		                			$tmp=explode('-',$row->d_product_register);
		                			$tgl=$tmp[2];
		                			$bln=$tmp[1];
		                			$thn=$tmp[0];
		                			$row->d_product_register=$tgl.'-'.$bln.'-'.$thn;
		                		}else{
		                			$row->d_product_register='';
		                		}
                                if($row->i_product_status=='4'){
                                  $status='Tidak';
                                }else{
                                  $status='Aktif';
                                }
                                $kode=$row->i_product;
                                $query=$this->db->query("select * from tm_ic where i_store='AA' and i_product='$kode'");
                                if($query->num_rows()>0){
                                  foreach($query->result() as $xx){
                                    if($xx->n_quantity_stock>0){
                                      $adastok='Ya';
                                    }else{
                                      $adastok='Tidak';
                                    }  
                                  }
                                }else{
                                  $adastok='Tidak';
                                }
                                if(substr($kode,0,1)=='Z'){
                                  $grade='B';
                                }else{
                                  $grade='A';
                                }
		                	    echo "<tr> 
		                		  <td>$kode</td>
		                		  <td>$row->e_product_name</td>
		                		  <td align=right>".number_format($row->h00)."</td>
		                		  <td align=right>".number_format($row->h01)."</td>
		                		  <td align=right>".number_format($row->h02)."</td>
		                		  <td align=right>".number_format($row->h03)."</td>
		                		  <td align=right>".number_format($row->h04)."</td>
		                		  <td align=right>".number_format($row->h05)."</td>
		                		  <td align=right>".number_format($row->h06)."</td>
		                		  <td align=right>".number_format($row->hg0)."</td>
		                		  <td align=right>".number_format($row->hg2)."</td>
		                		  <td align=right>".number_format($row->hg3)."</td>
		                		  <td align=right>".number_format($row->hg5)."</td>
		                		  <td align=center>$row->i_product_type</td>
		                		  <td align=center>$row->e_product_typename</td>
		                		  <td align=center>$row->i_product_class</td>
		                		  <td align=center>$row->e_product_classname</td>
		                		  <td align=center>$row->i_product_category</td>
		                		  <td align=center>$row->e_product_categoryname</td>
		                		  <td align=center>$row->f_product_pricelist</td>
		                		  <td align=center>$row->d_product_register</td>";
		                		if($this->session->userdata('departement')=='6'){
                                        echo "<td align=right>".number_format($row->v_product_mill)."</td>";
                                }
                                echo "
		                		  <td align=center>$grade</td>
		                		  <td align=center>$status</td>
		                		  <td align=center>$row->i_supplier</td>
		                		  <td align=center>$row->e_supplier_name</td>
		                		  <td align=center>$adastok</td>
		                		  <td align=center>$row->i_product_group</td>
		                		  <td align=center>$row->e_product_groupname</td>
                                </tr>";
			                }
		                }
	                ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
</script>