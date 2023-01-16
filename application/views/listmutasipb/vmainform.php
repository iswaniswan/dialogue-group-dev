<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-info-circle"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/view'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div class="col-md-6">
                    <div class="form-group row">
                    <label class="col-md-12">Periode</label>
                        <div class="col-sm-6">
                            <select name="bulan" id="bulan" class="form-control" required="">
                                <option value="">-- Pilih Bulan --</option>
                                <option value='01'>Januari</option>
                                <option value='02'>Pebruari</option>
                                <option value='03'>Maret</option>
                                <option value='04'>April</option>
                                <option value='05'>Mei</option>
                                <option value='06'>Juni</option>
                                <option value='07'>Juli</option>
                                <option value='08'>Agustus</option>
                                <option value='09'>September</option>
                                <option value='10'>Oktober</option>
                                <option value='11'>November</option>
                                <option value='12'>Desember</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="tahun" id="tahun" class="form-control" required="">
                                <option value="">-- Pilih Tahun --</option>
                                <?php 
                                $tahun1 = date('Y')-3;
                                $tahun2 = date('Y');
                                for($i=$tahun1;$i<=$tahun2;$i++){ ?>
                                    <option value="<?= $i;?>"><?= $i;?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-md-12">Pelanggan</label>
                        <div class="col-sm-12">
                            <select id="icustomer" name="icustomer" class="form-control select2" required="" onchange="getdetailpel(this.value);">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-12">SPG</label>
                        <div class="col-sm-6">
                            <input type="text" name="espgname" id="espgname" class="form-control" value="" readonly>
                            <input type="hidden" name="ispg" id="ispg" class="form-control" value="" readonly>
                        </div>
                    </div>
                    

                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-8">
                            <button type="submit" id="submit" class="btn btn-inverse btn-rounded btn-sm"> <i class="fa fa-table"></i>&nbsp;&nbsp;View</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
<script>
    $(document).ready(function () {
        showCalendar('.date');
        $('#bulan').select2({
            placeholder: 'Pilih Bulan'
        });
        $('#tahun').select2({
            placeholder: 'Pilih Tahun'
        });
        /* $('#istore').select2({
            placeholder: 'Cari Berdasarkan Kode / Nama'
        }); */

        $('#icustomer').select2({
            placeholder: 'Cari Pelanggan',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/customer'); ?>',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
    });

    function getdetailpel(icustomer){
        var icustomer  = $('#icustomer').val();
        $.ajax({
            type: "post",
            data: {
                'icustomer': icustomer
            },
            url: '<?= base_url($folder.'/cform/getdetailspg'); ?>',
            dataType: "json",
            success: function (data) {
                $('#ispg').val(data[0].i_spg);
                $('#espgname').val(data[0].e_spg_name);
            },
            error: function () {
                swal('Error :)');
            }
        });
    }

    /* function get(id) {   
        
        $.ajax({
            type: "post",
            data: {
                'istore' : id,
            },
            url: '<#?= base_url($folder.'/cform/getstore'); ?>',
            dataType: "json",
            success: function (data) {
                var istorelocation   = data['isi']['i_store_location'];
                $('#istorelocation').val(istorelocation);
                var iarea   = data['isi']['i_area'];
                $('#iarea').val(iarea);
            },
            error: function () {
                swal('Error :)');
            }
        });
        xx = $('#jml').val();
    } */
    
    /* function getdetailpel(icustomer){
        var icus  = $('#dspb').val();
        var iarea = $('#iarea').val();
        $.ajax({
            type: "post",
            data: {
                'isalesman': isalesman,
                'dspb'     : dspb,
                'iarea'    : iarea
            },
            url: '<#?= base_url($folder.'/cform/getdetailsal'); ?>',
            dataType: "json",
            success: function (data) {
                $('#isalesmanx').val(data[0].i_salesman);
                $('#esalesmannamex').val(data[0].e_salesman_name);
            },
            error: function () {
                swal('Error :)');
            }
        });
    } */
</script>