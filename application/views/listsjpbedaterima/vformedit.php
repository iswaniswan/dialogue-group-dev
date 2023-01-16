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
                            <input id="istore" name="istore" type="hidden" value="<?php if($isi->i_store) echo $isi->i_store; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">SJ Lama</label>
                        <div class="col-sm-6">
                            <input readonly id="isjold" name="isjold" class="form-control" value="<?php echo $isi->i_sjp_old;?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-8">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/view/<?=$dfrom;?>/<?=$dto;?>","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                            &nbsp;&nbsp;
                            <button type="button" nama="cmdres" id="cmdres" class="btn btn-secondary btn-rounded btn-sm"><i class="fa fa-download"></i>&nbsp;&nbsp;Export</button>&nbsp;&nbsp;
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div id="pesan"></div>
                    <div class="form-group row">
                        <?php if($isi->d_sjp_receive){
			                if($isi->d_sjp_receive!=''){
			                	  $tmp=explode("-",$isi->d_sjp_receive);
			                	  $hr=$tmp[2];
			                	  $bl=$tmp[1];
			                	  $th=$tmp[0];
			                	  $isi->d_sjp_receive=$hr."-".$bl."-".$th;
			                }
		                }?>
                        <label class="col-md-12">Tanggal Terima</label>
                        <div class="col-sm-3">
                            <input readonly id="dreceive" class="form-control date" name="dreceive" value="<?php if($isi->d_sjp_receive) echo $isi->d_sjp_receive; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Nilai Kirim</label>
                        <div class="col-sm-6">
                            <input readonly id="vsj" name="vsj" class="form-control" value="<?php echo number_format($isi->v_sjp);?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Nilai Terima</label>
                        <div class="col-sm-6">
                            <input readonly id="vsjrec" name="vsjrec" class="form-control" value="<?php echo number_format($isi->v_sjp_receive);?>">
                        </div>
                    </div>
                </div>
                    <div class="table-responsive">
                    <table class="table table-bordered" id="sitabel" name="sitabel" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th style="text-align: center; width: 7%;">No</th>
                                        <th style="text-align: center; width: 20%;">No SJ</th>
                                        <th style="text-align: center; width: 10%;">Area</th>
                                        <th style="text-align: center; width: 15%;">Kode</th>
                                        <th style="text-align: center; width: 40%;">Nama Barang</th>
                                        <th style="text-align: center; width: 20%;">Ket</th>
                                        <th style="text-align: center; width: 10%;">Jumlah Kirim</th>
                                        <th style="text-align: center; width: 10%;">Jumlah Terima</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php               
                                    if($detail){
                                         $i=0;
                                         foreach($detail as $row){
                                             $i++;
                                            $vtotal=$row->v_unit_price*$row->n_quantity_receive;
                                         echo"
                                         <tr>
                                                <td style=\"text-align: center; width:23px;\">$i</td>
                                                <td style=\"width:23px;\">$row->i_sjp</td>
                                                <td style=\"text-align: center; width:23px;\">$row->i_area</td>
                                                <td style=\"width:66px;\">$row->i_product</td>
                                                <td style=\"width:314px;\">$row->e_product_name</td>
                                                <td style=\"width:103px;\">$row->e_remark</td>
                                                <td style=\"text-align: center; width:74px;\">$row->n_quantity_deliver</td>
                                                <td style=\"text-align: center; width:74px;\">$row->n_quantity_receive</td>
                                         </tr>";
                                        }
                                    }?>
                                    <input type="hidden" name="jml" id="jml" value="<?= $jmlitem;?>">
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
        $('.select2').select2();
        showCalendar('.date');
    });

    $( "#cmdres" ).click(function() {  
    var Contents = $('#sitabel').html();    
    window.open('data:application/vnd.ms-excel, ' +  '<table>'+encodeURIComponent($('#sitabel').html()) +  '</table>' );
  });
</script>