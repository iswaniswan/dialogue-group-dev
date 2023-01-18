<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">                       
                    <div class="form-group row">
                        <label class="col-md-6">No Adjustment</label><label class="col-md-6">Tanggal Adjustment</label>
                        <div class="col-sm-6">
                            <input readonly id="iadj" name="iadj" class="form-control" value="<?= $isi->i_adj;?>">
                        </div>
                        <div class="col-sm-6">
                            <input readonly id= "dadj" name="dadj" class="form-control" value="<?= $isi->dadj;?>">
                        </div>
                    </div> 
                    <div class="form-group row">&nbsp;</div>              
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales(parseFloat(document.getElementById('jml').value));"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>&nbsp;&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Batal</button>                               
                        </div>
                    </div>
                </div>                
                <div class="col-md-6"> 
                    <div class="form-group row">
                        <label class="col-md-6">Toko</label><label class="col-md-6">Stockopname</label>
                        <div class="col-sm-6">
                            <input class="form-control" readonly="" name="ecustomername" id="ecustomername" value="<?= $isi->e_customer_name;?>">
                            <input type="hidden" name="icustomer" id="icustomer" value="<?= $isi->i_customer ;?>">
                        </div>
                        <div class="col-sm-6">
                            <input class="form-control" id="istockopname" name="istockopname" value="<?= $isi->i_stockopname ;?>" readonly>
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <input readonly id= "eremark" name="eremark" class="form-control" value="<?= $isi->e_remark ;?>">
                        </div>
                    </div> 
                </div>
                <div class="col-md-12">
                    <table id="tabledata" class="display table" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th style="text-align: center; width: 4%;">No</th>
                                <th style="text-align: center; width: 15%;">Kode</th>
                                <th style="text-align: center; width: 30%;">Nama Barang</th>
                                <th style="text-align: center;">Motif</th>
                                <th style="text-align: center;">Qty</th>
                                <th style="text-align: center; width: 20%;">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($detail) {
                                $i = 0;
                                foreach ($detail as $row) { 
                                    $i ++;
                                    ?>
                                    <tr>
                                        <td style="text-align: center;"><?= $i;?>
                                             <input type="hidden" readonly id="baris<?= $i;?>" name="baris<?= $i;?>" value="<?= $i;?>">
                                             <input type="hidden" id="motif<?= $i;?>" name="motif<?= $i;?>" value="<?= $row->i_product_motif;?>">
                                             <input type="hidden" id="grade<?= $i;?>" name="grade<?= $i;?>" value="<?= $row->i_product_grade;?>">
                                        </td>
                                        <td>
                                            <input class="form-control" readonly id="iproduct<?= $i;?>" name="iproduct<?= $i;?>" value="<?= $row->i_product;?>">
                                        </td>
                                        <td>
                                            <input class="form-control" readonly id="eproductname<?= $i;?>" name="eproductname<?= $i;?>" value="<?= $row->e_product_name;?>">
                                        </td>
                                        <td>
                                            <input readonly class="form-control"  id="emotifname<?= $i;?>" name="emotifname<?= $i;?>" value="<?= $row->e_product_motifname;?>">
                                        </td>
                                        <td>
                                            <input class="form-control" style="text-align: right;" onkeypress="return hanyaAngka(event);" id="nquantity<?= $i;?>" name="nquantity<?= $i;?>" value="<?= $row->n_quantity;?>">
                                        </td>
                                        <td>
                                            <input class="form-control" id="eremark<?= $i;?>" name="eremark<?= $i;?>" value="<?= $row->e_remark;?>">
                                        </td>
                                    </tr>
                                <?php }
                            } ?>
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
    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#addrow").attr("disabled", true);
    });

    function dipales(a){
        if( (document.getElementById("dadj").value!='')||(document.getElementById("iarea").value!='')||(document.getElementById("eremark").value!='')||(document.getElementById("istockopname").value!='') ) {
            if(a==0){
                swal('Isi data item minimal 1 !!!');
                return false;
            }else{
                for(i=1;i<=a;i++){
                    if((document.getElementById("iproduct"+i).value=='') || (document.getElementById("eproductname"+i).value=='') || (document.getElementById("nquantity"+i).value=='')){
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
</script>