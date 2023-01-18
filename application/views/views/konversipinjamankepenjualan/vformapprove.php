<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-check"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">  
                    <div class="form-group row">
                        <label class="col-md-3">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-3">Tanggal Dokumen</label>
                        <label class="col-md-3">Partner</label>
                        <div class="col-sm-3">
                            <input type="text" readonly="" class="form-control input-sm" value="<?= $data->e_bagian_name;?>">
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="hidden" id="id" name="id" value="<?= $data->id;?>">
                                <input type="text" readonly="" class="form-control input-sm" value="<?= $data->i_document;?>">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" class="form-control input-sm" value="<?= $data->d_document;?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" readonly="" class="form-control input-sm" value="<?= $data->e_partner_name;?>">
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-6">Dokumen Referensi</label>
                        <label class="col-md-6">Keterangan</label>
                        <div class="col-sm-6">
                            <select disabled="" required="" multiple="multiple" class="form-control input-sm select2">
                                <?php if ($referensi) {
                                    foreach ($referensi->result() as $key) {?>
                                        <option value="<?= $key->id;?>" selected><?= 'Nomor : '.$key->i_document.' - Tanggal : '.$key->d_document;?></option>
                                    <?php }
                                } ?>
                            </select>
                            <input type="hidden" id="dreff" readonly value="<?=$tanggal;?>">
                        </div>
                        <div class="col-sm-6">
                            <textarea type="text" id="eremark" name="eremark" readonly maxlength="250" class="form-control input-sm"><?= $data->e_remark;?></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                            <button type="button" class="btn btn-warning btn-rounded btn-sm" onclick="statuschange('<?= $folder."','".$data->id;?>','1','<?= $dfrom."','".$dto;?>');"> <i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;Change Requested</button>&nbsp;
                            <button type="button" class="btn btn-danger btn-rounded btn-sm"  onclick="statuschange('<?= $folder."','".$data->id;?>','4','<?= $dfrom."','".$dto;?>');"> <i class="fa fa-times"></i>&nbsp;&nbsp;Reject</button>&nbsp;
                            <button type="button" id="approve" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Approve</button>&nbsp;
                        </div>
                    </div>
                </div>           
            </div>
        </div>
    </div>
</div>
<?php $i = 0; if ($datadetail) {?>
<div class="white-box" id="detail">
    <div class="col-sm-3">
        <h3 class="box-title m-b-0">Detail Barang</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" width="3%">No</th>
                        <th class="text-center" width="10%">Kode</th>
                        <th class="text-center" width="30%">Nama Barang</th>
                        <th class="text-center" width="10%">Satuan</th>
                        <th class="text-center" width="8%">Jml</th>
                        <th class="text-center" width="8%">Sisa</th>
                        <th class="text-center" width="10%">Konversi</th>
                        <th class="text-center">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 0; $group = ""; foreach ($datadetail as $key) {
                        $no++;
                        if($group==""){ ?>
                            <tr class="tdna">
                                <td colspan="8">Referensi : <?= $key->i_document;?></td>
                            </tr>
                            <?php 
                        }else{
                            if($group!=$key->id_document_reff){?>
                                <tr class="tdna">
                                    <td colspan="8">Referensi : <?= $key->i_document;?></td>
                                </tr>
                            <?php $no = 1; }
                        }
                        $group = $key->id_document_reff;?>
                        <tr>
                            <td class="text-center"><?= $no;?></td>
                            <td>
                                <?= $key->i_material;?>
                                <input type="hidden" id="iproduct<?=$i;?>" type="text" value="<?= $key->i_material;?>">
                            </td>
                            <td><?= $key->e_material_name;?></td>
                            <td><?= $key->e_satuan_name;?></td>
                            <td class="text-right">
                                <?= $key->n_quantity_reff;?>
                            </td>
                            <td class="text-right">
                                <?= $key->n_quantity_sisa_reff;?>
                                <input type="hidden" id="nquantitysisa<?=$i;?>" value="<?= $key->n_quantity_sisa_reff;?>">
                            </td>
                            <td class="text-right">
                                <?= $key->n_quantity;?>
                                <input type="hidden" id="nquantity<?=$i;?>" value="<?= $key->n_quantity;?>">
                            </td>
                            <td><?= $key->e_remark;?></td>
                        </tr>
                        <?php $i++; 
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<input type="hidden" name="jml" id="jml" value ="<?= $i ;?>">
<?php } ?>
<script>
    /*----------  LOAD SAAT DOKUMEN READY  ----------*/
    $(document).ready(function () {
        $('.select2').select2();
    });

    /*----------  VALIDASI SAAT APPROVE  ----------*/
    
    $('#approve').click(function(event) {
        var habis    = false;
        var iproduct = '';
        for (var x = 0; x < $("#jml").val(); x++) {

            /*----------  Jika Jumlah Konversi Melebihi Sisa Yang Ada  ----------*/
            
            if (parseInt($('#nquantity'+x).val()) > parseInt($('#nquantitysisa'+x).val())) {
                var habis = true;
                iproduct += $('#iproduct'+x).val()+', ';
            }
        }

        var n = iproduct.length-2;
        if (habis==false) {
            statuschange('<?= $folder;?>',$('#id').val(),'6','<?= $dfrom."','".$dto;?>');
        }else{
            swal("Yaaahhh :(", "Jml Konversi Kode "+iproduct.substr(0,n)+" Melebihi Jml Sisa :(", "error");
            return false;
        }
    });
</script>