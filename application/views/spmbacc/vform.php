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
                        <label class="col-md-6">No SPMB</label><label class="col-md-6">Tanggal SPMB</label>
                        <div class="col-sm-6">
                            <input id="ispmb" name="ispmb" class="form-control" required="" readonly value="<?= $isi->i_spmb;?>">
                        </div>
                        <div class="col-sm-6">
                            <input id= "dspmbx" name="dspmbx" class="form-control" readonly value="<?= $isi->dspmb;?>">
                            <input type="hidden" id= "dspmb" name="dspmb" class="form-control" readonly value="<?= $isi->d_spmb;?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">SPMB Lama</label><label class="col-md-6">Nilai Acc</label>
                        <div class="col-sm-6">
                            <input id="ispmbold" name="ispmbold" class="form-control" value="<?= $isi->i_spmb_old; ?>">
                        </div>
                        <div class="col-sm-6">
                            <input id= "vacc" name="vacc" class="form-control" readonly value="0">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-8">
                            <button type="submit" id="submit" onclick="return dipales();" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>
                                &nbsp;&nbsp;Simpan
                            </button>&nbsp;&nbsp;
                            <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>
                                &nbsp;&nbsp;Item
                            </button>&nbsp;&nbsp; 
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i
                                class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali
                            </button>
                        </div>
                    </div>
                </div> 
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-12">
                            <input id= "eareaname" name="eareaname" class="form-control" required="" readonly value="<?= $isi->e_area_name;?>">
                            <input id="iarea" name="iarea" type="hidden" value="<?= $isi->i_area; ?>">
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <input id="eremark" name="eremark" class="form-control" value="<?= $isi->e_remark; ?>">
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="tabledata" class="table table-bordered" cellspacing="0">
                        <thead>
                            <tr>
                                <th style="text-align: center; width: 4%;">No</th>
                                <th style="text-align: center; width: 9%;">Kode</th>
                                <th style="text-align: center; width: 30%;">Nama Barang</th>
                                <th style="text-align: center; width: 5%;">Motif</th>
                                <th style="text-align: center;">Jml Pesan</th>
                                <th style="text-align: center;">Jml Acc</th>
                                <th style="text-align: center;">Jml Stk</th>
                                <th style="text-align: center;">Jml Rata2</th>
                                <th style="text-align: center;">Nilai Rata2</th>
                                <th style="text-align: center;">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($detail) {
                                $i = 0;
                                foreach ($detail as $row) { 
                                    $i++;
                                    $pangaos = number_format($row->v_unit_price,2);
                                    $total   = $row->v_unit_price*$row->n_order;
                                    $total   = number_format($total,2);
                                    if($row->n_acc=='' || $row->n_acc==0){
                                        $row->n_acc = $row->n_order; 
                                    }
                                    if($isi->i_area=='00'){
                                        $store='AA';
                                    }else{
                                        $store=$isi->i_area;
                                    }
                                    $nstock = 0;
                                    $query = $this->mmaster->stock($row->i_product, $store);
                                    if ($query->num_rows() > 0){
                                        foreach($query->result() as $tt){
                                            $nstock = number_format($tt->n_quantity_stock);
                                        }
                                    }
                                    if ($row->vrata!=''||$row->vrata!=null) {
                                        $vrata = number_format($row->vrata);
                                    }else{
                                        $vrata = 0;
                                    }
                                    if ($row->nrata!=''||$row->nrata!=null) {
                                        $nrata = number_format($row->nrata);
                                    }else{
                                        $nrata = 0;
                                    }
                                    ?>
                                    <tr>
                                        <td style="text-align: center;"><?= $i;?>
                                        <input type="hidden" class="form-control" id="baris<?=$i;?>" name="baris<?=$i;?>" value="<?= $i;?>">
                                        <input type="hidden" id="motif<?=$i;?>" name="motif<?=$i;?>" value="<?= $row->i_product_motif; ?>">
                                    </td>
                                    <td>
                                        <input class="form-control" readonly id="iproduct<?=$i;?>" name="iproduct<?=$i;?>" value="<?= $row->i_product; ?>">
                                    </td>
                                    <td>
                                        <input readonly class="form-control" id="eproductname<?=$i;?>" name="eproductname<?=$i;?>" value="<?= $row->e_product_name; ?>">
                                    </td>
                                    <td>
                                        <input class="form-control" readonly id="emotifname<?=$i;?>" name="emotifname<?=$i;?>" value="<?= $row->e_product_motifname; ?>">
                                        <input type="hidden" id="vproductmill<?=$i;?>" name="vproductmill<?=$i;?>" value="<?= $pangaos;?>">
                                    </td>
                                    <td>
                                        <input style="text-align: right;" class="form-control" readonly id="norder<?=$i;?>" name="norder<?=$i;?>" value="<?= $row->n_order;?>">
                                    </td>
                                    <td>
                                        <input style="text-align: right;" class="form-control" id="nacc<?=$i;?>" name="nacc<?=$i;?>" value="<?= $row->n_acc;?>" onkeypress="return hanyaAngka(event);" onkeyup="hetang();">
                                    </td>
                                    <td>
                                        <input style="text-align: right;" class="form-control" readonly id="jmlstock<?=$i;?>" name="jmlstock<?=$i;?>" value="<?= $nstock;?>">
                                    </td>
                                    <td>
                                        <input style="text-align: right;" class="form-control" readonly id="jmlrata<?=$i;?>" name="jmlrata<?=$i;?>" value="<?= $nrata;?>">
                                    </td>
                                    <td>
                                        <input style="text-align: right;" class="form-control" readonly id="nilairata<?=$i;?>" name="nilairata<?=$i;?>" value="<?= $vrata;?>">
                                    </td>
                                    <td>
                                        <input class="form-control" id="eremark<?=$i;?>" name="eremark<?=$i;?>" value="<?= $row->e_remark;?>">
                                        <input type="hidden" id="vtotal<?=$i;?>" name="vtotal<?=$i;?>" value="<?= $total;?>">
                                    </td>
                                </tr>
                            <?php  } ?>
                            <input type="hidden" readonly name="jml" id="jml" value="<?= $i;?>">
                        <?php } ?>
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
    var uu = xx-1;
    $("#addrow").on("click", function () {
        if (xx<=30) {
            xx++;
            uu++;

            $("#tabledata").attr("hidden", false);
            var iproduct = $('#iproduct'+uu).val();
            count=$('#tabledata tr').length;
            if ((iproduct==''||iproduct==null)&&(count>1)) {
                swal('Isi dulu yang masih kosong!!');
                xx = xx-1;
                uu = uu-1;
                return false;
            }
            $('#jml').val(xx);
            var newRow = $("<tr>");
            var cols = "";
            cols += '<td style="text-align: center;"><spanx id="snum'+xx+'">'+count+'</spanx><input type="hidden" id="baris'+xx+'" class="form-control" name="baris'+xx+'" value="'+xx+'"><input type="hidden" id="motif'+xx+'" name="motif'+xx+'" value=""></td>';
            cols += '<td><select id="iproduct'+xx+'" class="form-control" name="iproduct'+xx+'" onchange="getdetailproduct('+xx+')";></select></td>';
            cols += '<td><input readonly id="eproductname'+xx+'" class="form-control" name="eproductname'+xx+'"></td>';
            cols += '<td><input readonly id="emotifname'+xx+'" class="form-control" name="emotifname'+xx+'"><input type="hidden" id="vproductmill'+xx+'" value="0" class="form-control" name="vproductmill'+xx+'"></td>';
            cols += '<td><input style="text-align: right;" id="norder'+xx+'" class="form-control" name="norder'+xx+'" onkeypress="return hanyaAngka(event);" onkeyup="ngetang('+xx+');" value="0" disabled></td>';
            cols += '<td><input style="text-align: right;" id="nacc'+xx+'" class="form-control" name="nacc'+xx+'" onkeypress="return hanyaAngka(event);" onkeyup="ngetang('+xx+');" value="0" disabled></td>';
            cols += '<td><input style="text-align: right;" id="nstock'+xx+'" class="form-control" name="nstock'+xx+'" readonly value="0"></td>';
            cols += '<td><input style="text-align: right;" id="jmlrata'+xx+'" class="form-control" name="jmlrata'+xx+'" readonly value="0"></td>';
            cols += '<td><input style="text-align: right;" id="nilairata'+xx+'" class="form-control" name="nilairata'+xx+'" readonly value="0"></td>';
            cols += '<td><input id="eremark'+xx+'" class="form-control" name="eremark'+xx+'"><input type="hidden" id="vtotal'+xx+'" class="form-control" name="vtotal'+xx+'" value="0"></td>';
            /*cols += '<td><button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';*/
            newRow.append(cols);
            $("#tabledata").append(newRow);
            $('#iproduct'+xx).select2({
                placeholder: 'Cari Kode/Nama',
                allowClear: true,
                ajax: {
                    url: '<?= base_url($folder.'/cform/getproduct/'); ?>',
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

    function del() {
        obj=$('#tabledata tr:visible').find('spanx');
        $.each( obj, function( key, value ) {
            id=value.id;
            $('#'+id).html(key+1);
        });
    }

    function getdetailproduct(id){
        ada=false;
        var a = $('#iproduct'+id).val();
        var x = $('#jml').val();
        for(i=1;i<=x;i++){
            if((a == $('#iproduct'+i).val()) && (i!=x)){
                swal ("kode Barang : "+a+" sudah ada !!!!!");            
                ada=true;            
                break;        
            }else{            
                ada=false;             
            }
        }

        if(!ada){
            var iproduct = $('#iproduct'+id).val();
            $.ajax({
                type: "post",
                data: {
                    'iproduct'  : iproduct
                },
                url: '<?= base_url($folder.'/cform/getdetailproduct'); ?>',
                dataType: "json",
                success: function (data) {
                    $('#eproductname'+id).val(data[0].nama);
                    $('#motif'+id).val(data[0].motif);
                    $('#emotifname'+id).val(data[0].namamotif);
                    $('#vproductmill'+id).val(data[0].harga);
                    $('#jmlrata'+id).val(formatcemua(data[0].nrata));
                    $('#nilairata'+id).val(formatcemua(data[0].vrata));
                    $('#norder'+id).attr('disabled', false);
                    $('#nacc'+id).attr('disabled', false);
                    /*hitungnilai(id);*/
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

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#addrow").attr("disabled", true);
    });


    function hetang(){
        jml=document.getElementById("jml").value;
        if (isNaN(parseFloat(jml))){
            alert("Input harus numerik");
        }else{
            total=0;
            for(i=1;i<=jml;i++){
                hrg=formatulang(document.getElementById("vproductmill"+i).value);
                qty=formatulang(document.getElementById("nacc"+i).value);        
                harga=parseFloat(hrg)*parseFloat(qty);
                document.getElementById("vtotal"+i).value=formatcemua(harga);
                total=total+harga;
            }
            document.getElementById("vacc").value=formatcemua(total);
        }
    }    

    $(document).ready(function () {
        hetang();
    });

    function ngetang(brs){
        acc=document.getElementById("nacc"+brs).value;
        if (isNaN(parseFloat(acc))){
            alert("Input harus numerik");
        }else{
            hrg=formatulang(document.getElementById("vproductmill"+brs).value);
            qty=formatulang(acc);
            vhrg=parseFloat(hrg)*parseFloat(qty);
            document.getElementById("vtotal"+brs).value=formatcemua(vhrg);
            total=total+vhrg;
        }
        document.getElementById("vacc").value=formatcemua(total);
    }

    function cektanggal() {
        var ddkb = $('#ddkb').val();
        var dsjreceive = $('#dsjreceive').val();
        if (dsjreceive<ddkb && (ddkb!=''||ddkb!=null)) {
            swal('Tidak boleh lebih kecil dari tanggal DKB!');
            $('#dsjreceive').val('');
        }
    }

    function dipales(){
        if(document.getElementById("dsjreceive").value==''){
            swal('Tanggal terima belum diisi!');
            return false;
        }
    }
</script>