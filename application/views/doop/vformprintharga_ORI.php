<link href="<?= base_url(); ?>assets/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="<?= base_url(); ?>assets/css/style.css" rel="stylesheet">
<style type="text/css">
   .header{
      font-size: 13px; padding: 2px 10px !important;
   }

   .table-bordered tfoot th {
      border: 0 !important;
      font-size: 12px; 
      padding: 0px 8px !important;
   }

   .table-bordered tbody td {
      font-size: 11px; padding: 1px 8px !important;
   }

   .huruf10{
      font-size: 10px !important;
   }

   .huruf11{
      font-size: 11px !important;
   }

   .huruf12{
      font-size: 12px !important;
   }

   .huruf13{
      font-size: 13px !important;
   }

   .juduldok{
      font-size: 18px; line-height: 0px !important;
   }

   .nmperusahaan{
      font-size: 14px; line-height: 10px !important;
   }

   .hrna{
      margin-top: -1rem !important;
      margin-bottom: 0rem !important;
   }

   .hr{
      margin-top: 0rem !important;
      margin-bottom: 0rem !important; 
      font-size: 9px;
   }

   .pna{
      font-size: 12px; 
      margin-top: -0.75rem !important;
      margin-bottom: 0rem !important;
   }

   .p{
      font-size: 12px; 
      margin-top: -1rem !important; 
      margin-bottom: 1rem !important;
   }

   .isi{
      font-size: 11px; padding: 1px 8px !important;
   }
</style>
<style type="text/css" media="print">
   #kotak {
      border-collapse: collapse;
      border: 1px solid black;
   }
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
      size: portrait;
   }
   @page { size: Letter;
      margin: 0mm;  /* this affects the margin in the printer settings */
   }
</style>
<?php include ("php/fungsi.php");?>
<?php 
$hal=1;
foreach($data->result() as $row){?>
   <!-- color CSS -->
   <div class="white-box printableArea">
      <!-- <table class="isinya" border='0' align="center" width="70%"> -->
         <div class="row">
            <div class="col-sm-12">
               <div class="row">
                  <div class="col-sm-12">
                     <div class="pull-left">
                        <address style="padding:0px">
                           <h3 class="juduldok"><b>SURAT JALAN (SJ)</b><br></h3>
                           <!-- <table cellpadding="0" cellspacing="0" >
                              <tr>
                                 <td width="160px" class="text-muted m-l-3 huruf12"><b>Nomor SJ</b></td> 
                                 <td width="10px" class="text-muted m-l-3 huruf12"><b>:</b></td> 
                                 <td width="300px" class="text-muted m-l-3 huruf12"><b><?= $row->i_document;?></b></td> 
                              </tr>
                              <tr>
                                 <td width="160px" class="text-muted m-l-3 huruf12"><b>Nomor SPB</b></td> 
                                 <td width="10px" class="text-muted m-l-3 huruf12"><b>:</b></td> 
                                 <td width="300px" class="text-muted m-l-3 huruf12"><b><?= $row->i_referensi; ?></b></td> 
                              </tr>
                           </table> -->
                        </address>
                     </div>
                     <div class="pull-right text-right huruf13"> <address>Cimahi,&nbsp;<?= date('d', strtotime($row->d_document)).' '.$this->fungsi->mbulan(date('m', strtotime($row->d_document))).' '.date('Y', strtotime($row->d_document));?></address></div>
                  </div>
               </div>
               <hr class="hrna">
               <div class="row">
                  <div class="col-sm-12">
                     <div class="pull-left"> <address>
                        <h4 class="font-bold nmperusahaan"><?= check_constant('NmPerusahaan');?></h4>
                        <h5 class="huruf10"><?= check_constant('AlmtPerusahaan');?>  </h5>
                        <h5 class="huruf10">Telp/Fax : <?= check_constant('TlpPerusahaan'). " / ". check_constant('FaxPerusahaan');?>  </h5>
                        <h5 class="huruf10"><?= check_constant('KotaPerusahaan');?>  </h5>
                     </address></div>
                     <div class="pull-right text-right"> <address>
                        <h4 class="huruf12">Kepada Yth,</h4>
                        <h4 class="font-bold nmperusahaan"><?= $row->e_customer_name; ?></h4>
                        <h5 class="huruf10"><?= $row->e_customer_address;?>  </h5>
                        <h5 class="huruf10"><?= $row->e_city_name;?>  </h5>
                     </address> </div>
                  </div>
               </div>
               <hr class="hrna">
               <div class="row">
                  <div class="col-sm-12">
                     <div class="pull-left">
                        <address style="padding:0px">
                           <table cellpadding="0" cellspacing="0" >
                              <tr>
                                 <td width="130px" class="text-muted m-l-3 huruf12"><b>Nomor Surat Jalan</b></td> 
                                 <td width="15px" class="text-muted m-l-3 huruf12"><b>:</b></td> 
                                 <td width="300px" class="text-muted m-l-3 huruf12"><b><?= $row->i_document;?></b></td> 
                              </tr>
                              <tr>
                                 <td width="130px" class="text-muted m-l-3 huruf12"><b>Nomor SPB</b></td> 
                                 <td width="15px" class="text-muted m-l-3 huruf12"><b>:</b></td> 
                                 <td width="300px" class="text-muted m-l-3 huruf12"><b><?= $row->i_referensi; ?></b></td> 
                              </tr>
                           </table>
                        </address>
                     </div>
                  </div>
                  <div class="col-sm-12">
                     <p class="text-muted pna"> Harap diterima barang-barang berikut ini : </p>
                  </div>
                  <div class="col-sm-12">
                     <div class="table-responsive m-t-0">
                        <table class="table table-bordered" cellpadding="0" cellspacing="0">
                           <thead>
                              <tr>
                                 <th width="3%" class="text-center header">No</th>
                                 <th class="header">Nama Barang</th>
                                 <th class="text-right header">Qty</th>
                                 <th class="header">Satuan</th>
                                 <th class="text-right header">Harga</th>
                                 <th class="text-right header">Jumlah</th>
                                 <th class="header">Ket</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php 
                              $no = 0;
                              $v_bruto = 0;
                              $v_netto = 0;
                              $v_dpp = 0;
                              $v_ppn = 0;
                              $v_discount = 0;
                              if ($datadetail) {
                                 $hasiltotal = 0;
                                 $total = 0;
                                 $qty   = 0;
                                 foreach ($datadetail->result() as $rowi) {
                                    $no++;
                                    $total = $rowi->n_quantity * $rowi->v_price;
                                    $v_discount1 = $total * ($rowi->n_diskon1/100);
                                    $v_discount2 = ($total - $v_discount1) * ($rowi->n_diskon2/100);
                                    $v_discount3 = ($total - $v_discount1 - $v_discount2) * ($rowi->n_diskon3/100);
                                    $v_total_discount = $v_discount1 + $v_discount2 + $v_discount3 + $rowi->v_diskon_tambahan;
                                    $total = (int)$total;
                                    $qty   = $qty + $rowi->n_quantity;
                                    $hasiltotal = $hasiltotal + $total;?>
                                    <tr>
                                       <td class="text-center"><?= $no;?></td>
                                       <td>
                                          <?php if(strlen($rowi->e_product_basename )>50){
                                             $nam    = substr($rowi->e_product_basename,0,50);
                                          }else{
                                             $nam    = $rowi->e_product_basename.str_repeat(" ",50-strlen($rowi->e_product_basename ));
                                          }
                                          echo $rowi->i_product_base." - ".$nam. " - ".$rowi->e_color_name;
                                          ?>
                                       </td>
                                       <td class="text-right">
                                          <?= $rowi->n_quantity;?>
                                       </td>
                                       <td class="text-left"><?= $rowi->e_satuan_name;?></td>
                                       <td class="text-right"> Rp. <?= number_format($rowi->v_price,2,',','.');?></td>
                                       <td class="text-right"> Rp. <?= number_format($total,2,',','.');?></td>
                                       <td><?= $rowi->e_remark;?></td>
                                    </tr>
                                 <?php 
                                 $v_bruto += $total;
                                 $v_discount += $v_total_discount;
                              } 
                              $v_dpp = $v_bruto - $v_discount;
                              $v_ppn = $v_dpp * ($row->n_ppn / 100);
                              $v_netto = $v_dpp + $v_ppn;
                              ?>
                              </tbody>
                              <tfoot>
                                 <tr>
                                    <th colspan="5" class="text-right">Total : </th>
                                    <th colspan="1" class="text-right">Rp. <?= number_format($v_bruto,2,',','.');?></th>
                                 </tr>
                                 <tr>
                                    <th colspan="5" class="text-right">Diskon : </th>
                                    <th colspan="1" class="text-right"><u>Rp. <?= number_format($v_discount,2,',','.');?></u></th>
                                 </tr>
                                 <tr>
                                    <th colspan="5" class="text-right">DPP : </th>
                                    <th colspan="1" class="text-right">Rp. <?= number_format($v_dpp,2,',','.');?></th>
                                 </tr>
                                 <tr>
                                    <th colspan="5" class="text-right">PPN (<?= $row->n_ppn;?>%) : </th>
                                    <th colspan="1" class="text-right"><u>Rp. <?= number_format($v_ppn,2,',','.');?></u></th>
                                 </tr>
                                 <tr>
                                    <th colspan="5" class="text-right">Grand Total : </th>
                                    <th colspan="1" class="text-right">Rp. <?= number_format(round($v_netto),2,',','.');?> </th>
                                 </tr>
                                 <tr>
                                    <th colspan="6" style="font-size: 12px; padding: 0px 8px;border:0;" class="huruf nmper text-right"><i>
                                       (<?php
                                          $bilangan = new Terbilang;
                                          $kata=ucwords($bilangan->eja(round($v_netto)));  
                                          $tmp=explode("-",$row->d_document);
                                          $th=$tmp[0];
                                          $bl=$tmp[1];
                                          $hr=$tmp[2];
                                          $d_op=$hr." ".mbulan($bl)." ".$th;
                                          echo $kata." Rupiah";?>)
                                       </i> 
                                    </th>
                                 </tr>
                              <?php } ?> 
                           </tfoot>
                        </table>
                     </div>
                  </div>
                  <div class="col-sm-12">
                     <div class="row">
                        <div class="col-sm-4">
                           <div class="pull-center m-t-30 text-center">
                              <p class="p">Penerima,</p>
                              <h3 class="huruf12">(...................................)</h3>
                           </div>
                        </div>
                        <div class="col-sm-4">
                           <div class="pull-center m-t-30 text-center">
                              <p class="p">Mengetahui,</p>
                              <h3 class="huruf12">(...................................)</h3>
                           </div>
                        </div>
                        <div class="col-sm-4">
                           <div class="pull-center m-t-30 text-center">
                              <p class="p">Hormat Kami,</p>
                              <h3 class="huruf12">(...................................)</h3>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="col-sm-12" >
                     <center>
                        <table id="kotak" class="table table-bordered">
                           <tr>
                              <td class="text-center huruf12"><font size="2px;"> P E N T I N G </font><br><br>KLAIM KEKURANGAN / TOLAKAN BARANG BERLAKU MAKSIMAL 7 HARI DARI BARANG DITERIMA, SURAT JALAN INI BUKAN DOKUMEN PENAGIHAN DAN BUKAN MERUPAKAN BUKTI PEMBAYARAN<br>UNTUK PEMBAYARAN DENGAN GIRO / TRANSFER MOHON DITUJUKAN KE :<br>REKENING <?= check_constant('BkRek');?><br><span class="huruf13"><b>A/C : <?= check_constant('NoRek');?><br>A/N : <?= check_constant('NmRek');?></b></span><br>CALL CENTER : <?= check_constant('CallCenter');?> (Telp/WA)<br>MOHON CANTUMKAN NAMA DAN NOMOR SJ
                              </td>
                           </tr>
                        </table>
                     </center>
                  </div>
               </div>
               <hr class="hr">
               <font size="2">
                  <?php date_default_timezone_set('Asia/Jakarta'); echo "Tanggal Cetak : ".$tgl=date("d")." ".$this->fungsi->mbulan(date("m"))." ".date("Y").",  Jam : ".date("H:i:s");
                  ?>
               </font>
               <div class="row">
               <?php } ?>
            </div>
            <div class="noDisplay">
               <div class="text-center"> <button id="print" class="btn btn-info btn-outline" onclick="window.print();" type="button"> <span><i class="fa fa-print"></i> Print</span> </button> </div>
            </div>
         </div>
      </div>
      <!-- </table> -->
   </div>
   <script src="<?= base_url(); ?>assets/plugins/bower_components/jquery/dist/jquery.min.js"></script>
   <script type="text/javascript">  
      window.onafterprint = function (){
         var id    = '<?= $id ?>';
         $.ajax({
            type: "POST",
            url: "<?= site_url($folder.'/cform/updateprint');?>",
            data: {
               'id'  : id,
            },
            success: function(data){
               opener.window.refreshview();
               setTimeout(window.close,0);
            },
            error:function(XMLHttpRequest){
               alert('fail');
            }
         });
      }
   </script>