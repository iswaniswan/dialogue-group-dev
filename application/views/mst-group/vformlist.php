<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?>
                <?php  if(check_role($this->i_menu, 1)){ ?><a href="#" onclick="callmodals(); return false;" class="btn btn-info btn-sm pull-right"><i
                        class="fa fa-plus"></i> &nbsp;<?= $title; ?></a>
                <?php } ?>
            </div>
            <div class="panel-body table-responsive">
                <table id="tabledata" class="display nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Kode Partner Group</th>
                            <th>Nama Partner Group</th> 
                            <th>Level Group</th> 
                            <th>Kategori Partner</th> 
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="modal" id="myModal" role="dialog">
                <div class="modal-dialog">
                  <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title" style="text-align:center;"><b>Pilih Kepala Partner</b></h4>
                        </div>
                        <div class="modal-body">
                            <label class="col-md-6">Kategori Partner</label>
                            <select id="isuppliergroup" name="isuppliergroup" class="form-control select2" style="width:100%;" onchange="getpartner(this.value);"></select>
                        </div>
                        <div class="modal-body">
                            <label class="col-md-6">Kepala Partner</label>
                            <select id="isupplier" name="isupplier" class="form-control select2" style="width:100%;"></select>
                            <input type="hidden" id="ilevel" name="ilevel" value="PLV00">
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="prosessupplier" class="btn btn-info btn-sm" data-dismiss="modal">Proses</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        datatable('#tabledata', base_url + '<?= $folder; ?>/Cform/data');

        $(".select2").select2();

        $("#isuppliergroup").select2({
            placeholder : "Pilih Kategori Partner",
        });

        $("#isupplier").select2({
            placeholder : "Pilih Kepala Partner",
        });
    });

    function callmodals(){
        $('#myModal').modal('show');
        $.ajax({
            type : "POST",
            url  : "<?php echo site_url($folder.'/Cform/getkategoripartner');?>",
            data : {
            },
            dataType : 'json',
            success  : function(data){
                $('#isuppliergroup').html(data.kop);
                if(data.kosong == 'kopong'){
                    $('#submit').attr("disabled", true);
                }else{
                    $('#submit').attr("disabled", false);
                }
            }
        });
    }

    function getpartner(id){
        $.ajax({
            type : "POST",
            url  : "<?php echo site_url($folder.'/Cform/getpartner');?>",
            data : {
                'id' : id,
            },
            dataType : 'json',
            success  : function(data){
                $('#isupplier').html(data.kop);
                if(data.kosong == 'kopong'){
                    $('#submit').attr("disabled", true);
                }else{
                    $('#submit').attr("disabled", false);
                }
            }
        });
    }

    $('#prosessupplier').click(function(){
        $.ajax({
            type: "post",
            data: {
                'isupplier'     : $("#isupplier").val(),
                'ilevel'        : $("#ilevel").val(),
                'isuppliergroup': $("#isuppliergroup").val()
            },
            url: '<?= base_url($folder.'/cform/simpan'); ?>',
            dataType: "html",
            success: function (data) {
                swal("Kepala Partner Berhasil di Tambahkan");
                $('#main').html(data);
            },
            error: function (data) {
                swal("Maaf", "Data gagal ditambahkan", "error");
            }
        });
    });
</script>