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
                        <label class="col-md-3">No. Dokumen</label>
                        <label class="col-md-2">Tgl. Dokumen</label>
                        <label class="col-md-2">No. Dokumen Referensi</label>
                        <label class="col-md-2">Tgl. Dokumen Referensi</label>
                        <div class="col-sm-3">
                            <input type="text" name="ebagian" id="ebagian" class="form-control input-sm" value="<?= $data->e_bagian;?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" value="<?= $id ;?>" readonly>
                                <input type="hidden" name="idocumentold" id="idocumentold" value="<?= $data->i_document;?>" readonly>
                                <input type="text" name="idocument" id="idocument" readonly="" autocomplete="off" onkeyup="gede(this);" maxlength="16" class="form-control input-sm" value="<?= $data->i_document;?>" aria-label="Text input with dropdown button">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="ddocument" id="ddocument" class="form-control input-sm date" value="<?= $data->d_document;?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="ispbb" id="ispbb" class="form-control input-sm" value="<?= $data->i_spbb;?>" readonly>
                            <input type="hidden" name="idspbb" id="idspbb" value="<?= $data->id_spbb;?>">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="dspbb" id="dspbb" class="form-control input-sm" value="<?= $data->d_spbb;?>" readonly>
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-3">Permintaan Dari</label>
                        <label class="col-md-9">Keterangan</label>
                        <div class="col-sm-3">
                            <input type="hidden" name="itujuan" id="itujuan" class="form-control input-sm" value="<?= $data->i_bagian_tujuan;?>" readonly>
                            <input type="text" name="etujuan" id="etujuan" class="form-control input-sm" value="<?= $data->e_bagian_name;?>" readonly>
                        </div>
                        <div class="col-sm-9">
                            <textarea type="text" id="eremark" name="eremark" maxlength="250" readonly="" class="form-control input-sm" placeholder="Isi keterangan jika ada!"><?= $data->e_remark;?></textarea>
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
        <h3 class="box-title m-b-0">Detail Barang</h3>
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 3%;">No</th>
                        <th class="text-center" style="width: 10%;">Kode</th>
                        <th class="text-center" style="width: 30%;">Nama Material</th>
                        <th class="text-center" style="width: 8%;">Satuan</th>
                        <th class="text-center" style="width: 8%;">Jml SPBB</th>
                        <th class="text-center" style="width: 10%;">Panjang Kain</th>
                        <th class="text-center" style="width: 8%;">Jml Keluar</th>
                        <th class="text-center">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($datadetail as $key) {?>
                        <tr>
                            <td class="text-center"><?= $i+1;?></td>
                            <td><?= $key->i_material;?>
                            <input type="hidden" id="imaterial<?= $i ;?>" name="imaterial<?= $i ;?>" value="<?= $key->i_material;?>">
                            <input type="hidden" id="idmaterial<?= $i ;?>" name="idmaterial<?= $i ;?>" value="<?= $key->id_material;?>">
                            <input type="hidden" id="idproduct<?= $i ;?>" name="idproduct<?= $i ;?>" value="<?= $key->id_product_wip;?>"></td>
                            <td><?= $key->e_material_name;?></td>
                            <td><?= $key->e_satuan_name;?></td>
                            <td class="text-right">
                                <?= $key->qtywip;?>
                                <input type="hidden" id="nquantity<?= $i ;?>" name="nquantity<?= $i ;?>" value="<?= $key->qtywip;?>">
                            </td>
                            <td class="text-right">
                                <?= $key->n_panjang_kain;?>
                                <input type="hidden" id="npanjangkain<?= $i ;?>" name="npanjangkain<?= $i ;?>" value="<?= $key->n_panjang_kain;?>">
                                <input type="hidden" id="nsisa<?= $i ;?>" name="nsisa<?= $i ;?>" value="<?= $key->n_panjang_kain;?>">
                            </td>
                            <td class="text-right">
                                <?= $key->qtymaterial;?>
                                <input type="hidden" id="npemenuhan<?= $i ;?>" name="npemenuhan<?= $i ;?>" value="<?= $key->qtymaterial;?>">
                            </td>
                            <td><?= $key->e_remark;?></td>
                        </tr>
                        <?php $i++; } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php 
    } ?>
    <input type="hidden" name="jml" id="jml" value ="<?= $i;?>">
    <script>
        $("form").submit(function(event) {
            event.preventDefault();
            $("input").attr("disabled", true);
            $("select").attr("disabled", true);
            $("#submit").attr("disabled", true);
            $("#send").attr("hidden", false);
        });

        $(document).ready(function () {
            $('.select2').select2();
            for (var i = 0; i < $("#jml").val(); i++) {
                $.ajax({
                    type: "post",
                    data: {
                        'idmaterial'    : $('#idmaterial'+i).val(),
                        'idproductwip'  : $('#idproduct'+i).val(),
                        'idspbb'        : $('#idspbb').val(),
                        'i'             : i,
                    },
                    url: '<?= base_url($folder.'/cform/ceksisa'); ?>',
                    dataType: "json",
                    success: function (data) {
                        $('#nsisa'+data['z']).val(data['qty']);
                    },
                    error: function () {
                        swal('Error :(');
                    }
                });
            }
        });

        $('#approve').click(function(event) {
            var habis     = false;
            var xmaterial = '';
            for (var x = 0; x < $("#jml").val(); x++) {
                if (parseInt($('#nsisa'+x).val()) <= 0) {
                    var habis = true;
                    xmaterial += $('#imaterial'+x).val()+', ';
                }
            }

            var n = xmaterial.length-2;
            if (habis==false) {
                statuschange('<?= $folder;?>',$('#id').val(),'6','<?= $dfrom."','".$dto;?>');
            }else{
                swal("Yaaahhh :(", "Jml sisa kode material "+xmaterial.substr(0,n)+" di SPBB nya sudah habis.. :(", "error");
                return false;
            }
        });
    </script>