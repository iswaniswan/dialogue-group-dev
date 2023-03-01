<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?>
                <?php  if(check_role($this->i_menu, 1)){ ?><a href="#" onclick="show('<?= $folder; ?>/cform/tambah/<?= $year . '/' . $month ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i
                        class="fa fa-plus"></i> &nbsp;<?= $title; ?></a>
                <?php } ?>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                    <div class="">
                        <div class="form-group row">
                            <label class="col-md-5">Periode</label><label class="col-md-7"></label>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <select name="year" id="year" class="form-control select2 input-sm">
                                        <?php $year_start = date('Y')-3; 
                                            $year_end = date('Y');                                            
                                            
                                            for($i=$year_start; $i<=$year_end; $i++){ 
                                                $selected = ($i == $year) ? 'selected' : ''; ?>
                                                <option value='<?= $i ?>' <?= $selected ?>><?= $i ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select name="month" id="month" class="form-control select2 input-sm">
                                    <option value='01' <?php if($month=='01'){?> selected <?php } ?>>Januari</option>
                                    <option value='02' <?php if($month=='02'){?> selected <?php } ?>>Pebruari</option>
                                    <option value='03' <?php if($month=='03'){?> selected <?php } ?>>Maret</option>
                                    <option value='04' <?php if($month=='04'){?> selected <?php } ?>>April</option>
                                    <option value='05' <?php if($month=='05'){?> selected <?php } ?>>Mei</option>
                                    <option value='06' <?php if($month=='06'){?> selected <?php } ?>>Juni</option>
                                    <option value='07' <?php if($month=='07'){?> selected <?php } ?>>Juli</option>
                                    <option value='08' <?php if($month=='08'){?> selected <?php } ?>>Agustus</option>
                                    <option value='09' <?php if($month=='09'){?> selected <?php } ?>>September</option>
                                    <option value='10' <?php if($month=='10'){?> selected <?php } ?>>Oktober</option>
                                    <option value='11' <?php if($month=='11'){?> selected <?php } ?>>November</option>
                                    <option value='12' <?php if($month=='12'){?> selected <?php } ?>>Desember</option>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <button type="submit" id="submit" class="btn btn-sm btn-info"> <i
                                        class="fa fa-search"></i>&nbsp;&nbsp;Cari</button>
                            </div>
                        </div>
                    </div>
                </form>

                <table id="tabledata" class="display nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th style="width: 35px;">No</th>
                            <!-- <th>Area</th> -->
                            <!-- <th>Customer</th> -->
                            <th>Salesman</th>
                            <!-- <th>Brand</th> -->
                            <th style="width: 100px;">Status</th>
                            <th style="width: 100px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function get_datatable(table, link) {
        var t = $(table).DataTable({
            serverSide: true,
            autoWidth: false,
            processing: true,
            ajax: link,
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "All"]
            ],
            columnDefs: [
                {
                    searchable: false,
                    orderable: false,
                    targets: [0],
                    className: "text-center"
                }
            ],
            /*dom: "lBfrtip",
                        buttons: ["copy", "csv", "excel", "pdf", "print"],*/
            language: {
                decimal: "",
                emptyTable: "Tidak ada data yang tersedia pada tabel ini",
                info: "Menampilkan _START_ Sampai _END_ Dari _TOTAL_ data",
                infoEmpty: "Menampilkan 0 Sampai 0 Dari 0 Data",
                infoFiltered: "(disaring dari _MAX_ data keseluruhan)",
                infoPostFix: "",
                thousands: ".",
                lengthMenu: "<span>Tampilkan</span> _MENU_ Data",
                loadingRecords: "Sedang memproses...",
                processing: "Sedang memproses...",
                search: "Cari:",
                searchPlaceholder: "Cari Data",
                zeroRecords: "Tidak ditemukan data yang sesuai",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                }
            }
            /* bStateSave: true,
                fnStateSave: function(oSettings, oData) {
                    localStorage.setItem("offersDataTables", JSON.stringify(oData));
                },
                fnStateLoad: function(oSettings) {
                    return JSON.parse(localStorage.getItem("offersDataTables"));
                }, */
        });
        t.on("draw.dt", function () {
            var info = t.page.info();
            t.column(0, {
                search: "applied",
                order: "applied",
                page: "applied"
            })
                .nodes()
                .each(function (cell, i) {
                    cell.innerHTML = i + 1 + info.start;
                });
        });
        $("div.dataTables_filter input", t.table().container()).focus();
        /*t.on( 'order.dt search.dt', function () {
                t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                } );
            }).draw();*/
    }

    $(document).ready(function () {
        get_datatable('#tabledata', base_url + '<?= $folder; ?>/Cform/data/<?= $year . '/' . $month ?>');

        $('.select2').select2();
    });
</script>