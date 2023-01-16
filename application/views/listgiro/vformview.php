<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-info">
      <div class="panel-heading"> <i class="fa fa-list"></i> <?=$title;?><a href="#"
          onclick="show('<?=$folder;?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i
            class="fa fa-list"></i></a></div>
      <div class="panel-body table-responsive">
        <table id="tabledata" class="display nowrap" cellspacing="0" width="100%">
          <thead>
            <tr>
              <th>No Giro</th>
              <th>Area</th>
              <th>Tgl Giro</th>
              <th>No RV</th>
              <th>Tgl RV</th>
              <th>Tgl Jth Tempo</th>
              <th>No DT</th>
              <th>Tgl DT</th>
              <th>Customer</th>
              <th>Bank</th>
              <th>Jumlah</th>
              <th>Sisa</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
          <tfoot align="right">
            <tr>
              <th colspan="10" style="text-align: center;">Sub Total </th>
              <th></th>
              <th></th>
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
        .column(10)
        .data()
        .reduce(function(a, b) {
          return intVal(a) + intVal(b);
        }, 0);
      var sisaTotal = api
        .column(11)
        .data()
        .reduce(function(a, b) {
          return intVal(a) + intVal(b);
        }, 0);
      $(api.column(9).footer()).html('Sub Total');
      $(api.column(10).footer()).html(JumlahTotal.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));
      $(api.column(11).footer()).html(sisaTotal.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'));
    },
    "language": {
      "decimal": ",",
      "thousands": "."
    },

    serverSide: true,
    processing: true,
    dom: "lBfrtip",
    buttons: ["copy", "csv", "excel", "pdf", "print"],
    "lengthMenu": [
      [10, 25, 50, -1],
      [10, 25, 50, "All"]
    ],
    "columnDefs": [{
      "targets": [10, 11],
      "className": "text-right",
    }],
    "ajax": {
      "url": base_url +
        "<?php echo $folder; ?>/Cform/data/<?php echo $dfrom; ?>/<?php echo $dto; ?>/<?php echo $iarea; ?>",
      "type": "POST"
    },
    "displayLength": 10
  });
});
</script>