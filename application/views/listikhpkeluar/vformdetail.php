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
                <div id="pesan">
                </div>
                <div class="col-md-6">
                    <div id="pesan"></div>
                    <div class="form-group row">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-6">
                            <select name="iarea" id="iarea" required="" class="form-control select2" onchange="getarea(this.value);">
                                <option value="<?= $isi->i_area; ?>"><?= $isi->e_area_name; ?></option>
                                <?php if ($area) {                                 
                                    foreach ($area as $key) { ?>
                                        <option value="<?php echo $key->i_area;?>"><?= $key->i_area." - ".$key->e_area_name;?></option>
                                    <?php } 
                                } ?>
                            </select>
                            <input type="hidden" id="eareaname" name="eareaname" value="<?php echo $isi->e_area_name; ?>" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Tanggal Bukti</label>
                            <?php 
			                    $tmp=explode("-",$isi->d_bukti);
			                    $th =$tmp[0];
			                    $bl =$tmp[1];
			                    $hr =$tmp[2];
			                    $isi->d_bukti=$hr."-".$bl."-".$th;
		                    ?>
                            <div class="col-sm-3">
                                <input readonly class="form-control date" id="dbukti" name="dbukti" value="<?php echo date("d-m-Y", strtotime($isi->d_bukti));?>">
                            </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">No Bukti</label>
                        <div class="col-sm-6">
                            <input type="text" id="ibukti" class="form-control" name="ibukti" value="<?php echo $isi->i_bukti;?>" maxlength=13>
                            <input type="hidden" id="iikhp" class="form-control" name="iikhp" value="<?php echo $isi->i_ikhp;?>" maxlength=13>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Uraian</label>
                        <div class="col-sm-6">
                            <select name="iikhptype" id="iikhptype" required="" class="form-control select2">
                                <option value="<?= $isi->i_ikhp_type; ?>"><?= $isi->e_ikhp_typename; ?></option>
                                <?php if ($iikhptype) {                                 
                                    foreach ($iikhptype as $key) { ?>
                                        <option value="<?php echo $key->i_ikhp_type;?>"><?= $key->i_ikhp_type." - ".$key->e_ikhp_typename;?></option>
                                    <?php } 
                                } ?>
                            </select>
							<input type="hidden" name="icoa" id="icoa" class="form-control" value="<?php echo $isi->i_coa; ?>">
							<input type="hidden" name="ecoaname" id="ecoaname" class="form-control" value="<?php echo $isi->e_coa_name; ?>">
							<input type="hidden" name="eikhptypename" id="eikhptypename" class="form-control" value="<?php echo $isi->e_ikhp_typename; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales(parseFloat(document.getElementById('jml').value));"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                            &nbsp;&nbsp;
                            <?php
                                $tmp 	= explode("-", $dfrom);
                                $det	= $tmp[0];
                                $mon	= $tmp[1];
                                $yir 	= $tmp[2];
                                $dfrom	= $yir."-".$mon."-".$det;
                                $tmp 	= explode("-", $dto);
                                $det	= $tmp[0];
                                $mon	= $tmp[1];
                                $yir 	= $tmp[2];
                                $dto	= $yir."-".$mon."-".$det;
                            ?>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/view/<?= $dfrom."/".$dto."/".$iarea."/";?>","#main")'> <i
                                class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-12">Terima Tunai</label>
                        <div class="col-sm-6">
                            <input type="text" id="vterimatunai" name="vterimatunai" class="form-control" value="<?php echo number_format($isi->v_terima_tunai); ?>" onkeyup="reformat(this);">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Terima Giro</label>
                        <div class="col-sm-6">
                            <input type="text" id="vterimagiro" name="vterimagiro" class="form-control" value="<?php echo number_format($isi->v_terima_giro); ?>" onkeyup="reformat(this);">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keluar Tunai</label>
                        <div class="col-sm-6">
                            <input type="text" id="vkeluartunai" name="vkeluartunai" class="form-control" value="<?php echo number_format($isi->v_keluar_tunai); ?>" onkeyup="reformat(this);">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keluar Giro</label>
                        <div class="col-sm-6">
                            <input type="text" id="vkeluargiro" name="vkeluargiro" class="form-control" value="<?php echo number_format($isi->v_keluar_giro); ?>" onkeyup="reformat(this);">
                        </div>
                    </div>
                </div>
                <input type="hidden" name="jml" id="jml" value="0">
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
            $('.select2').select2();
            showCalendar('.date');
    });

    function dipales(){
		if(
			(document.getElementById("ikum").value=='')||
			(document.getElementById("dkum").value=='')
		  )
		{
			alert("Data Header belum lengkap !!!");
		}else{			
			document.getElementById("login").disabled=true;
		}
	}

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
    });

</script>