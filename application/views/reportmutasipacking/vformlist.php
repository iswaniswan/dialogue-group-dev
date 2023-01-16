<link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/4.1.0/css/fixedColumns.dataTables.min.css" />
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?>
                <?php if (check_role($this->i_menu, 1)) { ?><a href="#" onclick="show('<?= $folder; ?>/cform/daftarop/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> &nbsp;<?= $list_title; ?></a>
                <?php } ?>
            </div>
            <div class="panel-body">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
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
                            <button type="submit" id="submit" class="btn btn-info btn-sm"> <i class="fa fa-search"></i>&nbsp;&nbsp;Cari</button>
                        </div>
                    </div>
                </div>
                </form>
                <div class="table-responsive">
                    <table id="tabledata" class="display nowrap" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th width="2%;">No</th>
                                <th>No. Dok</th>
                                <th>Tgl Dok</th>
                                <th>Bagian</th>
                                <th>No. Dok Supp</th>
                                <th>PP</th>
                                <!-- th>Tgl PP</th> -->
                                <th>No. OP</th>
                                <th>Tgl OP</th>
                                <th>Supplier</th>
                                <th>Keterangan</th>
                                <th>Status Dok</th>
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
</div>
<script src="https://cdn.datatables.net/fixedcolumns/4.1.0/js/dataTables.fixedColumns.min.js"></script>
<script>
    $(document).ready(function() {
        var table = $('#tabledata').DataTable({
            scrollY: "500px",
            scrollX: true,
            scrollCollapse: true,
            paging: false,
            fixedColumns: {
                left: 2
            }
        });
    });
</script>