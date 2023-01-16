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
                    <div class="form-group row">
                        <?php 
		                    if($isi->d_kum!=''){
                                $tmp=explode('-',$isi->d_kum);
                                $tgl=$tmp[2];
                                $bln=$tmp[1];
                                $thn=$tmp[0];
                                $isi->d_kum=$tgl.'-'.$bln.'-'.$thn;
                            }
		                ?>
                        <label class="col-md-6">Bukti Transfer</label><label class="col-md-6">Tanggal Transfer</label>
                        <div class="col-sm-6">
                            <input type="text" required="" id= "ikum" name="ikum" class="form-control" value="<?php echo $isi->i_kum;?>" readonly>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" readonly id= "dkum" name="dkum" class="form-control" value="<?php echo date("d-m-Y", strtotime($isi->d_kum));?>">
                        </div>
                    </div>    
                    <div class="form-group row">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-12">
                            <input required="" readonly id= "eareaname" name="eareaname" class="form-control" value="<?php echo $isi->e_area_name;?>">
                            <input required="" type="hidden" readonly id= "iarea" name="iarea" class="form-control" value="<?php echo $isi->i_area;?>">
                            <input required="" type="hidden" readonly id= "iareaasal" name="iareaasal" class="form-control" value="<?php echo $isi->i_area;?>">
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-12">Pelanggan</label>
                        <div class="col-sm-12">
                        <?php if($isi->v_sisa>0 && $pst == '00'){?>
                            <select name="icustomer" id="icustomer" class="form-control select2" onchange="getdetailpajak(this.value);">
                                <option value="<?= $isi->i_customer; ?>"><?=$isi->e_customer_name?></option>
                                <?php if ($cust) {                                 
                                    foreach ($cust as $key) { ?>
                                        <option value="<?php echo $key->i_customer;?>"><?php echo $key->e_customer_name;?></option>
                                    <?php } 
                                } ?>
                            </select>
                        <?}else{?>
                                <select name="icustomer" id="icustomer" disabled="true" class="form-control select2" onchange="getdetailpajak(this.value);">
                                <option value="<?= $isi->i_customer; ?>"><?=$isi->e_customer_name?></option>
                                <?php if ($cust) {                                 
                                    foreach ($cust as $key) { ?>
                                        <option value="<?php echo $key->i_customer;?>"><?php echo $key->e_customer_name;?></option>
                                    <?php } 
                                } ?>
                            </select>
                        <?}?>
                        </div>
                    </div>    
                    <div class="form-group row">
                        <label class="col-md-12">Jumlah</label>
                        <div class="col-sm-6">
                            <input style="text-align: right;" required="" readonly id= "vjumlah" name="vjumlah" class="form-control" value="<?php echo number_format($isi->v_jumlah);?>">
                        </div>
                    </div>                         
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-12">
                        <?php if($isi->v_sisa>0 && $pst=='00'){?>
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales();"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan
                            </button>&nbsp;&nbsp;
                       <?php }?>
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
                        <label class="col-md-12">Nama Bank</label>
                        <div class="col-sm-12">
                            <select name="ibank" id="ibank" disabled="true" required="" class="form-control select2">
                                <option value="<?= $isi->i_bank; ?>"><?= $isi->e_bank_name; ?></option>
                                <?php if ($bank) {                                 
                                    foreach ($bank as $key) { ?>
                                        <option value="<?php echo $key->i_bank;?>"><?= $key->i_bank." - ".$key->e_bank_name;?></option>
                                    <?php } 
                                } ?>
                            </select>
                            <input type="hidden" name="icustomergroupar" id="icustomergroupar" value="<?php echo $isi->i_customer_groupar; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Salesman</label>
                        <div class="col-sm-12">
                            <input name="esalesmanname" id="esalesmanname" readonly value="<?php echo $isi->e_salesman_name; ?>" class="form-control">
                            <input type="hidden" name="isalesman" id="isalesman" value="<?php echo $isi->i_salesman; ?>">     
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                        <?php if($isi->v_sisa>0 && $pst=='00'){?>
                            <input name="eremark" id="eremark"  class="form-control" value="<?php echo $isi->e_remark; ?>">
                        <?}else{
                                echo "<input id=\"eremark\" readonly name=\"eremark\" class=\"form-control\" value=\"$isi->e_remark\">";
                        }?>
                        </div>
                    </div>  
                    <div class="form-group row">
                        <label class="col-md-12">Sisa</label>
                        <div class="col-sm-6">
                            <input style="text-align: right;" required="" readonly id= "vsisa" name="vsisa" class="form-control" value="<?php echo number_format($isi->v_sisa);?>">
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