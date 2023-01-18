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
                <div id="pesan"></div>
                <?php if($isi->d_sj){
                    if($isi->d_sj!=''){
                        $tmp=explode("-",$isi->d_sj);
                        $hr=$tmp[2];
                        $bl=$tmp[1];
                        $th=$tmp[0];
                        $isi->d_sj=$hr."-".$bl."-".$th;
                        ?>
                        <input hidden id="bsj" name="bsj" value="<?php echo $bl; ?>">
                        <?php 
                    }
                }
                if($isi->d_spb){
                    if($isi->d_spb!=''){
                        $tmp=explode("-",$isi->d_spb);
                        $hr=$tmp[2];
                        $bl=$tmp[1];
                        $th=$tmp[0];
                        $isi->d_spb=$hr."-".$bl."-".$th;
                    }
                }
                ?>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Nomor SJ</label>
                        <label class="col-md-3">Tanggal SJ</label>
                        <label class="col-md-3">Nomor SPB</label>
                        <label class="col-md-3">Tanggal SPB</label>
                        <div class="col-sm-3">
                            <input id="isj" name="isj" class="form-control" required="" readonly value="<?= $isi->i_sj;?>">
                        </div>
                        <div class="col-sm-3">
                            <input id= "dsj" name="dsj" class="form-control" required="" readonly value="<?= $isi->d_sj;?>">
                        </div>
                        <div class="col-sm-3">
                            <input id= "ispb" name="ispb" class="form-control date" readonly value="<?= $isi->i_spb; ?>">
                        </div>
                        <div class="col-sm-3">
                            <input id="dspb" readonly name="dspb" class="form-control" value="<?= $isi->d_spb; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Area</label>
                        <label class="col-md-3">Nama Toko</label>
                        <label class="col-md-3">Nomor SJ Lama</label>
                        <label class="col-md-3">Nilai SJ</label>
                        <div class="col-sm-3">
                            <input id="eareaname" name="eareaname" class="form-control" required="" readonly value="<?= $isi->e_area_name; ?>">
                            <input id="iarea" name="iarea" type="hidden" value="<?= $isi->i_area; ?>">
                        </div>
                        <div class="col-sm-3">
                            <input class="form-control" readonly id="ecustomername" name="ecustomername" value="<?= $isi->e_customer_name; ?>">
                        </div>
                        <div class="col-sm-3">
                            <input readonly="" class="form-control" id="isjold" name="isjold" type="text" value="<?= $isi->i_sj_old; ?>">
                        </div>
                        <div class="col-sm-3">
                            <input class="form-control" readonly id="vsjnetto" name="vsjnetto" type="text" value="<?= number_format($isi->v_nota_netto); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-8">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/view/<?= $iarea."/".$dfrom."/".$dto;?>","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table id="tabledata" class="table color-table inverse-table table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th style="text-align: center; width: 4%;">No</th>
                                    <th style="text-align: center; width: 20%;">Kode Barang</th>
                                    <th style="text-align: center; width: 40%;">Nama Barang</th>
                                    <th style="text-align: center;">Motif</th>
                                    <th style="text-align: center;">Qty Order</th>
                                    <th style="text-align: center;">Qty Kirim</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php               
                                $i=0;
                                if($detail){
                                    foreach($detail as $row){ 
                                        $i++;
                                        $query=$this->db->query(" select f_spb_stockdaerah from tm_spb
                                            where i_spb='$ispb' and i_area='$iarea'",false);
                                        if ($query->num_rows() > 0){
                                            foreach($query->result() as $qq){
                                                $stockdaerah=$qq->f_spb_stockdaerah;
                                            }
                                        }
                                        if($stockdaerah=='f'){
                                            $query=$this->db->query("   select n_quantity_stock as qty from tm_ic
                                                where i_product='$row->i_product'
                                                and i_product_motif='$row->i_product_motif'
                                                and i_product_grade='$row->i_product_grade'
                                                and i_store='AA' and i_store_location='01' and i_store_locationbin='00'",false);
                                        }else{
                                            $query=$this->db->query("   select n_quantity_stock as qty from tm_ic
                                                where i_product='$row->i_product'
                                                and i_product_motif='$row->i_product_motif'
                                                and i_product_grade='$row->i_product_grade'
                                                and i_store='$istore' and i_store_location='00' and i_store_locationbin='00'",false);
                                        }

                                        if ($query->num_rows() > 0){
                                            foreach($query->result() as $tt){
                                                $stock=$tt->qty+$row->n_deliver;
                                            }
                                        }else{
                                            $stock=0;
                                        }
                                        if($stock>$row->n_qty)$stock=$row->n_qty;
                                        if($stock<0)$stock=0;
                                        $vtot=$row->harga*$stock;
                                        $stock=number_format($stock);
                                        ?>
                                        <tr>
                                            <td class="text-center">
                                                <?= $i;?>
                                                <input class="form-control" type="hidden" readonly type="text" id="baris<?= $i;?>" name="baris<?= $i;?>" value="<?= $i;?>">
                                                <input class="form-control" type="hidden" id="motif<?= $i;?>" name="motif<?= $i;?>" value="<?= $row->i_product_motif;?>">
                                            </td>
                                            <td>
                                                <input class="form-control" readonly type="text" id="iproduct<?= $i;?>" name="iproduct<?= $i;?>" value="<?= $row->i_product;?>">
                                            </td>
                                            <td>
                                                <input class="form-control" readonly type="text" id="eproductname<?= $i;?>" name="eproductname<?= $i;?>" value="<?= $row->e_product_name;?>">
                                            </td>
                                            <td>
                                                <input class="form-control" readonly type="text" id="emotifname<?= $i;?>" name="emotifname<?= $i;?>" value="<?= $row->e_product_motifname;?>">
                                                <input class="form-control" type="hidden" id="vproductmill<?= $i;?>" name="vproductmill<?= $i;?>" value="<?= $row->v_unit_price;?>">
                                            </td>
                                            <td>
                                                <input class="form-control text-right" readonly type="text" id="norder<?= $i;?>" name="norder<?= $i;?>" value="<?= $row->n_qty;?>">
                                            </td>
                                            <td>
                                                <input class="form-control text-right" readonly type="text" id="ndeliver<?= $i;?>" name="ndeliver<?= $i;?>" value="<?= $row->n_deliver;?>">
                                                <input class="form-control" type="hidden" id="ntmp<?= $i;?>" name="ntmp<?= $i;?>" value="<?= $row->n_deliver;?>">
                                                <input class="form-control" type="hidden" id="ndeliverhidden<?= $i;?>" name="ndeliverhidden<?= $i;?>" value="<?= $stock;?>">
                                                <input class="form-control" type="hidden" id="vtotal<?= $i;?>" name="vtotal<?= $i;?>" value="">
                                            </td>
                                        </tr>
                                    <?php }
                                    /*if ($detail1) {
                                        $i = 0;
                                        foreach ($detail1 as $tmp) {
                                            $i++;
                                            $query=$this->db->query(" select f_spb_stockdaerah from tm_spb
                                              where i_spb='$ispb' and i_area='$iarea'",false);
                                            if ($query->num_rows() > 0){
                                                foreach($query->result() as $qq){
                                                    $stockdaerah=$qq->f_spb_stockdaerah;
                                                }
                                            }
                                            if($stockdaerah=='f'){
                                                $query=$this->db->query("select n_quantity_stock as qty from tm_ic
                                                    where i_product='$tmp->i_product'
                                                    and i_product_motif='$tmp->i_product_motif'
                                                    and i_product_grade='$tmp->i_product_grade'
                                                    and i_store='AA' and i_store_location='01' and i_store_locationbin='00'",false);
                                            }else{
                                                $query=$this->db->query("select n_quantity_stock as qty from tm_ic
                                                    where i_product='$tmp->i_product'
                                                    and i_product_motif='$tmp->i_product_motif'
                                                    and i_product_grade='$tmp->i_product_grade'
                                                    and i_store='$istore' and i_store_location='00' and i_store_locationbin='00'",false);
                                            }
                                            if ($query->num_rows() > 0){
                                                foreach($query->result() as $tt){
                                                    $stock=$tt->qty+$tmp->n_deliver;
                                                }
                                            }else{
                                                $stock=0;
                                            }
                                            if($stock<0)$stock=0;
                                            $vtot=$tmp->harga*$stock;
                                            $stock=number_format($stock);?>
                                            <tr>
                                                <td class="text-center">
                                                    <?= $i;?>
                                                    <input class="form-control" type="hidden" readonly type="text" id="baris<?= $i;?>" name="baris<?= $i;?>" value="<?= $i;?>">
                                                    <input class="form-control" type="hidden" id="motif<?= $i;?>" name="motif<?= $i;?>" value="<?= $tmp->i_product_motif;?>">
                                                </td>
                                                <td>
                                                    <input class="form-control" readonly type="text" id="iproduct<?= $i;?>" name="iproduct<?= $i;?>" value="<?= $tmp->i_product;?>">
                                                </td>
                                                <td>
                                                    <input class="form-control" readonly type="text" id="eproductname<?= $i;?>" name="eproductname<?= $i;?>" value="<?= $tmp->e_product_name;?>">
                                                </td>
                                                <td>
                                                    <input class="form-control" readonly type="text" id="emotifname<?= $i;?>" name="emotifname<?= $i;?>" value="<?= $tmp->e_product_motifname;?>">
                                                    <input class="form-control" type="hidden" id="vproductmill<?= $i;?>" name="vproductmill<?= $i;?>" value="<?= $tmp->v_unit_price;?>">
                                                </td>
                                                <td>
                                                    <input class="form-control text-right" readonly type="text" id="norder<?= $i;?>" name="norder<?= $i;?>" value="<?= $tmp->n_order;?>">
                                                </td>
                                                <td>
                                                    <input class="form-control text-right" readonly type="text" id="ndeliver<?= $i;?>" name="ndeliver<?= $i;?>" value="<?= $tmp->n_deliver;?>">
                                                    <input class="form-control" type="hidden" id="ntmp<?= $i;?>" name="ntmp<?= $i;?>" value="<?= $tmp->n_deliver;?>">
                                                    <input class="form-control" type="hidden" id="ndeliverhidden<?= $i;?>" name="ndeliverhidden<?= $i;?>" value="<?= $stock;?>">
                                                    <input class="form-control" type="hidden" id="vtotal<?= $i;?>" name="vtotal<?= $i;?>" value="">
                                                </td>
                                            </tr>
                                        <?php }
                                    }*/
                                } ?>
                                <input type="hidden" name="jml" id="jml" value="<?= $i;?>">
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
</div>