<link href="<?= base_url();?>assets/plugins/bower_components/bootstrap-table/dist/bootstrap-table.min.css" rel="stylesheet" type="text/css" />
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
            <?php
                $a=substr($iperiode,0,4);
                $b=substr($iperiode,4,2);
                $periode=$a.$b;
            ?>
                <i class="fa fa-info-circle"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/view/<?=$b;?>/<?=$a;?>/<?=$istore;?>/<?=$istorelocation;?>/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="col-sm-12">
                <div class="white-box">
                    
                    <input type="hidden" name="iperiode" id="iperiode" class="form-control" value="<?=$iperiode;?>">
                    <input type="hidden" name="iarea" id="iarea" class="form-control" value="<?=$iarea;?>">
                    <input type="hidden" name="istorelocation" id="istorelocation" class="form-control" value="<?=$istorelocation;?>">
                    <input type="hidden" name="iproduct" id="iproduct" class="form-control" value="<?=$iproduct;?>">
                    <input type="hidden" name="nsaldo" id="nsaldo" class="form-control" value="<?=$saldo;?>">

                    <?php
                        if($detail){
                            foreach($detail as $row){
                                if($row->area=='00'){
                                    $gudang = 'AA';
                                }else{
                                    $gudang = $row->area;
                                }
                            }
                        }
                    ?>
                    <h3 class="box-title m-b-0"><?= strtoupper($title).'-'.$gudang.' ('.$istorelocation.')';?></h3>
                    <p class="text-muted m-b-30">Periode : <?= $iperiode;?></p>
                    <h3 class="box-title m-b-0">Kode : <?= $iproduct.'-'.str_replace('%20',' ',$eproductname);?></h3>
                    <table data-show-columns="true" id="clmtable" data-height="500" data-mobile-responsive="true" class="table color-table success-table table-bordered">
                        <thead>
                            <tr>
                                <th>Refferensi</th>
			                    <th>Tanggal</th>
	     	                    <th>Nama Toko</th>
			                    <th>Awal</th>
			                    <th>In</th>
			                    <th>Out</th>
			                    <th>Akhir</th>
                                <th>GiT</th>
                                <th>GiT Penj</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($detail) {
                                $no=0;
                                $tsawal=0;
                                $in=0;
                                $out=0;
                                $sawal=0;
                                $sahir=$saldo;
                                $sgit=0;
                                $sgitp=0;
                                foreach ($detail as $row) {
                                    if($row->dreff!=''){
                                        $tmp=explode('-',$row->dreff);
                                        $tgl=$tmp[2];
                                        $bln=$tmp[1];
                                        $thn=$tmp[0];
                                        if(strlen($tgl)==2){
                                            $row->dreff=$tgl.'-'.$bln.'-'.$thn;
                                        }
                                    }
                                    $sawal=$sahir;
                                    if($no==0){
                                        $tsawal=$sawal;
                                    }
                                    $no++;
                                    $sahir=$sawal+$row->in-$row->out;
                                    $sgit =$sgit+$row->git;
                                    $sgitp =$sgitp+$row->gitpenjualan;
                                    if(substr($row->ireff,0,3)=='DO-'){
                                        $query = $this->db->query(" select i_op from tm_do where i_do='$row->ireff'",false);
                                        if($query->num_rows()>0){
                                            foreach($query->result() as $do){
                                                $quer = $this->db->query(" select i_reff from tm_op where i_op='$do->i_op'",false);
                                                if($quer->num_rows()>0){
                                                    foreach($quer->result() as $op){
                                                        $row->ireff=$row->ireff.' &nbsp;-> ('.$op->i_reff.')';
                                                    }
                                                }
                                            }
                                        }
                                    }elseif(substr($row->ireff,0,3)=='SJ-'){
                                        $quer = $this->db->query(" select a.i_spb, a.i_area, b.i_spmb from tm_nota a, tm_spb b where
                                                                    a.i_spb = b.i_spb
                                                                    and a.i_customer = b.i_customer
                                                                    and a.i_area = b.i_area
                                                                    and a. i_sj='$row->ireff'",false);
                                        if($quer->num_rows()>0){
                                            foreach($quer->result() as $pb){
                                                $row->ireff=$row->ireff.' &nbsp;-> ('.$pb->i_spb.') &nbsp;-> ('.$pb->i_spmb.')';
                                            }
                                        }
                                    }elseif(substr($row->ireff,0,3)=='SJP'){
                                        $quer = $this->db->query(" select i_spmb, i_area from tm_sjp where i_sjp='$row->ireff'",false);
                                        if($quer->num_rows()>0){
                                            foreach($quer->result() as $pb){
                                                $row->ireff=$row->ireff.' -> ('.$pb->i_spmb.' ('.$pb->i_area.'))';
                                            }
                                        }
                                      }elseif(substr($row->ireff,0,2)=='SB'){
                                            $quer = $this->db->query(" select i_sjp, i_area from tm_sjpb where i_sjpb='$row->ireff'",false);
                                            if($quer->num_rows()>0){
                                                foreach($quer->result() as $pb){
                                                    $row->ireff=$row->ireff.' -> ('.$pb->i_sjp.' ('.$pb->i_area.'))';
                                                }
                                            }
                                      }?>
                                    <tr>
                                        <td><?= $row->ireff;?></td>
                                        <td><?= $row->dreff;?></td>
                                        <td><?= $row->e_customer_name;?></td>
                                        <td class="text-right"><?= $sawal;?></td>
                                        <td class="text-right"><?= $in;?></td>
                                        <td class="text-right"><?= $out;?></td>
                                        <td class="text-right"><?= $sahir;?></td>
                                        <td class="text-right"><?= $row->git;?></td>
                                        <td class="text-right"><?= $row->gitpenjualan;?></td>
                                    </tr>
                                    <?php 
                                    $in=$in+$row->in;
                                    $out=$out+$row->out;
                                    }?>
                                <tr>
                                    <td colspan="3" class="text-center">TOTAL</td>
                                    <td class="text-right"><?= number_format($tsawal);?></td>
                                    <td class="text-right"><?= number_format($in);?></td>
                                    <td class="text-right"><?= number_format($out);?></td>
                                    <td class="text-right"><?= number_format($sahir);?></td>
                                    <td class="text-right"><?= number_format($sgit);?></td>
                                    <td class="text-right"><?= number_format($sgitp);?></td>
                                </tr>
                            <? } ?>
                        </tbody>
                    </table>
                    <div style="text-align: center;">
                        <button type="button" id="cetak" name="cetak" class="btn btn-secondary btn-rounded btn-sm" onclick="yyy();"><i class="fa fa-download"></i>&nbsp;&nbsp;PRINT</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url();?>assets/plugins/bower_components/bootstrap-table/dist/bootstrap-table.min.js"></script>
<script>
    function yyy(){
  	    lebar =1024;
        tinggi=768;
        var periode = $('#iperiode').val();
        var area = $('#iarea').val();
        var product = $('#iproduct').val();
        var sawal = $('#nsaldo').val();
        var istorelocation = $('#istorelocation').val();
        //periode=document.getElementById("iperiode").value;
        //area   =document.getElementById("iarea").value;
        //product=document.getElementById("iproduct").value;
        //sawal  =document.getElementById("saldo").value;
        //istorelocation   =document.getElementById("istorelocation").value;
        eval('window.open("<?php echo site_url(); ?>"+"/listmutasidaerah/cform/cetakdetail/"+periode+"/"+area+"/"+product+"/"+sawal+"/"+istorelocation,"","width="+lebar+"px,height="+tinggi+"px,resizable=1,menubar=1,scrollbars=1,top='+(screen.height-tinggi)/2+',left='+(screen.width-lebar)/2+'")');
    }
</script>
<!-- <script type="text/javascript">
    $(function () {
        $('#clmtable').bootstrapTable('destroy').bootstrapTable();
    });
</script> -->