<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-check"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-3">Bagian Pembuat</label>
                        <label class="col-md-2">Nomor Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-3">Kas/Bank</label>
                        <label class="col-md-2">Bank</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control input-sm" value="<?= $data->e_bagian_name;?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" value="<?=$data->id;?>">
                                <input type="text" readonly="" class="form-control input-sm" value="<?= $data->i_document;?>">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" class="form-control input-sm" value="<?= $data->d_document;?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" class="form-control input-sm" value="<?=$data->e_kas_name;?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" class="form-control input-sm" value="<?= $data->e_bank_name;?>" readonly>
                        </div> 
                    </div>
                    <div class="form-group row">            
                        <label class="col-md-3">Jenis Keluar</label>
                        <label class="col-md-2">Referensi</label>
                        <label class="col-md-2">Total Nilai</label>
                        <label class="col-md-5">Keterangan</label>
                        <div class="col-sm-3">
                            <input class="form-control input-sm" readonly value="<?= $data->e_jenis_name;?>">
                        </div>   
                        <div class="col-sm-2">
                            <input class="form-control input-sm" readonly value="<?= $data->i_referensi;?>">
                        </div>
                        <div class="col-sm-2">
                            <input class="form-control text-right input-sm" required="" placeholder="Rp. 0,000,000.00" name="vnilai" id="vnilai" readonly value="<?= number_format($data->n_nilai);?>">
                        </div>                        
                        <div class="col-sm-5">
                            <textarea id= "eremark" name="eremark" class="form-control" readonly=""><?=$data->e_remark;?></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                            <button type="button" class="btn btn-warning btn-rounded btn-sm" onclick="statuschange('<?= $folder."','".$id;?>','1','<?= $dfrom."','".$dto;?>');"> <i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;Change Requested</button>&nbsp;
                            <button type="button" class="btn btn-danger btn-rounded btn-sm"  onclick="statuschange('<?= $folder."','".$id;?>','4','<?= $dfrom."','".$dto;?>');"> <i class="fa fa-times"></i>&nbsp;&nbsp;Reject</button>&nbsp;
                            <button type="button" class="btn btn-success btn-rounded btn-sm" id="approve"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Approve</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<div class="white-box" id="detail" >
    <div class="col-sm-5">
        <h3 class="box-title m-b-0">Detail Transaksi</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead> 
                    <tr>
                        <th class="text-center" width="3%;">No</th>
                        <th class="text-center">No. Referensi</th>
                        <th class="text-center">Tgl. Referensi</th>
                        <th class="text-center">Nilai Referensi</th>
                        <th class="text-center">Nilai</th>
                        <th class="text-center" width="30%;">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $i = 0;
                    if ($datadetail) {
                        foreach ($datadetail as $row) {?>
                            <tr>
                                <td class="text-center"><?= $i+1;?></td>
                                <td><?= $row->i_referensi;?></td>
                                <td><?= $row->d_referensi;?></td>
                                <td class="text-right"><?= number_format($row->n_sisa);?></td>
                                <td class="text-right">
                                    <?= number_format($row->n_nilai);?>
                                    <input type="hidden" id="n_nilai<?=$i;?>" value ="<?= $row->n_nilai;?>">
                                    <input type="hidden" id="n_sisa<?=$i;?>" value ="<?= $row->n_sisa;?>">
                                </td>
                                <td><?= $row->e_remark;?></td>
                            </tr>
                        <?php $i++; } 
                    }?>
                    <input type="hidden" name="jml" id="jml" value ="<?= $i;?>">
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('.select2').select2();
    });

    $('#approve').click(function(event) {
        var ada = false;
        for (var i = 0; i < $('#jml').val(); i++) {
            if (parseInt(formatulang($('#n_nilai'+i).val())) > parseInt(formatulang($('#n_sisa'+i).val()))) {
                ada = true;
            }
            if (!ada) {
                statuschange('<?= $folder."','".$id;?>','6','<?= $dfrom."','".$dto;?>');
            }else{
                swal('Maaf :(','Jumlah Nilai Tidak Boleh Lebih Besar Dari Nilai Referensi!','error');
                return false;
            }
        }
    });
</script>