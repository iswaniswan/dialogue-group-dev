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
                            <th class="table-active text-center middle">Kode</th>
                            <th class="table-active text-center middle">Nama Barang</th>
                            <th class="table-active text-center middle">Warna</th>
                            <?php
                            $week = "";
                            $i = 0;
                            if ($header->num_rows() > 0) {
                                foreach ($header->result() as $key) {
                                    if ($week != $key->n_week) {
                                        $i++; ?>
                                        <th class="table-warning text-center middle">SPB M<?= $i; ?></th>
                                    <?php }
                                    $week = $key->n_week;
                                    ?>
                                    <th class="text-right table-info"><?= $key->d_sj; ?></th>
                            <?php  }
                            } ?>
                            <th class="text-right table-success">SPB</th>
                            <th class="text-right table-success">SJ</th>
                            <th class="text-right table-success">Pendingan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $total_op = 0;
                        $total_sj = 0;
                        if ($detail->num_rows() > 0) {
                            foreach ($detail->result() as $key) { ?>
                                <tr>
                                    <td><?= $no; ?></td>
                                    <td class="text-left"><?= $key->i_product; ?></td>
                                    <td class="text-left"><?= $key->e_product_name; ?></td>
                                    <td class="text-left"><?= $key->e_color_name; ?></td>
                                    <?php
                                    $week = "";
                                    $i = 0;
                                    if ($header->num_rows() > 0) {
                                        $product = "";
                                        foreach ($header->result() as $row) {
                                            if ($week != $row->n_week) {
                                                $i++;
                                                $weekend = 'n_week' . $i;
                                                if ($product != $key->i_product) {
                                                    $total_op = $key->$weekend;
                                                } else {
                                                    $total_op += $key->$weekend;
                                                }
                                    ?>
                                                <td class="text-right table-warning"><?= $key->$weekend; ?></td>
                                            <?php }
                                            $val = 0;
                                            ?>
                                            <td class="text-right val">
                                                <?php
                                                $product = "";
                                                foreach (json_decode($key->d_sj) as $x => $d_sj) {
                                                    if ($d_sj == $row->d_sj) {
                                                        echo json_decode($key->n_deliver)[$x];
                                                        if ($product != $key->i_product) {
                                                            $total_sj = json_decode($key->n_deliver)[$x];
                                                        } else {
                                                            $total_sj += json_decode($key->n_deliver)[$x];
                                                        }
                                                    }
                                                    $product = $key->i_product;
                                                } ?>
                                            </td>
                                    <?php
                                            $week = $row->n_week;
                                            $product = $key->i_product;
                                        }
                                    } ?>
                                    <td class="text-right table-success"><?= $total_op; ?></td>
                                    <td class="text-right table-success"><?= $total_sj; ?></td>
                                    <td class="text-right table-success"><?= $total_op - $total_sj; ?></td>
                                </tr>
                        <?php
                                $no++;
                            }
                        } ?>
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

        $("#submit").click(function() {
            const dfrom = $('#dfrom').val().split('-');
            const dto = $('#dto').val().split('-');
            const periode_from = dfrom[2] + dfrom[1];
            const periode_to = dto[2] + dto[1];
            if (periode_from !== periode_to) {
                swal('Maaf :(', 'Tanggal yang dipilih harus Bulan yang sama !', 'info');
                return false;
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