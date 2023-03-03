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
                            <label class="col-md-3">Tanggal Dokumen</label>
                            <label class="col-md-3">Area</label>
                            
                            <div class="col-md-3">
                                <select class="form-control select2 input-sm" disabled>
                                    <option value="" selected><?= $data->e_bagian_name ?></option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control input-sm" value="<?= $data->i_alokasi_id ?>" readonly="" autocomplete="off">                                
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control input-sm date" readonly value="<?= $data->d_alokasi ?>">
                            </div>
                            <div class="col-md-3">
                                <select name="id_area" id="id_area" class="form-control select2 input-sm" disabled>
                                    <option value=""><?= $data->e_area ?></option>
                                </select>                           
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-4">Nomor Voucher</label>
                            <label class="col-md-4">Tanggal Voucher</label>
                            <label class="col-md-4">Bank</label>                            

                            <div class="col-md-4">
                                <input type="text" class="form-control input-sm" value="<?= $data->i_rv_id ?>" readonly>
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control input-sm" value="<?= $data->d_rv ?>" readonly="">
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control input-sm" value="<?= $data->e_coa_name ?>" readonly="">                                
                            </div>                            
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-6">Customer</label>
                            <label class="col-md-6">Jumlah Bayar</label>                            

                            <div class="col-md-6">
                                <input type="text" class="form-control input-sm" value="<?= $data->e_customer_name ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control input-sm" value="Rp. <?= number_format($data->v_jumlah, 0, ",", ".") ?>" readonly="">                                
                            </div>                           
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
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
            <h3 class="box-title m-b-0">Detail Alokasi</h3>
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
                            <!-- <th class="text-center" width="12%;">Sisa</th>
                            <th class="text-center" width="12%;">Lebih</th> -->
                            <th class="text-center" width="17%;">Keterangan</th>
                            <!-- <th class="text-center" width="3%">Act</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($datadetail as $item) { $i++; ?>
                            <tr id="tr<?= $i; ?>">
                                <td class="text-center">
                                    <spanx id="snum<?= $i; ?>"><?= $i; ?></spanx>
                                </td>
                                <td><?= $item->i_document ?></td>
                                <td><?= $item->d_document ?></td>
                                <td>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" style="padding: 0px 5px">Rp.</span>
                                        </div>
                                        <span><?= number_format($item->v_nota, 0, ",", ".") ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" style="padding: 0px 5px">Rp.</span>
                                        </div>
                                        <span><?= number_format($item->v_jumlah, 0, ",", ".") ?></span>
                                    </div>
                                </td>
                                <?php /*
                                <td>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" style="padding: 0px 5px">Rp.</span>
                                        </div>
                                        <span><?= number_format($item->v_sisa, 0, ",", ".") ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" style="padding: 0px 5px">Rp.</span>
                                        </div>
                                        <span><?= number_format($item->v_lebih, 0, ",", ".") ?></span>
                                    </div>
                                </td>
                                */ ?>
                                <td><?= $item->e_remark ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <?php /*
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
                    */ ?>
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
        return;
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

    $(document).ready(function() {
        $('.select2').select2();
    });

</script>