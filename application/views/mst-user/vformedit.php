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
                        <label class="col-md-3">Username</label>
                        <label class="col-md-3">Nama Karyawan / User</label>
                        <label class="col-md-3">Password Lama</label>
                        <label class="col-md-3">Password Baru</label>
                        <div class="col-sm-3">
                            <input type="text" name="iuser" class="form-control" required="" readonly="" value="<?= $data->username; ?>">
                            <input type="hidden" name="iuserold" class="form-control" required=""  value="<?= $data->username; ?>">
                            <input type="hidden" name="idcompany" class="form-control" required=""  value="<?= $data->id_company; ?>">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" name="eusername" onkeyup="clearname(this);" class="form-control" value="<?= $data->e_name; ?>">
                        </div>
                        <div class="col-sm-3">
                            <input type="hidden" name="pasworold" class="form-control" value="<?= $data->e_password;?> ">
                            <input type="password" name="passwordoldd" class="form-control" value="" >
                        </div>
                        <div class="col-sm-3">
                            <input type="password" name="passwordnew" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">User Area <input type="checkbox" id="user_area_checkbox"> <span class="text-muted">Pilih Semua</span></label>
                        <label class="col-md-4">User Salesman <input type="checkbox" id="user_salesman_checkbox"> <span class="text-muted">Pilih Semua</span></label>
                        <label class="col-md-4">User Kas RV <input type="checkbox" id="user_kas_rv_checkbox"> <span class="text-muted">Pilih Semua</span></label>
                        <div class="col-sm-4">
                            <select name="iuser_area[]" id="iuser_area" class="form-control select2" multiple="multiple">
                                <?php foreach ($userarea as $iuserarea):?>
                                    <option value="<?php echo $iuserarea->i_area;?>|<?= $iuserarea->id ?>" <?= (!empty($data->iuser_area) && in_array($iuserarea->i_area . '|' . $iuserarea->id, json_decode($data->iuser_area))) ? 'selected' : '' ?>><?= $iuserarea->e_area;?></option>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" id="userareatotal" value="<?= count($userarea); ?>">
                        </div>
                        <div class="col-sm-4">
                            <select name="iuser_salesman[]" id="iuser_salesman" class="form-control select2" multiple="multiple">
                                <?php foreach ($usersalesman as $iusersalesman):?>
                                    <option value="<?php echo $iusersalesman->id;?>" <?= (!empty($data->id_salesman) && in_array($iusersalesman->id, json_decode($data->id_salesman))) ? 'selected' : '' ?>><?= $iusersalesman->e_sales;?></option>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" id="usersalesmantotal" value="<?= count($usersalesman); ?>">
                        </div>
                        <div class="col-sm-4">
                            <select name="iuser_kas_rv[]" id="iuser_kas_rv" class="form-control select2" multiple="multiple">
                                <?php foreach ($userkasrv as $iuserkasrv):?>
                                    <option value="<?php echo $iuserkasrv->i_rv_type;?>" <?= (!empty($data->i_rv_type) && in_array($iuserkasrv->i_rv_type, json_decode($data->i_rv_type))) ? 'selected' : '' ?>><?= $iuserkasrv->e_rv_type_name;?></option>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" id="userkasrvtotal" value="<?= count($userkasrv); ?>">
                        </div> 
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-3 col-sm-12">
                            <button type="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Update</button>&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                            <span align="justify"><font face="Courier New" color="red" size="3">*Note : Isi Password Lama dan Password baru  </font></span>
                        </div>
                    </div>
                </div>
                <!-- <div class="col-md-6">
                    <div class="form-group row">
                    </div>
                </div> -->
            </form>
        </div>
    </div>
</div>
<!-- <div class="col-sm-4">
    <select name="iuser_area[]" id="iuser_area" class="form-control select2" multiple="multiple">
        <?php foreach (json_decode($data->iuser_area) as $key=>$iuserarea):?>
            <option value="<?php echo $iuserarea;?>" selected><?= json_decode($data->e_area)[$key];?></option>
        <?php endforeach; ?>
    </select>
</div>
<div class="col-sm-4">
    <select name="iuser_salesman[]" id="iuser_salesman" class="form-control select2" multiple="multiple">
        <?php foreach (json_decode($data->id_salesman) as $key=>$iusersalesman):?>
            <option value="<?php echo $iusersalesman;?>" selected><?= json_decode($data->e_sales)[$key];?></option>
        <?php endforeach; ?>
    </select>
</div>
<div class="col-sm-4">
    <select name="iuser_kas_rv[]" id="iuser_kas_rv" class="form-control select2" multiple="multiple">
        <?php foreach (json_decode($data->i_rv_type) as $key=>$iuserkasrv):?>
            <option value="<?php echo $iuserkasrv;?>" selected><?= json_decode($data->e_rv_type_name)[$key];?></option>
        <?php endforeach; ?>
    </select>
</div>  -->
<script>
    $(document).ready(function () {
        $(".select2").select2();
        checkselectall($('#iuser_area').val()?.length, $('#userareatotal').val(), $('#user_area_checkbox'));
        checkselectall($('#iuser_salesman').val()?.length, $('#usersalesmantotal').val(), $('#user_salesman_checkbox'));
        checkselectall($('#iuser_kas_rv').val()?.length, $('#userkasrvtotal').val(), $('#user_kas_rv_checkbox'));
        $("#user_area_checkbox").click(function(){
            ischeck(this, 'iuser_area');
        });

        $("#user_salesman_checkbox").click(function(){
            ischeck(this, 'iuser_salesman');
        });

        $("#user_kas_rv_checkbox").click(function(){
            ischeck(this, 'iuser_kas_rv');
        });
    });

    function ischeck(checkbox, select)
    {
        const selectAll = $(checkbox).is(':checked');
        if (selectAll) {
            $(`#${select} > option`).prop("selected","selected");
        } else {
            $(`#${select}`).val(null).trigger('change');
        }
        $(`#${select}`).trigger("change");
    }

    function checkselectall(select, total, checkbox)
    {
        if(select == total) {
            checkbox.prop('checked', true);
        }
    }
</script>