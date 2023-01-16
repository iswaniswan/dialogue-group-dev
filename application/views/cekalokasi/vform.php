<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-refresh"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-4">Nomor Bank</label><label class="col-md-4">Tanggal Bank</label><label class="col-md-4">Tanggal Alokasi</label>
                        <div class="col-sm-4">
                            <input id="ialokasi" name="ialokasi" class="form-control" required="" readonly value="<?= $isi->i_alokasi;?>">
                            <input type="hidden" id="ikbank" name="ikbank" value="<?= $isi->i_kbank; ?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <input id= "dbank" name="dbank" class="form-control" required="" readonly value="<?= date('d-m-Y', strtotime($isi->d_bank));?>">
                        </div>
                        <div class="col-sm-4">
                            <input id="dkn" class="form-control" name="dkn" readonly value="<?= date('d-m-Y', strtotime($isi->d_alokasi));?>">
                        </div>
                    </div>                    
                    <div class="form-group row">
                        <label class="col-md-12">Bank</label>
                        <div class="col-sm-12">
                            <input id="egirobank" class="form-control" name="egirobank" readonly value="<?= $isi->e_bank_name.' ('.$isi->i_kbank.')';?>">
                        </div>
                    </div>                      
                    <div class="form-group row">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-12">
                            <input readonly id="eareaname" name="eareaname" class="form-control" value="<?= $isi->e_area_name; ?>">
                            <input id="iarea" name="iarea" type="hidden" value="<?= $isi->i_area; ?>">
                            <input id= "nkuyear" name="nkuyear" type="hidden" class="form-control"  readonly value="<?= date('Y', strtotime($isi->d_alokasi));?>">
                        </div>
                    </div>                                         
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan Cek</label>
                        <div class="col-sm-12">
                            <input id="ecek1" name="ecek1" value="<?= $isi->e_cek; ?>" maxlength="500" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-8">
                            <?php if($isi->i_cek==''){?>
                                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales();"> <i class="fa fa-save"></i>&nbsp;&nbsp;Dicek</button>&nbsp;&nbsp;
                            <?php } ?>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/view/<?= $dfrom."/".$dto."/".$iarea;?>/","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6"> 
                    <div class="form-group row">
                        <label class="col-md-12">Debitur</label>
                        <div class="col-sm-12">
                            <input id= "ecustomername" name="ecustomername" class="form-control" required="" readonly value="<?= $isi->e_customer_name;?>">
                            <input id="icustomer" name="icustomer" type="hidden" value="<?= $isi->i_customer; ?>">
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-12">Alamat</label>
                        <div class="col-sm-12">
                            <input readonly id="ecustomeraddress" name="ecustomeraddress" class="form-control" value="<?= $isi->e_customer_address; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Kota</label>
                        <div class="col-sm-12">
                            <input id="ecustomercity" name="ecustomercity" class="form-control" readonly value="<?= $isi->e_customer_city; ?>">
                        </div>
                    </div>                    
                    <div class="form-group row">
                        <label class="col-md-4">Jumlah</label><label class="col-md-4">Lebih</label><label class="col-md-4">Pembulatan</label>
                        <div class="col-sm-4">
                            <input id="vjumlah" name="vjumlah" readonly="" class="form-control" value="<?= number_format($isi->v_jumlah); ?>">
                            <input type="hidden" id="vsisa" name="vsisa" value="0" >
                        </div>
                        <div class="col-sm-4">
                            <input readonly="" id="vlebih" name="vlebih" class="form-control" value="<?= number_format($isi->v_lebih); ?>">
                        </div>
                        <div class="col-sm-4">
                            <input readonly="" id="vbulat" name="vbulat" class="form-control" value="0">
                        </div>
                    </div>   
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%;" cellspacing="0" cellpadding="0">
                        <thead>
                            <tr>
                                <th style="text-align: center; width: 5%;">No</th>
                                <th style="text-align: center;">Nota</th>
                                <th style="text-align: center;">Tanggal Nota</th>
                                <th style="text-align: center;">Nilai</th>
                                <th style="text-align: center;">Bayar</th>
                                <th style="text-align: center;">Sisa</th>
                                <th style="text-align: center;">Ket2</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($detail) {
                                $i = 0;
                                foreach ($detail as $row) { 
                                    $i++; 
                                    ?>
                                    <tr>
                                        <th scope="row" style="text-align: center;">
                                            <?= $i;?>
                                            <input type="hidden" readonly class="form-control" id="baris<?=$i;?>" name="baris<?=$i;?>" value="<?= $i;?>">
                                        </th>
                                        <td>
                                            <input class="form-control" readonly id="inota<?= $i; ?>" name="inota<?= $i; ?>" value="<?= $row->i_nota; ?>">
                                        </td>
                                        <td>
                                            <input readonly class="form-control" id="dnota<?= $i; ?>" name="dnota<?= $i; ?>" value="<?= date('d-m-Y', strtotime($row->d_nota)); ?>">
                                        </td>
                                        <td>
                                            <input style="text-align: right;" class="form-control" readonly id="vnota<?=$i;?>" name="vnota<?=$i;?>" value="<?= number_format($row->v_nota); ?>">
                                        </td>
                                        <td>
                                            <input style="text-align: right;" class="form-control" id="vjumlah<?=$i;?>" name="vjumlah<?=$i;?>" value="<?= number_format($row->v_jumlah); ?>" readonly>
                                        </td>
                                        <td>
                                            <input style="text-align: right;" class="form-control" readonly id="vsisa<?=$i;?>" name="vsisa<?=$i;?>" value="<?= $row->v_sisa_nota; ?>">
                                            <input type="hidden" id="vsesa<?= $i; ?>" name="vsesa<?= $i; ?>" value="<?= number_format($row->v_sisa_nota); ?>">
                                            <input type="hidden" id="vlebih<?= $i; ?>" name="vlebih<?= $i; ?>" value="">
                                            <input type="hidden" id="vasal<?= $i; ?>" name="vasal<?= $i; ?>" value="<?= $row->v_jumlah+$row->v_sisa_nota; ?>">
                                        </td>
                                        <td>
                                            <input readonly id="eremark<?= $i; ?>" name="eremark<?= $i; ?>" value="<?= $row->e_remark; ?>">
                                        </td>
                                    </tr>
                                <?php  } ?>
                                <input type="hidden" readonly name="jml" id="jml" value="<?= $i;?>">
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
    function dipales(){
        if(($("#ikbank").val()!='') && ($("#dbank").val()!='') && ($("#dalokasi").val()!='') && ($("#vjumlah").val()!='') && ($("#vjumlah").val()!='0') && ($("#icustomer").val()!='')) {
            var a=parseFloat($("#jml").val());
            for(i=1;i<=a;i++){
                if($("#vjumlah"+i).val()!='0'){
                    sisa=parseFloat(formatulang($("#vsisa"+i).val()));
                    awal=parseFloat(formatulang($("#vjumlah"+i).val()));
                    if( (sisa-awal>0) ){
                        swal('Keterangan sisa wajib diisi !!!');
                        return false;
                    }else{
                        return true;
                    }
                }else{
                    swal('Isi jumlah detail pelunasan minimal 1 item !!!');
                    return false;
                }
            }
        }else{
            swal('Data header masih ada yang salah !!!');
            return false;
        }
    }

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
    });
</script>