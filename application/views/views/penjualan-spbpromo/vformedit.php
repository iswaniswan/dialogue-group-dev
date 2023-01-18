<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-3">Nomor Promo</label>
                            <label class="col-md-5">Nama Promo</label>
                            <label class="col-md-4">Jenis Promo</label>                            
                            <div class="col-sm-3">
                                <input type="hidden" name="id" id="id" class="form-control" value="<?= $data->id; ?>">
                                <input type="text" name="ipromo" id="ipromo" autocomplete="off" class="form-control"required="" maxlength="15" onkeyup="gede(this); clearcode(this);" value="<?= $data->i_promo; ?>">
                                <span class="notekode" hidden="true"><b> * Kode Sudah Ada!</b></span>
                            </div>
                            <div class="col-sm-5">
                                <input type="text" name="epromo" id="epromo" onkeyup="gede(this); clearname(this);" class="form-control" value="<?= $data->e_promo; ?>" maxlength="300">
                            </div>
                            <div class="col-sm-4">
                                <input type="text" name="ejenis" id="ejenis" onkeyup="gede(this); clearname(this);" class="form-control" value="<?= $data->e_jenispromo; ?>" maxlength="100">
                            </div>
                        </div>       
                        <div class="form-group row">
                            <label class="col-md-3">Jumlah Promo (%)</label>
                            <label class="col-md-9">Periode Berlaku</label>
                            <div class="col-sm-3">
                                <input type="text" name="njumlah" id="njumlah" class="form-control" value="<?= $data->n_jumlahpromo; ?>">
                            </div>
                            <div class="col-sm-2">
                                <input type="text" name="dperiode" id="dperiode" class="form-control date" value="<?= date("d-m-Y",strtotime($data->d_berlaku))?>" readonly>
                            </div>
                        </div>  
                    <div class="form-group">
                        <div class="col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return konfirm();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Update</button>&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                        </div>           
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<div class="white-box" id="detail">
    <div class="col-sm-5">
        <h3 class="box-title m-b-0">Detail Barang</h3>
        <div class="m-b-0">
            <div class="form-group row">
                <div class="col-sm-1">
                    <button type="button" id="addrow" class="btn btn-info btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Item</button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead> 
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Kode Barang</th>
                        <th class="text-center">Nama Barang</th>
                        <th class="text-center">Warna</th>
                        <th class="text-center">Diskon (%)</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    if($datadetail){
                    $i = 0;
                    foreach($datadetail as $row){
                        $i++;                             
                ?>
                    <tr>              
                        <td class="text-center"><spanx id="snum<?=$i;?>"><?= $i;?></spanx></td>
                        <td>  
                            <input style="width:120px" type="hidden" class="form-control" id="idproduct<?=$i;?>" name="idproduct[]" value="<?= $row->id_product; ?>" readonly>
                            <input style="width:200px" type="text" class="form-control" id="iproduct<?=$i;?>" name="iproduct[]" value="<?= $row->i_product_base; ?>" readonly>
                        </td>
                        </td>
                        <td>
                            <input style="width:400px" type="text" class="form-control" id="eproduct<?=$i;?>" name="eproduct[]"value="<?= $row->e_product_basename; ?>" readonly>
                        </td>
                        <td>
                            <input style="width:120px" type="hidden" class="form-control" id="idcolor<?=$i;?>" name="idcolor[]" value="<?= $row->id_color; ?>" readonly>
                            <input style="width:200px" type="text" class="form-control" id="ecolor<?=$i;?>" name="ecolor[]" value="<?= $row->e_color_name; ?>" readonly>
                        </td>
                        <td>
                            <input style="width:150px" type="text" class="form-control" id="ndiskon<?=$i;?>" name="ndiskon[]" value="<?= $row->n_diskon; ?>"> 
                        </td>
                        <td>
                            <button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button>
                        </td>                       
                    </tr>                       
                    <?}
                }?>        
                <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                </tbody>         
            </table>
        </div>
    </div>
</div>
</form>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
    $(document).ready(function () {
        $( "#ipromo" ).focus();
        showCalendar('.date');
    });

    $( "#ipromo" ).keyup(function() {
        $.ajax({
            type: "post",
            data: {
                'kode' : $(this).val(),
            },
            url: '<?= base_url($folder.'/cform/cekkode'); ?>',
            dataType: "json",
            success: function (data) {
                if (data==1) {
                    $(".notekode").attr("hidden", false);
                    $("#submit").attr("disabled", true);
                }else{
                    $(".notekode").attr("hidden", true);
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
        $("#addrow").attr("disabled", true);
    });

   // var counter = 0;

    var counter = $('#jml').val();
    var counterx = counter-1;
    $("#addrow").on("click", function () {
        counter++;
        counterx++;
        $("#tabledatax").attr("hidden", false);
        var iproduct = $('#iproduct'+counterx).val();
        count=$('#tabledatax tr').length;
        if ((iproduct==''||iproduct==null)&&(count>1)) {
            swal('Isi dulu yang masih kosong!!');
            counter = counter-1;
            counterx = counterx-1;
            return false;
        }
        $('#jml').val(counter);
        var newRow = $("<tr>");
        var cols = "";

        cols += '<td class="text-center"><spanx id="snum'+counter+'">'+count+'</spanx></td>';
        cols += '<td><input type="hidden" readonly id="idproduct'+ counter + '" class="form-control" name="idproduct[]"><input type="text" readonly id="iproduct'+ counter + '" style="width:200px;" class="form-control" name="iproduct' + counter + '"></td>';
        cols += '<td><select type="text" style="width:400px;" id="eproduct'+ counter + '" class="form-control" name="eproduct'+ counter + '" onchange="getproduct('+ counter + ');"</td>';
        cols += '<td><input type="hidden" id="idcolor'+ counter + '" class="form-control" name="idcolor[]"><input type="text" readonly style="width:200px;" id="ecolorproduct'+ counter + '" class="form-control" name="ecolorproduct'+ counter + '"></td>';
        cols += '<td><input style="width:150px;" type="text" id="ndiskon'+ counter + '" class="form-control text-right" name="ndiskon[]" value="0" onkeypress="return hanyaAngka(event);"></td>';
        cols += '<td><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>';

        newRow.append(cols);
        $("#tabledatax").append(newRow);

        $('#eproduct'+ counter).select2({
            placeholder: 'Cari Berdasarkan Nama / Kode',
            templateSelection: formatSelection,
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/dataproduct'); ?>',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
    });

    function formatSelection(val) {
        return val.name;
    }

    function getproduct(id){
        ada=false;
        var a = $('#eproduct'+id).val();
        var x = $('#jml').val();
        for(i=1;i<=x;i++){
            if((a == $('#eproduct'+i).val()) && (i!=x)){
                swal ("kode Barang : "+a+" sudah ada !!!!!");            
                ada=true;            
                break;        
            }else{            
                ada=false;             
            }
        }

        if(!ada){
            var eproduct = $('#eproduct'+id).val();
            $.ajax({
                type: "post",
                data: {
                    'eproduct'  : eproduct
                },
                url: '<?= base_url($folder.'/cform/getproduct'); ?>',
                dataType: "json",
                success: function (data) {
                    $('#idproduct'+id).val(formatcemua(data[0].id_product));
                    $('#iproduct'+id).val(formatcemua(data[0].i_product_base));
                    $('#idcolor'+id).val(formatcemua(data[0].id_color));
                    $('#ecolorproduct'+id).val(formatcemua(data[0].e_color_name));
                    $('#ndiskon'+id).focus();
                },
                error: function () {
                    swal('Error :)');
                }
            });
        }else{
            $('#idproduct'+id).html('');
            $('#iproduct'+id).html('');
            $('#eproduct'+id).html('');
            $('#idcolor'+id).html('');
            $('#ecolorproduct'+id).html('');
            $('#idproduct'+id).val('');
            $('#iproduct'+id).val('');
            $('#eproduct'+id).val('');
            $('#idcolor'+id).val('');
            $('#ecolorproduct'+id).val('');
        }
    }

    $("#tabledatax").on("click", ".ibtnDel", function (event) {    
        $(this).closest("tr").remove();
        /*$('#jml').val(i);*/
        del();
    });

    function del() {
        obj=$('#tabledatax tr:visible').find('spanx');
        $.each( obj, function( key, value ) {
            id = value.id;
            $('#'+id).html(key+1);
        });
    }

    function konfirm() {
        var jml = $('#jml').val();

        if ($('#ipromo').val()!='' || $('#epromo').val()!='' || $('#njumlah').val()!='' || $('#dperiode').val()!='') {
            if(jml==0){
                swal('Isi data item minimal 1 !!!');
                return false;
            }else{
                for(i=1;i<=jml;i++){
                    if($('#idproduct'+i).val()=='' || $('#ndiskon'+i).val()==''){
                        swal('Data item masih ada yang salah !!!');
                        return false;
                    }else{
                        return true;
                    } 
                }
            }
        }else{
            swal('Data Header Masih Ada yang Kosong!');
            return false;
        }
    }
</script>