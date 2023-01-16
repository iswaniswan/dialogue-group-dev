<style type="text/css">
    .select2-results__options {
        font-size: 14px !important;
    }

    .select2-selection__rendered {
        font-size: 12px;
    }

    .pudding {
        padding-left: 3px;
        padding-right: 3px;
        font-size: 14px;
        background-color: #e1f1e4;
    }

    .font-11 {
        padding-left: 3px;
        padding-right: 3px;
        font-size: 11px;
        height: 20px;
    }

    .font-12 {
        padding-left: 3px;
        padding-right: 3px;
        font-size: 12px;
    }
</style>
<form id="cekinputan">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <i class="fa fa-eye mr-2"></i> &nbsp; <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp;
                        <?= $title_list; ?></a>
                </div>
                <div class="panel-body table-responsive">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-3">Bagian Pembuat</label>
                            <label class="col-md-3">Nomor Dokumen</label>
                            <label class="col-md-3">Tanggal Dokumen</label>
                            <label class="col-md-3">Referensi Forecast Jahit</label>
                            <div class="col-sm-3">
                                <select name="ibagian" id="ibagian" class="form-control select2" required="" disabled="">
                                    <?php if ($bagian) {
                                        foreach ($bagian as $row) : ?>
                                            <option value="<?= $row->i_bagian; ?>" <?php if ($row->i_bagian == $data->i_bagian) { ?> selected <?php } ?>>
                                                <?= $row->e_bagian_name; ?>
                                            </option>
                                    <?php endforeach;
                                    } ?>
                                </select>
                                <input type="hidden" name="ibagianold" id="ibagianold" value="<?= $data->i_bagian; ?>">
                            </div>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="hidden" name="id" id="id" value="<?= $id; ?>">
                                    <input type="hidden" name="idocumentold" id="ifccuttingold" value="<?= $data->i_document; ?>">
                                    <input type="text" name="idocument" required="" id="ifccutting" readonly="" autocomplete="off" onkeyup="gede(this);" maxlength="25" class="form-control input-sm" value="<?= $data->i_document; ?>" aria-label="Text input with dropdown button">
                                </div>
                                <span class="notekode">Format : (<?= $number; ?>)</span><br>
                                <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                            </div>
                            <div class="col-sm-3">
                                <input type="text" id="ddocument" name="ddocument" class="form-control input-sm date" required="" readonly value="<?= $data->d_document; ?>">
                            </div>
                            <div class="col-sm-3">
                                <input type="hidden" id="idforecast" name="idforecast" required="" value="<?= $data->id_referensi; ?>">
                                <input type="hidden" id="iperiode" name="iperiode" required="" value="<?= $data->tahun . $data->bulan; ?>">
                                <input type="text" class="form-control input-sm" readonly value="<?= $data->i_document_forecast . ' - [' . $this->fungsi->mbulan($data->bulan) . ' ' . $data->tahun . ']'; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-12">Keterangan</label>
                            <div class="col-sm-12">
                                <textarea id="eremark" name="eremark" class="form-control" placeholder="Isi keterangan jika ada!" readonly><?= $data->e_remark; ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $i = 0;
    if ($datadetail) { ?>
        <div class="white-box" id="detail">
            <div class="col-sm-6">
                <h3 class="box-title m-b-0">Detail Item</h3>
            </div>
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table id="tabledatay" class="table color-table inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                        <thead>
                            <tr>
                                <th class="text-center" width="3%;">No</th>
                                <th width="8%;">Kode</th>
                                <th width="25%;">Nama Barang</th>
                                <th class="text-center" width="8%;">Warna</th>
                                <th class="text-right" width="7%;">FC Jahit</th>
                                <th class="text-right" width="7%;">Sisa</th>
                                <th class="text-right" width="7%;">Jumlah</th>
                                <th width="13%;">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 0;
                            $group = "";
                            foreach ($datadetail as $key) {
                                /* $subkategori = trim(str_replace(" ", "", $key->e_type_name)); */
                                if ($group == "") { ?>
                                    <tr class="table-active">
                                        <td class="text-center"><a href="#" class="toggler" data-icon-name="fa-eye-slash" data-prod-cat="<?= $key->grup; ?>"><i class="fa fa-eye-slash text-success"></i></a></td>
                                        <td colspan="10"><?= $key->e_type_name; ?></td>
                                    </tr>
                                    <?php } else {
                                    if ($group != $key->grup) { ?>
                                        <tr class="table-active">
                                            <td class="text-center"><a href="#" class="toggler" data-icon-name="fa-eye-slash" data-prod-cat="<?= $key->grup; ?>"><i class="fa fa-eye-slash text-success"></i></a></td>
                                            <td colspan="10"><?= $key->e_type_name; ?></td>
                                        </tr>
                                <?php $i = 0;
                                    }
                                }
                                $group = $key->grup;
                                ?>
                                <tr class="<?= $key->grup; ?>" style="display:none">
                                    <td class="text-center"><?= $i + 1; ?></td>
                                    <td><?= $key->i_product_wip; ?></td>
                                    <td><?= $key->e_product_name; ?></td>
                                    <td><?= $key->e_color_name; ?></td>
                                    <td class="text-right"><?= $key->n_quantity_fc; ?></td>
                                    <td class="text-right"><?= $key->n_quantity_sisa; ?></td>
                                    <td class="text-right"><?= $key->n_quantity; ?></td>
                                    <td><?= $key->e_remark; ?></td>
                                </tr>
                            <?php
                                $i++;
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php } else { ?>
        <div class="white-box">
            <div class="card card-outline-danger text-center text-dark">
                <div class="card-block">
                    <footer>
                        <cite title="Source Title"><b>Item Tidak Ada</b></cite>
                    </footer>
                </div>
            </div>
        </div>
    <?php } ?>
    <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">

</form>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script src="<?= base_url(); ?>assets/js/jquery.validate.min.js"></script>
<script>
    /*----------  LOAD SAAT DOKUMEN DIBUKA  ----------*/
    $(document).ready(function() {
        fixedtable($('#tabledatay'));
        $('.select2').select2();

        $(".toggler").click(function(e) {
            e.preventDefault();
            $('.' + $(this).attr('data-prod-cat')).toggle();
            // $(this).addClass('active');

            //Remove the icon class
            if ($(this).find('i').hasClass('fa-eye')) {
                //then change back to the original one
                $(this).find('i').removeClass('fa-eye').addClass($(this).data('icon-name'));
            } else {
                //Remove the cross from all other icons
                $('.faq-links').each(function() {
                    if ($(this).find('i').hasClass('fa-eye')) {
                        $(this).find('i').removeClass('fa-eye').addClass($(this).data('icon-name'));
                    }
                });

                $(this).find('i').addClass('fa-eye').removeClass($(this).data('icon-name'));
            }
        });
    });
</script>