<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> 
            </div>
            <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
            <div class="panel-body table-responsive">
            <div class="col-md-6">
                <div id="pesan"></div>
                <p><b> Periode : <?php echo $dfrom." s.d. ".$dto; ?></b></p>
                
            </div>
            <div class="panel-body table-responsive">
                <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%">
                    <thead>                        
                        <tr>
                            <th style="vertical-align:middle;text-align:center;" rowspan='2'>No</th>
                            <th style="vertical-align:middle;text-align:center;" rowspan='2'>Kode Barang</th>
                            <th style="vertical-align:middle;text-align:center;" rowspan='2' align="center">Nama Barang</th>
                            <th style="vertical-align:middle;text-align:center;" rowspan='2' align="center">Warna</th>
                            <th style="vertical-align:middle;text-align:center;" rowspan='2' align="center">Saldo Awal</th>
                            <center><th colspan='3' style="text-align:center;">Masuk</th></center>
                            <center><th colspan='3' style="text-align:center;">Keluar</th></center>
                            <th style="vertical-align:middle;text-align:center;" rowspan='2' align="center">*GIT (Good In Transit)</th>
                            <th style=";vertical-align:middle;text-align:center;" rowspan='2' align="center">Saldo Akhir</th>
                            <th style="vertical-align:middle;text-align:center;" rowspan='2' align="center">SO</th>
                            <th style="vertical-align:middle;text-align:center;" rowspan='2' align="center">Selisih</th>              
                        </tr>
                        <tr class="success">
                            <th align="center">Packing</th>
                            <th align="center">**Retur Penjualan</th>
                            <th align="center">Masuk Lain-lain</th>
                            <th align="center">Penjualan</th>
                            <th align="center">Retur Packing</th>
                            <th align="center">Keluar Lain-lain</th>
                        </tr>
                    </thead>
                    <tbody>  
                    <?php
                    $i=1;   
                    if($data){
                        $group='';
                        $bis='';
                        $totsaldoawl=0;

                        foreach($data as $row){
                            $product = $row->i_product;
                            if($bis==''){
                               $gtotsaldoawal=0;
                               $gtotsaldoawal=$gtotsaldoawal+$row->sawal;
                            }else{
                                if($bis!=$row->i_product){
                                  echo "<td colspan=4 align=center><b>TOTAL Per Item</td>
                                        <td align=right><b>$gtotsaldoawal</b></td>";

                                      $gtotsaldoawal=0;
                                }
                                $gtotsaldoawal=$gtotsaldoawal+$row->sawal;
                            }
                            if($group==''){
                                echo "<tr><td bgcolor=\"#E0FFFF\" colspan=18 style=\"font-size:13px;\"><b>".strtoupper($i." -- ".$row->i_product." (".$row->e_product_namewip.")")."</b></td></tr>";
                            }else{
                                if($group!=$product){
                                    $i++;
                                    echo "<tr><td bgcolor=\"#E0FFFF\" colspan=18 style=\"font-size:13px;\"><b>".strtoupper($i." -- ".$row->i_product." (".$row->e_product_namewip.")")."</b></td></tr>";
                                        $totsaldoawl=0;          
                                }
                            }
                            $group=$product;
                            $bis=$row->i_product;
                            echo "<td></td>";
                            echo "<td></td>";
                            echo "<td></td>";  
                            echo "<td>$row->e_color_name</td>";
                            echo "<td align=right>$row->sawal</td>";
                            echo "<td align=right>$row->masukpacking</td>";
                            echo "<td align=right>$row->returpenjualan</td>";
                            echo "<td align=right>$row->masuklain</td>";
                            echo "<td align=right>$row->sjkeluar</td>";
                            echo "<td align=right>$row->returpacking</td>";
                            echo "<td align=right>$row->keluarlain</td>";
                            echo "<td align=right>$row->git</td>";                            
                            echo "<td align=right>$row->salhir</td>";
                            echo "<td align=right>$row->so</td>";
                            echo "<td align=right>$row->selisih</td>";         
                            echo "</tr>";
                            $totsaldoawl=$totsaldoawl+$row->sawal;
                        }
                        echo "<td colspan=4 align=center><b>TOTAL Per Item</td>
                          <td align=right><b>$gtotsaldoawal</b></td>";
                        echo "<tr>";
                        echo "<td colspan=4 align=center><b>TOTAL Seluruh</td>
                          <td align=right><b>$totsaldoawl</b></td>";
                        echo "</tr>";
                    }else{                     
                    }
                    ?>
                    </tbody>
                </table>
            </div>       
        </div>
    </div>
    </form>
    </div>
</div>
<script>
$("form").submit(function (event) {
    event.preventDefault();
});
    
$(document).ready(function () {
    $(".select2").select2();
});

$(document).ready(function () {
    $('.select2').select2();
    showCalendar('.date');
});
</script>