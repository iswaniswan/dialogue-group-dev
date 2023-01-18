<!DOCTYPE html>
<html>
<title><?= $title; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/luckysheet@2.1.2/dist/plugins/css/pluginsCss.css' />
<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/luckysheet@2.1.2/dist/plugins/plugins.css' />
<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/luckysheet@2.1.2/dist/css/luckysheet.css' />
<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/luckysheet@2.1.2/dist/assets/iconfont/iconfont.css' />

<body>

	<div class="w3-container ">
		<h2>Laporan Mutasi</h2>
		<!-- <p>Tabs are perfect for single page web applications, or for web pages capable of displaying different subjects. Click on the links below.</p> -->
	</div>

	<div class="w3-bar w3-black">
		<button class="w3-bar-item w3-button" onclick="openCity('London')">Unit Packing</button>
		<!-- <button class="w3-bar-item w3-button" onclick="openCity('Paris')">Gudang Jadi</button>
		<button class="w3-bar-item w3-button" onclick="openCity('Tokyo')">WIP</button> -->
		<input type="hidden" id="i_bagian" value="<?= $i_bagian; ?>">
		<input type="hidden" id="i_periode" value="<?= $i_periode; ?>">
		<input type="hidden" id="d_jangka_awal" value="<?= $d_jangka_awal; ?>">
		<input type="hidden" id="d_jangka_akhir" value="<?= $d_jangka_akhir; ?>">
		<input type="hidden" id="d_from" value="<?= $dfrom; ?>">
		<input type="hidden" id="d_to" value="<?= $dto; ?>">
		<input type="hidden" id="i_kelompok" value="<?= $ikelompok; ?>">
		<input type="hidden" id="jenis_barang" value="<?= $jnsbarang; ?>">
	</div>

	<div id="London" class="w3-container city printableArea" style="position: absolute;width: 100%;top:145px;bottom: 0px;left:0px">
		<div id="luckysheet" style="margin:0px;padding:0px;position:absolute;width:100%;height:100%;left: 0px;top: 0px;"></div>
	</div>

	<!-- <div id="Paris" class="w3-container city" style="display:none">
		<h2>Paris</h2>
		<p>Paris is the capital of France.</p>
	</div>

	<div id="Tokyo" class="w3-container city" style="display:none">
		<h2>Tokyo</h2>
		<p>Tokyo is the capital of Japan.</p>
	</div> -->
</body>

</html>
<script src="https://cdn.jsdelivr.net/npm/luckysheet@2.1.2/dist/plugins/js/plugin.js"></script>
<script src="https://cdn.jsdelivr.net/npm/luckysheet@2.1.2/dist/luckysheet.umd.js"></script>
<!-- <script src="<?= base_url(); ?>assets/plugins/bower_components/jquery/dist/jquery.min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/bower_components/blockUI/jquery.blockUI.js"></script> -->
<script type="text/javascript">
	$(function() {
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
			/* beforeSend: function() {
				showLoadingScreen();
			}, */
			success: function(params) {
				var data = [{
					r: 0,
					c: 4,
					v: {
						"ht": "0",
						"vt": "0",
						"bl": 1,
						"bg": "#fce5cd",
						"v": "MASUK",
						"fs": "14"
					}
				}, {
					r: 0,
					c: 8,
					v: {
						"ht": "0",
						"vt": "0",
						"bl": 1,
						"bg": "#fce5cd",
						"v": "KELUAR",
						"fs": "14"
					}
				}];
				var header = ['Kode', 'Nama Barang', 'Warna', 'Saldo Awal', 'Terima untuk di packing (BARANG BAGUS)', 'Terima proses pengisian', 'Total Terima',
					'Kirim hasil packing', 'Kirim repair (UNTUK DIPERBAIKI)', 'Kirim untuk proses pengisian', 'Total Kirim', 'Saldo Akhir', 'SO', 'Selisih'
				];
				for (var h = 0; h < header.length; h++) {
					data.push({
						r: 4,
						c: h,
						v: {
							"ht": "0",
							"vt": "0",
							"bl": "1",
							"tb": "2",
							"bg": "#9fc5e8",
							"v": header[h],
							"fs": "12"
						}
					});
				}
				if (params.length > 0) {
					row = 5;
					var isi = ['i_product_wip', 'e_product_basename', 'e_color_name', 'n_saldo_awal', 'n_masuk_1', 'n_masuk_2', 'n_masuk_total',
						'n_keluar_1', 'n_keluar_2', 'n_keluar_3', 'n_keluar_total', 'n_saldo_akhir', 'n_so', 'n_selisih'
					];
					for (let x = 0; x < params.length; x++) {
						for (var z = 0; z < isi.length; z++) {
							var v = isi[z];
							var ht = 1;
							if (z > 2) {
								ht = '2';
							}
							data.push({
								"r": row,
								"c": z,
								"v": {
									"vt": "0",
									"ht": ht,
									"ct": {
										"fa": "General",
										"t": "g"
									},
									"v": params[x][v],
									"m": params[x][v]
								}
							});
						}
						row++;
					}
				} else {
					data.push([]);
				}


				luckysheet.create({
					container: 'luckysheet',
					showinfobar: false,
					allowEdit: false,
					data: [{
						"name": "Mutasi Packing",
						"color": "",
						"frozen": {
							type: 'rangeBoth',
							range: {
								row_focus: 4,
								column_focus: 2
							}
						},
						"filter_select": {
							"row": [4, (params.length + 4)],
							"column": [0, header.length]
						},
						"config": {
							"merge": {
								"0_0": {
									"rs": 4,
									"cs": 3,
									// "r": 0,
									// "c": 0
								},
								"0_4": {
									"rs": 1,
									"cs": 3,
									// "r": 0,
									// "c": 0
								},
							},
							"rowlen": {
								/* "0": 30,
								"1": 30,
								"2": 30,
								"3": 30, */
								"4": 30,
							},
							"columnlen": {
								"0": 100,
								"1": 400,
								"2": 100
							},
							"borderInfo": [{
									"rangeType": "range",
									"borderType": "border-all",
									"style": "1",
									"color": "#444444",
									"range": [{
										"row": [0, 4],
										"column": [0, header.length]
									}]
								},
								{
									"rangeType": "range",
									"borderType": "border-outside",
									"style": "1",
									"color": "#444444",
									"range": [{
										"row": [0, (params.length + 4)],
										"column": [0, header.length]
									}]
								}, {
									"rangeType": "range",
									"borderType": "border-vertical",
									"style": "1",
									"color": "#444444",
									"range": [{
										"row": [0, (params.length + 4)],
										"column": [0, header.length]
									}]
								}
							]
						},
						"index": "0",
						"chart": [{
							"sheetIndex": "0",
							"dataSheetIndex": "0",
							"chartType": "column",
							"row": "[1,3]",
							"column": "[3,3]",
							"chartStyle": "default",
							"myWidth": "480",
							"myHeight": "288",
							"myLeft": "67",
							"myTop": "11"
						}],
						"status": "1",
						"order": "0",
						"column": header.length,
						"row": params.length,
						"defaultRowHeight": 20, //Customized default row height
						"defaultColWidth": 100, //Customized default column width
						"celldata": data,
						"visibledatarow": [],
						"visibledatacolumn": [],
						"rowsplit": [],
						"ch_width": 4748,
						"rh_height": 1790,
						"luckysheet_select_save": [{
							"row": [
								0,
								1
							],
							"column": [
								0,
								0
							]
						}],
						"luckysheet_selection_range": [],
						"scrollLeft": 0,
						"scrollTop": 0
					}]
				})
			},
			error: function(response) {
				alert('Gagal :(');
				// $(".printableArea").unblock();
			},
			complete: function(data) {
				// $(".printableArea").unblock();
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
		});
	}


	function openCity(cityName) {
		var i;
		var x = document.getElementsByClassName("city");
		for (i = 0; i < x.length; i++) {
			x[i].style.display = "none";
		}
		document.getElementById(cityName).style.display = "block";
	}
</script>