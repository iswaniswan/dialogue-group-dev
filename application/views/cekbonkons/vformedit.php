<?php 
include ("php/fungsi.php");
?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>

            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div class="col-md-6">
                    <div id="pesan"></div>
                    <div class="form-group row">
                        <label class="col-md-12">Tanggal</label>
                        <div class="col-sm-3">
                            <input readonly type="text" id="dnotapb" name="dnotapb" class="form-control date" value="<?php echo $isi->d_notapb ?>">
                        </div>
                        <div class="col-sm-5">
                            <input type="text" name="inotapb" id="inotapb" class="form-control" required="" value="<?php echo $inotapb; ?>" readonly>
                        </div>
                        <input type="hidden" name="xinotapb" id="xinotapb" class="form-control" required="" value="">
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-6">
                            <input readonly id="eareaname" name="eareaname" class="form-control" value="<?php echo $isi->e_area_name; ?>" readonly>
		                    <input id="iarea" name="iarea" type="hidden" class="form-control" value="<?php echo $isi->i_area; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">SPG</label>
                        <div class="col-sm-6">
                            <input readonly id="espgname" name="espgname" class="form-control" value="<?php echo $isi->e_spg_name; ?>" readonly >
		                    <input id="ispg" name="ispg" type="hidden" value="<?php echo $isi->i_spg; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Pelanggan</label>
                        <div class="col-sm-6">
                            <input readonly id="ecustomername" name="ecustomername" class="form-control" value="<?php echo $isi->e_customer_name; ?>">
                            <input readonly type="hidden" id="icustomer" name="icustomer" class="form-control" value="<?php echo $isi->i_customer; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-8">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales(parseFloat(document.getElementById('jml').value));"> <i class="fa fa-save"></i>&nbsp;&nbsp;Cek</button>
                            &nbsp;&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>                    
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-12">Potongan</label>
                        <div class="col-sm-3">
                            <input  id="nnotapbdiscount" name="nnotapbdiscount" class="form-control" value="<?php echo number_format($isi->n_notapb_discount); ?>" onkeyup="hitungnilai();">
                            <?php 
		                        if($isi->v_notapb_discount){
		                        }else{
		                          $isi->v_notapb_discount=0;
		                        }
		                    ?>
                        </div>
                        <div class="col-sm-4">
                            <input id="vnotapbdiscount" name="vnotapbdiscount" type="text" class="form-control" value="<?php echo number_format($isi->v_notapb_discount); ?>" onkeyup="diskonrupiah();">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Total</label>
                        <?php 
		                    if($isi->v_notapb_gross){
		                    }else{
		                      $isi->v_notapb_gross=0;
		                    }
		                ?>
                        <div class="col-sm-6">
                            <input type="hidden" id="vnotapbgross" name="vnotapbgross" class="form-control" value="<?php echo number_format($isi->v_notapb_gross); ?>">
                            <input type="text" id="vnotapbnetto" name="vnotapbnetto" class="form-control" value="<?php echo number_format($isi->v_notapb_discount); ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan Cek</label>
                        <div class="col-sm-6">
                            <input type="text" id="ecek" name="ecek" class="form-control" value="">
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                        <table class="table table-bordered" width="100%;" cellspacing="0">
                            <thead>
                                <tr>
                                    <th style="text-align: center; width: 7%;">No</th>
                                    <th style="text-align: center; width: 15%;">Kode Barang</th>
                                    <th style="text-align: center; width: 30%;">Nama Barang</th>
                                    <th style="text-align: center;">Jumlah</th>
                                    <th style="text-align: center;">Harga</th>
                                    <th style="text-align: center;">Total</th>
                                    <th style="text-align: center;">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($detail) {
                                    $i = 0;
                                    foreach($detail as $row){
				  	                        $i++;
                                              $totall=$row->n_quantity*$row->v_unit_price;
                                    ?>
                                        <tr>
                                            <td>
                                                <input style="text-align:center;" readonly type="text" class="form-control" id="baris<?=$i;?>" name="baris<?=$i;?>" value="<?= $i;?>">
                                                <input type="hidden" id="motif<?=$i;?>" name="motif<?=$i;?>" value="<?= $row->i_product_motif; ?>">
                                                <input type="hidden" id="ipricegroupco<?=$i;?>" name="ipricegroupco<?=$i;?>" value="<?= $row->i_price_groupco; ?>">
                                            </td>
                                            <td>
                                                <input class="form-control" readonly type="text" id="iproduct<?=$i;?>" name="iproduct<?=$i;?>" value="<?= $row->i_product; ?>">
                                            </td>
                                            <td>
                                                <input readonly type="text" class="form-control" id="eproductname<?=$i;?>" name="eproductname<?=$i;?>" value="<?= $row->e_product_name; ?>">
                                            </td>
                                            <td>
                                                <input class="form-control"  type="text" id="nquantity<?=$i;?>" name="nquantity<?=$i;?>" value="<?= $row->n_quantity; ?>">
                                            </td>
                                            <td>
                                                <input class="form-control" style="text-align:right;" readonly type="text" id="vunitprice<?=$i;?>" name="vunitprice<?=$i;?>" value="<?= $row->v_unit_price; ?>">
                                            </td>
                                            <td>
                                                <input class="form-control" style="text-align:right;" type="text" id="total<?=$i;?>" name="total<?=$i;?>" value="<?= $totall; ?>">
                                            </td>
                                            <td>
                                                <input class="form-control" style="text-align:right;" type="text" id="eremark<?=$i;?>" name="eremark<?=$i;?>" value="<?= $row->e_remark; ?>">
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
<script>
$("form").submit(function(event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
});
 $(document).ready(function () {
    $(".select2").select2();
    showCalendar('.date');
 });

 function dipales(){
   	document.getElementById("login").disabled=true;
  }
  function dipalesegein(){
   	document.getElementById("login").disabled=true;
   	document.getElementById("notapprove").disabled=true;
  }
  function clearitem(){
    document.getElementById("detailisi").innerHTML='';
    document.getElementById("pesan").innerHTML='';
    document.getElementById("jml").value='0';
    document.getElementById("login").disabled=false;
  }
</script>
