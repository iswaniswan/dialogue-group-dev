<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-4">Pembuat Dokumen</label>
                        <label class="col-md-2">Bulan</label>
                        <label class="col-md-2">Tahun</label>
                        <label class="col-md-4">Keterangan</label>
                        <div class="col-sm-4">
                            <input type="hidden" name="id" id="id" class="form-control" value="<?php if ($head) echo $head->id; ?>" readonly>
                            <input type="hidden" name="ibagian" id="ibagian" class="form-control" value="<?= $bagian->i_bagian; ?>" readonly>
                            <input type="text" name="e_bagian_name" id="e_bagian_name" class="form-control input-sm" value="<?= $bagian->e_bagian_name; ?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="hidden" name="ibulan" id="ibulan" class="form-control" value="<?= $bulan; ?>" readonly>
                            <input type="text" name="bulan" id="bulan" class="form-control input-sm" value="<?= mbulan($bulan); ?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="tahun" id="tahun" class="form-control input-sm" value="<?= $tahun; ?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <textarea id="eremarkh" name="eremarkh" class="form-control" placeholder="Isi keterangan jika ada!"><?php if (isset($head->e_remark_supplier)) {
                                                                                                                                    echo $head->e_remark_supplier;
                                                                                                                                }; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-5 col-sm-10">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>&nbsp;
                           <!--  <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Item</button> -->
                            <button type="button" id="send" class="btn btn-primary btn-rounded btn-sm" hidden><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom; ?>/<?= $dto; ?>','#main'); return false;"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="white-box" id="detail">
    <div class="col-sm-6">
        <h3 class="box-title m-b-0">Detail Barang</h3>
    </div>
    <div class="col-sm-12">
        <!-- style="overflow-x:auto !important;" -->
        <div class="table-responsive" >
            <table id="tabledatax" class="table color-table tableFixHead success-table table-bordered class display nowrap" cellspacing="0" width="100%">
                <thead>
                    <tr class="d-flex">
                        <th class="text-center col-1" colspan="1"></th>
                        <th class="text-center col-1"><span id="h_fc_berjalan" class="autoNum"><b></b></span></th>
                        <th class="text-center col-1"><span id="h_do_berjalan" class="autoNum"><b></b></span></th>
                        <th class="text-center col-1"><span id="h_fcd" class="autoNum"><b></b></span></th>
                        <th class="text-center col-1"><span id="h_fc_selanjutnya" class="autoNum"><b></b></span></th>
                        <th class="text-center col-1"><span id="h_sjd" class="autoNum"><b></b></span></th>
                        <th class="text-center col-1"><span id="h_swip" class="autoNum"><b></b></span></th>
                        <th class="text-center col-1"><span id="h_sjahit" class="autoNum"><b></b></span></th>
                        <th class="text-center col-1"><span id="h_speng" class="autoNum"><b></b></span></th>
                        <th class="text-center col-1"><span id="h_spack" class="autoNum"><b></b></span></th>
                        <th class="text-center col-1"><span id="h_jml_tmp" class="autoNum"><b></b></span></th>
                        <th class="text-center col-1"><span id="h_qty_up" class="autoNum"><b></b></span></th>
                        <th class="text-center col-1"><span id="h_jml" class="autoNum"><b></b></span></th>
                        <th class="text-center col-1"></th>
                    </tr>

                    <tr class="d-flex">
                        <!-- <th class="text-center" width="3%">No</th>
                        <th class="text-center" width="25%">Barang</th>
                        <th class="text-center">Kategori</th>
                        <th class="text-center" width="7%">FC BLN<br>Berjalan</th>
                        <th class="text-center" width="7%">DO BLN<br>Berjalan</th>
                        <th class="text-center" width="7%">FC <br>Dist</th>
                        <th class="text-center" width="7%">FC BLN<br>Selanjutnya</th>
                        <th class="text-center" width="7%">Stk<br>Jadi</th>
                        <th class="text-center" width="7%">Stk<br>WIP</th>
                        <th class="text-center" width="7%">Stk<br>Jahit</th>
                        <th class="text-center" width="7%">Stk Peng<br>daan</th>
                        <th class="text-center" width="7%">QTY Up</th>
                        <th class="text-center" width="7%">Jml FC Produksi<br> Perhitungkan </th>
                        <th class="text-center" width="7%">Jml FC Produksi<br> Budgetkan </th>
                        <th class="text-center" width="10%">Ket</th> -->

                        <th class="text-center col-1">No</th>
                        <th class="text-center col-1">FC BLN<br>Berjalan</th>
                        <th class="text-center col-1">DO BLN<br>Berjalan</th>
                        <th class="text-center col-1">FC <br>Dist</th>
                        <th class="text-center col-1">FC BLN<br>Selanjutnya</th>
                        <th class="text-center col-1">Stok<br>Jadi</th>
                        <th class="text-center col-1">Stok<br>WIP</th>
                        <th class="text-center col-1">Stok<br>Jahit</th>
                        <th class="text-center col-1">Stok Pengadaan</th>
                        <th class="text-center col-1">Stok Packing</th>
                        <th class="text-center col-1">Jml FC Produksi<br> Perhitungkan </th>
                        <th class="text-center col-1">QTY Up</th>
                        <th class="text-center col-1">Jml FC Produksi<br> Budgetkan </th>
                        <th class="text-center col-1">Keterangan</th>
                    </tr>
                </thead>
                <tbody>

                        <tr style="height:20px;">
                            <td class="text-center col-13"></td>
                        </tr>                                                                                                                                
                    <?php
                    $i = 0;

                    $tot_nquantity_fc = 0;
                    $tot_nquantity_stock = 0;
                    $tot_nquantity_stock_wip = 0;
                    $tot_nquantity_stock_jahit = 0;
                    $tot_nquantity_stock_pengadaan = 0;
                    $tot_nquantity_stock_packing = 0;
                    $tot_nquantity_up = 0;
                    $tot_nquantity = 0;

                    $tot_nquantity_fc_berjalan = 0;
                    $tot_nquantity_do_berjalan = 0;
                    $tot_nquantity_fc_selanjutnya = 0;
                    $tot_nquantity_tmp = 0;

                    foreach ($datadetail as $key) {
                        $i++;

                        $tot_nquantity_fc += $key["n_quantity_fc"];
                        $tot_nquantity_stock += $key["n_quantity_stock"];
                        $tot_nquantity_stock_wip += $key["n_quantity_wip"];
                        $tot_nquantity_stock_jahit += $key["n_quantity_unitjahit"];
                        $tot_nquantity_stock_pengadaan += $key["n_quantity_pengadaan"];
                        $tot_nquantity_stock_packing += $key["n_packing"];

                        $tot_nquantity_up += $key["persen_up"];

                        $tot_nquantity_fc_berjalan += $key["n_fc_berjalan"];
                        $tot_nquantity_do_berjalan += $key["qty_do"];
                        $tot_nquantity_fc_selanjutnya += $key["n_fc_next"];
                        $tot_nquantity_tmp += (int) $key["n_quantity"];

                        $tot_nquantity += (int) $key["n_quantity"]  + $key["persen_up"];

                    ?>
                        
                        <tr class="d-flex">
                            <td class="text-center col-1">
                                <spanx id="snum<?= $i; ?>"><?= $i; ?></spanx>
                            </td>
                            <td class="col-10">
                                <select data-nourut="<?= $i; ?>" id="idproduct<?= $i; ?>" class="form-control select2 input-sm" name="idproduct<?= $i; ?>">
                                    <option value="<?= $key['id_product_base'] ?>"><?= $key["i_product_base"] . ' - ' . $key["e_product_basename"] . ' - ' . $key["e_color_name"]; ?></option>
                                </select>
                            </td>
                            <td class="col-2" id="kategori<?= $i; ?>"> <?= $key["kategori"]; ?> </td>
                        </tr>

                        <tr class="d-flex">
                            <td class="col-1"></td>
                            <td class="col-1"><input type="text" id="n_fc_berjalan<?= $i; ?>" class="form-control text-right input-sm" autocomplete="off" name="n_fc_berjalan<?= $i; ?>" readonly value="<?= $key["n_fc_berjalan"]; ?>" onkeyup="angkahungkul(this);"></td>
                            <td class="col-1"><input type="text" id="estimasi<?= $i; ?>" class="form-control text-right input-sm" autocomplete="off" name="estimasi<?= $i; ?>" readonly value="<?= $key["qty_do"]; ?>"></td>
                            <td class="col-1"><input type="text" id="nquantity_fc<?= $i; ?>" class="form-control text-right input-sm" autocomplete="off" name="nquantity_fc<?= $i; ?>" readonly value="<?= $key["n_quantity_fc"]; ?>" onkeyup="angkahungkul(this);"></td>
                            <td class="col-1"><input type="text" id="n_fc_next<?= $i; ?>" class="form-control text-right input-sm" autocomplete="off" name="n_fc_next<?= $i; ?>" readonly value="<?= $key["n_fc_next"]; ?>" onkeyup="angkahungkul(this);"></td>

                            <td class="col-1"><input type="text" id="nquantity_stock<?= $i; ?>" class="form-control text-right input-sm" autocomplete="off" name="nquantity_stock<?= $i; ?>" readonly value="<?= $key["n_quantity_stock"]; ?>"></td>
                            <td class="col-1"><input type="text" id="nquantity_stock_wip<?= $i; ?>" class="form-control text-right input-sm" autocomplete="off" name="nquantity_stock_wip<?= $i; ?>" readonly value="<?= $key["n_quantity_wip"]; ?>"></td>
                            <td class="col-1"><input type="text" id="nquantity_stock_jahit<?= $i; ?>" class="form-control text-right input-sm" autocomplete="off" name="nquantity_stock_jahit<?= $i; ?>" readonly value="<?= $key["n_quantity_unitjahit"]; ?>"></td>
                            <td class="col-1"><input type="text" id="nquantity_stock_pengadaan<?= $i; ?>" class="form-control text-right input-sm" autocomplete="off" name="nquantity_stock_pengadaan<?= $i; ?>" readonly value="<?= $key["n_quantity_pengadaan"]; ?>"></td>
                            <td class="col-1"><input type="text" id="nquantity_stock_packing<?= $i; ?>" class="form-control text-right input-sm" autocomplete="off" name="nquantity_stock_packing<?= $i; ?>" readonly value="<?= $key["n_packing"]; ?>"></td>
                            <td class="col-1"><input type="text" id="nquantity_tmp<?= $i; ?>" class="form-control text-right input-sm inputitem" autocomplete="off" name="nquantity_tmp<?= $i; ?>" value="<?= $key["n_quantity"]; ?>" onclick="ambilAngka(<?= $i; ?>)" readonly oninput="hetangQty(<?= $i; ?>)" onblur='if(this.value==""){this.value="0";}' onfocus='if(this.value=="0"){this.value="";}'></td>
                            <td class="col-1"><input type="text" id="persen_up<?= $i; ?>" class="form-control persen_up text-right input-sm" autocomplete="off" name="persen_up<?= $i; ?>" value="<?= $key["persen_up"]; ?>"  oninput="angkahungkul(this);hetang(<?= $i; ?>); hitungPersen();" onblur='if(this.value==""){this.value="0";hetang(<?= $i; ?>);}' onfocus='if(this.value=="0"){this.value="";}'></td>
                            <td class="col-1"><input type="text" id="nquantity<?= $i; ?>" class="form-control text-right input-sm inputitem" autocomplete="off" name="nquantity<?= $i; ?>" value="<?= $key["n_quantity"] + $key["persen_up"]; ?>" onclick="ambilAngka(<?= $i; ?>)" readonly oninput="hetangQty(<?= $i; ?>)" onblur='if(this.value==""){this.value="0";}' onfocus='if(this.value=="0"){this.value="";}'></td>
                            <td class="col-1"><input type="text" class="form-control input-sm" name="eremark<?= $i; ?>" id="eremark<?= $i; ?>" value="<?= $key["e_remark"]; ?>" placeholder="Isi keterangan jika ada!" /></td>
                           <!--  <td class="text-center"><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td> -->
                        </tr>
                    <?php } ?>
                    <input type="hidden" name="istatus" id="istatus" value="<?php if($head) echo $head->i_status; ?>">
                    <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                    <input type="hidden" name="tot_nquantity_fc_berjalan" id="tot_nquantity_fc_berjalan" value="<?= $tot_nquantity_fc_berjalan; ?>">
                    <input type="hidden" name="tot_nquantity_do_berjalan" id="tot_nquantity_do_berjalan" value="<?= $tot_nquantity_do_berjalan; ?>">
                    <input type="hidden" name="tot_nquantity_fc" id="tot_nquantity_fc" value="<?= $tot_nquantity_fc; ?>">
                    <input type="hidden" name="tot_nquantity_fc_selanjutnya" id="tot_nquantity_fc_selanjutnya" value="<?= $tot_nquantity_fc_selanjutnya; ?>">
                    <input type="hidden" name="tot_nquantity_stock" id="tot_nquantity_stock" value="<?= $tot_nquantity_stock; ?>">
                    <input type="hidden" name="tot_nquantity_stock_wip" id="tot_nquantity_stock_wip" value="<?= $tot_nquantity_stock_wip; ?>">
                    <input type="hidden" name="tot_nquantity_stock_jahit" id="tot_nquantity_stock_jahit" value="<?= $tot_nquantity_stock_jahit; ?>">
                    <input type="hidden" name="tot_nquantity_stock_pengadaan" id="tot_nquantity_stock_pengadaan" value="<?= $tot_nquantity_stock_pengadaan; ?>">
                    <input type="hidden" name="tot_nquantity_stock_packing" id="tot_nquantity_stock_packing" value="<?= $tot_nquantity_stock_packing; ?>">
                    <input type="hidden" name="tot_nquantity_up" id="tot_nquantity_up" value="<?= $tot_nquantity_up; ?>">
                    <input type="hidden" name="tot_nquantity_tmp" id="tot_nquantity_tmp" value="<?= $tot_nquantity_tmp; ?>">
                    <input type="hidden" name="tot_nquantity" id="tot_nquantity" value="<?= $tot_nquantity; ?>">

                </tbody>
            </table>
        </div>
    </div>
</div>
</form>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script src="<?= base_url(); ?>assets/js/jquery.floatThead.min.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/autonumeric@4.5.4"></script> -->
<script>
    $(document).ready(function() {

        var $table = $('table.tableFixHead');
            $table.floatThead({
                responsiveContainer: function($table) {
                    return $table.closest('.table-responsive');
                }
            });

        $('#h_fcd').text($('#tot_nquantity_fc').val());
        $('#h_sjd').text($('#tot_nquantity_stock').val());
        $('#h_swip').text($('#tot_nquantity_stock_wip').val());
        $('#h_sjahit').text($('#tot_nquantity_stock_jahit').val());
        $('#h_speng').text($('#tot_nquantity_stock_pengadaan').val());
        $('#h_spack').text($('#tot_nquantity_stock_packing').val());
        $('#h_qty_up').text($('#tot_nquantity_up').val() > 0 ? $('#tot_nquantity_up').val() : 0);
        $('#h_jml').text($('#tot_nquantity').val());

        $('#h_fc_berjalan').text($('#tot_nquantity_fc_berjalan').val());
        $('#h_do_berjalan').text($('#tot_nquantity_do_berjalan').val());
        $('#h_fc_selanjutnya').text($('#tot_nquantity_fc_selanjutnya').val());
        $('#h_jml_tmp').text($('#tot_nquantity_tmp').val());

        for (var i = 1; i <= $('#jml').val(); i++) {
            $('#idproduct' + i).select2({
                placeholder: 'Cari Kode / Nama Barang Jadi',
                /* allowClear: true,*/
                width: "100%",
                type: "POST",
                ajax: {
                    url: '<?= base_url($folder . '/cform/product/'); ?>',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        var query = {
                            q: params.term,
                        }
                        return query;
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                }
            }).change(function(event) {
                var z = $(this).data('nourut');
                var ada = true;
                for (var x = 1; x <= $('#jml').val(); x++) {
                    if ($(this).val() != null) {
                        if ((($(this).val()) == $('#idproduct' + x).val()) && (z != x)) {
                            swal("kode barang tersebut sudah ada !!!!!");
                            ada = false;
                            break;
                        }
                    }
                }
                if (!ada) {
                    $(this).val('');
                    $(this).html('');
                }
            });
        }

        $('#send').click(function(event) {
            statuschange('<?= $folder;?>',$('#id').val(),'2','<?= $dfrom."','".$dto;?>');
        });

        $('#cancel').click(function(event) {
            statuschange('<?= $folder;?>',$('#id').val(),'1','<?= $dfrom."','".$dto;?>');
        });

        $('#hapus').click(function(event) {
            statuschange('<?= $folder;?>',$('#id').val(),'5','<?= $dfrom."','".$dto;?>');
        });
    });

    $("#submit").click(function(event) {
        ada = false;
        if ($('#jml').val() == 0) {
            swal('Isi item minimal 1!');
            return false;
        } else {
            $("#tabledatax tbody tr").each(function() {
                $(this).find("td select").each(function() {
                    if ($(this).val() == '' || $(this).val() == null) {
                        swal('Kode barang tidak boleh kosong!');
                        ada = true;
                    }
                });
                $(this).find("td .inputitem").each(function() {
                    if ($(this).val() == '' || $(this).val() == null) {
                        swal('Quantity Tidak Boleh Kosong !');
                        ada = true;
                    }
                });
            });
            if (!ada) {
                return true;
            } else {
                return false;
            }
        }
    })

    /**
     * After Submit
     */

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#addrow").attr("disabled", true);
        $("#send").attr("hidden", false);
    });

    function cekselisih() {
        var jml = $('#jml').val();
        for (var i = 1; i <= jml; i++) {
            var saldoakhir = Number($('#saldoakhir' + i).val());
            var stokopname = Number($('#stokopname' + i).val());

            total = stokopname - Math.abs(saldoakhir);
            $('#selisih' + i).val(total);

        }
    }

    var i = $('#jml').val();
    $("#addrow").on("click", function() {
        i++;
        $("#jml").val(i);
        var no = $('#tabledatax tr').length;
        var newRow = $("<tr>");
        var cols = "";
        cols += `<td class="text-center"><spanx id="snum${i}">${no}</spanx></td>`;
        cols +=
            `<td><select data-nourut="${i}" id="idproduct${i}" class="form-control input-sm" name="idproduct${i}" ></select></td><td><span id="kategori${i}"></span></td>`;
        cols +=
            `<td><input type="text" readonly id="nquantity_fc${i}" class="form-control text-right input-sm autoNum" autocomplete="off" name="nquantity_fc${i}" value="0"></td>`;
        cols +=
            `<td><input type="text" readonly id="nquantity_stock${i}" class="form-control text-right input-sm autoNum" autocomplete="off" name="nquantity_stock${i}" value="0"></td>`;
        cols +=
            `<td><input type="text" readonly id="nquantity_stock_wip${i}" class="form-control text-right input-sm autoNum" autocomplete="off" name="nquantity_stock_wip${i}" value="0"></td>`;
        cols +=
            `<td><input type="text" readonly id="nquantity_stock_jahit${i}" class="form-control text-right input-sm autoNum" autocomplete="off" name="nquantity_stock_jahit${i}" value="0"></td>`;
        cols +=
            `<td><input type="text" readonly id="nquantity_stock_pengadaan${i}" class="form-control text-right input-sm autoNum" autocomplete="off" name="nquantity_stock_pengadaan${i}" value="0"></td>`;
        cols +=
            `<td><input type="text" readonly id="nquantity_stock_packing${i}" class="form-control text-right input-sm autoNum" autocomplete="off" name="nquantity_stock_packing${i}" value="0"></td>`;
        cols +=
            `<td><input type="text" id="persen_up${i}" class="form-control persen_up text-right input-sm" autocomplete="off" name="persen_up${i}" onblur='if(this.value==""){this.value="0";}' onfocus='if(this.value=="0"){this.value="";}' value="0" onclick="ambilAngka(${i})" oninput="hetang(${i}); hitungPersen();" ></td>`;
        cols +=
            `<td><input type="text" id="estimasi${i}" class="form-control text-right input-sm" autocomplete="off" name="estimasi${i}" readonly value="0"></td>`;
        cols +=
            `<td><input type="text" id="nquantity${i}" class="form-control text-right input-sm inputitem autoNum" autocomplete="off" name="nquantity${i}" onblur='if(this.value==""){this.value="0";}' onfocus='if(this.value=="0"){this.value="";}' value="0" onclick="ambilAngka(${i})" oninput="hetangQty(${i})" ></td>`;
        cols +=
            `<td><input type="text" class="form-control input-sm" name="eremark${i}" id="eremark${i}" placeholder="Isi keterangan jika ada!"/></td>`;
        cols +=
            `<td class="text-center"><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>`;
        newRow.append(cols);
        $("#tabledatax").append(newRow);
        $('#idproduct' + i).select2({
            placeholder: 'Cari Kode / Nama Barang Jadi',
            /* allowClear: true,
            width: "100%", */
            type: "POST",
            ajax: {
                url: '<?= base_url($folder . '/cform/product/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    var query = {
                        q: params.term,
                    }
                    return query;
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        }).change(function(event) {
            /**
             * Cek Barang Sudah Ada
             * Get Harga Barang
             */
            var z = $(this).data('nourut');
            var check = $(this).val();
            var ada = true;
            for (var x = 1; x <= $('#jml').val(); x++) {
                if ($(this).val() != null) {
                    if ((($(this).val()) == $('#idproduct' + x).val()) && (z != x)) {
                        swal("kode barang tersebut sudah ada !!!!!" + check);
                        ada = false;
                        break;
                    }
                    else {
                        var idproduct= $(this).val();

                        $.ajax({
                            type: "POST",
                            url: "<?= base_url($folder . '/cform/classproduct/'); ?>",
                            data: "idproduct=" + idproduct,
                            dataType: 'json',
                            success: function(data) {
                                $('#kategori'+i).html(data.eclassname);
                            },

                            error: function(XMLHttpRequest) {
                                alert(XMLHttpRequest.responseText);
                            }

                        })
                    }
                }
            }
            if (!ada) {
                $(this).val('');
                $(this).html('');
            } else {
                $('#nquantity' + z).focus();
            }
        });

        
        // var total  = '';
        // var check  = '';
        // var a      = '';
            
        // $('#nquantity'+ i).on('click', function () {
        //     check = $(this).val();
        //     a = $('#h_jml').text();
        //     a = a.split(".");
        //     a = a.join("");
        //     a = parseInt(a,0);
        //     if(check !== ""){
        //     check = check.split(".");
        //     check = check.join("");
        //     check = parseInt(check,0);
        //     }
        // });

        // $('#nquantity'+ i).on('keyup', function () {
        //     var b = $(this).val();
        //     b = b.split(".");
        //     b = b.join("");
        //     b = parseInt(b,0);
        //     total = a + b - check;
        //     total = toCommas(total);
        //     document.getElementById("h_jml").innerHTML = total;
        // });

    });

    /** Sum Dynamic */

    // var itotal = 0;
    // var i      = <?php echo $i; ?>;
    // var total  = '';
    // var check  = '';
    // var a      = '';

    // for(itotal;itotal<i;itotal++){
        
    //     $('#nquantity'+ itotal).on('click', function () {
    //         check = $(this).val();
    //         a = $('#h_jml').text();
    //         a = a.split(".");
    //         a = a.join("");
    //         a = parseInt(a,0);
    //         if(check !== ""){
    //         check = check.split(".");
    //         check = check.join("");
    //         check = parseInt(check,0);
    //         }
    //     });

    //     $('#nquantity'+ itotal).on('keyup', function () {
    //         var b = $(this).val();
    //         b = b.split(".");
    //         b = b.join("");
    //         b = parseInt(b,0);
    //         total = a + b - check;
    //         total = toCommas(total);
    //         document.getElementById("h_jml").innerHTML = total;
    //     });

    // }

    /** Auto Numeric */

    // new AutoNumeric.multiple('.autoNum', {
    //     aSep: '.', 
    //     aDec: ',',
    //     decimalPlaces:'0',
    //     aForm: true,
    //     unformatOnSubmit: true,
    //     vMax: '999999999999',
    //     vMin: '-999999999999',

    // }); 

    /**
     * Hapus Detail Item
     */

    $("#tabledatax").on("click", ".ibtnDel", function(event) {
        $(this).closest("tr").remove();

        $('#jml').val(i);
        var obj = $('#tabledatax tr:visible').find('spanx');
        $.each(obj, function(key, value) {
            id = value.id;
            $('#' + id).html(key + 1);
        });
    });

    function toCommas(value) {
        return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    var nquantity_fc = '';
    var nquantity_stock = '';
    var nquantity_stock_wip = '';
    var nquantity_stock_jahit = '';
    var nquantity_stock_pengadaan = '';
    var nquantity_stock_packing = '';
    var persen_up = '';
    var estimasi = '';
    var nquantity = '';
    var h_jmlh = '';
    var total = '';

    function ambilAngka (i){
        nquantity_fc                = parseFloat($('#nquantity_fc'+i).val());
        nquantity_stock             = parseFloat($('#nquantity_stock'+i).val());
        nquantity_stock_wip         = parseFloat($('#nquantity_stock_wip'+i).val());
        nquantity_stock_jahit       = parseFloat($('#nquantity_stock_jahit'+i).val());
        nquantity_stock_pengadaan   = parseFloat($('#nquantity_stock_pengadaan'+i).val());
        nquantity_stock_packing   = parseFloat($('#nquantity_stock_packing'+i).val());
        persen_up                   = parseFloat($('#persen_up'+i).val());
        estimasi                    = parseFloat($('#estimasi'+i).val());
        nquantity                   = parseFloat($('#nquantity'+i).val());
        h_jmlh                      = $('#h_jml').text();
        h_jmlh                      = h_jmlh.split(".");
        h_jmlh                      = h_jmlh.join("");
        h_jmlh                      = parseFloat(h_jmlh,0);
    }

    function hetang(i) {
        var nquantity = parseFloat($('#nquantity'+i).val());
        var nquantity_tmp = parseFloat($('#nquantity_tmp'+i).val());
        var persen = parseFloat($('#persen_up'+i).val());
        // var n_qty = nquantity_fc - Math.abs(nquantity_stock + nquantity_stock_wip + nquantity_stock_jahit + nquantity_stock_pengadaan);
        // var n_qty_up = Math.round(n_qty * ($('#persen_up'+i).val()/100));
        // //alert(n_qty + "-" + n_qty_up + "-" + estimasi);
        // parseFloat($('#nquantity'+i).val(n_qty + n_qty_up - estimasi));

        // total = 0; 

        // for (var x=1; x<=$('#jml').val(); x++) {
        //     total += parseFloat($('#nquantity'+x).val())
        // }
        // //alert(total);
        // $('#h_jml').text(toCommas(parseFloat(total)));

         var n_qty_up = 0;
        if (nquantity_tmp >= 0) {
            n_qty_up = nquantity_tmp + persen;
        } else {
            n_qty_up = persen;
        }
        
        //alert(n_qty + "-" + n_qty_up + "-" + estimasi);
        parseFloat($('#nquantity'+i).val(n_qty_up));

        total = 0; 

        for (var x=1; x<=$('#jml').val(); x++) {
            total += parseFloat($('#nquantity'+x).val())
        }
        //alert(total);
        $('#h_jml').text(toCommas(parseFloat(total)));
    }

    function hetangQty(i){
        var getQty = parseFloat($('#nquantity'+i).val());
        if(isNaN(nquantity)){
            nquantity = 0;
        }
        total = h_jmlh + getQty - nquantity; 

        $('#h_jml').text(toCommas(parseFloat(total)));
    }

    function hitungPersen()
    {
        let total = 0;
        $("#tabledatax tbody tr td .persen_up").each(function() {
            total += parseFloat($(this).val());
        });
        $('#h_qty_up').text(total);
    }
</script>