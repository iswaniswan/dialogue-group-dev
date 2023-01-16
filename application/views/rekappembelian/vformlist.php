<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            
            <div class="panel-body table-responsive">
            <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/index'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Date From</label>
                        <label class="col-md-3">Date To</label>
                        <label class="col-md-3">Kategori Barang</label>
                        <label class="col-md-3">Jenis Barang</label>     
                        <div class="col-sm-3">
                            <input type="text" id="dfrom" name="dfrom" class="form-control date"  readonly value="<?=$dfrom;?>">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="dto" name="dto" class="form-control date"  readonly value="<?=$dto;?>">
                        </div>
                        <div class="col-sm-3">
                            <select id="kategori" name="kategori" class="form-control select2" onchange="getjenisbarang(this.value);">
                                <?php if($ikategori == 'ALL'){?>
                                    <option value="ALL" selected>Semua Kategori Barang</option>
                                    <?php foreach ($kategori as $key):?>
                                        <option value="<?php echo $key->i_kode_kelompok;?>"><?=$key->i_kode_kelompok.' - '.$key->e_nama_kelompok;?></option>
                                    <?php endforeach; ?>
                                <?}else{?>
                                    <option value="<?=$ikategori;?>" selected><?=$ikategori.' - '.$namekategori->e_nama_kelompok;?></option>
                                    <option value="ALL" >Semua Kategori Barang</option>
                                    <?php foreach ($kategori as $key):?>
                                        <option value="<?php echo $key->i_kode_kelompok;?>"><?=$key->i_kode_kelompok.' - '.$key->e_nama_kelompok;?></option>
                                    <?php endforeach; ?>
                                <?}?>
                            </select>
                            <?php if($ikategori == 'ALL'){?>
                                <input type="hidden" id="ekategori" name="ekategori" class="form-control"  readonly value="Semua Kategori Barang">
                            <?}else{?>
                                <input type="hidden" id="ekategori" name="ekategori" class="form-control"  readonly value="<?=$namekategori->e_nama_kelompok;?>">
                            <?}?>
                        </div>
                        <div class="col-sm-3">
                            <select id="jenis" name="jenis" class="form-control select2" disabled>
                                <?php if($ijenis == 'ALL'){?>
                                    <option value="ALL" selected>Semua Jenis Barang</option>
                                <?}else{?>
                                    <option value="<?=$ijenis;?>" selected><?=$ijenis.' - '.$namejenis->e_type_name;?></option>
                                    <option value="ALL">Semua Jenis Barang</option>
                                <?}?>
                            </select>
                            <?php if($ijenis == 'ALL'){?>
                                <input type="hidden" id="ejenis" name="ejenis" class="form-control"  readonly value="Semua Jenis Barang">
                            <?}else{?>
                                <input type="hidden" id="ejenis" name="ejenis" class="form-control"  readonly value="<?=$namejenis->e_type_name;?>">
                            <?}?>
                        </div>
                        <div style="display:none;" class="col-sm-4">
                            <select id="produk" name="produk" class="form-control select2" hidden></select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-5 col-sm-10">
                            <button type="submit" id="submit" class="btn btn-info btn-rounded btn-sm"> <i class="fa fa-search"></i>&nbsp;&nbsp;Search</button>&nbsp;&nbsp;&nbsp;&nbsp;
                            <a id="downloadLink" onclick="exportF(this);"><button type="button" class="btn btn-inverse btn-rounded btn-sm"><i class="fa fa-download"></i>&nbsp;&nbsp;Export</button> </a>
                        </div>
                    </div>
                </div>
                
                <table id="tabledata" class="table color-table inverse-table table-bordered"" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th style="text-align:center;" rowspan=2>No</th>
                            <th style="text-align:center;" rowspan=2>Supplier</th>
	                        <th style="text-align:center;" rowspan=2>Kode Barang</th>
		                    <th style="text-align:center;" rowspan=2>Nama Barang</th>
                            <?php                         
                                if($dfrom!=''){
	                            	$tmp=explode("-",$dfrom);
	                            	$blasal=$tmp[1];
                                    settype($bl,'integer');
                                }
                                $interval = $interfall->inter;
                                $bl=$blasal;
                                for($i=0;$i<=$interval;$i++){
                                    switch ($bl){
                                        case '01' :
                                          echo '<th style="text-align:center;" colspan=6>Januari</th>';
                                          break;
                                        case '02' :
                                          echo '<th style="text-align:center;" colspan=6>Februari</th>';
                                          break;
                                        case '03' :
                                          echo '<th style="text-align:center;" colspan=6>Maret</th>';
                                          break;
                                        case '04' :
                                          echo '<th style="text-align:center;" colspan=6>April</th>';
                                          break;
                                        case '05' :
                                          echo '<th style="text-align:center;" colspan=6>Mei</th>';
                                          break;
                                        case '06' :
                                          echo '<th style="text-align:center;" colspan=6>Juni</th>';
                                          break;
                                        case '07' :
                                          echo '<th style="text-align:center;" colspan=6>Juli</th>';
                                          break;
                                        case '08' :
                                          echo '<th style="text-align:center;" colspan=6>Agustus</th>';
                                          break;
                                        case '09' :
                                          echo '<th style="text-align:center;" colspan=6>September</th>';
                                          break;
                                        case '10' :
                                          echo '<th style="text-align:center;" colspan=6>Oktober</th>';
                                          break;
                                        case '11' :
                                          echo '<th style="text-align:center;" colspan=6>November</th>';
                                          break;
                                        case '12' :
                                          echo '<th style="text-align:center;" colspan=6>Desember</th>';
                                          break;
                                    }
                                    $bl++;
                                    if($bl==13)$bl=1;
                                }
                            ?>
                            <th style="text-align:center;" rowspan=2>Total QTY OP</th>
                            <th style="text-align:center;" rowspan=2>Total QTY BTB</th>
                            <th style="text-align:center;" rowspan=2>Total Nilai OP</th>
                            <th style="text-align:center;" rowspan=2>Total Nilai BTB</th>
                            <th style="text-align:center;" rowspan=2>% Pemenuhan</th>

                        </tr>
                        <tr>
                            <?php
                            for($i=0;$i<=$interval;$i++){
                                echo '<th style="text-align:center;">QTY OP</th>';
                                echo '<th style="text-align:center;">Qty BTB</th>';
                                echo '<th style="text-align:center;">Nilai OP</th>';
                                echo '<th style="text-align:center;">Nilai BTB</th>';
                                echo '<th style="text-align:center;">Sisa Qty OP</th>';
                                echo '<th style="text-align:center;">Sisa Nilai OP</th>';
                            }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
		                foreach($data as $row){
                            $jan = explode('|',$row->jan);
                            $qtybtb_jan     = (float)$jan[0];
                            $qtyop_jan      = (float)$jan[1];
                            $nilaibtb_jan   = (float)$jan[2];
                            $nilaiop_jan    = (float)$jan[3];

                            $feb = explode('|',$row->feb);
                            $qtybtb_feb     = (float)$feb[0];
                            $qtyop_feb      = (float)$feb[1];
                            $nilaibtb_feb   = (float)$feb[2];
                            $nilaiop_feb    = (float)$feb[3];

                            $mar = explode('|',$row->mar);
                            $qtybtb_mar     = (float)$mar[0];
                            $qtyop_mar      = (float)$mar[1];
                            $nilaibtb_mar   = (float)$mar[2];
                            $nilaiop_mar    = (float)$mar[3];

                            $apr = explode('|',$row->apr);
                            $qtybtb_apr     = (float)$apr[0];
                            $qtyop_apr      = (float)$apr[1];
                            $nilaibtb_apr   = (float)$apr[2];
                            $nilaiop_apr    = (float)$apr[3];

                            $may = explode('|',$row->may);
                            $qtybtb_may     = (float)$may[0];
                            $qtyop_may      = (float)$may[1];
                            $nilaibtb_may   = (float)$may[2];
                            $nilaiop_may    = (float)$may[3];

                            $jun = explode('|',$row->jun);
                            $qtybtb_jun     = (float)$jun[0];
                            $qtyop_jun      = (float)$jun[1];
                            $nilaibtb_jun   = (float)$jun[2];
                            $nilaiop_jun    = (float)$jun[3];

                            $jul = explode('|',$row->jul);
                            $qtybtb_jul     = (float)$jul[0];
                            $qtyop_jul      = (float)$jul[1];
                            $nilaibtb_jul   = (float)$jul[2];
                            $nilaiop_jul    = (float)$jul[3];

                            $aug = explode('|',$row->aug);
                            $qtybtb_aug     = (float)$aug[0];
                            $qtyop_aug      = (float)$aug[1];
                            $nilaibtb_aug   = (float)$aug[2];
                            $nilaiop_aug    = (float)$aug[3];

                            $sep = explode('|',$row->sep);
                            $qtybtb_sep     = (float)$sep[0];
                            $qtyop_sep      = (float)$sep[1];
                            $nilaibtb_sep   = (float)$sep[2];
                            $nilaiop_sep    = (float)$sep[3];

                            $oct = explode('|',$row->oct);
                            $qtybtb_oct     = (float)$oct[0];
                            $qtyop_oct      = (float)$oct[1];
                            $nilaibtb_oct   = (float)$oct[2];
                            $nilaiop_oct    = (float)$oct[3];

                            $nov = explode('|',$row->nov);
                            $qtybtb_nov     = (float)$nov[0];
                            $qtyop_nov      = (float)$nov[1];
                            $nilaibtb_nov   = (float)$nov[2];
                            $nilaiop_nov    = (float)$nov[3];

                            $des = explode('|',$row->des);
                            $qtybtb_des     = (float)$des[0];
                            $qtyop_des      = (float)$des[1];
                            $nilaibtb_des   = (float)$des[2];
                            $nilaiop_des    = (float)$des[3];
                           
                            $rataqtybtb     = (float) 0;
                            $rataqtyop      = (float) 0;
                            $ratanilaibtb   = (float) 0;
                            $ratanilaiop    = (float) 0;

                              echo "<tr>
                                    <td style='text-align:center;'>$row->nomor</td>
                                    <td>$row->isupplier".' - '."$row->esupplier</td>
                                    <td>$row->imaterial</td>
                                    <td style='width:300px;'>$row->ematerial</td>";
                            $bl=$blasal;
                            $interval = $interfall->inter;
                            for($i=0;$i<=$interval;$i++){
                                switch ($bl){
                                    case '01' :
                                        $rataqtyop      =$rataqtyop+$qtyop_jan;
                                        $rataqtybtb     =$rataqtybtb+$qtybtb_jan;
                                        $ratanilaiop    =$ratanilaiop+$nilaiop_jan;
                                        $ratanilaibtb   =$ratanilaibtb+$nilaibtb_jan;

                                        echo '<td align=right>'.number_format($qtybtb_jan).'</td>';
                                        echo '<td align=right>'.number_format($qtyop_jan).'</td>';
                                        echo '<td align=right>'.number_format($nilaibtb_jan).'</td>';
                                        echo '<td align=right>'.number_format($nilaiop_jan).'</td>';
                                        echo '<td align=right>'.number_format($qtybtb_jan-$qtyop_jan).'</td>';
                                        echo '<td align=right>'.number_format($nilaibtb_jan-$nilaiop_jan).'</td>';
                                      break;
                                    case '02' :
                                        $rataqtyop      =$rataqtyop+$qtyop_feb;
                                        $rataqtybtb     =$rataqtybtb+$qtybtb_feb;
                                        $ratanilaiop    =$ratanilaiop+$nilaiop_feb;
                                        $ratanilaibtb   =$ratanilaibtb+$nilaibtb_feb;

                                        echo '<td align=right>'.number_format($qtybtb_feb).'</td>';
                                        echo '<td align=right>'.number_format($qtyop_feb).'</td>';
                                        echo '<td align=right>'.number_format($nilaibtb_feb).'</td>';
                                        echo '<td align=right>'.number_format($nilaiop_feb).'</td>';
                                        echo '<td align=right>'.number_format($qtybtb_feb-$qtyop_feb).'</td>';
                                        echo '<td align=right>'.number_format($nilaibtb_feb-$nilaiop_feb).'</td>';
                                    break;
                                    case '03' :
                                        $rataqtyop      =$rataqtyop+$qtyop_mar;
                                        $rataqtybtb     =$rataqtybtb+$qtybtb_mar;
                                        $ratanilaiop    =$ratanilaiop+$nilaiop_mar;
                                        $ratanilaibtb   =$ratanilaibtb+$nilaibtb_mar;
                                        
                                        echo '<td align=right>'.number_format($qtybtb_mar).'</td>';
                                        echo '<td align=right>'.number_format($qtyop_mar).'</td>';
                                        echo '<td align=right>'.number_format($nilaibtb_mar).'</td>';
                                        echo '<td align=right>'.number_format($nilaiop_mar).'</td>';
                                        echo '<td align=right>'.number_format($qtybtb_mar-$qtyop_mar).'</td>';
                                        echo '<td align=right>'.number_format($nilaibtb_mar-$nilaiop_mar).'</td>';
                                    break;
                                    case '04' :
                                        $rataqtyop      =$rataqtyop+$qtyop_apr;
                                        $rataqtybtb     =$rataqtybtb+$qtybtb_apr;
                                        $ratanilaiop    =$ratanilaiop+$nilaiop_apr;
                                        $ratanilaibtb   =$ratanilaibtb+$nilaibtb_apr;
                                        
                                        echo '<td align=right>'.number_format($qtybtb_apr).'</td>';
                                        echo '<td align=right>'.number_format($qtyop_apr).'</td>';
                                        echo '<td align=right>'.number_format($nilaibtb_apr).'</td>';
                                        echo '<td align=right>'.number_format($nilaiop_apr).'</td>';
                                        echo '<td align=right>'.number_format($qtybtb_apr-$qtyop_apr).'</td>';
                                        echo '<td align=right>'.number_format($nilaibtb_apr-$nilaiop_apr).'</td>';
                                    break;
                                    case '05' :
                                        $rataqtyop      =$rataqtyop+$qtyop_may;
                                        $rataqtybtb     =$rataqtybtb+$qtybtb_may;
                                        $ratanilaiop    =$ratanilaiop+$nilaiop_may;
                                        $ratanilaibtb   =$ratanilaibtb+$nilaibtb_may;
                                        
                                        echo '<td align=right>'.number_format($qtybtb_may).'</td>';
                                        echo '<td align=right>'.number_format($qtyop_may).'</td>';
                                        echo '<td align=right>'.number_format($nilaibtb_may).'</td>';
                                        echo '<td align=right>'.number_format($nilaiop_may).'</td>';
                                        echo '<td align=right>'.number_format($qtybtb_may-$qtyop_may).'</td>';
                                        echo '<td align=right>'.number_format($nilaibtb_may-$nilaiop_may).'</td>';
                                    break;
                                    case '06' :
                                        $rataqtyop      =$rataqtyop+$qtyop_jun;
                                        $rataqtybtb     =$rataqtybtb+$qtybtb_jun;
                                        $ratanilaiop    =$ratanilaiop+$nilaiop_jun;
                                        $ratanilaibtb   =$ratanilaibtb+$nilaibtb_jun;
                                        
                                        echo '<td align=right>'.number_format($qtybtb_jun).'</td>';
                                        echo '<td align=right>'.number_format($qtyop_jun).'</td>';
                                        echo '<td align=right>'.number_format($nilaibtb_jun).'</td>';
                                        echo '<td align=right>'.number_format($nilaiop_jun).'</td>';
                                        echo '<td align=right>'.number_format($qtybtb_jun-$qtyop_jun).'</td>';
                                        echo '<td align=right>'.number_format($nilaibtb_jun-$nilaiop_jun).'</td>';
                                    break;
                                    case '07' :
                                        $rataqtyop      =$rataqtyop+$qtyop_jul;
                                        $rataqtybtb     =$rataqtybtb+$qtybtb_jul;
                                        $ratanilaiop    =$ratanilaiop+$nilaiop_jul;
                                        $ratanilaibtb   =$ratanilaibtb+$nilaibtb_jul;
                                        
                                        echo '<td align=right>'.number_format($qtybtb_jul).'</td>';
                                        echo '<td align=right>'.number_format($qtyop_jul).'</td>';
                                        echo '<td align=right>'.number_format($nilaibtb_jul).'</td>';
                                        echo '<td align=right>'.number_format($nilaiop_jul).'</td>';
                                        echo '<td align=right>'.number_format($qtybtb_jul-$qtyop_jul).'</td>';
                                        echo '<td align=right>'.number_format($nilaibtb_jul-$nilaiop_jul).'</td>';
                                    break;
                                    case '08' :
                                        $rataqtyop      =$rataqtyop+$qtyop_aug;
                                        $rataqtybtb     =$rataqtybtb+$qtybtb_aug;
                                        $ratanilaiop    =$ratanilaiop+$nilaiop_aug;
                                        $ratanilaibtb   =$ratanilaibtb+$nilaibtb_aug;
                                        
                                        echo '<td align=right>'.number_format($qtybtb_aug).'</td>';
                                        echo '<td align=right>'.number_format($qtyop_aug).'</td>';
                                        echo '<td align=right>'.number_format($nilaibtb_aug).'</td>';
                                        echo '<td align=right>'.number_format($nilaiop_aug).'</td>';
                                        echo '<td align=right>'.number_format($qtybtb_aug-$qtyop_aug).'</td>';
                                        echo '<td align=right>'.number_format($nilaibtb_aug-$nilaiop_aug).'</td>';
                                    break;
                                    case '09' :
                                        $rataqtyop      =$rataqtyop+$qtyop_sep;
                                        $rataqtybtb     =$rataqtybtb+$qtybtb_sep;
                                        $ratanilaiop    =$ratanilaiop+$nilaiop_sep;
                                        $ratanilaibtb   =$ratanilaibtb+$nilaibtb_sep;
                                        
                                        echo '<td align=right>'.number_format($qtybtb_sep).'</td>';
                                        echo '<td align=right>'.number_format($qtyop_sep).'</td>';
                                        echo '<td align=right>'.number_format($nilaibtb_sep).'</td>';
                                        echo '<td align=right>'.number_format($nilaiop_sep).'</td>';
                                        echo '<td align=right>'.number_format($qtybtb_sep-$qtyop_sep).'</td>';
                                        echo '<td align=right>'.number_format($nilaibtb_sep-$nilaiop_sep).'</td>';
                                    break;
                                    case '10' :
                                        $rataqtyop      =$rataqtyop+$qtyop_oct;
                                        $rataqtybtb     =$rataqtybtb+$qtybtb_oct;
                                        $ratanilaiop    =$ratanilaiop+$nilaiop_oct;
                                        $ratanilaibtb   =$ratanilaibtb+$nilaibtb_oct;
                                        
                                        echo '<td align=right>'.number_format($qtybtb_oct).'</td>';
                                        echo '<td align=right>'.number_format($qtyop_oct).'</td>';
                                        echo '<td align=right>'.number_format($nilaibtb_oct).'</td>';
                                        echo '<td align=right>'.number_format($nilaiop_oct).'</td>';
                                        echo '<td align=right>'.number_format($qtybtb_oct-$qtyop_oct).'</td>';
                                        echo '<td align=right>'.number_format($nilaibtb_oct-$nilaiop_oct).'</td>';
                                    break;
                                    case '11' :
                                        $rataqtyop      =$rataqtyop+$qtyop_nov;
                                        $rataqtybtb     =$rataqtybtb+$qtybtb_nov;
                                        $ratanilaiop    =$ratanilaiop+$nilaiop_nov;
                                        $ratanilaibtb   =$ratanilaibtb+$nilaibtb_nov;
                                        
                                        echo '<td align=right>'.number_format($qtybtb_nov).'</td>';
                                        echo '<td align=right>'.number_format($qtyop_nov).'</td>';
                                        echo '<td align=right>'.number_format($nilaibtb_nov).'</td>';
                                        echo '<td align=right>'.number_format($nilaiop_nov).'</td>';
                                        echo '<td align=right>'.number_format($qtybtb_nov-$qtyop_nov).'</td>';
                                        echo '<td align=right>'.number_format($nilaibtb_nov-$nilaiop_nov).'</td>';
                                    break;
                                    case '12' :
                                        $rataqtyop      =$rataqtyop+$qtyop_des;
                                        $rataqtybtb     =$rataqtybtb+$qtybtb_des;
                                        $ratanilaiop    =$ratanilaiop+$nilaiop_des;
                                        $ratanilaibtb   =$ratanilaibtb+$nilaibtb_des;
                                        
                                        echo '<td align=right>'.number_format($qtybtb_des).'</td>';
                                        echo '<td align=right>'.number_format($qtyop_des).'</td>';  
                                        echo '<td align=right>'.number_format($nilaibtb_des).'</td>';                                    
                                        echo '<td align=right>'.number_format($nilaiop_des).'</td>';
                                        echo '<td align=right>'.number_format($qtybtb_des-$qtyop_des).'</td>';
                                        echo '<td align=right>'.number_format($nilaibtb_des-$nilaiop_des).'</td>';
                                    break;
                                }
                              $bl++;
                              if($bl==13)$bl=1;
                            }
                            echo '<th style="text-align:right;">'.number_format($rataqtybtb).'</th>';
                            echo '<th style="text-align:right;">'.number_format($rataqtyop).'</th>';
                            echo '<th style="text-align:right;">'.number_format($ratanilaibtb).'</th>';
                            echo '<th style="text-align:right;">'.number_format($ratanilaiop).'</th>';
                            echo '<th style="text-align:right;">'.number_format($rataqtyop*100/$rataqtybtb,2).' %'.'</th>';
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>

                <table hidden id="tableexport" class="table color-table inverse-table table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th style="text-align:center;font-size:14px;" colspan=<?=3+($interval*4)+4+4;?>>REKAP PEMBELIAN</th>
                        </tr>
                        <tr>
                            <th style="text-align:center;font-size:14px;" colspan=<?=3+($interval*4)+4+4;?>>PERIODE : <?=$dfrom;?> s/d <?=$dto;?></th>
                        </tr>
                        <tr>
                        <?php if($ikategori == 'ALL'){?>
                            <th style="text-align:left;" colspan=<?=3+($interval*4)+4+4;?>>Kategori Barang : Semua Kategori Barang</th>
                        <?}else{?>
                            <th style="text-align:left;" colspan=<?=3+($interval*4)+4+4;?>>Kategori Barang : <?=$ikategori;?> - <?=$namekategori->e_nama;?></th>
                        <?}?>
                        </tr>
                        <tr>
                            <?php if($ijenis == 'ALL'){?>
                                <th style="text-align:left;" colspan=<?=3+($interval*4)+4+4;?>>Jenis Barang : Semua Jenis Barang</th>
                            <?}else{?>
                                <th style="text-align:left;" colspan=<?=3+($interval*4)+4+4;?>>Jenis Barang : <?=$ijenis;?> - <?=$namejenis->e_type_name;?></th>
                            <?}?>
                            
                        </tr>
                        <tr>
                            <th style="text-align:right;" colspan=<?=3+($interval*4)+4+4;?>></th>
                        </tr>
                        <tr>
                            <th style="text-align:middle;" rowspan=2>No</th>
                            <th style="text-align:center;" rowspan=2>Supplier</th>
	                        <th style="text-align:middle;" rowspan=2>Kode Barang</th>
		                    <th style="text-align:middle;width:300%;" rowspan=2>Nama Barang</th>
                            <?php                         
                                if($dfrom!=''){
	                            	$tmp=explode("-",$dfrom);
	                            	$blasal=$tmp[1];
                                    settype($bl,'integer');
                                }
                                $interval = $interfall->inter;
                                $bl=$blasal;
                                for($i=0;$i<=$interval;$i++){
                                    switch ($bl){
                                        case '01' :
                                          echo '<th style="text-align:center;" colspan=6>Januari</th>';
                                          break;
                                        case '02' :
                                          echo '<th style="text-align:center;" colspan=6>Februari</th>';
                                          break;
                                        case '03' :
                                          echo '<th style="text-align:center;" colspan=6>Maret</th>';
                                          break;
                                        case '04' :
                                          echo '<th style="text-align:center;" colspan=6>April</th>';
                                          break;
                                        case '05' :
                                          echo '<th style="text-align:center;" colspan=6>Mei</th>';
                                          break;
                                        case '06' :
                                          echo '<th style="text-align:center;" colspan=6>Juni</th>';
                                          break;
                                        case '07' :
                                          echo '<th style="text-align:center;" colspan=6>Juli</th>';
                                          break;
                                        case '08' :
                                          echo '<th style="text-align:center;" colspan=6>Agustus</th>';
                                          break;
                                        case '09' :
                                          echo '<th style="text-align:center;" colspan=6>September</th>';
                                          break;
                                        case '10' :
                                          echo '<th style="text-align:center;" colspan=6>Oktober</th>';
                                          break;
                                        case '11' :
                                          echo '<th style="text-align:center;" colspan=6>November</th>';
                                          break;
                                        case '12' :
                                          echo '<th style="text-align:center;" colspan=6>Desember</th>';
                                          break;
                                    }
                                    $bl++;
                                    if($bl==13)$bl=1;
                                }
                            ?>
                            <th style="text-align:middle;" rowspan=2>Total QTY OP</th>
                            <th style="text-align:middle;" rowspan=2>Total QTY BTB</th>
                            <th style="text-align:middle;" rowspan=2>Total Nilai OP</th>
                            <th style="text-align:middle;" rowspan=2>Total Nilai BTB</th>
                            <th style="text-align:center;" rowspan=2>% Pemenuhan</th>
                        </tr>
                        <tr>
                            <?php
                            for($i=0;$i<=$interval;$i++){
                                echo '<th style="text-align:middle;background-color:#f4bb08;">QTY OP</th>';
                                echo '<th style="text-align:middle;background-color:#f4bb08;">Qty BTB</th>';
                                echo '<th style="text-align:middle;background-color:#f4bb08;">Nilai OP</th>';
                                echo '<th style="text-align:middle;background-color:#f4bb08;">Nilai BTB</th>';
                                echo '<th style="text-align:middle;background-color:#f4bb08;">Sisa Qty OP</th>';
                                echo '<th style="text-align:middle;background-color:#f4bb08;">Sisa Nilai OP</th>';
                            }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
		                foreach($data as $row){
                            $jan = explode('|',$row->jan);
                            $qtybtb_jan     = (float)$jan[0];
                            $qtyop_jan      = (float)$jan[1];
                            $nilaibtb_jan   = (float)$jan[2];
                            $nilaiop_jan    = (float)$jan[3];

                            $feb = explode('|',$row->feb);
                            $qtybtb_feb     = (float)$feb[0];
                            $qtyop_feb      = (float)$feb[1];
                            $nilaibtb_feb   = (float)$feb[2];
                            $nilaiop_feb    = (float)$feb[3];

                            $mar = explode('|',$row->mar);
                            $qtybtb_mar     = (float)$mar[0];
                            $qtyop_mar      = (float)$mar[1];
                            $nilaibtb_mar   = (float)$mar[2];
                            $nilaiop_mar    = (float)$mar[3];

                            $apr = explode('|',$row->apr);
                            $qtybtb_apr     = (float)$apr[0];
                            $qtyop_apr      = (float)$apr[1];
                            $nilaibtb_apr   = (float)$apr[2];
                            $nilaiop_apr    = (float)$apr[3];

                            $may = explode('|',$row->may);
                            $qtybtb_may     = (float)$may[0];
                            $qtyop_may      = (float)$may[1];
                            $nilaibtb_may   = (float)$may[2];
                            $nilaiop_may    = (float)$may[3];

                            $jun = explode('|',$row->jun);
                            $qtybtb_jun     = (float)$jun[0];
                            $qtyop_jun      = (float)$jun[1];
                            $nilaibtb_jun   = (float)$jun[2];
                            $nilaiop_jun    = (float)$jun[3];

                            $jul = explode('|',$row->jul);
                            $qtybtb_jul     = (float)$jul[0];
                            $qtyop_jul      = (float)$jul[1];
                            $nilaibtb_jul   = (float)$jul[2];
                            $nilaiop_jul    = (float)$jul[3];

                            $aug = explode('|',$row->aug);
                            $qtybtb_aug     = (float)$aug[0];
                            $qtyop_aug      = (float)$aug[1];
                            $nilaibtb_aug   = (float)$aug[2];
                            $nilaiop_aug    = (float)$aug[3];

                            $sep = explode('|',$row->sep);
                            $qtybtb_sep     = (float)$sep[0];
                            $qtyop_sep      = (float)$sep[1];
                            $nilaibtb_sep   = (float)$sep[2];
                            $nilaiop_sep    = (float)$sep[3];

                            $oct = explode('|',$row->oct);
                            $qtybtb_oct     = (float)$oct[0];
                            $qtyop_oct      = (float)$oct[1];
                            $nilaibtb_oct   = (float)$oct[2];
                            $nilaiop_oct    = (float)$oct[3];

                            $nov = explode('|',$row->nov);
                            $qtybtb_nov     = (float)$nov[0];
                            $qtyop_nov      = (float)$nov[1];
                            $nilaibtb_nov   = (float)$nov[2];
                            $nilaiop_nov    = (float)$nov[3];

                            $des = explode('|',$row->des);
                            $qtybtb_des     = (float)$des[0];
                            $qtyop_des      = (float)$des[1];
                            $nilaibtb_des   = (float)$des[2];
                            $nilaiop_des    = (float)$des[3];
                           
                            $rataqtybtb     = (float) 0;
                            $rataqtyop      = (float) 0;
                            $ratanilaibtb   = (float) 0;
                            $ratanilaiop    = (float) 0;

                              echo "<tr>
                                    <td>$row->nomor</td>
                                    <td>$row->isupplier".' - '."$row->esupplier</td>
                                    <td>$row->imaterial</td>
                                    <td style='width:300px;'>$row->ematerial</td>";
                            $bl=$blasal;
                            $interval = $interfall->inter;
                            for($i=0;$i<=$interval;$i++){
                                switch ($bl){
                                    case '01' :
                                        $rataqtyop      =$rataqtyop+$qtyop_jan;
                                        $rataqtybtb     =$rataqtybtb+$qtybtb_jan;
                                        $ratanilaiop    =$ratanilaiop+$nilaiop_jan;
                                        $ratanilaibtb   =$ratanilaibtb+$nilaibtb_jan;

                                        echo '<td align=right>'.number_format($qtybtb_jan).'</td>';
                                        echo '<td align=right>'.number_format($qtyop_jan).'</td>';
                                        echo '<td align=right>'.number_format($nilaibtb_jan).'</td>';
                                        echo '<td align=right>'.number_format($nilaiop_jan).'</td>';
                                        echo '<td align=right>'.number_format($qtybtb_jan-$qtyop_jan).'</td>';
                                        echo '<td align=right>'.number_format($nilaibtb_jan-$nilaiop_jan).'</td>';
                                      break;
                                    case '02' :
                                        $rataqtyop      =$rataqtyop+$qtyop_feb;
                                        $rataqtybtb     =$rataqtybtb+$qtybtb_feb;
                                        $ratanilaiop    =$ratanilaiop+$nilaiop_feb;
                                        $ratanilaibtb   =$ratanilaibtb+$nilaibtb_feb;

                                        echo '<td align=right>'.number_format($qtybtb_feb).'</td>';
                                        echo '<td align=right>'.number_format($qtyop_feb).'</td>';
                                        echo '<td align=right>'.number_format($nilaibtb_feb).'</td>';
                                        echo '<td align=right>'.number_format($nilaiop_feb).'</td>';
                                        echo '<td align=right>'.number_format($qtybtb_feb-$qtyop_feb).'</td>';
                                        echo '<td align=right>'.number_format($nilaibtb_feb-$nilaiop_feb).'</td>';
                                    break;
                                    case '03' :
                                        $rataqtyop      =$rataqtyop+$qtyop_mar;
                                        $rataqtybtb     =$rataqtybtb+$qtybtb_mar;
                                        $ratanilaiop    =$ratanilaiop+$nilaiop_mar;
                                        $ratanilaibtb   =$ratanilaibtb+$nilaibtb_mar;
                                        
                                        echo '<td align=right>'.number_format($qtybtb_mar).'</td>';
                                        echo '<td align=right>'.number_format($qtyop_mar).'</td>';
                                        echo '<td align=right>'.number_format($nilaibtb_mar).'</td>';
                                        echo '<td align=right>'.number_format($nilaiop_mar).'</td>';
                                        echo '<td align=right>'.number_format($qtybtb_mar-$qtyop_mar).'</td>';
                                        echo '<td align=right>'.number_format($nilaibtb_mar-$nilaiop_mar).'</td>';
                                    break;
                                    case '04' :
                                        $rataqtyop      =$rataqtyop+$qtyop_apr;
                                        $rataqtybtb     =$rataqtybtb+$qtybtb_apr;
                                        $ratanilaiop    =$ratanilaiop+$nilaiop_apr;
                                        $ratanilaibtb   =$ratanilaibtb+$nilaibtb_apr;
                                        
                                        echo '<td align=right>'.number_format($qtybtb_apr).'</td>';
                                        echo '<td align=right>'.number_format($qtyop_apr).'</td>';
                                        echo '<td align=right>'.number_format($nilaibtb_apr).'</td>';
                                        echo '<td align=right>'.number_format($nilaiop_apr).'</td>';
                                        echo '<td align=right>'.number_format($qtybtb_apr-$qtyop_apr).'</td>';
                                        echo '<td align=right>'.number_format($nilaibtb_apr-$nilaiop_apr).'</td>';
                                    break;
                                    case '05' :
                                        $rataqtyop      =$rataqtyop+$qtyop_may;
                                        $rataqtybtb     =$rataqtybtb+$qtybtb_may;
                                        $ratanilaiop    =$ratanilaiop+$nilaiop_may;
                                        $ratanilaibtb   =$ratanilaibtb+$nilaibtb_may;
                                        
                                        echo '<td align=right>'.number_format($qtybtb_may).'</td>';
                                        echo '<td align=right>'.number_format($qtyop_may).'</td>';
                                        echo '<td align=right>'.number_format($nilaibtb_may).'</td>';
                                        echo '<td align=right>'.number_format($nilaiop_may).'</td>';
                                        echo '<td align=right>'.number_format($qtybtb_may-$qtyop_may).'</td>';
                                        echo '<td align=right>'.number_format($nilaibtb_may-$nilaiop_may).'</td>';
                                    break;
                                    case '06' :
                                        $rataqtyop      =$rataqtyop+$qtyop_jun;
                                        $rataqtybtb     =$rataqtybtb+$qtybtb_jun;
                                        $ratanilaiop    =$ratanilaiop+$nilaiop_jun;
                                        $ratanilaibtb   =$ratanilaibtb+$nilaibtb_jun;
                                        
                                        echo '<td align=right>'.number_format($qtybtb_jun).'</td>';
                                        echo '<td align=right>'.number_format($qtyop_jun).'</td>';
                                        echo '<td align=right>'.number_format($nilaibtb_jun).'</td>';
                                        echo '<td align=right>'.number_format($nilaiop_jun).'</td>';
                                        echo '<td align=right>'.number_format($qtybtb_jun-$qtyop_jun).'</td>';
                                        echo '<td align=right>'.number_format($nilaibtb_jun-$nilaiop_jun).'</td>';
                                    break;
                                    case '07' :
                                        $rataqtyop      =$rataqtyop+$qtyop_jul;
                                        $rataqtybtb     =$rataqtybtb+$qtybtb_jul;
                                        $ratanilaiop    =$ratanilaiop+$nilaiop_jul;
                                        $ratanilaibtb   =$ratanilaibtb+$nilaibtb_jul;
                                        
                                        echo '<td align=right>'.number_format($qtybtb_jul).'</td>';
                                        echo '<td align=right>'.number_format($qtyop_jul).'</td>';
                                        echo '<td align=right>'.number_format($nilaibtb_jul).'</td>';
                                        echo '<td align=right>'.number_format($nilaiop_jul).'</td>';
                                        echo '<td align=right>'.number_format($qtybtb_jul-$qtyop_jul).'</td>';
                                        echo '<td align=right>'.number_format($nilaibtb_jul-$nilaiop_jul).'</td>';
                                    break;
                                    case '08' :
                                        $rataqtyop      =$rataqtyop+$qtyop_aug;
                                        $rataqtybtb     =$rataqtybtb+$qtybtb_aug;
                                        $ratanilaiop    =$ratanilaiop+$nilaiop_aug;
                                        $ratanilaibtb   =$ratanilaibtb+$nilaibtb_aug;
                                        
                                        echo '<td align=right>'.number_format($qtybtb_aug).'</td>';
                                        echo '<td align=right>'.number_format($qtyop_aug).'</td>';
                                        echo '<td align=right>'.number_format($nilaibtb_aug).'</td>';
                                        echo '<td align=right>'.number_format($nilaiop_aug).'</td>';
                                        echo '<td align=right>'.number_format($qtybtb_aug-$qtyop_aug).'</td>';
                                        echo '<td align=right>'.number_format($nilaibtb_aug-$nilaiop_aug).'</td>';
                                    break;
                                    case '09' :
                                        $rataqtyop      =$rataqtyop+$qtyop_sep;
                                        $rataqtybtb     =$rataqtybtb+$qtybtb_sep;
                                        $ratanilaiop    =$ratanilaiop+$nilaiop_sep;
                                        $ratanilaibtb   =$ratanilaibtb+$nilaibtb_sep;
                                        
                                        echo '<td align=right>'.number_format($qtybtb_sep).'</td>';
                                        echo '<td align=right>'.number_format($qtyop_sep).'</td>';
                                        echo '<td align=right>'.number_format($nilaibtb_sep).'</td>';
                                        echo '<td align=right>'.number_format($nilaiop_sep).'</td>';
                                        echo '<td align=right>'.number_format($qtybtb_sep-$qtyop_sep).'</td>';
                                        echo '<td align=right>'.number_format($nilaibtb_sep-$nilaiop_sep).'</td>';
                                    break;
                                    case '10' :
                                        $rataqtyop      =$rataqtyop+$qtyop_oct;
                                        $rataqtybtb     =$rataqtybtb+$qtybtb_oct;
                                        $ratanilaiop    =$ratanilaiop+$nilaiop_oct;
                                        $ratanilaibtb   =$ratanilaibtb+$nilaibtb_oct;
                                        
                                        echo '<td align=right>'.number_format($qtybtb_oct).'</td>';
                                        echo '<td align=right>'.number_format($qtyop_oct).'</td>';
                                        echo '<td align=right>'.number_format($nilaibtb_oct).'</td>';
                                        echo '<td align=right>'.number_format($nilaiop_oct).'</td>';
                                        echo '<td align=right>'.number_format($qtybtb_oct-$qtyop_oct).'</td>';
                                        echo '<td align=right>'.number_format($nilaibtb_oct-$nilaiop_oct).'</td>';
                                    break;
                                    case '11' :
                                        $rataqtyop      =$rataqtyop+$qtyop_nov;
                                        $rataqtybtb     =$rataqtybtb+$qtybtb_nov;
                                        $ratanilaiop    =$ratanilaiop+$nilaiop_nov;
                                        $ratanilaibtb   =$ratanilaibtb+$nilaibtb_nov;
                                        
                                        echo '<td align=right>'.number_format($qtybtb_nov).'</td>';
                                        echo '<td align=right>'.number_format($qtyop_nov).'</td>';
                                        echo '<td align=right>'.number_format($nilaibtb_nov).'</td>';
                                        echo '<td align=right>'.number_format($nilaiop_nov).'</td>';
                                        echo '<td align=right>'.number_format($qtybtb_nov-$qtyop_nov).'</td>';
                                        echo '<td align=right>'.number_format($nilaibtb_nov-$nilaiop_nov).'</td>';
                                    break;
                                    case '12' :
                                        $rataqtyop      =$rataqtyop+$qtyop_des;
                                        $rataqtybtb     =$rataqtybtb+$qtybtb_des;
                                        $ratanilaiop    =$ratanilaiop+$nilaiop_des;
                                        $ratanilaibtb   =$ratanilaibtb+$nilaibtb_des;
                                        
                                        echo '<td align=right>'.number_format($qtybtb_des).'</td>';
                                        echo '<td align=right>'.number_format($qtyop_des).'</td>';  
                                        echo '<td align=right>'.number_format($nilaibtb_des).'</td>';                                    
                                        echo '<td align=right>'.number_format($nilaiop_des).'</td>';
                                        echo '<td align=right>'.number_format($qtybtb_des-$qtyop_des).'</td>';
                                        echo '<td align=right>'.number_format($nilaibtb_des-$nilaiop_des).'</td>';
                                    break;
                                }
                              $bl++;
                              if($bl==13)$bl=1;
                            }
                            echo '<th style="text-align:right;background-color:#f4bb08;">'.number_format($rataqtybtb).'</th>';
                            echo '<th style="text-align:right;background-color:#f4bb08;">'.number_format($rataqtyop).'</th>';
                            echo '<th style="text-align:right;background-color:#f4bb08;">'.number_format($ratanilaibtb).'</th>';
                            echo '<th style="text-align:right;background-color:#f4bb08;">'.number_format($ratanilaiop).'</th>';
                            echo '<th style="text-align:right;background-color:#f4bb08;">'.number_format($rataqtyop*100/$rataqtybtb,2).' %'.'</th>';
                            echo "</tr>";
                            }

                        ?>
                    </tbody>
                </table>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('#tabledata').DataTable();
        $('.select2').select2();
        showCalendar2('.date');
    });

    function getjenisbarang(id){
        $.ajax({
            type: "POST",
            url: "<?php echo site_url($folder.'/Cform/getjenisbarang');?>",
            data: "ikategori=" + id,
            dataType: 'json',
            success: function (data) {
                $("#jenis").html(data.kop);
                if (data.kosong == 'kopong') {
                    $("#jenis").attr("disabled", false);
                } else {
                    $("#jenis").attr("disabled", false);
                }
            },

            error: function (XMLHttpRequest) {
                alert(XMLHttpRequest.responseText);
            }
        });
    } 

    function exportF(elem) {
        var dfrom       = $('#dfrom').val();
        var dto         = $('#dto').val();
        var ikategori   = $('#ekategori').val();
        var ijenis      = $('#ejenis').val();
        var table       = document.getElementById("tableexport");
        var html        = table.outerHTML;
        var url         = 'data:application/vnd.ms-Excel,' + escape(html); // Set your html table into url 
        elem.setAttribute("href", url);
        elem.setAttribute("download", dfrom+"-sd-"+dto+"-"+ikategori+"-"+ijenis+".xls"); // Choose the file name
        return false;
    }
</script>