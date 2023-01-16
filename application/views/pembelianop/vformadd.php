<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom; ?>/<?= $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/tambah'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div class="col-md-4">
                    <div class="form-group row">
                        <label class="col-md-5">Date From</label>
                        <label class="col-md-5">Date To</label>
                        <div class="col-sm-5">
                            <input type="text" id="dfrom" name="dfrom" class="form-control input-sm date" required value="<?= date("d-m-Y", strtotime($dfrom1));; ?>" readonly placeholder="Date From">
                        </div>
                        <div class="col-sm-5">
                            <input type="text" id="dto" name="dto" class="form-control input-sm date" required value="<?= date("d-m-Y", strtotime($dto1));; ?>" readonly placeholder="Date To">
                        </div>
                        <div class="col-sm-2">
                            <button type="submit" id="submit" class="btn btn-info btn-sm"> <i class="fa fa-search"></i>&nbsp;&nbsp;Cari</button>
                        </div>
                    </div>
                    <input type="hidden" id="ipp" name="ipp" value="">
                    <input type="hidden" id="ibagian" name="ibagian" value="">
                    <input type="hidden" id="supptmp" value="">
                </div>

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
                <!-- End Modal -->
                </form>
                <table id="tabledata" class="display nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th width="3%;">No</th>
                            <th width="15%;">Gudang</th>
                            <th width="15%;">No Referensi</th>
                            <th width="10%;">Tgl. Referensi</th>
                            <th width="40%;">Barang</th>
                            <th width="10%;">Jumlah PP</th>
                            <th width="7%;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <div class="form-group row">
                    <div class="col-sm-offset-5 col-sm-12" style="text-align: center;">
                        <button type="button" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="check();"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Proses</button>
                        &nbsp;&nbsp;
                        <label class="custom-control custom-checkbox">
                            <input type="checkbox" id="checkAll" name="checkAll" class="custom-control-input">
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">Check All</span>
                        </label>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <span class="notekode"><b>Note : </b></span><br>
                            <span class="notekode">* Jika barang hilang setelah memproses PP -> Harap mengisi master harga barang untuk supplier yang di pilih.</span><br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        datatablemod('#tabledata', base_url + '<?= $folder; ?>/Cform/data_pp/<?= $dfrom . '/' . $dto; ?>');

        /*var table = $('#tabledata').DataTable({
          'ajax': '<?= $folder; ?>/Cform/data_pp/<?= $dfrom . '/' . $dto; ?>',
          'columnDefs': [
             {
                'targets': 6,
                'checkboxes': {
                   'selectRow': true
                }
             }
          ],
          'select': {
             'style': 'multi'
          }
        });
        table.on('draw.dt', function () {
            var info = table.page.info();
            table.column(0, { search: 'applied', order: 'applied', page: 'applied' }).nodes().each(function (cell, i) {
                cell.innerHTML = i + 1 + info.start;
            });
        });*/


        showCalendar('.date');
        $(".select2").select2();
        $('#isupplier').select2({
            placeholder: "Pilih Supplier",
        });
    });

    function onlyUnique(value, index, self) {
        return self.indexOf(value) === index;
    }

    $("#checkAll").click(function() {
        $('input:checkbox').not(this).prop('checked', this.checked);
    });

    function check() {
        var jml = $("#jml").val();
        var idmaterial = [];
        for (var x = 1; x <= jml; x++) {
            if ($('#chk' + x).is(':checked')) {
                idmaterial.push($('#id_material' + x).val());
            }
        }
        idmaterial = idmaterial.filter(onlyUnique);
        //console.log(idmaterial);
        callswal(idmaterial);
        //var jml = $("#jml").val();
    }

    function gettmp(id) {
        $("#supptmp").val(id);
    }

    function callswal(idmaterial) {
        $('#myModal').modal('show');
        // $('#ipp').val(ipp);
        // $('#ibagian').val(ibagian);
        $.ajax({
            type: "POST",
            url: "<?php echo site_url($folder . '/Cform/getsup'); ?>",
            data: {
                'idmaterial': idmaterial,
            },
            dataType: 'json',
            success: function(data) {
                $('#isupplier').html(data.kop);
                if (data.kosong == 'kopong') {
                    $('#submit').attr("disabled", true);
                } else {
                    $('#submit').attr("disabled", false);
                }
            }
        });
    }

    $('#prosessupplier').click(function() {
        if ($("#supptmp").val() != '') {
            var jml = $("#jml").val();
            var id_pp_item = []
            for (var x = 1; x <= jml; x++) {
                if ($('#chk' + x).is(':checked')) {
                    id_pp_item.push($('#id_pp_item' + x).val());
                }
            }
            id_pp_item = id_pp_item.filter(onlyUnique);
            $.ajax({
                type: "post",
                data: {
                    'isupplier': $("#supptmp").val(),
                    'id_pp_item': id_pp_item,
                    'dfrom': $("#dfrom").val(),
                    'dto': $("#dto").val()
                },
                url: '<?= base_url($folder . '/cform/proses1'); ?>',
                dataType: "html",
                success: function(data) {
                    $('#main').html(data);
                },
                error: function(data) {
                    swal("Maaf", "Data kosong", "error");
                }
            });
        } else {
            $.ajax({
                success: function(data) {
                    swal("Maaf", "Data kosong, Supplier Tidak Terdaftar dalam Sistem", "error");
                }
            });
        }
    });

    // function callswal(ipp, ibagian){
    //     $('#ipp').val(ipp);
    //     $('#ibagian').val(ibagian);
    //     $.ajax({
    //         type: "POST",
    //         url: "<?php echo site_url($folder . '/Cform/getsup'); ?>",
    //         data:{
    //             'ipp': ipp,
    //             'ibagian' : ibagian,
    //         },
    //         dataType: 'json',
    //         success: function(data){
    //             $("#isupplier").html(data.kop);
    //             if (data.kosong=='kopong') {
    //                 $("#submit").attr("disabled", true);
    //             }else{
    //                 $("#submit").attr("disabled", false);
    //                 swal({ 
    //                     html:$('#content').html(), 
    //                     title:'Pilih Supplier', 
    //                     showCancelButton: true,
    //                     confirmButtonText: 'Proses',
    //                     showLoaderOnConfirm: true,
    //                 }).then(function(isupplier) {
    //                     if($("#supptmp").val() != ''){
    //                         $.ajax({
    //                             type: "post",
    //                             data: {
    //                                 'isupplier'  : $("#supptmp").val(),
    //                                 'ibagian'    : $("#ibagian").val(),
    //                                 'ipp'        : $("#ipp").val(),
    //                                 'dfrom'      : $("#dfrom").val(),
    //                                 'dto'        : $("#dto").val()
    //                             },
    //                             url: '<?= base_url($folder . '/cform/proses1'); ?>',
    //                             dataType: "html",
    //                             success: function (data) {
    //                                 $('#main').html(data);
    //                                 $('#content').attr("hidden",true);
    //                             },
    //                             error: function (data) {
    //                                 swal("Maaf", "Data kosong", "error");
    //                             }
    //                         });
    //                     }else{
    //                         $.ajax({
    //                             success: function (data) {
    //                                 swal("Maaf", "Data kosong, Supplier Tidak Terdaftar dalam Sistem", "error");
    //                             }
    //                         });
    //                     }
    //                 });
    //             }
    //         },
    //         error:function(XMLHttpRequest){
    //             alert(XMLHttpRequest.responseText);
    //         }
    //     });      
    // }

    /*$("form").submit(function (event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
    });*/

    /*function getgudang(){
        $("#igudang").attr("disabled", false);
    }

    $(document).ready(function () {
        $('#igudang').select2({
            placeholder: 'Pilih Gudang',
            allowClear: true,
            ajax: {
              url: '<?= base_url($folder . '/cform/gudang'); ?>',
              dataType: 'json',
              delay: 250,          
              processResults: function (data) {
                return {
                  results: data
              };
          },
          cache: true
      }
    })
    });*/

    /*function getpp(dfrom) {
        $("#ipp").attr("disabled", false);
        var dfrom = $('#dfrom').val();
        var dto = $('#dto').val();
        var igudang = $('#igudang').val();
        $.ajax({
            type: "POST",
            url: "<?php echo site_url($folder . '/Cform/getpp'); ?>",
            data:{
                'dfrom': dfrom,
                'dto':dto,
                'igudang':igudang,
            },
            dataType: 'json',
            success: function(data){
                $("#ipp").html(data.kop);
                if (data.kosong=='kopong') {
                    $("#submit").attr("disabled", true);
                }else{
                    $("#submit").attr("disabled", false);
                }
            },

            error:function(XMLHttpRequest){
                alert(XMLHttpRequest.responseText);
            }

        });
    }*/
</script>