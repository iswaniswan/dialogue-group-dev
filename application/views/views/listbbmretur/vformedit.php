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
                        <label class="col-md-12">No TTB</label>
                            <div class="col-sm-6">
                            <?php 
				                $tmp=explode("-",$isi->d_ttb);
				                $th =$tmp[0];
				                $bl =$tmp[1];
				                $hr =$tmp[2];
				                $isi->d_ttb=$hr."-".$bl."-".$th;
			                ?>
			                    <input type="hidden" id="dttb" name="dttb" value="<?php echo $isi->d_ttb; ?>"></td>
                                <input readonly id="ittb" name="ittb" class="form-control" value="<?php echo $isi->i_ttb; ?>">
                            </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">No BBM</label>
                            <div class="col-sm-6">
                                <input readonly id="ibbm" name="ibbm" class="form-control" value="<?php echo $isi->i_bbm; ?>">
                            </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Tanggal BBM</label>
                            <div class="col-sm-6">
                            <?php 
				                $tmp=explode("-",$isi->d_bbm);
				                $th =$tmp[0];
				                $bl =$tmp[1];
				                $hr =$tmp[2];
				                $isi->d_bbm=$hr."-".$bl."-".$th;
			                ?>
                                <input readonly id="dbbm" name="dbbm" class="form-control date" value="<?php echo $isi->d_bbm; ?>">
                            </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-3 col-sm-8">
                            <?php if($isi->i_kn == ''){?>
		                        <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales(parseFloat(document.getElementById('jml').value));"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                                &nbsp;&nbsp; 
                                <button type="button" id="addrow" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah Item</button>
                           <? }?>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/view/<?=$dfrom;?>/<?=$dto;?>/<?=$iarea;?>","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                            &nbsp;&nbsp;
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div id="pesan"></div>
                    <div class="form-group row">
                        <label class="col-md-12">Area</label>
                            <div class="col-sm-6">
                                <input readonly id="iarea" name="iarea" class="form-control" type="hidden" value="<?php echo $isi->i_area; ?>">
                                <input readonly id="eareaname" name="eareaname" class="form-control" type="text" value="<?php echo $isi->e_area_name; ?>">
                            </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Pelanggan</label>
                        <div class="col-sm-6">
                            <input readonly id="icustomer" name="icustomer" class="form-control" type="hidden" value="<?php echo $isi->i_customer; ?>">
                            <input readonly id="ecustomername" name="ecustomername" class="form-control" type="text" value="<?php echo $isi->e_customer_name; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Salesman</label>
                        <div class="col-sm-6">
                            <input readonly id="isalesman" name="isalesman" class="form-control" type="hidden" value="<?php echo $isi->i_salesman; ?>">
                            <input readonly id="esalesmanname" name="esalesmanname" class="form-control" type="text" value="<?php echo $isi->e_salesman_name; ?>">
                        </div>
                    </div>
                </div>
                    <div class="table-responsive">
                    <table class="table table-bordered" id="tabledata" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th style="text-align: center; width: 7%;">No</th>
                                        <th style="text-align: center; width: 12%;">Kode Barang</th>
                                        <th style="text-align: center; width: 35%;">Nama Barang</th>
                                        <th style="text-align: center; width: 10%;">Motif</th>
                                        <th style="text-align: center; width: 7%;"">TTB</th>
                                        <th style="text-align: center; width: 12%;"">Kode Barang</th>
                                        <th style="text-align: center; width: 7%;"">Nama Barang</th>
                                        <th style="text-align: center; width: 7%;"">Motif</th>
                                        <th style="text-align: center; width: 20%;"">BBM</th>
                                        <th style="text-align: center;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php               
                                    if($detail){
                                        $i=0;
                                        foreach($detail as $row){
                                                $i++;?>
                                                    <tr>
                                                        <td class="col-sm-1"> 
                                                            <input style="width:40px;" readonly type="text" class="form-control" id="baris<?=$i;?>" name="baris<?=$i;?>" value="<?=$i;?>">
                                                        </td>
                                                        <td class="col-sm-1"> 
                                                            <input style="width:100px;" readonly type="text" class="form-control" id="iproduct<?=$i; ?>" name="iproduct<?=$i; ?>" value="<?php echo $row->i_product1; ?>">
                                                        </td>
                                                        <td class="col-sm-1"> 
                                                            <input style="width:272px;" readonly type="text" class="form-control" id="eproductname<?=$i; ?>" name="eproductname<?=$i; ?>" value="<?php echo $row->e_product2_name; ?>">
                                                            <input type="hidden" class="form-control" id="iproductmotif<?=$i; ?>" name="iproductmotif<?=$i; ?>" value="<?php echo $row->i_product1_motif; ?>">
                                                            <input type="hidden" class="form-control" id="iproductgrade<?=$i; ?>" name="iproductgrade<?=$i; ?>" value="<?php echo $row->i_product1_grade; ?>">
                                                            <input type="hidden" class="form-control" id="vunitprice<?=$i; ?>" name="vunitprice<?=$i; ?>" value="<?php echo $row->v_unit_price; ?>">
                                                            <input type="hidden" class="form-control" id="inota<?=$i; ?>" name="inota<?=$i; ?>" value="<?php echo $row->i_nota; ?>">
                                                        </td>
                                                        <td class="col-sm-1"> 
                                                            <input readonly style="width:93px;"  type="text" class="form-control" id="emotifname<?=$i; ?>" name="emotifname<?=$i; ?>" value="<?php echo $row->e_product2_motifname; ?>">
                                                        </td>
                                                        <td class="col-sm-1"> 
                                                            <input readonly style="width:60px;"  type="text" class="form-control" id="nttb<?=$i; ?>" name="nttb<?=$i; ?>"  value="<?php echo $row->n_quantity; ?>">
                                                        </td>
                                                        <!--BBM-->
                                                        <td class="col-sm-1"> 
                                                            <input style="width:100px;" readonly type="text" class="form-control" id="iproductx<?=$i; ?>" name="iproductx<?=$i; ?>" value="<?php echo $row->i_product1; ?>">
                                                        </td>
                                                        
                                                        <input type="hidden" id="iproductxxx<?=$i;?>" class="form-control" name="iproductxxx<?=$i;?>" value="<?php echo $row->i_product2;?>">
                                                        <input type="hidden" id="iproductmotifxxx<?=$i;?>" class="form-control" name="iproductmotifxxx<?=$i;?>" value="<?php echo $row->i_product2_motif;?>">
                                                        <input class="form-control" type="hidden" id="iproductgradexxx<?=$i;?>" name="iproductgradexxx<?=$i;?>" value="<?php echo $row->i_product2_grade;?>">
                                                        <input class="form-control" readonly type="hidden" id="iproductx<?=$i;?>" name="iproductx<?=$i;?>" value="<?php echo $row->i_product2;?>">
                                                        
                                                        <?php foreach($detail2 as $rw){?>
                                                            <td class="col-sm-1"> 
                                                                <input style="width:272px;" readonly type="text" class="form-control" id="eproductnamex<?=$i; ?>" name="eproductnamex<?=$i; ?>" value="<?php echo $rw->e_product1_name; ?>">
                                                                <input type="hidden" class="form-control" id="iproductmotifx<?=$i; ?>" name="iproductmotifx<?=$i; ?>" value="<?php echo $row->i_product1_motif; ?>">
                                                                <input type="hidden" class="form-control" id="iproductgradex<?=$i; ?>" name="iproductgradex<?=$i; ?>" value="<?php echo $row->i_product1_grade; ?>">
                                                                <input type="hidden" class="form-control" id="vunitpricex<?=$i; ?>" name="vunitpricex<?=$i; ?>" value="<?php echo $row->v_unit_price; ?>">
                                                                <input type="hidden" class="form-control" id="inotax<?=$i; ?>" name="inotax<?=$i; ?>" value="<?php echo $row->i_nota; ?>">
                                                            </td>
                                                            <td class="col-sm-1"> 
                                                                <input readonly style="width:93px;"  type="text" class="form-control" id="emotifnamex<?=$i; ?>" name="emotifnamex<?=$i; ?>" value="<?php echo $rw->e_product1_motifname; ?>">
                                                            </td>
                                                        <?}?>
                                                        <td class="col-sm-1"> 
                                                            <input style="width:60px;"  type="text" class="form-control" id="nbbm<?=$i; ?>" name="nbbm<?=$i; ?>" value="<?php echo $row->n_quantity; ?>">
                                                        </td>
                                                        <td>
                                                            <button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button>
                                                        </td>
                                                    </tr>
                                           <? }
                                        }
                                    ?>
                                    </div>
                                    <input type="hidden" name="jml" id="jml" value="<?= $jmlitem;?>">
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
    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date');
    });

    var counter = $('#jml').val(); 
    $("#addrow").on("click", function () {
        counter++;
        $("#tabledata").attr("hidden", false);
        $('#jml').val(counter);
        count=$('#tabledata tr').length;
        var newRow = $("<tr>");
        var cols = "";
        cols += '<td><input readonly type="text" class="form-control" id="baris'+ counter + '" class="form-control" name="baris'+ counter + '" value="'+counter+'"/></td>';
        cols += '<td><input readonly class="form-control" input type="text" id="iproduct'+ counter + '" type="text" class="form-control" name="iproduct' + counter + '"/></td>';
        cols += '<td><input readonly class="form-control" input type="text" id="eproductname'+ counter + '" class="form-control" name="eproductname'+ counter + '"/><input type="hidden" id="iproductmotif'+ counter + '" class="form-control" name="iproductmotif'+ counter + '"/><input type="hidden" id="iproductgrade'+ counter + '" class="form-control" name="iproductgrade'+ counter + '"/><input type="hidden" id="vunitprice'+ counter + '" class="form-control" name="vunitprice'+ counter + '"/><input type="hidden" id="inota'+ counter + '" class="form-control" name="inota'+ counter + '"/></td>';
        cols += '<td><input readonly class="form-control" input type="text" id="emotifname'+ counter + '" class="form-control" name="emotifname'+ counter + '"/></td>';
        cols += '<td><input readonly class="form-control" input type="text" id="nttb'+ counter + '" class="form-control" name="nttb' + counter + '"/></td>';
        cols += '<td><select class="form-control" type="text" id="iproductx'+ counter + '" name="iproductx' + counter + '" onchange="getproduk('+ counter + ');"/></td>';
        cols += '<td><input readonly class="form-control" type="text" id="eproductnamex'+ counter + '" class="form-control" name="eproductnamex'+ counter + '"/><input type="hidden" id="iproductmotifx'+ counter + '" class="form-control" name="iproductmotifx'+ counter + '"/><input type="text" id="iproductgradex'+ counter + '" class="form-control" name="iproductgradex'+ counter + '"/><input type="hidden" id="vunitpricex'+ counter + '" class="form-control" name="vunitpricex'+ counter + '"/></td>';
        cols += '<td><input readonly class="form-control" type="text" id="emotifnamex'+ counter + '" class="form-control" name="emotifnamex'+ counter + '"/></td>';
        cols += '<td><input class="form-control" type="text" id="nbbm'+ counter + '" class="form-control" name="nbbm' + counter + '"/></td>';
        cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger"  value="Delete"></td>';
        newRow.append(cols);
        $("#tabledata").append(newRow); 
        $('#iproductx'+counter).select2({
            placeholder: 'Cari Product / Barang',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/databrg/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var iproduct   = $('#iproductx').val();
                    
                    var query   = {
                        q           : params.term,
                        iproduct    : iproduct
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
            $('#iproduct'+id).val(data[0].i_product);
            $('#eproductname'+id).val(data[0].e_product_name);
            $('#iproductmotif'+id).val(data[0].i_product_motif);
            $('#emotifname'+id).val(data[0].e_product_motifname);
            $('#iproductgrade'+id).val(data[0].i_product_grade);
            $('#vunitprice'+id).val(data[0].v_product_retail);
            $('#iproductmotifx'+id).val(data[0].i_product_motif);
            $('#eproductnamex'+id).val(data[0].e_product_name);
            $('#emotifnamex'+id).val(data[0].e_product_motifname);
            $('#vunitpricex'+id).val(data[0].v_product_retail);
            $('#iproductgradex'+id).val(data[0].i_product_grade);
            $('#nbbm'+id).val(0);
        },
        error: function () {
            alert('Error :)');
        }
    });
    }

    $("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
        /*xx -= 1;*/
        $('#jml').val(xx);
        del();
    });

    function del() {
        obj=$('#tabledata tr').find('spanx');
        $.each( obj, function( key, value ) {
            id=value.id;
            $('#'+id).html(key+1);
        });
    }
</script>
