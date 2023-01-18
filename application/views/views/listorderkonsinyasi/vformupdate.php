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
                <div class="col-md-6">
                    <div class="form-group row">
                        <?php 
                        $tmp=explode("-",$isi->d_orderpb);
                        $th=$tmp[0];
                        $bl=$tmp[1];
                        $hr=$tmp[2];
                        $thbl = $th.$bl;
                        $isi->d_orderpb=$hr."-".$bl."-".$th;
                        ?>
                        <label class="col-md-6">Nomor Order</label>
                        <label class="col-md-6">Tanggal</label>
                        <div class="col-sm-6">
                            <input readonly class="form-control" id="iorderpb" name="iorderpb" value="<?= $iorderpb; ?>">
                            <input type="hidden" id="xiorderpb" name="xiorderpb" value="<?php if($iorderpb) echo $iorderpb; ?>" maxlength=7>
                        </div>
                        <div class="col-sm-6">
                            <input readonly class="form-control date" id="dorderpb" name="dorderpb" value="<?= $isi->d_orderpb; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">Area</label>
                        <label class="col-md-6">SPG</label>
                        <div class="col-sm-6">
                            <input readonly id="eareaname" class="form-control" name="eareaname" value="<?= $isi->e_area_name; ?>">
                            <input id="iarea" name="iarea" class="form-control" type="hidden" value="<?= $isi->i_area; ?>">
                        </div>
                        <div class="col-sm-6">
                            <input readonly id="espgname" name="espgname" class="form-control" value="<?= $isi->e_spg_name; ?>">
                            <input id="ispg" name="ispg" type="hidden" class="form-control" value="<?= $isi->i_spg; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Pelanggan</label>
                        <div class="col-sm-12">
                            <input readonly id="ecustomername" name="ecustomername" class="form-control" value="<?= $isi->e_customer_name; ?>">
                            <input id="icustomer" name="icustomer" type="hidden" class="form-control" value="<?= $isi->i_customer; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <?php if($thbl < $periode->i_periode){

                            }else{
                                if (check_role($i_menu, 3) && $isi->f_orderpb_rekap=='f' && ($isi->i_spmb=='' || $isi->i_spmb==null)){ ?>
                                    <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales(parseFloat(document.getElementById('jml').value));"> <i class="fa fa-save"></i>&nbsp;&nbsp;Update</button>&nbsp;&nbsp;
                                    <?php 
                                }
                            }?>
                            <?php if (check_role($i_menu, 3) && $isi->f_orderpb_rekap=='f' && ($isi->i_spmb=='' || $isi->i_spmb==null)){ ?>
                                <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm" ><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah</button>&nbsp;&nbsp;
                            <?php }?>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/view/<?= $dfrom.'/'.$dto;?>","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table id="tabledata" class="table color-table inverse-table table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 4%;">No</th>
                                    <th class="text-center" style="width: 15%;">Kode Barang</th>
                                    <th class="text-center" style="width: 35%;">Nama Barang</th>
                                    <th class="text-center">Order</th>
                                    <th class="text-center">Stock</th>
                                    <th class="text-center">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($detail) {
                                    $i = 0;
                                    foreach ($detail as $row) {
                                        $i++;?>
                                        <tr>
                                            <td class="text-center">
                                                <?= $i;?>
                                                <input type="hidden" readonly type="text" id="baris<?= $i;?>" name="baris<?= $i;?>" value="<?= $i;?>">
                                                <input type="hidden" id="motif<?= $i;?>" name="motif<?= $i;?>" value="<?= $row->i_product_motif;?>">
                                            </td>
                                            <td>
                                                <input class="form-control" readonly type="text" id="iproduct<?= $i;?>" name="iproduct<?= $i;?>" value="<?= $row->i_product;?>">
                                            </td>
                                            <td>
                                                <input class="form-control" readonly type="text" id="eproductname<?= $i;?>" name="eproductname<?= $i;?>" value="<?= $row->e_product_name;?>">
                                            </td>
                                            <td>
                                                <input class="form-control text-right" type="text" id="nquantityorder<?= $i;?>" name="nquantityorder<?= $i;?>" value="<?= $row->n_quantity_order;?>" onkeypress="return hanyaAngka(event);" onkeyup="hitungnilaiorder(<?= $i;?>);">
                                            </td>
                                            <td>
                                                <input class="form-control text-right" type="text" id="nquantitystock<?= $i;?>" name="nquantitystock<?= $i;?>" value="<?= $row->n_quantity_stock;?>" onkeypress="return hanyaAngka(event);" onkeyup="hitungnilaistock(<?= $i;?>);">
                                            </td>
                                            <td>
                                                <input class="form-control" type="text" id="eremark<?= $i;?>" name="eremark<?= $i;?>" value="<?= $row->e_remark;?>">
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
    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date',0);
    });

    var counter = $('#jml').val();
    $("#addrow").on("click", function () {
        counter++;
        document.getElementById("jml").value = counter;
        var newRow = $("<tr>");
        var icustomer = $("#icustomer").val();
        
        var cols = "";
        
        cols+='<td class="text-center">'+counter+'<input readonly type="hidden" id="baris'+counter+'" name="baris'+counter+'" class="form-control" value="'+counter+'"><input type="hidden" id="motif'+counter+'" name="motif'+counter+'" value=""></td>';
        cols+='<td><select readonly type="text" id="iproduct'+counter+'" name="iproduct'+counter+'"  class="form-control select2" value="" onchange="getproduct('+counter+')"></select></td>';
        cols+='<td><input readonly readonly type="text" id="eproductname'+counter+'" name="eproductname'+counter+'"  class="form-control" value=""></td>';
        cols+='<td><input type="text" id="nquantityorder'+counter+'" name="nquantityorder'+counter+'"  class="form-control text-right" value="" onkeypress="return hanyaAngka(event);" onkeyup="hitungnilaiorder();"></td>';
        cols+='<td><input style="text-align:right; width:100px;" type="text" id="nquantitystock'+counter+'" name="nquantitystock'+counter+'" class="form-control text-right" value="" onkeypress="return hanyaAngka(event);" onkeyup="hitungnilaistock();"></td>';
        cols+='<td><input style="text-align:right; width:100px;" type="text" id="eremark'+counter+'" name="eremark'+counter+'"  class="form-control" value=""></td>';
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
                $('#nquantityorder'+id).focus();
            },
            error: function () {
                alert('Error :)');
            }
        });
    }

    function hitungnilaiorder(){
        jml=document.getElementById("jml").value;
        for(i=1;i<=jml;i++){
            qty=document.getElementById("nquantityorder"+i).value;
            if (isNaN(parseFloat(qty))){
                alert("Input harus numerik");
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
                alert("Input harus numerik");
                document.getElementById("nquantitystock"+i).value='0';
                break;
            }
        }
    }

    function dipales(a){
        cek='false';
        if((document.getElementById("dorderpb").value!='')) {
          if(a==0){
              alert('Isi data item minimal 1 !!!');
          }else{
              for(i=1;i<=a;i++){
                if((document.getElementById("iproduct"+i).value=='') ||
                   (document.getElementById("eproductname"+i).value=='') ||
                   (document.getElementById("nquantityorder"+i).value=='') ||
                   (document.getElementById("nquantitystock"+i).value=='')){
                 alert('Data item masih ada yang salah !!!');
             exit();
             cek='false';
         }else{
             cek='true';	
         } 
     }
 }
 if(cek=='true'){
  document.getElementById("login").disabled=true;
  document.getElementById("cmdtambahitem").disabled=true;
}else{
   document.getElementById("login").disabled=false;
}
}else{
   alert('Data header masih ada yang salah !!!');
}
}
</script>