<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-info">
      <div class="panel-heading"> <i class="fa fa-list"></i> <?=$title;?> Periode : <?=$periode;?>
        <a href="#" onclick="show('<?=$folder;?>/cform','#main'); return false;"
          class="btn btn-info btn-sm pull-right"><i class="fa fa-arrow-circle-o-left"></i> &nbsp;<?="Kembali";?></a>
      </div>
      <div class="panel-body table-responsive">
        <div id="pesan"></div>
        <table class="table table-bordered color-bordered-table info-bordered-table" id="sitabel">
          <thead>
            <tr>
              <th align="center">No</th>
              <th align="center">Kode Supplier</th>
              <th align="center">Nama Supplier</th>
              <th align="center">Sld Awal</th>
              <th align="center">DPP</th>
              <th align="center">PPN</th>
              <th align="center">Debet</th>
              <th align="center">Kredit</th>
              <th align="center">Sld Akhir</th>
            </tr>
          </thead>
          <tbody>
            <?php
$i = 0;
$jumsaldoawal = 0;
$jumdpp = 0;
$jumppn = 0;
$jumdebet = 0;
$jumkredit = 0;
$jumsaldoakhir = 0;
if ($isi) {
    foreach ($isi->result() as $row) {
        $i++;
        $jumsaldoawal = $jumsaldoawal + $row->v_saldo_awal;
        $jumdpp = $jumdpp + $row->dpp;
        $jumppn = $jumppn + $row->ppn;
        $jumdebet = $jumdebet + $row->v_debet;
        $jumkredit = $jumkredit + $row->v_kredit;
        $jumsaldoakhir = $jumsaldoakhir + $row->v_saldo_akhir;

        echo "<tr>
            <td align='center'>$i</td>
            <td>$row->i_supplier</td>
            <td>$row->e_supplier_name</td>
            <td align='right'>" . number_format($row->v_saldo_awal) . "</td>
            <td align='right'>" . number_format($row->dpp) . "</td>
            <td align='right'>" . number_format($row->ppn) . "</td>
            <td align='right'>" . number_format($row->v_debet) . "</td>
            <td align='right'>" . number_format($row->v_kredit) . "</td>
            <td align='right'>" . number_format($row->v_saldo_akhir) . "</td>
            </tr>";
    }
}
?>
            <tr>
              <td colspan='3' align="center">TOTAL</td>
              <td align="right"><?php echo number_format($jumsaldoawal); ?></td>
              <td align="right"><?php echo number_format($jumdpp); ?></td>
              <td align="right"><?php echo number_format($jumppn); ?></td>
              <td align="right"><?php echo number_format($jumdebet); ?></td>
              <td align="right"><?php echo number_format($jumkredit); ?></td>
              <td align="right"><?php echo number_format($jumsaldoakhir); ?></td>
            </tr>
          </tbody>
        </table>
        <button type="button" name="cmdreset" id="cmdreset" class="btn btn-inverse btn-rounded btn-sm"> <i
            class="fa fa-download"></i>&nbsp;&nbsp;Export</button>
      </div>
    </div>
    </form>
  </div>
</div>
<script type="text/javascript">
$("#cmdreset").click(function() {
  var Contents = $('#sitabel').html();
  window.open('data:application/vnd.ms-excel, ' + '<table>' + encodeURIComponent($('#sitabel').html()) +
    '</table>');
});
</script>