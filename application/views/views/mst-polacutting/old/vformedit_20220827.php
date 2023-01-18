<link href="<?= base_url(); ?>assets/plugins/bower_components/switchery/dist/switchery.min.css" rel="stylesheet" />
<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-6">Kode dan Nama Barang</label>
                        <label class="col-md-6">Ceklist Untuk Mengedit Seluruh Warna</label>
                        <div class="col-sm-6">
                            <select name="iproductwip" id="iproductwip" required="" class="form-control select2" data-placeholder="Cari Barang WIP">
                                <option value="<?= $data->i_product_wip . '|' . $data->i_color; ?>"><?= $data->i_product_wip . ' - ' . $data->e_product_wipname . ' - ' . $data->e_color_name; ?></option>
                            </select>
                            <input type="hidden" name="iproductcolor" value="<?= $data->i_product_wip . '|' . $data->i_color; ?>">
                        </div>
                        <div class="col-sm-6">
                            <input type="checkbox" name="cek">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <button type="submit" id="submit" class="btn btn-success btn-block btn-sm"><i class="fa fa-save fa-lg mr-2"></i>Update</button>
                        </div>
                        <div class="col-sm-4">
                            <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder; ?>/cform','#main')"> <i class="fa fa-arrow-circle-left fa-lg mr-2"></i>Kembali</button>
                        </div>
                        <div class="col-sm-4">
                            <button type="button" id="addrow" onclick="tambah($('#jml').val());" class="btn btn-info btn-block btn-sm"><i class="fa fa-plus fa-lg mr-2"></i>Item</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if ($detail) { ?>
    <div class="white-box" id="detail">
        <div class="col-sm-5">
            <h3 class="box-title m-b-0">Detail Barang</h3>
        </div>
        <div class="col-sm-12">
            <div class="table-responsive">
                <table id="sitabel" class="table color-table success-table table-bordered class sitabel" cellpadding="8" cellspacing="1" width="100%">
                    <caption>Detail Barang</caption>
                    <thead>
                        <tr>
                            <th class="text-center" width="3%">No</th>
                            <th class="text-center" width="30%">Nama Material</th>
                            <!-- <th class="text-center" width="15%">Konversi ke Set</th>-->
                            <th class="text-center" width="10%">Gudang</th>
                            <th class="text-center" width="10%">Bagian</th>
                            <th class="text-center" width="8%">Gelar</th>
                            <th class="text-center" width="8%">Set</th>
                            <th class="text-center" width="10%;">Kebutuhan<br>Bis<sup>2</sup>an</th>
                            <th class="text-center" width="10%;">Ukuran<br>Bis<sup>2</sup>an</th>
                            <th class="text-center" width="20%;">Type Makloon</th>
                            <!-- <th class="text-center" width="5%;">Cutting</th>
                        <th class="text-center" width="5%;">Autocutter</th>
                        <th class="text-center" width="5%;">Badan</th>
                        <th class="text-center" width="5%;">Print</th>
                        <th class="text-center" width="5%;">Bordir</th>
                        <th class="text-center" width="5%;">Quilting</th> -->
                            <th class="text-center" width="3%">Act</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 0;
                        foreach ($detail as $key) {
                            $i++;
                        ?>
                            <tr>
                                <td class="text-center">
                                    <spanx id="snum<?= $i; ?>"><?= $i; ?></spanx>
                                </td>
                                <td class="col-5">
                                    <select data-nourut="<?= $i; ?>" id="imaterial<?= $i; ?>" class="form-control input-sm id" name="imaterial<?= $i; ?>">
                                        <option value="<?= $key->i_material; ?>"><?= $key->i_material . ' - ' . $key->e_material_name; ?></option>
                                    </select>
                                    <!-- <input type="hidden" id="vtoset<?= $i; ?>" class="form-control text-right input-sm inputitem" autocomplete="off" name="vtoset<?= $i; ?>" value="<?= $key->v_toset; ?>" onkeyup="angkahungkul(this);"> -->
                                </td>
                                <td><input type="text" readonly id="gudang<?= $i; ?>" class="form-control input-sm" name="gudang<?= $i; ?>" value="<?= $key->gudang; ?>"></td>
                                <td><input type="text" id="bagian<?= $i; ?>" class="form-control input-sm" autocomplete="off" name="bagian<?= $i; ?>" value="<?= $key->bagian; ?>"></td>
                                <td><input type="text" id="vgelar<?= $i; ?>" class="form-control text-right input-sm" autocomplete="off" name="vgelar<?= $i; ?>" value="<?= $key->v_gelar; ?>" onkeyup="angkahungkul(this);"></td>
                                <td><input type="text" id="vset<?= $i; ?>" class="form-control text-right input-sm" autocomplete="off" name="vset<?= $i; ?>" value="<?= $key->v_set; ?>" onkeyup="angkahungkul(this);"></td>
                                <td><input type="text" id="bis3<?= $i; ?>" class="form-control text-right input-sm" autocomplete="off" name="bis3<?= $i; ?>" value="<?= $key->n_bis3; ?>" onkeyup="angkahungkul(this);"></td>
                                <td>
                                    <select data-urut="<?= $i; ?>" id="id_bisbisan<?= $i; ?>" class="form-control input-sm" name="id_bisbisan<?= $i; ?>">
                                        <option value="<?= $key->id_bisbisan; ?>"><?= $key->n_bisbisan . ' - ' . $key->e_jenis_potong; ?></option>
                                    </select>
                                </td>
                                <!-- <td class="text-center"><input type="checkbox" id="f_cutting<?= $i; ?>" name="f_cutting<?= $i; ?>" <?php if ($key->f_cutting == 't') { ?> checked <?php } ?>></td>
                                <td class="text-center"><input type="checkbox" id="autocutter<?= $i; ?>" name="autocutter<?= $i; ?>" <?php if ($key->f_autocutter == 't') { ?> checked <?php } ?>></td>
                                <td class="text-center"><input type="checkbox" id="badan<?= $i; ?>" name="badan<?= $i; ?>" <?php if ($key->f_badan == 't') { ?> checked <?php } ?>></td>
                                <td class="text-center"><input type="checkbox" id="print<?= $i; ?>" name="print<?= $i; ?>" <?php if ($key->f_print == 't') { ?> checked <?php } ?>></td>
                                <td class="text-center"><input type="checkbox" id="bordir<?= $i; ?>" name="bordir<?= $i; ?>" <?php if ($key->f_bordir == 't') { ?> checked <?php } ?>></td>
                                <td class="text-center"><input type="checkbox" id="quilting<?= $i; ?>" name="quilting<?= $i; ?>" <?php if ($key->f_quilting == 't') { ?> checked <?php } ?>></td> -->
                                <td>
                                    <select data-urut="<?= $i; ?>" id="id_type_makloon<?= $i; ?>" class="form-control input-sm" multiple name="id_type_makloon<?= $i; ?>[]">
                                        <?php
                                        $makloon = json_decode($key->makloon);
                                        foreach ($makloon as $value) {
                                            // var_dump($value);
                                            // var_dump(is_null($value));
                                            if (is_null($value)==false) {
                                                // echo 'x';
                                                $id = explode("|", $value)[0];
                                                $name = explode("|", $value)[1];
                                                echo "<option value='$id' selected>'$name'</option>";
                                            }else{
                                                // echo 'y';
                                                /* $id = '';
                                                $name = ''; */
                                                echo "<option value=''></option>";
                                            }
                                        ?>
                                        <?php }
                                        ?>
                                    </select>
                                </td>
                                <td class="text-center"><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">
<?php } ?>
</form>
<script src="<?= base_url(); ?>assets/plugins/bower_components/switchery/dist/switchery.min.js"></script>
<script>
    $(document).ready(function() {        
        fixedtable($('#sitabel'));
        $('#iproductwip').select2({
            placeholder: 'Cari Barang WIP',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder . '/cform/productwip'); ?>',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        })

        for (var i = 1; i <= $('#jml').val(); i++) {
            /* $('.swit'+i).swit(i); */
            $('#imaterial' + i).select2({
                placeholder: 'Cari Kode / Nama Material',
                allowClear: true,
                width: "100%",
                type: "POST",
                ajax: {
                    url: '<?= base_url($folder . '/cform/material/'); ?>',
                    dataType: 'json',
                    delay: 250,
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
                        if ((($(this).val()) == $('#imaterial' + x).val()) && (z != x)) {
                            swal("kode : " + $(this).val() + " sudah ada !!!!!");
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

            $('#id_bisbisan' + i).select2({
                placeholder: 'Pilih Ukuran',
                allowClear: true,
                width: "100%",
                type: "POST",
                ajax: {
                    url: '<?= base_url($folder . '/cform/get_bisbisan/'); ?>',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        var z = $(this).data('urut');
                        var query = {
                            q: params.term,
                            i_material: $('#imaterial' + z).val(),
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

            $('#id_type_makloon' + i).select2({
                placeholder: 'Pilih Type Makloon',
                allowClear: true,
                width: "100%",
                type: "POST",
                ajax: {
                    url: '<?= base_url($folder . '/cform/get_type_makloon/'); ?>',
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
            });
        }
    })

    // var i = $('#jml').val();
    // $("#addrow").on("click", function() {
    //     i++;
    //     $("#jml").val(i);
    //     var no = $('#sitabel tr').length;
    //     var newRow = $("<tr class='d-flex'>");
    //     var cols = "";
    //     cols += `<td class="text-center col-1"><spanx id="snum${i}">${no}</spanx></td>`;
    //     cols += `<td class="col-5"><select data-nourut="${i}" id="imaterial${i}" class="form-control input-sm id" name="imaterial${i}" ></select><input type="hidden" id="vtoset${i}" name="vtoset${i}"></td>`;
    //     cols += `<td class="col-1"><input type="text" readonly id="gudang${i}" class="form-control input-sm" name="gudang${i}"></td>`;
    //     cols += `<td class="col-1"><input type="text" id="bagian${i}" class="form-control input-sm" autocomplete="off" name="bagian${i}"></td>`;
    //     cols += `<td class="col-1"><input type="text" id="vgelar${i}" class="form-control text-right input-sm" autocomplete="off" name="vgelar${i}" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);"></td>`;
    //     cols += `<td class="col-1"><input type="text" id="vset${i}" class="form-control text-right input-sm" autocomplete="off" name="vset${i}" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);"></td>`;
    //     cols += `<td class="col-2"><input type="text" id="bis3${i}" class="form-control text-right input-sm" autocomplete="off" name="bis3${i}" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);"></td>`;
    //     /* cols += `<td><input type="text" id="bis4${i}" class="form-control text-right input-sm" autocomplete="off" name="bis4${i}" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);"></td>`; */
    //     cols += `<td class="col-2"><select data-urut="${i}" id="id_bisbisan${i}" class="form-control input-sm" name="id_bisbisan${i}" ></select></td>`;
    //     cols += `<td class="text-center col-1"><input type="checkbox" id="f_cutting${i}" name="f_cutting${i}" checked></td>`;
    //     cols += `<td class="text-center col-1"><input type="checkbox" id="autocutter${i}" name="autocutter${i}"></td>`;
    //     cols += `<td class="text-center col-1"><input type="checkbox" id="badan${i}" name="badan${i}"></td>`;
    //     cols += `<td class="text-center col-1"><input type="checkbox" id="print${i}" name="print${i}"></td>`;
    //     cols += `<td class="text-center col-1"><input type="checkbox" id="bordir${i}" name="bordir${i}"></td>`;
    //     cols += `<td class="text-center col-1"><input type="checkbox" id="quilting${i}" name="quilting${i}"></td>`;
    //     /* cols += `<td class="text-center"><input type="checkbox" name="fbis${i}" id="fbis${i}" class="swit${i}"/></td>`; */
    //     cols += `<td class="col-1 text-center"><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>`;
    //     newRow.append(cols);
    //     $("#sitabel").append(newRow);
    //     /* $('.swit'+i).swit(i); */
    //     $('#imaterial' + i).select2({
    //         placeholder: 'Cari Kode / Nama Material',
    //         allowClear: true,
    //         width: "100%",
    //         type: "POST",
    //         ajax: {
    //             url: '<?= base_url($folder . '/cform/material/'); ?>',
    //             dataType: 'json',
    //             delay: 250,
    //             processResults: function(data) {
    //                 return {
    //                     results: data
    //                 };
    //             },
    //             cache: true
    //         }
    //     }).change(function(event) {
    //         var z = $(this).data('nourut');
    //         $.ajax({
    //             type: "post",
    //             data: {
    //                 'i_material': $(this).val(),
    //             },
    //             url: '<?= base_url($folder . '/cform/getdetailmaterial'); ?>',
    //             dataType: "json",
    //             success: function(data) {
    //                 $('#gudang' + z).val(data['detail'][0]['e_bagian_name']);
    //             },
    //             error: function() {
    //                 swal('Error :)');
    //             }
    //         });
    //     });
    //     $('#id_bisbisan' + i).select2({
    //         placeholder: 'Pilih Ukuran',
    //         allowClear: true,
    //         width: "100%",
    //         type: "POST",
    //         ajax: {
    //             url: '<?= base_url($folder . '/cform/get_bisbisan/'); ?>',
    //             dataType: 'json',
    //             delay: 250,
    //             data: function(params) {
    //                 var query = {
    //                     q: params.term,
    //                     i_material: $('#imaterial' + i).val(),
    //                 }
    //                 return query;
    //             },
    //             processResults: function(data) {
    //                 return {
    //                     results: data
    //                 };
    //             },
    //             cache: true
    //         }
    //     });
    // });

    function tambah(jml) {
        let i = parseInt(jml) + 1;
        $("#jml").val(i);
        var no = $('#sitabel tr').length;
        var newRow = $("<tr>");
        var cols = "";
        cols += `<td class="text-center"><spanx id="snum${i}">${no}</spanx></td>`;
        cols += `<td><select data-nourut="${i}" id="imaterial${i}" class="form-control input-sm id" name="imaterial${i}" ></select><input type="hidden" id="vtoset${i}" name="vtoset${i}"></td>`;
        cols += `<td><input type="text" readonly id="gudang${i}" class="form-control input-sm" name="gudang${i}"></td>`;
        cols += `<td><input type="text" id="bagian${i}" class="form-control input-sm" autocomplete="off" name="bagian${i}"></td>`;
        cols += `<td><input type="text" id="vgelar${i}" class="form-control text-right input-sm" autocomplete="off" name="vgelar${i}" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);"></td>`;
        cols += `<td><input type="text" id="vset${i}" class="form-control text-right input-sm" autocomplete="off" name="vset${i}" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);"></td>`;
        cols += `<td><input type="text" id="bis3${i}" class="form-control text-right input-sm" autocomplete="off" name="bis3${i}" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);"></td>`;
        /* cols += `<td><input type="text" id="bis4${i}" class="form-control text-right input-sm" autocomplete="off" name="bis4${i}" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);"></td>`; */
        cols += `<td><select data-urut="${i}" id="id_bisbisan${i}" class="form-control input-sm" name="id_bisbisan${i}" ></select></td>`;
        cols += `<td><select data-urut="${i}" id="id_type_makloon${i}" class="form-control input-sm" multiple name="id_type_makloon${i}[]" ></select></td>`;
        /* cols += `<td class="text-center"><input type="checkbox" id="f_cutting${i}" name="f_cutting${i}" checked></td>`;
        cols += `<td class="text-center"><input type="checkbox" id="autocutter${i}" name="autocutter${i}"></td>`;
        cols += `<td class="text-center"><input type="checkbox" id="badan${i}" name="badan${i}"></td>`;
        cols += `<td class="text-center"><input type="checkbox" id="print${i}" name="print${i}"></td>`;
        cols += `<td class="text-center"><input type="checkbox" id="bordir${i}" name="bordir${i}"></td>`;
        cols += `<td class="text-center"><input type="checkbox" id="quilting${i}" name="quilting${i}"></td>`; */
        /* cols += `<td class="text-center"><input type="checkbox" name="fbis${i}" id="fbis${i}" class="swit${i}"/></td>`; */
        cols += `<td><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>`;
        newRow.append(cols);
        // $("#sitabel").append(newRow);
        $("#sitabel tr:first").after(newRow);
        restart();
        /* $('.swit'+i).swit(i); */
        $('#imaterial' + i).select2({
            placeholder: 'Cari Kode / Nama Material',
            allowClear: true,
            width: "100%",
            type: "POST",
            ajax: {
                url: '<?= base_url($folder . '/cform/material/'); ?>',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        }).change(function(event) {
            var z = $(this).data('nourut');
            $.ajax({
                type: "post",
                data: {
                    'i_material': $(this).val(),
                },
                url: '<?= base_url($folder . '/cform/getdetailmaterial'); ?>',
                dataType: "json",
                success: function(data) {
                    $('#gudang' + z).val(data['detail'][0]['e_bagian_name']);
                },
                error: function() {
                    swal('Error :)');
                }
            });
        });
        $('#id_bisbisan' + i).select2({
            placeholder: 'Pilih Ukuran',
            allowClear: true,
            width: "100%",
            type: "POST",
            ajax: {
                url: '<?= base_url($folder . '/cform/get_bisbisan/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    var query = {
                        q: params.term,
                        i_material: $('#imaterial' + i).val(),
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
        /* .change(function(event) {
                    var z   = $(this).data('nourut');
                    var ada = true;
                    for(var x = 1; x <= $('#jml').val(); x++){
                        if ($(this).val()!=null) {
                            if((($(this).val()) == $('#imaterial'+x).val()) && (z!=x)){
                                swal ("kode : "+ $(this).val() +" sudah ada !!!!!");
                                ada = false;
                                break;
                            }
                        }
                    }
                    if (!ada) {                
                        $(this).val('');
                        $(this).html('');
                    }
                }); */
        /* });   */

        $('#id_type_makloon' + i).select2({
            placeholder: 'Pilih Type Makloon',
            allowClear: true,
            width: "100%",
            type: "POST",
            ajax: {
                url: '<?= base_url($folder . '/cform/get_type_makloon/'); ?>',
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
        });
    }

    $("#sitabel").on("click", ".ibtnDel", function(event) {
        $(this).closest("tr").remove();

        $('#jml').val(i);
        var obj = $('#sitabel tr:visible').find('spanx');
        $.each(obj, function(key, value) {
            id = value.id;
            $('#' + id).html(key + 1);
        });
    });

    $("#submit").click(function(event) {
        ada = false;
        if ($('#jml').val() == 0) {
            swal('Isi item minimal 1!');
            return false;
        } else {
            $("#sitabel tbody tr").each(function() {
                $(this).find("td select .id").each(function() {
                    if ($(this).val() == '' || $(this).val() == null) {
                        swal('Kode barang tidak boleh kosong!');
                        ada = true;
                    }
                });
                $(this).find("td .inputitem").each(function() {
                    if ($(this).val() == '' || $(this).val() == null || $(this).val() == 0) {
                        swal('Jml harus lebih besar dari 0 !');
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

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#addrow").attr("disabled", true);
    });

function restart() {
    var obj = $('#sitabel tr:visible').find('spanx');
    $.each(obj, function(key, value) {
        id = value.id;
        $('#' + id).html(key + 1);
    });
}
</script>