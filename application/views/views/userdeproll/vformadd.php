<link href="<?=base_url();?>assets/plugins/bower_components/icheck/skins/all.css" rel="stylesheet" type="text/css" />
<?= $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-6">Departement</label>
                        <label class="col-md-6">level</label>
                        <div class="col-sm-6">
                            <select required="" data-placeholder="Pilih Departement" name="idept" id="idept" class="form-control select2">
                                <option value=""></option>
                                <?php if ($depart) {
                                    foreach ($depart as $row): ?>
                                        <option value="<?= $row->i_departement; ?>"><?= $row->e_departement_name;?></option>
                                    <?php endforeach; 
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <select required="" name="ilevel" id="ilevel" class="form-control select2">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Update</button>&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-6">Menu</label>
                        <label class="col-md-6">Sub Menu</label>
                        <div class="col-sm-6">
                            <select data-placeholder="Pilih Menu" required="" name="imenu" id="imenu" class="form-control select2">
                                <option value=""></option>
                                <?php if ($menu) {
                                    foreach ($menu as $imenu): ?>
                                        <option value="<?= $imenu->i_menu; ?>"><?= $imenu->e_menu;?></option>
                                    <?php endforeach; 
                                }?>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <select name="isubmenu" id="isubmenu" class="form-control select2">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <span class="note">&nbsp;&nbsp;<b>NOTE :</b></span><br>
                        <span class="note">&nbsp;&nbsp;* Pilih Departement & Level terlebih dahulu!!!</span>
                    </div>                        
                </div>
            </div>
        </div>
    </div>
</div>
<div class="white-box" id="detail">
    <div class="col-sm-5">
        <h3 class="box-title m-b-0">Detail Menu</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" width="3%;">No</th>
                        <th class="text-center" width="10%;">Kode Menu</th>
                        <th class="text-center">Nama Menu</th>
                        <th class="text-center" width="60%;">Akses Menu</th>
                        <th class="text-center" width="2%;">
                            <label class="custom-control text-center custom-checkbox">
                                <input type="checkbox" id="cekbox" name="cekbox" class="custom-control-input">
                                <span class="custom-control-indicator"></span>
                                <span class="custom-control-description"></span>
                            </label>
                        </th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    <input type="hidden" name="jml" id="jml" value="">
    <input type="hidden" name="jmldetail" id="jmldetail" value="">
</div>
</form>
<script src="<?=base_url();?>assets/plugins/bower_components/icheck/icheck.min.js"></script>
<script>
    $('#idept').change(function(event) {
        $('#ilevel').val('');
        $('#ilevel').html('');
        $('#imenu').val('');
        $('#imenu').html('');
        $('#isubmenu').val('');
        $('#isubmenu').html('');
        $("#tabledatax tbody").remove();
    });

    $("form").submit(function (event) {
        event.preventDefault();
        //$("input").attr("disabled", true);
        //$("select").attr("disabled", true);
        //$("#submit").attr("disabled", true);
    });

    $("#cekbox").click(function(){
        $('input:checkbox').not(this).prop('checked', this.checked);
    });

    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date');

        $('#ilevel').select2({
            placeholder: 'Pilih Level',
            width: '100%',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/level'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q : params.term,
                        i_departement : $('#idept').val(),
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data,
                    };
                },
                cache: false
            }
        })

        $('#imenu').select2({
            placeholder: 'Pilih Menu',
            width: '100%',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/menu'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q : params.term,
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data,
                    };
                },
                cache: false
            }
        })

        $('#isubmenu').select2({
            placeholder: 'Pilih Sub Menu',
            width: '100%',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/submenu'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q : params.term,
                        i_menu : $('#imenu').val(),
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data,
                    };
                },
                cache: false
            }
        })
    });

    $('#imenu').change(function(event) {
        $('#isubmenu').val('');
        $('#isubmenu').html('');
        getmenu();
    });

    $('#isubmenu').change(function(event) {
        getmenu();
    });

    function getmenu() {
        $.ajax({
            type: "post",
            data: {
                'dep'       : $('#idept').val(),
                'lev'       : $('#ilevel').val(),
                'imenu'     : $('#imenu').val(),
                'isubmenu'  : $('#isubmenu').val(),
            },
            url: '<?= base_url($folder.'/cform/getmenu'); ?>',
            dataType: "json",
            success: function (data) {
                $("#tabledatax tbody").remove();
                for (let a = 0; a < data['menu'].length; a++) {
                    var no = a+1;
                    $('#jml').val(no);
                    var imenu  = data['menu'][a]['i_menu'];
                    var emenu  = data['menu'][a]['e_menu'];
                    var id     = data['menu'][a]['id'];
                    var idmis  = data['menu'][a]['idmis'];
                    var cols   = "";
                    var newRow = $("<tr>");
                    cols += '<td class="text-center">'+no+'</td>';
                    cols += '<td><input readonly class="form-control input-sm" type="text" id="i_menu'+no+'" name="i_menu'+no+'" value="'+imenu+'"></td>';
                    cols += '<td><input readonly class="form-control input-sm" type="text" id="emenu'+no+'" name="emenu'+no+'" value="'+emenu+'"></td>';
                    cols += '<td class="cekitem'+no+'"><div class="row"><?php foreach ($userpower as $key) {?>';
                    var string = '<?=$key->id?>';
                    if (idmis != null) {
                        if (idmis.indexOf(string) !== -1) {
                            var hidden = '';
                            var disabled = '';
                        }else{
                            var disabled = 'disabled';
                            var hidden = 'hidden';
                        }
                    }else{
                        var hidden = '';   
                    }
                    if (id != null) {
                        if (id.indexOf(string) !== -1) {
                            var checked = 'checked';
                        }else{
                            var checked = '';
                        }
                    }else{
                        var checked = '';   
                    }
                    cols += '<div class="col-sm-2"><label class="custom-control custom-checkbox" '+hidden+'><input '+disabled+' '+checked+' type="checkbox" name="<?= strtolower($key->e_name);?>'+no+'" class="custom-control-input"><span class="custom-control-indicator"></span><span class="custom-control-description"><?= $key->e_name;?></span></div><?php } ?></div></td>';
                    cols +='<td class="text-center"><label class="custom-control custom-checkbox"><input type="checkbox" id="cekrow'+no+'" name="cekrow[]" class="custom-control-input"><span class="custom-control-indicator"></span><span class="custom-control-description"></span></td>';
                    newRow.append(cols);
                    $("#tabledatax").append(newRow);
                    $("#cekrow"+no).click(function(){
                        $(this).parents('tr').find(':checkbox').prop('checked', this.checked);
                    });
                }
            },
            error: function () {
                swal('Error :)');
            }
        });
        no = $('#jml').val();
    }
</script>