<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <?= $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Kode Unit</label>
                        <label class="col-md-3">Nama Unit Kerja</label>
                        <label class="col-md-3">Lokasi</label>
                        <label class="col-md-3">Bagian</label>

                        <div class="col-sm-3">
                            <input type="text" readonly name="ikode" id="ikode" class="form-control input-sm" value="<?= $data->i_bagian; ?>">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" name="enama" id="enama" class="form-control input-sm" readonly value="<?= $data->e_bagian_name; ?>">
                        </div>
                        <div class="col-sm-3">
                            <input readonly type="text" class="form-control input-sm" value="<?= $isi->e_lokasi_name; ?>">
                        </div>
                        <div class="col-sm-3">
                            <input readonly type="text" name="jenis_bagian" id="jenis_bagian" class="form-control input-sm" value="<?= $isi->e_departement_name; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Sub Bagian</label>
                        <label class="col-md-3">Kategori Barang</label>
                        <label class="col-md-3">Internal/Eksternal</label>
                        <label class="col-md-3">Jenis Bagian</label>
                        <div class="col-sm-3">
                            <select name="ijenis" id="ijenis" required="" disabled class="form-control select2">
                                <option value="<?= $jenisgudang['i_type']; ?>"><?= $jenisgudang['e_type_name']; ?></option>
                            </select>
                            <input type="hidden" name="itype" id="itype">
                        </div>
                        <div class="col-sm-3">
                            <select name="ikelompok[]" id="ikelompok" disabled multiple="multiple" class="form-control select2">
                                <?php if ($detail) {
                                    foreach ($detail as $kuy) { ?>
                                        <option value="<?= $kuy->i_kode_kelompok; ?>" selected><?= $kuy->e_nama_kelompok; ?></option>
                                <?php }
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="inter_exter" id="inter_exter" disabled class="form-control input-sm select2">
                                <?php if ($jahit->num_rows() > 0) {
                                    foreach ($jahit->result() as $key) { ?>
                                        <option value="<?= $key->id; ?>"><?= $key->e_nama_kategori; ?></option>
                                <?php }
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" readonly name="jenis_bagian" id="jenis_bagian" class="form-control input-sm" maxlength="15" onkeyup="gede(this); clearname(this);" placeholder="Maksimal 15 Karakter" value="<?= $data->e_jenis_bagian; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder; ?>/cform','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $(".select2").select2();
    });
</script>