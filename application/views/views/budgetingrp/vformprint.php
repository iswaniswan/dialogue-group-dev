<link href="<?= base_url(); ?>assets/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="<?= base_url(); ?>assets/css/style.css" rel="stylesheet">
<style type="text/css" media="print">
   .noDisplay{
      display:none;
   }
   .pagebreak {
      page-break-before: always;
   }
   @media print {
      .page-break { display: block; page-break-before: always; }
   }
   .style {
      padding: 1px 8px;
   }
   {
      size: landscape;
   }
   @page { 
      size: Letter; 
      margin: 0mm;  /* this affects the margin in the printer settings */
   }
</style>
<?php 
$hal=1;
foreach($data->result() as $row){?>
   <!-- color CSS -->
   <div class="row">
      <div class="col-md-12">
         <div class="white-box printableArea">
            <h3><img src="<?= base_url(); ?>assets/images/logo/<?= check_constant('logo');?>"><b>&nbsp;&nbsp;<?= check_constant('NmPerusahaan');?></b><span class="pull-right">Cimahi,&nbsp;<?= date('d', strtotime($row->d_pp)).' '.$this->fungsi->mbulan(date('m', strtotime($row->d_pp))).' '.date('Y', strtotime($row->d_pp));?></span></h3>
            <hr>
            <div class="row">
               <div class="col-md-12">
                  <div class="pull-left">
                     <address>
                        <h3> &nbsp;<b>SURAT PERMINTAAN PEMBELIAN</b><br></h3>
                        <p class="text-muted m-l-5">Nomor PP &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;: <b><?= $row->i_pp;?></b>
                           <p class="text-muted m-l-5">Gudang &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;: <b><?= $row->e_bagian_name;?></b>
                              <p class="text-muted m-l-5">Batas Terakhir &nbsp: <b><?php 
                              $tmp=explode("-",$row->d_pp);
                              $th=$tmp[0];
                              $bl=$tmp[1];
                              $hr=$tmp[2];
                              $d_pp=$hr." ".$this->fungsi->mbulan($bl)." ".$th;
                              echo $d_pp;?></b>
                              <br> 
                              <br>Dengan hormat,
                              <br>Bersama surat ini kami mohon dikirimkan barang-barang sbb :
                           </p>
                        </address>
                     </div>
                     <div class="pull-right text-right">
                        <address>
                           <h4>Kepada Yth,</h4>
                           <h4 class="font-bold">Bag. Pembelian</h4>
                           <h5><?= check_constant('NmPerusahaan')." (PUSAT)";?>  </h5>
                        </address>
                     </div>
                  </div>
                  <div class="col-md-12">
                     <div class="table-responsive m-t-0">
                        <table class="table table-bordered" cellpadding="0" cellspacing="0">
                           <thead>
                              <tr>
                                 <th style="padding: 2px 10px; width: 3%;" class="text-center">No</th>
                                 <th style="padding: 2px 10px;"  >Kode Barang</th>
                                 <th style="padding: 2px 10px;"  class="text-left">Nama Barang</th>
                                 <th style="padding: 2px 10px;"  class="text-right">Quantity</th>
                                 <th style="padding: 2px 10px;"  class="text-center">Satuan</th>
                                 <th style="padding: 2px 10px;"  class="text-center">Keterangan</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php 
                              $counter = 0;
                              if ($detail) {
                                 foreach ($detail->result() as $rowi) {
                                    $counter++;?>
                                    <tr>
                                       <td style="padding: 1px 8px;"  class="text-center">
                                          <?= $counter;?>
                                       </td>
                                       <td style="padding: 1px 8px;" >
                                          <?= $rowi->i_material;?>
                                       </td>
                                       <td style="padding: 1px 8px;" >
                                          <?php 
                                          if(strlen($rowi->e_material_name )>50){
                                           $nam    = substr($rowi->e_material_name,0,50);
                                        }else{
                                           $nam    = $rowi->e_material_name.str_repeat(" ",50-strlen($rowi->e_material_name ));
                                        }
                                        echo $nam;
                                        ?>
                                     </td>
                                     <td style="padding: 1px 8px;"  class="text-right">
                                       <?= $rowi->n_quantity;?>
                                    </td>
                                    <td style="padding: 1px 8px;"  class="text-center">
                                       <?php echo $rowi->e_satuan_name;?>
                                    </td>
                                    <td style="padding: 1px 8px;" >
                                       <?= $rowi->e_remark;?>
                                    </td>
                                 </td>
                              </tr>
                           <?php } 
                        }?>
                     </tbody>
                  </table>
               </div>
            </div>
            <div class="col-md-12">Demikian surat ini kami sampaikan, atas perhatian dan kerjasamanya kami ucapkan terima kasih.</div>
            <div class="col-md-6">
               <div class="pull-center m-t-30 text-center">
                  <p>Hormat Kami</p>
                  <p>&nbsp;</p>
                  <hr>
                  <h3>(.............................................................)</h3>
               </div>
            </div>
            <div class="col-md-6">
               <div class="pull-center m-t-30 text-center">
                  <p>Menyetujui,</p>
                  <p>&nbsp;</p>
                  <hr>
                  <h3>(.............................................................)</h3>
               </div>
            </div>
            <hr>
         </div>
         <?php date_default_timezone_set('Asia/Jakarta'); echo "TANGGAL CETAK : ".$tgl=date("d")." ".$this->fungsi->mbulan(date("m"))." ".date("Y")."  Jam : ".date("H:i:s");
         ?>
         <div class="row">
            <?php    
         }
         ?>
      </div>
      <div class="noDisplay">
         <div class="text-center">
            <button id="print" class="btn btn-info btn-outline" onclick="window.print();" type="button"> <span><i class="fa fa-print"></i> Print</span> </button>
         </div>
      </div>
   </div>
</div>
</div>