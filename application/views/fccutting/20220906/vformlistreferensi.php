<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?>
                <?php if (check_role($this->i_menu, 2)) { ?>
                    <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right">
                        <i class="fa fa-list"></i> &nbsp;list <?= $title; ?>
                    </a>
                <?php } ?>
            </div>
            <div class="panel-body table-responsive">
                <?= $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/indexx'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div class="col-md-4">
                    <div class="form-group row">
                        <label class="col-md-5">Date From</label><label class="col-md-5">Date To</label>
                        <div class="col-sm-5">
                            <input class="form-control input-sm date" readonly="" type="text" name="dfrom" id="dfrom" value="<?= $dfrom; ?>">
                        </div>
                        <div class="col-sm-5">
                            <input class="form-control input-sm date" readonly="" type="text" name="dto" id="dto" value="<?= $dto; ?>">
                        </div>
                        <div class="col-sm-2">
                            <button type="submit" class="btn btn-info btn-sm"><i class="fa fa-search"></i>&nbsp;&nbsp;Cari</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group pull-right row">
                        <!-- <label class="col-md-12">&nbsp;</label> -->
                        <div class="col-sm-12 mt-5 pull-right">
                            <button type="button" class="btn btn-rounded btn-primary btn-sm" onclick="show('<?= $folder; ?>/cform/indexx/<?= $dfrom . "/" . $dto; ?>','#main'); return false;"> <i class="fa fa-spin fa fa-refresh fa-lg mr-2"></i>Reload Page</button>
                        </div>
                    </div>
                </div>
                </form>
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/prosesdata'), 'update' => '#main', 'type' => 'post', 'id' => 'formclose', 'class' => 'form-horizontal')); ?>
                <table id="tabledata" class="display nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th width="3%;">No</th>
                            <th>No. Dokumen</th>
                            <th>Tgl. Dokumen</th>
                            <th>Periode Schedule</th>
                            <th>Bagian</th>
                            <th>Keterangan</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <div class="form-group row">
                    <div class="col-sm-offset-5 col-sm-12" style="text-align: center;">
                        <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Proses</button>
                        &nbsp;&nbsp;
                        <label class="custom-control custom-checkbox">
                            <input type="checkbox" id="checkAll" name="checkAll" class="custom-control-input">
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">Check All</span>
                        </label>
                    </div>
                </div>
                <input type="hidden" name="d_from" value="<?= $dfrom; ?>">
                <input type="hidden" name="d_to" value="<?= $dto; ?>">
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        showCalendar2('.date');
        datatablemod('#tabledata', base_url + '<?= $folder; ?>/Cform/datareferensi/<?= $dfrom . '/' . $dto; ?>');
        
        $("#submit").click(function(event) {
            if ($("#formclose input:checkbox:checked").length > 0) {
                return true;
            } else {
                swal('Maaf :(', 'Pilih data minimal satu!', 'error');
                return false;
            }
        });

        $("#checkAll").click(function() {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });

        $("#dfrom").change(function() {
            var dfrom = splitdate($(this).val());
            var dto = splitdate($('#dto').val());
            if (dfrom != null && dto != null) {
                if (dfrom > dto) {
                    swal('Tanggal Mulai Tidak Boleh Lebih Besar Dari Tanggal Sampai!!!');
                    $('#dfrom').val('');
                }
            }
        });

        $("#dto").change(function() {
            var dto = splitdate($(this).val());
            var dfrom = splitdate($('#dfrom').val());
            if (dfrom != null && dto != null) {
                if (dfrom > dto) {
                    swal('Tanggal Sampai Tidak Boleh Lebih Kecil Dari Tanggal Mulai!!!');
                    $('#dto').val('');
                }
            }
        });
    });
</script>