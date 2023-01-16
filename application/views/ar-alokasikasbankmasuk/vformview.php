<style type="text/css">
    .bold{
        font-weight: bold;
    }
</style>
<form id="cekinputan">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
                </div>
                <div class="panel-body table-responsive">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-3">Bagian Pembuat</label>
                            <label class="col-md-3">Nomor Dokumen</label>
                            <label class="col-md-2">Tanggal Dokumen</label>
                            <label class="col-md-2">Nomor Referensi</label>
                            <label class="col-md-2">Tanggal Referensi</label>
                            <div class="col-sm-3">
                                <input type="text" readonly="" class="form-control input-sm" value="<?= $data->e_bagian_name;?>">
                            </div>
                            <div class="col-sm-3">
                                <input type="hidden" name="id" id="id" value="<?= $data->id;?>">
                                <input type="text" readonly="" class="form-control input-sm" value="<?= $data->i_document;?>">
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control input-sm date" required="" readonly value="<?= $data->d_document;?>">
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control input-sm" required="" readonly value="<?= $data->i_referensi; ?>">
                            </div>
                            <div class="col-sm-2">
                                <input type="text" id="drefrensi" name="drefrensi" class="form-control input-sm" readonly value="<?= $data->d_referensi; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3">Customer</label>
                            <label class="col-md-3">Alamat</label>
                            <label class="col-md-3">Kota</label>
                            <label class="col-md-3">Keterangan</label>                            
                            <div class="col-sm-3">
                                <input type="text" class="form-control input-sm" readonly value="<?= $data->e_partner_name.' ('.$data->i_partner.')';?>">
                            </div>
                            <div class="col-sm-3">
                                <textarea type="text" class="form-control input-sm" readonly><?= $data->e_partner_address;?></textarea>
                            </div>
                            <div class="col-sm-3">
                                <input type="text" class="form-control input-sm" readonly value="<?= $data->e_city_name;?>">
                            </div>
                            <div class="col-sm-3">
                                <textarea class="form-control" readonly><?= $data->e_remark;?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php $i = 0; if ($datadetail) {?>
    <div class="white-box" id="detail">
        <div class="col-sm-6">
            <h3 class="box-title m-b-0">Detail Barang</h3>
        </div>
        <div class="col-sm-12">
            <div class="table-responsive">
                <table id="tabledatay" class="table color-table inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center" width="3%;">No</th>
                            <th class="text-center" width="15%;">No. Nota</th>
                            <th class="text-center" width="11%;">Tgl. Nota</th>
                            <th class="text-center" width="12%;">Nilai</th>
                            <th class="text-center" width="12%;">Bayar</th>
                            <th class="text-center" width="12%;">Sisa</th>
                            <th class="text-center" width="12%;">Lebih</th>
                            <th class="text-center" width="17%;">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($datadetail as $key) { $i++; ?>
                            <tr>
                                <td class="text-center"><spanx id="snum<?=$i;?>"><?=$i;?></spanx></td>
                                <td>
                                    <input type="hidden" id="idnota<?=$i;?>" value="<?= $key->id_nota;?>"/>
                                    <input type="text" readonly class="form-control input-sm" value="<?= $key->i_nota;?>"/>
                                </td>
                                <td><input type="text" readonly class="form-control input-sm" id="dnota<?=$i;?>" value="<?= $key->d_nota;?>"/></td>
                                <td>
                                    <input type="text" readonly class="form-control input-sm text-right" id="vnotabersih<?=$i;?>" value="<?= number_format($key->v_nota);?>"/>
                                    <input type="hidden" readonly class="form-control input-sm text-right" id="vnilai<?=$i;?>" value="<?= number_format($key->v_sisa);?>"/>
                                </td>
                                <td><input type="text" readonly class="form-control text-right input-sm" id="vbayar<?=$i;?>" value="<?= number_format($key->v_jumlah);?>"></td>
                                <td><input type="text" readonly class="form-control input-sm text-right" id="vsisa<?=$i;?>" value="<?= number_format($key->v_sisa);?>"/></td>
                                <td><input type="text" readonly class="form-control input-sm text-right" id="vlebih<?=$i;?>" value="0"/></td>
                                <td><input type="text" readonly class="form-control input-sm" value="<?= $key->e_remark;?>"/></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr class="bold">
                            <td class="text-right" colspan="6">Jumlah Referensi :</td>
                            <td>
                                <input type="text" id="vjumlah" name="vjumlah" class="form-control input-sm text-right" value="<?= number_format($data->v_jumlah);?>" readonly>
                                <input type="hidden" id="vjumlahsisa" name="vjumlahsisa" class="form-control input-sm text-right" value="" readonly>
                                <input type="hidden" id="vjumlahlebih" name="vjumlahlebih" class="form-control input-sm text-right" value="<?= number_format($data->v_jumlah);?>" readonly>
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <?php }else{ ?>
        <div class="white-box">
            <div class="card card-outline-danger text-center text-dark">
                <div class="card-block">
                    <footer>
                        <cite title="Source Title"><b>Item Tidak Ada</b></cite>
                    </footer>
                </div>
            </div>
        </div>
    <?php } ?>
<input type="hidden" name="jml" id="jml" value ="<?= $i;?>">
</form>
<script>

    /*----------  LOAD SAAT DOKUMEN DIBUKA  ----------*/    
    $(document).ready(function () {
        /*----------  Load Form Validation  ----------*/        
        hitung();
    });

    /*----------  HITUNG NILAI  ----------*/
    function hitung(){
        var vjmlbyr     = parseFloat(formatulang($('#vjumlah').val()));
        var vlebihitem  = vjmlbyr;
        var vjmlsisa    = 0;
        for (var i = 1; i <= $('#jml').val(); i++) {
            if(typeof $('#idnota'+i).val() != 'undefined'){
                vnota = parseFloat(formatulang($('#vnilai'+i).val()));
                if (!isNaN(parseFloat($('#vbayar'+i).val()))){
                    vjmlitem = parseFloat(formatulang($('#vbayar'+i).val()));
                }else{
                    vjmlitem = 0;
                }
                /*vsisaitem = vnota - vjmlitem;
                if (vsisaitem < 0) {
                    swal("Maaf :(","Jumlah bayar tidak bisa lebih besar dari nilai nota !!!!!","error");
                    $('#vbayar'+i).val(0);
                    vjmlitem = parseFloat(formatulang($('#vbayar'+i).val()));
                    vsisaitem = parseFloat(formatulang($('#vnilai'+i).val()));
                }*/
                vlebihitem = vlebihitem - vjmlitem;
                if (vlebihitem < 0) {
                    vlebihitem = vlebihitem + vjmlitem;
                   // vsisaitem = vnota - vlebihitem;
                    swal("jumlah item tidak bisa lebih besar dari nilai bayar !!!!!");
                    $('#vbayar'+i).val(formatcemua(vlebihitem));
                    vjmlitem = parseFloat(formatulang($('#vbayar'+i).val()));
                    vlebihitem = 0;
                }
                vjmlsisa += vjmlitem; 
                //$('#vsisa'+i).val(formatcemua(vsisaitem));
                $('#vlebih'+i).val(formatcemua(vlebihitem));
            }
        }
        $("#vjumlahsisa").val(formatcemua(vjmlbyr-vjmlsisa));
        $("#vjumlahlebih").val(formatcemua(vlebihitem));
    }
    /*----------  END HITUNG NILAI  ----------*/
</script>