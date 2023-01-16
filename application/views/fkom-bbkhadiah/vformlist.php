<div class="row">
    <div class="col-lg-12">
    <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/approve'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <table id="tabledata" class="display nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>No BBK</th>
                            <th>Tanggal BBK</th>
                            <th>Keterangan</th>
                            <th>Jumlah</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tr>
						<td colspan='5' align='center'>
                            <input style= "text-align: center;" id='ifakturkomersial' name='ifakturkomersial' placeholder="No Faktur" value='' maxlength=6>
                            <br><br>
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>&nbsp;&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </td>
					</tr>
                </table>
            </div>
        </div>
    </form>
    </div>
</div>


<script>
    $(document).ready(function () {
        datatable('#tabledata', base_url + '<?= $folder; ?>/Cform/data/<?= $periode ?>/');
    });
</script>
