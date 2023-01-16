<!DOCTYPE html>
<html>
<title>W3.CSS</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/luckysheet@2.1.2/dist/plugins/css/pluginsCss.css' />
<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/luckysheet@2.1.2/dist/plugins/plugins.css' />
<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/luckysheet@2.1.2/dist/css/luckysheet.css' />
<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/luckysheet@2.1.2/dist/assets/iconfont/iconfont.css' />
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
			success: function(data) {
				luckysheet.create({
					container: 'luckysheet',
					showinfobar: false,
					allowEdit: false,
					showstatisticBarConfig: {
						zoom: true,
					},
					data: [{
						"name": "Mutasi Packing",
						"color": "",
						"config": {
							"merge": {
								"0_0": {
									"rs": 1,
									"cs": 6,
									/* "r": 0,
									"c": 0 */
								},
								"0_6": {
									"rs": 1,
									"cs": 3,
								},
								"0_9": {
									"rs": 1,
									"cs": 4,
								},
								"0_13": {
									"rs": 1,
									"cs": 6,
								},
								"1_0": {
									"rs": 1,
									"cs": 6,
								},
								"1_13": {
									"rs": 1,
									"cs": 6,
								},
								"2_0": {
									"rs": 1,
									"cs": 6,
								},
								"2_13": {
									"rs": 1,
									"cs": 6,
								},
							},
							"rowlen": {}
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
						"column": 21,
						"row": 36,
						"celldata": [{
								r: 0,
								c: 6,
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
								c: 9,
								v: {
									"ht": "0",
									"vt": "0",
									"bl": 1,
									"bg": "#fce5cd",
									"v": "KELUAR",
									"fs": "14"
								}
							},
							{
								r: 10,
								c: 12,
								v: "value2"
							},
							{
								r: 10,
								c: 11,
								v: {
									f: "=sum",
									v: "100"
								}
							}
							/* , {
													"r": 0,
													"c": 0,
													"v": {
														"ct": {
															"fa": "General",
															"t": "g"
														},
														"v": "Kode Barang",
														"m": "Kode Barang",
														"mc": {
															"r": 0,
															"c": 0,
															"rs": 2,
															"cs": 1
														},
														"ht": "0",
														"vt": "0",
														"bl": 1,
														"bg": "#fce5cd"
													}
												}, {
													"r": 0,
													"c": 1,
													"v": {
														"ct": {
															"fa": "General",
															"t": "g"
														},
														"v": "Row2",
														"m": "Row2",
														"mc": {
															"r": 0,
															"c": 1,
															"rs": 1,
															"cs": 2
														},
														"ht": "0",
														"bl": 1,
														"bg": "#fce5cd"
													}
												}, {
													"r": 0,
													"c": 2,
													"v": {
														"mc": {
															"r": 0,
															"c": 1
														},
														"ht": "0",
														"bl": 1,
														"bg": "#fce5cd"
													}
												}, {
													"r": 0,
													"c": 3,
													"v": {
														"ct": {
															"fa": "General",
															"t": "g"
														},
														"v": "Row4",
														"m": "Row4",
														"mc": {
															"r": 0,
															"c": 3,
															"rs": 2,
															"cs": 2
														},
														"vt": "0",
														"ht": "0",
														"bl": 1,
														"bg": "#fce5cd"
													}
												}, {
													"r": 0,
													"c": 4,
													"v": {
														"mc": {
															"r": 0,
															"c": 3
														},
														"vt": "0",
														"ht": "0",
														"bl": 1,
														"bg": "#fce5cd"
													}
												}, {
													"r": 0,
													"c": 5,
													"v": {
														"ct": {
															"fa": "General",
															"t": "g"
														},
														"v": "Row6",
														"m": "Row6",
														"bl": 1,
														"bg": "#fce5cd"
													}
												}, {
													"r": 1,
													"c": 0,
													"v": {
														"mc": {
															"r": 0,
															"c": 0
														},
														"ht": "0",
														"vt": "0",
														"bl": 1,
														"bg": "#fce5cd"
													}
												}, {
													"r": 1,
													"c": 1,
													"v": {
														"ct": {
															"fa": "General",
															"t": "g"
														},
														"v": "Row3",
														"m": "Row3",
														"mc": {
															"r": 1,
															"c": 1,
															"rs": 1,
															"cs": 2
														},
														"ht": "0",
														"bl": 1,
														"bg": "#fce5cd"
													}
												}, {
													"r": 1,
													"c": 2,
													"v": {
														"mc": {
															"r": 1,
															"c": 1
														},
														"ht": "0",
														"bl": 1,
														"bg": "#fce5cd"
													}
												}, {
													"r": 1,
													"c": 3,
													"v": {
														"mc": {
															"r": 0,
															"c": 3
														},
														"vt": "0",
														"ht": "0",
														"bl": 1,
														"bg": "#fce5cd"
													}
												}, {
													"r": 1,
													"c": 4,
													"v": {
														"mc": {
															"r": 0,
															"c": 3
														},
														"vt": "0",
														"ht": "0",
														"bl": 1,
														"bg": "#fce5cd"
													}
												}, {
													"r": 1,
													"c": 5,
													"v": {
														"ct": {
															"fa": "General",
															"t": "g"
														},
														"v": "Row7",
														"m": "Row7",
														"bl": 1,
														"bg": "#fce5cd"
													}
												}, {
													"r": 2,
													"c": 0,
													"v": {
														"ct": {
															"fa": "General",
															"t": "g"
														},
														"v": "Row3",
														"m": "Row3"
													}
												}, {
													"r": 2,
													"c": 1,
													"v": {
														"ct": {
															"fa": "General",
															"t": "g"
														},
														"v": "Row4",
														"m": "Row4"
													}
												}, {
													"r": 2,
													"c": 2,
													"v": {
														"ct": {
															"fa": "General",
															"t": "g"
														},
														"v": "Row5",
														"m": "Row5"
													}
												}, {
													"r": 2,
													"c": 3,
													"v": {
														"ct": {
															"fa": "General",
															"t": "g"
														},
														"v": "Row6",
														"m": "Row6"
													}
												}, {
													"r": 2,
													"c": 4,
													"v": {
														"ct": {
															"fa": "General",
															"t": "g"
														},
														"v": "Row7",
														"m": "Row7"
													}
												}, {
													"r": 2,
													"c": 5,
													"v": {
														"ct": {
															"fa": "General",
															"t": "g"
														},
														"v": "Row8",
														"m": "Row8"
													}
												}, {
													"r": 3,
													"c": 0,
													"v": {
														"ct": {
															"fa": "General",
															"t": "g"
														},
														"v": "Row4",
														"m": "Row4"
													}
												}, {
													"r": 3,
													"c": 1,
													"v": {
														"ct": {
															"fa": "General",
															"t": "g"
														},
														"v": "Row5",
														"m": "Row5"
													}
												}, {
													"r": 3,
													"c": 2,
													"v": {
														"ct": {
															"fa": "General",
															"t": "g"
														},
														"v": "Row6",
														"m": "Row6"
													}
												}, {
													"r": 3,
													"c": 3,
													"v": {
														"ct": {
															"fa": "General",
															"t": "g"
														},
														"v": "Row7",
														"m": "Row7"
													}
												}, {
													"r": 3,
													"c": 4,
													"v": {
														"ct": {
															"fa": "General",
															"t": "g"
														},
														"v": "Row8",
														"m": "Row8"
													}
												}, {
													"r": 3,
													"c": 5,
													"v": {
														"ct": {
															"fa": "General",
															"t": "g"
														},
														"v": "Row9",
														"m": "Row9"
													}
												} */
						],
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
</script>

<body>

	<div class="w3-container ">
		<h2>Laporan Mutasi</h2>
		<!-- <p>Tabs are perfect for single page web applications, or for web pages capable of displaying different subjects. Click on the links below.</p> -->
	</div>

	<div class="w3-bar w3-black">
		<button class="w3-bar-item w3-button" onclick="openCity('London')">Unit Packing</button>
		<button class="w3-bar-item w3-button" onclick="openCity('Paris')">Gudang Jadi</button>
		<button class="w3-bar-item w3-button" onclick="openCity('Tokyo')">WIP</button>
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

	<div id="Paris" class="w3-container city" style="display:none">
		<h2>Paris</h2>
		<p>Paris is the capital of France.</p>
	</div>

	<div id="Tokyo" class="w3-container city" style="display:none">
		<h2>Tokyo</h2>
		<p>Tokyo is the capital of Japan.</p>
	</div>

	<script>
		function openCity(cityName) {
			var i;
			var x = document.getElementsByClassName("city");
			for (i = 0; i < x.length; i++) {
				x[i].style.display = "none";
			}
			document.getElementById(cityName).style.display = "block";
		}
	</script>

</body>

</html>