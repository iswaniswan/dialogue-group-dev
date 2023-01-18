<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>


            <div class="panel-body table-responsive">
            <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-3">Nama Unit Jahit</label>
                            <label class="col-md-3">Nama Perusahaan</label>
                            <label class="col-md-6">Lokasi Perusahaan</label>
                            <div class="col-sm-3">
                                <input type="text" name="eunitjahitname" class="form-control input-sm"  value="" placeholder="isi Nama Unit jahit">
                            </div>
                            <div class="col-sm-3">
                                <input type="text" name="eperusahaanname" class="form-control input-sm" required="" value="" placeholder="isi Nama Perusahaan Unit Jahit">
                            </div>
                            <div class="col-sm-6">
                                <input type="text" name="eunitjahitaddress" class="form-control input-sm" maxlength=""  value="" placeholder="isi Alamat Lokasi Perusahaan Unit Jahit">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-3">Penanggung Jawab</label>
                            <label class="col-md-3">Admin</label>
                            <label class="col-md-6">Kategori Unit</label>
                            <div class="col-sm-3">
                                <input type="text" name="epenanggungjawabname" class="form-control input-sm" required="" value="" placeholder="isi Penanggung Jawab Unit Jahit">
                            </div>
                            
                            <div class="col-sm-3">
                                <input type="text" name="eadminname" class="form-control input-sm" value="" placeholder="isi Admin Unit Jahit">
                            </div>
                            <div class="col-sm-3">
                                <select id="ikategori" class="form-control input-sm id" name="ikategori" style="width: 100%;" ></select>
                            </div>
                        </div>  
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="col-sm-offset-8 col-sm-5">
                                <button type="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                                <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
 

        </div>
    </div>
</div>

<script>
 $(document).ready(function () {
    $(".select2").select2();

    $('#ikategori').select2({
            placeholder: 'Cari Kategori Jahit',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getkategori'); ?>',
                dataType: 'json',
                delay: 250,          
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
 });
</script>
