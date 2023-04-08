<form id="cekinputan">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <i class="fa fa-lg fa-eye"></i> &nbsp; <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
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
                                <input type="text" readonly="" autocomplete="off" onkeyup="gede(this);" maxlength="15" class="form-control input-sm" value="<?= $data->e_bagian_name; ?>">
                            </div>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="text" name="i_document" required="" id="i_stb_sj" readonly="" autocomplete="off" onkeyup="gede(this);" maxlength="15" class="form-control input-sm" value="<?= $data->i_document; ?>">
                                    <input type="hidden" id="id" name="id" value="<?= $data->id; ?>">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <input type="text" name="d_document" required="" id="d_document" class="form-control input-sm date" value="<?= formatdmY($data->d_document); ?>" readonly>
                            </div>
                            <div class="col-sm-3">
                                <input type="hidden" name="id_company_tujuan" id="id_company_tujuan" value="<?= $data->id_company_tujuan; ?>">
                                <input type="text" class="form-control input-sm" value="<?= $data->name; ?>" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-md-3">Jenis Barang Keluar</label>
                            <label class="col-md-9">Keterangan</label>
                            <div class="col-md-3">
                                <input type="text" readonly="" class="form-control input-sm" value="<?= $data->e_jenis_name; ?>">
                            </div>
                            <div class="col-md-9">
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <textarea type="text" id="e_remark" name="e_remark" maxlength="250" class="form-control input-sm" readonly><?= $data->e_remark; ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;"><i class="fa fa-arrow-circle-left mr-2 fa-lg"></i>Kembali</button>
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
                <div class="col-sm-2">
                    <h3 class="box-title m-b-0 ml-1">Detail Material</h3>
                </div>
            </div>
            <div class="table-responsive">
                <table id="sitabledata" class="table middle color-table inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center" width="3%;">No</th>
                            <th>Kode</th>
                            <th>Nama Material</th>
                            <th>Bagian Panel</th>
                            <th>Kode Panel</th>
                            <th class="text-right">Qty<br>Penyusun</th>
                            <th class="text-right">Jml<br>Gelar</th>
                            <th class="text-right" hidden>STB Cutting<br>Hasil Baku</th>
                            <th class="text-right">Qty Panel<br>PCs</th>
                            <th class="text-right" hidden>Selisih<br>PCs</th>
                            <th class="text-right">Qty<br>Kirim</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <?php $i = 0; $group = '';
                        if ($data_detail->num_rows() > 0) {
                            foreach ($data_detail->result() as $key) {
                                $i++; 

                                if ($group != $key->id_product_wip.$key->id_material) {?>
                                <tr class="table-active">
                                    <td class="text-center"><i class="fa fa-check-square-o fa-lg text-success"></i></td>
                                    <td colspan="2">WIP : <?= $key->i_product_wip;?></td>
                                    <td colspan="5"><?= $key->e_product_wipname . ' - ' . $key->e_color_name ?></td>
                                    <td><input type="hidden" value="<?= $key->quantity_schedule ?>" class="form-control text-right input-sm" placeholder="" readonly></td>
                                    <td></td>
                                </tr>
                                <?php }
                                $group = $key->id_product_wip.$key->id_material;
                                ?>
                                <tr>
                                    <td class="text-center middle"><b><?= $i; ?></b></td>
                                    <td class="middle"><?= $key->i_material; ?></td>
                                    <td class="middle"><?= $key->e_material_name; ?></td>
                                    <td class="middle"><?= $key->bagian; ?></td>
                                    <td class="middle"><?= $key->i_panel; ?></td>
                                    <td class="middle text-right"><?= $key->n_quantity_penyusun; ?></td>
                                    <td class="middle text-right"><?= $key->n_jumlah_gelar; ?></td>
                                    <td class="middle text-right" hidden><?= number_format($key->n_quantity_stb_hasil, 2); ?></td>
                                    <td class="middle text-right"><?= $key->n_quantity_panel; ?></td>
                                    <td class="middle text-right" hidden><?= $key->n_quantity_selisih; ?></td>
                                    <td class="middle text-right"><?= $key->n_quantity; ?></td>
                                    <td><?= $key->e_remark; ?></td>
                                </tr>
                        <?php }
                        } ?>
                    </tbody>
                    <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                </table>
            </div>
        </div>
    </div>
</form>
<script>
    /*----------  LOAD SAAT DOKUMEN READY  ----------*/
    $(document).ready(function() {
        fixedtable($('#sitabledata'));
    });
</script>