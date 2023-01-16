<style>
    .nowrap {
        white-space: nowrap !important;
        font-size: 12px;
    }
</style>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Kode Barang</label>
                        <label class="col-md-4">Nama Barang</label>
                        <label class="col-md-3">Nama Marker</label>
                        <label class="col-md-2 text-center">Marker Utama</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control input-sm" readonly value="<?= $data->i_product_wip; ?>">
                        </div>
                        <div class="col-sm-4">
                            <input type="text" class="form-control input-sm" readonly value="<?= $data->e_product_wipname; ?>">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" class="form-control input-sm" readonly value="<?= $data->e_marker_name; ?>">
                        </div>
                        <div class="col-sm-2">
                            <input type="checkbox" class="form-control input-sm" readonly <?php if($data->f_marker_utama=='t'){?> checked <?php } ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder; ?>/cform','#main')"> <i class="fa fa-arrow-circle-left fa-lg mr-2"></i>Kembali</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if ($detail) { ?>
    <div class="white-box" id="detail">
        <div class="col-sm-5">
            <h3 class="box-title m-b-0">Detail Barang</h3>
        </div>
        <div class="col-sm-12">
            <div class="table-responsive">
            <!-- <div class="table_fixed" style="width: 100%; max-height: 500px;"> -->
                <table id="sitabel" class="table color-table nowrap table-bordered inverse-table" cellpadding="8" cellspacing="1" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center" width="3%">No</th>
                            <th>Kode</th>
                            <th>Nama Material</th>
                            <th>Gudang</th>
                            <th>Bagian</th>
                            <th class="text-right">Panjang Gelaran</th>
                            <th class="text-right">Set</th>
                            <th class="text-right">Kebutuhan Bis<sup>2</sup>an</th>
                            <th>Ukuran Bis<sup>2</sup>an</th>
                            <th class="text-center">Type Makloon</th>
                            <th class="text-center">Kain<br>Utama</th>
                            <th class="text-center">Dibudgetkan</th>
                            <th class="text-center col-1">Jahit<br>
                                <i class="fa fa-question-circle fa-lg ml-2" tabindex="0" data-trigger="focus" class="btn btn-default"   data-container="body" data-toggle="popover" data-placement="bottom" title="Dapat diminta oleh bagian Pengadaan"></i>
                            </th>
                            <th class="text-center col-1">Packing<br>
                                <i class="fa fa-question-circle fa-lg ml-2" tabindex="0" data-trigger="focus" class="btn btn-default"   data-container="body" data-toggle="popover" data-placement="bottom" title="Dapat diminta oleh bagian Packing"></i>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 0;
                        $group = "";
                        foreach ($detail as $key) {
                            $i++;
                            if ($group!=$key->e_nama_group_barang) {?>
                                <tr class="table-active">
                                    <td colspan="14" class="text-left font-weight-bold"><?= $key->e_nama_group_barang;?></td>
                                </tr>
                            <?php $i =1; }
                            $group = $key->e_nama_group_barang;
                        ?>
                            <tr>
                                <td class=" text-center">
                                    <spanx id="snum<?= $i; ?>"><?= $i; ?></spanx>
                                </td>
                                <td><?= $key->i_material; ?></td>
                                <td><?= $key->e_material_name; ?></td>
                                <td><?= $key->gudang; ?></td>
                                <td><?= $key->bagian; ?></td>
                                <td class="text-right"><?= $key->v_gelar; ?></td>
                                <td class="text-right"><?= $key->v_set; ?></td>
                                <td class="text-right"><?= $key->n_bis3; ?></td>
                                <td><?= $key->n_bisbisan . ' - ' . $key->e_jenis_potong; ?></td>
                                <td>
                                    <?php
                                    $makloon = json_decode($key->e_type_makloon_name);
                                    $text = "";
                                    foreach ($makloon as $value) {
                                        $text .= $value . ", ";
                                    }
                                    echo wordwrap(substr($text, 0, -2), 40, "<br>\n");
                                    ?>
                                </td>
                                <td>
                                    <input type="checkbox" class="form-control input-sm" <?php if ($key->f_kain_utama=='t') {?> checked <?php } ?>>
                                </td>
                                <td>
                                    <input type="checkbox" class="form-control input-sm" <?php if ($key->f_budgeting=='t') {?> checked <?php } ?>>
                                </td>
                                <td>
                                    <input type="checkbox" class="form-control input-sm" <?php if ($key->f_jahit=='t') {?> checked <?php } ?>>
                                </td>
                                <td>
                                    <input type="checkbox" class="form-control input-sm" <?php if ($key->f_packing=='t') {?> checked <?php } ?>>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">
<?php } ?>
<script>
    /* $(function() {
        $(".table_fixed").freezeTable({
            'columnNum': 3,
            'scrollable': true,
        });
    }) */

    $(document).ready(function() {
        var $table = $('#sitabel');

        function buildTable(elm) {
            elm.bootstrapTable('destroy').bootstrapTable({
                height: 400,
                // columns          : columns,
                // data             : data,
                search: true,
                showColumns: true,
                // showToggle       : true,
                // clickToSelect    : true,
                // fixedColumns: true,
                // fixedNumber: 2,
                // fixedRightNumber: 1
            })
        }

        $(function() {
            buildTable($table)
            popover();
        })

	// 	var table = $('#sitabel').DataTable({
	// 		/* scrollY: "400px",
	// 		scrollX: true, */
	// 		scrollCollapse: true,
	// 		paging: false,
	// 		fixedColumns: {
	// 			left: 3
	// 		},
	// 	});
	// 	table.columns.adjust().draw();
	// 	$('input[type=search]').attr('class', 'input-sm');
    //     $('input[type=search]').attr('class', 'mr-4');
	// 	$("input[type=search]").attr("size", "15");
	// 	$("input[type=search]").attr("placeholder", "type to search ...");
	// 	$("input[type=search]").focus();
    //     fixedtable($('#sitabel'));
	});
</script>