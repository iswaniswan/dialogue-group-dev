<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-4">Gudang</label>
                        <div class="col-sm-4">
                            <input type="hidden" name="ikodemaster" id="ikodemaster" class="form-control date" value="<?= $kodemaster;?>"disabled = 't'>
                            <input type="text" name="ekodemaster" id="ekodemaster" class="form-control date" value="<?= $gudang->e_nama_master;?>"disabled = 't'>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Date From</label>
                        <label class="col-md-8">Date to</label>
                        <div class="col-sm-4">
                            <input type="text" name="dfrom" id="dfrom" class="form-control" value="<?= $dfrom;?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" name="dto" id="dto" class="form-control" value="<?= $dto;?>" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-6">Kategori Barang</label>
                        <div class="col-sm-6">
                            <input type="text" name="ekodemaster" id="ekodemaster" class="form-control date" value="<?= $kategori->e_nama;?>"disabled = 't'>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-6">Jenis Barang</label>
                        <div class="col-sm-6">
                            <input type="text" name="ekodemaster" id="ekodemaster" class="form-control date" value="<?= $jenis->e_type_name;?>"disabled = 't'>
                        </div>
                    </div>
                </div>
                    <div class="panel-body table-responsive">
                        <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Satuan</th>
                                    <th>Saldo Awal</th>
                                    <th>Masuk dari Pembelian</th>
                                    <th>Masuk dari Makloon</th>
                                    <th>Masuk Lain</th>
                                    <th>Keluar ke Produksi</th>
                                    <th>Keluar Lain</th>
                                    <th>Retur Pembelian</th>
                                    <th>GIT</th>
                                    <th>Saldo Akhir</th>
                                    <th>SO</th>
                                    <th>Selisih</th> 
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0;
                                $gudang = '';
                                    foreach ($data2 as $row) {
                                    $i++;
                                ?>
                                <?php if($gudang != $row->kodegudang){ ?>
                                   <!--  <tr>
                                    <td colspan="13"> <?= $row->gudang; ?></td>
                                    </tr> -->
                                <?php } ?>
                                <tr>
                                <td class="col-sm-1">
                                    <?= $i; ?>
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:100px"type="text" class="form-control" id="imaterial<?=$i;?>" name="imaterial<?=$i;?>"value="<?= $row->kode; ?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:400px"type="text" class="form-control" id="ematerialname<?=$i;?>" name="ematerialname<?=$i;?>"value="<?= $row->barang; ?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:80px" type="text" class="form-control" id="esatuan<?=$i;?>" name="esatuan<?=$i;?>"value="<?= $row->satuan; ?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:70px"type="text" class="form-control" id="saldoawal<?=$i;?>" name="saldoawal<?=$i;?>"value="<?= $row->saldoawal; ?>"readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:70px"type="text" class="form-control" id="bonmasuk<?=$i;?>" name="bonmasuk<?=$i;?>"value="<?= $row->bonmasuk1; ?>"readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:70px"type="text" class="form-control" id="bonmasukmakloon<?=$i;?>" name="bonmasukmakloon<?=$i;?>"value="<?= $row->bonmasukmakloon; ?>" readonly>
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:70px"type="text" class="form-control" id="bonmasuklain<?=$i;?>" name="bonmasuklain<?=$i;?>"value="<?= $row->bonmasuklain; ?>" readonly>
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:70px"type="text" class="form-control" id="bonkeluar<?=$i;?>" name="bonkeluar<?=$i;?>"value="<?= $row->bonkeluar; ?>" readonly>
                                </td>
                                
                                <td class="col-sm-1">
                                    <input style ="width:70px"type="text" class="form-control" id="bonkeluarlain<?=$i;?>" name="bonkeluarlain<?=$i;?>"value="<?= $row->bonkeluarlain; ?>" readonly>
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:70px"type="text" class="form-control" id="returpembelian<?=$i;?>" name="returpembelian<?=$i;?>"value="<?= $row->returpembelian; ?>" readonly>
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:70px"type="text" class="form-control" id="git<?=$i;?>" name="git<?=$i;?>"value="0" readonly>
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:70px"type="text" class="form-control" id="saldoakhir<?=$i;?>" name="saldoakhir<?=$i;?>"value="<?= $row->saldoakhir; ?>" readonly>
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:70px"type="text" class="form-control" id="so<?=$i;?>" name="so<?=$i;?>"value="<?= $row->so; ?>" readonly>
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:70px"type="text" class="form-control" id="selisih<?=$i;?>" name="selisih<?=$i;?>"value="<?= $row->selisih; ?>" readonly>
                                </td>
                                </tr>
                               <!--  <?php $gudang = $row->kodegudang; } ?> -->
                                <input style ="width:50px"type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
</script>