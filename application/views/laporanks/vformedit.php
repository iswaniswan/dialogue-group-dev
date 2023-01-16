<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-6">Nomor KS</label>
                        <label class="col-md-6">Tanggal KS</label>
                        <div class="col-sm-6">
                            <?php 
                            $tmp=explode("-",$isi->d_ic_convertion);
                            $th=$tmp[0];
                            $bl=$tmp[1];
                            $hr=$tmp[2];
                            $dicconvertion=$hr."-".$bl."-".$th;
                            ?>
                            <input id="iicconvertion" name="iicconvertion" class="form-control" required="" readonly value="<?= $isi->i_ic_convertion;?>">
                            <input type="hidden" id="bicconvertion" name="bicconvertion" class="form-control" required="" readonly value="<?= $bl;?>">
                        </div>
                        <div class="col-sm-6">
                            <input id= "dicconvertion" name="dicconvertion" class="form-control date" required="" readonly value="<?= $dicconvertion;?>" onchange="cektanggal();">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">Nomor Referensi</label>
                        <label class="col-md-6">Tanggal Referensi</label>
                        <div class="col-sm-6">
                            <?php 
                            if($isi->d_refference!=''){       
                                $tmp=explode("-",$isi->d_refference);
                                $th=$tmp[0];
                                $bl=$tmp[1];
                                $hr=$tmp[2];
                                $drefference=$hr."-".$bl."-".$th;
                            }else{
                                $drefference='';
                            }
                            ?>
                            <input id="irefference" name="irefference" class="form-control" required="" readonly value="<?= $isi->i_refference;?>">
                        </div>
                        <div class="col-sm-6">
                            <input id= "drefference" name="drefference" class="form-control" required="" readonly value="<?= $drefference;?>">
                            <input type="hidden" id="tglicconvertion" name="tglicconvertion" value="<?php echo $dicconvertion; ?>">
                            <input type="hidden" id="ibbk" name="ibbk" value="<?php echo $isi->i_bbk; ?>">
                            <input type="hidden" id="ibbm" name="ibbm" value="<?php echo $isi->i_bbm; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-8">
                            <?php if(check_role($i_menu, 3) && $isi->i_refference == '' && $status == 'f'){?>
                                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales(parseFloat(document.getElementById('jml1').value),parseFloat(document.getElementById('jml2').value)"> <i class="fa fa-save"></i>&nbsp;&nbsp;Update</button>
                                &nbsp;&nbsp;
                            <?php } ?>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/view/<?= $iperiode;?>","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                            <?php if(check_role($i_menu, 3) && $isi->i_refference == '' && $status == 'f'){?>
                                &nbsp;&nbsp;<button type="button" id="addrow1" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Product Asal
                                </button>&nbsp;&nbsp;                                
                                <button type="button" id="addrow2" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Product Jadi
                                </button>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php $a = 0;?>
                <div class="col-md-12">
                    <div class="table-responsive">
                        <h3 class="box-title text-center text-success"><b>Product Asal</b></h3>
                        <table id="tabeldata1" class="table color-table inverse-table table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th style="text-align: center; width: 25%;">Kode Barang</th>
                                    <th style="text-align: center; width: 10%;">Grade</th>
                                    <th style="text-align: center;">Nama Barang</th>
                                    <th style="text-align: center; width: 10%;">Qty</th>
                                    <th style="text-align: center; width: 5%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($isi->f_ic_convertion=='t'){ ?>
                                    <tr>
                                        <td>
                                            <input class="form-control" readonly type="text" id="iproduct1" name="iproduct1" value="<?= $isi->i_product; ?>">
                                        </td>
                                        <td>
                                            <input type="hidden" id="iproductmotif1" name="iproductmotif1" value="<?= $isi->i_product_motif; ?>">
                                            <input type="hidden" id="vproductretail1" name="vproductretail1" value="0">
                                            <input class="form-control" readonly type="text" id="iproductgrade1" name="iproductgrade1" value="<?= $isi->i_product_grade; ?>">
                                        </td>
                                        <td>
                                            <input class="form-control" readonly type="text" id="eproductname1" name="eproductname1" value="<?= $isi->e_product_name; ?>">
                                        </td>
                                        <td>
                                            <input class="form-control text-right" type="text" id="nicconvertion1" name="nicconvertion1" value="<?= $isi->n_ic_convertion; ?>">
                                            <input type="hidden" id="nicconvertion1x" name="nicconvertion1x" value="<?php echo $isi->n_ic_convertion; ?>">
                                        </td>
                                        <td class="text-center">&nbsp;</td>
                                    </tr>
                                <?php }else{
                                    if ($detail) {
                                        $a = 0;
                                        foreach ($detail as $row) {
                                            $a++;?>
                                            <tr>
                                                <td>
                                                    <input class="form-control" readonly type="text" id="iproduct<?= $a;?>" 
                                                    name="iproduct<?= $a;?>" value="<?= $row->i_product; ?>">
                                                </td>
                                                <td>
                                                    <input type="hidden" id="iproductmotif<?= $a;?>" name="iproductmotif<?= $a;?>" value="<?= $row->i_product_motif; ?>">
                                                    <input type="hidden" id="vproductretail<?= $a;?>" name="vproductretail<?= $a;?>" value="0">
                                                    <input class="form-control" readonly type="text" id="iproductgrade<?= $a;?>" name="iproductgrade<?= $a;?>" value="<?= $row->i_product_grade; ?>">
                                                </td>
                                                <td>
                                                    <input class="form-control" readonly type="text" id="eproductname<?= $a;?>" name="eproductname<?= $a;?>" value="<?= $row->e_product_name; ?>">
                                                </td>
                                                <td>
                                                    <input class="form-control text-right" type="text" id="nicconvertion<?= $a;?>" name="nicconvertion<?= $a;?>" value="<?= $row->n_ic_convertion; ?>">
                                                    <input type="hidden" id="nicconvertion<?= $a;?>x" name="nicconvertion<?= $a;?>x" value="<?= $row->n_ic_convertion; ?>">
                                                </td>
                                                <td class="text-center">
                                                    <?php if($status=='f' && check_role($i_menu, 4)){?>
                                                        <button type="button" onclick="hapusitem('<?= $row->i_ic_convertion.",".$row->i_product.",".$row->i_product_grade.",".$row->i_product_motif.",".$row->n_ic_convertion.",".$isi->f_ic_convertion;?>'); return false;" title="Delete" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <?php 
                                        }
                                    }
                                }?>
                            </tbody>
                        </table>
                        <h3 class="box-title text-center text-inverse"><b>Product Jadi</b></h3>
                        <table id="tabeldata2" class="table color-table success-table table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th style="text-align: center; width: 25%;">Kode Barang</th>
                                    <th style="text-align: center; width: 10%;">Grade</th>
                                    <th style="text-align: center;">Nama Barang</th>
                                    <th style="text-align: center; width: 10%;">Qty</th>
                                    <th style="text-align: center; width: 5%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($isi->f_ic_convertion=='f'){ ?>
                                        <tr>
                                            <td>
                                                <input class="form-control" readonly type="text" id="2iproduct1" name="2iproduct1" value="<?= $isi->i_product; ?>">
                                            </td>
                                            <td>
                                                <input type="hidden" id="2iproductmotif1" name="2iproductmotif1" value="<?= $isi->i_product_motif; ?>">
                                                <input type="hidden" id="2vproductretail1" name="2vproductretail1" value="0">
                                                <input class="form-control" readonly type="text" id="2iproductgrade1" name="2iproductgrade1" value="<?= $isi->i_product_grade; ?>">
                                            </td>
                                            <td>
                                                <input class="form-control" readonly type="text" id="2eproductname1" name="2eproductname1" 
                                                value="<?= $isi->e_product_name; ?>">
                                            </td>
                                            <td>
                                                <input class="form-control text-right" type="text" id="2nicconvertion1" name="2nicconvertion1" value="<?= $isi->n_ic_convertion; ?>">
                                                <input type="hidden" id="2nicconvertion1x" name="2nicconvertion1x" value="<?= $isi->n_ic_convertion; ?>">
                                            </td>
                                            <td class="text-center">&nbsp;</td>
                                        </tr>
                                    <?php 
                                }else{
                                    if ($detail) {
                                        $a = 0;
                                        foreach ($detail as $row) {
                                            $a++;?>
                                            <tr>
                                                <td>
                                                    <input class="form-control" readonly type="text" id="2iproduct<?= $a;?>" name="2iproduct<?= $a;?>" value="<?= $row->i_product; ?>">
                                                </td>
                                                <td>
                                                    <input type="hidden" id="2iproductmotif<?= $a;?>" name="2iproductmotif<?= $a;?>" value="<?= $row->i_product_motif; ?>">
                                                    <input type="hidden" id="2vproductretail<?= $a;?>" name="2vproductretail<?= $a;?>" value="0">
                                                    <input class="form-control" readonly type="text" id="2iproductgrade<?= $a;?>" name="2iproductgrade<?= $a;?>" value="<?= $row->i_product_grade; ?>">
                                                </td>
                                                <td>
                                                    <input class="form-control" readonly type="text" id="2eproductname<?= $a;?>" name="2eproductname<?= $a;?>" value="<?= $row->e_product_name; ?>">
                                                </td>
                                                <td>
                                                    <input class="form-control text-right" type="text" id="2nicconvertion<?= $a;?>" name="2nicconvertion<?= $a;?>" value="<?= $row->n_ic_convertion; ?>">
                                                    <input type="hidden" id="2nicconvertion<?= $a;?>x" name="2nicconvertion<?= $a;?>x" value="<?= $row->n_ic_convertion; ?>">
                                                </td>
                                                <td class="text-center">
                                                    <?php if($status=='f' && check_role($i_menu, 4)){?>
                                                        <button type="button" onclick="hapusitem('<?= $row->i_ic_convertion."','".$row->i_product."','".$row->i_product_grade."','".$row->i_product_motif."','".$row->n_ic_convertion."','".$isi->f_ic_convertion;?>'); return false;" title="Delete" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php }
                                    }
                                }?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php if($isi->f_ic_convertion=='f'){?>
                    <input type="hidden" name="jml1" id="jml1" value="<?php echo $a;?>">
                    <input type="hidden" name="jml2" id="jml2" value="1">
                <?php }else{ ?>
                    <input type="hidden" name="jml1" id="jml1" value="1">
                    <input type="hidden" name="jml2" id="jml2" value="<?php echo $a;?>">
                <?php } ?>
            </form>
        </div>
    </div>
</div>
</div>
</div>
<script>
    function hapusitem(i_ic_convertion,i_product,i_product_grade,i_product_motif,n_ic_convertion,f_ic_convertion) {
        swal({   
            title: "Apakah anda yakin ?",   
            text: "Anda tidak akan dapat memulihkan data ini!",   
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Ya, hapus!",   
            cancelButtonText: "Tidak, batalkan!",   
            closeOnConfirm: false,   
            closeOnCancel: false 
        }, function(isConfirm){   
            if (isConfirm) { 
                $.ajax({
                    type: "post",
                    data: {
                       'i_ic_convertion' : i_ic_convertion,
                       'i_product'       : i_product,
                       'i_product_grade' : i_product_grade,
                       'i_product_motif' : i_product_motif,
                       'n_ic_convertion' : n_ic_convertion,
                       'f_ic_convertion' : f_ic_convertion,
                   },
                   url: '<?= base_url($folder.'/cform/deleteitem'); ?>',
                   dataType: "json",
                   success: function (data) {
                    swal("Dihapus!", "Data berhasil dihapus :)", "success");
                    show('<?= $folder;?>/cform/edit/<?= $id.'/'.$status.'/'.$iperiode;?>','#main');     
                },
                error: function () {
                    swal("Maaf", "Data gagal dihapus :(", "error");
                }
            });
            } else {     
                swal("Dibatalkan", "Anda membatalkan penghapusan :)", "error");
            } 
        });
    }


    var xx  = parseFloat($('#jml1').val());
    var xxx = parseFloat($('#jml2').val());
    var uu  = xx-1;
    $("#addrow1").on("click", function () {
        if (((parseFloat($('#jml2').val())<=1) && (parseFloat($('#jml1').val())<1))||
            ((parseFloat($('#jml2').val())>=1) && (parseFloat($('#jml1').val())<1))||
            ((parseFloat($('#jml2').val())<=1) && (parseFloat($('#jml1').val())>=1))) {
            xx++;
        uu++;
        $("#tabeldata1").attr("hidden", false);
        var iproduct = $('#iproduct'+uu).val();
        count=$('#tabeldata1 tr').length;
        if ((iproduct==''||iproduct==null)&&(count>1)) {
            swal('Isi dulu yang masih kosong!!');
            xx = xx-1;
            uu = uu-1;
            return false;
        }
        $('#jml1').val(xx);
        var newRow = $("<tr>");
        var cols = "";
        cols += '<td><select id="iproduct'+xx+'" class="form-control" name="iproduct'+xx+'" onchange="getdetail1('+xx+');"><input type="hidden" id="iproductmotif'+xx+'" name="iproductmotif'+xx+'" value=""></td>';
        cols += '<td><input id="iproductgrade'+xx+'" class="form-control" name="iproductgrade'+xx+'" readonly><input type="hidden" id="vproductretail'+xx+'" name="vproductretail'+xx+'" value=""></td>';
        cols += '<td><input id="eproductname'+xx+'" class="form-control" name="eproductname'+xx+'" readonly></td>';
        cols += '<td><input id="nicconvertion'+xx+'" class="form-control" name="nicconvertion'+xx+'" onkeypress="return hanyaAngka(event);" value="0" style="text-align: right;"/></td>';
        /*cols += '<td style="text-align: center;"><button type="button" id="addrow1" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';*/
        newRow.append(cols);
        $("#tabeldata1").append(newRow);
        $('#iproduct'+xx).select2({
            placeholder: 'Cari Product',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/product/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query   = {
                        q       : params.term
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

    $("#tabeldata1").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();
        $('#jml1').val(xx);
        del();
    });

    function del() {
        obj=$('#tabeldata1 tr:visible').find('spanx');
        $.each( obj, function( key, value ) {
            id=value.id;
            $('#'+id).html(key+1);
        });
    }

    function getdetail1(id){
        ada=false;
        var iproductgrade = $('#iproduct'+id+' option:selected').text();
        var grade = iproductgrade.substr(-1);
        var a = $('#iproduct'+id).val();
        var x = $('#jml1').val();
        for(i=1;i<=x;i++){            
            if((a == $('#iproduct'+i).val()) && (grade == $('#iproductgrade'+i).val()) && (i!=x)){
                swal ("Kode : "+a+" sudah ada !!!!!");            
                ada=true;            
                break;        
            }else{            
                ada=false;             
            }
        }

        if(!ada){
            for(i=1;i<=x;i++){            
                if((a == $('#2iproduct'+i).val()) && (grade == $('#2iproductgrade'+i).val())){
                    swal ("Kode : "+a+" sudah ada !!!!!");            
                    ada=true;            
                    break;        
                }else{            
                    ada=false;             
                }
            }
        }

        if(!ada){
            $.ajax({
                type: "post",
                data: {
                    'iproduct' : a,
                    'grade'    : grade
                },
                url: '<?= base_url($folder.'/cform/getdetail'); ?>',
                dataType: "json",
                success: function (data) {
                    $('#eproductname'+id).val(data[0].e_product_name);
                    $('#iproductgrade'+id).val(data[0].i_product_grade);
                    $('#vproductretail'+id).val(data[0].v_product_retail);
                    $('#iproductmotif'+id).val(data[0].i_product_motif);
                    $('#nicconvertion'+id).focus();
                },
                error: function () {
                    swal('Data ada yang salah :)');
                }
            });
        }else{
            $('#iproduct'+id).html('');
            $('#iproduct'+id).val('');
        }
    }

    var zzz = parseFloat($('#jml1').val());
    var zz  = parseFloat($('#jml2').val());
    var ww  = zz-1;
    $("#addrow2").on("click", function () {
        if (((parseFloat($('#jml2').val())<1) && (parseFloat($('#jml1').val())<=1))||
            ((parseFloat($('#jml2').val())>=1) && (parseFloat($('#jml1').val())<=1))||
            ((parseFloat($('#jml2').val())<1) && (parseFloat($('#jml1').val())>=1))) {
            zz++;
        ww++;
        $("#tabeldata2").attr("hidden", false);
        var iproduct = $('#2iproduct'+ww).val();
        count=$('#tabeldata2 tr').length;
        if ((iproduct==''||iproduct==null)&&(count>1)) {
            swal('Isi dulu yang masih kosong!!');
            zz = zz-1;
            ww = ww-1;
            return false;
        }
        $('#jml2').val(zz);
        var newRow = $("<tr>");
        var cols = "";
        cols += '<td><select id="2iproduct'+zz+'" class="form-control" name="2iproduct'+zz+'" onchange="getdetail2('+zz+');"><input type="hidden" id="2iproductmotif'+zz+'" name="2iproductmotif'+zz+'" value=""></td>';
        cols += '<td><input id="2iproductgrade'+zz+'" class="form-control" name="2iproductgrade'+zz+'" readonly><input type="hidden" id="2vproductretail'+zz+'" name="2vproductretail'+zz+'" value=""></td>';
        cols += '<td><input id="2eproductname'+zz+'" class="form-control" name="2eproductname'+zz+'" readonly></td>';
        cols += '<td><input id="2nicconvertion'+zz+'" class="form-control" name="2nicconvertion'+zz+'" onkeypress="return hanyaAngka(event);" value="0" style="text-align: right;"/></td>';
        /*cols += '<td style="text-align: center;"><button type="button" id="addrow2" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';*/
        newRow.append(cols);
        $("#tabeldata2").append(newRow);
        $('#2iproduct'+zz).select2({
            placeholder: 'Cari Product',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/product/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query   = {
                        q       : params.term
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

    $("#tabeldata2").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();
        $('#jml2').val(zz);
        dell();
    });

    function dell() {
        obj=$('#tabeldata2 tr:visible').find('spanz');
        $.each( obj, function( key, value ) {
            id=value.id;
            $('#'+id).html(key+1);
        });
    }

    function getdetail2(id){
        ada=false;
        var iproductgrade = $('#2iproduct'+id+' option:selected').text();
        var grade = iproductgrade.substr(-1);
        var a = $('#2iproduct'+id).val();
        var x = $('#jml2').val();
        for(i=1;i<=x;i++){            
            if((a == $('#2iproduct'+i).val()) && (grade == $('#2iproductgrade'+i).val()) && (i!=x)){
                swal ("Kode : "+a+" sudah ada !!!!!");            
                ada=true;            
                break;        
            }else{            
                ada=false;             
            }
        }

        if(!ada){
            for(i=1;i<=x;i++){            
                if((a == $('#iproduct'+i).val()) && (grade == $('#iproductgrade'+i).val())){
                    swal ("Kode : "+a+" sudah ada !!!!!");            
                    ada=true;            
                    break;        
                }else{            
                    ada=false;             
                }
            }
        }

        if(!ada){
            $.ajax({
                type: "post",
                data: {
                    'iproduct' : a,
                    'grade'    : grade
                },
                url: '<?= base_url($folder.'/cform/getdetail'); ?>',
                dataType: "json",
                success: function (data) {
                    $('#2eproductname'+id).val(data[0].e_product_name);
                    $('#2iproductgrade'+id).val(data[0].i_product_grade);
                    $('#2vproductretail'+id).val(data[0].v_product_retail);
                    $('#2iproductmotif'+id).val(data[0].i_product_motif);
                    $('#2nicconvertion'+id).focus();
                },
                error: function () {
                    swal('Data ada yang salah :)');
                }
            });
        }else{
            $('#2iproduct'+id).html('');
            $('#2iproduct'+id).val('');
        }
    }

    $(document).ready(function () {
        showCalendar('.date');
    });

    function dipales(a,b){
        if(($("#dicconvertion").val()!='')) {
            if( (a=='0') || (b=='0')){
                swal('Isi data item minimal 1 !!!');
                return false;
            }else{
                for(i=1;i<=a;i++){
                    if(($("#iproduct"+i).val()=='') || ($("#iproductgrade"+i).val()=='') || ($("#eproductname"+i).val()=='') || ($("#nicconvertion"+i).val()=='')){
                        swal('Data item masih ada yang salah !!!');
                        return false;
                    }else{
                        return true;
                    } 
                }
                for(i=1;i<=b;i++){
                    if(($("#2iproduct"+i).val()=='') || ($("#2iproductgrade"+i).val()=='') || ($("#2eproductname"+i).val()=='') || ($("#2nicconvertion"+i).val()=='')){
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

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#addrow").attr("disabled", true);
        $("#refresh").attr("disabled", true);
    });

    /*function ngetang(){
        var jml = parseFloat($('#jml').val());
        var tot = 0;
        for(brs=1;brs<=jml;brs++){    
            ord = $("#nreceive"+brs).val();
            hrg  = formatulang($("#vproductmill"+brs).val());
            qty  = formatulang(ord);
            vhrg = parseFloat(hrg)*parseFloat(qty);
            $("#vtotal"+brs).val(formatcemua(vhrg));
            if($("#chk"+brs).is(':checked')){
                tot+=parseFloat(formatulang($("#vtotal"+brs).val()));
            }
        }
        $("#vsjrec").val(formatcemua(tot));
    }*/

    function cektanggal(){
        dspb = $('#dicconvertion').val();
        bspb = $('#bicconvertion').val();
        dtmp = dspb.split('-');
        per  = dtmp[2]+dtmp[1]+dtmp[0];
        bln  = dtmp[1];
        if( (bspb!='') && (dspb!='') ){
            if(bspb != bln){
                swal("Tanggal Konversi tidak boleh dalam bulan yang berbeda !!!");
                $("#dicconvertion").val('');
            }
        }
    }
</script>