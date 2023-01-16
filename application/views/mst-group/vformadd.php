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
                <!-- <div class="col-md-6">
                    <div class="form-group row">
                    <label class="col-md-6">Kategori Partner Group</label><label class="col-md-6">Nama Partner Group</label>
                        <div class="col-sm-6">
                            <select name="isuppliergroup" id ="isuppliergroup" class="form-control select2" onchange="getpartner(this.value);">
                             <option value="">-- Pilih Kategori Partner --</option>
                                <?php foreach ($kategori as $r):?>
                                <option value="<?php echo $r->i_supplier_group;?>"><?php echo $r->i_supplier_group." - ".$r->e_supplier_groupname;?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <select name="epartner" id ="epartner" class="form-control select2" disabled onchange="getdetailpartner();">
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-8 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-6">Kode Partner Group</label><label class="col-md-6">Level Group</label>
                        <div class="col-sm-6">
                            <input type="text" name="ipartner"  id="ipartner" class="form-control" value="" readonly>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" name="elevelgroup" id="elevelgroup" class="form-control" maxlength="100"  value="Pusat" readonly>
                            <input type="hidden" name="ilevelgroup" id="ilevelgroup" class="form-control" maxlength="100"  value="PLV1001" readonly>
                        </div>
                    </div> 
                </div> -->

                <!-- Modal -->
                <div class="modal" id="myModal" role="dialog">
                    <div class="modal-dialog">
                      <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title" style="text-align:center;"><b>Pilih Supplier</b></h4>
                            </div>
                            <div class="modal-body">
                                <select id="isupplier" name="isupplier" class="form-control select2" style="width:100%;" onchange="gettmp(this.value);"></select>
                            </div>
                            <div class="modal-footer">
                                <button type="button" id="prosessupplier" class="btn btn-info btn-sm" data-dismiss="modal">Proses</button>
                            </div>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    $(".select2").select2();
    $('#myModal').modal('show');
});

function getpartner(id){
    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/getpartner');?>",
        data: "isuppliergroup=" + id,
        dataType: 'json',
        success: function (data) {
            $("#epartner").html(data.kop);
            if (data.kosong == 'kopong') {
                $("#submit").attr("disabled", true);
            } else {
                $("#submit").attr("disabled", false);
                $("#epartner").attr("disabled", false);
            }
        },

        error: function (XMLHttpRequest) {
            alert(XMLHttpRequest.responseText);
        }
    });
}

function getdetailpartner(){
    var ipusat = $('#epartner').val();
    $.ajax({
        type: "post",
        data: {
            'ipusat': ipusat
        },
        url: '<?= base_url($folder.'/cform/getdetailpartner'); ?>',
        dataType: "json",
        success: function (data) {
            $('#ipartner').val(data[0].i_kepala_pusat);
        },
        error: function () {
            alert('Error :)');
        }
    });
}

$("form").submit(function (event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
})
</script>
