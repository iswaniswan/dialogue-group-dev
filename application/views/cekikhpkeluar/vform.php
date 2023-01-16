<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-refresh"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-6">Nomor Bukti</label><label class="col-md-6">Tanggal Bukti</label>
                        <div class="col-sm-6">
                            <input id="ibukti" name="ibukti" class="form-control" required="" readonly value="<?= $isi->i_bukti;?>">
                            <input type="hidden" name="iikhp" id="iikhp" value="<?= $isi->i_ikhp; ?>">
                        </div>
                        <div class="col-sm-6">
                            <input id= "dbukti" name="dbukti" class="form-control date" required="" readonly value="<?= date('d-m-Y', strtotime($isi->d_bukti));?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan Cek</label>
                        <div class="col-sm-12">
                            <textarea id="ecek1" class="form-control" name="ecek1"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-8">
                            <?php if ($isi->d_cek!=null){ echo "sudah dicek "; }else{?>
                                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales();"> <i class="fa fa-save"></i>&nbsp;&nbsp;Dicek</button>
                            <?php } ?>
                            &nbsp;&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/view/<?= $dfrom."/".$dto."/".$iarea;?>/","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">                    
                    <div class="form-group row">
                        <label class="col-md-6">Area</label><label class="col-md-6">Uraian</label>
                        <div class="col-sm-6">
                            <select name="iarea" id="iarea" class="form-control select2" required="">
                                <?php if ($area) {
                                    foreach ($area as $key) { ?>
                                        <option value="<?= $key->i_area;?>" <?php if ($key->i_area==$isi->i_area) {
                                            echo "selected";} ?>><?= $key->i_area." - ".$key->e_area_name;?>
                                        </option> 
                                    <?php }
                                } ?>   
                            </select>
                            <input type="hidden" name="icoa" id="icoa" value="<?php echo $isi->i_coa; ?>">
                            <input type="hidden" name="ecoaname" id="ecoaname" value="<?php echo $isi->e_coa_name; ?>">
                        </div>
                        <div class="col-sm-6">
                            <select name="iikhptype" id="iikhptype" class="form-control select2" required="">
                                <?php if ($urai) {
                                    foreach ($urai as $key) { ?>
                                        <option value="<?= $key->i_ikhp_type;?>" <?php if ($key->i_ikhp_type==$isi->i_ikhp_type) {
                                            echo "selected";} ?>><?= $key->e_ikhp_typename;?>
                                        </option> 
                                    <?php }
                                } ?>   
                            </select>
                            <input type="hidden" id= "eikhptypename" name="eikhptypename" class="form-control date" required="" readonly value="<?= $isi->e_ikhp_typename;?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">Terima Tunai</label><label class="col-md-6">Terima Giro</label>
                        <div class="col-sm-6">
                            <input id="vterimatunai" name="vterimatunai" class="form-control" required="" readonly value="<?= number_format($isi->v_terima_tunai);?>" onkeypress="return hanyaAngka(event);" onkeyup="reformat(this);">
                        </div>
                        <div class="col-sm-6">
                            <input id="vterimagiro" name="vterimagiro" class="form-control" required="" readonly value="<?= number_format($isi->v_terima_giro);?>" onkeypress="return hanyaAngka(event);" onkeyup="reformat(this);">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">Keluar Tunai</label><label class="col-md-6">Keluar Giro</label>
                        <div class="col-sm-6">
                            <input id="vkeluartunai" name="vkeluartunai" class="form-control" required="" readonly value="<?= number_format($isi->v_keluar_tunai);?>" onkeypress="return hanyaAngka(event);" onkeyup="reformat(this);">
                        </div>
                        <div class="col-sm-6">
                            <input id="vkeluargiro" name="vkeluargiro" class="form-control" required="" readonly value="<?= number_format($isi->v_keluar_giro);?>" onkeypress="return hanyaAngka(event);" onkeyup="reformat(this);">
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
        $('.select2').select2();
    });

    function dipales(){
        if(($("#dbukti").val() == '') || ($("#eikhptypename").val() == '') || ($("#iarea").val() == '') || ( ($("#vterimatunai").val()=='') && ($("#vterimagiro").val()=='') && ($("#vkeluartunai").val()=='') && ($("#vkeluargiro").val()=='') )) {
            swal('Data header masih ada yang salah !!!');
            return false;
        }else{
            return true;
        }
    }

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
    });
</script>