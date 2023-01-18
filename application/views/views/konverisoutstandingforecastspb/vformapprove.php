<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-check"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?=$title_list;?></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/approve2'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <!-- <div class="form-group row">
                        <label class="col-md-4">Gudang</label>
                        <label class="col-md-4">Nomor SJ Makloon</label>
                        <label class="col-md-4">Tanggal SJ Makloon</label>
                        <div class="col-sm-4">
                            <select name="ibagian" id="ibagian" class="form-control select2" disabled="">
                                <option value="" selected>-- Pilih Gudang --</option>
                                <?php foreach ($kodemaster as $ibagian):?>
                                    <?php if ($ibagian->i_departement == $head->i_kode_master) { ?>
                                    <option value="<?php echo $ibagian->i_departement;?>" selected><?= $ibagian->e_departement_name;?></option>
                                    <?php }else { ?>
                                    <option value="<?php echo $ibagian->i_departement;?>"><?= $ibagian->e_departement_name;?></option>
                                    <?php }?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id="isj" name="isj" class="form-control" value="<?php echo $head->i_sj?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id="dsjk" name="dsjk" class="form-control date" value="<?php echo date('d-m-Y', strtotime($head->d_sj))?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-8">Nomor Referensi Pengeluaran</label>
                        <label class="col-md-4">Tanggal Referensi</label>
                        <div class="col-sm-8">
                             <input type="text" id="reff" name="reff" class="form-control"  value="<?php echo $head->i_reff?>" readonly>
                        </div>  
                        <div class="col-sm-4">
                             <input type="text" id="dreff" name="dreff" class="form-control"  value="<?php echo $head->d_referensi?>" readonly>
                        </div>  
                    </div> -->
                <div class="form-group row">
                        <div class="col-sm-6">
                            <label >Nomor SPB</label>
                            <input type="text" id="ispb" name="ispb" class="form-control" readonly maxlength="" value="<?php echo $data->i_op_code;?>">
                            <?php foreach ($bagian as $ibagian):?>
                                <input type="hidden" id= "ibagian" name="ibagian" class="form-control date" value="<?php echo $ibagian->i_departement; ?>" readonly>
                                <?php endforeach; ?>
                        </div>
                </div>
                <div class="form-group row">
                        <label class="col-md-6">Tanggal SPB</label>
                        <label class="col-md-6">Batas Kirim</label>
                        <div class="col-sm-6">
                                <input type="text" id= "dspb" name="dspb" class="form-control date" value="<?php echo $data->d_op; ?>" required="" readonly>
                        </div>
                        <div class="col-sm-6">
                                <input type="text" id= "dbatas" name="dbatas" class="form-control date" value="<?php echo $data->d_delivery_limit; ?>" required="" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">PO Refferensi</label>
                        <label class="col-md-6">Promo</label>
                        <div class="col-sm-6">
                            <input type="text" id= "iporeff" name="iporeff" class="form-control" value="<?= $data->i_op_reff ?>">
                        </div>
                        <div class="col-sm-6">
                            <select name="ipromo" id="ipromo" class="form-control select2" disabled="">
                                <option value="">-- Pilih promo --</option>
                                <?php foreach ($promo as $ipromo):?>
                                    <?php if ($ipromo->i_promo == $data->i_promo) { ?>
                                         <option value="<?php echo $ipromo->i_promo;?>" selected><?= $ipromo->i_promo;?></option>
                                    <?php } else { ?>
                                         <option value="<?php echo $ipromo->i_promo;?>"><?= $ipromo->i_promo;?></option>
                                    <?php } ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                                <input type="text" id= "eremark" name="eremark" class="form-control" value="<?= $data->e_op_remark?>" >
                        </div>
                    </div>
                     <div class="form-group">
                        <?if($data->i_status =='7'){?>
                        <div class="col-sm-offset-5 col-sm-10">  
                            <button type="button" disabled id="cancel" class="btn btn-inverse btn-rounded btn-sm" onclick="return getenabledcancel();"> <i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Cancel</button>
                            <button type="button" id="change" class="btn btn-warning btn-rounded btn-sm" onclick="return getenabledchange();"> <i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;&nbsp;Change Requested</button>                           
                            <button type="button" id="reject" class="btn btn-danger btn-rounded btn-sm" onclick="return getenabledreject();"> <i class="fa fa-times"></i>&nbsp;&nbsp;&nbsp;Reject</button>
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Approve</button>
                        </div>
                        <?}else if($data->i_status =='3'){?>
                        <div class="col-sm-offset-5 col-sm-10">  
                            <button type="button" disabled id="cancel" class="btn btn-inverse btn-rounded btn-sm" onclick="return getenabledcancel();"> <i class="fa fa-refresh" onclick="return getenabledchange();"></i>&nbsp;&nbsp;&nbsp;Cancel</button>
                            <button type="button" disabled id="change" class="btn btn-warning btn-rounded btn-sm"> <i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;&nbsp;Change Requested</button>
                            <button type="button" id="reject" class="btn btn-danger btn-rounded btn-sm" onclick="return getenabledreject();"> <i class="fa fa-times"></i>&nbsp;&nbsp;&nbsp;Reject</button>
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Approve</button>
                        </div>
                        <?}else if($data->i_status =='4'){?>
                        <div class="col-sm-offset-5 col-sm-10">  
                            <button type="button" disabled id="cancel" class="btn btn-inverse btn-rounded btn-sm" onclick="return getenabledcancel();"> <i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Cancel</button>
                            <button type="button" disabled id="change" class="btn btn-warning btn-rounded btn-sm" onclick="return getenabledchange();"> <i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;&nbsp;Change Requested</button>
                            <button type="button" disabled id="reject" class="btn btn-danger btn-rounded btn-sm" onclick="return getenabledreject();"> <i class="fa fa-times"></i>&nbsp;&nbsp;&nbsp;Reject</button>
                            <button type="submit" disabled id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Approve</button>
                        </div>
                        <?}else if($data->i_status =='6'){?>
                        <div class="col-sm-offset-5 col-sm-10">  
                            <button type="button" id="cancel" class="btn btn-inverse btn-rounded btn-sm" onclick="return getenabledcancel();"> <i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Cancel</button>
                            <button type="button" disabled id="change" class="btn btn-warning btn-rounded btn-sm" onclick="return getenabledchange();"> <i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;&nbsp;Change Requested</button>
                            <button type="button" disabled id="reject" class="btn btn-danger btn-rounded btn-sm" onclick="return getenabledreject();"> <i class="fa fa-times"></i>&nbsp;&nbsp;&nbsp;Reject</button>
                            <button type="submit" disabled id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Approve</button>
                        </div>
                        <?}else{?>
                        <div class="col-sm-offset-5 col-sm-10">  
                            <button type="button" id="cancel" class="btn btn-inverse btn-rounded btn-sm" onclick="return getenabledcancel();"> <i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Cancel</button>
                            <button type="button" id="change" class="btn btn-warning btn-rounded btn-sm" onclick="return getenabledchange();"> <i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;&nbsp;Change Requested</button>
                            <button type="button" id="reject" class="btn btn-danger btn-rounded btn-sm" onclick="return getenabledreject();"> <i class="fa fa-times"></i>&nbsp;&nbsp;&nbsp;Reject</button>
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Approve</button>
                        </div>
                        <?}?>
                    </div>
                </div>
                <div class="col-md-6">
                    <!-- <div class="form-group row">
                        <label class="col-md-1"></label>
                        <label class="col-md-4">Perkiraan Kembali</label>
                        <label class="col-md-4">Partner</label>
                        <label class="col-md-3">Type Makloon</label>
                        <div class="col-sm-1"></div>
                        <div class="col-sm-4">
                            <input type="text" id="dback" name="dback" class="form-control date" value="<?php echo date('d-m-Y', strtotime($head->d_kembali));?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <select name="ipartner" id="ipartner" class="form-control select2" disabled="">
                                <option value="">-- Pilih Partner --</option>
                                <?php foreach ($partner as $ipartner):?>
                                    <?php if ($ipartner->i_partner == $head->partner) { ?>
                                         <option value="<?php echo $ipartner->i_partner;?>" selected><?= $ipartner->e_partner;?></option>
                                    <?php } else { ?>
                                         <option value="<?php echo $ipartner->i_partner;?>"><?= $ipartner->e_partner;?></option>
                                    <?php } ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="itypemakloon" id="itypemakloon" class="form-control select2" disabled="">
                                <option value="">-- Pilih Type Makloon --</option>
                                <?php foreach ($typemakloon as $itypemakloon):?>
                                    <?php if ($itypemakloon->i_type_makloon == $head->i_type_makloon) { ?>
                                         <option value="<?php echo $itypemakloon->i_type_makloon;?>" selected><?= $itypemakloon->e_type_makloon;?></option>
                                    <?php } else { ?>
                                         <option value="<?php echo $itypemakloon->i_type_makloon;?>"><?= $itypemakloon->e_type_makloon;?></option>
                                    <?php } ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-1"></label>
                        <label class="col-md-11">Keterangan</label>
                        <div class="col-sm-1"></div>
                        <div class="col-sm-11">
                            <input type="text" id="eremark" name="eremark" class="form-control" value="<?php echo $head->e_remark?>" readonly>
                        </div>
                    </div> -->
                    <div class="form-group row">
                        <label class="col-md-6">Pelanggan</label>
                        <label class="col-md-6">Alamat</label>
                        <div class="col-sm-6">          
                            <select name="icustomer" id="icustomer" class="form-control select2" >
                                <option value="">-- Pilih Customer --</option>
                                <?php foreach ($customer as $icustomer):?>
                                    <?php if ($icustomer->i_customer == $data->i_customer) { ?>
                                         <option value="<?php echo $icustomer->i_customer;?>" selected><?= $icustomer->e_customer_name;?></option>
                                    <?php } else { ?>
                                         <option value="<?php echo $icustomer->i_customer;?>"><?= $icustomer->e_customer_name;?></option>
                                    <?php } ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-sm-6">                            
                            <input type="text" id= "ecustomeraddress" name="ecustomeraddress" class="form-control" value="<?= $data->e_customer_address; ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-4">Nilai Kotor</label>
                        <label class="col-md-4">Nilai Diskon</label>
                        <label class="col-md-4">Nilai Bersih</label>
                        <div class="col-sm-4">
                            <input type="text" id= "vgross" name="vgross" class="form-control" value="<?= $data->v_total_gross; ?>">
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id= "vdiskon" name="vdiskon" class="form-control" value="<?= $data->v_total_discount; ?>">
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id= "vnetto" name="vnetto" class="form-control" value="<?= $data->v_total_netto; ?>">
                        </div>
                    </div>
                </div>
            <div class="panel-body table-responsive">
                <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Barang</th>
                            <th width="45%">Nama Barang Jadi</th>
                            <th>Harga</th>
                            <th>Quantity </th>
                            <th>Disc</th>
                            <th>Total</th>
                            <th>Keterangan</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 0;
                            foreach ($datadetail as $row) {
                            $i++;
                        ?>
                        <tr>
                        <td style="text-align: center;"><?=$i;?>
                            <input type="hidden" class="form-control" readonly id="baris<?=$i;?>" name="baris<?=$i;?>" value="<?=$i;?>">
                        </td>
                        <td class="col-sm-1">
                            <input style ="width:100px" class="form-control" type="text" id="iproduct<?=$i;?>" name="iproduct[]" value="<?= $row->i_product; ?>" readonly >    
                        </td>
                        <td class="col-sm-1">
                            <input style ="width:400px" class="form-control" type="text" id="eproductname<?=$i;?>" name="eproductname[]" value="<?= $row->e_product_basename; ?>" readonly >    
                        </td>
                        <td class="col-sm-1">     
                            <input style ="width:80px" class="form-control" type="text" id="vharga<?=$i;?>" name="vharga[]" value="<?= $row->v_price; ?>" >
                        </td>
                        <td class="col-sm-1">
                            <input style ="width:100px" class="form-control" type="text" id="nquantity<?=$i;?>" name="nquantity[]" value="<?= $row->n_count; ?>" >
                        </td>
                        <td class="col-sm-1">
                            <input style ="width:100px" class="form-control" type="text" id="ndisc<?=$i;?>" name="ndisc[]" value="<?= $row->n_disc; ?>" >
                        </td>
                        <td class="col-sm-1">
                            <input style ="width:100px" class="form-control" type="text" id="vsubtotal<?=$i;?>" name="vsubtotal[]" value="<?= (($row->v_price*$row->n_count)-($row->v_price*$row->n_count)*$row->n_disc/100); ?>" >
                        </td>
                        <td class="col-sm-1">
                            <input style ="width:200px" class="form-control" type="text" id="edesc<?=$i;?>" name="edesc[]"value="<?= $row->e_remark; ?>" >
                        </td>
                        <td align="center">
                            <button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button>
                        </td>
                        </tr>
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
$("form").submit(function (event) {
    event.preventDefault();
    $("input").attr("disabled", true);
     $("select").attr("disabled", true);
     $("#submit").attr("disabled", true);
     $("#cancel").attr("disabled", true);
     $("#reject").attr("disabled", true);
     $("#change").attr("disabled", true);  
});
    
$(document).ready(function () {
    $('.select2').select2();
    showCalendar('.date');
});

function getenabledcancel() {
    $('#change').attr("disabled", true);
    $('#reject').attr("disabled", true);
    $('#cancel').attr("disabled", true);
    $('#submit').attr("disabled", true);
}

function getenabledchange() {
    $('#change').attr("disabled", true);
    $('#reject').attr("disabled", true);
    $('#cancel').attr("disabled", true);
    $('#submit').attr("disabled", true);
}

function getenabledreject() {
    $('#change').attr("disabled", true);
    $('#reject').attr("disabled", true);
    $('#cancel').attr("disabled", true);
    $('#submit').attr("disabled", true);
}

$(document).ready(function(){
    $("#cancel").on("click", function () {
        var isj = $("#isj").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/cancel'); ?>",
            data: {
                     'isj'  : isj,
                    },
            dataType: 'json',
            delay: 250, 
            success: function(data) {
                return {
                results: data
                };
            },
             cache: true
        });
    });
});

$(document).ready(function(){
    $("#change").on("click", function () {
       var ispb = $("#ispb").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/change'); ?>",
            data: {
                     'ispb'  : ispb,
                    },
            dataType: 'json',
            delay: 250, 
            success: function(data) {
                return {
                results: data
                };
            },
             cache: true
        });
    });
});

$(document).ready(function(){
    $("#reject").on("click", function () {
    var ispb = $("#ispb").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/reject'); ?>",
            data: {
                     'ispb'  : ispb,
                    },
            dataType: 'json',
            delay: 250, 
            success: function(data) {
                return {
                results: data
                };
            },
             cache: true
        });
    });
});
</script>