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
					<!-- <div class="controls">
					<button id="export-file">Download CSV</button>
				</div> -->
					<br>
					<center>
						<div class="text-center mt-4">
							<!-- <button id="print" class="btn btn-info btn-outline exportToExcel" type="button"> <span><i class="fa fa-download"></i> Export</span> </button>  -->
							<!-- <button class="button exportToExcel" style="vertical-align:middle"><span>Export </span></button> -->
							<button class="button" id="export-file" style="vertical-align:middle"><span>Export </span></button>
						</div>
					</center>
				</div>
			</div>
		</div>
</body>
<script src="<?= base_url(); ?>assets/plugins/bower_components/jquery/dist/jquery.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/bower_components/blockUI/jquery.blockUI.js"></script>
<!-- <script src="<?= base_url(); ?>assets/bootstrap/dist/js/bootstrap.min.js"></script> -->
<script src="<?= base_url(); ?>assets/js/jquery.table2excel.js"></script>
<script src="<?= base_url(); ?>assets/js/handsontable.full.min.js"></script>
<script>
	$(function() {
		$(".exportToExcel").click(function(e) {
			$(".htCore").table2excel({
				exclude: ".floatThead-col",
				name: "Mutasi Packing",
				filename: "Report_Mutasi_Packing", //do not include extension
				fileext: ".xls" // file extension
			});
		});

		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: '<?= base_url($folder . '/cform/get_data'); ?>',
			async: false,
			data: {
				'i_bagian': $('#i_bagian').val(),
				'i_periode': $('#i_periode').val(),
				'd_jangka_awal': $('#d_jangka_awal').val(),
				'd_jangka_akhir': $('#d_jangka_akhir').val(),
				'd_from': $('#d_from').val(),
				'd_to': $('#d_to').val(),
				'i_kelompok': $('#i_kelompok').val(),
				'jenis_barang': $('#jenis_barang').val(),
			},
			beforeSend: function() {
				showLoadingScreen();
			},
			success: function(data) {
				// console.log(JSON.parse(data[0].data_json));
				var data = JSON.parse(data[0].data_json);
				const generateData = (rows = 3, columns = 7, additionalRows = true) => {
					let counter = 0;

					const array2d = [...new Array(rows)]
						.map(_ => [...new Array(columns)]
						.map(_ => counter++));
					
					// add an empty row at the bottom, to display column summaries
					if (additionalRows) {
						array2d.push([]);
					}

					return array2d;
				};

				const container = document.querySelector('#example2');
				const button = document.querySelector('#export-file');
				Handsontable.renderers.registerRenderer('negativeValueRenderer', negativeValueRenderer);
				const hot = new Handsontable(container, {
					data: data,
					columns: [
						{type: 'text'},
						{type: 'text'},
						{type: 'text'},
						{type: 'numeric'},
						{type: 'numeric'},
						{type: 'numeric'},
						{type: 'numeric'},
						{type: 'numeric'},
						{type: 'numeric'},
						{type: 'numeric'},
						{type: 'numeric'},
						{type: 'numeric'},
						{type: 'numeric'},
						{type: 'numeric'},
						{type: 'numeric'},
						{type: 'numeric'},
						{type: 'numeric'},
						{type: 'numeric'}
					],
					/* colHeaders: true,
					rowHeaders: true, */
					columnSorting: true,
					filters: true,
					readOnly: true,
					dropdownMenu: true,
					// fixedColumnsStart: 3,
					fillHandle: true, // possible values: true, false, "horizontal", "vertical",
					// colWidths: 100,
					width: '100%',
					height: 600,
					rowHeaders: true,
					colHeaders: true,
					fixedColumnsStart: 3,
					contextMenu: true,
					manualColumnFreeze: true,
					// height: 'auto',
						nestedHeaders: [
						[{label: '',colspan: 5}, {label: 'MASUK',colspan: 3}, {label: 'KIRIM',colspan: 4}, {label: '',colspan: 6}],
						[{label: '',colspan: 5},'Dari WIP','Dari Pengisan','','ke GUDANG JADI','Ke WIP','Ke Pengisan','', {label: '',colspan: 6}],
						[{label: '',colspan: 4},'Total','Terima untuk di Packing','Terima Proses','Total','Kirim','Kirim Repair','Kirim untuk','Total','','Total','','Total','','Total'],
						['Kode','Nama Barang','Warna','Saldo Awal','Saldo Awal','(BARANG BAGUS)','Pengisian','Terima','Hasil Packing','(UNTUK DIPERBAIKI)','Proses Pengisian','Kirim','Saldo Akhir','Saldo Akhir','SO','SO','Selisih','Selisih',]
					],
					// afterSelection(row, col, row2, col2) {
					// 	const meta = this.getCellMeta(row2, col2);

					// 	if (meta.readOnly) {
					// 		this.updateSettings({
					// 			fillHandle: false
					// 		});

					// 	} else {
					// 		this.updateSettings({
					// 			fillHandle: true
					// 		});
					// 	}
					// },
					cells(row, col) {
						const cellProperties = {};
						const data = this.instance.getData();

						if (row === 0 || data[row] && data[row][col] === 'readOnly') {
							cellProperties.readOnly = true; // make cell read-only if it is first row or the text reads 'readOnly'
						}

						// if (row === 0) {
						// // cellProperties.renderer = firstRowRenderer; // uses function directly

						// } else {
						cellProperties.renderer = 'negativeValueRenderer'; // uses lookup map
						// }

						return cellProperties;
					},
					columnSummary() {
						const configArray = [];
						const summaryTypes = ['sum'];

						for (let i = 0; i < this.hot.countCols(); i++) { // iterate over visible columns
						// for each visible column, add a column summary with a configuration
						configArray.push({
							sourceColumn: i,
							type: summaryTypes[i],
							// count row coordinates backward
							reversedRowCoords: true,
							// display the column summary in the bottom row (because of the reversed row coordinates)
							destinationRow: 0,
							destinationColumn: i,
							forceNumeric: true
						});
						}
						return configArray;
					},
					licenseKey: 'non-commercial-and-evaluation'
				});

				// const hot = new Handsontable(container, {
				// 	data: data,
				// 	height: 'auto',
				// 	afterSelection(row, col, row2, col2) {
				// 		const meta = this.getCellMeta(row2, col2);

				// 		if (meta.readOnly) {
				// 			this.updateSettings({
				// 				fillHandle: false
				// 			});

				// 		} else {
				// 			this.updateSettings({
				// 				fillHandle: true
				// 			});
				// 		}
				// 	},
				// 	cells(row, col) {
				// 		const cellProperties = {};
				// 		const data = this.instance.getData();

				// 		if (row === 0 || data[row] && data[row][col] === 'readOnly') {
				// 			cellProperties.readOnly = true; // make cell read-only if it is first row or the text reads 'readOnly'
				// 		}

				// 		// if (row === 0) {
				// 		// // cellProperties.renderer = firstRowRenderer; // uses function directly

				// 		// } else {
				// 		cellProperties.renderer = 'negativeValueRenderer'; // uses lookup map
				// 		// }

				// 		return cellProperties;
				// 	},
				// 	columns: [
					// 	{type: 'text'},
					// 	{type: 'text'},
					// 	{type: 'text'},
					// 	{type: 'numeric'},
					// 	{type: 'numeric'},
					// 	{type: 'numeric'},
					// 	{type: 'numeric'},
					// 	{type: 'numeric'},
					// 	{type: 'numeric'},
					// 	{type: 'numeric'},
					// 	{type: 'numeric'},
					// 	{type: 'numeric'},
					// 	{type: 'numeric'},
					// 	{type: 'numeric'},
					// 	{type: 'numeric'},
					// 	{type: 'numeric'},
					// 	{type: 'numeric'},
					// 	{type: 'numeric'}
					// ],
				// 	// colHeaders: true,
				// 	/* colHeaders: [
				// 		'Kode', 
				// 		'Nama Barang', 
				// 		'Warna', 
				// 		'Saldo Awal', 
				// 		'Total Saldo Awal', 
				// 		'Terima untuk di packing (BARANG BAGUS)', 
				// 		'Terima proses pengisian', 
				// 		'Total Terima', 
				// 		'Kirim hasil packing', 
				// 		'Kirim repair (UNTUK DIPERBAIKI)', 
				// 		'Kirim untuk proses pengisian',
				// 		'Total Kirim', 
				// 		'Saldo Akhir', 
				// 		'Total Saldo Akhir', 
				// 		'SO', 
				// 		'Total SO', 
				// 		'Selisih', 
				// 		'Total Selisih', 
				// 	], */
				// 	colHeaders: true,
				// 	rowHeaders: true,
				// 	columnSorting: true,
				// 	filters: true,
				// 	readOnly: true,
				// 	dropdownMenu: true,
				// 	fixedColumnsStart: 3,
				// 	fillHandle: true, // possible values: true, false, "horizontal", "vertical",
				// 	height: 'auto',
				// 	nestedHeaders: [
				// 		[{label: '',colspan: 5}, {label: 'MASUK',colspan: 3}, {label: 'KIRIM',colspan: 4}, 'M'],
				// 		['Kode','Nama Barang','Warna','Saldo Awal','Total Saldo Awal','Terima untuk di packing (BARANG BAGUS)','Terima proses pengisian','Total Terima','Kirim hasil packing','Kirim repair (UNTUK DIPERBAIKI)','Kirim untuk proses pengisian','Total Kirim','Saldo Akhir','Total Saldo Akhir','SO','Total SO','Selisih','Total Selisih',]
				// 	],
				// 	licenseKey: 'non-commercial-and-evaluation'
				// });

				const exportPlugin = hot.getPlugin('exportFile');

				button.addEventListener('click', () => {
					exportPlugin.downloadFile('csv', {
						bom: false,
						columnDelimiter: ',',
						columnHeaders: false,
						exportHiddenColumns: true,
						exportHiddenRows: true,
						fileExtension: 'csv',
						filename: 'Report-Mutasi-Packing_[YYYY]-[MM]-[DD]',
						mimeType: 'text/csv',
						rowDelimiter: '\r\n',
						rowHeaders: true
					});
				});
			}, 
			error: function(response) {
				alert('Gagal :(');
				$(".printableArea").unblock();
			},
			complete: function(data) {
				$(".printableArea").unblock();
			}
		});
	});

	function showLoadingScreen() {
		// include block.js for using this
		$(".printableArea").block({
			message: '<img src="../../../../../../../assets/images/loading.gif" alt=""/><h1>Please Waiting...</h1>',
			centerX: false,
			centerY: false,
            css: {
                border: "none",
                background: "none",
				position: 'fixed',
				margin: 'auto'
            },
			/* message: 'Loading....',
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
			} */
		});
	}

	/* function firstRowRenderer(instance, td, row, col, prop, value, cellProperties) {
		Handsontable.renderers.TextRenderer.apply(this, arguments);
		td.style.fontWeight = 'bold';
		td.style.color = 'green';
		td.style.background = '#CEC';
	} */

	function negativeValueRenderer(instance, td, row, col, prop, value, cellProperties) {
		Handsontable.renderers.TextRenderer.apply(this, arguments);

		// if row contains negative number
		if (parseInt(value, 10) > 0) {
			// add class "negative"
			// td.className = 'make-me-red';
			td.style.fontWeight = 'bold';
			td.style.color = 'green';
			td.style.background = '#CEC';
		} else if (parseInt(value, 10) < 0) {
			td.style.fontWeight = 'bold';
			td.style.color = 'red';
			td.style.background = '#CEC';
		}
		if (!isNaN(value)) {
			td.className = 'htRight';
		}

		if (!value || value === '') {
			td.style.background = '#EEE';
		} else {
			/* if (value === 'Nissan') {
			td.style.fontStyle = 'italic';
			} */
			td.style.background = '';
		}
	}

	// const container = document.getElementById('example1');

	// const data = [
	// 	['', 'Tesla', 'Nissan', 'Toyota', 'Honda'],
	// 	['2017', -5, '', 12, 13],
	// 	['2018', '', -11, 14, 13],
	// 	['2019', '', 15, -12, 'readOnly']
	// ];

	// // maps function to a lookup string
	// Handsontable.renderers.registerRenderer('negativeValueRenderer', negativeValueRenderer);

	// const hot = new Handsontable(container, {
	// 	data: data,
	// 	licenseKey: 'non-commercial-and-evaluation',
	// 	height: 'auto',
	// 	afterSelection(row, col, row2, col2) {
	// 		const meta = this.getCellMeta(row2, col2);

	// 		if (meta.readOnly) {
	// 		this.updateSettings({fillHandle: false});

	// 		} else {
	// 		this.updateSettings({fillHandle: true});
	// 		}
	// 	},
	// 	cells(row, col) {
	// 		const cellProperties = {};
	// 		const data = this.instance.getData();

	// 		if (row === 0 || data[row] && data[row][col] === 'readOnly') {
	// 		cellProperties.readOnly = true; // make cell read-only if it is first row or the text reads 'readOnly'
	// 		}

	// 		if (row === 0) {
	// 		cellProperties.renderer = firstRowRenderer; // uses function directly

	// 		} else {
	// 		cellProperties.renderer = 'negativeValueRenderer'; // uses lookup map
	// 		}

	// 		return cellProperties;
	// 	}
	// });

	/* const container = document.querySelector('#example1');

    const hot = new Handsontable(container, {
        data: Handsontable.helper.createSpreadsheetData(100, 50),
        colWidths: 100,
        width: '100%',
        height: 320,
        rowHeaders: true,
        colHeaders: true,
        columnSorting: true,
        filters: true,
        dropdownMenu: ['filter_by_condition', 'filter_action_bar'],
        fixedColumnsStart: 2,
        licenseKey: 'non-commercial-and-evaluation'
    }); */

	/* const container = document.querySelector('#example1');

const hot = new Handsontable(container, {
  data: [
    ['Lorem', 'ipsum', 'dolor', 'sit', '12/1/2015', 23],
    ['adipiscing', 'elit', 'Ut', 'imperdiet', '5/12/2015', 6],
    ['Pellentesque', 'vulputate', 'leo', 'semper', '10/23/2015', 26],
    ['diam', 'et', 'malesuada', 'libero', '12/1/2014', 98],
    ['orci', 'et', 'dignissim', 'hendrerit', '12/1/2016', 8.5]
  ],
  columns: [
    { type: 'text' },
    { type: 'text' },
    { type: 'text' },
    { type: 'text' },
    { type: 'date', dateFormat: 'M/D/YYYY' },
    { type: 'numeric' }
  ],
  colHeaders: true,
  rowHeaders: true,
  dropdownMenu: true,
  filters: true,
  height: 'auto',
  licenseKey: 'non-commercial-and-evaluation'
}); */
</script>