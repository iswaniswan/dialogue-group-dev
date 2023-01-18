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
                        <label class="col-md-3">Kode</label>
                        <label class="col-md-3">Satuan Awal</label>
                        <label class="col-md-3">Satuan Konversi</label>
                        <label class="col-md-3">Angka Faktor</label>
                        <div class="col-sm-3">
                            <input type="text" name="kodekonversi" id="kodekonversi" class="form-control" maxlength="30" onkeyup="gede(this);" value="<?= $data->i_satuan_konversi;?>">
                            <input type="hidden" name="kodekonversiold" id="kodekonversiold" class="form-control" maxlength="30" value="<?= $data->i_satuan_konversi;?>">
                            <input type="hidden" name="id" id="id" class="form-control" maxlength="30" value="<?= $data->id;?>">
                            <span id="cek" hidden="true"> 
                                <font size="3" face="Courier New" color="red">Kode Sudah Ada!</font> 
                            </span>
                        </div>
                        <div class="col-sm-3">
                            <select name="isatuanawal" id="isatuanawal" class="form-control select2">
                                <option value="<?=$data->i_satuan_code;?>" selected="selected"><?=$data->e_satuan_awal;?></option>
                                <?php foreach ($satuan as $isatuanawal):?>
                                    <option value="<?php echo $isatuanawal->i_satuan_code;?>">
                                        <?php echo $isatuanawal->e_satuan_name;?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                        <select name="isatuankonversi" id="isatuankonversi" class="form-control select2">
                            <option value="<?=$data->i_satuan_code_konversi;?>" selected="selected"><?=$data->e_satuan_konversi;?></option>
                            <?php foreach ($satuan as $isatuanawal):?>
                                <option value="<?php echo $isatuanawal->i_satuan_code;?>">
                                        <?php echo $isatuanawal->e_satuan_name;?></option>
                                <?php endforeach; ?>
                        </select> 
                        </div>
                        <div class="col-sm-3">
                            <input type="text" name="nfaktorkonversi" id="nfaktorkonversi" class="form-control" maxlength="30" value="<?= $data->n_angka_faktor_konversi;?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Rumus Konversi</label>
                        <div class="col-sm-6">
                        <select name="irumuskonversi" id="irumuskonversi" class="form-control select2">
                            <option value="<?=$data->i_rumus_konversi;?>" selected="selected"><?=$data->e_rumus_konversi;?></option>
                            <?php foreach($rumuskonversi as $row):?>
                                <option value="<?= $row->i_rumus_konversi; ?>"><?=$row->e_rumus_konversi;?></option>
                            <?php endforeach; ?>
                        </select>
                        </div>
                    </div>  
                    <div class="form-group">
                        <div class="col-sm-offset-8 col-sm-5">
                        <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Update</button>&nbsp;&nbsp;&nbsp;  
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>
                    </div>
                    <div class="form-group">
                        <span style="color: #8B0000"><b>NOTE :</b></span><br>
                        <span style="color: #8B0000">* Standar Kode Konversi Satuan Barang diambil berdasarkan singkatan dari Kode Satuan Awal - Kode Satuan Konversi</span><br><br>
                        <span style="color: #8B0000"><b>Contoh :</b></span><br>
                        <span style="color: #8B0000">* Kilogram ke Gram = KG-GR</span><br>
                        <span style="color: #8B0000">* Centimeter ke Meter = CM-M</span><br>
                        <span style="color: #8B0000">* Kodi ke Pieces = KD-PC</span><br>
                        <span style="color: #8B0000">* dst</span><br>
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
    $(".select2").select2();
 });

 $( "#kodekonversi" ).keyup(function() {
    var kode = $('#kodekonversi').val();
    var kodeold = $('#kodekonversiold').val();
    $.ajax({
        type: "post",
        data: {
            'kode' : kode,
        },
        url: '<?= base_url($folder.'/cform/cekkode'); ?>',
        dataType: "json",
        success: function (data) {
            if (data==1 && kodeold!=kode) {
                $("#cek").attr("hidden", false);
                $("#submit").attr("disabled", true);
            }else{
                $("#cek").attr("hidden", true);
                $("#submit").attr("disabled", false);
            }
        },
        error: function () {
            swal('Error :)');
        }
    });
});

$("form").submit(function (event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
});

$(document).ready(function () {
    $( "#kodekonversi" ).focus();
});
</script>
