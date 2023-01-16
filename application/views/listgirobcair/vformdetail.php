<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/detail'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <?php 
                $tmp 	= explode("-", $dfrom);
                $det	= $tmp[0];
                $mon	= $tmp[1];
                $yir 	= $tmp[2];
                $dfrom	= $yir."-".$mon."-".$det;
                $tmp 	= explode("-", $dto);
                $det	= $tmp[0];
                $mon	= $tmp[1];
                $yir 	= $tmp[2];
                $dto	= $yir."-".$mon."-".$det;

                if($isi->d_giro!=''){
                    $tmp=explode("-",$isi->d_giro);
                    $th=$tmp[0];
                    $bl=$tmp[1];
                    $hr=$tmp[2];
                    $isi->d_giro=$hr."-".$bl."-".$th;
                }
                if($isi->d_rv!=''){
                    $tmp=explode("-",$isi->d_rv);
                    $th=$tmp[0];
                    $bl=$tmp[1];
                    $hr=$tmp[2];
                    $isi->d_rv=$hr."-".$bl."-".$th;
                }
                if($isi->d_giro_duedate!=''){
                    $tmp=explode("-",$isi->d_giro_duedate);
                    $th=$tmp[0];
                    $bl=$tmp[1];
                    $hr=$tmp[2];
                    $isi->d_giro_duedate=$hr."-".$bl."-".$th;
                }
                if($isi->d_giro_cair!=''){
                    $tmp=explode("-",$isi->d_giro_cair);
                    $th=$tmp[0];
                    $bl=$tmp[1];
                    $hr=$tmp[2];
                    $isi->d_giro_cair=$hr."-".$bl."-".$th;
                }
                if($isi->d_giro_tolak!=''){
                    $tmp=explode("-",$isi->d_giro_tolak);
                    $th=$tmp[0];
                    $bl=$tmp[1];
                    $hr=$tmp[2];
                    $isi->d_giro_tolak=$hr."-".$bl."-".$th;
                }
                ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-6">Giro</label><label class="col-md-6">Tanggal Giro</label>
                        <div class="col-sm-6">
                           <input readonly class="form-control" name="igiro" id="igiro" value="<?= $isi->i_giro; ?>" maxlength="10">
                       </div>
                       <div class="col-sm-6">
                        <input class="form-control" name="dgiro" id="dgiro" readonly="" required="" value="<?= $isi->d_giro; ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-6">Receive Voucher</label><label class="col-md-6">Tanggal Receive</label>
                    <div class="col-sm-6">
                        <input class="form-control" readonly="" name="irv" id="irv" value="<?= $isi->i_rv; ?>" maxlength="10">
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" readonly="" name="drv" id="drv" readonly="" required="" value="<?= $isi->d_rv; ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-12">Area</label>
                    <div class="col-sm-12">
                    <input class="form-control" readonly="" name="eareaname" id="eareaname" readonly value="<?= $isi->e_area_name; ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-12">Pelanggan</label>
                    <div class="col-sm-12">
                    <input class="form-control date" readonly="" name="ecustomername" id="ecustomername" readonly value="<?= $isi->e_customer_name; ?>">
                        <input type="hidden" name="icustomergroupar" id="icustomergroupar" value="">
                        <input type="hidden" name="ecustomername" id="ecustomername" value="">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-2">
                        <div class="form-check">
                            <label class="custom-control custom-checkbox">
                                <input name="fgirouse" id="fgirouse" value="<?php echo $isi->f_giro_use; ?>" type="hidden">
                                <input type="checkbox" id="fgirotolak" name="fgirotolak" class="custom-control-input" <?php if($isi->f_giro_tolak=='t'){ echo "checked";}?>>
                                <span class="custom-control-indicator"></span>
                                <span class="custom-control-description">Tolak</span>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-check">
                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" id="fgirobatal" name="fgirobatal" class="custom-control-input" <?php if($isi->f_giro_batal=='t'){ echo "checked";}?>>
                                <span class="custom-control-indicator"></span>
                                <span class="custom-control-description">Batal</span>
                            </label>
                        </div>
                    </div>
                </div>                 
                <div class="form-group row">
                    <div class="col-sm-offset-5 col-sm-12">
                        <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/view/<?= $dfrom."/".$dto."/".$iarea."/"; ?>","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                    </div>
                </div>
            </div>
            <div class="col-md-6"> 
                <div class="form-group row">
                    <label class="col-md-12">Tanggal Jatuh Tempo</label>
                    <div class="col-sm-6">
                        <input class="form-control" name="dgiroduedate" id="dgiroduedate" readonly="" required="" value="<?= $isi->d_giro_duedate ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-12">Tanggal Cair</label>
                    <div class="col-sm-6">
                        <input class="form-control" name="dgirocair" id="dgirocair" required="" readonly="">
                    </div>
                </div>                   
                <div class="form-group row">
                    <label class="col-md-12">Bank</label>
                    <div class="col-sm-12">
                        <input readonly type="text" name="egirobank" id="egirobank" class="form-control" value="<?= $isi->e_giro_bank;?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-12">Keterangan</label>
                    <div class="col-sm-12">
                        <input type="text" name="egirodescription" id="egirodescription" class="form-control"  value="<?= $isi->e_giro_description ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-6">Jumlah</label><label class="col-md-6">Sisa</label>
                    <div class="col-sm-6">
                        <input <?php if($isi->f_giro_use=='t') echo "readonly"; ?> readonly name="vjumlah" id="vjumlah" class="form-control" maxlength="16" autocomplete="off" required="" value="<?= number_format($isi->v_jumlah) ?>" onkeypress="return hanyaAngka(event);" onkeyup="reformat(this);sama(this.value);">
                    </div>
                    <div class="col-sm-6">
                        <input readonly name="vsisa" id="vsisa" class="form-control" required="" value="<?= number_format($isi->v_sisa) ?>">
                    </div>
                </div>       
            </div>
            <div class="panel-body table-responsive">
                <table id="tabledata" class="display table" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th style="text-align: center; width: 4%;">No</th>
                            <th style="text-align: center; width: 20%;">Nota</th>
                            <th style="text-align: center; width: 10%; ">Tanggal Nota</th>
                            <th style="text-align: center; width: 10%;">Nilai</th>
                            <th style="text-align: center; width: 15%;">Bayar</th>
                            <th style="text-align: center; width: 10%;">Sisa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php               
                        $i=0;
                        if($detail!=''){
                            foreach($detail as $row){ 
                                $i++;
                                if($row->d_nota!=''){
                                    $tmp=explode('-',$row->d_nota);
                                    $tgl=$tmp[2];
                                    $bln=$tmp[1];
                                    $thn=$tmp[0];
                                    $row->d_nota=$tgl.'-'.$bln.'-'.$thn;
                                }
                                ?>
                                <tr>
                                    <td style="text-align: center;"><?= $i;?>
                                    <input type="hidden" class="form-control" readonly id="baris<?= $i;?>" name="baris<?= $i;?>" value="<?= $i;?>">
                                </td>
                                <td>
                                    <input style="font-size: 12px;" class="form-control" readonly id="inota<?= $i;?>" name="inota<?= $i;?>" value="<?= $row->i_nota;?>">
                                </td>
                                <td>
                                    <input style="font-size: 12px;" class="form-control" readonly id="dnota<?= $i;?>" name="dnota<?= $i;?>" value="<?= $row->d_nota;?>">
                                </td>
                                <td>
                                    <input style="font-size: 12px;" readonly class="form-control" id="vnota<?= $i;?>" name="vnota<?= $i;?>" value="<?= number_format($row->v_nota);?>">
                                </td>
                                <td>
                                    <input style="font-size: 12px; text-align: right;" readonly class="form-control" width:85px;"  id="vjumlah<?= $i;?>" name="vjumlah<?= $i;?>" value="<?= number_format($row->v_jumlah);?>">
                                </td>
                                <td>
                                    <input style="font-size: 12px; text-align: right;" class="form-control" id="vsisa<?= $i;?>" name="vsisa<?= $i;?>" value="<?= number_format($row->v_sisa);?>" readonly>
                                    <input style="font-size: 12px; text-align: right;" type="hidden" class="form-control" id="vsesa<?= $i;?>" name="vsesa<?= $i;?>" value="<?= number_format($row->v_sisa);?>" readonly>
                                    <input style="font-size: 12px; text-align: right;" type="hidden" class="form-control" id="vlebih<?= $i;?>" name="vlebih<?= $i;?>" value="" readonly>
                                    <input style="font-size: 12px; text-align: right;" type="hidden" class="form-control" id="vasal<?= $i;?>" name="vasal<?= $i;?>" value="<?= number_format($row->v_jumlah + $row->v_sisa);?>" readonly>
                                </td>
                            </tr>
                            <?php 
                        }
                    }?>
                    <input type="hidden" name="jml" id="jml" value="<?= $i;?>">
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
    function getcustomer(icustomer) {
        var iarea = $('#iarea').val();
        $.ajax({
            type: "post",
            data: {
                'iarea'    : iarea,
                'icustomer': icustomer
            },
            url: '<?= base_url($folder.'/cform/getdetailcustomer'); ?>',
            dataType: "json",
            success: function (data) {
                $('#ecustomername').val(data[0].e_customer_name);
            },
            error: function () {
                alert('Error :)');
            }
        });
    }

    function sama(a){
        if(document.getElementById("fgirouse").value!='t'){
            document.getElementById("vsisa").value=a;
        }
    }

    function getbank() {
        var ebank = $('#ibank option:selected').text();
        $('#ebankname').val(ebank);
    }

    $(document).ready(function () {
        showCalendar('.date');
        $('#iarea').select2({
            placeholder: 'Cari Area Berdasarkan Kode / Nama'
        });

        $('#icustomer').select2({
            placeholder: 'Cari Customer Berdasarkan Kode / Nama',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getcustomer/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var iarea    = $('#iarea').val();
                    var query = {
                        q: params.term,
                        iarea:iarea
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

        $('#ibank').select2({
            placeholder: 'Pilih Bank',
        });
    });

    function hetang(){
        $('#vsisa').val($('#vjumlah').val());
    }

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
    });

    function hanyaAngka(evt) {      
        var charCode = (evt.which) ? evt.which : event.keyCode      
        if (charCode > 31 && (charCode < 48 || charCode > 57))        
            return false;    
        return true;
    }

    function dipales(){
        if((document.getElementById("igiro").value!='') && (document.getElementById("dgiroduedate").value!='') && (document.getElementById("ibank").value!='')) {
            return true; 
        }else{
            swal('Data Masih Ada yang Salah!!!');
            return false;
        }
    }
</script>