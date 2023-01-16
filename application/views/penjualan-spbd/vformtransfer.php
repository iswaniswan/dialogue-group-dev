<style type="text/css">
    .pudding{
        padding-left: 3px;
        padding-right: 3px;
        font-size: 14px;
        background-color: #e1f1e4;
    }
</style>
<form>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
                </div>
                <div class="panel-body table-responsive">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-2">Bagian Pembuat</label>
                            <div class="col-sm-3">
                                <select name="ibagian" id="ibagian" class="form-control select2" required="">
                                    <?php if ($bagian) {
                                        foreach ($bagian as $row):?>
                                            <option value="<?= $row->i_bagian;?>">
                                                <?= $row->e_bagian_name;?>
                                            </option>
                                        <?php endforeach; 
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2">Distributor</label>
                            <div class="col-sm-3">
                                <input type="text" readonly="" name="ecustomer" class="form-control input-sm" value="<?= $data->e_customer_name;?>">
                                <input type="hidden" id="etypespb" name="etypespb" class="form-control input-sm" value="Transfer" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2">Kelompok Harga</label>
                            <div class="col-sm-3">
                                <input type="hidden" name="idcustomer" id="idcustomer" value="<?= $idcustomer;?>">
                                <input type="hidden" name="idharga" value="<?= $data->id_harga_kode;?>">
                                <input type="hidden" name="vdiscount1" value="<?= $data->v_customer_discount;?>">
                                <input type="hidden" name="vdiscount2" value="<?= $data->v_customer_discount2;?>">
                                <input type="hidden" name="vdiscount3" value="<?= $data->v_customer_discount3;?>">
                                <input type="text" readonly="" name="eharga" class="form-control input-sm" value="<?= $data->i_harga.' - '.$data->e_harga;?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <button type="button" id="submit" class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>&nbsp;
                                <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform/transfer/<?= $dfrom."/".$dto."/".$idcustomer;?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <span class="notekode"><b>Note : </b></span><br>
                            <span class="notekode">* Harga barang jadi, sesuai yang di master harga jual barang jadi berdasarkan kelompok harga distributor!</span><br>
                            <span class="notekode">* Apabila ada item yang tidak muncul, mohon untuk dicek di master harga jual barang jadi sesuai kelompok harga distributornya!</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $i = 0; if ($datadetail) {?>
    <div class="white-box" id="detail">
        <div class="col-sm-6">
            <h3 class="box-title m-b-0">Detail Order Pembelian (OP)</h3>
        </div>
        <div class="col-sm-12">
            <div class="table-responsive">
                <table id="tabledatay" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center" width="3%">No</th>
                            <th class="text-center" width="12%;">Kode</th>
                            <th class="text-center" width="30%;">Nama Barang</th>
                            <th class="text-center" width="8%">Sisa FC</th>
                            <th class="text-center" width="8%">Qty SPB</th>
                            <th class="text-center" width="12%">Harga</th>
                            <th class="text-center">Keterangan</th>
                            <th class="text-center" width="3%"><input type="checkbox" id="ceklisall"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 0; $group = ""; foreach ($datadetail as $key) {
                            $no++;
                            if($group==""){ ?>
                                <tr class="pudding">
                                    <td colspan="8">Nomor Referensi : <b><?= $key->i_op;?></b> &nbsp;&nbsp;-&nbsp;&nbsp; Tanggal Referensi : <b><?= $key->d_referensi;?></b> &nbsp;&nbsp;-&nbsp;&nbsp; Area : <b><?= $key->i_area.' - '.$key->e_area;?></b></td>
                                </tr>
                                <?php 
                            }else{
                                if($group!=$key->i_op){?>
                                    <tr class="pudding">
                                        <td colspan="8">Nomor Referensi : <b><?= $key->i_op;?></b> &nbsp;&nbsp;-&nbsp;&nbsp; Tanggal Referensi : <b><?= $key->d_referensi;?></b> &nbsp;&nbsp;-&nbsp;&nbsp; Area : <b><?= $key->i_area.' - '.$key->e_area;?></b></td>
                                    </tr>
                                    <?php $no = 1; }
                                }
                                $group = $key->i_op;?>
                                <tr>
                                    <td class="text-center"><?= $no;?></td>
                                    <td><input readonly class="form-control input-sm" type="text" id="i_product<?=$i;?>" name="i_product<?=$i;?>" value="<?= $key->i_product;?>">
                                        <input type="hidden" name="iop<?=$i;?>" value="<?= $key->i_op;?>">
                                        <input type="hidden" name="idarea<?=$i;?>" value="<?= $key->id_area;?>">
                                        <input type="hidden" id="idproduct<?=$i;?>" name="idproduct<?=$i;?>" value="<?= $key->id_product_base;?>">
                                    </td>
                                    <td><input readonly class="form-control input-sm" type="text" value="<?= $key->e_product_basename;?>"></td>
                                    <td><input readonly class="form-control input-sm text-right" type="text" id="fc<?=$i;?>" value="<?= $key->fc;?>"></td>
                                    <td><input class="form-control input-sm text-right inputitem" type="text" id="qty<?=$i;?>" name="qty<?=$i;?>" value="<?= $key->n_order;?>" placeholder="0" autocomplete="off" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" onkeyup="angkahungkul(this);ngetang(<?=$i;?>)"></td>
                                    <td><input readonly class="form-control input-sm text-right" type="text" name="harga<?=$i;?>" value="<?= number_format($key->v_price);?>"></td>
                                    <td><input class="form-control input-sm" type="text" name="eremark<?=$i;?>" value="<?= $key->e_op_remark;?>"></td>
                                    <td class="text-center"><input class="text-center" onclick="cek(<?=$i;?>);" type="checkbox" id="ceklis<?=$i;?>" name="ceklis<?=$i;?>"></td>
                                </tr>
                                <?php $i++; 
                            } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <input type="hidden" name="jml" id="jml" value ="<?=$i;?>">
<?php }else{ ?>
    <div class="white-box">
            <div class="card card-outline-danger text-center text-dark">
                <div class="card-block">
                    <footer>
                        <cite title="Source Title"><b>Item Tidak Ada, Silahkan Cek Kesesuain Master Dengan Kode Barang Pelanggan!</b></cite>
                    </footer>
                </div>
            </div>
        </div>
<?php } ?>
</form>
<script>

    /*----------  LOAD SAAT DOKUMEN DIBUKA  ----------*/    
    $(document).ready(function () {
        $('.select2').select2();
    });

    /*----------  VALIDASI CEKLIS SEMUA  ----------*/
    $('#ceklisall').click(function(event) {
        var valid = true;
        if(this.checked) {
            var x = 0-1;
            $(':checkbox').each(function() {
                this.checked = true;
                if (typeof $('#qty'+x).val()!='undefined' && typeof $('#fc'+x).val()!='undefined') {
                    if (parseInt($('#qty'+x).val()) > parseInt($('#fc'+x).val())) {
                        valid = false;
                        $('#ceklis'+x).attr('checked', false);
                    }
                }
                x++;
            });
        } else {
            $(':checkbox').each(function() {
                this.checked = false;                       
            });
        }
        if (valid==false) {
            swal('Maaf :(','Qty SPB tidak boleh lebih dari Qty Sisa FC!!!','error');
            return false;
        }
    });
    
    /*----------  VALIDASI CEKLIS SATU  ----------*/
    function cek(i) {
        if($('#ceklis'+i).is(':checked')){
            if (parseInt($('#qty'+i).val()) > parseInt($('#fc'+i).val())) {
                swal('Maaf :(','Qty SPB tidak boleh lebih dari Qty Sisa FC!!!','error');
                $('#ceklis'+i).attr('checked', false);
                return false;
            }
        }
    }

    /*----------  VALIDASI INPUT SPB  ----------*/
    function ngetang(i) {
        if (parseInt($('#qty'+i).val()) > parseInt($('#fc'+i).val())) {
            swal('Maaf :(','Qty SPB tidak boleh lebih dari Qty Sisa FC!!!','error');
            $('#qty'+i).val($('#fc'+i).val());
        }
    }

    /*----------  VALIDASI UPDATE DATA  ----------*/    
    $( "#submit" ).click(function(event) {
        if ($("#tabledatay input:checkbox:checked").length > 0){
            ada = false;
            if (($('#ibagian').val()!='' || $('#ibagian').val()!=null) && ($('#idcustomer').val()!='' || $('#idcustomer').val()!=null)) {
                if ($('#jml').val()==0) {
                    swal('Isi item minimal 1!');
                    return false;
                }else{
                    for (var i = 0; i <= $('#jml').val(); i++) {
                        if($('#ceklis'+i).is(':checked')){
                            if (parseInt($('#qty'+i).val()) <= 0) {
                                swal('Maaf :(','Quantity Tidak Boleh Kosong Atau 0!','error');
                                $('#ceklis'+i).attr('checked', false);
                                return false;
                            } else {
                                var sumfc = 0;
                                var sumspb = 0;
                                for (var j = 0; j <= $('#jml').val(); j++) { 
                                    if($('#ceklis'+j).is(':checked')){
                                        if ($('#idproduct'+i).val() == $('#idproduct'+j).val()) {
                                            sumspb = parseInt(sumspb) + parseInt($('#qty'+j).val());
                                            sumfc =  parseInt($('#fc'+i).val());
                                        }
                                    }     
                                }

                                //console.log(sumfc+ " " + sumspb);
                                if (sumspb > sumfc) {
                                    swal('Maaf :(','Jumlah Barang '+ $('#i_product'+i).val()+' melebihi jumlah forecast','error');
                                    //$('#ceklis'+i).attr('checked', false);
                                    return false;
                                }
                            }
                        }
                    }
                    if (!ada) {
                        swal({   
                            title: "Simpan Data Ini?",   
                            text: "Anda Dapat Membatalkannya Nanti",
                            type: "warning",   
                            showCancelButton: true,   
                            confirmButtonColor: "#DD6B55",   
                            confirmButtonColor: 'LightSeaGreen',
                            confirmButtonText: "Ya, Simpan!",   
                            closeOnConfirm: false 
                        }, function(){
                            $.ajax({
                                type: "POST",
                                data: $("form").serialize(),
                                url: '<?= base_url($folder.'/cform/simpantransfer/'); ?>',
                                dataType: "json",
                                success: function (data) {
                                    if (data.sukses==true) {
                                        swal({
                                            title: "Sukses :)",
                                            text: "No Dokumen : "+data.kode+", Berhasil Disimpan :)",
                                            showConfirmButton: true,
                                            type: "success",
                                        },function(){
                                            show('<?= $folder;?>/cform/transfer/<?= $dfrom."/".$dto."/".$idcustomer;?>','#main');
                                        }); 
                                    }else{
                                        swal("Maaf :(", "No Dokumen : "+data.kode+", Gagal Disimpan :(", "error"); 
                                    }
                                },
                                error: function () {
                                    swal("Maaf", "Data Gagal Disimpan :(", "error");
                                }
                            });
                        });
                    }else{
                        return false;
                    }
                }
            }else{
                swal('Data Header Masih Ada yang Kosong!');
                return false;
            }
        }else{
            swal('Maaf :(','Pilih data minimal satu!','error');
            return false;
        }
    })
</script>