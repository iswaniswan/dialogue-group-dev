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
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-6">Nomor Pelunasan</label><label class="col-md-6">Tanggal Pelunasan</label>
                        <div class="col-sm-6">
                            <input id="ipelunasanap" name="ipelunasanap" class="form-control" required="" readonly value="<?= $isi->i_pelunasanap;?>">
                        </div>
                        <div class="col-sm-6">
                            <input id= "dpelunasanap" name="dpelunasanap" class="form-control" required="" readonly value="<?= date('d-m-Y', strtotime($isi->d_bukti));?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Debitur</label>
                        <div class="col-sm-12">
                            <input id= "esuppliername" name="esuppliername" class="form-control" required="" readonly value="<?= $isi->e_supplier_name;?>">
                            <input id="isupplier" name="isupplier" type="hidden" value="<?= $isi->i_supplier; ?>">
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-12">Alamat</label>
                        <div class="col-sm-12">
                            <input readonly id="esupplieraddress" name="esupplieraddress" class="form-control" maxlength="100" value="<?= $isi->e_supplier_address; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Kota</label>
                        <div class="col-sm-12">
                            <input id="esuppliercity" name="esuppliercity" class="form-control" readonly value="<?= $isi->e_supplier_city; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-8">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales();"> <i class="fa fa-save"></i>&nbsp;&nbsp;Dicek</button>&nbsp;&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/view/<?= $dfrom;?>/<?= $dto;?>/<?= $isupplier;?>/","#main")'> <i
                                class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6"> 
                        <div class="form-group row">
                            <label class="col-md-12">Giro/Cek</label>
                            <div class="col-sm-6">
                                <input id="ejenisbayarname" name="ejenisbayarname" class="form-control" value="<?= $isi->e_jenis_bayarname; ?>" readonly>
                                <input id="ijenisbayar" name="ijenisbayar" type="hidden" value="<?= $isi->i_jenis_bayar; ?>">
                            </div>
                            <div class="col-sm-6">
                                <input id="igiro" class="form-control" name="igiro" readonly="" value="<?= $isi->i_giro; ?>">
                            </div>
                        </div>                        
                        <div class="form-group row">
                            <label class="col-md-12">Bank</label>
                            <div class="col-sm-12">
                                <input readonly="" id="egirobank" name="egirobank" maxlength="100" class="form-control" value="<?= $isi->e_bank_name; ?>">
                                <input type="hidden" id="nkuyear" name="nkuyear" value="<?= date('Y', strtotime($isi->d_bukti));?>">
                            </div>
                        </div>                   
                        <div class="form-group row">
                            <label class="col-md-4">Tanggal Jatuh Tempo</label><label class="col-md-4">Jumlah</label><label class="col-md-4">Lebih</label>
                            <div class="col-sm-4">
                                <input readonly="" id="djt" name="djt" maxlength="100" class="form-control" value="<?= date('d-m-Y', strtotime($isi->d_giro)); ?>">
                            </div>
                            <div class="col-sm-4">
                                <input onkeypress="return hanyaAngka(event);" onkeyup="reformat(this); hetang();" id="vjumlah" name="vjumlah" maxlength="100" class="form-control" value="<?= number_format($isi->v_jumlah); ?>">
                                <input type="hidden" id="vsisa" name="vsisa" value="<?= $vsisa; ?>" >
                            </div>
                            <div class="col-sm-4">
                                <input readonly="" id="vlebih" name="vlebih" class="form-control" value="<?= number_format($isi->v_lebih); ?>">
                            </div>
                        </div>                        
                        <div class="form-group row">
                            <label class="col-md-12">Ket Cek</label>
                            <div class="col-sm-12">
                                <input id="ecek1" name="ecek1" maxlength="100" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%;" cellspacing="0">
                            <thead>
                                <tr>
                                    <th style="text-align: center; width: 5%;">No</th>
                                    <th style="text-align: center;">Nota</th>
                                    <th style="text-align: center;">Tanggal Nota</th>
                                    <th style="text-align: center;">Nilai</th>
                                    <th style="text-align: center;">Bayar</th>
                                    <th style="text-align: center;">Sisa</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($detail) {
                                    $i = 0;
                                    foreach ($detail as $row) { 
                                        $i++; 
                                        ?>
                                        <tr>
                                            <td style="text-align: center;"><?= $i;?>
                                            <input type="hidden" readonly class="form-control" id="baris<?=$i;?>" name="baris<?=$i;?>" value="<?= $i;?>">
                                        </td>
                                        <td>
                                            <input class="form-control" readonly id="inota<?= $i; ?>" name="inota<?= $i; ?>" value="<?= $row->i_dtap; ?>">
                                        </td>
                                        <td>
                                            <input readonly class="form-control" id="dnota<?= $i; ?>" name="dnota<?= $i; ?>" value="<?= date('d-m-Y', strtotime($row->d_dtap)); ?>">
                                        </td>
                                        <td>
                                            <input style="text-align: right;" class="form-control" readonly id="vnota<?=$i;?>" name="vnota<?=$i;?>" value="<?= number_format($row->v_jumlah); ?>">
                                        </td>
                                        <td>
                                            <input style="text-align: right;" class="form-control" id="vjumlah<?=$i;?>" name="vjumlah<?=$i;?>" value="<?= number_format($row->v_jumlah); ?>" onkeyup="reformat(this);hetangs(<?= $i;?>);">
                                        </td>
                                        <td>
                                            <input style="text-align: right;" class="form-control" readonly id="vsisa<?=$i;?>" name="vsisa<?=$i;?>" value="<?= $row->v_sisa_nota; ?>">
                                            <input type="hidden" id="vsesa<?= $i; ?>" name="vsesa<?= $i; ?>" value="<?= number_format($row->v_sisa_nota); ?>">
                                            <input type="hidden" id="vlebih<?= $i; ?>" name="vlebih<?= $i; ?>" value="">
                                            <input type="hidden" id="vasal<?= $i; ?>" name="vasal<?= $i; ?>" value="<?= $row->v_jumlah+$row->v_sisa_nota; ?>">
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
    function hetang(){
        jml=$("#jml").val();
        vjml=formatulang($("#vjumlah").val());
        vjmlitem=0;
        for(i=1;i<=jml;i++){
            vjmlitem=vjmlitem+parseFloat(formatulang($("#vjumlah"+i).val()));
        }
        $("#vlebih").val(formatcemua(parseFloat(formatulang(vjml))-vjmlitem));
    }

    function hetangs(a){ 
        vjmlbyr   = parseFloat(formatulang($("#vjumlah").val()));
        vlebih    = parseFloat(formatulang($("#vlebih").val()));
        vsisadt   = parseFloat(formatulang($("#vsisa").val()));
        vjmlitem  = parseFloat(formatulang($("#vjumlah"+a).val()));
        vsisaitem = parseFloat(formatulang($("#vsisa"+a).val()));
        vasal     = parseFloat(formatulang($("#vasal"+a).val()));
        jml       = $("#jml").val();
        if(vjmlitem>vasal){
            swal("jumlah bayar tidak boleh melebihi sisa nota !!!!!");
            if(vlebih>vasal){
                $("#vjumlah"+a).val(formatcemua(vasal));
            }else{
                $("#vjumlah"+a).val(formatcemua(vlebih));
            }
            vjmlitem=0;
            for(i=1;i<=jml;i++){
                vjmlitem=vjmlitem+parseFloat(formatulang($("#vjumlah"+i).val()));
            }
            $("#vlebih").val(formatcemua(vjmlbyr-vjmlitem));
        }else if(vjmlitem>vjmlbyr){
            swal("jumlah bayar item tidak boleh melebihi jumlah bayar total !!!!!");
            vjmlitem=0;
            for(i=1;i<=jml;i++){
                if(i!=a){
                    vjmlitem=vjmlitem+parseFloat(formatulang($("#vjumlah"+i).val()));
                }
            }   
            $("#vjumlah"+a).val(formatcemua(vjmlbyr-vjmlitem));
            $("#vlebih").val(0);
        }else if(vjmlitem>vsisadt){
            swal("jumlah bayar tidak boleh melebihi sisa tagihan !!!!!");
            $("#vjumlah"+a).val(0);
        }else{
            vjmlitem=0;
            for(i=1;i<=jml;i++){
                vjmlitem=vjmlitem+parseFloat(formatulang($("#vjumlah"+i).val()));
            }
            if(vjmlitem>vjmlbyr){
                swal("jumlah item tidak boleh melebihi jumlah bayar !!!!!");
                $("#vjumlah"+a).val(0);
                vjmlitem=0;
                for(i=1;i<=jml;i++){
                    vjmlitem=vjmlitem+parseFloat(formatulang($("#vjumlah"+i).val()));
                }
            }
            $("#vlebih").val(formatcemua(vjmlbyr-vjmlitem));
        }
    }

    function dipales(){
        if(($("#idt").val()!='') && ($("#ijenisbayar").val()!='') && ($("#vjumlah").val()!='') && ($("#isupplier").val()!='')) {
            var a = parseFloat($("#jml").val());
            for(i=1;i<=a;i++){
                if($("#vjumlah"+i).val()!='0'){
                    cek='true';
                    break;
                }else{
                    cek='false';
                } 
            }
            if(cek=='true'){
                return true;
            }else{
                alert('Isi jumlah detail pelunasan minimal 1 item !!!');
                return false;
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