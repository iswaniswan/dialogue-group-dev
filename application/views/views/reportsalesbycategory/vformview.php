<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list mr-2"></i> <?= $title_list; ?></div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div class="d-flex justify-content-center">
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
                </div>
                </form>
                <table id="tabledata" class="display nowrap" cellspacing="0" width="100%" data-id-field="id">
                    <thead>
                        <tr>
                            <th class="table-active text-center middle" rowspan="2">#</th>
                            <th class="table-active text-center middle" rowspan="2">Category</th>
                            <th class="table-active text-center middle" colspan="3">OA</th>
                            <th class="table-active text-center middle" colspan="3">Sales Qty(Unit)</th>
                            <th class="table-active text-center middle" colspan="3">Net Sales(Rp.)</th>
                            <th class="table-active text-center middle" rowspan="2">%Ctr Net Sales(Rp.)</th>
                        </tr>
                        <tr>
                            <th class="table-active text-center middle"><?= date('Y', strtotime($dfrom . '- 1 years')) ?></th>
                            <th class="table-active text-center middle"><?= date('Y', strtotime($dfrom)) ?></th>
                            <th class="table-active text-center middle">%</th>
                            <th class="table-active text-center middle"><?= date('Y', strtotime($dfrom . '- 1 years')) ?></th>
                            <th class="table-active text-center middle"><?= date('Y', strtotime($dfrom)) ?></th>
                            <th class="table-active text-center middle">%</th>
                            <th class="table-active text-center middle"><?= date('Y', strtotime($dfrom . '- 1 years')) ?></th>
                            <th class="table-active text-center middle"><?= date('Y', strtotime($dfrom)) ?></th>
                            <th class="table-active text-center middle">%</th>
                            <?php
                            // $week = "";
                            // $i = 0;
                            // if ($header->num_rows() > 0) {
                            //     foreach ($header->result() as $key) {
                            //         if ($week != $key->n_week) {
                            //             $i++; ?>
                                        <!-- <th class="table-warning text-center middle">SPB M<?= $i; ?></th> -->
                                    <?php  // }
                                    // $week = $key->n_week;
                                    ?>
                                    <!-- <th class="text-right table-info"><?= $key->d_sj; ?></th> -->
                            <?php  // }
                             //} ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $total_op = 0;
                        $total_sj = 0;
                        // $sum_oa_tahun_sebelumnya = 0;
                        // $sum_oa_tahun_saat_ini = 0;
                        // $total_sum_percentage_oa = 0;
                        // $sum_sales_qty_tahun_sebelumnya = 0;
                        // $sum_sales_qty_tahun_saat_ini = 0;
                        // $total_sum_percentage_sales_qty = 0;
                        // $sum_net_sales_tahun_sebelumnya = 0;
                        // $sum_net_sales_tahun_saat_ini = 0;
                        // $total_sum_percentage_net_sales = 0;
                        $total_ctr_net_sales = 0;
                        if ($detail->num_rows() > 0) {
                            foreach ($detail->result() as $key) { 
                                ?>
                                <tr>
                                    <td><?= $no; ?></td>
                                    <td class="text-left"><?= $key->category; ?></td>
                                    <td class="text-right"><?= number_format($key->oa_tahun_sebelumnya,2,',','.'); ?></td>
                                    <td class="text-right"><?= number_format($key->oa_tahun_saat_ini,2,',','.'); ?></td>
                                    <td class="text-right"><?= ($key->oa_tahun_saat_ini != 0 AND $key->oa_tahun_sebelumnya != 0) ? round(($key->oa_tahun_saat_ini / $key->oa_tahun_sebelumnya * 100) - 100, 2) . '%' : 0 . '%' ?></td>
                                    <td class="text-right"><?= number_format($key->sales_qty_tahun_sebelumnya,2,',','.'); ?></td>
                                    <td class="text-right"><?= number_format($key->sales_qty_tahun_saat_ini,2,',','.'); ?></td>
                                    <td class="text-right"><?= ($key->sales_qty_tahun_saat_ini != 0 AND $key->sales_qty_tahun_sebelumnya != 0) ? round(($key->sales_qty_tahun_saat_ini / $key->sales_qty_tahun_sebelumnya * 100) - 100, 2) . '%' : 0 . '%' ?></td>
                                    <td class="text-right"><?= number_format($key->net_sales_tahun_sebelumnya,2,',','.'); ?></td>
                                    <td class="text-right"><?= number_format($key->net_sales_tahun_saat_ini,2,',','.'); ?></td>
                                    <td class="text-right"><?= ($key->net_sales_tahun_saat_ini != 0 AND $key->net_sales_tahun_sebelumnya != 0) ? round(($key->net_sales_tahun_saat_ini / $key->net_sales_tahun_sebelumnya * 100) - 100, 2) . '%' : 0 . '%' ?></td>
                                    <td class="text-right"><?= ($key->net_sales_tahun_saat_ini != 0 AND $sum_net_sales_tahun_saat_ini != 0) ? round(($key->net_sales_tahun_saat_ini / $sum_net_sales_tahun_saat_ini * 100), 2) . '%' : 0 . '%' ?></td>
                                </tr>
                        <?php
                                // $sum_oa_tahun_sebelumnya += $key->oa_tahun_sebelumnya;
                                // $sum_oa_tahun_saat_ini += $key->oa_tahun_saat_ini;
                                // $total_sum_percentage_oa += ($key->oa_tahun_saat_ini != 0 AND $key->oa_tahun_sebelumnya != 0) ? round(($key->oa_tahun_saat_ini / $key->oa_tahun_sebelumnya * 100) - 100, 2) : 0;
                                // $sum_sales_qty_tahun_sebelumnya += $key->sales_qty_tahun_sebelumnya;
                                // $sum_sales_qty_tahun_saat_ini += $key->sales_qty_tahun_saat_ini;
                                // $total_sum_percentage_sales_qty += ($key->sales_qty_tahun_saat_ini != 0 AND $key->sales_qty_tahun_sebelumnya != 0) ? round(($key->sales_qty_tahun_saat_ini / $key->sales_qty_tahun_sebelumnya * 100) - 100, 2) : 0;
                                // $sum_net_sales_tahun_sebelumnya += $key->net_sales_tahun_sebelumnya;
                                // $sum_net_sales_tahun_saat_ini += $key->net_sales_tahun_saat_ini;
                                // $total_sum_percentage_net_sales += ($key->net_sales_tahun_saat_ini != 0 AND $key->net_sales_tahun_sebelumnya != 0) ? round(($key->net_sales_tahun_saat_ini / $key->net_sales_tahun_sebelumnya * 100) - 100, 2) : 0;
                                $total_ctr_net_sales += ($key->net_sales_tahun_saat_ini != 0 AND $sum_net_sales_tahun_saat_ini != 0) ? round(($key->net_sales_tahun_saat_ini / $sum_net_sales_tahun_saat_ini * 100), 2) : 0;
                                $no++;
                            }
                        } ?>
                        <tr>
                            <th colspan="2">Total</th>
                            <th class="text-right"><?= number_format($sum_oa_tahun_sebelumnya,2,',','.'); ?></th>
                            <th class="text-right"><?= number_format($sum_oa_tahun_saat_ini,2,',','.'); ?></th>
                            <th class="text-right"><?= number_format($total_sum_percentage_oa,2,',','.'); ?>%</th>
                            <th class="text-right"><?= number_format($sum_sales_qty_tahun_sebelumnya,2,',','.'); ?></th>
                            <th class="text-right"><?= number_format($sum_sales_qty_tahun_saat_ini,2,',','.'); ?></th>
                            <th class="text-right"><?= number_format($total_sum_percentage_sales_qty,2,',','.'); ?>%</th>
                            <th class="text-right"><?= number_format($sum_net_sales_tahun_sebelumnya,2,',','.'); ?></th>
                            <th class="text-right"><?= number_format($sum_net_sales_tahun_saat_ini,2,',','.'); ?></th>
                            <th class="text-right"><?= number_format($total_sum_percentage_net_sales,2,',','.'); ?>%</th>
                            <th class="text-right"><?= round($total_ctr_net_sales) ?>%</th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        showCalendar2('.date', null);
        $("#dfrom").change(function() {
            var dfrom = splitdate($(this).val());
            var dto = splitdate($('#dto').val());
            var dfromSplit = $('#dfrom').val().split('-');
            var dtoSplit = $('#dto').val().split('-');
            let yearFrom = dfromSplit[2];
            let yearTo = dtoSplit[2];
            if (dfrom != null && dto != null) {
                if (dfrom > dto) {
                    swal('Tanggal Mulai Tidak Boleh Lebih Besar Dari Tanggal Sampai!!!');
                    $('#dfrom').val('');
                }
                if(yearFrom != yearTo) {
                    swal('Periode Harus Dari Tahun Yang Sama!!!');
                    $('#dto').val('');
                }
            }
        });

        $("#dto").change(function() {
            var dto = splitdate($(this).val());
            var dfrom = splitdate($('#dfrom').val());
            var dfromSplit = $('#dfrom').val().split('-');
            var dtoSplit = $('#dto').val().split('-');
            let yearFrom = dfromSplit[2];
            let yearTo = dtoSplit[2];
            if (dfrom != null && dto != null) {
                if (dfrom > dto) {
                    swal('Tanggal Sampai Tidak Boleh Lebih Kecil Dari Tanggal Mulai!!!');
                    $('#dto').val('');
                }
                if(yearFrom != yearTo) {
                    swal('Periode Harus Dari Tahun Yang Sama!!!');
                    $('#dto').val('');
                }
            }
        });

        var td = document.querySelectorAll('.val');
        td.forEach((x, y) => {
            if (x.innerText == '') {
                x.innerText = 0;
            }
        })

        $("#submit").click(function() {
            var dfromSplit = $('#dfrom').val().split('-');
            var dtoSplit = $('#dto').val().split('-');
            let yearFrom = dfromSplit[2];
            let yearTo = dtoSplit[2];
            if(yearFrom != yearTo) {
                swal('Maaf :(', 'Periode Harus Dari Tahun Yang Sama!!!', 'info');
                $('#dto').val('');
            }
        });
        buildTable($('#tabledata'));
        /* setTimeout(function(){
            buildTable($('#tabledata'));
        }, 100); */
    });

    function buildTable(elm) {
        elm.bootstrapTable('destroy').bootstrapTable({
            showExport: true,
            /*exportOptions: {
                fileName: 'Laporan_penjualan'
            }, */
            height: 300,
            /* columns: [
                [{
                    field: 'no',
                    align: 'center',
                    valign: 'middle'
                }, {
                    field: 'kode',
                    align: 'center',
                    valign: 'middle'
                }, {
                    title: 'Item ID',
                    field: 'id',
                    align: 'center',
                    valign: 'middle',
                    footerFormatter: 'TOTAL'
                }]
            ], */
            // columns          : columns,
            // data             : data,
            search: true,
            showColumns: false,
            showFullscreen: true,
            // showFooter: true,
            showToggle: true,
            // clickToSelect    : true,
            fixedColumns: true,
            fixedNumber: 4,
            // fixedRightNumber: 1
        })
    }
</script>