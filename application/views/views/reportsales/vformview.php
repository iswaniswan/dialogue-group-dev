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
                            <th class="table-active text-center middle">#</th>
                            <th class="table-active text-center middle">Bulan</th>
                            <th class="table-active text-center middle">Target(RP)</th>
                            <th class="table-active text-center middle">SPB(RP)</th>
                            <th class="table-active text-center middle">SPB(QTY)</th>
                            <th class="table-active text-center middle">SJ(RP)</th>
                            <th class="table-active text-center middle">SJ(QTY)</th>
                            <th class="table-active text-center middle">SPB-SJ(RP)</th>
                            <th class="table-active text-center middle">SPB-SJ(QTY)</th>
                            <th class="table-active text-center middle">Nota(RP)</th>
                            <th class="table-active text-center middle">Nota(QTY)</th>
                            <th class="table-active text-center middle">Nota To Target(%)</th>
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
                        $sum_target_rp = 0;
                        $sum_spb_rp = 0;
                        $sum_spb_qty = 0;
                        $sum_sj_rp = 0;
                        $sum_sj_qty = 0;
                        $sum_spb_sj_rp = 0;
                        $sum_spb_sj_qty = 0;
                        $sum_nota_rp = 0;
                        $sum_nota_qty = 0;
                        $total_sum_nota_to_target = 0;
                        if ($detail->num_rows() > 0) {
                            foreach ($detail->result() as $key) { 
                                ?>
                                <tr>
                                    <td><?= $no; ?></td>
                                    <td class="text-left"><?= $key->periode; ?></td>
                                    <td class="text-right"><?= number_format($key->target_rp,2,',','.'); ?></td>
                                    <td class="text-right"><?= number_format($key->spb_rp,2,',','.'); ?></td>
                                    <td class="text-right"><?= $key->spb_qty; ?></td>
                                    <td class="text-right"><?= number_format($key->sj_rp,2,',','.');; ?></td>
                                    <td class="text-right"><?= $key->sj_qty; ?></td>
                                    <td class="text-right"><?= number_format($key->spb_sj_rp,2,',','.'); ?></td>
                                    <td class="text-right"><?= $key->spb_sj_qty; ?></td>
                                    <td class="text-right"><?= number_format($key->nota_rp,2,',','.'); ?></td>
                                    <td class="text-right"><?= $key->nota_qty; ?></td>
                                    <td class="text-right"><?= ($key->nota_rp != 0 AND $key->target_rp != 0) ? round(($key->nota_rp / $key->target_rp) * 100 * 100, 2) . '%' : 0 . '%' ?></td>
                                </tr>
                        <?php
                                $sum_target_rp += $key->target_rp;
                                $sum_spb_rp += $key->spb_rp;
                                $sum_spb_qty += $key->spb_qty;
                                $sum_sj_rp += $key->sj_rp;
                                $sum_sj_qty += $key->sj_qty;
                                $sum_spb_sj_rp += $key->spb_sj_rp;
                                $sum_spb_sj_qty += $key->spb_sj_qty;
                                $sum_nota_rp += $key->nota_rp;
                                $sum_nota_qty += $key->nota_qty;
                                $total_sum_nota_to_target = ($key->nota_rp != 0 AND $key->target_rp != 0) ? round(($key->nota_rp / $key->target_rp) * 100 * 100, 2) : 0;
                                $no++;
                            }
                        } ?>
                        <tr>
                            <th colspan="2">Total</th>
                            <th class="text-right"><?= number_format($sum_target_rp,2,',','.'); ?></th>
                            <th class="text-right"><?= number_format($sum_spb_rp,2,',','.'); ?></th>
                            <th class="text-right"><?= number_format($sum_spb_qty,2,',','.'); ?></th>
                            <th class="text-right"><?= number_format($sum_sj_rp,2,',','.'); ?></th>
                            <th class="text-right"><?= number_format($sum_sj_qty,2,',','.'); ?></th>
                            <th class="text-right"><?= number_format($sum_spb_sj_rp,2,',','.'); ?></th>
                            <th class="text-right"><?= number_format($sum_spb_sj_qty,2,',','.'); ?></th>
                            <th class="text-right"><?= number_format($sum_nota_rp,2,',','.'); ?></th>
                            <th class="text-right"><?= number_format($sum_nota_qty,2,',','.'); ?></th>
                            <th class="text-right"><?= $total_sum_nota_to_target; ?>%</th>
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

        var td = document.querySelectorAll('.val');
        td.forEach((x, y) => {
            if (x.innerText == '') {
                x.innerText = 0;
            }
        })

        // $("#submit").click(function() {
        //     const dfrom = $('#dfrom').val().split('-');
        //     const dto = $('#dto').val().split('-');
        //     const periode_from = dfrom[2] + dfrom[1];
        //     const periode_to = dto[2] + dto[1];
        //     if (periode_from !== periode_to) {
        //         swal('Maaf :(', 'Tanggal yang dipilih harus Bulan yang sama !', 'info');
        //         return false;
        //     }
        // });
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