<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?>
                <?php  if(check_role($this->i_menu, 1)){ ?><a href="#" onclick="show('<?= $folder; ?>/cform/tambah/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i
                        class="fa fa-plus"></i> &nbsp;<?= $title; ?></a>
                <?php } ?>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/view2/'.$dfrom), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="row">
                        <label class="col-md-12">Tanggal Berlaku</label>
                        <div class="col-sm-3">
                            <input type="text" id="dberlaku" name="dberlaku" class="form-control input-sm date"  readonly value="<?=$dfrom;?>" placeholder="Tanggal Berlaku">
                        </div>
                        <div class="col-sm-3">
                            <button type="submit" id="submit" class="btn btn-info btn-sm"> <i class="fa fa-search mr-2"></i>View</button>
                        </div>
                    </div>
                </div>
                </form>
            </div>
            <div class="panel-body table-responsive">
                <table id="tabledata" class="display nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th width="3%">No</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Warna Barang</th>
                            <th>Grade Barang</th>
                            <th>Harga</th>
                            <th>Tgl Berlaku</th>
                            <th>Tgl Berakhir</th>
                            <!-- <th>Status</th> -->
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        datatablemod('#tabledata', base_url + '<?= $folder; ?>/Cform/data/<?=$dfrom;?>/');

        $(".select2").select2();
        showCalendar2('.date');
    });
</script>