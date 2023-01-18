<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?><a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a></div>
            <div class="panel-body table-responsive">
                <table id="tabledata" class="display nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>No Nota</th>
                            <th>Tgl Nota</th>
                            <th>Area</th>
                            <th>Customer</th>
                            <th>Disc</th>
                            <th>Nilai</th>
                            <th>Sisa</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot align="right">
	                      <tr>
                          <th colspan="5" style="text-align: right;">Grand Total </th>
                          <th style="text-align: right;"><?= number_format($total->nilai);?></th>
                          <th style="text-align: right;"><?= number_format($total->sisa);?></th><th></th>
                          </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
  var table = $('#tabledata').DataTable({
    "footerCallback": function(row, data, start, end, display) {
      var api = this.api(),
        data;
      // converting to interger to find total
      var intVal = function(i) {
        return typeof i === 'string' ?
          i.replace(/[\$,]/g, '') * 1 :
          typeof i === 'number' ?
          i : 0;
      };
      var JumlahTotal = api
        .column(5)
        .data()
        .reduce(function(a, b) {
          return intVal(a) + intVal(b);
        }, 0);
      var sisaTotal = api
        .column(6)
        .data()
        .reduce(function(a, b) {
          return intVal(a) + intVal(b);
        }, 0);
      $(api.column(4).footer()).html('Total');
      $(api.column(5).footer()).html(JumlahTotal.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));
      $(api.column(6).footer()).html(sisaTotal.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));
    },
            "language": {
                "decimal": ",",
                "thousands": "."
            },
            
            serverSide: true,
            processing: true,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "columnDefs": [{
                "targets": [5,6],
                "className": "text-right",
            }],
            "ajax": {
                "url": base_url + "<?php echo $folder; ?>/Cform/data/<?php echo $dfrom;?>/<?php echo $dto;?>/<?php echo $iarea;?>",
                "type": "POST"
            },
            "displayLength": 10
        } );
    });

    
</script>
