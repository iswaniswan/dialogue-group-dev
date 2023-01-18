<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-4">Gudang</label>
                        <label class="col-md-4">No Konversi </label>
                        <label class="col-md-4">Tanggal</label>
                        <div class="col-sm-4">
                            <select name="ikodemaster" id="ikodemaster" class="form-control select2" disabled="" onchange="getstore();">
                                <option value="" selected>-- Pilih Gudang --</option>
                                <?php foreach ($gudang as $ikodemaster):?>
                                    <?php if ($ikodemaster->i_kode_master == $head->i_kode_master) { ?>
                                    <option value="<?php echo $ikodemaster->i_kode_master;?>" selected><?= $ikodemaster->e_nama_master;?></option>
                                    <?php }else { ?>
                                    <option value="<?php echo $ikodemaster->i_kode_master;?>"><?= $ikodemaster->e_nama_master;?></option>
                                    <?php }?>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" id="istore" name="istore" class="form-control" value="<?php echo $head->i_kode_master?>">
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id="nokonversi" name="nokonversi" class="form-control" value="<?php echo $head->i_konv?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id="dkonversi" name="dkonversi" class="form-control date" value="<?php echo $head->d_konv?>" readonly>
                        </div>
                    </div>    
                    <div class="form-group">
                        <label class="col-md-4">No Pengeluaran Pinjaman</label>
                        <div class="col-sm-8">
                            <input type="text" id="isjkp" name="isjkp" class="form-control" value="<?php echo $head->i_reff?>" readonly>
                        </div>
                    </div>    
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                        <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return cek();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>  
                        <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>           
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-12">Customer</label>
                        <div class="col-sm-6">
                            <select name="icustomer" id="icustomer" class="form-control select2">
                                <option value="" selected>-- Pilih Customer --</option>
                                <?php foreach ($customer as $icustomer):?>
                                    <?php if ($icustomer->i_supplier == $head->i_customer) { ?>
                                    <option value="<?php echo $icustomer->i_supplier;?>" selected><?= $icustomer->e_supplier_name;?></option>
                                    <?php }else { ?>
                                    <option value="<?php echo $icustomer->i_supplier;?>"><?= $icustomer->e_supplier_name;?></option>
                                    <?php }?>
                                <?php endforeach; ?>
                            </select>
                        </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <input type="text" id= "eremark "name="eremark" class="form-control" maxlength="30" value="<?php echo $head->e_remark?>">
                        </div>
                    </div>                  
                </div>
                    <div class="table-responsive">
                        <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Barang</th>
                                    <th width="35%">Nama Barang</th>
                                    <th>Qty Pinjaman Awal</th>
                                    <th>Qty Outstanding</th>
                                    <th>Qty Konversi</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?$i = 0;
                                foreach ($datadetail as $row) {
                                $i++;?>
                                <tr>
                                <td style="text-align: center;"><?= $i;?>
                                    <input type="hidden" class="form-control" readonly id="baris<?= $i;?>" name="baris<?= $i;?>" value="<?= $i;?>">
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:160px" type="text" class="form-control" id="imaterial<?=$i;?>" name="imaterial[]"value="<?= $row->i_material; ?>"  readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:400px"type="text" id="ematerialname<?=$i;?>" name="ematerialname<?=$i;?>"value="<?= $row->e_material_name; ?>" class="form-control" readonly >
                                </td>                   
                                <td class="col-sm-1">
                                    <input style ="width:80px" class="form-control" type="text" id="nqtyawal<?=$i;?>" name="nqtyawal[]"value="<?= number_format($row->n_qty,0); ?>" readonly>
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:80px" class="form-control" type="text" id="nqtyout<?=$i;?>" name="nqtyout[]"value="<?= number_format($row->n_qty_out,0); ?>" readonly>
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:80px" class="form-control" type="text" id="nquantity<?=$i;?>" name="nquantity[]"value="<?= number_format($row->n_konversi,0); ?>" >
                                </td>
                                <td class="col-sm-1">
                                    <input class="form-control" type="hidden" id="isatuan<?=$i;?>" name="isatuan[]"value="<?= $row->i_satuan_code; ?>" >
                                    <input style ="width:300px" class="form-control" type="text" id="edesc<?=$i;?>" name="edesc[]"value="<?= $row->e_remark; ?>" >
                                </td>
                                </tr>
                                <?}?>
                                <input style ="width:50px"type="hidden" name="jml" id="jml" value="<?= $i; ?>"> 
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    $('.select2').select2();
    showCalendar('.date');
});

function getstore() {
    var gudang = $('#ikodemaster').val();
    //alert(gudang);

    if (gudang == "") {

    } else {
        $('#istore').val(gudang);
        $("#ikodemaster").attr("disabled", true);
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