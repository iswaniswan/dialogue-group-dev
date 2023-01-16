<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-lg fa-list mr-2"></i><?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list fa-lg mr-2"></i><?= $title_list; ?> </a>
            </div>
            <div class="panel-body">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/tambah_kirim'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div class="col-md-4">
                    <div class="form-group row">
                        <label class="col-md-5">Date From</label><label class="col-md-5">Date To</label>
                        <div class="col-sm-5">
                            <input class="form-control input-sm date" readonly="" type="text" name="dfrom" id="dfrom" value="<?= $dfrom; ?>">
                        </div>
                        <div class="col-sm-5">
                            <input class="form-control input-sm date" readonly="" type="text" name="dto" id="dto" value="<?= $dto; ?>">
                        </div>
                        <div class="col-sm-2">
                            <button type="submit" id="submit" class="btn btn-info btn-sm"> <i class="fa fa-search fa-lg mr-2"></i>Cari</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
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
                    <table id="tabledata" class="display nowrap" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th width="2%">No</th>
                                <th>WIP</th>
                                <th>Nama WIP</th>
                                <th>Warna</th>
                                <th>Material</th>
                                <th>Nama Material</th>
                                <th>Satuan</th>
                                <th>FC Cutting</th>
                                <th>Tanggal Jahit</th>
                                <th width="5%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="form-group row">
                    <div class="col-sm-offset-5 col-sm-12" style="text-align: center;">
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
        $('.proses').hide();
        $('#ibagian').change((e) => {
            $('.proses').show();
        })
        /*Tidak boleh lebih dari hari ini*/
        showCalendar2('.date');
        datatable_no_search('#tabledata', base_url + '<?= $folder; ?>/Cform/data_schedule/<?= $dfrom . '/' . $dto; ?>');
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
        var idmaterial = [];
        var idproductwip = [];
        var idtypemakloon = [];
        for (var x = 1; x <= jml; x++) {
            if ($('#chk' + x).is(':checked')) {
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