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
                        <?php if($isi->d_sjr){
			                if($isi->d_sjr!=''){
			                	  $tmp=explode("-",$isi->d_sjr);
			                	  $hr=$tmp[2];
			                	  $bl=$tmp[1];
			                	  $th=$tmp[0];
			                	  $isi->d_sjr=$hr."-".$bl."-".$th;
			                }
		                }?>
                            <div class="col-sm-6">
                                <input readonly id="isjr" name="isjr" class="form-control" value="<?php echo $isi->i_sjr; ?>">
                            </div>
                            <div class="col-sm-3">
                                <input readonly id="dsjr" name="dsjr" class="form-control date" value="<?php echo $isi->d_sjr; ?>">
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
                            <div class="col-sm-offset-5 col-sm-8">
		                        <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales(parseFloat(document.getElementById('jml').value));"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                                &nbsp;&nbsp; 
                                <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/view/<?=$dfrom;?>/<?=$dto;?>/<?=$iarea;?>","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                                &nbsp;&nbsp;
                                <?php if(($jmlitem != 0) || ($jmlitem != '')){?>
                                    <label class="custom-control custom-checkbox">
                                        <input type="checkbox" id="checkAll" name="checkAll" class="custom-control-input" checked>
                                        <span class="custom-control-indicator"></span>
                                        <span class="custom-control-description">&nbsp;&nbsp;Check All</span>
                                    </label>
                                <?}?>
                            </div>
                        </div>
                </div>

                <div class="col-md-6">
                    <div id="pesan"></div>
                    <div class="form-group row">
                        <label class="col-md-6">No TTB</label><label class="col-md-6">Tanggal TTB</label>
                        <div class="col-sm-6">
                            <input readonly id="ittb" class="form-control" name="ittb" value="<?php if($isi->i_ttb) echo $isi->i_ttb; ?>">
                        </div>
                        <div class="col-sm-3">
                            <input readonly id="dttb" class="form-control" name="dttb" value="<?php if($isi->d_ttb) echo $isi->d_ttb; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Nilai</label>
                        <div class="col-sm-6">
                            <input readonly id="vsj" name="vsj" class="form-control" value="<?php echo number_format($isi->v_sjr);?>">
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
                                        <th style="text-align: center;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php               
                                    if($detail){
                                         $i=0;
                                         foreach($detail as $row){
                                             $i++;
                                            $vtotal=$row->v_unit_price*$row->n_quantity_deliver;
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
                                                    <input class="form-control" type="hidden" readonly id="emotifname<?= $i;?>" name="emotifname<?= $i;?>" value="<?= $row->e_product_motifname;?>">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" id="eremark<?= $i;?>" name="eremark<?= $i;?>" value="<?= $row->e_remark;?>">
                                                </td>
                                                <td>
                                                    <input class="form-control" id="ndeliver<?= $i;?>" name="ndeliver<?= $i;?>" value="<?= $row->n_quantity_deliver;?>">
                                                    <input type="hidden" class="form-control" id="vproductmill<?= $i;?>" name="vproductmill<?= $i;?>" value="<?= $row->v_unit_price;?>">
                                                    <input class="form-control" type="hidden" id="vtotal<?= $i;?>" name="vtotal<?= $i;?>" value="<?= $vtotal;?>">
                                                </td>
                                                <td style="text-align: center;">
                                                    <input type='checkbox' name="chk<?=$i;?>" id="chk<?=$i;?>" value='on' checked onclick='ngetang()'>
                                                </td>
                                            </tr>
                                        <?
                                        }
                                            foreach($cquery as $tmp){
                                                $i++;
                                                $jmlitem++;
                                            ?>
                                            <tr>
                                                <td style="text-align: center;">
                                                    <input  type="text" class="form-control" readonly id="baris<?= $i;?>" name="baris<?= $i;?>" value="<?= $i;?>">
                                                    <input  class="form-control" type="hidden" id="motif<?= $i;?>" name="motif<?= $i;?>" value="<?= $tmp->i_product_motif;?>">
                                                </td>
                                                <td>
                                                    <input class="form-control" readonly id="iproduct<?= $i;?>" name="iproduct<?= $i;?>" value="<?= $tmp->i_product;?>">
                                                </td>
                                                <td>
                                                    <input class="form-control" readonly id="eproductname<?= $i;?>" name="eproductname<?= $i;?>" value="<?= $tmp->e_product_name;?>">
                                                    <input class="form-control" readonly type="hidden" id="emotifname<?= $i;?>" name="emotifname<?= $i;?>" value="<?= $tmp->e_product_motifname;?>">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" id="eremark<?= $i;?>" name="eremark<?= $i;?>" value="<?= $tmp->e_remark;?>">
                                                </td>
                                                <td>
                                                    <input class="form-control" id="ndeliver<?= $i;?>" name="ndeliver<?= $i;?>" value="<?= $tmp->n_quantity_deliver;?>">
                                                    <input type="hidden" class="form-control" id="vproductmill<?= $i;?>" name="vproductmill<?= $i;?>" value="<?= $tmp->v_unit_price;?>">
                                                    <input class="form-control" type="hidden" id="vtotal<?= $i;?>" name="vtotal<?= $i;?>" value="<?= $vtotal;?>">
                                                </td>
                                                <td style="text-align: center;">
                                                    <input type='checkbox' name="chk<?=$i;?>" id="chk<?=$i;?>" value='on' checked onclick='ngetang()'>
                                                </td>
                                            </tr>
                                        <?}
                                        }?>
                                    </div>
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

    $("#checkAll").click(function(){
        $('input:checkbox').not(this).prop('checked', this.checked);
        ngetang();
    });

    function ngetang(){
        var jml = parseFloat($('#jml').val());
        var tot = 0;
        for(brs=1;brs<=jml;brs++){    
            ord = $("#ndeliver"+brs).val();
            hrg  = formatulang($("#vproductmill"+brs).val());
            qty  = formatulang(ord);
            vhrg = parseFloat(hrg)*parseFloat(qty);
            $("#vtotal"+brs).val(formatcemua(vhrg));
            if($("#chk"+brs).is(':checked')){
                tot+=parseFloat(formatulang($("#vtotal"+brs).val()));
            }
            $("#vsj").val(formatcemua(tot));
        } 
    }
</script>