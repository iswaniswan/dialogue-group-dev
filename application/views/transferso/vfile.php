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
                        <label class="col-md-6">Tanggal Stockopname</label><label class="col-md-6">Nama File</label>
                        <div class="col-sm-6">
                            <input type="text" required="" readonly id= "tgl" name="tgl" class="form-control date" value="<?= $tgl;?>">
                        </div>
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
                                <th>Kode</th>
                                <th>Nama Barang</th>
                                <th>Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no=0;
                            if ($items) {
                                foreach($items as $row){
                                    if($row['KODEPROD'] <> null){
                                        $no++;
                                    }?>
                                    <tr>
                                        <td><?= $no;?></td>
                                        <td><?= $row['KODEPROD'];?></td>
                                        <td><?= $row['NAMAPROD'];?></td>
                                        <td><?= $row['STOCKOPNAM'];?></td>
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