<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?>
                <?php  if(check_role($this->i_menu, 1)){ ?><a href="#" onclick="show('<?= $folder; ?>/cform/tambah/<?= $dfrom."/".$dto;?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i
                    class="fa fa-plus"></i> &nbsp;<?= $title; ?></a>
                <?php } ?>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/index'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Partner</label>
                        <label class="col-md-2">Date From</label> 
                        <label class="col-md-7">Date To</label> 
                        <div class="col-sm-3">
                            <select name="ipartner" id="ipartner" class="form-control select2">
                                <?php 
                                    if($ipartner == 'ALL'){?>
                                        <option value="<?=$idpartner.'-'.$epartnertype;?>" selected="true">Semua Partner</option>
                                    <?}else{?>
                                        <option value="<?=$idpartner.'-'.$epartnertype;?>" selected="true"><?=$epartner;?></option>
                                    <?}
                                ?>
                            </select>
                            <input type="hidden" id= "idpartner" name="idpartner" class="form-control" readonly value="<?=$idpartner;?>">
                            <input type="hidden" id= "epartnertype" name="epartnertype" class="form-control" readonly value="<?=$epartnertype;?>">
                            <input type="hidden" id= "epartner" name="epartner" class="form-control" readonly value="<?=$epartner;?>">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="dfrom" name="dfrom" class="form-control date" readonly value="<?=$dfrom;?>">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id= "dto" name="dto" class="form-control date" readonly value="<?=$dto;?>">
                        </div>                        
                        <div class="col-sm-2">
                            <button type="submit" id="submit" class="btn btn-info btn-sm"> <i class="fa fa-search"></i>&nbsp;&nbsp;Cari</button>
                        </div>
                    </div>
                </div>
                </form>
                <table class="display nowrap" cellspacing="0" width="100%" id="tabledata">
                    <thead>
                        <tr>
                            <th width="2%">No</th>
                            <th>Nomor Dokumen</th>
                            <th>Tanggal Dokumen</th>
                            <th>Partner</th>
                            <th>Total</th>
                            <th>Keterangan</th>
                            <th>Status Dokumen</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function () {
        datatablemod('#tabledata', base_url + '<?= $folder; ?>/Cform/data/<?= $dfrom."/".$dto."/".$ipartner."/".$idpartner."/".$epartnertype."/".$epartner;?>');
        $('.select2').select2();
        showCalendar2('.date');
    });

    $('#ipartner').select2({
        placeholder: 'Pilih Partner',
        allowClear: true,
        ajax: {
            url: '<?= base_url($folder.'/cform/getpartner/'); ?>',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                var query = {
                    q : params.term,
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
    }).change(function(event) {
        var nama = ($("#ipartner option:selected").text());
       var ipartner = $("#ipartner").val();
       var a =ipartner.split("-");
       var idpartner = a[0];
       var epartnertype = a[1];
       $("#idpartner").val(idpartner);
       $("#epartnertype").val(epartnertype);
       $("#epartner").val(nama);
    });
</script>