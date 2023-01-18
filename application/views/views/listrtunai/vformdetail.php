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
                       <input readonly class="form-control" name="irtunai" id="irtunai" value="<?= $isi->i_rtunai; ?>" maxlength="10">
                   </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-12">Tanggal Setor</label>
                    <?php 
		                $tmp=explode('-',$isi->d_rtunai);
		                $yy=$tmp[0];
		                $mm=$tmp[1];
                        $dd=$tmp[2];
                        $thbl=$yy.$mm;
		                $isi->d_rtunai=$dd.'-'.$mm.'-'.$yy;
		            ?>
                    <div class="col-sm-3">
                        <input class="form-control date" readonly name="drtunai" id="drtunai" value="<?= $isi->d_rtunai; ?>">
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
                        <input type="hidden" name="xiarea" id="xiarea" value="<?php echo $isi->i_area; ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-offset-5 col-sm-8">
                    <?php 
                        if($isi->i_cek == '' && $iperiode <= $thbl){?>
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales(parseFloat(document.getElementById('jml').value));"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                            &nbsp;&nbsp;
                            <button type="button" class="btn btn-success btn-rounded btn-sm" id="addrow""> <i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah Item</button>
                            &nbsp;&nbsp;
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
                    <label class="col-md-12">Bank</label>
                    <div class="col-sm-6">
                        <select name="ibank" id="ibank" class="form-control select2" required="">
                            <option value="<?= $isi->i_bank ?>"><?= $isi->e_bank_name ?></option>
                            <?php if ($bank) {
                                foreach ($bank as $key) { ?>
                                    <option value="<?php echo $key->i_bank;?>"><?php echo $key->i_bank." - ".$key->e_bank_name;?></option> 
                                <?php }
                            } ?>   
                        </select>
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
                        <input readonly type="text" name="vjumlah" id="vjumlah" class="form-control" value="<?php echo number_format($isi->v_jumlah);?>">
                    </div>
                </div>   
            </div>
            <div class="panel-body table-responsive">
                <table id="tabledata" class="display table" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th style="text-align: center; width: 4%;">No</th>
                            <th style="text-align: center; width: 15%;">Area</th>
                            <th style="text-align: center; width: 15%;">No Tunai</th>
                            <th style="text-align: center; width: 10%;">Tanggal Tunai</th>
                            <th style="text-align: center; width: 20%;">Pelanggan</th>
                            <th style="text-align: center; width: 15%;">Jumlah</th>
                            <th style="text-align: center; width: 15%;">Keterangan</th>
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
                                    <input style="font-size: 12px;" readonly class="form-control" id="eareaname<?= $i;?>" name="eareaname<?= $i;?>" value="<?= $row->e_area_name;?>">
                                    <input style="font-size: 12px;" readonly type="hidden" class="form-control" id="iarea<?= $i;?>" name="iarea<?= $i;?>" value="<?= $row->i_area;?>">
                                </td>
                                <td>
                                    <input style="font-size: 12px;" class="form-control" readonly id="itunai<?= $i;?>" name="itunai<?= $i;?>" value="<?= $row->i_tunai;?>">
                                </td>
                                <td>
                                    <input style="font-size: 12px;" class="form-control" readonly id="dtunai<?= $i;?>" name="dtunai<?= $i;?>" value="<?= $row->d_tunai;?>">
                                </td>
                                <td>
                                    <input style="font-size: 12px;" class="form-control" readonly id="ecustomername<?= $i;?>" name="ecustomername<?= $i;?>" value="<?= $row->e_customer_name;?>">
                                    <input style="font-size: 12px;" class="form-control" readonly type="hidden" id="icustomer<?= $i;?>" name="icustomer<?= $i;?>" value="<?= $row->i_customer;?>">
                                </td>
                                <td>
                                <input style="font-size: 12px;" class="form-control jum" readonly type="hidden" id="jumlahasal<?= $i;?>" name="jumlahasal<?= $i;?>" value="<?= $row->v_jumlah;?>">
                                <input style="font-size: 12px;" class="form-control jum" readonly type="hidden" id="jumlahasallagi<?= $i;?>" name="jumlahasallagi<?= $i;?>" value="<?= $row->v_jumlah;?>">
                                    <input style="font-size: 12px; text-align: right;" class="form-control jum" width:85px;"  id="vjumlahx<?= $i;?>" name="vjumlahx<?= $i;?>" value="<?= number_format($row->v_jumlah);?>">
                                </td>
                                <td>
                                    <input style="font-size: 12px;" class="form-control" width:85px;"  id="eremark<?= $i;?>" name="eremark<?= $i;?>" value="<?= $row->e_remark;?>">
                                </td>
                                <td style="text-align: center;">
                                    <?php
                                        if($iperiode <= $thbl){?>
                                            <button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button>
                                        <?php }?>
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
			(document.getElementById("drtunai").value=='')||(document.getElementById("iarea").value=='')||(document.getElementById("vjumlah").value=='')||(document.getElementById("vjumlah").value=='0')
		  )
		{
			alert("Data Header belum lengkap !!!");
		}else{			
			document.getElementById("login").disabled=true;
			document.getElementById("cmdtambahitem").disabled=true;
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
            cols += '<td><input readonly type="hidden" id="iarea'+xx+'" class="form-control" name="iarea'+xx+'" value=""><input readonly type="text" id="eareaname'+xx+'" class="form-control" name="eareaname'+xx+'" value=""></td>';
            cols += '<td><select id="itunai'+xx+'" class="form-control select2" name="itunai'+xx+'" onchange="getdetailtunai('+xx+');"></select></td>';
            cols += '<td><input readonly id="dtunai'+xx+'" class="form-control" name="dtunai'+xx+'" value=""></td>';
            cols += '<td><input readonly style="text-align;" type="text" id="ecustomername'+xx+'" class="form-control" name="ecustomername'+xx+'" value=""><input readonly style="text-align: right;" type="hidden" id="icustomer'+xx+'" class="form-control" name="icustomer'+xx+'" value=""></td>';
            cols += '<td><input readonly style="text-align: right;" type="hidden" id="vjumlahasal'+xx+'" class="form-control jum" name="vjumlahasal'+xx+'" value=""><input readonly style="text-align: right;" type="hidden" id="vjumlahasallagi'+xx+'" class="form-control jum" name="vjumlahasallagi'+xx+'" value=""><input readonly style="text-align: right;" type="type" id="vjumlahx'+xx+'" class="form-control jum" name="vjumlahx'+xx+'" value=""></td>';
            cols += '<td><input readonly style="text-align: right;" type="text" id="eremark'+xx+'" class="form-control" name="eremark'+xx+'" value=""></td>';
            cols += '<td style="text-align: center;"><button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';
            newRow.append(cols);
            $("#tabledata").append(newRow);
            $('#itunai'+xx).select2({
                placeholder: 'Cari Tunai',
                allowClear: true,
                ajax: {
                    url: '<?= base_url($folder.'/cform/gettunai/'); ?>',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        var iarea       = $('#iarea').val();
                        var drtunai      = $('#drtunai').val();
                        var query   = {
                            q           : params.term,
                            iarea       : iarea,
                            drtunai     : drtunai
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

    function getdetailtunai(id){
        ada=false;
        var a = $('#itunai'+id).val();
        var x = $('#jml').val();
        for(i=1;i<=x;i++){   
            if((a == $('#itunai'+i).val()) && (i!=x)){
                swal ("kode : "+a+" sudah ada !!!!!");            
                ada=true;            
                break;        
            }else{            
                ada=false;             
            }
        }
        if(!ada){
            var itunai = $('#itunai'+id).val();
            var iarea = $('#iarea').val();
            $.ajax({
                type: "post",
                data: {
                    'itunai'  : itunai,
                    'iarea'  : iarea
                },
                url: '<?= base_url($folder.'/cform/getdetailtunai'); ?>',
                dataType: "json",
                success: function (data) {
                    $('#eareaname'+id).val(data[0].e_area_name);
                    $('#iarea'+id).val(data[0].i_area);
                    $('#dtunai'+id).val(data[0].d_tunai);
                    $('#icustomer'+id).val(data[0].i_customer);
                    $('#ecustomername'+id).val(data[0].e_customer_name);
                    $('#vjumlahx'+id).val(formatcemua(data[0].v_jumlah));
                    $('#vjumlahasal'+id).val(formatcemua(data[0].v_jumlah));
                    $('#vjumlahasallagi'+id).val(formatcemua(data[0].v_jumlah));
                    $('#vjumlah').val(formatcemua(parseFloat(formatulang($('#vjumlah').val()))+parseFloat(data[0].v_jumlah)));
                    $('#eremark'+id).val(data[0].e_remark);
                },
                error: function () {
                    swal('Error :)');
                }
            });
        }else{
            $('#itunai'+id).html('');
            $('#itunai'+id).val('');
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