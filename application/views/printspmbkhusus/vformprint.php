<?php 
include ("php/fungsi.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1252" />
</head>
<body>
    <style type="text/css" media="all">
/*
@page land {size: landscape;}
@media print {
input.noPrint { display: none; }
}
@page
        {
            size: auto;   /* auto is the initial value */
            margin: 0mm;  /* this affects the margin in the printer settings */
        }
        */
        *{
            size: landscape;
        }

        @page { size: Letter; 
            margin: 0mm;  /* this affects the margin in the printer settings */
        }

        .huruf {
          FONT-FAMILY: Tahoma, Verdana, Arial, Helvetica, sans-serif;
      }
      .miring {
          font-style: italic;

      }
      .wrap {
        margin: 0 auto;
        text-align: left;
    }

    .ceKotak{-
        background-color:#f0f0f0;
        border-bottom:#80c0e0 1px solid;
        border-top:#80c0e0 1px solid;
        border-left:#80c0e0 1px solid;
        border-right:#80c0e0 1px solid;
    }
    .garis { 
        background-color:#000000;
        width: 100%;
        height: 50%;
        font-size: 100px;
        border-style: solid;
        border-width:0.01px;
        border-collapse: collapse;
        spacing:1px;
    }
    .garis td { 
        background-color:#FFFFFF;
        border-style: solid;
        border-width:0.01px;
        font-size: 10px;
        FONT-WEIGHT: normal; 
        padding:1px;
    }
    .garisy { 
        background-color:#000000;
        width: 100%;
        height: 50%;
        border-style: solid;
        border-width:0.01px;
        border-collapse: collapse;
        spacing:1px;
    }
    .garisy td { 
        background-color:#FFFFFF;
        border-style: solid;
        border-width:0.01px;
        padding:1px;
    }
    .garisx { 
        background-color:#000000;
        width: 100%;
        height: 50%;
        border-style: none;
        border-collapse: collapse;
        spacing:1px;
    }
    .garisx td { 
        background-color:#FFFFFF;
        border-style: none;
        font-size: 10px;
        FONT-WEIGHT: normal; 
        padding:1px;
    }
    .judul {
        font-size: 20px;
        FONT-WEIGHT: normal; 
    }
    .nmper {
        margin-top: 0;
        font-size: 12px;
        FONT-WEIGHT: normal; 
    }
    .isi {
        font-size: 10px;
        font-weight:normal;
    }
    .eusinya {
        font-size: 8px;
        font-weight:normal;
    }
    .garisbawah { 
        border-bottom:#000000 0.1px solid;
    }
    .garisatas { 
        border-top:#000000 0.1px solid;
    }
    .button {
        background-color: #008CBA; /* blue */
        border: none;
        color: white;
        padding: 15px 32px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        margin: 4px 2px;
        cursor: pointer;
    }
    .button1 {
        font-size: 12px;
        border-radius: 8px;
        box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);
    }
</style>
<style type="text/css" media="print">
    .noDisplay{
        display:none;
    }
    .pagebreak {
        page-break-before: always;
    }
</style>
<?php 
$hal=1;
foreach($isi as $row)
{
    ?>
    <table width="100%" class="nmper" border="0">
        <tr>
          <td><?php echo check_constant('NmPerusahaan'); ?></td>
      </tr>
      <tr>
          <td><?php echo check_constant('AlmtPerusahaan'); ?></td>
      </tr>    
      <tr>
          <td class="huruf judul" align="center" >S U R A T &nbsp;&nbsp;&nbsp;&nbsp;P E R M I N T A A N&nbsp;&nbsp;&nbsp;&nbsp;B A R A N G</td>
      </tr>
      <tr>
          <td align="center">Nomor SPMB : <?php echo $row->i_spmb."/".$row->i_area."-".$row->e_area_name;?></td>
      </tr>
      <tr>
          <td align="center">Tanggal : <?php 
          $tmp=explode("-",$row->d_spmb);
          $th=$tmp[0];
          $bl=$tmp[1];
          $hr=$tmp[2];
          $dspmb=$hr." ".mbulan($bl)." ".$th;
          echo $dspmb;?></td>
      </tr>
      <tr>
          <td>Kepada Yth.</td>
      </tr>
      <tr>
          <td>Bag. Pembelian</td>
      </tr>
      <tr>
        <td><?php echo check_constant('NmPerusahaan')." (PUSAT)";?></td>
      </tr>
      <tr>
          <td>&nbsp;</td>
      </tr>
      <tr>
          <td>Dengan hormat,</td>
      </tr>
      <tr>
          <td>Bersama surat ini kami mohon dikirimkan barang-barang sbb :</td>
      </tr>
      <tr align="center">
          <td>
            <table width="100%" class="nmper" border="0">
              <tr>
                <td width="50px" class="garisatas garisbawah">
                  NO. URUT
              </td>
              <td class="garisatas garisbawah" colspan="2" align="center">
                  BANYAK
              </td>
              <td width="75px" class="garisatas garisbawah">
                  KODE BARANG
              </td>
              <td class="garisatas garisbawah">
                  NAMA BARANG
              </td>
              <td width="100px" class="garisatas garisbawah">
                  KETERANGAN
              </td>
          </tr>
          <?php 
          $i  = 0;
          $j  = 0;
          $hrg= 0;
          $jml=count($detail);

          foreach($detail as $rowi){
            $i++;
            $j++;
#               $hrg    = $hrg+($rowi->n_order*$rowi->v_product_mill);
            ?>
            <tr>
                <td width="25">
                  <?php echo $i;?>
              </td>
              <td width="20px" class="garisbawah">
                  &nbsp;
              </td>
              <td>

              </td>
              <td align="center" width="20px" class="garisbawah">
                  <?php echo $rowi->n_acc;?>
              </td>
              <td>
                  <?php echo $rowi->i_product;?>
              </td>
              <td>
                  <?php 
                  if(strlen($rowi->e_product_name )>50){
                    $nam    = substr($rowi->e_product_name,0,50);
                }else{
                    $nam    = $rowi->e_product_name.str_repeat(" ",50-strlen($rowi->e_product_name ));
                }
                echo $nam;?>
            </td>
            <td width="20px">
              <?php echo $rowi->e_remark;?>
          </td>
      </tr>
  <?php }?>
</td>
</tr>
</table>
<tr>
    <td colspan=5 class=garisatas>&nbsp;</td>
</tr>
</table >
<table width="100%" class="nmper" border="0">
  <tr>
    <td colspan ="4">Demikian surat ini kami sampaikan, atasa perhatian dan kerjasamanya kami ucapkan terima kasih.</td>
</tr>  
<tr>
    <td colspan="4">&nbsp;</td>
</tr>
<tr>
    <td>&nbsp;</td>
    <td align="center" width="300px">
      Hormat kami,
  </td>
  <td align="center" width="300px">
      Menyetujui,
  </td>   
  <td>&nbsp;</td>
</tr>
<tr>
    <td>
      &nbsp;
  </td>
  <td align="center">
      &nbsp;
  </td>   
  <td>
      &nbsp;
  </td>
  <td align="center">
      &nbsp;
  </td>   
</tr>
<tr>
    <td colspan="4">&nbsp;</td>
</tr>
<tr>
    <td colspan="4">&nbsp;</td>
</tr>
<tr>
    <td>
      &nbsp;
  </td>
  <td align="center">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Kepala Gudang&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
  <td align="center">(&nbsp;Supervisor Administrasi&nbsp;)</td>
  <td>
      &nbsp;
  </td>
</tr>
<tr>
    <td colspan ="4" align="left"><?php echo "TANGGAL CETAK : ".$tgl=date("d")." ".mbulan(date("m"))." ".date("Y")."  Jam : ".date("H:i:s");
    ?></td>
</tr>
</table>

<?php    
}
?>
<!-- <div class="noDisplay"><center><b><a href="#" onClick="window.print()">Print</a></b></center></div> -->
<div class="noDisplay"><center><b><a href="#" class="button button1" onClick="window.print();">Print</a></b></center></div>
<script src="<?= base_url(); ?>assets/plugins/bower_components/jquery/dist/jquery.min.js"></script>
<script type="text/javascript">
    window.onafterprint = function (){
        var id    = '<?php echo $id ?>';
        $.ajax({
            type: "POST",
            url: "<?= site_url($folder.'/cform/update');?>",
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