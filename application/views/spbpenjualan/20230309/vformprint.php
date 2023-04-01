<!DOCTYPE html>
<html>
<head>
   <title><?= $title;?></title>
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
      #kotak {
         border-collapse: collapse;
         font-size: 12px;
         border: 1px solid #e4e7ea;
      }
   </style>
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
         size: portrait;
      }
      @page { size: Letter;
         margin: 0mm;  /* this affects the margin in the printer settings */
      }
   </style>
</head>
<body>
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
                              <h3 class="juduldok"><b>SURAT PESANAN BARANG (SPB)</b><br></h3>
                           </address>
                        </div>
                        <div class="pull-right text-right huruf13"> <address>Cimahi,&nbsp;<?= date('d', strtotime($row->d_document)).' '.mbulan(date('m', strtotime($row->d_document))).' '.date('Y', strtotime($row->d_document));?></address></div>
                     </div>
                  </div>
                  <hr class="hrna">
                  <div class="row">
                     <div class="col-sm-12">
                        <div class="pull-left"> 
                           <address>
                              <h4 class="font-bold nmperusahaan"><?= check_constant('NmPerusahaan');?></h4>
                              <table cellpadding="0" cellspacing="0" >
                                 <tr>
                                    <td width="130px" class="text-muted m-l-3 huruf12"><b>Nomor SPB</b></td> 
                                    <td width="15px" class="text-muted m-l-3 huruf12"><b>:</b></td> 
                                    <td width="300px" class="text-muted m-l-3 huruf12"><b><?= $row->i_document;?></b></td> 
                                 </tr>
                                 <tr>
                                    <td width="130px" class="text-muted m-l-3 huruf12"><b>Nomor Referensi</b></td> 
                                    <td width="15px" class="text-muted m-l-3 huruf12"><b>:</b></td> 
                                    <td width="300px" class="text-muted m-l-3 huruf12"><b><?= $row->i_referensi; ?></b></td> 
                                 </tr>
                              </table>
                           </address>
                        </div>
                        <div class="pull-right text-right"> <address>
                           <!-- <h4 class="huruf12">Pelanggan,</h4> -->
                           <h4 class="font-bold nmperusahaan"><?= "(".$row->i_customer.") ".$row->e_customer_name; ?></h4>
                           <h5 class="huruf10"><?= $row->e_customer_address;?>  </h5>
                           <h5 class="huruf10"><?= $row->e_city_name;?>  </h5>
                           <h5 class="huruf10"><?= $row->e_sales;?>  </h5>
                        </address> </div>
                     </div>
                  </div>
                  <hr class="hrna">
                  <div class="row">
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
                                    <th class="text-right header">Diskon</th>
                                    <th class="text-right header">Jumlah</th>
                                    <th class="header">Ket</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <?php 
                                 $no = 0;
                                 if ($datadetail) {
                                    foreach ($datadetail->result() as $rowi) {
                                       $no++;
                                       ?>
                                       <tr>
                                          <td class="text-center"><?= $no;?></td>
                                          <td>
                                             <?php if(strlen($rowi->e_product_basename )>40){
                                                $nam    = substr($rowi->e_product_basename,0,40);
                                             }else{
                                                $nam    = $rowi->e_product_basename.str_repeat(" ",40-strlen($rowi->e_product_basename ));
                                             }
                                             echo $rowi->i_product_base." - ".$nam." - ". $rowi->e_color_name;
                                             ?>
                                          </td>
                                          <td class="text-right">
                                             <?= $rowi->n_quantity;?>
                                          </td>
                                          <td class="text-left"><?= $rowi->e_satuan_name;?></td>
                                          <td class="text-right"> Rp. <?= number_format($rowi->v_price,2,',','.');?></td>
                                          <td class="text-right"> Rp. <?= number_format($rowi->v_diskon_total,2,',','.');?></td>
                                          <td class="text-right"> Rp. <?= number_format($rowi->v_total,2,',','.');?></td>
                                          <td><?= $rowi->e_remark;?></td>
                                       </tr>
                                       <?php 
                                    } ?>
                                 </tbody>
                                 <tfoot>
                                    <tr>
                                       <th colspan="4">Tanggal Daftar : <?= date('d', strtotime($row->d_join)).' '.mbulan(date('m', strtotime($row->d_join))).' '.date('Y', strtotime($row->d_join));?></th>
                                       <th colspan="2" class="text-right">Total : </th>
                                       <th colspan="1" class="text-right">Rp. <?= number_format($row->v_kotor,2,',','.');?></th>
                                    </tr>
                                    <tr>
                                       <th colspan="4">Plafon : <?= "Rp. 00,000,000.00";?></th>
                                       <th colspan="2" class="text-right">Diskon : </th>
                                       <th colspan="1" class="text-right"><u>Rp. <?= number_format($row->v_diskon,2,',','.');?></u></th>
                                    </tr>
                                    <tr>
                                       <th colspan="4">Rata-rata Keterlambatan : <?= "- Hari";?></th>
                                       <th colspan="2" class="text-right">DPP : </th>
                                       <th colspan="1" class="text-right">Rp. <?= number_format($row->v_dpp,2,',','.');?></th>
                                    </tr>
                                    <tr>
                                       <th colspan="4">Status : </th>
                                       <th colspan="2" class="text-right">PPN (<?= number_format($row->n_ppn);?>%) : </th>
                                       <th colspan="1" class="text-right"><u>Rp. <?= number_format($row->v_ppn,2,',','.');?></u></th>
                                    </tr>
                                    <tr>
                                       <th colspan="4">Saldo Piutang : Rp. <?= number_format($piutang,2);?></th>
                                       <th colspan="2" class="text-right">Grand Total : </th>
                                       <th colspan="1" class="text-right">Rp. <?= number_format(round($row->v_bersih),2,',','.');?> </th>
                                    </tr>
                                    <tr>
                                       <th colspan="7" style="font-size: 12px; padding: 0px 8px;border:0;" class="huruf nmper text-right"><i>
                                          (<?php
                                             $bilangan = new Terbilang;
                                             $kata=ucwords($bilangan->eja(round($row->v_bersih)));  
                                             echo $kata." Rupiah";?>)
                                          </i> 
                                       </th>
                                    </tr>
                                    <?php 
                                 } ?> 
                              </tfoot>
                           </table>
                        </div>
                     </div>                  
                     <div class="col-sm-12" >
                        <table width="100%" id="kotak" border="0">
                           <tr>
                              <td colspan="9">&nbsp;&nbsp;<b>History Penjualan - 6 Bulan :</b></td>
                           </tr>
                           <tr>
                              <td width="10%;">&nbsp;&nbsp;<?= mbulan(date('m', strtotime('-1 month', strtotime($dhistory))))." ".date('Y', strtotime('-1 month', strtotime($dhistory)));?></td>
                              <td width="2%;">:</td>
                              <td width="14%;"><b>Rp. <?= number_format($history->v_total1,2);?></b></td>
                              <td width="10%;">&nbsp;&nbsp;<?= mbulan(date('m', strtotime('-2 month', strtotime($dhistory))))." ".date('Y', strtotime('-2 month', strtotime($dhistory)));?></td>
                              <td width="2%;">:</td>
                              <td width="14%;"><b>Rp. <?= number_format($history->v_total2,2);?></b></td>
                              <td width="10%;">&nbsp;&nbsp;<?= mbulan(date('m', strtotime('-3 month', strtotime($dhistory))))." ".date('Y', strtotime('-3 month', strtotime($dhistory)));?></td>
                              <td width="2%;">:</td>
                              <td width="14%;"><b>Rp. <?= number_format($history->v_total3,2);?></b></td>
                           </tr>
                           <tr>
                              <td width="10%;">&nbsp;&nbsp;<?= mbulan(date('m', strtotime('-4 month', strtotime($dhistory))))." ".date('Y', strtotime('-4 month', strtotime($dhistory)));?></td>
                              <td width="2%;">:</td>
                              <td width="14%;"><b>Rp. <?= number_format($history->v_total4,2);?></b></td>
                              <td width="10%;">&nbsp;&nbsp;<?= mbulan(date('m', strtotime('-5 month', strtotime($dhistory))))." ".date('Y', strtotime('-5 month', strtotime($dhistory)));?></td>
                              <td width="2%;">:</td>
                              <td width="14%;"><b>Rp. <?= number_format($history->v_total5,2);?></b></td>
                              <td width="10%;">&nbsp;&nbsp;<?= mbulan(date('m', strtotime('-6 month', strtotime($dhistory))))." ".date('Y', strtotime('-6 month', strtotime($dhistory)));?></td>
                              <td width="2%;">:</td>
                              <td width="14%;"><b>Rp. <?= number_format($history->v_total6,2);?></b></td>
                           </tr>
                        </table>
                     </div>
                     <div class="col-sm-12" >
                        <table id="kotak" width="100%;">
                           <tr>
                              <td colspan="9" class="huruf13">&nbsp;&nbsp;<b>N O T E :</b><br>&nbsp;</td>
                           </tr>
                        </table>
                     </div>
                     <div class="col-sm-12">
                        <div class="row">
                           <div class="col-sm-4">
                              <div class="pull-center m-t-30 text-center">
                                 <p class="p">Pemesan,</p>
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
                                 <p class="p">Menyetujui,</p>
                                 <h3 class="huruf12">(...................................)</h3>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <hr class="hr">
                  <font size="2">
                     <?php date_default_timezone_set('Asia/Jakarta'); echo "Tanggal Cetak : ".$tgl=date("d")." ".mbulan(date("m"))." ".date("Y").",  Jam : ".date("H:i:s");
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
   </body>
   </html>
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