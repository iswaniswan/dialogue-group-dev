<style type="text/css">
    .font {
        font-size: 12px;
        /* background-color: #e1f1e4; */
    }

    #tabledatalistx td {
        padding: 5px 3px !important;
        vertical-align: middle !important;
    }
</style>
<!-- <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?> -->
<form id="cekinputan">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <i class="fa fa-lg fa-pencil mr-2"></i><?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list fa-lg mr-2"></i><?= $title_list; ?> </a>
                </div>
                <div class="panel-body table-responsive">
                    <div id="pesan"></div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-3">Bagian Pembuat</label>
                            <label class="col-md-3">Nomor Dokumen</label>
                            <label class="col-md-3">Tanggal Dokumen</label>
                            <label class="col-md-3">Perkiraan Kembali</label>
                            <div class="col-sm-3">
                                <select name="ibagian" id="ibagian" required="" class="form-control select2">
                                    <?php if ($bagian) {
                                        foreach ($bagian->result() as $key) { ?>
                                            <option value="<?= trim($key->i_bagian); ?>" <?php if ($key->i_bagian == $data->i_bagian) { ?> selected <?php } ?>><?= $key->e_bagian_name; ?></option>
                                    <?php }
                                    } ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="text" name="idocument" required="" id="isj" value="<?= $data->i_document; ?>" readonly="" placeholder="<?= $number; ?>" class="form-control input-sm">
                                </div>
                                <input type="hidden" name="id" id="id" value="<?= $id; ?>">
                                <input type="hidden" name="isjold" id="isjold" value="<?= $data->i_document; ?>">
                            </div>
                            <div class="col-sm-3">
                                <input type="text" name="ddocument" required="" id="ddocument" class="form-control input-sm date" value="<?= $data->date_document; ?>" readonly>
                            </div>
                            <div class="col-sm-3">
                                <input type="text" name="destimate" required="" id="destimate" class="form-control input-sm tgl" value="<?= $data->date_estimate; ?>" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3">Bagian Penerima</label>
                            <label class="col-md-3">Tipe Makloon</label>
                            <label class="col-md-3">Partner</label>
                            <label class="col-md-3">Keterangan</label>
                            <div class="col-sm-3">
                                <select name="ibagianreceive" required="" id="ibagianreceive" class="form-control select2">
                                    <?php if ($bagian_receive) {
                                        foreach ($bagian_receive->result() as $key) { ?>
                                            <option value="<?= trim($key->i_bagian); ?>" <?php if ($key->i_bagian == $data->i_bagian_receive) { ?> selected <?php } ?>><?= $key->e_bagian_name; ?></option>
                                    <?php }
                                    } ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <select name="idtype" required="" id="idtype" class="form-control select2" data-placeholder="Pilih Tipe Makloon">
                                    <option value=""></option>
                                    <?php if ($type) {
                                        foreach ($type->result() as $key) { ?>
                                            <option value="<?= $key->id; ?>" <?php if ($key->id == $data->id_type_makloon) { ?> selected <?php } ?>><?= $key->e_name; ?></option>
                                    <?php }
                                    } ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <select name="idpartner" id="idpartner" required="" class="form-control input-sm select2" data-placeholder="Pilih Partner">
                                    <option value="<?= $data->id_supplier; ?>"><?= $data->e_supplier_name; ?></option>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <textarea type="text" id="eremark" name="eremark" maxlength="250" class="form-control input-sm" placeholder="Isi keterangan jika ada!"><?= $data->e_remark; ?></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <?php if ($data->i_status == '1' || $data->i_status == '3' || $data->i_status == '7') { ?>
                                <div class="col-sm-3">
                                    <button type="button" id="submit" class="btn btn-success btn-block btn-sm" onclick="return simpan();"><i class="fa fa-save mr-2 fa-lg"></i>Update</button>
                                </div>
                                <div class="col-sm-3">
                                    <button type="button" id="send" class="btn btn-primary btn-block btn-sm"><i class="fa fa-paper-plane-o mr-2 fa-lg"></i>Send</button>
                                </div>
                                <div class="col-sm-3">
                                    <button type="button" id="hapus" class="btn btn-danger btn-block btn-sm"><i class="fa fa-trash mr-2 fa-lg"></i>Delete</button>
                                </div>
                                <div class="col-sm-3">
                                    <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;"><i class="fa fa-arrow-circle-left mr-2 fa-lg"></i>Kembali</button>
                                </div>
                            <?php } elseif ($data->i_status == '2') { ?>
                                <div class="col-sm-6">
                                    <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;"><i class="fa fa-arrow-circle-left mr-2 fa-lg"></i>Kembali</button>
                                </div>
                                <div class="col-sm-6">
                                    <button type="button" id="cancel" class="btn btn-primary btn-block btn-sm"><i class="fa fa-refresh mr-2 fa-lg"></i>Cancel</button>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="white-box" id="detail">
        <div class="form-group row">
            <div class="col-sm-2">
                <h3 class="box-title m-b-0 ml-1">Detail Barang</h3>
            </div>
            <div class="col-sm-2 text-right"><span class="text-right mr-1"><?= $this->doc_qe; ?></span></div>
            <div class="col-sm-2 ml-auto">
                <button type="button" class="btn btn-info btn-block btn-sm mr-2" id="addrowlist"> <i class="fa fa-plus fa-lg mr-2"></i>Item</button>
            </div>
            <!-- <div class="col-sm-1"></div> -->
        </div>
        <div class="table-responsive">
            <table id="tabledatalistx" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 3%;">No</th>
                        <th class="text-center" style="width: 25%;">Product dan Material Keluar</th>
                        <th class="text-center" style="width: 10%;">Qty</th>
                        <th class="text-center" style="width: 25%;">Material Masuk</th>
                        <th class="text-center" style="width: 10%;">Qty</th>
                        <th class="text-center" style="width: 20%;">Keterangan</th>
                        <th colspan="2" class="text-center" style="width: 7%;">Act</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php
                    $i = 0;
                    $z = 0;
                    $zz = 0;
                    $a = 0;
                    $aa = 0;
                    if ($datadetail) {
                        $group = "";
                        foreach ($datadetail as $key) {
                            $i++;
                            $a++;
                            if ($group != $key->id_keluar) {
                                $z++;
                            }
                            if ($group == "") { ?>
                                <tr class="table-warning xx<?= $z; ?>">
                                    <td class="text-center">
                                        <spanlistx id="snum<?= $z; ?>"><b><?= $z; ?></b></spanlistx>
                                    </td>
                                    <td>
                                        <select data-xx="<?= $z; ?>" id="id_wip<?= $z; ?>" class="form-control input-sm" name="id_wip<?= $z; ?>" onchange="changeval(<?= $z ?>, <?= $a ?>, <?= $i ?>)">
                                            <option value="<?= $key->id_product; ?>"><?= '[' . $key->i_product_wip, '] - ' . $key->e_product_wipname; ?> - <?= $key->e_color_name ?></option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="hidden" name="id_keluar<?= $z; ?>" id="id_keluar<?= $z; ?>" value="<?= $z; ?>" >
                                    </td>
                                    <td colspan="3"><input type="hidden" id="jmldetail<?= $z; ?>" value=""></td>
                                    <td class="text-center"><button data-urut="<?= $z; ?>" type="button" id="addlist<?= $z; ?>" title="Tambah List" class="btn btn-sm btn-circle btn-info"><i class="ti-plus"></i></button></td>
                                    <td class="text-center"><button type="button" onclick="hapusdetail(<?= $z; ?>);" title="Delete" class="ibtnDel btn btn-sm btn-circle btn-danger"><i class="ti-close"></i></button></td>
                                </tr>
                                <tr class="tr table-info del<?= $z; ?>" id="tr<?= $z; ?>">
                                    <td class="text-center"><a href="#" onclick="toge(<?= $z; ?>); return false;" class="toggler<?= $z; ?>" data-icon-name="fa-eye" data-prod-cat="eye_<?= $z; ?>"><i class="fa fa-eye fa-lg text-success"></i></a></td>
                                    <td colspan="7" class="font"><b>LIST BARANG MATERIAL</b></td>
                                </tr>
                                <?php
                            } else {
                                if ($group != $key->id_keluar) { ?>
                                    <tr class="table-warning xx<?= $z; ?>">
                                        <td class="text-center">
                                            <spanlistx id="snum<?= $z; ?>"><b><?= $z; ?></b></spanlistx>
                                        </td>
                                        <td>
                                            <select data-xx="<?= $z; ?>" id="id_wip<?= $z; ?>" class="form-control input-sm" name="id_wip<?= $z; ?>" onchange="changeval(<?= $z ?>, <?= $a ?>, <?= $i ?>)">
                                                <option value="<?= $key->id_product; ?>"><?= '[' . $key->i_product_wip, '] - ' . $key->e_product_wipname; ?> - <?= $key->e_color_name ?></option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="hidden" name="id_keluar<?= $z; ?>" id="id_keluar<?= $z; ?>" value="<?= $z; ?>" >
                                        </td>
                                        <td colspan="3"><input type="hidden" id="jmldetail<?= $z; ?>" value=""></td>
                                        <td class="text-center"><button data-urut="<?= $z; ?>" type="button" id="addlist<?= $z; ?>" title="Tambah List" class="btn btn-sm btn-circle btn-info"><i class="ti-plus"></i></button></td>
                                        <td class="text-center"><button type="button" onclick="hapusdetail(<?= $z; ?>);" title="Delete" class="ibtnDel btn btn-sm btn-circle btn-danger"><i class="ti-close"></i></button></td>
                                    </tr>
                                    <tr class="tr table-info del<?= $z; ?>" id="tr<?= $z; ?>">
                                        <td class="text-center"><a href="#" onclick="toge(<?= $z; ?>); return false;" class="toggler<?= $z; ?>" data-icon-name="fa-eye" data-prod-cat="eye_<?= $z; ?>"><i class="fa fa-eye fa-lg text-success"></i></a></td>
                                        <td colspan="7" class="font"><b>LIST BARANG MATERIAL</b></td>
                                    </tr>
                            <?php $i = 1;
                                }
                            }
                            $group = $key->id_keluar; ?>
                            <tr id="trdetail<?= $z . $i; ?>" class="table-success add<?= $z; ?> del<?= $z; ?> cat_eye_<?= $z; ?>">
                                <td class="text-right">
                                    <spanlist<?= $z; ?> id="snum<?= $z; ?>"></spanlist<?= $z; ?>><input type="hidden" id="idkeluarhead<?= $z . $i; ?>" name="idkeluarhead<?= $a; ?>"><input type="hidden" id="idwip<?= $z . $i; ?>" name="idwip<?= $a; ?>"><input type="hidden" name="itemperkeluar" value="<?= $i ?>">
                                </td>
                                <td>
                                    <select data-urutan="<?= $z . $i; ?>" id="idmateriallist<?= $z . $i; ?>" class="form-control input-sm" name="idmateriallist<?= $a ?>">
                                        <option value="<?= $key->id_material_keluar; ?>"><?= '[' . $key->i_material_keluar . '] - ' . $key->e_material_name_keluar . ' - ' . $key->e_satuan_name_keluar; ?></option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" id="nquantitylist<?= $z . $i; ?>" class="form-control text-right input-sm inputqty" autocomplete="off" name="nquantitylist<?= $a ?>" value="<?= $key->n_quantity_keluar ?>" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" onkeyup="angkahungkul(this); sumqty(this, <?= $z; ?>, <?= $i; ?>, null)">
                                </td>
                                <td>
                                    <select data-urutan="<?= $z . $i; ?>" id="idmateriallist2<?= $z . $i; ?>" class="form-control input-sm" name="idmateriallist2<?= $a ?>">
                                        <option value="<?= $key->id_material_masuk; ?>"><?= '[' . $key->i_material_masuk . '] - ' . $key->e_material_name_masuk . ' ' . $key->e_satuan_name_masuk; ?></option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" id="nquantitylist2<?= $z . $i; ?>" class="form-control text-right input-sm inputqty" autocomplete="off" name="nquantitylist2<?= $a ?>" value="<?= $key->n_quantity_masuk ?>" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" onkeyup="angkahungkul(this);hetangqtysisa(this, <?= $z . $i; ?>);sumqty(this, <?= $z; ?>, <?= $i; ?>, 2)">
                                    <input type="hidden" id="nquantitylist2sisa<?= $z . $i; ?>" class="form-control text-right input-sm inputqty" autocomplete="off" name="nquantitylist2sisa<?= $a; ?>">
                                </td>
                                <td><input type="text" class="form-control input-sm" name="eremarklist<?= $a ?>" id="eremarklist<?= $z . $i; ?>" value="<?= $key->e_remark; ?>" placeholder="Isi keterangan jika ada!" /></td>
                                <td colspan="2" class="text-center"><button type="button" title="Delete" data-b="<?= $z; ?>" class="ibtnDel btn-sm btn btn-circle btn-warning"><i class="ti-close"></i></button></td>
                            </tr>
                    <?php
                            $aa = $a;
                            $zz = $z;
                        }
                    } ?>
                <!-- <?php
                $i = 0;
                $z = 0;
                $a = 0;
                foreach($datadetailprod as $key) { 
                    $i++;
                    $a++;
                    ?>
                    <tr class="table-warning xx<?= $z; ?>">
                        <td class="text-center">
                            <spanlistx id="snum<?= $z; ?>"><b><?= ++$z; ?></b></spanlistx>
                        </td>
                        <td>
                            <select data-xx="<?= $z; ?>" id="id_wip<?= $z; ?>" class="form-control input-sm" name="id_wip<?= $z; ?>" onchange="changeval(<?= $z ?>, <?= $a ?>, <?= $i ?>)">
                                <option value="<?= $key->id_product; ?>"><?= '[' . $key->i_product_wip, '] - ' . $key->e_product_wipname; ?></option>
                            </select>
                        </td>
                        <td>
                            <input type="hidden" name="id_keluar<?= $z; ?>" id="id_keluar<?= $z; ?>" value="<?= $z; ?>" >
                        </td>
                        <td colspan="3"><input type="hidden" id="jmldetail<?= $z; ?>" value=""></td>
                        <td class="text-center"><button data-urut="<?= $z; ?>" type="button" id="addlist<?= $z; ?>" title="Tambah List" class="btn btn-sm btn-circle btn-info"><i class="ti-plus"></i></button></td>
                        <td class="text-center"><button type="button" onclick="hapusdetail(<?= $z; ?>);" title="Delete" class="ibtnDel btn btn-sm btn-circle btn-danger"><i class="ti-close"></i></button></td>
                    </tr>
                    <tr class="tr table-info del<?= $z; ?>" id="tr<?= $z; ?>">
                        <td class="text-center"><a href="#" onclick="toge(<?= $z; ?>); return false;" class="toggler<?= $z; ?>" data-icon-name="fa-eye" data-prod-cat="eye_<?= $z; ?>"><i class="fa fa-eye fa-lg text-success"></i></a></td>
                        <td colspan="7" class="font"><b>LIST BARANG MATERIAL</b></td>
                    </tr>
                    <?php foreach($datadetail as $key2) { ?>
                        <?php if($key->id_product == $key2->id_product) { ?>
                            <tr id="trdetail<?= $z . $i; ?>" class="table-success add<?= $z; ?> del<?= $z; ?> cat_eye_<?= $z; ?>">
                                <td class="text-right">
                                    <spanlist<?= $z; ?> id="snum<?= $z; ?>"></spanlist<?= $z; ?>><input type="hidden" id="idkeluarhead<?= $z . $i; ?>" name="idkeluarhead<?= $a; ?>"><input type="hidden" id="idwip<?= $z . $i; ?>" name="idwip<?= $a; ?>">
                                </td>
                                <td>
                                    <select data-urutan="<?= $z . $i; ?>" id="idmateriallist<?= $z . $i; ?>" class="form-control input-sm" name="idmateriallist<?= $a ?>">
                                        <option value="<?= $key2->id_material_keluar; ?>"><?= '[' . $key2->i_material_keluar . '] - ' . $key2->e_material_name_keluar . ' ' . $key2->e_satuan_name_keluar; ?></option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" id="nquantitylist<?= $z . $i; ?>" class="form-control text-right input-sm inputqty" autocomplete="off" name="nquantitylist<?= $a ?>" value="<?= $key2->n_quantity_keluar ?>" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" onkeyup="angkahungkul(this);">
                                </td>
                                <td>
                                    <select data-urutan="<?= $z . $i; ?>" id="idmateriallist2<?= $z . $i; ?>" class="form-control input-sm" name="idmateriallist2<?= $a ?>">
                                        <option value="<?= $key2->id_material_masuk; ?>"><?= '[' . $key2->i_material_masuk . '] - ' . $key2->e_material_name_masuk . ' ' . $key2->e_satuan_name_masuk; ?></option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" id="nquantitylist2<?= $z . $i; ?>" class="form-control text-right input-sm inputqty" autocomplete="off" name="nquantitylist2<?= $a ?>" value="<?= $key2->n_quantity_masuk ?>" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" onkeyup="angkahungkul(this);">
                                    <input type="hidden" id="nquantitylist2sisa<?= $z . $i; ?>" class="form-control text-right input-sm inputqty" autocomplete="off" name="nquantitylist2sisa<?= $a; ?>">
                                </td>
                                <td><input type="text" class="form-control input-sm" name="eremarklist<?= $a ?>" id="eremarklist<?= $z . $i; ?>" value="<?= $key2->e_remark; ?>" placeholder="Isi keterangan jika ada!" /></td>
                                <td colspan="2" class="text-center"><button type="button" title="Delete" data-b="<?= $z; ?>" class="ibtnDel btn-sm btn btn-circle btn-warning"><i class="ti-close"></i></button></td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                <?php } ?> -->
                </tbody>
            </table>
        </div>
    </div>
    <input type="hidden" name="jml" id="jml" value="<?= $zz; ?>">
    <input type="hidden" name="jmlitem" id="jmlitem" value="<?= $aa ?>">
</form>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script src="<?= base_url(); ?>assets/js/jquery.validate.min.js"></script>
<script>
    /*----------  LOAD SAAT DOKUMEN READY  ----------*/

    $(document).ready(function() {
        /*----------  Load Form Validation  ----------*/
        $('#cekinputan').validate({
            errorClass: "my-error-class",
            validClass: "my-valid-class"
        });
        $('#isj').mask('SS-0000-000000S');
        $('.select2').select2();
        /*Tidak boleh lebih dari hari ini*/
        showCalendar('.date', null, 0);
        /*Tidak boleh kurang dari hari ini*/
        showCalendar('.tgl', 0);

        $('#idtype').change(function(event) {
            $('#idpartner').val('');
            $('#idpartner').html('');
            $('#idreff').val('');
            $('#idreff').html('');
            $("#tabledatay tr:gt(0)").remove();
            $("#jml").val(0);
        });

        $('#idpartner').select2({
            placeholder: 'Pilih Partner',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder . '/cform/partner'); ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    var query = {
                        q: params.term,
                        idtype: $('#idtype').val(),
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

        /*----------  UPDATE STATUS DOKUMEN ----------*/

        $('#send').click(function(event) {
            statuschange('<?= $folder; ?>', $('#id').val(), '2', '<?= $dfrom . "','" . $dto; ?>');
        });

        $('#cancel').click(function(event) {
            statuschange('<?= $folder; ?>', $('#id').val(), '1', '<?= $dfrom . "','" . $dto; ?>');
        });

        $('#hapus').click(function(event) {
            statuschange('<?= $folder; ?>', $('#id').val(), '5', '<?= $dfrom . "','" . $dto; ?>');
        });

        $("#idtype, #idpartner, #dfrom, #dto").change(function(event) {
            clear_table();
        });

        /**
         * Tambah Item Khusus Makloon
         */

        var iter = $('#jml').val();
        $("#addrowlist").on("click", function() {
            iter++;
            $("#jml").val(iter);
            var no = $('#tabledatalistx .tr').length;
            var newRow = $('<tr class="table-warning xx' + iter + '">');
            var cols = "";
            var col = "";
            cols += `<td class="text-center"><spanlistx id="snum${iter}"><b>${(no+1)}</b></spanlistx></td>`;
            cols += `<td><select data-xx="${iter}" id="id_wip${iter}" class="form-control input-sm" name="id_wip${iter}" ></select></td>`;
            cols += `<td><input type="hidden" name="id_keluar${iter}" id="id_keluar${iter}" value="${iter}" ></td>`;
            cols += `<td></td>`;
            cols += `<td></td>`;
            cols += `<td><input type="hidden" class="form-control input-sm" name="eremark${iter}" id="eremark${iter}" placeholder="Isi keterangan jika ada!"/></td>`;
            // cols += `<td class="text-center"><i data-urut="${iter}" title="Tambah List" id="addlist${iter}" class="fa fa-plus fa-lg text-info"></i></td>`;
            cols += `<td class="text-center"><button data-urut="${iter}" type="button" id="addlist${iter}" title="Tambah List" class="btn btn-sm btn-circle btn-info"><i class="ti-plus"></i></button></td>`;
            cols += `<td class="text-center"><button type="button" onclick="hapusdetail(${iter});" title="Delete" class="ibtnDel btn btn-sm btn-circle btn-danger"><i class="ti-close"></i></button></td>`;
            newRow.append(cols);
            // $("#tabledatalistx").append(newRow);
            $("#tabledatalistx tr:first").after(newRow);
            var newRow1 = $('<tr class="tr table-info del' + iter + '" id="tr' + iter + '"><td class="text-center"><a href="#" onclick="toge(' + iter + '); return false;" class="toggler' + iter + '" data-icon-name="fa-eye" data-prod-cat="eye_' + iter + '"><i class="fa fa-eye fa-lg text-success"></i></a></td><td colspan="7" class="font"><b>LIST BARANG MATERIAL</b></td></tr>');
            // $("#tabledatalistx").append(newRow1);
            // $("#tabledatalistx tr:last").after(newRow1);
            $(newRow1).insertAfter("#tabledatalistx .xx" + iter);
            restart();
            $('#id_wip' + iter).select2({
                placeholder: 'Cari Kode / Nama WIP',
                allowClear: true,
                width: "100%",
                type: "POST",
                ajax: {
                    url: '<?= base_url($folder . '/cform/product_wip/'); ?>',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        var query = {
                            q: params.term,
                            dfrom: $('#dfrom').val(),
                            dto: $('#dto').val(),
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
                // var z = $(this).data('xx');
                // var ada = true;
                // for (var x = 1; x <= $('#jml').val(); x++) {
                //     if ($(this).val() != null) {
                //         if ((($(this).val()) == $('#id_wip' + x).val()) && (z != x)) {
                //             swal("kode barang tersebut sudah ada !!!!!");
                //             ada = false;
                //             break;
                //         }
                //     }
                //     // $('#idmaterialhead' + z + x).val($('#id_wip' + z).val());
                // }
                // if (!ada) {
                //     $(this).val('');
                //     $(this).html('');
                // }
            });
            $('#idmaterial' + iter).select2({
                placeholder: 'Cari Kode / Nama Material',
                allowClear: true,
                width: "100%",
                type: "POST",
                ajax: {
                    url: '<?= base_url($folder . '/cform/product_material/'); ?>',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        var query = {
                            q: params.term,
                            dfrom: $('#dfrom').val(),
                            dto: $('#dto').val(),
                            id_wip: $('#id_wip' + $(this).data('nourut')).val(),
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
                        if ((($(this).val()) == $('#idmaterial' + x).val()) && (z != x)) {
                            swal("kode barang tersebut sudah ada !!!!!");
                            ada = false;
                            break;
                        }
                    }
                    $('#idmaterialhead' + z + x).val($('#idmaterial' + z).val());
                }
                if (!ada) {
                    $(this).val('');
                    $(this).html('');
                }
                /* else {
                                   $.ajax({
                                       type: "post",
                                       data: {
                                           'id_material': $(this).val(),
                                           'dfrom': $('#dfrom').val(),
                                           'dto': $('#dfrom').val(),
                                       },
                                       url: '<?= base_url($folder . '/cform/detail_product'); ?>',
                                       dataType: "json",
                                       success: function(data) {
                                           // console.log(data['detail'][0]['n_quantity']);
                                           $('#nquantity'+z).val(data['detail'][0]['n_quantity']);
                                       },
                                       error: function() {
                                           swal('Error :(');
                                       }
                                   });
                               } */
            });

            var nox = 0;
            $("#addlist" + iter).on("click", function() {
                let jmlitem = parseInt($('#jmlitem').val()) + 1;
                var u = $(this).data('urut');
                nox++;
                var newRow1 = $('<tr id="trdetail' + u + nox + '" class="table-success add' + u + ' del' + u + ' cat_eye_' + u + '">');
                var nomer = $('#tabledatalistx .add' + u).length;
                col += `<td class="text-right"><spanlist${u} id="snum${u}"></spanlist${u}><input type="hidden" id="idkeluarhead${u}${nox}" name="idkeluarhead${jmlitem}"><input type="hidden" id="idwip${u}${nox}" name="idwip${jmlitem}"><input type="hidden" name="itemperkeluar" value="${nox}"></td>`;
                col += `<td><select data-urutan="${u}${nox}" data-nourut="${u}${nox}" id="idmateriallist${u}${nox}" class="form-control input-sm" name="idmateriallist${jmlitem}"></select></td>`;
                col += `<td><input type="text" id="nquantitylist${u}${nox}" class="form-control text-right input-sm inputqty" autocomplete="off" name="nquantitylist${jmlitem}" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);sumqty(this, ${u}, ${nox}, null)"></td>`;
                col += `<td><select data-nourut="${u}${nox}" id="idmateriallist2${u}${nox}" class="form-control input-sm" name="idmateriallist2${jmlitem}" ></select></td>`;
                col += `<td><input type="text" id="nquantitylist2${u}${nox}" class="form-control text-right input-sm inputqty" autocomplete="off" name="nquantitylist2${jmlitem}" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);hetang(${u}${nox});hetangqtysisa(this, ${u}${nox});sumqty(this, ${u}, ${nox}, 2)"><input type="hidden" id="nquantitylist2sisa${u}${nox}" class="form-control text-right input-sm inputqty" autocomplete="off" name="nquantitylist2sisa${jmlitem}"></td>`;
                col += `<td><input type="text" class="form-control input-sm" name="eremarklist${jmlitem}" id="eremarklist${u}${nox}" placeholder="Isi keterangan jika ada!"/></td>`;
                col += `<td colspan="2" class="text-center"><button type="button" title="Delete" data-b = "${u}" class="ibtnDel btn-sm btn btn-circle btn-warning"><i class="ti-close"></i></button></td></tr>`;
                newRow1.append(col);
                if (nox > 1) {
                    var v = nox - 1;
                    if (typeof $('#idmateriallist' + u + v).val() == 'undefined') {
                        $(newRow1).insertAfter("#tabledatalistx #tr" + u);
                    } else {
                        $(newRow1).insertAfter("#tabledatalistx #trdetail" + u + v)
                    }
                } else {
                    $(newRow1).insertAfter("#tabledatalistx #tr" + u);
                }
                $('#jmlitem').val(jmlitem);
                $('#idkeluarhead' + u + nox).val($('#id_keluar' + u).val());
                $('#idwip' + u + nox).val($('#id_wip' + u).val());
                $('#nquantityhead' + u + nox).val($('#nquantity' + u).val());

                $('#idmateriallist' + u + nox).select2({
                    placeholder: 'Cari Kode / Nama Material',
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
                                dfrom: $('#dfrom').val(),
                                dto: $('#dto').val(),
                                idtype: $('#idtype').val(),
                            }
                            return query;
                        },
                        processResults: function(data) {
                            return {
                                results: data,
                            };
                        },
                        cache: true
                    }
                }).change(function(event) {
                    var z = $(this).data('urutan');
                    var ada = true;
                    for (var x = 1; x <= $('#tabledatalistx .add' + u).length; x++) {
                        y = String(u) + x;
                        if ($(this).val() != null) {
                            if ((($(this).val()) == $('#idmateriallist' + u + x).val()) && (z != y)) {
                                swal("kode barang sudah ada !!!!!");
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

                $('#idmateriallist2' + u + nox).select2({
                    placeholder: 'Cari Kode / Nama Material',
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
                                dfrom: $('#dfrom').val(),
                                dto: $('#dto').val(),
                                idtype: $('#idtype').val(),
                            }
                            return query;
                        },
                        processResults: function(data) {
                            return {
                                results: data,
                            };
                        },
                        cache: true
                    }
                }).change(function(event) {
                    var z = $(this).data('nourut');
                    var ada = true;
                    for (var x = 1; x <= $('#tabledatalistx .add' + u).length; x++) {
                        y = String(u) + x;
                        if ($(this).val() != null) {
                            if ((($(this).val()) == $('#idmateriallist2' + u + x).val()) && (z != y)) {
                                swal("kode barang sudah ada !!!!!");
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
            });

            $("#tabledatalistx").on("click", ".ibtnDel", function(event) {
                $(this).closest("tr").remove();
                var obj = $('#tabledatalistx tr:visible').find('spanlistx');
                $.each(obj, function(key, value) {
                    id = value.id;
                    $('#' + id).html(key + 1);
                });
            });
        });


        for (var i = 1; i <= $('#jml').val(); i++) {
            $('#id_wip' + i).select2({
                placeholder: 'Cari Kode / Nama WIP',
                allowClear: true,
                width: "100%",
                type: "POST",
                ajax: {
                    url: '<?= base_url($folder . '/cform/product_wip/'); ?>',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        var query = {
                            q: params.term,
                            dfrom: $('#dfrom').val(),
                            dto: $('#dto').val(),
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
                // var z = $(this).data('xx');
                // var ada = true;
                // for (var x = 1; x <= $('#jml').val(); x++) {
                //     if ($(this).val() != null) {
                //         if ((($(this).val()) == $('#id_wip' + x).val()) && (z != x)) {
                //             swal("kode barang tersebut sudah ada !!!!!");
                //             ada = false;
                //             break;
                //         }
                //     }
                //     // $('#idmaterialhead' + z + x).val($('#id_wip' + z).val());
                // }
                // if (!ada) {
                //     $(this).val('');
                //     $(this).html('');
                // }
            });
            $('#idmaterial' + i).select2({
                placeholder: 'Cari Kode / Nama Material',
                allowClear: true,
                width: "100%",
                type: "POST",
                ajax: {
                    url: '<?= base_url($folder . '/cform/product_material/'); ?>',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        var query = {
                            q: params.term,
                            dfrom: $('#dfrom').val(),
                            dto: $('#dto').val(),
                            id_wip: $('#id_wip' + $(this).data('nourut')).val(),
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
                        if ((($(this).val()) == $('#idmaterial' + x).val()) && (z != x)) {
                            swal("kode barang tersebut sudah ada !!!!!");
                            ada = false;
                            break;
                        }
                    }
                    $('#idmaterialhead' + z + x).val($('#idmaterial' + z).val());
                }
                if (!ada) {
                    $(this).val('');
                    $(this).html('');
                }
            });

            $('#jmldetail' + i).val($('#tabledatalistx .add' + i).length);
            $("#addlist" + i).on("click", function() {
                let jmlitem = parseInt($('#jmlitem').val()) + 1;
                var col = "";
                var u = $(this).data('urut');
                var nox = $('#jmldetail' + u).val();
                nox++;
                $('#jmldetail' + u).val(nox);
                var newRow1 = $('<tr id="trdetail' + u + nox + '" class="table-success add' + u + ' del' + u + ' cat_eye_' + u + '">');
                var nomer = $('#tabledatalistx .add' + u).length;
                col += `<td class="text-right"><spanlist${u} id="snum${u}"></spanlist${u}><input type="hidden" id="idkeluarhead${u}${nox}" name="idkeluarhead${jmlitem}"><input type="hidden" id="idwip${u}${nox}" name="idwip${jmlitem}"><input type="hidden" name="itemperkeluar" value="${nox}"></td>`;
                col += `<td><select data-urutan="${u}${nox}" data-nourut="${u}${nox}" id="idmateriallist${u}${nox}" class="form-control input-sm" name="idmateriallist${jmlitem}"></select></td>`;
                col += `<td><input type="text" id="nquantitylist${u}${nox}" class="form-control text-right input-sm inputqty" autocomplete="off" name="nquantitylist${jmlitem}" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);sumqty(this, ${u}, ${nox}, null)"></td>`;
                col += `<td><select data-nourut="${u}${nox}" id="idmateriallist2${u}${nox}" class="form-control input-sm" name="idmateriallist2${jmlitem}" ></select></td>`;
                col += `<td><input type="text" id="nquantitylist2${u}${nox}" class="form-control text-right input-sm inputqty" autocomplete="off" name="nquantitylist2${jmlitem}" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);hetang(${u}${nox});hetangqtysisa(this, ${u}${nox});sumqty(this, ${u}, ${nox}, 2)"><input type="hidden" id="nquantitylist2sisa${u}${nox}" class="form-control text-right input-sm inputqty" autocomplete="off" name="nquantitylist2sisa${jmlitem}"></td>`;
                col += `<td><input type="text" class="form-control input-sm" name="eremarklist${jmlitem}" id="eremarklist${u}${nox}" placeholder="Isi keterangan jika ada!"/></td>`;
                col += `<td colspan="2" class="text-center"><button type="button" title="Delete" data-b = "${u}" class="ibtnDel btn-sm btn btn-circle btn-warning"><i class="ti-close"></i></button></td></tr>`;
                newRow1.append(col);
                if (nox > 1) {
                    var v = nox - 1;
                    if (typeof $('#idmateriallist' + u + v).val() == 'undefined') {
                        $(newRow1).insertAfter("#tabledatalistx #tr" + u);
                    } else {
                        $(newRow1).insertAfter("#tabledatalistx #trdetail" + u + v);
                    }
                } else {
                    $(newRow1).insertAfter("#tabledatalistx #tr" + u);
                }
                $('#jmlitem').val(jmlitem);
                $('#idkeluarhead' + u + nox).val($('#id_keluar' + u).val());
                $('#idwip' + u + nox).val($('#id_wip' + u).val());
                $('#nquantityhead' + u + nox).val($('#nquantity' + u).val());

                $('#idmateriallist' + u + nox).select2({
                    placeholder: 'Cari Kode / Nama Material',
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
                                dfrom: $('#dfrom').val(),
                                dto: $('#dto').val(),
                                idtype: $('#idtype').val(),
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
                    var z = $(this).data('urutan');
                    var ada = true;
                    for (var x = 1; x <= $('#tabledatalistx .add' + u).length; x++) {
                        y = String(u) + x;
                        if ($(this).val() != null) {
                            if ((($(this).val()) == $('#idmateriallist' + u + x).val()) && (z != y)) {
                                swal("kode barang sudah ada !!!!!");
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
                $('#idmateriallist2' + u + nox).select2({
                    placeholder: 'Cari Kode / Nama Material',
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
                                dfrom: $('#dfrom').val(),
                                dto: $('#dto').val(),
                                idtype: $('#idtype').val(),
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
                    for (var x = 1; x <= $('#tabledatalistx .add' + u).length; x++) {
                        y = String(u) + x;
                        if ($(this).val() != null) {
                            if ((($(this).val()) == $('#idmateriallist2' + u + x).val()) && (z != y)) {
                                swal("kode barang sudah ada !!!!!");
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
            });

            for (var j = 1; j <= $('#tabledatalistx .add' + i).length; j++) {
                $('#idmateriallist' + i + j).select2({
                    placeholder: 'Cari Kode / Nama Material',
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
                                ibagian: $('#ibagian').val(),
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
                    // var z = String($(this).data('urutan'));
                    // var ada = true;
                    // var t = z.substring(0, 1);
                    // for (var x = 1; x <= $('#tabledatalistx .add' + t).length; x++) {
                    //     y = String(t) + x;
                    //     if ($(this).val() != null) {
                    //         if ((($(this).val()) == $('#idmateriallist' + t + x).val()) && (z != y)) {
                    //             swal("kode barang sudah ada !!!!!");
                    //             ada = false;
                    //             break;
                    //         }
                    //     }
                    // }
                    // if (!ada) {
                    //     $(this).val('');
                    //     $(this).html('');
                    // }
                });
                $('#idmateriallist2' + i + j).select2({
                    placeholder: 'Cari Kode / Nama Material',
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
                                ibagian: $('#ibagian').val(),
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
                    // var z = String($(this).data('urutan'));
                    // var ada = true;
                    // var t = z.substring(0, 1);
                    // for (var x = 1; x <= $('#tabledatalistx .add' + t).length; x++) {
                    //     y = String(t) + x;
                    //     if ($(this).val() != null) {
                    //         if ((($(this).val()) == $('#idmateriallist2' + t + x).val()) && (z != y)) {
                    //             swal("kode barang sudah ada !!!!!");
                    //             ada = false;
                    //             break;
                    //         }
                    //     }
                    // }
                    // if (!ada) {
                    //     $(this).val('');
                    //     $(this).html('');
                    // }
                });
                $('#idkeluarhead' + i + j).val($('#id_keluar' + i).val());
                $('#idwip' + i + j).val($('#id_wip' + i).val());
            }


            $("#tabledatalistx").on("click", ".ibtnDel", function(event) {
                $(this).closest("tr").remove();
                var obj = $('#tabledatalistx tr:visible').find('spanlistx');
                $.each(obj, function(key, value) {
                    id = value.id;
                    $('#' + id).html(key + 1);
                });
            });
        }
    });


    /*----------  CEK QTY HEADER  ----------*/

    function cekqty(i) {
        if (parseInt($('#nquantity' + i).val()) > parseInt($('#nquantitysisa' + i).val())) {
            swal('Maaf', 'Jumlah Kirim Tidak Boleh Lebih Dari Jumlah Sisa = ' + $('#nquantitysisa' + i).val() + '!', 'error');
            $('#nquantity' + i).val($('#nquantitysisa' + i).val());
        }
    }

    /*----------  CEK QTY ITEM  ----------*/

    function cekjml(i) {
        if (parseInt($('#nqtylist' + i).val()) > parseInt($('#nqtylistsisa' + i).val())) {
            swal('Maaf', 'Jumlah Kirim Tidak Boleh Lebih Dari Jumlah Sisa = ' + $('#nqtylistsisa' + i).val() + '!', 'error');
            $('#nqtylist' + i).val($('#nqtylistsisa' + i).val());
        }
        if (parseInt($('#nqtylist' + i).val()) <= 0) {
            swal('Maaf :(', 'Jumlah Pemenuhan List Harus Lebih Besar dari 0!', 'error');
            $('#nqtylist' + i).val($('#nqtylistsisa' + i).val());
        }
    }

    function changeval(z,a,i) {
        let allValues = $(`.add${z} td:first-child input[name^="itemperkeluar"]`).map(function() { return +this.value; }).toArray();
        var maxValue = Math.max.apply(Math, allValues);
        for(let o = 1; o <= maxValue; o++) {
            $(`#idkeluarhead${z}${o}`).val($(`#id_keluar${z}`).val());
            $(`#idwip${z}${o}`).val($(`#id_wip${z}`).val());
        }
    }

    /*----------- Validasi QTY --------- */
    function sumqty(i, u, nox, qty) {
        let allValues = $(`.add${u} td:first-child input[name^="itemperkeluar"]`).map(function() { return +this.value; }).toArray();
        var maxValue = Math.max.apply(Math, allValues);
        if(qty) {
            if($(`#idmateriallist2${u}${nox}`).val()) {
                let totalQtyKeluar = 0;
                let totalQtyMasuk = 0;
                for(let a = 1; a<=maxValue; a++) {
                    totalQtyKeluar += parseInt($(`#nquantitylist${u}${a}`).val())
                    totalQtyMasuk += parseInt($(`#nquantitylist2${u}${a}`).val())
                }
                if(totalQtyMasuk > totalQtyKeluar) {
                    swal(`total quantity keluar: ${totalQtyKeluar} tidak boleh lebih kecil dari total quantity masuk: ${totalQtyMasuk}`);
                    $(`#nquantitylist2${u}${nox}`).val(0)
                }
            } else {
                $(`#nquantitylist2${u}${nox}`).val(0)
            }
        } else {
            if($(`#idmateriallist${u}${nox}`).val()) {
                let totalQtyKeluar = 0;
                let totalQtyMasuk = 0;
                for(let a = 1; a<=maxValue; a++) {
                    totalQtyKeluar += parseInt($(`#nquantitylist${u}${a}`).val())
                    totalQtyMasuk += parseInt($(`#nquantitylist2${u}${a}`).val())
                }
                if(totalQtyMasuk > totalQtyKeluar) {
                    swal(`total quantity keluar: ${totalQtyKeluar} tidak boleh lebih kecil dari total quantity masuk: ${totalQtyMasuk}`);
                    $(`#nquantitylist2${u}${nox}`).val(0)
                }
            } else {
                $(`#nquantitylist${u}${nox}`).val(0)
            }
        }
    }

    /*----------  SET VALUE DETAIL  ----------*/

    function hetang(qty, id) {
        for (var i = 0; i < $('#jml').val(); i++) {
            if (id == $("#idmaterial" + i).val()) {
                if (qty == '') {
                    qty = 0;
                }
                $('#nqty' + i).val(qty);
            }
        }
    }

    function hetang(i) {
        for (var x = 1; x <= $('#tabledatalistx .add' + i).length; x++) {
            $('#nquantityhead' + i + x).val($('#nquantity' + i).val());
        }
    }

    function hetangqtysisa(i, o) {
        let valueqty = $(i).val();
        $(`#nquantitylist2sisa${o}`).val(valueqty);
    }

    /**
     * Hapus Detail Item
     */

    function hapusdetail(x) {
        $("#tabledatalistx tbody").each(function() {
            $("tr.del" + x).remove();
        });
    }

    function toge(i) {
        /* $(".toggler"+i).click(function(e) {
            e.preventDefault(); */
        $('.cat_' + $(".toggler" + i).attr('data-prod-cat')).toggle();
        // console.log($(".toggler" + i).find('i'));
        // $(".toggler"+i).addClass('active');

        //Remove the icon class
        if ($(".toggler" + i).find('i').hasClass('fa-eye-slash')) {
            //then change back to the original one
            $(".toggler" + i).find('i').removeClass('fa-eye-slash').addClass($(".toggler" + i).data('icon-name'));
        } else {
            //Remove the cross from all other icons
            $('.faq-links').each(function() {
                if ($(".toggler" + i).find('i').hasClass('fa-eye-slash')) {
                    $(".toggler" + i).find('i').removeClass('fa-eye-slash').addClass($(".toggler" + i).data('icon-name'));
                }
            });

            $(".toggler" + i).find('i').addClass('fa-eye-slash').removeClass($(".toggler" + i).data('icon-name'));
        }
        // });
    }

    function restart() {
        var obj = $('#tabledatalistx tr:visible').find('spanlistx');
        $.each(obj, function(key, value) {
            id = value.id;
            $('#' + id).html(key + 1);
        });
    }

    function clear_table() {
        // $('#tabledatalistx'),remove();
        // $('#tabledatalistx > tbody').remove();

        // $("#tabledatalistx > tr:eq(1)").remove();
        $("#tabledatalistx tr:gt(0)").remove();
        // $('#tableBody').find('tr').remove();
        $('#jml').val(0);
    }

    function simpan() {
        var valid = $("#cekinputan").valid();
        if (valid) {
            ada = false;
            /* if ($('#jml').val() == 0) {
                swal('Isi item minimal 1!');
                return false;
            } else { */
            swal({
                title: "Update Data Ini?",
                text: "Anda Dapat Membatalkannya Nanti",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonColor: 'LightSeaGreen',
                confirmButtonText: "Ya, Update!",
                closeOnConfirm: false
            }, function() {
                $.ajax({
                    type: "POST",
                    data: $("form").serialize(),
                    url: '<?= base_url($folder . '/cform/update/'); ?>',
                    dataType: "json",
                    success: function(data) {
                        if (data.sukses == true) {
                            $('#id').val(data.id);
                            swal("Sukses!", "No Dokumen : " + data.kode +
                                ", Berhasil Diupdate :)", "success");
                            $("input").attr("disabled", true);
                            $("select").attr("disabled", true);
                            $("#submit").attr("disabled", true);
                            $("#addrow").attr("disabled", true);
                            $("#send").attr("disabled", false);
                        } else if (data.sukses == 'ada') {
                            swal("Maaf :(", "Data tersebut sudah ada :(", "error");
                        } else {
                            swal("Maaf :(", "No Dokumen : " + data.kode +
                                ", Gagal Diupdate :(", "error");
                        }
                    },
                    error: function() {
                        swal("Maaf", "Data Gagal Diupdate :(", "error");
                    }
                });
            });
            // }
        }
        return false;
    }
</script>