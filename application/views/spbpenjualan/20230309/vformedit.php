<style type="text/css">
    .pudding {
        padding-left: 3px;
        padding-right: 3px;
    }
    .toggle-button-cover
    {
        display: table-cell;
        position: relative;
        width: 125px;
        box-sizing: border-box;
    }

    .button-cover
    {
        height: 30px;
        margin: 2px;
        background-color: #fff;
        box-shadow: 0 10px 20px -8px #c5d6d6;
        border-radius: 4px;
    }

    .button-cover:before
    {
        counter-increment: button-counter;
        content: counter(button-counter);
        position: absolute;
        right: 0;
        bottom: 0;
        color: #d7e3e3;
        font-size: 12px;
        line-height: 1;
        padding: 5px;
    }

    .button-cover, .knobs, .layer
    {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
    }

    .button
    {
        position: relative;
        top: 50%;
        width: 120px;
        height: 36px;
        margin: -20px auto 0 auto;
        overflow: hidden;
    }

    .button.r, .button.r .layer
    {
        border-radius: 100px;
    }

    .button.b2
    {
        border-radius: 2px;
    }

    .checkbox
    {
        position: relative;
        width: 100%;
        height: 100%;
        padding: 0;
        margin: 0;
        opacity: 0;
        cursor: pointer;
        z-index: 3;
    }

    .knobs
    {
        z-index: 2;
    }

    .layer
    {
        width: 100%;
        background-color: #ebf7fc;
        transition: 0.3s ease all;
        z-index: 1;
    }

    /* Button 10 */
    #button-10 .knobs:before, #button-10 .knobs:after, #button-10 .knobs span
    {
        position: absolute;
        width: 54px;
        height: 50px;
        font-size: 10px;
        font-weight: bold;
        text-align: center;
        line-height: 1;
        padding: 9px 0px 9px 0px;
        border-radius: 2px;
        transition: 0.3s ease all;
    }

    #button-10 .knobs:before
    {
        content: '';
        left: 0px;
        background-color: #03A9F4;
    }

    #button-10 .knobs:after
    {
        content: 'Stok Daerah';
        right: 1px;
        color: #4e4e4e;
    }

    #button-10 .knobs span
    {
        display: inline-block;
        left: 0px;
        color: #fff;
        z-index: 1;
    }

    #button-10 .checkbox:checked + .knobs span
    {
        color: #4e4e4e;
    }

    #button-10 .checkbox:checked + .knobs:before
    {
        left: 65px;
        background-color: #F44336;
    }

    #button-10 .checkbox:checked + .knobs:after
    {
        color: #fff;
    }

    #button-10 .checkbox:checked ~ .layer
    {
        background-color: #fcebeb;
    }
</style>
<form>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
                </div>
                <div class="panel-body table-responsive">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-3">Bagian Pembuat</label>
                            <label class="col-md-3">Nomor Dokumen</label>
                            <label class="col-md-2">Tanggal Dokumen</label>
                            <label class="col-md-4">Area</label>
                            <div class="col-sm-3">
                                <select name="ibagian" id="ibagian" class="form-control select2" required="">
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
                                    <input type="hidden" name="id" id="id" value="<?= $data->id; ?>">
                                    <input type="hidden" name="idocumentold" id="i_spbold" value="<?= $data->i_document; ?>">
                                    <input type="text" name="idocument" id="i_spb" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number; ?>" maxlength="25" class="form-control input-sm" value="<?= $data->i_document; ?>" aria-label="Text input with dropdown button">
                                    <span class="input-group-addon">
                                        <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                    </span>
                                </div>
                                <span class="notekode">Format : (<?= $number; ?>)</span><br>
                                <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                            </div>
                            <div class="col-sm-2">
                                <input type="text" id="ddocument" name="ddocument" class="form-control input-sm date" required="" readonly value="<?= $data->d_document; ?>">
                            </div>
                            <div class="col-sm-4">
                                <select name="iarea" id="iarea" class="form-control select2" required="">
                                    <?php if ($area) {
                                        foreach ($area as $row) : ?>
                                            <option value="<?= $row->id; ?>" <?php if ($row->id == $data->id_area) { ?> selected <?php } ?>>
                                                <?= $row->e_area . ' (' . $row->i_area . ')'; ?>
                                            </option>
                                    <?php endforeach;
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3">Customer</label>
                            <label class="col-md-2">Kelompok Harga</label>
                            <label class="col-md-2">Salesman</label>
                            <label class="col-md-2">Penentuan Stok</label>
                            <label class="col-md-3">Nomor Referensi</label>

                            <div class="col-sm-3">
                                <select name="icustomer" id="icustomer" class="form-control select2" required="">
                                    <option value="<?= $data->id_customer; ?>"><?= $data->e_customer_name . ' (' . $data->i_customer . ')'; ?></option>
                                </select>
                                <input type="hidden" id="ecustomer" name="ecustomer" class="form-control" readonly value="<?= $data->e_customer_name; ?>">
                                <input type="hidden" id="ndiskon1" name="ndiskon1" class="form-control" readonly value="<?= $data->v_customer_discount; ?>">
                                <input type="hidden" id="ndiskon2" name="ndiskon2" class="form-control" readonly value="<?= $data->v_customer_discount2; ?>">
                                <input type="hidden" id="ndiskon3" name="ndiskon3" class="form-control" readonly value="<?= $data->v_customer_discount3; ?>">
                            </div>
                            <div class="col-sm-2">
                                <input type="hidden" id="idkodeharga" name="idkodeharga" class="form-control input-sm" value="<?= $data->id_harga_kode; ?>">
                                <input type="text" readonly="" id="ekodeharga" name="ekodeharga" class="form-control input-sm" placeholder="Harga Per Pelanggan" value="<?= $data->i_harga . ' - ' . $data->e_harga; ?>">
                            </div>
                            <div class="col-sm-2">
                                <select name="isales" id="isales" class="form-control select2" required="">
                                    <?php if ($salesman) {
                                        foreach ($salesman as $row) : ?>
                                            <option value="<?= $row->id; ?>" <?php if ($row->id == $data->id_salesman) { ?> selected <?php } ?>>
                                                <?= $row->e_sales . ' (' . $row->i_sales . ')'; ?>
                                            </option>
                                    <?php endforeach;
                                    } ?>
                                </select>
                            </div>

                            <div class="col-sm-2">
                                <div class="toggle-button-cover">
                                  <div class="button-cover">
                                    <div class="button b2" id="button-10">
                                      <input type="checkbox" class="checkbox" name="f_spb_stockdaerah" <?= ($data->f_spb_stockdaerah == 't') ? "checked" : "";?> >
                                      <div class="knobs">
                                        <span>Stok Pusat</span>
                                      </div>
                                      <div class="layer"></div>
                                    </div>
                                  </div>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <input type="text" id="ireferensi" name="ireferensi" class="form-control input-sm" onkeyup="gede(this);" maxlength="20" placeholder="No Referensi Pelanggan" value="<?= $data->i_referensi; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3">Jenis Barang</label>
                            <label class="col-md-9">Keterangan</label>
                            <div class="col-sm-3">
                                <select name="id_jenis_barang_keluar" id="id_jenis_barang_keluar" class="form-control select2" onchange="clear_table();">
                                    <?php if ($jenisbarang->num_rows() > 0) {
                                        foreach ($jenisbarang->result() as $row) : ?>
                                            <option value="<?= $row->id; ?>" <?php if ($data->id_jenis_barang_keluar == $row->id) { ?> selected <?php } ?>>
                                                <?= $row->e_jenis_name; ?>
                                            </option>
                                    <?php endforeach;
                                    } ?>
                                </select>
                            </div>
                            <div class="col-sm-9">
                                <textarea id="eremarkh" name="eremarkh" class="form-control" placeholder="Isi keterangan jika ada!"><?= $data->e_remark; ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <?php if ($data->i_status == '1' || $data->i_status == '3' || $data->i_status == '7') { ?>
                                <div class="col-sm-3">
                                    <button type="button" id="submit" class="btn btn-success btn-block btn-sm"><i class="fa fa-save mr-2"></i>Update</button>
                                </div>
                                <div class="col-sm-3">
                                    <button type="button" id="send" class="btn btn-primary btn-block btn-sm"><i class="fa fa-paper-plane-o mr-2"></i>Send</button>
                                </div>
                                <div class="col-sm-3">
                                    <button type="button" id="hapus" class="btn btn-danger btn-block btn-sm"><i class="fa fa-trash mr-2"></i>Delete</button>
                                </div>
                                <div class="col-sm-3">
                                    <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;"><i class="fa fa-arrow-circle-left mr-2"></i>Kembali</button>
                                </div>
                            <?php } elseif ($data->i_status == '2') { ?>
                                <div class="col-sm-6">
                                    <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;"><i class="fa fa-arrow-circle-left mr-2"></i>Kembali</button>
                                </div>
                                <div class="col-sm-6">
                                    <button type="button" id="cancel" class="btn btn-primary btn-block btn-sm"><i class="fa fa-refresh mr-2"></i>Cancel</button>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <!-- <div class="form-group"> -->
                        <span class="notekode"><b>Note : </b></span><br>
                        <span class="notekode">* Harga barang jadi yang digunakan adalah harga exclude.</span><br>
                        <span class="notekode">* Harga sesuai dengan yang di master harga jual barang jadi dan sesuai kelompok barang distributornya!</span><br>
                        <span class="notekode">* Tanggal Berlaku master harga jual barang jadi sesuai tanggal dokumen.</span><br>
                        <span class="notekode">* Area bisa disesuaikan dengan pelanggan yang ada areanya!</span>
                        <!-- </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $i = 0;
    if ($datadetail) { ?>
        <div class="white-box" id="detail">
            <div class="col-sm-6">
                <h3 class="box-title m-b-0">Detail Barang</h3>
                <div class="m-b-0">
                    <div class="form-group row">
                        <label class="col-md-5">Kategori Barang</label>
                        <label class="col-md-6">Jenis Barang</label>
                        <label class="col-md-1"></label>
                        <div class="col-sm-5">
                            <select class="form-control select2" name="ikategori" id="ikategori">
                                <option value="all">Semua Kategori</option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <select class="form-control select2" name="ijenis" id="ijenis">
                                <option value="all">Semua Jenis</option>
                            </select>
                        </div>
                        <div class="col-sm-1">
                            <button type="button" id="addrow" class="btn btn-info btn-sm"><i class="fa fa-plus mr-2"></i>Item</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table id="tabledatay" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                        <thead>
                            <tr>
                                <th class="text-center" width="3%">No</th>
                                <!-- <th class="text-center" width="30%;">Barang</th> -->
                                <th class="text-center">Qty</th>
                                <th class="text-center">Harga</th>
                                <th class="text-center">Disc 123 (%)</th>
                                <th class="text-center">Disc (Rp.)</th>
                                <th class="text-center" width="12%">Total</th>
                                <th class="text-center">Keterangan</th>
                                <th class="text-center" width="3%">Act</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($datadetail->num_rows() > 0) {
                                foreach ($datadetail->result() as $key) {
                                    $i++;
                                    $total = $key->v_price * $key->n_quantity; ?>
                                    <tr class="tr<?= $i; ?>">
                                        <td rowspan="2" class="text-center no">
                                            <spanx id="snum<?= $i; ?>"><?= $i; ?></spanx>
                                        </td>
                                        <td colspan="6">
                                            <select data-nourut="<?= $i; ?>" id="idproduct<?= $i; ?>" class="form-control input-sm" name="idproduct<?= $i; ?>" onchange="getproduct(<?= $i; ?>);">
                                                <option value="<?= $key->id_product; ?>"><?= $key->i_product_base . ' - ' . $key->e_product_basename. ' - ' . $key->e_color_name; ?></option>
                                            </select>
                                        </td>

                                        <td rowspan="2" class="text-center"><button data-delete="<?= $i; ?>" type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>
                                    </tr>
                                    <tr class="tr<?= $i; ?>">
                                        <td><input type="text" id="nquantity<?= $i; ?>" class="form-control text-right input-sm inputitem" autocomplete="off" name="nquantity<?= $i; ?>" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="<?= $key->n_quantity; ?>" onkeyup="angkahungkul(this); hitungtotal();"> <input type="hidden" readonly class="form-control input-sm text-right" name="fc<?= $i; ?>" id="fc<?= $i; ?>" value="<?= $key->fc; ?>" /></td>
                                        <td><input type="text" class="form-control input-sm text-right" name="vharga<?= $i; ?>" id="vharga<?= $i; ?>" value="<?= number_format($key->v_price); ?>" onblur='if(this.value==""){this.value="0";}' onfocus='if(this.value=="0"){this.value="";}' onkeyup="angkahungkul(this);" /></td>
                                        <td>
                                            <div class="row">
                                                <div class="col-sm-4 pudding">
                                                    <input type="text" class="form-control input-sm text-right" placeholder="%1" name="ndisc1<?= $i; ?>" id="ndisc1<?= $i; ?>" value="<?= $key->n_diskon1; ?>" />
                                                    <input type="hidden" readonly class="form-control input-sm text-right" name="vdisc1<?= $i; ?>" id="vdisc1<?= $i; ?>" value="<?= $key->v_diskon1; ?>" />
                                                </div>
                                                <div class="col-sm-4 pudding">
                                                    <input type="text" class="form-control input-sm text-right" placeholder="%2" name="ndisc2<?= $i; ?>" id="ndisc2<?= $i; ?>" value="<?= $key->n_diskon2; ?>" />
                                                    <input type="hidden" readonly class="form-control input-sm text-right" name="vdisc2<?= $i; ?>" id="vdisc2<?= $i; ?>" value="<?= $key->v_diskon2; ?>" />
                                                </div>
                                                <div class="col-sm-4 pudding">
                                                    <input type="text" class="form-control input-sm text-right" placeholder="%3" name="ndisc3<?= $i; ?>" id="ndisc3<?= $i; ?>" value="<?= $key->n_diskon3; ?>" />
                                                    <input type="hidden" readonly class="form-control input-sm text-right" name="vdisc3<?= $i; ?>" id="vdisc3<?= $i; ?>" value="<?= $key->v_diskon3; ?>" />
                                                </div>
                                            </div>
                                        </td>
                                        <td><input type="text" class="form-control input-sm text-right" name="vdiscount<?= $i; ?>" id="vdiscount<?= $i; ?>" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="<?= number_format($key->v_diskon_tambahan); ?>" onkeyup="angkahungkul(this); hitungtotal(); reformat(this);" /></td>
                                        <td><input type="text" readonly class="form-control input-sm text-right" name="vtotal<?= $i; ?>" id="vtotal<?= $i; ?>" value="<?= number_format($total); ?>" /><input type="hidden" readonly class="form-control input-sm text-right" name="vtotaldiskon<?= $i; ?>" id="vtotaldiskon<?= $i; ?>" value="<?= $key->v_diskon_total; ?>" /></td>
                                        <td><input type="text" class="form-control input-sm" name="eremark<?= $i; ?>" id="eremark<?= $i; ?>" placeholder="Jika Ada!" value="<?= $key->e_remark; ?>"></td>
                                    </tr>
                            <?php }
                            } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td class="text-right" colspan="5">Total :</td>
                                <td><input type="text" id="nkotor" name="nkotor" class="form-control input-sm text-right" value="<?= number_format($data->v_kotor); ?>" readonly></td>
                                <td colspan="2"></td>
                            </tr>
                            <tr>
                                <td class="text-right" colspan="5">Diskon :</td>
                                <td><input type="text" id="ndiskontotal" name="ndiskontotal" class="form-control input-sm text-right" readonly value="<?= number_format($data->v_diskon); ?>"></td>
                                <td colspan="2"></td>
                            </tr>
                            <tr>
                                <td class="text-right" colspan="5">DPP :</td>
                                <td><input type="text" id="vdpp" name="vdpp" class="form-control input-sm text-right" value="<?= number_format($data->v_dpp); ?>" readonly></td>
                                <td colspan="2"></td>
                            </tr>
                            <tr>
                                <td class="text-right" colspan="5">PPN (<span id="xppn"><?= number_format($data->n_ppn); ?></span>%) :</td>
                                <td>
                                    <input type="text" id="vppn" name="vppn" class="form-control input-sm text-right" value="<?= number_format($data->v_ppn); ?>" readonly>
                                    <input type="hidden" id="nppn" name="nppn" class="form-control input-sm text-right" value="<?= number_format($data->n_ppn); ?>" readonly>
                                </td>
                                <td colspan="2"></td>
                            </tr>
                            <tr>
                                <td class="text-right" colspan="5">Grand Total :</td>
                                <td><input type="text" id="nbersih" name="nbersih" class="form-control input-sm text-right" value="<?= number_format($data->v_bersih); ?>" readonly></td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
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
<script>
    /*----------  LOAD SAAT DOKUMEN DIBUKA  ----------*/
    $(document).ready(function() {
        // $('#i_spb').mask('SSS-0000-000000S');
        $('.select2').select2();

        /*----------  Tanggal tidak boleh kurang dari hari ini!  ----------*/
        showCalendar('.date', 0);

        /*----------  Ganti Barang Pas Edit  ----------*/
        for (var i = 1; i <= $('#jml').val(); i++) {
            $('#idproduct' + i).select2({
                placeholder: 'Cari Kode / Nama Barang',
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
                            ikategori: $('#ikategori').val(),
                            ijenis: $('#ijenis').val(),
                            ibagian: $('#ibagian').val(),
                            idharga: $('#idkodeharga').val(),
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
        }

        /*----------  GANTI AREA ----------*/
        $('#iarea').change(function(event) {
            $('#ekodeharga').val('');
            $('#icustomer').html('');
            $('#icustomer').val('');
        });

        /*----------  Cari Pelanggan  ----------*/
        $('#icustomer').select2({
            placeholder: 'Pilih Pelanggan',
            width: '100%',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder . '/cform/customer'); ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    var query = {
                        q: params.term,
                        'iarea': $('#iarea').val()
                    }
                    return query;
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                cache: false
            }
        }).change(function(event) {
            $("#addrow").attr('hidden', false);
            $("#tabledatay > tbody").remove();
            $("#jml").val(0);
            hitungtotal();
            $.ajax({
                type: "post",
                data: {
                    'idcustomer': $(this).val()
                },
                url: '<?= base_url($folder . '/cform/getdetailcustomer'); ?>',
                dataType: "json",
                success: function(data) {
                    $('#ndiskon1').val(data[0].v_customer_discount);
                    $('#ndiskon2').val(data[0].v_customer_discount2);
                    $('#ndiskon3').val(data[0].v_customer_discount3);
                    $('#ekodeharga').val(data[0].e_harga_kode);
                    $('#idkodeharga').val(data[0].id_harga_kode);
                    $('#ecustomer').val(data[0].e_customer_name);
                },
                error: function() {
                    swal('Error :)');
                }
            });
            /*$("#iarea").select2("val", "1");*/
        });

        /*----------  Cari Kategori Barang Sesuai Bagiannya  ----------*/
        $('#ikategori').select2({
            placeholder: 'Pilih Kategori',
            width: '100%',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder . '/cform/kelompok'); ?>',
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
                        results: data,
                    };
                },
                cache: false
            }
        }).change(function(event) {
            $('#ijenis').val('');
            $('#ijenis').html('');
        });

        /*----------  Cari Jenis Barang Sesuai Bagian dan Kategorinya  ----------*/
        $('#ijenis').select2({
            placeholder: 'Pilih Jenis',
            width: '100%',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder . '/cform/jenis'); ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    var query = {
                        q: params.term,
                        ikategori: $('#ikategori').val(),
                        ibagian: $('#ibagian').val(),
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
        });
    });

    /*----------  RUBAH NO DOKUMEN (GANTI TANGGAL & BAGIAN)  ----------*/
    $('#ibagian, #ddocument').change(function(event) {
        number();
        set_ppn();
    });

    /*----------  RUNNING NUMBER DOKUMEN  ----------*/
    function number() {
        if (($('#ibagian').val() == $('#ibagianold').val())) {
            $('#i_spb').val($('#i_spbold').val());
        } else {
            $.ajax({
                type: "post",
                data: {
                    'tgl': $('#ddocument').val(),
                    'ibagian': $('#ibagian').val(),
                },
                url: '<?= base_url($folder . '/cform/number'); ?>',
                dataType: "json",
                success: function(data) {
                    $('#i_spb').val(data);
                },
                error: function() {
                    swal('Error :)');
                }
            });
        }
    }

    /*----------  RUNNING PPN  ----------*/
    function set_ppn() {
        $.ajax({
            type: "post",
            data: {
                'tgl': $('#ddocument').val(),
            },
            url: '<?= base_url($folder . '/cform/get_ppn'); ?>',
            dataType: "json",
            success: function(data) {
                $('#nppn').val(data);
                $('#xppn').text(data);
                hitungtotal();
            },
            error: function() {
                swal('Error :)');
            }
        });
    }

    /*----------  UPDATE STATUS DOKUMEN  ----------*/
    $('#send').click(function(event) {
        statuschange('<?= $folder; ?>', $('#id').val(), '2', '<?= $dfrom . "','" . $dto; ?>');
    });

    $('#cancel').click(function(event) {
        statuschange('<?= $folder; ?>', $('#id').val(), '1', '<?= $dfrom . "','" . $dto; ?>');
    });

    $('#hapus').click(function(event) {
        statuschange('<?= $folder; ?>', $('#id').val(), '5', '<?= $dfrom . "','" . $dto; ?>');
    });

    /*----------  CEKLIS NO DOKUMEN (MANUAL)  ----------*/
    $('#ceklis').click(function(event) {
        if ($('#ceklis').is(':checked')) {
            $("#i_spb").attr("readonly", false);
        } else {
            $("#i_spb").attr("readonly", true);
            $("#ada").attr("hidden", true);
            number();
        }
    });

    /*----------  CEK NO DOKUMEN  ----------*/
    $("#i_spb").keyup(function() {
        $.ajax({
            type: "post",
            data: {
                'kode': $(this).val(),
                'ibagian': $('#ibagian').val(),
            },
            url: '<?= base_url($folder . '/cform/cekkode'); ?>',
            dataType: "json",
            success: function(data) {
                if (data == 1 && ($('#i_spb').val() != $('#i_spbold').val())) {
                    $("#ada").attr("hidden", false);
                    $("#submit").attr("disabled", true);
                } else {
                    $("#ada").attr("hidden", true);
                    $("#submit").attr("disabled", false);
                }
            },
            error: function() {
                swal('Error :)');
            }
        });
    });

    /*----------  TAMBAH ITEM SPBD  ----------*/
    var i = $('#jml').val();
    $("#addrow").on("click", function() {
        i++;
        $("#jml").val(i);
        var no = $('#tabledatay tbody tr .no').length;
        var newRow = $("<tr class='tr" + i + "'>");
        var newRiw = $("<tr class='tr" + i + "'>");
        var closeRow = $("</tr>");
        var cols = "";
        cols += `<td rowspan="2" class="text-center no"><spanx id="snum${i}">${no+1}</spanx></td>`;
        cols += `<td colspan="6"><select data-nourut="${i}" id="idproduct${i}" class="form-control input-sm" name="idproduct${i}" onchange="getproduct(${i});"></select></td>`;
        /* cols += `<td><input type="text" id="nquantity${i}" class="form-control text-right input-sm inputitem" autocomplete="off" name="nquantity${i}" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this); hitungtotal();"></td>`;
        cols += `<td><input type="text" readonly class="form-control input-sm text-right" name="vharga${i}" id="vharga${i}" value="0"/></td>`;
        cols += `<td>
                    <div class="row">
                        <div class="col-sm-4 pudding">
                            <input type="text" readonly class="form-control input-sm text-right" placeholder="%1" name="ndisc1${i}" id="ndisc1${i}"/>
                            <input type="hidden" readonly class="form-control input-sm text-right" name="vdisc1${i}" id="vdisc1${i}"/>
                        </div>
                        <div class="col-sm-4 pudding">
                            <input type="text" readonly class="form-control input-sm text-right" placeholder="%2" name="ndisc2${i}" id="ndisc2${i}"/>
                            <input type="hidden" readonly class="form-control input-sm text-right" name="vdisc2${i}" id="vdisc2${i}"/>
                        </div>
                        <div class="col-sm-4 pudding">
                            <input type="text" readonly class="form-control input-sm text-right" placeholder="%3" name="ndisc3${i}" id="ndisc3${i}"/>
                            <input type="hidden" readonly class="form-control input-sm text-right" name="vdisc3${i}" id="vdisc3${i}"/>
                        </div>
                    </div>
                </td>`;
        cols += `<td><input type="text" class="form-control input-sm text-right" name="vdiscount${i}" id="vdiscount${i}" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this); hitungtotal(); reformat(this);"/></td>`;
        cols += `<td><input type="text" readonly class="form-control input-sm text-right" name="vtotal${i}" id="vtotal${i}" value="0"/><input type="hidden" readonly class="form-control input-sm text-right" name="vtotaldiskon${i}" id="vtotaldiskon${i}" value="0"/></td>`;
        cols += `<td><input type="text" class="form-control input-sm" name="eremark${i}" id="eremark${i}" placeholder="Jika Ada!"/></td>`;*/
        cols += `<td rowspan="2" class="text-center"><button data-delete="${i}" type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>`;
        cols += `</tr>`;
        newRow.append(cols);
        $("#tabledatay").append(newRow);
        $("#tabledatay").append(closeRow);
        cols += `<tr>`;
        cols += `<td><input type="text" id="nquantity${i}" class="form-control text-right input-sm inputitem" autocomplete="off" name="nquantity${i}" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this); hitungtotal();"></td>`;
        cols += `<td><input type="text" class="form-control input-sm text-right" name="vharga${i}" id="vharga${i}" value="0" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);hitungtotal();"/></td>`;
        cols += `<td>
                    <div class="row">
                        <div class="col-sm-4 pudding">
                            <input type="text" class="form-control input-sm text-right" placeholder="%1" name="ndisc1${i}" id="ndisc1${i}" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this); hitungtotal();" />
                            <input type="hidden" readonly class="form-control input-sm text-right" name="vdisc1${i}" id="vdisc1${i}"/>
                        </div>
                        <div class="col-sm-4 pudding">
                            <input type="text" class="form-control input-sm text-right" placeholder="%2" name="ndisc2${i}" id="ndisc2${i}" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this); hitungtotal();"/>
                            <input type="hidden" readonly class="form-control input-sm text-right" name="vdisc2${i}" id="vdisc2${i}"/>
                        </div>
                        <div class="col-sm-4 pudding">
                            <input type="text" class="form-control input-sm text-right" placeholder="%3" name="ndisc3${i}" id="ndisc3${i}" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this); hitungtotal();"/>
                            <input type="hidden" readonly class="form-control input-sm text-right" name="vdisc3${i}" id="vdisc3${i}"/>
                        </div>
                    </div>
                </td>`;
        cols += `<td><input type="text" class="form-control input-sm text-right" name="vdiscount${i}" id="vdiscount${i}" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this); hitungtotal(); reformat(this);"/></td>`;
        cols += `<td><input type="text" readonly class="form-control input-sm text-right" name="vtotal${i}" id="vtotal${i}" value="0"/><input type="hidden" readonly class="form-control input-sm text-right" name="vtotaldiskon${i}" id="vtotaldiskon${i}" value="0"/></td>`;
        cols += `<td><input type="text" class="form-control input-sm" name="eremark${i}" id="eremark${i}" placeholder="Jika Ada!"/></td>`;
        newRiw.append(cols);
        $("#tabledatay").append(newRiw);
        $("#tabledatay").append(closeRow);
        // $("#tabledatay").append(newRiw);
        $('#idproduct' + i).select2({
            placeholder: 'Cari Kode / Nama Barang',
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
                        ikategori: $('#ikategori').val(),
                        ijenis: $('#ijenis').val(),
                        ibagian: $('#ibagian').val(),
                        idharga: $('#idkodeharga').val(),
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
        i++;
    });

    /*----------  GET DETAIL PRODUCT  ----------*/
    function getproduct(id) {
        $.ajax({
            type: "post",
            data: {
                'idproduct': $('#idproduct' + id).val(),
                'idharga': $('#idkodeharga').val(),
                'ddocument': $('#ddocument').val(),
                'idcustomer': $('#icustomer').val(),

            },
            url: '<?= base_url($folder . '/cform/getproduct'); ?>',
            dataType: "json",
            success: function(data) {
                if (parseInt(data.length) < 1) {
                    swal('Maaf :(', 'Harga Barang Jadi Periode ' + $('#ddocument').val() + ' Masih Kosong, Silahkan Input di Master Harga Jual Barang Jadi!', 'error');
                    $('#idproduct' + id).html('');
                    $('#idproduct' + id).val('');
                    return false;
                }
                if (typeof data[0] != 'undefined') {
                    ada = false;
                    for (var i = 1; i <= $('#jml').val(); i++) {
                        if (($('#idproduct' + id).val() == $('#idproduct' + i).val()) && (i != id)) {
                            swal("kode : " + data[0].i_product_base + " sudah ada !!!!!");
                            ada = true;
                            break;
                        } else {
                            ada = false;
                        }
                    }
                    if (!ada) {
                        $('#vharga' + id).val(formatcemua(data[0].v_price));
                        $('#nquantity' + id).focus();
                        $('#ndisc1' + id).val($('#ndiskon1').val());
                        $('#ndisc2' + id).val($('#ndiskon2').val());
                        $('#ndisc3' + id).val($('#ndiskon3').val());
                        $('#fc' + id).val(data[0].fc);
                        hitungtotal();
                    } else {
                        $('#idproduct' + id).html('');
                        $('#iproduct' + id).val('');
                    }
                } else {
                    swal('Data tidak ada!');
                }
            },
            error: function() {
                swal('Ada kesalahan :(');
            }
        });
    }

    /*----------  HITUNG NILAI  ----------*/
    function hitungtotal() {
        var total = 0;
        var totaldis = 0;
        var vjumlah = 0;
        var dpp = 0;
        var ppn = 0;
        var grand = 0;
        for (var i = 1; i <= $('#jml').val(); i++) {
            if (typeof $('#idproduct' + i).val() != 'undefined') {
                if (!isNaN(parseFloat($('#nquantity' + i).val()))) {
                    var qty = parseFloat($('#nquantity' + i).val());
                } else {
                    var qty = 0;
                }
                var jumlah = formatulang($('#vharga' + i).val()) * qty;
                var disc1 = formatulang($('#ndisc1' + i).val());
                var disc2 = formatulang($('#ndisc2' + i).val());
                var disc3 = formatulang($('#ndisc3' + i).val());
                if (!isNaN(parseFloat($('#vdiscount' + i).val()))) {
                    var disc4 = formatulang($('#vdiscount' + i).val());
                } else {
                    var disc4 = 0;
                }
                var ndisc1 = jumlah * (disc1 / 100);
                var ndisc2 = (jumlah - ndisc1) * (disc2 / 100);
                var ndisc3 = (jumlah - ndisc1 - ndisc2) * (disc3 / 100);

                var vtotaldis = (ndisc1 + ndisc2 + ndisc3 + parseFloat(disc4));

                var vtotal = jumlah - vtotaldis;

                $('#vdisc1' + i).val(ndisc1);
                $('#vdisc2' + i).val(ndisc2);
                $('#vdisc3' + i).val(ndisc3);
                $('#vtotaldiskon' + i).val(formatcemua(vtotaldis));
                $('#vtotal' + i).val(formatcemua(jumlah));
                $('#vtotalnet' + i).val(formatcemua(vtotal));
                totaldis += vtotaldis;
                vjumlah += jumlah;
                total += vtotal;
            }
        }
        $('#nkotor').val(formatcemua(vjumlah));
        $('#ndiskontotal').val(formatcemua(totaldis));

        dpp = vjumlah - totaldis;
        // ppn = dpp * 0.1;
        ppn = dpp * (parseFloat($('#nppn').val()) / 100);
        grand = dpp + ppn;

        $('#nbersih').val(formatcemua(grand));
        $('#vdpp').val(formatcemua(dpp));
        $('#vppn').val(formatcemua(ppn));
    }

    /*----------  HAPUS TR  ----------*/
    /* $("#tabledatay").on("click", ".ibtnDel", function(event) {
        $(this).closest("tr").remove();
        hitungtotal();
        obj = $('#tabledatay tr:visible').find('spanx');
        $.each(obj, function(key, value) {
            id = value.id;
            $('#' + id).html(key + 1);
        });
    }) */
    /*----------  HAPUS TR  ----------*/
    $("#tabledatay").on("click", ".ibtnDel", function(event) {
        $('#tabledatay tbody .tr' + $(this).data('delete') + '').remove()
        // $(this).closest(".tr"+$(this).data('delete')).remove();
        hitungtotal();
        obj = $('#tabledatay tr:visible').find('spanx');
        $.each(obj, function(key, value) {
            id = value.id;
            $('#' + id).html(key + 1);
        });
    })

    /*----------  VALIDASI SIMPAN DATA  ----------*/
    $("#submit").click(function(event) {
        ada = false;
        if (($('#ibagian').val() != '' || $('#ibagian').val() != null) && ($('#iarea').val() != '' || $('#iarea').val() != null) && ($('#icustomer').val() != '' || $('#icustomer').val() != null)) {
            if ($('#jml').val() == 0) {
                swal('Isi item minimal 1!');
                return false;
            } else {
                $("#tabledatay tbody tr").each(function() {
                    $(this).find("td select").each(function() {
                        if ($(this).val() == '' || $(this).val() == null) {
                            swal('Barang tidak boleh kosong!');
                            ada = true;
                        }
                    });
                    $(this).find("td .inputitem").each(function() {
                        if ($(this).val() == '' || $(this).val() == null || $(this).val() == 0) {
                            swal('Quantity Tidak Boleh Kosong Atau 0!');
                            ada = true;
                        }
                    });
                });

                for (var i = 1; i <= $('#jml').val(); i++) {
                    if (parseInt($('#nquantity' + i).val()) > parseInt($('#fc' + i).val())) {
                        swal('Maaf :(', 'Quantity ' + $('#idproduct' + i).text() + ' Tidak Boleh lebih dari ' + $('#fc' + i).val() + ' !', 'error');
                        return false;
                    }
                }

                if (!ada) {
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
                                    $('#id').val(data.id)
                                    swal("Sukses!", "No Dokumen : " + data.kode + ", Berhasil Diupdate :)", "success");
                                    $("input").attr("disabled", true);
                                    $("select").attr("disabled", true);
                                    $("#submit").attr("disabled", true);
                                    $("#addrow").attr("disabled", true);
                                    $("#send").attr("hidden", false);
                                } else if (data.sukses == 'ada') {
                                    swal("Maaf :(", "No Dokumen : " + data.kode + ", Sudah Ada :(", "error");
                                } else {
                                    swal("Maaf :(", "No Dokumen : " + data.kode + ", Gagal Diupdate :(", "error");
                                }
                            },
                            error: function() {
                                swal("Maaf", "Data Gagal Diupdate :(", "error");
                            }
                        });
                    });
                } else {
                    return false;
                }
            }
        } else {
            swal('Data Header Masih Ada yang Kosong!');
            return false;
        }
    })
</script>