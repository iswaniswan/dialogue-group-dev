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
                        <label class="col-md-6">Tanggal Dokumen</label><label class="col-md-6">Giro</label>
                        <div class="col-sm-6">
                            <input class="form-control datedoc" name="did" id="did" readonly="" required="">
                        </div>
                        <div class="col-sm-6">
                         <input class="form-control" name="igiro" id="igiro" maxlength="10">
                        </div> 
                </div>

                <div class="form-group row">
                <label class="col-md-6">Tanggal Terima</label><label class="col-md-6">Penerima</label>
                    <div class="col-sm-6">
                            <input class="form-control date" name="dgiroterima" id="dgiroterima" readonly="" required="">
                    </div>
                    <div class="col-sm-6">
                            <input type="hidden" name="ekaryawan" id="ekaryawan" readonly>
                                <select name="ikaryawan" id="ikaryawan" class="form-control">
                                    <?php foreach ($karyawan as $ikaryawan):?>
                                        <option></option>
                                        <option value="<?php echo $ikaryawan->i_karyawan;?>"><?php echo $ikaryawan->e_nik."-".$ikaryawan->e_nama_karyawan;?></option>
                                    <?php endforeach; ?>  
                                </select>
                    </div>
                </div>

                <div class="form-group row">
                <label class="col-md-6">Tanggal Setor</label><label class="col-md-6">Bank</label>
                    <div class="col-sm-6">
                        <input class="form-control date" name="dsetor" id="dsetor" required="" disabled>
                    </div>
                    <div class="col-sm-6">
                        <input type="hidden" name="egirobank" id="egirobank" class="form-control">
                        <select name="ibank" id="ibank" class="form-control">
                        <option value = "">---</option>
                            <?php
                                if ($bank) {
                                    foreach ($bank->result() as $row) {?>
                                        <option value="<?php echo $row->i_bank; ?>"><?php echo $row->e_bank_name; ?></option>
                            <?php 
                                    } 
                                }
                            ?>
                        </select>
                    </div>
                </div>

                <!-- <div class="form-group row">
                    <label class="col-md-6">Area</label>
                    <div class="col-sm-12">
                        <select name="iarea" id="iarea" class="form-control select2" required="" onchange="cekarea(this.value);">
                            <option value=""></option>
                            <#?php if ($area) {                                   
                                foreach ($area as $kuy) { ?>
                                    <option value="<#?php echo $kuy->i_area;?>"><#?= $kuy->i_area." - ".$kuy->e_area_name;?></option>
                                <#?php }; 
                            } ?>
                        </select>
                    </div>
                </div> -->
                <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <input type="text" name="egirodescription" id="egirodescription" class="form-control">
                        </div>
                </div>
                <!-------------------------------------------------------------------------------------------------->
                <div class="form-group row">
                    <div class="col-sm-offset-5 col-sm-12">
                        <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales();"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>&nbsp;&nbsp;
                        <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                    </div>
                </div>
            </div>
                <div class="form-group row">
                    <label class="col-md-6">Tanggal Giro</label><label class="col-md-6">Tanggal Jatuh Tempo</label>
                        <div class="col-sm-6">
                            <input class="form-control date" name="dgiro" id="dgiro" readonly="" required="">
                        </div>
                        <div class="col-sm-6">
                            <input class="form-control date" name="dgiroduedate" id="dgiroduedate" readonly="" required="">
                        </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-12">Jumlah</label>
                    <div class="col-sm-12">
                        <input name="vjumlah" id="vjumlah" class="form-control" autocomplete="off" required="" onkeypress="return hanyaAngka(event);" onkeyup="reformat(this);">
                    </div>
                </div>       
                <div class="form-group row">
                        <label class="col-md-12">Pelanggan</label>
                        <div class="col-sm-12">
                            <input type="hidden" name="ecustomername" id="ecustomername" readonly>
                                <select name="icustomer" id="icustomer" class="form-control">
                                    <?php foreach ($customer as $icustomer):?>
                                        <option></option>
                                        <option value="<?php echo $icustomer->i_customer;?>"><?php echo $icustomer->e_customer_name;?></option>
                                    <?php endforeach; ?>  
                                </select>
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
    /* function getdt(idt) {
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
    } */

    /* function getbank() {
        var ebank = $('#ibank option:selected').text();
        $('#ebankname').val(ebank);
    } */

    $(document).ready(function () {
        showCalendar('.date');
        showCalendar('.datedoc',1800,0);

        $('#icustomer').select2({
            placeholder: 'Cari Pelanggan Berdasarkan Kode / Nama',
            allowClear: true,
        }).on("change", function (e) {
            var kode = $('#icustomer option:selected').text();
            //kode = kode.split("-");
            $('#ecustomername').val(kode);
        });

        $('#ikaryawan').select2({
            placeholder: 'Cari Karyawan Berdasarkan NIK / Nama',
            allowClear: true,
        }).on("change", function (e) {
            var kode = $('#ikaryawan option:selected').text();
            temp     = kode.split("-");
            id 		 = temp[2];
  	        nama 	 = temp[1];
            $('#ekaryawan').val(nama);
        });

        $('#ibank').select2({
            placeholder: 'Cari Nama Bank',
            allowClear: true,
        }).on("change", function (e) {
            var kode = $('#ibank option:selected').text();
            $('#egirobank').val(kode);
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