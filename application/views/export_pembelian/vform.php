<style>
    .table-xs>thead>tr>td,
    .table-xs>tbody>tr>td,
    .table-xs>tfoot>tr>td {
        padding: 4px;
    }

    .table-xs>thead>tr>th,
    .table-xs>tbody>tr>th,
    .table-xs>tfoot>tr>th {
        padding: 5px;
    }

    .table-xs tr:nth-child(even) {
        background-color: #e7e9eb;
    }
</style>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info shadow">
            <div class="panel-heading">
                <i class="fa fa-download mr-2"></i><?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-spin fa-refresh mr-2"></i>Refresh</a>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-3 text-right">
                        <!-- <span class="text-right">Periode : </span> -->
                        <label class="mt-2">SELECT PERIODE</label>
                    </div>
                    <div class="col-sm-2">
                        <!-- <input type="text" id="dfrom" name="dfrom" class="form-control input-sm date" readonly value="<?= date("d-m-Y"); ?>"> -->
                        <div class="input-group mb-3">
                            <input type="text" readonly value="<?= date("01-m-Y"); ?>" id="dfrom" name="dfrom" class="form-control date input-sm" placeholder="" aria-label="" aria-describedby="basic-addon1">
                            <button class="btn btn-sm btn-primary btn-light-danger" type="button">
                                <i class="fa fa-calendar-minus-o" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <!-- <input type="text" id="dto" name="dto" class="form-control input-sm date" readonly value="<?= date("d-m-Y"); ?>"> -->
                        <div class="input-group mb-3">
                            <input type="text" readonly value="<?= date("d-m-Y"); ?>" id="dto" name="dto" class="form-control date input-sm" placeholder="" aria-label="" aria-describedby="basic-addon1">
                            <button class="btn btn-sm btn-primary btn-light-danger" type="button">
                                <i class="fa fa-calendar-plus-o" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <select class="form-control form-control-sm select2" id="i_supplier">
                            <option value="0" selected>All Supplier</option>
                            <?php if ($supplier->num_rows() > 0) {
                                foreach ($supplier->result() as $key) { ?>
                                    <option value="<?= $key->i_supplier; ?>"><?= $key->e_supplier_name; ?></option>
                            <?php }
                            } ?>
                        </select>
                    </div>
                    <div class="col-sm-1"></div>
                </div>
                <div class="row" hidden>
                    <div class="col-sm-2"></div>
                    <div class="col-sm-8 text-center">
                        <!--  <label class="mr-2" for="#d_transaksi">Dari Tanggal Transaksi</label><input type="radio" checked id="d_transaksi" name="check" value="tgl_transaksi" class="mt-3 mr-3"> -->
                        <label class="mr-2" for="#d_sj">Dari Tanggal Surat Jalan / Faktur (Supplier)</label><input type="radio" id="d_sj" name="check" value="tgl_sj" class="mt-3" checked>
                    </div>
                    <!-- <div class="col-sm-1">
                        <div class="input-group mb-3">
                            <input type="radio" id="d_transaksi" name="check" value="tgl_transaksi" class="input-sm" placeholder="" aria-label="" aria-describedby="basic-addon1">
                        </div>
                    </div>
                    <div class="col-sm-2 text-right">
                        <label class="mt-3">Dari Tanggal Surat Jalan</label>
                    </div>
                    <div class="col-sm-1">
                        <div class="input-group mb-3">
                            <input type="radio" id="d_sj" name="check" value="tgl_sj" class="input-sm" placeholder="" aria-label="" aria-describedby="basic-addon1">
                        </div>
                    </div> -->
                    <div class="col-sm-2"></div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-xs color-table inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="3%">No</th>
                                        <th class="text-center" width="30%">Nama Module</th>
                                        <th class="text-center" colspan="4" width="50%">File Download</th>
                                        <th class="text-center" width="2%"><i class="fa fa-chevron-down" aria-hidden="true"></i></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $data = [
                                        ['params' => 'exp_pembelian', 'title' => 'Laporan Pembelian', 'color' => 'success'],
                                        ['params' => 'exp_kartu', 'title' => 'Kartu Hutang', 'color' => 'success'],
                                        ['params' => 'exp_opname', 'title' => 'Laporan Opname Hutang Dagang', 'color' => 'success'],
                                        ['params' => 'exp_rekapitulasi', 'title' => 'Rekapitulasi Hutang Dagang', 'color' => 'success'],
                                        ['params' => 'exp_buku', 'title' => 'Rekapitulasi Buku Pembelian', 'color' => 'success'],
                                        ['params' => 'exp_opvsbtb', 'title' => 'Laporan OP vs BTB/SJ Qty', 'color' => 'info'],
                                        ['params' => 'exp_budgeting_realisasi', 'title' => 'Laporan Budgeting vs Realisasi', 'color' => 'info'],
                                        ['params' => 'exp_btb_faktur', 'title' => 'Laporan BTB vs Faktur', 'color' => 'info'],
                                        ['params' => 'exp_rekap_supplier', 'title' => 'Rekap Pembelian per Supplier', 'color' => 'info'],
                                        ['params' => 'exp_btb_dan_faktur', 'title' => 'Laporan BTB dan Faktur Pembelian', 'color' => 'info'],
                                        ['params' => 'exp_per_kategori', 'title' => 'Laporan Pembelian per Kategori', 'color' => 'info'],
                                        ['params' => 'exp_pp', 'title' => 'Laporan Permintaan Pembelian Belum OP', 'color' => 'info'],
                                        ['params' => 'exp_obpp', 'title' => 'Laporan OB / PP', 'color' => 'info'],
                                    ]; ?>
                                    <tr class="table-active">
                                        <td colspan="2" class="text-center"><strong> L A P O R A N</strong></td>
                                        <td width="25%" colspan="2" class="text-center text-info"><strong> V I E W</strong></td>
                                        <td width="25%" colspan="2" class="text-center text-success"><strong> E X P O R T</strong></td>
                                        <td class="text-center"><span class="badge label-primary"><?= count($data); ?></span><!-- <span class="badge badge-pill">9</span> -->
                                        </td>
                                    </tr>
                                    <?php
                                    $no = 0;
                                    foreach ($data as $key) {
                                        $no++; ?>
                                        <tr class="<?= $key['params']; ?>">
                                            <td class="text-center">
                                                <label class="mt-2"><?= $no; ?></label>
                                            </td>
                                            <td>
                                                <label class="mt-2"><?= $key['title']; ?></label>
                                            </td>
                                            <td class="text-center"><i class="fa fa-file-text-o fa-2x text-info" aria-hidden="true"></i></td>
                                            <td>
                                                <!-- <a data-toggle="modal" href="vform.php" data-target="#modal">Click me</a> -->
                                                <button type="button" onclick="view_pembelian('<?= $key['params']; ?>','<?= $key['title']; ?>');" class="btn btn-outline btn-sm btn-block btn-info">
                                                    <strong><?= $key['title']; ?><i class="fa fa-eye fa-lg ml-2"></i></strong>
                                                </button>
                                            </td>
                                            <td class="text-center"><i class="fa fa-file-excel-o fa-2x text-success" aria-hidden="true"></i></td>
                                            <td>
                                                <button type="button" onclick="export_pembelian('<?= $key['params']; ?>');" class="btn btn-outline btn-sm btn-block btn-success">
                                                    <strong><?= $key['title']; ?><i class="fa fa-download fa-lg ml-2"></i></strong>
                                                </button>
                                            </td>
                                            <td></td>
                                        </tr>
                                    <?php }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('.select2').select2();
        showCalendar('.date', null, 0);
    });

    function disableF5(e) {
        if ((e.which || e.keyCode) == 116) e.preventDefault();
    };

    $(document).on("keydown", disableF5);

    function export_pembelian(id_laporan) {
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '<?= base_url($folder . '/cform/export_laporan'); ?>',
            async: false,
            data: {
                'date_from': $('#dfrom').val(),
                'date_to': $('#dto').val(),
                'i_supplier': $('#i_supplier').val(),
                'laporan': id_laporan,
                'check': $('input[name="check"]:checked').val()
            },
            beforeSend: function() {
                showLoadingScreen(id_laporan);
            },
            success: function(data) {
                // console.log(data);
                if (data.data === true) {
                    console.log(data);
                    var $a = $("<a>");
                    $a.attr("href", data.file);
                    $("body").append($a);
                    $a.attr("download", data.nama_file);
                    $a[0].click();
                    $a.remove();
                    $("." + id_laporan + "").unblock();
                } else {
                    swal('Maaf :(', 'Data untuk periode / supplier yang dpilih tidak ada ^_^ ', 'error');
                }
            },
            error: function(response) {
                swal('Gagal :(', 'Gagal Export Laporan ', 'error');
                $("." + id_laporan + "").unblock();
            },
            complete: function(data) {
                $("." + id_laporan + "").unblock();
            }
        });
    }

    function showLoadingScreen(id_laporan) {
        //include block.js for using this
        $("." + id_laporan + "").block({
            message: 'Loading....',
            /* message: '<img src="' + base_url + '/assets/images/loading1.gif" alt="loading" /><h1 class="text-white">L o a d i n g</h1>',
            centerX: false,
            centerY: false,
            overlayCSS: {
                // backgroundColor: "#fff",
                opacity: 0.8,
                cursor: "wait",
            },
            css: {
                border: 0,
                padding: 0,
                backgroundColor: "none",
            } */
            css: {
                border: '0',
                width: '99%',
                height: '25px',
                padding: '0',
                backgroundColor: '#000',
                '-webkit-border-radius': '10px',
                '-moz-border-radius': '10px',
                opacity: .5,
                color: '#fff'
            }
        });
    }

    function view_pembelian(id_laporan, title) {
        const src = '<?= base_url($folder . '/cform/view_laporan'); ?>/?date_from='+ $('#dfrom').val() + "&date_to=" + $('#dto').val() + "&i_supplier=" + $('#i_supplier').val() + "&laporan=" + id_laporan+ "&title=" + title;
        window.open(src, "title", "width="+screen.availWidth+",height="+screen.availHeight)
        // $("#ModalTitle").text(title);
        // $.ajax({
        //     type: 'POST',
        //     dataType: 'json',
        //     url: '<?= base_url($folder . '/cform/get_data_laporan'); ?>',
        //     async: false,
        //     data: {
        //         'date_from': $('#dfrom').val(),
        //         'date_to': $('#dto').val(),
        //         'i_supplier': $('#i_supplier').val(),
        //         'laporan': id_laporan,
        //         'check': $('input[name="check"]:checked').val()
        //     },
        //     beforeSend: function() {
        //         showLoadingScreen(id_laporan);
        //     },
        //     success: function(data) {
        //         // console.log(data['data'].length);
        //         if (data['data'].length > 0) {
        //             var table = `<table id="tabledatay" class="table color-table tableFixHead inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
        //         <thead>
        //             <tr>
        //                 <th class="text-center" width="3%;">No</th>
        //                 <th>Kode Barang</th>
        //                 <th>Nama Barang</th>
        //                 <th>Warna</th>
        //                 <th class="text-right">Jml SO Bagus</th>
        //                 <th class="text-right">Jml SO Repair</th>
        //                 <th>Keterangan</th>
        //                 <th>Keterangan</th>
        //                 <th>Keterangan</th>
        //                 <th>Keterangan</th>
        //                 <th>Keterangan</th>
        //                 <th>Keterangan</th>
        //                 <th>Keterangan</th>
        //                 <th>Keterangan</th>
        //                 <th>Keterangan</th>
        //                 <th>Keterangan</th>
        //                 <th>Keterangan</th>
        //                 <th>Keterangan</th>
        //                 <th>Keterangan</th>
        //             </tr>`;
        //             $("#myModal .modal-body").html(table);
        //             $("#myModal").modal('show');
        //         } else {
        //             swal('Maaf :(', 'Data untuk periode / supplier yang dpilih tidak ada ^_^ ', 'error');
        //         }
        //     },
        //     error: function(response) {
        //         console.log(response);
        //         $("." + id_laporan + "").unblock();
        //     },
        //     complete: function(data) {
        //         $("." + id_laporan + "").unblock();
        //     }
        // });
    }
</script>