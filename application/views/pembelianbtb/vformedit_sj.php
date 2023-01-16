<?= $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/update_sj'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil mr-2"></i>  <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list mr-2"></i><?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-4">Bagian Pembuat</label>
                        <label class="col-md-4">Nomor BTB</label>
                        <label class="col-md-4">Tanggal BTB</label>
                        <!-- <label class="col-md-3">Gudang Penerima</label> -->
                        <div class="col-sm-4">
                            <input type="hidden" name="igudangold" name="igudangold" value="<?= $data->bagian_pembuat; ?>">
                            <select name="igudang" id="igudang" class="form-control select2" required="" disabled>
                                <?php if ($bagian) {
                                    foreach ($bagian as $row) : ?>
                                        <option value="<?= $row->i_bagian; ?>" <?php if ($data->bagian_pembuat == $row->i_bagian) { ?> selected <?php } ?>>
                                            <?= $row->e_bagian_name; ?>
                                        </option>
                                <?php endforeach;
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" value="<?= $data->id; ?>">
                                <input type="hidden" name="ibtbold" id="ibtbold" value="<?= $data->i_btb; ?>">
                                <input type="text" name="ibtb" id="ibtb" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number; ?>" maxlength="17" class="form-control input-sm" value="<?= $data->i_btb; ?>">
                            </div>
                            <span class="notekode">Format : (<?= $number; ?>)</span><br>
                            <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-4">
                            <input id="dbtb" name="dbtb" class="form-control input-sm" value="<?= $data->d_btb; ?>" readonly>
                        </div>
                        <div class="col-sm-3" hidden="true">
                            <input type="hidden" name="xbagian" id="xbagian" value="<?= $data->i_bagian; ?>">
                            <select class="form-control select2" id="ibagian" name="ibagian">
                                <option value="<?= $data->i_bagian; ?>"><?= $data->e_bagian_name; ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Supplier</label>
                        <label class="col-md-3">SJ Supplier</label>
                        <label class="col-md-2">Tanggal SJ</label>
                        <label class="col-md-2">Nomor OP</label>
                        <label class="col-md-2">Tanggal OP</label>
                        <div class="col-sm-3">
                            <input type="text" id="esupplier" name="esupplier" class="form-control input-sm" required="" value="<?= $data->e_supplier_name; ?>" readonly>
                            <input type="hidden" id="isupplier" name="isupplier" class="form-control input-sm" required="" value="<?= $data->i_supplier; ?>">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="isj" name="isj" class="form-control input-sm" onkeyup="gede(this);" required="" value="<?= $data->i_sj_supplier; ?>" maxlength="15">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="dsj" name="dsj" class="form-control input-sm date" required="" value="<?= $data->d_sj; ?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="iop" name="iop" class="form-control input-sm" required="" value="<?= $data->i_op; ?>" readonly>
                            <input type="hidden" id="idop" name="idop" class="form-control input-sm" required="" value="<?= $data->id_op; ?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="dop" name="dop" class="form-control input-sm" required="" value="<?= $data->d_op; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-8">Keterangan</label>
                        <label class="col-md-2">Nomor PP</label>
                        <label class="col-md-2">Tanggal PP</label>
                        <div class="col-sm-8">
                            <textarea class="form-control input-sm" readonly name="remark" placeholder="Isi keterangan jika ada!"><?= $data->e_remark; ?></textarea>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="ipp" name="ipp" class="form-control input-sm" required="" value="<?= $data->i_pp; ?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="dpp" name="dpp" class="form-control input-sm" required="" value="<?= $data->d_pp; ?>" readonly>
                        </div>
                    </div>
                    <!-- <div class="form-group row">
                        <label class="col-md-3">Nomor BTB</label>
                        <label class="col-md-2">Tanggal BTB</label>
                        <label class="col-md-3">Supplier</label> 
                        <label class="col-md-2">SJ Supplier</label>
                        <label class="col-md-2">Tanggal SJ</label> 
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" value="<?= $data->id; ?>">
                                <input type="hidden" name="ibtbold" id="ibtbold" value="<?= $data->i_btb; ?>">
                                <input type="text" name="ibtb" id="ibtb" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $data->i_btb; ?>" maxlength="17" class="form-control input-sm" value="<?= $data->i_btb; ?>" aria-label="Text input with dropdown button">
                                <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span>
                            </div>
                            <span class="notekode">Format : (<?= $data->i_btb; ?>)</span><br>
                            <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-2">
                            <input id="dbtb" name="dbtb" class="form-control input-sm date" value="<?= $data->d_btb; ?>" readonly>
                        </div>                        
                        <div class="col-sm-3">
                            <input type="text" id= "esupplier" name="esupplier" class="form-control input-sm" required="" value="<?= $data->e_supplier_name; ?>" readonly>
                            <input type="hidden" id= "isupplier" name="isupplier" class="form-control input-sm" required="" value="<?= $data->i_supplier; ?>">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id= "isj" name="isj" class="form-control input-sm" onkeyup="gede(this);" required="" value="<?= $data->i_sj_supplier; ?>" maxlength="15">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id= "dsj" name="dsj" class="form-control input-sm date" required="" value="<?= $data->d_sj; ?>" readonly>
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-2">Nomor OP</label> 
                        <label class="col-md-2">Tanggal OP</label> 
                        <label class="col-md-2">Nomor PP</label> 
                        <label class="col-md-2">Tanggal PP</label>
                        <label class="col-md-4">Gudang Penerima</label>        
                        <div class="col-sm-2">
                            <input type="text" id= "iop" name="iop" class="form-control input-sm" required="" value="<?= $data->i_op; ?>" readonly>
                            <input type="hidden" id= "idop" name="idop" class="form-control input-sm" required="" value="<?= $data->id_op; ?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id= "dop" name="dop" class="form-control input-sm" required="" value="<?= $data->d_op; ?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id= "ipp" name="ipp" class="form-control input-sm" required="" value="<?= $data->i_pp; ?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id= "dpp" name="dpp" class="form-control input-sm" required="" value="<?= $data->d_pp; ?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <input type="hidden" name="xbagian" id="xbagian" value="<?= $data->i_bagian; ?>">
                            <select class="form-control select2" id="ibagian" required="" name="ibagian" data-placeholder="Pilih Gudang">
                                <option value="<?= $data->i_bagian; ?>"><?= $data->e_bagian_name; ?></option>
                            </select>
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-sm-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea class="form-control input-sm" name="remark"><?= $data->e_remark; ?></textarea>
                        </div>
                    </div> -->
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <button type="submit" id="submit" class="btn btn-success btn-block btn-sm" onclick="return konfirm();"><i class="fa fa-save mr-2"></i>Update</button>
                        </div>
                        <div class="col-sm-6">
                            <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;"><i class="fa fa-arrow-circle-left mr-2"></i>Kembali</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<div class="white-box" id="detail">
    <div class="col-sm-5">
        <h3 class="box-title m-b-0">Detail Barang</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 3%;">No</th>
                        <!-- <th class="text-center" style="width: 12%;">Gudang</th> -->
                        <th style="width: 10%;">Kode</th>
                        <th style="width: 25%;">Nama Barang</th>
                        <!-- <th class="text-center" style="width: 10%;">Jml Eks</th>
                        <th class="text-center" style="width: 10%;">Sat Eks</th> -->
                        <th class="text-right" style="width: 10%;">Jml OP</th>
                        <th class="text-right" style="width: 10%;">Masuk</th>
                        <th class="text-right" style="width: 10%;">Toleransi</th>
                        <th style="width: 10%;">Satuan</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $i = 0;
                    if ($detail) {
                        foreach ($detail as $row) {
                            $i++;?>
                            <tr>
                                <td class="text-center"><spanx id="snum<?=$i;?>"><?= $i;?></spanx></td>
                                <td><?= $row->i_material;?></td>
                                <td><?= htmlentities($row->e_material_name);?></td>
                                <td hidden="true">
                                    <input type="text" id="nquantityeks<?=$i;?>" class="form-control text-right input-sm" autocomplete="off" name="nquantityeks[]" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="<?= $row->n_quantity_eks;?>" onkeyup="angkahungkul(this);">
                                </td>
                                <td hidden="true">
                                    <select id="isatuaneks<?=$i;?>" name="isatuaneks[]" class="form-control select2" data-placeholder="Pilih Satuan Sup">
                                        <option value="">Pilih Satuan</option>
                                        <?php if ($satuan) {
                                            foreach ($satuan as $key) {?>
                                                <option value="<?= $key->i_satuan_code;?>" <?php if ($key->i_satuan_code==$row->i_satuan_code_eks) {?> selected <?php } ?>><?= $key->e_satuan_name;?></option>
                                            <?php }
                                        }?>
                                    </select>
                                </td>

                                <td class="text-right"><?= $row->qty_op;?></td>
                                <td class="text-right"><?= $row->n_quantity;?></td>
                                <td class="text-right"><?= $row->toleransi;?></td>
                                <td><?= $row->e_satuan_name;?></td>
                                <td><?= $row->e_remark;?></td>
                            </tr>
                    <?php } 
                }?>
                <input type="hidden" name="jml" id="jml" value ="<?= $i;?>">
                </tbody>
            </table>
        </div>
    </div>
</div>
</form>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
    $(document).ready(function() {
        $('#ibtb').mask('SSS-0000-000000S');
        $('.select2').select2({
            width: '100%',
        });
        fixedtable($('.table'));
        // showCalendar('.date');
        $('#dsj').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: "dd-mm-yyyy",
            todayBtn: "linked",
            daysOfWeekDisabled: [0],
            startDate: document.getElementById('dop').value,
        });
    });
</script>