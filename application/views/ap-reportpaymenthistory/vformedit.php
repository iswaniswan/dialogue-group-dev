<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>

                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-6">Giro</label><label class="col-md-6">Tanggal Giro</label>
                        <div class="col-sm-6">
                         <input type = "hidden" class="form-control" name="id" id="id" value="<?= $isi->id;?>" readonly>
                         <input class="form-control" name="igiro" id="igiro" maxlength="10" value="<?= $isi->i_giro;?>" >
                     </div>
                     <div class="col-sm-6">
                        <input class="form-control date" name="dgiro" id="dgiro" readonly="" required="" value="<?= $isi->d_giro;?>">
                    </div>
                </div>

                <div class="form-group row">
                <label class="col-md-6">Tanggal Terima</label><label class="col-md-6">Penerima</label>
                    <div class="col-sm-6">
                            <input class="form-control date" name="dgiroterima" id="dgiroterima" readonly="" required="" value="<?= $isi->d_giro_terima;?>">
                    </div>
                    <div class="col-sm-6">
                            <input type="hidden" name="ekaryawan" id="ekaryawan" value ="<?= $isi->e_nama_karyawan;?>" readonly>
                                <select name="ikaryawan" id="ikaryawan" class="form-control">
                                    <?php foreach ($karyawan as $ikaryawan):?>
                                        <option value="<?php echo $ikaryawan->i_karyawan;?>" <?php if($ikaryawan->i_karyawan == $isi->i_karyawan){ echo 'selected'; }?> ><?php echo $ikaryawan->e_nik."-".$ikaryawan->e_nama_karyawan;?></option>
                                    <?php endforeach; ?>  
                                </select>
                    </div>
                </div>

                <div class="form-group row">
                <label class="col-md-6">Tanggal Setor</label><label class="col-md-6">Bank</label>
                    <div class="col-sm-6">
                        <input class="form-control date" name="dsetor" id="dsetor" readonly="" required="" value="<?= $isi->d_rv;?>" disabled>
                    </div>
                    <div class="col-sm-6">
                        <input type="hidden" name="egirobank" id="egirobank" class="form-control">
                        <select name="ibank" id="ibank" class="form-control">
                        <option value = "">---</option>
                            <?php
                                if ($bank) {
                                    foreach ($bank->result() as $row) {?>
                                        <option value="<?php echo $row->i_bank; ?>" <?php if($isi->i_bank == $row->i_bank){ echo 'selected'; }?>><?php echo $row->e_bank_name; ?></option>
                            <?php 
                                    } 
                                }
                            ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <input type="text" name="egirodescription" id="egirodescription" class="form-control" value="<?= $isi->e_giro_description;?>">
                        </div>
                </div>
                <!-------------------------------------------------------------------------------------------------->
                <div class="form-group row">
                    <div class="col-sm-offset-5 col-sm-12">
                        <?php if(($isi->v_sisa == $isi->v_jumlah) && ($isi->f_giro_cair == 'f') && ($isi->f_giro_batal =='f') && ($isi->f_giro_tolak =='f') && ($isi->f_giro_batal_input =='f')){?>
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales();"> <i class="fa fa-save"></i>&nbsp;&nbsp;Update</button>&nbsp;&nbsp;
                        <?php }?>
                        
                        <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                    </div>
                </div>
            </div>
                <div class="form-group row">
                    <label class="col-md-12">Tanggal Jatuh Tempo</label>
                        <div class="col-sm-12">
                            <input class="form-control date" name="dgiroduedate" id="dgiroduedate" readonly="" required="" value="<?= $isi->d_giro_duedate;?>">
                        </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-12">Jumlah</label>
                    <div class="col-sm-12">
                        <input name="vjumlah" id="vjumlah" class="form-control" autocomplete="off" required="" onkeypress="return hanyaAngka(event);" onkeyup="reformat(this);" value="<?= number_format($isi->v_jumlah);?>">
                    </div>
                </div>       
                <div class="form-group row">
                    <label class="col-md-12">Pelanggan</label>
                    <div class="col-sm-12">
                        <input type="hidden" name="ecustomername" id="ecustomername" readonly>
                            <select name="icustomer" id="icustomer" class="form-control">
                                <?php foreach ($customer as $icustomer):?>
                                    <option value="<?php echo $icustomer->i_customer;?>" <?php if($icustomer->i_customer == $isi->i_customer){ echo 'selected'; }?> ><?php echo $icustomer->e_customer_name;?></option>
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
    $(document).ready(function () {
        showCalendar('.date');

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