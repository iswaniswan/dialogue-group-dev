<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-check fa-lg mr-2"></i> &nbsp;
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
                        <label class="col-md-3">Tanggal Dokumen</label>
                        <label class="col-md-3">Area</label>
                        <div class="col-sm-3">
                            <input type="text" name="e_bagian_name" id="e_bagian_name" readonly autocomplete="off"
                                maxlength="17" class="form-control input-sm" value="<?= $data->e_bagian_name; ?>"
                                aria-label="Text input with dropdown button">
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" readonly value="<?= $data->i_kum; ?>">
                                <input type="text" name="i_kum_id" id="i_kum_id" readonly autocomplete="off"
                                    maxlength="17" class="form-control input-sm" value="<?= $data->i_kum_id; ?>"
                                    aria-label="Text input with dropdown button">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="d_kum" name="d_kum" class="form-control input-sm date" required=""
                                readonly onchange="number();" value="<?= format_indo($data->d_kum); ?>">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" name="i_area" id="i_area" readonly autocomplete="off" maxlength="17"
                                class="form-control input-sm" value="<?= $data->e_area; ?>"
                                aria-label="Text input with dropdown button">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Nama Customer</label>
                        <label class="col-md-3">Nama Sales</label>
                        <label class="col-md-3">No. Daftar Tagihan</label>
                        <label class="col-md-3">Bank</label>
                        <div class="col-sm-3">
                            <input type="text" name="i_customer" id="i_customer" readonly class="form-control input-sm"
                                value="<?='[' . $data->i_customer_code . '] - ' . $data->e_customer_name; ?>">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" name="i_salesman" id="i_salesman" readonly class="form-control input-sm"
                                value="<?='[' . $data->i_sales_code . '] - ' . $data->e_sales; ?>">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" name="i_dt" id="i_dt" readonly class="form-control input-sm"
                                value="<?= $data->i_dt_id . ' - ' . $data->d_dt; ?>">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" name="i_dt" id="i_dt" readonly class="form-control input-sm"
                                value="<?= $data->i_bank_code . ' - ' . $data->e_bank_name; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Jumlah</label>
                        <label class="col-md-3">Atas Nama</label>
                        <label class="col-md-6">Keterangan</label>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" name="v_jumlah" id="v_jumlah" autocomplete="off"
                                    class="form-control input-sm" readonly placeholder="Input Jumlah Transfer" required
                                    value="<?= number_format($data->v_jumlah); ?>"
                                    onkeyup="angkahungkul(this); reformat(this);">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="e_send_name" name="e_send_name" class="form-control input-sm"
                                placeholder="Nama Pengirim" readonly maxlength="150" value="<?= $data->e_send_name; ?>">
                        </div>
                        <div class="col-sm-6">
                            <textarea class="form-control input-sm" name="e_remark"
                                readonly><?= $data->e_remark; ?></textarea>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <button type="button" class="btn btn-inverse btn-block btn-sm"
                                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main')"> <i
                                    class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                        </div>
                        <div class="col-sm-3">
                            <button type="button" class="btn btn-warning btn-block btn-sm"
                                onclick="statuschange('<?= $folder . "','" . $id; ?>','1','<?= $dfrom . "','" . $dto; ?>');">
                                <i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;Change Requested</button>&nbsp;
                        </div>
                        <div class="col-sm-3">
                            <button type="button" class="btn btn-danger btn-block btn-sm"
                                onclick="statuschange('<?= $folder . "','" . $id; ?>','4','<?= $dfrom . "','" . $dto; ?>');">
                                <i class="fa fa-times"></i>&nbsp;&nbsp;Reject</button>&nbsp;
                        </div>
                        <div class="col-sm-3">
                            <button type="button" class="btn btn-success btn-block btn-sm"
                                onclick="statuschange('<?= $folder . "','" . $id; ?>','6','<?= $dfrom . "','" . $dto; ?>');">
                                <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Approve</button>&nbsp;
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</form>