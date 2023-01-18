<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-edit"></i> &nbsp; <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-rotate-left"></i> <b>Kembali</b></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-6">Kode Promo</label><label class="col-md-6">Tanggal Promo</label>
                        <div class="col-sm-6">
                            <input id="ipromo" name="ipromo" type="text" class="form-control" value="<?= $isi->i_promo;?>" readonly>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" readonly id= "dpromo" name="dpromo" class="form-control date" value="<?= date('d-m-Y', strtotime($isi->d_promo));?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <input id="epromoname" required="" class="form-control" name="epromoname" onkeyup="gede(this);" value="<?php echo $isi->e_promo_name;?>">
                        </div>
                    </div>            
                    <div class="form-group row">
                        <label class="col-md-12">Periode Promo</label>
                        <label class="col-md-6">Dari Tanggal</label><label class="col-md-6">Sampai Tanggal</label>
                        <div class="col-sm-6">
                            <input type="text" required="" readonly id= "dpromostart" name="dpromostart" class="form-control date" value="<?= date('d-m-Y', strtotime($isi->d_promo_start));?>">
                        </div>
                        <div class="col-sm-6">
                            <input type="text" required="" readonly id= "dpromofinish" name="dpromofinish" class="form-control date" value="<?= date('d-m-Y', strtotime($isi->d_promo_finish));?>">
                        </div>
                    </div>                      
                    <div id="disc" class="form-group row" hidden="true">
                        <label class="col-md-12" id="label">Discount (%)</label>
                        <label class="col-md-6" id="label1" hidden="true">Discount I (%)</label><label class="col-md-6" id="label2" hidden="true">Discount II (%)</label>
                        <div class="col-sm-6" id="disc1">
                            <input type="text" id= "npromodiscount1" name="npromodiscount1" class="form-control" value="<?= $isi->n_promo_discount1; ?>" onkeypress="return hanyaAngka(event);">
                        </div>
                        <div class="col-sm-6" id="disc2">
                            <input type="text" id= "npromodiscount2" name="npromodiscount2" class="form-control" value="<?= $isi->n_promo_discount2; ?>" onkeypress="return hanyaAngka(event);">
                        </div>
                    </div>                          
                    <div class="form-group row"></div>   
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-12">
                            <?php if ($areauser!='00' && check_role($i_menu, 3)) {?>
                                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales(parseFloat(document.getElementById('jmlp').value),parseFloat(document.getElementById('jmlc').value),parseFloat(document.getElementById('jmlg').value));"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan
                                </button>&nbsp;&nbsp;
                                <?php if(($isi->f_all_product=='f') && ($isi->f_all_baby=='f') && ($isi->f_all_reguler=='f') && ($isi->f_all_nb=='f')){?>  
                                    <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Product
                                    </button>&nbsp;&nbsp;
                                <?php } if(($isi->f_all_customer=='f') && ($isi->f_customer_group=='f')){ ?>
                                    <button type="button" id="addpel" class="btn btn-inverse btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Pelanggan
                                    </button>&nbsp;&nbsp;
                                <?php } if($isi->f_customer_group=='t'){ ?>
                                    <button type="button" id="addpelgr" class="btn btn-inverse btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Group Pelanggan
                                    </button>&nbsp;&nbsp;
                                <?php } if($isi->f_all_area=='f'){ ?>       
                                    <button type="button" id="addar" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Area
                                    </button>
                                <?php } 
                            }?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-12">Jenis Promo</label>
                        <div class="col-sm-12">
                            <select name="ipromotype" id="ipromotype" required="" class="form-control" onchange="getpromo(this.value);">
                                <option value="">-- Pilih Promo --</option>
                                <?php if ($promo) {                                 
                                    foreach ($promo as $key) { ?>
                                        <option value="<?php echo $key->i_promo_type;?>" <?php if ($key->i_promo_type==$isi->i_promo_type) {
                                            echo "selected";
                                        }?>><?= $key->e_promo_typename;?></option>
                                    <?php }; 
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Kode Harga</label>
                        <div class="col-sm-12">
                            <select name="ipricegroup" id="ipricegroup" required="" class="form-control select2">
                                <option value="<?= $isi->i_price_group;?>"><?= $isi->i_price_group;?></option>
                            </select>
                        </div>
                    </div> 
                    <?php 
                    if ($isi->f_all_product=='t') {
                        $productgroup = "sp";
                    }elseif ($isi->f_all_reguler=='t') {
                        $productgroup = "00";
                    }elseif ($isi->f_all_baby=='t') {
                        $productgroup = "01";
                    }elseif ($isi->f_all_nb=='t') {
                        $productgroup = "02";
                    }else{
                        $productgroup = "";
                    }
                    ?>
                    <div class="form-group row">
                        <label class="col-md-12">Group Barang</label>
                        <div class="col-sm-12">
                            <select name="productgroup" id="productgroup" class="form-control" onchange="group(this.value);">
                                <option value="" <?php if ($productgroup=="") {
                                    echo "selected";
                                }?>></option>
                                <option value="sp" <?php if ($productgroup=="sp") {
                                    echo "selected";
                                }?>>Semua Product</option>
                                <?php if ($group) {
                                    foreach ($group as $key) { ?>
                                        <option value="<?= $key->i_product_group;?>" <?php if ($productgroup==$key->i_product_group) {
                                            echo "selected";
                                        }?>><?= $key->e_product_groupname;?></option> 
                                    <?php }
                                } ?>   
                            </select>
                        </div>
                    </div> 
                    <div class="col-md-12">
                        <div class="form-check">
                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" id="fallcustomer" name="fallcustomer" class="custom-control-input" <?php 
                                if($isi->f_all_customer=='t'){
                                    echo 'checked';
                                }else{
                                    if($isi->f_customer_group=='t'){
                                        echo 'disabled=true';
                                    }
                                }
                                ?>>
                                <span class="custom-control-indicator"></span>
                                <span class="custom-control-description">Semua Pelanggan</span>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-check">
                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" id="fcustomergroup" name="fcustomergroup" class="custom-control-input"
                                <?php 
                                if($isi->f_customer_group=='t'){
                                    echo 'checked';
                                }else{
                                    if($isi->f_all_customer=='t'){
                                        echo 'disabled=true';
                                    }
                                }
                                ?>>
                                <span class="custom-control-indicator"></span>
                                <span class="custom-control-description">Group Pelanggan</span>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-check">
                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" id="fallarea" name="fallarea" class="custom-control-input" 
                                <?php 
                                if($isi->f_all_area=='t'){
                                    echo "checked";
                                }?>>
                                <span class="custom-control-indicator"></span>
                                <span class="custom-control-description">Semua Area</span>
                            </label>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="jmlp" id="jmlp" value="<?= $jmlitemp; ?>">
                <input type="hidden" name="jmlc" id="jmlc" value="<?= $jmlitemc; ?>">
                <input type="hidden" name="jmlg" id="jmlg" value="<?= $jmlitemg; ?>">
                <input type="hidden" name="jmla" id="jmla" value="<?= $jmlitema; ?>">
                <div class="col-md-12">
                    <?php if($jmlitemp>0){?>
                        <table id="tablep" class="display table" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th style="text-align: center; width: 4%;">No</th>
                                    <th style="text-align: center; width: 15%;">Kode Barang</th>
                                    <th style="text-align: center; width: 30%;">Nama Barang</th>
                                    <th style="text-align: center;">Motif</th>
                                    <?php if(($isi->i_promo_type=='2')||($isi->i_promo_type=='4')||($isi->i_promo_type=='5')||($isi->i_promo_type=='6')){?>            
                                        <th id="harga" style="text-align: center;">Harga</th>
                                    <?php } ?>
                                    <th style="text-align: center;">Min Pesan</th>
                                    <th style="text-align: center; width: 5%;">Act</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($detailp) {
                                    $i = 0;
                                    foreach ($detailp as $rowp) {
                                        $i++;?>
                                        <tr>
                                            <td style="text-align: center;">
                                                <?= $i;?>
                                                <input type="hidden" id="barisp<?= $i;?>" type="text" class="form-control" name="barisp<?= $i;?>" value="<?= $i;?>">
                                                <input type="hidden" id="motif<?= $i;?>" name="motif<?= $i;?>" value="<?= $rowp->i_product_motif;?>">
                                            </td>
                                            <td>
                                                <input type="text" id="iproduct<?=$i;?>" class="form-control" name="iproduct<?= $i;?>" readonly="" value="<?= $rowp->i_product;?>">
                                            </td>
                                            <td>
                                                <input type="text" id="eproductname<?= $i;?>" type="text" class="form-control" name="eproductname<?= $i;?>" readonly value="<?= $rowp->e_product_name;?>">
                                            </td>
                                            <td>
                                                <input type="text" id="emotifname<?= $i;?>" type="text" class="form-control" name="emotifname<?= $i;?>" readonly value="<?= $rowp->e_product_motifname;?>">
                                            </td>
                                            <?php if(($isi->i_promo_type=='2')||($isi->i_promo_type=='4')||($isi->i_promo_type=='5')||($isi->i_promo_type=='6')){?>  
                                                <td>
                                                    <input type="text" id="vunitprice<?= $i;?>" class="form-control" name="vunitprice<?= $i;?>" onkeyup="reformat(this);" onkeypress="return hanyaAngka(event);" value="<?= number_format($rowp->v_unit_price);?>" style="text-align: right;">
                                                </td>
                                            <?php } ?>
                                            <td>
                                                <input type="text" id="nquantitymin<?= $i;?>" class="form-control" name="nquantitymin<?= $i;?>" onkeypress="return hanyaAngka(event);" value="<?= $rowp->n_quantity_min;?>" style="text-align: right;"/>
                                            </td>
                                            <td>
                                                <button type="button" onclick="hapusp('<?= $rowp->i_promo."','".$rowp->i_product."','".$rowp->i_product_grade."','".$rowp->i_product_motif;?>'); return false;" title="Delete" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    <?php } 
                                } ?>
                            </tbody>
                        </table>      
                    <?php } ?>
                    <?php if($jmlitemc>0){?>
                        <table id="tablec" class="display table" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th style="text-align: center; width: 4%;">No</th>
                                    <th style="text-align: center; width: 20%;">Kode</th>
                                    <th style="text-align: center; width: 30%;">Nama Pelanggan</th>
                                    <th style="text-align: center;">Alamat</th>
                                    <th style="text-align: center; width: 5%;">Act</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($detailc) {
                                    $i = 0;
                                    foreach ($detailc as $rowc) {
                                        $i++;?>
                                        <tr>
                                            <td style="text-align: center;"><?= $i;?><input type="hidden" id="barisc<?= $i;?>" type="text" class="form-control" name="barisc<?= $i;?>" value="<?= $i;?>"></td>
                                            <td><input type="text" id="icustomer<?= $i;?>" class="form-control" name="icustomer<?= $i;?>" value="<?= $rowc->i_customer;?>"></td>
                                            <td><input type="text" id="ecustomername<?= $i;?>" type="text" class="form-control" name="ecustomername<?= $i;?>" readonly value = "<?= $rowc->e_customer_name;?>"></td>
                                            <td><input type="text" id="ecustomeraddress<?= $i;?>" type="text" class="form-control" name="ecustomeraddress<?= $i;?>" readonly value = "<?= $rowc->e_customer_address;?>"></td>
                                            <td><button type="button" onclick="hapusc('<?= $rowc->i_promo."','".$rowc->i_customer;?>'); return false;" title="Delete" class="btn btn-danger"><i class="fa fa-trash"></i></button></td>
                                        </tr>
                                    <?php } 
                                } ?>
                            </tbody>
                        </table>
                    <?php } ?>
                    <?php if($jmlitemg>0){?>
                        <table id="tableg" class="display table" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th style="text-align: center; width: 4%;">No</th>
                                    <th style="text-align: center; width: 30%;">Kode Group</th>
                                    <th style="text-align: center;">Nama Group</th>
                                    <th style="text-align: center; width: 5%;">Act</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($detailg) {
                                    $i = 0;
                                    foreach ($detailg as $rowg) {
                                        $i++;?>
                                        <tr>
                                            <td style="text-align: center;"><?= $i;?><input type="hidden" id="barisg<?= $i;?>" type="text" class="form-control" name="barisg<?= $i;?>" value="<?= $i;?>"></td>
                                            <td><input type="text" id="icustomergroup<?= $i;?>" class="form-control" name="icustomergroup<?= $i;?>" value="<?= $rowg->i_customer_group;?>"></td>
                                            <td><input type="text" id="ecustomergroupname<?= $i;?>" type="text" class="form-control" name="ecustomergroupname<?= $i;?>" readonly  value="<?= $rowg->e_customer_groupname;?>"></td>
                                            <td><button type="button" onclick="hapusg('<?= $rowg->i_promo."','".$rowg->i_customer_group;?>'); return false;" title="Delete" class="btn btn-danger"><i class="fa fa-trash"></i></button></td>
                                        </tr>
                                    <?php } 
                                } ?>
                            </tbody>
                        </table>
                    <?php } ?>
                    <?php if($jmlitema>0){?>
                        <table id="tablea" class="display table" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th style="text-align: center; width: 4%;">No</th>
                                    <th style="text-align: center; width: 30%;">Kode Area</th>
                                    <th style="text-align: center;">Nama Area</th>
                                    <th style="text-align: center; width: 5%;">Act</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($detaila) {
                                    $i = 0;
                                    foreach ($detaila as $rowa) {
                                        $i++;?>
                                        <tr>
                                            <td style="text-align: center;"><?= $i;?><input type="hidden" id="barisa<?= $i;?>" type="text" class="form-control" name="barisa<?= $i;?>" value="<?= $i;?>"></td>
                                            <td><input type="text" id="iarea<?= $i;?>" class="form-control" name="iarea<?= $i;?>" value="<?= $rowa->i_area;?>"></td>
                                            <td><input type="text" id="eareaname<?= $i;?>" type="text" class="form-control" name="eareaname<?= $i;?>" readonly value="<?= $rowa->e_area_name;?>"></td>
                                            <td><button type="button" onclick="hapusa('<?= $rowa->i_promo."','".$rowa->i_area;?>'); return false;" title="Delete" class="btn btn-danger"><i class="fa fa-trash"></i></button></td>
                                        </tr>
                                    <?php } 
                                } ?>
                            </tbody>
                        </table>
                    <?php } ?>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
</div>
<script>
    var xx = '<?= $jmlitemp; ?>';
    $("#addrow").on("click", function () {
        xx++;
        $("#tablep").attr("hidden", false);
        $('#jmlp').val(xx);
        var tipe = $('#ipromotype').val();
        if((tipe=='2')||(tipe=='4')||(tipe=='5')||(tipe=='6')){
            $("#harga").attr("hidden", false);
        }else{
            $("#harga").attr("hidden", true);
        }
        var newRow = $("<tr>");
        var cols = "";
        cols += '<td style="text-align: center;">'+xx+'<input type="hidden" id="barisp'+xx+'" type="text" class="form-control" name="barisp'+xx+'" value="'+xx+'"><input type="hidden" id="motif'+xx+'" name="motif'+xx+'" value=""></td>';
        cols += '<td><select type="text" id="iproduct'+xx+ '" class="form-control" name="iproduct'+xx+'" onchange="getdetailp('+xx+');"></td>';
        cols += '<td><input type="text" id="eproductname'+xx+'" type="text" class="form-control" name="eproductname'+xx+'" readonly></td>';
        cols += '<td><input type="text" id="emotifname'+xx+'" type="text" class="form-control" name="emotifname'+xx+'" readonly></td>';
        if((tipe=='2')||(tipe=='4')||(tipe=='5')||(tipe=='6')){
            cols += '<td><input type="text" id="vunitprice'+xx+'" class="form-control" name="vunitprice'+xx+'" onkeyup="reformat(this);" onkeypress="return hanyaAngka(event);" value="0" style="text-align: right;"></td>';
        }
        cols += '<td><input type="text" id="nquantitymin'+xx+'" class="form-control" name="nquantitymin'+xx+'" onkeypress="return hanyaAngka(event);" value="0" style="text-align: right;"/></td>';
        cols += '<td></td>';
        newRow.append(cols);
        $("#tablep").append(newRow);
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
    });

    $("#tablep").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
        xx -= 1
        $('#jmlp').val(xx);
    });

    function getdetailp(id){
        ada=false;
        var a = $('#iproduct'+id).val();
        var x = $('#jmlp').val();
        for(i=1;i<=x;i++){            
            if((a == $('#iproduct'+i).val()) && (i!=x)){
                swal ("Kode : "+a+" sudah ada !!!!!");            
                ada=true;            
                break;        
            }else{            
                ada=false;             
            }
        }
        if(!ada){
            $.ajax({
                type: "post",
                data: {
                    'iproduct' : a
                },
                url: '<?= base_url($folder.'/cform/getdetailp'); ?>',
                dataType: "json",
                success: function (data) {
                    $('#iproduct'+id).val(data[0].kode);
                    $('#eproductname'+id).val(data[0].nama);
                    $('#vunitprice'+id).val(formatcemua(data[0].harga));
                    $('#motif'+id).val(data[0].motif);
                    $('#emotifname'+id).val(data[0].namamotif);
                    $('#nquantitymin'+id).val(1);
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

    var yy = '<?= $jmlitemc; ?>';
    $("#addpel").on("click", function () {
        yy++;
        $("#tablec").attr("hidden", false);
        $('#jmlc').val(yy);
        var newRow = $("<tr>");
        var cols = "";
        cols += '<td style="text-align: center;">'+yy+'<input type="hidden" id="barisc'+yy+'" type="text" class="form-control" name="barisc'+yy+'" value="'+yy+'"></td>';
        cols += '<td><select type="text" id="icustomer'+yy+ '" class="form-control" name="icustomer'+yy+'" onchange="getdetailc('+yy+');"></td>';
        cols += '<td><input type="text" id="ecustomername'+yy+'" type="text" class="form-control" name="ecustomername'+yy+'" readonly></td>';
        cols += '<td><input type="text" id="ecustomeraddress'+yy+'" type="text" class="form-control" name="ecustomeraddress'+yy+'" readonly></td>';
        cols += '<td></td>';
        newRow.append(cols);
        $("#tablec").append(newRow);
        $('#icustomer'+yy).select2({
            placeholder: 'Cari Pelanggan',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/customer/'); ?>',
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
    });

    $("#tablec").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
        yy -= 1
        $('#jmlc').val(yy);
    });

    function getdetailc(id){
        ada=false;
        var a = $('#icustomer'+id).val();
        var x = $('#jmlc').val();
        for(i=1;i<=x;i++){            
            if((a == $('#icustomer'+i).val()) && (i!=x)){
                swal ("Kode Pelanggan : "+a+" sudah ada !!!!!");            
                ada=true;            
                break;        
            }else{            
                ada=false;             
            }
        }
        if(!ada){
            $.ajax({
                type: "post",
                data: {
                    'icustomer' : a
                },
                url: '<?= base_url($folder.'/cform/getdetailc'); ?>',
                dataType: "json",
                success: function (data) {
                    $('#icustomer'+id).val(data[0].i_customer);
                    $('#ecustomername'+id).val(data[0].e_customer_name);
                    $('#ecustomeraddress'+id).val(data[0].e_customer_address);
                },
                error: function () {
                    swal('Error :)');
                }
            });
        }else{
            $('#icustomer'+id).html('');
            $('#icustomer'+id).val('');
        }
    }

    var zz = '<?= $jmlitemg; ?>';
    $("#addpelgr").on("click", function () {
        zz++;
        $("#tableg").attr("hidden", false);
        $('#jmlg').val(zz);
        var newRow = $("<tr>");
        var cols = "";
        cols += '<td style="text-align: center;">'+zz+'<input type="hidden" id="barisg'+zz+'" type="text" class="form-control" name="barisg'+zz+'" value="'+zz+'"></td>';
        cols += '<td><select type="text" id="icustomergroup'+zz+ '" class="form-control" name="icustomergroup'+zz+'" onchange="getdetailg('+zz+');"></td>';
        cols += '<td><input type="text" id="ecustomergroupname'+zz+'" type="text" class="form-control" name="ecustomergroupname'+zz+'" readonly></td>';
        cols += '<td></td>';
        newRow.append(cols);
        $("#tableg").append(newRow);
        $('#icustomergroup'+zz).select2({
            placeholder: 'Cari Group',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/customergroup/'); ?>',
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
    });

    $("#tableg").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
        zz -= 1
        $('#jmlg').val(zz);
    });

    function getdetailg(id){
        ada=false;
        var a = $('#icustomergroup'+id).val();
        var x = $('#jmlg').val();
        for(i=1;i<=x;i++){            
            if((a == $('#icustomergroup'+i).val()) && (i!=x)){
                swal ("Kode Group : "+a+" sudah ada !!!!!");            
                ada=true;            
                break;        
            }else{            
                ada=false;             
            }
        }
        if(!ada){
            $.ajax({
                type: "post",
                data: {
                    'icustomergroup' : a
                },
                url: '<?= base_url($folder.'/cform/getdetailg'); ?>',
                dataType: "json",
                success: function (data) {
                    $('#icustomergroup'+id).val(data[0].i_customer_group);
                    $('#ecustomergroupname'+id).val(data[0].e_customer_groupname);
                },
                error: function () {
                    swal('Error :)');
                }
            });
        }else{
            $('#icustomergroup'+id).html('');
            $('#icustomergroup'+id).val('');
        }
    }

    var ww = '<?= $jmlitema; ?>';
    $("#addar").on("click", function () {
        ww++;
        $("#tablea").attr("hidden", false);
        $('#jmla').val(ww);
        var newRow = $("<tr>");
        var cols = "";
        cols += '<td style="text-align: center;">'+ww+'<input type="hidden" id="barisa'+ww+'" type="text" class="form-control" name="barisa'+ww+'" value="'+ww+'"></td>';
        cols += '<td><select type="text" id="iarea'+ww+'" class="form-control" name="iarea'+ww+'" onchange="getdetaila('+ww+');"></td>';
        cols += '<td><input type="text" id="eareaname'+ww+'" type="text" class="form-control" name="eareaname'+ww+'" readonly></td>';
        cols += '<td></td>';
        newRow.append(cols);
        $("#tablea").append(newRow);
        $('#iarea'+ww).select2({
            placeholder: 'Cari Area',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/area/'); ?>',
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
    });

    $("#tablea").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
        ww -= 1
        $('#jmla').val(ww);
    });

    function getdetaila(id){
        ada=false;
        var a = $('#iarea'+id).val();
        var x = $('#jmla').val();
        for(i=1;i<=x;i++){            
            if((a == $('#iarea'+i).val()) && (i!=x)){
                swal ("Kode Area : "+a+" sudah ada !!!!!");            
                ada=true;            
                break;        
            }else{            
                ada=false;             
            }
        }
        if(!ada){
            $.ajax({
                type: "post",
                data: {
                    'iarea' : a
                },
                url: '<?= base_url($folder.'/cform/getdetaila'); ?>',
                dataType: "json",
                success: function (data) {
                    $('#iarea'+id).val(data[0].i_area);
                    $('#eareaname'+id).val(data[0].e_area_name);
                },
                error: function () {
                    swal('Error :)');
                }
            });
        }else{
            $('#iarea'+id).html('');
            $('#iarea'+id).val('');
        }
    }    

    function getpromo(kode) {
        if (kode!='') {
            $("#addrow").attr("disabled", false);
            $("#ipricegroup").attr("disabled", false);
            $("#disc").attr("hidden", false);
        }else{
            $("#addrow").attr("disabled", true);
            $("#ipricegroup").attr("disabled", true);
            $("#disc").attr("hidden", true);
        }
        $("#tablep tr:gt(0)").remove();       
        $("#tablep").attr("hidden", true);
        $("#jmlp").val(0);
        xx = 0;
        if((kode=='1')||(kode=='3')){
            $("#disc").attr("hidden", false);
            $("#label").attr("hidden", true);
            $("#label1").attr("hidden", false);
            $("#label2").attr("hidden", false);
        }else if((kode=='5')||(kode=='6')){
            $("#disc").attr("hidden", false);
            $("#disc1").attr("hidden", false);
            $("#disc2").attr("hidden", true);
            $("#label").attr("hidden", false);
            $("#label1").attr("hidden", true);
            $("#label2").attr("hidden", true);
        }else{
            $("#disc").attr("hidden", true);
        }
    }

    function group(igroup) {
        if (igroup!='') {
            $("#addrow").attr("hidden", true);
        }else{
            $("#addrow").attr("hidden", false);
        }
    }

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#addrow").attr("disabled", true);
        $("#addpel").attr("disabled", true);
        $("#addpelgr").attr("disabled", true);
        $("#addar").attr("disabled", true);
    });

    function hanyaAngka(evt) {      
        var charCode = (evt.which) ? evt.which : event.keyCode      
        if (charCode > 31 && (charCode < 48 || charCode > 57))        
            return false;    
        return true;
    }

    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date');
        $('#ipricegroup').select2({
            placeholder: 'Cari Berdasarkan Kode',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getgroup/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var ipromotype = $('#ipromotype').val();
                    var query = {
                        q: params.term,
                        ipromotype: ipromotype
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

        $('#fallarea').click(function (event) {
            if (this.checked) {
                $("#addar").attr("hidden", true);
            } else {             
                $("#addar").attr("hidden", false);
            }
            $("#tablea tr:gt(0)").remove();       
            $("#tablea").attr("hidden", true);
            $("#jmla").val(0);
            ww = 0;
        });

        $('#fcustomergroup').click(function (event) {
            if (this.checked) {
                $("#fallcustomer").attr("disabled", true);
                $("#addpel").attr("hidden", true);
                $("#addpelgr").attr("hidden", false);
                $("#tablec tr:gt(0)").remove();       
                $("#tablec").attr("hidden", true);
                $("#jmlc").val(0);
                yy = 0;
            } else {             
                $("#fallcustomer").attr("disabled", false);
                $("#addpel").attr("hidden", false);
                $("#addpelgr").attr("hidden", true);
                $("#tableg tr:gt(0)").remove();       
                $("#tableg").attr("hidden", true);
                $("#jmlg").val(0);
                zz = 0;
            }
        });

        $('#fallcustomer').click(function (event) {
            if (this.checked) {
                $("#fcustomergroup").attr("disabled", true);
                $("#addpel").attr("hidden", true);
            } else {             
                $("#fcustomergroup").attr("disabled", false);
                $("#addpel").attr("hidden", false);
            }
            $("#tablec tr:gt(0)").remove();       
            $("#tablec").attr("hidden", true);
            $("#jmlc").val(0);
            yy = 0;
            $("#tableg tr:gt(0)").remove();       
            $("#tableg").attr("hidden", true);
            $("#jmlg").val(0);
            zz = 0;
        });

        var kode = '<?= $isi->i_promo_type;?>';
        if((kode=='1')||(kode=='3')){
            $("#disc").attr("hidden", false);
            $("#label").attr("hidden", true);
            $("#label1").attr("hidden", false);
            $("#label2").attr("hidden", false);
        }else if((kode=='5')||(kode=='6')){
            $("#disc").attr("hidden", false);
            $("#disc1").attr("hidden", false);
            $("#disc2").attr("hidden", true);
            $("#label").attr("hidden", false);
            $("#label1").attr("hidden", true);
            $("#label2").attr("hidden", true);
        }else{
            $("#disc").attr("hidden", true);
        }
    });

    function hapusp(ipromo,iproduct,iproductgrade,iproductmotif) {
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
                        'ipromo' : ipromo,
                        'iproduct' : iproduct,
                        'iproductgrade' : iproductgrade,
                        'iproductmotif' : iproductmotif,
                    },
                    url: '<?= base_url($folder.'/cform/deletep'); ?>',
                    dataType: "json",
                    success: function (data) {
                        swal("Dihapus!", "Data berhasil dihapus :)", "success");
                        show('<?= $folder;?>/cform/edit/<?= $isi->i_promo;?>','#main');     
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

    function hapusc(ipromo,icustomer) {
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
                        'ipromo' : ipromo,
                        'icustomer' : icustomer,
                    },
                    url: '<?= base_url($folder.'/cform/deletec'); ?>',
                    dataType: "json",
                    success: function (data) {
                        swal("Dihapus!", "Data berhasil dihapus :)", "success");
                        show('<?= $folder;?>/cform/edit/<?= $isi->i_promo;?>','#main');     
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

    function hapusg(ipromo,icustomer) {
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
                        'ipromo' : ipromo,
                        'icustomer' : icustomer,
                    },
                    url: '<?= base_url($folder.'/cform/deleteg'); ?>',
                    dataType: "json",
                    success: function (data) {
                        swal("Dihapus!", "Data berhasil dihapus :)", "success");
                        show('<?= $folder;?>/cform/edit/<?= $isi->i_promo;?>','#main');     
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

    function hapusa(ipromo,iarea) {
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
                        'ipromo' : ipromo,
                        'iarea' : iarea,
                    },
                    url: '<?= base_url($folder.'/cform/deletea'); ?>',
                    dataType: "json",
                    success: function (data) {
                        swal("Dihapus!", "Data berhasil dihapus :)", "success");
                        show('<?= $folder;?>/cform/edit/<?= $isi->i_promo;?>','#main');     
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

    function dipales(a,b,c){           
        if((document.getElementById("dpromo").value!='') && (document.getElementById("ipromotype").value!='')) {
            if((a==0)&&(b==0)&&(c==0)){
                swal('Isi data item minimal 1 !!!');
                return false;
            }else{
                if(a>0){
                    for(i=1;i<=a;i++){
                        if((document.getElementById("iproduct"+i).value=='') || (document.getElementById("eproductname"+i).value=='') || (document.getElementById("nquantitymin"+i).value=='')){
                            swal('Data item masih ada yang salah !!!');
                            return false;
                        }else{
                            return true; 
                        } 
                    }
                }
                if(b>0){
                    for(i=1;i<=b;i++){
                        if((document.getElementById("icustomer"+i).value=='') || (document.getElementById("ecustomername"+i).value=='')){
                            swal('Data item masih ada yang salah !!!');
                            return false;
                        }else{
                            return true;
                        } 
                    }
                }
                if(c>0){
                    for(i=1;i<=c;i++){
                        if((document.getElementById("icustomergroup"+i).value=='') || (document.getElementById("ecustomergroupname"+i).value=='')){                            
                            swal('Data item masih ada yang salah !!!');
                            return false;
                        }else{
                            return true;
                        } 
                    }
                }
            }
        }else{
            swal('Data header masih ada yang salah !!!');
            return false;
        }
    }
</script>