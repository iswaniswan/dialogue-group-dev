<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-6">Bagian</label>
                        <label class="col-md-6">No Konversi </label>                        
                        <div class="col-sm-6">
                            <select name="ikodemaster" id="ikodemaster" class="form-control select2" disabled="" onchange="getstore();">
                                <option value="" selected>-- Pilih Bagian --</option>
                                <?php foreach ($gudang as $ikodemaster):?>
                                    <?php if ($ikodemaster->i_departement == $head->i_bagian) { ?>
                                    <option value="<?php echo $ikodemaster->i_departement;?>" selected><?= $ikodemaster->e_departement_name;?></option>
                                    <?php }else { ?>
                                    <option value="<?php echo $ikodemaster->i_departement;?>"><?= $ikodemaster->e_departement_name;?></option>
                                    <?php }?>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" id="istore" name="istore" class="form-control" value="<?php echo $head->i_bagian?>">
                        </div>
                        <div class="col-sm-5">
                            <input type="text" id="nokonversi" name="nokonversi" class="form-control" value="<?php echo $head->i_konversi?>" readonly>
                        </div>
                       
                    </div>    
                    <div class="form-group">
                        <label class="col-md-12">No Pengeluaran Pinjaman</label>
                        <div class="col-sm-6">
                            <input type="hidden" id="isjkp" name="isjkp" class="form-control" value="<?php echo $head->i_referensi?>" readonly>
                              <select name="isjkp" id="isjkp" class="form-control select2" disabled="">
                                <option value="" selected>Pilih No Pengeluaran Pinjaman</option>
                                <?php foreach ($referensi as $isjkp):?>
                                    <?php if ($isjkp->i_bonmk == $head->i_referensi) { ?>
                                    <option value="<?php echo $isjkp->i_bonmk;?>" selected><?= $isjkp->i_bonmk." || ".$isjkp->d_bonmk;?></option>
                                    <?php }else { ?>
                                    <option value="<?php echo $isjkp->i_bonmk;?>"><?= $isjkp->i_bonmk." || ".$isjkp->d_bonmk;?></option>
                                    <?php }?>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" id="dreferensi" name="dreferensi" class="form-control" value="<?php echo $head->d_referensi?>" readonly>
                        </div>
                    </div>    
                    <div class="form-group">
                        <div class="col-sm-offset-5 col-sm-10">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button> 
                        </div>     
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-4">Tanggal</label>
                        <label class="col-md-8">Partner</label>
                         <div class="col-sm-4">
                            <input type="text" id="dkonversi" name="dkonversi" class="form-control" value="<?php echo $head->d_konversi?>" readonly>
                        </div>
                        <div class="col-sm-8">
                            <select name="ipartner" id="ipartner" class="form-control select2" disabled="">
                                <option value="" selected>-- Pilih Partner --</option>
                                <?php foreach ($partner as $ipartner):?>
                                    <?php if ($ipartner->i_supplier == $head->i_partner) { ?>
                                    <option value="<?php echo $ipartner->i_supplier;?>" selected><?= $ipartner->e_supplier_name;?></option>
                                    <?php }else { ?>
                                    <option value="<?php echo $ipartner->i_supplier;?>"><?= $ipartner->e_supplier_name;?></option>
                                    <?php }?>
                                <?php endforeach; ?>
                            </select>
                        </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <input type="text" id= "eremark "name="eremark" class="form-control" maxlength="30" value="<?php echo $head->e_remark?>" readonly>
                        </div>
                    </div>                  
                </div>
                    <div class="table-responsive">
                         <table id="tabledata" class="table color-table info-table table-bordered" cellspacing="0" width="100%">
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
                                        <input style ="width:160px" type="text" class="form-control" id="imaterial<?=$i;?>" name="imaterial[]"value="<?= $row->i_product; ?>"  readonly >
                                    </td>
                                    <td class="col-sm-1">
                                        <input style ="width:400px"type="text" id="ematerialname<?=$i;?>" name="ematerialname<?=$i;?>"value="<?= $row->e_material_name; ?>" class="form-control" readonly >
                                    </td>                   
                                    <td class="col-sm-1">
                                        <input style ="width:80px" class="form-control" type="text" id="nqtyawal<?=$i;?>" name="nqtyawal[]"value="<?= number_format($row->n_quantity_awal,0); ?>" readonly>
                                    </td>
                                    <td class="col-sm-1">
                                        <input style ="width:80px" class="form-control" type="text" id="nqtyout<?=$i;?>" name="nqtyout[]"value="<?= number_format($row->n_quantity_outstanding,0); ?>" readonly>
                                    </td>
                                    <td class="col-sm-1">
                                        <input style ="width:80px" class="form-control" type="text" id="nquantity<?=$i;?>" name="nquantity[]"value="<?= number_format($row->n_quantity_konversi,0); ?>" readonly>
                                    </td>
                                    <td class="col-sm-1">
                                        <input class="form-control" type="hidden" id="isatuan<?=$i;?>" name="isatuan[]"value="<?= $row->i_satuan; ?>" >
                                        <input style ="width:300px" class="form-control" type="text" id="edesc<?=$i;?>" name="edesc<?=$i;?>"value="<?= $row->e_remark; ?>" readonly>
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