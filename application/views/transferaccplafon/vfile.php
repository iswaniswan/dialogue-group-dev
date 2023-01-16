<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-exchange"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/transfer'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <input type="text" value="<?= $filename;?>" readonly id= "namafile" name="namafile" class="form-control">
                        </div>
                    </div> 
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-exchange"></i>&nbsp;&nbsp;Transfer
                            </button>&nbsp;&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i
                                class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali
                            </button>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="jml" id="jml" value="<?= $jml;?>">
                <div class="col-md-12">
                    <table id="tabledata" class="display table" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th style="width: 5%;">No</th>
                                <th align="center">Area</th>
			                    <th align="center">Kode Cust Group Bayar</th>
			                    <th align="center">Kategori</th>
			                    <th align="center">Index</th>
			                    <th align="center">Rata Telat</th>
			                    <th align="center">Total Penjualan</th>
			                    <th align="center">Max Penjualan</th>
			                    <th align="center">Rata Penjualan</th>
			                    <th align="center">Plafond</th>
			                    <th align="center">Plafond Sebelumnya</th>
			                    <th align="center">Plafond Acc</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no=0;
                            if ($items) {
                                foreach($items as $row){
                                    if($row['e_area_name'] <> null){
                                        $no++;
                                    }?>
                                    <tr>
                                        <td><?= $no;?></td>
                                        <td><?= $row['e_area_name'];?></td>
                                        <td><?= $row['i_customer_groupbayar'];?></td>
                                        <td><?= $row['e_kategori'];?></td>
                                        <td><?= $row['i_index'];?></td>
                                        <td><?= $row['n_rata_telat'];?></td>
                                        <td><?= $row['v_total_penjualan'];?></td>
                                        <td><?= $row['v_max_penjualan'];?></td>
                                        <td><?= $row['v_rata_penjualan'];?></td>
                                        <td><?= $row['v_plafond'];?></td>
                                        <td><?= $row['v_plafond_before'];?></td>
                                        <td><?= $row['v_plafond_acc'];?></td>
                                    </tr>
                                <?php } 
                            } ?>
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
<script>
    $(document).ready(function () {
        showCalendar('.date');
    });

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
    });
</script>