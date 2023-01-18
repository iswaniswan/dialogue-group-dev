<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/proses'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
            <div class="panel-body table-responsive">
                <div class="col-md-12">
                    <div id="pesan"></div>
                    <div class="form-group row">
                        <label class="col-md-4">Kategori Barang</label>  
                        <label class="col-md-4">Jenis Barang</label>
                        <label class="col-md-4">Kode Barang</label>
                        <div class="col-sm-4">
                            <select name="ikodekelompok" id ="ikodekelompok" class="form-control select2">
                                <option value="semua">Semua Kategori</option>
                                <?php foreach ($kodekelompok as $r):?>
                                    <option value="<?php echo $r->i_kode_kelompok;?>"><?php echo $r->i_kode_kelompok." - ".$r->e_nama;?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>              
                        <div class="col-sm-4">
                            <select name="ikodejenis" id="ikodejenis" class="form-control select2">
                                <option value="semua">Semua Jenis</option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <select name="i_product" id ="i_product" class="form-control select2"> 
                                <option value="semua">Semua Barang</option>                         
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-spin fa-spinner"></i>&nbsp;&nbsp;Proses</button>&nbsp;&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/','#main'); return false;"><i class="ti-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;&nbsp;
                        </div>
                    </div>
                </div>
                <input type="hidden" name="jml" id="jml">
            </div>
        </div>
    </form>
</div>
</div>
<script>
    $("form").submit(function (event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
    });

    $(document).ready(function () {
        $(".select2").select2();
        $('#ikodekelompok').select2({
            placeholder: 'Semua Kategori',
        })
        $('#ikodejenis').select2({
            placeholder: 'Cari Berdasarkan Jenis',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/jenis'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                        ikodekelompok : $('#ikodekelompok').val(),
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: false
            }
        });
        $('#i_product').select2({
            placeholder: 'Cari Barang',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/product'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                        ikodekelompok : $('#ikodekelompok').val(),
                        ikodejenis : $('#ikodejenis').val(),
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: false
            }
        });
    });

    $( "#ikodekelompok" ).change(function() {
        if ($(this).val()!='semua') {
            $("#ikodejenis").val("");
            $("#ikodejenis").html("");
            $("#i_product").val("");
            $("#i_product").html("");
        }else{
            var data = {
                id: 'semua',
                text: 'Semua Jenis'
            };
            var newOption = new Option(data.text, data.id, false, false);
            $('#ikodejenis').append(newOption).trigger('change');
            var datax = {
                id: 'semua',
                text: 'Semua Barang'
            };
            var newOptionx = new Option(datax.text, datax.id, false, false);
            $('#i_product').append(newOptionx).trigger('change');            
        };
    });
</script>