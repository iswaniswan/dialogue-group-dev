<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus fa-lg mr-2"></i><?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom; ?>/<?= $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <div class="panel-body">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/tambah'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
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
                            <button type="submit" class="btn btn-info btn-sm"> <i class="fa fa-search fa-lg mr-2"></i>Cari</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group pull-right row">
                        <!-- <label class="col-md-12">&nbsp;</label> -->
                        <div class="col-sm-12 mt-5 pull-right">
                            <button type="button" class="btn btn-rounded btn-primary btn-sm" onclick="show('<?= $folder; ?>/cform/tambah/<?= $dfrom . "/" . $dto; ?>','#main'); return false;"> <i class="fa fa-spin fa fa-refresh fa-lg mr-2"></i>Reload Page</button>
                        </div>
                    </div>
                </div>
                </form>
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/proses'), 'update' => '#main', 'type' => 'post', 'id' => 'formclose', 'class' => 'form-horizontal')); ?>
                <div class="table-responsive">
                    <table id="tabledata" class="display nowrap" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th width="3%;">No</th>
                                <th>Perusahaan</th>
                                <th>WIP</th>
                                <th>Tgl. Schedule</th>
                                <th>Nama WIP</th>
                                <th>Material</th>
                                <th>Nama Material</th>
                                <th>Tgl. Realisasi</th>
                                <th>Jml</th>
                                <th>Jml Gelar</th>
                                <th width="5%;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-sm-12" style="text-align: center;">
                        <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-lg fa-check-square-o mr-2"></i>Proses</button>&nbsp;&nbsp;
                        <label class="custom-control custom-checkbox">
                            <input type="checkbox" id="checkAll" name="checkAll" class="custom-control-input">
                            <input type="hidden" name="d_from" id="d_from" value="<?= $dfrom; ?>">
                            <input type="hidden" name="d_to" id="d_to" value="<?= $dto; ?>">
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">Check All</span>
                        </label>
                    </div>
                </div>
                </form>

                <!-- <div class="form-group row">
                <div class="col-md-12">
                    <div class="form-group">
                        <span class="notekode"><b>Note : </b></span><br>
                        <span class="notekode">* Jika barang hilang setelah memproses PP -> Harap mengisi master harga barang untuk supplier yang di pilih.</span><br>
                    </div>
                </div>
             </div> -->
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        datatablemod('#tabledata', base_url + '<?= $folder; ?>/Cform/data_referensi/<?= $dfrom . '/' . $dto; ?>');
        showCalendar('.date');
        $(".select2").select2();
    });

    function onlyUnique(value, index, self) {
        return self.indexOf(value) === index;
    }

    $("#checkAll").click(function() {
        $('input:checkbox').not(this).prop('checked', this.checked);
    });

    $("#submit").click(function(event) {
        if ($("#formclose input:checkbox:checked").length > 0) {
            return true;
        } else {
            swal('Maaf :(', 'Pilih data minimal satu!', 'error');
            return false;
        }
    });

    $('#prosessupplier').click(function() {
        var jml = $("#jml").val();
        var id = []
        var id_company_referensi = []
        for (var x = 1; x <= jml; x++) {
            if ($('#chk' + x).is(':checked')) {
                id.push($('#id' + x).val());
                id_company_referensi.push($('#id_company_referensi' + x).val());
            }
        }
        id = id.filter(onlyUnique);
        id_company_referensi = id_company_referensi.filter(onlyUnique);
        $.ajax({
            type: "post",
            data: {
                'id_company_referensi': $("#id_company_referensi").val(),
                'id': id,
                'dfrom': $("#dfrom").val(),
                'dto': $("#dto").val()
            },
            url: '<?= base_url($folder . '/cform/proses'); ?>',
            dataType: "html",
            success: function(data) {
                $('#main').html(data);
            },
            error: function(data) {
                swal("Maaf", "Data kosong", "error");
            }
        });
    });
</script>