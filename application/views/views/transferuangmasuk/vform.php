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
                        <label class="col-md-3">Bukti Transfer</label>
                        <div class="col-sm-5">
                           <input class="form-control" name="ikum" id="ikum" maxlength="15" value="">
                        </div>
                        <div class="col-sm-3">
                            <input class="form-control date" name="dkum" id="dkum" readonly="" required="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Area</label>
                        <div class="col-sm-8">
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
                        <label class="col-md-3">Pelanggan</label>
                        <div class="col-sm-8">
                            <select name="icustomer" id="icustomer" class="form-control select2" required="" disabled=""  onchange="getcustomer(this.value);">
                                <option value=""></option>
                            </select>
                            <input type="hidden" name="icustomergroupar" id="icustomergroupar" value="">
                            <input type="hidden" name="ecustomername" id="ecustomername" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Jumlah</label>
                        <div class="col-sm-8">
                            <input name="vjumlah" id="vjumlah" class="form-control" autocomplete="off" required="" onkeypress="return hanyaAngka(event);" onkeyup="reformat(this);hetang(this);">
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
                        <label class="col-md-3">Nama Bank</label>
                        <div class="col-sm-8">
                            <select name="ibank" id="ibank" class="form-control select2" required="" onchange="getbank();">
                                <option value=""></option>
                                <?php if ($bank) {                                   
                                    foreach ($bank as $kuy) { ?>
                                        <option value="<?php echo $kuy->i_bank;?>"><?= $kuy->e_bank_name;?></option>
                                    <?php }; 
                                } ?>
                            </select>
                            <input type="hidden" name="ebankname" id="ebankname" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Salesman</label>
                        <div class="col-sm-8">
                            <input type="hidden" name="isalesman" id="isalesman" class="form-control">
                            <input type="text" name="esalesmanname" readonly="" id="esalesmanname" class="form-control">
                        </div>
                    </div>  
                    <div class="form-group row">
                        <label class="col-md-3">Keterangan</label>
                        <div class="col-sm-8">
                            <input type="text" name="eremark" id="eremark" class="form-control">
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-3">Sisa</label>
                        <div class="col-sm-8">
                            <input name="vsisa" id="vsisa" class="form-control" readonly="" onkeypress="return hanyaAngka(event);" onkeyup="reformat(this);">
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
        }else{
            $("#icustomer").attr("disabled", true);
        }
        $('#icustomer').html('');
        $('#icustomer').val('');
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
                $('#icustomergroupar').val(data[0].i_customer_groupar);
                $('#isalesman').val(data[0].i_salesman);
                $('#esalesmanname').val(data[0].e_salesman_name);
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

        $('#ibank').select2({
            placeholder: 'Pilih Bank'
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
        if((document.getElementById("ikum").value=='')||(document.getElementById("ibank").value=='')||(document.getElementById("dkum").value=='')){
            swal("Data Masih Ada yang Kososng !!!");
            return false;
        }else{          
            return true;
        }
    }
</script>