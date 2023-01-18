<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/approve'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                 <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="card">
  <div class="card-header">
    <H3>Detail HPP</H3>
  </div>
  <div class="card-body">
    <div id="pesan"></div>
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label class="col-sm-12 col-form-label">Nomor HPP</label>
          <div class="col-sm-12">
            <input type="text" class="form-control" placeholder="012/HPPKDC2/DGU/03/2020" name="i_hpp" id="i_hpp"
              value="<?=$data->i_hpp;?>" readonly>
            <input type="hidden" class="form-control" name="id" id="id" value="<?=$data->id;?>">
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label class="col-sm-12 col-form-label">Redaksi</label>
          <div class="col-sm-12">
            <input type="text" class="form-control" readonly name="i_redaksi" id="i_redaksi"
              value="<?=$data->i_redaksi;?>">
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label class="col-sm-12 col-form-label">Tanggal HPP</label>
          <div class="col-sm-12">
            <input type="text" class="form-control datepicker" placeholder="dd/mm/yyyy" name="d_hpp"
              value="<?=$data->d_hpp;?>" readonly>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label class="col-sm-12 col-form-label">Kode Barang</label>
          <div class="col-sm-12">
            <input type="text" class="form-control" readonly name="i_product" id="i_product"
              value="<?=$data->i_product;?>">
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label class="col-sm-12 col-form-label">Motif</label>
          <div class="col-sm-12">
            <input type="hidden" class="form-control" readonly name="i_motif" id="i_motif" value="<?=$data->i_motif;?>">
            <input type="text" class="form-control" readonly name="e_motif" id="e_motif" value="<?=$data->e_motif;?>">
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label class="col-sm-12 col-form-label">Biaya Operasional</label>
          <div class="col-sm-12">
            <select name="ioperasional_harga" id="i_operasional_harga" class="form-control" readonly onchange="get_operasionalbiayadetail(this.value);">
              <option selected="" style="display: none;" value="">-- Please Select --</option>
              <?php foreach ($operasi->result() as $rowx) {
    $hargaoperasi = $rowx->i_operasional_harga;
}
if ($data_operasional_harga) {
    foreach ($data_operasional_harga->result() as $row) {?>
              <option <?php if ($hargaoperasi == $row->i_operasional_harga) {
        echo "selected";
    }
        ?> value="<?=$row->i_operasional_harga;?>"><?=$row->i_operasional_harga;?> -
                <?=$row->d_operasional_harga;?></option>

              <?php }
}?>
            </select>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label class="col-sm-12 col-form-label">Biaya Ekspedisi</label>
          <div class="col-sm-12">
            <input type="text" class="form-control" placeholder="Biaya Ekspedisi" name="v_biaya_ekspedisi"
              value="<?=number_format($data->v_biaya_ekspedisi);?>" onkeyup="return total_hpp_operasional(this.value);" readonly>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label class="col-sm-12 col-form-label">Overhead</label>
          <div class="col-sm-12">
            <input type="text" class="form-control" placeholder="Overhead" name="v_overhead" id="v_overhead"
              value="<?=number_format($data->v_overhead);?>" readonly>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label class="col-sm-12 col-form-label">Total</label>
          <div class="col-sm-12">
            <input type="text" class="form-control" name="v_hpp" id="v_hpp" readonly
              value="<?=number_format($data->v_hpp - $data->v_overhead);?>">
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label class="col-sm-12 col-form-label">HPP</label>
          <div class="col-sm-12">
            <input type="text" class="form-control" name="v_hpp1" id="v_hpp1" readonly value="<?=number_format($data->v_hpp);?>">
            <?php
foreach ($countbahanbaku->result() as $bb) {
    $jmlbb = $bb->jmlbb;
}
foreach ($countjahit->result() as $jht) {
    $jmljht = $jht->jmljht;
}
foreach ($countpacking->result() as $pc) {
    $jmlpc = $pc->jmlpc;
}
?>
            <input type='hidden' class='form-control' name='jmlbb' id='jmlbb' value='<?=$jmlbb;?>' readonly>
            <input type='hidden' class='form-control' name='jmljht' id='jmljht' value='<?=$jmljht;?>' readonly>
            <input type='hidden' class='form-control' name='jmlpc' id='jmlpc' value='<?=$jmlpc;?>' readonly>
          </div>
        </div>
      </div>
      <?php
if ($data->f_acc2 == 't') {
    ?>
      <div class="col-md-2">
        <label class="col-sm-12 col-form-label">Margin 1</label>
        <div class="row col-sm-12">
          <label class="col-sm-4 col-form-label">%</label>
          <input type="number" class="form-control col-sm-6 col-form-label" name="n_hjp1" id="n_hjp1"
            value="<?=$data->n_hjp1;?>" max="100" readonly>
        </div>
        <div class="row col-sm-12">
          <label class="col-sm-4 col-form-label">Ex</label>
          <input type="number" class="form-control col-sm-8" name="v_hjpex1" id="v_hjpex1" value="<?=$data->v_hjpex1;?>"
            readonly>
        </div>
        <div class="row col-sm-12">
          <label class="col-sm-4 col-form-label">In</label>
          <input type="number" class="form-control col-sm-8" name="v_hjpin1" id="v_hjpin1" value="<?=$data->v_hjpin1;?>"
            readonly>
        </div>
      </div>
      <div class="col-md-2">
        <label class="col-sm-12 col-form-label">Margin 2</label>
        <div class="row col-sm-12">
          <label class="col-sm-4 col-form-label">%</label>
          <input type="number" class="form-control col-sm-6 col-form-label" name="n_hjp2" id="n_hjp2"
            value="<?=$data->n_hjp2;?>" max="100" readonly>
        </div>
        <div class="row col-sm-12">
          <label class="col-sm-4 col-form-label">Ex</label>
          <input type="number" class="form-control col-sm-8" name="v_hjpex2" id="v_hjpex2" value="<?=$data->v_hjpex2;?>"
            readonly>
        </div>
        <div class="row col-sm-12">
          <label class="col-sm-4 col-form-label">In</label>
          <input type="number" class="form-control col-sm-8" name="v_hjpin2" id="v_hjpin2" value="<?=$data->v_hjpin2;?>"
            readonly>
        </div>
      </div>
      <div class="col-md-2">
        <label class="col-sm-12 col-form-label">Margin 3</label>
        <div class="row col-sm-12">
          <label class="col-sm-4 col-form-label">%</label>
          <input type="number" class="form-control col-sm-6 col-form-label" name="n_hjp3" id="n_hjp3"
            value="<?=$data->n_hjp3;?>" max="100" readonly>
        </div>
        <div class="row col-sm-12">
          <label class="col-sm-4 col-form-label">Ex</label>
          <input type="number" class="form-control col-sm-8" name="v_hjpex3" id="v_hjpex3" value="<?=$data->v_hjpex3;?>"
            readonly>
        </div>
        <div class="row col-sm-12">
          <label class="col-sm-4 col-form-label">In</label>
          <input type="number" class="form-control col-sm-8" name="v_hjpin3" id="v_hjpin3" value="<?=$data->v_hjpin3;?>"
            readonly>
        </div>
      </div>
      <div class="col-md-2">
        <label class="col-sm-12 col-form-label">Margin 4</label>
        <div class="row col-sm-12">
          <label class="col-sm-4 col-form-label">%</label>
          <input type="number" class="form-control col-sm-6 col-form-label" name="n_hjp4" id="n_hjp4"
            value="<?=$data->n_hjp4;?>" max="100" readonly>
        </div>
        <div class="row col-sm-12">
          <label class="col-sm-4 col-form-label">Ex</label>
          <input type="number" class="form-control col-sm-8" name="v_hjpex4" id="v_hjpex4" value="<?=$data->v_hjpex4;?>"
            readonly>
        </div>
        <div class="row col-sm-12">
          <label class="col-sm-4 col-form-label">In</label>
          <input type="number" class="form-control col-sm-8" name="v_hjpin4" id="v_hjpin4" value="<?=$data->v_hjpin4;?>"
            readonly>
        </div>
      </div>
      <div class="col-md-2">
        <label class="col-sm-12 col-form-label">Margin 5</label>
        <div class="row col-sm-12">
          <label class="col-sm-4 col-form-label">%</label>
          <input type="number" class="form-control col-sm-6 col-form-label" name="n_hjp5" id="n_hjp5"
            value="<?=$data->n_hjp5;?>" max="100" readonly>
        </div>
        <div class="row col-sm-12">
          <label class="col-sm-4 col-form-label">Ex</label>
          <input type="number" class="form-control col-sm-8" name="v_hjpex5" id="v_hjpex5" value="<?=$data->v_hjpex5;?>"
            readonly>
        </div>
        <div class="row col-sm-12">
          <label class="col-sm-4 col-form-label">In</label>
          <input type="number" class="form-control col-sm-8" name="v_hjpin5" id="v_hjpin5" value="<?=$data->v_hjpin5;?>"
            readonly>
        </div>
      </div>
      <div class="col-md-2">
        <label class="col-sm-12 col-form-label">Margin 6</label>
        <div class="row col-sm-12">
          <label class="col-sm-4 col-form-label">%</label>
          <input type="number" class="form-control col-sm-6 col-form-label" name="n_hjp6" id="n_hjp6"
            value="<?=$data->n_hjp6;?>" max="100" readonly>
        </div>
        <div class="row col-sm-12">
          <label class="col-sm-4 col-form-label">Ex</label>
          <input type="number" class="form-control col-sm-8" name="v_hjpex6" id="v_hjpex6" value="<?=$data->v_hjpex6;?>"
            readonly>
        </div>
        <div class="row col-sm-12">
          <label class="col-sm-4 col-form-label">In</label>
          <input type="number" class="form-control col-sm-8" name="v_hjpin6" id="v_hjpin6" value="<?=$data->v_hjpin6;?>"
            readonly>
        </div>
      </div>
      <?php
}
?>
    </div>

    <div class="box-header">
      <h4 class="box-title text-muted">Pemakaian Bahan Baku</h4>
      <!--<button id="addbahanbaku" type="button" class="btn btn-secondary btn-sm" style="float: right;">
      <span class="fa fa-plus"></span> Add Bahan Baku</button>-->
    </div>
    <div style="width: 100%" class="table-responsive">
      <table class="table table-bordered table-striped table-hover" id="tblbahanbaku">
        <thead>
          <tr align="center">
            <th>No.</th>
            <th>Nama Part</th>
            <th>Bahan Baku</th>
            <th>Warna</th>
            <th>Panjang</th>
            <th>Lebar</th>
            <th>Qty</th>
            <th>Harga</th>
            <th>Sub Total</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
$itembb = 0;
$totalbb = 0;

foreach ($bahanbaku->result() as $rbhn) {
    $xx = $this->db->query("
                          SELECT * FROM dblink('host=$url_db port=$port user=$user_postgre password=$password_postgre dbname=$db_name',
                              $$ select e_bahanbaku from tr_bahanbaku where i_bahanbaku='$rbhn->i_bahan_baku' $$) AS tm_hpp (e_bahanbaku varchar(255));
        ");
    foreach ($xx->result() as $xbhn) {
        $nmbhn = $xbhn->e_bahanbaku;
    }
    $itembb++;
    echo "<tr>
                      <td>" . $itembb . "</td>
                      <td><input type=text class=form-control numeric' name='bbitem[]' id='bbitem" . $itembb . "'
                           value='" . $rbhn->i_item . "' readonly></td>
                      <td><input type=text class=form-control numeric'  value='" . $nmbhn . "' readonly>
                      <input type=hidden class=form-control numeric' name='bbbhn[]' id='bbbhn" . $itembb . "' value='" . $rbhn->i_bahan_baku . "' readonly></td>
                      <td><input type='text' class='form-control' name='bb_e_color[]' id='bb_e_color" . $itembb . "' value='" . $rbhn->e_color . "' readonly>
                          <input type='hidden' class='form-control' name='bb_i_color[]' id='bb_i_color" . $itembb . "' value='" . $rbhn->i_color . "' readonly>
                          <input type='hidden' class='form-control' name='bb_i_uom[]' id='bb_i_uom" . $itembb . "' value='" . $rbhn->i_uom . "' readonly>
                          <input type='hidden' class='form-control' name='bb_i_bahanbaku_harga[]' id='bb_i_bahanbaku_harga" . $itembb . "'
                                 value='" . $rbhn->i_bahanbaku_harga . "' readonly></td>
                      <td><input type=text class=form-control numeric' name='bbpanjang[]' id='bbpanjang" . $itembb . "' value='" . number_format($rbhn->n_panjang_pemakaian, 2) . "' onkeyup='return hitung_bbsubtotal(" . $itembb . ");' readonly></td>
                      <td><input type='text' class='form-control numeric' name='bblebar[]' id='bblebar" . $itembb . "' value='" . number_format($rbhn->n_lebar_pemakaian, 2) . "'
                          onkeyup='return hitung_bbsubtotal(" . $itembb . ");' readonly></td>
                      <td><input type='text' class='form-control numeric' name='bbqty[]' id='bbqty" . $itembb . "' value='" . number_format($rbhn->n_jumlah_pemakaian, 2) . "'
                          onkeyup='return hitung_bbsubtotal(" . $itembb . ");' readonly></td>
                      <td><input type='text' class='form-control' name='bbharga[]' id='bbharga" . $itembb . "' value='" . number_format($rbhn->v_uom_harga, 3) . "'readonly>
                          <input type='hidden' class='form-control' name='bbpanjanghidden[]' id='bbpanjanghidden" . $itembb . "' value='" . $rbhn->n_panjang . "'readonly>
                          <input type='hidden' class='form-control' name='bblebarhidden[]' id='bblebarhidden" . $itembb . "' value='" . $rbhn->n_lebar . "'readonly></td>
                      <td><input type='text' class='form-control' name='bbsubtotal[]' id='bbsubtotal" . $itembb . "' value='" . $rbhn->v_pemakaian . "' readonly></td>
                      <td><!--<input type='button' class='ibtnDel btn btn-md btn-danger' value='Delete'>--></td>
                      </tr>";
    $totalbb = $totalbb + $rbhn->v_pemakaian;
}
// echo '<tr>
//   <td colspan=8 align=right>Total Bahan Baku</td>
//   <td><input type="text" class="form-control" required value="'.number_format($totalbb,2).'" name="bbtotal" id="bbtotal" readonly>
//       <input type="hidden" class="form-control" value="'.$itembb.'" id="jml_bb" name="jml_bb"></td>
// </tr>';

?>
        </tbody>
      </table>
      <table class="table table-bordered table-striped table-hover">
        <tr>
          <td colspan=8 align=right>Total Bahan Baku</td>
          <td><input type="text" class="form-control" required value="<?=number_format($totalbb);?>" name="bbtotal"
              id="bbtotal" readonly>
            <input type="hidden" class="form-control" value="<?=$itembb;?>" id="jml_bb" name="jml_bb"></td>
        </tr>
      </table>
    </div>
    <hr>
    <div class="box-header">
      <h4 class="box-title text-muted">Pemakaian Aksesoris Jahit</h4>
      <!--<<button id="addaksesorisjahit" type="button" class="btn btn-secondary btn-sm" style="float: right;"><span
          class="fa fa-plus"></span> Add Aksesoris Jahit</button>-->
    </div>
    <div style="width: 100%" class="table-responsive">
      <table class="table table-bordered table-striped table-hover" id="tblaksesorisjahit">
        <thead>
          <tr align="center">
            <th>No.</th>
            <th>Aksesoris</th>
            <th>Jenis</th>
            <th>Quantity</th>
            <th>Harga</th>
            <th>Harga Satuan</th>
            <th>Sub Total</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
$itemjht = 0;
$totaljahit = 0;
foreach ($jahit->result() as $rjht) {
    $xx = $this->db->query("
                          SELECT * FROM dblink('host=$url_db port=$port user=$user_postgre password=$password_postgre dbname=$db_name',
                              $$ select e_aksesorisjahit from tr_aksesorisjahit where i_aksesorisjahit='$rjht->i_aksesorisjahit' $$) AS tm_hpp (e_aksesorisjahit varchar(255));
        ");

    foreach ($xx->result() as $xjht) {
        $nmjht = $xjht->e_aksesorisjahit;
        $yy = $this->db->query("
                          SELECT * FROM dblink('host=$url_db port=$port user=$user_postgre password=$password_postgre dbname=$db_name',
                            $$ select e_aksesorisjahit_jenis from tr_aksesorisjahit_jenis
                                where i_aksesorisjahit='$rjht->i_aksesorisjahit' and i_aksesorisjahit_jenis='$rjht->i_aksesorisjahit_jenis'
                            $$) AS tm_hpp (e_aksesorisjahit_jenis varchar(255));
        ");
        foreach ($yy->result() as $yjht) {
            $nmjns = $yjht->e_aksesorisjahit_jenis;
        }
    }
    $itemjht++;
    echo "<tr>
                      <td>" . $itemjht . "</td>";
/*
<td><input type=text class=form-control numeric' name='aksjahititem[]' id='aksjahititem".$itembb."'
value='".$rjht->i_item . " - " . $rjht->e_remark."'></td>
 */
    echo " <td><input type=text class=form-control numeric' name='i_aksesorisjahit[]' id='i_aksesorisjahit" . $itemjht . "'
                           value='" . $nmjht . "' readonly></td>
                      <td><input type=text class=form-control numeric' name='i_aksesorisjahit_jenis[]' id='i_aksesorisjahit_jenis" . $itemjht . "'
                           value='" . $nmjns . "' readonly></td>
                      <td><input type='text' class='form-control numeric' name='aksjahitqty[]' id='aksjahitqty" . $itemjht . "' value='" . number_format($rjht->n_jumlah_pemakaian, 2) . "'
                           onkeyup='return hitung_aksjahitsubtotal(" . $itemjht . ");' readonly></td>
                      <td><input type='text' class='form-control numeric' name='aksjahitharga[]' id='aksjahitharga" . $itemjht . "' value='" . number_format($rjht->v_harga, 3) . "'
                           onkeyup='return hitung_aksjahitsubtotal(" . $itemjht . ");' readonly></td>
                      <td><input type='text' class='form-control numeric' name='aksjahithargapcs[]' id='aksjahithargapcs" . $itemjht . "' value='" . number_format($rjht->v_uom_harga/$rjht->n_jumlah_uom, 3) . "'
                           onkeyup='return hitung_aksjahitsubtotal(" . $itemjht . ");' readonly></td>
                      <td><input type='text' class='form-control' name='aksjahitsubtotal[]' id='aksjahitsubtotal" . $itemjht . "'
                           value ='" . $rjht->v_pemakaian . "' readonly>
                          <input type='hidden' class='form-control' name='aksjahitpanjanghidden[]' id='aksjahitpanjanghidden" . $itemjht . "'
                           value='" . $rjht->v_uom_harga . "' readonly>
                          <input type='hidden' class='form-control' name='aksjahitlebarhidden[]' id='aksjahitlebarhidden" . $itemjht . "'
                           value='" . $rjht->v_uom_harga . "' readonly>
                          <input type='hidden' class='form-control' name='i_aksesorisjahit_i_uom[]' id='i_aksesorisjahit_i_uom" . $itemjht . "'
                           value='" . $rjht->i_uom . "' readonly>
                          <input type='hidden' class='form-control' name='i_aksesorisjahit_harga[]' id='i_aksesorisjahit_harga" . $itemjht . "'
                           value='" . $rjht->i_aksesorisjahit_harga . "' readonly></td>
                           <td><!--<<input type='button' class='ibtnDel btn btn-md btn-danger' value='Delete'>--></td>
                      </tr>";
    $totaljahit = $totaljahit + ($rjht->v_pemakaian);
}
// echo '<tr>
//   <td colspan=6 align=right>Total Jahit</td>
//   <td><input type="text" class="form-control" required value="'.number_format($totaljahit,2).'" name="jahittotal" id="jahittotal" readonly>
//       <input type="hidden" class="form-control" value="'.$itemjht.'" id="jml_jahit" name="jml_jahit"></td>
// </tr>';

?>
        </tbody>
      </table>
      <table class="table table-bordered table-striped table-hover">
        <tr>
          <td colspan=6 align=right>Total Jahit</td>
          <td><input type="text" class="form-control" required value="<?=number_format($totaljahit);?>"
              name="jahittotal" id="jahittotal" readonly>
            <input type="hidden" class="form-control" value="<?=$itemjht;?>" id="jml_jahit" name="jml_jahit"></td>
        </tr>
      </table>
    </div>
    <hr>
    <div class="box-header">
      <h4 class="box-title text-muted">Pemakaian Aksesoris Packing</h4>
      <!--<<button id="addaksesorispacking" type="button" class="btn btn-secondary btn-sm" style="float: right;"><span
          class="fa fa-plus"></span> Add Aksesoris Packing</button>-->
    </div>
    <div style="width: 100%" class="table-responsive">
      <table class="table table-bordered table-striped table-hover" id="tblaksesorispacking">
        <thead>
          <tr align="center">
            <th>No.</th>
            <th>Nama Aksesoris Packing</th>
            <th>Quantity</th>
            <th>Harga</th>
            <th>Harga Satuan</th>
            <th>Sub Total</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody id="addPAP">
          <?php
$itempac = 0;
$totalpacking = 0;
foreach ($packing->result() as $rpac) {
    $itempac++;
    echo "<tr>
                      <td>" . $itempac . "</td>";
    echo "  <td><input type='text' class='form-control numeric'
                           value='" . $rpac->e_aksesorispacking . "'
                           onkeyup='return hitung_akspackingsubtotal(" . $itempac . ");' readonly>
                           <input type='hidden' class='form-control numeric' name='akspackingitem[]' id='akspackingitem" . $itempac . "'
                           value='" . $rpac->i_item . "'
                           onkeyup='return hitung_akspackingsubtotal(" . $itempac . ");' readonly></td>";
    echo "  <td><input type='text' class='form-control numeric' name='akspackingqty[]' id='akspackingqty" . $itempac . "' value='" . number_format($rpac->n_jumlah_pemakaian, 2) . "'
                           onkeyup='return hitung_akspackingsubtotal(" . $itempac . ");' readonly></td>
                      <td><input type='text' class='form-control numeric' name='akspackingharga[]' id='akspackingharga" . $itempac . "'
                           value='" . number_format($rpac->v_uom_harga, 2) . "' readonly></td>
                      <td><input type='text' class='form-control numeric' name='akspackinghargapcs[]' id='akspackinghargapcs" . $itempac . "'
                           value='" . number_format($rpac->v_uom_harga/$rpac->n_jumlah_uom, 2) . "' readonly></td>
                      <td><input type='text' class='form-control numeric' name='akspackingsubtotal[]' id='akspackingsubtotal" . $itempac . "'
                           value='" . $rpac->v_pemakaian . "' readonly>
                          <input type='hidden' class='form-control numeric' name='akspackingi_uom[]' id='akspackingi_uom" . $itempac . "' value='" . $rpac->i_uom . "'>
                          <input type='hidden' class='form-control numeric' name='akspackingi_aksesorispacking_harga[]'
                           id='akspackingi_aksesorispacking_harga" . $itempac . "' value='" . $rpac->i_aksesorispacking_harga . "' readonly><input type='hidden' class='form-control numeric' name='akspackingn_jumlah_uom[]'
                           id='akspackingn_jumlah_uom" . $itempac . "' value='" . $rpac->n_jumlah_uom . "' readonly></td>
                           <td><!--<<input type='button' class='ibtnDel btn btn-md btn-danger' value='Delete'>--></td>
                      </tr>
                    ";
    $totalpacking = $totalpacking + ($rpac->v_pemakaian);
}

?>
        </tbody>
      </table>
      <table class="table table-bordered table-striped table-hover">
        <tr>
          <td colspan=5 align=right>Total Packing</td>
          <td><input type="text" class="form-control" required value="<?=number_format($totalpacking);?>"
              name="packingtotal" id="packingtotal" readonly>
            <input type="hidden" class="form-control" value="<?=$itempac;?>" id="jml_packing" name="jml_packing"></td>
        </tr>
      </table>

    </div>

    <hr>
    <div class="box-header">
      <h4 class="box-title text-muted">Biaya Jasa</h4>
    </div>
    <div style="width: 100%" class="table-responsive">
      <table class="table table-bordered table-striped table-hover">
        <thead>
          <tr align="center">
            <th>No.</th>
            <th>Jasa</th>
            <th>Harga</th>
          </tr>
        </thead>
        <tbody>
          <?php
$no = 0;
if ($data_jasa) {
    $totaljasa = 0;
    foreach ($data_jasa->result() as $row) {$no++;?>
          <tr>
            <td><?=$no;?></td>
            <td><?=$row->e_jasa;?></td>
            <td> <input type="hidden" class="form-control" value="<?=$row->i_item;?>" name="i_jasa[]">
              <input type="text" class="form-control" value="<?=number_format($row->v_jasa, 3);?>" required
                name="jasaharga[]" id="jasaharga<?=$no;?>" onkeyup="return hitung_jasasubtotal();">
            </td>
          </tr>
          <?php
$totaljasa = $totaljasa + $row->v_jasa;
    }?>
          <tr>
            <td colspan=2 align=right>Total Jasa</td>
            <td><input type="text" class="form-control" required value="<?=number_format($totaljasa);?>"
                name="jasatotal" id="jasatotal" readonly>
              <input type="hidden" class="form-control" value="<?=$no;?>" id="jml_jasa" name="jml_jasa"></td>
          </tr>
          <?php
}
?>
        </tbody>
      </table>
    </div>

    <hr>
    <div class="box-header">
      <h4 class="box-title text-muted">Biaya Operasional</h4>

    </div>
    <div style="width: 100%" class="table-responsive">
      <table class="table table-bordered table-striped table-hover">
        <thead>
          <tr align="center">
            <th>No.</th>
            <th>Operasional</th>
            <th>Durasi (Menit)</th>
            <th>Harga Per Menit</th>
            <th>Sub Total</th>
          </tr>
        </thead>
        <tbody>
          <?php
$no = 0;
if ($data_operasional) {
    $totaloper = 0;
    foreach ($data_operasional->result() as $row) {$no++;?>
          <tr>
            <td><?=$no;?></td>
            <td><?=$row->e_operasional;?></td>
            <td> <input type="hidden" class="form-control" value="<?=$row->i_operasional;?>" name="i_operasional[]">
              <input type="text" class="form-control" value="<?=number_format($row->n_jumlah_pemakaian, 2);?>" required
                name="operasionalqty[]" id="operasionalqty<?=$no;?>"
                onkeyup="return hitung_operasionalsubtotal('operasionalqty<?=$no;?>','operasionalsubtotal<?=$no;?>');"
                readonly>
            </td>
            <td><input type="text" class="form-control" required value="<?=number_format($row->v_menit, 3);?>"
                name="operasionalharga[]" id="operasionalharga<?=$no;?>" readonly>
            </td>
            <td><input type="text" class="form-control" required value="<?=$row->v_pemakaian;?>"
                name="operasionalsubtotal[]" id="operasionalsubtotal<?=$no;?>" readonly>
            </td>
          </tr>
          <?php
$totaloper = $totaloper + $row->v_pemakaian;
    }

    ?>
          <tr>
            <td colspan=4 align=right>Total Operasional</td>
            <td><input type="text" class="form-control" required value="<?=number_format($totaloper);?>"
                name="operasionaltotal" id="operasionaltotal" readonly>
              <input type="hidden" class="form-control" value="<?=$no;?>" id="jml_operasional" name="jml_operasional">
            </td>
          </tr>

          <?php
}
?>
        </tbody>
      </table>
    </div>
    <input type="hidden" class="form-control" value="<?=$no;?>" name="jmloperasional">
    <input type="hidden" class="form-control" value="" id="hargapermenit">
    <hr>
  </div>
</div>

                    <div class="form-group row">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</form>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
    function approve() {
        statuschange('<?= $folder."','".$id;?>','6','<?= $dfrom."','".$dto;?>');   
    }
</script>