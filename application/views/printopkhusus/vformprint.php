<?php include ("php/fungsi.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1252" />
</head>
<body>
    <style type="text/css" media="all">
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
    foreach($isi as $row){
        if($row->n_print > 0){
            die();
        }
        $tmp=explode("-",$row->d_op);
        $th=$tmp[0];
        $bl=$tmp[1];
        $hr=$tmp[2];
        $dop=$hr." ".mbulan($bl)." ".$th;
        ?>
        <table width="100%" class="nmper" border="0">
            <tr>
                <td colspan="3">PT. <?= strtoupper($company->name); ?></td>
                <td ><?php echo KotaPerusahaan.',  '.$dop;?></td>
            </tr>
            <tr>
                <td colspan="4" ><?php echo AlmtPerusahaan; ?></td>
            </tr>
            <tr>
                <td colspan="3" rowspan="4" class="huruf judul" ><?php echo "ORDER PEMBELIAN"; ?></td>
            </tr>
            <tr>
                <td >&nbsp;</td>
            </tr>
            <tr>
                <td >Kepada Yth.</td>
            </tr>
            <tr>
                <td ><?php echo strtoupper($row->e_supplier_name)?></td>
            </td>
        </tr>
        <tr>
            <td width="100px">Nomor</td>
            <td >:</td>
            <td width="350px"><?php echo $row->i_op."/".$row->i_area."-".$row->e_area_shortname?></td>
            <td ><?php echo $row->e_supplier_address?></td>
        </tr>
        <tr>
            <td width="100px">No. SPmB</td>
            <td >:</td>
            <td width="350px"><?php echo $row->i_reff?></td>
            <td ><?php echo $row->e_supplier_city?></td>
        </tr>
        <tr align="center">
            <td colspan="4">
                <table width="98%" class="isi">
                    <tr>
                        <td colspan="5">&nbsp;</td>
                        <td class="garisatasgarisbawah" align="right"><?php echo "Hal:".$hal;?></td>
                    </tr>
                    <tr>
                        <td class="garisatas garisbawah">
                            NO.
                        </td>
                        <td class="garisatas garisbawah" colspan="2" align="center">
                            BANYAK
                        </td>
                        <td class="garisatas garisbawah">
                            KODE
                        </td>
                        <td class="garisatas garisbawah">
                            NAMA BARANG
                        </td>
                        <td class="garisatas garisbawah">
                            KETERANGAN
                        </td>
                    </tr>
                    <?php 
                    $i  = 0;
                    $j  = 0;
                    $hrg= 0;
                    $op = $row->i_op;
                    if(substr($row->i_reff,0,3)=='SPB'){
                        $query  = $this->db->query("select a.i_op from tm_op_item a, tm_spb_item b, tm_op c where a.i_op='$op'
                          and a.i_op=c.i_op
                          and a.i_op=b.i_op and c.i_reff=b.i_spb and c.i_area=b.i_area 
                          and a.i_product=b.i_product and a.i_product_motif=b.i_product_motif
                          and a.i_product_grade=b.i_product_grade",false);
                    }else{
                        $query  = $this->db->query("select a.i_op from tm_op_item a, tm_spmb_item b, tm_op c where a.i_op='$op'
                          and a.i_op=c.i_op
                          and a.i_op=b.i_op and c.i_reff=b.i_spmb and c.i_area=b.i_area 
                          and a.i_product=b.i_product and a.i_product_motif=b.i_product_motif
                          and a.i_product_grade=b.i_product_grade",false);
                    }
                    $jml  = $query->num_rows();
                    foreach($detail as $rowi){
                        $i++;
                        $j++;
                        $hrg  = $hrg+($rowi->n_order*$rowi->v_product_mill);
                        ?>
                        <tr>
                          <td width="25">
                            <?php echo $i;?>
                        </td>
                        <td width="20px" class="garisbawah">
                            &nbsp;
                        </td>
                        <td width="20px" class="garisbawah">
                            <?php echo $rowi->n_order;?>
                        </td>
                        <td>
                            <?php echo $rowi->i_product;?>
                        </td>
                        <td>
                            <?php 
                            if(strlen($rowi->e_product_name )>50){
                              $nam  = substr($rowi->e_product_name,0,50);
                          }else{
                              $nam  = $rowi->e_product_name.str_repeat(" ",50-strlen($rowi->e_product_name ));
                          }
                          echo $nam;?>
                      </td>
                      <td>
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
<table width="100%" class="nmper" >
    <tr>
        <td>
            <?php 
            $quer   = $this->db->query("select * from tr_supplier
              where i_supplier='$row->i_supplier'",false);
            if($quer->num_rows()>0){
                foreach($quer->result() as $xx){
                    if($xx->n_supplier_discount!=0 && $xx->n_supplier_discount!=null){
                        $yy=($hrg*$xx->n_supplier_discount)/100;
                        $hrg=$hrg-$yy;
                    }
                    if($xx->n_supplier_discount2!=0 && $xx->n_supplier_discount2!=null){
                        $yy=($hrg*$xx->n_supplier_discount2)/100;
                        $hrg=$hrg-$yy;
                    }
                }
            }
            $bilangan = new Terbilang;
            $kata=ucwords($bilangan->eja($hrg));  
            $hrg=number_format($hrg);

            echo"Jumlah Total  : Rp.".$hrg;?>
        </td>
        <td align="center">
            Hormat kami,
        </td>
        <td align="center">
            Menyetujui,
        </td>
    </tr>
    <tr>
        <td>
            <?php echo"(".$kata." RUPIAH)";?>
        </td>
        <td align="center">
            &nbsp;
        </td>
        <td align="center">
            &nbsp;
        </td>
    </tr>
    <tr>
        <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
        <td>Batas Pengiriman Terakhir : <?php 
        $tmp = explode("-", $row->d_op);
        $det  = $tmp[2];
        $mon  = $tmp[1];
        $yir  = $tmp[0];
        $dop  = $yir."/".$mon."/".$det;
        $dudet  =dateAdd("d",$row->n_delivery_limit,$dop);
        $dudet  = explode("-", $dudet);
        $det1 = $dudet[2];
        $mon1 = $dudet[1];
        $yir1   = $dudet[0];
        $dop  = $det1." ".mbulan($mon1)." ".$yir1;
        echo $dop;?></td>
        <td align="center" class="garisbawah"></td>
        <td align="center" class="garisbawah">Farrah Debri</td>
    </tr>
    <tr>
        <td>Cara pembayaran : <?php 
        if($row->n_top_length>0){
            $bayar= "Kredit ".$row->n_top_length." hari";
        }else{
            $bayar= "Tunai";
        }
        echo $bayar;?></td>
        <td align="center">Adm. Pembelian</td>
        <td align="center">MD</td>
    </tr>
    <tr>
        <td colspan ="3" align="left"  class="huruf judul"><?php echo $row->e_op_statusname?></td>
    </tr>
    <tr>
        <td colspan ="3" align="left"><?php echo $row->e_op_remark;?></td>
    </tr>  
    <tr>
        <td colspan ="3" align="left"><?php $tgl=date("d")." ".mbulan(date("m"))." ".date("Y")."  Jam : ".date("H:i:s");
        echo $tgl;?>

    </td>
</tr>
</table>

<?php } ?>
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