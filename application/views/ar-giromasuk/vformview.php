<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye fa-lg mr-2"></i> &nbsp;
                <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp;
                    <?= $title_list; ?>
                </a>
            </div>
            <div class="panel-body">
                <div id="pesan"></div>
                <div class="col-md-12">
                <div class="form-group row">
                        <label class="col-md-3">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-2">Tanggal Jatuh Tempo</label>
                        <label class="col-md-2">Tanggal Terima</label>
                        <div class="col-sm-3">
                            <!-- <select name="i_bagian" id="i_bagian" onchange="number();" class="form-control select2" readonly>
                                <?php if ($bagian) {
                                    foreach ($bagian as $row): ?>
                                        <option value="<?= $row->i_bagian; ?>">
                                            <?= $row->e_bagian_name; ?>
                                        </option>
                                    <?php endforeach;
                                } ?>
                            </select> -->
                            <input type="text" value="<?= $data->e_bagian_name ?>" class="form-control input-sm" readonly>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" name="i_giro_id" id="i_giro_id" autocomplete="off"
                                    maxlength="17" class="form-control input-sm" value="<?= $data->i_giro_id ?>"
                                    aria-label="Text input with dropdown button" readonly>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="d_giro" name="d_giro" class="form-control input-sm" required=""
                                readonly onchange="number();" value="<?php echo date("d-m-Y"); ?>">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="d_giro_duedate" name="d_giro_duedate" class="form-control input-sm" required=""
                                readonly onchange="number();" value="<?php echo date("d-m-Y"); ?>">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="d_giro_terima" name="d_giro_terima" class="form-control input-sm" required=""
                                readonly onchange="number();" value="<?php echo date("d-m-Y"); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Area</label>
                        <label class="col-md-3">Nama Customer</label>
                        <!-- <label class="col-md-3">Nama Sales</label> -->
                        <label class="col-md-3">No. Daftar Tagihan</label>
                        <label class="col-md-3">Bank</label>
                        <div class="col-sm-3">
                            <!-- <select name="i_area" id="i_area" class="form-control select2" onchange="number();" readonly>
                                <?php if ($area) {
                                    foreach ($area as $row): ?>
                                        <option value="<?= $row->id; ?>"><?="[" . $row->i_area . "] - " . $row->e_area; ?>
                                        </option>
                                    <?php endforeach;
                                } ?>
                            </select> -->
                            <input type="text" value="<?= $data->e_area ?>" class="form-control input-sm" readonly>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" value="<?= $data->e_customer_name ?>" class="form-control input-sm" readonly>
                        </div>
                        <!-- <div class="col-sm-3">
                            <select name="i_salesman" id="i_salesman" class="form-control select2">
                                <option value=""></option>
                            </select>
                        </div> -->
                        <div class="col-sm-3">
                            <input type="text" value="<?= $data->i_dt_id ?>" class="form-control input-sm" readonly>
                        </div>
                        <div class="col-sm-3">
                            <!-- <select name="i_bank" id="i_bank" class="form-control select2" onchange="number();">
                                <?php if ($bank) {
                                    foreach ($bank as $row): ?>
                                        <option value="<?= $row->id; ?>"><?="[" . $row->i_bank . "] - " . $row->e_bank_name; ?>
                                        </option>
                                    <?php endforeach;
                                } ?>
                            </select> -->
                            <input type="text" name="e_giro_bank" id="e_giro_bank" autocomplete="off" placeholder="Bank" class="form-control input-sm" value="<?= $data->e_giro_bank ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Jumlah</label>
                        <!-- <label class="col-md-3">Atas Nama</label> -->
                        <label class="col-md-8">Keterangan</label>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" name="v_jumlah" id="v_jumlah" autocomplete="off"
                                    class="form-control input-sm" placeholder="Input Jumlah Transfer" required value="<?= number_format($data->v_jumlah) ?>"
                                    onkeyup="angkahungkul(this); reformat(this);" readonly>
                            </div>
                        </div>
                        <!-- <div class="col-sm-3">
                            <input type="text" id="e_send_name" name="e_send_name" class="form-control input-sm"
                                placeholder="Nama Pengirim" maxlength="150">
                        </div> -->
                        <div class="col-sm-8">
                            <textarea class="form-control input-sm" name="e_giro_description" placeholder="Note ..." readonly><?= $data->e_giro_description ?></textarea>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-inverse btn-block btn-sm"
                                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;"><i
                                    class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</form>