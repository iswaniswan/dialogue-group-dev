<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?><a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-spin fa-refresh"></i> &nbsp;Refresh</a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/index'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                    <div class="col-md-10">
                        <div class="form-group row">
                            <label class="col-md-2">Date From</label><label class="col-md-2">Date To</label><label class="col-md-8">Supplier</label>
                            <div class="col-sm-2">
                                <input class="form-control input-sm date" readonly="" type="text" name="dfrom" id="dfrom" value="<?= $dfrom;?>">
                            </div>
                            <div class="col-sm-2">
                                <input class="form-control input-sm date" readonly="" type="text" name="dto" id="dto" value="<?= $dto;?>" >
                            </div>
                            <div class="col-sm-3">
                                <select name="supplier" id="supplier" class="form-control select2">
                                    <option value="SP" selected>Semua Supplier</option>
                                    <?php foreach ($ceksup as $isupplier):?>
                                        <?php if ($isupplier->i_supplier == $supplier) { ?>
                                            <option value="<?= $isupplier->i_supplier;?>" selected><?= $isupplier->e_supplier_name;?></option>
                                        <?php }else { ?>
                                            <option value="<?= $isupplier->i_supplier;?>"><?= $isupplier->e_supplier_name;?></option>
                                        <?php }?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <button type="submit" id="submit" class="btn btn-sm btn-info"> <i class="fa fa-search"></i>&nbsp;&nbsp;Cari</button>
                            </div>
                        </div>
                    </div>
                </form>
                <table class="display nowrap" cellspacing="0" width="100%" id="tabledata">
                    <thead>
                        <tr>
                            <th class="text-center" width="2%;">No</th>
                            <th>No. Nota</th>
                            <th>Tgl. Nota</th>
                            <th>No. Pajak</th>
                            <th>Tgl. Pajak</th>
                            <th>Supplier</th>
                            <th class="text-right">Nilai DPP</th>
                            <th class="text-right">Nilai PPN</th>
                            <th class="text-right">Nilai Total</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <th colspan="6" class="text-right">TOTAL :</th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tfoot>
                </table>
                <br>
                <a id="href"><button type="button" class="btn btn-inverse btn-rounded btn-sm"><i class="fa fa-file-text-o"></i>&nbsp;&nbsp;Export CSV</button></a>
            </div>
        </div>
    </div>
</div>
<script>
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

    $(document).ready(function () {
        $(".select2").select2();
        showCalendar('.date',null,0);
        var t = $('#tabledata').DataTable( {
            serverSide: true,
            processing: true,
            "columnDefs": [ {
                "searchable": false,
                "orderable": false,
                "targets": [6, 7, 8], 
                "className": "text-right",
            }, 
            { "targets": [0], 
            "className": "text-center",
        }],

        lengthMenu: [[10, 25, 50, 100, -1],[10, 25, 50, 100, "All"],],
        "ajax": {
            "url": "<?= site_url($folder); ?>/Cform/data/<?= $supplier."/".$dfrom."/".$dto;?>",
            "type": "POST"
        },
        "order": [[ 1, 'asc' ]],

        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;

            /*converting to interger to find total*/
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                i.replace(/[\$,]/g, '')*1 :
                typeof i === 'number' ?
                i : 0;
            };  

            /*computing column Total of the complete result */
            var ppn = api
            .column( 7 )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );

            var total = api
            .column( 8 )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );

            var dpp = api
            .column( 6 )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );
            
            /*Update footer by showing the total with the reference of the column index */
            $( api.column( 0 ).footer() ).html('TOTAL');
            $( api.column( 6 ).footer() ).html(formatcemua(parseFloat(dpp).toFixed(2)));
            $( api.column( 7 ).footer() ).html(formatcemua(parseFloat(ppn).toFixed(2)));
            $( api.column( 8 ).footer() ).html(formatcemua(parseFloat(total).toFixed(2)));
        },
    });
        t.on('draw.dt', function () {
            var info = t.page.info();
            t.column(0, { search: 'applied', order: 'applied', page: 'applied' }).nodes().each(function (cell, i) {
                cell.innerHTML = i + 1 + info.start;
            });
        });
    });

    $('#href').click(function(event) {
        /* Act on the event */
        var supplier = $('#supplier').val();
        var dfrom = $('#dfrom').val();
        var dto = $('#dto').val();
        if (dfrom != '' && dto != '' && supplier != '') {
            $('#href').attr('href','<?= site_url($folder.'/cform/exportcsv/');?>'+supplier+'/'+dfrom+'/'+dto);
        }else{
            swal('Maaf :(','Tanggal dan Supplier tidak boleh kosong!','error');
            return false;
        }
    });
</script>