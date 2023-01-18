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
                        <label class="col-md-6">Nomor</label><label class="col-md-6">Tanggal Setor</label>
                        <div class="col-sm-6">
                            <input id="irtunai" name="irtunai" class="form-control" required="" readonly value="<?= $isi->i_rtunai;?>">
                        </div>
                        <div class="col-sm-6">
                            <input id= "drtunai" name="drtunai" class="form-control date" required="" readonly value="<?= date('d-m-Y', strtotime($isi->d_rtunai));?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <input readonly id="eremark" name="eremark" value="<?= $isi->e_remark; ?>" maxlength="100" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-8">
                            <?php if ($isi->d_cek!=null){ echo "sudah dicek "; }else{?>
                                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales();"> <i class="fa fa-save"></i>&nbsp;&nbsp;Dicek</button>
                            <?php } ?>
                            &nbsp;&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/view/<?= $dfrom."/".$dto."/".$iarea;?>/","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-7">Area</label><label class="col-md-5">Jumlah</label>
                        <div class="col-sm-7">
                            <input id= "eareaname" name="eareaname" class="form-control" required="" readonly value="<?= $isi->e_area_name;?>">
                            <input id="iarea" name="iarea" type="hidden" value="<?= $isi->i_area; ?>">
                        </div>
                        <div class="col-sm-5">
                            <input id= "vjumlah" name="vjumlah" class="form-control" readonly value="<?= number_format($isi->v_jumlah); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan Cek</label>
                        <div class="col-sm-12">
                            <input id="eremarkcek" name="eremarkcek" value="<?= $isi->e_cek; ?>" maxlength="250" class="form-control">
                        </div>
                    </div>
                </div>                
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%;" cellspacing="0">
                        <thead>
                            <tr>
                                <th style="text-align: center; width: 5%;">No</th>
                                <th style="text-align: center;">No Tunai</th>
                                <th style="text-align: center;">Tgl Tunai</th>
                                <th style="text-align: center; width: 30%;">Pelanggan</th>
                                <th style="text-align: center;">Jml</th>
                                <th style="text-align: center;">Keterangan</th>
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
                                        <input type="hidden" class="form-control" readonly id="iarea<?= $i; ?>" name="iarea<?= $i; ?>" value="<?= $row->i_area_tunai; ?>">
                                    </td>
                                    <td>
                                        <input readonly class="form-control" id="itunai<?= $i; ?>" name="itunai<?= $i; ?>" value="<?= $row->i_tunai; ?>">
                                    </td>
                                    <td>
                                        <input readonly class="form-control" id="dtunai<?= $i; ?>" name="dtunai<?= $i; ?>" value="<?= date('d-m-Y', strtotime($row->d_tunai)); ?>">
                                    </td>
                                    <td>
                                        <input readonly class="form-control" id="ecustomername<?= $i; ?>" name="ecustomername<?= $i; ?>" value="<?= "(".$row->i_customer.") ".$row->e_customer_name; ?>">
                                        <input type="hidden" class="form-control" id="icustomer<?= $i; ?>" name="icustomer<?= $i; ?>" value="<?= $row->i_customer; ?>">
                                    </td>
                                    <td>
                                        <input readonly style="text-align: right;" class="form-control" readonly id="vjumlah<?=$i;?>" name="vjumlah<?=$i;?>" value="<?= number_format($row->v_jumlah); ?>">
                                    </td>
                                    <td>
                                        <input readonly class="form-control" readonly id="eremark<?=$i;?>" name="eremark<?=$i;?>" value="<?= $row->e_remark; ?>">
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

    function dipales(){
        var a = parseFloat($("#jml").val());
        if(($("#drtunai").val()=='')||($("#iarea").val()=='')||($("#vjumlah").val()=='')||($("#vjumlah").val()=='0')) {
            swal('Data header masih ada yang salah !!!');
            return false;
        }else{
            return true;
        }
    }

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
    });
</script>