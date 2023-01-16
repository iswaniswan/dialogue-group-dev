<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-info-circle"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-6">No. DT</label><label class="col-md-6">Tanggal DT</label>
                        <div class="col-sm-6">
                            <input readonly id= "idt" name="idt" class="form-control" value="<?= $isi->i_dt;?>">
                        </div>
                        <div class="col-sm-6">
                            <input type="text" required="" placeholder="Pilih Tanggal" readonly id= "ddt" name="ddt" class="form-control date" value="<?= $isi->ddt;?>">
                            <input type="hidden" readonly id= "xddt" name="xddt" class="form-control date" value="<?= $isi->ddt;?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">Area</label><label class="col-md-6">Jumlah</label>
                        <div class="col-sm-6">
                            <select name="iarea" id="iarea" required="" class="form-control" onchange="cekarea(this.value);">
                                <?php if ($area) {                                 
                                    foreach ($area as $key) { ?>
                                        <option value="<?= $key->i_area;?>" <?php if ($key->i_area==$isi->i_area) {echo "selected";}?>><?= $key->i_area." - ".$key->e_area_name;?></option>
                                    <?php }; 
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <input style="text-align: right;" readonly id= "vjumlah" name="vjumlah" class="form-control" value="<?= number_format($isi->v_jumlah);?>">
                        </div>
                    </div>            
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-12">
                            <button <?php if($area1!='00' && !$bisaedit && $iperiode <= $isi->periodedt) echo "disabled"; ?> type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales(parseFloat(document.getElementById('jml').value));"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>&nbsp;&nbsp;
                            <button <?php if($area1!='00' && !$bisaedit && $iperiode <= $isi->periodedt) echo "disabled"; ?> type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Detail</button>&nbsp;&nbsp;                                
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/view/<?= $dfrom;?>/<?= $dto;?>/<?= $iarea;?>/","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>                             
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <table id="tabledata" class="display table" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th style="text-align: center; width: 4%;">No</th>
                                <th style="text-align: center; width: 12%;">Nota</th>
                                <th style="text-align: center; width: 10%;">Tanggal Nota</th>
                                <th style="text-align: center; width: 10%;">Tanggal JT</th>
                                <th style="text-align: center;">Pelanggan</th>
                                <th style="text-align: center; width: 11%;">Jumlah</th>
                                <th style="text-align: center; width: 11%;">Sisa</th>
                                <th style="text-align: center; width: 5%;">Act</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($detail) {
                                $i = 0;
                                foreach ($detail as $row) { 
                                    $i++; 
                                    $tmp=explode("-",$row->d_nota);
                                    $th=$tmp[0];
                                    $bl=$tmp[1];
                                    $hr=$tmp[2];
                                    $row->d_nota=$hr."-".$bl."-".$th;
                                    $tmp=explode("-",$row->d_jatuh_tempo);
                                    $th=$tmp[0];
                                    $bl=$tmp[1];
                                    $hr=$tmp[2];
                                    $row->d_jatuh_tempo=$hr."-".$bl."-".$th;
                                    $not=trim($row->i_nota);
                                    $jum=$isi->v_jumlah-$row->v_jumlah;
                                    $njum=$row->v_jumlah;
                                    $row->v_jumlah=number_format($row->v_jumlah);
                                    $row->v_sisa=number_format($row->v_sisa);
                                    ?>
                                    <tr>
                                        <td style="text-align: center;">
                                            <spanx id="snum<?= $i;?>"><?=$i;?></spanx>
                                            <input style="text-align: center;" readonly type="hidden" class="form-control" id="baris<?=$i;?>" name="baris<?=$i;?>" value="<?= $i;?>">
                                        </td>
                                        <td>
                                            <select class="form-control select2" onchange="getdetailnota('<?= $i;?>');" id="inota<?=$i;?>" name="inota<?=$i;?>">
                                                <option value="<?= $row->i_nota; ?>"><?= $row->i_nota; ?></option>
                                            </select>
                                        </td>
                                        <td>
                                            <input class="form-control" readonly type="text" id="dnota<?=$i;?>" name="dnota<?=$i;?>" value="<?= $row->d_nota; ?>">
                                        </td>
                                        <td>
                                            <input readonly type="text" class="form-control" id="djatuhtempo<?=$i;?>" name="djatuhtempo<?=$i;?>" value="<?= $row->d_jatuh_tempo; ?>">
                                        </td>
                                        <td>
                                            <input class="form-control" readonly type="text" id="ecustomername<?=$i;?>" name="ecustomername<?=$i;?>" value="<?= "(".$row->i_customer.") ".$row->e_customer_name. " (".$row->e_customer_city.")"; ?>">
                                            <input type="hidden" id="icustomer<?=$i;?>" name="icustomer<?=$i;?>" value="<?= $row->i_customer ;?>">
                                        </td>
                                        <td>
                                            <input class="form-control" readonly style="text-align:right;" type="text" id="vjumlah<?=$i;?>" name="vjumlah<?=$i;?>" value="<?= $row->v_jumlah; ?>">
                                        </td>
                                        <td>
                                            <input class="form-control jum" readonly style="text-align:right;" type="text" id="vsisa<?=$i;?>" name="vsisa<?=$i;?>" value="<?= $row->v_sisa; ?>">
                                        </td>
                                        <td style="text-align: center;">
                                            <?php if( ($area1=='00') || (($area1!='00')&&($bisaedit) && $iperiode<=$isi->periodedt) ){?>
                                                <button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php  }
                            } ?>
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
    function cekarea(iarea) {
        if (iarea != '') {
            $("#addrow").attr("disabled", false);
        }else{
            $("#addrow").attr("disabled", true);
        }
        $("#tabledata").attr("hidden", true);
        $("#tabledata tr:gt(0)").remove();       
        $("#jml").val(0);
        xx = 0;
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
            cols += '<td><input id="dnota'+xx+'" class="form-control" name="dnota'+xx+'" ></td>';
            cols += '<td><input type="hidden" id="djatuhtempo'+xx+'" name="djatuhtempo'+xx+'" ><input readonly id="djatuhtempox'+xx+'" class="form-control"></td>';
            cols += '<td><input type="hidden" id="icustomer'+xx+'" name="icustomer'+xx+'" ><input readonly id="ecustomername'+xx+'" name="ecustomername'+xx+'" class="form-control"></td>';
            cols += '<td><input style="text-align: right;" type="text" id="vjumlah'+xx+'" class="form-control" name="vjumlah'+xx+'" onkeypress="return hanyaAngka(event);" onkeyup="reformat(this);" maxlength="17"></td>';
            cols += '<td><input style="text-align: right;" type="text" id="vsisa'+xx+'" class="form-control jum" name="vsisa'+xx+'" readonly></td>';
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
                        var iarea   = $('#iarea').val();
                        var query   = {
                            q       : params.term,
                            iarea   : iarea
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
                    $('#dnota'+id).val(data[0].dnota);
                    $('#djatuhtempo'+id).val(data[0].d_jatuh_tempo);
                    $('#djatuhtempox'+id).val(data[0].djtp);
                    $('#ecustomername'+id).val('('+data[0].i_customer+') '+data[0].e_customer_name+' '+data[0].e_customer_city);
                    $('#icustomer'+id).val(data[0].i_customer);
                    $('#vjumlah'+id).val(formatcemua(data[0].v_nota_netto));
                    $('#vsisa'+id).val(formatcemua(data[0].v_sisa));
                    $('#vjumlah').val(formatcemua(parseFloat(formatulang($('#vjumlah').val()))+parseFloat(data[0].v_sisa)));
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

    $(document).ready(function () {
        showCalendar('.date');
        $('#iarea').select2({
            placeholder: 'Cari Area Berdasarkan Kode / Nama'
        });

        for (var i = 1; i <= $('#jml').val(); i++) {            
            $('#inota'+i).select2({
                placeholder: 'Cari Nota',
                allowClear: true,
                ajax: {
                    url: '<?= base_url($folder.'/cform/getnota/'); ?>',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        var iarea   = $('#iarea').val();
                        var query   = {
                            q       : params.term,
                            iarea   : iarea
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
        }
    });

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#addrow").attr("disabled", true);
    });

    function dipales(a){
        if((document.getElementById("idt").value!='') && (document.getElementById("ddt").value!='') && (document.getElementById("iarea").value!='') && (document.getElementById("vjumlah").value!='0')) {
            if(a==0){
                swal('Isi data item minimal 1 !!!');
                return false;
            }else{
                for(i=1;i<=a;i++){
                    if((document.getElementById("inota"+i).value=='') || (document.getElementById("icustomer"+i).value=='')){
                        swal('Data item masih ada yang salah !!!');
                        return false;
                    }else{
                        return true;
                    }
                }
            }
        }else{
            swal('Data header masih ada yang salah !!!');
            return false;
        }
    }
</script>