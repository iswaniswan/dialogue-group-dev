<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <?php if($isi->d_sjpb){
                    if($isi->d_sjpb!=''){
                        $tmp=explode("-",$isi->d_sjpb);
                        $hr=$tmp[2];
                        $bl=$tmp[1];
                        $th=$tmp[0];
                        $isi->d_sjpb=$hr."-".$bl."-".$th;
                    }
                }
                if($isi->d_sjpb_receive){
                    if($isi->d_sjpb_receive!=''){
                        $tmp=explode("-",$isi->d_sjp_receive);
                        $hr=$tmp[2];
                        $bl=$tmp[1];
                        $th=$tmp[0];
                        $isi->d_sjpb_receive=$hr."-".$bl."-".$th;
                    }
                }
                ?>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-4">No SJPB</label>
                        <label class="col-md-4">Tanggal SJPB</label>
                        <label class="col-md-4">Tanggal Terima</label>
                        <div class="col-sm-4">
                            <input id="isjp" name="isjp" class="form-control" required="" readonly value="<?= $isi->i_sjpb;?>">
                        </div>
                        <div class="col-sm-4">
                            <input id= "dsjp" name="dsjp" class="form-control" required="" readonly value="<?= $isi->d_sjpb;?>">
                        </div>
                        <div class="col-sm-4">
                            <input id= "dreceive" name="dreceive" class="form-control" readonly value="<?= $isi->d_sjpb_receive; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Area</label><label class="col-md-4">Nilai Kirim</label><label class="col-md-4">Nilai Terima</label>
                        <div class="col-sm-4">
                            <input id= "eareaname" name="eareaname" class="form-control" required="" readonly value="<?= $isi->e_area_name;?>">
                        </div>
                        <div class="col-sm-4">
                            <input id="vsj" name="vsj" readonly class="form-control" value="<?= number_format($isi->v_sjpb); ?>">
                        </div>
                        <div class="col-sm-4">
                            <input id= "vsjrec" name="vsjrec" class="form-control" readonly value="<?= number_format($isi->v_sjpb_receive); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-8">
                            <!-- <button type="submit" id="submit" onclick="return dipales();" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                                &nbsp;&nbsp; -->
                                <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i
                                    class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali
                                </button>
                            </div>
                        </div>
                    </div> 
                    <div class="table-responsive">
                        <table class="table table-bordered" cellspacing="0">
                            <thead>
                                <tr>
                                    <th style="text-align: center; width: 4%;">No</th>
                                    <th style="text-align: center; width: 10%;">Kode Barang</th>
                                    <th style="text-align: center; width: 40%;">Nama Barang</th>
                                    <th style="text-align: center;">Qty Kirim</th>
                                    <th style="text-align: center;">Qty Terima</th>
                                    <th style="text-align: center;">Action</th>
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
                                            <input type="hidden" class="form-control" id="baris<?=$i;?>" name="baris<?=$i;?>" value="<?= $i;?>">
                                            <input type="hidden" id="motif<?=$i;?>" name="motif<?=$i;?>" value="<?= $row->i_product_motif; ?>">
                                        </td>
                                        <td>
                                            <input class="form-control" readonly type="text" id="iproduct<?=$i;?>" name="iproduct<?=$i;?>" value="<?= $row->i_product; ?>">
                                        </td>
                                        <td>
                                            <input readonly type="text" class="form-control" id="eproductname<?=$i;?>" name="eproductname<?=$i;?>" value="<?= $row->e_product_name; ?>">
                                        </td>                                 
                                        <td>
                                            <input style="text-align: right;" class="form-control" readonly id="ndeliver$i" name="ndeliver$i" value="<?= $row->n_deliver;?>">
                                        </td>
                                        <td>
                                            <input style="text-align: right;" class="form-control" readonly id="nreceive$i" name="nreceive$i" value="<?= $row->n_receive;?>"">
                                        </td>
                                        <td style="text-align: center;">
                                            <input style="text-align: center;" type="checkbox" name="cek">
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
    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
    });

    /*$(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date', 3, 0);
    });*/
</script>