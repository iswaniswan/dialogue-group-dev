<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-4">Kode Barang</label>
                            <label class="col-md-8">Nama Barang</label>

                            <div class="col-sm-4">
                                <div class="input-group">
                                    <input type="hidden" id="id" name="id" required="" readonly value="<?= $data->id ?>">
                                    <input type="text" name="iproduct" id="iproduct" readonly="" class="form-control input-sm" value="<?= $data->i_product_wip; ?>">
                                    <input type="hidden" id="idproduct" name="idproduct" required="" readonly>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="row">
                                    <div class="col-sm-9">
                                        <select name="product" id="product" class="form-control select2" disabled>
                                            <option value="<?= $data->id . '-' . $data->i_product_wip . '-' . $data->i_color . '-' . $data->e_color_name; ?>" selected><?= $data->i_product_wip . ' - ' . $data->e_product_wipname . ' - ' . $data->e_color_name; ?></option>
                                        </select>
                                        <input type="hidden" id="icolor" name="icolor" required="" readonly value="<?= $data->i_color ?>">
                                    </div>
                                    <div class="col-sm-3">
                                        <select name="marker" id="marker" class="form-control select2" disabled>
                                            <option value="<?= $data->id_marker; ?>" selected><?= $data->e_marker_name; ?></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-12">Keterangan</label>
                            <div class="col-sm-12">
                                <textarea id="eremarkh" placeholder="Isi Keterangan Jika Ada!!!" name="eremarkh" class="form-control" disabled><?= $data->e_remark; ?></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/','#main'); return false;"><i class="ti-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;&nbsp;
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="white-box" id="detail">
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
                        <table id="tabledatax" class="table color-table inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                            <thead>
                                <tr>
                                    <th class="text-center" width="3%;">No</th>
                                    <th class="text-center">Jenis Kain</th>
                                    <th class="text-center">Bagian Panel</th>
                                    <th class="text-center">Kode Panel</th>
                                    <th class="text-right">Qty Penyusun</th>
                                    <th class="text-right">Panjang <sup>cm</sup></th>
                                    <th class="text-right">Lebar <sup>cm</sup></th>
                                    <th class="text-center" width="8%;">Panjang <br>Gelaran <sup>cm</sup></th>
                                    <th class="text-center" width="8%;">Lebar <br>Gelaran <sup>cm</sup></th>
                                    <th class="text-center" width="8%;">Hasil <br>Gelaran <sup>set</sup></th>
                                    <th class="text-center" width="8%;">Efficiency <br> Marker <sup>%</sup></th>
                                    <th class="text-right">Print</th>
                                    <th class="text-right">Bordir</th>
                                    <th class="text-center" width="4%;" hidden>Khusus <br>Pengadaan</th>
                                    <th class="text-center" width="30%;">Makloon</th>
                                    <!-- <th class="text-center">Keterangan</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 0;
                                foreach ($datadetail as $detail) {
                                    $i++; ?>
                                    <tr>
                                        <td class="text-center"><?= $i; ?></td>
                                        <td><?= $detail->i_material . ' - ' . $detail->e_material_name; ?></td>
                                        <td><?= $detail->bagian; ?></td>
                                        <td><?= $detail->i_panel; ?></td>
                                        <td class="text-right"><?= $detail->n_qty_penyusun; ?></td>
                                        <td class="text-right"><?= $detail->n_panjang_cm; ?></td>
                                        <td class="text-right"><?= $detail->n_lebar_cm; ?></td>

                                        <td class="text-right"><?= $detail->n_panjang_gelar; ?></td>
                                        <td class="text-right"><?= $detail->n_lebar_gelar; ?></td>
                                        <td class="text-right"><?= $detail->n_hasil_gelar; ?></td>
                                        <td class="text-right"><?= $detail->n_efficiency; ?></td>

                                        <td class="text-center"><input type="checkbox" <?php if($detail->f_print == 't'){echo "checked";} ?>></td>
                                        <td class="text-center"><input type="checkbox" <?php if($detail->f_bordir == 't'){echo "checked";} ?>></td>
                                        
                                        <td class="text-center" hidden><input type="checkbox" <?php if($detail->f_khusus_pengadaan == 't'){echo "checked";} ?>></td>
                                        <td><?= $detail->i_material_makloon . ' - ' . $detail->e_material_makloon; ?></td>
                                        <!-- <td><?= $detail->e_remark; ?></td> -->
                                    </tr>
                                <?php } ?>
                                <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        </form>

        <script>
            $(document).ready(function() {
                $('.select2').select2();

                $('#product').select2({
                    placeholder: 'Cari Kode / Nama WIP',
                    allowClear: true,
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

                    var produk = $(this).val().split("-");
                    $("#idproduct").val(produk[0]);
                    $("#iproduct").val(produk[1]);
                    $("#ecolor").val(produk[3]);
                    $("#icolor").val(produk[2]);

                    var jml = $("#jml").val();
                    if (jml > 0) {
                        for (i = 1; i <= jml; i++) {
                            $("#ipanel" + i).val(produk[1]);
                            $("#ebagian" + i).val('');
                        }
                    }

                })

                var jml = $("#jml").val();
                for (i = 1; i <= jml; i++) {
                    $('#imaterial' + i).select2({
                        placeholder: 'Cari Kode / Nama WIP',
                        allowClear: true,
                        width: "100%",
                        type: "POST",
                        ajax: {
                            url: '<?= base_url($folder . '/cform/material/'); ?>',
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
                    $('#ebagian' + i).keyup(function event() {
                        var id = $(this).data('nourut');
                        var ebagian = $("#ebagian" + id).val();
                        if (ebagian == "") {
                            $("#ipanel" + id).val(ipanel);
                        } else {
                            var matches = ebagian.match(/\b(\w)/g);
                            var bagian = matches.join('');
                            var ipanel = $("#iproduct").val();
                            $("#ipanel" + id).val(ipanel + '_' + bagian);
                        }
                    });
                }

                // showCalendar('.date', 1830, 0);
            });

            $("form").submit(function(event) {
                event.preventDefault();
                $("input").attr("disabled", true);
                $("select").attr("disabled", true);
                $("#submit").attr("disabled", true);
                $("#addrow").attr("disabled", true);
                $("#send").attr("hidden", false);
            });

            $('#send').click(function(event) {
                statuschange('<?= $folder; ?>', $('#id').val(), '2', '<?= $dfrom . "','" . $dto; ?>');
            });

            /**
             * Tambah Item
             */

            $("#addrow").on("click", function() {
                var i = $('#jml').val();
                //alert("tes");
                i++;
                $("#jml").val(i);
                var no = $('#tabledatax tr').length;
                var newRow = $('<tr id="tr' + i + '">');
                var ipanel = $("#iproduct").val();
                var cols = "";
                cols += '<td class="text-center">' + i + '</td>';
                cols += '<td><select data-nourut="' + i + '" id="imaterial' + i + '" class="form-control input-sm" name="imaterial' + i + '"></select></td>';
                cols += '<td><input data-nourut="' + i + '" class="form-control qty input-sm text-right" autocomplete="off" type="text" name="ebagian' + i + '" id="ebagian' + i + '"></td>';
                cols += '<td><input class="form-control input-sm" readonly type="text" id="ipanel' + i + '" name="ipanel' + i + '" value="' + ipanel + '"></td>';
                cols += '<td><input class="form-control input-sm" type="text" name="eremark' + i + '" id="eremark' + i + '" placeholder="Isi keterangan jika ada!"></td>';
                cols += `<td class="text-center"><button type="button" title="Delete" onclick="hapusdetail(${i});" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>`;
                cols += `</tr>`;
                newRow.append(cols);
                $("#tabledatax").append(newRow);
                $('#imaterial' + i).select2({
                    placeholder: 'Cari Kode / Nama WIP',
                    allowClear: true,
                    width: "100%",
                    type: "POST",
                    ajax: {
                        url: '<?= base_url($folder . '/cform/material/'); ?>',
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
                $('#ebagian' + i).keyup(function event() {
                    var id = $(this).data('nourut');
                    var ebagian = $("#ebagian" + id).val();
                    if (ebagian == "") {
                        $("#ipanel" + id).val(ipanel);
                    } else {
                        var matches = ebagian.match(/\b(\w)/g);
                        var bagian = matches.join('');
                        var ipanel = $("#iproduct").val();
                        $("#ipanel" + id).val(ipanel + '_' + bagian);
                    }
                });
            });

            /**
             * Hapus Detail Item
             */

            function hapusdetail(x) {
                $("#tabledatax tbody").each(function() {
                    $("tr.del" + x).remove();
                });
                var jml = $("#jml").val();
                var sisa = jml - 1;
                $("#jml").val(sisa);
            }

            $("#tabledatax").on("click", ".ibtnDel", function(event) {
                $(this).closest("tr").remove();
            });


            function konfirm() {
                var jml = $('#jml').val();
                ada = false;
                if (jml == 0) {
                    swal('Isi data item minimal 1 !!!');
                    return false;
                } else {
                    $("#tabledatax tbody tr").each(function() {
                        $(this).find("td select").each(function() {
                            if ($(this).val() == '' || $(this).val() == null) {
                                swal('Material tidak boleh kosong!');
                                ada = true;
                            }
                        });
                        // $(this).find("td input").each(function() {
                        //     if ($(this).val()=='' || $(this).val()==null) {
                        //         swal('Quantity Tidak Boleh Kosong Atau 0!');
                        //         ada = true;
                        //     }
                        // });

                    });
                    if (!ada) {
                        return true;
                    } else {
                        return false;
                    }
                }

            }
        </script>