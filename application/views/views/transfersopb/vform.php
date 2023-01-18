<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-exchange"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/load'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-6">Tanggal Stockopname</label>
                        <div class="col-sm-6">
                            <input type="text" required="" readonly id= "dstockopname" name="dstockopname" class="form-control date" value="">
                        </div>
                    </div>    
                    <div class="form-group row">
                        <label class="col-md-6">Pilih File Yang Akan Ditransfer</label><label class="col-md-6">Nama File</label>
                        <div class="col-sm-6">
                            <select name="selfile" id="selfile" required="" class="form-control" onchange="pilih();">
                                <option value=""></option>
                                <?php if ($file) {                                 
                                    for($i=0;$i<count($file);$i++) { ?>
                                        <option label="<?= $file[$i];?>"><?= $file[$i];?></option>
                                    <?php }; 
                                } ?>
                            </select>
                        </div>                        
                        <div class="col-sm-6">
                            <input type="text" required="" readonly id= "namafile" name="namafile" class="form-control">
                        </div>
                    </div>                        
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-12">
                            <button type="submit" onclick="return konfirm();" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;Lihat Detail
                            </button>&nbsp;&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i
                                class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
<script>
    function pilih(){
        var tgl = $('#tgl').val();
        $('#namafile').val($('#selfile').val());
        if (tgl!='') {
            $('#submit').click();
        }
    }

    $(document).ready(function () {
        showCalendar('.date');
    });

    function konfirm() {
        if ($('#dstockopname').val()=='') {
            swal('Tanggal SO harus Diisi!');
            return false;
        }
    }
</script>