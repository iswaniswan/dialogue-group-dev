<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <?if ($data){?>
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-4">Gudang</label>
                        <div class="col-sm-4">
                            <input type="hidden" name="ikodemaster" id="ikodemaster" class="form-control date" value="<?= $kodemaster;?>">
                            <input type="text" name="ekodemaster" id="ekodemaster" class="form-control date" value="<?= $gudang->e_nama_master;?>"disabled = 't'>
                            <input type="hidden" name="ikodeso" id="ikodeso" class="form-control date" value="<?= $data->i_stok_opname_bahanbaku;?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Date From</label>
                        <label class="col-md-8">Date to</label>
                        <div class="col-sm-4">
                            <input type="text" name="dfrom" id="dfrom" class="form-control" value="<?= $iperiodebl;?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" name="dto" id="dto" class="form-control" value="<?= $iperiodeth;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-5 col-sm-10">  
                        <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return cek();"><i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                    </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <?
                    }else{
                        $read = "disabled";
                        echo "<div class=\"col-md-12\"><table class=\"table table-striped bottom\" style=\"width:100%;\"><tr><td colspan=\"6\" style=\"text-align:center;\">Maaf Tidak Ada Data!</td></tr></table></div>";
                    }?>   
                    </div>
                    <div class="panel-body table-responsive">
                        <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Satuan</th>
                                    <th>Saldo Awal</th>
                                    <th>Saldo Akhir</th>
                                    <th>Stok Opname</th>
                                    <th>Selisih</th> 
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $i = 0;
                                    foreach ($datadetail as $row) {
                                    $i++;
                                ?>
                                <tr>
                                <td style="text-align: center;"><?= $i;?>
                                    <input type="hidden" class="form-control" readonly id="baris<?= $i;?>" name="baris<?= $i;?>" value="<?= $i;?>">
                                    <input style ="width:100px"type="hidden" class="form-control" id="ikode<?=$i;?>" name="ikode<?=$i;?>"value="<?= $row->i_stok_opname_bahanbaku; ?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:100px"type="text" class="form-control" id="imaterial<?=$i;?>" name="imaterial<?=$i;?>"value="<?= $row->i_kode_brg; ?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:450px"type="text" class="form-control" id="ematerialname<?=$i;?>" name="ematerialname<?=$i;?>"value="<?= $row->e_material_name; ?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:90px" type="text" class="form-control" id="esatuan<?=$i;?>" name="esatuan<?=$i;?>"value="<?= $row->e_satuan; ?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:100px"type="text" class="form-control" id="saldoawal<?=$i;?>" name="saldoawal<?=$i;?>"value="<?= $row->v_stok_awal; ?>"readonly >
                                </td>
                                
                                <td class="col-sm-1">
                                    <input style ="width:100px"type="text" class="form-control" id="saldoakhir<?=$i;?>" name="saldoakhir<?=$i;?>"value="<?= $row->v_saldo_akhir; ?>" readonly>
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:100px"type="text" class="form-control" id="stokopname<?=$i;?>" name="stokopname<?=$i;?>"value="<?= $row->v_jum_stok_opname; ?>" readonly>
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:100px"type="text" class="form-control" id="selisih<?=$i;?>" name="selisih<?=$i;?>"value="" readonly>
                                </td>
                                <?}?>
                                </tr>
                                <input style ="width:50px"type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
$(document).ready(function () {
    cekselisih();
}

 $("form").submit(function (event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("#submit").attr("disabled", true);
});

function cekselisih(){
    alert("tes");
    var jml = $('#jml').val();
    for(var i=1; i<=jml; i++){
        var saldoakhir = $('#saldoakhir'+i).val();
        var stokopname = $('#stokopname'+i).val();

        total = stokopname-saldoakhir;
        $('#selisih'+i).val(formatcemua(total));

    }
}
</script>