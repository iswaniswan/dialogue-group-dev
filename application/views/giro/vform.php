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
                        <label class="col-md-6">Giro</label><label class="col-md-6">Tanggal Giro</label>
                        <div class="col-sm-6">
                         <input class="form-control" name="igiro" id="igiro" maxlength="10">
                     </div>
                     <div class="col-sm-6">
                        <input class="form-control date" name="dgiro" id="dgiro" readonly="" required="">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-6">Tanggal Setor</label><label class="col-md-6">Tanggal Jatuh Tempo</label>
                    <div class="col-sm-6">
                        <input class="form-control date" name="dsetor" id="dsetor" readonly="" required="">
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control date" name="dgiroduedate" id="dgiroduedate" readonly="" required="">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-12">Area</label>
                    <div class="col-sm-12">
                        <select name="iarea" id="iarea" class="form-control select2" required="" onchange="cekarea(this.value);">
                            <option value=""></option>
                            <?php if ($area) {                                   
                                foreach ($area as $kuy) { ?>
                                    <option value="<?php echo $kuy->i_area;?>"><?= $kuy->i_area." - ".$kuy->e_area_name;?></option>
                                <?php }; 
                            } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-12">Pelanggan</label>
                    <div class="col-sm-12">
                        <select name="icustomer" id="icustomer" class="form-control select2" required="" disabled=""  onchange="getcustomer(this.value);">
                            <option value=""></option>
                        </select>
                        <input type="hidden" name="icustomergroupar" id="icustomergroupar" value="">
                        <input type="hidden" name="ecustomername" id="ecustomername" value="">
                    </div>
                </div>               
                <div class="form-group row">
                    <div class="col-sm-offset-5 col-sm-12">
                        <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales();"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>&nbsp;&nbsp;
                        <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Batal</button>
                    </div>
                </div>
            </div>
            <div class="col-md-6"> 
                <div class="form-group row">
                    <label class="col-md-6">No DT</label><label class="col-md-6">Tanggal Terima</label>
                    <div class="col-sm-6">
                        <select class="form-control select2" name="idt" id="idt" required="" disabled="" onchange="getdt(this.value);"></select>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" name="dgiroterima" id="dgiroterima" readonly="" required="">
                    </div>
                </div>                   
                <div class="form-group row">
                    <label class="col-md-12">Bank</label>
                    <div class="col-sm-12">
                        <input type="text" name="egirobank" id="egirobank" class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-12">Keterangan</label>
                    <div class="col-sm-12">
                        <input type="text" name="egirodescription" id="egirodescription" class="form-control">
                    </div>
                </div> 
                <div class="form-group row">
                    <label class="col-md-12">Jumlah</label>
                    <div class="col-sm-12">
                        <input name="vjumlah" id="vjumlah" class="form-control" autocomplete="off" required="" onkeypress="return hanyaAngka(event);" onkeyup="reformat(this);">
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
    function cekarea(iarea) {
        if (iarea != '') {
            $("#icustomer").attr("disabled", false);
            $("#idt").attr("disabled", false);
        }else{
            $("#icustomer").attr("disabled", true);
            $("#idt").attr("disabled", true);
        }
        $('#icustomer').html('');
        $('#icustomer').val('');
        $('#idt').html('');
        $('#idt').val('');
    }

    function getcustomer(icustomer) {
        var iarea = $('#iarea').val();
        $.ajax({
            type: "post",
            data: {
                'iarea'    : iarea,
                'icustomer': icustomer
            },
            url: '<?= base_url($folder.'/cform/getdetailcustomer'); ?>',
            dataType: "json",
            success: function (data) {
                $('#ecustomername').val(data[0].e_customer_name);
            },
            error: function () {
                alert('Error :)');
            }
        });
    }

    function getdt(idt) {
        var iarea = $('#iarea').val();
        $.ajax({
            type: "post",
            data: {
                'iarea' : iarea,
                'idt'   : idt
            },
            url: '<?= base_url($folder.'/cform/getdetaildt'); ?>',
            dataType: "json",
            success: function (data) {
                $('#dgiroterima').val(data[0].d_dt);
            },
            error: function () {
                alert('Error :)');
            }
        });
    }

    function getbank() {
        var ebank = $('#ibank option:selected').text();
        $('#ebankname').val(ebank);
    }

    $(document).ready(function () {
        showCalendar('.date');
        $('#iarea').select2({
            placeholder: 'Cari Area Berdasarkan Kode / Nama'
        });

        $('#icustomer').select2({
            placeholder: 'Cari Customer Berdasarkan Kode / Nama',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getcustomer/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var iarea    = $('#iarea').val();
                    var query = {
                        q: params.term,
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

        $('#idt').select2({
            placeholder: 'Cari DT',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getdt/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var iarea    = $('#iarea').val();
                    var query = {
                        q: params.term,
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
    });

    function hetang(){
        $('#vsisa').val($('#vjumlah').val());
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
        if((document.getElementById("igiro").value!='') && (document.getElementById("idt").value!='') && (document.getElementById("dgiroterima").value!='') && (document.getElementById("dgiroduedate").value!='') && (document.getElementById("iarea").value!='') && (document.getElementById("icustomer").value!='') && (document.getElementById("dgiro").value!='') && (document.getElementById("dsetor").value!='') && (document.getElementById("vjumlah").value!='')){
            tes=adaspasi(document.getElementById("igiro").value);
            if(tes){
                swal('Nomor Giro tidak boleh ada spasi !!!!!');
                return false;
            }else{
                return true;
            }
        }else{
            swal('Data header masih ada yang salah !!!!!');
            return true;
        }
    }
</script>