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
                                                if ($product_wip==$key->id_product_wip) {
                                                    $select = " selected";
                                                }
                                            }
                                        }?>
                                        <option value="<?= $key->id_product_wip;?>"<?=$select;?>><?= $key->i_product_wip.' - '.$key->e_product_wipname;?></option>
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
                                <th class="text-right">FC Cutting</th>
                                <th>Tanggal Jahit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 0;
                            if ($detail->num_rows() > 0) {
                                foreach ($detail->result() as $key) {
                                    $i++; ?>
                                    <tr>
                                        <td class="text-center">
                                            <label class='custom-control custom-checkbox ml-4'>
                                                <input type='checkbox' id='chk<?= $i; ?>' name='chk<?= $i; ?>' class='custom-control-input'>
                                                <input id='id_material<?= $i;?>' name='id_material<?= $i;?>' value='<?= $key->id_material;?>' type='hidden'>
                                                <input id='id_product_wip<?= $i;?>' name='id_product_wip<?= $i;?>' value='<?= $key->id_product_wip;?>' type='hidden'>
                                                <input id='id_type_makloon<?= $i;?>' name='id_type_makloon<?= $i;?>' value='<?= $key->id_type_makloon;?>' type='hidden'>
                                                <input id='id<?= $i;?>' name='id<?= $i;?>' value="<?= $key->id_product_wip.'|'.$key->id_material.'|'.$key->id;?>" type='hidden'>
                                                <input id='i_periode<?= $i;?>' name='i_periode<?= $i;?>' value='<?= $key->i_periode;?>' type='hidden'>
                                                <span class='custom-control-indicator'></span>
                                                <span class='custom-control-description'></span>
                                            </td>
                                        <td class="text-center"><?= $i; ?></td>
                                        <td><?= $key->i_product_wip; ?></td>
                                        <td><?= $key->e_product_wipname; ?></td>
                                        <td><?= $key->e_color_name; ?></td>
                                        <td><?= $key->i_material; ?></td>
                                        <td><?= $key->e_material_name; ?></td>
                                        <td><?= $key->e_satuan_name; ?></td>
                                        <td class="text-right"><?= $key->qty; ?></td>
                                        <td><?= trim($key->tanggal_schedule); ?></td>
                                    </tr>
                            <?php }
                            } ?>
                        </tbody>
                    </table>
                    <input id='jml' class="jml" name='jml' value='<?= $i;?>' type='hidden'>
                </div>
                <div class="form-group row mt-5">
                    <div class="col-sm-12" style="text-align: center;">
                        <button type="button" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="check();"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Proses</button>
                        &nbsp;&nbsp;
                        <label class="custom-control custom-checkbox">
                            <input type="checkbox" id="checkAll" name="checkAll" class="custom-control-input">
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">Check All</span>
                        </label>
                    </div>
                </div>
                <input type="hidden" id="bagtmp" value="">
                <input type="hidden" name="d_from" value="<?= $dfrom; ?>">
                <input type="hidden" name="d_to" value="<?= $dto; ?>">
                <!-- Modal -->
                <div class="modal" id="myModal" role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title" style="text-align:center;"><b>Pilih bagian</b></h4>
                            </div>
                            <div class="modal-body">
                                <select id="ibagian" name="ibagian" class="form-control select2" style="width:100%;" onchange="gettmp(this.value);"></select>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" id="prosesbagian" class="btn btn-info btn-sm proses" data-dismiss="modal">Proses</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Modal -->
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('.select2').select2();
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

        $('.proses').hide();
        $('#ibagian').change((e) => {
            $('.proses').show();
        })
        /*Tidak boleh lebih dari hari ini*/
        showCalendar2('.date');
        // datatable_no_search('#tabledata', base_url + '<?= $folder; ?>/Cform/data_schedule/<?= $dfrom . '/' . $dto; ?>');
        $('#ibagian').select2({
            placeholder: "Pilih bagian",
        });
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
    });

    function onlyUnique(value, index, self) {
        return self.indexOf(value) === index;
    }

    function check() {
        var jml = $("#jml").val();
        // console.log(jml);
        var idmaterial = [];
        var idproductwip = [];
        var idtypemakloon = [];
        for (var x = 1; x <= jml; x++) {
            // console.log($('input[name="chk'+x+'"]:checked').length);
            
            // if ($('#chk' + x).is(':checked')) {
            if ($('input[name="chk'+x+'"]:checked').length) {
                console.log("checked");
                idmaterial.push($('#id_material' + x).val());
                idproductwip.push($('#id_product_wip' + x).val());
                let str = $('#id_type_makloon' + x).val();
                let arrres = str.replace('{', '');
                let arrres2 = arrres.replace('}', '');
                let arrres3 = arrres2.split(',');
                for (let i = 0; i < arrres3.length; i++) {
                    idtypemakloon.push(arrres3[i]);
                }
            }
        }
        idmaterial = idmaterial.filter(onlyUnique);
        idproductwip = idproductwip.filter(onlyUnique);
        idtypemakloon = idtypemakloon.filter(onlyUnique);
        // console.log(idtypemakloon);
        callswal(idmaterial, idproductwip, idtypemakloon);
        //var jml = $("#jml").val();
    }

    function gettmp(id) {
        $("#bagtmp").val(id);
        $('#ibagian').val(id);
    }

    function callswal(idmaterial, idproductwip, idtypemakloon) {
        $('#myModal').modal('show');
        // $('#ipp').val(ipp);
        // $('#ibagian').val(ibagian);
        $.ajax({
            type: "POST",
            url: "<?php echo site_url($folder . '/Cform/getbag'); ?>",
            data: {
                'idmaterial': idmaterial,
                'idproductwip': idproductwip,
                'idtypemakloon': idtypemakloon,
            },
            dataType: 'json',
            success: function(data) {
                $('#ibagian').html(data.kop);
                if (data.kosong == 'kopong') {
                    $('#submit').attr("disabled", true);
                } else {
                    $('#submit').attr("disabled", false);
                }
            }
        });
    }

    $('#prosesbagian').click(function() {
        if ($("#supptmp").val() != '') {
            var jml = $("#jml").val();
            var id_pp_item = []
            for (var x = 1; x <= jml; x++) {
                if ($('#chk' + x).is(':checked')) {
                    id_pp_item.push($('#id_pp_item' + x).val());
                }
            }
            id_pp_item = id_pp_item.filter(onlyUnique);
            $.ajax({
                type: "post",
                data: $('#proses_kirim').serialize(),
                url: '<?= base_url($folder . '/cform/proses_kirim'); ?>',
                dataType: "html",
                success: function(data) {
                    $('#main').html(data);
                },
                error: function(data) {
                    swal("Maaf", "Data kosong", "error");
                }
            });
        } else {
            $.ajax({
                success: function(data) {
                    swal("Maaf", "Data kosong, Supplier Tidak Terdaftar dalam Sistem", "error");
                }
            });
        }
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