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
                            <input type="hidden" name="ibagian" id="ibagian" class="form-control" value="<?= $bagian->i_bagian;?>" disabled = 't'>
                            <input type="text" name="ebagian" id="ebagian" class="form-control" value="<?= $bagian->e_bagian_name;?>" disabled = 't'>
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
                            <input type="text" name="ekodemaster" id="ekodemaster" class="form-control date" value="<?= $kategori->e_nama_kelompok;?>"disabled = 't'>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-6">Jenis Barang</label>
                        <div class="col-sm-6">
                            <input type="text" name="ekodemaster" id="ekodemaster" class="form-control date" value="<?= $jenis->e_type_name;?>"disabled = 't'>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-12">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/','#main'); return false;"><i class="ti-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;&nbsp;
                            
                        </div>
                    </div>
                </div>
                    <div class="panel-body table-responsive">
                        <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Barang</th>
                                    <th width="25%">Nama Barang</th>
                                    <th>Satuan</th>
                                    <th>Saldo Awal</th>
                                    <th>Bon Masuk</th>
                                    <th>Masuk Makloon</th>
                                    <th>Masuk Pinjaman</th>
                                    <th>Bon Keluar</th>
                                    <th>Keluar Makloon</th>
                                    <th>Keluar Pinjaman</th>
                                    <th>Adjustment</th>
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
                                <tr>
                                    <td>
                                        <?= $i; ?>
                                    </td>
                                    <td>
                                       <?= $row->i_material; ?>
                                    </td>
                                    <td>
                                         <?= $row->e_material_name; ?>
                                    </td>
                                    <td>
                                        <?= $row->e_satuan_name; ?>
                                    </td>
                                    <td>
                                        <?= $row->saldoawal; ?>
                                    </td>
                                    <td>
                                       <?= $row->m_masuk; ?>
                                    </td>
                                    <td>
                                       <?= $row->m_maklon; ?>
                                    </td>
                                    <td>
                                       <?= $row->m_pinjam; ?>
                                    </td>
                                    <!-- <td>
                                       <?= //$row->k_jual; ?>
                                    </td>
                                    <td>
                                        <?=// $row->k_returbeli; ?>
                                    </td> -->
                                    <td>
                                        <?= $row->k_keluar; ?>
                                    </td>
                                    <td>
                                        <?= $row->k_makloon; ?>
                                    </td>
                                        
                                    <td>
                                        <?= $row->k_pinjam; ?>
                                    </td>
                                    <!-- <td>
                                       <?= //$row->k_produksi; ?>
                                    </td> -->
                                    <td>
                                        <?= $row->adjust; ?>
                                    </td>
                                    <td>
                                        <?= $row->saldo_akhir; ?>
                                    </td>
                                    <td>
                                       <?= $row->so; ?>
                                    </td>
                                    <td>
                                        <?= $row->selisih; ?>
                                    </td>
                                </tr>
                                <?php  } ?>
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
$(document).ready(function () {
    $(".select").select();
    showCalendar('.date');
});
</script>