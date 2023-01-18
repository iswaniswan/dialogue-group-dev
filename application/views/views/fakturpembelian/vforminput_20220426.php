<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?=$title_list;?></a>
            </div>
            <div class="panel-body table-responsive">
            <div id="pesan"></div>
            <div class="col-md-12">
                <?php if($data){
                ?>
                <div class="form-group row">
                    <label class="col-md-3">Bagian Pembuat</label>
                    <label class="col-md-3">Nomor Dokumen</label>
                    <label class="col-md-2">Tanggal Dokumen</label>
                    <label class="col-md-4">Supplier</label>
                    <div class="col-sm-3">
                        <select name="ibagian" id="ibagian" class="form-control select2" required="">
                            <?php if ($bagian) {
                                foreach ($bagian as $row):?>
                                    <option value="<?= $row->i_bagian;?>">
                                        <?= $row->e_bagian_name;?>
                                    </option>
                                <?php endforeach; 
                            } ?>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <div class="input-group">
                            <input type="text" name="inota" id="inota" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="FP-2010-000001" maxlength="15" class="form-control input-sm" value="<?= $number;?>" aria-label="Text input with dropdown button">
                            <span class="input-group-addon">
                                <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                            </span>
                        </div>
                        <span class="notekode">Format : (<?= $number;?>)</span><br>
                        <span class="notekode" hidden="true"><b> * No. Sudah Ada!</b></span>
                    </div> 
                        <div class="col-sm-2">
                            <input type="text" name="dnota" id="dnota" class="form-control input-sm date" value="<?php echo date("d-m-Y"); ?>" readonly="" onchange="max_tgl(this.value);">
                    </div>
                    <div class="col-sm-4">
                        <input type="hidden" name="isjaja" id="isjaja" class="form-control input-sm" value="" readonly>
                        <input type="hidden" name="isupplier" class="form-control input-sm" value="<?= $data->i_supplier;?>" readonly>
                        <input type="text" name="isuppliername" class="form-control input-sm" value="<?= $data->e_supplier_name;?>" readonly>
                        <input type="hidden" name="fsupplierpkp" id="fsupplierpkp" class="form-control input-sm" value="<?= $data->f_pkp;?>" readonly>
                        <input type="hidden" name="ntop" id="ntop" class="form-control input-sm" value="<?= $data->n_top;?>" readonly>
                    </div>       
                </div>
                <div class="form-group row">
                    <label class="col-md-2">Nomor Pajak</label>
                    <label class="col-md-2">Tanggal Pajak</label>
                    <label class="col-md-2">Nomor Faktur</label>
                    <label class="col-md-2">Tgl Faktur Supplier</label>
                    <label class="col-md-2">Tgl Terima Faktur</label>  
                    <label class="col-md-2">Tgl Jatuh Tempo</label> 
                    <div class="col-sm-2">
                        <input type="text" name="ipajak" id="ipajak" class="form-control input-sm" value="">
                    </div>
                    <div class="col-sm-2">
                        <input type="text" name="dpajak" id="dpajak" class="form-control input-sm date" value=""
                        readonly >
                    </div>
                     <div class="col-sm-2">
                        <input type="text" name="ifaktur" id="ifaktur" class="form-control input-sm" value="">
                    </div>
                    <div class="col-sm-2">
                        <input type="text" name="dfsupp" id="dfsupp" class="form-control input-sm date" value="<?php echo date("d-m-Y"); ?>"
                      readonly="" onchange="return tgl_jatuhtempo();" >
                    </div>
                    <div class="col-sm-2">
                        <input type="text" name="dreceivefaktur" id="dreceivefaktur" class="form-control input-sm date" value="<?php echo date("d-m-Y"); ?>" readonly="" >
                    </div>
                    <div class="col-sm-2">
                        <input type="text" name="djatuhtempo" id="djatuhtempo" class="form-control input-sm" value="" readonly>
                        <input type="hidden" name="suptop" id="suptop" class="form-control input-sm" value="<?= $data->sup_top;?>" readonly="" >
                    </div>
                </div>
                <div class="form-group row"> 
                    <label class="col-md-1">Diskon (Rp.)</label>     
                    <label class="col-md-1">Jml Dis Reg</label>
                    <label class="col-md-2">Nilai Total DPP</label>
                    <label class="col-md-2">Nilai Total PPN</label>   
                    <label class="col-md-2">Jumlah Nilai Bruto</label>
                    <label class="col-md-4">Jumlah Nilai Netto</label>
                    <!-- <label class="col-md-2">Jumlah Total</label>  -->
                    <div class="col-sm-1"><input type="text" name="vdiskon" id="vdiskon" class="form-control input-sm" value="0" 
                        onkeyup="hitungdiskon()"></div>
                    <div class="col-sm-1">
                        <input type="text" name="vtotaldis" id="vtotaldis" class="form-control input-sm" value="0" readonly>
                        <input type="hidden" name="diskonsup" id="diskonsup" class="form-control input-sm" value="<?= $data->n_diskon;?>" readonly>
                    </div>
                    <div class="col-sm-2">
                        <input type="text" name="vtotaldpp" id="vtotaldpp" class="form-control input-sm" value="0" readonly>
                    </div>
                    <div class="col-sm-2">
                        <input type="text" name="vtotalppn" id="vtotalppn" class="form-control input-sm" value="0" readonly>
                    </div>   
                    <div class="col-sm-2">
                       <input type="text" name="vtotalbruto" id="vtotalbruto" class="form-control input-sm" value="0" readonly>
                    </div>
                    <div class="col-sm-2">
                        <input type="text" name="vtotalnet" id="vtotalnet" class="form-control input-sm" value="0" readonly>
                        <input type="hidden" name="vtotalneto" id="vtotalneto" class="form-control input-sm" value="0" readonly>
                    </div>
                    <div class="col-sm-2">
                       <input type="hidden" name="vtotalfa" id="vtotalfa" class="form-control input-sm" value="0" readonly>
                       <input type="hidden" name="ie" id="ie" class="form-control input-sm" value="" readonly>
                    </div>         
                </div>
                <div class="form-group">
                    <label class="col-md-12">Keterangan</label>
                    <div class="col-sm-12">
                        <textarea class="form-control input-sm" name="eremark" placeholder="Isi keterangan jika ada!"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-5">
                        <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return validasi();"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                        <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        <button type="button" id="send" hidden="true" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
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
        </div>
        </div>
    </div>
</div>
<div class="white-box" id="detail">
    <div class="col-sm-5">
        <h3 class="box-title m-b-0">Detail Barang</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No OP</th>
                        <th>No BTB</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <!-- <th>Qty Eks</th>
                        <th>Satuan Eks</th> -->
                        <th>Satuan In</th>
                        <th class="text-right">Qty In</th>
                        <th class="text-right">Toleransi</th>
                        <th class="text-center">Plus Toleransi</th>
                        <th class="text-right">Total Qty</th>
                        <th class="text-right">Harga</th>
                        <th class="text-right">DPP</th>
                        <th class="text-right">PPN</th>
                        <th class="text-right">Jumlah Total (Rp.)</th>
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
                        <td class="col-sm-1 text-center">
                          <?php echo $i;?>
                        </td>
                        <td class="col-sm-1">
                            <input style="width:170px" class="form-control input-sm" type="text" id="iop<?=$i;?>" name="iop<?=$i;?>" value="<?= $row->i_op; ?>" readonly>
                        </td>
                        <td class="col-sm-1">
                            <input style="width:170px" class="form-control input-sm" type="hidden" id="idpp<?=$i;?>" name="idpp<?=$i;?>" value="<?= $row->id_pp; ?>" readonly>
                            <input style="width:170px" class="form-control input-sm" type="hidden" id="idiop<?=$i;?>" name="idiop<?=$i;?>" value="<?= $row->id_op; ?>" readonly>
                            <input style="width:170px" class="form-control input-sm" type="hidden" id="idbtb<?=$i;?>" name="idbtb<?=$i;?>" value="<?= $row->id_btb; ?>" readonly>
                            <input style="width:170px" class="form-control input-sm" type="text" id="ibtb<?=$i;?>" name="ibtb<?=$i;?>" value="<?= $row->i_btb; ?>" readonly>
                            <input style="width:150px" class="form-control input-sm" type="hidden" id="isj<?=$i;?>" name="isj<?=$i;?>" value="<?= $row->i_sj_supplier; ?>" readonly>
                            <input style="width:100px" type="hidden" id="dsj<?=$i;?>" name="dsj<?=$i;?>" value="<?= $row->d_btb; ?>" readonly>
                        </td>
                        <td class="col-sm-1">
                            <input style="width:100px" class="form-control input-sm" type="text" id="imaterial<?=$i;?>" name="imaterial<?=$i;?>" value="<?= $row->i_material; ?>" readonly>
                        </td>
                        <td class="col-sm-1">
                            <input style="width:300px" class="form-control input-sm" type="text" id="ematerial<?=$i;?>" name="ematerial<?=$i;?>" value="<?= $row->e_material_name; ?>" readonly>
                            <input style="width:100px" class="form-control input-sm" type="hidden" id="isupplier<?=$i;?>" name="isupplier<?=$i;?>" value="<?= $row->i_supplier; ?>" readonly>
                        </td>
                        <td class="col-sm-1">
                            <input style="width:100px" class="form-control input-sm" type="hidden" id="isatuan<?=$i;?>" name="isatuan<?=$i;?>" value="<?= $row->i_satuan_code; ?>" readonly>
                            <input style="width:100px" class="form-control input-sm" type="text" id="esatuan<?=$i;?>" name="esatuan<?=$i;?>" value="<?= $row->e_satuan_name; ?>" readonly>
                        </td>
                        <td class="col-sm-1" hidden="true">
                            <input style ="width:70px"type="text" id="nquantityeks<?=$i;?>" name="nquantityeks<?=$i;?>" value="<?=$row->n_quantity_eks;?>" class="form-control input-sm" readonly>
                        </td>
                        <td class="col-sm-1" hidden="true">
                            <input style="width:100px;" type="hidden" id="isatuaneks<?=$i;?>" class="form-control input-sm" name="isatuaneks<?=$i;?>" value="<?=$row->i_satuan_code_eks;?>" readonly>
                            <input style="width:100px;" type="text" id="esatuaneks<?=$i;?>" class="form-control input-sm" name="esatuaneks<?=$i;?>" value="<?=$row->satuaneks;?>" readonly>
                        </td>
                        <td class="col-sm-1">
                            <input style="width:100px" class="form-control input-sm text-right" type="text" id="nquantity<?=$i;?>" name="qty<?=$i;?>" value="<?php echo $row->n_quantity; ?>" readonly>
                        </td>
                        <td class="col-sm-1">
                            <input style="width:100px" class="form-control input-sm text-right" type="text" id="toleransi<?=$i;?>" name="toleransi<?=$i;?>" value="<?php echo $row->n_toleransi; ?>" readonly>
                        </td>
                        <td class="col-sm-1 text-center">
                            <input type="checkbox" id="plus<?php echo $i; ?>" name="plus<?php echo $i; ?>" onclick="plus(<?php echo $i ?>)">
                        </td>
                        <td class="col-sm-1">
                            <input style="width:100px" class="form-control input-sm text-right" type="text" id="qty_total<?=$i;?>" name="nquantity<?=$i;?>" value="<?php echo $row->n_quantity; ?>" readonly>
                        </td>
                        <td class="col-sm-1">
                            <input style="width:100px" class="form-control input-sm text-right" type="text" id="vharga<?=$i;?>" name="vharga<?=$i;?>" value="<?= $row->v_price; ?>" readonly>
                            <input style="width:100px" class="form-control input-sm" type="hidden" id="itipe<?=$i;?>" name="itipe<?=$i;?>" value="<?= $row->f_ppn; ?>" readonly>
                        </td>
                        <td class="col-sm-1">
                            <input style="width:120px" class="form-control input-sm text-right" type="text" id="vdpp<?=$i;?>" name="vdpp<?=$i;?>" value="" readonly>
                        </td>
                        <td class="col-sm-1">
                            <input style="width:120px" class="form-control input-sm text-right" type="text" id="vppn<?=$i;?>" name="vppn<?=$i;?>" value="" readonly>
                        </td>
                        <td class="col-sm-1">
                            <input style="width:150px" type="hidden" id=" vtotal<?=$i;?>" name="vtotal<?=$i;?>" value="<?php echo number_format($row->v_price,0); ?>" readonly>
                            <input style="width:150px" class="form-control input-sm text-right" name="totalfake<?php echo $i; ?>" id="totalfake<?php echo $i; ?>" type="hidden" value="<?php echo number_format($row->v_price,0); ?>" readonly>
                            <input style="width:150px" class="form-control input-sm text-right" type="text" id="vtotalsem<?=$i;?>"name="vtotalsem<?=$i;?>" value="" readonly>
                        </td>
                        <td style="width:2%;" class="text-center">
                            <input type="checkbox" id="cek<?php echo $i; ?>" name="cek<?php echo $i; ?>" onclick="hitungnilai(<?php echo $i ?>)">
                        </td>
                    </tr>
                  <?}
                    }else{
                        $i=0;
                        $read = "disabled";                               
                        echo "<table class=\"table table-striped bottom\" style=\"width:100%;\"><tr><td colspan=\"6\" style=\"text-align:center;\">Maaf Tidak Ada BTB!</td></tr></table>";
                    ?>
                    <button type="button" class="btn btn-inverse btn-rounded btn-sm"
                        onclick='show("<?= $folder;?>/cform/tambah","#main")'> <i
                          class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali
                    </button>
                    <?}?>
                </tbody>
                <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">
            </table>
        </div>
    </div>
</div>
</from>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
    $(document).ready(function() {
      $('#inota').mask('SS-0000-000000S');
      $(".select2").select2();
      showCalendar('.date');
      hitung();
      tgl_jatuhtempo();
      fixedtable($('.table'));
    });

    $("form").submit(function (event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#send").attr("hidden", false);
    });

    $('#send').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'2','<?= $dfrom."','".$dto;?>');
    });

    $( "#inota" ).keyup(function() {
        $.ajax({
            type: "post",
            data: {
                'kode' : $(this).val(),
            },
            url: '<?= base_url($folder.'/cform/cekkode'); ?>',
            dataType: "json",
            success: function (data) {
                if (data==1) {
                    $(".notekode").attr("hidden", false);
                    $("#submit").attr("disabled", true);
                }else{
                    $(".notekode").attr("hidden", true);
                    $("#submit").attr("disabled", false);
                }
            },
            error: function () {
                swal('Error :)');
            }
        });
    });

    $('#ceklis').click(function(event) {
        if($('#ceklis').is(':checked')){
            $("#inota").attr("readonly", false);
        }else{
            $("#inota").attr("readonly", true);
            $("#inota").val("<?= $number;?>");
        }
    });

    function hitung() {
        var jml = $('#jml').val();
        var tot = 0;
        var dpp = 0;
        var ppn = 0;
         //alert(jml);    
        for (var i = 1; i <= jml; i++) {
            var hrg = parseFloat($('#vharga' + i).val());
            var qty = parseFloat($('#qty_total' + i).val());
            var tipe= $('#itipe' + i).val();

            vharga = (qty)*(hrg);

            //alert(hrg + " " + qty + " " +tipe);
            $('#ie').val(tipe);
            if(tipe == 'I'){//include
                  dpp = (parseFloat(vharga) / 1.1);
              //alert(dpp);
                  $('#vdpp' + i).val(formatcemua(Math.round(dpp)));
                  //$('#vdpp'+i).val(formatMoney(dpp,2,',','.'));
              //alert(hrg);
                  ppn = (dpp * 0.1);
                  //$('#vppn'+i).val(formatMoney(ppn,2,',','.'));
                  $('#vppn' + i).val(formatcemua(Math.round(ppn)));

                  tot = (parseFloat(dpp) + parseFloat(ppn));
                  //$('#vtotalsem'+i).val(formatMoney(tot,2,',','.'));
                  $('#vtotalsem' + i).val(formatcemua(Math.round(tot)));

            }else if(tipe == 'E'){//Exclude
                  dpp =  parseFloat(vharga);
              //alert(dpp);
                 $('#vdpp' + i).val(formatcemua(Math.round(dpp)));
                  //$('#vdpp'+i).val(formatMoney(dpp,2,',','.'));
              //alert(hrg);
                  ppn = (dpp * 0.1);
                  //$('#vppn'+i).val(formatMoney(ppn,2,',','.'));
                 $('#vppn' + i).val(formatcemua(Math.round(ppn)));

                  tot = (parseFloat(dpp) + parseFloat(ppn));
                  //$('#vtotalsem'+i).val(formatMoney(tot,2,',','.'));
                 $('#vtotalsem' + i).val(formatcemua(Math.round(tot)));                 
            }
        }
    }

    

    function plus(i) {
        let harga = parseFloat(formatulang($('#vharga'+i).val()));
        if($('#plus'+i).is(':checked')){
            $('#qty_total'+i).val(parseFloat($('#nquantity'+i).val())+parseFloat($('#toleransi'+i).val()));
        }else{
            $('#qty_total'+i).val(parseFloat($('#nquantity'+i).val()));
        }
        hitung();
    }

    function hitungnilai(i) {
        var totfak = formatulang(document.getElementById('vtotalfa').value);
        var totdpp = formatulang(document.getElementById('vtotaldpp').value);
        var totppn = formatulang(document.getElementById('vtotalppn').value);
        var totfak = formatulang(document.getElementById('vtotalfa').value);
        var tipe   = formatulang(document.getElementById('ie').value);

        //alert(totfak + " " +totdpp+ " " + " "+totppn);
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
        document.getElementById('isjaja').value = sj;

        var total = formatulang(document.getElementById('vtotalfa').value);
        var diskon = formatulang(document.getElementById('diskonsup').value);
        var diskon2 = formatulang(document.getElementById('vdiskon').value);
        if(tipe == 'I'){
            bruto   = total;
            vdis    = diskon/100;
            vdiskon = parseFloat(bruto)*parseFloat(vdis);
            vnet    = bruto - vdiskon - diskon2;
            dpp     = parseFloat(vnet)/1.1;
            ppn     = dpp*0.1;
            vnetto  = vnet;
        }else if(tipe == 'E'){
            bruto   = total*1.1;
            vdis    = diskon/100;
            vdiskon = parseFloat(bruto)*parseFloat(vdis);
            vnet    = bruto - vdiskon;
            dpp     = vnet/1.1;
            ppn     = dpp*0.1;
            vdis2   = diskon2/100;
            vdiskon2= parseFloat(vnet)*parseFloat(vdis2);
            vnetto  = vnet - vdiskon2;
        }
        document.getElementById('vtotaldpp').value = formatcemua(Math.round(dpp));
        document.getElementById('vtotalppn').value = formatcemua(Math.round(ppn));
        document.getElementById('vtotalbruto').value = formatcemua(bruto);
        document.getElementById('vtotaldis').value = formatcemua(vdiskon);
        document.getElementById('vtotalnet').value = formatcemua(vnetto);
        document.getElementById('vtotalneto').value = formatcemua(vnetto);
    }

    function hitungnilai_20211203(i) {
        var totfak = formatulang(document.getElementById('vtotalfa').value);
        var totdpp = formatulang(document.getElementById('vtotaldpp').value);
        var totppn = formatulang(document.getElementById('vtotalppn').value);
        var totfak = formatulang(document.getElementById('vtotalfa').value);
        var tipe   = formatulang(document.getElementById('ie').value);

        //alert(totfak + " " +totdpp+ " " + " "+totppn);
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
        document.getElementById('isjaja').value = sj;

        var total = formatulang(document.getElementById('vtotalfa').value);
        var diskon = formatulang(document.getElementById('diskonsup').value);
        var diskon2 = formatulang(document.getElementById('vdiskon').value);
        if(tipe == 'I'){
            bruto   = total;
            vdis    = diskon/100;
            vdiskon = parseFloat(bruto)*parseFloat(vdis);
            vnet    = bruto - vdiskon - diskon2;
            dpp     = parseFloat(vnet)/1.1;
            ppn     = dpp*0.1;
            vnetto  = vnet;
        }else if(tipe == 'E'){
            bruto   = total*1.1;
            vdis    = diskon/100;
            vdiskon = parseFloat(bruto)*parseFloat(vdis);
            vnet    = bruto - vdiskon;
            dpp     = vnet/1.1;
            ppn     = dpp*0.1;
            vdis2   = diskon2/100;
            vdiskon2= parseFloat(vnet)*parseFloat(vdis2);
            vnetto  = vnet - vdiskon2;
        }
        document.getElementById('vtotaldpp').value = formatcemua(Math.round(dpp));
        document.getElementById('vtotalppn').value = formatcemua(Math.round(ppn));
        document.getElementById('vtotalbruto').value = formatcemua(bruto);
        document.getElementById('vtotaldis').value = formatcemua(vdiskon);
        document.getElementById('vtotalnet').value = formatcemua(vnetto);
        document.getElementById('vtotalneto').value = formatcemua(vnetto);
    }

    function hitungdiskon() {

        var vnetto = formatulang($('#vtotalneto').val());    
        var vnetto2 = formatulang($('#vtotalneto').val());    
        var vdiskon= formatulang($('#vdiskon').val());
        if(vdiskon == ''){
            vdiskon = 0;
        }
       
        //vdis  = vdiskon/100;
        //total = parseFloat(vnetto)*parseFloat(vdis);
        //totalnet= parseFloat(vnetto2)-parseFloat(total);
        totalnet = parseFloat(vnetto2) - parseFloat(vdiskon);
        dpp      = parseFloat(totalnet)/1.1;
        ppn      = dpp*0.1;

        //$('#vtotalnet').val(formatMoney(totalnet,2,',','.'));
        document.getElementById('vtotalnet').value = formatcemua(Math.round(totalnet));
        document.getElementById('vtotaldpp').value = formatcemua(Math.round(dpp));
        document.getElementById('vtotalppn').value = formatcemua(Math.round(ppn));
    }

    // function hitungnilaipp(){
    //     var jml = $('#jml').val();
    //     var pkp = $('#fsupplierpkp').val();
    //     var tipepajak = $('#ipaymenttype').val();
    //     var totop=0;
    //     var tot=0;
    //     var selisih=0;
    //     var dpp=0;
    //     var ppn=0;
    //     var gtotppn=0;
    //     var gtotop=0;
    //     var gtot=0;
    //     var gtotselisih=0;

    //     if(pkp='t')
    //     {
    //       for(var i=1; i<=jml; i++)
    //       {
    //         var qty = $('#nquantity'+i).val()==''?$('#nquantity'+i).val(0):qty;
    //         qty = $('#nquantity'+i).val() || 0;
            
    //         // var hrgop = formatulang($('#vpriceop'+i).val());
            
    //         var hrg = formatulang($('#vharga'+i).val())==''?$('#vharga'+i).val(0):hrg;
    //         hrg   = formatulang($('#vharga'+i).val()) || 0; 
            
    //         var diskon = $('#vdiskon').val()==''?$('#diskon').val(0):diskon;
    //         diskon = $('#vdiskon').val() || 0;

    //         if(tipepajak=='0')
    //         {
    //           // totop = (parseFloat(hrgop)*parseFloat(qty))-parseFloat(diskon);
    //           // $('#vtotalop'+i).val(formatcemua(totop));
    //           tot = (parseFloat(hrg)*parseFloat(qty))-parseFloat(diskon);
    //           $('#vtotalsem'+i).val(formatcemua(tot));
    //           // selisih = totop-tot;
    //           // $('#selisih'+i).val(formatcemua(selisih));

    //           var pi = tot/1.1;
    //           ppn = tot-pi;
    //           $('#vppn'+i).val(formatMoney(ppn,2,',','.'));

    //           gtotppn += ppn;
    //           // gtotop += totop;
    //           gtot += tot;
    //         } else {
    //           // totop = (parseFloat(hrgop)*parseFloat(qty))-parseFloat(diskon);
    //           // $('#vtotalop'+i).val(formatcemua(totop));
    //           tot = (parseFloat(hrg)*parseFloat(qty))-parseFloat(diskon);
    //           $('#vtotalsem'+i).val(formatcemua(tot));
    //           // selisih = totop-tot;
    //           // $('#selisih'+i).val(formatcemua(selisih));

    //           // pe=pajak exclude
    //           var pe = tot*0.1;
    //           $('#vppn'+i).val(formatMoney(pe,2,',','.'));
    //           var newtot = parseFloat(pe)+parseFloat(tot);

    //           // peop=pajak exclude op
    //           // var peop = totop*0.1;
    //           // var newtotop = parseFloat(peop)+parseFloat(totop);

    //           gtotppn += pe;
    //           // gtotop += newtotop;
    //           gtot += newtot;
    //         }

    //         // $('#grandtotop').val(formatcemua(gtotop));
    //         $('#vtotalfa').val(formatcemua(gtot));
    //         $('#vtotalppn').val(formatMoney(gtotppn,2,',','.'));
    //         dpp = gtot/1.1;
    //         $('#vtotaldpp').val(formatMoney(dpp,2,',','.'));
    //         // gtotselisih = gtotop-gtot;
    //         // $('#grandselisih').val(formatcemua(gtotselisih));
    //       }
    //     } else {
    //       for(var i=1; i<=jml; i++)
    //       {
    //         var qty = $('#nquantity'+i).val()==''?$('#nquantity'+i).val(0):qty;
    //         qty = $('#nquantity'+i).val() || 0;
            
    //         // var hrgop = formatulang($('#vpriceop'+i).val());

    //         var hrg = formatulang($('#vharga'+i).val())==''?$('#vharga'+i).val(0):hrg;
    //         hrg   = formatulang($('#vharga'+i).val()) || 0; 
            
    //         var diskon = $('#vdiskon').val()==''?$('#vdiskon').val(0):diskon;
    //         diskon = $('#vdiskon').val() || 0;
    //         $('#vppn'+i).val(0);
    //         // totop = (parseFloat(hrgop)*parseFloat(qty))-parseFloat(diskon);
    //         // $('#vtotalop'+i).val(formatcemua(totop));
    //         tot = (parseFloat(hrg)*parseFloat(qty))-parseFloat(diskon);
    //         $('#vtotalsem'+i).val(formatcemua(tot));
    //         // selisih = totop-tot;
    //         // $('#selisih'+i).val(formatcemua(selisih));

    //         // gtotop += totop;
    //         gtot += tot;
    //       }
    //       // $('#grandtotop').val(formatcemua(gtotop));
    //       $('#vtotalfa').val(formatcemua(gtot));
    //       $('#vtotalppn').val(0);
    //       $('#vtotaldpp').val(0);
    //       // gtotselisih = gtotop-gtot;
    //       // $('#grandselisih').val(formatcemua(gtotselisih));
    //     }
    // }

    function tgl_jatuhtempo(){
//<?php //$da=$data->sup_top; $int = (int)$da; echo date("d-m-Y", strtotime('+'.$int.' day', strtotime(date('d-m-Y'))));?>
        
        var dfsupp  = $('#dfsupp').val(); 
        var suptop  = $('#suptop').val(); 

        var a       = parseInt(suptop);
       //var d = new Date(2018, 11, 24);
        var arr = dfsupp.split("-");
        var d  = arr[0];
         //alert(d);
        var m  = arr[1];

        var y  = arr[2];
        var x = y+" "+m+" "+d;
        //alert(x);
        var date = new Date(x);
        //alert(date);

        date.setDate(date.getDate() + a); // add 30 days 
        var year    = date.getFullYear();
        var month   = date.getMonth();
        var ndate   = date.getDate();
        //alert(month);
        var day     = new Date(year, month, ndate);
        //alert(day);      
        var year1=day.getFullYear();
        var month1=day.getMonth()+1; //getMonth is zero based;
       // alert(month1);
       var mm = ("0"+month1).slice(-2);
       //alert(mm);
        var day1=("0" + day.getDate()).slice(-2);
        dnew= day1 + "-" + mm + "-" + year1;
        //alert(dnew);
        $('#djatuhtempo').val(dnew);
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

    function validasi() {
        var s = 0;
        var pkp = $('#fsupplierpkp').val();

        var textinputs = document.querySelectorAll('input[type=checkbox]');
        var empty = [].filter.call(textinputs, function(el) {
            return !el.checked
        });

        if (pkp == 't') {
            /*if (document.getElementById('ipajak').value == '') {
                swal("No Pajak Masih Kosong");
                return false;
            } else */
            if (textinputs.length == empty.length) {
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
</script>