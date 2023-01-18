<style type="text/css">
    .pudding{
        padding-left: 3px;
        padding-right: 3px;
        font-size: 14px;
        background-color: #ddd;
    }
</style>
<!-- <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?> -->
<form>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?=$title_list;?></a>
            </div>
            <div class="panel-body table-responsive">
            <div id="pesan"></div>
            <div class="col-md-12">
                <?php if($head){
                ?>
                <div class="form-group row">
                    <label class="col-md-3">Bagian Pembuat</label>
                    <label class="col-md-3">Nomor Dokumen</label>
                    <label class="col-md-2">Tanggal Dokumen</label>
                    <label class="col-md-4">Partner</label>
                    <div class="col-sm-3">
                        <input type="text" readonly="" class="form-control input-sm" value="<?= $head->e_bagian_name;?>">
                    </div>
                    <div class="col-sm-3"> 
                        <div class="input-group">
                            <input type="text" readonly="" class="form-control input-sm" value="<?= $head->i_document;?>">
                        </div>
                    </div> 
                    <div class="col-sm-2">
                            <input type="text" name="ddocument" id="ddocument" class="form-control" value="<?= $head->tgl;?>" readonly="">
                    </div>
                    <div class="col-sm-4">
                        <input type="text" name="e_customer_name" class="form-control" value="<?= $head->e_partner_name." (".$head->e_partner_type.")";?>" readonly>
                    </div>       
                </div>
                <div class="form-group row">
                    <label class="col-md-2">Nomor Pajak</label>
                    <label class="col-md-2">Tanggal Pajak</label>
                    <label class="col-md-2">Tgl Terima Faktur</label>  
                    <label class="col-md-2">Tgl Jatuh Tempo</label> 
                    <label class="col-md-4">Kode Harga</label> 
                    <div class="col-sm-2">
                        <input type="text" name="ipajak" id="ipajak" class="form-control" value="<?= $head->i_pajak;?>" readonly>
                    </div>
                    <div class="col-sm-2">
                        <input type="text" name="dpajak" id="dpajak" class="form-control" value="<?= $head->d_pajak;?>"
                        readonly >
                    </div>

                    <div class="col-sm-2">
                        <input type="text" name="dreceivefaktur" id="dreceivefaktur" class="form-control" value="<?= $head->d_terima_faktur;?>" readonly="" onchange="return tgl_jatuhtempo(this.value);">
                    </div>
                    <div class="col-sm-2">
                        <input type="text" name="djatuhtempo" id="djatuhtempo" class="form-control" value="<?= $head->d_jatuh_tempo;?>" readonly>
                    </div>
                    <div class="col-sm-4">
                        <input type="text" name="ekodeharga" id="ekodeharga" class="form-control" value="<?= $head->e_harga;?>" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Keterangan</label>
                    <div class="col-sm-12">
                        <textarea class="form-control input-sm" name="eremark" placeholder="Isi keterangan jika ada!" readonly><?= $head->e_remark;?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <span class="notekode"><b>Note : </b></span><br>
                    <span class="notekode">* Harga barang yang digunakan adalah harga exclude.</span><br>
                    <span class="notekode">* Tanggal jatuh tempo adalah tanggal nota + TOP!</span><br>
                    <span class="notekode">* Jika sudah di terima maka tanggal jatuh tempo adalah tanggal terima + TOP!</span>
                </div>
            </div>
            <div class="col-md-12">
                <?php
                    }else{                           
                            $read = "disabled";
                            echo "<table class=\"table table-striped bottom\" style=\"width:100%;\"><tr><td colspan=\"6\" style=\"text-align:center;\">Maaf Tidak Ada Data!</td></tr></table>";
                    }?>
            </div>
        </div>
        </div>
    </div>
</div>
<?php $i = 0;$group = "";$no = 0; if ($detail) {?>        
        <div class="white-box" id="detail">
            <div class="col-sm-6">
                <h3 class="box-title m-b-0">Detail Barang</h3>
            </div>
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table id="tabledatay" class="table color-table inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                        <thead>
                            <tr>
                                <th class="text-center" width="3%">No</th>
                                <th class="text-center" width="30%;">Barang</th>
                                <th class="text-center">Qty</th>
                                <th class="text-center">Harga</th>
                                <th class="text-center" width="15%;">Disc 123 (%)</th>
                                <th class="text-center">Disc (Rp.)</th>
                                <th class="text-center">Total</th>
                                <th class="text-center">Keterangan</th>
                               <!--  <th class="text-center" width="3%">Act</th> -->
                            </tr>
                        </thead>
                        <tbody> 
                            <?php 
                            foreach ($detail as $key) { 
                                $i++; 
                                $no++;
                                $total = $key->v_price * $key->n_quantity;
                                if($group==""){ ?>
                                    <tr class="pudding">
                                    <td colspan="8">Nomor SJ : <b><?= $key->i_document;?></b> &nbsp;&nbsp;-&nbsp;&nbsp; Tanggal SJ : <b><?= $key->d_document;?></b> &nbsp;&nbsp; (<b><?= $key->e_type_reff;?> )</td>
                                    </tr>
                                <?php } else {
                                        if($group!=$key->id_document_reff.$key->e_type_reff){ ?>
                                        <tr class="pudding">
                                            <td colspan="8">Nomor SJ : <b><?= $key->i_document;?></b> &nbsp;&nbsp;-&nbsp;&nbsp; Tanggal SJ : <b><?= $key->d_document;?></b> &nbsp;&nbsp; (<b><?= $key->e_type_reff;?> )</td>
                                        </tr>
                                        <?php $no = 1; }
                                    }
                                    $group = $key->id_document_reff.$key->e_type_reff;?>
                                <tr>
                                    <td class="text-center"><spanx id="snum<?=$i;?>"><?=$no;?></spanx></td>
                                    <td><input type="text" readonly class="form-control input-sm" name="i_material<?=$i;?>" id="i_material<?=$i;?>" value="<?= $key->i_material.' - '.$key->e_material_name;?>"/>
                                        <input type="hidden" readonly class="form-control input-sm" name="id_document<?=$i;?>" id="id_document<?=$i;?>" value="<?= $key->id_document_reff;?>"/>
                                        <input type="hidden" readonly class="form-control input-sm" name="id_material<?=$i;?>" id="id_material<?=$i;?>" value="<?= $key->id_material;?>"/>
                                        <input type="hidden" readonly class="form-control input-sm" name="e_type_reff<?=$i;?>" id="e_type_reff<?=$i;?>" value="<?= $key->e_type_reff;?>"/>
                                    </td>
                                    <td><input type="text" id="nquantity<?=$i;?>" class="form-control text-right input-sm inputitem" autocomplete="off" name="nquantity<?=$i;?>" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="<?= $key->n_quantity;?>" onkeyup="angkahungkul(this); hitungtotal();" readonly>  <input type="hidden" readonly class="form-control input-sm text-right" name="nquantity_sj<?=$i;?>" id="nquantity_sj<?=$i;?>" value="<?= $key->n_quantity_sisa;?>"/></td>
                                    <td><input type="text" readonly class="form-control input-sm text-right hargaitem" name="vharga<?=$i;?>" id="vharga<?=$i;?>" value="<?= number_format($key->v_price);?>"/></td>
                                    <td>
                                        <div class="row">
                                            <div class="col-sm-4 pudding">
                                                <input type="text" readonly class="form-control input-sm text-right" placeholder="%1" name="ndisc1<?=$i;?>" id="ndisc1<?=$i;?>" value="<?= $key->n_diskon1;?>"/>
                                                <input type="hidden" readonly class="form-control input-sm text-right" name="vdisc1<?=$i;?>" id="vdisc1<?=$i;?>" value="<?= $key->v_diskon1;?>"/>
                                            </div>
                                            <div class="col-sm-4 pudding">
                                                <input type="text" readonly class="form-control input-sm text-right" placeholder="%2" name="ndisc2<?=$i;?>" id="ndisc2<?=$i;?>" value="<?= $key->n_diskon2;?>"/>
                                                <input type="hidden" readonly class="form-control input-sm text-right" name="vdisc2<?=$i;?>" id="vdisc2<?=$i;?>" value="<?= $key->v_diskon2;?>"/>
                                            </div>
                                            <div class="col-sm-4 pudding">
                                                <input type="text" readonly class="form-control input-sm text-right" placeholder="%3" name="ndisc3<?=$i;?>" id="ndisc3<?=$i;?>" value="<?= $key->n_diskon3;?>"/>
                                                <input type="hidden" readonly class="form-control input-sm text-right" name="vdisc3<?=$i;?>" id="vdisc3<?=$i;?>" value="<?= $key->v_diskon3;?>"/>
                                            </div>
                                        </div>
                                    </td>
                                    <td><input readonly type="text" class="form-control input-sm text-right" name="vdiscount<?=$i;?>" id="vdiscount<?=$i;?>" onblur='if(this.value==""){this.value="0";}' onfocus='if(this.value=="0"){this.value="";}' value="<?= number_format($key->v_diskon_tambahan);?>" onkeyup="angkahungkul(this); hitungtotal(); reformat(this);"/></td>
                                    <td>
                                        <input type="text" readonly class="form-control input-sm text-right" name="vtotal<?=$i;?>" id="vtotal<?=$i;?>"  value="<?= number_format($total);?>"/>
                                        <input type="hidden" readonly class="form-control input-sm text-right" name="vtotaldiskon<?=$i;?>" id="vtotaldiskon<?=$i;?>" value="<?= $key->v_diskon_total;?>"/>
                                    </td>
                                    <td><input readonly type="text" class="form-control input-sm" name="eremark<?=$i;?>" id="eremark<?=$i;?>" placeholder="Jika Ada!"  value="<?= $key->e_remark;?>"></td>
                                </tr>
                                <?php } ?>
                        </tbody>
                        <tfoot>

                           <tr>
                                <td class="text-right" colspan="6">Total :</td>
                                <td><input type="text" id="nkotor" name="nkotor" class="form-control input-sm text-right" value="<?= number_format($head->v_kotor);?>" readonly></td>
                                <td colspan="2"></td>
                            </tr>
                            <tr>
                                <td class="text-right" colspan="6">Diskon :</td>
                                <td><input type="text" id="ndiskontotal" name="ndiskontotal" class="form-control input-sm text-right" readonly value="<?= number_format($head->v_diskon);?>"></td>
                                <td colspan="2"></td>
                            </tr>
                            <tr>
                                <td class="text-right" colspan="6">DPP :</td>
                                <td><input type="text" id="vdpp" name="vdpp" class="form-control input-sm text-right" value="<?= number_format($head->v_dpp);?>" readonly></td>
                                <td colspan="2"></td>
                            </tr>
                            <tr>
                                <td class="text-right" colspan="6">PPN (10%) :</td>
                                <td><input type="text" id="vppn" name="vppn" class="form-control input-sm text-right" value="<?= number_format($head->v_ppn);?>" readonly></td>
                                <td colspan="2"></td>
                            </tr>
                            <tr>
                                <td class="text-right" colspan="6">Grand Total :</td>
                                <td><input type="text" id="nbersih" name="nbersih" class="form-control input-sm text-right" value="<?= number_format($head->v_bersih);?>" readonly></td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    <?php }else{ ?>
        <div class="white-box">
            <div class="card card-outline-danger text-center text-dark">
                <div class="card-block">
                    <footer>
                        <cite title="Source Title"><b>Item Tidak Ada</b></cite>
                    </footer>
                </div>
            </div>
        </div>
    <?php } ?>
    <input type="hidden" name="jml" id="jml" value="<?=$i;?>">
</form>

<script>
    function approve() {
        var data = [];
         
        for (var i = 1; i <= $('#jml').val(); i++) { 
            if (parseInt($('#nquantity'+i).val()) > parseInt($('#nquantity_sj'+i).val())) {
                swal('Maaf :(','Quantity '+$('#i_material'+i).val()+' Sudah Dibuat Nota !','error');
                data.push("lebih");
                //return false;
            } else {
                data.push("oke");
            }
        }

        //console.log(data.includes("lebih"));
        if (data.includes("lebih") == false) {
          statuschange('<?= $folder."','".$id;?>','6','<?= $dfrom."','".$dto;?>');
        } 
          
    }



</script>