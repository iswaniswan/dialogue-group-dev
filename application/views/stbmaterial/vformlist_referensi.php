<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-lg fa-list mr-2"></i><?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list fa-lg mr-2"></i><?= $title_list; ?> </a>
            </div>
            <div class="panel-body">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/tambah_kirim'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div class="col-md-10">
                    <div class="form-group row">
                        <label class="col-md-2">Date From</label>
                        <label class="col-md-2">Date To</label>
                        <label class="col-md-4">Kode WIP</label>
                        <label class="col-md-3">Kode Material</label>
                        <label class="col-md-1"></label>
                        <div class="col-sm-2">
                            <input class="form-control input-sm date" readonly="" type="text" name="dfrom" id="dfrom" value="<?= $dfrom; ?>">
                        </div>
                        <div class="col-sm-2">
                            <input class="form-control input-sm date" readonly="" type="text" name="dto" id="dto" value="<?= $dto; ?>">
                        </div>
                        <div class="col-sm-4">
                            <select class="form-control select2" name="i_product_wip[]" multiple data-placeholder="Semua">
                                <option value=""></option>
                                <?php
                                    if ($wip->num_rows()>0) {
                                    foreach ($wip->result() as $key) {
                                        $select = "";
                                        if ($i_product_wip) {
                                            foreach ($i_product_wip as $product_wip) {
                                                if ($product_wip==$key->id_product) {
                                                    $select = " selected";
                                                }
                                            }
                                        }?>
                                        <option value="<?= $key->id_product;?>"<?=$select;?>><?= $key->i_product_wip.' - '.$key->e_product_wipname;?></option>
                                    <?php }
                                }?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select class="form-control select2" name="i_material[]" multiple data-placeholder="Semua">
                                <option value=""></option>
                                <?php if ($material->num_rows()>0) {
                                    foreach ($material->result() as $key) {
                                        $select = "";
                                        if ($i_material) {
                                            foreach ($i_material as $material) {
                                                if ($material==$key->id_material) {
                                                    $select = " selected";
                                                }
                                            }
                                        }    
                                    ?>
                                        <option value="<?= $key->id_material;?>"<?=$select;?>><?= $key->i_material.' - '.$key->e_material_name;?></option>
                                    <?php }
                                }?>
                            </select>
                        </div>
                        <div class="col-sm-1">
                            <button type="submit" id="submit" class="btn btn-info btn-sm"> <i class="fa fa-search fa-lg mr-2"></i>Cari</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group pull-right row">
                        <!-- <label class="col-md-12">&nbsp;</label> -->
                        <div class="col-sm-12 mt-5 pull-right">
                            <button type="button" class="btn btn-rounded btn-primary btn-sm" onclick="show('<?= $folder; ?>/cform/tambah_kirim/<?= $dfrom . "/" . $dto; ?>','#main'); return false;"> <i class="fa fa-spin fa fa-refresh fa-lg mr-2"></i>Reload Page</button>
                        </div>
                    </div>
                </div>
                </form>
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/proses_kirim'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal', 'id' => 'proses_kirim')); ?>
                <div class="table-responsive">
                    <table id="table" class="table display nowrap color-table inverse-table table-bordered class" data-search="false" data-show-refresh="false" data-show-toggle="true" data-show-fullscreen="true" data-show-columns="true" data-show-columns-toggle-all="true" data-show-export="true" data-show-pagination-switch="false" data-pagination="false" data-id-field="id" data-page-list="[10, 25, 50, 100, all]">
                        <thead>
                            <tr>
                                <th class="text-center" width="5%">Act</th>
                                <th class="text-center" width="2%">No</th>
                                <th>WIP</th>
                                <th>Nama WIP</th>
                                <th>Warna</th>
                                <th>Material</th>
                                <th>Nama Material</th>
                                <th>Satuan</th>
                                <th>Quantity</th>
                                <th>No. Referensi</th>
                                <th>Tgl. Referensi</th>
                                <th>Pembuat Memo</th>
                                <th>Tujuan Perusahaan Memo</th>
                                <th>Tgl. Kirim Memo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 0;
                            if ($detail->num_rows() > 0) {
                                foreach ($detail->result() as $key) {
                                    $i++; ?>
                                    <tr>
                                        <td class="text-center">
                                            <label class='custom-control custom-checkbox'> 
                                            <input type='checkbox' id='chk<?= $i ?>' name='chk<?= $i ?>' class='custom-control-input'>
                                            <span class='custom-control-indicator'></span>
                                            <span class='custom-control-description'></span>
                                            <input id='id<?= $i ?>' name='id<?= $i ?>' value='<?= $key->id ?>' type='hidden'>
                                            <input id='i_bagian<?= $i ?>' name='i_bagian<?= $i ?>' value='<?= $key->i_bagian ?>' type='hidden'>
                                            <input id='i_type<?= $i ?>' name='i_type<?= $i ?>' value='<?= $key->i_type ?>' type='hidden'>
                                            <input id='i_tujuan<?= $i ?>' name='i_tujuan<?= $i ?>' value='<?= $key->i_tujuan ?>' type='hidden'>
                                            <input id='d_kirim<?= $i ?>' name='d_kirim<?= $i ?>' value='<?= $key->d_kirim ?>' type='hidden'>
                                            <input id='tujuan_name<?= $i ?>' name='tujuan_name<?= $i ?>' value='<?= $key->tujuan_name ?>' type='hidden'>
                                            <input id='company_name<?= $i ?>' name='company_name<?= $i ?>' value='<?=$key->company_name ?>' type='hidden'>
                                            <input id='id_company_tujuan<?= $i ?>' name='id_company_tujuan<?= $i ?>' value='<?=$key->id_company_tujuan ?>' type='hidden'>
                                            <input id='jml' name='jml' value='<?= $key->jml ?>' type='hidden'>
                                        </td>
                                        <td class="text-center"><?= $i; ?></td>
                                        <td><?= $key->i_product_wip; ?></td>
                                        <td><?= $key->e_product_wipname; ?></td>
                                        <td><?= $key->e_color_name; ?></td>
                                        <td><?= $key->i_material; ?></td>
                                        <td><?= $key->e_material_name; ?></td>
                                        <td><?= $key->e_satuan_name; ?></td>
                                        <td class="text-right"><?= $key->n_quantity_sisa; ?></td>
                                        <td><?= $key->i_document; ?></td>
                                        <td><?= trim($key->d_document); ?></td>
                                        <td><?= $key->e_bagian_name ?> - <?= $key->company_pembuat ?></td>
                                        <td><?= $key->tujuan_name ?> - <?= $key->company_name; ?></td>
                                        <td><?= trim($key->d_kirim); ?></td>
                                    </tr>
                            <?php }
                            } ?>
                        </tbody>
                    </table>
                    <!-- <input id='jml' class="jml" name='jml' value='<?= $i;?>' type='hidden'> -->
                </div>
                <div class="form-group row mt-5">
                    <div class="col-sm-12" style="text-align: center;">
                        <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Proses</button>
                        &nbsp;&nbsp;
                        <label class="custom-control custom-checkbox">
                            <input type="checkbox" id="checkAll" name="checkAll" class="custom-control-input">
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">Check All</span>
                        </label>
                    </div>
                </div>
                <input type="hidden" name="d_from" value="<?= $dfrom; ?>">
                <input type="hidden" name="d_to" value="<?= $dto; ?>">
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        /*Tidak boleh lebih dari hari ini*/
        $('.select2').select2();
        showCalendar2('.date');
        // datatablemain('#tabledata', base_url + '<?= $folder; ?>/Cform/data_schedule/<?= $dfrom . '/' . $dto; ?>', 7);
        var $table = $('#table');

        function initTable(table) {
            table.bootstrapTable('destroy').bootstrapTable({
                height: 500,
                fixedColumns: true,
                fixedNumber: 6,
                // columns: [
                //     [{
                //         field: 'state',
                //         checkbox: true,
                //         align: 'center',
                //         valign: 'middle'
                //     }, {
                //         title: 'Item ID',
                //         field: 'id',
                //         align: 'center',
                //         valign: 'middle',
                //         sortable: true,
                //         footerFormatter: totalTextFormatter
                //     },{
                //         field: 'name',
                //         title: 'Item Name',
                //         sortable: true,
                //         footerFormatter: totalNameFormatter,
                //         align: 'center'
                //     }, {
                //         field: 'price',
                //         title: 'Item Price',
                //         sortable: true,
                //         align: 'center',
                //         footerFormatter: totalPriceFormatter
                //     }, {
                //         field: 'operate',
                //         title: 'Item Operate',
                //         align: 'center',
                //         clickToSelect: false,
                //         events: window.operateEvents,
                //         formatter: operateFormatter
                //     }]
                // ]
            })
        }

        $(function() {
            initTable($table)
        })

        $("#dfrom").change(function() {
            var dfrom = splitdate($(this).val());
            var dto = splitdate($('#dto').val());
            if (dfrom != null && dto != null) {
                if (dfrom > dto) {
                    swal('Tanggal Mulai Tidak Boleh Lebih Besar Dari Tanggal Sampai!!!');
                    $('#dfrom').val('');
                }
            }
        });

        $("#dto").change(function() {
            var dto = splitdate($(this).val());
            var dfrom = splitdate($('#dfrom').val());
            if (dfrom != null && dto != null) {
                if (dfrom > dto) {
                    swal('Tanggal Sampai Tidak Boleh Lebih Kecil Dari Tanggal Mulai!!!');
                    $('#dto').val('');
                }
            }
        });

        $("#checkAll").click(function() {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });

        $(".submit").click(function(event) {
            if ($("#formclose input:checkbox:checked").length > 0) {
                return true;
            } else {
                swal('Maaf :(', 'Pilih data minimal satu!', 'error');
                return false;
            }
        });
    });

    function cekcari() {
        if ($('#dfrom').val() != '' && $('#dto').val() == '') {
            swal('Tanggal Sampai Harus Dipilih!!! ');
            return false;
        } else if ($('#dfrom').val() == '' && $('#dto').val() != '') {
            swal('Tanggal Mulai Harus Dipilih!!! ');
            return false;
        } else {
            return true;
        }
    }
</script>