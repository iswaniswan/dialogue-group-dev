<style>
    .bold {
        font-weight: bold;
    }
</style>
<?= $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i><?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-3">Tanggal Dokumen</label>
                        <label class="col-md-3">Tujuan</label>
                        <div class="col-sm-3">
                            <input type="hidden" name="xbagian" id="xbagian" value="<?= $data->i_bagian; ?>">
                            <select name="ibagian" id="ibagian" class="form-control select2">
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
                                <input type="hidden" name="ibonkold" id="ibonkold" value="<?= $data->i_keluar_qc; ?>">
                                <input type="text" name="ibonk" id="dokumenbon" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number; ?>" maxlength="17" class="form-control input-sm" value="<?= $data->i_keluar_qc; ?>" aria-label="Text input with dropdown button">
                                <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span>
                            </div>
                            <span class="notekode">Format : (<?= $number; ?>)</span><br>
                            <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="dbonk" name="dbonk" class="form-control input-sm date" required="" readonly value="<?= $data->d_keluar_qc; ?>">
                        </div>
                        <?php /*
                        <div class="col-sm-3">
                            <select name="itujuan" id="itujuan" class="form-control select2">
                                <?php if ($tujuan) {
                                    foreach ($tujuan as $row) : ?>
                                        <option value="<?= $row->i_bagian; ?>" <?php if ($row->i_bagian == $data->i_tujuan) { ?> selected <?php } ?>>
                                            <?= $row->e_bagian_name; ?>
                                        </option>
                                <?php endforeach;
                                } ?>
                            </select>
                        </div>
                        */?>
                        <div class="col-sm-3">
                            <select name="itujuan" id="itujuan" class="form-control select2" onchange="number();">
                                <?php if ($tujuan) {
                                $group = "";
                                foreach ($tujuan as $row) : ?>
                                <?php if ($group!=$row->name) {?>
                                <optgroup label="<?= strtoupper(str_replace(".","",$row->name));?>">
                                    <?php }
                                    $group = $row->name;
                                    ?>
                                    <?php $selected = '';
                                        if (($row->i_bagian == $data->i_tujuan) and ($row->id_company == $data->id_company_tujuan)) {
                                            $selected = 'selected';
                                        }
                                    ?>
                                    <option value="<?= "$row->id_company|$row->i_bagian"; ?>" <?= $selected ?>>
                                        <?= $row->e_bagian_name; ?>
                                    </option>
                                    <?php endforeach;
                                    } ?>
                                </optgroup>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Jenis Barang Keluar</label>
                        <label class="col-md-9">Keterangan</label>
                        <div class="col-sm-3">
                            <select name="ijenis" id="ijenis" class="form-control select2" data-placeholder="Select Jenis Barang">
                                <option value="<?= $data->id_jenis_barang_keluar; ?>"><?= $data->e_jenis_name; ?></option>
                                <!--  <?php if ($jenisbarang) {
                                            foreach ($jenisbarang as $row) : ?>
                                        <option value="<?= $row->id; ?>" <?php if ($row->id == $data->id_jenis_barang_keluar) { ?> selected <?php } ?>>
                                            <?= $row->e_jenis_name; ?>
                                        </option>
                                <?php endforeach;
                                        } ?> -->
                            </select>
                        </div>
                        <div class="col-sm-9">
                            <textarea id="eremark" name="eremark" placeholder="Isi Keterangan Jika Ada!!!" class="form-control"><?= $data->e_remark; ?></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <?php if ($data->i_status == '1' || $data->i_status == '3' || $data->i_status == '7') { ?>
                                <button type="submit" id="submit" class="btn btn-success btn-sm mr-2" onclick="return konfirm();"><i class="fa fa-save mr-2"></i>Update</button>
                            <?php } ?>
                            <?php if ($data->i_status == '2') { ?>
                                <button type="button" id="addrow" class="btn btn-info btn-sm mr-2" hidden="true"><i class="fa fa-plus mr-2"></i>Item</button>
                            <?php } else { ?>
                                <button type="button" id="addrow" class="btn btn-info btn-sm mr-2"><i class="fa fa-plus mr-2"></i>Item</button>
                            <?php } ?>
                            <button type="button" class="btn btn-inverse btn-sm mr-2" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;"><i class="fa fa-arrow-circle-left mr-2"></i>Kembali</button>
                            <?php if ($data->i_status == '1') { ?>
                                <button type="button" id="send" class="btn btn-primary btn-sm mr-2"><i class="fa fa-paper-plane-o mr-2"></i>Send</button>
                                <button type="button" id="hapus" class="btn btn-danger btn-sm mr-2"><i class="fa fa-trash mr-2"></i>Delete</button>
                            <?php } elseif ($data->i_status == '2') { ?>
                                <button type="button" id="cancel" class="btn btn-primary btn-sm mr-2"><i class="fa fa-refresh mr-2"></i>Cancel</button>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="white-box" id="detail">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-11">
                <h3 class="box-title m-b-0">Detail Barang</h3>
            </div>
            <div class="col-sm-1" style="text-align: right;">
                <?= $doc; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 3%;">No</th>
                                <th style="width: 10%;">Kode Barang</th>
                                <th style="width: 37%;">Nama Barang Jadi</th>
                                <th style="width: 10%;">Warna</th>
                                <th class="text-right" style="width: 10%;">Quantity</th>
                                <th colspan="2" style="width: 30%;">Keterangan</th>
                                <th style="width: 5%;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $j = 0;
                            $k = 1;
                            $counter = 0;
                            $group = "";
                            $group2 = "";
                            if ($detail) {
                                foreach ($detail as $row) {
                                    if ($data->id_jenis_barang_keluar == '1') {
                                        $saldo_akhir = $row->saldo_akhir;
                                    } else {
                                        $saldo_akhir = $row->saldo_akhir_repair;
                                    }
                                    if ($group != $row->id_product) {
                                        $counter++; 
                                        $j = 0; ?>

                                        <tr class='no tr<?= $counter; ?>'>
                                            <td class="text-center">
                                                <spanx id="snum<?= $counter; ?>"><?= $counter; ?></spanx>
                                            </td>
                                            <td>
                                                <input value="<?= $row->id_product; ?>" type="hidden" readonly id="idproduct<?= $counter; ?>" class="form-control" name="idproduct[]">
                                                <input value="<?= $row->id_marker; ?>" type="hidden" readonly id="idmarker<?= $counter; ?>" class="form-control" name="idmarker[]">
                                                <input value="<?= $row->i_product_base; ?>" type="text" readonly id="iproduct<?= $counter; ?>" class="form-control input-sm" name="iproduct<?= $counter; ?>">
                                            </td>
                                            <td>
                                                <select type="text" data-placeholder="Pilih Barang" id="eproduct<?= $counter; ?>" class="form-control select2_product" name="eproduct<?= $counter; ?>" onchange="getproduct(<?= $counter; ?>); getstok(<?= $counter; ?>);">
                                                    <option value="<?= $row->id_product; ?>"><?= $row->e_product_basename; ?></option>
                                                </select>
                                                <select type="text" data-placeholder="Pilih Marker" id="emarker<?= $counter; ?>" class="form-control select2_marker" name="emarker<?= $counter; ?>" onchange="getproduct(<?= $counter; ?>); getstok(<?= $counter; ?>);">
                                                    <option value="<?= $row->id_marker; ?>"><?= $row->e_marker_name; ?></option>
                                                </select>
                                                <input type="hidden" id="stok<?= $counter; ?>" name="stok<?= $counter; ?>" value="<?= $saldo_akhir; ?>">
                                            </td>
                                            <td><input type="hidden" value="<?= $row->id_color; ?>" id="idcolorproduct<?= $counter; ?>" name="idcolorproduct[]"><input type="text" value="<?= $row->e_color_name; ?>" readonly id="ecolorproduct<?= $counter; ?>" class="form-control input-sm" name="ecolorproduct<?= $counter; ?>"></td>
                                            <td><input type="text" value="<?= $row->n_quantity_product; ?>" id="nquantity<?= $counter; ?>" class="form-control input-sm text-right inputitem" name="nquantity[]" onblur="if(this.value=='' ){this.value='0' ;}" onfocus="if(this.value=='0' ){this.value='' ;}" onkeyup="angkahungkul(this);validasi(<?= $counter; ?>);berhitung(<?= $counter; ?>)"></td>
                                            <td colspan="2"><input value="<?= $row->e_remark; ?>" type="text" id="edesc<?= $counter; ?>" class="form-control input-sm" placeholder="Keterangan..." name="edesc[]"></td>
                                            <td class="text-center"><button data-urut="<?= $counter ?>" type="button" onclick="tambah_material(<?= $counter ?>);" title="Tambah List" class="btn btn-sm btn-circle btn-info"><i data-urut="<?= $counter ?>" id="addlist<?= $counter ?>"  class="fa fa-plus-circle fa-lg" aria-hidden="true"></i></button><button type="button" data-i="<?= $counter; ?>" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>
                                        </tr>
                                        <?php
                                        // if ($group2 != $row->id_keluar_qc_item) { ?>
                                            <tr class="table-active tr_second<?= $counter; ?>">
                                                <td class="text-center"><i class="fa fa-hashtag fa-lg"></i></a></td>
                                                <td colspan="8"><b>Bundling Produk</b></td>
                                            </tr>
                                            <?php /* $o = 1; */ foreach($bundling as $b) {
                                                if($b->id_keluar_qc_item == $row->id) { 
                                            ?>
                                            <tr class="no tr_bundling<?= $counter ?>">
                                                <td class="text-center">
                                                    <i class="fa fa-check-circle-o fa-lg text-success" aria-hidden="true"></i>
                                                </td>
                                                <td colspan="3">
                                                    <select type="text" data-placeholder="Pilih Barang" id="eproduct_bundle<?= $counter ?><?= $k;?>"   class="form-control" name="eproduct_bundle<?= $counter ?><?= $k;?>"><option value="<?= $b->id_product ?>"><?= $b->e_product_basename ?></option></select>
                                                    <script>
                                                        // for(let i = 1; i<=parseInt($('#jml_item').val()); i++) {
                                                            // $(`#eproduct_bundle<?= $counter ?><?= $k;?>`).select2({
                                                            //     placeholder: 'Cari Berdasarkan Nama / Kode',
                                                            //     templateSelection: formatSelection,
                                                            //     allowClear: true,
                                                            //     width: "100%",
                                                            //     ajax: {
                                                            //         url: '<?= base_url($folder . '/cform/dataproduct'); ?>',
                                                            //         dataType: 'json',
                                                            //         delay: 250,
                                                            //         processResults: function(data) {
                                                            //             return {
                                                            //                 results: data
                                                            //             };
                                                            //         },
                                                            //         cache: true
                                                            //     }
                                                            // });
                                                        // }
                                                        $(`#eproduct_bundle<?= $counter ?><?= $k;?>`).select2({
                                                            width: "100%",
                                                        });
                                                    </script>
                                                </td>
                                                <td  class="d-flex justify-content-between">
                                                    <input type="text" id="n_qty_bundle_<?= $counter ?>_<?= $k;?>" class="form-control text-right input-sm" name="n_qty_bundle<?= $counter ?><?= $k;?>" value="<?= $b->n_quantity_bundling ?>" onblur='if(this.value==""){this.value="0";}' onfocus='if(this.value=="0"){this.value="";}' onkeyup="angkahungkul(this);validasi(<?= $counter ?>);berhitung(<?= $counter ?>)">
                                                </td>
                                                <td colspan="2">
                                                    <?= $b->e_remark; ?>
                                                </td>
                                                <td class="text-center"><button type="button" title="Delete" data-b = "<?= $counter ?>" class="ibtnDel btn-sm btn btn-circle btn-warning"><i class="fa fa-lg fa-minus-circle" aria-hidden="true"></i></td>
                                            </tr>
                                            <?php
                                                $k++; } /* $o++; */
                                            } ?>
                                        <?php // }
                                        //$group = $row->id_keluar_qc_item;
                                        ?>
                                        <tr class="th<?= $counter; ?> bold">
                                            <td class="text-center"><i class="fa fa-hashtag fa-lg" aria-hidden="true"></i></td>
                                            <td>Kode Material</td>
                                            <td>Nama Material</td>
                                            <td>Satuan</td>
                                            <td class="text-right">Kebutuhan<br>Per PCs</td>
                                            <td class="text-right">Stock Acc<br>Packing</td>
                                            <td class="text-right">Kebutuhan<br>Material</td>
                                            <td class="text-center"><i class="fa fa-list-ul fa-lg" aria-hidden="true"></i></td>
                                        </tr>
                                    <?php }
                                    $group = $row->id_product;
                                    ?>
                                    <tr class="td<?= $counter; ?>">
                                        <td class="text-center"><i class="fa fa-check-circle-o fa-lg text-info" aria-hidden="true"></i></td>
                                        <td><?= $row->i_material;?></td>
                                        <td><?= $row->e_material_name;?></td>
                                        <td><?= $row->e_satuan_name;?></td>
                                        <td class="text-right">
                                            <span id="n_kebutuhan_perpcs<?= $counter; ?>_<?= $j;?>">
                                            <?= number_format($row->n_kebutuhan, 4, ".", ",")?>
                                        </span>
                                        </td>
                                        <td class="text-right">
                                            <span id="n_stock_material<?= $counter; ?>_<?= $j;?>">
                                            <?= number_format($row->n_saldo_akhir, 4, ".", ",")?>
                                        </span>
                                        </td>
                                        <td class="text-right">
                                            <span class="reset_<?= $counter; ?>" id="n_kebutuhan_material<?= $counter; ?>_<?= $j;?>">
                                            <?= number_format($row->n_kebutuhan_material, 4, ".", ",")?>
                                        </span>
                                        </td>
                                        <td class="text-center"><i class="fa <?= ($row->n_saldo_akhir > 0) ? 'fa-thumbs-o-up fa-lg text-success' : 'fa-thumbs-o-down fa-lg text-danger' ;?>" aria-hidden="true"></i></td>
                                    </tr>


                                    <!-- <tr>
                                    <td class="text-center">
                                        <spanx id="snum<?= $i; ?>"><?= $i; ?></spanx>
                                    </td>
                                    <td>
                                        <input type="hidden" value="<?= $row->id_product; ?>" id="idproduct<?= $i; ?>" name="idproduct[]">
                                        <input type="text" value="<?= $row->i_product_base; ?>" readonly id="iproduct<?= $i; ?>" name="iproduct[]" class="form-control input-sm">
                                    </td>
                                    <td>
                                        <select id="eproduct<?= $i; ?>" class="form-control select2" name="eproduct[]" onchange="getproduct(<?= $i; ?>);">
                                            <option value="<?= $row->id_product; ?>"><?= $row->e_product_basename; ?></option>
                                        </select>
                                        <input type="hidden" id="stok<?= $i; ?>" name="stok<?= $i; ?>" value="<?= $saldo_akhir; ?>">
                                    </td>
                                    <td>
                                        <input type="hidden" value="<?= $row->id_color; ?>" id="idcolorproduct<?= $i; ?>" name="idcolorproduct[]">
                                        <input type="text" value="<?= $row->e_color_name; ?>" readonly id="ecolorproduct<?= $i; ?>" name="ecolorproduct[]" class="form-control input-sm">
                                    </td>
                                    <td>
                                        <input type="text" value="<?= $row->n_quantity_product; ?>" id="nquantity<?= $i; ?>" class="form-control text-right input-sm inputitem" autocomplete="off" name="nquantity[]" onblur="if(this.value==''){this.value='0';}" onblur='if(this.value==""){this.value="0";}' onfocus='if(this.value=="0"){this.value="";}' onkeyup="angkahungkul(this); validasi(<?= $i; ?>);">
                                    </td>
                                    <td>
                                        <input type="text" id="edesc<?= $i; ?>" class="form-control input-sm" value="<?= $row->e_remark; ?>" name="edesc[]">
                                    </td>
                                    <td class="text-center">
                                        <button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button>
                                    </td>
                                </tr> -->
                            <?php
                                    $j++;
                                }
                            } ?>
                            <input type="hidden" name="jml" id="jml" value="<?= $counter; ?>">
                            <input type="hidden" name="jml_item" id="jml_item" value="<?= $k; ?>">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</form>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
    $(document).ready(function() {
        $('#dokumenbon').mask('SSS-0000-0000S');
        $('#itujuan').select2();
        $('.select2_product').select2({
            width: '70%',
        });
        $('.select2_marker').select2({
            width: '30%',
        });
        showCalendar('.date');
        $('#ibagian').select2({
            placeholder: 'Pilih Bagian',
            width: '100%',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder . '/cform/bagian'); ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    var query = {
                        q: params.term,
                        ibagian: $('#xbagian').val(),
                    }
                    return query;
                },
                processResults: function(data) {
                    return {
                        results: data,
                    };
                },
                cache: false
            }
        });

        $('#itujuan').change(function(event) {
            $('#ijenis').html('');
            $('#ijenis').val('');
        });

        $('#ijenis').select2({
            placeholder: 'Pilih Jenis Barang',
            width: '100%',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder . '/cform/jenis_barang'); ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    var query = {
                        q: params.term,
                        'itujuan': $('#itujuan').val()
                    }
                    return query;
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                cache: false
            }
        }).change(function(event) {
            for (let index = 1; index <= parseInt($('#jml').val()); index++) {
                getstok(index);
            }
        });

        /* $('#ijenis').change(function(event) {
            for (let index = 1; index <= parseInt($('#jml').val()); index++) {
                getstok(index);
            }
        }); */

        var jmls = $('#jml').val();
        for (s = 1; s <= jmls; s++) {
            getstok(s);
        }
    });



    $("#dokumenbon").keyup(function() {
        $.ajax({
            type: "post",
            data: {
                'kode': $(this).val(),
                'ibagian': $('#ibagian').val(),
            },
            url: '<?= base_url($folder . '/cform/cekkode'); ?>',
            dataType: "json",
            success: function(data) {
                if (data == 1 && ($('#dokumenbon').val() != $('#ibonkold').val())) {
                    $("#ada").attr("hidden", false);
                    $("#submit").attr("disabled", true);
                } else {
                    $("#ada").attr("hidden", true);
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
            $("#dokumenbon").attr("readonly", false);
        } else {
            $("#dokumenbon").attr("readonly", true);
            $("#ada").attr("hidden", true);
            $("#dokumenbon").val($("#ibonkold").val());
            /*number();*/
        }
    });

    function number() {
        $.ajax({
            type: "post",
            data: {
                'tgl': $('#dbonk').val(),
                'ibagian': $('#ibagian').val(),
            },
            url: '<?= base_url($folder . '/cform/number'); ?>',
            dataType: "json",
            success: function(data) {
                $('#dokumenbon').val(data);
            },
            error: function() {
                swal('Error :)');
            }
        });
    }

    function tambah_material(i) {
        var ii = parseInt($('#jml_item').val()) + 1;
        var col = "";
        $('#jml_item').val(ii);
        var newRow = $("<tr class='no tr_bundling" + i + "'>");
        col += `
        <td class="text-center"><i class="fa fa-check-circle-o fa-lg text-success" aria-hidden="true"></i></td>
        <td colspan="3">
            <select type="text" data-placeholder="Pilih Barang" id="eproduct_bundle${i}${ii}" class="form-control" name="eproduct_bundle${i}${ii}"><option value=""></option></select>
        </td>
        <td><input type="text" id="n_qty_bundle_${i}_${ii}" class="form-control text-right input-sm" name="n_qty_bundle${i}${ii}" value="0" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' onkeyup="angkahungkul(this);validasi(${i});berhitung(${i})"></td>
        <td colspan="2"></td>
        <td class="text-center"><button type="button" title="Delete" data-b = "${i}" class="ibtnDel btn-sm btn btn-circle btn-warning"><i class="fa fa-lg fa-minus-circle" aria-hidden="true"></i></td>
        `;
        newRow.append(col);
        $(newRow).insertAfter("#tabledatax .tr_second" + i);

        $(`#eproduct_bundle${i}${ii}`).select2({
            placeholder: 'Cari Berdasarkan Nama / Kode',
            templateSelection: formatSelection,
            allowClear: true,
            width: "100%",
            ajax: {
                url: '<?= base_url($folder . '/cform/dataproduct'); ?>',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
        // $(`<tr class="table-active tr_second${i}">
        //         <td class="text-center"><i class="fa fa-hashtag fa-lg"></i></a></td>
        //         <td colspan="6"><b>Bundling Produk</b></td>
        //         <td class="text-center"><button type="button" data-i = "${i}" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>
        //     </tr>
        //     `).insertAfter("#tabledatax .tr" + i);
    }

    function restart() {
        var obj = $('#tabledatax tr:visible').find('spanx');
        $.each(obj, function(key, value) {
            id = value.id;
            $('#' + id).html(key + 1);
        });
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

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#addrow").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#send").attr("disabled", false);
    });

    var counter = $('#jml').val();
    //var counterx = counter-1;
    $("#addrow").on("click", function() {
        counter++;
        $("#tabledatax").attr("hidden", false);
        //  var iproduct = $('#iproduct'+counterx).val();
        count = $('#tabledatax .no').length + 1;
        // if ((iproduct==''||iproduct==null)&&(count>1)) {
        //     swal('Isi dulu yang masih kosong!!');
        //     counter = counter-1;
        //     counterx = counterx-1;
        //     return false;
        // }
        $('#jml').val(counter);
        var newRow = $("<tr class='no tr" + counter + "'>");
        var cols = "";

        cols += '<td class="text-center"><spanx id="snum' + counter + '">' + count + '</spanx></td>';
        cols += '<td><input type="hidden" readonly id="idproduct' + counter + '" class="form-control" name="idproduct[]"><input type="hidden" readonly id="idmarker' + counter + '" class="form-control" name="idmarker[]"><input type="text" readonly id="iproduct' + counter + '" class="form-control input-sm" name="iproduct' + counter + '"></td>';
        cols += '<td><select type="text" data-placeholder="Pilih Barang" id="eproduct' + counter + '" class="form-control" name="eproduct' + counter + '" onchange="getproduct(' + counter + '); getstok(' + counter + ');"><option value=""></option></select> <select type="text" data-placeholder="Pilih Marker" id="emarker' + counter + '" class="form-control" name="emarker' + counter + '" onchange="getproduct(' + counter + '); getstok(' + counter + ');"><option value=""></option></select><input type="hidden" id="stok' + counter + '" name="stok' + counter + '"></td>';
        cols += '<td><input type="hidden" id="idcolorproduct' + counter + '" name="idcolorproduct[]"><input type="text" readonly id="ecolorproduct' + counter + '" class="form-control input-sm" name="ecolorproduct' + counter + '"></td>';
        cols += '<td><input type="text" id="nquantity' + counter + '" class="form-control input-sm text-right inputitem" name="nquantity[]" value="0" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' onkeyup="angkahungkul(this);validasi(' + counter + ');berhitung(' + counter + ')"></td>';
        cols += '<td colspan="2"><input type="text" id="edesc' + counter + '" class="form-control input-sm" placeholder="Keterangan..." name="edesc[]"></td>';
        cols += '<td class="text-center"><button data-urut="' + counter + '" type="button" onclick="tambah_material(' + counter + ');" title="Tambah List" class="btn btn-sm btn-circle btn-info"><i data-urut="' + counter + '" id="addlist' + counter + '"  class="fa fa-plus-circle fa-lg" aria-hidden="true"></i></button><button type="button" data-i = "' + counter + '" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>';

        newRow.append(cols);
        $("#tabledatax tr:first").after(newRow);
        var newRow1 = $(
            `<tr class="table-active tr_second${counter}">
                <td class="text-center"><i class="fa fa-hashtag fa-lg"></i></a></td>
                <td colspan="7"><b>Bundling Produk</b></td>
            </tr>`);
        $(newRow1).insertAfter(`#tabledatax .tr${counter}`);
        // $("#tabledatax").append(newRow);
        restart();

        $('#eproduct' + counter).select2({
            placeholder: 'Cari Berdasarkan Nama / Kode',
            templateSelection: formatSelection,
            width: "70%",
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder . '/cform/dataproduct'); ?>',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });

        $('#emarker' + counter).select2({
            placeholder: 'Cari Nama Marker',
            allowClear: true,
            type: 'POST',
            width: "30%",
            ajax: {
                url: '<?= base_url($folder . '/cform/marker/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    var query = {
                        q: params.term,
                        id_product_wip: $('#eproduct' + counter).val()
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
        });
    });

    function formatSelection(val) {
        return val.name;
    }

    function getproduct(id) {
        ada = false;
        var a = $('#eproduct' + id).val();
        var b = $('#emarker' + id).val();
        var x = $('#jml').val();
        if(a.length > 0 && b.length > 0) {
            for (i = 1; i <= x; i++) {
                if ((a == $('#eproduct' + i).val()) && (i != x)) {
                    swal("Kode Barang sudah ada !!!!!");
                    ada = true;
                    break;
                } else {
                    ada = false;
                }
            }

            if (!ada) {
                var eproduct = $('#eproduct' + id).val();
                var emarker = $('#emarker' + id).val();
                $.ajax({
                    type: "post",
                    data: {
                        'eproduct': eproduct,
                        'emarker': emarker
                    },
                    url: '<?= base_url($folder . '/cform/getproduct'); ?>',
                    dataType: "json",
                    success: function(data) {
                        $('#idproduct' + id).val(data['data'][0]['id_product']);
                        $('#idmarker' + id).val(emarker);
                        $('#iproduct' + id).val(data['data'][0]['i_product_base']);
                        $('#idcolorproduct' + id).val(data['data'][0]['id_color']);
                        $('#ecolorproduct' + id).val(data['data'][0]['e_color_name']);
                        $('#nquantity' + id).val(0);
                        $('#nquantity' + id).focus();
                        if (data['detail'].length > 0) {
                            hapus_tr(id);
                            $(`
                            <tr class="th${id} bold">
                                <td class="text-center"><i class="fa fa-hashtag fa-lg" aria-hidden="true"></i></td>
                                <td>Kode Material</td>
                                <td>Nama Material</td>
                                <td>Satuan</td>
                                <td class="text-right">Kebutuhan<br>Per PCs</td>
                                <td class="text-right">Stock Acc<br>Packing</td>
                                <td class="text-right">Kebutuhan<br>Material</td>
                                <td class="text-center"><i class="fa fa-list-ul fa-lg" aria-hidden="true"></i></td>
                            </tr>
                            `).insertAfter(($("#tabledatax .tr_bundling" + id).length > 0) ? "#tabledatax .tr_bundling" + id : "#tabledatax .tr_second" + id);
                            /* "#tabledatax .tr" + id */ 
                            for (let j = 0; j < data['detail'].length; j++) {
                                var newRow = $(`<tr class="td${id}">`);
                                var cols = "";
                                cols += `
                                    <td class="text-center"><i class="fa fa-check-circle-o fa-lg text-info" aria-hidden="true"></i></td>
                                    <td>${data['detail'][j]['i_material']}</td>
                                    <td>${data['detail'][j]['e_material_name']}</td>
                                    <td>${data['detail'][j]['e_satuan_name']}</td>
                                    <td class="text-right"><span id="n_kebutuhan_perpcs${id}_${j}">${data['detail'][j]['n_kebutuhan']}</span></td>
                                    <td class="text-right"><span id="n_stock_material${id}_${j}">${data['detail'][j]['n_stock']}</span></td>
                                    <td class="text-right"><span class="reset_${id}" id="n_kebutuhan_material${id}_${j}">0</span></td>
                                    <td class="text-center"><i class="fa ${(data['detail'][j]['n_stock']) > 0 ? 'fa-thumbs-o-up fa-lg text-success' : 'fa-thumbs-o-down fa-lg text-danger'}" aria-hidden="true"></i></td>
                                `;
                                newRow.append(cols);
                                $(newRow).insertAfter("#tabledatax .th" + id);
                                // $("#tabledatax").append(newRow);         
                            }
                        } else {
                            hapus_tr(id);
                        }
                    },
                    error: function() {
                        swal('Error :)');
                    }
                });
            } else {
                $('#idproduct' + id).html('');
                $('#iproduct' + id).html('');
                $('#eproduct' + id).html('');
                $('#idcolorproduct' + id).html('');
                $('#ecolorproduct' + id).html('');
                $('#idproduct' + id).val('');
                $('#iproduct' + id).val('');
                $('#eproduct' + id).val('');
                $('#idcolorproduct' + id).val('');
                $('#ecolorproduct' + id).val('');
            }
        }
    }

    function getstok(id) {
        var idproduct = $('#eproduct' + id).val();
        var idmarker = $('#emarker' + id).val();

        var ibagian = $('#ibagian').val();
        if(idproduct.length > 0 && idmarker.length > 0) {
            $.ajax({
                type: "post",
                data: {
                    'idproduct': idproduct,
                    'ibagian': ibagian,
                },
                url: '<?= base_url($folder . '/cform/getstok'); ?>',
                dataType: "json",
                success: function(data) {
                    if ($('#ijenis').val() == '1' || $('#ijenis').val() == '4') {
                        $('#stok' + id).val(data.saldo_akhir);
                    } else {
                        $('#stok' + id).val(data.saldo_akhir_repair);
                    }
                    validasi(id);
                },
                error: function() {
                    swal('Error :)');
                }
            });
        }
    }

    $("#tabledatax").on("click", ".ibtnDel", function(event) {
        $(this).closest("tr").remove();
        $('#jml').val(counter);
        del();
        let no = $(this).data('i');
        $(`.tr_second${no}`).closest("tr").remove();
        $(`.tr_bundling${no}`).closest("tr").remove();
        hapus_tr($(this).data('i'));
    });

    function del() {
        obj = $('#tabledatax tr:visible').find('spanx');
        $.each(obj, function(key, value) {
            id = value.id;
            $('#' + id).html(key + 1);
        });
    }

    function hapus_tr(i) {
        $(`.th${i}`).closest("tr").remove();
        $(`.td${i}`).closest("tr").remove();
    }

    function berhitung(i) {
        var n_quantity_product = parseFloat($('#nquantity' + i).val());
        if (isNaN(n_quantity_product)) {
            n_quantity_product = 0;
        }
        var ada = 0;
        // console.log($(`#tabledatax .td${i}`).length);
        for (let j = 0; j < $(`#tabledatax .td${i}`).length; j++) {
            var n_kebutuhan_perpcs = parseFloat($('#n_kebutuhan_perpcs' + i + '_' + j).text());
            var n_stock_material = parseFloat($('#n_stock_material' + i + '_' + j).text());

            let nilaiMaterial = n_quantity_product * n_kebutuhan_perpcs;
            nilaiMaterial = parseFloat(nilaiMaterial).toFixed(4);

            $('#n_kebutuhan_material' + i + '_' + j).text(nilaiMaterial);
            var n_kebutuhan_material = parseFloat($('#n_kebutuhan_material' + i + '_' + j).text());
            if (n_kebutuhan_material > n_stock_material) {
                console.log(n_kebutuhan_material, n_stock_material)
                ada = 1;
                break;
            }
        }
        console.log(ada);

        if (ada > 0) {
            swal("Maaf :(", "Jumlah kebutuhan material tidak boleh melebihi stok, mohon untuk dicek kembali :)", "error");
            $('.reset_' + i).text(0);
            return ada;
        }
    }

    function validasi(id) {
        var jml = document.getElementById("jml").value;
        if (id === undefined) {
            for (i = 1; i <= jml; i++) {
                var nquantity = document.getElementById("nquantity" + i).value;
                var stok = document.getElementById("stok" + i).value;
                if (parseFloat(nquantity) > parseFloat(stok)) {
                    swal('Quantity Kirim Tidak Boleh Melebihi \nSaldo akhir ' + stok);
                    document.getElementById("nquantity" + i).value = stok;
                    return true;
                    break;
                }
                if (parseFloat(nquantity) == 0 && parseFloat(nquantity) == '') {
                    swal('Quantity Kirim Tidak Boleh 0 atau Kosong');
                    document.getElementById("nquantity" + i).value = stok;
                    return true;
                    break;
                }
            }
            return false;
        } else {
            var nquantity = document.getElementById("nquantity" + id).value;
            var stok = document.getElementById("stok" + id).value;
            if (parseFloat(nquantity) > parseFloat(stok)) {
                swal('Quantity Kirim Tidak Boleh Melebihi \nSaldo akhir ' + stok);
                document.getElementById("nquantity" + id).value = stok;

            }
            if (parseFloat(nquantity) == 0 && parseFloat(nquantity) == '') {
                swal('Quantity Kirim Tidak Boleh 0 atau Kosong');
                document.getElementById("nquantity" + id).value = stok;

            }
        }
    }

    /* function konfirm() {
        var jml = $('#jml').val();
        ada = false;
        if (jml == 0) {
            swal('Isi data item minimal 1 !!!');
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
                    if ($(this).val() == '' || $(this).val() == null || $(this).val() == 0) {
                        swal('Quantity Tidak Boleh Kosong Atau 0!');
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
    } */

    function konfirm() {
        var jml = $('#jml').val();
        var count = 0;
        for (let i = 1; i <= jml; i++) {
            berhitung(i);
            if (berhitung(i) > 0) {
                count = 1;
            };
        }
        // return false;
        ada = false;
        if (jml == 0) {
            swal('Isi data item minimal 1 !!!');
            return false;
        } else {
            /* alert(count);
            return false; */
            if (count == 0) {
                $("#tabledatax tbody tr").each(function() {
                    $(this).find("td select").each(function() {
                        if ($(this).val() == '' || $(this).val() == null) {
                            swal('Kode barang tidak boleh kosong!');
                            ada = true;
                        }
                    });
                    $(this).find("td .inputitem").each(function() {
                        if ($(this).val() == '' || $(this).val() == null || $(this).val() == 0) {
                            swal('Quantity Tidak Boleh Kosong Atau 0!');
                            ada = true;
                        }
                    });
                });
                if (!ada) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }
</script>