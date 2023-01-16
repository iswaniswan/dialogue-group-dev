<link href="<?= base_url(); ?>assets/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="<?= base_url(); ?>assets/css/style.css" rel="stylesheet">
<link href="<?= base_url(); ?>assets/css/colors/green.css" id="theme" rel="stylesheet">
<?php 
$tmp=explode("-",$dfrom);
$th=$tmp[2];
$bl=$tmp[1];
$hr=$tmp[0];
$dfroms=$hr." ".mbulan($bl)." ".$th;
$tmp=explode("-",$dto);
$th=$tmp[2];
$bl=$tmp[1];
$hr=$tmp[0];
$dtos=$hr." ".mbulan($bl)." ".$th;
?>
<div class="col-sm-12">
    <div class="white-box">
        <h3 class="box-title">Detail Dokumen Rencana & Realisasi Kunjungan Salesman</h3>
        <p class="text-muted">Periode : <?= $dfroms." s/d ".$dtos;?></p>
        <div class="table-responsive">
            <table class="table color-bordered-table info-bordered-table">
                <thead>
                    <tr>
                        <th style="text-align: center;font-size: 14px;">No</th>
                        <th style="text-align: center;font-size: 14px;">Salesman</th>
                        <th style="text-align: center;font-size: 14px;">Area</th>
                        <th style="text-align: center;font-size: 14px;">Hari</th>
                        <th style="text-align: center;font-size: 14px;">Tanggal</th>
                        <th style="text-align: center;font-size: 14px;">Toko</th>
                        <th style="text-align: center;font-size: 14px;">Rencana</th>
                        <th style="text-align: center;font-size: 14px;">Realisasi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if($data){
                        $i=0;
                        $icustomer = '';
                        foreach($data as $row){
                            $tmp = explode("-",$row->d_rrkh);
                            $bl=$tmp[1];
                            $tg=$tmp[2];
                            $th=$tmp[0];
                            $tgl=mktime(0,0,0,$bl,$tg,$th);
                            $hasil=date("w", $tgl);
                            $row->d_rrkh=$tg.'-'.$bl.'-'.$th;
                            switch($hasil){
                              case 0:
                              $hari='minggu';
                              break;
                              case 1:
                              $hari='senin';
                              break;
                              case 2:
                              $hari='selasa';
                              break;
                              case 3:
                              $hari='rabu';
                              break;
                              case 4:
                              $hari='kamis';
                              break;
                              case 5:
                              $hari='jumat';
                              break;
                              case 6:
                              $hari='sabtu';
                              break;
                          }
                          if($row->f_kunjungan_realisasi=='t'){
                              $realisasi= 'YA';
                          }else{
                              $realisasi= 'TIDAK';
                          }
                          $i++;
                          ?>
                          <tr>
                            <td style="font-size: 13px; text-align: center;">$i</td>
                            <td style="font-size: 13px;">$row->i_salesman - $row->e_salesman_name</td>
                            <td style="font-size: 13px;">$row->i_area - $row->e_area_name</td>
                            <td style="font-size: 13px;">$hari</td>
                            <td style="font-size: 13px;">$row->d_rrkh</td>
                            <td style="font-size: 13px;">$row->i_customer - $row->e_customer_name</td>
                            <td style="font-size: 13px;">$row->e_kunjungan_typename</td>
                            <td style="font-size: 13px;">$realisasi</td>
                        </tr>
                    <?php }
                } ?>
            </tbody>
        </table>
        <button type="submit" id="submit" class="btn btn-inverse btn-rounded btn-sm" onclick="dipales();"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Keluar</button>
    </div>
</div>
</div>
<script type="text/javascript">
    function dipales() {
        this.close();
    }
</script>