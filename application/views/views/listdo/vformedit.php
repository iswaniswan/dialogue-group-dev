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
                        <label class="col-md-4">Nomor OP</label>
                        <label class="col-md-4">Nomor DO</label>
                        <label class="col-md-4">Tanggal DO</label>
                        <div class="col-sm-4">
                            <input id="iop" name="iop" class="form-control" required="" readonly value="<?= $isi->i_op;?>">
                        </div>
                        <div class="col-sm-4">
                            <input class="form-control" readonly id="ido" name="ido" value="<?= $isi->i_do; ?>">
                            <input id="idoold" name="idoold" type="hidden" value="<?= $isi->i_do; ?>">
                        </div>
                        <?php 
                        $tmp=explode("-",$isi->d_do);
                        $th=$tmp[0];
                        $bl=$tmp[1];
                        $hr=$tmp[2];
                        $ddo=$hr."-".$bl."-".$th;
                        $query3 = $this->db->query("
                            SELECT
                            sum(n_deliver*v_product_mill) AS jum_kotor
                            FROM
                            tm_do_item
                            WHERE
                            i_do = '$isi->i_do'
                            AND i_supplier = '$isi->i_supplier'
                            ");
                        if ($query3->num_rows() > 0){
                            $hasilrow = $query3->row();
                            $v_do_gross = $hasilrow->jum_kotor;
                        }
                        $query3 = $this->db->query("
                            SELECT
                            DISTINCT i_dtap
                            FROM
                            tm_dtap_item
                            WHERE
                            i_do = '$isi->i_do'
                            AND i_supplier = '$isi->i_supplier'
                            ");
                        if ($query3->num_rows() > 0){
                            $is_ada_nota = "y";
                        }else{
                            $is_ada_nota = "t";
                        }?>
                        <div class="col-sm-4">
                            <input id= "ddo" name="ddo" class="form-control date" required="" onchange="cektanggal();" readonly value="<?= $ddo; ?>">
                            <input hidden id="bdo" name="bdo" value="<?php echo $bl; ?>">
                            <input hidden id="tgldo" name="tgldo" value="<?php echo $ddo; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Pemasok</label>
                        <label class="col-md-4">Area</label>
                        <label class="col-md-4">Nilai Kotor</label>
                        <div class="col-sm-4">
                            <input class="form-control" readonly id="esuppliername" name="esuppliername" value="<?= $isi->e_supplier_name; ?>">
                            <input class="form-control" id="isupplier" name="isupplier" type="hidden" value="<?= $isi->i_supplier; ?>">
                        </div>
                        <div class="col-sm-4">
                            <input class="form-control" readonly id="eareaname" name="eareaname" value="<?= $isi->e_area_name; ?>">
                            <input id="iarea" name="iarea" type="hidden" value="<?= $isi->i_area; ?>">
                        </div>
                        <div class="col-sm-4">
                            <input class="form-control" readonly id="vdogross" name="vdogross" value="<?= number_format($v_do_gross); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-8">
                            <?php if(check_role($i_menu, 3) && $isi->f_do_cancel=='f' && $is_ada_nota == "t"){?>
                                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales(parseFloat(document.getElementById('jml').value));"> <i class="fa fa-save"></i>&nbsp;&nbsp;Update</button>
                                &nbsp;&nbsp;
                            <?php } ?>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/view/<?= $iarea."/".$dfrom."/".$dto;?>","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                            <?php if(check_role($i_menu, 3) && $isi->f_do_cancel=='f' && $is_ada_nota == "t"){?>
                                &nbsp;&nbsp;
                                <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Item</button>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table id="tabledata" class="table color-table inverse-table table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 4%;">No</th>
                                    <th class="text-center" style="width: 10%;">Kode</th>
                                    <th class="text-center" style="width: 30%;">Nama Barang</th>
                                    <th class="text-center">Ket</th>
                                    <th class="text-center">Harga</th>
                                    <th class="text-center">Qty Kirim</th>
                                    <th class="text-center">Total</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php               
                                $i=0;
                                if($detail){
                                    foreach($detail as $row){ 
                                        $i++;
                                        $pangaos = number_format($row->v_product_mill);
                                        $total   = $row->v_product_mill*$row->n_deliver;
                                        $total   = number_format($total,2);
                                        ?>
                                        <tr>
                                            <td class="text-center">
                                                <?= $i;?>
                                                <input class="form-control" readonly type="hidden" id="baris<?= $i;?>" name="baris<?= $i;?>" value="<?= $i;?>">
                                                <input class="form-control" type="hidden" id="motif<?= $i;?>" name="motif<?= $i;?>" value="<?= $row->i_product_motif;?>">
                                            </td>
                                            <td>
                                                <input class="form-control" readonly type="text" id="iproduct<?= $i;?>" name="iproduct<?= $i;?>" value="<?= $row->i_product;?>">
                                            </td>
                                            <td>
                                                <input class="form-control" readonly type="text" id="eproductname<?= $i;?>" name="eproductname<?= $i;?>" value="<?= $row->e_product_name;?>">
                                            </td>
                                            <td>
                                                <input class="form-control" type="text" id="eremark<?= $i;?>" name="eremark<?= $i;?>" value="<?= $row->e_remark;?>">
                                                <input class="form-control" readonly type="hidden" id="emotifname<?= $i;?>" name="emotifname<?= $i;?>" value="<?= $row->e_product_motifname;?>">
                                            </td>
                                            <td><input class="form-control text-right" readonly type="text" id="vproductmill<?= $i;?>" name="vproductmill<?= $i;?>" value="<?= $pangaos;?>">
                                            </td>
                                            <td>
                                                <input class="form-control text-right" type="text" id="ndeliver<?= $i;?>" name="ndeliver<?= $i;?>" value="<?= $row->n_deliver;?>"  onkeypress="return hanyaAngka(event);" onkeyup="hitungnilai(this.value); pembandingnilai('<?= $i;?>');">
                                                <input class="form-control" type="hidden" id="ntmp<?= $i;?>" name="ntmp<?= $i;?>" value="<?= $row->n_deliver;?>">
                                                <input class="form-control" type="hidden" id="ndeliverhidden<?= $i;?>" name="ndeliverhidden<?= $i;?>" value="<?= $row->n_order;?>">
                                            </td>
                                            <td>
                                                <input class="form-control text-right" readonly type="text" id="vtotal<?= $i;?>" name="vtotal<?= $i;?>" value="<?= $total;?>">
                                            </td>
                                            <td class="text-center">
                                                <?php if(check_role($i_menu,4) && $isi->f_do_cancel == 'f'){ ?>
                                                    <button type="button" onclick="hapusitem('<?= $row->i_do."','".$row->i_supplier."','".$row->i_product."','".$row->i_product_grade."','".$row->i_product_motif."','".$pangaos."','".$row->n_deliver;?>'); return false;" title="Delete" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php }
                                } ?>
                                <input type="hidden" name="jml" id="jml" value="<?= $i;?>">
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
</div>
<script>
    function hapusitem(ido,isupplier,iproduct,iproductgrade,iproductmotif,pangaos,ndeliver) {
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
                        'ido'           : ido,
                        'isupplier'     : isupplier,
                        'iproduct'      : iproduct,
                        'iproductgrade' : iproductgrade,
                        'iproductmotif' : iproductmotif,
                        'ddo'           : $('#ddo').val(),
                    },
                    url: '<?= base_url($folder.'/cform/deleteitem'); ?>',
                    dataType: "json",
                    success: function (data) {
                        swal("Dihapus!", "Data berhasil dihapus :)", "success");
                        show('<?= $folder;?>/cform/edit/<?= $id.'/'.$isupplier.'/'.$dfrom.'/'.$dto.'/'.$iarea;?>','#main');     
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

    var xx = $('#jml').val();
    $("#addrow").on("click", function () {
        xx++;
        $('#jml').val(xx);
        var newRow = $("<tr>");
        var cols = "";
        cols += '<td class="text-center">'+xx+'<input type="hidden" id="baris'+xx+'" class="form-control" name="baris'+xx+'" value="'+xx+'"><input type="hidden" id="motif'+xx+'" name="motif'+xx+'" value=""></td>';
        cols += '<td><select id="iproduct'+xx+ '" class="form-control" name="iproduct'+xx+'" onchange="detail('+xx+');" value=""></td>';
        cols += '<td><input id="eproductname'+xx+'" class="form-control" name="eproductname'+xx+'" value="" readonly></td>';
        cols += '<td><input class="form-control" type="text" id="eremark'+xx+'" name="eremark'+xx+'" value=""><input type="hidden" class="form-control" type="text" id="emotifname'+xx+'" class="form-control" name="emotifname'+xx+'" value="" readonly></td>';
        cols += '<td><input class="form-control text-right" readonly type="text" id="vproductmill'+xx+'" name="vproductmill'+xx+'" value="0"></td>';
        cols += '<td><input class="form-control text-right" readonly type="text" id="ndeliver'+xx+'" name="ndeliver'+xx+'" value="0" onkeypress="return hanyaAngka(event);"  onkeyup="hitungnilai(this.value); pembandingnilai('+xx+');"><input type="hidden" id="ndeliverhidden'+xx+'" name="ndeliverhidden'+xx+'" value=""><input type="hidden" id="ntmp'+xx+'" name="ntmp'+xx+'" value=""></td>';
        cols += '<td><input class="form-control text-right" onkeypress="return hanyaAngka(event);" type="text" id="vtotal'+xx+'" name="vtotal'+xx+'" value="0"></td>';
        cols += '<td></td>';
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
                        q   : params.term,
                        iop : $('#iop').val(),
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

    $(document).ready(function () {
        showCalendar('.date');
        $('.select2').select2();
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
                    'iop'       : $('#iop').val(),
                },
                url: '<?= base_url($folder.'/cform/detailproduct'); ?>',
                dataType: "json",
                success: function (data) {
                    $('#eproductname'+id).val(data[0].nama);
                    $('#vproductmill'+id).val(formatcemua(data[0].harga));
                    $('#motif'+id).val(data[0].motif);
                    $('#emotifname'+id).val(data[0].namamotif);
                    $('#ndeliver'+id).val(data[0].n_order);
                    $('#ndeliver'+id).focus();
                    hitungnilai(data[0].n_order);
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

    function dipales(a){
        if((document.getElementById("dreceive").value!='') &&
            (document.getElementById("iarea").value!='')) {
            if(a==0){
                swal('Isi data item minimal 1 !!!');
                return false;
            }else{
                for(i=1;i<=a;i++){
                    if((document.getElementById("iproduct"+i).value=='') || (document.getElementById("eproductname"+i).value=='') || (document.getElementById("nreceive"+i).value=='')){
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

    function pembandingnilai(a){
        var n_deliver   = $('#ndeliver'+a).val();
        var deliverasal = $('#ndeliverhidden'+a).val();

        if(parseFloat(n_deliver) > parseFloat(deliverasal)) {
            swal('Jml kirim ( '+n_deliver+' item ) tdk dpt melebihi Order ( '+deliverasal+' item )');
            $('#ndeliver'+a).val(deliverasal);
            $('#ndeliver'+a).focus();
            return false;
        }
    }

    function hitungnilai(isi){
        var jml = $('#jml').val();
        if (isNaN(parseFloat(isi))){
            swal("Input harus numerik");
        }else{
            vtot =0;
            for(i=1;i<=jml;i++){
                vhrg=formatulang($("#vproductmill"+i).val());
                nqty=formatulang($("#ndeliver"+i).val());
                vhrg=parseFloat(vhrg)*parseFloat(nqty);
                vtot=vtot+vhrg;
                $("#vtotal"+i).val(formatcemua(vhrg));
            }
            $("#vdogross").val(formatcemua(vtot));
        }
    }

    function cektanggal(){
        var tmp = $('#ddo').val();
        var dox = $('#ido').val();
        if(dox.length==14){
            atu=tmp.substring(8);
            uwa=tmp.substring(3,5);
            ddo=atu+uwa;
            ido=dox.substring(0,3)+ddo+dox.substring(7);
            $('#ido').val(ido);
        }

        dspb=$('#ddo').val();
        bspb=$('#bdo').val();
        dtmp=dspb.split('-');
        per=dtmp[2]+dtmp[1]+dtmp[0];
        bln = dtmp[1];
        if( (bspb!='') && (dspb!='') ){
            if(bspb != bln){
                swal("Tanggal DO tidak boleh dalam bulan yang berbeda !!!");
                $("#ddo").val('');
            }
        }
    }
</script>