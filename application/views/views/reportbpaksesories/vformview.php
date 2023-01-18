<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-list"></i> &nbsp; <?= $title; ?> 
            </div>
            <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
            <div class="panel-body table-responsive">
            <div class="col-md-6">
                <div id="pesan"></div>
                <p><b> Periode : <?php echo $dfrom." s.d. ".$dto; ?></b></p>
                <p><b> Gudang Accesories</b></p>
            </div>
            <div class="panel-body table-responsive">
                <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%">
                    <thead>                        
                        <tr>
                            <!-- <th style="vertical-align:middle;text-align:center;" rowspan='2'></th> -->
                            <th>No</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th> 
                            <th>Saldo Awal</th>
                            <th>Masuk</th>
                            <th>**Masuk Lain</th>
                            <th>Keluar</th>
                            <th>**Keluar Lain</th>  
                            <th>**Retur</th>
                            <th>Adjusment</th>
                            <th>Saldo Akhir</th>
                            <th>SO</th> 
                            <th>Selisih</th>         
                        </tr>
                    </thead>
                    <tbody>  
                    <?php
                    $i=1;   
                    if($data){
                        foreach($data as $row){
                            echo "<td>$i</td>";
                            echo "<td>$row->i_material</td>";
                            echo "<td>$row->e_material_name</td>";
                            echo "<td align=right>$row->sawal</td>";
                            echo "<td align=right>$row->masuk</td>";
                            echo "<td align=right>$row->masuklain</td>";
                            echo "<td align=right>$row->keluar</td>";
                            echo "<td align=right>$row->keluarlain</td>";
                            echo "<td align=right></td>";
                            echo "<td align=right>$row->adjusment</td>";                           
                            echo "<td align=right>$row->salhir</td>";
                            echo "<td align=right>$row->so</td>";
                            echo "<td align=right>$row->selisih</td>";         
                            echo "</tr>";                           
                        }                 
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