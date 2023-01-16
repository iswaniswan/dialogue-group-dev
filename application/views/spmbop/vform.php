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
                        <label class="col-md-6">No SPMB</label><label class="col-md-6">Tanggal SPMB</label>
                        <div class="col-sm-6">
                            <input id="ispmb" name="ispmb" class="form-control" required="" readonly value="<?= $isi->i_spmb;?>">
                        </div>
                        <div class="col-sm-6">
                            <input id= "dspmb" name="dspmb" class="form-control" readonly value="<?= $isi->dspmb;?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-8">
                            <button type="submit" id="submit" onclick="return dipales();" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>
                                &nbsp;&nbsp;Simpan
                            </button>&nbsp;&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i
                                class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali
                            </button>
                        </div>
                    </div>
                </div> 
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-12">
                            <input id= "eareaname" name="eareaname" class="form-control" required="" readonly value="<?= $isi->e_area_name;?>">
                            <input id="iarea" name="iarea" type="hidden" value="<?= $isi->i_area; ?>">
                        </div>
                    </div> 
                </div>
                <div class="table-responsive">
                    <table id="tabledata" class="table table-bordered" cellspacing="0">
                        <thead>
                            <tr>
                                <th style="text-align: center; width: 4%;">No</th>
                                <th style="text-align: center; width: 9%;">Kode</th>
                                <th style="text-align: center; width: 30%;">Nama Barang</th>
                                <th style="text-align: center; width: 5%;">Motif</th>
                                <th style="text-align: center;">Jml Pesan</th>
                                <th style="text-align: center;">Keterangan</th>
                                <th style="text-align: center;">Jml Stk</th>
                                <!-- <th style="text-align: center;">Pemenuhan</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($detail) {
                                $i = 0;
                                foreach ($detail as $row) { 
                                    $i++;
                                    $nstock  = 0;
                                    $query   = $this->mmaster->stock($row->i_product, $row->i_product_grade, $row->i_product_motif);
                                    if ($query->num_rows() > 0){
                                        foreach($query->result() as $tt){
                                            $nstock = number_format($tt->n_quantity_stock);
                                        }
                                    }
                                    $pangaos = number_format($row->v_unit_price);
                                    $total   = $row->v_unit_price*$row->n_acc;
                                    $total   = number_format($total);
                                    $acc     = number_format($row->n_acc);
                                    $ord     = number_format($row->n_order);
                                    if($nstock >= $row->n_acc){
                                        $nstock = number_format($row->n_acc);
                                        $stock  = number_format($row->n_acc);
                                    }else{ 
                                        $stock  = $nstock;
                                    }

                                    if ($nstock<0) {
                                            $nstock = 0;
                                        }else{
                                            $nstock = $nstock;
                                        }
                                    ?>
                                    <tr>
                                        <td style="text-align: center;"><?= $i;?>
                                        <input type="hidden" class="form-control" id="baris<?=$i;?>" name="baris<?=$i;?>" value="<?= $i;?>">
                                        <input type="hidden" id="motif<?=$i;?>" name="motif<?=$i;?>" value="<?= $row->i_product_motif; ?>">
                                    </td>
                                    <td>
                                        <input class="form-control" readonly id="iproduct<?=$i;?>" name="iproduct<?=$i;?>" value="<?= $row->i_product; ?>">
                                    </td>
                                    <td>
                                        <input readonly class="form-control" id="eproductname<?=$i;?>" name="eproductname<?=$i;?>" value="<?= $row->e_product_name; ?>">
                                    </td>
                                    <td>
                                        <input class="form-control" readonly id="emotifname<?=$i;?>" name="emotifname<?=$i;?>" value="<?= $row->e_product_motifname; ?>">
                                        <input type="hidden" id="vproductmill<?=$i;?>" name="vproductmill<?=$i;?>" value="<?= $pangaos;?>">
                                    </td>
                                    <td>
                                        <input style="text-align: right;" class="form-control" readonly id="nacc<?=$i;?>" name="nacc<?=$i;?>" value="<?= $acc;?>"><input type="hidden" id="norder<?=$i;?>" name="norder<?=$i;?>" value="<?= $ord;?>">
                                    </td>
                                    <td>
                                        <input readonly class="form-control" id="eremark<?=$i;?>" name="eremark<?=$i;?>" value="<?= $row->e_remark;?>">
                                        <input type="hidden" id="vtotal<?=$i;?>" name="vtotal<?=$i;?>" value="<?= $total;?>">
                                    </td>
                                    <td>
                                        <input style="text-align: right;" readonly class="form-control" id="nqtystock<?=$i;?>" name="nqtystock<?=$i;?>" value="<?= $stock;?>">
                                    </td>
                                    <!-- <td>
                                        <input style="text-align: right;" class="form-control" onkeypress="return hanyaAngka(event);" id="nstock<?=$i;?>" name="nstock<?=$i;?>" value="<?= $nstock;?>">
                                    </td> -->
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
        var a = $('#jml').val();
        if((document.getElementById("dspmb").value!='') &&
            (document.getElementById("iarea").value!='')) {
            if(a==0){
                swal('Isi data item minimal 1 !!!');
                return false;
            }else{
                for(i=1;i<=a;i++){
                    if((document.getElementById("iproduct"+i).value=='') || (document.getElementById("eproductname"+i).value=='') || (document.getElementById("nacc"+i).value=='')){
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