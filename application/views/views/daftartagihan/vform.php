<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-6">No. DT</label><label class="col-md-6">Tanggal DT</label>
                        <div class="col-sm-6">
                            <input maxlength="7" id= "idt" name="idt" class="form-control" onkeyup="gede(this)">
                        </div>
                        <div class="col-sm-6">
                            <input type="text" required="" placeholder="Pilih Tanggal" readonly id= "ddt" name="ddt" class="form-control date" value="<?= date('d-m-Y');?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">Area</label><label class="col-md-6">Jumlah</label>
                        <div class="col-sm-6">
                            <select name="iarea" id="iarea" required="" class="form-control" onchange="cekarea(this.value);">
                                <option value=""></option>
                                <?php if ($area) {                                 
                                    foreach ($area as $key) { ?>
                                        <option value="<?php echo $key->i_area;?>"><?= $key->i_area." - ".$key->e_area_name;?></option>
                                    <?php }; 
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <input style="text-align: right;" readonly id= "vjumlah" name="vjumlah" class="form-control" value="0">
                        </div>
                    </div>            
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales(parseFloat(document.getElementById('jml').value));"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>&nbsp;&nbsp;
                            <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm" disabled=""><i class="fa fa-plus"></i>&nbsp;&nbsp;Detail</button>&nbsp;&nbsp;                                
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Batal</button>                               
                        </div>
                    </div>
                </div>
                <input type="hidden" name="jml" id="jml" value="0">
                <div class="col-md-12">
                    <table id="tabledata" class="display table" cellspacing="0" width="100%" hidden="true">
                        <thead>
                            <tr>
                                <th style="text-align: center; width: 4%;">No</th>
                                <th style="text-align: center; width: 20%;">Nota</th>
                                <th style="text-align: center; width: 10%;">Tanggal Nota</th>
                                <th style="text-align: center; width: 10%;">Tanggal JT</th>
                                <th style="text-align: center;">Pelanggan</th>
                                <th style="text-align: center; width: 12%;">Jumlah</th>
                                <th style="text-align: center; width: 12%;">Sisa</th>
                                <th style="text-align: center; width: 5%;">Act</th>
                            </tr>
                        </thead>
                        <tbody>
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

    var xx = 0;
    $("#addrow").on("click", function () {
        xx++;
        if(xx<=20){
            $("#tabledata").attr("hidden", false);
            $('#jml').val(xx);
            var newRow = $("<tr>");
            var cols = "";
            cols += '<td style="text-align: center;">'+xx+'<input type="hidden" id="baris'+xx+'" type="text" class="form-control" name="baris'+xx+'" value="'+xx+'"></td>';
            cols += '<td><select id="inota'+xx+'" class="form-control" name="inota'+xx+'" onchange="getdetailnota('+xx+');"></select></td>';
            cols += '<td><input type="hidden" id="dnota'+xx+'" name="dnota'+xx+'" ><input readonly id="dnotax'+xx+'" name="dnotax'+xx+'" class="form-control"></td>';
            cols += '<td><input type="hidden" id="djatuhtempo'+xx+'" name="djatuhtempo'+xx+'" ><input readonly id="djatuhtempox'+xx+'" class="form-control"></td>';
            cols += '<td><input type="hidden" id="icustomer'+xx+'" name="icustomer'+xx+'" ><input readonly id="ecustomername'+xx+'" name="ecustomername'+xx+'" class="form-control"></td>';
            cols += '<td><input style="text-align: right;" type="text" id="vjumlah'+xx+'" class="form-control" name="vjumlah'+xx+'" onkeypress="return hanyaAngka(event);" onkeyup="reformat(this);" maxlength="17"></td>';
            cols += '<td><input style="text-align: right;" type="text" id="vsisa'+xx+'" class="form-control" name="vsisa'+xx+'" readonly></td>';
            cols += '<td><button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';
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
        var e = $('#motif'+id).val();
        var x = $('#jml').val();
        for(i=1;i<=x;i++){            
            if((a == $('#inota'+i).val()) && (i!=x)){
                alert ("kode : "+a+" sudah ada !!!!!");            
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
                    $('#dnota'+id).val(data[0].d_nota);
                    $('#dnotax'+id).val(data[0].dnota);
                    $('#djatuhtempo'+id).val(data[0].d_jatuh_tempo);
                    $('#djatuhtempox'+id).val(data[0].djtp);
                    $('#ecustomername'+id).val(data[0].e_customer_name);
                    $('#icustomer'+id).val(data[0].i_customer);
                    $('#vjumlah'+id).val(formatcemua(data[0].v_nota_netto));
                    $('#vsisa'+id).val(formatcemua(data[0].v_sisa));
                    $('#vjumlah').val(formatcemua(parseFloat(formatulang($('#vjumlah').val()))+parseFloat(data[0].v_sisa)));
                },
                error: function () {
                    alert('Error :)');
                }
            });
        }else{
            $('#inota'+id).html('');
            $('#inota'+id).val('');
        }
    }

    $("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
        xx -= 1
        $('#jml').val(xx);
    });

    $(document).ready(function () {
        showCalendar('.date');
        $('#iarea').select2({
            placeholder: 'Cari Area Berdasarkan Kode / Nama'
        });
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