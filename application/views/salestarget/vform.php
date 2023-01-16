<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-12">Periode</label>
                        <div class="col-sm-8">
                            <select name="bulan" id="bulan" class="form-control" required="" onchange="cekbulan(this.value);">
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
                        <div class="col-sm-4">
                            <select name="tahun" id="tahun" class="form-control" required="" onchange="cektahun(this.value);">
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
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-12">
                            <select name="iarea" id="iarea" class="form-control select2" disabled="" onchange="cekarea(this.value);">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Salesman</label>
                        <div class="col-sm-12">
                            <select name="isalesman" id="isalesman" class="form-control select2" disabled="" onchange="ceksales(this.value);">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Kota</label>
                        <div class="col-sm-12">
                            <select name="icity" id="icity" class="form-control select2" disabled="" onchange="cekcity(this.value);">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>                
                    <div class="form-group row"></div>   
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales();"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>&nbsp;&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Batal</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">&nbsp;</div>
                    <div class="form-group row">
                        <label class="col-md-12">Target Area Rp.</label>
                        <div class="col-sm-12">
                            <input name="vareatarget" maxlength="12" id="vareatarget" required="" class="form-control" readonly="" value="0">
                            <input type="hidden" id="htargetarea" name="htargetarea" value="0">
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-12">Target Salesman Rp.</label>
                        <div class="col-sm-12">
                            <input name="vsalesmantarget" maxlength="12" id="vsalesmantarget" required="" class="form-control" readonly="" value="0">
                            <input type="hidden" id="htargetsalesman" name="htargetsalesman" value="0">
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-12">Target Kota Rp.</label>
                        <div class="col-sm-12">
                            <input name="vcitytarget" maxlength="16" id="vcitytarget" required="" class="form-control" value="0" onkeypress="return hanyaAngka(event);" onkeyup="reformat(this); hitung();">
                            <input type="hidden" id="htargetkota" name="htargetkota" value="0">
                        </div>
                    </div> 
                </div>
            </form>
        </div>
    </div>
</div>
</div>
</div>
<script>
    function cekbulan(bulan) {
        var tahun = $("#tahun").val();
        if (bulan != '' && tahun != '') {
            $("#iarea").attr("disabled", false);
        }else{
            $("#iarea").attr("disabled", true);
        }
        $('#iarea').html('');
        $('#iarea').val('');
    }

    function cektahun(tahun) {
        var bulan = $("#bulan").val();
        if (bulan != '' && tahun != '') {
            $("#iarea").attr("disabled", false);
        }else{
            $("#iarea").attr("disabled", true);
        }
        $('#iarea').html('');
        $('#iarea').val('');
    }

    function cekarea(iarea) {
        var bulan = $("#bulan").val();
        var tahun = $("#tahun").val();
        if (bulan != '' && tahun != '' && iarea != '') {
            $("#isalesman").attr("disabled", false);
        }else{
            $("#isalesman").attr("disabled", true);
        }
        $('#isalesman').html('');
        $('#isalesman').val('');

        var iperiode = $('#tahun').val() + $('#bulan').val();
        $.ajax({
            type: "post",
            data: {
                'iperiode': iperiode,
                'iarea'   : iarea
            },
            url: '<?= base_url($folder.'/cform/getvarea'); ?>',
            dataType: "json",
            success: function (data) {
                $('#htargetarea').val(data[0].v_target);
                $('#vareatarget').val(formatcemua(data[0].v_target));
            },
            error: function () {
                alert('Error :)');
            }
        });
    }

    function ceksales(isalesman) {
        var bulan = $("#bulan").val();
        var tahun = $("#tahun").val();
        var iarea = $("#iarea").val();
        if (bulan != '' && tahun != '' && iarea != '' && isalesman != '') {
            $("#icity").attr("disabled", false);
        }else{
            $("#icity").attr("disabled", true);
        }
        $('#icity').html('');
        $('#icity').val('');

        var iperiode = $('#tahun').val() + $('#bulan').val();
        $.ajax({
            type: "post",
            data: {
                'iperiode' : iperiode,
                'iarea'    : iarea,
                'isalesman': isalesman
            },
            url: '<?= base_url($folder.'/cform/getvsalesman'); ?>',
            dataType: "json",
            success: function (data) {
                $('#htargetsalesman').val(data[0].v_target);
                $('#vsalesmantarget').val(formatcemua(data[0].v_target));
            },
            error: function () {
                alert('Error :)');
            }
        });
    }

    function cekcity(icity) {
        var bulan       = $("#bulan").val();
        var tahun       = $("#tahun").val();
        var iarea       = $("#iarea").val();
        var isalesman   = $("#isalesman").val();
        var iperiode    = $('#tahun').val() + $('#bulan').val();
        $.ajax({
            type: "post",
            data: {
                'iperiode' : iperiode,
                'iarea'    : iarea,
                'isalesman': isalesman,
                'icity'    : icity
            },
            url: '<?= base_url($folder.'/cform/getvcity'); ?>',
            dataType: "json",
            success: function (data) {
                $('#htargetkota').val(data[0].v_target);
                $('#vcitytarget').val(formatcemua(data[0].v_target));
            },
            error: function () {
                alert('Error :)');
            }
        });
    }

    $(document).ready(function () {
        $('#tahun').select2({
            placeholder: 'Pilih Tahun'
        });

        $('#bulan').select2({
            placeholder: 'Pilih Bulan'
        });

        $('#iarea').select2({
            placeholder: 'Cari Area Berdasarkan Kode / Nama',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getarea/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var iperiode = $('#tahun').val() + $('#bulan').val();
                    var query = {
                        q: params.term,
                        iperiode: iperiode
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: false
            }
        });

        $('#isalesman').select2({
            placeholder: 'Cari Salesman Berdasarkan Kode / Nama',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getsalesman/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var iperiode = $('#tahun').val() + $('#bulan').val();
                    var iarea    = $('#iarea').val();
                    var query = {
                        q: params.term,
                        iperiode: iperiode,
                        iarea:iarea
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: false
            }
        });

        $('#icity').select2({
            placeholder: 'Cari Kota Berdasarkan Kode / Nama',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getcity/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var iperiode  = $('#tahun').val() + $('#bulan').val();
                    var iarea     = $('#iarea').val();
                    var isalesman = $('#isalesman').val();
                    var query = {
                        q: params.term,
                        iperiode: iperiode,
                        iarea:iarea,
                        isalesman:isalesman
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: false
            }
        });
    });

    function hitung() {
        vtarea        = parseFloat(formatulang(document.getElementById("vareatarget").value));
        vtsalesman    = parseFloat(formatulang(document.getElementById("vsalesmantarget").value));
        vtcity        = parseFloat(formatulang(document.getElementById("vcitytarget").value));
        vasalarea     = parseFloat(formatulang(document.getElementById("htargetarea").value));
        vasalsalesman = parseFloat(formatulang(document.getElementById("htargetsalesman").value));
        vasalcity     = parseFloat(formatulang(document.getElementById("htargetkota").value));
        vtsalesman    = (vasalsalesman+vtcity)-vasalcity;
        vtarea        = (vasalarea+vtcity)-vasalcity;
        document.getElementById("vareatarget").value     = formatcemua(vtarea);
        document.getElementById("vsalesmantarget").value = formatcemua(vtsalesman);
    }

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
    });

    function hanyaAngka(evt) {      
        var charCode = (evt.which) ? evt.which : event.keyCode      
        if (charCode > 31 && (charCode < 48 || charCode > 57))        
            return false;    
        return true;
    }

    function dipales(){
        if((document.getElementById("bulan").value=='')||
            (document.getElementById("tahun").value=='')||
            (document.getElementById("iarea").value=='')||
            (document.getElementById("isalesman").value=='')||
            (document.getElementById("icity").value=='')||
            (document.getElementById("vcitytarget").value=='')||
            (document.getElementById("ncitytarget").value=='')){
            swal("Data Belum Lengkap !!!");
            return false;
        }else{          
            return true;
        }
    }
</script>