<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Tanggal Daftar</label>
                        <label class="col-md-3">Kode Barang</label>
                        <label class="col-md-6">Nama Barang</label>
                        <div class="col-sm-3">
                            <input class="form-control input-sm date" type="text" name="dregister" id="dregister"
                                value="<?=$data->d_register;?>" placeholder="Tanggal Daftar" readonly>
                            <input class="form-control" type="hidden" name="id" id="id" value="<?=$data->id;?>"
                                readonly>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" name="ikodebrg" id="ikodebrg" class="form-control input-sm"
                                onkeyup="gede(this);" value="<?=$data->i_material;?>" placeholder="Kode Barang"
                                readonly>
                            <input class="form-control" type="hidden" name="ikodeold" id="ikodeold"
                                value="<?=$data->i_material;?>" readonly>
                            <span id="cek" hidden="true">
                                <font size="3" face="Courier New" color="red">Kode Sudah Ada!</font>
                            </span>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" name="enamabrg" class="form-control input-sm" onkeyup="gede(this)"
                                value="<?=$data->e_material_name;?>" placeholder="Nama Barang">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Group Barang</label>
                        <label class="col-md-3">Kategori Barang</label>
                        <label class="col-md-6">Sub Kategori Barang</label>
                        <!-- <label class="col-md-3">Divisi</label> -->
                        <div class="col-sm-3">
                            <select name="igroupbrg" id="igroupbrg" class="form-control select2">
                                <option value="<?=$data->i_kode_group_barang;?>"><?=$data->e_nama_group_barang;?>
                                </option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="ikategori" id="ikategori" class="form-control select2">
                                <option value="<?=$data->i_kode_kelompok;?>"><?=$data->e_nama_kelompok;?></option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="ijenisbrg" id="ijenisbrg" class="form-control select2">
                                <option value="<?=$data->i_type_code;?>"><?=$data->e_type_name;?></option>
                            </select>
                        </div>
                        <!-- <div class="col-sm-3">
                            <select name="idivisi" id="idivisi" class="form-control select2">
                                <option value="<?=$data->i_divisi;?>"><?=$data->e_nama_divisi;?></option>
                            </select>
                        </div> -->
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Satuan Barang</label>
                        <label class="col-md-1">Panjang</label>
                        <label class="col-md-1">Lebar</label>
                        <label class="col-md-2">Tinggi</label>
                        <label class="col-md-5">Berat</label>
                        <div class="col-sm-3">
                            <select name="isatuan" id="isatuan" class="form-control select2">
                                <option value="<?=$data->i_satuan_code?>"><?=$data->e_satuan_barang;?></option>
                            </select>
                        </div>
                        <div class="col-sm-1">
                            <input type="text" name="npanjang" class="form-control input-sm" maxlength="20"
                                value="<? if($data->n_panjang == null) { echo 0;} else {echo $data->n_panjang;};?>"
                                placeholder="0">
                        </div>
                        <div class="col-sm-1">
                            <input type="text" name="nlebar" class="form-control input-sm" maxlength="20"
                                value="<? if($data->n_lebar == null) { echo 0;} else {echo $data->n_lebar;};?>"
                                placeholder="0">
                        </div>
                        <div class="col-sm-1">
                            <input type="text" name="ntinggi" class="form-control input-sm" maxlength="20"
                                value="<? if($data->n_tinggi == null) { echo 0;} else {echo $data->n_tinggi;};?>"
                                placeholder="0">
                        </div>
                        <div class="col-sm-1">
                            <input type="text" name="isatuanukuran" id="isatuanukuran" class="form-control input-sm"
                                value="CM" readonly>
                        </div>
                        <div class="col-sm-1">
                            <input type="text" name="nberat" class="form-control input-sm" maxlength="30"
                                value="<? if($data->n_berat == null) { echo 0;} else {echo $data->n_berat;};?>"
                                placeholder="0">
                        </div>
                        <div class="col-sm-1">
                            <input type="hidden" class="form-control input-sm" value="GR" name="isatuanberat"
                                id="isatuanberat" readonly>
                            <input type="text" class="form-control input-sm" value="Gram">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Status Produksi</label>
                        <label class="col-md-3">Style Barang</label>
                        <label class="col-md-3">Brand Barang</label>
                        <label class="col-md-3">Supplier Utama</label>
                        <div class="col-sm-3">
                            <select name="istatusproduksi" id="istatusproduksi" class="form-control select2">
                                <option value="<?=$data->i_status_produksi;?>"><?=$data->e_status_produksi;?></option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="istyle" id="istyle" class="form-control select2">
                                <option value="<?=$data->i_style;?>"><?=$data->e_style_name;?></option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="ibrand" id="ibrand" class="form-control select2">
                                <option value="<?=$data->i_brand;?>"><?=$data->e_brand_name;?></option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="isupplier" id="isupplier" class="form-control select2">
                                <option value="<?=$data->i_supplier;?>"><?=$data->e_supplier_name;?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea name="edeskripsi" id="edeskripsi" class="form-control"
                                placeholder="Keterangan"><?=$data->e_remark;?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-8 col-sm-20">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm mr-2"
                                onclick="return validasi();"> <i class="fa fa-save"></i>&nbsp;&nbsp;Update</button>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm"
                                onclick='show("<?= $folder;?>/cform","#main")'><i
                                    class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>
                    </div>
                    <div class="form-group">
                        <span style="color: #8B0000"><b>NOTE :</b></span><br>
                        <span style="color: #8B0000">* Standar Kode Barang terdiri dari 7 (tujuh) kombinasi huruf dan
                            angka</span><br>
                        <span style="color: #8B0000">* Susunan huruf dapat diambil dari singkatan Nama Barang</span><br>
                        <span style="color: #8B0000">* Susunan angka dapat dikombinasikan antara angka 0 (nol) dengan
                            nomor urutan terakhir pada Barang sebelumnya</span><br><br>
                        <span style="color: #8B0000"><b>* Contoh : PLA0001, PLA0002, APB0001, dst</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="white-box" id="detailbis" hidden>
    <div class="col-sm-5">
        <h3 class="box-title m-b-0">Ukuran Bis-Bisan Material</h3>
        <br>
        <button type="button" id="addrowbis" class="btn btn-info btn-sm"><i
                class="fa fa-plus"></i>&nbsp;&nbsp;Item</button><br><br>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledataxbis" class="table color-table success-table table-bordered class" cellpadding="8"
                cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th valign="center" class="text-center" style="width:5%;">No</th>
                        <th class="text-center" style="width:8%;">Ukuran Bisbisan</th>
                        <th class="text-center" style="width:8%;">Lebar kain</th>
                        <th class="text-center" style="width:12%;">Jenis Potong</th>
                        <th class="text-center" style="width:10%;">% Hilang <br>Lebar Kain</th>
                        <th class="text-center" style="width:15%;">Lebar Kain Jadi</th>
                        <th class="text-center" style="width:10%;">Jml Roll</th>
                        <th class="text-center" style="width:12%;">% Tambah <br>Panjang Kain</th>
                        <th class="text-center" style="width:10%;">Panjang Bisbisan</th>
                        <th class="text-center" style="width:15%;">Panjang Bisbisan per 1m</th>
                        <th class="text-center" style="width:5%;">Act</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 0; if($bisbisan->num_rows()>0){
                        foreach($bisbisan->result() AS $key){ $i++; ?>
                    <tr>
                        <td style="text-align: center;">
                            <spanx id="snum<?= $i;?>"><?= $i;?></spanx>
                            <input readonly type="hidden" id="id_bisbisan'<?= $i;?>'"
                                class="form-control text-center input-sm inputitem" autocomplete="off"
                                name="id_bisbisan'<?= $i;?>'" value="<?= $key->id; ?>">
                        </td>

                        <td><input type="text" id="n_bisbisan<?= $i; ?>"
                                class="form-control text-center input-sm inputitem" autocomplete="off"
                                name="n_bisbisan<?= $i; ?>" onblur='if(this.value==""){this.value="0";}'
                                onfocus='if(this.value=="0"){this.value="";}' value="<?= $key->n_bisbisan; ?>"
                                onkeyup="angkahungkul(this);hitungbis('<?= $i;?>');"></td>

                        <td><input type="text" id="v_lebar_kain_awal<?= $i; ?>"
                                class="form-control text-center input-sm inputitem" autocomplete="off"
                                name="v_lebar_kain_awal<?= $i; ?>" onblur='if(this.value==""){this.value="0";}'
                                onfocus='if(this.value=="0"){this.value="";}' value="<?= $key->v_lebar_kain_awal; ?>"
                                onkeyup="angkahungkul(this);hitungbis('<?= $i;?>');"></td>

                        <td>
                            <select required data-nourut="<?= $i; ?>" id="id_jenis_potong<?= $i; ?>"
                                class="form-control input-sm" name="id_jenis_potong<?= $i; ?>">
                                <option value="<?= $key->id_jenis_potong; ?>" selected><?= $key->e_jenis_potong; ?>
                                </option>
                            </select>
                        </td>

                        <td><input readonly type="text" id="n_hilang_lebar<?= $i; ?>"
                                class="form-control text-center input-sm inputitem" autocomplete="off"
                                name="n_hilang_lebar<?= $i; ?>" value="<?= $key->n_hilang_lebar; ?>"></td>

                        <td><input readonly type="text" id="v_lebar_kain_akhir<?= $i; ?>"
                                class="form-control text-center input-sm inputitem" autocomplete="off"
                                name="v_lebar_kain_akhir<?= $i; ?>" value="<?= $key->v_lebar_kain_akhir; ?>"></td>

                        <td><input readonly type="text" id="v_jumlah_roll<?= $i; ?>"
                                class="form-control text-center input-sm inputitem" autocomplete="off"
                                name="v_jumlah_roll<?= $i; ?>" value="<?= $key->v_jumlah_roll; ?>"></td>

                        <td><input readonly type="text" id="n_tambah_panjang<?= $i; ?>"
                                class="form-control text-center input-sm inputitem" autocomplete="off"
                                name="n_tambah_panjang<?= $i; ?>" value="<?= $key->n_tambah_panjang; ?>"></td>

                        <td><input readonly type="text" id="n_panjang_bis<?= $i; ?>"
                                class="form-control text-center input-sm inputitem" autocomplete="off"
                                name="n_panjang_bis<?= $i; ?>" value="<?= $key->n_panjang_bis; ?>"></td>

                        <td><input readonly type="text" id="v_panjang_bis<?= $i; ?>"
                                class="form-control text-center input-sm inputitem" autocomplete="off"
                                name="v_panjang_bis<?= $i; ?>" value="<?= $key->v_panjang_bis; ?>"></td>

                        <td class="text-center"><button type="button" title="Delete"
                                class="ibtnDelbis btn btn-circle btn-danger"><i class="ti-close"></i></button></td>

                    </tr>
                    <?php }
                    }?>
                </tbody>
            </table>
            <input type="hidden" name="jmlbis" id="jmlbis" value="<?= $i;?>">
        </div>
    </div>
</div>


<div class="white-box" id="detail">
    <div class="col-sm-5">
        <h3 class="box-title m-b-0">Konversi Material</h3>
        <br>
        <button type="button" id="addrow" class="btn btn-info btn-sm"><i
                class="fa fa-plus"></i>&nbsp;&nbsp;Item</button><br><br>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8"
                cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" style="width:5%;">No</th>
                        <th class="text-center" style="width:15%;">Satuan Pemakaian</th>
                        <th class="text-center" style="width:20%;">Operator</th>
                        <th class="text-center" style="width:15%;">Faktor</th>
                        <th class="text-center" style="width:20%;">Satuan Konversi</th>
                        <th class="text-center" style="width:5%;">Dipakai Pembelian</th>
                        <th class="text-center" style="width:5%;">Act</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 0; if($detail->num_rows()>0){
                        foreach($detail->result() AS $key){ $i++; ?>
                    <tr>
                        <td style="text-align: center;">
                            <spanx id="snum<?= $i;?>"><?= $i;?></spanx>
                        </td>
                        <td>
                            <input type="hidden" id="isatuanmaterial<?= $i;?>" name="isatuanmaterial<?= $i;?>"
                                class="form-control" value="<?=$data->i_satuan_code; ?>">
                            <span class="xspan"><?=$data->e_satuan_barang; ?></span>
                        </td>
                        <td>
                            <select id="eperator<?= $i;?>" required class="form-control input-sm"
                                name="eperator<?= $i;?>">
                                <option value=""></option>
                                <option value="*" <?php if($key->e_operator=='*'){echo "selected";}?>>Kali (*)</option>
                                <option value="/" <?php if($key->e_operator=='/'){echo "selected";}?>>Bagi (/)</option>
                             <!--    <option value="+" <?php if($key->e_operator=='+'){echo "selected";}?>>Tambah (+)
                                </option>
                                <option value="-" <?php if($key->e_operator=='-'){echo "selected";}?>>Kurang (-)
                                </option> -->
                            </select>
                        </td>
                        <td>
                            <input value="<?= $key->n_faktor;?>" type="text" id="faktor<?= $i;?>"
                                class="form-control text-right input-sm inputitem" autocomplete="off"
                                name="faktor<?= $i;?>" onblur="if(this.value==''){this.value='0';}"
                                onfocus="if(this.value=='0'){this.value='';}" value="0" onkeyup="angkahungkul(this);">
                        </td>
                        <td>
                            <select data-nourut="<?= $i;?>" id="isatuankonversi<?= $i;?>" class="form-control input-sm"
                                name="isatuankonversi<?= $i;?>" required>
                                <option value="<?= $key->i_satuan_code_konversi;?>"><?= $key->e_satuan_name;?></option>
                            </select>
                        </td>
                        <td class="text-center">
                            <input type="hidden" id="default<?= $i;?>" name="default<?= $i;?>"
                                value="<?php if($key->f_default=='t'){echo "t";}else{ echo "f";}?>">
                            <input type="radio" class="cekdefault" name="cekdefault" id="cekdefault<?= $i;?>"
                                onclick="cek();" <?php if($key->f_default=='t'){echo "checked";}?>>
                        </td>
                        <td class="text-center"><button type="button" title="Delete"
                                class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>
                    </tr>
                    <?php }
                    }?>
                </tbody>
            </table>
            <input type="hidden" name="jml" id="jml" value="<?= $i;?>">
        </div>
    </div>
</div>
</form>
<script>
$(document).ready(function() {
    $(".select2").select2();
    showCalendar('.date', 1835, 30);

    $('#igroupbrg').select2({
        placeholder: 'Pilih Group',
        allowClear: true,
        ajax: {
            url: '<?= base_url($folder.'/cform/getgroup'); ?>',
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

    $('#ikategori').select2({
        placeholder: 'Pilih Kategori Barang',
        width: '100%',
        allowClear: true,
        ajax: {
            url: '<?= base_url($folder.'/cform/getkategori'); ?>',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                var query = {
                    q: params.term,
                    igroup: $('#igroupbrg').val(),
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
    }).change(function(event) {
        $('#ijenisbrg').val('');
        $('#ijenisbrg').html('');
    });

    $('#ijenisbrg').select2({
        placeholder: 'Pilih Sub Kategori Barang',
        allowClear: true,
        ajax: {
            url: '<?= base_url($folder.'/cform/getjenis'); ?>',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                var query = {
                    q: params.term,
                    ikategori: $('#ikategori').val(),
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
    })

    $('#istyle').select2({
        placeholder: 'Pilih Style Barang',
        allowClear: true,
        ajax: {
            url: '<?= base_url($folder.'/cform/style'); ?>',
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

    $('#ibrand').select2({
        placeholder: 'Pilih Brand Barang',
        allowClear: true,
        ajax: {
            url: '<?= base_url($folder.'/cform/brand'); ?>',
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

    $('#isatuan').select2({
        placeholder: 'Pilih Satuan Barang',
        allowClear: true,
        ajax: {
            url: '<?= base_url($folder.'/cform/satuan'); ?>',
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
        for (let i = 1; i <= $('#jml').val(); i++) {
            if (typeof $('#isatuanmaterial' + i).val() != 'undefined') {
                $('#isatuanmaterial' + i).val($('#isatuan').val());
                $('.xspan').text($("#isatuan option:selected").text());
            }
        }
    });

    $('#istatusproduksi').select2({
        placeholder: 'Pilih Status Produksi',
        allowClear: true,
        ajax: {
            url: '<?= base_url($folder.'/cform/statusproduksi'); ?>',
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

    $('#isupplier').select2({
        placeholder: 'Pilih Supplier Utama',
        allowClear: true,
        ajax: {
            url: '<?= base_url($folder.'/cform/supplier'); ?>',
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

    // $('#idivisi').select2({
    //     placeholder: 'Pilih Divisi',
    //     allowClear: true,
    //     ajax: {
    //         url: '<?= base_url($folder.'/cform/divisi'); ?>',
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

    $("#ikodebrg").keyup(function() {
        var kode = $(this).val().replace(/[^a-zA-Z0-9]/g, '');
        $('#ikodebrg').val(kode);
        var kodeold = $('#ikodeold').val();
        if (kode.length == 7) {
            $.ajax({
                type: "post",
                data: {
                    'kode': kode,
                },
                url: '<?= base_url($folder.'/cform/cekkode'); ?>',
                dataType: "json",
                success: function(data) {
                    if (data == 1 && kodeold != kode) {
                        $("#cek").attr("hidden", false);
                        $("#submit").attr("disabled", true);
                    } else {
                        $("#cek").attr("hidden", true);
                        $("#submit").attr("disabled", false);
                    }
                },
                error: function() {
                    swal('Error :)');
                }
            });
        } else {
            $("#cek").attr("hidden", true);
            $("#submit").attr("disabled", false);
        }
    });

    $("#enamabrg").keyup(function() {
        var name = $(this).val().replace(/[^\w\s]/gi, '');
        $('#enamabrg').val(name);
    });

    $(document).ready(function() {
        $("#ikodebrg").focus();
    });

    for (let i = 1; i <= $('#jml').val(); i++) {
        $('#eperator' + i).select2({
            placeholder: 'Pilih Operator Hitungan',
            width: "100%",
        });
        $('#isatuankonversi' + i).select2({
            placeholder: 'Cari Satuan Konversi',
            allowClear: true,
            width: "100%",
            type: "POST",
            ajax: {
                url: '<?= base_url($folder . '/cform/get_satuan_konversi/'); ?>',
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
             * Cek Sudah Ada
             */
            var ada = true;
            var z = $(this).data('nourut');
            for (var x = 1; x <= $('#jml').val(); x++) {
                if ($(this).val() != null) {
                    if ((($(this).val()) == $('#isatuankonversi' + x).val()) && (z != x)) {
                        swal("Satuan tersebut sudah ada !!!!!");
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

    for (let i = 1; i <= $('#jmlbis').val(); i++) {
        $('#id_jenis_potong' + i).select2({
            placeholder: 'Cari Jenis Potong',
            allowClear: true,
            width: "100%",
            type: "POST",
            ajax: {
                url: '<?= base_url($folder . '/cform/get_jenis_potong/'); ?>',
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
            $.ajax({
                type: "post",
                data: {
                    'id': $(this).val(),
                },
                url: '<?= base_url($folder . '/cform/get_jenis_potong_detail/'); ?>',
                dataType: "json",
                success: function(data) {
                    if (data['data'] != null) {
                        $("#n_hilang_lebar" + i).val(data['data']['n_hilang_lebar']);
                        $("#n_tambah_panjang" + i).val(data['data']['n_tambah_panjang']);
                        hitungbis(i);
                    }
                },
                error: function() {
                    swal('500 internal server error : (');
                }
            });
        });
    }

   
    $("#addrow").on("click", function() {
        if ($('#isatuan').val() != null) {
            var i = $('#jml').val();
            $('#tabledatax').attr("hidden", false);
            i++;
            $("#jml").val(i);
            var no = $('#tabledatax tr').length;
            var newRow = $("<tr>");
            var cols = "";
            cols += '<td style="text-align: center;"><spanx id="snum' + i + '">' + no + '</spanx></td>';
            cols += '<td><input type="hidden" id="isatuanmaterial' + i + '" name="isatuanmaterial' + i +
                '" class="form-control" value="' + $("#isatuan").val() + '"><span class="xspan">' + $(
                    "#isatuan option:selected").text(); + '</span></td>';
            cols += '<td><select id="eperator' + i + '" class="form-control input-sm" name="eperator' +
                i +
                '" required><option value=""></option><option value="*">Kali (*)</option><option value="/">Bagi (/)</option></select></td>';
            cols += '<td><input type="text" id="faktor' + i +
                '" class="form-control text-right input-sm inputitem" autocomplete="off" name="faktor' +
                i +
                '" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);" required></td>';
            cols += '<td><select data-nourut="' + i + '" id="isatuankonversi' + i +
                '" class="form-control input-sm" name="isatuankonversi' + i +
                '" required><option></option></select></td>';
            cols += '<td class="text-center"><input type="hidden" id="default' + i + '" name="default' +
                i +
                '" value="f"><input type="radio" class="cekdefault" name="cekdefault" id="cekdefault' +
                i + '" onclick="cek();" value=""></td>';
            cols +=
                '<td class="text-center"><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>';
            newRow.append(cols);
            $("#tabledatax").append(newRow);
            $('#eperator' + i).select2({
                placeholder: 'Pilih Operator Hitungan',
                width: "100%",
            });
            $('#isatuankonversi' + i).select2({
                placeholder: 'Cari Satuan Konversi',
                allowClear: true,
                width: "100%",
                type: "POST",
                ajax: {
                    url: '<?= base_url($folder . '/cform/get_satuan_konversi/'); ?>',
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
                 * Cek Sudah Ada
                 */
                var ada = true;
                var z = $(this).data('nourut');
                for (var x = 1; x <= $('#jml').val(); x++) {
                    if ($(this).val() != null) {
                        if ((($(this).val()) == $('#isatuankonversi' + x).val()) && (z != x)) {
                            swal("Satuan tersebut sudah ada !!!!!");
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
        } else {
            swal("Pilih Satuan Barang Terlebih Dahulu!");
        }
    });

    $("#tabledatax").on("click", ".ibtnDel", function(event) {
        $(this).closest("tr").remove();

        //$('#jml').val(i);
        obj = $('#tabledatax tr:visible').find('spanx');
        $.each(obj, function(key, value) {
            id = value.id;
            $('#' + id).html(key + 1);
        });
    });

    var i = $('#jmlbis').val();
    $("#addrowbis").on("click", function() {
        if ($('#ikodebrg').val() != null) {
            $('#tabledataxbis').attr("hidden", false);
            i++;
            $("#jmlbis").val(i);
            var no = $('#tabledataxbis tr').length;
            var newRow = $("<tr>");
            var cols = "";
            cols += '<td style="text-align: center;"><input readonly type="hidden" id="id_bisbisan' +
                i +
                '" class="form-control text-center input-sm inputitem" autocomplete="off" name="id_bisbisan' +
                i + '" value="0"><spanx id="snum' + i + '">' + no + '</spanx></td>';

            cols += '<td><input type="text" id="n_bisbisan' + i +
                '" class="form-control text-center input-sm inputitem" autocomplete="off" name="n_bisbisan' +
                i +
                '" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);hitungbis(' +
                i + ');"></td>';

            cols += '<td><input type="text" id="v_lebar_kain_awal' + i +
                '" class="form-control text-center input-sm inputitem" autocomplete="off" name="v_lebar_kain_awal' +
                i +
                '" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);hitungbis(' +
                i + ');"></td>';
            cols += '<td><select required data-nourut="' + i + '" id="id_jenis_potong' + i +
                '" class="form-control input-sm" name="id_jenis_potong' + i +
                '"><option></option></select></td>';

            cols += '<td><input readonly type="text" id="n_hilang_lebar' + i +
                '" class="form-control text-center input-sm inputitem" autocomplete="off" name="n_hilang_lebar' +
                i + '" value="0" ></td>';

            cols += '<td><input readonly type="text" id="v_lebar_kain_akhir' + i +
                '" class="form-control text-center input-sm inputitem" autocomplete="off" name="v_lebar_kain_akhir' +
                i + '" value="0" ></td>';

            cols += '<td><input readonly type="text" id="v_jumlah_roll' + i +
                '" class="form-control text-center input-sm inputitem" autocomplete="off" name="v_jumlah_roll' +
                i + '" value="0" ></td>';

            cols += '<td><input readonly type="text" id="n_tambah_panjang' + i +
                '" class="form-control text-center input-sm inputitem" autocomplete="off" name="n_tambah_panjang' +
                i + '" value="0" ></td>';

            cols += '<td><input readonly type="text" id="n_panjang_bis' + i +
                '" class="form-control text-center input-sm inputitem" autocomplete="off" name="n_panjang_bis' +
                i + '" value="0" ></td>';

            cols += '<td><input readonly type="text" id="v_panjang_bis' + i +
                '" class="form-control text-center input-sm inputitem" autocomplete="off" name="v_panjang_bis' +
                i + '" value="0" ></td>';
            cols +=
                '<td class="text-center"><button type="button" title="Delete" class="ibtnDelbis btn btn-circle btn-danger"><i class="ti-close"></i></button></td>';
            newRow.append(cols);
            $("#tabledataxbis").append(newRow);
            $('#id_jenis_potong' + i).select2({
                placeholder: 'Cari Jenis Potong',
                allowClear: true,
                width: "100%",
                type: "POST",
                ajax: {
                    url: '<?= base_url($folder . '/cform/get_jenis_potong/'); ?>',
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
                $.ajax({
                    type: "post",
                    data: {
                        'id': $(this).val(),
                    },
                    url: '<?= base_url($folder . '/cform/get_jenis_potong_detail/'); ?>',
                    dataType: "json",
                    success: function(data) {
                        if (data['data'] != null) {
                            $("#n_hilang_lebar" + i).val(data['data'][
                                'n_hilang_lebar'
                            ]);
                            $("#n_tambah_panjang" + i).val(data['data'][
                                'n_tambah_panjang'
                            ]);
                            hitungbis(i);
                        }
                    },
                    error: function() {
                        swal('500 internal server error : (');
                    }
                });
            });
        } else {
            swal("Isi Kode Barang Terlebih Dahulu!");
        }
    });

    $("#tabledataxbis").on("click", ".ibtnDelbis", function(event) {
        $(this).closest("tr").remove();

        $('#jml').val(i);
        obj = $('#tabledataxbis tr:visible').find('spanx');
        $.each(obj, function(key, value) {
            id = value.id;
            $('#' + id).html(key + 1);
        });
    });
});

function hitungbis(i) {
    var n_bisbisan = parseFloat($('#n_bisbisan' + i).val());
    var v_lebar_kain_awal = parseFloat($('#v_lebar_kain_awal' + i).val());
    var h_lebar = $('#n_hilang_lebar' + i).val();
    var t_panjang = $('#n_tambah_panjang' + i).val();

    var res_lebarjadi = 0;

    if (h_lebar == 0) {
        res_lebarjadi = v_lebar_kain_awal;
    } else {
        res_lebarjadi = v_lebar_kain_awal - (v_lebar_kain_awal * h_lebar / 100)
    }

    var res_jmlrol = res_lebarjadi / n_bisbisan;
    var res_panjangbis = 0;

    if (t_panjang == 0) {
        res_panjangbis = 1;
    } else {
        res_panjangbis = 1 + (1 * t_panjang / 100);
    }

    var total = res_jmlrol * res_panjangbis;

    $('#v_lebar_kain_akhir' + i).val(res_lebarjadi);
    $('#v_jumlah_roll' + i).val(res_jmlrol);
    $('#n_panjang_bis' + i).val(res_panjangbis);
    $('#v_panjang_bis' + i).val(total);
}

function cek() {
    for (let i = 1; i <= $('#jml').val(); i++) {
        if (typeof $('#cekdefault' + i).val() != 'undefined') {
            if ($('#cekdefault' + i).is(':checked')) {
                $('#default' + i).val('t');
            } else {
                $('#default' + i).val('f');
            }
        }
    }
}

function getjenis(id) {
    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/getkelompok');?>",
        data: "itypecode=" + id,
        dataType: 'json',
        success: function(data) {
            $("#ikategori").val(data.ikategori);
            $("#ekategori").val(data.ekategori);
            $("#igroupbrg").val(data.igroup);
            $("#egroupbrg").val(data.egroupname);
            $("#istyle").attr("disabled", false);
            $("#ibrand").attr("disabled", false);
            $("#isatuan").attr("disabled", false);
            $("#isupplier").attr("disabled", false);
            $("#istatusproduksi").attr("disabled", false);
        },

        error: function(XMLHttpRequest) {
            alert(XMLHttpRequest.responseText);
        }

    })
}

function getkodebrg() {
    var ijenisbrg = $('#ijenisbrg').val();
    var kdoe = ijenisbrg.substring(0, 3);
    document.getElementById('ikodebrg').value = kdoe;
}

function angka(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}

function validasi() {
    var dregister = $('#dregister').val();
    var igroupbrg = $('#igroupbrg').val();
    var ikelompok = $('#ikategori').val();
    var ijenisbrg = $('#ijenisbrg').val();
    var ikodebrg = $('#ikodebrg').val();
    var enamabrg = $('#enamabrg').val();
    var isatuan = $('#isatuan').val();
    if (igroupbrg == '' || igroupbrg == null) {
        swal('Group Barang Belum dipilih');
        return false;
    } else if (ikelompok == '' || ikelompok == null) {
        swal('Kategori Barang Belum dipilih');
        return false;
    } else if (ijenisbrg == '' || ijenisbrg == null) {
        swal('Jenis Barang Belum dipilih');
        return false;
    } else if (enamabrg == '') {
        swal('Nama Barang Belum diisi');
        return false;
    } else if (isatuan == '' || isatuan == null) {
        swal('Jenis Satuan Belum dipilih');
        return false;
    } else if (dregister == '' || dregister == null) {
        swal('Tanggal Daftar Belum dipilih');
        return false;
    } else {
        return true;
    }
}

$("form").submit(function(event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("textarea").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
});
</script>