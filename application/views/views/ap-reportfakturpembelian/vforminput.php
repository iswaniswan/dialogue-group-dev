<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-info">
      <div class="panel-heading">
        <i class="fa fa-plus"></i> &nbsp; <?= $title; ?>
        <a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
          class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?= $title_list; ?> </a>
      </div>
      <div class="panel-body table-responsive">
        <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div class="col-md-6">
                  <div id="pesan"></div>
                  <?php if($data){
                        ?>
                  <div class="form-group row">
                    <!--- <label class="col-md-6">No SJ</label> -->
                    <label class="col-md-4">Supplier</label>
                    <label class="col-md-4">Tanggal Faktur</label>
                    <label class="col-md-4">Tanggal Faktur Supplier</label>
                    <div class="col-sm-4">
                        <input type="hidden" name="isjaja" id="isjaja" class="form-control" value="" readonly>
                        <input type="hidden" name="isupplier" class="form-control" value="<?= $data->i_supplier;?>" readonly>
                        <input type="text" name="isupplierfake" class="form-control" value="<?= $data->e_supplier_name;?>"
                        readonly>
                        <input type="hidden" name="fsupplierpkp" id="fsupplierpkp" class="form-control"
                        value="<?= $data->f_supplier_pkp;?>" readonly>
                    </div>
                    <div class="col-sm-4">
                        <input type="text" name="dnota" id="dnota" class="form-control date" value="<?php echo date("d-m-Y"); ?>"
                        readonly="" onchange="max_tgl(this.value);">
                    </div>
                     <div class="col-sm-4">
                        <input type="text" name="dfsupp" id="dfsupp" class="form-control date" value="<?php echo date("d-m-Y"); ?>"
                        readonly="" >
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-md-8">Jenis Pembelian</label>
                    <label class="col-md-4">Diskon %</label>
                    <div class="col-sm-4">
                      <!-- <input type="hidden" name="ipaymenttype" class="form-control" value="<?= $data->i_payment_type;?>" readonly> -->
                      <select name="ipaymenttype" class="form-control select2" readonly>
                        <option value="0" <?php if($data->i_jenis_pembelian =='0') { ?> selected <?php } ?>>Cash</option>
                        <option value="1" <?php if($data->i_jenis_pembelian =='1') { ?> selected <?php } ?>>Kredit</option>
                      </select>
                    </div>
                    <div class="col-sm-4">
                    </div>
                    <div class="col-sm-4">
                        <input type="text" name="vdiskon" id="vdiskon" class="form-control" value="0" maxlength="3"
                        onkeypress="return angka(event)" onkeyup="hitungdiskon()">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-8">Keterangan</label>
                    <div class="col-sm-8">
                        <input type="text" name="eremark" id="eremark" class="form-control" value="">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-5">
                      <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return validasi();"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                      <button type="button" id="send" class="btn btn-success btn-rounded btn-sm" onclick="return getenabledsend();"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div id="pesan"></div>
                  <div class="form-group row">
                    <label class="col-md-4">Tanggal Terima Faktur</label>
                    <label class="col-md-4">No Pajak</label>
                    <label class="col-md-4">Tanggal Pajak</label>
                    <div class="col-sm-4">
                        <input type="text" name="dreceivefaktur" id="dreceivefaktur" class="form-control date"
                        value="<?php echo date("d-m-Y"); ?>" readonly="" >
                    </div>
                    <div class="col-sm-4">
                        <input type="text" name="ipajak" id="ipajak" class="form-control" value="">
                    </div>
                    <div class="col-sm-4">
                        <input type="text" name="dpajak" id="dpajak" class="form-control date" value="<?php echo date("d-m-Y"); ?>"
                        readonly>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-md-4">Jumlah Diskon Reguler</label>
                    <label class="col-md-4">Nilai Total DPP</label>
                    <label class="col-md-4">Nilai Total PPN</label>
                    <div class="col-sm-4">
                        <input type="text" name="vtotaldis" id="vtotaldis" class="form-control" value="0" readonly>
                        <input type="hidden" name="diskonsup" id="diskonsup" class="form-control" value="<?= $data->v_diskon;?>" readonly>
                    </div>
                    <div class="col-sm-4">
                        <input type="text" name="vtotaldpp" id="vtotaldpp" class="form-control" value="0" readonly>
                    </div>
                    <div class="col-sm-4">
                        <input type="text" name="vtotalppn" id="vtotalppn" class="form-control" value="0" readonly>
                    </div>                    
                  </div>
                  <div class="form-group row">
                    <label class="col-md-4">Jumlah Nilai Bruto</label>
                    <label class="col-md-4">Jumlah Nilai Netto</label>
                    <label class="col-md-4">Jumlah Total</label>
                    <div class="col-sm-4">
                       <input type="text" name="vtotalbruto" id="vtotalbruto" class="form-control" value="0" readonly>
                    </div>
                    <div class="col-sm-4">
                        <input type="text" name="vtotalnet" id="vtotalnet" class="form-control" value="0" readonly>
                    </div>
                    <div class="col-sm-4">
                       <input type="text" name="vtotalfa" id="vtotalfa" class="form-control" value="0" readonly>
                    </div>
                  </div>
                </div>
                <div class="col-md-12">
                <?php
                    }else{                           
                            $read = "disabled";
                            echo "<table class=\"table table-striped bottom\" style=\"width:100%;\"><tr><td colspan=\"6\" style=\"text-align:center;\">Maaf Tidak Ada Data!</td></tr></table>";
                    }?>
                </div>
                <div class="panel-body table-responsive">
                  <table class="table table-bordered" id="tabledata" width="100%;" cellspacing="0">
                    <thead>
                      <tr>
                        <th>No</th>
                        <!--  <th>No OP</th> -->
                        <th>No BTB</th>
                        <!-- <th>No SJ</th> -->
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Qty Eks</th>
                        <th>Satuan Eks</th>
                        <th>Qty In</th>
                        <th>Satuan In</th>
                        <th>Harga</th>
                        <th>DPP</th>
                        <th>PPN</th>
                        <th>Jumlah Total (Rp.)</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                            if($data1){
                            $i = 0;
                            foreach($data1 as $row){
                                $i++;
                       ?>
                      <tr>
                        <td class="col-sm-1">
                          <?php echo $i;?>
                        </td>
                        <td class="col-sm-1">
                            <input style="width:170px" class="form-control" type="hidden" id="iop<?=$i;?>" name="iop<?=$i;?>" value="<?= $row->i_op; ?>" readonly>
                            <input style="width:170px" class="form-control" type="text" id="ibtb<?=$i;?>" name="ibtb<?=$i;?>" value="<?= $row->i_btb; ?>" readonly>
                            <input style="width:150px" class="form-control" type="hidden" id="isj<?=$i;?>" name="isj<?=$i;?>" value="<?= $row->i_sj; ?>" readonly>
                            <input style="width:100px" type="hidden" id="dsj<?=$i;?>" name="dsj<?=$i;?>" value="<?= $row->d_sj; ?>" readonly>
                        </td>
                        <td class="col-sm-1">
                            <input style="width:100px" class="form-control" type="text" id="imaterial<?=$i;?>" name="imaterial<?=$i;?>" value="<?= $row->i_material; ?>" readonly>
                        </td>
                        <td class="col-sm-1">
                            <input style="width:300px" class="form-control" type="text" id="ematerial<?=$i;?>" name="ematerial<?=$i;?>" value="<?= $row->e_material_name; ?>" readonly>
                            <input style="width:100px" class="form-control" type="hidden" id="isupplier<?=$i;?>" name="isupplier<?=$i;?>" value="<?= $row->i_supplier; ?>" readonly>
                        </td>
                        <td class="col-sm-1">
                            <input style ="width:70px"type="text" id="nquantityeks<?=$i;?>" name="nquantityeks<?=$i;?>" value="<?=$row->n_qty_eks;?>" class="form-control" readonly>
                        </td>
                        <td class="col-sm-1">
                            <input style="width:100px;" type="hidden" id="isatuaneks<?=$i;?>" class="form-control" name="isatuaneks<?=$i;?>" value="<?=$row->i_satuan_eks;?>" readonly>
                            <input style="width:100px;" type="text" id="esatuaneks<?=$i;?>" class="form-control" name="esatuaneks<?=$i;?>" value="<?=$row->satuaneks;?>" readonly>
                        </td>
                        <td class="col-sm-1">
                            <input style="width:100px" class="form-control" type="text" id="nquantity<?=$i;?>" name="nquantity<?=$i;?>" value="<?php echo number_format($row->n_qty,2); ?>" readonly>
                        </td>
                        <td class="col-sm-1">
                            <input style="width:100px" class="form-control" type="hidden" id="isatuan<?=$i;?>" name="isatuan<?=$i;?>" value="<?= $row->i_satuan_code; ?>" readonly>
                            <input style="width:100px" class="form-control" type="text" id="esatuan<?=$i;?>" name="esatuan<?=$i;?>" value="<?= $row->e_satuan; ?>" readonly>
                        </td>
                        <td class="col-sm-1">
                            <input style="width:100px" class="form-control" type="text" id="vharga<?=$i;?>" name="vharga<?=$i;?>" value="<?= $row->v_price; ?>" readonly>
                        </td>
                        <td class="col-sm-1">
                            <input style="width:100px" class="form-control" type="text" id="vdpp<?=$i;?>" name="vdpp<?=$i;?>" value="" readonly>
                        </td>
                        <td class="col-sm-1">
                            <input style="width:100px" class="form-control" type="text" id="vppn<?=$i;?>" name="vppn<?=$i;?>" value="" readonly>
                        </td>
                        <td class="col-sm-1">
                            <input style="width:150px" type="hidden" id=" vtotal<?=$i;?>" name="vtotal<?=$i;?>" value="<?php echo number_format($row->v_unit_price,0); ?>" readonly>
                            <input style="width:150px" class="form-control" name="totalfake<?php echo $i; ?>" id="totalfake<?php echo $i; ?>" type="hidden" value="<?php echo number_format($row->v_unit_price,0); ?>" readonly>
                            <input style="width:150px" class="form-control" type="text" id="vtotalsem<?=$i;?>"name="vtotalsem<?=$i;?>" value="" readonly>
                        </td>
                        <td style="width:2%;">
                            <input type="checkbox" name="cek<?php echo $i; ?>" value="cek" id="cek<?php echo $i; ?>" onclick="hitungnilai(<?php echo $i ?>)">
                        </td>
                      </tr>
                      <?}
                        }else{
                            $i=0;
                            $read = "disabled";                               
                            echo "<table class=\"table table-striped bottom\" style=\"width:100%;\"><tr><td colspan=\"6\" style=\"text-align:center;\">Maaf Tidak Ada SJ!</td></tr></table>";
                        ?>
                      <button type="button" class="btn btn-inverse btn-rounded btn-sm"
                        onclick='show("<?= $folder;?>/cform/tambah","#main")'> <i
                          class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali
                      </button>
                    <?}?>
                </tbody>
                      <input type="text" name="jml" id="jml" value="<?= $i; ?>">
            </table>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
$(document).ready(function() {
  $(".select2").select2();
  //hitung();
});

$(document).ready(function () {
    $("#send").attr("disabled", true);
});

$("form").submit(function (event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
    $("#send").attr("disabled", false);
});

function getenabledsend() {
    $('#send').attr("disabled", true);
}

$(document).ready(function() {
  hitung();
  //hitungnilaipp();
});

$(document).ready(function() {
  $('.select2').select2();
  showCalendar('.date');
});

function hitung() {
  var jml = $('#jml').val();
  var tot = 0;
  var dpp = 0;
  var ppn = 0;
 //alert(jml);  
  
  for (var i = 0; i <= jml; i++) {
    var hrg = $('#vharga' + i).val();
    var qty = $('#nquantity' + i).val();
    //dpp = (100/110 * parseFloat(hrg) );
    dpp = parseFloat(qty)*parseFloat(hrg);
//alert(dpp);
    $('#vdpp' + i).val(formatcemua(dpp));
    //$('#vdpp'+i).val(formatMoney(dpp,2,',','.'));
//alert(hrg);
    ppn = (0.1 * dpp);
    //$('#vppn'+i).val(formatMoney(ppn,2,',','.'));
    $('#vppn' + i).val(formatcemua(ppn));

    tot = (parseFloat(dpp) + parseFloat(ppn));
    //$('#vtotalsem'+i).val(formatMoney(tot,2,',','.'));
    $('#vtotalsem' + i).val(formatcemua(tot));
  }
}

function hitungnilai(i) {
  var totfak = formatulang(document.getElementById('vtotalfa').value);
  var totdpp = formatulang(document.getElementById('vtotaldpp').value);
  var totppn = formatulang(document.getElementById('vtotalppn').value);
  var sj = document.getElementById('isj' + i).value;

  if (document.getElementById('cek' + i).checked == true) {
    var nilaisj = formatulang($('#vtotalsem' + i).val());
    totakhir = parseFloat(totfak) + parseFloat(nilaisj);
  } else {
    var nilaisj = formatulang($('#vtotalsem' + i).val());
    totakhir = parseFloat(totfak) - parseFloat(nilaisj);
  }

  if (document.getElementById('isjaja').value == '') {
    document.getElementById('isjaja').value = sj;
  } else {
    document.getElementById('isjaja').value = document.getElementById('isjaja').value + ';' + sj;
  }

  document.getElementById('vtotalfa').value = formatcemua(totakhir);
  // document.getElementById('vtotaldpp').value = formatcemua(totakhirdpp);
  // document.getElementById('vtotalppn').value = formatcemua(totakhirppn);
  
  //$('#vtotaldis').val(formatMoney(vdiskon,2,',','.'));
  //$('#vtotalfa').val(formatMoney(totakhir,2,',','.'));
  //$('#vtotaldpp').val(formatMoney(totakhirdpp,2,',','.'));
  //$('#vtotalppn').val(formatMoney(totakhirppn,2,',','.'));
  document.getElementById('isjaja').value = sj;

    var total = formatulang(document.getElementById('vtotalfa').value);
    var diskon = formatulang(document.getElementById('diskonsup').value);
    var diskon2 = formatulang(document.getElementById('vdiskon').value);
    
    bruto   = total*1.1;
    vdis    = diskon/100;
    vdiskon = parseFloat(bruto)*parseFloat(vdis);
    vnet    = bruto - vdiskon;
    dpp     = vnet/1.1;
    ppn     = dpp*0.1;
    vdis2   = diskon2/100;
    vdiskon2= parseFloat(vnet)*parseFloat(vdis2);
    vnetto  = vnet - vdiskon2;


    document.getElementById('vtotaldpp').value = formatcemua(dpp);
    document.getElementById('vtotalppn').value = formatcemua(ppn);
    document.getElementById('vtotalbruto').value = formatcemua(bruto);
    document.getElementById('vtotaldis').value = formatcemua(vdiskon);
    document.getElementById('vtotalnet').value = formatcemua(vnetto);

    // $('#vtotaldpp').val(formatMoney(dpp,2,',','.'));
    // $('#vtotalppn').val(formatMoney(ppn,2,',','.'));
    // $('#vtotalbruto').val(formatMoney(bruto,2,',','.'));
    // $('#vtotaldis').val(formatMoney(vdiskon,2,',','.'));
    // $('#vtotalnet').val(formatMoney(vnet,2,',','.'));
}

function hitungdiskon() {

  var vnetto = $('#vtotalnet').val();    
  var vnetto2 = $('#vtotalnet').val();    
  var vdiskon= $('#vdiskon').val();

  vdis  = vdiskon/100;

  total = parseFloat(vnetto)*parseFloat(vdis);

  totalnet= parseFloat(vnetto2)-parseFloat(total);
  //$('#vtotalnet').val(formatMoney(totalnet,2,',','.'));
  document.getElementById('vtotalnet').value = totalnet.toFixed(3);
}

function hitungnilaipp(){
    var jml = $('#jml').val();
    var pkp = $('#fsupplierpkp').val();
    var tipepajak = $('#ipaymenttype').val();
    var totop=0;
    var tot=0;
    var selisih=0;
    var dpp=0;
    var ppn=0;
    var gtotppn=0;
    var gtotop=0;
    var gtot=0;
    var gtotselisih=0;

    if(pkp='t')
    {
      for(var i=1; i<=jml; i++)
      {
        var qty = $('#nquantity'+i).val()==''?$('#nquantity'+i).val(0):qty;
        qty = $('#nquantity'+i).val() || 0;
        
        // var hrgop = formatulang($('#vpriceop'+i).val());
        
        var hrg = formatulang($('#vharga'+i).val())==''?$('#vharga'+i).val(0):hrg;
        hrg   = formatulang($('#vharga'+i).val()) || 0; 
        
        var diskon = $('#vdiskon').val()==''?$('#diskon').val(0):diskon;
        diskon = $('#vdiskon').val() || 0;

        if(tipepajak=='0')
        {
          // totop = (parseFloat(hrgop)*parseFloat(qty))-parseFloat(diskon);
          // $('#vtotalop'+i).val(formatcemua(totop));
          tot = (parseFloat(hrg)*parseFloat(qty))-parseFloat(diskon);
          $('#vtotalsem'+i).val(formatcemua(tot));
          // selisih = totop-tot;
          // $('#selisih'+i).val(formatcemua(selisih));

          var pi = tot/1.1;
          ppn = tot-pi;
          $('#vppn'+i).val(formatMoney(ppn,2,',','.'));

          gtotppn += ppn;
          // gtotop += totop;
          gtot += tot;
        } else {
          // totop = (parseFloat(hrgop)*parseFloat(qty))-parseFloat(diskon);
          // $('#vtotalop'+i).val(formatcemua(totop));
          tot = (parseFloat(hrg)*parseFloat(qty))-parseFloat(diskon);
          $('#vtotalsem'+i).val(formatcemua(tot));
          // selisih = totop-tot;
          // $('#selisih'+i).val(formatcemua(selisih));

          // pe=pajak exclude
          var pe = tot*0.1;
          $('#vppn'+i).val(formatMoney(pe,2,',','.'));
          var newtot = parseFloat(pe)+parseFloat(tot);

          // peop=pajak exclude op
          // var peop = totop*0.1;
          // var newtotop = parseFloat(peop)+parseFloat(totop);

          gtotppn += pe;
          // gtotop += newtotop;
          gtot += newtot;
        }

        // $('#grandtotop').val(formatcemua(gtotop));
        $('#vtotalfa').val(formatcemua(gtot));
        $('#vtotalppn').val(formatMoney(gtotppn,2,',','.'));
        dpp = gtot/1.1;
        $('#vtotaldpp').val(formatMoney(dpp,2,',','.'));
        // gtotselisih = gtotop-gtot;
        // $('#grandselisih').val(formatcemua(gtotselisih));
      }
    } else {
      for(var i=1; i<=jml; i++)
      {
        var qty = $('#nquantity'+i).val()==''?$('#nquantity'+i).val(0):qty;
        qty = $('#nquantity'+i).val() || 0;
        
        // var hrgop = formatulang($('#vpriceop'+i).val());

        var hrg = formatulang($('#vharga'+i).val())==''?$('#vharga'+i).val(0):hrg;
        hrg   = formatulang($('#vharga'+i).val()) || 0; 
        
        var diskon = $('#vdiskon').val()==''?$('#vdiskon').val(0):diskon;
        diskon = $('#vdiskon').val() || 0;
        $('#vppn'+i).val(0);
        // totop = (parseFloat(hrgop)*parseFloat(qty))-parseFloat(diskon);
        // $('#vtotalop'+i).val(formatcemua(totop));
        tot = (parseFloat(hrg)*parseFloat(qty))-parseFloat(diskon);
        $('#vtotalsem'+i).val(formatcemua(tot));
        // selisih = totop-tot;
        // $('#selisih'+i).val(formatcemua(selisih));

        // gtotop += totop;
        gtot += tot;
      }
      // $('#grandtotop').val(formatcemua(gtotop));
      $('#vtotalfa').val(formatcemua(gtot));
      $('#vtotalppn').val(0);
      $('#vtotaldpp').val(0);
      // gtotselisih = gtotop-gtot;
      // $('#grandselisih').val(formatcemua(gtotselisih));
    }
  }

function validasi() {
  var s = 0;
  var pkp = $('#fsupplierpkp').val();

  var textinputs = document.querySelectorAll('input[type=checkbox]');
  var empty = [].filter.call(textinputs, function(el) {
    return !el.checked
  });

  if (pkp == 't') {
    if (document.getElementById('ipajak').value == '') {
      swal("No Pajak Masih Kosong");
      return false;
    } else if (textinputs.length == empty.length) {
      swal("BTB Masih Kosong, Pilih Minimal 1 BTB!");
      return false;
    } else {
      return true;
    }
  } else {
    if (textinputs.length == empty.length) {
      swal("BTB Masih Kosong, Pilih Minimal 1 BTB!");
      return false;
    } else {
      return true;
    }
  }
}

function max_tgl(val) {
  $('#dpajak').datepicker('destroy');
  $('#dpajak').datepicker({
    autoclose: true,
    todayHighlight: true,
    format: "dd-mm-yyyy",
    todayBtn: "linked",
    daysOfWeekDisabled: [0],
    startDate: document.getElementById('dnota').value,
  });
}
$('#dpajak').datepicker({
  autoclose: true,
  todayHighlight: true,
  format: "dd-mm-yyyy",
  todayBtn: "linked",
  daysOfWeekDisabled: [0],
  startDate: document.getElementById('dnota').value,
});

$(document).ready(function(){
    $("#send").on("click", function () {
        var kode = $("#kode").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/send'); ?>",
            data: {
                     'kode'  : kode,
                    },
            dataType: 'json',
            delay: 250, 
            success: function(data) {
                return {
                results: data
                };
            },
             cache: true
        });
    });
});
</script>