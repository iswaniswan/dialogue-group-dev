<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?=$dfrom;?>/<?=$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/tambah'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div class="col-md-4">
                    <div class="form-group row">
                        <label class="col-md-5">Date From</label>
                        <label class="col-md-5">Date To</label>
                        <div class="col-sm-5">
                            <input type="text" id= "dfrom" name="dfrom" class="form-control input-sm date" required value="<?= date("d-m-Y", strtotime($dfrom1));;?>" readonly placeholder="Date From">
                        </div>
                        <div class="col-sm-5">
                            <input type="text" id= "dto" name="dto" class="form-control input-sm date" required value="<?= date("d-m-Y", strtotime($dto1));;?>" readonly onchange="getgudang();" placeholder="Date To">
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
            </form>
            <table id="tabledata" class="display nowrap" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th width="3%;">No</th>
                        <th>Gudang</th>
                        <th>No Referensi</th> 
                        <th>Tanggal Referensi</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <input type="hidden" name="jml" id="jml" value="0"> 
        </div>
    </div>
</div>
</div>
<script>
    $(document).ready(function () {
        datatablemod('#tabledata', base_url + '<?= $folder; ?>/Cform/data_pp/<?= $dfrom.'/'.$dto;?>');
        showCalendar('.date');
        $(".select2").select2();
        $('#isupplier').select2({
            placeholder : "Pilih Supplier",
        });
    });

    function gettmp(id){
        $("#supptmp").val(id);
    }

    function callswal(ipp,ibagian){
        $('#myModal').modal('show');
        $('#ipp').val(ipp);
        $('#ibagian').val(ibagian);
        $.ajax({
            type : "POST",
            url  : "<?php echo site_url($folder.'/Cform/getsup');?>",
            data : {
                'ipp' : ipp,
                'ibagian' : ibagian,
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
        if($("#supptmp").val() != ''){
            $.ajax({
                type: "post",
                data: {
                    'isupplier'  : $("#supptmp").val(),
                    'ibagian'    : $("#ibagian").val(),
                    'ipp'        : $("#ipp").val(),
                    'dfrom'      : $("#dfrom").val(),
                    'dto'        : $("#dto").val()
                },
                url: '<?= base_url($folder.'/cform/proses1'); ?>',
                dataType: "html",
                success: function (data) {
                    $('#main').html(data);
                },
                error: function (data) {
                    swal("Maaf", "Data kosong", "error");
                }
            });
        }else{
            $.ajax({
                success: function (data) {
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
//         url: "<?php echo site_url($folder.'/Cform/getsup');?>",
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
//                             url: '<?= base_url($folder.'/cform/proses1'); ?>',
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

$("form").submit(function (event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
});

function getgudang(){
    $("#igudang").attr("disabled", false);
}

$(document).ready(function () {
    $('#igudang').select2({
        placeholder: 'Pilih Gudang',
        allowClear: true,
        ajax: {
          url: '<?= base_url($folder.'/cform/gudang'); ?>',
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
});

function getpp(dfrom) {
    $("#ipp").attr("disabled", false);
    var dfrom = $('#dfrom').val();
    var dto = $('#dto').val();
    var igudang = $('#igudang').val();
    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/getpp');?>",
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
}
</script>
