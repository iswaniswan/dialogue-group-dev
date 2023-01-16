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
                <div class="col-md-6">
                    <div id="pesan"></div>
                    <div class="form-group row">
                        <label class="col-md-6">No SJ</label><label class="col-md-6">Tanggal SJ</label>
                        <?php if($isi->d_sjbr){
			                if($isi->d_sjbr!=''){
			                	  $tmp=explode("-",$isi->d_sjbr);
			                	  $hr=$tmp[2];
			                	  $bl=$tmp[1];
			                	  $th=$tmp[0];
			                	  $isi->d_sjbr=$hr."-".$bl."-".$th;
			                }
		                }?>
                            <div class="col-sm-6">
                                <input id="isjbr" name="isjbr" class="form-control" value="<?php echo $isi->i_sjbr; ?>">
                            </div>
                            <div class="col-sm-3">
                                <input id="d_now" name="d_now" type="hidden" value="<?php echo date('Y-m-d');?>">
                                <input readonly id="dsjbr" name="dsjbr" class="form-control date" value="<?php echo $isi->d_sjbr; ?>">
                            </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-9">
                            <input readonly id="eareaname" class="form-control" name="eareaname" value="<?php if($isi->e_area_name) echo $isi->e_area_name; ?>">
                            <input id="iarea" name="iarea" class="form-control" type="hidden" value="<?php echo $isi->i_area; ?>">
                            <input id="istore" name="istore" type="hidden" value="<?php if($isi->i_store) echo $isi->i_store; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Nilai</label>
                        <div class="col-sm-9">
                            <input readonly id="vsj" name="vsj" class="form-control" value="<?php echo number_format($isi->v_sjbr);?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-8">
                        <?php if($isi->f_sjbr_cancel != 't'){?>
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales(parseFloat(document.getElementById('jml').value));"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                            &nbsp;&nbsp; 
                            <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Item</button>
                            <?}?>
		                    <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/view/<?=$dfrom;?>/<?=$dto;?>/<?=$iarea;?>","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                            &nbsp;&nbsp;
                        </div>
                    </div>
                </div>
                    <div class="table-responsive">
                    <table class="table table-bordered" cellspacing="0" width="100%" id="tabledata">
                                <thead>
                                    <tr>
                                        <th style="text-align: center; width: 7%;">No</th>
                                        <th style="text-align: center; width: 10%;">Kode Barang</th>
                                        <th style="text-align: center; width: 35%;">Nama Barang</th>
                                        <th style="text-align: center; width: 10%;">Ket</th>
                                        <th style="text-align: center;">Jumlah Retur</th>
                                        <th style="text-align: center;">Jumlah Kirim</th>
                                        <th style="text-align: center;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php               
                            $i=0;
                            if($detail){
                                foreach($detail as $row){ 
                                    $i++;
                                    $vtotal=$row->v_unit_price*$row->n_quantity_retur;
                                    ?>
                                    <tr>
                                        <td style="text-align: center;">
                                            <?= $i;?>
                                            <input type="hidden" class="form-control" readonly id="baris<?= $i;?>" name="baris<?= $i;?>" value="<?= $i;?>">
                                            <input type="hidden" id="motif<?= $i;?>" name="motif<?= $i;?>" value="<?= $row->i_product_motif;?>">
                                        </td>
                                        <td>
                                            <input class="form-control" readonly id="iproduct<?= $i;?>" name="iproduct<?= $i;?>" value="<?= $row->i_product;?>">
                                        </td>
                                        <td>
                                            <input class="form-control" readonly id="eproductname<?= $i;?>" name="eproductname<?= $i;?>" value="<?= $row->e_product_name;?>">
                                            <input type="hidden" readonly class="form-control" id="emotifname<?= $i;?>" name="emotifname<?= $i;?>" value="<?= $row->e_product_motifname;?>">
                                        </td>
                                        <td>
                                            <input class="form-control" id="eremark<?= $i;?>" name="eremark<?= $i;?>" value="<?= $row->e_remark;?>">
                                            <input type="hidden" style="text-align: right;" readonly class="form-control" width:85px;"  id="vproductmill<?= $i;?>" name="vproductmill<?= $i;?>" value="<?= $row->v_unit_price;?>">
                                        </td>
                                        <td>
                                            <input style="text-align: right;" class="form-control" id="nretur<?= $i;?>" name="nretur<?= $i;?>" value="<?= $row->n_quantity_retur;?>" readonly>
                                        </td> 
                                        <td>
                                            <input style="text-align: right;" class="form-control" id="nreceive<?= $i;?>" name="nreceive<?= $i;?>" value="<?= $row->n_quantity_receive;?>" onkeypress="return hanyaAngka(event);"  onkeyup="ngetang()">
                                            <input type="hidden" id="nasal<?= $i;?>" name="nasal<?= $i;?>" value="<?= $row->n_quantity_retur;?>">
                                            <input style="text-align: right;" type="hidden" readonly class="form-control" id="vtotal<?= $i;?>" name="vtotal<?= $i;?>" value="<?= $vtotal;?>">
                                        </td>
                                        <td style="text-align: center;">
                                            <input type='checkbox' name="chk<?=$i;?>" id="chk<?=$i;?>" value='on' checked onclick='ngetang()'>
                                        </td>
                                    </tr>
                                <?php }
                            } ?>
                                    <input type="text" name="jml" id="jml" value="<?= $jmlitem;?>">
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
var xx = $('#jml').val();
    $("#addrow").on("click", function () {
        xx++;
        document.getElementById("jml").value = xx;
        $('#jml').val(xx);
        var newRow = $("<tr>");
        var cols = "";
        
        cols += '<td style="text-align: center;">'+xx+'<input type="hidden" id="baris'+xx+'" class="form-control" name="baris'+xx+'" value="'+xx+'"><input type="hidden" id="motif'+xx+'" name="motif'+xx+'" value=""></td>';
        cols += '<td><select id="iproduct'+xx+'" class="form-control select2" name="iproduct'+xx+'" onchange="detail('+xx+');" value=""></select></td>';
        cols += '<td><input id="eproductname'+xx+'" class="form-control" name="eproductname'+xx+'" value="" readonly><input type="hidden" id="emotifname'+xx+'" class="form-control" name="emotifname'+xx+'" value="" readonly></td>';
        cols += '<td><input id="eremark'+xx+'" class="form-control" name="eremark'+xx+'"></td>';
        cols += '<td><input class="form-control" id="nretur'+xx+'" style="text-align: right;" name="nretur'+xx+'" required value="0" onkeyup="ngetang('+xx+');"><input type="hidden" id="vproductmill'+xx+'" class="form-control" name="vproductmill'+xx+'" readonly></td>';
        cols += '<td><input class="form-control" id="nreceive'+xx+'" name="nreceive'+xx+'" required value="" style="text-align: right;"><input type="hidden" id="nasal'+xx+'" class="form-control" name="nasal'+xx+'" value="0"><input type="hidden" id="vtotal'+xx+'" name="vtotal'+xx+'" value="0"></td>';
        cols += '<td style="text-align: center;"><input type="checkbox" name="chk'+xx+'" id="chk'+xx+'" value="on" checked onclick="ngetang()"></td>';
        // /*cols += '<td><button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';*/
        
        newRow.append(cols);
        $("#tabledata").append(newRow);
        
        $('#iproduct'+xx).select2({
            placeholder: 'Cari Kode / Nama',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/product/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query   = {
                        q       : params.term,
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

    $("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
        xx -= 1
        document.getElementById("jml").value = xx;
    });

    function detail(id){
        ada=false;
        var a = $('#iproduct'+id).val();
        var x = $('#jml').val();
        for(i=1;i<=x;i++){            
            if((a == $('#iproduct'+i).val()) && (i!=x)){
                swal ("kode : "+a+" sudah ada !!!!!");            
                ada=true;            
                break;        
            }else{            
                ada=false;             
            }
        }
        if(!ada){
            var iproduct    = $('#iproduct'+id).val();
            $.ajax({
                type: "post",
                data: {
                    'iproduct'  : iproduct,
                },
                url: '<?= base_url($folder.'/cform/detailproduct'); ?>',
                dataType: "json",
                success: function (data) {
                    $('#eproductname'+id).val(data[0].nama);
                    $('#vproductmill'+id).val(formatcemua(data[0].harga));
                    $('#emotifname'+id).val(data[0].namamotif);
                    $('#motif'+id).val(data[0].motif);
                    $('#ndeliver'+id).focus();
                },
                error: function () {
                    swal('Error :)');
                }
            });
        }else{
            $('#iproduct'+id).html('');
            $('#iproduct'+id).val('');
        }
    }

    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date');
    });

    $("#checkAll").click(function(){
        $('input:chk').not(this).prop('checked', this.checked);
        ngetang();
    });

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#addrow").attr("disabled", true);
        $("#refresh").attr("disabled", true);
    });

    function ngetang(){
        var jml = parseFloat($('#jml').val());
        var tot = 0;
        for(brs=1;brs<=jml;brs++){    
            ord = $("#nretur"+brs).val();
            hrg  = formatulang($("#vproductmill"+brs).val());
            qty  = formatulang(ord);
            vhrg = parseFloat(hrg)*parseFloat(qty);
            $("#vtotal"+brs).val(formatcemua(vhrg));
            if($("#chk"+brs).is(':checked')){
                tot+=parseFloat(formatulang($("#vtotal"+brs).val()));
            }
            $("#vsj").val(formatcemua(tot));
        } 
    }
</script>