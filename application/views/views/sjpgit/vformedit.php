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
                <div class="col-md-6">
                    <div id="pesan"></div>
                    <div class="form-group row">
                        <label class="col-md-6">No SJ</label><label class="col-md-6">Tanggal SJ</label>
                        <?php if($isi->d_sjp){
			                if($isi->d_sjp!=''){
			                	  $tmp=explode("-",$isi->d_sjp);
			                	  $hr=$tmp[2];
			                	  $bl=$tmp[1];
			                	  $th=$tmp[0];
			                	  $isi->d_sjp=$hr."-".$bl."-".$th;
			                }
		                }?>
                            <div class="col-sm-6">
                                <input readonly id="isj" name="isj" class="form-control" value="<?php echo $isi->i_sjp; ?>">
                            </div>
                            <div class="col-sm-3">
                                <input readonly id="dsj" name="dsj" class="form-control date" value="<?php echo $isi->d_sjp; ?>">
                            </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-6">
                            <input readonly id="eareaname" class="form-control" name="eareaname" value="<?php if($isi->e_area_name) echo $isi->e_area_name; ?>">
                            <input id="iarea" name="iarea" class="form-control" type="hidden" value="<?php echo $isi->i_area; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">SJ Lama</label>
                        <div class="col-sm-6">
                            <input id="isjpold" name="isjpold" class="form-control" type="text" value="<?php echo $isi->i_sjp_old; ?>">
                        </div>
                    </div>
                        <div class="form-group row">
                            <div class="col-sm-offset-3 col-sm-5">
                                <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                                &nbsp;&nbsp;
                            </div>
                        </div>
                </div>

                <div class="col-md-6">
                    <div id="pesan"></div>
                    <div class="form-group row">
                        <label class="col-md-12">Tanggal Kirim</label>
                        <div class="col-sm-6">
                        <?php if($isi->d_sjp_receive){
			                if($isi->d_sjp_receive!=''){
			                	  $tmp=explode("-",$isi->d_sjp_receive);
			                	  $hr=$tmp[2];
			                	  $bl=$tmp[1];
			                	  $th=$tmp[0];
			                	  $isi->d_sjp_receive=$hr."-".$bl."-".$th;
			                }
		                }?>
                            <input readonly id="dreceive" class="form-control" name="dreceive" value="<?php if($isi->d_sjp_receive) echo $isi->d_sjp_receive; ?>">   
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Nilai Kirim</label>
                        <div class="col-sm-6">
                            <input readonly id="vsj" name="vsj" class="form-control" value="<?php if($isi->v_sjp) echo number_format($isi->v_sjp); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Nilai Terima</label>
                        <div class="col-sm-6">
                            <input readonly id="vsjrec" name="vsjrec" class="form-control" value="<?php if($isi->v_sjp_receive) echo number_format($isi->v_sjp_receive); ?>">
                        </div>
                    </div>
                </div>
                    <div class="table-responsive">
                    <table class="table table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th style="text-align: center; width: 7%;">No</th>
                                        <th style="text-align: center; width: 10%;">Kode Barang</th>
                                        <th style="text-align: center; width: 35%;">Nama Barang</th>
                                        <th style="text-align: center; width: 10%;">Ket</th>
                                        <th style="text-align: center;">Jumlah Kirim</th>
                                        <th style="text-align: center;">Jumlah Terima</th>
                                        <th style="text-align: center;">Selisih</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php               
                                    if($detail){
                                         $i=1;
                                         foreach($detail as $row){
                                             $i++;
                                            $vtotal=$row->v_unit_price*$row->n_quantity_receive;
                                            $selisih=$row->n_quantity_receive-$row->n_quantity_deliver;
                                ?>
                                            <tr>
                                                <td style="text-align: center;">
                                                    <input  type="text" class="form-control" readonly id="baris<?= $i;?>" name="baris<?= $i;?>" value="<?= $i;?>">
                                                    <input  class="form-control" type="hidden" id="motif<?= $i;?>" name="motif<?= $i;?>" value="<?= $row->i_product_motif;?>">
                                                </td>
                                                <td>
                                                    <input class="form-control" readonly id="iproduct<?= $i;?>" name="iproduct<?= $i;?>" value="<?= $row->i_product;?>">
                                                </td>
                                                <td>
                                                    <input class="form-control" readonly id="eproductname<?= $i;?>" name="eproductname<?= $i;?>" value="<?= $row->e_product_name;?>">
                                                    <input type="hidden" class="form-control" readonly id="emotifname<?= $i;?>" name="emotifname<?= $i;?>" value="<?= $row->e_product_motifname;?>">
                                                </td>
                                                <td>
                                                    <input class="form-control" id="eremark<?= $i;?>" name="eremark<?= $i;?>" value="<?= $row->e_remark;?>">
                                                    <input type="hidden" class="form-control" id="vproductmill<?= $i;?>" name="vproductmill<?= $i;?>" value="<?= $row->v_unit_price;?>">
                                                </td>
                                                <td>
                                                    <input class="form-control" id="ndeliver<?= $i;?>" name="ndeliver<?= $i;?>" value="<?= $row->n_quantity_deliver;?>">
                                                </td>
                                                <td>
                                                    <input class="form-control" id="nreceive<?= $i;?>" name="nreceive<?= $i;?>" value="<?= $row->n_quantity_receive;?>" onkeyup="hitungnilai(<?=$i;?>)">
                                                    <input type="hidden" class="form-control" id="vtotal<?= $i;?>" name="vtotal<?= $i;?>" value="<?= $vtotal;?>">
                                                    <input type="hidden" class="form-control" id="nasal<?= $i;?>" name="nasal<?= $i;?>" value="<?= $row->n_quantity_receive;?>">
                                                </td>
                                                <td>
                                                    <input class="form-control" id="selisih<?= $i;?>" name="selisih<?= $i;?>" value="<?= $selisih;?>">
                                                </td>
                                            </tr>
                                        <?}
                                        }?>
                                    </div>
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
  function hitungnilai(brs){
    var tot=0;
	  ord=document.getElementById("nreceive"+brs).value;
	  if (isNaN(parseFloat(ord))){
		  alert("Input harus numerik");
	  }else{
		  hrg=formatulang(document.getElementById("vproductmill"+brs).value);
		  qty=formatulang(ord);
		  vhrg=parseFloat(hrg)*parseFloat(qty);
		  document.getElementById("vtotal"+brs).value=formatcemua(vhrg);

      jml=parseFloat(document.getElementById("jml").value);
      for(i=1;i<=jml;i++){
        if(document.getElementById("chk"+i).value=='on'){
          tot+=parseFloat(formatulang(document.getElementById("vtotal"+i).value));
        }
      }
      document.getElementById("vsjrec").value=formatcemua(tot);
	  }
  }
</script>
