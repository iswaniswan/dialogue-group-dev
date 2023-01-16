<link href="<?= base_url(); ?>assets/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="<?= base_url(); ?>assets/css/style.css" rel="stylesheet">
<link href="<?= base_url(); ?>assets/css/colors/green.css" id="theme" rel="stylesheet">
<div class="col-sm-12">
    <!-- div awal -->
    <h3 class="box-title" style="text-align: center;"><?= $title; ?></h3>
    <p class="text-muted" style="text-align: center;">Periode : <?= $iperiode;?></p>
    <div class="panel-body table-responsive">
        <table class="table color-bordered-table info-bordered-table display nowrap" id="sitabel" cellpadding="0"
            cellspacing="0" border="1">
            <thead>
                <?php if($detail){ ?>
                <tr>
                    <th style="font-size: 12px;text-align: center;vertical-align: middle;" rowspan=2>No</th>
                    <th style="font-size: 12px;text-align: center;vertical-align: middle;" rowspan=2>Area
                    </th>
                    <th style="font-size: 12px;text-align: center;vertical-align: middle;" rowspan=2>Salesman</th>
                    <th style="font-size: 12px;text-align: center;vertical-align: middle;" rowspan=2>Toko</th>
                    <th style="font-size: 12px;text-align: center;vertical-align: middle;" rowspan=2>Tanggal</th>
                    <th style="font-size: 12px;text-align: center;vertical-align: middle;" rowspan=2>Nota</th>
                    <th style="font-size: 12px;text-align: center;vertical-align: middle;" rowspan=2>Target</th>
                    <th style="font-size: 12px;text-align: center;vertical-align: middle;" rowspan=2>Realisasi</th>
                    <th style="font-size: 12px;text-align: center;vertical-align: middle;" rowspan=2>Blm Bayar</th>
                    <th style="font-size: 12px;text-align: center;vertical-align: middle;" colspan=2>Tdk Telat</th>
                    <th style="font-size: 12px;text-align: center;vertical-align: middle;" colspan=2>Telat</th>
                </tr>

                <tr>
                    <th style="font-size: 12px;text-align: center;">Target</th>
                    <th style="font-size: 12px;text-align: center;">Realisasi</th>
                    <th style="font-size: 12px;text-align: center;">Target</th>
                    <th style="font-size: 12px;text-align: center;">Realisasi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
		            if($detail){
                        $no         = 0;
                        $ttotal     = 0;
                        $treal      = 0;
                        $tblm       = 0;
                        $ttelat     = 0;
                        $trealtelat = 0;
                        $ttdk       = 0;
                        $trealtdk=0;
			  foreach($detail as $row){
          if($row->total>0 || $row->realisasi>0){
            $no++;
            $tmp=explode('-',$row->d_nota);
		        $tgl=$tmp[2];
		        $bln=$tmp[1];
		        $thn=$tmp[0];
            if(strlen($tgl)==2){
        		  $row->d_nota=$tgl.'-'.$bln.'-'.$thn;
            }
            $ttotal=$ttotal+$row->total;
            $treal =$treal+$row->realisasi;
            $tblm=$tblm+$row->blmbayar;
            $trealtelat =$trealtelat+$row->realisasitelat;
            $trealtdk =$trealtdk+$row->realisasitdktelat;
            $ttelat =$ttelat+$row->telat;
            $ttdk =$ttdk+$row->tdktelat;
		        echo "<tr>
              <td>$no</td>
              <td>$row->i_area - $row->e_area_name</td>
  				    <td>$row->i_salesman ($row->e_salesman_name)</td>
				      <td>($row->i_customer) - $row->e_customer_name</td>";
#              <td>$row->e_customer_classname</td>
            echo "
				      <td>$row->d_nota</td>
				      <td>$row->i_nota</td>
				      <td align=right>".number_format($row->total)."</td>
				      <td align=right>".number_format($row->realisasi)."</td>";
				    echo "
              <td align=right>".number_format($row->blmbayar)."</td>
              <td align=right>".number_format($row->tdktelat)."</td>
              <td align=right>".number_format($row->realisasitdktelat)."</td>
              <td align=right>".number_format($row->telat)."</td>
              <td align=right>".number_format($row->realisasitelat)."</td></tr>";	
          }
			  }
        echo "<tr>
              <th colspan='6'>Total</th>
				      <th align=right>".number_format($ttotal)."</th>
				      <th align=right>".number_format($treal)."</th>
				      <th align=right>".number_format($tblm)."</th>
				      <th align=right>".number_format($ttdk)."</th>
				      <th align=right>".number_format($trealtdk)."</th>
				      <th align=right>".number_format($ttelat)."</th>
				      <th align=right>".number_format($trealtelat)."</th>
				      </tr>";
		  }
	        ?>
            </tbody>
            <?php }?>
            <!-- end if isi -->
        </table>
    </div> <!-- end div awal -->

    <script language="javascript" type="text/javascript">
    function bbatal(a) {
        show("listtargetcollectionrealtime/cform/view/" + a + "/", "#main");
    }

    function yyy() {
        lebar = 1024;
        tinggi = 768;
        periode = document.getElementById("iperiode").value;
        area = document.getElementById("iarea").value;
        eval('window.open("<?php echo site_url(); ?>"+"/listtargetcollectionrealtime/cform/cetakdetail/"+periode+"/"+area,"","width="+lebar+"px,height="+tinggi+"px,resizable=1,menubar=1,scrollbars=1,top=' +
            (screen.height - tinggi) / 2 + ',left=' + (screen.width - lebar) / 2 + '")');
    }
    </script>