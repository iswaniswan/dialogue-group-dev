<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-info">
      <div class="panel-heading"> <i class="fa fa-list"></i> <?=$title;?></div>
      <div class="panel-body table-responsive">
        <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
              <div class="col-md-4">
                  <div class="form-group row">
                      <label class="col-md-5">Date From</label><label class="col-md-5">Date To</label>
                      <div class="col-sm-5">
                          <input class="form-control date" readonly="" type="text" name="dfrom" id="dfrom" value="<?= $dfrom;?>">
                      </div>
                      <div class="col-sm-5">
                          <input class="form-control date" readonly="" type="text" name="dto" id="dto" value="<?= $dto;?>">
                      </div>
                      <div class="col-sm-2">
                          <button type="submit" id="submit" class="btn btn-info"> <i class="fa fa-search"></i>&nbsp;&nbsp;Cari</button>
                      </div>
                  </div>
              </div>
          </form>

        <table id="tabledata" class="display nowrap" cellspacing="0" width="100%">
          <thead>
            <tr>
                <th>No</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Saldo Awal</th>
                <th>Masuk</th>
                <th>Masuk Makloon</th>
                <th>Keluar</th>
                <th>Keluar Makloon</th>
                <th>Saldo Akhir</th>
                <th>SO</th>
                <th>Selisih</th> 
            </tr>
          </thead>
          <tbody>
          </tbody>
          <tfoot>
                <tr>
                    <th colspan="3" style="text-align:right"></th>
                    <th style="text-align:right"></th>
                    <th style="text-align:right"></th>            
                    <th style="text-align:right"></th>
                    <th style="text-align:right"></th>
                    <th style="text-align:right"></th>
                    <th style="text-align:right"></th>
                    <th style="text-align:right"></th>
                    <th style="text-align:right"></th>
                </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
    $(document).ready(function () {
        showCalendar2('.date',1835,30);
        //datatable('#tabledata', base_url + '<?= $folder; ?>/Cform/data/<?= $dfrom."/".$dto; ?>');
        
        var t = $('#tabledata').DataTable( {
            serverSide: true,
            processing: true,
            "columnDefs": [ {
                "searchable": false,
                "orderable": false,
                "targets": [3, 4, 5, 6, 7, 8, 9, 10], 
                "className": "text-right",
            }, 
            { "targets": [0], 
                 "className": "text-center",
            }
            ],

            "lengthMenu": [[-1, 10, 25, 50], ["All", 10, 25, 50]],
            
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel'/*, 'pdf', 'print'*/
            ],
            
            "ajax": {
                    "url": "<?= site_url($folder); ?>/Cform/data/<?= $dfrom."/".$dto;?>",
                    "type": "POST"
            },
            //"displayLength": 5,
            "order": [[ 1, 'asc' ]],

            "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // converting to interger to find total
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // computing column Total of the complete result 
            var sawal = api
                .column( 3 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            var masuk = api
                .column( 4 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            var masukmakloon = api
                .column( 5 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );    
            
            var keluar = api
                .column( 6 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            var keluarmakloon = api
                .column( 7 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            var sahir = api
                .column( 8 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
				
            var so = api
                .column( 9 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            var selisih = api
                .column( 10 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            var sahirx = (sawal+(masuk+masukmakloon))-(keluar+keluarmakloon);
            // Update footer by showing the total with the reference of the column index 
                $( api.column( 0 ).footer() ).html('Total');
                $( api.column( 3 ).footer() ).html(formatcemua(sawal));
                $( api.column( 4 ).footer() ).html(formatcemua(masuk));
                $( api.column( 5 ).footer() ).html(formatcemua([masukmakloon]));
                $( api.column( 6 ).footer() ).html(formatcemua([keluar]));
                $( api.column( 7 ).footer() ).html(formatcemua([keluarmakloon]));
                $( api.column( 8 ).footer() ).html(formatcemua([sahirx]));
                $( api.column( 9 ).footer() ).html(formatcemua([so]));
                //$( api.column( 10 ).footer() ).html(formatcemua([selisih]));
            },
        } );
    
        t.on( 'order.dt search.dt', function () {
            t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();
        
    });
</script>