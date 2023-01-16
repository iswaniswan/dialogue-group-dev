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
                        <label class="col-md-3">Nomor DT</label><label class="col-md-3">Tanggal DT</label><label class="col-md-6">Area</label>
                        <div class="col-sm-3">
                            <input hidden id="bdt" name="bdt" value="<?= date('m', strtotime($isi->d_dt)); ?>">
                            <input id="idt" name="idt" class="form-control" required="" readonly value="<?= $isi->i_dt;?>">
                        </div>
                        <div class="col-sm-3">
                            <input id= "ddt" name="ddt" class="form-control date" onchange="cektanggal()" required="" readonly value="<?= date('d-m-Y', strtotime($isi->d_dt));?>">
                            <input type="hidden" id="tgldt" name="tgldt" value="<?= date('d-m-Y', strtotime($isi->d_dt));?>">
                            <input type="hidden" id="xddt" name="xddt" value="<?= $isi->d_dt;?>">
                        </div>
                        <div class="col-sm-6">
                            <input id= "eareaname" name="eareaname" class="form-control" required="" readonly value="<?= $isi->e_area_name;?>">
                            <input id="iarea" name="iarea" type="hidden" value="<?= $isi->i_area; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Jumlah</label><label class="col-md-9">Keterangan</label>
                        <div class="col-sm-3">
                            <input class="form-control" readonly id="vjumlah" name="vjumlah" value="<?php echo number_format($isi->v_jumlah);?>">
                        </div>
                        <div class="col-sm-9">
                            <input id="ecek1" name="ecek1" value="<?= $isi->e_cek; ?>" maxlength="100" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-8">
                            <?php if ($isi->d_cek!=null){ echo "sudah dicek "; }else{?>
                                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales();"> <i class="fa fa-save"></i>&nbsp;&nbsp;Dicek</button>
                            <?php } ?>
                            &nbsp;&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/view/<?= $dfrom."/".$dto."/".$iarea;?>/","#main")'> <i
                                class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%;" cellspacing="0">
                            <thead>
                                <tr>
                                    <th style="text-align: center; width: 5%;">No</th>
                                    <th style="text-align: center;">Nota</th>
                                    <th style="text-align: center;">Tgl Nota</th>
                                    <th style="text-align: center;">Tgl JT</th>
                                    <th style="text-align: center; width: 30%;">Pelanggan</th>
                                    <th style="text-align: center;">Jml Nota</th>
                                    <th style="text-align: center;">Jml DT</th>
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
                                            <input class="form-control" readonly id="inota<?= $i; ?>" name="inota<?= $i; ?>" value="<?= $row->i_nota; ?>">
                                        </td>
                                        <td>
                                            <input readonly class="form-control" id="dnota<?= $i; ?>" name="dnota<?= $i; ?>" value="<?= date('d-m-Y', strtotime($row->d_nota)); ?>">
                                        </td>
                                        <td>
                                            <input readonly class="form-control" id="djatuhtempo<?= $i; ?>" name="djatuhtempo<?= $i; ?>" value="<?= date('d-m-Y', strtotime($row->d_jatuh_tempo)); ?>">
                                        </td>
                                        <td>
                                            <input readonly class="form-control" id="ecustomername<?= $i; ?>" name="ecustomername<?= $i; ?>" value="<?= ($row->i_customer)." ".$row->e_customer_name." ".($row->e_customer_city); ?>">
                                            <input type="hidden" class="form-control" id="icustomer<?= $i; ?>" name="icustomer<?= $i; ?>" value="<?= $row->i_customer; ?>">
                                        </td>
                                        <td>
                                            <input style="text-align: right;" class="form-control" readonly id="vnota<?=$i;?>" name="vnota<?=$i;?>" value="<?= number_format($row->v_jumlah); ?>">
                                        </td>
                                        <td>
                                            <input readonly style="text-align: right;" class="form-control" id="vsisadt<?=$i;?>" name="vsisadt<?=$i;?>" value="<?= number_format($row->jml); ?>" onkeyup="reformat(this);">
                                        </td>
                                        <td>
                                            <input style="text-align: right;" class="form-control" readonly id="vsisa<?=$i;?>" name="vsisa<?=$i;?>" value="<?= $row->sisanota; ?>">
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
    $(document).ready(function () {
        showCalendar('.date');
    });

    function cektanggal() {
        var dspb = $('#ddt').val();
        var bspb = $('#bdt').val();
        dtmp = dspb.split('-');
        per  = dtmp[2]+dtmp[1]+dtmp[0];
        bln  = dtmp[1];
        if( (bspb!='') && (dspb!='') ){
            if(bspb != bln){
                swal("Tanggal DT tidak boleh dalam bulan yang berbeda !!!");
                $("#ddt").val('');
            }
        }
    }

    function dipales(){
        var a = parseFloat($("#jml").val());
        if(($("#idt").val()!='') && ($("#ddt").val()!='') && ($("#vjumlah").val()!='') && ($("#iarea").val()!='')) {
            if(a==0){
                swal('Isi data item minimal 1 !!!');
                return false;
            }else{
                for(i=1;i<=a;i++){
                    if(($("#inota"+i).val()=='') || ($("#icustomer"+i).val()=='')){
                        swal('Data item masih ada yang salah !!!');
                        return false;
                    }else{
                        return true;
                    } 
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