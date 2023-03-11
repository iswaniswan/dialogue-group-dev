<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i></i> <?= $title_list; ?>
                <?php if (check_role($this->i_menu, 1)) { ?>
                    <a href="#" onclick="show('<?= $folder; ?>/cform/index2/<?= $dfrom; ?>/<?= $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-plus"></i> &nbsp;<?= $title; ?></a>
                    <a href="#" onclick="show('<?= $folder; ?>/cform/overbudget/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-plus"></i> &nbsp;<?= $title; ?> Over Budget</a>
                <?php } ?>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
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
                </form>
                <table id="tabledata" class="display nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th width="3%;">No</th>
                            <th>No. Dokumen</th>
                            <th>Periode</th>
                            <th>Over Budget</th>
                            <th>Bagian Pembuat</th>
                            <th>Total Qty <br>FC Dist</th>
                            <th>Total Qty <br>Budget</th>
                            <th>Keterangan</th>
                            <th>Status Dokumen</th>
                            <th>Action</th>
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
    $(document).ready(function() {
        showCalendar2('.date', null);
        datatablemod('#tabledata', base_url + '<?= $folder; ?>/Cform/data/<?= $dfrom . '/' . $dto; ?>');
    });

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

    function _datatable(table, link) {
        $(table).DataTable({
            serverSide: false,
            autoWidth: false,
            processing: true,
            ajax: link,
            // dom: "Bfrtip",
            // buttons: ["copy", "csv", "excel", "pdf", "print"],
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
                zeroRecords: "Tidak ditemukan data yang sesuai",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                }
            }
        });
    }
</script>