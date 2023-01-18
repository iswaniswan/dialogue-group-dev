<link href="<?=base_url();?>assets/plugins/bower_components/icheck/skins/all.css" rel="stylesheet" type="text/css" />
<style type="text/css">
    .tableFixHead { overflow-y: auto; height: 400px; z-index: 10000;}
    .tableFixHead thead th { position: sticky; top: 0; }
    /* Just common table stuff. Really. */
    table  { border-collapse: collapse; width: 100%; }
    th, td { padding: 8px 16px; }
    th     { background:#eee; }
</style>
<!-- <style type="text/css">
    .fixed{
        position: sticky; 
        top: 0;
    }
</style> -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <?php if ($data) {?>
                <div class="panel-body table-responsive">
                    <div id="pesan"></div>
                    <div class="col-sm-5">
                        <p>Departement : <b><?= $row->e_departement_name;?></b></p>
                        <p>Level : <b><?= $row->e_level_name;?></b></p>
                    </div>
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table id="tabledatax" class="table color-table inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="3%;">No</th>
                                        <th class="text-center" width="10%;">Kode Menu</th>
                                        <th class="text-center">Nama Menu</th>
                                        <th class="text-center" width="60%;">Akses Menu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 0; foreach ($data as $kuy) { $i++; ?>
                                        <tr>
                                            <td class="text-center"><?= $i;?></td>
                                            <td><?= $kuy->i_menu;?></td>
                                            <td><?= $kuy->e_menu;?></td>
                                            <td><div class="row"><?php foreach ($userpower as $key) {
                                                if ($kuy->idmis != null) {
                                                    if (strpos($kuy->idmis,$key->id) !== false) {
                                                        $hidden   = '';
                                                    }else{
                                                        $hidden   = 'hidden';
                                                    }
                                                }else{
                                                    $hidden = '';   
                                                }
                                                if ($key->id != null) {
                                                    if (strpos($kuy->id,$key->id) !== false) {
                                                        $checked = 'checked';
                                                    }else{
                                                        $checked = '';
                                                    }
                                                }else{
                                                    $checked = '';   
                                                }?>
                                                <div class="col-sm-2"><label class="custom-control custom-checkbox" <?= $hidden;?>><input readonly="" <?= $checked;?> type="checkbox" name="<?= strtolower($key->e_name);?>" class="custom-control-input"><span class="custom-control-indicator"></span><span class="custom-control-description"><?= $key->e_name;?></span></div><?php 
                                            } ?></div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <div class="white-box">
                <div class="card card-outline-success text-center text-dark">
                    <div class="card-block">
                        <footer>
                            <cite title="Source Title"><b>BELUM ADA ROLE AKSES</b></cite>
                        </footer>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
</div>
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
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
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