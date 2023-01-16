<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-lg fa-list mr-2"></i><?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list fa-lg mr-2"></i><?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/tambah_kirim'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
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
                            <button type="submit" id="submit" class="btn btn-info btn-sm"> <i class="fa fa-search fa-lg mr-2"></i>Cari</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group pull-right row">
                        <!-- <label class="col-md-12">&nbsp;</label> -->
                        <div class="col-sm-12 mt-5 pull-right">
                            <button type="button" class="btn btn-rounded btn-primary btn-sm" onclick="show('<?= $folder; ?>/cform/tambah_kirim/<?= $dfrom . "/" . $dto; ?>','#main'); return false;"> <i class="fa fa-spin fa fa-refresh fa-lg mr-2"></i>Reload Page</button>
                        </div>
                    </div>
                </div>
                </form>
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/proses_kirim'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <table id="tabledata" class="display nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th width="2%">No</th>
                            <th>WIP</th>
                            <th>Nama WIP</th>
                            <th>Warna</th>
                            <th>Material</th>
                            <th>Nama Material</th>
                            <th>Satuan</th>
                            <th class="text-right">FC Cutting</th>
                            <th>Tanggal Jahit</th>
                            <th width="3%">Act</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 0;
                        if ($detail->num_rows() > 0) {
                            foreach ($detail->result() as $key) {
                                $i++; ?>
                                <tr>
                                    <td class="text-center"><?= $i; ?></td>
                                    <td><?= $key->i_product_wip; ?></td>
                                    <td><?= $key->e_product_wipname; ?></td>
                                    <td><?= $key->e_color_name; ?></td>
                                    <td><?= $key->i_material; ?></td>
                                    <td><?= $key->e_material_name; ?></td>
                                    <td><?= $key->e_satuan_name; ?></td>
                                    <td class="text-right"><?= $key->qty; ?></td>
                                    <td><?= $key->tanggal_schedule; ?></td>
                                    <td class="text-center">
                                        <label class='custom-control custom-checkbox'>
                                            <input type='checkbox' id='chk$i' name='chk<?= $i; ?>' class='custom-control-input'>
                                            <span class='custom-control-indicator'></span>
                                            <span class='custom-control-description'></span>
                                            <input id='id_material<?= $i; ?>' name='id_material<?= $i; ?>' value='<?= $key->id_material;?>' type='hidden'>
                                            <input id='id_product_wip<?= $i; ?>' name='id_product_wip<?= $i; ?>' value='<?= $key->id_product_wip; ?>' type='hidden'>
                                            <input id='id<?= $i; ?>' name='id<?= $i; ?>' value='<?= $key->id_product_wip.'|'.$key->id_material; ?>' type='hidden'>
                                            <input id='i_periode<?= $i; ?>' name='i_periode<?= $i; ?>' value='<?= $key->i_periode; ?>' type='hidden'>
                                    </td>
                                </tr>
                        <?php }
                        } ?>
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
                <input id='jml' name='jml' value='<?= $i; ?>' type='hidden'>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        /*Tidak boleh lebih dari hari ini*/
        showCalendar2('.date');
        // datatablemod('#tabledata', base_url + '<?= $folder; ?>/Cform/data_schedule/<?= $dfrom . '/' . $dto; ?>');
        var $table = $('#tabledata');

        function buildTable(elm) {
            elm.bootstrapTable('destroy').bootstrapTable({
                height: 400,
                // columns          : columns,
                // data             : base_url + '<?= $folder; ?>/Cform/data_schedule/<?= $dfrom . '/' . $dto; ?>',
                search: true,
                showColumns: true,
                // showToggle       : true,
                // clickToSelect    : true,
                fixedColumns: true,
                fixedNumber: 4,
                // fixedRightNumber: 1
            })
        }

        $(function() {
            buildTable($table)
        })

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

        $("#checkAll").click(function() {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
    });

    function cekcari() {
        if ($('#dfrom').val() != '' && $('#dto').val() == '') {
            swal('Tanggal Sampai Harus Dipilih!!! ');
            return false;
        } else if ($('#dfrom').val() == '' && $('#dto').val() != '') {
            swal('Tanggal Mulai Harus Dipilih!!! ');
            return false;
        } else {
            return true;
        }
    }
</script>