<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?>
                <?php  if(check_role($this->i_menu, 1)){ ?><a href="#" onclick="show('<?= $folder; ?>/cform/tambah/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i
                        class="fa fa-plus"></i> &nbsp;<?= $title; ?></a>
                <?php } ?>
            </div>
            
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-3">Date From</label>
                            <label class="col-md-9">Date To</label>
                            <div class="col-sm-3">
                                <input class="form-control date" readonly="" type="text" name="dfrom" id="dfrom" value="<?= date("d-m-Y", strtotime($dfrom));?>">
                            </div>
                            <div class="col-sm-3">
                                <input class="form-control date" readonly="" type="text" name="dto" id="dto" value="<?= date("d-m-Y", strtotime($dto));?>">
                            </div>
                            <div class="col-sm-4">
                                <button type="submit" id="submit" class="btn btn-info"> <i class="fa fa-search"></i>&nbsp;&nbsp;Cari</button>
                            </div>
                        </div>
                    </div>
                </form>
                <table id="tabledata" class="display nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nomor Dokumen</th>
                            <th>Tanggal Dokumen</th>
                            <th>Kas/Bank</th>
                            <th>Supplier</th>
                            <th>Nomor Referensi</th>
                            <th>Jumlah Bayar</th>
                            <th>Nomor Voucher</th>
                            <th>Status Dokumen</th>
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

<script type="text/javascript">
    showCalendar2('.date');
    $(document).ready(function () {
        datatablemod('#tabledata', base_url + '<?= $folder; ?>/Cform/data/<?=$dfrom;?>/<?=$dto;?>');
    });
</script>
