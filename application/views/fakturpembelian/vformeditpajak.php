<style>
    th,
    td {
        padding: 0.60rem !important;
    }
</style>
<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/update_pajak'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil mr-2"></i> <?= $title; ?>
                <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list mr-2"></i><?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Bagian Pembuat</label>
                        <label class="col-md-3">No Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-4">Supplier</label>
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control input-sm select2" disabled required="">
                                <?php if ($bagian) {
                                    foreach ($bagian as $row) : ?>
                                        <option value="<?= $row->i_bagian; ?>" <?php if ($row->i_bagian == $data->i_bagian) { ?> selected <?php } ?>>
                                            <?= $row->e_bagian_name; ?>
                                        </option>
                                <?php endforeach;
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" value="<?= $id; ?>">
                                <input type="hidden" name="inotaold" id="inotaold" value="<?= $data->i_nota; ?>">
                                <input type="text" name="inota" id="inota" readonly="" onkeyup="gede(this);" placeholder="FP-2010-000001" maxlength="15" class="form-control input-sm input-sm" value="<?= $data->i_nota; ?>">
                            </div>
                            <span class="notekode">Format : (<?= $data->i_nota; ?>)</span><br>
                            <span class="notekode" hidden="true"><b>* No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="dnota" id="dnota" class="form-control input-sm" value="<?= date("d-m-Y", strtotime($data->d_nota)) ?>" readonly="" onchange="max_tgl(this.value);">
                        </div>
                        <div class="col-sm-4">
                            <input type="hidden" name="isupplier" class="form-control input-sm" value="<?= $data->i_supplier; ?>" readonly>
                            <input type="text" name="isuppliername" class="form-control input-sm" value="<?= $data->e_supplier_name; ?>" readonly>
                            <input type="hidden" name="fsupplierpkp" id="fsupplierpkp" class="form-control input-sm" value="<?= $data->f_pkp; ?>" readonly>
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
                            <input type="text" name="ipajak" id="ipajak" class="form-control input-sm" required value="<?= $data->i_pajak; ?>">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="dpajak" id="dpajak" class="form-control input-sm date" required value="<?php if ($data->d_pajak != null) {
                                                                                                                        echo date("d-m-Y", strtotime($data->d_pajak));
                                                                                                                    } ?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="ifaktur" id="ifaktur" class="form-control input-sm" value="<?= $data->i_faktur_supplier; ?>">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="dfsupp" id="dfsupp" class="form-control input-sm" value="<?= date("d-m-Y", strtotime($data->d_faktur_supplier)); ?>" readonly onchange="return tgl_jatuhtempo();">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="dreceivefaktur" id="dreceivefaktur" class="form-control input-sm" value="<?= date("d-m-Y", strtotime($data->d_terima_faktur)); ?>" readonly="">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="djatuhtempo" id="djatuhtempo" class="form-control input-sm" value="<?= date("d-m-Y", strtotime($data->d_jatuh_tempo)); ?>" readonly="">
                            <input type="hidden" name="suptop" id="suptop" class="form-control input-sm" value="<?= $data->sup_top; ?>" readonly="">
                        </div>
                    </div>
                    <!-- <div class="form-group row">
                        <label class="col-md-1">Diskon (Rp.)</label>
                        <label class="col-md-1">Jml Dis Reg</label>
                        <label class="col-md-2">Nilai Total DPP</label>
                        <label class="col-md-2">Nilai Total PPN</label>   
                        <label class="col-md-2">Jumlah Nilai Bruto</label>
                        <label class="col-md-4">Jumlah Nilai Netto</label>
                        <div class="col-sm-2">
                             <input type="text" name="vdiskon" id="vdiskon" class="form-control input-sm" value="<?= $data->v_diskon_lain; ?>" onkeyup="hitungdiskon()">
                             <input type="hidden" name="diskonsup" id="diskonsup" class="form-control input-sm" value="<?= $data->n_diskon; ?>" readonly>
                        </div> 
                         <div class="col-sm-2">
                            <input type="text" name="vtotaldis" id="vtotaldis" class="form-control input-sm" value="<?= $data->v_sub_diskon; ?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="vtotaldpp" id="vtotaldpp" class="form-control input-sm" value="<?= $data->v_dpp; ?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="vtotalppn" id="vtotalppn" class="form-control input-sm" value="<?= $data->v_ppn; ?>" readonly>
                        </div> 
                        <div class="col-sm-2">
                           <input type="text" name="vtotalbruto" id="vtotalbruto" class="form-control input-sm" value="<?= $data->v_total_bruto; ?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="vtotalnet" id="vtotalnet" class="form-control input-sm" value="<?= $data->v_total_net; ?>" readonly>
                            <input type="hidden" name="vtotalneto" id="vtotalneto" class="form-control input-sm" value="<?= $data->v_total_net; ?>" readonly>
                             <input type="hidden" name="vtotal_neto" id="vtotal_neto" class="form-control input-sm" value="" readonly>
                        </div>
                        <div class="col-sm-2">
                           <input type="hidden" name="vtotalfa" id="vtotalfa" class="form-control input-sm" value="<?= $data->v_total; ?>" readonly>
                        </div>                    
                    </div>   -->
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea readonly class="form-control input-sm input-sm" name="eremark" placeholder="Isi keterangan jika ada!"><?= $data->e_remark; ?></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <?php if ($data->i_status == '11' || $data->i_status == '12' || $data->i_status == '13') { ?>
                            <div class="col-sm-6">
                                <button type="submit" id="submit" class="btn btn-success btn-block btn-sm"><i class="fa fa-save mr-2"></i>Update</button>
                            </div>
                            <div class="col-sm-6">
                                <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;"><i class="fa fa-arrow-circle-left mr-2"></i>Kembali</button>
                            </div>
                        <?php } ?>
                    </div>
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
                        <th>No BTB</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Satuan In</th>
                        <!-- <th>Qty Eks</th>
                        <th>Satuan Eks</th> -->
                        <th class="text-right">Qty In</th>
                        <th class="text-right">Toleransi</th>
                        <th class="text-center">Plus Toleransi</th>
                        <th class="text-right">Total Qty</th>
                        <th class="text-right">Harga</th>
                        <th class="text-right">Harga Manual</th>
                        <th class="text-right">DPP</th>
                        <th class="text-right">PPN</th>
                        <th class="text-right">Jumlah Total (Rp.)</th>
                        <th class="text-right">Pembulatan (Rp.)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($data1) {
                        $i = 0;
                        foreach ($data1 as $row) {
                            $i++;
                    ?>
                            <tr>
                                <td class="col-sm-1 text-center">
                                    <?php echo $i; ?>
                                </td>
                                <td class="col-sm-1">
                                    <input style="width:170px" class="form-control input-sm" type="hidden" id="idiop<?= $i; ?>" name="idiop<?= $i; ?>" value="<?= $row->id_op; ?>" readonly>
                                    <input style="width:170px" class="form-control input-sm" type="hidden" id="idbtb<?= $i; ?>" name="idbtb<?= $i; ?>" value="<?= $row->id_btb; ?>" readonly>
                                    <input style="width:170px" class="form-control input-sm" type="text" id="ibtb<?= $i; ?>" name="ibtb<?= $i; ?>" value="<?= $row->i_btb; ?>" readonly>
                                </td>
                                <td class="col-sm-1">
                                    <input style="width:100px" class="form-control input-sm" type="text" id="imaterial<?= $i; ?>" name="imaterial<?= $i; ?>" value="<?= $row->i_material; ?>" readonly>
                                </td>
                                <td class="col-sm-1">
                                    <input style="width:300px" class="form-control input-sm" type="text" id="ematerial<?= $i; ?>" name="ematerial<?= $i; ?>" value="<?= htmlentities($row->e_material_name); ?>" readonly>
                                    <input style="width:100px" class="form-control input-sm" type="hidden" id="isupplier<?= $i; ?>" name="isupplier<?= $i; ?>" value="<?= $row->i_supplier; ?>" readonly>
                                </td>
                                <td class="col-sm-1">
                                    <input style="width:100px" class="form-control input-sm" type="hidden" id="isatuan<?= $i; ?>" name="isatuan<?= $i; ?>" value="<?= $row->i_satuan_code; ?>" readonly>
                                    <input style="width:100px" class="form-control input-sm" type="text" id="esatuan<?= $i; ?>" name="esatuan<?= $i; ?>" value="<?= $row->e_satuan_name; ?>" readonly>
                                </td>
                                <td class="col-sm-1" hidden="true">
                                    <input style="width:70px" type="text" id="nquantityeks<?= $i; ?>" name="nquantityeks<?= $i; ?>" value="<?= $row->n_quantity_eks; ?>" class="form-control input-sm" readonly>
                                </td>
                                <td class="col-sm-1" hidden="true">
                                    <input style="width:100px;" type="hidden" id="isatuaneks<?= $i; ?>" class="form-control input-sm" name="isatuaneks<?= $i; ?>" value="<?= $row->i_satuan_code_eks; ?>" readonly>
                                    <input style="width:100px;" type="text" id="esatuaneks<?= $i; ?>" class="form-control input-sm" name="esatuaneks<?= $i; ?>" value="<?= $row->satuaneks; ?>" readonly>
                                </td>
                                <td class="col-sm-1">
                                    <input style="width:100px" class="form-control text-right input-sm" type="text" id="nquantity<?= $i; ?>" name="qty<?= $i; ?>" value="<?php echo number_format($row->n_quantity_btb, 2); ?>" readonly>
                                </td>
                                <td class="col-sm-1">
                                    <input style="width:100px" class="form-control input-sm text-right" type="text" id="toleransi<?= $i; ?>" name="toleransi<?= $i; ?>" value="<?php echo $row->n_toleransi; ?>" readonly>
                                </td>
                                <td class="col-sm-1 text-center">
                                    <input type="checkbox" <?php if ($row->f_toleransi == 't') {
                                                                echo "checked";
                                                            }; ?> id="plus<?php echo $i; ?>" class="form-control input-sm" name="plus<?php echo $i; ?>" onclick="plus(<?php echo $i ?>)">
                                </td>
                                <td class="col-sm-1">
                                    <input style="width:100px" class="form-control input-sm text-right" type="text" id="qty_total<?= $i; ?>" name="nquantity<?= $i; ?>" value="<?php echo $row->n_quantity; ?>" readonly>
                                </td>
                                <td class="col-sm-1">
                                    <input style="width:100px" class="form-control text-right input-sm" type="text" id="vharga<?= $i; ?>" name="vharga<?= $i; ?>" value="<?= number_format($row->v_price,4); ?>" readonly>
                                    <input style="width:100px" class="form-control input-sm" type="hidden" id="itipe<?= $i; ?>" name="itipe<?= $i; ?>" value="<?= $row->f_ppn; ?>" readonly>
                                    <input style="width:100px" class="form-control input-sm" type="hidden" id="nppn<?= $i; ?>" name="nppn<?= $i; ?>" value="<?= $ppnop->ppn_op; ?>" readonly>
                                </td>
                                <td class="col-sm-1">
                                    <input style="width:100px" class="form-control input-sm text-right" type="text" id="vharga_manual<?= $i; ?>" name="vharga_manual<?= $i; ?>" value="<?= number_format($row->v_price_manual,4); ?>" readonly onkeyup="angkahungkul(this);hitung();reformat(this);">
                                </td>
                                <td class="col-sm-1 ">
                                    <input style="width:100px" class="form-control text-right input-sm" type="text" id="vdpp<?= $i; ?>" name="vdpp<?= $i; ?>" value="<?= $row->v_dpp; ?>" readonly>
                                </td>
                                <td class="col-sm-1">
                                    <input style="width:100px" class="form-control text-right input-sm" type="text" id="vppn<?= $i; ?>" name="vppn<?= $i; ?>" value="<?= $row->v_ppn; ?>" readonly>
                                </td>
                                <td class="col-sm-1">
                                    <input style="width:150px" type="hidden" id="vtotal<?= $i; ?>" name="vtotal<?= $i; ?>" value="<?= $row->v_total; ?>" readonly>
                                    <input style="width:150px" class="form-control text-right input-sm" name="totalfake<?php echo $i; ?>" id="totalfake<?php echo $i; ?>" type="hidden" value="<?php echo number_format($row->v_total, 2); ?>" readonly>
                                    <input style="width:150px" class="form-control text-right input-sm" type="text" id="vtotalsem<?= $i; ?>" name="vtotalsem<?= $i; ?>" value="<?= $row->v_total; ?>" readonly>
                                </td>
                                <td class="col-sm-1">
                                    <input style="width:150px" class="form-control text-right input-sm" type="text" id="v_pembulatan_item<?= $i; ?>" name="v_pembulatan_item<?= $i; ?>" value="<?= number_format($row->v_pembulatan); ?>" readonly>
                                </td>
                            </tr>
                    <? }
                    } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th class="text-right" colspan="11">Nilai Bruto Rp.</th>
                        <th><input type="text" name="vtotalbruto" id="vtotalbruto" class="form-control text-right input-sm" value="0" readonly></th>
                        <th colspan="3">Diskon Supplier (%)</th>
                    </tr>
                    <tr>
                        <th class="text-right" colspan="11">Nilai Diskon Tambahan Rp.</th>
                        <th>
                            <input type="text" autocomplete="off" name="vdiskon" readonly id="vdiskon" class="form-control text-right input-sm" onblur="if(this.value==''){this.value='0';hitung();}" onfocus="if(this.value=='0'){this.value='';}" value="<?= number_format($data->v_sub_diskon); ?>" onkeyup="hitung(); reformat(this);">
                            <input type="hidden" name="vdiskonsup" id="vdiskonsup" class="form-control input-sm" value="0" readonly>
                        </th>
                        <th>
                            <input type="text" name="diskonsup" id="diskonsup" class="form-control text-right input-sm" value="<?= $data->n_diskon; ?>" readonly>
                        </th>
                        <th class="text-right" colspan="2"></th>
                    </tr>
                    <tr>
                        <th class="text-right" colspan="11">Nilai Diskon Total (<span id="diskon_persen"><?= $data->n_diskon; ?></span>%) Rp.</th>
                        <th>
                            <input type="text" autocomplete="off" name="vdiskontotal" id="vdiskontotal" class="form-control text-right input-sm" value="0" readonly>
                        </th>
                        <th class="text-right" colspan="3"></th>
                    </tr>
                    <tr>
                        <th class="text-right" colspan="11">Nilai DPP Rp.</th>
                        <th><input type="text" name="vtotaldpp" id="vtotaldpp" class="form-control text-right input-sm" value="0" readonly></th>
                        <th class="text-right" colspan="3"></th>
                    </tr>
                    <tr>
                        <th class="text-right" colspan="11">Nilai PPN Rp.</th>
                        <th><input type="text" name="vtotalppn" id="vtotalppn" class="form-control text-right input-sm" value="0" readonly></th>
                        <th colspan="3">Pembulatan</th>
                    </tr>
                    <tr>
                        <th class="text-right" colspan="11">Nilai Netto Rp.</th>
                        <th><input type="text" name="vtotalnet" id="vtotalnet" class="form-control text-right input-sm" value="0" readonly></th>
                        <th>
                            <input type="text" name="v_pembulatan" id="v_pembulatan" class="form-control text-right input-sm" value="<?= number_format($data->v_pembulatan); ?>" readonly>
                        </th>
                        <th class="text-right" colspan="2"></th>
                    </tr>
                </tfoot>
                <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                <input type="hidden" name="ppn_op" id="ppn_op" value="<?= $ppnop->ppn_op; ?>">
            </table>
        </div>
    </div>
</div>
</form>

<script>
    $(document).ready(function() {
        $(".select2").select2();
        // hitungnilai();
        fixedtable($('.table'));
        hitung();
    });

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#send").attr("hidden", false);
        $("#send").attr("disabled", false);
    });

    function getenabledsend() {
        $('#send').attr("disabled", true);
        $('#submit').attr("disabled", true);
    }

    $('#send').click(function(event) {
        statuschange('<?= $folder; ?>', $('#id').val(), '2', '<?= $dfrom . "','" . $dto; ?>');
    });

    $('#cancel').click(function(event) {
        statuschange('<?= $folder; ?>', $('#id').val(), '1', '<?= $dfrom . "','" . $dto; ?>');
    });

    $('#hapus').click(function(event) {
        statuschange('<?= $folder; ?>', $('#id').val(), '5', '<?= $dfrom . "','" . $dto; ?>');
    });

    $(document).ready(function() {
        $('.select2').select2();
        showCalendar('.date');
    });

    $("#inota").keyup(function() {
        $.ajax({
            type: "post",
            data: {
                'kode': $(this).val(),
            },
            url: '<?= base_url($folder . '/cform/cekkode'); ?>',
            dataType: "json",
            success: function(data) {
                if (data == 1 && ($('#inota').val() != $('#inotaold').val())) {
                    $(".notekode").attr("hidden", false);
                    $("#submit").attr("disabled", true);
                } else {
                    $(".notekode").attr("hidden", true);
                    $("#submit").attr("disabled", false);
                }
            },
            error: function() {
                swal('Error :)');
            }
        });
    });

    $('#ceklis').click(function(event) {
        if ($('#ceklis').is(':checked')) {
            $("#inota").attr("readonly", false);
        } else {
            $("#inota").attr("readonly", true);
            $("#inota").val("<?= $id; ?>");
        }
    });

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
    /*
        function hitungnilai(){
            var totfak = formatulang(document.getElementById('vtotalfa').value);
            if(document.getElementById('cek'+i).checked==true){
                var nilaisj = document.getElementById('vtotal'+i).value;
                totakhir = parseFloat(totfak)+parseFloat(nilaisj);
                
            } else {
                var nilaisj = document.getElementById('vtotal'+i).value;
                totakhir = parseFloat(totfak)-parseFloat(nilaisj);
            }
            document.getElementById('vtotalfa').value = formatcemua(totakhir);
        }*/

    // function tgl_jatuhtempo(){
    //     var dpajak  = $('#dpajak').val(); 
    //     var suptop  = $('#suptop').val();  
    //     var dpajak  = $('#dpajak').val();
    //     var a       = parseInt(suptop);
    //     var da      = dpajak.substr(0, 2);
    //     var ma      = dpajak.substr(3, 2);
    //     var ya      = dpajak.substr(6, 4);
    //     var today   = new Date(ya, ma, da);

    //     var year    = today.getFullYear();
    //     var month   = today.getMonth();
    //     var date    = today.getDate();


    //     var day     = new Date(year, month, date+a);

    //     mnth = ("0" + (day.getMonth())).slice(-2),
    //     dath = ("0" + day.getDate()).slice(-2);
    //     jam  = [dath, mnth, today.getFullYear()].join("-");

    //     $('#djatuhtempo').val(jam);
    // }

    function hitungnilai() {
        var jml = $('#jml').val();
        //var total = formatulang($('#vtotalfa').val());
        var diskon = formatulang($('#diskonsup').val());
        var diskon2 = formatulang($('#vdiskon').val());
        var totfak = formatulang(document.getElementById('vtotalfa').value);
        totakhir = 0;
        for (var i = 1; i <= jml; i++) {
            var tipe = $('#itipe' + i).val();
            $('#ie').val(tipe);
            // alert(tipe);
            var nilaisj = formatulang($('#vtotalsem' + i).val());
            totakhir += parseFloat(nilaisj);
            total = totakhir;

            // if(tipe == 'I'){
            //     bruto   = total;
            //     vdis    = diskon/100;
            //     vdiskon = parseFloat(bruto)*parseFloat(vdis);
            //     vnet    = bruto - vdiskon - diskon2;
            //     dpp     = parseFloat(vnet)/1.1;
            //     ppn     = dpp*0.1;
            //     // vdis2   = diskon2/100;
            //     // vdiskon2= parseFloat(vnet)*parseFloat(vdis2);
            //     vnetto  = vnet;

            // }else if(tipe == 'E'){
            //     bruto   = total*1.1;
            //     vdis    = diskon/100;
            //     vdiskon = parseFloat(bruto)*parseFloat(vdis);
            //     vnet    = bruto - vdiskon;
            //     dpp     = vnet/1.1;
            //     ppn     = dpp*0.1;
            //     vdis2   = diskon2/100;
            //     vdiskon2= parseFloat(vnet)*parseFloat(vdis2);
            //     vnetto  = vnet - vdiskon2;
            // }      
            document.getElementById('vtotal_neto').value = formatcemua(total);
        }
    }

    function tgl_jatuhtempo() {
        //<?php //$da=$data->sup_top; $int = (int)$da; echo date("d-m-Y", strtotime('+'.$int.' day', strtotime(date('d-m-Y'))));
            ?>

        var dfsupp = $('#dfsupp').val();
        var suptop = $('#suptop').val();

        var a = parseInt(suptop);
        //var d = new Date(2018, 11, 24);
        var arr = dfsupp.split("-");
        var d = arr[0];
        //alert(d);
        var m = arr[1];

        var y = arr[2];
        var x = y + " " + m + " " + d;
        //alert(x);
        var date = new Date(x);
        //alert(date);

        date.setDate(date.getDate() + a); // add 30 days 
        var year = date.getFullYear();
        var month = date.getMonth();
        var ndate = date.getDate();
        //alert(month);
        var day = new Date(year, month, ndate);
        //alert(day);      
        var year1 = day.getFullYear();
        var month1 = day.getMonth() + 1; //getMonth is zero based;
        // alert(month1);
        var mm = ("0" + month1).slice(-2);
        //alert(mm);
        var day1 = ("0" + day.getDate()).slice(-2);
        dnew = day1 + "-" + mm + "-" + year1;
        //alert(dnew);
        $('#djatuhtempo').val(dnew);
    }

    function hitungdiskon() {
        var vnetto = formatulang($('#vtotalneto').val());
        var vnetto2 = formatulang($('#vtotalneto').val());
        var vdiskon = formatulang($('#vdiskon').val());
        if (vdiskon == '') {
            vdiskon = 0;
        }

        //vdis  = vdiskon/100;
        //total = parseFloat(vnetto)*parseFloat(vdis);
        //totalnet= parseFloat(vnetto2)-parseFloat(total);
        totalnet = parseFloat(vnetto2) - parseFloat(vdiskon);
        dpp = parseFloat(totalnet) / 1.1;
        ppn = dpp * 0.1;

        //$('#vtotalnet').val(formatMoney(totalnet,2,',','.'));
        document.getElementById('vtotalnet').value = formatcemua(Math.round(totalnet));
        document.getElementById('vtotaldpp').value = formatcemua(Math.round(dpp));
        document.getElementById('vtotalppn').value = formatcemua(Math.round(ppn));
        document.getElementById('vtotal_neto').value = formatcemua(Math.round(totalnet));
    }

    function validasi() {
        var s = 0;
        var textinputs = document.querySelectorAll('input[type=checkbox]');
        var empty = [].filter.call(textinputs, function(el) {
            return !el.checked
        });
        if (textinputs.length == empty.length) {
            alert("Maaf Tolong Pilih Minimal 1 SJ!");
            return false;
        } else if (document.getElementById('dnota').value == '') {
            alert("Maaf Tolong Pilih Tanggal Faktur");
            return false;
        } else if (document.getElementById('dpajak').value == '') {
            alert("Maaf Tolong Pilih Tanggal Pajak");
            return false;
        } else {
            return true
        }
    }

    function hitung() {
        var jml = $('#jml').val();
        var bruto = 0;
        var subtotal = 0;
        var dpp = 0;
        var ppn = 0;
        for (var i = 1; i <= jml; i++) {
            var harga = parseFloat(formatulang($('#vharga_manual' + i).val()));
            var qty = parseFloat(formatulang($('#qty_total' + i).val()));
            var n_ppn = parseFloat(formatulang($('#nppn' + i).val()));
            /* if ($("#cek" + i + ":checked").length > 0) { */
            var v_dpp = harga * qty;
            $('#vdpp' + i).val(formatcemua(Math.round(v_dpp)));
            v_ppn = (v_dpp * (n_ppn / 100));
            $('#vppn' + i).val(formatcemua(Math.round(v_ppn)));
            v_total = (parseFloat(v_dpp) + parseFloat(v_ppn));
            $('#vtotalsem' + i).val(formatcemua(Math.round(v_total)));
            dpp += v_dpp;
            ppn += v_ppn;
            subtotal += v_total;
            /* } */
        }
        var diskon_persen_sup = parseFloat(formatulang(document.getElementById('diskonsup').value));
        var diskon = parseFloat(formatulang(document.getElementById('vdiskon').value));
        if (isNaN(diskon)) {
            var diskon = 0;
        }
        var diskon_sup = dpp * (diskon_persen_sup / 100);
        document.getElementById('vdiskonsup').value = formatcemua(Math.round(diskon_sup));
        document.getElementById('vdiskontotal').value = formatcemua(Math.round(diskon_sup + diskon));
        var diskon_total = parseFloat(formatulang(document.getElementById('vdiskontotal').value));
        if (isNaN(diskon_total)) {
            var diskon_total = 0;
        }
        var tot_dpp = dpp - diskon_total;
        var tot_ppn = tot_dpp * (parseFloat(formatulang(document.getElementById('ppn_op').value)) / 100);
        document.getElementById('diskon_persen').textContent = formatcemua(Math.round((diskon_total / dpp) * 100));
        document.getElementById('vtotalbruto').value = formatcemua(Math.round(dpp));
        document.getElementById('vtotaldpp').value = formatcemua(Math.round(tot_dpp));
        document.getElementById('vtotalppn').value = formatcemua(Math.round(tot_ppn));
        document.getElementById('vtotalnet').value = formatcemua(Math.round(tot_dpp) + Math.round(tot_ppn));
    }

    function plus(i) {
        let harga = parseFloat(formatulang($('#vharga' + i).val()));
        if ($('#plus' + i).is(':checked')) {
            $('#qty_total' + i).val(parseFloat($('#nquantity' + i).val()) + parseFloat($('#toleransi' + i).val()));
        } else {
            $('#qty_total' + i).val(parseFloat($('#nquantity' + i).val()));
        }
        hitung();
    }
</script>