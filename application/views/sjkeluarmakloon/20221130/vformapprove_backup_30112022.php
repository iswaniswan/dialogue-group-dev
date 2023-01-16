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
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-lg fa-check mr-2"></i><?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list fa-lg mr-2"></i><?= $title_list; ?> </a>
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
                            <input type="text" value="<?= $data->e_bagian_name; ?>" readonly="" class="form-control input-sm">
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" name="idocument" id="isj" value="<?= $data->i_document; ?>" readonly="" class="form-control input-sm">
                            </div>
                            <input type="hidden" name="id" id="id" value="<?= $id; ?>">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" name="ddocument" id="ddocument" class="form-control input-sm date" value="<?= $data->date_document; ?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" name="destimate" id="destimate" class="form-control input-sm tgl" value="<?= $data->date_estimate; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Bagian Penerima</label>
                        <label class="col-md-3">Tipe Makloon</label>
                        <label class="col-md-3">Partner</label>
                        <label class="col-md-3">Keterangan</label>
                        <div class="col-sm-3">
                            <input type="text" value="<?= $data->e_bagian_receive_name; ?>" readonly="" class="form-control input-sm">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" value="<?= $data->e_type_makloon_name; ?>" readonly="" class="form-control input-sm">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" value="<?= $data->e_supplier_name; ?>" readonly="" class="form-control input-sm">
                        </div>
                        <div class="col-sm-3">
                            <textarea type="text" readonly class="form-control input-sm"><?= $data->e_remark; ?></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main')"> <i class="fa fa-arrow-circle-left mr-2 fa-lg"></i>Kembali</button>
                        </div>
                        <div class="col-sm-3">
                            <button type="button" class="btn btn-warning btn-block btn-sm" onclick="statuschange('<?= $folder . "','" . $id; ?>','1','<?= $dfrom . "','" . $dto; ?>');"> <i class="fa fa-pencil-square-o mr-2 fa-lg"></i>Change Requested</button>
                        </div>
                        <div class="col-sm-3">
                            <button type="button" class="btn btn-danger btn-block btn-sm" onclick="statuschange('<?= $folder . "','" . $id; ?>','4','<?= $dfrom . "','" . $dto; ?>');"> <i class="fa fa-times mr-2 fa-lg"></i>Reject</button>
                        </div>
                        <div class="col-sm-3">
                            <button type="button" id="approve" class="btn btn-success btn-block btn-sm" onclick="statuschange('<?= $folder . "','" . $id; ?>','6','<?= $dfrom . "','" . $dto; ?>');"> <i class="fa fa-check-square-o mr-2 fa-lg"></i>Approve</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="white-box" id="detail">
    <div class="row">
        <div class="col-sm-6">
            <h3 class="box-title m-b-0 ml-1">Detail Barang</h3>
        </div>
        <div class="col-sm-6 text-right"><span class="text-right mr-1"><?= $this->doc_qe; ?></span></div>
    </div>
    <div class="table-responsive">
        <table id="sitabel" class="table color-table inverse-table table-bordered class sitabel" cellpadding="8" cellspacing="1" width="100%">
            <thead>
                <tr>
                    <th class="text-center" style="width: 3%;">No</th>
                    <th>Kode WIP</th>
                    <th>Nama Barang WIP</th>
                    <th>Kode Material</th>
                    <th>Nama Barang Material</th>
                    <th class="text-right">Qty</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <?php
                $i = 0;
                $z = 0;
                if ($datadetail) {
                    $group = "";
                    foreach ($datadetail as $key) {
                        $i++;
                        if ($group != $key->id_material) {
                            $z++;
                        }
                        if ($group == "") { ?>
                            <tr class="table-warning xx<?= $z; ?>">
                                <td class="text-center">
                                    <spanlistx id="snum<?= $z; ?>"><b><?= $z; ?></b></spanlistx>
                                </td>
                                <td><?= $key->i_product_wip; ?></td>
                                <td><?= $key->e_product_wipname; ?></td>
                                <td><?= $key->i_material; ?></td>
                                <td><?= $key->e_material_name . ' ' . $key->e_satuan_name; ?></td>
                                <td class="text-right"><?= $key->n_quantity; ?></td>
                                <td></td>
                            </tr>
                            <tr class="tr table-info del<?= $z; ?>" id="tr<?= $z; ?>">
                                <td class="text-center"><a href="#" onclick="toge(<?= $z; ?>); return false;" class="toggler<?= $z; ?>" data-icon-name="fa-eye" data-prod-cat="eye_<?= $z; ?>"><i class="fa fa-eye fa-lg text-success"></i></a></td>
                                <td colspan="7" class="font"><b>LIST BARANG MATERIAL</b></td>
                            </tr>
                            <?php
                        } else {
                            if ($group != $key->id_material) { ?>
                                <tr class="table-warning xx<?= $z; ?>">
                                    <td class="text-center">
                                        <spanlistx id="snum<?= $z; ?>"><b><?= $z; ?></b></spanlistx>
                                    </td>
                                    <td><?= $key->i_product_wip; ?></td>
                                    <td><?= $key->e_product_wipname; ?></td>
                                    <td><?= $key->i_material; ?></td>
                                    <td><?= $key->e_material_name . ' ' . $key->e_satuan_name; ?></td>
                                    <td class="text-right"><?= $key->n_quantity; ?></td>
                                    <td></td>
                                </tr>
                                <tr class="tr table-info del<?= $z; ?>" id="tr<?= $z; ?>">
                                    <td class="text-center"><a href="#" onclick="toge(<?= $z; ?>); return false;" class="toggler<?= $z; ?>" data-icon-name="fa-eye" data-prod-cat="eye_<?= $z; ?>"><i class="fa fa-eye fa-lg text-success"></i></a></td>
                                    <td colspan="7" class="font"><b>LIST BARANG MATERIAL</b></td>
                                </tr>
                        <?php $i = 1;
                            }
                        }
                        $group = $key->id_material; ?>
                        <tr id="trdetail<?= $z . $i; ?>" class="table-success add<?= $z; ?> del<?= $z; ?> cat_eye_<?= $z; ?>">
                            <td class="text-right">
                                <spanlist<?= $z; ?> id="snum<?= $z; ?>"></spanlist<?= $z; ?>>
                            </td>
                            <td><?= $key->i_material_list;?></td>
                            <td colspan="3"><?= $key->e_material_name_list . ' ' . $key->e_satuan_name_list; ?></td>
                            <td class="text-right"><?= $key->n_quantity_list; ?></td>
                            <td><?= $key->e_remark; ?></td>
                        </tr>
                <?php }
                } ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    $(document).ready(function() {
        fixedtable($('#sitabel'));
    });

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
</script>