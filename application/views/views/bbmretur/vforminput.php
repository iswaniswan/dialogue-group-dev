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
                <div class="col-md-6">
                    <div id="pesan"></div>
                    <?php if($data){
                    ?>
                    <div class="form-group">
                        <label class="col-md-12">No TTB</label>
                        <div class="col-sm-5">
                            <input type="text" name="ittb" class="form-control" value="<?= $data->i_ttb;?>" readonly> 
                            <input type="hidden" id= "dttb" name="dttb" class="form-control date" value="<?= $data->d_ttb;?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-5">
                            <input readonly id="eareaname" type="text" name="eareaname" class="form-control" value="<?= $data->e_area_name;?>">
                            <input id="iarea" name="iarea" type="hidden" class="form-control" value="<?= $data->i_area;?>">
                            <input readonly id="ibbm" name="ibbm" type="hidden" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Tanggal BBM</label>
                        <div class="col-sm-5">
                            <input readonly id="dbbm" type="text" name="dbbm" class="form-control date">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-info btn-rounded btn-sm"> <i
                                    class="fa fa-plus"></i>&nbsp;&nbsp;Simpan</button> &nbsp;&nbsp;<button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp; Tambah Item </button>
                        </div>
                    </div>
                    <div class="col-md-12">
                    <?php
                    }else{                           
                            $read = "disabled";
                            echo "<table class=\"table table-striped bottom\" style=\"width:100%;\"><tr><td colspan=\"6\" style=\"text-align:center;\">Maaf Tidak Ada Data!</td></tr></table>";
                    }?> 
                    </div>
                </div>
                <div class="col-md-6">
                <div id="pesan"></div>
                    <?php if($data){
                    ?>
                    <div class="form-group">
                        <label class="col-md-12">Pelanggan</label>
                        <div class="col-sm-5">
                            <input readonly id="ecustomername" type="text" name="ecustomername" class="form-control" value="<?= $data->e_customer_name;?>">
                            <input id="icustomer" name="icustomer" type="hidden" class="form-control" value="<?= $data->i_customer;?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Salesman</label>
                        <div class="col-sm-5">
                            <input readonly id="esalesmanname" type="text" name="esalesmanname" class="form-control" value="<?= $data->e_salesman_name;?>">
                            <input id="isalesman" name="isalesman" type="hidden" class="form-control" value="<?= $data->i_salesman;?>">
                        </div>
                    </div>
                    <div class="col-md-12">
                    <?php
                    }else{                           
                            $read = "disabled";
                            echo "<table class=\"table table-striped bottom\" style=\"width:100%;\"><tr><td colspan=\"6\" style=\"text-align:center;\">Maaf Tidak Ada Data!</td></tr></table>";
                    }?> 
                    </div>
                </div>
                    <div class="panel-body table-responsive">
                            <table id="tabledata" class="display table" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Barang</th>
                                        <th>Nama Barang</th>
                                        <th>Motif</th>
                                        <th>TTB</th>
                                        <th>Kode Barang</th>
                                        <th>Nama Barang</th>
                                        <th>Motif</th>
                                        <th>BBM</th>
                                    </tr>
                                </thead>
                                <?php $i=0; ?>
                                <?php if($data1!='') {?>
                                <tbody>
                                <? 
                                    $i=0;
                                    foreach($data1 as $row){
                                    $i++;
                                ?>
                                <tr>
                                    <td class="col-sm-1"> 
                                        <input style="width:40px;" readonly type="text" class="form-control" id="baris<?=$i;?>" name="baris<?=$i;?>" value="<?=$i;?>">
                                    </td>
                                    <td class="col-sm-1"> 
                                        <input style="width:100px;" readonly type="text" class="form-control" id="iproduct<?=$i; ?>" name="iproduct<?=$i; ?>" value="<?php echo $row->i_product1; ?>">
                                    </td>
                                    <td class="col-sm-1"> 
                                        <input style="width:272px;" readonly type="text" class="form-control" id="eproductname<?=$i; ?>" name="eproductname<?=$i; ?>" value="<?php echo $row->e_product_name; ?>">
                                        <input type="hidden" class="form-control" id="iproductmotif<?=$i; ?>" name="iproductmotif<?=$i; ?>" value="<?php echo $row->i_product1_motif; ?>">
                                        <input type="hidden" class="form-control" id="iproductgrade<?=$i; ?>" name="iproductgrade<?=$i; ?>" value="<?php echo $row->i_product1_grade; ?>">
                                        <input type="hidden" class="form-control" id="vunitprice<?=$i; ?>" name="vunitprice<?=$i; ?>" value="<?php echo $row->v_unit_price; ?>">
                                        <input type="hidden" class="form-control" id="inota<?=$i; ?>" name="inota<?=$i; ?>" value="<?php echo $row->i_nota; ?>">
                                    </td>
                                    <td class="col-sm-1"> 
                                        <input readonly style="width:93px;"  type="text" class="form-control" id="emotifname<?=$i; ?>" name="emotifname<?=$i; ?>" value="<?php echo $row->e_product_motifname; ?>">
                                    </td>
                                    <td class="col-sm-1"> 
                                        <input readonly style="width:60px;"  type="text" class="form-control" id="nttb<?=$i; ?>" name="nttb<?=$i; ?>"  value="<?php echo $row->n_quantity; ?>">
                                    </td>
                                    <td class="col-sm-1"> 
                                        <input style="width:100px;" readonly type="text" class="form-control" id="iproductx<?=$i; ?>" name="iproductx<?=$i; ?>" value="<?php echo $row->i_product1; ?>">
                                    </td>
                                    <td class="col-sm-1"> 
                                        <input style="width:272px;" readonly type="text" class="form-control" id="eproductnamex<?=$i; ?>" name="eproductnamex<?=$i; ?>" value="<?php echo $row->e_product_name; ?>">
                                        <input type="hidden" class="form-control" id="iproductmotifx<?=$i; ?>" name="iproductmotifx<?=$i; ?>" value="<?php echo $row->i_product1_motif; ?>">
                                        <input type="hidden" class="form-control" id="iproductgradex<?=$i; ?>" name="iproductgradex<?=$i; ?>" value="<?php echo $row->i_product1_grade; ?>">
                                        <input type="hidden" class="form-control" id="vunitpricex<?=$i; ?>" name="vunitpricex<?=$i; ?>" value="<?php echo $row->v_unit_price; ?>">
                                        <input type="hidden" class="form-control" id="inotax<?=$i; ?>" name="inotax<?=$i; ?>" value="<?php echo $row->i_nota; ?>">
                                    </td>
                                    <td class="col-sm-1"> 
                                        <input readonly style="width:93px;"  type="text" class="form-control" id="emotifnamex<?=$i; ?>" name="emotifnamex<?=$i; ?>" value="<?php echo $row->e_product_motifname; ?>">
                                    </td>
                                    <td class="col-sm-1"> 
                                        <input style="width:60px;"  type="text" class="form-control" id="nbbm<?=$i; ?>" name="nbbm<?=$i; ?>" value="<?php echo $row->n_quantity; ?>">
                                    </td>
                                </tr>
                                    <?}?>
                                </tbody>
                            </table>
                            <?}?>
                        </div>
                        <div id="pesan"></div>
                            <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">
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

    $(document).ready(function () {
        $('#ittb').select2({
        placeholder: 'Pilih TTB',
        allowClear: true,
        ajax: {
          url: '<?= base_url($folder.'/cform/data_ttb'); ?>',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            return {
              results: data
            };
          },
          cache: true
        }
      }).on("change", function(e) {
        var kode = $('#ittb').text();
     });
    });

    function get(id) {
        /*alert(iarea);*/
        $.ajax({
            type: "post",
            data: {
                'i_ttb': id
            },
            url: '<?= base_url($folder.'/cform/get_ttb'); ?>',
            dataType: "json",
            success: function (data) {
                $('#iarea').val(data[0].i_area);
                $('#eareaname').val(data[0].e_area_name);
                $('#icustomer').val(data[0].i_customer);
                $('#ecustomername').val(data[0].e_customer_name);
                $('#isalesman').val(data[0].i_salesman);
                $('#esalesmanname').val(data[0].e_salesman_name);
            },
            error: function () {
                alert('Error :)');
            }
        });
    }

    var counter=document.getElementById("jml").value;

    $("#addrow").on("click", function () {
        counter++;
        document.getElementById("jml").value = counter;
        var newRow = $("<tr>");
        
        var cols = "";
        
        cols += '<td><input readonly type="text" class="form-control" id="baris'+ counter + '" class="form-control" name="baris'+ counter + '" value="'+counter+'"/></td>';
        cols += '<td><input readonly class="form-control" input type="text" id="iproduct'+ counter + '" type="text" class="form-control" name="iproduct' + counter + '"/></td>';
        cols += '<td><input readonly class="form-control" input type="text" id="eproductname'+ counter + '" class="form-control" name="eproductname'+ counter + '"/><input type="hidden" id="iproductmotif'+ counter + '" class="form-control" name="iproductmotif'+ counter + '"/><input type="hidden" id="iproductgrade'+ counter + '" class="form-control" name="iproductgrade'+ counter + '"/><input type="hidden" id="vunitprice'+ counter + '" class="form-control" name="vunitprice'+ counter + '"/><input type="hidden" id="inota'+ counter + '" class="form-control" name="inota'+ counter + '"/></td>';
        cols += '<td><input readonly class="form-control" input type="text" id="emotifname'+ counter + '" class="form-control" name="emotifname'+ counter + '"/></td>';
        cols += '<td><input readonly class="form-control" input type="text" id="nttb'+ counter + '" class="form-control" name="nttb' + counter + '"/></td>';
        cols += '<td><select class="form-control" type="text" id="iproductx'+ counter + '" name="iproductx' + counter + '" onchange="getproduk('+ counter + ');"/></td>';
        cols += '<td><input readonly class="form-control" type="text" id="eproductnamex'+ counter + '" class="form-control" name="eproductnamex'+ counter + '"/><input type="hidden" id="iproductmotifx'+ counter + '" class="form-control" name="iproductmotifx'+ counter + '"/><input type="hidden" id="iproductgradex'+ counter + '" class="form-control" name="iproductgradex'+ counter + '"/><input type="hidden" id="vunitpricex'+ counter + '" class="form-control" name="vunitpricex'+ counter + '"/></td>';
        cols += '<td><input readonly class="form-control" type="text" id="emotifnamex'+ counter + '" class="form-control" name="emotifnamex'+ counter + '"/></td>';
        cols += '<td><input class="form-control" type="text" id="nbbm'+ counter + '" class="form-control" name="nbbm' + counter + '"/></td>';
        cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger"  value="Delete"></td>';
        newRow.append(cols);
        $("#tabledata").append(newRow);

        $("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
        counter -= 1
        document.getElementById("jml").value = counter;
        });

        $('#iproductx'+ counter).select2({
        placeholder: 'Pilih Kode Barang',
        allowClear: true,
        ajax: {
          url: '<?= base_url($folder.'/cform/databrg'); ?>',
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

    function getproduk(id){
        var iproduct = $('#iproductx'+id).val();
        $.ajax({
        type: "post",
        data: {
            'i_product': iproduct
        },
        url: '<?= base_url($folder.'/cform/getproduct'); ?>',
        dataType: "json",
        
        success: function (data) {
            $('#eproductname'+id).val(data[0].e_product_name);
            $('#iproductmotif'+id).val(data[0].i_product_motif);
            $('#emotifname'+id).val(data[0].e_product_motifname);
            $('#iproductmotifx'+id).val(data[0].i_product_motif);
            $('#eproductnamex'+id).val(data[0].e_product_name);
            $('#emotifnamex'+id).val(data[0].e_product_motifname);
            $('#vunitprice'+id).val(data[0].v_product_retail);
            $('#vunitpricex'+id).val(data[0].v_product_retail);
            $('#iproductgrade'+id).val(data[0].i_product_grade);
            $('#iproductgrade'+id).val(data[0].i_product_grade);
            $('#nbbm'+id).val(0);
        },
        error: function () {
            alert('Error :)');
        }
    });
    }
</script>