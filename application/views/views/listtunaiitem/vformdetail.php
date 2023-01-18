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
                <div id="pesan"></div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label class="col-md-12">Nomor</label>
                    <div class="col-sm-6">
                       <input readonly class="form-control" name="itunai" id="itunai" value="<?= $isi->i_tunai; ?>" maxlength="10">
                   </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-12">Tanggal Terima</label>
                    <?php 
		                $tmp=explode('-',$isi->d_tunai);
		                $yy=$tmp[0];
		                $mm=$tmp[1];
		                $dd=$tmp[2];
		                $isi->d_tunai=$dd.'-'.$mm.'-'.$yy;
		            ?>
                    <div class="col-sm-3">
                        <input class="form-control date" readonly name="dtunai" id="dtunai" value="<?= $isi->d_tunai; ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-12">Area</label>
                    <div class="col-sm-6">
                        <select name="iarea" id="iarea" class="form-control select2" required="">
                            <option value="<?= $isi->i_area ?>"><?= $isi->e_area_name ?></option>
                            <?php if ($area) {
                                foreach ($area as $key) { ?>
                                    <option value="<?php echo $key->i_area;?>"><?php echo $key->i_area." - ".$key->e_area_name;?></option> 
                                <?php }
                            } ?>   
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-12">Pelanggan</label>
                    <div class="col-sm-6">
                        <select name="icustomer" id="icustomer" class="form-control select2" required="">
                            <option value="<?= $isi->i_customer; ?>"><?= $isi->e_customer_name ?></option>
                            <?php if ($icustomer) {
                                foreach ($icustomer as $key) { ?>
                                    <option value="<?php echo $key->i_customer;?>"><?php echo $key->i_customer." - ".$key->e_customer_name;?></option> 
                                <?php }
                            } ?>   
                        </select>
                        <input type="hidden" name="icustomergroupar" id="icustomergroupar" value="">
                        <input type="hidden" name="ecustomername" id="ecustomername" value="">
                    </div>
                </div>     
                <div class="form-group row">
                    <div class="col-sm-offset-5 col-sm-8">
                    <?php 
                    if($iperiode <= $dtunai){
                        $bisaedit=true;
                    }
                    if($bisaedit){
                        if($isi->v_jumlah == $isi->v_sisa){?>
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales(parseFloat(document.getElementById('jml').value));"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                            &nbsp;&nbsp;
                            <button type="button" class="btn btn-success btn-rounded btn-sm" id="addrow""> <i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah Item</button>
                            &nbsp;&nbsp;
                        <?php
                            }?>
                    <?php
                        }?>
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
                        <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/view/<?= $dfrom."/".$dto."/".$iarea."/"; ?>","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                    </div>
                </div>
            </div>
            <div class="col-md-6"> 
                <div class="form-group row">
                    <label class="col-md-12">Salesman</label>
                    <div class="col-sm-6">
                        <input class="form-control" name="esalesmanname" id="esalesmanname" readonly="" required="" value="<?= $isi->e_salesman_name; ?>">
                        <input class="form-control" type="hidden" name="isalesman" id="isalesman" readonly="" required="" value="<?= $isi->i_salesman; ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-12">Keterangan</label>
                    <div class="col-sm-6">
                        <input class="form-control" name="eremark" id="eremark" value="<?php echo $isi->e_remark; ?>">
                    </div>
                </div>                   
                <div class="form-group row">
                    <label class="col-md-12">Jumlah</label>
                    <div class="col-sm-6">
                        <input readonly  type="hidden" name="vsisa" id="vsisa" class="form-control" value="<?php echo number_format($isi->v_sisa); ?>" onkeyup="reformat(this);hetang(this);">
                        <input readonly type="text" name="vjumlah" id="vjumlah" class="form-control" value="<?php echo number_format($isi->v_jumlah);?>">
                    </div>
                    <div class="col-md-6">
                        <div class="form-check">
                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" id="lebihbayar" name="lebihbayar" class="custom-control-input" <?php if($isi->f_lebihbayar=='t') { echo "checked value='on'"; }else{ echo "value=''";} ?> <?php if($isi->i_tunai=='')?> onclick="pilihlebihbayar(this);">
                                <span class="custom-control-indicator"></span>
                                <span class="custom-control-description">Lebih Bayar</span>
                            </label>
                        </div>
                    </div>
                </div>   
            </div>
            <div class="panel-body table-responsive">
                <table id="tabledata" class="display table" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th style="text-align: center; width: 4%;">No</th>
                            <th style="text-align: center; width: 15%; ">No Nota</th>
                            <th style="text-align: center; width: 10%;">Tanggal Nota</th>
                            <th style="text-align: center; width: 10%;">Area</th>
                            <th style="text-align: center; width: 15%;">Jumlah</th>
                            <th style="text-align: center; width: 20%;">Keterangan</th>
                            <th style="text-align: center; width: 10%;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php               
                        $i=0;
                        if($detail!=''){
                            foreach($detail as $row){ 
                                $i++;
                                ?>
                                <tr>
                                    <td style="text-align: center;"><?= $i;?>
                                    <input type="hidden" class="form-control" readonly id="baris<?= $i;?>" name="baris<?= $i;?>" value="<?= $i;?>">
                                </td>
                                <td>
                                    <input style="font-size: 12px;" class="form-control" readonly id="inota<?= $i;?>" name="inota<?= $i;?>" value="<?= $row->i_nota;?>">
                                </td>
                                <td>
                                    <input style="font-size: 12px;" class="form-control" readonly id="dnota<?= $i;?>" name="dnota<?= $i;?>" value="<?= $row->d_nota;?>">
                                </td>
                                <td>
                                    <input style="font-size: 12px;" readonly class="form-control" id="eareaname<?= $i;?>" name="eareaname<?= $i;?>" value="<?= $row->e_area_name;?>">
                                    <input style="font-size: 12px;" readonly type="hidden" class="form-control" id="iarea<?= $i;?>" name="iarea<?= $i;?>" value="<?= $row->i_area;?>">
                                </td>
                                <td>
                                    <input style="font-size: 12px; text-align: right;" class="form-control jum" width:85px;"  id="vsisa<?= $i;?>" name="vsisa<?= $i;?>" value="<?= number_format($row->v_jumlah);?>">
                                </td>
                                <td>
                                    <input style="font-size: 12px;" class="form-control" width:85px;"  id="eremark<?= $i;?>" name="eremark<?= $i;?>" value="<?= $row->e_remark;?>">
                                </td>
                                <td style="text-align: center;">
                                    <?php
                                        if($bisaedit){
                                            if(($row->i_nota!='' && $row->i_nota!=null && $isi->v_jumlah==$isi->v_sisa)){?>
                                                <button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button>
                                           <?php }
                                        }?>
                                </td>
                            </tr>
                            <?php 
                        }
                    }?>
                    <input type="hidden" name="jml" id="jml" value="<?= $i;?>">
                </tbody>
            </table>
        </div>
        </form>
    </div>
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
			(document.getElementById("dtunai").value=='')||(document.getElementById("icustomer").value=='')||(document.getElementById("vjumlah").value=='')||(document.getElementById("vjumlah").value=='0')
		  )
		{
			alert("Data Header belum lengkap !!!");
		}else{			
			document.getElementById("login").disabled=true;
			document.getElementById("cmdtambahitem").disabled=true;
			alert(inota);
			alert("Data Header belum lengkaps !!!");
		}
	} 

    function hetang(){
		document.getElementById("vsisa").value=document.getElementById("vjumlah").value;
	}

	function pilihlebihbayar(a){
        if(document.getElementById("itunai").value==''){
	    	if(a==''){
	    	  document.getElementById("lebihbayar").value='on';
	    	}else{
	    	  document.getElementById("lebihbayar").value='';
	    	}
        }else{
	    	if(a==''){
	    	  document.getElementById("lebihbayar").value='';
	    	}else{
	    	  document.getElementById("lebihbayar").value='on';
	    	}
        }
    }

    var xx = $('#jml').val();
    $("#addrow").on("click", function () {
        xx++;
        if(xx<=20){
            $("#tabledata").attr("hidden", false);
            $('#jml').val(xx);
            count=$('#tabledata tr').length;
            var newRow = $("<tr>");
            var cols = "";
            cols += '<td style="text-align: center;"><spanx id="snum'+xx+'">'+count+'</spanx><input type="hidden" id="baris'+xx+'" type="text" class="form-control" name="baris'+xx+'" value="'+xx+'"></td>';
            cols += '<td><select id="inota'+xx+'" class="form-control" name="inota'+xx+'" onchange="getdetailnota('+xx+');"></select></td>';
            cols += '<td><input readonly id="dnota'+xx+'" class="form-control" name="dnota'+xx+'" value=""></td>';
            cols += '<td><input readonly type="hidden" id="iarea'+xx+'" class="form-control" name="iarea'+xx+'" value=""><input readonly type="text" id="eareaname'+xx+'" class="form-control" name="eareaname'+xx+'" value=""></td>';
            cols += '<td><input readonly style="text-align: right;" type="text" id="vsisa'+xx+'" class="form-control jum" name="vsisa'+xx+'" value=""></td>';
            cols += '<td><input readonly style="text-align: right;" type="text" id="eremark'+xx+'" class="form-control" name="eremark'+xx+'" value=""></td>';
            cols += '<td style="text-align: center;"><button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';
            newRow.append(cols);
            $("#tabledata").append(newRow);
            $('#inota'+xx).select2({
                placeholder: 'Cari Nota',
                allowClear: true,
                ajax: {
                    url: '<?= base_url($folder.'/cform/getnota/'); ?>',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        var iarea       = $('#iarea').val();
                        var icustomer   = $('#icustomer').val();
                        var dtunai      = $('#dtunai').val();
                        var query   = {
                            q           : params.term,
                            iarea       : iarea,
                            icustomer   : icustomer,
                            dtunai      : dtunai
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
        }else{
            swal("Maksimal 20 Nota");
        }
    });

    function getdetailnota(id){
        ada=false;
        var a = $('#inota'+id).val();
        var x = $('#jml').val();
        for(i=1;i<=x;i++){   
            if((a == $('#inota'+i).val()) && (i!=x)){
                swal ("kode : "+a+" sudah ada !!!!!");            
                ada=true;            
                break;        
            }else{            
                ada=false;             
            }
        }
        if(!ada){
            var inota = $('#inota'+id).val();
            var iarea = $('#iarea').val();
            $.ajax({
                type: "post",
                data: {
                    'inota'  : inota,
                    'iarea'  : iarea
                },
                url: '<?= base_url($folder.'/cform/getdetailnota'); ?>',
                dataType: "json",
                success: function (data) {
                    $('#eareaname'+id).val(data[0].e_area_name);
                    $('#iarea'+id).val(data[0].i_area);
                    $('#dnota'+id).val(data[0].d_nota);
                    $('#vsisa'+id).val(formatcemua(data[0].v_sisa));
                    $('#vjumlah').val(formatcemua(parseFloat(formatulang($('#vjumlah').val()))+parseFloat(data[0].v_sisa)));
                    $('#eremark'+id).val(data[0].e_remark);
                },
                error: function () {
                    swal('Error :)');
                }
            });
        }else{
            $('#inota'+id).html('');
            $('#inota'+id).val('');
        }
    }

    $("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
        /*xx -= 1;*/
        $('#jml').val(xx);
        del();
        ngetang();
    });

    function del() {
        obj=$('#tabledata tr').find('spanx');
        $.each( obj, function( key, value ) {
            id=value.id;
            $('#'+id).html(key+1);
        });
    }

    function ngetang() {
        var sum = 0;
        $('#tabledata > tbody  > tr').each(function() {
            var price = $(this).find('.jum').val();
            var amount = parseFloat(formatulang(price));
            sum+=amount;
        });
        $('#vjumlah').val(formatcemua(sum));
    }
</script>
