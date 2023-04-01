<!-- <form id="submit" method="post" action="<?= $folder; ?>/cform/insertspbnew"> -->
<form id="form" action="" method="post">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <i class="fa fa-check"></i> &nbsp; <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;&nbsp;<?= $title_list; ?></a>
                </div>
                <div class="panel-body table-responsive">
                    <div id="pesan"></div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-3">Bagian Pembuat</label>
                            <label class="col-md-3">Nomor Dokumen</label>
                            <label class="col-md-3">Tanggal Dokumen</label>
                            <label class="col-md-3">Jenis SPB</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control input-sm" value="<?= $data->e_bagian_name; ?>" readonly>
                                <input type="hidden" name="ibagianreff" id="ibagianreff" class="form-control" value="<?= $data->ibagian_reff; ?>" readonly>
                                <input type="hidden" name="id_jenis_barang_keluar" id="id_jenis_barang_keluar" class="form-control" value="<?= $data->id_jenis_barang_keluar; ?>" readonly>
                            </div>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="hidden" name="id" id="id" value="<?= $id; ?>">
                                    <input type="text" name="isj" id="isj" readonly="" autocomplete="off" class="form-control input-sm" value="<?= $data->i_document; ?>" readonly>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <input type="text" id="ddocument" name="ddocument" class="form-control input-sm" required="" readonly value="<?= $data->d_document; ?>">
                            </div>
                            <div class="col-sm-3">
                                <select name="iarea" id="iarea" class="form-control select2">
                                    <option value="<?= $data->id_area; ?>"><?= $data->e_area; ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3">Customer</label>
                            <label class="col-md-3">Nomor Referensi</label>
                            <label class="col-md-3">Tanggal Referensi</label>
                            <label class="col-md-3">Keterangan</label>
                            <div class="col-sm-3">
                                <select name="icustomer" id="icustomer" class="form-control select2">
                                    <option value="<?= $data->id_customer; ?>"><?= $data->e_customer_name; ?></option>
                                </select>
                                <input type="hidden" id="ndiskontotal" name="ndiskontotal" class="form-control" value="" readonly>
                            </div>
                            <div class="col-sm-3">
                                <select name="ireferensi" id="ireferensi" class="form-control select2">
                                    <option value="<?= $data->id_document_reff; ?>"><?= $data->i_referensi; ?> ~ <?= $data->e_jenis_spb ?></option>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <input type="text" id="dreferensi" name="dreferensi" class="form-control input-sm" value="<?= $data->d_referensi; ?>" readonly>
                                <input type="hidden" id="idkodeharga" name="idkodeharga" class="form-control input-sm" value="<?= $data->id_harga_kode; ?>" readonly>
                                <input type="hidden" id="ejenisspb" name="ejenisspb" class="form-control input-sm" value="<?= $data->e_jenis_spb; ?>" readonly>
                            </div>
                            <div class="col-sm-3">
                                <textarea id="eremark" name="eremark" class="form-control" readonly><?= $data->e_remark; ?></textarea>
                                <!-- hitungan u/ spb baru -->
                                <input type="hidden" id="nkotor" name="nkotor" class="form-control" value="" readonly>
                                <input type="hidden" id="nbersih" name="nbersih" class="form-control" value="" readonly>
                                <input type="hidden" id="vdpp" name="vdpp" class="form-control" value="" readonly>
                                <input type="hidden" id="vppn" name="vppn" class="form-control" value="" readonly>

                                <!-- hitungan u/ spb lama -->
                                <input type="hidden" id="nkotorold" name="nkotorold" class="form-control" value="" readonly>
                                <input type="hidden" id="nbersihold" name="nbersihold" class="form-control" value="" readonly>
                                <input type="hidden" id="vdppold" name="vdppold" class="form-control" value="" readonly>
                                <input type="hidden" id="vppnold" name="vppnold" class="form-control" value="" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-offset-3 col-sm-12">
                                <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                                <button type="button" class="btn btn-warning btn-rounded btn-sm" onclick="statuschange('<?= $folder . "','" . $data->id; ?>','1','<?= $dfrom . "','" . $dto; ?>');"> <i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;Change Requested</button>&nbsp;
                                <button type="button" class="btn btn-danger btn-rounded btn-sm" onclick="statuschange('<?= $folder . "','" . $data->id; ?>','4','<?= $dfrom . "','" . $dto; ?>');"> <i class="fa fa-times"></i>&nbsp;&nbsp;Reject</button>&nbsp;
                                <button type="button" onclick="return validation('<?= $data->e_jenis_spb ?>', '<?= $data->id_spb ?>');" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Approve</button>&nbsp;
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="white-box" id="detail">
                <div class="col-sm-5">
                    <h3 class="box-title m-b-0">Detail Barang</h3>
                    <div class="m-b-0">
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="table-responsive">
                        <table id="tabledatax" class="table color-table inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                            <thead>
                                <tr>
                                    <th class="text-center" width="3%;">No</th>
                                    <th class="text-center" width="6%;">Kode</th>
                                    <th class="text-center" width="23%;">Nama Barang</th>
                                    <th class="text-center" width="6%;">Warna</th>
                                    <th class="text-center" width="5%;">FC</th>
                                    <th class="text-center" width="8%;">Total Order <br>Belum proses</th>
                                    <th class="text-center" width="5%;">Stock</th>
                                    <th class="text-center" width="8%;">Stock - <br>DO Belum Approve</th>
                                    <th class="text-center" width="5%;">Qty <br> Order</th>
                                    <th class="text-center" width="5%;">Qty <br> SJ</th>
                                    <th class="text-center" width="10%;">Keterangan</th>
                                    <th class="text-center" width="10%;">Ket OP</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($datadetail) {
                                    $i = 0;
                                    foreach ($datadetail as $row) {
                                        $sisa_b = $row->nquantity_permintaan - $row->n_quantity;
                                        $i++;
                                ?>
                                        <tr>
                                            <td class="text-center"><?= $i; ?></td>
                                            <td>
                                                <input type="hidden" class="form-control input-sm" id="idproduct<?= $i; ?>" name="idproduct[]" value="<?= $row->id_product; ?>" readonly>
                                                <input type="hidden" class="form-control input-sm text-right" id="sisab<?= $i; ?>" name="sisab[]" value="<?= $sisa_b; ?>" readonly>
                                                <input type="hidden" class="form-control input-sm text-right" id="sisa<?= $i; ?>" name="sisa[]" value="<?= $row->nquantity_permintaan; ?>" readonly>
                                                <input type="hidden" class="form-control input-sm text-right" id="nquantity<?= $i; ?>" name="nquantity[]" value="<?= $row->n_quantity; ?>" readonly>
                                                <input type="hidden" class="form-control input-sm" id="vprice<?= $i; ?>" name="vprice[]" value="<?= $row->v_price; ?>" readonly>
                                                <input type="hidden" class="form-control input-sm" id="1ndiskon<?= $i; ?>" name="1ndiskon[]" value="<?= $row->n_diskon1; ?>" readonly>
                                                <input type="hidden" class="form-control input-sm" id="2ndiskon<?= $i; ?>" name="2ndiskon[]" value="<?= $row->n_diskon2; ?>" readonly>
                                                <input type="hidden" class="form-control input-sm" id="3ndiskon<?= $i; ?>" name="3ndiskon[]" value="<?= $row->n_diskon3; ?>" readonly>
                                                <input type="hidden" class="form-control input-sm" id="1vdiskon<?= $i; ?>" name="1vdiskon[]" value="" readonly>
                                                <input type="hidden" class="form-control input-sm" id="2vdiskon<?= $i; ?>" name="2vdiskon[]" value="" readonly>
                                                <input type="hidden" class="form-control input-sm" id="3vdiskon<?= $i; ?>" name="3vdiskon[]" value="" readonly>
                                                <input type="hidden" class="form-control input-sm" id="vdiskonadd<?= $i; ?>" name="vdiskonadd[]" value="<?= $row->v_diskon_tambahan; ?>" readonly>
                                                <input type="hidden" class="form-control input-sm" id="vtdiskon<?= $i; ?>" name="vtdiskon[]" value="" readonly>
                                                <input type="hidden" class="form-control input-sm" id="vtotal<?= $i; ?>" name="vtotal[]" value="" readonly>
                                                <input type="hidden" class="form-control input-sm" id="vtotalbersih<?= $i; ?>" name="vtotalbersih[]" value="" readonly>
                                                <input type="hidden" class="form-control input-sm" id="vtotalold<?= $i; ?>" name="vtotalold[]" value="" readonly>
                                                <input type="hidden" class="form-control input-sm" id="vtotalbersihold<?= $i; ?>" name="vtotalbersihold[]" value="" readonly>
                                                <input type="hidden" class="form-control input-sm" id="edesc<?= $i; ?>" name="edesc[]" value="<?= $row->e_remark; ?>" readonly>
                                                <?= $row->i_product_base; ?>
                                            </td>
                                            <td><?= $row->e_product_basename; ?></td>
                                            <td><?= $row->e_color_name; ?></td>
                                            <td class="text-right"><?= $row->n_quantity_fc; ?></td>
                                            <td class="text-right"><?= $row->n_quantity_sisa_total; ?></td>
                                            <td class="text-right"><?= $row->saldo_akhir; ?></td>
                                            <td class="text-right"><?= $row->n_stock_outstanding; ?></td>
                                            <td class="text-right"><?= $row->nquantity_permintaan; ?></td>
                                            <td class="text-right"><?= $row->n_quantity; ?></td>
                                            <td><?= $row->e_remark; ?></td>
                                            <td><?= $row->e_remark_op; ?></td>
                                        </tr>

                                    <?php
                                    } ?>
                                    <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                                <?php
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
</form>
<script>
    $(document).ready(function() {
        fixedtable($('#tabledatax'));
        $('.select2').select2();
        showCalendar('.date');
        $("#submit").on('click', function() {
            ada = false;
            var stock = 0;
            for (var i = 1; i <= $('#jml').val(); i++) {
                if (parseInt($('#nquantity' + i).val()) > parseInt($('#sisa' + i).val())) {
                    swal('Dokumen Referensi sudah pernah dibuat, silahkan dicek kembali');
                    ada = true;
                    return false;

                }
            }
            if (!ada) {
                for (var i = 1; i <= $('#jml').val(); i++) {
                    $.ajax({
                        url: '<?= base_url($folder . '/cform/get_stock/'); ?>',
                        type: "POST",
                        dataType: 'json',
                        data: {
                            id_product: $('#idproduct' + i).val(),
                            d_document: $('#ddocument').val(),
                            id_jenis_barang_keluar: $('#id_jenis_barang_keluar').val(),
                            x: i,
                        },
                        success: function(data) {
                            // alert(data['i']);
                            //alert($('#nquantity' + data['i']).val()+' = '+data['n_quantity']);
                            // alert()
                            if (parseInt($('#nquantity' + data['i']).val()) > data['n_quantity']) {
                                stock = 1;
                                swal("Maaf", "Stock ada yang kurang :(", "error");
                                $('#set').val(1);
                                //alert(stock);
                                //return false;
                            }
                        },
                        error: function() {
                            swal("Maaf", "Gagal cek stock :(", "error");
                        }
                    });
                }
                // alert($('#set').val());
                /* if ($('#set').val() == 0) {
                    alert('x'); */
                return false;
                for (var i = 1; i <= $('#jml').val(); i++) {
                    if (parseInt($('#sisab' + i).val()) > 0) {
                        cek = true;
                    } else {
                        cek = false;
                    }
                }
                if (cek) {
                    swal({
                        title: "Quantity masih belum terpenuhi!",
                        text: " Buat SPB baru?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        cancelButtonText: "Tidak, hanya Approve!",
                        confirmButtonText: "Ya, Buat SPB baru!",
                        closeOnConfirm: false,
                        closeOnCancel: false
                    }, function(isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                url: '<?= base_url($folder . '/cform/insertspbnew/'); ?>',
                                type: "POST", // type of action POST || GET
                                dataType: 'json', // data type
                                data: $("#form").serialize(), // post data || get data
                                success: function(data) {
                                    if (data.sukses == true) {

                                        // you can see the result from the console
                                        // tab of the developer tools
                                        // statuschange('<?= $folder; ?>', $('#id').val(), '6', '<?= $dfrom . "','" . $dto; ?>');
                                        swal("Success!", "Data berhasil Diapprove dan Disimpan menjadi SPB Baru :)", "success");
                                        show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>', '#main');
                                        //console.log(result);
                                        //return false;
                                    } else {
                                        swal("Maaf!", "Data gagal diapprove dan disimpan :(", "error");
                                    }
                                },
                                error: function() {
                                    swal("Maaf", "Data gagal insert :(", "error");
                                    //console.log(xhr, resp, text);
                                }
                            });
                        } else {
                            /*swal("Dibatalkan", "Anda membatalkan insert spb baru :)", "error");*/
                            statuschange('<?= $folder; ?>', $('#id').val(), '6', '<?= $dfrom . "','" . $dto; ?>');
                        }
                    });
                } else {
                    statuschange('<?= $folder; ?>', $('#id').val(), '6', '<?= $dfrom . "','" . $dto; ?>');
                }
            } else {
                return false;
            }
        });
    });

    function cubmit() {
        var ada = false;
        var cek = true;
        for (var i = 1; i <= $('#jml').val(); i++) {
            if (parseInt($('#nquantity' + i).val()) > parseInt($('#sisa' + i).val())) {
                swal('Dokumen Referensi sudah pernah dibuat, silahkan dicek kembali');
                ada = true;
                return false;

            } else {
                $.ajax({
                    url: '<?= base_url($folder . '/cform/get_stock/'); ?>',
                    type: "POST",
                    dataType: 'json',
                    data: {
                        id_product: $('#idproduct' + i).val(),
                        d_document: $('#ddocument').val(),
                        n_quantity: $('#nquantity' + i).val(),
                        id_jenis_barang_keluar: $('#id_jenis_barang_keluar').val(),
                    },
                    success: function(data) {
                        if (data['sukses'] == false) {
                            swal("Maaf", "Stock ada yang kurang :(", "error");
                            ada = true;
                            return false;
                        }
                    },
                    error: function() {
                        swal("Maaf", "Gagal cek stock :(", "error");
                    }
                });
            }
        }
        if (!ada) {
            for (var i = 1; i <= $('#jml').val(); i++) {
                if (parseInt($('#sisab' + i).val()) > 0) {
                    cek = true;
                } else {
                    cek = false;
                }
            }
            // alert(cek);
            // return false;
            if (cek) {
                swal({
                    title: "Quantity masih belum terpenuhi!",
                    text: " Buat SPB baru?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    cancelButtonText: "Tidak, hanya Approve!",
                    confirmButtonText: "Ya, Buat SPB baru!",
                    closeOnConfirm: false,
                    closeOnCancel: false
                }, function(isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            url: '<?= base_url($folder . '/cform/insertspbnew/'); ?>',
                            type: "POST", // type of action POST || GET
                            dataType: 'json', // data type
                            data: $("#form").serialize(), // post data || get data
                            success: function(data) {
                                if (data.sukses == true) {
                                    swal("Success!", "Data berhasil Diapprove dan Disimpan menjadi SPB Baru :)", "success");
                                    show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>', '#main');
                                } else {
                                    swal("Maaf!", "Data gagal diapprove dan disimpan :(", "error");
                                }
                            },
                            error: function() {
                                swal("Maaf", "Data gagal insert :(", "error");
                            }
                        });
                    } else {
                        statuschange('<?= $folder; ?>', $('#id').val(), '6', '<?= $dfrom . "','" . $dto; ?>');
                    }
                });
            } else {
                statuschange('<?= $folder; ?>', $('#id').val(), '6', '<?= $dfrom . "','" . $dto; ?>');
            }
        } else {
            return false;
        }
    }


    function validation(e_jenis_spb = null, id_spb = null) {
        $.ajax({
            url: '<?= base_url($folder . '/cform/approve_validation/'); ?>',
            type: "POST", // type of action POST || GET
            dataType: 'json', // data type
            data: {
                id: $('#id').val(),
                i_customer: $('#icustomer').val(),
                d_document: $('#ddocument').val(),
                e_jenis_spb: e_jenis_spb,
                id_spb: id_spb
            }, // post data || get data
            success: function(data) {
                /** Jika Dokumen SPB, sudah ada yang pernah Approve */
                if (data['sudah'] === true) {
                    swal('Dokumen Referensi sudah pernah dibuat, silahkan dicek kembali');
                    return false;
                }
                /** Jika Qty SJ Melebihi Stock */
                if (data['stock'] === true) {
                    swal("Maaf", "Stock ada yang kurang :(", "error");
                    return false;
                }

                /** Jika Qty SJ tidak Sama Dengan QTY SPB */
                if (data['turunan'] === true) {
                    swal({
                        title: "Quantity masih belum terpenuhi!",
                        text: " Buat SPB baru?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        cancelButtonText: "Tidak, hanya Approve!",
                        confirmButtonText: "Ya, Buat SPB baru!",
                        closeOnConfirm: false,
                        closeOnCancel: false
                    }, function(isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                url: '<?= base_url($folder . '/cform/insertspbnew/'); ?>',
                                type: "POST", // type of action POST || GET
                                dataType: 'json', // data type
                                data: $("#form").serialize(), // post data || get data
                                success: function(data) {
                                    if (data.sukses == true) {
                                        swal("Success!", "Data berhasil Diapprove dan Disimpan menjadi SPB Baru :)", "success");
                                        show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>', '#main');
                                    } else {
                                        swal("Maaf!", "Data gagal diapprove dan disimpan :(", "error");
                                    }
                                },
                                error: function() {
                                    swal("Maaf", "Data gagal insert :(", "error");
                                }
                            });
                        } else {
                            statuschange('<?= $folder; ?>', $('#id').val(), '6', '<?= $dfrom . "','" . $dto; ?>');
                        }
                    });
                }else{
                    statuschange('<?= $folder; ?>', $('#id').val(), '6', '<?= $dfrom . "','" . $dto; ?>');
                }
                // console.log(data);
            },
            error: function() {
                swal("Maaf", "Data gagal diapprove :(", "error");
            }
        });
    }
</script>