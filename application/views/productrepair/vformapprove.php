<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-check"></i>  <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i><?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-3">Tanggal Dokumen</label>
                        <label class="col-md-3">Keterangan</label>
                        <input type="hidden" id="id" name="id" value="<?= $data->id ?>" />
                        <div class="col-md-3">
                            <input type="text" name="id_bagian" id="id_bagian" readonly="" autocomplete="off" class="form-control input-sm" value="<?= $data->e_bagian_name ?>" aria-label="Text input with dropdown button">
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="i_document" id="i_document" readonly="" autocomplete="off" class="form-control input-sm" value="<?= $data->i_document ?>" aria-label="Text input with dropdown button">
                        </div>
                        <div class="col-md-3">
                            <input type="text" id="d_document" name="d_document" class="form-control input-sm date"  required="" readonly value="<?= date("d-m-Y");?>">
                        </div>                        
                        <div class="col-md-3">
                            <textarea id= "e_remark" placeholder="Isi Keterangan Jika Ada!!!" name="e_remark" class="form-control"><?= $data->e_remark ?></textarea>
                        </div>
                    </div>
                    

                    <div class="form-group row">
                        <div class="col">
                            <button type="button" class="btn btn-inverse btn-block btn-sm mr-2" onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i class="fa fa-arrow-circle-left mr-2"></i>Kembali</button>
                        </div>
                        <div class="col">
                            <button type="button" class="btn btn-warning btn-block btn-sm mr-2" onclick="statuschange('<?= $folder."','".$data->id;?>','1','<?= $dfrom."','".$dto;?>');"> <i class="fa fa-pencil-square-o mr-2"></i>Change Requested</button>
                        </div>
                        <div class="col">
                            <button type="button" class="btn btn-danger btn-block btn-sm mr-2"  onclick="statuschange('<?= $folder."','".$data->id;?>','4','<?= $dfrom."','".$dto;?>');"> <i class="fa fa-times mr-2"></i>Reject</button>
                        </div>
                        <div class="col">
                            <button type="button" id="approve" class="btn btn-success btn-block btn-sm mr-2"> <i class="fa fa-check-square-o mr-2"></i>Approve</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="white-box" id="detail">
    <div class="col-sm-5">
        <h3 class="box-title m-b-0">Detail Barang</h3>
        <div class="m-b-0">
        </div>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 3%;">No</th>
                        <th class="text-center" style="width: 15%;">Kode Barang</th>
                        <th class="text-center" style="width: 27%;">Nama Barang Jadi</th>
                        <th class="text-center" style="width: 15%;">Warna</th>
                        <!-- <th class="text-center" style="width: 10%;">Stock</th> -->
                        <th class="text-center" style="width: 10%;">Quantity</th>
                        <th class="text-center" style="width: 30%;">Keterangan</th>
                        <!-- <th class="text-center" style="width: 5%;">Action</th> -->
                    </tr>
                </thead>
                <tbody>
                <?php $i=1; foreach ($detail as $item) { ?>
                    <tr>
                        <td><?= $i ?></td>
                        <td><?= $item->i_product_base ?></td>
                        <td><?= $item->e_product_basename ?></td>
                        <td><?= $item->e_color_name ?></td>
                        <td><?= $item->n_quantity ?></td>
                        <td><?= $item->e_remark ?></td>
                    </tr>
                <?php $i++; } ?>
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

    function getrequest(){
        
    }

    $('#approve').click(function(event) {
        ada = false;
        if (!ada) {
            statuschange('<?= $folder;?>',$('#id').val(),'6','<?= $dfrom."','".$dto;?>');
        }else{
            return false;
        }
    });
</script>
