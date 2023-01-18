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
		                    foreach($isi as $row){ 
		                    	$tmp=explode('-',$isi->d_kn);
		                    	$tgl=$tmp[2];
		                    	$bln=$tmp[1];
		                    	$thn=$tmp[0];
		                    	$isi->d_kn=$tgl.'-'.$bln.'-'.$thn;
                            if($isi->d_pajak){
	                            $tmp=explode('-',$isi->d_pajak);
	                            $tgl=$tmp[2];
	                            $bln=$tmp[1];
	                            $thn=$tmp[0];
	                            $isi->d_pajak=$tgl.'-'.$bln.'-'.$thn;
                            }
                        }
		                ?>
                        <label class="col-md-6">Kredit Nota</label><label class="col-md-6">Tanggal Kredit Nota</label>
                        <div class="col-sm-6">
                            <input type="text" required="" id= "ikn" name="ikn" class="form-control" value="<?php echo $isi->i_kn;?>" readonly>
                        </div>
                        <div class="col-sm-6">
                        <?php if($isi->v_netto == $isi->v_sisa){?>
                            <input type="text" readonly id= "dkn" name="dkn" class="form-control date" value="<?php echo date("d-m-Y", strtotime($isi->d_kn));?>">
                        <?}else{?>
                            <input type="text" readonly id= "dkn" name="dkn" class="form-control" value="<?php echo date("d-m-Y", strtotime($isi->d_kn));?>">
                       <? }?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <?php 
			                if(!empty($isi->d_refference) || $isi->d_refference!=''){
			                	$tmp=explode("-",$isi->d_refference);
			                	$th=$tmp[0];
			                	$bl=$tmp[1];
			                	$hr=substr($tmp[2],0,2);
			                	$isi->drefference=$hr."-".$bl."-".$th;
			                }else{
			                	$isi->drefference='';
			                }
		                ?>
                        <label class="col-md-6">No. Refferensi</label><label class="col-md-6">Tanggal Referensi</label>
                        <div class="col-sm-6">
                            <?php if($isi->v_netto == $isi->v_sisa){?>
                                <select name="irefference" id="irefference" class="form-control select2" onchange="getdetailref(this.value);">
                                        <option value="<?= $isi->i_refference; ?>"><?=$isi->i_refference?></option>
                                        <?php if ($refference) {                                 
                                            foreach ($refference as $key) { ?>
                                                <option value="<?php echo $key->i_bbm;?>"><?php echo $key->i_bbm;?></option>
                                            <?php } 
                                        } ?>
                                    </select>
                            <?}else{?>
                                <select name="irefference" id="irefference" disabled="true" class="form-control select2" onchange="getdetailref(this.value);">
                                    <option value="<?= $isi->i_refference; ?>"><?=$isi->i_refference?></option>
                                    <?php if ($refference) {                                 
                                            foreach ($refference as $key) { ?>
                                                <option value="<?php echo $key->i_bbm;?>"><?php echo $key->i_bbm;?></option>
                                            <?php } 
                                    } ?>
                                </select>
                            <?}?>
                        </div>
                        <div class="col-sm-6">
                            <?php if($isi->v_netto == $isi->v_sisa){?>
                                <input type="text" readonly id= "drefference" name="drefference" class="form-control date" value="<?php echo date("d-m-Y", strtotime($isi->d_refference));?>">
                            <?}else{?>
                                <input type="text" readonly id= "drefference" name="drefference" class="form-control" value="<?php echo date("d-m-Y", strtotime($isi->d_refference));?>">
                            <? }?>
                        </div>
                    </div>          
                    <div class="form-group row">
                        <label class="col-md-6">Nilai Kotor</label><label class="col-md-6">Nilai Potongan</label>
                        <div class="col-sm-6">
                        <?php if($isi->v_netto == $isi->v_sisa){?>
                                <input type="text"  style="text-align: right;"  id= "vgross" name="vgross" class="form-control" value="<?php echo number_format($isi->v_gross);?>">
                           <? }else{?>
                                <input type="text"  style="text-align: right;"  readonly id= "vgross" name="vgross" class="form-control" value="<?php echo number_format($isi->v_gross);?>">
                            <?}?>
                        </div>
                        <div class="col-sm-6">
                            <input style="text-align: right;" type="text" required="" id= "vdiscount" name="vdiscount" class="form-control" value="<?php echo number_format($isi->v_discount);?>"  readonly>
                            <input type="hidden" name="ncustomerdiscount1" id="ncustomerdiscount1" value="0">
		                    <input type="hidden" name="ncustomerdiscount2" id="ncustomerdiscount2" value="0">
		                    <input type="hidden" name="ncustomerdiscount3" id="ncustomerdiscount3" value="0">
		                    <input type="hidden" name="vcustomerdiscount1" id="vcustomerdiscount1" value="0">
		                    <input type="hidden" name="vcustomerdiscount2" id="vcustomerdiscount2" value="0">
		                    <input type="hidden" name="vcustomerdiscount2" id="vcustomerdiscount3" value="0">
                        </div>
                    </div>          
                    <div class="form-group row">
                        <label class="col-md-6">Nilai Bersih</label><label class="col-md-6">Nilai Sisa</label>
                        <div class="col-sm-6">
                            <input style="text-align: right;" required="" readonly id= "vnetto" name="vnetto" class="form-control" value="<?php echo number_format($isi->v_netto);?>">
                        </div>
                        <div class="col-sm-6">
                            <input style="text-align: right;" required="" readonly id= "vsisa" name="vsisa" class="form-control" value="<?php echo number_format($isi->v_sisa);?>">
                        </div>
                    </div>           
                    <div class="form-group row"> 
                        <div class="col-md-6">
                            <div class="form-check">
                                <label class="custom-control custom-checkbox">
                                    <input type="checkbox" id="finsentif" name="finsentif" class="custom-control-input" checked>
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">Insentif</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <label class="custom-control custom-checkbox">
                                    <input type="checkbox" id="fmasalah" name="fmasalah" class="custom-control-input">
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">Masalah</span>
                                </label>
                            </div>
                        </div>
                    </div>                 
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-12">
                        <?php if($isi->v_netto==$isi->v_sisa && $isi->f_kn_cancel!='t'){?>
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
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-12">
                        <?php if($isi->v_netto == $isi->v_sisa){?>
                            <select name="iarea" id="iarea" required="" class="form-control select2" onchange="getarea(this.value);">
                                <option value="<?= $isi->i_area; ?>"><?= $isi->e_area_name; ?></option>
                                <?php if ($area) {                                 
                                    foreach ($area as $key) { ?>
                                        <option value="<?php echo $key->i_area;?>"><?= $key->i_area." - ".$key->e_area_name;?></option>
                                    <?php } 
                                } ?>
                            </select>
                        <?}else{
                                echo "<input id=\"eareaname\" disabled=\"true\" name=\"eareaname\" class=\"form-control\" value=\"$isi->e_area_name\">";
                        }?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Pelanggan</label>
                        <div class="col-sm-12">
                            <input name="ecustomername" id="ecustomername" readonly value="<?php echo $isi->e_customer_name; ?>" class="form-control">
                            <input type="hidden" name="icustomer" id="icustomer" value="<?php echo $isi->i_customer; ?>">
                            <input type="hidden" name="icustomergroupar" id="icustomergroupar" value="<?php echo $isi->i_customer_groupar; ?>">
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-12">Alamat</label>
                        <div class="col-sm-12">
                            <input  name="ecustomeraddress" id="ecustomeraddress" class="form-control" value="<?php echo $isi->e_customer_address; ?>" readonly>
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-12">Salesman</label>
                        <div class="col-sm-12">
                            <input name="esalesmanname" id="esalesmanname" class="form-control" readonly value="<?php echo $isi->e_salesman_name; ?>">
                            <input type="hidden" name="isalesman" id="isalesman" value="<?php echo $isi->i_salesman; ?>">
                        </div>
                    </div>                   
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                        <?php if($isi->v_netto == $isi->v_sisa){?>
                            <input name="eremark" id="eremark" class="form-control" value="<?php echo $isi->e_remark; ?>">
                        <?}else{
                                echo "<input id=\"eremark\" readonly name=\"eremark\" class=\"form-control\" value=\"$isi->e_remark\">";
                        }?>
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

    function getarea(kode) {
        if (kode!='') {
            $("#irefference").attr("disabled", false);
        }else{
            $("#irefference").attr("disabled", true);
        }
        $('#irefference').html('');
        $('#irefference').val('');
    }

    function getdetailref(kode) {
        $("#tabledata tr:gt(0)").remove();       
        $("#jml").val(0);
        var iarea  = $('#iarea').val();
        $.ajax({
            type: "post",
            data: {
                'ibbm': kode,
                'iarea': iarea
            },
            url: '<?= base_url($folder.'/cform/getdetailref'); ?>',
            dataType: "json",
            success: function (data) {
                $('#drefference').val(data['data'][0].d_bbm); 
                $('#icustomer').val(data['data'][0].i_customer); 
                $('#icustomergroupar').val(data['data'][0].i_customer_groupar); 
                $('#ecustomername').val(data['data'][0].e_customer_name); 
                $('#isalesman').val(data['data'][0].i_salesman); 
                $('#esalesmanname').val(data['data'][0].e_salesman_name);
                $('#ecustomeraddress').val(data['data'][0].e_customer_address); 
                $('#vgross').val(formatcemua(data['data'][0].v_ttb_gross)); 
                $('#vttbgross').val(data['data'][0].v_ttb_gross); 
                $('#vsisa').val(formatcemua(data['data'][0].v_ttb_sisa)); 
                $('#vnetto').val(formatcemua(data['data'][0].v_ttb_netto)); 
                $('#vttbnetto').val(data['data'][0].v_ttb_netto);
                $('#vdiscount').val(formatcemua(data['data'][0].v_ttb_discounttotal)); 
                $('#vttbdiscounttotal').val(data['data'][0].v_ttb_discounttotal); 
                $('#nttbdiscount1').val(data['data'][0].n_ttb_discount1);
                $('#nttbdiscount2').val(data['data'][0].n_ttb_discount2);
                $('#nttbdiscount3').val(data['data'][0].n_ttb_discount3);
                $('#vttbdiscount1').val(data['data'][0].v_ttb_discount1);
                $('#vttbdiscount2').val(data['data'][0].v_ttb_discount2);
                $('#vttbdiscount3').val(data['data'][0].v_ttb_discount3);
                $('#jml').val(data['detail'].length);
            },
            error: function () {
                alert('Error :)');
            }
        });
    }

    $(document).ready(function () {
        /*ngetang(0);*/
        $('.select2').select2();
        showCalendar('.date');
        $('#iarea').select2({
            placeholder: 'Pilih Area'
        });

        $('#irefference').select2({
            placeholder: 'Cari Berdasarkan BBM',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getreferensi/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var iarea  = $('#iarea').val();
                    var query = {
                        q: params.term,
                        iarea: iarea
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
    });

    function dipales(){
        if((document.getElementById("dkn").value=='') || (document.getElementById("iarea").value=='') || (document.getElementById("irefference").value=='') || (document.getElementById("finsentif").checked==false)){
            swal("Data Header belum lengkap !!!");
            return false;
        }else{          
            return true;
        }
    }
</script>