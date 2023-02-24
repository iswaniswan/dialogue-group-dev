<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                    <div id="pesan"></div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-2">Kode Sales</label>
                            <label class="col-md-4">Nama Sales</label>
                            <label class="col-md-3">Area</label>
                            <label class="col-md-3">Role</label>
                            <div class="col-sm-2">
                                <input type="hidden" readonly="" name="id" value="<?= $data->id; ?>">
                                <input type="text" name="isales" id="isales" class="form-control input-sm" required="" maxlength="2" onkeyup="gede(this); clearcode(this);" value="<?= $data->i_sales; ?>">
                                <input type="hidden" name="isalesold" id="isalesold" class="form-control input-sm" required="" maxlength="2" onkeyup="gede(this); clearcode(this);" value="<?= $data->i_sales; ?>">
                                <span class="notekode" hidden="true"><b>* Kode Sudah Ada!</b></span>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" name="esales" id="esales" maxlength="100" class="form-control input-sm" required="" onkeyup="gede(this); clearname(this);" value="<?= $data->e_sales; ?>">
                            </div>
                            <div class="col-sm-3">
                                <select name="iarea" id="iarea" class="form-control select2">
                                    <option value="<?=$data->id_area;?>"><?=$data->e_area;?></option>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <select name="irole" id="irole" class="form-control select2">
                                    <option value="<?=$data->i_role;?>"><?=$data->e_role_name;?></option>
                                </select>
                            </div>
                        </div>  
                        <div class="form-group row">       
                            <label class="col-md-3">Kota</label>
                            <label class="col-md-3">Telepon</label>      
                            <label class="col-md-4">Alamat</label>
                            <label class="col-md-2">Kode Pos</label>   
                            <div class="col-sm-3">
                                <input type="text" name="ekota" id="ekota" onkeyup="gede(this); clearname(this);" class="form-control input-sm" value="<?=$data->e_kota;?>">
                            </div>   
                            <div class="col-sm-3">
                                <input type="text" name="etelepon" id="etelepon" class="form-control input-sm" value="<?=$data->e_telepon;?>" >
                            </div>                                                  
                            <div class="col-sm-4">
                                <textarea class="form-control input-sm" name="ealamat"><?=$data->e_alamat;?></textarea>
                            </div>
                            <div class="col-sm-2">
                                <input type="text" name="ekodepos" id="ekodepos" class="form-control input-sm" value="<?=$data->e_kodepos;?>">
                            </div>
                        </div>                        
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return konfirm();"> <i class="fa fa-save"></i>&nbsp;&nbsp;Update</button>&nbsp;
                                <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <span class="note">&nbsp;&nbsp;<b>NOTE :</b></span><br>
                            <span class="note">&nbsp;&nbsp;* Standar Kode terdiri dari 2 (dua) angka</span><br>
                            <span class="note">&nbsp;&nbsp;<b>Contoh : 00</b></span>
                        </div>                        
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $(".select2").select2();
        $( "#earea" ).focus();

        $('#iarea').select2({
            placeholder: 'Pilih Area',
            allowClear: true,
            ajax: {
            url: '<?= base_url($folder.'/cform/area'); ?>',
            dataType: 'json',
            delay: 250,          
            processResults: function (data) {
                return {
                results: data
                };
            },
            cache: true
            }
        }).change(function() {
            cekkode();
        });

        $('#irole').select2({
            placeholder: 'Pilih Role',
            allowClear: true,
            ajax: {
            url: '<?= base_url($folder.'/cform/role'); ?>',
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

    $( "#isales" ).keyup(function() {
        cekkode();
    });

    $("form").submit(function (event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
    });

    function konfirm() {
        if ($('#isales').val()!='' && $('#esales').val()!='' && $('#iarea').val()!='' && $('#iarea').val() && $('#ekota').val()!='' && $('#etelepon').val()!='' && $('#ealamat').val()!='' && $('#ekodepos').val()!='') {
            return true;
        }else{
            swal('Data Masih Ada yang Kosong!');
            return false;
        }
    }

    function cekkode() {
        $.ajax({
            type: "post",
            data: {
                'kode' : $('#isales').val(),
                'kode_old' : $('#isalesold').val(),
                // 'area' : $('#iarea').val()
            },
            url: '<?= base_url($folder.'/cform/cekkodeedit'); ?>',
            dataType: "json",
            success: function (data) {
                if (data==1) {
                    $(".notekode").attr("hidden", false);
                    $("#submit").attr("disabled", true);
                }else{
                    $(".notekode").attr("hidden", true);
                    $("#submit").attr("disabled", false);
                }
            },
            error: function () {
                swal('Error :)');
            }
        });
    }
</script>
