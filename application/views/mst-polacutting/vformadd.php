<link href="<?= base_url(); ?>assets/plugins/bower_components/switchery/dist/switchery.min.css" rel="stylesheet" />
<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Kode dan Nama Barang WIP</label>
                        <label class="col-md-2">Marker</label>
                        <label class="col-md-2 text-center">Marker Utama</label>
                        <label class="col-md-3">Kode dan Nama Barang WIP Referensi (OPTIONAL)</label>
                        <label class="col-md-2">Marker</label>
                        <div class="col-sm-3">
                            <select name="iproductwip" id="iproductwip" required="" class="form-control select2" data-placeholder="Cari Barang WIP"></select>
                        </div>
                        <div class="col-sm-2">
                            <select name="id_marker" id="id_marker" required="" class="form-control select2" data-placeholder="Pilih Marker">
                                <?php if ($marker->num_rows()>0) {
                                    foreach ($marker->result() as $key) {?>
                                        <option value="<?= $key->id;?>"><?= $key->e_marker_name;?></option>
                                    <?php }
                                }?>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <input type="checkbox" name="f_marker_utama" id="f_marker_utama" class="form-control input-sm">
                        </div>
                        <div class="col-sm-3">
                            <select name="iproductwipref" id="iproductwipref" class="form-control select2" data-placeholder="Cari Barang WIP">
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <select name="id_marker_ref" id="id_marker_ref" onchange="get_marker();" class="form-control select2" data-placeholder="Pilih Marker">
                                <option value=""></option>
                                <?php if ($marker->num_rows()>0) {
                                    foreach ($marker->result() as $key) {?>
                                        <option value="<?= $key->id;?>"><?= $key->e_marker_name;?></option>
                                    <?php }
                                }?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <button type="submit" id="submit" class="btn btn-success btn-block btn-sm"><i class="fa fa-save fa-lg mr-2"></i>Simpan</button>
                        </div>
                        <div class="col-sm-4">
                            <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder; ?>/cform','#main')"> <i class="fa fa-arrow-circle-left fa-lg mr-2"></i>Kembali</button>
                        </div>
                        <div class="col-sm-4">
                            <button type="button" onclick="tambah($('#jml').val());" id="addrow" class="btn btn-info btn-block btn-sm"><i class="fa fa-plus fa-lg mr-2"></i>Item</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="white-box" id="detail">
    <div class="col-sm-5">
        <h3 class="box-title m-b-0">Detail Material</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="sitabel" class="table color-table success-table table-bordered class sitabel" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr class="d-flex">
                        <!-- <th class="text-center" width="3%">No</th> -->
                        <th class="text-center col-3">Nama Material</th>
                        <th class="text-center col-2">Group Barang</th>
                        <th class="text-center col-1">Bagian</th>
                        <th class="text-center col-1">Panjang Gelaran</th>
                        <th class="text-center col-1">Set</th>
                        <th class="text-center col-1">Kebutuhan<br>Bis<sup>2</sup>an</th>
                        <th class="text-center col-1">Ukuran<br>Bis<sup>2</sup>an</th>
                        <th class="text-center col-2">Type Makloon</th>
                        <th class="text-center col-1">Kain<br>Utama</th>
                        <th class="text-center col-1">Dibudgetkan</th>
                        <th class="text-center col-1">Jahit<br>
                            <i class="fa fa-question-circle fa-lg ml-2" tabindex="0" data-trigger="focus" class="btn btn-default"   data-container="body" data-toggle="popover" data-placement="bottom" title="Dapat diminta oleh bagian Pengadaan"></i>
                        </th>
                        <th class="text-center col-1">Packing<br>
                            <i class="fa fa-question-circle fa-lg ml-2" tabindex="0" data-trigger="focus" class="btn btn-default"   data-container="body" data-toggle="popover" data-placement="bottom" title="Dapat diminta oleh bagian Packing"></i>
                        </th>
                        <th class="text-center col-1">Act</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<input type="hidden" name="jml" id="jml" value="0">
</form>
<script src="<?= base_url(); ?>assets/plugins/bower_components/switchery/dist/switchery.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2();
        popover();
        // fixedtable($('#sitabel'));
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
        }).change(function(event) {
            $("#sitabel > tbody").remove();
            $('#iproductwipref').val('');
            $('#iproductwipref').html('');
        });

        $('#id_marker').change(function(event) {
            $("#sitabel > tbody").remove();
            $('#iproductwipref').val('');
            $('#iproductwipref').html('');
        });

        $('#f_marker_utama').change(function(e) {
            if($('#f_marker_utama').is(':checked')) {
                let iproductwip = $('#iproductwip').val();
                $.ajax({
                    url: '<?= base_url($folder . '/cform/checkmarkerutama'); ?>',
                    type: 'POST',
                    data: {
                        iproductwip: iproductwip
                    },
                    success: function(data) {
                        if(data > 0) {
                            swal('Marker utama dari product tersebut sudah ada!!!');
                            $('#f_marker_utama').attr('checked', false);
                        } else {
                            $('#f_marker_utama').attr('checked', true);
                        }
                    }
                })
            }
        })

        $('#iproductwipref').select2({
            placeholder: 'Cari Barang WIP Referensi',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder . '/cform/productwipref'); ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    var query = {
                        q: params.term,
                        i_product_wip: $('#iproductwip').val(),
                        id_marker: $('#id_marker').val(),
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
            get_marker();
        });

        $("#sitabel").on("click", ".ibtnDel", function(event) {
            $(this).closest("tr").remove();
            /* alert(i); */
            /* $('#jml').val(i); */
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
    })

    // var i = $("#jml").val();
    /*
        $("#addrow").on("click", function () {
            i++; */
    function tambah(jml) {
        let i = parseInt(jml) + 1;
        $("#jml").val(i);
        var no = $('#sitabel tr').length;
        var newRow = $("<tr class='d-flex'>");
        var cols = "";
        // cols += `<td class="text-center"><spanx id="snum${i}">${no}</spanx></td>`;
        cols += `<td class="col-3"><select data-nourut="${i}" id="imaterial${i}" class="form-control input-sm id" name="imaterial${i}" ></select><input type="hidden" id="vtoset${i}" name="vtoset${i}"></td>`;
        cols += `<td class="col-2"><input type="text" readonly id="gudang${i}" class="form-control input-sm" name="gudang${i}"></td>`;
        cols += `<td class="col-1"><input type="text" id="bagian${i}" class="form-control input-sm" autocomplete="off" name="bagian${i}"></td>`;
        cols += `<td class="col-1"><input type="text" id="vgelar${i}" class="form-control text-right input-sm" autocomplete="off" name="vgelar${i}" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);"></td>`;
        cols += `<td class="col-1"><input type="text" id="vset${i}" class="form-control text-right input-sm" autocomplete="off" name="vset${i}" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);"></td>`;
        cols += `<td class="col-1"><input type="text" id="bis3${i}" class="form-control text-right input-sm" autocomplete="off" name="bis3${i}" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);"></td>`;
        /* cols += `<td><input type="text" id="bis4${i}" class="form-control text-right input-sm" autocomplete="off" name="bis4${i}" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);"></td>`; */
        cols += `<td class="col-1"><select data-urut="${i}" id="id_bisbisan${i}" class="form-control input-sm" name="id_bisbisan${i}" ></select></td>`;
        cols += `<td class="col-2"><select data-urut="${i}" id="id_type_makloon${i}" class="form-control input-sm" multiple name="id_type_makloon${i}[]" ></select></td>`;
        cols += `<td class="text-center col-1"><input class="form-control input-sm" type="checkbox" id="f_kain_utama${i}" name="f_kain_utama${i}"></td>`;
        cols += `<td class="text-center col-1"><input class="form-control input-sm" type="checkbox" id="f_budgeting${i}" name="f_budgeting${i}" checked></td>`;
        cols += `<td class="text-center col-1"><input class="form-control input-sm" type="checkbox" id="f_jahit${i}" name="f_jahit${i}"></td>`;
        cols += `<td class="text-center col-1"><input class="form-control input-sm" type="checkbox" id="f_packing${i}" name="f_packing${i}"></td>`;
        /* cols += `<td class="text-center"><input type="checkbox" id="f_cutting${i}" name="f_cutting${i}" checked></td>`;
        cols += `<td class="text-center"><input type="checkbox" id="autocutter${i}" name="autocutter${i}"></td>`;
        cols += `<td class="text-center"><input type="checkbox" id="badan${i}" name="badan${i}"></td>`;
        cols += `<td class="text-center"><input type="checkbox" id="print${i}" name="print${i}"></td>`;
        cols += `<td class="text-center"><input type="checkbox" id="bordir${i}" name="bordir${i}"></td>`;
        cols += `<td class="text-center"><input type="checkbox" id="quilting${i}" name="quilting${i}"></td>`; */
        /* cols += `<td class="text-center"><input type="checkbox" name="fbis${i}" id="fbis${i}" class="swit${i}"/></td>`; */
        cols += `<td class="text-center col-1"><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>`;
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
                    if(data['detail'][0]['e_bagian_name'] == 'ACC JAHIT') {
                        $('#f_jahit' + z).prop('checked', true);
                        $('#f_packing' + z).prop('checked', false);
                    } else if(data['detail'][0]['e_bagian_name'] == 'ACC PACKING') {
                        $('#f_jahit' + z).prop('checked', false);
                        $('#f_packing' + z).prop('checked', true);
                    }
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

    function restart() {
        var obj = $('#sitabel tr:visible').find('spanx');
        $.each(obj, function(key, value) {
            id = value.id;
            $('#' + id).html(key + 1);
        });
    }

    function get_marker() {
        const id_product = $('#iproductwipref').val();
        const id_marker = $('#id_marker_ref').val();
        // console.log(id_marker.length);
        // return false;
        /* console.log(id_product, id_marker);
        return false; */
        if (id_product !== null && (id_marker != '' || id_marker.length > 0)) {
            $.ajax({
                type: "post",
                data: {
                    'i_product_wip': id_product,
                    'id_marker': id_marker,
                },
                url: '<?= base_url($folder . '/cform/getdetailref'); ?>',
                dataType: "json",
                success: function(data) {
                    $("#sitabel > tbody").remove();
                    $('#jml').val(data['detail'].length);
                    for (let ii = 0; ii < data['detail'].length; ii++) {
                        var x = ii + 1;
                        var newRow = $("<tr class='d-flex'>");
                        var cols = "";

                        var jml = JSON.parse(data['detail'][ii]['e_type_makloon_name']).length;
                        var idmakloon = JSON.parse(data['detail'][ii]['type_makloon_id']);
                        var makloon = JSON.parse(data['detail'][ii]['e_type_makloon_name']);
                        var option = [];
                        for (let index = 0; index < jml; index++) {
                            
                            if(idmakloon[index] !== null){
                                // console.log(idmakloon[index]);
                                // option.push("<option value=''></option>");
                                option.push("<option value=" + idmakloon[index] + " selected>" + makloon[index] + "</option>");
                            }
                            // if (idmakloon[index]!='' || idmakloon[index]!=null || idmakloon[index] != 'null') {
                            //     option.push("<option value=" + idmakloon[index] + " selected>" + makloon[index] + "</option>");
                            // }else{
                                // option.push("<option value=''></option>");
                            // }
                        }
                        /* if (data['detail'][ii]['f_cutting'] == 't') {
                            cekfcut = 'checked';
                        } else {
                            cekfcut = '';
                        }
                        if (data['detail'][ii]['f_autocutter'] == 't') {
                            cekauto = 'checked';
                        } else {
                            cekauto = '';
                        }
                        if (data['detail'][ii]['f_badan'] == 't') {
                            cekbadan = 'checked';
                        } else {
                            cekbadan = '';
                        }
                        if (data['detail'][ii]['f_print'] == 't') {
                            cekprint = 'checked';
                        } else {
                            cekprint = '';
                        }
                        if (data['detail'][ii]['f_bordir'] == 't') {
                            cekbordir = 'checked';
                        } else {
                            cekbordir = '';
                        }
                        if (data['detail'][ii]['f_quilting'] == 't') {
                            cekquilt = 'checked';
                        } else {
                            cekquilt = '';
                        } */

                        if (data['detail'][ii]['id_bisbisan'] == null) {
                            id_bisbisan = '';
                            e_jenis_potong = '';
                        } else {
                            id_bisbisan = data['detail'][ii]['id_bisbisan'];
                            e_jenis_potong = data['detail'][ii]['n_bisbisan'] + ' - ' + data['detail'][ii]['e_jenis_potong'];
                        }

                        var gudang = data['detail'][ii]['e_nama_group_barang'];
                        if (gudang=='null' || gudang == null) {
                            gudang = '';
                        }

                        var checked = '';
                        if (data['detail'][ii]['f_kain_utama']=='t') {
                            checked = 'checked';
                        }

                        // console.log(data['detail'][ii]['f_budgeting']);

                        var check = '';
                        if (data['detail'][ii]['f_budgeting']=='t') {
                            check = 'checked';
                        }

                        var checkJahit = '';
                        if (data['detail'][ii]['f_jahit']=='t') {
                            checkJahit = 'checked';
                        }

                        var checkPacking = '';
                        if (data['detail'][ii]['f_packing']=='t') {
                            checkPacking = 'checked';
                        }
                        // cols += `<td class="text-center"><spanx id="snum${x}">${x}</spanx></td>`;
                        cols += `<td class="col-3">
                                    <select data-nourut="${x}" id="imaterial${x}" class="form-control input-sm id" name="imaterial${x}">
                                        <option value="${data['detail'][ii]['i_material']}">${data['detail'][ii]['i_material']} - ${data['detail'][ii]['e_material_name']}</option>
                                    </select>
                                    <input type="hidden" id="vtoset${x}" name="vtoset${x}" class="form-control text-right input-sm" value="${data['detail'][ii]['v_toset']}">
                                </td>`;
                        cols += `<td class="col-2"><input type="text" value="${gudang}" readonly id="gudang${x}" class="form-control input-sm" name="gudang${x}"></td>`;
                        cols += `<td class="col-1"><input type="text" value="${data['detail'][ii]['bagian']}" id="bagian${x}" class="form-control input-sm" autocomplete="off" name="bagian${x}"></td>`;
                        cols += `<td class="col-1"><input type="text" id="vgelar${x}" class="form-control text-right input-sm" autocomplete="off" name="vgelar${x}" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0.00" || this.value=="0"){this.value="";}\' value="${data['detail'][ii]['v_gelar']}" onkeyup="angkahungkul(this);"></td>`;
                        cols += `<td class="col-1"><input type="text" id="vset${x}" class="form-control text-right input-sm" autocomplete="off" name="vset${x}" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0.00" || this.value=="0"){this.value="";}\' value="${data['detail'][ii]['v_set']}" onkeyup="angkahungkul(this);"></td>`;
                        cols += `<td class="col-1"><input type="text" id="bis3${x}" class="form-control text-right input-sm" autocomplete="off" name="bis3${x}" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0.00" || this.value=="0"){this.value="";}\' value="${data['detail'][ii]['v_bisbisan']}" onkeyup="angkahungkul(this);"></td>`;
                        /* cols += `<td><input type="text" id="bis4${x}" class="form-control text-right input-sm" autocomplete="off" name="bis4${x}" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0.00" || this.value=="0"){this.value="";}\' value="${data['detail'][ii]['n_bis4_5']}" onkeyup="angkahungkul(this);"></td>`; */
                        cols += `<td class="col-1"><select data-urut="${x}" id="id_bisbisan${x}" class="form-control input-sm" name="id_bisbisan${x}" ><option value="${id_bisbisan}">${e_jenis_potong}</option></select></td>`;
                        /* cols += `<td class="text-center"><input type="checkbox" id="f_cutting${x}" name="f_cutting${x}" ${cekfcut}></td>`;
                        cols += `<td class="text-center"><input type="checkbox" id="autocutter${x}" name="autocutter${x}" ${cekauto}></td>`;
                        cols += `<td class="text-center"><input type="checkbox" id="badan${x}" name="badan${x}" ${cekbadan}></td>`;
                        cols += `<td class="text-center"><input type="checkbox" id="print${x}" name="print${x}" ${cekprint}></td>`;
                        cols += `<td class="text-center"><input type="checkbox" id="bordir${x}" name="bordir${x}" ${cekbordir}></td>`;
                        cols += `<td class="text-center"><input type="checkbox" id="quilting${x}" name="quilting${x}" ${cekquilt}></td>`; */
                        cols += `<td class="col-2">
                        <select data-urut="${x}" id="id_type_makloon${x}" class="form-control input-sm" multiple name="id_type_makloon${x}[]" >
                        ${option}
                        </select></td>`;
                        cols += `<td class="text-center col-1"><input class="form-control input-sm"  type="checkbox" id="f_kain_utama${x}" name="f_kain_utama${x}" ${checked}></td>`;
                        cols += `<td class="text-center col-1"><input class="form-control input-sm"  type="checkbox" id="f_budgeting${x}" name="f_budgeting${x}" ${check}></td>`;
                        cols += `<td class="text-center col-1"><input class="form-control input-sm"  type="checkbox" id="f_jahit${x}" name="f_jahit${x}" ${checkJahit}></td>`;
                        cols += `<td class="text-center col-1"><input class="form-control input-sm"  type="checkbox" id="f_packing${x}" name="f_packing${x}" ${checkPacking}></td>`;
                        cols += `<td class="col-1 text-center"><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>`;
                        newRow.append(cols);
                        $("#sitabel").append(newRow);
                        $('#imaterial' + x).select2({
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
                        $('#id_bisbisan' + x).select2({
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
                                        i_material: $('#imaterial' + x).val(),
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
                        $('#id_type_makloon' + x).select2({
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
                },
                error: function() {
                    swal('Error :)');
                }
            });
        }
    }
</script>