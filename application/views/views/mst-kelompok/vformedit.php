<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
        <div class="panel-body table-responsive">
             <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Kode Kategori Barang</label>
                        <label class="col-md-6">Nama Kategori Barang</label>
                        <label class="col-md-3">Group Barang</label>
                        <div class="col-sm-3">
                            <input type="text" name="ikelompok" id="ikelompok" class="form-control" placeholder="Kode Kategori Barang" required="" onkeyup="gede(this);" value="<?= $data->i_kode_kelompok; ?>">
                            <input type="hidden" name="ikelompokold" id="ikelompokold" class="form-control" value="<?= $data->i_kode_kelompok; ?>" readonly>
                            <input type="hidden" name="id" id="id" class="form-control" value="<?= $data->id; ?>" readonly>
                            <span id="cek" hidden="true"> 
                                <font size="3" face="Courier New" color="red">Kode Sudah Ada!</font> 
                            </span>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" name="enama" id="enama" class="form-control" placeholder="Nama Kategori Barang" value="<?= $data->e_nama_kelompok; ?>" required="">
                        </div>
                        <div class="col-sm-3">
                           <select name="igroupbrg" id="igroupbrg" class="form-control select2">
                                <option value="">Pilih Group Barang</option>
                                <?php foreach($groupbarang as $igroupbrg): ?>
                                <option value="<?php echo $igroupbrg->i_kode_group_barang;?>" 
                                <?php if($igroupbrg->i_kode_group_barang==$data->i_kode_group_barang) { ?> selected="selected" <?php } ?>>
                                <?php echo $igroupbrg->e_nama_group_barang;?></option>
                                <?php endforeach; ?> 
                            </select>
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-3">COA</label>
                        <label class="col-md-9">Divisi</label>
                        <div class="col-sm-3">
                            <select name="icoa" id="icoa" class="form-control select2">
                                <option value="<?=$data->i_coa;?>"><?=$data->e_coa_name;?></option>
                            </select>  
                        </div>
                        <div class="col-sm-6">
                            <select name="idivisi" id="idivisi" class="form-control select2">
                                <option value="<?=$data->id_divisi;?>"><?=$data->e_nama_divisi;?></option>
                            </select>  
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Update</button>&nbsp;&nbsp;&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>
                    </div> 
                    <div class="form-group">
                        <span style="color: #8B0000"><b>NOTE :</b></span><br>
                        <span style="color: #8B0000">* Standar Kode Kategori Barang terdiri dari 7 (tujuh) kombinasi huruf dan angka</span><br>
                        <span style="color: #8B0000">* Susunan huruf dapat diambil dari singkatan Nama Kategori Barang</span><br>
                        <span style="color: #8B0000">* Susunan angka dapat dikombinasikan antara angka 0 (nol) dengan nomor urutan terakhir pada Kategori Barang sebelumnya</span><br><br>
                        <span style="color: #8B0000"><b>* Contoh : KTBJ001, KTBW002, KTBI003, dst</span>
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
        $( "#ikelompok" ).focus();
        $(".select2").select2();

        $('#icoa').select2({
            placeholder: 'Pilih COA',
            allowClear: true,
            ajax: {
            url: '<?= base_url($folder.'/cform/coa'); ?>',
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

        $('#idivisi').select2({
            placeholder: 'Pilih Divisi',
            allowClear: true,
            ajax: {
            url: '<?= base_url($folder.'/cform/divisi'); ?>',
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

    $( "#ikelompok" ).keyup(function() {
        var kode = $('#ikelompok').val();
        var kodeold = $('#ikelompokold').val();
        $.ajax({
            type: "post",
            data: {
                'kode' : kode,
            },
            url: '<?= base_url($folder.'/cform/cekkode'); ?>',
            dataType: "json",
            success: function (data) {
                if (data==1 && kodeold!=kode) {
                    $("#cek").attr("hidden", false);
                    $("#submit").attr("disabled", true);
                }else{
                    $("#cek").attr("hidden", true);
                    $("#submit").attr("disabled", false);
                }
            },
            error: function () {
                swal('Error :)');
            }
        });
    });

    $("form").submit(function (event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
    });
</script>
