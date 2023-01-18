<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-edit"></i> &nbsp;UPDATE <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/view/<?= $dfrom.'/'.$dto;?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-rotate-left"></i> Kembali</a>
            </div>

            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-6">Tanggal Order</label><label class="col-md-6">No Order</label>
                        <div class="col-sm-6">
                            <input readonly class="form-control date" id="dorderpb" required="" onchange="cektanggal()" name="dorderpb" value="<?= date('d-m-Y', strtotime($isi->d_orderpb)); ?>">
                            <input readonly type="hidden" required="" id="borderpb" name="borderpb" value="<?= date('m', strtotime($isi->d_orderpb)); ?>">
                        </div>
                        <div class="col-sm-6">
                            <input type="text" required="" readonly="" class="form-control" id="iorderpb" name="iorderpb" value="<?php if($iorderpb) echo $iorderpb; ?>">
                            <input type="hidden" id="xiorderpb" name="xiorderpb" value="<?php if($iorderpb) echo $iorderpb; ?>" maxlength=7>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">Area</label><label class="col-md-6">SPG</label>
                        <div class="col-sm-6">
                            <input id="eareaname" name="eareaname" class="form-control" value="<?= $isi->e_area_name; ?>" readonly>
                            <input type="hidden" id="iarea" name="iarea" class="form-control" value="<?= $isi->i_area; ?>">
                        </div>
                        <div class="col-sm-6">
                            <input id="espgname" name="espgname" class="form-control" value="<?= $isi->e_spg_name; ?>" readonly>
                            <input type="hidden" id="ispg" name="ispg" class="form-control" value="<?= $isi->i_spg; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Pelanggan</label>
                        <div class="col-sm-12">
                            <input readonly id="ecustomername" name="ecustomername" class="form-control" value="<?= $isi->e_customer_name; ?>">
                            <input id="icustomer" name="icustomer" type="hidden" class="form-control" value="<?= $isi->i_customer; ?>">
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-sm-offset-5 col-sm-12">
                        <?php if ($isi->f_orderpb_rekap=='f' && ($isi->i_spmb=='' || $isi->i_spmb==null)){ ?>
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales(parseFloat(document.getElementById('jml').value));"> <i class="fa fa-save"></i>&nbsp;&nbsp;Update</button>&nbsp;&nbsp;
                            <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm" ><i class="fa fa-plus"></i>&nbsp;&nbsp;Item</button>&nbsp;&nbsp;
                        <?php } ?>
                        <button type="button" onclick="show('<?= $folder; ?>/cform/view/<?= $dfrom.'/'.$dto;?>','#main'); return false;" class="btn btn-inverse btn-rounded btn-sm" ><i  class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="panel-body table-responsive">
                        <table id="tabledata" class="display table" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th style="text-align: center;">No</th>
                                    <th style="text-align: center; width: 12%;">Kode Barang</th>
                                    <th style="text-align: center; width: 35%;">Nama Barang</th>
                                    <th style="text-align: center;">Order</th>
                                    <th style="text-align: center;">Stock</th>
                                    <th style="text-align: center; width: 30%;">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($detail) {
                                    $i = 0;
                                    foreach ($detail as $row) {
                                        $i++;
                                        ?>
                                        <tr>
                                            <td style="text-align: center;">
                                                <?= $i;?>
                                                <input readonly type="hidden" id="baris<?= $i;?>" name="baris<?= $i;?>" value="<?= $i;?>">
                                                <input type="hidden" id="motif<?= $i;?>" name="motif<?= $i;?>" value="<?= $row->i_product_motif;?>">
                                            </td>
                                            <td>
                                                <input class="form-control" readonly type="text" id="iproduct<?= $i;?>" name="iproduct<?= $i;?>" value="<?= $row->i_product;?>">
                                            </td>
                                            <td>
                                                <input class="form-control" readonly type="text" id="eproductname<?= $i;?>" name="eproductname<?= $i;?>" value="<?= $row->e_product_name;?>">
                                            </td>
                                            <td>
                                                <input class="form-control" type="text" style="text-align:right;" onkeypress="return hanyaAngka(event);" id="nquantityorder<?= $i;?>" name="nquantityorder<?= $i;?>" value="<?= $row->n_quantity_order;?>" onkeyup="hitungnilaiorder();">
                                            </td>
                                            <td>
                                                <input class="form-control" type="text" style="text-align:right;" onkeypress="return hanyaAngka(event);" id="nquantitystock<?= $i;?>" name="nquantitystock<?= $i;?>" value="<?= $row->n_quantity_stock;?>" onkeyup="hitungnilaistock();">
                                            </td>
                                            <td>
                                                <input class="form-control" type="text" id="eremark<?= $i;?>" name="eremark<?= $i;?>" value="<?= $row->e_remark;?>">
                                            </td>
                                        </tr>
                                    <?php }
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <input type="hidden" name="jml" id="jml" value="<?= $i;?>">
            </form>
        </div>
    </div>
</div>
</div>
</div>

<script>
    function cektanggal() {
        var dspb = document.getElementById('dorderpb').value;
        var bspb = document.getElementById('borderpb').value;
        var dtmp = dspb.split('-');
        per=dtmp[2]+dtmp[1]+dtmp[0];
        bln = dtmp[1];
        if( (bspb!='') && (dspb!='') ){
            if(bspb != bln){
                swal("Tanggal Order tidak boleh dalam bulan yang berbeda !!!");
                document.getElementById("dorderpb").value="";
            }
        }
    }

    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date');
    });

    var counter = $('#jml').val();
    $("#addrow").on("click", function () {
        counter++;
        document.getElementById("jml").value = counter;
        var newRow = $("<tr>");
        var icustomer = $("#icustomer").val();

        var cols = "";

        cols+='<td style="text-align: center;">'+counter+'<input readonly type="hidden" id="baris'+counter+'" name="baris'+counter+'"  class="form-control" value="'+counter+'"><input type="hidden" id="motif'+counter+'" name="motif'+counter+'" value=""></td>';
        cols+='<td><select readonly type="text" id="iproduct'+counter+'" name="iproduct'+counter+'"  class="form-control select2" value="" onchange="getproduct('+counter+')"></select></td>';
        cols+='<td><input readonly readonly type="text" id="eproductname'+counter+'" name="eproductname'+counter+'"  class="form-control" value=""></td>';
        cols+='<td><input style="text-align:right; type="text" id="nquantityorder'+counter+'" name="nquantityorder'+counter+'"  class="form-control" value="" onkeyup="hitungnilaiorder();"></td>';
        cols+='<td><input style="text-align:right; type="text" id="nquantitystock'+counter+'" name="nquantitystock'+counter+'"  class="form-control" value="" onkeyup="hitungnilaistock();"></td>';
        cols+='<td><input type="text" id="eremark'+counter+'" name="eremark'+counter+'"  class="form-control" value=""></td>';
        /*cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger " value="Delete"></td>';*/

        newRow.append(cols);
        $("#tabledata").append(newRow);

        $("#tabledata").on("click", ".ibtnDel", function (event) {
            $(this).closest("tr").remove();       
            counter -= 1
            document.getElementById("jml").value = counter;
        });

        $('#iproduct'+ counter).select2({
            placeholder: 'Pilih Kode Barang',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/databarang/'); ?>'+icustomer,
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

    $("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
        counter -= 1
        document.getElementById("jml").value = counter;

    });

    function getproduct(id){
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
            var iproduct = $('#iproduct'+id).val();
            var icustomer = $("#icustomer").val();
            $.ajax({
                type: "post",
                data: {
                    'i_product': iproduct
                },
                url: '<?= base_url($folder.'/cform/getproduct/'); ?>'+icustomer,
                dataType: "json",
                success: function (data) {
                    $('#iproduct'+id).val(data[0].i_product);
                    $('#eproductname'+id).val(data[0].e_product_name);
                    $('#motif'+id).val(data[0].i_product_motif);
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

    function hitungnilaiorder(){
        jml=document.getElementById("jml").value;
        for(i=1;i<=jml;i++){
            qty=document.getElementById("nquantityorder"+i).value;
            if (isNaN(parseFloat(qty))){
                swal("Input harus numerik");
                document.getElementById("nquantityorder"+i).value='0';
                break;
            }
        }
    }
    function hitungnilaistock(){
        jml=document.getElementById("jml").value;
        for(i=1;i<=jml;i++){
            qty=document.getElementById("nquantitystock"+i).value;
            if (isNaN(parseFloat(qty))){
                swal("Input harus numerik");
                document.getElementById("nquantitystock"+i).value='0';
                break;
            }
        }
    }

    function dipales(a){
        if((document.getElementById("dorderpb").value!='')) {
            if(a==0){
                swal('Isi data item minimal 1 !!!');
                return false;
            }else{
                for(i=1;i<=a;i++){
                    if((document.getElementById("iproduct"+i).value=='') || (document.getElementById("eproductname"+i).value=='') || (document.getElementById("nquantityorder"+i).value=='') || (document.getElementById("nquantitystock"+i).value=='')){
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
    });
</script>