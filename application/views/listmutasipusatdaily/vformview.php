<link href="<?= base_url();?>assets/plugins/bower_components/bootstrap-table/dist/bootstrap-table.min.css" rel="stylesheet" type="text/css" />
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-info-circle"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="col-sm-12">
                <div class="white-box">
                    <?php 
                    $a=substr($tgl,6,2);
                    $b=substr($tgl,4,2);
                    $c=substr($tgl,0,4);
                    $periode=$a.' '.$this->fungsi->mbulan($b).' '.$c;
                    ?>
                    <h3 class="box-title m-b-0"><?= strtoupper($title);?></h3>
                    <p class="text-muted m-b-30">Periode <?= $periode;?></p>
                    <div class="table-responsive">
                        <table data-show-columns="true" id="clmtable" data-height="500" data-mobile-responsive="true" class="table color-table success-table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode</th>
                                    <th>Grade</th>
                                    <th>Nama</th>
                                    <th>Saldo Awal</th>
                                    <th>SJ</th>
                                    <th>Konv +</th>
                                    <th>Konv -</th>
                                    <th>SJP</th>
                                    <th>BBk</th>
                                    <th>SJR</th>
                                    <th>BBM</th>
                                    <th>DO</th>
                                    <th>BBMAP</th>
                                    <th>SJBR</th>
                                    <th>BBK Retur</th>
                                    <th>Saldo Akhir</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($isi) {
                                    $no = 0;
                                    $group='';
                                    $saldoakhir=0;
                                    $totsaldoawal=0;
                                    $totsj=0;
                                    $totconvplus=0;
                                    $totconvminus=0;
                                    $totsjp=0;
                                    $totbbk=0;
                                    $totsjr=0;
                                    $totbbm=0;
                                    $totsido=0;
                                    $totbbmap=0;
                                    $totsjbr=0;
                                    $totbbkretur=0;
                                    $totsaldoakhir=0;
                                    foreach ($isi->result() as $row) {
                                        $no++;
                                        $saldoakhir=($row->saldoawal+$row->convplus+$row->sjr+$row->bbm+$row->sido+$row->bbmap+$row->sjbr)-($row->sj+$row->convminus+$row->sjp+$row->bbk+$row->bbkretur);
                                        if($group==''){ ?>
                                            <tr>
                                                <td colspan="17" class="text-center text-success" style="font-size:18px;"><b><?= strtoupper($row->e_product_groupname);?></b></td>
                                            </tr>
                                            <?php 
                                            $no=1;
                                            $gtotsaldoawal=0;
                                            $gtotsj=0;
                                            $gtotconvplus=0;
                                            $gtotconvminus=0;
                                            $gtotsjp=0;
                                            $gtotbbk=0;
                                            $gtotsjr=0;
                                            $gtotbbm=0;
                                            $gtotsido=0;
                                            $gtotbbmap=0;
                                            $gtotsjbr=0;
                                            $gtotbbkretur=0;
                                            $gtotsaldoakhir=0;
                                        }else{
                                            if($group!=$row->e_product_groupname){?>
                                                <tr>
                                                    <td colspan="4" class="text-center">TOTAL <?= strtoupper($group);?></td>
                                                    <td class="text-right"><?= $gtotsaldoawal;?></td>
                                                    <td class="text-right"><?= $gtotsj;?></td>
                                                    <td class="text-right"><?= $gtotconvplus;?></td>
                                                    <td class="text-right"><?= $gtotconvminus;?></td>
                                                    <td class="text-right"><?= $gtotsjp;?></td>
                                                    <td class="text-right"><?= $gtotbbk;?></td>
                                                    <td class="text-right"><?= $gtotsjr;?></td>
                                                    <td class="text-right"><?= $gtotbbm;?></td>
                                                    <td class="text-right"><?= $gtotsido;?></td>
                                                    <td class="text-right"><?= $gtotbbmap;?></td>
                                                    <td class="text-right"><?= $gtotsjbr;?></td>
                                                    <td class="text-right"><?= $gtotbbkretur;?></td>
                                                    <td class="text-right"><?= $gtotsaldoakhir;?></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="17" colspan="17" class="text-center text-success" style="font-size:18px;"><b><?= strtoupper($row->e_product_groupname);?></b></td>
                                                </tr>
                                                <?php 
                                                $no=1;
                                                $gtotsaldoawal=0;
                                                $gtotsj=0;
                                                $gtotconvplus=0;
                                                $gtotconvminus=0;
                                                $gtotsjp=0;
                                                $gtotbbk=0;
                                                $gtotsjr=0;
                                                $gtotbbm=0;
                                                $gtotsido=0;
                                                $gtotbbmap=0;
                                                $gtotsjbr=0;
                                                $gtotbbkretur=0;
                                                $gtotsaldoakhir=0;
                                            }
                                        } 
                                        $group=$row->e_product_groupname;
                                        ?>
                                        <tr>
                                            <td class="text-center"><?= $no;?></td>
                                            <td><?= $row->i_product;?></td>
                                            <td><?= $row->i_product_grade;?></td>
                                            <td><?= $row->e_product_name;?></td>
                                            <td class="text-right"><?= $row->saldoawal;?></td>
                                            <td class="text-right"><?= $row->sj;?></td>
                                            <td class="text-right"><?= $row->convplus;?></td>
                                            <td class="text-right"><?= $row->convminus;?></td>
                                            <td class="text-right"><?= $row->sjp;?></td>
                                            <td class="text-right"><?= $row->bbk;?></td>
                                            <td class="text-right"><?= $row->sjr;?></td>
                                            <td class="text-right"><?= $row->bbm;?></td>
                                            <td class="text-right"><?= $row->sido;?></td>
                                            <td class="text-right"><?= $row->bbmap;?></td>
                                            <td class="text-right"><?= $row->sjbr;?></td>
                                            <td class="text-right"><?= $row->bbkretur;?></td>
                                            <td class="text-right"><?= $saldoakhir;?></td>
                                        </tr>
                                        <?php 
                                        $totsaldoawal=$totsaldoawal+$row->saldoawal;
                                        $totsj=$totsj+$row->sj;
                                        $totconvplus=$totconvplus+$row->convplus;
                                        $totconvminus=$totconvminus+$row->convminus;
                                        $totsjp=$totsjp+$row->sjp;
                                        $totbbk=$totbbk+$row->bbk;
                                        $totsjr=$totsjr+$row->sjr;
                                        $totbbm=$totbbm+$row->bbm;
                                        $totsido=$totsido+$row->sido;
                                        $totbbmap=$totbbmap+$row->bbmap;
                                        $totsjbr=$totsjbr+$row->sjbr;
                                        $totbbkretur=$totbbkretur+$row->bbkretur;
                                        $totsaldoakhir=$totsaldoakhir+$saldoakhir;
                                        $gtotsaldoawal=$gtotsaldoawal+$row->saldoawal;
                                        $gtotsj=$gtotsj+$row->sj;
                                        $gtotconvplus=$gtotconvplus+$row->convplus;
                                        $gtotconvminus=$gtotconvminus+$row->convminus;
                                        $gtotsjp=$gtotsjp+$row->sjp;
                                        $gtotbbk=$gtotbbk+$row->bbk;
                                        $gtotsjr=$gtotsjr+$row->sjr;
                                        $gtotbbm=$gtotbbm+$row->bbm;
                                        $gtotsido=$gtotsido+$row->sido;
                                        $gtotbbmap=$gtotbbmap+$row->bbmap;
                                        $gtotsjbr=$gtotsjbr+$row->sjbr;
                                        $gtotbbkretur=$gtotbbkretur+$row->bbkretur;
                                        $gtotsaldoakhir=$gtotsaldoakhir+$saldoakhir;
                                    }?>
                                    <tr>
                                        <td colspan="4" class="text-center">TOTAL <?= strtoupper($group);?></td>
                                        <td class="text-right"><?= $gtotsaldoawal;?></td>
                                        <td class="text-right"><?= $gtotsj;?></td>
                                        <td class="text-right"><?= $gtotconvplus;?></td>
                                        <td class="text-right"><?= $gtotconvminus;?></td>
                                        <td class="text-right"><?= $gtotsjp;?></td>
                                        <td class="text-right"><?= $gtotbbk;?></td>
                                        <td class="text-right"><?= $gtotsjr;?></td>
                                        <td class="text-right"><?= $gtotbbm;?></td>
                                        <td class="text-right"><?= $gtotsido;?></td>
                                        <td class="text-right"><?= $gtotbbmap;?></td>
                                        <td class="text-right"><?= $gtotsjbr;?></td>
                                        <td class="text-right"><?= $gtotbbkretur;?></td>
                                        <td class="text-right"><?= $gtotsaldoakhir;?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-center">TOTAL</td>
                                        <td class="text-right"><?= $totsaldoawal;?></td>
                                        <td class="text-right"><?= $totsj;?></td>
                                        <td class="text-right"><?= $totconvplus;?></td>
                                        <td class="text-right"><?= $totconvminus;?></td>
                                        <td class="text-right"><?= $totsjp;?></td>
                                        <td class="text-right"><?= $totbbk;?></td>
                                        <td class="text-right"><?= $totsjr;?></td>
                                        <td class="text-right"><?= $totbbm;?></td>
                                        <td class="text-right"><?= $totsido;?></td>
                                        <td class="text-right"><?= $totbbmap;?></td>
                                        <td class="text-right"><?= $totsjbr;?></td>
                                        <td class="text-right"><?= $totbbkretur;?></td>
                                        <td class="text-right"><?= $totsaldoakhir;?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url();?>assets/plugins/bower_components/bootstrap-table/dist/bootstrap-table.min.js"></script>
<!-- <script type="text/javascript">
    $(function () {
        $('#clmtable').bootstrapTable('destroy').bootstrapTable();
    });
</script> -->