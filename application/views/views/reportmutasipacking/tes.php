<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/luckysheet/dist/plugins/css/pluginsCss.css' />
<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/luckysheet/dist/plugins/plugins.css' />
<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/luckysheet/dist/css/luckysheet.css' />
<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/luckysheet/dist/assets/iconfont/iconfont.css' />
<script src="https://cdn.jsdelivr.net/npm/luckysheet/dist/plugins/js/plugin.js"></script>
<script src="https://cdn.jsdelivr.net/npm/luckysheet/dist/luckysheet.umd.js"></script>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=10; IE=9; IE=8; IE=7; IE=EDGE" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><?= $this->global['title']; ?></title>
    <!-- <link href="<?= base_url(); ?>assets/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/plugins/bower_components/bootstrap-extension/css/bootstrap-extension.css?v=1" rel="stylesheet"> -->
    <link type="text/css" rel="stylesheet" href="<?= base_url(); ?>assets/css/handsontable.full.min.css" />
    <!-- color CSS -->
    <!-- <link href="<?= base_url(); ?>assets/css/colors/blue.css" id="theme" rel="stylesheet"> -->
    <style>
        .button {
            display: inline-block;
            border-radius: 4px;
            background-color: #2E86C1;
            border: none;
            color: #FFFFFF;
            text-align: center;
            font-size: 15px;
            padding: 5px;
            width: 100px;
            transition: all 0.5s;
            cursor: pointer;
            margin: 4px;
        }

        .button span {
            cursor: pointer;
            display: inline-block;
            position: relative;
            transition: 0.5s;
        }

        .button span:after {
            content: '\00bb';
            position: absolute;
            opacity: 0;
            top: 0;
            right: -20px;
            transition: 0.5s;
        }

        .button:hover span {
            padding-right: 25px;
        }

        .button:hover span:after {
            opacity: 1;
            right: 0;
        }
    </style>
</head>

<body>
    <div class="white-box printableArea">
        <div class="row">
            <div class="col-sm-12">
                <div class="row mt-4">
                    <div class="col-sm-12">
                        <div class="pull-left">
                            <address style="padding:0px">
                                <table cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td width="160px" class="xx text-muted m-l-3" style="font-size: 16px"><b>Nama Bagian</b></td>
                                        <td width="10px" class="xx huruf2 text-muted m-l-3" style="font-size: 16px"><b>:</b></td>
                                        <?php if ($i_bagian != '') { ?>
                                            <td width="300px" class="xx huruf2 text-muted m-l-3" style="font-size: 16px"><b><?= $bagian->i_bagian . ' - ' . $bagian->e_bagian_name; ?></b></td>
                                        <?php } else { ?>
                                            <td width="300px" class="xx huruf2 text-muted m-l-3" style="font-size: 16px"><b>Semua</b></td>
                                        <?php } ?>
                                    </tr>
                                    <tr>
                                        <td width="160px" class="text-muted m-l-3" style="font-size: 16px"><b>Tanggal Mutasi</b></td>
                                        <td width="10px" class="text-muted m-l-3" style="font-size: 16px"><b>:</b></td>
                                        <td width="300px" class="text-muted m-l-3" style="font-size: 16px"><b><?php echo $dfrom . ' s/d ' . $dto; ?></b></td>
                                    </tr>
                                    <tr>
                                        <?php if (!empty($kategori->e_nama_kelompok)) {
                                            $e_nama_kelompok = $kategori->e_nama_kelompok;
                                        } else {
                                            $e_nama_kelompok = "SEMUA KATEGORI";
                                        } ?>
                                        <td width="160px" class="xx text-muted m-l-3" style="font-size: 16px"><b>Kategori Barang</b></td>
                                        <td width="10px" class="xx huruf2 text-muted m-l-3" style="font-size: 16px"><b>:</b></td>
                                        <td width="300px" class="xx huruf2 text-muted m-l-3" style="font-size: 16px"><b><?= $e_nama_kelompok; ?></b></td>
                                    </tr>
                                    <tr>
                                        <?php if (!empty($jenis->e_type_name)) {
                                            $e_type_name = $jenis->e_type_name;
                                        } else {
                                            $e_type_name = "SEMUA SUB KATEGORI";
                                        } ?>
                                        <td width="200" class="xx text-muted m-l-3" style="font-size: 16px"><b>Sub Kategori Barang</b></td>
                                        <td width="10px" class="xx huruf2 text-muted m-l-3" style="font-size: 16px"><b>:</b></td>
                                        <td width="300px" class="xx huruf2 text-muted m-l-3" style="font-size: 16px"><b><?= $e_type_name; ?></b></td>
                                    </tr>
                                </table>
                            </address>
                        </div>
                    </div>
                    <div>
                        &nbsp;
                        <input type="hidden" id="i_bagian" value="<?= $i_bagian; ?>">
                        <input type="hidden" id="i_periode" value="<?= $i_periode; ?>">
                        <input type="hidden" id="d_jangka_awal" value="<?= $d_jangka_awal; ?>">
                        <input type="hidden" id="d_jangka_akhir" value="<?= $d_jangka_akhir; ?>">
                        <input type="hidden" id="d_from" value="<?= $dfrom; ?>">
                        <input type="hidden" id="d_to" value="<?= $dto; ?>">
                        <input type="hidden" id="i_kelompok" value="<?= $ikelompok; ?>">
                        <input type="hidden" id="jenis_barang" value="<?= $jnsbarang; ?>">
                    </div>
                    <div id="example2" class="hot"></div>
                    <div id="luckysheet" style="margin:0px;padding:0px;position:absolute;width:100%;height:90%;left: 0px;bottom: 0px;"></div>
                    <!-- <div class="controls">
					<button id="export-file">Download CSV</button>
				</div> -->
                    <!-- <br>
                    <center>
                        <div class="text-center mt-4">
                            <button class="button" id="export-file" style="vertical-align:middle"><span>Export </span></button>
                        </div>
                    </center> -->
                </div>
            </div>
        </div>
</body>
<script>
    $(function() {
        //Configuration item
        var options = {
            container: 'luckysheet', //luckysheet is the container id,
            default : [{ "name": "Sheet1", color: "", "status": "1", "order": "0", "data": [], "config": {}, "index":0 }, { "name": "Sheet2", color: "", "status": "0", "order": "1", "data": [], "config": {}, "index":1 }, { "name": "Sheet3", color: "", "status": "0", "order": "2", "data": [], "config": {}, "index":2 }]
        }
        luckysheet.create(options)
    })
</script>