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
                        <label class="col-md-6">Kredit Nota</label><label class="col-md-6">Tanggal Kredit Nota</label>
                        <div class="col-sm-6">
                            <input type="text" maxlength="2" required="" id= "ikn" name="ikn" class="form-control" value="" placeholder="No KN">
                        </div>
                        <div class="col-sm-6">
                            <input type="text" required="" readonly id= "dkn" name="dkn" class="form-control date" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">No Refferensi</label><label class="col-md-6">Tanggal Referensi</label>
                        <div class="col-sm-6">
                            <input type="text" maxlength="15" required="" id= "irefference" name="irefference" class="form-control" value="" placeholder="Referensinya">
                        </div>
                        <div class="col-sm-6">
                            <input type="text" required="" readonly id= "drefference" name="drefference" class="form-control date" value="">
                        </div>
                    </div>          
                    <div class="form-group row">
                        <label class="col-md-6">Nilai Kotor</label><label class="col-md-6">Nilai Potongan</label>
                        <div class="col-sm-6">
                            <input style="text-align: right;" required="" id= "vgross" name="vgross" class="form-control" value="0" onkeypress="return hanyaAngka(event);" onkeyup="reformat(this); hetang();" autocomplete="off" maxlength="16">
                        </div>
                        <div class="col-sm-6">
                            <input style="text-align: right;" required="" id= "vdiscount" name="vdiscount" class="form-control" value="0" onkeypress="return hanyaAngka(event);" onkeyup="reformat(this); hetang();" autocomplete="off" maxlength="15">
                            <input type="hidden" name="ncustomerdiscount1" id="ncustomerdiscount1" value="0">
                            <input type="hidden" name="ncustomerdiscount2" id="ncustomerdiscount2" value="0">
                            <input type="hidden" name="ncustomerdiscount3" id="ncustomerdiscount3" value="0">
                            <input type="hidden" name="vcustomerdiscount1" id="vcustomerdiscount1" value="0">
                            <input type="hidden" name="vcustomerdiscount2" id="vcustomerdiscount2" value="0">
                            <input type="hidden" name="vcustomerdiscount2" id="vcustomerdiscount3" value="0">
                        </div>
                    </div>          
                    <div class="form-group row">
                        <label class="col-md-6">Nilai Bersih</label><label class="col-md-6">Nilai Sisa</label>
                        <div class="col-sm-6">
                            <input style="text-align: right;" required="" readonly id= "vnetto" name="vnetto" class="form-control" value="0">
                        </div>
                        <div class="col-sm-6">
                            <input style="text-align: right;" required="" readonly id= "vsisa" name="vsisa" class="form-control" value="0">
                        </div>
                    </div>                    
                    <div class="form-group row"> 
                        <div class="col-md-6">
                            <div class="form-check">
                                <label class="custom-control custom-checkbox">
                                    <input type="checkbox" id="finsentif" name="finsentif" class="custom-control-input">
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">Insentif</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <label class="custom-control custom-checkbox">
                                    <input type="checkbox" id="fmasalah" name="fmasalah" class="custom-control-input">
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">Masalah</span>
                                </label>
                            </div>
                        </div>
                    </div>                           
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales();"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan
                            </button>&nbsp;&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i
                                class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-md-12">Area</label>
                            <div class="col-sm-12">
                                <select name="iarea" id="iarea" required="" class="form-control select2" onchange="getarea(this.value);">
                                    <option value=""></option>
                                    <?php if ($area) {                                 
                                        foreach ($area as $key) { ?>
                                            <option value="<?php echo $key->i_area;?>"><?= $key->i_area." - ".$key->e_area_name;?></option>
                                        <?php }; 
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-12">Pelanggan</label>
                            <div class="col-sm-12">
                                <select name="icustomer" id="icustomer" required="" class="form-control select2" disabled="" onchange="getpelanggan(this.value);"></select>
                                <input type="hidden" name="ecustomername" id="ecustomername">
                                <input type="hidden" name="icustomergroupar" id="icustomergroupar">
                            </div>
                        </div> 
                        <div class="form-group row">
                            <label class="col-md-12">Alamat</label>
                            <div class="col-sm-12">
                                <input  name="ecustomeraddress" id="ecustomeraddress" class="form-control" readonly>
                            </div>
                        </div> 
                        <div class="form-group row">
                            <label class="col-md-12">Salesman</label>
                            <div class="col-sm-12">
                                <select name="isalesman" id="isalesman" class="form-control select2" disabled="" onchange="getsalesman(this.value);"></select>
                                <input type="hidden" name="xsalesman" id="xsalesman">
                            </div>
                        </div> 
                        <div class="form-group row">
                            <label class="col-md-12">Keterangan</label>
                            <div class="col-sm-12">
                                <input name="eremark" id="eremark" class="form-control">
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
    function hetang(){
        if(document.getElementById("vgross").value=='') document.getElementById("vgross").value='0';
        if(document.getElementById("vdiscount").value=='') document.getElementById("vdiscount").value='0';
        kotor=formatulang(document.getElementById("vgross").value);
        disco=formatulang(document.getElementById("vdiscount").value);
        if(parseFloat(disco)<parseFloat(kotor)){
            document.getElementById("vnetto").value=formatcemua(kotor-disco);
            document.getElementById("vsisa").value=formatcemua(kotor-disco);
        }else{
            swal("Discount tidak boleh lebih besar dari nilai kotor");
            document.getElementById("vdiscount").value="0";
            document.getElementById("vnetto").value=formatcemua(kotor);
            document.getElementById("vsisa").value=formatcemua(kotor);
        }
    }

    function getarea(kode) {
        if (kode!='') {
            $("#icustomer").attr("disabled", false);
        }else{
            $("#icustomer").attr("disabled", true);
        }
    }

    function getsalesman(isalesman) {
        $("#xsalesman").val(isalesman);
    }

    function getpelanggan(kode) {
        if (kode!='') {
            $("#isalesman").attr("disabled", false);
        }else{
            $("#isalesman").attr("disabled", true);
        }
        var iarea  = $('#iarea').val();
        $.ajax({
            type: "post",
            data: {
                'icustomer': kode,
                'iarea'    : iarea
            },
            url: '<?= base_url($folder.'/cform/getdetailcus'); ?>',
            dataType: "json",
            success: function (data) {
                $('#ecustomeraddress').val(data[0].e_customer_address); 
                $('#icustomergroupar').val(data[0].i_customer_groupar);
                if (data[0].i_salesman!=null) {
                    $('#select2-isalesman-container').html(data[0].i_salesman+'-'+data[0].e_salesman_name);
                }
                $('#xsalesman').val(data[0].i_salesman);
                $('#esalesmanname').val(data[0].e_salesman_name);
                $('#ncustomerdiscount1').val(formatulang(data[0].n_customer_discount1));
                $('#ncustomerdiscount2').val(formatulang(data[0].n_customer_discount2));
                $('#ncustomerdiscount3').val(formatulang(data[0].n_customer_discount3));
            },
            error: function () {
                alert('Error :)');
            }
        });
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

    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date');
        $('#iarea').select2({
            placeholder: 'Pilih Area'
        });

        $('#icustomer').select2({
            placeholder: 'Cari Berdasarkan Kodelang / Nama',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getcustomer/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var iarea  = $('#iarea').val();
                    var query = {
                        q: params.term,
                        iarea: iarea
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
            placeholder: 'Cari Berdasarkan Kode / Nama',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getsalesman/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var icustomer = $('#icustomer').val();
                    var iarea  = $('#iarea').val();
                    var query = {
                        q: params.term,
                        iarea: iarea,
                        icustomer: icustomer
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

    function dipales(){
        if((document.getElementById("dkn").value=='') || (document.getElementById("iarea").value=='') || (document.getElementById("irefference").value=='')){
            swal("Data masih belum lengkap !!!");
            return false;
        }else{
            return true;
        }
    }
</script>