<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list fa-lg mr-2"></i>
                <?= $title; ?>
                <?php if (check_role($this->i_menu, 1)) { ?><a href="#"
                        onclick="show('<?= $folder; ?>/cform/tambah/<?= $dfrom . "/" . $dto; ?>','#main'); return false;"
                        class="btn btn-info btn-sm pull-right"><i class="fa fa-plus"></i> &nbsp;
                        <?= $title; ?>
                    </a>
                <?php } ?>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-2">Date From</label>
                        <label class="col-md-2">Date To</label>
                        <label class="col-md-3">Area</label>
                        <label class="col-md-3">Jenis</label>
                        <label class="col-md-2"></label>
                        <div class="col-sm-2">
                            <input class="form-control input-sm date" readonly="" type="text" name="dfrom" id="dfrom"
                                value="<?= $dfrom; ?>">
                        </div>
                        <div class="col-sm-2">
                            <input class="form-control input-sm date" readonly="" type="text" name="dto" id="dto"
                                value="<?= $dto; ?>">
                        </div>
                        <div class="col-sm-3">
                            <select name="i_area" id="i_area" class="form-control select2"
                                onchange="number();clear_table();">
                                <option value="all" <?php if ($i_area == 'all') { ?> selected <?php } ?>>NASIONAL
                                </option>
                                <?php if ($area) {
                                    foreach ($area as $row): ?>
                                        <option value="<?= $row->id; ?>" <?php if ($i_area == $row->id) {?> selected <?php } ?>><?="[" . $row->i_area . "] - " . $row->e_area; ?>
                                        </option>
                                    <?php endforeach;
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="i_rv_type" id="i_rv_type" class="form-control select2"
                                onchange="number();clear_table();">
                                <option value="all" <?php if ($i_rv_type == 'all') { ?> selected <?php } ?>>Semua</option>
                                <?php if ($rvtype) {
                                    foreach ($rvtype as $row): ?>
                                        <option value="<?= $row->i_rv_type; ?>" <?php if ($i_rv_type == $row->i_rv_type) {?> selected <?php } ?>><?="[" . $row->i_rv_type_id . "] - " . $row->e_rv_type_name; ?>
                                        </option>
                                    <?php endforeach;
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <button type="submit" id="submit" class="btn btn-sm btn-block btn-info"> <i
                                    class="fa fa-search"></i>&nbsp;&nbsp;Cari</button>
                        </div>
                    </div>
                </div>
                </form>
                <table id="tabledata" class="display nowrap table-info" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th width="3%;" class="text-center">No</th>
                            <th>No. Dok</th>
                            <th>Tgl. Dok</th>
                            <th>CoA</th>
                            <th>Area</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th><i class="fa fa-print fa-lg"></i></th>
                            <th width="5%;">Aksi</th>
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
        $('.select2').select2();
        showCalendar2('.date', null, 0);
        datatableedit('#tabledata', base_url + '<?= $folder; ?>/Cform/data/<?= $dfrom . '/' . $dto . '/' . $i_area . '/' . $i_rv_type; ?>',0,5);
    });

    $(document).ready(function () {
        var table = $('#tabledata').DataTable();
        table.buttons('.dt-buttons').remove();
    });

    $("#dfrom").change(function () {
        var dfrom = splitdate($(this).val());
        var dto = splitdate($('#dto').val());
        if (dfrom != null && dto != null) {
            if (dfrom > dto) {
                swal('Tanggal Mulai Tidak Boleh Lebih Besar Dari Tanggal Sampai!!!');
                $('#dfrom').val('');
            }
        }
    });

    $("#dto").change(function () {
        var dto = splitdate($(this).val());
        var dfrom = splitdate($('#dfrom').val());
        if (dfrom != null && dto != null) {
            if (dfrom > dto) {
                swal('Tanggal Sampai Tidak Boleh Lebih Kecil Dari Tanggal Mulai!!!');
                $('#dto').val('');
            }
        }
    });

    function cetak(id) {
        var lebar = 1024;
        var tinggi = 768;
        eval('window.open("<?= site_url($folder); ?>"+"/cform/cetak/"+id,"","width="+lebar+"px,height="+tinggi+"px,resizable=1,menubar=1,scrollbars=1,top=' + (screen.height - tinggi) / 2 + ',left=' + (screen.width - lebar) / 2 + '")');
    }

    function refreshview() {
        show('<?= $folder; ?>/cform/index/<?= $dfrom . '/' . $dto; ?>', '#main');
    }
</script>