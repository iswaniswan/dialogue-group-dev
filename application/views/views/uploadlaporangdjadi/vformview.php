<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                   <div class="form-group row">
                            <label class="col-md-4">Pembuat Dokumen</label>
                            <label class="col-md-2">Bulan</label>
                            <label class="col-md-2">Tahun</label>
                            <div class="col-md-4"></div>
                            
                            <div class="col-sm-4">
                                <!-- <input type="hidden" name="id" id="id" class="form-control" value="<?php /* if($head) echo $head->id */;?>" readonly>  -->
                                <input type="hidden" name="ibagian" id="ibagian" class="form-control" value="<?= $bagian->i_bagian;?>" readonly>   
                                <input type="text" name="e_bagian_name" id="e_bagian_name" class="form-control input-sm" value="<?= $bagian->e_bagian_name;?>" readonly>
                            </div>

                            <div class="col-sm-2"> 
                                <input type="text" name="bulan" id="bulan" class="form-control input-sm" value="<?= $bulan;?>" readonly>   
                            </div>
                            <div class="col-sm-2">
                                <input type="text" name="tahun" id="tahun" class="form-control input-sm" value="<?= $tahun;?>" readonly>   
                            </div>
                    </div>  

                    <div class="form-group">
                        <div class="col-sm-offset-5 col-sm-10">  
                        <!-- <?php //if (($customer->tahun.$customer->ibulan) > date('Ym')) { 
                            /* if($head) {
                                if ($head->i_status == '1' || $head->i_status == '3') {
                                    echo '<button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>'. '     ';
                                    echo '<button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah</button>'. '     ';
                                    echo '<button type="button" id="send" hidden="true" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>'. '     ';
                                }
                            } else {
                                echo '<button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>'. '     ';
                                echo '<button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah</button>'. '     ';
                                echo '<button type="button" id="send" hidden="true" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>'. '     ';
                            } */
                        ?> -->
                       
                        <button type="button" class="btn btn-inverse btn-rounded btn-sm mr-2" onclick="show('<?= $folder; ?>/cform/index','#main'); return false;"><i class="fa fa-arrow-circle-left mr-2"></i>Kembali</button>
                        </div>
                    </div>
                </div>
               <!--  <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-6">Barang</label>
                        <div class="col-sm-6">
                            <input type="text" name="ekodebrg" id="ekode" class="form-control date" value="<?= $barang->e_material_name;?>"disabled = 't'>
                        </div>
                    </div>
                </div> -->
        </div>
                        </div>
                        </div>
                        </div>

    <div class="white-box" id="detail">
        <div class="col-sm-6">
            <h3 class="box-title m-b-0">Detail Barang</h3>
        </div>
        <div class="col-sm-12">
            <div class="table-responsive">
                <table id="tabledatax" class="table color-table inverse-table table-bordered class" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th width="3%">No</th>
                            <th width="72%">Nama Barang</th>
                            <th width="15%">Warna</th>
                            <th width="10%">Saldo Awal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 0;
                        foreach ($datadetail as $key) {
                            $i++;
                            ?>
                            <tr>
                                <td class="text-center"><spanx id="snum<?= $i ;?>"><?= $i ;?></spanx></td>
                                <td>
                                    <?= $key["i_product_base"];?> - <?= $key["e_product_basename"];?> - <?= $key["e_color_name"];?>
                                </td>
                                <!-- <td>
                                    <input type="text" id="i_product_base<?= $i ;?>" class="form-control input-sm" readonly name="i_product_base<?= $i ;?>" value="<?= $key["i_product_base"];?>">
                                </td> -->
                                <td><?= $key["e_color_name"];?></td>
                                <td><?= $key["n_saldo_awal"];?></td>
                                <!-- <td><input type="text" id="nquantity<?= $i ;?>" class="form-control text-right input-sm inputitem" autocomplete="off" name="nquantity<?= $i ;?>" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="<?= $key["n_quantity"];?>" onkeyup="angkahungkul(this);"></td> -->
                            </tr>
                        <?php } 
                        ?> 
                        <input style ="width:50px"type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
</div>
</form>