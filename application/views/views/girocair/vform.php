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
                <?php 
                if($isi->d_giro!=''){
                    $tmp=explode("-",$isi->d_giro);
                    $th=$tmp[0];
                    $bl=$tmp[1];
                    $hr=$tmp[2];
                    $isi->d_giro=$hr."-".$bl."-".$th;
                }
                if($isi->d_rv!=''){
                    $tmp=explode("-",$isi->d_rv);
                    $th=$tmp[0];
                    $bl=$tmp[1];
                    $hr=$tmp[2];
                    $isi->d_rv=$hr."-".$bl."-".$th;
                }
                if($isi->d_giro_duedate!=''){
                    $tmp=explode("-",$isi->d_giro_duedate);
                    $th=$tmp[0];
                    $bl=$tmp[1];
                    $hr=$tmp[2];
                    $isi->d_giro_duedate=$hr."-".$bl."-".$th;
                }
                if($isi->d_giro_cair!=''){
                    $tmp=explode("-",$isi->d_giro_cair);
                    $th=$tmp[0];
                    $bl=$tmp[1];
                    $hr=$tmp[2];
                    $isi->d_giro_cair=$hr."-".$bl."-".$th;
                }
                if($isi->d_giro_tolak!=''){
                    $tmp=explode("-",$isi->d_giro_tolak);
                    $th=$tmp[0];
                    $bl=$tmp[1];
                    $hr=$tmp[2];
                    $isi->d_giro_tolak=$hr."-".$bl."-".$th;
                }
                ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-6">Giro</label><label class="col-md-6">Tanggal Giro</label>
                        <div class="col-sm-6">
                           <input class="form-control" name="igiro" id="igiro" value="<?= $isi->i_giro; ?>" maxlength="10">
                       </div>
                       <div class="col-sm-6">
                        <input class="form-control date" name="dgiro" id="dgiro" readonly="" required="" value="<?= $isi->d_giro; ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-6">Receive Voucher</label><label class="col-md-6">Tanggal Receive</label>
                    <div class="col-sm-6">
                        <input class="form-control" readonly="" name="irv" id="irv" value="<?= $isi->i_rv; ?>" maxlength="10">
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control date" readonly="" name="drv" id="drv" readonly="" required="" value="<?= $isi->d_rv; ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-12">Area</label>
                    <div class="col-sm-12">
                        <select name="iarea" id="iarea" class="form-control select2" required="">
                            <?php if ($area) {                                   
                                foreach ($area as $kuy) { ?>
                                    <option value="<?= $kuy->i_area;?>" <?php if ($iarea==$kuy->i_area) { echo "selected";} ?>><?= $kuy->i_area." - ".$kuy->e_area_name;?></option>
                                <?php }; 
                            } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-12">Pelanggan</label>
                    <div class="col-sm-12">
                        <select name="icustomer" id="icustomer" class="form-control select2" required="" onchange="getcustomer(this.value);">
                            <option value="<?= $isi->i_customer ?>"><?= $isi->i_customer." - ".$isi->e_customer_name; ?></option>
                        </select>
                        <input type="hidden" name="icustomergroupar" id="icustomergroupar" value="">
                        <input type="hidden" name="ecustomername" id="ecustomername" value="">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-2">
                        <div class="form-check">
                            <label class="custom-control custom-checkbox">
                                <input name="fgirouse" id="fgirouse" value="<?php echo $isi->f_giro_use; ?>" type="hidden">
                                <input type="checkbox" id="fgirotolak" name="fgirotolak" class="custom-control-input" <?php if($isi->f_giro_tolak=='t'){ echo "checked";}?>>
                                <span class="custom-control-indicator"></span>
                                <span class="custom-control-description">Tolak</span>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group row">
                            <label class="col-md-4">Tanggal Tolak</label>
                            <div class="col-md-7">
                                <input class="form-control date" name="dgirotolak" id="dgirotolak" type="text" readonly="" value="">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-check">
                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" id="fgirobatal" name="fgirobatal" class="custom-control-input" <?php if($isi->f_giro_batal=='t'){ echo "checked";}?>>
                                <span class="custom-control-indicator"></span>
                                <span class="custom-control-description">Batal</span>
                            </label>
                        </div>
                    </div>
                </div>                 
                <div class="form-group row">
                    <div class="col-sm-offset-5 col-sm-12">
                        <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales();"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>&nbsp;&nbsp;
                        <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/view/<?= $dfrom."/".$dto."/".$xarea."/"; ?>","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                    </div>
                </div>
            </div>
            <div class="col-md-6"> 
                <div class="form-group row">
                    <label class="col-md-12">Tanggal Jatuh Tempo</label>
                    <div class="col-sm-12">
                        <input class="form-control date" name="dgiroduedate" id="dgiroduedate" readonly="" required="" value="<?= $isi->d_giro_duedate ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-6">Tanggal Cair</label><label class="col-md-6">Cair Bank</label>
                    <div class="col-sm-6">
                        <input class="form-control date" name="dgirocair" id="dgirocair" required="" readonly="">
                    </div>
                    <div class="col-sm-6">
                        <select name="ibank" id="ibank" class="form-control select2" required="">
                            <option value=""></option>
                            <?php if ($bank) {                                   
                                foreach ($bank as $kuy) { ?>
                                    <option value="<?= $kuy->i_bank;?>"><?= $kuy->i_coa." - ".$kuy->e_bank_name;?></option>
                                <?php }; 
                            } ?>
                        </select>
                    </div>
                </div>                   
                <div class="form-group row">
                    <label class="col-md-12">Bank</label>
                    <div class="col-sm-12">
                        <input type="text" name="egirobank" id="egirobank" class="form-control" value="<?= $isi->e_giro_bank;?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-12">Keterangan</label>
                    <div class="col-sm-12">
                        <input type="text" name="egirodescription" id="egirodescription" class="form-control"  value="<?= $isi->e_giro_description ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-6">Jumlah</label><label class="col-md-6">Sisa</label>
                    <div class="col-sm-6">
                        <input <?php if($isi->f_giro_use=='t') echo "readonly"; ?> name="vjumlah" id="vjumlah" class="form-control" maxlength="16" autocomplete="off" required="" value="<?= number_format($isi->v_jumlah) ?>" onkeypress="return hanyaAngka(event);" onkeyup="reformat(this);sama(this.value);">
                    </div>
                    <div class="col-sm-6">
                        <input readonly="" name="vsisa" id="vsisa" class="form-control" required="" value="<?= number_format($isi->v_sisa) ?>">
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

    function sama(a){
        if(document.getElementById("fgirouse").value!='t'){
            document.getElementById("vsisa").value=a;
        }
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

        $('#ibank').select2({
            placeholder: 'Pilih Bank',
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
        if((document.getElementById("igiro").value!='') && (document.getElementById("dgiroduedate").value!='') && (document.getElementById("ibank").value!='')) {
            return true; 
        }else{
            swal('Data Masih Ada yang Salah!!!');
            return false;
        }
    }
</script>