<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-eye"></i> <?= $title; ?><a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $supplier."/".$dfrom."/".$dto;?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> &nbsp;<?= $title_list;?>  </a>
            </div>
            <?php if ($data) {?>
                <div class="white-box" id="detail">
                    <h3 class="box-title m-b-0">Periode : <?= date('d',strtotime($dfrom))." ".$this->fungsi->mbulan(date('m',strtotime($dfrom)))." ".date('Y',strtotime($dfrom));?> s/d <?= date('d',strtotime($dto))." ".$this->fungsi->mbulan(date('m',strtotime($dto)))." ".date('Y',strtotime($dto));?></h3>
                    <h3 class="box-title m-b-0">Detail BTB Dari OP : <span class="text-info"><b><?=$iop;?></b></span></h3>
                    <div class="table-responsive">
                        <table id="tabledatax" class="table color-table inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 3%;">No</th>
                                    <th class="text-center" style="width: 10%;">No. Dok BTB</th>
                                    <th class="text-center" style="width: 7%;">Kode</th>
                                    <th class="text-center" style="width: 35%;">Nama Material</th>
                                    <th class="text-center" style="width: 5%;">Satuan</th>
                                    <th class="text-center" style="width: 8%;">Jml BTB</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $i = 1;
                                foreach ($data as $row) {?>
                                    <tr>
                                        <td class="text-center"><?= $i ;?></td>
                                        <td><?= $row->i_btb ;?></td>
                                        <td><?= $row->i_material ;?></td>
                                        <td><?= $row->e_material_name ;?></td>
                                        <td><?= $row->e_satuan_name ;?></td>
                                        <td class="text-right"><?= $row->n_quantity ;?></td>
                                    </tr>
                                    <?php $i++; 
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php }else{ ?>                
                <div class="white-box">
                    <div class="card card-outline-success text-center text-dark">
                        <div class="card-block">
                            <footer>
                                <cite title="Source Title"><b>BELUM ADA BTB</b></cite>
                            </footer>
                        </div>
                    </div>
                </div>
            <?php }
            ?>
        </div>
    </div>
</div>