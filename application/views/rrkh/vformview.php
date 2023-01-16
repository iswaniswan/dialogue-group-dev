<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-check"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;&nbsp;<?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
               <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-3">Bagian Pembuat</label>
                        <label class="col-sm-3">Nomor Dokumen</label>
                        <label class="col-sm-2">Tanggal Dokumen</label>
                        <label class="col-sm-2">Area</label>
                        <label class="col-sm-2">Salesman</label>
                       
                        <div class="col-sm-3">
                            <input type="text" id="ibagian" name="ibagian" class="form-control input-sm" required="" readonly value="<?= $data->e_bagian_name;?>">
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" value="<?= $id;?>">
                                <input type="text" name="dok_rrkh" id="dok_rrkh" value="<?= $data->i_document;?>" class="form-control input-sm" readonly>                                
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="drrkh" name="drrkh" class="form-control input-sm" required="" readonly value="<?= $data->d_document;?>">
                        </div> 
                        <div class="col-sm-2">
                            <input type="text" id="kode_area" name="kode_area" class="form-control input-sm" required="" readonly value="<?= $data->e_area;?>">
                        </div> 
                        <div class="col-sm-2">
                            <input type="text" id="kode_salesman" name="kode_salesman" class="form-control input-sm" required="" readonly value="<?= $data->e_sales;?>">
                        </div> 
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="white-box" id="detail">
    <div class="col-sm-5">
        <h3 class="box-title m-b-0">Detail Customer</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table font-11 success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 3%;">No</th>
                        <th class="text-center" style="width: 7%;">Id Cust.</th>
                        <th class="text-center" style="width: 20%;">Nama Cust.</th>
                        <th class="text-center" style="width: 10%;">Waktu</th>
                        <th class="text-center" style="width: 10%;">Area Cust.</th>
                        <th class="text-center" style="width: 10%;">Rencana</th>
                        <th class="text-center" style="width: 5%;">Real</th>
                        <th class="text-center" style="width: 5%;">Bukti</th>
                        <th class="text-center" style="width: 25%;">Keterangan</th>
                    </tr>
                </thead>
                <tbody>                   
                <?php 
                    $i = 0;
                    if ($detail) {
                        foreach ($detail as $row) {
                            $i++;?>
                            <tr>
                                <td class="text-center">
                                    <spanx id="snum<?=$i;?>"><?= $i;?></spanx>
                                </td>
                                <td>
                                   <?= $row->i_customer;?>
                                </td>
                                <td>
                                   <?= $row->e_customer_name;?>
                                </td>
                                <td>
                                   <?= $row->waktu;?>
                                </td>
                                <td>
                                   <?= $row->e_area;?>
                                </td>
                                <td>
                                   <?= $row->nama_rencana;?>
                                </td>

                            <?php if($row->f_real == 't'){ ?>

                                <td>
                                    <input type="checkbox" id="real" name="real" disabled checked>
                                </td>
                                
                            <?php } else if($row->f_real == 'f') { ?> 
                                
                                <td>
                                    <input type="checkbox" id="real" name="real" disabled>
                                </td>

                            <?php } ?>

                            <?php if($row->f_bukti == 't'){ ?>

                                <td>
                                    <input type="checkbox" id="bukti" name="bukti" disabled checked>
                                </td>
                                
                            <?php } else if($row->f_bukti == 'f') { ?> 
                                
                                <td>
                                    <input type="checkbox" id="bukti" name="bukti" disabled>
                                </td>

                            <?php } ?>

                                <td>
                                    <?= $row->keterangan;?>
                                </td>
                            </tr>
                        <?php } 
                    }?>
                    <input type="hidden" name="jml" id="jml" value ="<?= $i;?>">
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
    $(document).ready(function () {
        $('.select2').select2({
            width : '100%',
        });
    });

    $('#approve').click(function(event) {
        // ada = false;
        // for (var i = 1; i <= $('#jml').val(); i++) {
        //     if (parseInt($('#nquantity'+i).val()) > parseInt($('#nsisa'+i).val())) {
        //         swal('Jml tidak boleh lebih dari sisa op = '+ $('#nsisa'+i).val());
        //         $('#nquantity'+i).val($('#nsisa'+i).val());
        //         ada = true;
        //         return false;
        //     }
        // }

        // if (!ada) {
            statuschange('<?= $folder;?>',$('#id').val(),'6','<?= $dfrom."','".$dto;?>');
        // }else{
        //     return false;
        // }
    });
</script>
