<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?>
            <?php if(check_role($this->i_menu, 2)){ ?>
                <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;" class="btn btn-info btn-sm pull-right">
                    <i class="fa fa-list"></i> &nbsp;list <?= $title; ?>
                </a>
            <?php } ?>
        </div>            
        <div class="panel-body table-responsive">
            <?= $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/indexx'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
            <div class="col-md-4">
                <div class="form-group row">
                    <label class="col-md-5">Date From</label><label class="col-md-5">Date To</label>
                    <div class="col-sm-5">
                        <input class="form-control input-sm date" readonly="" type="text" name="dfrom" id="dfrom" value="<?= $dfrom;?>">
                    </div>
                    <div class="col-sm-5">
                        <input class="form-control input-sm date" readonly="" type="text" name="dto" id="dto" value="<?= $dto;?>">
                    </div>
                    <div class="col-sm-2">
                        <button type="submit" id="submit" class="btn btn-info btn-sm"><i class="fa fa-search"></i>&nbsp;&nbsp;Cari</button>
                    </div>
                </div>
            </div>
        </form>
        <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/prosesdata'), 'update' => '#main', 'type' => 'post', 'id' => 'formclose', 'class' => 'form-horizontal')); ?>
        <table id="tabledata" class="display nowrap" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th width="3%;">No</th>
                    <th>No. Dokumen</th>
                    <th>Tgl. Dokumen</th>
                    <th>Gudang</th>
                    <th>Supplier</th>
                    <th>Keterangan</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>                
            </tbody>
            <!-- <tfoot>
                <th></th>
                <th>No. Dokumen</th>
                <th>Tgl. Dokumen</th>
                <th>Gudang</th>
                <th>Supplier</th>
                <th>Keterangan</th>
                <th>Action</th>
            </tfoot> -->
        </table>
        <div class="form-group row">
            <div class="col-sm-12 text-center">
                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Proses</button>
                &nbsp;&nbsp;
                <label class="custom-control custom-checkbox">
                    <input type="checkbox" id="checkAll" name="checkAll" class="custom-control-input">
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description">Check All</span>
                </label>
            </div>
        </div>
    </form>
    <div class="col-md-12">
        <div class="form-group">
            <span class="notekode"><b>N O T E : </b></span><br>
            <span class="notekode">* Data Yang Dipilih Harus Supplier Yang Sama!</span><br>
            <span class="notekode">* Untuk Yang Berbeda Halaman, Silahkan Rubah <b>"Tampilkan Jumlah Data"</b> Yang Dimunculkan!</span>
        </div>
    </div>
</div>
</div>
</div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        showCalendar2('.date',null,0);
        datatablemod('#tabledata', base_url + '<?= $folder; ?>/Cform/datareferensi/<?= $dfrom.'/'.$dto;?>');
    });

    $( "#dfrom" ).change(function() {
        var dfrom   = splitdate($(this).val());
        var dto     = splitdate($('#dto').val());
        if (dfrom!=null&& dto!=null) {
            if (dfrom>dto) {
                swal('Tanggal Mulai Tidak Boleh Lebih Besar Dari Tanggal Sampai!!!');
                $('#dfrom').val('');
            }
        }
    });

    $( "#dto" ).change(function() {
        var dto   = splitdate($(this).val());
        var dfrom = splitdate($('#dfrom').val());
        if (dfrom!=null && dto!=null) {   
            if (dfrom>dto) {
                swal('Tanggal Sampai Tidak Boleh Lebih Kecil Dari Tanggal Mulai!!!');
                $('#dto').val('');
            }
        }
    });

    $("#checkAll").click(function(){
        $('input:checkbox').not(this).prop('checked', this.checked);
    });

    /*var t = $('#tabledata').DataTable( {
        serverSide: true,
        processing: true,
        "columnDefs": [{ 
            "targets": [0,6], 
            "className": "text-center",
        }],
        lengthMenu: [[10, 25, 50, 100, -1],[10, 25, 50, 100, "All"],],
        "ajax": {
            "url": "<?= site_url($folder); ?>/Cform/datareferensi/<?= $dfrom."/".$dto;?>",
            "type": "POST"
        },
        "order": [[ 1, 'asc' ]],
        initComplete: function () {
            this.api().columns().every( function () {
                var column = this;
                var select = $('<select class="select2"><option value="">All</option></select>')
                .appendTo( $(column.footer()).empty() )
                .on( 'change', function () {
                    var val = $.fn.dataTable.util.escapeRegex(
                        $(this).val()
                        );

                    column
                    .search(val, true, false )
                    .draw();
                } );

                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                } );
            } );
            $('.select2').select2();
        }
    } );
    t.on('draw.dt', function () {
        var info = t.page.info();
        t.column(0, { search: 'applied', order: 'applied', page: 'applied' }).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1 + info.start;
        });
    });
    $('div.dataTables_filter input', t.table().container()).focus();*/
                    /*.search( val ? '^'+val+'$' : '', true, false )*/
</script>